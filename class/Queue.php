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
	*	@file Queue.php
	*	
	*	@author Michal Sudwoj <mswoj61@gmail.com>
	*	@copyright Michal Sudwoj
	*	@link http://www.sourceforge.com/projects/schades/
	*	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
	*	@version 0.8
	*/
	
	require_once( 'include.php' ) ;
	
	/**
	*	@brief Queue class
	*	
	*	@details Represents a queue item.
	*/
	class Queue extends Superclass {
		
		/**
		*	@brief int
		*/
		private $id ;
		
		/**
		*	@brief Person
		*/
		private $concerning ;
		
		/**
		*	@brief Person
		*/
		private $to ;
		
		/**
		*	@brief int
		*/
		private $type ;
		
		/**
		*	@brief int
		*/
		private $week ;
		
		/**
		*	@brief int
		*/
		private $status ;
		
		/**
		*	@brief Period
		*/
		private $pr ;
		
		/**
		*	@brief Constructor
		*	
		*	@param int $id
		*/
		public function __construct( $id ) {
			$this->id = $id ;
		}
		
		/**
		*	@brief Initialize basic variables.
		*/
		private function getData() {
			$query = 'SELECT * FROM ' . DB::name( 'queue' ) ;
			$query .= ' WHERE ' . DB::name( 'queue' , 'queue_id' ) . ' = ' . DB::escape( $this->getId() ) . ' ;' ;
			if( $resultset = DB::query( $query ) ) {
				if( DB::count( $resultset ) == 1 ) {
					$row = DB::fetch( $resultset ) ;
					$this->pr = new Period( $row[ 'pr' ] ) ;
					$this->concerning = new Person( $row[ 'concerning' ] , $this->getPr() ) ;
					$this->to = $row[ 'to' ] ;
					$this->type = $row[ 'action_type' ] ;
					$this->week = $row[ 'week' ] ;
					$this->status = $row[ 'status' ] ;
					mysql_free_result( $resultset ) ;
				} else {
					//throw exception
				}
			} else {
				//throw exception
			}
		}
		
		/**
		*	@return int $id
		*/
		public function getId() {
			return $this->id ;
		}
		
		/**
		*	@return Person $concerning
		*/
		public function getConcerning() {
			if( !isset( $this->concerning ) ) {
				$this->getData() ;
			}
			return $this->concerning ;
		}
		
		/**
		*	@return Person $to
		*/
		public function getTo() {
			if( !isset( $this->to ) ) {
				$this->getData() ;
			}
			return $this->to ;
		}
		
		/**
		*	@return int $type
		*/
		public function getType() {
			if( !isset( $this->type ) ) {
				$this->getData() ;
			}
			return $this->type ;
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
		*	@return int $status
		*/
		public function getStatus() {
			if( !isset( $this->status ) ) {
				$this->getData() ;
			}
			return $this->status ;
		}
		
		/**
		*	@return Period $pr
		*/
		public function getPr() {
			if( !isset( $this->pr ) ) {
				$this->getData() ;
			}
			return $this->pr ;
		}
		
		/**
		*	@brief Adds an item to the queue.
		*	
		*	@param Person $concerning
		*	@param Person $to
		*	@param int $type
		*	@param int status
		*	@param Period $pr
		*	@param int $week
		*	@return Queue|bool Queue / false
		*	@todo check
		*/
		public static function addQueue( Person $concerning , Person $to , $type , $status = 0 , Period $pr = NULL , $week = 0 ) {
			if( $pr == NULL ) {
				//$pr = Date::now()->getAbs_pr() ;
				$pr = new Period( 50 ) ;
			}
			$query = 'INSERT INTO ' . DB::name( 'queue' ) ;
			$query .= ' SET' ;
			$query .= ' ' . DB::name( 'queue' , 'queue_id' ) . ' = DEFAULT ,' ;
			$query .= ' ' . DB::name( 'queue' , 'concerning' ) . ' = ' . DB::escape( $concerning->getId() ) . ' ,' ;
			$query .= ' ' . DB::name( 'queue' , 'to' ) . ' = ' . DB::escape( $to->getId() ) . ' ,' ;
			$query .= ' ' . DB::name( 'queue' , 'action_type' ) . ' = ' . DB::escape( $type ) . ' ,' ;
			$query .= ' ' . DB::name( 'queue' , 'status' ) . ' = ' . DB::escape( $status ) . ' ,' ;
			$query .= ' ' . DB::name( 'queue' , 'pr' ) . ' = ' . DB::escape( $pr->getId() ) . ' ,' ;
			$query .= ' ' . DB::name( 'queue' , 'week' ) . ' = ' . DB::escape( $week ) . ' ;' ;
			if( DB::query( $query ) ) {
				$id = DB::insertId() ;
				if( $id === false ) {
					return false ;
				} else {
					if( $id == 0 ) {
						$query = 'SELECT ' . DB::name( 'queue' , 'queue_id' ) ;
						$query .= ' FROM ' . DB::name( 'queue' ) ;
						$query .= ' WHERE ' . DB::name( 'queue' , 'concerning' ) . ' = ' . DB::escape( $concerning->getId() ) ;
						$query .= ' AND ' . DB::name( 'queue' , 'action_type' ) . ' = ' . DB::escape( $type ) ;
						$query .= ' AND ' . DB::name( 'queue' , 'pr' ) . ' = ' . DB::escape( $pr->getId() ) ;
						$query .= ' AND ' . DB::name( 'queue' , 'week' ) . ' = ' . DB::escape( $week ) . ' ;' ;
							
						if( $resultset = DB::query( $query ) ) {
							if( DB::count( $resultset ) == 1 ) {
								$row = DB::fetch( $resultset ) ;
								DB::free( $resultset ) ;
								$id = $row['queue_id'] ;
							} else {
								//throw Exception
								return false ;
							}
						} else {
							//throw Exception
							return false ;
						}
					}
					return new Queue( $id ) ;
				}
			} else {
				//throw Exception
				return false ;
			}
		}
		
		/**
		*	@brief Remove $this item from the queue.
		*	@todo check
		*/
		public function deleteQueue() {
			$query = 'DELETE FROM ' . DB::name( 'queue' ) ;
			$query .= ' WHERE ' . DB::name( 'queue' , 'queue_id' ) . ' = ' . DB::escape( $this->getId() ) . ' ;' ;
			if( DB::query( $query ) ) {
				unset( $this ) ;
				return true ;
			}
			return false ;
		}
		
		public static function search( Person $concerning , $type , Period $pr = NULL , $destroy = false , $week = 0 ) {
			$result = array() ;
			if( $pr == NULL ) {
				$pr = Date::now()->getAbs_pr() ;
			}
			
			$query = 'SELECT ' . DB::name( 'queue' , 'queue_id' ) ;
			$query .= ' FROM ' . DB::name( 'queue' ) ;
			$query .= ' WHERE ' . DB::name( 'queue' , 'concerning' ) . ' = ' . DB::escape( $concerning->getId() ) ;
			$query .= ' AND ' . DB::name( 'queue' , 'action_type' ) . ' = ' . DB::escape( $type ) ;
			$query .= ' AND ' . DB::name( 'queue' , 'pr' ) . ' = ' . DB::escape( $pr->getId() ) ;
			if( $week != 0 ) {
				$query .= ' AND ' . DB::name( 'queue' , 'week' ) . ' = ' . DB::escape( $week ) ;
			}
			$query .= ' ;' ;
			if( $resultset = DB::query( $query ) ) {
				while( $row = DB::fetch( $resultset ) ) {
					$result[] = new Queue( $row[ 'queue_id' ] ) ;
				}
			}
			
			if( $destroy ) {
				foreach( $result as $q ) {
					if( !$q->deleteQueue() ) {
						return false ;
					}
				}
				return true ;
			} else {
				return $result ;
			}
			return false ;
		}
	
	}
?>