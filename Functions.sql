DELIMITER $$

DROP FUNCTION IF EXISTS `pg_regex` $$
CREATE DEFINER = `schades`@`localhost` FUNCTION
	pg_regex( pg VARCHAR(5) )
	RETURNS VARCHAR(13)
		BEGIN
			DECLARE pg_type CHAR(1) ;
			SET pg = REPLACE( REPLACE( pg , ' ' , '' ) , '_' , '' ) ;
			SET pg_type = SUBSTRING( pg , -1 ) ;
			SET pg = SUBSTRING( pg , 1 , 2 ) ;
			RETURN IF( pg_type = 'D' OR pg_type = 'F' , CONCAT( '_?' , pg , ' ?[dfDF]' ) , CONCAT( pg , ' ?[^dfDF]' ) ) ;
		END ; $$

DROP FUNCTION IF EXISTS `co_forms` $$
CREATE DEFINER = `schades`@`localhost` FUNCTION
	co_forms( forms VARCHAR(50) )
	RETURNS VARCHAR(50)
		BEGIN
			DECLARE result VARCHAR(50) DEFAULT'' ;
			SET forms = CONCAT( SUBSTRING_INDEX( SUBSTRING_INDEX( forms , '(' , -1 ) , ')' , 1 ) , '/' ) ;
			SET result = pg_regex( SUBSTRING_INDEX( forms , '/' , 1 ) ) ;
			SET forms = SUBSTRING( forms , LOCATE( '/' , forms ) + 1 ) ;
			WHILE forms != '' DO
				SET result = CONCAT( result , '|' , pg_regex( SUBSTRING_INDEX( forms , '/' , 1 ) ) ) ;
				SET forms = SUBSTRING( forms , LOCATE( '/' , forms ) + 1 ) ;
			END WHILE ;
			RETURN result ;
		END ; $$

DROP FUNCTION IF EXISTS `co_type` $$
CREATE DEFINER = `schades`@`localhost` FUNCTION
	co_type( id VARCHAR(50) )
	RETURNS VARCHAR(5)
		BEGIN
			SET id = SUBSTRING( id , 1 , 2 ) ;
			IF id = 'EF' THEN 
				RETURN 'x' ;
			ELSEIF id = 'FF' THEN
				RETURN '#' ;
			ELSE
				RETURN '[^x#]' ;
			END IF ;
		END ; $$

DROP FUNCTION IF EXISTS `get_abs_year` $$
CREATE DEFINER = `schades`@`localhost` FUNCTION
	get_abs_year( w TINYINT , pr TINYINT )
	RETURNS YEAR(4)
		BEGIN
			DECLARE return_year YEAR(4) DEFAULT 0000 ;
			DECLARE start_date DATE ;
			DECLARE end_date DATE ;
			SELECT `abs_start` , `abs_end`
				FROM `pr`
				WHERE `pr_id` = pr
				INTO start_date , end_date ;
			SET return_year = YEAR( start_date ) ;
			IF w < WEEK( end_date ) THEN
				BEGIN
					SET return_year = YEAR( end_date ) ;
				END ;
			END IF ;
			RETURN return_year ;
		END ; $$

DROP FUNCTION IF EXISTS `get_ad_year` $$
CREATE DEFINER = `schades`@`localhost` FUNCTION
	get_ad_year( w TINYINT , pr TINYINT )
	RETURNS YEAR(4)
		BEGIN
			DECLARE return_year YEAR(4) DEFAULT 0000 ;
			DECLARE start_date DATE ;
			DECLARE end_date DATE ;
			SELECT `ad_start` , `ad_end`
				FROM `pr`
				WHERE `pr_id` = pr
				INTO start_date , end_date ;
			SET return_year = YEAR( start_date ) ;
			IF w < WEEK( end_date ) THEN
				BEGIN
					SET return_year = YEAR( end_date ) ;
				END ;
			END IF ;
			RETURN return_year ;
		END ; $$

DROP FUNCTION IF EXISTS `set_abs_in` $$
CREATE DEFINER = `schades`@`localhost` FUNCTION
	set_abs_in( person INT(11) , w TINYINT , d TINYINT , l TINYINT , val CHAR(1) , exec INT(11) )
	RETURNS INT(11)
		BEGIN
			DECLARE return_id INT(11) DEFAULT NULL ;
			SELECT `abs_id`
				FROM `abs_abs`
				WHERE `ad_id`  = person AND
				      `week`   = w      AND
				      `day`    = d      AND
				      `lesson` = l
				INTO return_id ;
			IF ISNULL( return_id ) THEN
				BEGIN
					INSERT INTO `abs_abs`
						SET `abs_id`   = DEFAULT ,
						    `ad_id`    = person  ,
						    `week`     = w       ,
						    `day`      = d       ,
						    `lesson`   = l       ,
						    `in`       = val     ;
					SET return_id = LAST_RETURN_ID() ;
					INSERT INTO `abs_log_abs`
						SET `log_abs_id`  = DEFAULT   ,
						    `abs_id`      = return_id ,
						    `before`      = NULL      ,
						    `after`       = val       ,
						    `in_out`      = 'in'      ,
						    `executor_id` = exec      ,
						    `date_time`   = DEFAULT   ;
				END ;
			ELSE
				BEGIN
					DECLARE b CHAR(1) DEFAULT '?' ;
					SELECT `in`
						FROM `abs_abs`
						WHERE `abs_id` = return_id
						INTO b ;
					UPDATE `abs_abs`
						SET `in` = val
						WHERE `abs_id` = return_id ;
					INSERT INTO `abs_log_abs`
						SET `log_abs_id`  = DEFAULT   ,
						    `abs_id`      = return_id ,
						    `before`      = b         ,
						    `after`       = val       ,
						    `in_out`      = 'in'      ,
						    `executor_id` = exec      ,
						    `date_time`   = DEFAULT   ;
				END ;
			END IF ;
			RETURN return_id ;
		END ; $$
		
DROP FUNCTION IF EXISTS `set_abs_out` $$
CREATE DEFINER = `schades`@`localhost` FUNCTION
	set_abs_out( person INT(11) , w TINYINT , d TINYINT , l TINYINT , val CHAR(1) , exec INT(11) )
	RETURNS INT(11)
		BEGIN
			DECLARE return_id INT(11) DEFAULT NULL ;
			SELECT `abs_id`
				FROM `abs_abs`
				WHERE `ad_id`  = person AND
				      `week`   = w      AND
				      `day`    = d      AND
				      `lesson` = l
				INTO return_id ;
			IF ISNULL( return_id ) THEN
				BEGIN
					INSERT INTO `abs_abs`
						SET `abs_id`   = DEFAULT ,
						    `ad_id`    = person  ,
						    `week`     = w       ,
						    `day`      = d       ,
						    `lesson`   = l       ,
						    `out`      = val     ;
					SET return_id = LAST_RETURN_ID() ;
					INSERT INTO `abs_log_abs`
						SET `log_abs_id`  = DEFAULT   ,
						    `abs_id`      = return_id ,
						    `before`      = NULL      ,
						    `after`       = val       ,
						    `in_out`      = 'out'     ,
						    `executor_id` = exec      ,
						    `date_time`   = DEFAULT   ;
				END ;
			ELSE
				BEGIN
					DECLARE b CHAR(1) DEFAULT '?' ;
					SELECT `out`
						FROM `abs_abs`
						WHERE `abs_id` = return_id
						INTO b ;
					UPDATE `abs_abs`
						SET `out` = val
						WHERE `abs_id` = return_id ;
					INSERT INTO `abs_log_abs`
						SET `log_abs_id`  = DEFAULT   ,
						    `abs_id`      = return_id ,
						    `before`      = b         ,
						    `after`       = val       ,
						    `in_out`      = 'out'     ,
						    `executor_id` = exec      ,
						    `date_time`   = DEFAULT   ;
				END ;
			END IF ;
			RETURN return_id ;
		END ; $$

DELIMITER ;

#Student
SELECT `tt_tt`.`week` , `tt_tt`.`day` , `tt_tt`.`lesson` , `tt_tt`.`class` , `tt_tt`.`subject` , `tt_tt`.`teacher` , `co_co`.`name` , `abs_abs`.`out`
	FROM `ad_ad` #for clarity, we could also use eg. ad_pg and join to it
	
	#join with form
	JOIN `ad_pg`      ON       `ad_ad`.`ad_id`              =      `ad_ad`.`ad_id`
	JOIN `pg_full`    ON       `pg_full`.`pg_id`            =      `ad_pg`.`pg_id`
	JOIN `ad_pg_type` ON       `ad_pg_type`.`ad_pg_type_id` =      `ad_pg`.`ad_pg_type` #for clarity
	
	#join with absences
	JOIN `abs_abs`    ON       `abs_abs`.`ad_id`            =      `ad_ad`.`ad_id`
	
	#join with courses
	JOIN `ad_co`      ON       `ad_co`.`ad_id`              =      `ad_ad`.`ad_id`
	JOIN `co_co`      ON (     `co_co`.`co_id`              =      `ad_co`.`co_id`
	                       AND `co_co`.`name`               REGEXP `pg_full`.`pg_sql` )
	JOIN `ad_co_type` ON       `ad_co_type`.`ad_co_type_id` =      `ad_co`.`ad_co_type` #for clarity #error
	
	#join with lessons
	JOIN `tt_mapping` ON       `tt_mapping`.`co_type_id`    =      `co_co`.`co_type_id`
	JOIN `tt_tt`      ON (     `tt_tt`.`subject`            =      `tt_mapping`.`tt_short_name`
	                       AND `tt_tt`.`week`               =      `abs_abs`.`week`
	                       AND `tt_tt`.`day`                =      `abs_abs`.`day`
	                       AND `tt_tt`.`lesson`             =      `abs_abs`.`lesson` 
	                       AND `tt_tt`.`class`              REGEXP `pg_full`.`pg_sql` )
	
	#join with terms
	JOIN `pr`         ON (     `pr`.`pr_id`                 =      `co_co`.`pr_id`
	                       OR  `pr`.`pr_id` - 1             =      `co_co`.`pr_id` )
	
	WHERE     `ad_pg_type`.`ad_pg_type_id` = 1 #Student
	      AND `ad_co_type`.`ad_co_type_id` = 1 #Student
	      AND `ad_ad`.`ad_id`              = ?
	      AND `pr`.`pr_id`                 = ?
	ORDER BY  `tt_tt`.`week` , `tt_tt`.`day` , `tt_tt`.`lesson` ;

#Teacher
SELECT DISTINCT `ad_ad`.`surname` , `ad_ad`.`first_name` , `tt_tt`.`week` , `tt_tt`.`day` , `tt_tt`.`lesson` , `tt_tt`.`class` , `tt_tt`.`subject` , `tt_tt`.`teacher` , `co_full`.`name` , `abs_abs`.`in`
	FROM `co_full`
	
	#join with terms
	JOIN `pr`         ON      `pr`.`pr_id`                  =      `co_full`.`pr_id` #for future DB architecture
	
	#join with lessons
	JOIN `tt_mapping` ON (     `tt_mapping`.`co_type_id`    =      `co_full`.`co_type_id`
	                       AND `tt_mapping`.`tt_short_name` REGEXP `co_full`.`co_sql` )
	JOIN `tt_tt`      ON (     `tt_tt`.`subject`            =      `tt_mapping`.`tt_short_name`
	                       AND `tt_tt`.`class`              REGEXP `co_full`.`pg_sql` )
	
	#join with students
	JOIN `ad_co`      ON       `ad_co`.`co_id`              =      `co_full`.`co_id`
	JOIN `ad_ad`      ON       `ad_ad`.`ad_id`              =      `ad_co`.`ad_id` #for clarity
	JOIN `ad_co_type` ON       `ad_co_type`.`ad_co_type_id` =      `ad_co`.`ad_co_type` #for clarity
	
	#join with absences
	JOIN `abs_abs`    ON (     `abs_abs`.`ad_id`            =      `ad_ad`.`ad_id`
	                       AND `abs_abs`.`week`             =      `tt_tt`.`week`
	                       AND `abs_abs`.`day`              =      `tt_tt`.`day`
	                       AND `abs_abs`.`lesson`           =      `tt_tt`.`lesson` )
	
	WHERE     `ad_co_type`.`ad_co_type_id` = 1 #Student
	      AND `co_full`.`co_id`            = ?
	      AND `tt_tt`.`week`               = ?
	ORDER BY `ad_ad`.`surname` , `ad_ad`.`first_name` , `tt_tt`.`day` , `tt_tt`.`lesson` ; #error

# Permissions view
SELECT `t1`.`ad_id`      AS `student`  ,
       `t2`.`ad_id`      AS `inquirer` ,
       `t2`.`ad_pg_type` AS `type`     ,
       `pg`.`ad_pr`      AS `ad_pr`
	FROM `ad_pg` AS `t1`
	JOIN `pg`            ON `pg`.`pg_id` = `t1`.`pg_id`
	JOIN `ad_pg` AS `t2` ON `t2`.`pg_id` = `t1`.`pg_id`
	WHERE     `t1`.`ad_pg_type`  = 1 # Student
	      AND `t2`.`ad_pg_type` != 1 # Not Student
	UNION
		SELECT `t3`.`ad_id`      AS `student`  ,
		       `t4`.`ad_id`      AS `inquirer` ,
		       `t4`.`ad_co_type` AS `type`     ,
		       `co_co`.`pr_id`   AS `ad_pr`
			FROM `ad_co` AS `t3`
			JOIN `co_co`         ON `co_co`.`co_id` = `t3`.`co_id`
			JOIN `ad_co` AS `t4` ON `t4`.`co_id`    = `t3`.`co_id`
			WHERE     `t3`.`ad_co_type`  = 1 # Student
			      AND `t4`.`ad_co_type` != 1 # Not Student

