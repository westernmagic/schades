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
	*	@file mat.php
	*	
	*	@author Michal Sudwoj <mswoj61@gmail.com>
	*	@copyright Michal Sudwoj
	*	@link http://www.sourceforge.com/projects/schades/
	*	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
	*	@version 0.8
	*	
	*	@brief settings file
	*	
	*	@details Configurable settings file.
	*	
	*	@todo migrate to Period::now() .
	*/
	
	define( 'abs_pr' , 50 ) ;
	define( 'abs_start' , 5 ) ;
				
	$server   = 'localhost' ;
	$user     = 'schades'   ;
	$password = ''          ;
	$db_name  = 'schades'   ;
	
	$tables =	array(
		'abs_abs' => array(
			0                  => 'abs_abs'          ,
			'date'             => 'date'             ,
			'week'             => 'week'             ,
			'day'              => 'day'              ,
			'lesson'           => 'lesson'           ,
			'abs_type'         => 'abs_type'         ,
			'in'               => 'in'               ,
			'out'              => 'out'              ,
			'abs_id'           => 'abs_id'           ,
			'ad_id'            => 'ad_id'            
		) ,
		'abs_action_type' => array(
			0                  => 'abs_action_type'  ,
			'action_type'      => 'action_type'      ,
			'action_type_name' => 'action_type_name' 
		) ,
		'abs_excuse' => array(
			0                  => 'abs_excuse'       ,
			'excuse_id'        => 'excuse_id'        ,
			'ad_id'            => 'ad_id'            ,
			'from_week'        => 'from_week'        ,
			'from_day'         => 'from_day'         ,
			'from_lesson'      => 'from_lesson'      ,
			'to_week'          => 'to_week'          ,
			'to_day'           => 'to_day'           ,
			'to_lesson'        => 'to_lesson'        ,
			'value'            => 'value'            ,
			'reason'           => 'reason'           ,
			'granted_by'       => 'granted_by'       ,
			'date_time'        => 'date_time'        
		),
		'abs_log_abs' => array(
			0                  => 'abs_log_abs'      ,
			'log_abs_id'       => 'log_abs_id'       ,
			'abs_id'           => 'abs_id'           ,
			'after'            => 'after'            ,
			'in_out'           => 'in_out'           ,
			'executor_id'      => 'executor_id'      ,
			'date_time'        => 'date_time'        
		) ,
		'abs_log_login' => array(
			0                  => 'abs_log_login'    ,
			'log_login_id'     => 'log_login_id'     ,
			'ad_id'            => 'ad_id'            ,
			'date_time'        => 'date_time'        ,
			'ip'               => 'ip'               
		) ,
		'ad_ad' => array(	
			0                  => 'ad_ad'            ,
			'ad_id'            => 'ad_id'            ,
			'surname'          => 'surname'          ,
			'first_name'       => 'first_name'       ,
			'phone'            => 'phone'            ,
			'email'            => 'email'            ,
			'street1'          => 'street1'          ,
			'street2'          => 'street2'          ,
			'city'             => 'city'             ,
			'zip'              => 'zip'              ,
			'title'            => 'title'            ,
			'birth_date'       => 'birth_date'       ,
			'teacher_short'    => 'teacher_short'    ,
			'ad_type_id'       => 'ad_type_id'       ,
			'abs_level'        => 'abs_level'        
		) ,
		'ad_co' => array(
			0                  => 'ad_co'            ,
			'ad_id'            => 'ad_id'            ,
			'co_id'            => 'co_id'            ,
			'ad_co_type'       => 'ad_co_type'       
		) ,
		'ad_pg' => array(
			0                  => 'ad_pg'            ,
			'ad_pg_type'       => 'ad_pg_type'       ,
			'pg_id'            => 'pg_id'            ,
			'ad_id'            => 'ad_id'            
		) ,
		'co_co' => array(
			0                  => 'co_co'            ,
			'co_id'            => 'co_id'            ,
			'name'             => 'name'             ,
			'pr_id'            => 'pr_id'            ,
			'co_type_id'       => 'co_type_id'       
		) ,
		'login' => array(
			0                  => 'login'            ,
			'ad_id'            => 'ad_id'            ,
			'username'         => 'username'         ,
			'password'         => 'password'         
		) ,
		'pg' => array(
			0                  => 'pg'               ,
			'pg_id'            => 'pg_id'            ,
			'pg'               => 'pg'               ,
			'ad_pr'            => 'ad_pr'            ,
			'class'            => 'class'            
		) ,
		'pr' => array(	
			0                  => 'pr'               ,
			'pr_id'            => 'pr_id'            ,
			'pr_short'         => 'pr_short'         ,
			'abs_start'        => 'abs_start'        ,
			'abs_end'          => 'abs_end'          ,
			'ad_start'         => 'ad_start'         ,
			'ad_end'           => 'ad_end'           
		) ,
		'queue' => array(
			0                  => 'queue'            ,
			'queue_id'         => 'queue_id'         ,
			'concerning'       => 'concerning'       ,
			'to'               => 'to'               ,
			'action_type'      => 'action_type'      ,
			'pr'               => 'pr'               ,
			'week'             => 'week'             ,
			'status'           => 'status'           
		),
		'tt_mapping' => array(
			0                  => 'tt_mapping'       ,
			'co_type_id'       => 'co_type_id'       ,
			'tt_short_name'    => 'tt_short_name'    
		) ,
		'tt_tt' => array(
			0                  => 'tt_tt'            ,
			'week'             => 'week'             ,
			'day'              => 'day'              ,
			'lesson'           => 'lesson'           ,
			'subject'          => 'subject'          ,
			'class'            => 'class'            
		)
	) ;
?>
