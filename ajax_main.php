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
	*	@file ajax_main.php
	*	
	*	@author Michal Sudwoj <mswoj61@gmail.com>
	*	@copyright Michal Sudwoj
	*	@link http://www.sourceforge.com/projects/schades/
	*	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
	*	@version 0.8
	*	
	*	@brief AJAX Main page
	*	
	*	@details Returns subtoolbars.
	*/
	
	require_once( 'class/Printer.php' ) ;
	require_once( 'class/Period.php' ) ;
	//require_once( 'settings/settings.php' ) ;
	
	session_start() ;
	
	if( isset( $_SESSION[ 'person' ] ) ) {
		$ad = $_SESSION[ 'person' ] ;
	} else {
		header( 'Location: logout.php?type=error' ) ;
	}
	
	$tab = $_GET['tab'] ;
	$line = '' ;
	
	switch( $tab ) {
		case 0 :
			$line .= '<div>Do you want to recieve email notifications?<br />' ;
			$line .= '<input type="radio" name="qemail" id="qemail-true" onchange="" /><label for="qemail-true">Yes</label><br />' ;
			$line .= '<input type="radio" name="qemail" id="qemail-false" onchange="" /><label for="qemail-false">No</label></div>' ;
			break ;
		case 1 :
			$line .= '<div id="form" >' ;
			$line .= Printer::form( $ad ) ;
			$line .= '</div>' ;
			$line .= '<div class="loading" id="loading" ></div><div id="table" ></div>' ;
			break ;
		case 2 :
			$line .= '<div id="subtoolbar" ><table class="toolbar" ><thead></thead><tbody><tr><td class="toolcell" >Queue</td><!--<td class="toolcell" >Compose</td><td class="toolcell" >Inbox</td><td class="toolcell" >Sent</td></tbody></table></div><div id="messages" >--></div>' ;
			break ;
	}
	echo Printer::tidy( $line ) ;
?>