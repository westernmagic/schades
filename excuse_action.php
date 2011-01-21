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
	*	@file excuse_action.php
	*	
	*	@author Michal Sudwoj <mswoj61@gmail.com>
	*	@copyright Michal Sudwoj
	*	@link http://www.sourceforge.com/projects/schades/
	*	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
	*	@version 0.8
	*	
	*	@brief Excuse handling script.
	*	
	*	@details Adds and deletes excuses.
	*/
	
	require_once( 'include.php' ) ;
	
	session_start() ;
	if( isset( $_SESSION[ 'person' ] ) ) {
		if( isset( $_GET[ 'pr' ] ) ) {
			$pr = new Period( $_GET[ 'pr' ] ) ;
		} else {
			$pr = new Period( abs_pr ) ;
		}
		$person = $_SESSION[ 'person' ] ;
		
		if( $person->getType() != 8 ) {
			if( $_GET[ 'action' ] == 'add' ) {
				$excuse = Excuse::newExcuse( new Person( $_GET[ 'student' ] , $pr ) , (int) $_GET[ 'from_week' ] , (int) $_GET[ 'from_day' ] , (int) $_GET[ 'from_lesson' ] , (int) $_GET[ 'to_week' ] , (int) $_GET[ 'to_day' ] , (int) $_GET[ 'to_lesson' ] , (int) $_GET[ 'value' ] , $_GET[ 'reason' ] , $person , (int) $_GET[ 'excuseid' ] ) ;
				if( $excuse ) {
					echo 0 ;
				} else {
					echo 1 ;
				}
			} elseif( $_GET[ 'action' ] == 'delete' ) {
				$query = 'DELETE FROM ' . DB::name( 'abs_excuse' ) ;
				$query .= ' WHERE ' . DB::name( 'abs_excuse' , 'excuse_id' ) . ' = ' . DB::excuse( $_GET[ 'excuseid' ] ) . ' ;' ;
				if( mysql_query( $query , my_link ) ) {
					echo 0 ;
				} else {
					echo 1 ;
				}
			} else {
				echo 1 ;
			}
		}
	}
?>