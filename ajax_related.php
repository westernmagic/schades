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
	*	@file ajax_related.php
	*	
	*	@author Michal Sudwoj <mswoj61@gmail.com>
	*	@copyright Michal Sudwoj
	*	@link http://www.sourceforge.com/projects/schades/
	*	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
	*	@version 0.8
	*	
	*	@brief AJAX mail-to-related page.
	*/
	
	require_once( 'include.php' ) ;
	
	if( isset( $_GET[ 'abs_pr' ] ) ) {
		$pr = new Period( $_GET[ 'abs_pr' ] ) ;
	} else {
		$pr = abs_pr ;
	}
	$student = new Person( $_GET[ 'student' ] , $pr ) ;
	
	$teacher = $student->getForms( 0 )->getTeacher() ;
	$head = $student->getForms( 0 )->getHead() ;
	$admin = $student->getForms( 0 )->getAdmin() ;
	$courses = $student->getCourses() ;
	
	$line = '<form name="mails" id="mails" ><table>' ;
	$line .= '<thead><tr><th>None</th><th>To</th><th>Cc</th><th>Bcc</th>' ;
	$line .= '<th>Person</th></tr></thead>' ;
	$line .= '<tbody>' ;
	
	//student
	$line .= '<tr>' ;
	$line .= '<td><input type="radio" name="' . $student->getEmail() . '"id="' . $student->getEmail() . '" value="none" onchange="mailtoSet() ;" checked="checked" /></td>' ;
	$line .= '<td><input type="radio" name="' . $student->getEmail() . '"id="' . $student->getEmail() . '" value="to" onchange="mailtoSet() ;"/></td>' ;
	$line .= '<td><input type="radio" name="' . $student->getEmail() . '"id="' . $student->getEmail() . '" value="cc" onchange="mailtoSet() ;"/></td>' ;
	$line .= '<td><input type="radio" name="' . $student->getEmail() . '"id="' . $student->getEmail() . '" value="bcc" onchange="mailtoSet() ;"/></td>' ;
	$line .= '<td><label for="' . $student->getEmail() . '" >Student (' . $student->getFirst() . ' ' . $student->getSurname() . ')</label></td>' ;
	$line .= '</tr>' ;
	
	//form teacher
	$line .= '<tr>' ;
	$line .= '<td><input type="radio" name="' . $teacher->getEmail() . '" id="' . $teacher->getEmail() . '" value="none" onchange="mailtoSet() ;" checked="checked" /></td>' ;
	$line .= '<td><input type="radio" name="' . $teacher->getEmail() . '" id="' . $teacher->getEmail() . '" value="to" onchange="mailtoSet() ;" /></td>' ;
	$line .= '<td><input type="radio" name="' . $teacher->getEmail() . '" id="' . $teacher->getEmail() . '" value="cc" onchange="mailtoSet() ;" /></td>' ;
	$line .= '<td><input type="radio" name="' . $teacher->getEmail() . '" id="' . $teacher->getEmail() . '" value="bcc" onchange="mailtoSet() ;" /></td>' ;
	$line .= '<td><label for="' . $teacher->getEmail() . '" >Form Teacher (' . $teacher->getFirst() . ' ' . $teacher->getSurname() . ' (' . $teacher->getTeacher() . ') )</label></td>' ;
	$line .= '</tr>' ;
	
	//head of year
	$line .= '<tr>' ;
	$line .= '<td><input type="radio" name="' . $head->getEmail() . '" id="' . $head->getEmail() . '" value="none" onchange="mailtoSet() ;" checked="checked" /></td>' ;
	$line .= '<td><input type="radio" name="' . $head->getEmail() . '" id="' . $head->getEmail() . '" value="to" onchange="mailtoSet() ;" /></td>' ;
	$line .= '<td><input type="radio" name="' . $head->getEmail() . '" id="' . $head->getEmail() . '" value="cc" onchange="mailtoSet() ;" /></td>' ;
	$line .= '<td><input type="radio" name="' . $head->getEmail() . '" id="' . $head->getEmail() . '" value="bcc" onchange="mailtoSet() ;" /></td>' ;
	$line .= '<td><label for="' . $head->getEmail() . '" >Head of Year (' . $head->getFirst() . ' ' . $head->getSurname() . ' (' . $head->getTeacher() . ') )</label></td>' ;
	$line .= '</tr>' ;
	
	//absence admin
	$line .= '<tr>' ;
	$line .= '<td><input type="radio" name="' . $admin->getEmail() . '" id="' . $admin->getEmail() . '" value="none" onchange="mailtoSet() ;" checked="checked" /></td>' ;
	$line .= '<td><input type="radio" name="' . $admin->getEmail() . '" id="' . $admin->getEmail() . '" value="to" onchange="mailtoSet() ;" /></td>' ;
	$line .= '<td><input type="radio" name="' . $admin->getEmail() . '" id="' . $admin->getEmail() . '" value="cc" onchange="mailtoSet() ;" /></td>' ;
	$line .= '<td><input type="radio" name="' . $admin->getEmail() . '" id="' . $admin->getEmail() . '" value="bcc" onchange="mailtoSet() ;" /></td>' ;
	$line .= '<td><label for="' . $admin->getEmail() . '" >Absence Admin (' . $admin->getFirst() . ' ' . $admin->getSurname() . ' (' . $admin->getTeacher() . ') )</label></td>' ;
	$line .= '</tr>' ;
	
	//course teachers
	$line .= '<tr>' ;
	$line .= '<td><input type="radio" name="courses" id="courses" value="none" onchange="mailtoMoveAll() ; mailtoSet() ;"checked="checked" /></td>' ;
	$line .= '<td><input type="radio" name="courses" id="courses" value="to" onchange="mailtoMoveAll() ; mailtoSet() ;"/></td>' ;
	$line .= '<td><input type="radio" name="courses" id="courses" value="cc" onchange="mailtoMoveAll() ; mailtoSet() ;"/></td>' ;
	$line .= '<td><input type="radio" name="courses" id="courses" value="bcc" onchange="mailtoMoveAll() ; mailtoSet() ;"/></td>' ;
	$line .= '<td><label for="courses" >Course Teachers</label></td>' ;
	$line .= '</tr>' ;
	
	foreach( $courses as $c ) {
		$t = $c->getTeacher() ;
		$line .= '<tr>' ;
		$line .= '<td><input type="radio" name="' . $t->getEmail() . '" id="' . $t->getEmail() . '" value="none" onchange="mailtoSet() ;"checked="checked" /></td>' ;
		$line .= '<td><input type="radio" name="' . $t->getEmail() . '" id="' . $t->getEmail() . '" value="to" onchange="mailtoSet() ;"/></td>' ;
		$line .= '<td><input type="radio" name="' . $t->getEmail() . '" id="' . $t->getEmail() . '" value="cc" onchange="mailtoSet() ;"/></td>' ;
		$line .= '<td><input type="radio" name="' . $t->getEmail() . '" id="' . $t->getEmail() . '" value="bcc" onchange="mailtoSet() ;"/></td>' ;
		$line .= '<td><label for="' . $t->getEmail() . '" >' . $c->getId() . ' (' . $t->getFirst() . ' ' . $t->getSurname() . ' (' . $t->getTeacher() . ') )</label></td>' ;
		$line .= '</tr>' ;
	}
	
	$line .= '<tr><td class="toolcell" colspan="4" onclick="doMailto() ;" >MailTo</td>' ;
	$line .= '<td><textarea name="to" id="to" readonly="readonly" ></textarea><textarea name="cc" id="cc" readonly="readonly" ></textarea><textarea name="bcc" id="bcc" readonly="readonly" ></textarea></td>' ;
	$line .= '</tr></tbody></table></form>' ;
	
	echo Printer::tidy( $line ) ;
	
?>
