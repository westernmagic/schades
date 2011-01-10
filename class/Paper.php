<?php
	/*	
	This file is part of schades.

    schades is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    schades is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with schades.  If not, see <http://www.gnu.org/licenses/>.
	*/
	/**
	*	@file Paper.php
	*	
	*	@author Michal Sudwoj <mswoj61@gmail.com>
	*	@copyright Michal Sudwoj
	*	@link http://www.sourceforge.com/projects/schades/
	*	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
	*	@version 0.8
	*/
	
	require_once( 'include.php' ) ;
	require_once( 'lib/tcpdf/config/lang/eng.php' ) ;
	require_once( 'lib/tcpdf/tcpdf.php' ) ;
	
	/**
	*	@brief Paper class
	*/
	class Paper extends SuperClass , TCPDF {
	
		public function __construct( $type , Person $from , Person $to , $arg = NULL ) {
			parent::__construct( 'P' , 'mm' , 'A4' , true , 'UTF-8', false ) ; 
			
			$this->SetCreator( 'TCPDF' ) ;
			$this->SetAuthor( $from->getFirst() . ' ' . $from->getSurname() ) ;
			//$this->SetTitle( '' ) ;
			//$this->SetSubject( '' ) ;
			//$this->SetKeywords( '' ) ;
			//$this->SetHeaderData( '../../../logo.jpg' , 180 ) ;
			//$this->setHeaderFont( Array( PDF_FONT_NAME_MAIN , '' , PDF_FONT_SIZE_MAIN ) ) ;
			//$this->setFooterFont( Array( PDF_FONT_NAME_DATA , '' , PDF_FONT_SIZE_DATA ) ) ;
			//$this->SetDefaultMonospacedFont( PDF_FONT_MONOSPACED );
			$this->SetMargins( 15 , 30 , 15 ) ;
			$this->SetHeaderMargin( 5 ) ;
			$this->SetFooterMargin( 10 ) ;
			$this->SetAutoPageBreak( TRUE , 25 ) ;
			$this->setImageScale( 1 ) ;
			//$this->setLanguageArray( $l ) ; 
			$this->SetFont( 'freesans' , '' , 10 ) ;
			
			$this->AddPage() ;
			$this->typewriter( $type , $from , $to , $arg ) ;
		}
		
		public function Header() {
			$this->Image( '../img/logo.jpg' , 10 , 8 , 170 , 0 , '' , '' , 'N' , false , 300 , 'C' ) ;
			$this->in( '<span>' . Date::now()->getNorm() . '</span><br />' ) ;
		}
		
		public function Footer() {
			$this->SetY( -15 ) ;
			$this->MultiCell( 0 , 10 , "Kantonsschule Rychenberg, Rychenbergstrasse 110, CH-8400 Winterthur, www.ksrychenberg.ch\nTelefon +41 (0)52 244 04 04, Fax (0)52 244 04 00, sekretariat@krw.bid.zh.ch" , 0 , 'C' ) ;
		}
		
		public function in( $html ) {
			$this->writeHTML( Printer::tidy( $html ) , false );
		}
		
		public function out( $type = 'I' , $name = '' ) {
			$this->lastPage() ;
			$this->Output( $name , $type );
		}
		
		private function typewriter( $type , Person $from , Person $to , $arg = NULL ) {
			$this->in( '<span style="text-align: right ;" >' . $from->getUsername() . '&rarr;' . $to->getUsername() . '</span><br /><br />' ) ;
			$html = '' ;
			
			switch( $type ) {
				case 101:
					if( is_string( $arg ) ) {
						$text = $arg ;
					} else {
						$text = '' ;
					}
					$html = '<span>' . $text . '</span>' ;
					break ;
				case 411:
					$html = '<h2>Absenzenkontrolle: Gespr&auml;ch nach dem Erreichen von ' . $to->getLower() ;
					$html .= ' Einheiten</h2><br /><br /><br /><br />' ;
					$html .= '<span>Folgende Sch&uuml;lerin / folgender Sch&uuml;ler hat seit Semesterbeginn ' . $to>getLower() ;
					$html .= ' oder mehr Absenzeneinheiten erhalten.</span><br /><br />' ;
					$html .= '<span>Name der Sch&uuml;lerin / des Sch&uuml;lers: </span><span style="text-align: right ; font-size: 1.5em ;" >' ;
					$html .= $to->getSurname() . ', ' . $to->getFirst() . '</span><br /><br />' ;
					$html .= '<span>Klasse: </span><span style="font-size: 1.5em ;" >' . $to->getForms( 0 )->getId() . '</span><br /><br />' ;
					$html .= '<span>Gem&auml;ss Merkblatt f&uuml;hrt die Klassenlehrkraft mit der Sch&uuml;lerin/dem Sch&uuml;ler ' ;
					$html .= 'ein Gespr&auml;ch und weist sie/ihn darauf hin, dass beim Erreichen von ' ;
					$html .= $to->getUpper() . ' Absenzeneinheiten die Versetzung in die ' ;
					if( $to->getLvl() == 3 ) {
						$html .= 'Disziplinarstufe' ;
					} else {
						$html .= 'Stufe 2' ;
					}
					$html .= ' droht. Mit den untenstehenden Unterschriften wird best&auml;tigt, dass das Gespr&auml;ch stattgefunden hat.' ;
					$html .= '</span><br /><br />' ;
					$html .= '<span>Datum des Gespr&auml;chs:</span><br /><br />' ;
					$html .= '<span>Unterschrift der Sch&uuml;lerin / des Sch&uuml;lers:</span><br /><br />' ;
					$html .= '<span>Unterschrift der Klassenlehrerin / des Klassenlehrers:</span><br /><br />' ;
					$html .= '<span style="font-weight: bold ;" >Unterschriebenes Formular bitte zur&uuml;ck an ' ;
					$html .= $from->getUsername() . '</span>' ;
					break ;
				case 416:
					$html = '<h2>Absenzenkontrolle: Gespr&auml;ch nach dem Erreichen von ' . $to->getUpper() ;
					$html .= ' Einheiten</h2><br /><br /><br /><br />' ;
					$html .= '<span>Folgende Sch&uuml;lerin / folgender Sch&uuml;ler hat ' ;
					if( $to->getPr()->getId() < Date::now()->getAbs_pr() ) {
						$html .= 'im letzten Semester' ;
					} else {
						$html .= 'seit Semesterbeginn' ;
					}
					$html .= ' ' . $to->getUpper() . ' oder mehr Absenzeneinheiten erhalten.</span><br /><br />' ;
					$html .= '<span>Name der Sch&uuml;lerin / des Sch&uuml;lers: </span><span style="text-align: right ; font-size: 1.5em ;" >' ;
					$html .= $to->getSurname() . ', ' . $to->getFirst() . '</span><br /><br />' ;
					$html .= '<span>Klasse: </span><span style="font-size: 1.5em ;" >' . $to->getForms( 0 )->getId() . '</span><br /><br />' ;
					$html .= '<span>Datum und Zeit des Gesprächs: </span><span style="font-size: 1.5em ;" >' . $arg->norm ;
					$html .= '</span><br /><br />' ;
					$html .= '<span>Gem&auml;ss Merkblatt gilt:</span><br />' ;
					$html .= '<span><em>Wenn ein Sch&uuml;ler innerhalb eines Semester insgesamt 16 Absenzeneinheiten ' ;
					$html .= '(verk&uuml;rztes Semester: 14 Absenzeneinheiten) erreicht hat, f&uuml;hrt der f&uuml;r die Klasse ' ;
					$html .= 'zust&auml;ndige Jahrgangsbetreuer der Schulleitung mit jenem ein Gespr&auml;ch, ' ;
					$html .= 'zu welchem die Klassenlehrkraft beigezogen werden kann. In diesem Gespr&auml;ch er&ouml;rtert der Sch&uuml;ler ' ;
					$html .= 'nochmals die Begr&uuml;ndungen f&uuml;r seine Absenzen. In zwingenden F&auml;llen ' ;
					$html .= '(z. B. bei chronischer Erkrankung, Unfall, Spitalaufenthalt) kann der Jahrgangsbetreuer ' ;
					$html .= 'die Zahl der Absenzeneinheiten herabsetzen. Sonst versetzt er den Sch&uuml;ler in die Absenzenstufe 2. ' ;
					$html .= 'Gleichzeitig orientiert er ihn &uuml;ber das ver&auml;nderte Abmeldeverfahren in der Stufe 2 und ' ;
					$html .= 'die Folgen von Verst&ouml;ssen.</em></span><br /><br />' ;
					$html .= '<span>Ich bitte Sie, zum oben genannten Zeitpunkt bei mir im B&uuml;ro zu erscheinen. ' ;
					$html .= 'Bitte bringen Sie auch das Absenzenb&uuml;chlein und allf&auml;llige Arztzeugnisse mit.</span><br /><br />' ;
					$html .= '<span>Freundliche Gr&uuml;sse</span><br />' ;
					$html .= '<span>' . $from->getFirst() . ' ' . $from->getSurname() . '</span>' ;
					break ;
				case 404:
					$html = '<h2>Arztzeugnis bei l&auml;ngerer Krankheit</h2><br /><br /><br /><br />' ;
					$html .= '<span>Name der Sch&uuml;lerin / des Sch&uuml;lers: </span>' ;
					$html .= '<span style="font-size: 1.5em ;" >' . $to->getSurname() . ', ' . $to->getFirst() . '</span><br /><br />' ;
					$html .= '<span>Klasse: </span><span style="font-size: 1.5em ;" >' . $to->getForms( 0 )->getId() . '</span><br /><br />' ;
					$html .= '<span>Gem&auml;ss meinen Unterlagen haben Sie &uuml;ber eine l&auml;ngere Zeit an der Schule gefehlt. ' ;
					$html .= 'Bitte lassen Sie mir &uuml;ber das Sekretariat eine Kopie des betreffenden Arztzeugnisses zukommen.</span><br />' ;
					$html .= '<span>Woche: </span><span style="font-size: 1.5em ;" >' . $arg . '</span><br /><br />' ;
					$html .= '<span>Freundliche Gr&uuml;sse</span><br />' ;
					$html .= '<span>' . $from->getFirst() . ' ' . $from->getSurname() . '</span>' ;
					break ;
				default:
					break ;
			}
			
			$this->in( $html ) ;
			$this->out() ;
		}
	}
?>