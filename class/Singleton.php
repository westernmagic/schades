<?php
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with schades.  If not, see <http://www.gnu.org/licenses/>.
	*/
	/**
	*	@file Singleton.php
	*	
	*	@author Michal Sudwoj <mswoj61@gmail.com>
	*	@copyright Michal Sudwoj
	*	@link http://www.sourceforge.com/projects/schades/
	*	@licence http://www.fsf.org/licensing/licenses/agpl-3.0.html AGPL 3.0
	*	@version 0.8
	*/

	require_once( 'include.php' ) ;

	class Singleton {
		private static $instances = array() ;
		
		protected __construct() {}
		private final __clone() {}
		
		public getInstance() {
			$class = get_called_class() ;
			if( !isset( self::$instances[ $class ] ) ) {
				self::$instances[ $class ] = new $class ;
			)
			
			return self::$instances[ $class ] ;
		}
	}
?>