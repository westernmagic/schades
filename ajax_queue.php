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
	*	@file index.php
	*	
	*	@author Michal Sudwoj <mswoj61@gmail.com>
	*	@copyright Michal Sudwoj
	*	@link http://www.sourceforge.com/projects/schades/
	*	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
	*	@version 0.8
	*	
	*	@brief AJAX page
	*	
	*	@details Returns the queue page.
	*	
	*	@todo check whether works.
	*/
	
	require_once( 'include.php' ) ;
	
	session_start() ;
	$person = $_SESSION[ 'person' ] ;
	
	$queue = $person->getQueue() ;
	
	$line = '<div class="loading" ></div><table><thead><tr>' ;
	$line .= '<th>Who</th><th>When</th><th>What</th><th>Action</th>' ;
	$line .= '</tr></thead><tbody>' ;
	
	foreach( $queue as $q ) {
		$line2 = '<tr><td>' . $q->getConcerning()->getSurname() . ', ' . $q->getConcerning()->getFirst() . '</td>' ;
		$line2 .= '<td>Pr' . $q->getPr()->getId() . 'W' . $q->getWeek() . '</td>' ;
		switch ( $q->getType() ) {	// set action spans and do functions
			case 401:
				$line2 .= '<td>promotion</td><td>Yes No</td>';
				break ;
			case 402:
				$line2 .= '<td>promotion</td><td>Aknowledge</td>';
				break ;
			case 404:
				$line2 .= '<td>excuse</td><td></td>';
				break ;
			case 405:
				$line2 .= '<td>excuse</td><td>Recieved</td>';
				break ;
			case 409:
				$line2 .= '<td>lower</td><td>Yes No</td>';
				break ;
			case 410:
				$line2 .= '<td>lower</td><td>Aknowledge</td>';
				break ;
			case 411:
				$line2 .= '<td>lower</td><td>Print</td>';
				break ;
			case 412:
				$line2 .= '<td>lower</td><td>Recieved</td>';
				break ;
			case 415:
				$line2 .= '<td>upper</td><td>Yes No</td>';
				break ;
			case 416:
				$line2 .= '<td>upper</td><td>Print Mail</td>';
				break ;
		}
		$line .= $line2 . '</tr>';
	}
	
	$line .= '</tbody></table>' ;
	
	echo Printer::tidy( $line ) ;
	
?>
