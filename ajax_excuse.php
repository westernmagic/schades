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
	*	@file ajax_excuse.php
	*	
	*	@author Michal Sudwoj <mswoj61@gmail.com>
	*	@copyright Michal Sudwoj
	*	@link http://www.sourceforge.com/projects/schades/
	*	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
	*	@version 0.8
	*	
	*	@brief AJAX Excuse page
	*	
	*	@details Returns the excuse page.
	*/
	
	//require_once( 'settings/settings.php' ) ;
	require_once( 'class/Excuse.php' ) ;
	require_once( 'class/Period.php' ) ;
	require_once( 'class/Printer.php' ) ;
	
	if( isset( $_GET[ 'abs_pr' ] ) ) {
		$pr = new Period( $_GET[ 'abs_pr' ] ) ;
	} else {
		$pr = new Period( abs_pr ) ;
	}
	
	if( isset( $_GET[ 'student' ] ) ) {
		$student = new Person( $_GET[ 'student' ] , $pr ) ;
	}
	$excuses = $student->getExcuses() ;
	
	$line = '' ;
	$line .= '<br /><table id="excusetable" border="1" style="border-collapse: collapse ; width: 100% ;" ><thead><tr><th colspan="3" >From</th><th colspan="3" >To</th><th rowspan="2" >Value</th><th rowspan="2" >Reason</th><th rowspan="2" >Granted</th><th colspan="2" rowspan="2" ></th></tr>' ;
	$line .= '<tr><th>Week</th><th>Day</th><th>Lesson</th>' 
	$ine .= '<th>Week</th><th>Day</th><th>Lesson</th></tr></thead><tbody>' ;
	foreach( $excuses as $exc ) {
		$line .= '<tr id="' . $exc->getId() . '" >' ;
		$line .= '<td>' . $exc->getFrom_week() .'</td><td>' . $exc->getFrom_day() .'</td><td>' . $exc->getFrom_lesson() . '</td>' ;
		$line .= '<td>' . $exc->getTo_week() . '</td><td>' . $exc->getTo_day() . '</td><td>' . $exc->getTo_lesson() . '</td>' ;
		$line .= '<td>' . $exc->getValue() . '</td><td>' . $exc->getReason() . '</td>' ;
		$line .= '<td>' . $exc->getGranter()->getFirst() . ' ' . $exc->getGranter()->getSurname() . ' ( ' . $exc->getGranted()->getNorm() . ' )</td>' ;
		$line .= '<td onclick="excuseEdit( ' . $exc->getId() . ' ) ;" >Edit</td>' ;
		$line .= '<td onclick="ajaxExcuseDelete( ' . $exc->getId() . ' ) ;" >Delete</td>' ;
		$line .= '</tr>' ;
	}
	$line .= '</tbody></table>' ;
	
	$line .= '<br /><form><table style="border-style: solid ; border-width: 1px ; margin-left: auto ; margin-right: auto ;" ><thead></thead><tbody>' ;
	
	$line .= '<tr><th colspan="4" >From</th></tr>' ;
	$line .= '<tr><td><label for="from_date" >Date:</label></td><td><input type="text" id="from_date" /></td>' ;
	$line .= '<td><label for="from_week" >Week:</label></td><td><select id="from_week" onselect="">' ;
	foreach( $pr->getAbs_weeks() as $w ) {
		$line .= '<option value="' . $w . '" ' ;
		$line .= '>' . $w . '</option>' ;
	}
	
	$line .= '</select></td></tr>' ;
	$line .= '<tr><td colspan="2" ></td><td><label for="from_day" >Day:</label></td><td><select id="from_day" onselect="" >' ;
	$line .= '<option value="1" >1 (Monday)</option><option value="2" >2 (Tuesday)</option><option value="3" >3 (Wednesday)</option><option value="4" >4 (Thursday)</option><option value="5" >5 (Friday)</option>' ;
	$line .= '</select></td></tr>' ;
	
	$line .= '<tr><td><label for="from_time" >Time:</label></td><td><input type="text" id="from_time" /></td><td><label for="from_lesson" />Lesson:</label></td><td><select id="from_lesson" >' ;
	for( $i = 1 ; $i <= 12 ; $i++ ) {
		$line .= '<option value="' . $i . '" ' ;
		if( $i == 1 ) {	
			$line .= 'selected="true" ' ;
		}
		$line .= '>' . $i . '</option>' ;
	}
	$line .= '</select></td></tr>' ;
	
	$line .= '<tr><th colspan="4" >To</th></tr>' ;
	$line .= '<tr><td><label for="to_date" >Date:</label></td><td><input type="text" id="to_date" /></td>' ;
	$line .= '<td><label for="to_week"  >Week:</label></td><td><select id="to_week" onselect="" >' ;
	foreach( $pr->getAbs_weeks() as $w ) {
		$line .= '<option value="' . $w . '" ' ;
		$line .= '>' . $w . '</option>' ;
	}
	
	$line .= '</select></td></tr>' ;
	$line .= '<tr><td colspan="2" ></td><td><label for="to_day" >Day:</label></td><td><select id="to_day" onselect="" >' ;
	$line .= '<option value="1" >1 (Monday)</option><option value="2" >2 (Tuesday)</option><option value="3" >3 (Wednesday)</option><option value="4" >4 (Thursday)</option><option value="5" >5 (Friday)</option>' ;
	$line .= '</select></td></tr>' ;
	
	$line .= '<tr><td><label for="to_time" >Time:</label></td><td><input type="text" id="to_time" /></td><td><label for="to_lesson" />Lesson:</label></td><td><select id="to_lesson" >' ;
	for( $i = 1 ; $i <= 12 ; $i++ ) {
		$line .= '<option value="' . $i . '" ' ;
		if( $i == 12 ) {	
			$line .= 'selected="true" ' ;
		}
		$line .= '>' . $i . '</option>' ;
	}
	$line .= '</select></td></tr>' ;
	
	$line .= '<tr><td colspan="4" >&nbsp;</td></tr>' ;
	$line .= '<tr><th colspan="3" ><label for="value" >Value</label></th><td><input type="text" id="value" /></td></tr>' ;
	$line .= '<tr><td colspan="4" >&nbsp;</td></tr>' ;
	
	$line .= '<tr><th colspan="4" >Reason</th></tr>' ;
	$line .= '<tr><td colspan="4" ><textarea id="reason" style="width: 99% ; height: 100px ;" ></textarea><input type="hidden" id="excuseid" value="0" /></td><tr>' ;
	$line .= '<tr><td colspan="2" class="toolcell" onclick="ajaxExcuseSave() ;" >Save</td><td colspan="2" class="toolcell" onclick="parentNode.parentNode.parentNode.parentNode.reset() ;" >Reset</td></tr>' ;
	
	$line .= '</tbody></table></form>' ;
	
	echo Printer::tidy( $line ) ;
	
?>