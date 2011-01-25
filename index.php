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
	*	@brief Main page
	*	
	*	@details The core of the application. Evokes all other pages.
	*/
	
	require_once( 'include.php' ) ;
	
	session_start();
	
	if( isset( $_SESSION[ 'person' ] ) ) {
		$ad = $_SESSION[ 'person' ] ;
	} else {
		header( 'Location: logout.php' ) ;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Absence Administration System</title>
		<meta name="author" content="Michal Sudwoj" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" type="text/css" href="settings/styles.css" />
		
		<script type="application/javascript" src="ajax.js" ></script>
	</head>
	<body onload="ajaxMain( 1 ) ;" >
		<div id="toolbar" >
			<table class="toolbar" >
				<thead>
				</thead>
				<tbody>
					<tr>
						<td class="toolcell" onclick="redirect( 'agpl.txt' ) ;" >
							<img alt="AGPLv3" style="border-width:0" src="img/agplv3-155x51.png" />
						</td>
						<td class="toolcell" onclick="ajaxQueue() ;">Queue( <span id="qcount"><?php echo count( $ad->getQueue() ) ;?></span> )</td>
						<td class="toolcell" onclick="ajaxMain( 1 ) ;">Absences</td>
						<td class="toolcell" onclick="ajaxMain( 0 ) ;">Settings</td>
						<td class="toolcell" onclick="window.location = 'logout.php?type=logout' ;">Logout</td>
						<td class="toolcell" onclick="redirect( 'http://http://193.17.76.37/redmine/' ) ;" >Bug Report / Feature Request / Source</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div id="main" >
			<span class="loading" id="loading" >
			</span>
		</div>
	</body>
</html>
