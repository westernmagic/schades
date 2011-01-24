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
	*	@file DB.php
	*	
	*	@author Michal Sudwoj <mswoj61@gmail.com>
	*	@copyright Michal Sudwoj
	*	@link http://www.sourceforge.com/projects/schades/
	*	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
	*	@version 0.8
	*/
	
	require_once( 'include.php' ) ;
	
	/**
	*	@brief DB class
	*	
	*	@details Used to connect and query a database
	*	
	*/
	abstract class DB extends SuperClass {
			
			/**
			*	@brief string
			*/
			private static $server ;
			
			/**
			*	@brief string
			*/
			private static $user ;
			
			/**
			*	@brief string
			*/
			private static $password ;
			
			/**
			*	@brief string
			*/
			private static $db_name ;
			
			/**
			*	@brief mysqli|resource
			*/
			private static $link ;
			
			/**
			*	@brief array string
			*/
			private static $tables ;
			
			/**
			*	@brief Initialize variables, choose database and connect
			*	
			*	@param string $db database name, optional, default 'mat'
			*/
			public static function init( $db = 'schades' ) {
				if( include_once( 'settings/db/' . $db . '.php' ) ) {
					
					self::$server     = $server   ;
					self::$user       = $user     ;
					self::$password   = $password ;
					self::$db_name    = $db_name  ;
					self::$tables     = $tables   ;
					self::$link       =  mysql_connect( self::$server , self::$user , self::$password ) ;
					
					list( $a , $b , $c ) = sscanf( mysql_get_server_info( self::$link ) , '%i.%i.%i' ) ;
					if( $a > 4 || ( $a == 4 && $b > 1 ) || ( $a == 4 && $b == 1 && $c >= 3 ) ) {
						 mysql_close( self::$link ) ;
						 self::$link = new mysqli( self::$server , self::$user , self::$password , self::$db_name ) ;
					} else {
						mysql_select_db( self::$db_name , self::$link ) ;
					}
				}
			}
			
			/**
			*	@param string $table
			*	@param string|int $field optional, default 0
			*	@param bool $full optional, default false
			*	@return string field name
			*/
			public static function name( $table , $field = 0 ) {
				if( $field !== 0 ) {
					return self::$tables[ $table ][ 0 ] . '.' . self::$tables[ $table ][ $field ] ;
				} else {
					return self::$tables[ $table ][ $field ] ;
				}
			}
			
			/**
			*	@param string $var
			*	@return string escaped
			*/
			public static function escape( $var ) {
				if( self::$link instanceof 'mysqli' ) {
					return self::$link->real_escape_string( $var ) ;
				} else {
					return mysql_real_escape_string( $var , self::$link ) ;
				}
			}
			
			/**
			*	@param string $query
			*	@return resource|bool resultset
			*/
			public static function query( $query ) {
				if( self::$link instanceof 'mysqli' ) {
					return self::$link->query( $query ) ;
				} else {
					return mysql_query( $query , self::$link ) ;
				}
			}
			
			/**
			*	@param resource $resultset
			*	@return int|bool number of rows
			*/
			public static function count( $resultset ) {
				if( $resultset instanceof 'mysqli_result' && property_exists( $resultset , 'num_rows' ) ) {
					return $resultset->num_rows ;
				} else {
					return mysql_num_rows( $resultset ) ;
				}
			}
			
			/**
			*	@param resource $resultset
			*	@return array|bool row of $resultset
			*/
			public static function fetch( $resultset ) {
				if( $resultset instanceof 'mysqli_result' && method_exists( $resultset , 'fetch_assoc' ) ) {
					return $resultset->fetch_assoc() ;
				} else {
					return mysql_fetch_assoc( $resultset ) ;
				}
			}
			
			/**
			*	@param resource $resultset
			*	@return int|bool
			*/
			public static function insertId() {
				if( self::$link instanceof 'mysqli' ) {
					return self::$link->insert_id() ;
				} else {
					return mysql_insert_id( self::$link ) ;
				}
			}
			
			/**
			*	@brief Free the $resultset
			*	
			*	@param resource $resultset
			*	@return bool
			*/
			public static function free( $resultset ) {
				if( $resultset instanceof 'mysqli_result' && method_exists( $resultset , 'free' ) ) {
					return $resultset->free() ;
				} else {
					return mysql_free_result( $resultset ) ;
				}
			}
	
	}
	
?>