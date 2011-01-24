<?php
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