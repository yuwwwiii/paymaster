DELIMITER $$

DROP FUNCTION IF EXISTS `checkIfEntryIsNotZero`$$

CREATE DEFINER=`root`@`localhost` FUNCTION `checkIfEntryIsNotZero`(m_period_start INT, m_period_end INT, y_period_start INT, y_period_end INT, emp_id INT, psa_id INT) RETURNS tinyint(1)
BEGIN DECLARE val tinyint;
 IF((select sum(ppe_amount) as total from payroll_paystub_entry a join payroll_pay_stub b on (b.paystub_id=a.paystub_id) join payroll_paystub_report c on (c.paystub_id=b.paystub_id) join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) where a.psa_id=psa_id  and c.emp_id=emp_id and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end) and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end)) != 0) THEN SET val = TRUE; ELSE SET val = FALSE; END IF; RETURN val;
 END$$

DELIMITER ;

DELIMITER $$

DROP FUNCTION IF EXISTS `checkIfNotZero`$$

CREATE DEFINER=`root`@`localhost` FUNCTION `checkIfNotZero`(m_period_start INT, m_period_end INT, y_period_start INT, y_period_end INT, emp_id INT, param_id INT, tble varchar(30)) RETURNS tinyint(1)
BEGIN
 DECLARE val tinyint;IF tble = "TA" THEN	 IF ((select sum(emp_tarec_amtperrate) as total from ta_emp_rec a	 join ta_tbl b on (b.tatbl_id=a.tatbl_id)	 join payroll_pay_period c on (c.payperiod_id=a.payperiod_id)	 where a.tatbl_id=param_id	 and a.emp_id=emp_id  	 and (c.payperiod_period >= m_period_start and c.payperiod_period <= m_period_end)	 and (c.payperiod_period_year >= y_period_start and c.payperiod_period_year <= y_period_end)) != 0) THEN	 SET val = TRUE;	 ELSE	 SET val = FALSE;	 END IF;ELSEIF tble = "ENTRY" THEN	IF ((select sum(ppe_amount) as total from payroll_paystub_entry a	inner join payroll_pay_stub b on (b.paystub_id=a.paystub_id)	inner join payroll_paystub_report c on (c.paystub_id=b.paystub_id)	inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)	where a.psa_id=param_id 	and c.emp_id=emp_id 	and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end)	and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end)) > 0) THEN	SET val = TRUE;	ELSE	SET val = FALSE;	END IF;END IF; RETURN val;
 END$$

DELIMITER ;

DELIMITER $$

DROP FUNCTION IF EXISTS `checkIfPayperiodExists`$$

CREATE DEFINER=`root`@`localhost` FUNCTION `checkIfPayperiodExists`(p_period INT, p_year INT) RETURNS tinyint(1)
BEGIN DECLARE val tinyint;
 IF ((select count(1) from payroll_pay_period where payperiod_period=p_period and payperiod_period_year=p_year) > 0) THEN SET val = true; ELSE SET val = false; END IF; RETURN val;
 END$$

DELIMITER ;

DELIMITER $$

DROP FUNCTION IF EXISTS `checkIfPSAExists`$$

CREATE DEFINER=`root`@`localhost` FUNCTION `checkIfPSAExists`(p_psa_id int, p_emp_id int) RETURNS tinyint(1)
BEGIN DECLARE val BOOL; if(exists(select 1 from z_temp where psa_id=p_psa_id and emp_id=p_emp_id) = 1) then  set val = true; else set val = false; end if; return val;
 END$$

DELIMITER ;

DELIMITER $$

DROP FUNCTION IF EXISTS `checkNonZeroInAmendment`$$

CREATE DEFINER=`root`@`localhost` FUNCTION `checkNonZeroInAmendment`(m_period_start int, m_period_end int, y_period_start varchar(10), y_period_end varchar(10), m_psa_id INT) RETURNS tinyint(1)
BEGIN
 DECLARE val BOOL; 
IF(((select sum(a.amendemp_amount) from payroll_ps_amendemp a 
join payroll_ps_amendment b on (b.psamend_id=a.psamend_id) 
join payroll_pay_stub c on (c.paystub_id=a.paystub_id) 
join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) 
where b.psa_id=m_psa_id and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end) 
and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end)) != 0)) THEN 
set val = true; ELSE  set val = false; END IF; return val;
 END$$

DELIMITER ;

DELIMITER $$

DROP FUNCTION IF EXISTS `checkNonZeroInEntry`$$

CREATE DEFINER=`root`@`localhost` FUNCTION `checkNonZeroInEntry`(m_period_start int, m_period_end int, y_period_start varchar(10), y_period_end varchar(10), m_psa_id INT) RETURNS tinyint(1)
BEGIN
 DECLARE val BOOL; IF(((select sum(ppe_amount) as total from payroll_paystub_entry a inner join payroll_pay_stub b on (b.paystub_id=a.paystub_id) inner join payroll_paystub_report c on (c.paystub_id=b.paystub_id) inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) 
where a.psa_id=m_psa_id and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end) 
and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end)) != 0)) THEN set val = true; ELSE  set val = false; END IF; return val;
 END$$

DELIMITER ;

DELIMITER $$

DROP FUNCTION IF EXISTS `checkNonZeroInLoan`$$

CREATE DEFINER=`root`@`localhost` FUNCTION `checkNonZeroInLoan`(m_period_start int, m_period_end int, y_period_start varchar(10), y_period_end varchar(10), m_psa_id INT) RETURNS tinyint(1)
BEGIN
 DECLARE val BOOL; 
 IF(((select sum(a.loansum_payment) 
 from loan_detail_sum a 
 join loan_info b on (b.loan_id=a.loan_id) 
 join payroll_pay_stub c on (c.paystub_id=a.paystub_id) 
 join payroll_pay_period d on (d.payperiod_id=c.payperiod_id) 
 where b.psa_id=m_psa_id and (d.payperiod_period >= m_period_start and d.payperiod_period <= m_period_end) 
 and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end)) != 0)) 
 THEN 
	set val = true; 
 ELSE 
	set val = false; 
 END IF; 
 return val;
 END$$

DELIMITER ;