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
	
	/**
	* 	@brief Singleton class, children can only have on instance.
	*/
	class Singleton {
		
		/**
		* 	@brief Array of all the instances.
		*/
		private static $instances = array() ;
		
		/**
		* 	@brief Stop childrens' constructor from being called outside of them.
		*/
		protected __construct() {}
		
		/**
		*	@brief Cloning is not allowed.
		*/
		private final __clone() {}
		
		/**
		*	@brief Get the child's instance.
		*
		*	@return self::$instance[ $class ]
		*/
		public getInstance() {
			$class = get_called_class() ;
			if( !isset( self::$instances[ $class ] ) ) {
				self::$instances[ $class ] = new $class ;
			)
			
			return self::$instances[ $class ] ;
		}
	}
?>