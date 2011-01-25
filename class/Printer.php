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
	*	@file Printer.php
	*	
	*	@author Michal Sudwoj <mswoj61@gmail.com>
	*	@copyright Michal Sudwoj
	*	@link http://www.sourceforge.com/projects/schades/
	*	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
	*	@version 0.8
	*/
	
	require_once( 'include.php' ) ;
	
	/**
	*	@brief Printer class
	*	
	*	@details Parses information into HTML code.
	*/
	abstract class Printer extends Superclass {
		
		/**
		*	@return string legend table
		*	@todo constantize
		*/
		public static function legend() {
			$line =	'<table>
						<tbody>
							<tr>
								<td style="width: 20px; background-color: cyan ;" ></td>
								<td>Late</td>
								<td style="width: 20px; background-color: green ;"></td>
								<td>Present</td>
							</tr>
							<tr>
								<td style="width: 20px; background-color: yellow ;" ></td>
								<td>Absent</td>
								<td style="width: 20px; background-color: gray ;" ></td>
								<td>Unknown</td>
							</tr>
							<tr>
								<td style="width: 20px; background-color: red ;" ></td>
								<td>Exam</td>
								<td style="width: 20px; background-color: black ;" ></td>
								<td>Free</td>
							</tr>
								<td style="width: 20px; background-color: blue ;" ></td>
								<td>Excused</td>
								<td></td>
								<td></td>
							</tr>
						</tbody>
					</table>' ;
			
			return self::tidy( $line ) ;
		}
		
		/**
		*	@deprecated never used
		*	@param string $type Absence type
		*	@return string text colour
		*/
		private static function colour_text( $type ) {
			$type = self::char1( $type ) ;
			switch( $type ) {
				case "?" :
					$line = 'style="color: gray ;"' ;
					break ;
				case "." :
					$line = 'style="color: green ;"' ;
					break ;
				case "o" :
					$line = 'style="color: cyan ;"' ;
					break ;
				case "x" :
					$line = 'style="color: yellow ;"' ;
					break ;
				case "p" :
					$line = 'style="color: red ;"' ;
					break ;
				case "e" :
					$line = 'style="color: blue ;"' ;
					break ;
				case '*' :
					$line = 'style="color: black ;"' ;
					break ;
				default :
					$line = 'style="color: gray ;"' ;
					break ;
			}
			
			return $line ;
		}
		
		/**
		*	@param string $type Absence type
		*	@return string css class
		*/
		private static function colour_code( $type ) {
			$type = self::char1( $type ) ;
			switch( $type ) {
				case "?" :
					$line = 'class="u"' ;
					break ;
				case "." :
					$line = 'class="t"' ;
					break ;
				case "o" :
					$line = 'class="o"' ;
					break ;
				case "x" :
					$line = 'class="x"' ;
					break ;
				case "p" :
					$line = 'class="p"' ;
					break ;
				case "e" :
					$line = 'class="e"' ;
					break ;
				case '*' :
					$line = 'class="f"' ;
					break ;
				default :
					$line = 'class="u"' ;
					break ;
			}
			
			return $line ;
		}
		
		/**
		*	@param string $type Absence type
		*	@return string css-classed table cell
		*/
		private static function colour_cell( $type ) {
			$type = self::char1( $type ) ;
			return'<td ' . self::colour_code( $type ) . ' ></td>' ;
		}
		
		/**
		*	@param string $type Absence type
		*	@param int $ad_id student id
		*	@param int $week
		*	@param int $day
		*	@param int $lesson
		*	@return string css-classed table cell you can click on
		*/
		private static function colour_opt( $type , $ad_id , $week , $day , $lesson ) {
			$type = self::char1( $type ) ;
			$key = $ad_id . ':' . $week . ':' . $day . ':' . $lesson ;
			$line = '<td id="' . $key . '" onclick="changeOpt( this ) ;" ' . self::colour_code( $type ) . ' ></td>' ;
			
			return $line ;
		}
		
		/**
		*	@brief Decides when to use colour_cell() and when to use colour_opt()
		*	
		*	@param string $type Absence type
		*	@param int $ad_id student id
		*	@param int $week
		*	@param int $day
		*	@param int $lesson
		*	@return string edit classed table cell
		*/
		private static function colour_edit( $type, $ad_id , $week , $day , $lesson ) {
			$type = self::char1( $type ) ;
			switch( $type ) {
				case '*' :
				case 'e' :
					return self::colour_cell( $type ) ;
					break ;
				case '?' :
				case 'u' :
					return self::colour_opt( '.' , $ad_id , $week , $day , $lesson ) ;
					break ;
				default:
					return self::colour_opt( $type , $ad_id , $week , $day , $lesson ) ;
					break ;
			}
		}
		
		/**
		*	@param Person $student
		*	@return string table of student's absences
		*/
		public static function student_table( Person $person ) {
			$line =	'<table border="1" style="border-collapse: collapse; border-color: lime;" >
						<thead>
							<tr>
								<th colspan="2" >Lessons</th>
								<th colspan="12" >Monday</th>
								<th colspan="12" >Tuesday</th>
								<th colspan="12" >Wednesday</th>
								<th colspan="12" >Thursday</th>
								<th colspan="12" >Friday</th>
							</tr>
							<tr>
								<th>Weeks</th>
								<th>AUs</th>' ;
			
			for( $d = 1 ; $d <= 5 ; $d++ ) {
				for( $l = 1 ; $l <= 12 ; $l++ ) {
					$line .= '<th style="width: 20px;" >' . $l . '</th>' ;
				}
			}
			
			$line .=	'</tr></thead><tbody>' ;
			
			$pr = $person->getPr() ;
			$weeks = $pr->getAbs_weeks() ;
			$combined = $person->getCombined() ;
			$total = $person->getTotal() ;
			foreach( $weeks as $w ) {
				$week_abs = $total[$w] ;
				$line .= '<tr><th>' . $w . '</th>' ;
				$line .= '<td>' . $week_abs['c_tot'] . '</td>' ;
				for( $d = 1 ; $d <= 5 ; $d++ ) {
					for( $l = 1 ; $l <= 12 ; $l++ ) {
						$line .= self::colour_cell( $combined[ $w * 5 * 12 + $d * 12 + $l ] ) ;
					}
				}
				$line .= '</tr>' ;
			}
			
			$line .= '<tr><th>Total</th><td>' . $total['c_tot'] . '</td><td colspan="60">&nbsp;</td></tr>' ;
			
			$line .= '</tbody></table>' ;
			
			return self::tidy( $line ) ;
		}
		
		/**
		*	@deprecated Use form() instead.
		*	@param Person $person teacher
		*	@param int $week selecte week
		*	@param Course|Form $copg selected form / course
		*	@return string form w/ 3 fields
		*/
		public static function teacher_form( Person $person , $week , $copg ) {
			$line = '<form action="teacher.php" method="get" ><table><tbody><tr><td>' ;
			$line .= '<select id="abs_pr" onchange="ajaxForm( document.getElementById(\'abs_pr\').value , document.getElementById(\'copg\').value , document.getElementById(\'week\').value , false );" >' ;
			$query = "SELECT " . DB::name( 'pr' , 'pr_id' ) . " , " . DB::name( 'pr' , 'pr_short' ) . " FROM " . DB::name( 'pr' ) . " ORDER BY " . DB::name( 'pr' , 'start_date' ) ;
			if( $resultset = DB::query( $query ) ) {
				while( $row = DB::fetch( $resultset ) ) {
					$line .= '<option value="' . $row["pr_id"] . '">' . $row["pr_short"] . '</option>' ;
				}
				
				DB::free( $resultset ) ;
			}
			$line .= '</select>' ;
			$line .= '</td><td>' ;
			$line .= self::logout() ;
			$line .= '</td></tr><tr><td>' ;
			$line .= self::copg( $person , $copg ) ;
			$line .= '</td><td>' ;
			$line .= self::weeks( $person->getPr() , $week ) ;
			$line .= '</td></tr></tbody></table></form>' ;
			
			return self::tidy( $line ) ;
		}
		
		/**
		*	@brief Decides when to use table_course() and when to use table_form()
		*	
		*	@param Course|Form $copg Course / Form
		*	@param int $week
		*	@param bool $edit whether editing mode is on or not
		*	@return string table
		*/
		public static function teacher_table( $copg , $week , $edit ) {
			if( $copg instanceof Course ) {
				$line = self::table_course( $copg , $week , $edit ) ;
			} elseif( $copg instanceof Form ) {
				$line = self::table_form( $copg , $week ) ;
			}
			
			return self::tidy( $line ) ;
		}
		
		/**
		*	@return string drop-down of absence periods (terms)
		*/
		private static function abs_pr() {
			$line = '<td><select id="abs_pr" onchange="ajaxMain( 1 ) ;" >' ;
			$query = "SELECT " . DB::name( 'pr' , 'pr_id' ) . " , " . DB::name( 'pr' ,'pr_short' ) . " FROM " . DB::name( 'pr' ) . ' WHERE pr_id = 50 ' . " ORDER BY " . DB::name( 'pr', 'pr_id' ) ; //temp
			if( $resultset = DB::query( $query ) ) {
				while( $row = DB::fetch( $resultset ) ) {
					$line .= '<option value="' . $row["pr_id"] . '"' ;
					/*if( $row['pr_id'] == '50' ) {
						$line .= 'selected="true"' ;
					}*/
					$line .= '>' . $row["pr_short"] . '</option>' ;
				}
				
				DB::free( $resultset ) ;
			}
			$line .= '</select></td>' ;
			
			return $line ;
		}
		
		/**
		*	@deprecated integrated into index.php
		*	@return string logout button
		*/
		private static function logout() {
			$line = '<a href="logout.php?type=logout" ><input type="button" value="Logout" /></a>' ;
			
			return $line ;
		}
		
		/**
		*	@param Period $pr
		*	@return string drop-down of weeks in the absence period, including buttons
		*/
		private static function weeks( Period $pr ) {
			$line = '<td class="toolcell" onclick="javascript:getElementById(\'week\').value--; ajaxForm( false );" >&lt;&lt;</td>' ;
			$line .= '<td><select id="week" onchange="ajaxForm( false );" >' ;
			
			foreach( $pr->getAbs_weeks() as $w ) {
				$line .= '<option value="' . $w . '" ' ;
				$line .= '>' . $w . '</option>' ;
			}
			
			$line .= '</select></td>' ;
			$line .= '<td class="toolcell" onclick="javascript:getElementById(\'week\').value++; ajaxForm( false );" >&gt;&gt;</td>' ;
			
			return $line ;
		}
		
		/**
		*	@param Person $person
		*	@return string drop-down of a person's forms and courses
		*/
		private static function copg( Person $person ) {
			$line = '<td><select id="copg" onchange="ajaxForm( false );" >' ;
			
			foreach( $person->getForms() as $f ) {
				$line .= '<option value="f:' . $f->getId() . '"' ;
				$line .= ' >' . $f->getNorm() . '</option>' ;
			}
			
			foreach( $person->getCourses() as $c ) {
				$line .= '<option value="c:' . $c->getId() . '"' ;
				$line .= ' >' . $c->getId() . '</option>' ;
			}
			
			$line .= '</select></td>' ;
			
			return $line ;
		}
		
		/**
		*	@param Course $course
		*	@param int $week
		*	@param bool $edit
		*	@return string Absence table for $course. Can be editable.
		*/
		private static function table_course( Course $course , $week , $edit ) {
			$line = '' ;
			$line .= '<table id="absences" border="1" style="border-collapse: collapse; border-color: lime;" ><thead><tr><th colspan="2" >Lessons</th>' ;
			if( $course->getCount( $week , 1 ) > 0 ) {
				$line .= '<th colspan="' . $course->getCount( $week , 1 ) . '" >Monday</th>' ;
			}
			if( $course->getCount( $week , 2 ) > 0 ) {
				$line .= '<th colspan="' . $course->getCount( $week , 2 ) . '" >Tuesday</th>' ;
			}
			if( $course->getCount( $week , 3 ) > 0 ) {
				$line .= '<th colspan="' . $course->getCount( $week , 3 ) . '" >Wednesday</th>' ;
			}
			if( $course->getCount( $week , 4 ) > 0 ) {
				$line .= '<th colspan="' . $course->getCount( $week , 4 ) . '" >Thursday</th>' ;
			}
			if( $course->getCount( $week , 5 ) > 0 ) {
				$line .= '<th colspan="' . $course->getCount( $week , 5 ) . '" >Friday</th>' ;
			}
			$line .='</tr><tr><th>Surname</th><th>First Name</th>' ;
			for( $d = 1 ; $d <= 5 ; $d++ ) {
				foreach( $course->getLessons( $week , $d ) as $key => $value ) {
					$line .= '<th style="width: 20px;" >' . $key . '</th>' ;
					unset( $key ) ;
					unset( $value ) ;
				}
			}
			$line .= '</tr></thead><tbody>' ;
			
			foreach( $course->getStudents() as $student ) {
				$abs = Absence::abs_type( $student->getAbs( $week ) ) ;
				$combined = Person::combine_course( $course->getLessons( $week ) , $abs ) ;
				$redirect = ' onclick="ajaxStudent( \'' . $student->getId() . '\' ) ;" ' ;
				
				$line .= '<tr>' ;
				$line .= "<td" . $redirect . ">" . $student->getSurname() . "</td>" ;
				$line .= "<td" . $redirect . ">" . $student->getFirst() . "</td>" ;
				foreach( $combined as $key => $c ) {
					$day = (int) ( $key / 12 ) ;
					$lesson = $key % 12 ;
					if( $lesson == 0 ) {
						$lesson = 12 ;
						$day -- ;
					}
					if( $edit ) {
						$line .= self::colour_edit( $c , $student->getId() , $week , $day , $lesson ) ;
					} else {
						$line .= self::colour_cell( $c ) ;
					}
				}
				$line .= '</tr>' ;
			}
			
			$line .= '</tbody></table><br />' ;
			$line .= '<div>' ;
			$line .= '<input type=button value="' ;
			if( $edit ) {
				$line .= 'Cancel' ;
				$edittext = 'false' ;
			} else {
				$line .= 'Edit' ;
				$edittext = 'true' ;
			}
			$line .= '" onclick="ajaxForm( ' . $edittext . ');" />' ;
			if( $edit ) {
				$line .= '<input type="button" value="Save" onclick="ajaxSave() ;" /></form>' ;
			}
			$line .= '</div>' ;
			
			return $line ;
		}
		
		/**
		*	@param Form $form
		*	@param int $week
		*	@return string Absence table for $form.
		*/
		private static function table_form( Form $form , $week ) {
			$line =	'<table border="1" style="border-collapse: collapse; border-color: lime;" >
						<thead>
							<tr>
								<th colspan="4" >Lessons</th>
								<th colspan="12" >Monday</th>
								<th colspan="12" >Tuesday</th>
								<th colspan="12" >Wednesday</th>
								<th colspan="12" >Thursday</th>
								<th colspan="12" >Friday</th>
							</tr>
							<tr>
								<th>Surname</th>
								<th>First Name</th>
								<th>AUs Total</th>
								<th>AUs Week</th>' ;
			
			for( $d = 1 ; $d <= 5 ; $d++ ) {
				for( $l = 1 ; $l <= 12 ; $l++ ) {
					$line .= '<th style="width: 20px;" >' . $l . '</th>' ;
				}
			}
			
			$line .=	'</tr></thead><tbody>' ;
			
			foreach( $form->getStudents() as $student ) {
				$redirect = ' onclick="ajaxStudent( \'' . $student->getId() . '\' ) ;" ' ;
				//$combined = Functions::combine( $student ) ;
				$combined_week = $student->getCombined( $week ) ;
				$abs = $student->getTotal() ;
				$abs_week = $abs[$week] ;
				
				$line .= '<tr>' ;
				$line .= "<td" . $redirect . ">" . $student->getSurname() . "</td>" ;
				$line .= "<td" . $redirect . ">" . $student->getFirst() . "</td>" ;
				$line .= '<td' . $redirect . '>' . $abs['c_tot'] . '</td>' ;
				$line .= '<td>' . $abs_week['c_tot'] . '</td>' ;
				
				
				for( $d = 1 ; $d <= 5 ; $d++ ) {
					for( $l = 1 ; $l <= 12 ; $l++ ) {
						$line .= self::colour_cell( $combined_week[ $d * 12 + $l ] ) ;
					}
				}
				$line .= '</tr>' ;
			}
			$line .= '</tbody></table>' ;
			
			return $line ;
			
		}
			
		/**
		*	@param string $html
		*	@return string Prettified HTML
		*/
		public static function tidy( $html ) {	//move config
			if( USE_TIDY ) {
				$config = array(
								'indent'		=> true		,
								'output-xml'	=> true		,
								'input-xml'		=> true		,
								'wrap'			=> '1000'	) ;
				
				$tidy = new tidy() ;
				$tidy->parseString( $html , $config ) ;
				$tidy->cleanRepair() ;
				return tidy_get_output($tidy) ;
			} else {
				return $html ;
			}
		}
		
		/**
		*	@param Person $ad
		*	@return string 1 or 3 fielded form.
		*/
		public static function form( Person $ad ) {
			$line = '<table><thead></thead><tbody><tr>' ;
			$type = $ad->getType() ;
			switch( $type ) {
				case 8 :
					$line .= self::abs_pr() ;
					break ;
				case 7 :
				case 10 :
				case 100 :
					$line .= self::abs_pr() ;
					$line .= self::copg( $ad ) ;
					$line .= self::weeks( new Period( abs_pr ) ) ;
					break ;
				default :
					return '' ;
					break ;
			}
			$line .= '</tr></tbody></table>' ;
			return $line ;
		}
		
		/**
		*	@param string $str
		*	@return string First character of $str.
		*/
		public static function char1( $str ) {
			return $str[ 0 ] ;
		}
		
	}
?>
