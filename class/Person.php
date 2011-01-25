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
	*	@file Person.php
	*	
	*	@author Michal Sudwoj <mswoj61@gmail.com>
	*	@copyright Michal Sudwoj
	*	@link http://www.sourceforge.com/projects/schades/
	*	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
	*	@version 0.8
	*/
	
	require_once( 'include.php' ) ;
	
	/**
	*	@brief Person class
	*/
	class Person extends SuperClass {
		
		/**
		*	@brief int
		*/
		protected $id ;
		
		/**
		*	@brief string
		*/
		protected $surname ;
		
		/**
		*	@brief string
		*/
		protected $first ;
		
		/**
		*	@brief string
		*/
		protected $phone ;
		
		/**
		*	@brief string
		*/
		protected $email ;
		
		/**
		*	@brief string
		*/
		protected $street1 ;
		
		/**
		*	@brief string
		*/
		protected $street2 ;
		
		/**
		*	@brief string
		*/
		protected $city ;
		
		/**
		*	@brief string
		*/
		protected $zip ;
		
		/**
		*	@brief string
		*/
		protected $title ;
		
		/**
		*	@brief Date
		*/
		protected $birth ;
		
		/**
		*	@brief string
		*/
		protected $teacher ;
		
		/**
		*	@brief string
		*/
		protected $type ;
		
		/**
		*	@brief string
		*/
		protected $ad_line ;
		
		/**
		*	@brief string
		*/
		protected $ad_block ;
		
		/**
		*	@brief Period
		*	@todo migrate pr to param
		*/
		protected $pr ;
		
		/**
		*	@brief string
		*/
		protected $lvl ;
		
		/**
		*	@brief array Absence
		*/
		protected $abs ;
		
		/**
		*	@brief array Excuse
		*/
		protected $excuses ;
		
		/**
		*	@brief array Form
		*/
		protected $forms ;
		
		/**
		*	@brief string
		*/
		protected $sqlForms ;
		
		/**
		*	@brief array Course
		*/
		protected $lessons ;
		
		/**
		*	@brief array Course
		*/
		protected $courses ;
		
		/**
		*	@brief array mixed
		*/
		protected $combined ;
		
		/**
		*	@brief array int
		*/
		protected $total ;
		
		/**
		*	@brief string
		*/
		protected $username ;
		
		/**
		*	@brief int
		*/
		protected $lower ;
		
		/**
		*	@brief int
		*/
		protected $upper ;
		
		/**
		*	@brief Constructor
		*	
		*	@param int $id
		*	@param Period $pr
		*/
		public function __construct( $id , Period $pr ) {
			$this->id = $id ;
			$this->pr = $pr ;
		}
		
		/**
		*	@brief Initialize and set basic variables
		*/
		private function getData() {
			$query = "SELECT * FROM " . DB::name( 'ad_ad' ) ;
			$query .= " WHERE " . DB::name( 'ad_ad' , 'ad_id' ) . " = " . DB::escape( $this->getId() ) . ' ;' ;
			if( $resultset = DB::query( $query ) ) {
				if( DB::count( $resultset ) == 1 ) {
					$answer = DB::fetch( $resultset ) ;
					
					$this->surname = $answer["surname"] ;
					$this->first = $answer["first_name"] ;
					$this->phone = $answer["phone"] ;
					$this->email = $answer["email"] ;
					$this->street1 = $answer["street1"] ;
					$this->street2 = $answer["street2"] ;
					$this->city = $answer["city"] ;
					$this->zip = $answer["zip"] ;
					$this->title = $answer["title"] ;
					
					$this->birth = new Date( $answer["birth_date"] ) ;
					
					$this->teacher = $answer["teacher_short"] ;
					$this->type = $answer["ad_type_id"] ;
					$this->lvl = $answer[ 'abs_level' ] ;
					
					if( $this->getType() == 8 ){
						$this->lower = 10 ;
						$this->upper = 16 ;
						if( $this->getForms( 0 )->getGrade() == 3 and $this->getPr()->getId() % 2 == 1 ) {
							$this->lower = 9 ;
							$this->upper = 14 ;
						}
					}
				}
				DB::free( $resultset ) ;
			}
		}
		
		/**
		*	@return int $id
		*/
		public function getId(){
			return $this->id ;
		}
		
		/**
		*	@return string $surname
		*/
		public function getSurname(){
			if( !isset( $this->surname ) ) {
				$this->getData() ;
			}
			return $this->surname ;
		}
		
		/**
		*	@return string $first
		*/
		public function getFirst(){
			if( !isset( $this->first ) ) {
				$this->getData() ;
			}return $this->first ;
		}
		
		/**
		*	@return string $phone
		*/
		public function getPhone(){
			if( !isset( $this->phone ) ) {
				$this->getData() ;
			}
			return $this->phone ;
		}
		
		/**
		*	@return string $email
		*/
		public function getEmail(){
			if( !isset( $this->email ) ) {
				$this->getData() ;
			}
			return $this->email ;
		}
		
		/**
		*	@return string $street1
		*/
		public function getStreet1(){
			if( !isset( $this->street1 ) ) {
				$this->getData() ;
			}
			return $this->street1 ;
		}
		
		/**
		*	@return string $street2
		*/
		public function getStreet2(){
			if( !isset( $this->street2 ) ) {
				$this->getData() ;
			}
			return $this->street2 ;
		}
		
		/**
		*	@return string $city
		*/
		public function getCity(){
			if( !isset( $this->city ) ) {
				$this->getData() ;
			}
			return $this->city ;
		}
		
		/**
		*	@return string $zip
		*/
		public function getZip(){
			if( !isset( $this->zip ) ) {
				$this->getData() ;
			}
			return $this->zip ;
		}
		
		/**
		*	@return string $title
		*/
		public function getTitle(){
			if( !isset( $this->title ) ) {
				$this->getData() ;
			}
			return $this->title ;
		}
		
		/**
		*	@return Date $birth
		*/
		public function getBirth(){
			if( !isset( $this->birth ) ) {
				$this->getData() ;
			}return $this->birth ;
		}
		
		/**
		*	@return Person $teacher
		*/
		public function getTeacher(){
			if( !isset( $this->teacher ) ) {
				$this->getData() ;
			}
			return $this->teacher ;
		}
		
		/**
		*	@return int $type
		*/
		public function getType(){
			if( !isset( $this->type ) ) {
				$this->getData() ;
			}return $this->type ;
		}
		
		/**
		*	@return string $ad_line
		*/
		public function getAd_line(){
			if( !isset( $this->ad_line ) ) {
				if( !( isset( $this->street1 ) or isset( $this->street2 ) or isset( $this->zip ) or isset( $this->city ) ) ) {
					$this->getData() ;
				}
				$strt_line = $this->street1 ;
				if( $this->street2 != "" ) {
					$strt_line .= ", " . $this->street2 ;
				}
				$place = $this->zip . " " . $this->city ;
				$this->ad_line = $strt_line . ", " . $place ;
			}
			return $this->ad_line ;
		}
		
		/**
		*	@return string $ad_block
		*/
		public function getAd_block(){
			if( !isset( $this->ad_block ) ) {
				if( !( isset( $this->street1 ) or isset( $this->street2 ) or isset( $this->zip ) or isset( $this->city ) ) ) {
					$this->getData() ;
				}
				$strt_block = $this->street1 ;
				if( $this->street2 != "" ) {
					$strt_block .= ",<br />" . $this->street2 ;
				}
				$place = $this->zip . " " . $this->city ;
				$this->ad_block = $strt_block . ",\n" . $place ;
			}
			
			return $this->ad_block ;
		}
		
		/**
		*	@return Period $pr
		*/
		public function getPr(){
			return $this->pr ;
		}
		
		/**
		*	@return int $lvl
		*/
		public function getLvl() {
			if( !isset( $this->lvl ) ) {
				$this->getData() ;
			}
			
			return $this->lvl ;
		}
		
		/**
		*	@return string $username
		*/
		public function getUsername() {
			if( !isset( $this->username ) ) {
				$query = 'SELECT ' . DB::name( 'login' , 'username' ) . ' FROM ' . DB::name( 'login' ) ;
				$query .= ' WHERE ' . DB::name( 'login' , 'ad_id' ) . ' = ' . DB::escape( $this->getId() ) . ' ;' ;
				if( $resultset = DB::query( $query ) ) {
					if( DB::count( $resultset ) == 1 ) {
						$row = DB::fetch( $resultset ) ;
						$this->username = $row[ 'username' ] ;
					}
				}
			}
			
			return $this->username ;
		}
		
		/**
		*	@return int $lower
		*/
		public function getLower() {
			if( !isset( $this->lower ) ) {
				$this->getData() ;
			}
			
			return $this->lower ;
		}
		
		/**
		*	@return int $upper
		*/
		public function getUpper() {
			if( !isset( $this->upper ) ) {
				$this->getData() ;
			}
			
			return $this->upper ;
		}
		
		/**
		*	@param int|NULL $week optional, default NULL
		*	@param int|NULL $day optional, default NULL
		*	@return array Absence $abs
		*	@todo use getExcuses()
		*/
		public function getAbs( $week = NULL , $day = NULL ) {	//use getExcuses
			if( !isset( $this->abs[ 0 ] ) ) {
				$abs = array() ;
				
				$query = "SELECT " . DB::name( 'abs_abs' , 'abs_id' ) . " FROM " . DB::name( 'abs_abs' ) ;
				$query .= " WHERE " . DB::name( 'abs_abs' , 'ad_id' ) . " = " . DB::escape( $this->getId() ) ;
				$query .= " ORDER BY " . DB::name( 'abs_abs' , 'date' ) . " , " . DB::name( 'abs_abs' , 'lesson' ) . " ;" ;
				if( $resultset = DB::query( $query ) ) {
					while( $row = DB::fetch( $resultset ) ) {
						$absence = new Absence( $row['abs_id'] ) ;
						$abs[ $absence->getWeek() * 5 * 12 + $absence->getDay() * 12 + $absence->getLesson() ] = $absence ;
					}
					DB::free( $resultset ) ;
				}
				
				$query = "SELECT * FROM " . DB::name( 'abs_excuse' ) ;
				$query .= " WHERE " . DB::name( 'abs_excuse' , 'ad_id' ) . " = " . DB::escape( $this->getId() ) ;
				$query .= " ORDER BY " . DB::name( 'abs_excuse' , 'from_week' ) . " , " . DB::name( 'abs_excuse' , 'from_day' ) . " , " . DB::name( 'abs_excuse' , 'from_lesson' ) . " , " . DB::name( 'abs_excuse' , 'to_week' ) . " , " . DB::name( 'abs_excuse' , 'to_day' ) . " , " . DB::name( 'abs_excuse' , 'to_lesson' ) . " ;" ;
				if( $resultset = DB::query( $query ) ) {
					while( $row = DB::fetch( $resultset ) ) {
						$from = $row['from_lesson'] + 12 * $row['from_day'] + 5 * 12 * $row['from_week'] ;
						$to = $row['to_lesson'] + 12 * $row['to_day'] + 5 * 12 * $row['to_week'] ;
						$duration = 1 + $to - $from ;
						$value = $row['value'] ;
						
						for( $w = $row['from_week'] , $d = $row['from_day'] , $l = $row['from_lesson'] ; $l + 12 * $d + 5 * 12 * $w <= $to ; ) {
							$excuse = Absence::AbsExcuse( $w , $d , $l , $value , $duration ) ;
							$abs[ $l + 12 * $d + 5 * 12 * $w ] = $excuse ;
							$l++ ;
							if( $l == 13 ) {
								$l = 1 ;
								$d++ ;
								if( $d == 6 ) {
									$d = 1 ;
									$w++ ;
								}
							}
						}
					}
					DB::free( $resultset ) ;
				}
				$this->abs[ 0 ] = $abs ;
			}
			
			if( $week !== NULL ) {
				if( !isset( $this->abs[ $week ][ 0 ] ) ) {
					$temp = array() ;
					$abs = $this->abs[ 0 ] ;
					foreach( $abs as $absence ) {
						if( $absence->getWeek() == $week ) {
							$temp[ $absence->getDay() * 12 + $absence->getLesson() ] = $absence ;
						}
						unset( $absence ) ;
					}
					$this->abs[ $week ][ 0 ] = $temp ;
					unset( $temp ) ;
					
					if( $day !== NULL ) {
						if( !isset( $this->abs[ $week ][ $day ] ) ) {
							$temp = array() ;
							$abs = $this->abs[ $week ][ 0 ] ;
							foreach( $abs as $absence ) {
								if( $absence->getDay() == $day ) {
									$temp[ $absence->getLesson() ] = $absence ;
								}
								unset( $absence ) ;
							}
							$this->abs[ $week ][ $day ] = $temp ;
							unset( $temp ) ;
						}
						return $this->abs[ $week ][ $day ] ;
					}
					
					return $this->abs[ $week ][ 0 ] ;
				}
			}
			
			return $this->abs[ 0 ] ;
		}
		
		/**
		*	@return array Excuse $excuses
		*/
		public function getExcuses() {
			if( !isset( $this->excuses ) ) {
				$excuses = array() ;
				$query = "SELECT " . DB::name( 'abs_excuse' , 'excuse_id' ) ;
				$query .= " FROM " . DB::name( 'abs_excuse' ) ;
				$query .= " WHERE " . DB::name( 'abs_excuse' , 'ad_id' ) . " = " . DB::escape( $this->getId() ) ;
				$query .= " ORDER BY " . DB::name( 'abs_excuse' , 'from_week' ) . " , " . DB::name( 'abs_excuse' , 'from_day' ) . " , " . DB::name( 'abs_excuse' , 'from_lesson' ) . " , " . DB::name( 'abs_excuse' , 'to_week' ) . " , " . DB::name( 'abs_excuse' , 'to_day' ) . " , " . DB::name( 'abs_excuse' , 'to_lesson' ) . " ;" ;
				if( $resultset = DB::query( $query ) ) {
					while( $row = DB::fetch( $resultset ) ) {
						$excuses[] = new Excuse( $row[ 'excuse_id' ] , $this->getPr() ) ;
					}
					DB::free( $resultset ) ;
				}
				$this->excuses = $excuses ;
			}
			
			return $this->excuses ;
		}
		
		/**
		*	@param int|NULL $i Array index, optional, default NULL
		*	@return array Form $forms
		*	@return Form $forms[ $i ]
		*/
		public function getForms( $i = NULL ) {
			if( !isset( $this->forms ) ) {
				$forms = array() ;
				
				$query = "SELECT " . DB::name( 'pg' , 'pg' ) ;
				$query .= " FROM " . DB::name( 'pg' ) ;
				$query .= " JOIN " . DB::name( 'ad_pg' ) . " ON " . DB::name( 'pg' , 'pg_id' ) . " = " . DB::name( 'ad_pg' , 'pg_id' ) ;
				$query .= " JOIN " . DB::name( 'ad_ad' ) . " ON " . DB::name( 'ad_pg' , 'ad_id' ) . " = " . DB::name( 'ad_ad' , 'ad_id' ) ;
				$query .= " WHERE " . DB::name( 'pg' , 'class' ) . " > 0" ;
				$query .= " AND " . DB::name( 'ad_ad' , 'ad_id' ) . " = " . DB::escape( $this->getId() ) ;
				$query .= " AND ( " . DB::name( 'pg' , 'ad_pr' ) . " = " . DB::escape( $this->getPr()->getId() ) ;
				$query .= " OR " . DB::name( 'pg' , 'ad_pr' ) . " = " . DB::escape( $this->getPr()->getId() - 1 ) . " )" ;
				$query .= " ORDER BY " . DB::name( 'pg' , 'pg' ) . " ; " ;
				if( $resultset = DB::query( $query ) ) {
					while( $row = DB::fetch( $resultset ) ) {
						$forms[] = new Form( $row['pg'] , $this->getPr() ) ;
					}
					DB::free( $resultset ) ;
				}
				$this->forms = $forms ;
			}
			if( $i === NULL ) {
				return $this->forms ;
			} else {
				return $this->forms[ $i ] ;
			}
		}
		
		/**
		*	@return string $sqlForms
		*/
		public function getSqlForms() {
			if( !isset( $this->sqlForms ) ) {
				$result = '' ;
				$forms = $this->getForms() ;
				foreach( $forms as $form ) {
					$result .= $form->getSql() . '|' ;
				}
				$result = trim( $result , '|' ) ;
				$this->sqlForms = $result ;
			}
			
			return $this->sqlForms ;
		}
		
		/**
		*	@param int|NULL $week optional, default NULL
		*	@param int|NULL $day optional, default NULL
		*	@return array Course $lessons
		*/
		public function getLessons( $week = NULL , $day = NULL ) {
			if( !isset( $this->lessons[ 0 ] ) ) {
				$lessons = array() ;
				
				$query = "SELECT " . DB::name( 'tt_tt' , 'week' ) . " , " . DB::name( 'tt_tt' , 'day' ) . " , " . DB::name( 'tt_tt' , 'lesson' ) . " , " . DB::name( 'co_co' , 'name' ) ;
				$query .= " FROM " . DB::name( 'tt_tt' ) ;
				$query .= " JOIN " . DB::name( 'tt_mapping' ) . " ON " . DB::name( 'tt_tt' , 'subject' ) . " = " . DB::name( 'tt_mapping' , 'tt_short_name' ) ;
				$query .= " JOIN " . DB::name( 'co_co' ) . " ON " . DB::name( 'tt_mapping' , 'co_type_id' ) . " = " . DB::name( 'co_co' , 'co_type_id' ) ;
				$query .= " JOIN " . DB::name( 'ad_co' ) . " ON " . DB::name( 'co_co' , 'co_id' ) . " = " . DB::name( 'ad_co' , 'co_id' ) ;
				$query .= " JOIN " . DB::name( 'ad_ad' ) . " ON " . DB::name( 'ad_co' , 'ad_id' ) . " = " . DB::name( 'ad_ad' , 'ad_id' ) ;
				$query .= " WHERE " . DB::name( 'ad_ad' , 'ad_id' ) . " = " . DB::escape( $this->getId() ) ;
				$query .= " AND " . DB::name( 'tt_tt' , 'class' ) . " REGEXP '" . DB::escape( $this->getSqlForms() ) . "'" ;
				$query .= " AND " . DB::name( 'co_co' , 'name' ) . " REGEXP '" . DB::escape( $this->getSqlForms() ) . "'" ;
				//$query .= " AND " . DB::name( 'ad_co' , 'ad_co_type' ) . " = 1" ;
				$query .= " AND ( " . DB::name( 'co_co' , 'pr_id' ) . " = " . DB::escape( $this->pr->getId() ) ;
				$query .= " OR " . DB::name( 'co_co' , 'pr_id' ) . " = " . DB::escape( $this->pr->getId() - 1 ) . " )" ;
				$query .= " ORDER BY " . DB::name( 'tt_tt' , 'week' ) . " , " . DB::name( 'tt_tt' , 'day' ) . " , " . DB::name( 'tt_tt' , 'lesson' ) . " ;" ;
				if( $resultset = DB::query( $query ) ) {
					while( $row = DB::fetch( $resultset ) ) {
						$lessons[ $row['week'] * 5 * 12 + $row['day'] * 12 + $row['lesson'] ] = new Course( $row['name'] , $this->getPr() ) ;
					}
					DB::free( $resultset ) ;
				}
				$this->lessons[ 0 ] = $lessons ;
			}
			
			if( $week !== NULL ) {
				if( !isset( $this->lessons[ $week ][ 0 ] ) ) {
					$temp = array() ;
					foreach( $lessons as $key => $value ) {
						if( ( (int) ( $key / ( 5 * 12 ) ) ) == $week ) {
							$temp[ $key % ( 5 * 12 + 1 ) ] = $value ;
						}
						unset( $key ) ;
						unset( $value ) ;
					}
					$this->lessons[ $week ][ 0 ] = $temp ;
				}
				
				if( $day !== NULL ) {
					if( !isset( $this->lessons[ $week ][ $day ] ) ) {
						$temp = array() ;
						foreach( $lessons as $key => $value ) {
							if( ( (int) ( $key / 12 ) ) == $day ) {
								$temp[ $key % ( 12 + 1 ) ] = $value ;
							}
							unset( $key ) ;
							unset( $value ) ;
						}
						$this->lessons[ $week ][ $day ] = $temp ;
					}
					
					return $this->lessons[ $week ][ $day ] ;
				}
				
				return $this->lessons[ $week ][ 0 ] ;
			}
			
			return $this->lessons[ 0 ] ;
		}
		
		/**
		*	@return array Course $courses
		*/
		public function getCourses() {
			if( !isset( $this->courses ) ) {
				$courses = array() ;
				
				$query = "SELECT DISTINCT " . DB::name( 'co_co' , 'name' ) ;
				$query .= " FROM " . DB::name( 'co_co' ) ;
				$query .= " JOIN " . DB::name( 'ad_co' ) . " ON " . DB::name( 'co_co' , 'co_id' ) . " = " . DB::name( 'ad_co' , 'co_id' ) ;
				$query .= " JOIN " . DB::name( 'ad_ad' ) . " ON " . DB::name( 'ad_co' , 'ad_id' ) . " = " . DB::name( 'ad_ad' , 'ad_id' ) ;
				$query .= " WHERE " . DB::name( 'ad_ad' , 'ad_id' ) . " = " . DB::escape( $this->getId() ) ;
				$query .= " AND " . DB::name( 'co_co' , 'name' ) . " REGEXP '" . DB::escape( $this->getSqlForms() ) . "'" ;
				$query .= " AND ( " . DB::name( 'co_co' , 'pr_id' ) . " = " . DB::escape( $this->pr->getId() ) ;
				$query .= " OR " . DB::name( 'co_co' , 'pr_id' ) . " = " . DB::escape( $this->pr->getId() - 1 ) . " )" ;
				$query .= " ORDER BY " . DB::name( 'co_co' , 'name' ) . " ;" ;
				if( $resultset = DB::query( $query ) ) {
					while( $row = DB::fetch( $resultset ) ) {
						$courses[] = new Course( $row['name'] , $this->getPr() ) ;
					}
					DB::free( $resultset ) ;
				}
				$this->courses = $courses ;
			}
			return $this->courses ;
		}
		
		/**
		*	@return array $combined
		*/
		public function getCombined( $week = NULL , $day = NULL ) {
			if( !isset( $this->combined[ 0 ] ) ) {
				$combined = array() ;
				foreach( $this->pr->getAbs_weeks() as $w ) {
					for( $d = 1 ; $d <= 5 ; $d++ ) {
						for( $l = 1 ; $l <= 12 ; $l++ ) {
							$combined[ $w * 5 * 12 + $d * 12 + $l ] = '*' ;
						}
					}
				}
				
				$lessons = $this->getLessons() ;
				$absences = $this->getAbs() ;
				
				foreach( $lessons as $key => $value ) {
					$combined[ $key ] = '?' ;
					unset( $key ) ;
					unset( $value ) ;
				}
				
				foreach( $absences as $abs ) {
					$combined[ $abs->getWeek() * 5 * 12 + $abs->getDay() * 12 + $abs->getLesson() ] = $abs->getType() ;
					unset( $abs ) ;
				}
				$this->combined[ 0 ] = $combined ;
			}
			
			if( $week !== NULL ) {
				if( !isset( $this->combined[ $week ][ 0 ] ) ) {
					$temp = array() ;
					$combined = $this->combined[ 0 ] ;
					foreach( $combined as $key => $value ) {
						$temp_key = $key - $week * 5 * 12 ;
							if( 72 >= $temp_key and $temp_key >= 13 ) {
								$temp[ $temp_key ] = $value ;
							}
							unset( $temp_key ) ;
							unset( $key ) ;
							unset( $value ) ;
						}
					$this->combined[ $week ][ 0 ] = $temp ;
					unset( $temp ) ;
				}
				
				if( $day !== NULL ) {
					if( !isset( $this->combined[ $week ][ $day ] ) ) {
						$temp = array() ;
						$combined = $this->combined[ $week ][ 0 ] ;
						foreach( $combined as $key => $value ) {
							$temp_key = $key - $day * 12 ;
							if( 12 >= $temp_key and $temp_key >= 1 ) {
								$temp[ $temp_key ] = $value ;
							}
							unset( $temp_key ) ;
							unset( $key ) ;
							unset( $value ) ;
						}
						$this->combined[ $week ][ $day ] = $temp ;
						unset( $temp ) ;
					}
					
					return $this->combined[ $week ][ $day ] ;
				}
				
				return $this->combined[ $week ][ 0 ] ;
			}
				
			return $this->combined[ 0 ] ;
		}
		
		/**
		*	@return array $total[ 0 ]
		*/
		public function getTotal() {
			if( !isset( $this->total[ 0 ] ) ) {
				$weeks = $this->pr->getAbs_weeks() ;
				$result['late'] = 0 ;
				$result['abs'] = 0 ;
				$result['exc'] = 0 ;
				$result['tot'] = 0 ;
				$result['c_tot'] = 0 ;
				
				foreach( $weeks as $week ) {
					$data = $this->getTotal_week( $week ) ;
					$result[$week] = $data ;
					$result['late'] += $data['late'] ;
					$result['abs'] += $data['abs'] ;
					$result['exc'] += $data[ 'exc' ] ;
					$result['tot'] += $data['tot'] ;
					$result['c_tot'] += $data['c_tot'] ;
					unset( $week ) ;
				}
				
				$result['c_late'] = $result['late'] - 2 ;
				if( $result['c_late'] < 0 ) {
					$result['c_late'] = 0 ;
				}
				
				$result['c_tot'] = $result['c_tot'] - ( $result['late'] + $result['c_late'] ) / 2 ;
				
				if( $result[ 'c_tot' ] >= $this->getLower() ) {
					if( $result[ 'c_tot' ] >= $this->getUpper() ) {
						if( !Queue::addQueue( $this , $this->getForms( 0 )->getAdmin() , 415 , 0 , $this->getPr() ) ) {
							//throw Exception
						}
					} else {
						if( !Queue::search( $this , 401 , $this->getPr() , true ) ) {
							//throw
						}
						if( !Queue::search( $this , 415 , $this->getPr() , true ) ) {
							//throw
						}
						if( !Queue::search( $this , 416 , $this->getPr() , true ) ) {
							//throw
						}
						if( !Queue::addQueue( $this , $this->getForms( 0 )->getAdmin() , 409 , 0 , $this->getPr() ) ) {
							//throw Exception
						}
					}
				} else {
					Queue::search( $this , 409 , $this->getPr() , true ) ;
					Queue::search( $this , 410 , $this->getPr() , true ) ;
					Queue::search( $this , 411 , $this->getPr() , true ) ;
				}
				
				
				$this->total[ 0 ] = $result ;
			}
			
			return $this->total[ 0 ] ;
		}
		
		/**
		*	@param int $week
		*	@return array int $total[ $week ]
		*/
		public function getTotal_week( $week ) {
			if( !isset( $this->total[ $week ] ) ) {
				$absences = $this->getCombined( $week ) ;
				$blocks[ 0 ] = '' ;
				$j = 0 ;
				for( $i = 13 ; $i < 73 ; $i++ ) {
					switch ( $absences[ $i ] ) {
						case '.' :
						case '?' :
						case '*' :
						case false :
							break ;
						case 'o' :
							$blocks[ $j ] = 'o' ;
							$j++ ;
							break ;
						case 'x' :
						case 'p' :
						case ( Printer::char1( $absences[ $i ] ) == 'e' ) :
						default :
							$blocks[ $j ] .= $absences[ $i ] . ':' ;
							$i++ ;
							switch ( $absences[ $i ] ) {
								case '.' :
									break ;
								case 'o' :
								case 'x' :
								case 'p' :
								case ( Printer::char1( $absences[ $i ] ) == 'e' ) :
								case '?' :
								case '*' :
								case false :
								default :
									do {
										$blocks[ $j ] .= $absences[ $i ] . ':' ;
										$i++ ;
									} while ( $i < 73 and !( $absences[ $i ] == '.' or $absences[ $i ] == 'o' ) ) ;
									if( $absences[ $i - 1 ] == 'o' ) {
										$blocks[ $j ] .= 'o' ;
									}
									$blocks[ $j ] = trim( $blocks[ $j ] , ':.?*' ) ;
									break ;
							}
							$j++ ;
							break ;
					}
				}
				
				unset( $absences ) ;
				
				$result[ 'late' ] = 0 ;
				$result[ 'abs' ] = 0 ;
				$result[ 'exc' ] = 0 ;
				$result[ 'tot' ] = 0 ;
				$result[ 'c_tot' ] = 0 ;
				$az = false ;
				
				foreach( $blocks as $block ) {
					$late = 0 ;
					$abs = 0 ;
					$exc = 0 ;
					$parts = explode( ':' , $block ) ;
					foreach( $parts as $part ) {
						switch( $part ) {
							case '' :
								break ;
							case 'o' :
								$late++ ;
								break ;
							case ( Printer::char1( $part ) == 'e' ) :
								$excused = explode( '#' , $part ) ;
								if( $excused[2] != 0 ) {
									$exc += $excused[1] / $excused[2] ;
								}
								break ;
							case 'x' :
							case 'p' :
							case '.' :
							case '?' :
							case false :
							default :
								$abs++ ;
								break ;
						}
					}
					$result[ 'late' ] += $late ;
					$result[ 'abs' ] += $abs ;
					$result[ 'exc' ] += $exc ;
					$result[ 'tot' ] += $exc + $abs + $late / 2 ;
					
					if( $abs + $late / 2 > 4 ) {
						$az = true ;
						$result[ 'c_tot' ] += $exc + 4 ;
					} else {
						$result[ 'c_tot' ] += $exc + $abs + $late / 2 ;
					}
				}
				
				if( $az or ( $this->getLvl() >= 2 and $result[ 'abs' ] > 0 ) ) {
					if( !Queue::addQueue( $this , $this , 404 , 0 , $this->getPr() , $week ) ) {
						//throw Exception
					}
					if( !Queue::addQueue( $this , $this->getForms( 0 )->getAdmin() , 405 , 0 , $this->getPr() , $week ) ) {
						//throw Exception
					}
				} else {
					if( !Queue::search( $this , 404 , $this->getPr() , true , $week ) ) {
						//throw
					}
					if( !Queue::search( $this , 405 , $this->getPr() , true , $week ) ) {
						//throw
					}
				}
				
				$this->total[ $week ] = $result ;
			}
			
			return $this->total[ $week ] ;
			
		}
		
		/**
		*	@return array Queue
		*/
		public function getQueue() {
			$queue = array() ;
			$query = 'SELECT ' . DB::name( 'queue' , 'queue_id' ) . ' FROM ' . DB::name( 'queue' ) ;
			$query .= ' WHERE ' . DB::name( 'queue' , 'to' ) . ' = ' . DB::escape( $this->getId() ) . ' ;' ;
			if( $resultset = DB::query( $query ) ) {
				while( $row = DB::fetch( $resultset ) ) {
					$queue[] = new Queue( $row[ 'queue_id' ] ) ;
				}
				DB::free( $resultset ) ;
			}
			
			return $queue ;
		}
		
		
		/**
		 *	@brief promotes to next abs_level if possible
		 *	@param int $lvl
		 *	@return bool success/failure
		 */
		public function promote( $lvl = NULL ) {
			if( $lvl === NULL ) {
				$lvl = $this->getLvl() + 1 ;
			}
			if( $lvl > 3 ) {
				$lvl = 3 ;
			} elseif( $lvl < 1 ) {
				$lvl = 1 ;
			}
			$lvl = (int) $lvl ;
			if( $lvl == $this->getLvl() ) {
				return false ;
			} else {
				$query = 'UPDATE ' . DB::name( 'ad_ad' ) ;
				$query .= ' WHERE ' . DB::name( 'ad_ad' , 'ad_id' ) . ' = ' . DB::escape( $this->getId() ) ;
				$query .= ' SET ' . DB::name( 'ad_ad' , 'abs_level' ) . ' = ' . DB::escape( $lvl ) . ' ;' ;
				if( DB::query( $query ) ) {
					$this->getData() ;
					return true ;
				} else {
					//throw error
					return false ;
				}
			}
		}
		
		/**
		*	@param array $combined
		*	@param array string $abs
		*	@return array
		*/
		public static function combine_course( $combined , $abs ) {
			foreach( $combined as $key => &$value ) {
				$value = '?' ;
				if( isset( $abs[ $key ] ) ) {
					$value = $abs[ $key ] ;
				}
			}
			
			return $combined ;
		}
		
		/**
		*	@param int $id
		*	@return bool
		*/
		public static function isstudent( $id ) {
			$query = 'SELECT * FROM ' . DB::name( 'ad_ad' ) ;
			$query .= ' WHERE ' . DB::name( 'ad_ad' , 'ad_id' ) . ' = ' . DB::escape( $id ) ;
			$query .= ' AND ' . DB::name( 'ad_ad' , 'ad_type_id' ) . ' = 8' ;
			
			if( $resultset = DB::query( $query ) ) {
				if( DB::count( $resultset ) > 0 ) {
					return true ;
				}
			}
			return false;
			
		}
		
	}
?>
