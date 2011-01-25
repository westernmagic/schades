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
	*	@file SuperClass.php
	*	
	*	@author Michal Sudwoj <mswoj61@gmail.com>
	*	@copyright Michal Sudwoj
	*	@link http://www.sourceforge.com/projects/schades/
	*	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
	*	@version 0.8
	*/
	
	require_once( 'include.php' ) ;
	
	/**
	*	@brief A class defining (most) magic methods, to be extended by all others. The only NOT final method is __invoke().
	*/
	abstract class SuperClass {
		
		/**
		*	@brief Run the accessor, if it exists.
		*
		*	@param string $name The name of the property to be accessed.
		*/
		final public function __get( $name ) {
			if( property_exists( get_called_class() , $name ) ) {
				if( method_exists( $this , ( $method = 'get' . ucfirst( $name ) ) ) ) {
					return $this->$method() ;
				} elseif( method_exists( $this , ( $method = 'get_' . $name ) ) ) {
					return $this->$method() ;
				} else {
					throw new Exception( 'Getting ' . $name . ' not allowed' ) ;
				}
			} else {
				throw new Exception( $name . ' not defined in ' . get_called_class() ) ;
			}
		}
		
		/**
		*	@brief Run the corresponding isset, if it exists.
		*
		*	@param string $name The name of the property to be checked.
		*/
		final public function __isset( $name ) {
			if( property_exists( get_called_class() , $name ) ) {
				if( method_exists( $this , ( $method = 'isset' . ucfirst( $name ) ) ) ) {
					return $this->$method() ;
				} elseif( method_exists( $this , ( $method = 'isset_' . $name ) ) ) {
					return $this->$method() ;
				} else {
					throw new Exception( 'IsSetting ' . $name . ' not allowed' ) ;
				}
			} else {
				throw new Exception( $name . ' not defined in ' . get_called_class() ) ;
			}
		}
		
		/**
		*	@brief Run the mutator, if it exists.
		*
		*	@param string $name The name of the property to be set.
		*	@param mixed $value The value the property should be set to.
		*/
		final public function __set( $name , $value ) {
			if( property_exists( get_called_class() , $name ) ) {
				if( method_exists( $this , ( $method = 'set' . ucfirst( $name ) ) ) ) {
					$this->$method( $value ) ;
				} elseif( method_exists( $this , ( $method = 'set_' . $name ) ) ) {
					$this->$method( $value ) ;
				} else {
					throw new Exception( 'Setting ' . $name . ' not allowed' ) ;
				}
			} else {
				throw new Exception( $name . ' not defined in ' . get_called_class() ) ;
			}
		}
		
		/**
		*	@brief Run the unsetter, if it exists.
		*
		*	@param string $name The property to be unset.
		*/
		final public function __unset( $name ) {
			if( property_exists( get_called_class() , $name ) ) {
				if( method_exists( $this , ( $method = 'unset' . ucfirst( $name ) ) ) ) {
					$this->$method() ;
				} elseif( method_exists( $this , ( $method = 'unset_' . $name ) ) ) {
					$this->$method() ;
				} else {
					throw new Exception( 'Unsetting ' . $name . ' not allowed' ) ;
				}
			} else {
				throw new Exception( $name . ' not defined in ' . get_called_class() ) ;
			}
		}
		
		/**
		*	@brief Dump the class.
		*/
		final public function __toString() {
			return var_dump( $this ) ;
		}
		
		/**
		*	@brief Throw an exception on unknown method.
		*
		*	@param string $name The name of the method.
		*	@param array mixed $args The arguments to be passed.
		*/
		final public function __call( $name , $args ) {
			throw new Exception( $name . '()' . ' not defined with ' . count( $args ) . ' arguments'  ) ;
		}
		
		/**
		*	@brief Throw an exception on unknown static method.
		*
		*	@param string $name The name of the method.
		*	@param array mixed $args The arguments to be passed.
		*/
		final static public function __callStatic( $name , $args ) {
			throw new Exception( 'Static method ' . $name . '()' . ' not defined with ' . count( $args ) . ' arguments'  ) ;
		}
		
		/**
		*	@brief Throw an exception when the class is called as a function.
		*
		*	@param array mixed $args	The arguments to be passed.
		*/
		public static function __invoke( $args ) {
			throw new Exception( 'The class ' . get_called_class() . ' is not callable' ) ;
		}
		
	}

?>
