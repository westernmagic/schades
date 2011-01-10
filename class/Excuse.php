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
	*	@file Excuse.php
	*	
	*	@author Michal Sudwoj <mswoj61@gmail.com>
	*	@copyright Michal Sudwoj
	*	@link http://www.sourceforge.com/projects/schades/
	*	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
	*	@version 0.8
	*/
	
	require_once( 'include.php' ) ;
	
	/**
	*	@brief Excuse class
	*/
	class Excuse extends SuperClass {
		
		/**
		*	@brief int
		*/
		private $id ;
		
		/**
		*	@brief Period
		*	@todo migrate pr to param
		*/
		private $pr ;
		
		/**
		*	@brief int
		*/
		private $from_week ;
		
		/**
		*	@brief int
		*/
		private $from_day ;
		
		/**
		*	@brief int
		*/
		private $from_lesson ;
		
		/**
		*	@brief int
		*/
		private $to_week ;
		
		/**
		*	@brief int
		*/
		private $to_day ;
		
		/**
		*	@brief int
		*/
		private $to_lesson ;
		
		/**
		*	@brief int
		*/
		private $value ;
		
		/**
		*	@brief string
		*/
		private $reason ;
		
		/**
		*	@brief Person
		*/
		private $granter ;
		
		/**
		*	@brief Date
		*/
		private $granted ;
		
		/**
		*	@brief Constructor
		*	
		*	@param int $id
		*	@param Period $pr
		*	@todo migrate pr to param
		*/
		public function __construct( $id , Period $pr ) {
			$this->id = $id ;
			$this->pr = $pr ;
		}
		
		/**
		*	@brief Initialize basic variables
		*/
		private function getData() {
			$query = 'SELECT * FROM ' . DB::name( 'abs_excuse' ) ;
			$query .= ' WHERE ' . DB::name( 'abs_excuse' , 'excuse_id' ) . ' = ' . DB::escape( $this->getId() ) . ' ;' ;
			if( $resultset = DB::query( $query ) ) {
				if( DB::count( $resultset ) == 1 ) {
					$row = DB::fetch( $resultset ) ;
					
					$this->from_week = $row[ 'from_week' ] ;
					$this->from_day = $row[ 'from_day' ] ;
					$this->from_lesson = $row[ 'from_lesson' ] ;
					$this->to_week = $row[ 'to_week' ] ;
					$this->to_day = $row[ 'to_day' ] ;
					$this->to_lesson = $row[ 'to_lesson' ] ;
					$this->value = $row[ 'value' ] ;
					$this->reason = $row[ 'reason' ] ;
					$this->granter = new Person( $row[ 'granted_by' ] , $this->getPr() ) ;
					$this->granted = new Date( $row[ 'date_time' ] ) ;
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
		*	@return Period $pr
		*	@todo migrate pr to param
		*/
		public function getPr() {
			return $this->pr ;
		}
		
		/**
		*	@return int $from_week
		*/
		public function getFrom_week() {
			if( !isset( $this->from_week ) ) {
				$this->getData() ;
			}
			return $this->from_week ;
		}
		
		/**
		*	@return int $from_day
		*/
		public function getFrom_day() {
			if( !isset( $this->from_day ) ) {
				$this->getData() ;
			}
			return $this->from_day ;
		}
		
		/**
		*	@return int $from_lesson
		*/
		public function getFrom_lesson() {
			if( !isset( $this->from_lesson ) ) {
				$this->getData() ;
			}
			return $this->from_lesson ;
		}
		
		/**
		*	@return int $to_week
		*/
		public function getTo_week() {
			if( !isset( $this->to_week ) ) {
				$this->getData() ;
			}
			return $this->to_week ;
		}
		
		/**
		*	@return int $to_day
		*/
		public function getTo_day() {
			if( !isset( $this->to_day ) ) {
				$this->getData() ;
			}
			return $this->to_day ;
		}
		
		/**
		*	@return int $to_lesson
		*/
		public function getTo_lesson() {
			if( !isset( $this->to_lesson ) ) {
				$this->getData() ;
			}
			return $this->to_lesson ;
		}
		
		/**
		*	@return int $value
		*/
		public function getValue() {
			if( !isset( $this->value ) ) {
				$this->getData() ;
			}
			return $this->value ;
		}
		
		/**
		*	@return string $reason
		*/
		public function getReason() {
			if( !isset( $this->reason ) ) {
				$this->getData() ;
			}
			return $this->reason ;
		}
		
		/**
		*	@return Person $granter
		*/
		public function getGranter() {
			if( !isset( $this->granter ) ) {
				$this->getData() ;
			}
			return $this->granter ;
		}
		
		/**
		*	@return Date $granted
		*/
		public function getGranted() {
			if( !isset( $this->granted ) ) {
				$this->getData() ;
			}
			return $this->granted ;
		}
		
		/**
		*	@brief Inserts / updates an Excuse
		*	
		*	@param Person $student
		*	@param int $from_week
		*	@param int $from_day
		*	@param int $from_lesson
		*	@param int $to_week
		*	@param int $to_day
		*	@param int $to_lesson
		*	@param int $value
		*	@param string $reason
		*	@param Person $granter
		*	@param int $id optional, default 0
		*	@todo return Excuse
		*/
		public static function newExcuse( Person $student , $from_week , $from_day , $from_lesson , $to_week , $to_day , $to_lesson , $value , $reason , Person $granter , $id = 0 ) {
			if( $id == 0 ) {
				$id = 'DEFAULT' ;
			}
			
			$query = 'INSERT INTO ' .DB::name( 'abs_excuse' ) ;
			$query .= ' SET' ;
			$query .= DB::name( 'abs_excuse' , 'excuse_id' ) . ' = ' . DB::escape( $id ) . ' ,' ;
			$query .= DB::name( 'abs_excuse' , 'ad_id' ) . ' = ' . DB::escape( $student->getId() ) . ' ,' ;
			$query .= DB::name( 'abs_excuse' , 'from_week' ) . ' = ' . DB::escape( $from_week ) . ' ,' ;
			$query .= DB::name( 'abs_excuse' , 'from_day' ) . ' = ' . DB::escape( $from_day ) . ' ,' ;
			$query .= DB::name( 'abs_excuse' , 'from_lesson' ) . ' = ' . DB::escape( $from_lesson , my_lin ) . ' ,' ;
			$query .= DB::name( 'abs_excuse' , 'to_week' ) . ' = ' . DB::escape( $to_week ) . ' ,' ;
			$query .= DB::name( 'abs_excuse' , 'to_day' ) . ' = ' . DB::escape( $to_day ) . ' ,' ;
			$query .= DB::name( 'abs_excuse' , 'to_lesson' ) . ' = ' . DB::escape( $to_lesson  ) . ' ,' ;
			$query .= DB::name( 'abs_excuse' , 'value' ) . ' = ' . DB::escape( $value ) . ' ,' ;
			$query .= DB::name( 'abs_excuse' , 'reason' ) . ' = "' . DB::escape( $reason ) . '" ,' ;
			$query .= DB::name( 'abs_excuse' , 'granted_by' ) . ' = ' . DB::escape( $granter->getId() ) . ' ,' ;
			$query .= DB::name( 'abs_excuse' , 'date_time' ) . ' = NOW()' ;
			$query .= ' ON DUPLICATE KEY UPDATE' ;
			$query .= DB::name( 'abs_excuse' , 'ad_id' ) . ' = ' . DB::escape( $student->getId() ) . ' ,' ;
			$query .= DB::name( 'abs_excuse' , 'from_week' ) . ' = ' . DB::escape( $from_week ) . ' ,' ;
			$query .= DB::name( 'abs_excuse' , 'from_day' ) . ' = ' . DB::escape( $from_day ) . ' ,' ;
			$query .= DB::name( 'abs_excuse' , 'from_lesson' ) . ' = ' . DB::escape( $from_lesson , my_lin ) . ' ,' ;
			$query .= DB::name( 'abs_excuse' , 'to_week' ) . ' = ' . DB::escape( $to_week ) . ' ,' ;
			$query .= DB::name( 'abs_excuse' , 'to_day' ) . ' = ' . DB::escape( $to_day ) . ' ,' ;
			$query .= DB::name( 'abs_excuse' , 'to_lesson' ) . ' = ' . DB::escape( $to_lesson  ) . ' ,' ;
			$query .= DB::name( 'abs_excuse' , 'value' ) . ' = ' . DB::escape( $value ) . ' ,' ;
			$query .= DB::name( 'abs_excuse' , 'reason' ) . ' = "' . DB::escape( $reason ) . '" ,' ;
			$query .= DB::name( 'abs_excuse' , 'granted_by' ) . ' = ' . DB::escape( $granter->getId() ) . ' ,' ;
			$query .= DB::name( 'abs_excuse' , 'date_time' ) . ' = NOW() ;' ;
			if( !DB::query( $query ) ) {
				//throw Exception
				return false ;
			}
			return true ;
		}
	
	}

?>