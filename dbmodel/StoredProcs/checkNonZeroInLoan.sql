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
 and (d.payperiod_period_year >= y_period_start and d.payperiod_period_year <= y_period_end)) > 0)) 
 THEN 
	set val = true; 
 ELSE 
	set val = false; 
 END IF; 
 return val;
 END$$

DELIMITER ;