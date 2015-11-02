DELIMITER $$

DROP PROCEDURE IF EXISTS `fetchPsaAmendment`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `fetchPsaAmendment`(m_period_start int, m_period_end int, y_period_start varchar(10), y_period_end varchar(10), l_id INT, l_type INT)
BEGIN
 DECLARE l_psa_id INT; 
 DECLARE no_more_ea_am INT; 
 DECLARE l_amt DECIMAL(10,2); 
 DECLARE l_curr_amt DECIMAL(10,2); 
 DECLARE curr_csr CURSOR FOR select amt from z_temp where emp_id=l_id and psa_id=l_psa_id; 
 DECLARE ent_ea_am_csr CURSOR FOR select distinct a.psa_id from payroll_ps_account a 
 join payroll_ps_amendment b on (b.psa_id=a.psa_id) 
 join payroll_ps_amendemp c on (c.psamend_id=b.psamend_id) 
 join payroll_pay_stub d on (d.paystub_id=c.paystub_id) 
 join payroll_pay_period e on (e.payperiod_id=d.payperiod_id) 
 where a.psa_type=l_type and (e.payperiod_period >= m_period_start and e.payperiod_period <= m_period_end) 
 and (e.payperiod_period_year >= y_period_start and e.payperiod_period_year <= y_period_end) order by a.psa_order, a.psa_name; 
 DECLARE amt_csr CURSOR FOR select sum(a.amendemp_amount) as total from payroll_ps_amendemp a 
 join payroll_ps_amendment b on (b.psamend_id=a.psamend_id) 
 join payroll_pay_stub c on (c.paystub_id=a.paystub_id) 
 join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) 
 where a.emp_id=l_id and b.psa_id=l_psa_id and a.paystub_id!=0 and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end) 
 and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end); 
 
 DECLARE CONTINUE HANDLER FOR NOT FOUND SET no_more_ea_am=1; 
 SET no_more_ea_am=0; 
 OPEN ent_ea_am_csr; 
 ea_am_loop:WHILE(no_more_ea_am=0) DO 
 FETCH ent_ea_am_csr INTO l_psa_id; 
 IF no_more_ea_am=1 
 THEN LEAVE ea_am_loop; 
 END IF; SET l_amt = 0; 
 
 SET l_curr_amt = 0; 
 SET l_amt = 0; 
 OPEN amt_csr; 
 FETCH amt_csr INTO l_amt; 
 CLOSE amt_csr; 
 
 IF(l_amt IS NULL) THEN 
 SET l_amt = 0.00; 
 END IF; 
 
 IF(checkNonZeroInAmendment(m_period_start,m_period_end,y_period_start,y_period_end,l_psa_id)) THEN 
	 IF(checkIfPSAExists(l_psa_id,l_id)) THEN 
		OPEN curr_csr; 
		FETCH curr_csr INTO l_curr_amt; 
		CLOSE curr_csr; 
		SET @total = l_amt + l_curr_amt; 
		IF(@total IS NULL) THEN 
			SET @total = 0; 
		END IF; 
		UPDATE z_temp SET amt = @total WHERE emp_id=l_id and psa_id=l_psa_id; 
	 ELSE 
		INSERT INTO z_temp (psa_id, emp_id, amt) VALUES (l_psa_id, l_id, l_amt); 
	 END IF; 
 END IF; 
 END WHILE ea_am_loop; 
 CLOSE ent_ea_am_csr; 
 END$$

DELIMITER ;