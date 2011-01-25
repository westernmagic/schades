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
	*	@file Absence.php
	*	
	*	@author Michal Sudwoj <mswoj61@gmail.com>
	*	@copyright Michal Sudwoj
	*	@link http://www.sourceforge.com/projects/schades/
	*	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
	*	@version 0.8
	*/
	
	require_once( 'include.php' ) ;
	
	/**
	*	@brief Absence class
	*/
	class Absence extends SuperClass {
		
		/**
		*	@brief int
		*/
		private $id ;
		
		/**
		*	@brief string
		*/
		private $type ;
		
		/**
		*	@brief Date
		*/
		private $date ;
		
		/**
		*	@brief int
		*/
		private $week ;
		
		/**
		*	@brief int
		*/
		private $day ;
		
		/**
		* @brief int
		*/
		private $lesson ;
		
		/**
		*	@brief Constructor
		*
		*	@param int $id
		*/
		public function __construct ( $id ) {
			$this->id = $id ;
		}
		
		/**
		*	@brief Initialize basic variables
		*/
		private function getData() {
			$query = "SELECT " . DB::name( 'abs_abs' , 'date' ) . " , " . DB::name( 'abs_abs' , 'week' ) . " , " . DB::name( 'abs_abs' , 'day' ) . " , " . DB::name( 'abs_abs' , 'lesson' ) . " , " . DB::name( 'abs_abs' , 'abs_type' ) ;
			$query .= " FROM " . DB::name( 'abs_abs' ) ;
			$query .= " WHERE " . DB::name( 'abs_abs' , 'abs_id' ) . " = " . DB::escape( $this->getId() ) . " ; " ;
			if( $resultset = DB::query( $query ) ) {
				if( DB::count( $resultset ) == 1 ) {
					$row = DB::fetch( $resultset ) ;
					
					$this->date = new Date( $row['date'] ) ;
					if( $row['week'] >= 1 and $row['week'] <= 52 and $row['day'] >= 1 and $row['day'] <= 5 ) {
						$this->week = $row['week'] ;
						$this->day = $row['day'] ;
					} elseif ( strtotime( $row['date'] ) >= strtotime( '000-00-00' ) ) {
						$this->week = $this->getDate()->getWeek() ;
						$this->day = $this->getDate()->getDay() ;
					} else {
						throw new ErrorException( 'Absence data error' , 802 , 1 , 'Absence.php' , 39 ) ;
					}
					
					$this->lesson = $row['lesson'] ;
					$this->type = $row['abs_type'] ;
				}
				DB::free( $resultset ) ;
			}
		}
		
		/**
		*	@return int $id
		*/
		public function getId() {
			return $this->id ;
		}
		
		/**
		*	@return string $type
		*/
		public function getType() {
			if( !isset( $this->type ) ) {
				$this->getData() ;
			}
			return $this->type ;
		}
		
		/**
		*	@return Date $date
		*/
		public function getDate() {
			if( !isset( $this->date ) ) {
				$this->getData() ;
			}
			return $this->date ;
		}
		
		/**
		*	@return int $week
		*/
		public function getWeek() {
			if( !isset( $this->week ) ) {
				$this->getData() ;
			}
			return $this->week ;
		}
		
		/**
		*	@return int $day
		*/
		public function getDay() {
			if( !isset( $this->day ) ) {
				$this->getData() ;
			}
			return $this->day ;
		}
		
		/**
		*	@return int $lesson
		*/
		public function getLesson() {
			if( !isset( $this->lesson ) ) {
				$this->getData() ;
			}
			return $this->lesson ;
		}
		
		/**
		*	@brief Sets the type
		*
		*	@deprecated never used
		*	@param string $var type
		*	@return bool
		*/
		public function setType( $var ) {
			$query = "UPDATE " . DB::name( 'abs_abs' ) ;
			$query .= " SET " . DB::name( 'abs_abs' , 'abs_type' ) . " = " . DB::escape( $var ) ;
			$query .= " WHERE " . DB::name( 'abs_abs' , 'abs_id' ) . " = " . DB::escape( $this->id ) . ' ;' ;
			if( DB::query( $query ) ) {
				$this->type = $var ;
				return true ;
			} else {
				return false ;
			}
		}
		
		/**
		*	@param int $week
		*	@param int $day
		*	@param int @lesson
		*	@param int $value
		*	@param int duration
		*	@return Absence Excuse dummy absence
		*/
		public static function AbsExcuse( $week , $day , $lesson , $value , $duration ) {
			$excuse = new Absence( 0 ) ;
			$excuse->week = $week ;
			$excuse->day = $day ; 
			$excuse->lesson = $lesson ;
			$excuse->type = 'e#' . $value . '#' . $duration ;
			
			return $excuse ;
		}
		
		/**
		*	@brief Inserts / updates an Absence
		*
		*	@param int $ad
		*	@param int $week
		*	@param int $day
		*	@param int $lesson
		*	@param string $type
		*	@param int $exec
		*	@return Absence
		*/
		public static function newAbsence( $ad , $week , $day , $lesson , $type , $exec ) {
			$query = 'INSERT INTO ' . DB::name( 'abs_abs' ) ;
			$query .= ' SET' ;
			$query .= ' ' . DB::name( 'abs_abs' , 'abs_id' ) . ' = DEFAULT ,' ;
			$query .= ' ' . DB::name( 'abs_abs' , 'ad_id' ) . ' = ' . DB::escape( $ad ) . ' ,' ;
			$query .= ' ' . DB::name( 'abs_abs' , 'week' ) . ' = ' . DB::escape( $week ) . ' ,' ;
			$query .= ' ' . DB::name( 'abs_abs' , 'day' ) . ' = ' . DB::escape( $day ) . ' ,' ;
			$query .= ' ' . DB::name( 'abs_abs' , 'lesson' ) . ' = ' . DB::escape( $lesson ) . ' ,' ;
			$query .= ' ' . DB::name( 'abs_abs' , 'abs_type' ) . ' = "' . DB::escape( $type ) . '"' ;
			$query .= ' ON DUPLICATE KEY UPDATE' ;
			$query .= ' ' . DB::name( 'abs_abs' , 'abs_type' ) . ' = "' . DB::escape( $type ) . '" ;' ;
			
			if( DB::query( $query ) ) {
				$id = DB::insertId() ;
				if( $id === false ) {
					return false ;
				} else{
					if( $id == 0 ) {
						$query = 'SELECT ' . DB::name( 'abs_abs' , 'abs_id' ) ;
						$query .= ' FROM ' . DB::name( 'abs_abs' ) ;
						$query .= ' WHERE ' . DB::name( 'abs_abs' , 'ad_id' ) . ' = ' . DB::escape( $ad ) ;
						$query .= ' AND ' . DB::name( 'abs_abs' , 'week' ) . ' = ' . DB::escape( $week ) ;
						$query .= ' AND ' . DB::name( 'abs_abs' , 'day' ) . ' = ' . DB::escape( $day ) ;
						$query .= ' AND ' . DB::name( 'abs_abs' , 'lesson' ) . ' = ' . DB::escape( $lesson ) . ' ;' ;
							
						if( $resultset = DB::query( $query ) ) {	
							$row = DB::fetch( $resultset ) ;
							DB::free( $resultset ) ;
							
							$id = $row['abs_id'] ;
						}
					}
					
					$query = 'INSERT INTO ' . DB::name( 'abs_log_abs' ) ;
					$query .= ' SET' ;
					$query .= ' ' . DB::name( 'abs_log_abs' , 'log_abs_id' ) . ' = DEFAULT ,' ;
					$query .= ' ' . DB::name( 'abs_log_abs' , 'abs_id' ) . ' = ' . DB::escape( $id ) . ' ,' ;
					$query .= ' ' . DB::name( 'abs_log_abs' , 'after') . ' = "' . DB::escape( $type ) . '" ,' ;
					$query .= ' ' . DB::name( 'abs_log_abs' , 'executor_id' ) . ' = ' . DB::escape( $exec ) . ' ,' ;
					$query .= ' ' . DB::name( 'abs_log_abs' , 'date_time' ) . ' = DEFAULT ;' ;
					
					if( DB::query( $query ) ) {
						return new Absence( $id ) ;
					} else {
						throw new ErrorException( 'Absence logging error.' , 891 , 1 , 'Absence.php' , 135 ) ;
					}
				}
			} else {
				throw new ErrorException( 'Absence inserting error.' , 801 , 1 , 'Absence.php' , 139 ) ;
			}
			return false ;
			
		}
		
		/**
		*	@brief Filters arrays of Absence according to $week and $day
		*	
		*	@deprecated use Person::getAbs() instead
		*	@param array $abs Array of Absence
		*	@param int|NULL $week optional, default NULL
		*	@param int|NULL $day optional, default NULL
		*	@return array Filtered array of Absence
		*/
		public static function filter_abs( array $abs , $week = NULL , $day = NULL ) {
			if( $week !== NULL ) {
				$temp = array() ;
				foreach( $abs as $absence ) {
					if( $absence->getDate()->getWeek() == $week ) {
						$temp[ $absence->getDate()->getDay() * 12 + $absence->getLesson() ] = $absence ;
					}
					unset( $absence ) ;
				}
				$abs = $temp ;
				unset( $temp ) ;
				
				if( $day !== NULL ) {
					$temp = array() ;
					foreach( $abs as $absence ) {
						if( $absence->getDate()->getDay() == $day ) {
							$temp[ $absence->getLesson() ] = $absence ;
						}
						unset( $absence ) ;
					}
					$abs = $temp ;
					unset( $temp ) ;
				}
			}
			return $abs ;
		}
		
		
		/**
		*	@brief Transforms an array of Absence into an array of string types
		*	
		*	@param array $abs Array of Absence
		*	@return array Array of transformed string types
		*/
		public static function abs_type( $abs ) {
			$result = array() ;
			foreach( $abs as $key => $value ) {
				$result[ $key ] = $value->getType() ;
			}
			
			return $result ;
		}
		
	}

?>
