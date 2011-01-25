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
	*	@file queue_action.php
	*	
	*	@author Michal Sudwoj <mswoj61@gmail.com>
	*	@copyright Michal Sudwoj
	*	@link http://www.sourceforge.com/projects/schades/
	*	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
	*	@version 0.8
	*	
	*	@brief Script that executes actions on queue items
	*/
	
	require_once( 'include.php' ) ;
	
	//0 = No ; 1 = Yes ; 2 = Mail ; 3 = Print ; 4 = Akn
	switch( $type ) {
		case 401:
			switch( $action ) {
				case 0:
					break ;
				case 1:
					if( Queue::addQueue( $concerning , $concerning , 402 , 0 , $pr ) ) {
						$concerning->promote() ;
						if( !Queue::search( $concerning , 401 , $pr ) ) {
							//throw
						}
					} else {
						//throw exeption
					}
					break ;
				case 2:
				case 3:
				case 4:
				default:
					break;
			}
			break ;
		case 402:
			switch( $action ) {
				case 0:
				case 1:
				case 2:
				case 3:
					break ;
				case 4:
					if( !Queue::search( $concerning , 402 , $pr , true ) ) {
						//throw
					}
					if( !Queue::search( $concerning , 401 , $pr , true ) ) {
						//throw
					}
					break ;
				default:
					break;
			}
			break ;
		case 404:
			switch( $action ) {
				case 0:
				case 1:
				case 2:
				case 3:
				case 4:
				default:
					break;
			}
			break ;
		case 405:
			switch( $action ) {
				case 0:
					break ;
				case 1:
					if( !Queue::search( $concerning , 404 , $pr , true) ) {
						//throw
					}
					if( !Queue::search( $concerning , 405 , $pr , true ) ) {
						//throw
					}
					break ;
				case 2:
				case 3:
				case 4:
				default:
					break;
			}
			break ;
		case 409:
			switch( $action ) {
				case 0:
				case 1:
				case 2:
				case 3:
				case 4:
				default:
					break;
			}
			break ;
		case 410:
			switch( $action ) {
				case 0:
				case 1:
				case 2:
				case 3:
					break ;
				case 4:
					if( !Queue::search( $concerning , 409 , $pr , true ) ) {
						//throw
					}
					if( !Queue::search( $concerning , 410 , $pr , true ) ) {
						//throw
					}
					if( !Queue::search( $concerning , 411 , $pr , true ) ) {
						//throw
					}
					break ;
				default:
					break;
			}
			break ;
		case 411:
			switch( $action ) {
				case 0:
				case 1:
				case 2:
				case 3:
				case 4:
				default:
					break;
			}
			break ;
		case 412:
			switch( $action ) {
				case 0:
				case 1:
				case 2:
				case 3:
					break ;
				case 4:
					if( !Queue::search( $concerning , 409 , $pr , true ) ) {
						//throw
					}
					if( !Queue::search( $concerning , 410 , $pr , true ) ) {
						//throw
					}
					if( !Queue::search( $concerning , 411 , $pr , true ) ) {
						//throw
					}
					if( !Queue::search( $concerning , 412 , $pr , true ) ) {
						//throw
					}
					break ;
				default:
					break;
			}
			break ;
		case 415:
			switch( $action ) {
				case 0:
					break ;
				case 1:
					if( Queue::addQueue( $concerning , $concerning->getForms( 0 )->getHead() , 416 , 0 , $pr ) ) {
						if( !Queue::search( $concerning , 415 , $pr , true ) ) {
							//throw
						}
					} else {
						//throw
					}
					break ;
				case 2:
				case 3:
				case 4:
				default:
					break;
			}
			break ;
		case 416:
			switch( $action ) {
				case 0:
				case 1:
				case 2:
				case 3:
				case 4:
				default:
					break;
			}
			break ;
		default:
			break ;
	}
	
?>
