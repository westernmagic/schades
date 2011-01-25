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
	 * 	@file settings.php
	 * 	
	 * 	@author Michal Sudwoj <mswoj61@gmail.com>
	 * 	@copyright Michal Sudwoj
	 * 	@link http://www.sourceforge.com/projects/schades/
	 * 	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
	 * 	@version 0.8
	 * 	
	 * 	@brief Global settings file
	 * 	
	 * 	@details Configurable settings file.
	 * 	
	 */
	
	require_once( 'include.php' ) ;
	
	define(           USE_TIDY        ,  false ) ;
	
	ini_set(         'memory_limit'   , '64M'  ) ;
	error_reporting(  -1                       ) ;
	ini_set(         'display_errors' , 'On'   ) ;
	date_default_timezone_set( 'Europe/Zurich' ) ;
	
	DB::init( 'schades' ) ;
	
		

?>
