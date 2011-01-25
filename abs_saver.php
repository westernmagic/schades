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
	*	@file abs_saver.php
	*	
	*	@author Michal Sudwoj <mswoj61@gmail.com>
	*	@copyright Michal Sudwoj
	*	@link http://www.sourceforge.com/projects/schades/
	*	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
	*	@version 0.8
	*	
	*	@brief Absence saving script
	*	
	*	@details Saves all the absences in $_POST .
	*/
	
	require_once( 'include.php' ) ;
	
	session_start() ;
	$exec = $_SESSION['person'] ;
	
	foreach( $_POST as $key => $val ) {
		$parts = explode( ':' , $key ) ;
		if( Person::isstudent( $parts[0] ) ) {
			$abs = Absence::newAbsence( $parts[0] , $parts[1] , $parts[2] , $parts[3] , $val , $exec->getId() ) ;
			if( ! $abs ) {
				throw new ErrorException( 'Absence inserting error.' , 602 , 1 , 'abs_saver.php' , 21 ) ;
			}
			unset( $abs ) ;
		}
		unset( $parts ) ;
		unset( $key ) ;
		unset( $val ) ;
	}
	
	unset( $_POST ) ;
	
?>
