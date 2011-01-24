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
	*	@file login.php
	*	
	*	@author Michal Sudwoj <mswoj61@gmail.com>
	*	@copyright Michal Sudwoj
	*	@link http://www.sourceforge.com/projects/schades/
	*	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
	*	@version 0.8
	*	
	*	@brief Login page
	*	
	*	@details First contact with the user. Logs him/her in.
	*	
	*	@todo dynamic display of warining message, redirection if no JavaScript/AJAX.
	*/
	
	require_once( 'include.php' ) ;
	
	if( isset( $_POST["user"] ) ) {
		$user = stripslashes( $_POST["user"] ) ;
	}
	if( isset( $_POST["pass"] ) ) {
		$pass = stripslashes( $_POST["pass"] ) ;
	}
	if( isset( $_GET["type"] ) ) {
		$type = $_GET["type"] ;
	} else {
		$type = '' ;
	}
	unset( $_POST ) ;
	unset( $_GET ) ;
	
	if( isset( $user ) and isset( $pass) ) {
		$type = 'wrong' ;
		$query = "SELECT " . DB::name( 'login' , 'ad_id' ) . " , " . DB::name( 'login' , 'username' ) . " , " . DB::name( 'login' , 'password' ) . " , " . DB::name( 'ad_ad' , 'ad_type_id' ) ;
		$query .= " FROM " . DB::name( 'login' ) ;
		$query .= " JOIN " . DB::name( 'ad_ad' ) . " ON " . DB::name( 'login' , 'ad_id' ) . " = " . DB::name( 'ad_ad' , 'ad_id' ) ;
		$query .= " WHERE " . DB::name( 'login' , 'username' ) . " = '" . DB::escape( $user ) . "' " ;
		$query .= " AND " . DB::name( 'login' , 'password' ) . " = '" . DB::escape( $pass ) . "' ;" ;
		if( $resultset = DB::query( $query ) ) {
			if( DB::count( $resultset ) == 1 ) {
				$row = DB::fetch( $resultset ) ;
				if( $row["username"] == $user and $row["password"] == $pass ) {
					session_start();
					$_SESSION[ 'person' ] = new Person( $row[ 'ad_id' ] , new Period( abs_pr ) ) ;
					
					$query = 'INSERT INTO ' . DB::name( 'abs_log_login' ) ;
					$query .= ' SET' ;
					$query .= ' ' . DB::name( 'abs_log_login' , 'log_login_id' ) . ' = DEFAULT ,' ;
					$query .= ' ' . DB::name( 'abs_log_login' , 'ad_id' ) . ' = ' . DB::escape( $row['ad_id'] ) . ' ,' ;
					$query .= ' ' . DB::name( 'abs_log_login' , 'date_time' ) . ' = DEFAULT ,' ;
					$query .= ' ' . DB::name( 'abs_log_login' , 'ip' ) . ' = "' . $_SERVER['REMOTE_ADDR'] . '" ;' ;
					
					if( !DB::query( $query ) ) {
						throw new ErrorException( 'Login logging error.' , 191 , 1 , 'login.php' , 39 ) ;
					}
					
					header( 'Location: index.php' ) ;
				}
			} else {
				throw new ErrorException( 'Ambigous login data.' , 121 , 1 , 'login.php' , 45 ) ;
			}
		}
		
	}
?>
<!DOCTYPE html PUBLIC
  "-//W3C//DTD XHTML 1.1//EN"
  "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
	<head>
		<title>Absence Administration System</title>
		<script type="text/javascript" src="ajax.js" ></script>
		<script type="text/javascript" >
			if( xhr == null ){
				document.print( 'XMLHTTPRequest must be enabled past this point.' ) ;
			}
		</script>
	</head>
	<body>
		<?php
			switch( $type ) {
				case 'wrong' :
					echo 'Wrong username or password.<br />' ;
					break ;
				case 'error' :
					echo 'An error has occured.<br />' ;
					break ;
				case 'logout' :
					echo 'Logout successful.<br />' ;
					break ;
				case 'permissions' :
					echo 'You do not have the permissions to view that page.<br />' ;
					break ;
				default:
					echo '<br />' ;
					break ;
			}
		?>
		<noscript>JavaScript must be enabled past this point.</noscript>
		<form action="login.php" method="post" >
			<select id="user" name="user" onchange="submit() ;" >
				<option value="" selected="true" >Username</option>
				<option value="sudwmich" >sudwmich</option>
				<option value="Bx" >Bx</option>
				<option value="Ws" >Ws</option>
				<option value="Pl" >Pl</option>
				<option value="root" >root</option>
			</select>
			<input type="hidden" id="pass" name="pass" value="" />
			<!--<label for="user" >Username:</label><input type="text" id="user" name="user" />
			<label for="pass" >Password:</label><input type="password" id="pass" name="pass" />
			<input type="submit" value="Submit" />-->
		</form>
	</body>
</html>