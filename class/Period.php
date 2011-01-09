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
	*	@file Period.php
	*	
	*	@author Michal Sudwoj <mswoj61@gmail.com>
	*	@copyright Michal Sudwoj
	*	@link http://www.sourceforge.com/projects/schades/
	*	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
	*	@version 0.8
	*/
	
	require_once( 'Date.php' ) ;
	require_once( 'DB.php' ) ;
	
	/**
	*	@brief Period class
	*	
	*	$details School or absence term.
	*/
	class Period {
		
		/**
		*	@brief int
		*/
		private $id ;
		
		/**
		*	@brief string
		*/
		private $name ;
		
		/**
		*	@brief Date
		*/
		private $abs_start ;
		
		/**
		*	@brief Date
		*/
		private $abs_end ;
		
		/**
		*	@brief Date
		*/
		private $ad_start ;
		
		/**
		*	@brief Date
		*/
		private $ad_end ;
		
		/**
		*	@brief array int
		*/
		private $abs_weeks ;
		
		/**
		*	@brief array int
		*/
		private $ad_weeks ;
		
		/**
		*	@brief Constructor
		*	
		*	@param int $id
		*/
		public function __construct( $id = 0 ) {
			if( $id == 0 ){
				$this->id = 0 ;
				$this->name = 'Dummy' ;
			} else {
				$query = 'SELECT * FROM ' . DB::name( 'pr' ) ;
				$query .= ' WHERE ' . DB::name( 'pr' , 'pr_id' ) . ' = ' . DB::escape( $id ) . ' ;' ;
				
				if( $resultset = DB::query( $query ) ) {
					if( DB::count( $resultset ) == 1 ) {
						$row = DB::fetch( $resultset ) ;
						
						$this->id = $row['pr_id'] ;
						$this->name = $row['pr_short'] ;
						$this->abs_start = new Date( $row['abs_start'] ) ;
						$this->abs_end = new Date( $row['abs_end'] ) ;
						$this->ad_start = new Date( $row['ad_start'] ) ;
						$this->ad_end = new Date( $row['ad_end'] ) ;
					} else {
						//throw new ErrorException( 'Ambigous period error.' , 921 , 1 , 'Period.php' , 22 ) ;
					}
				} else {
					// throw sth
				}
			}
		}
		
		/**
		*	@return int $id
		*/
		public function getId() {
			return $this->id ;
		}
		
		/**
		*	@return string $name
		*/
		public function getName() {
			return $this->name ;
		}
		
		/**
		*	@return Date $abs_start
		*/
		public function getAbs_start() {
			return $this->abs_start ;
		}
		
		/**
		*	@return Date $abs_end
		*/
		public function getAbs_end() {
			return $this->abs_end ;
		}
		
		/**
		*	@return Date $ad_start
		*/
		public function getAd_start() {
			return $this->ad_start ;
		}
		
		/**
		*	@return Date $ad_end
		*/
		public function getAd_end() {
			return $this->ad_end ;
		}
		
		/**
		*	@return array int $abs_weeks
		*/
		public function getAbs_weeks() {
			if( !isset( $this->abs_weeks ) ) {
				$weeks = array() ;
				
				$date = $this->getAbs_start()->getTimestamp() ;
				$end = $this->getAbs_end()->getTimestamp() ;
				while( $date <= $end ) {
					$formatted = new Date( date( "Y-m-d" , $date ) ) ;
					$weeks[] = $formatted->getWeek() ;
					$date += Date::week ;
				}
				
				$this->abs_weeks = $weeks ;
			}
			
			return $this->abs_weeks ;
		}
		
		/**
		*	@return array int $ad_weeks
		*/
		public function getAd_weeks() {
			if( !isset( $this->ad_weeks ) ) {
				$weeks = array() ;
				
				$date = $this->getAd_start()->getTimestamp() ;
				$end = $this->getAd_end()->getTimestamp() ;
				while( $date <= $end ) {
					$formatted = new Date( date( "Y-m-d" , $date ) ) ;
					$weeks[] = $formatted->getWeek() ;
					$date += Date::week ;
				}
				
				$this->ad_weeks = $weeks ;
			}
			
			return $this->ad_weeks ;
		}
		
		/**
		*	@param Date $date
		*	@return Period
		*/
		public static function findAd_pr( Date $date ) {
			$query = 'SELECT pr_id FROM pr' ;
			$query .= ' WHERE ad_start <= ' . DB::escape( $date->getMysql() ) ;
			$query .= ' AND ad_end >= ' . DB::escape( $date->getMysql() ) . ' ;' ;
			if( $resultset = DB::query( $query ) ) {
				if( DB::count( $resultset ) == 1 ) {
					$row = DB::fetch( $resultset ) ;
					DB::free( $resultset ) ;
					return new Period( $row['pr_id'] ) ;
				} else {
					DB::free( $resultset ) ;
					//throw amb pr
				}
			} else {
				DB::free( $resultset ) ;
				//throw empty resultset
			}
			return false ;
		}
		
		/**
		*	@param Date $date
		*	@return Period
		*/
		public static function findAbs_pr( Date $date ) {
			$query = 'SELECT ' . DB::name( 'pr' , 'pr_id' ) . ' FROM ' . DB::name( 'pr' ) ;
			$query .= ' WHERE ' . DB::name( 'pr' , 'abs_start' ) . ' <= ' . DB::escape( $date->getMysql() ) ;
			$query .= ' AND ' . DB::name( 'pr' , 'abs_end' ) . ' >= ' . DB::escape( $date->getMysql() ) . ' ;' ;
			if( $resultset = DB::query( $query ) ) {
				if( DB::count( $resultset ) == 1 ) {
					$row = DB::fetch( $resultset ) ;
					DB::free( $resultset ) ;
					return new Period( $row['pr_id'] ) ;
				} else {
					DB::free( $resultset ) ;
					//throw amb pr
				}
			} else {
				DB::free( $resultset ) ;
				//throw empty resultset
			}
			return false ;
		}
		
		/**
		 *	@param int|NULL $i pr_id, default NULL
		 *	@return array Period $result all Periods
		 */
		public static function getPeriods( $i = NULL ) {
			$query = 'SELECT ' . DB::name( 'pr' , 'pr_id' ) ;
			$query .= ' FROM ' . DB::name( 'pr' ) ;
			if( $resultset = DB::query( $query ) ) {
				while( $row = DB::fetch( $resultset ) ) {
					$result[ $row[ 'pr_id' ] ] = new Period( $row[ 'pr_id' ] ) ;
				}
			}
			DB::free( $resultset ) ;
			
			if( $i === NULL ) {
				return $result ;
			} else {
				return $result[ $i ] ;
			}
		}
		
	}
	
?>