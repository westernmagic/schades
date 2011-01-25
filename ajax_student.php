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
	*	@file ajax_student.php
	*	
	*	@author Michal Sudwoj <mswoj61@gmail.com>
	*	@copyright Michal Sudwoj
	*	@link http://www.sourceforge.com/projects/schades/
	*	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
	*	@version 0.8
	*	
	*	@brief AJAX student page
	*	
	*	@details Returns the student subtoolbar.
	*/
	
	require_once( 'include.php' ) ;
	
	session_start() ;
	if( isset( $_GET["abs_pr"] ) ) {
		$abs_pr = new Period( $_GET["abs_pr"] ) ;
	} else {
		$abs_pr = new Period( abs_pr ) ;
	}
	if( isset( $_SESSION[ 'person' ] ) ) {
		$ad = $_SESSION[ 'person' ] ;
	} else {
		header( 'Location: logout.php?type=permissions' ) ;
	}
	
	if( isset( $_GET['student'] ) ) {
		$student = new Person( $_GET['student'] , $abs_pr ) ;
		$student_forms = $student->getForms() ;
		$head = $student_forms[ 0 ]->getHead() ;
		$admin = $student_forms[ 0 ]->getAdmin() ;
		$formteacher = $student_forms[ 0 ]->getTeacher() ;
		
		$line = '<input type="hidden" id="student" value="' . $student->getId() . '" />' ;
		$line .= '<div>' . $student->getFirst() . ' ' . $student->getSurname() . '</div>' ;
		$line .= '<table class="toolbar" ><thead></thead><tbody><tr>' ;
		$line .= '<td class="toolcell" onclick="ajaxForm( false );" >&larr; Back to form</td>' ;
		if( $ad->getId() == $formteacher->getId() or $ad->getId() == $head->getId() /*or $ad->getId() == $admin->getId()*/ ) {
			$line .= '<td class="toolcell" onclick="ajaxStudent( ' . $student->getId() . ' ) ;" >Absences</td>' ;
		}
		$line .= '<td class="toolcell" onclick="ajaxRelated( ' . $student->getId() . ' ) ;" >Mail</td>' ;
		$line .= '<td class="toolcell" onclick="ajaxPaper( ' . $student->getId() . ' ) ;" >Papers</td>' ;
		$line .= '<td class="toolcell" onclick="ajaxSchedule( ' . $student->getId() . ' ) ;" >Schedule</td>' ;
		if( $ad->getId() == $head->getId() /*or $ad->getId() == $admin->getId() */) {
			$line .= '<td class="toolcell" onclick="ajaxExcuse( ' . $student->getId() . ' ) ;" >Excuses</td>' ;
		}
		$line .= '</tr></tbody></table>' ;
		$line .= '<div id="data" >' . Printer::student_table( $student ) . Printer::legend() . '</div>' ;
		echo Printer::tidy( $line ) ;
	} else {
		echo 'Invalid student.' ;
	}
?>
