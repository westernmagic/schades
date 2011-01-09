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
	
	/**
	*	@brief DB class
	*	
	*	@details Used to connect and query a database
	*	
	*/
	abstract class DB {
			
			/**
			*	@breif string
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
			*	@breif string
			*/
			private static $db_name ;
			
			/**
			*	@brief resource
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
				require_once( './settings/db/' . $db . '.php' ) ;
				
				self::$server = $server ;
				self::$user = $user ;
				self::$password = $password ;
				self::$db_name = $db_name ;
				self::$tables = $tables ;
				
				self::$link = mysql_pconnect( self::$server , self::$user , self::$password ) ;
				mysql_select_db( self::$db_name , self::$link ) ;
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
				return mysql_real_escape_string( $var , self::$link ) ;
			}
			
			/**
			*	@param string $query
			*	@return resource|bool resultset
			*/
			public static function query( $query ) {
				return mysql_query( $query , self::$link ) ;
			}
			
			/**
			*	@param resource $resultset
			*	@return int|bool number of rows
			*/
			public static function count( $resultset ) {
				return mysql_num_rows( $resultset ) ;
			}
			
			/**
			*	@param resource $resultset
			*	@return array|bool row of $resultset
			*/
			public static function fetch( $resultset ) {
				return mysql_fetch_assoc( $resultset ) ;
			}
			
			/**
			*	@param resource $resultset
			*	@return bool
			*/
			public static function insertId() {
				return mysql_insert_id( self::$link ) ;
			}
			
			/**
			*	@brief Free the $resultset
			*	
			*	@param resource $resultset
			*	@return bool
			*/
			public static function free( $resultset ) {
				return mysql_free_result( $resultset ) ;
			}
	
	}
	
	DB::init( 'schades' ) ;
?>