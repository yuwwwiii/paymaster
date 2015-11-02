DELIMITER $$

DROP PROCEDURE IF EXISTS `statSummary`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `statSummary`(m_period varchar(10), y_period varchar(10))
BEGIN
 DECLARE l_id INT;
 DECLARE l_emp_idnum VARCHAR(30); 
 DECLARE l_lastname VARCHAR(30); 
 DECLARE l_firstname VARCHAR(30);
 DECLARE l_middlename VARCHAR(30);
 DECLARE l_salary DECIMAL(10,2);

 DECLARE l_deduction DECIMAL(10,2);
 DECLARE l_sss_num VARCHAR(30);
 DECLARE l_sss_contribution DECIMAL(10,2);
 DECLARE l_phic_num VARCHAR(30);
 DECLARE l_phic_contribution DECIMAL(10,2);
 DECLARE l_hdmf_num VARCHAR(30);
 DECLARE l_hdmf_contribution DECIMAL(10,2);
 DECLARE no_more_emp INT;
 DECLARE l_emp_count INT;

 DECLARE emp_csr CURSOR FOR
 select distinct a.emp_id
 from emp_masterfile a
 inner join emp_personal_info b on (b.pi_id=a.pi_id)
 inner join payroll_paystub_report c on (c.emp_id=a.emp_id)
 inner join payroll_pay_period d on (d.payperiod_id=c.payperiod_id)
 where d.payperiod_period=m_period and d.payperiod_period_year=y_period
 order by b.pi_lname;

 DECLARE emp_info_csr CURSOR FOR
 select a.emp_idnum, b.pi_lname, b.pi_fname, b.pi_mname, replace(b.pi_sss,'-',''), replace(b.pi_phic,'-',''), replace(b.pi_hdmf,'-','')
 from emp_masterfile a
 inner join emp_personal_info b on (b.pi_id=a.pi_id)
 WHERE a.emp_id=l_id;

 DECLARE salary_csr CURSOR FOR
 select sum(a.ppe_amount) 
 from payroll_paystub_entry a
 inner join payroll_paystub_report b on (b.paystub_id=a.paystub_id)
 inner join payroll_pay_period c on (c.payperiod_id=b.payperiod_id)
 where b.emp_id=l_id and c.payperiod_period=m_period and c.payperiod_period_year=y_period and (a.psa_id=4 or a.psa_id=25 or a.psa_id=26);

 DECLARE deduction_csr CURSOR FOR

 select sum(a.ppe_amount) 

 from payroll_paystub_entry a

 inner join payroll_paystub_report b on (b.paystub_id=a.paystub_id)

 inner join payroll_pay_period c on (c.payperiod_id=b.payperiod_id)

 where b.emp_id=l_id and c.payperiod_period=m_period and c.payperiod_period_year=y_period and (a.psa_id=33 or a.psa_id=34);

 DECLARE sss_contribution_csr CURSOR FOR
 select sum(a.ppe_amount) 
 from payroll_paystub_entry a
 inner join payroll_paystub_report b on (b.paystub_id=a.paystub_id)
 inner join payroll_pay_period c on (c.payperiod_id=b.payperiod_id)
 where b.emp_id=l_id and c.payperiod_period=m_period and c.payperiod_period_year=y_period and a.psa_id=7;

 DECLARE phic_contribution_csr CURSOR FOR
 select sum(a.ppe_amount) 
 from payroll_paystub_entry a
 inner join payroll_paystub_report b on (b.paystub_id=a.paystub_id)
 inner join payroll_pay_period c on (c.payperiod_id=b.payperiod_id)
 where b.emp_id=l_id and c.payperiod_period=m_period and c.payperiod_period_year=y_period and a.psa_id=14;

 DECLARE hdmf_contribution_csr CURSOR FOR
 select sum(a.ppe_amount) 
 from payroll_paystub_entry a
 inner join payroll_paystub_report b on (b.paystub_id=a.paystub_id)
 inner join payroll_pay_period c on (c.payperiod_id=b.payperiod_id)
 where b.emp_id=l_id and c.payperiod_period=m_period and c.payperiod_period_year=y_period and a.psa_id=15;

 DECLARE CONTINUE HANDLER FOR NOT FOUND SET no_more_emp=1;
 SET no_more_emp=0;
 OPEN emp_csr;
 emp_loop:WHILE(no_more_emp=0) DO
 FETCH emp_csr INTO l_id;
 OPEN emp_info_csr;
 FETCH emp_info_csr INTO l_emp_idnum, l_lastname, l_firstname, l_middlename, l_sss_num, l_phic_num, l_hdmf_num;
 CLOSE emp_info_csr;

 OPEN salary_csr;
 FETCH salary_csr INTO l_salary;
 CLOSE salary_csr;

 IF(l_salary IS NULL) THEN
	SET l_salary = 0.00;
 END IF;

 OPEN deduction_csr;
 FETCH deduction_csr INTO l_deduction;
 CLOSE deduction_csr;

 IF(l_deduction IS NULL) THEN
	SET l_deduction = 0.00;
 END IF;

 SET l_salary = l_salary - l_deduction;

 OPEN sss_contribution_csr;
 FETCH sss_contribution_csr INTO l_sss_contribution;
 CLOSE sss_contribution_csr;

 IF(l_sss_contribution IS NULL) THEN
	SET l_sss_contribution = 0.00;
 END IF;

 OPEN phic_contribution_csr;
 FETCH phic_contribution_csr INTO l_phic_contribution;
 CLOSE phic_contribution_csr;

 IF(l_phic_contribution IS NULL) THEN
	SET l_phic_contribution = 0.00;
 END IF;

 OPEN hdmf_contribution_csr;
 FETCH hdmf_contribution_csr INTO l_hdmf_contribution;
 CLOSE hdmf_contribution_csr;

 IF(l_hdmf_contribution IS NULL) THEN
	SET l_hdmf_contribution = 0.00;
 END IF;

 IF no_more_emp=1 THEN
 LEAVE emp_loop;
 END IF;
 SET l_emp_count=l_emp_count+1;
 select l_emp_idnum, l_lastname, l_firstname, l_middlename, l_salary, l_sss_num, l_sss_contribution, l_phic_num, l_phic_contribution, l_hdmf_num, l_hdmf_contribution;
 END WHILE emp_loop;
 CLOSE emp_csr;
 SET no_more_emp=0;
 END$$

DELIMITER ;