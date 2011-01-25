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
	*	@file ajax_paper.php
	*	
	*	@author Michal Sudwoj <mswoj61@gmail.com>
	*	@copyright Michal Sudwoj
	*	@link http://www.sourceforge.com/projects/schades/
	*	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
	*	@version 0.8
	*	
	*	@brief AJAX paper setting page.
	*/
	
	require_once( 'include.php' ) ;
		
	$line = '<form style="width: 100% ;" ><table style="margin-left: auto ; margin-right: auto ;" ><thead></thead><tbody>' ;
	$line .= '<tr><td style="text-align: center ;" id="type" ><select>' ;
	$line .= '<option value="101" >Letter</option>' ;
	$line .= '<option value="411" >Lower</option>' ;
	$line .= '<option value="416" >Upper</option>' ;
	$line .= '<option value="404" >AZ</option>' ;
	$line .= '</select></td></tr>' ;
	$line .= '<tr><td class="toolcell" onlclick="" >Print</td></tr>' ;
	$line .= '</tbody></table></form>' ;
	
	echo Printer::tidy( $line ) ;
?>
