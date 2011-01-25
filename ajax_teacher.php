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
	*	@file ajax_teacher.php
	*	
	*	@author Michal Sudwoj <mswoj61@gmail.com>
	*	@copyright Michal Sudwoj
	*	@link http://www.sourceforge.com/projects/schades/
	*	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
	*	@version 0.8
	*	
	*	@brief AJAX teacher page
	*	
	*	@details Returns the absence tables.
	*/
	
	require_once( 'include.php' ) ;
	
	session_start() ;
	if( isset( $_SESSION[ 'person' ] ) ) {
		if( isset( $_GET[ "abs_pr" ] ) ) {
			$abs_pr = new Period( $_GET["abs_pr"] ) ;
		} else {
			$abs_pr = new Period( abs_pr ) ;
		}
		
		$person = $_SESSION[ 'person' ] ;
		if( $person->getType() == 8 ) {
			$line = Printer::student_table( $person ) . Printer::legend() ;
		} else {
			if( isset( $_GET["copg"] ) ) {
				$parts = explode( ':' , $_GET['copg'] ) ;
				switch( $parts[0] ) {
					case 'f' :
						$copg = new Form( $parts[1] , $abs_pr ) ;
						break ;
					case 'c' :
						$copg = new Course( $parts[1] , $abs_pr ) ;
						break ;
					default:
						$copg = NULL ;
						break ;
				}
			}
			
			if( isset( $_GET["week"] ) ) {
				$week = stripslashes( $_GET["week"] ) ;
			} else {
				$date = Date::now() ;
				$week = $date->w ;
			}
			
			if( isset( $_GET['edit'] ) ) {
				$edit = $_GET['edit'] == 'true' ;
			} else {
				$edit = false ;
			}
			
			$line = Printer::teacher_table( $copg , $week , $edit ) . Printer::legend() ;
		}
		
	} else {
		header( 'Location: login.php' ) ;
	}
	
	unset( $_GET ) ;
	
	
	echo Printer::tidy( $line ) ;
?>
