DELIMITER $$

DROP FUNCTION `abs_in` $$
CREATE DEFINER = `schades`@`localhost` FUNCTION
	abs_in( person INT(11) , w TINYINT , d TINYINT , l TINYINT , val CHAR(1) , exec INT(11) )
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
		END $$
		
DROP FUNCTION `abs_out` $$
CREATE DEFINER = `schades`@`localhost` FUNCTION
	abs_out( person INT(11) , w TINYINT , d TINYINT , l TINYINT , val CHAR(1) , exec INT(11) )
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
		END $$

DELIMITER ;
