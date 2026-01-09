CREATE FUNCTION `getNoDokumen`(ref varchar(50)) RETURNS varchar(50) CHARSET utf8mb4
    DETERMINISTIC
BEGIN
	DECLARE min_x INTEGER;
	DECLARE max_x INTEGER;
	SELECT MIN(no_dokumen), MAX(no_dokumen) INTO min_x, max_x FROM tr_pelayanan_no WHERE tr_pelayanan_id=ref;
	IF(min_x IS NULL) THEN RETURN '-';
	ELSE
		IF(min_x=max_x AND min_x) THEN 
			RETURN LPAD(min_x,5,'0');
		ELSE 
			RETURN CONCAT(LPAD(min_x,5,'0'),' - ',LPAD(max_x,5,'0'));
		END IF;
	END IF;
	RETURN '';
END