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
	*	@file ajax_schedule.php
	*	
	*	@author Michal Sudwoj <mswoj61@gmail.com>
	*	@copyright Michal Sudwoj
	*	@link http://www.sourceforge.com/projects/schades/
	*	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
	*	@version 0.8
	*	
	*	@brief AJAX Schdule page
	*	
	*	@details Returns the schedule page.
	*/
	
	require_once( 'include.php' ) ;
	
	if( isset( $_GET[ 'abs_pr' ] ) ) {
		$pr = new Period( $_GET[ 'abs_pr' ] ) ;
	} else {
		$pr = abs_pr ;
	}
	$week = $_GET[ 'week' ] ;
	$person = new Person( $_GET[ 'ad_id' ] , $pr ) ;
	
	$lessons = $person->getLessons() ;
	foreach( $lessons as $k => &$l ) {
		$l = $l->getId() ;//. ' ' . $l->getTeacher()->getTeacher() ;
	}
	/*for( $d = 1 ; $d <= 5 ; $d++ ) {
		for( $d = 1 ; $d <= 12 ; $d++ ) {
			if( !isset( $lessons[ 12 * $d + $l ] ) ) {
				$lessons[ 12 * $d + $l ] = '' ;
			}
		}
	}*/

	$line = '<table border="1" style="border-collapse: collapse; " ><thead><tr>' ;
	$line .= '<th>Lesson</th><th>From</th><th>To</th><th>Monday</th>' ;
	$line .= '<th>Tuesday</th><th>Wednesday</th><th>Thursday</th>' ;
	$line .= '<th>Friday</th></tr></thead><tbody>' ;
	$line .= '<tr><th>1</th><th>07:45</th><th>08:25</th>' ;
	for( $d = 1 ; $d <= 5 ; $d++ ) {
		$line .= '<td>' . $lessons[ 5 * 12 * $week + 12 * $d + 1 ] . '</td>' ;
	}
	$line .= '</tr>' ;
	
	$line .= '<tr><th>2</th><th>08:35</th><th>09:15</th>' ;
	for( $d = 1 ; $d <= 5 ; $d++ ) {
		$line .= '<td>' . $lessons[ 5 * 12 * $week + 12 * $d + 2 ] . '</td>' ;
	}
	$line .= '</tr>' ;
	
	$line .= '<tr><th>3</th><th>09:25</th><th>10:05</th>' ;
	for( $d = 1 ; $d <= 5 ; $d++ ) {
		$line .= '<td>' . $lessons[ 5 * 12 * $week + 12 * $d + 3 ] . '</td>' ;
	}
	$line .= '</tr>' ;
	
	$line .= '<tr><th>4</th><th>10:20</th><th>11:00</th>' ;
	for( $d = 1 ; $d <= 5 ; $d++ ) {
		$line .= '<td>' . $lessons[ 5 * 12 * $week + 12 * $d + 4 ] . '</td>' ;
	}
	
	$line .= '</tr>' ;
	$line .= '<tr><th>5</th><th>11:10</th><th>11:50</th>' ;
	for( $d = 1 ; $d <= 5 ; $d++ ) {
		$line .= '<td>' . $lessons[ 5 * 12 * $week + 12 * $d + 5 ] . '</td>' ;
	}
	
	$line .= '</tr>' ;
	$line .= '<tr><th>6</th><th>12:00</th><th>12:40</th>' ;
	for( $d = 1 ; $d <= 5 ; $d++ ) {
		$line .= '<td>' . $lessons[ 5 * 12 * $week + 12 * $d + 6 ] . '</td>' ;
	}
	
	$line .= '</tr>' ;
	$line .= '<tr><th>7</th><th>12:45</th><th>13:25</th>' ;
	for( $d = 1 ; $d <= 5 ; $d++ ) {
		$line .= '<td>' . $lessons[ 5 * 12 * $week + 12 * $d + 7 ] . '</td>' ;
	}
	
	$line .= '</tr>' ;
	$line .= '<tr><th>8</th><th>13:35</th><th>14:15</th>' ;
	for( $d = 1 ; $d <= 5 ; $d++ ) {
		$line .= '<td>' . $lessons[ 5 * 12 * $week + 12 * $d + 8 ] . '</td>' ;
	}
	$line .= '</tr>' ;
	
	$line .= '<tr><th>9</th><th>14:25</th><th>15:05</th>' ;
	for( $d = 1 ; $d <= 5 ; $d++ ) {
		$line .= '<td>' . $lessons[ 5 * 12 * $week + 12 * $d + 9 ] . '</td>' ;
	}
	$line .= '</tr>' ;
	
	$line .= '<tr><th>10</th><th>15:15</th><th>15:55</th>' ;
	for( $d = 1 ; $d <= 5 ; $d++ ) {
		$line .= '<td>' . $lessons[ 5 * 12 * $week + 12 * $d + 10 ] . '</td>' ;
	}
	$line .= '</tr>' ;
	
	$line .= '<tr><th>11</th><th>16:05</th><th>16:45</th>' ;
	for( $d = 1 ; $d <= 5 ; $d++ ) {
		$line .= '<td>' . $lessons[ 5 * 12 * $week + 12 * $d + 11 ] . '</td>' ;
	}
	$line .= '</tr>' ;
	
	$line .= '<tr><th>12</th><th>16:50</th><th>17:30</th>' ;
	for( $d = 1 ; $d <= 5 ; $d++ ) {
		$line .= '<td>' . $lessons[ 5 * 12 * $week + 12 * $d + 12 ] . '</td>' ;
	}
	$line .= '</tr>' ;
	
	$line .= '</tbody></table>' ;
	
	$line .= '<br /><br /><div><span class="toolcell" onclick="redirect*( \'schedule.php?student=\'>iCal:</span></div>' ;
	
	echo Printer::tidy( $line ) ;
?>
