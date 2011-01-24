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
*	@file ajax.js
*	
*	@author Michal Sudwoj <mswoj61@gmail.com>
*	@copyright Michal Sudwoj
*	@link http://www.sourceforge.com/projects/schades/
*	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
*	@version 0.8
*	
*	@brief Contains all JavaScript functions, mostly AJAX.
*/
var xhr ;

function GetXMLHttpObject() {
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		return new XMLHttpRequest();
	}
	if (window.ActiveXObject) {
		// code for IE6, IE5
		return new ActiveXObject( "Microsoft.xhr" );
	}
	return null;
}

xhr = GetXMLHttpObject() ;

function redirect( url ) {
	window.open( url ) ;
}

function absences() {
	var fields = new Array() ;
	var y = 2 ;
	var table = document.getElementById( 'absences' ) ;
	while( y < table.rows.length ) {
		var x = 2 ;
		var row = table.rows[y] ;
		while( x < row.cells.length ) {
			var val = '.' ;
			var cell = row.cells[x] ;
			var type = cell.className;
			switch( type ) {
				case "t" :
					val = "." ;
					break ;
				case "o" :
					val = "o" ;
					break ;
				case "x" :
					val = "x" ;
					break ;
				case "p" :
					val = "p" ;
					break ;
			}
			fields[ cell.id ] = val ;
			x++ ;
		}
		y++ ;
	}
	return fields ;
}

function mailtoSet() {
	document.getElementById( 'to' ).value = '' ;
	document.getElementById( 'cc' ).value = '' ;
	document.getElementById( 'bcc' ).value = '' ;
	
	var radios = document.getElementById( 'mails' ).elements ;
	for( r in radios ) {
		var radio = radios[ r ] ;
		if( radio.checked && radio.value != 'none' && radio.id != 'courses' ) {
			if( document.getElementById( radio.value ).value != '' ) {
				document.getElementById( radio.value ).value += ',' ;
			}
			document.getElementById( radio.value ).value += radio.id ;
		}
	}
}

function mailtoMoveAll() {
	var past = false ;
	var state = '' ;
	var radios = document.getElementById( 'mails' ).elements ;
	for( r in radios ) {
		var radio = radios[ r ] ;
		if( radio.id == 'courses' && radio.checked ) {
			past = true ;
			state = radio.value ;
		}
		
		if( past ){
			if( radio.value == state ) {
				radio.checked = true ;
			} else {
				radio.checked = false ;
			}
		}
	}
}

function doMailto(){
	var mailtostring = 'mailto:' ;
	if( document.getElementById( 'to' ).value != '' ) {
		mailtostring += document.getElementById( 'to' ).value ;
	}
	if( document.getElementById( 'cc' ).value != '' ) {
		mailtostring += document.getElementById( 'cc' ).value ;
	}
	if( document.getElementById( 'bcc' ).value != '' ) {
		mailtostring += document.getElementById( 'bcc' ).value ;
	}
	redirect( mailtostring ) ;
}

function requestTable() {
	document.getElementById( "table" ).innerHTML='' ;
	switch( xhr.readyState ) {
		case 0 :
		case 1 :
		case 2 :
		case 3 :
			document.getElementById( "loading" ).innerHTML='Loading... Please wait.' ;
			break ;
		case 4 :
			document.getElementById( "loading" ).innerHTML='' ;
			document.getElementById( "table" ).innerHTML=xhr.responseText ;
			break ;
	}
}
	
function ajaxMain( tab ) {
	if ( !(tab == 0 || tab == 1 || tab == 2) ) {
		document.getElementById( "main" ).innerHTML="" ;
		return;
	}
	/*xhr=GetXMLHttpObject();
	if (xhr==null) {
		alert ( "Your browser does not support xhr!" );
		return;
	}*/
	var url="ajax_main.php";
	url += "?tab=" + tab ;
	xhr.onreadystatechange=function(){
	document.getElementById( "main" ).innerHTML='' ;
		switch( xhr.readyState ) {
			case 0 :
			case 1 :
			case 2 :
			case 3 :
				document.getElementById( "main" ).innerHTML='<div class="loading" >Loading... Please wait.</div>' ;
				break ;
			case 4 :
				document.getElementById( "main" ).innerHTML=xhr.responseText ;
				ajaxForm( false ) ;
				break ;
		}
	};
	xhr.open( "GET",url,true);
	xhr.send(null);
}

function ajaxForm( edit ) {
	var pr = document.getElementById('abs_pr').value ;
	try{
		var copg = document.getElementById('copg').value ;
		var week = document.getElementById('week').value ;
	} catch(err) {
		copg = "NULL" ;
		week = "NULL" ;
	}
	if ( pr.length == 0 || copg.length == 0 || week.length == 0 ) {
		document.getElementById( "table" ).innerHTML="" ;
		return;
	}
	/*xhr=GetXMLHttpObject();
	if (xhr==null) {
		alert ( "Your browser does not support xhr!" );
		return;
	}*/
	var url="ajax_teacher.php";
	url += "?abs_pr=" + pr + "&copg=" + copg + "&week=" + week + "&edit=" + edit ;
	xhr.onreadystatechange=requestTable;
	xhr.open( "GET",url,true);
	xhr.send(null);
}

function ajaxStudent( student ) {
	var pr = document.getElementById('abs_pr').value ;
	if ( student.length == 0 ) {
		document.getElementById( "table" ).innerHTML="" ;
		return;
	}
	/*xhr=GetXMLHttpObject();
	if (xhr==null) {
		alert ( "Your browser does not support xhr!" );
		return;
	}*/
	var url="ajax_student.php";
	url += "?abs_pr=" + pr + "&student=" + student ;
	xhr.onreadystatechange=requestTable;
	xhr.open( "GET",url,true);
	xhr.send(null);
}

function changeOpt( obj ) {
	var val ;
	var colour ;
	switch( obj.className ) {
		case "t" :
			val = "o" ;
			colour = "o" ;
			break ;
		case "o" :
			val = "x" ;
			colour = "x" ;
			break ;
		case "x" :
			val = "p" ;
			colour = "p" ;
			break ;
		case "p" :
			val = "." ;
			colour = "t" ;
			break ;
	}
	obj.setAttribute( "class" , colour ) ;
	fields[ obj.id ] = val ;
	return ;
}

function ajaxSave() {
	var params = "" ;
	/*xhr=GetXMLHttpObject();
	if (xhr==null) {
		alert ( "Your browser does not support xhr!" );
		return;
	}*/
	var fields = absences() ;
	for( key in fields ) {
		params += '&' + key + '=' + fields[key] ;
	}
	params = params.substr( 1 ) ;
	var url="abs_saver.php";
	xhr.onreadystatechange=function(){
	document.getElementById( "loading" ).innerHTML='' ;
		switch( xhr.readyState ) {
			case 0 :
			case 1 :
			case 2 :
			case 3 :
				document.getElementById( "loading" ).innerHTML='<div class="loading" >Saving...</div>' ;
				break ;
			case 4 :
				document.getElementById( "loading" ).innerHTML='' ;
				ajaxForm( false ) ;
				break ;
		}
	};
	xhr.open( "POST",url,true);
	xhr.setRequestHeader( "Content-type", "application/x-www-form-urlencoded" );
	xhr.send(params);
	ajaxForm( false ) ;
	return ;
}

function ajaxExcuse( student ) {
	var pr = document.getElementById('abs_pr').value ;
	if ( student.length == 0 ) {
		document.getElementById( "table" ).innerHTML="" ;
		return;
	}
	/*xhr=GetXMLHttpObject();
	if (xhr==null) {
		alert ( "Your browser does not support xhr!" );
		return;
	}*/
	var url="ajax_excuse.php";
	url += "?abs_pr=" + pr + "&student=" + student ;
	xhr.onreadystatechange=function(){
		document.getElementById( "data" ).innerHTML='' ;
		switch( xhr.readyState ) {
			case 0 :
			case 1 :
			case 2 :
			case 3 :
				document.getElementById( "loading" ).innerHTML='Loading... Please wait.' ;
				break ;
			case 4 :
				document.getElementById( "loading" ).innerHTML='' ;
				document.getElementById( "data" ).innerHTML=xhr.responseText ;
				break ;
		}
	};
	xhr.open( "GET",url,true);
	xhr.send(null);
}

function excuseEdit( id ) {
	document.getElementById( 'from_date' ).value = '' ;
	document.getElementById( 'from_time' ).value = '' ;
	document.getElementById( 'to_date' ).value = '' ;
	document.getElementById( 'to_time' ).value = '' ;
	
	var cells = document.getElementById( id ).cells ;
	
	var i = 0 ;
	do {
		document.getElementById( 'from_week' ).selectedIndex = i++ ;
	} while( parseInt( cells[ 0 ].innerHTML ) != document.getElementById( 'from_week' ).options[ i - 1 ].value ) ;
	document.getElementById( 'from_day' ).selectedIndex = parseInt( cells[ 1 ].innerHTML ) - 1 ;
	document.getElementById( 'from_lesson' ).selectedIndex = parseInt( cells[ 2 ].innerHTML ) - 1 ;
	
	i = 0 ;
	do {
		document.getElementById( 'to_week' ).selectedIndex = i++ ;
	} while( parseInt( cells[ 0 ].innerHTML ) != document.getElementById( 'to_week' ).options[ i - 1 ].value ) ;
	document.getElementById( 'to_day' ).selectedIndex = parseInt( cells[ 4 ].innerHTML ) - 1 ;
	document.getElementById( 'to_lesson' ).selectedIndex = parseInt( cells[ 5 ].innerHTML ) - 1 ;
	
	document.getElementById( 'value' ).value = cells[ 6 ].innerHTML ;
	document.getElementById( 'reason' ).value = cells[ 7 ].innerHTML ;
	
	document.getElementById( 'excuseid' ).value = id ;
	
}

function ajaxExcuseDelete( id ) {
	var student = document.getElementById( 'student' ).value;
	/*xhr=GetXMLHttpObject();
	if (xhr==null) {
		alert ( "Your browser does not support xhr!" );
		return;
	}*/
	var url="excuse_action.php";
	url += "?action=delete&excuseid=" + id ;
	xhr.onreadystatechange=function(){
		switch( xhr.readyState ) {
		case 0 :
		case 1 :
		case 2 :
		case 3 :
			document.getElementById( "loading" ).innerHTML='Loading... Please wait.' ;
			break ;
		case 4 :
			document.getElementById( "loading" ).innerHTML='' ;
			ajaxExcuse( student ) ;
			break ;
		}
	};
	xhr.open( "GET",url,true);
	xhr.send(null);
}

function ajaxExcuseSave() {
	var pr = document.getElementById( 'abs_pr' ).value ;
	var student = document.getElementById( 'student' ).value;
	var from_week = document.getElementById( 'from_week' ).value ;
	var from_day = document.getElementById( 'from_day' ).value ;
	var from_lesson = document.getElementById( 'from_lesson' ).value ;
	var to_week = document.getElementById( 'to_week' ).value ;
	var to_day = document.getElementById( 'to_day' ).value ;
	var to_lesson = document.getElementById( 'to_lesson' ).value ;
	var val = document.getElementById( 'value' ).value ;
	var reason = document.getElementById( 'reason' ).value ;
	var excuseid = document.getElementById( 'excuseid' ).value ;
	
	
	/*xhr=GetXMLHttpObject();
	if (xhr==null) {
		alert ( "Your browser does not support xhr!" );
		return;
	}*/
	var url="excuse_action.php";
	url += '?action=add&pr=' + pr + '&student=' + student + '&from_week=' + from_week + '&from_day=' + from_day + '&from_lesson=' + from_lesson ;
	url += '&to_week=' + to_week + '&to_day=' + to_day + '&to_lesson=' + to_lesson + '&value=' + val + '&reason=' + reason + '&excuseid=' + excuseid ;
	xhr.onreadystatechange=function(){
		switch( xhr.readyState ) {
		case 0 :
		case 1 :
		case 2 :
		case 3 :
			document.getElementById( "loading" ).innerHTML='Loading... Please wait.' ;
			break ;
		case 4 :
			document.getElementById( "loading" ).innerHTML='' ;
			ajaxExcuse( student ) ;
			break ;
		}
	};
	xhr.open( "GET",url,true);
	xhr.send(null);
}

function ajaxSchedule( id ) {
	var pr = document.getElementById('abs_pr').value ;
	var week = document.getElementById('week').value ;
	
	/*xhr=GetXMLHttpObject();
	if (xhr==null) {
		alert ( "Your browser does not support xhr!" );
		return;
	}*/
	var url="ajax_schedule.php";
	url += "?abs_pr=" + pr + '&week=' + week + "&ad_id=" + id ;
	xhr.onreadystatechange=function(){
		document.getElementById( "data" ).innerHTML='' ;
		switch( xhr.readyState ) {
			case 0 :
			case 1 :
			case 2 :
			case 3 :
				document.getElementById( "loading" ).innerHTML='Loading... Please wait.' ;
				break ;
			case 4 :
				document.getElementById( "loading" ).innerHTML='' ;
				document.getElementById( "data" ).innerHTML=xhr.responseText ;
				break ;
		}
	};
	xhr.open( "GET",url,true);
	xhr.send(null);
}

function ajaxQueue() {
	/*xhr=GetXMLHttpObject();
	if (xhr==null) {
		alert ( "Your browser does not support xhr!" );
		return;
	}*/
	var url="ajax_queue.php";
	xhr.onreadystatechange=function(){
	document.getElementById( "main" ).innerHTML='' ;
		switch( xhr.readyState ) {
			case 0 :
			case 1 :
			case 2 :
			case 3 :
				document.getElementById( "main" ).innerHTML='<div class="loading" >Loading... Please wait.</div>' ;
				break ;
			case 4 :
				document.getElementById( "main" ).innerHTML=xhr.responseText ;
				ajaxForm( false ) ;
				break ;
		}
	};
	xhr.open( "GET",url,true);
	xhr.send(null);
}

function ajaxRelated( student ) {
	var pr = document.getElementById('abs_pr').value ;
	if ( student.length == 0 ) {
		document.getElementById( "table" ).innerHTML="" ;
		return;
	}
	/*xhr=GetXMLHttpObject();
	if (xhr==null) {
		alert ( "Your browser does not support xhr!" );
		return;
	}*/
	var url="ajax_related.php";
	url += "?abs_pr=" + pr + "&student=" + student ;
	xhr.onreadystatechange=function(){
		document.getElementById( "data" ).innerHTML='' ;
		switch( xhr.readyState ) {
			case 0 :
			case 1 :
			case 2 :
			case 3 :
				document.getElementById( "loading" ).innerHTML='Loading... Please wait.' ;
				break ;
			case 4 :
				document.getElementById( "loading" ).innerHTML='' ;
				document.getElementById( "data" ).innerHTML=xhr.responseText ;
				break ;
		}
	};
	xhr.open( "GET",url,true);
	xhr.send(null);
}

function ajaxPaper( student ) {
	var pr = document.getElementById('abs_pr').value ;
	if ( student.length == 0 ) {
		document.getElementById( "table" ).innerHTML="" ;
		return;
	}
	var url="ajax_paper.php";
	url += "?abs_pr=" + pr + "&student=" + student ;
	xhr.onreadystatechange=function(){
		document.getElementById( "data" ).innerHTML='' ;
		switch( xhr.readyState ) {
			case 0 :
			case 1 :
			case 2 :
			case 3 :
				document.getElementById( "loading" ).innerHTML='Loading... Please wait.' ;
				break ;
			case 4 :
				document.getElementById( "loading" ).innerHTML='' ;
				document.getElementById( "data" ).innerHTML=xhr.responseText ;
				break ;
		}
	};
	xhr.open( "GET",url,true);
	xhr.send(null);
}