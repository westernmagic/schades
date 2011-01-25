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
	*	@file Course.php
	*	
	*	@author Michal Sudwoj <mswoj61@gmail.com>
	*	@copyright Michal Sudwoj
	*	@link http://www.sourceforge.com/projects/schades/
	*	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
	*	@version 0.8
	*/
	
	require_once( 'include.php' ) ;
	
	/**
	*	@brief Course class
	*/
	class Course extends SuperClass {
		
		/**
		*	@brief string
		*/
		private $id ;
		
		/**
		*	@brief array Form
		*/
		private $forms ;
		
		/**
		*	@brief string
		*/
		private $sqlForms ;
		
		/**
		*	@brief string
		*/
		private $type ;
		
		/**
		*	@brief Period
		*	@todo migrate pr to param
		*/
		private $pr ;
		
		/**
		*	@brief array Person
		*/
		private $students ;
		
		/**
		*	@brief Person
		*/
		private $teacher ;
		
		/**
		*	@brief array bool
		*/
		private $lessons ;
		
		/**
		*	@brief array int
		*/
		private $count ;
		
		/**
		*	@brief Constructor
		*
		*	@param int $course
		*	@param Period $pr
		*	@todo migrate pr to param
		*/
		public function __construct( $course , Period $pr ) {
			$this->id = $course ;
			$this->pr = $pr ;
		}
		
		/**
		*	@return int $id
		*/
		public function getId() {
			return $this->id ;
		}
		
		/**
		*	@return array Form $forms
		*/
		public function getForms() {
			if( !isset( $this->forms ) ) {
				$parts = array_reverse( explode( "(" , $this->getId() ) ) ;
				$forms = explode( "/" , trim( $parts[ 0 ] , "()" ) ) ;
				$this->forms = array() ;
				foreach( $forms as $form ) {
					$this->forms[] = new Form( $form , $this->getPr() ) ;
				}
			}
			return $this->forms ;
		}
		
		/**
		*	@return string $sqlForms
		*/
		public function getSqlForms() {
			if( !isset( $this->sqlforms ) ) {
				$forms = $this->getForms() ;
				$sqlForms = '' ;
				foreach( $forms as $form ) {
					$sqlForms .= $form->getSql() . '|' ;
				}
				$sqlForms = trim( $sqlForms , "|" ) ;
				$this->sqlForms = $sqlForms ;
			}
			return $this->sqlForms ;
		}
		
		/**
		*	@return string $type
		*/
		public function getType() {
			if( !isset( $this->type ) ) {
				$type = substr( $this->getId() , 0 , 2 ) ;
				switch( $type ) {
					case "EF" :
						$this->type = "x" ;
						break ;
					case "FF" :
						$this->type = "#" ;
						break ;
					case "BG" :
					case "FK" :
					case "GF" :
					case "WF" :
					default :
						$this->type = "[^x#]" ;
						break ;
				}
			}
			return $this->type ;
		}
		
		/**
		*	@return Period $pr
		*	@todo migrate pr to param
		*/
		public function getPr() {
			return $this->pr ;
		}
		
		/**
		*	@return array Person $students
		*	@todo migrate pr to param
		*/
		public function getStudents() {
			if( !isset( $this->students ) ) {
				$students = array() ;
				
				$query = "SELECT " . DB::name( 'ad_ad' , 'ad_id' ) ;	//for clearness - optimization possible
				$query .= " FROM " . DB::name( 'ad_ad' ) ;
				$query .= " JOIN " . DB::name( 'ad_co' ) . " ON " . DB::name( 'ad_ad' , 'ad_id' ) . " = " . DB::name( 'ad_co' , 'ad_id' ) ;
				$query .= " JOIN " . DB::name( 'co_co' ) . " ON " . DB::name( 'ad_co' , 'co_id' ) . " = " . DB::name( 'co_co' , 'co_id' ) ;
				$query .= " WHERE " . DB::name( 'co_co' , 'name' ) . " = '" . DB::escape( $this->getId() ) . "'" ;
				$query .= " AND " . DB::name( 'ad_co' , 'ad_co_type' ) . " = 1 " ;
				$query .= " AND ( " . DB::name( 'co_co' , 'pr_id' ) . " = " . DB::escape( $this->pr->getId() ) ;
				$query .= " OR " . DB::name( 'co_co' , 'pr_id' ) . " = " . DB::escape( $this->pr->getId() - 1 ) . " )" ;
				$query .= " ORDER BY " . DB::name( 'ad_ad' , 'surname' ) . " ," . DB::name( 'ad_ad' , 'first_name' ) . " ;" ;
				if( $resultset = DB::query( $query ) ) {
					while( $row = DB::fetch( $resultset ) ) {
						$students[] = new Person( $row['ad_id'] , $this->getPr() ) ;
					}
					DB::free( $resultset ) ;
				}
				
				$this->students = $students ;
			}
			return $this->students ;
		}
		
		/**
		*	@return Person $teacher
		*	@todo migrate pr to param
		*/
		public function getTeacher() {
			if( !isset( $this->teacher ) ) {
				$query = "SELECT " . DB::name( 'ad_ad' , 'ad_id' ) ;	//for clearness - optimization possible
				$query .= " FROM " . DB::name( 'ad_ad' ) ;
				$query .= " JOIN " . DB::name( 'ad_co' ) . " ON " . DB::name( 'ad_ad' , 'ad_id' ) . " = " . DB::name( 'ad_co' , 'ad_id' ) ;
				$query .= " JOIN " . DB::name( 'co_co' ) . " ON " . DB::name( 'ad_co' , 'co_id' ) . " = " . DB::name( 'co_co' , 'co_id' ) ;
				$query .= " WHERE " . DB::name( 'co_co' , 'name' ) . " = '" . DB::escape( $this->getId() ) . "'" ;
				$query .= " AND " . DB::name( 'ad_co' , 'ad_co_type' ) . " = 2 " ;
				$query .= " AND ( " . DB::name( 'co_co' , 'pr_id' ) . " = " . DB::escape( $this->pr->getId() ) ;
				$query .= " OR " . DB::name( 'co_co' , 'pr_id' ) . " = " . DB::escape( $this->pr->getId() - 1 ) . " ) ;" ;
				if( $resultset = DB::query( $query ) ) {
					if( DB::count( $resultset ) == 1 ) {
						$row = DB::fetch( $resultset ) ;
						$this->teacher = new Person( $row[ 'ad_id' ] , $this->getPr() ) ;
					} else {
						//throw exception
					}
					DB::free( $resultset ) ;
				} else {
					$this->teacher = false ;
				}
			}
			
			return $this->teacher ;
		}
		
		/**
		*	@param int|NULL $week optional, default NULL
		*	@param int|NULL $day optional, default NULL
		*	@return array bool $lessons
		*/
		public function getLessons( $week = NULL , $day = NULL ) {
			if( !isset( $this->lessons ) ) {
				$courses = array() ;
				
				$query = "SELECT DISTINCT " . DB::name( 'tt_tt' , 'week' ) . " , " . DB::name( 'tt_tt' , 'day' ) . " , " . DB::name( 'tt_tt' , 'lesson' ) ;
				$query .= " FROM " . DB::name( 'tt_tt' ) ;
				$query .= " JOIN " . DB::name( 'tt_mapping' ) . " ON " . DB::name( 'tt_tt' , 'subject' ) . " = " . DB::name( 'tt_mapping' , 'tt_short_name' ) ;
				$query .= " JOIN " . DB::name( 'co_co' ) . " ON " . DB::name( 'tt_mapping' , 'co_type_id' ) . " = " . DB::name( 'co_co' , 'co_type_id' ) ;
				$query .= " WHERE " . DB::name( 'co_co' , 'name' ) . " = '" . DB::escape( $this->getId() ) . "'" ;
				$query .= " AND " . DB::name( 'tt_tt' , 'class' ) ." REGEXP '" . DB::escape( $this->getSqlForms() ) . "'" ;
				$query .= " AND " . DB::name( 'tt_mapping' , 'tt_short_name' ) . " REGEXP '" . DB::escape( $this->getType() ) . "'" ;
				$query .= " ORDER BY " . DB::name( 'tt_tt' , 'week' ) . " , " . DB::name( 'tt_tt' , 'day' ) . " , " . DB::name( 'tt_tt' , 'lesson' ) . " ;" ;
				if( $resultset = DB::query( $query ) ) {
					while( $row = DB::fetch( $resultset ) ) {
						$courses[ $row["week"] * 5 * 12 + $row['day'] * 12 + $row['lesson'] ] = true ;
					}
					DB::free( $resultset ) ;
				}
				$this->lessons[ 0 ] = $courses ;
			}
			
			if( $week !== NULL ) {
				if( !isset( $this->lessons[ $week ][ 0 ] ) ) {
					$courses = $this->lessons[ 0 ] ;
					$temp = array() ;
					foreach( $courses as $key => $value ) {
						$temp_key = $key - $week * 5 * 12 ;
						if( 72 >= $temp_key and $temp_key >= 13 ) {
							$temp[ $temp_key ] = $value ;
						}
						unset( $temp_key ) ;
						unset( $key ) ;
						unset( $value ) ;
					}
					$this->lessons[ $week ][ 0 ] = $temp ;
					unset( $temp ) ;
				}
				
				if( $day !== NULL ) {
					if( !isset( $this->lessons[ $week ][ $day ] ) ) {
						$courses = $this->lessons[ $week ][ 0 ] ;
						$temp = array() ;
						foreach( $courses as $key => $value ) {
							$temp_key = $key - $day * 12 ;
							if( 13 >= $temp_key and $temp_key >= 1 ) {
								$temp[ $temp_key ] = $value ;
							}
							unset( $temp_key ) ;
							unset( $key ) ;
							unset( $value ) ;
						}
						$this->lessons[ $week ][ $day ] = $temp ;
						unset( $temp ) ;
					}
					return $this->lessons[ $week ][ $day ] ;
				}
				return $this->lessons[ $week ][ 0 ] ;
			}
			
			return $this->lessons[ 0 ] ;
		}
		
		/**
		*	@param int|NULL $week optional, default NULL
		*	@param int|NULL $day optional, default NULL
		*	@return array int $count
		*/
		public function getCount( $week = NULL , $day = NULL ) {
			if( !isset( $this->count[ 0 ] ) ) {
				$this->count[ 0 ] = count( $this->getLessons() ) ;
			}
			
			if( $week !==NULL ) {
				if( !isset( $this->count[ $week ][ 0 ] ) ) {
					$this->count[ $week ][ 0 ] = count( $this->getLessons( $week ) ) ;
				}
				
				if( $day !== NULL ) {
					if( !isset( $this->count[ $week ][ $day ] ) ) {
						$this->count[ $week ][ $day ] = count( $this->getLessons( $week , $day ) ) ;
					}
					
					return $this->count[ $week ][ $day ] ;
				}
				
				return $this->count[ $week ][ 0 ] ;
			}
			
			return $this->count[ 0 ] ;
		}
		
	}

?>
