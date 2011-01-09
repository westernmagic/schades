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
	*	@file Form.php
	*	
	*	@author Michal Sudwoj <mswoj61@gmail.com>
	*	@copyright Michal Sudwoj
	*	@link http://www.sourceforge.com/projects/schades/
	*	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
	*	@version 0.8
	*/
	
	//require_once( 'settings.php' ) ;
	require_once( 'Person.php' ) ;
	require_once( 'Period.php' ) ;
	require_once( 'DB.php' ) ;
	
	/**
	*	@brief Form class
	*/
	class Form {
		
		/**
		*	@brief string
		*/
		private $id ;
		
		/**
		*	@brief Period
		*	@todo migrate pr to param
		*/
		private $pr ;
		
		/**
		*	@brief string
		*/
		private $norm ;
		
		/**
		*	@brief string
		*/
		private $sql ;
		
		/**
		*	@brief int
		*/
		private $grade ;
		
		/**
		*	@brief string
		*/
		private $name ;
		
		/**
		*	@brief string
		*/
		private $type ;
		
		/**
		*	@brief Person
		*/
		private $teacher ;
		
		/**
		*	@brief Person
		*/
		private $head ;
		
		/**
		*	@brief Person
		*/
		private $admin ;
		
		/**
		*	@brief array Person
		*/
		private $students ;
		
		/**
		*	@brief Constructor
		*	
		*	@param string $form
		*	@param Period $pr
		*	@todo migrate pr to param
		*/
		public function __construct( $form , Period $pr ) {
			$this->id = $form ;
			$this->pr = $pr ;
		}
		
		/**
		*	@brief Initialize basic variables
		*/
		private function getData() {
			$form = $this->id ;
			$form = str_replace( " " , "" , $form ) ;
			$form = str_replace( "_" , "" , $form ) ;
			$parts = str_split( $form ) ;
			
			$this->grade = $parts[0] ;
			$this->name = $parts[1] ;
			$this->type = $parts[2] ;
			
			if( $this->type == "F" or $this->type == "D" ) {
				$this->norm = '_' . $this->grade . $this->name . ' ' . $this->type ;
				$this->sql = "_?" . $this->grade . $this->name . " ?[dfDF]" ;
			} elseif ( $this->type == "G" ) {
				$this->norm = $this->grade . $this->name . ' ' . $this->type ;
				$this->sql = $this->grade . $this->name . " ?[^dfDF]" ;
			}
		}
		
		/**
		*	@return string $id
		*/
		public function getId() {
			return $this->id ;
		}
		
		/**
		*	@return Period $pr
		*/
		public function getPr() {
			return $this->pr ;
		}
		
		/**
		*	@return string $norm
		*/
		public function getNorm() {
			if( !isset( $this->norm ) ) {
				$this->getData() ;
			}
			
			return $this->norm ;
		}
		
		/**
		*	@return string $sql
		*/
		public function getSql() {
			if( !isset( $this->sql ) ) {
				$this->getData() ;
			}
			
			return $this->sql ;
		}
		
		/**
		*	@return int $grade
		*/
		public function getGrade() {
			if( !isset( $this->grade ) ) {
				$this->getData() ;
			}
			
			return $this->grade ;
		}
		
		/**
		*	@return Person $teacher
		*/
		public function getTeacher() {
			if( !isset( $this->teacher ) ) {
				$teacher = false ;
				$query = "SELECT " . DB::name( 'ad_ad' , 'ad_id' ) ;	// ad_ad.ad_id = ad_pg.ad_id
				$query .= " FROM " . DB::name( 'ad_pg' ) ;
				$query .= " JOIN " . DB::name( 'pg' ) . " ON " . DB::name( 'ad_pg' , 'pg_id' ) . " = " . DB::name( 'pg' , 'pg_id' ) ;
				$query .= " JOIN " . DB::name( 'ad_ad' ) . " ON " . DB::name( 'ad_pg' , 'ad_id' ) . " = " . DB::name( 'ad_ad' , 'ad_id' ) ;	//for clearness - drop for optimization
				$query .= " WHERE " . DB::name( 'pg' , 'pg' ) . " REGEXP '" . DB::escape( $this->getSql() ) . "'" ;
				$query .= " AND " . DB::name( 'ad_pg' , 'ad_pg_type' ) . " = 3" ;
				$query .= " AND ( " . DB::name( 'pg' , 'ad_pr' ) . " = " . DB::escape( $this->pr->getId() ) ;
				$query .= " OR " . DB::name( 'pg' , 'ad_pr' ) . " = " . DB::escape( $this->pr->getId() - 1 ) . " ) ;" ;
				if( $resultset = DB::query( $query ) ) {
					if( DB::count( $resultset ) == 1 ) {
						$row = DB::fetch( $resultset ) ;
						$teacher = new Person( $row['ad_id'] , $this->getPr() ) ;
					} else {
						//throw exception
					}
					DB::free( $resultset ) ;
				} else {
					//throw exception
				}
				
				$this->teacher = $teacher ;
			}
			
			return $this->teacher ;
		}
		
		/**
		*	@return Person $head
		*/
		public function getHead() {
			if( !isset( $this->head ) ) {
				$head = false ;
				$query = "SELECT " . DB::name( 'ad_ad' , 'ad_id' ) ;	// ad_ad.ad_id = ad_pg.ad_id
				$query .= " FROM " . DB::name( 'ad_pg' ) ;
				$query .= " JOIN " . DB::name( 'pg' ) . " ON " . DB::name( 'ad_pg' , 'pg_id' ) . " = " . DB::name( 'pg' , 'pg_id' ) ;
				$query .= " JOIN " . DB::name( 'ad_ad' ) . " ON " . DB::name( 'ad_pg' , 'ad_id' ) . " = " . DB::name( 'ad_ad' , 'ad_id' ) ;	//for clearness - drop for optimization
				$query .= " WHERE " . DB::name( 'pg' , 'pg' ) . " REGEXP '" . DB::escape( $this->getSql() ) . "'" ;
				$query .= " AND " . DB::name( 'ad_pg' , 'ad_pg_type' ) . " = 4" ;
				$query .= " AND ( " . DB::name( 'pg' , 'ad_pr' ) . " = " . DB::escape( $this->pr->getId() ) ;
				$query .= " OR " . DB::name( 'pg' , 'ad_pr' ) . " = " . DB::escape( $this->pr->getId() - 1 ) . " ) ;" ;
				if( $resultset = DB::query( $query ) ) {
					if( DB::count( $resultset ) == 1 ) {
						$row = DB::fetch( $resultset ) ;
						$head = new Person( $row['ad_id'] , $this->getPr() ) ;
					} else {
						//throw exception
					}
					mysql_free_result( $resultset ) ;
				} else {
					//throw exception
				}
				$this->head = $head ;
			}
			
			return $this->head ;
		}
		
		/**
		*	@return Person $admin
		*/
		public function getAdmin() {
			if( !isset( $this->admin ) ) {
				$admin = false ;
				$query = "SELECT " . DB::name( 'ad_ad' , 'ad_id' ) ;	// ad_ad.ad_id = ad_pg.ad_id
				$query .= " FROM " . DB::name( 'ad_pg' ) ;
				$query .= " JOIN " . DB::name( 'pg' ) . " ON " . DB::name( 'ad_pg' , 'pg_id' ) . " = " . DB::name( 'pg' , 'pg_id' ) ;
				$query .= " JOIN " . DB::name( 'ad_ad' ) . " ON " . DB::name( 'ad_pg' , 'ad_id' ) . " = " . DB::name( 'ad_ad' , 'ad_id' ) ;	//for clearness - drop for optimization
				$query .= " WHERE " . DB::name( 'pg' , 'pg' ) . " REGEXP '" . DB::escape( $this->getSql() ) . "'" ;
				$query .= " AND " . DB::name( 'ad_pg' , 'ad_pg_type' ) . " = 100" ;
				$query .= " AND ( " . DB::name( 'pg' , 'ad_pr' ) . " = " . DB::escape( $this->pr->getId() ) ;
				$query .= " OR " . DB::name( 'pg' , 'ad_pr' ) . " = " . DB::escape( $this->pr->getId() - 1 ) . " ) ;" ;
				if( $resultset = DB::query( $query ) ) {
					if( DB::count( $resultset ) == 1 ) {
						$row = DB::fetch( $resultset ) ;
						$admin = new Person( $row['ad_id'] , $this->getPr() ) ;
					} else {
						//throw exception
					}
					mysql_free_result( $resultset ) ;
				} else {
					//throw exception
				}
				$this->admin = $admin ;
			}
			
			return $this->admin ;
		}
		
		/**
		*	@return array Person $students
		*/
		public function getStudents() {
			if( !isset( $this->students ) ) {
				$students = array() ;
				
				$query = "SELECT " . DB::name( 'ad_ad' , 'ad_id' ) ;	// ad_ad.ad_id = ad_pg.ad_id
				$query .= " FROM " . DB::name( 'ad_pg' ) ;
				$query .= " JOIN " . DB::name( 'pg' ) . " ON " . DB::name( 'ad_pg' , 'pg_id' ) . " = " . DB::name( 'pg' , 'pg_id' ) ;
				$query .= " JOIN " . DB::name( 'ad_ad' ) . " ON " . DB::name( 'ad_pg' , 'ad_id' ) . " = " . DB::name( 'ad_ad' , 'ad_id' ) ;	//for clearness - drop for optimization
				$query .= " WHERE " . DB::name( 'pg' , 'pg' ) . " REGEXP '" . DB::escape( $this->getSql() ) . "'" ;
				$query .= " AND " . DB::name( 'ad_pg' , 'ad_pg_type' ) . " = 1" ;
				$query .= " AND ( " . DB::name( 'pg' , 'ad_pr' ) . " = " . DB::escape( $this->getPr()->getId() ) ;
				$query .= " OR " . DB::name( 'pg' , 'ad_pr' ) . " = " . DB::escape( $this->getPr()->getId() - 1 ) . " )" ;
				$query .= " ORDER BY " . DB::name( 'ad_ad' , 'surname' ) . " , " . DB::name( 'ad_ad' , 'first_name' ) . " ;" ;
				if( $resultset = DB::query( $query ) ) {
					while( $row = DB::fetch( $resultset ) ) {
						$students[] = new Person( $row['ad_id'] , $this->getPr() ) ;
					}
					DB::free( $resultset ) ;
				} else {
					//throw exception
				}
				
				$this->students = $students ;
			}
			
			return $this->students ;
		}
	
	}

?>