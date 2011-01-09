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
	*	@file Date.php
	*	
	*	@author Michal Sudwoj <mswoj61@gmail.com>
	*	@copyright Michal Sudwoj
	*	@link http://www.sourceforge.com/projects/schades/
	*	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
	*	@version 0.8
	*/
	
	//require_once( "settings.php" ) ;
	require_once( 'Period.php' ) ;
	
	/**
	*	@brief Date class
	*/
	class Date {
		
		/**
		*	@brief const int = 7 * 24 * 60 * 60 = seconds in a week
		*/
		const week = 604800 ;
		
		/**
		*	@brief int day of the month
		*/
		private $d ;
		
		/**
		*	@brief int month
		*/
		private $m ;
		
		/**
		*	@brief int year
		*/
		private $y ;
		
		/**
		*	@brief string normal date format dd/mm/yyyy
		*	@todo privatise
		*/
		private $norm ;
		
		/**
		*	@brief string mysql date format yyyy-mm-dd
		*/
		private $mysql ;
		
		/**
		*	@brief int day of the week
		*/
		private $day ;
		
		/**
		*	@brief int week of the year
		*/
		private $week ;
		
		/**
		*	@brief int unix timestamp
		*/
		private $timestamp ;
		
		/**
		*	@brief int hours
		*/
		private $h ;
		
		/**
		*	@brief int minutes
		*/
		private $i ;
		
		/**
		*	@brief int seconds
		*/
		private $s ;
		
		/**
		*	@brief Period ad_pr
		*/
		private $ad_pr ;
		
		/**
		*	@brief Period abs_pr
		*/
		private $abs_pr ;
		
		//constructor
		
		/**
		*	@brief Constructor
		*	
		*	@param string $date
		*/
		public function __construct( $date ) {
			$this->timestamp = strtotime( $date ) ;
			$this->d = (int) date( "d" , $this->timestamp ) ;
			$this->m = (int) date( "m" , $this->timestamp ) ;
			$this->y = (int) date( "Y" , $this->timestamp ) ;
			$this->norm = date( "d/m/Y" , $this->timestamp ) ;
			$this->mysql = date( "Y-m-d" , $this->timestamp ) ;
			$this->day = (int) date( "w" , $this->timestamp ) ;
			$this->week = (int) date( "W" , $this->timestamp ) ;
			$this->h = date( 'H' , $this->timestamp ) ;
			$this->i = date( 'i' , $this->timestamp ) ;
			$this->s = date( 's' , $this->timestamp ) ;
			
			$this->ad_pr = Period::findAd_pr( $this ) ;
			$this->abs_pr = Period::findAbs_pr( $this ) ;
		}
		
		//getters
		
		/**
		 *	@return int timestamp
		 */
		public function getTimestamp() {
			return $this->timestamp ;
		}
		
		/**
		 *	@return int d
		 */
		public function getD() {
			return $this->d ;
		}
		
		/**
		 *	@return int m
		 */
		public function getM() {
			return $this->m ;
		}
		
		/**
		 *	@return int y
		 */
		public function getY() {
			return $this->y ;
		}
		
		/**
		 *	@return string norm
		 */
		public function getNorm() {
			return $this->norm ;
		}
		
		/**
		 *	@return string mysql
		 */
		public function getMysql() {
			return $this->mysql ;
		}
		
		/**
		 *	@return int day
		 */
		public function getDay() {
			return $this->day ;
		}
		
		/**
		 *	@return int week
		 */
		public function getWeek() {
			return $this->week ;
		}
		
		/**
		 *	@return int h
		 */
		public function getH() {
			return $this->h ;
		}
		
		/**
		 *	@return int i
		 */
		public function getI() {
			return $this->i ;
		}
		
		/**
		 *	@return int s
		 */
		public function getS() {
			return $this->s ;
		}
		
		/**
		 *	@return int ad_pr
		 */
		public function getAd_pr() {
			return $this->ad_pr ;
		}
		
		/**
		 *	@return int abs_pr
		 */
		public function getAbs_pr() {
			return $this->abs_pr ;
		}
		
		//static functions
		
		/**
		*	@return Date now
		*/
		public static function now() {
			return new Date( 'now' ) ;
		}
		
		/**
		 *	@brief transforms school date into Date
		 *
		 *	@param Period $pr
		 *	@param int $week
		 *	@param int $day
		 *	@param bool $ad ad or abs period
		 *	@param int $lesson
		 *	@param bool $end lesson start or end time
		 *
		 *	@return Date
		 */
		public static function schooldate( Period $pr , $week , $day , $ad = false , $lesson = 1 , $end = false ) {
			return new Date( $ad?$pr->getAd_start():$pr->getAbs_start() . ' ' . $end?Date::lesson_end( $lesson ):Date::lesson_start( $lesson ) . ' +' . $week . ' week +' . $day . ' day' );
		}
		
		/**
		 *	@param int $l
		 *	@return string lesson start time
		 */
		public static function lesson_start( $l = 1 ) {
			switch( $l ) {
				case 1:
					return '07:45' ;
					break ;
				case 2:
					return '08:35' ;
					break ;
				case 3:
					return '09:25' ;
					break ;
				case 4:
					return '10:20' ;
					break ;
				case 5:
					return '11:10' ;
					break ;
				case 6:
					return '12:00' ;
					break ;
				case 7:
					return '12:45' ;
					break ;
				case 8:
					return '13:35' ;
					break ;
				case 9:
					return '14:25' ;
					break ;
				case 10:
					return '15:15' ;
					break ;
				case 11:
					return '16:05' ;
					break ;
				case 12:
					return '16:50' ;
					break ;
				default:
					return '07:45' ;
					break ;
			}
		}
		
		/**
		 *	@param int $l
		 *	@return string lesson end time
		 */
		public static function lesson_end( $l = 12 ) {
			switch( $l ) {
				case 1:
					return '08:25' ;
					break ;
				case 2:
					return '09:15' ;
					break ;
				case 3:
					return '10:05' ;
					break ;
				case 4:
					return '11:00' ;
					break ;
				case 5:
					return '11:50' ;
					break ;
				case 6:
					return '12:40' ;
					break ;
				case 7:
					return '13:25' ;
					break ;
				case 8:
					return '14:15' ;
					break ;
				case 9:
					return '15:05' ;
					break ;
				case 10:
					return '15:55' ;
					break ;
				case 11:
					return '16:45' ;
					break ;
				case 12:
					return '17:30' ;
					break ;
				default:
					return '17:30' ;
					break ;
			}
		}
	
	}

?>