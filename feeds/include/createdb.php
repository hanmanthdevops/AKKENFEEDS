<?php
	$cpusername=strtolower($cpusername);
	$dataname=$cpusername;

	$que="DROP DATABASE $dataname";
	mysql_query($que,$db);

	$que="CREATE DATABASE $dataname";
	mysql_query($que,$db);

	mysql_select_db($dataname,$db);

	require(realpath("include/akken-db.php"));

	$logout="0000-00-00 00:00:00";
	$que="insert into users(username, userid, password, name, type, last_login, status,cdate) values('','".$legalname."','".$password."','".$spage1[1]." ".$spage1[2]." ".$spage1[3]."','sp','$logout','Super User',now())";
	mysql_query($que,$db);

	$reallegalname=$legalname;
	$legalname=mysql_insert_id($db);
	$name = trim($spage1[1]." ".$spage1[2]);
	$name = trim($name." ".$spage1[3]);
	
	//For inserting user as Applicant for API purpose.
	$que="insert into users(username, userid, password, name, type, last_login, status,cdate,um_cuser) values('','Applicant','Applicant','Applicant','cand','$logout','DA',now(),'".$legalname."')";
	mysql_query($que,$db);
	
	//To insert into userstatus
	$queUserStatus="insert into userstatus (sno,username,activated) values ('','".$legalname."',now())";
	mysql_query($queUserStatus,$db);	
	// To insert into company info table 
	//application.company_info.sno = sysadmin.companyinfo.sno
	$que="select accnumber from company_info where sno = '".$cid."'";
	$res = mysql_query($que,$maindb);
	$row = mysql_fetch_row($res);
	
	$accnum = $row[0];
	$que="insert into company_info (sno,company_name,fname,mname,lname,title,address1,address2,city,state,country,zip,email,phone1,phone2,fax,fid,accnumber,payroll_process) values (".$cid.",'".$spage1[0]."','".$spage1[1]."','".$spage1[2]."','".$spage1[3]."','".$spage1[4]."','".$spage1[5]."','".$spage1[6]."','".$spage1[7]."','".$spage1[8]."','".$spage1[9]."','".$spage1[10]."','".$spage1[11]."','".$spage1[12]."','".$spage1[13]."','".$spage1[14]."','".$spage1[15]."','".$accnum."','NONE')";
	mysql_query($que,$db);
	
	
	// To insert users as contacts of CRM companies of selected companies.
	$arrdata=array($spage1[1],$spage1[2],$spage1[3],$spage1[11]);
	insUpdAppCRMContact($arrdata,$legalname,'insert',$dataname);
		
	// End
	

	$que="insert into emp_list (username,approveuser,name,email,status,rate,location,skills,roles,cur_project,client_name,estatus,avail,astatus,lstatus,stime,type) values ('".$legalname."','".$legalname."','".$name."','".$spage1[11]."','','','','','','','','','','','ER',NOW(),'PE');";
	mysql_query($que,$db);
	
	$que="insert into sysuser(username, crm, hrm, collaboration, accounting, analytics, dashboard, admin, myprofile) values ('".$legalname."','crm+1+2+13+14+12+15+16+3+17+18+4+19+20+23+-+5+6+21+7+22+-+-+-+-','hrm+13+1+3+4+5+6+8+11+-+-+-+14','collaboration+1+2+3+4+5+6+7+8+9+-+-+10+-','accounting+1+2+3+4+5+6+7+-+-+-+11+12+13+14+15+16','analytics+1+2+3+4+-+6','NO','admin+1+16+5+13+-+-+-+-+-+12+-+14+-+-','myprofile+1+2+3+5+6+7+-+9+10+11+12+14+16+17+18+19');";
	mysql_query($que,$db);

	$mdsize="1024";
	$eaccname=str_replace(".","_",$cpusername)."1";
	$que="insert into EmailAcc(sno, username, emailuser, mquota, type) values ('','".$legalname."','$reallegalname','".$mdsize."','R')";
	mysql_query($que,$db);

	$que="insert into hrcon_general (username,fname,mname,lname,email,ustatus,udate) values ('".$legalname."','".$spage1[1]."','".$spage1[2]."','".$spage1[3]."','".$spage1[11]."','active',NOW());";
	mysql_query($que,$db);

	$que="insert into empcon_general (username,fname,mname,lname,email) values ('".$legalname."','".$spage1[1]."','".$spage1[2]."','".$spage1[3]."','".$spage1[11]."');";
	mysql_query($que,$db);

	$que="insert into empcon_jobs (username,jtype) values ('".$legalname."','AS')";
	mysql_query($que,$db);

	$que="insert into hrcon_compen (username,emp_id,dept,location,ustatus,udate) values ('1','1','1','1','active',NOW())";
	mysql_query($que,$db);
	$compen_id=mysql_insert_id($db);
	
	$que = "INSERT INTO employee_accounts(username , income_account , expense_account , liability_account , accounts_payable , ctime , mtime , status , compen_id) VALUES ('1', 9, 11, 6, 0, NOW(), NOW(), 'active', '".$compen_id."')";
	mysql_query($que,$db);
	
	$que="insert into empcon_compen (username,emp_id,dept,location) values ('1','1','1','1')";
	mysql_query($que,$db);

	$que="insert into hrcon_prof (username,objective,summary,addinfo,ustatus,udate) values ('1','','','','active',NOW())";
	mysql_query($que,$db);

	$que="insert into empcon_prof (username,objective,summary,addinfo) values ('1','','','')";
	mysql_query($que,$db);

	$que="insert into hrcon_status (username,arrivaldate,ssndate,ustatus,udate) values ('1','','','active',NOW())";
	mysql_query($que,$db);

	$que="insert into empcon_status (username,arrivaldate,ssndate) values ('1','','')";
	mysql_query($que,$db);

	$que="insert into hrcon_w4 (username,tax,ustatus,udate) values ('1','W-2','active',NOW())";
	mysql_query($que,$db);

	$que="insert into empcon_w4 (username,tax) values ('1','W-2')";
	mysql_query($que,$db);

	$que="insert into net_w4 (username,tax,ustatus,udate) values ('1','W-2','active',NOW())";
	mysql_query($que,$db);
	
	$que="insert into hrcon_desire (username,desirejob,desirestatus,ustatus,udate) values ('1','','','active',NOW())";
	mysql_query($que,$db);

	$que="insert into empcon_desire (username,desirejob,desirestatus) values ('1','','')";
	mysql_query($que,$db);

	$phone=substr($spage1[12],0,12);
	$ext=substr($spage1[12],13);
	$fax=$spage1[14];
	$address=$spage1[5]."|".$spage1[6];

	$que="insert into contact_manage (username,heading,company_name,address,city,state,country,zipcode,phone,fax,email,other,ptext,disind,discon,status,stime,feid, loccode, deflt)";
	$que.=" values ('".$legalname."','".$spage1[7]."','".$spage1[0]."','".$address."','".$spage1[7]."','".$spage1[8]."','".$spage1[9]."','".$spage1[10]."','".$phone."','".$fax."','".$spage1[11]."','".$other."','".$ext."','1','on','ER',NOW(),'".$spage1[15]."', '100', 'Y')";
	mysql_query($que,$db);

	$legalname=$reallegalname;

	$fd=fopen($default_comp_logo,"r");
	$content = fread ($fd, filesize ($default_comp_logo));
	fclose($fd);
	
	$que="insert into company_logo(image_data, image_type, image_size, username, udate ) values ('" . addslashes($content) . "','".mime_content_type($default_comp_logo)."','" . filesize ($default_comp_logo). "','1', NOW())";
	mysql_query($que,$db);
	
	
	// Inser queries from educeit.php
	/*
	$query = "INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (1, '1', 'AP', 'Accounts Payable', 0, 'ER', NOW(), 'Accounts Payable', '', '')";
mysql_query($query,$db);

	$query = "INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (2, '1', 'AR', 'Accounts receivable', 0, 'ER', NOW(), 'Accounts receivable', '', '')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (3, '1', 'BANK', 'BANK', 0, 'ER', NOW(), 'Checking or savings', '', '')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (4, '1', 'CCARD', 'Credit card account', 0, 'ER', NOW(), 'Credit card account', '', '')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (5, '1', 'COGS', 'Cost of goods sold', 0, 'ER', NOW(), 'Cost of goods sold', '', '')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (6, '1', 'EQUITY', 'Capital/Equity', 0, 'ER', NOW(), 'Capital/Equity', '', '')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (7, '1', 'EXEXP', 'Other expenses', 0, 'ER', NOW(), 'Other expenses', '', '')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (8, '1', 'EXINC', 'Other Income', 0, 'ER', NOW(), 'Other Income', '', '')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (9, '1', 'EXP', 'Expense', 0, 'ER', NOW(), 'Expense', '', '')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (10, '1', 'FIXASSET', 'Fixed Asset', 0, 'ER', NOW(), 'Fixed Asset', '', '')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (11, '1', 'INC', 'Income', 0, 'ER', NOW(), 'Income', '', '')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (12, '1', 'LTLIAB', 'Long term liability', 0, 'ER', NOW(), 'Long term liability', '', '')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (13, '1', 'OASSET', 'Other Asset', 0, 'ER', NOW(), 'Other Asset', '', '')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (14, '1', 'OCASSET', 'Other current asset', 0, 'ER', NOW(), 'Other current asset', '', '')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (15, '1', 'OCLIAB', 'Other current liability', 0, 'ER', NOW(), 'Other current liability', '', '')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (16, '1', 'EXP', 'Payroll Expenses', 9, 'ER', NOW(), 'Payroll Expenses', '', '')";
	mysql_query($query,$db);
	
	
	$query = "INSERT INTO reg_accdesc (sno, username, type, tdesc) VALUES (1, '1', 'AP', 'Account Payable')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_accdesc (sno, username, type, tdesc) VALUES (2, '1', 'AR', 'Account Receivable')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_accdesc (sno, username, type, tdesc) VALUES (3, '1', 'BANK', 'BANK')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_accdesc (sno, username, type, tdesc) VALUES (4, '1', 'COGS', 'Cost of Goods sold')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_accdesc (sno, username, type, tdesc) VALUES (5, '1', 'CCARD', 'Credit card')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_accdesc (sno, username, type, tdesc) VALUES (6, '1', 'EQUITY', 'Capital/Equity')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_accdesc (sno, username, type, tdesc) VALUES (7, '1', 'EXEXP', 'Other Expense')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_accdesc (sno, username, type, tdesc) VALUES (8, '1', 'EXINC', 'Other Income')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_accdesc (sno, username, type, tdesc) VALUES (9, '1', 'EXP', 'Expense')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_accdesc (sno, username, type, tdesc) VALUES (10, '1', 'FIXASSET', 'Fixed Asset')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_accdesc (sno, username, type, tdesc) VALUES (11, '1', 'INC', 'Income')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_accdesc (sno, username, type, tdesc) VALUES (12, '1', 'CLIAB', 'Current liability')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_accdesc (sno, username, type, tdesc) VALUES (13, '1', 'OASSET', 'Other Asset')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_accdesc (sno, username, type, tdesc) VALUES (14, '1', 'OCASSET', 'Other current asset')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_accdesc (sno, username, type, tdesc) VALUES (15, '1', 'OCLIAB', 'Other current liability')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_accdesc (sno, username, type, tdesc) VALUES (16, '1', 'LTLIAB', 'Long-term liability')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_accdesc (sno, username, type, tdesc) VALUES (17, '1', 'NP', 'Non-Posting')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO reg_accdesc (sno, username, type, tdesc) VALUES (18, '1', 'PAYEXP', 'Payroll Expense')";
	mysql_query($query,$db);
	*/
	
	$query = "INSERT INTO exp_type (sno, username, title) VALUES (1, 'Admin', 'AutoExpenses')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO exp_type (sno, username, title) VALUES (2, 'Admin', 'AutoRental')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO exp_type (sno, username, title) VALUES (3, 'Admin', 'BusTaxi')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO exp_type (sno, username, title) VALUES (4, 'Admin', 'Entertainment')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO exp_type (sno, username, title) VALUES (5, 'Admin', 'HotelCharges')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO exp_type (sno, username, title) VALUES (6, 'Admin', 'Meals.Self')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO exp_type (sno, username, title) VALUES (7, 'Admin', 'MealsBusiness')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO exp_type (sno, username, title) VALUES (8, 'Admin', 'Tips(Excl.Meals)')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO exp_type (sno, username, title) VALUES (9, 'Admin', 'TelephoneCharges')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO exp_type (sno, username, title) VALUES (10, 'Admin', 'Other')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO exp_type (sno, username, title) VALUES (11, 'Admin', 'Rail')";
	mysql_query($query,$db);
	
	$query = "INSERT INTO exp_type (sno, username, title) VALUES (12, 'Admin', 'Air')";
	mysql_query($query,$db);
	
	$query="INSERT INTO reportnames( reportnum, reportname, module) VALUES 
	(1, 'eCampaigns', 'CRM'),
	(2, 'eCampaigns last 30 days Summary', 'CRM'),
	(3, 'eCampaigns last 30 days details', 'CRM'),
	(4, 'Top eCampaign Responders', 'CRM'),
	(5, 'Top eCampaign Candidates', 'CRM'),
	(6, 'Top Candidate inquiries in eCampaign', 'CRM'),
	(7, 'Contacts', 'CRM'),
	(8, 'Companies', 'CRM'),
	(9, 'Candidates', 'CRM'),
	(10, 'Candidates available Summary-Last 30 days', 'CRM'),
	(11, 'Candidates available details-Last 30 days', 'CRM'),
	(12, 'Candidates available by skill', 'CRM'),
	(13, 'Candidates available by type', 'CRM'),
	(14, 'Candidates available by rate', 'CRM'),
	(15, 'Hot candidates - Last 30 Days', 'CRM'),
	(16, 'Employees on Bench Summary', 'CRM'),
	(17, 'Employees on Bench Detail', 'CRM'),
	(18, 'Employees on Bench by Skills', 'CRM'),
	(19, 'Employees on Bench by Rate', 'CRM'),
	(20, 'Hot Employees on Bench', 'CRM'),
	(21, 'Job Orders', 'CRM'),
	(22, 'Top Job Orders Last 30 Days', 'CRM'),
	(23, 'Top Customers by Number of Job Orders', 'CRM'),
	(24, 'Top Job Orders by Submissions', 'CRM'),
	(25, 'Top Closed Job Orders', 'CRM'),
	(26, 'Active Clients', 'CRM'),
	(27, 'Top Job Orders - Postings Last 30 Days', 'CRM'),
	(28, 'Top Postings - Responses Last 30 Days', 'CRM'),
	(29, 'Top Suppliers by Responses', 'CRM'),
	(30, 'My Placements', 'CRM'),
	(31, 'New Applications - last 30 days', 'HRM'),
	(32, 'Recruitment by stages', 'HRM'),
	(33, 'Recruitment by status', 'HRM'),
	(34, 'New Consultants - last 30 days', 'HRM'),
	(35, 'Consultants by stages', 'HRM'),
	(36, 'Consultant by status', 'HRM'),
	(37, 'Hiring Management', 'HRM'),
	(38, 'Hiring Review', 'HRM'),
	(39, 'Employees on Bench Detail', 'HRM'),
	(40, 'Employees on Bench Summary', 'HRM'),
	(41, 'Employees on Bench by Skills', 'HRM'),
	(42, 'Employees on Bench by Rate', 'HRM'),
	(43, 'Hot Employees on Bench', 'HRM'),
	(44, 'Employee Review', 'HRM'),
	(45, 'Benefits', 'HRM'),
	(46, 'Departments', 'HRM'),
	(47, 'Assignments', 'HRM'),
	(48, 'Account Listing', 'Accounting'),
	(49, 'Profit & Loss', 'Accounting'),
	(50, 'Profit & Loss Detail', 'Accounting'),
	(51, 'Profit & Loss By Candidates', 'Accounting'),
	(52, 'Profit & Loss By Assignments', 'Accounting'),
	(53, 'Balance Sheet', 'Accounting'),
	(54, 'Summary Balance Sheet', 'Accounting'),
	(55, 'Statement of Cash Flows', 'Accounting'),
	(56, 'Terms Listing', 'Accounting'),
	(57, 'Recurring Template Listing', 'Accounting'),
	(58, 'A/R Aging Summary', 'Accounting'),
	(59, 'A/R Aging Detail', 'Accounting'),
	(60, 'Customer Balance Summary', 'Accounting'),
	(61, 'Customer Balance Detail', 'Accounting'),
	(62, 'Collections Report', 'Accounting'),
	(63, 'Unbilled Charges', 'Accounting'),
	(64, 'Income by Customer Summary', 'Accounting'),
	(65, 'Estimates by Customer', 'Accounting'),
	(66, 'Transaction List by Customer', 'Accounting'),
	(67, 'Customer Phone List', 'Accounting'),
	(68, 'Customer Contact List', 'Accounting'),
	(69, 'Check Detail', 'Accounting'),
	(70, 'Deposit Detail', 'Accounting'),
	(71, 'Reconcile Reports', 'Accounting'),
	(72, 'Time Activities by Candidates Detail', 'Accounting'),
	(73, 'Time Activities by Customer Detail', 'Accounting'),
	(74, 'A/P Aging Summary', 'Accounting'),
	(75, 'A/P Aging Detail', 'Accounting'),
	(76, 'Vendor Balance Summary', 'Accounting'),
	(77, 'Vendor Balance Detail', 'Accounting'),
	(78, 'Unpaid Bills', 'Accounting'),
	(79, 'Expenses by Vendor Summary', 'Accounting'),
	(80, 'Bill Payment List', 'Accounting'),
	(81, 'Transaction List by Vendor', 'Accounting'),
	(82, 'Vendor Phone List', 'Accounting'),
	(83, 'Vendor Contact List', 'Accounting'),
	(84, 'Unbilled Time', 'Accounting'),
	(85, 'Employee Phone List', 'Accounting'),
	(86, 'Employee Contact List', 'Accounting'),
	(87, 'Payroll Summary by Employee', 'Accounting'),
	(88, 'Payroll Summary Totals', 'Accounting'),
	(89, 'Payroll Liability Balances Summary', 'Accounting'),
	(90, 'Paychecks by Employee', 'Accounting'),
	(91, 'Paycheck Detail', 'Accounting'),
	(92, 'Payment and Deduction Detail', 'Accounting'),
	(93, 'State Tax Summary', 'Accounting'),
	(94, 'Vacation Plan Summary', 'Accounting'),
	(95, 'Vacation Plan Detail by Employee', 'Accounting'),
	(96, 'Sick Plan Summary', 'Accounting'),
	(97, 'Sick Plan Detail by Employee', 'Accounting'),
	(98, 'Employee Taxes Listing', 'Accounting'),
	(99, 'Employee Payments & Deductions Listing', 'Accounting'),
	(100, 'Compensation Listing', 'Accounting'),
	(101, 'Tax Listing', 'Accounting'),
	(102, 'Other Payments & Deductions Listing', 'Accounting'),
	(103, 'Pay Schedule Listing', 'Accounting'),
	(104, 'Recent/Edited Time Activities', 'Accounting')";
	mysql_query($query,$db);


    $query="insert into mail_editor(username,editorname,autofill,epreview,auto_spell_check) values ('1','advanced','yes','Y','Y')";
    mysql_query($query,$db);
	
	$query="insert into folderlist(foldername,mdate)values('webfolder',now())";
	mysql_query($query,$db);
	
	// Default Data for timezone table

	$que = "INSERT INTO timezone VALUES (1,'Dateline Time','','-12:00',0,'Y','Etc/GMT+12','-12:00','-12:00'),(2,'Samoa Time','','-11:00',0,'Y','Pacific/Samoa','-11:00','-11:00'),(3,'Hawaiian Time','','-10:00',0,'Y','HST','-10:00','-10:00'),(4,'Alaskan Time','','-09:00',0,'Y','AST','-09:00','-09:00'),(5,'Pacific Time','','-08:00',0,'Y','PST8PDT','-08:00','-07:00'),(6,'Mountain Time','','-07:00',0,'Y','MST7MDT','-07:00','-06:00'),(7,'Central Time','','-06:00',0,'Y','CST6CDT','-06:00','-05:00'),(8,'Eastern Time','','-05:00',0,'Y','EST5EDT','-05:00','-04:00'),(9,'Atlantic Time','','-04:00',0,'Y','America/Anguilla','-04:00','-04:00'),(10,'Newfoundland Time','','-03:30',0,'Y','America/St_Johns','-03:30','-03:30'),(11,'SA Eastern Time','','-03:00',0,'Y','America/Araguaina','-03:00','-03:00'),(12,'Mid-Atlantic Time','','-02:00',0,'Y','Atlantic/South_Georgia','-02:00','-02:00'),(13,'Azores Time','','-01:00',0,'Y','Atlantic/Azores','-01:00','-01:00'),(14,'GMT Time','','+00:00',0,'Y','GMT','+00:00','+00:00'),(15,'Western Europe Time','','+01:00',0,'Y','Europe/Brussels','+01:00','+01:00'),(16,'Eastern Europe Time','','+02:00',0,'Y','Europe/Bucharest','+02:00','+02:00'),(17,'Russian Time','','+03:00',0,'Y','Europe/Moscow','+03:00','+03:00'),(18,'Iran Time','','+03:30',0,'Y','Asia/Tehran','+03:30','+03:30'),(19,'Arabian Time','','+04:00',0,'Y','Asia/Dubai','+04:00','+04:00'),(20,'Afghanistan Time','','+04:30',0,'Y','Asia/Kabul','+04:30','+04:30'),(21,'West Asia Time','','+05:00',0,'Y','Asia/Tashkent','+05:00','+05:00'),(22,'India Time','','+05:30',0,'Y','Asia/Calcutta','+05:30','+05:30'),(23,'Central Asia Time','','+06:00',0,'Y','Asia/Dacca','+06:00','+06:00'),(24,'Myanmar Time','','+06:30',0,'Y','Asia/Rangoon','+06:30','+06:30'),(25,'SE Asia Time','','+07:00',0,'Y','Asia/Bangkok','+07:00','+07:00'),(26,'China Time','','+08:00',0,'Y','Asia/Chongqing','+08:00','+08:00'),(27,'Korea Time','','+09:00',0,'Y','Asia/Seoul','+09:00','+09:00'),(28,'Central Australia Time','','+09:30',0,'Y','Australia/Adelaide','+09:30','+09:30'),(29,'Eastern Australia Time','','+10:00',0,'Y','Australia/Brisbane','+10:00','+10:00'),(30,'Central Pacific Time','','+11:00',0,'Y','Pacific/Noumea','+11:00','+11:00'),(31,'Fiji/New Zealand Time','','+12:00',0,'Y','Pacific/Fiji','+12:00','+12:00')";
	mysql_query($que,$db);

	// Default insert statements for manage types.
	// Following are default statements for type 'prefix'. This is for CRM-Contacts, Candidates
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'prefix','Mr','Y',NOW(),'1','1');";
	mysql_query($query,$db);

	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'prefix','Mrs','Y',NOW(),'1','1');";
	mysql_query($query,$db);
	
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'prefix','Ms','Y',NOW(),'1','1');";
	mysql_query($query,$db);
	
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'suffix','Jr','Y',NOW(),'1','1');";
	mysql_query($query,$db);
	
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'suffix','Sr','Y',NOW(),'1','1');";
	mysql_query($query,$db);
	
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'suffix','II','Y',NOW(),'1','1');";
	mysql_query($query,$db);
	
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'prefix','Dr','Y',NOW(),'1','1');";
	mysql_query($query,$db);
	
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'prefix','Prof','Y',NOW(),'1','1');";
	mysql_query($query,$db);
	
	// Following are default statements for type 'contacttype'. This is for CRM-Contacts
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'contacttype','Main Contact','Y',NOW(),'1','1');";
	mysql_query($query,$db);
	
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'contacttype','Key Contact','Y',NOW(),'1','1');";
	mysql_query($query,$db);
	
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'contacttype','Reporting Contact','Y',NOW(),'1','1');";
	mysql_query($query,$db);
	
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'contacttype','Billing Contact','Y',NOW(),'1','1');";
	mysql_query($query,$db);
	
	// Following are default statements for type 'businesstype'. This is for CRM-Companies
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'businesstype','New Business','N',NOW(),'1','1');";
	mysql_query($query,$db);
		
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'businesstype','Existing Business','N',NOW(),'1','1');";
	mysql_query($query,$db);
	
	// Following are default statements for type 'compsource'. This is for CRM-Companies
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'compsource','Partner','N',NOW(),'1','1');";
	mysql_query($query,$db);	
	
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'compsource','Advertisement','N',NOW(),'1','1');";
	mysql_query($query,$db);
	
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'compsource','Employee Referral','N',NOW(),'1','1');";
	mysql_query($query,$db);
	
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'compsource','External Referral','N',NOW(),'1','1');";
	mysql_query($query,$db);
	
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'compsource','Public Relations','N',NOW(),'1','1');";
	mysql_query($query,$db);
	
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'compsource','Seminar - Internal','N',NOW(),'1','1');";
	mysql_query($query,$db);
	
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'compsource','Seminar - Partner','N',NOW(),'1','1');";
	mysql_query($query,$db);
	
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'compsource','Trade Show','N',NOW(),'1','1');";
	mysql_query($query,$db);
	
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'compsource','Web','N',NOW(),'1','1');";
	mysql_query($query,$db);
	
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'compsource','Word of mouth','N',NOW(),'1','1');";
	mysql_query($query,$db);
	
	// Following are default statements for type 'stage'. This is for CRM-Companies
	
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'stage','Prospecting','N',NOW(),'1','1');";
	mysql_query($query,$db);
	
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'stage','Qualification','N',NOW(),'1','1');";
	mysql_query($query,$db);
	
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'stage','Needs Analysis','N',NOW(),'1','1');";
	mysql_query($query,$db);
	
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'stage','Value Proposition','N',NOW(),'1','1');";
	mysql_query($query,$db);
	
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'stage','Decision Making','N',NOW(),'1','1');";
	mysql_query($query,$db);
	
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'stage','Perception Analysis','N',NOW(),'1','1');";
	mysql_query($query,$db);
	
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'stage','Proposal/Price Quote','N',NOW(),'1','1');";
	mysql_query($query,$db);
	
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'stage','Negotiation/Review','N',NOW(),'1','1');";
	mysql_query($query,$db);

	$job_status=array('Open','Closed','Filled','Cancelled');
	$job_stage=array('Accepting Resumes','Phone Interviews','Interviewing','Deliberating','Offer','Filled');

	for($i=0;$i<count($job_status);$i++)
	{
		$ins_status="INSERT INTO manage(sno,cuser,muser,cdate,mdate,type,name,status) values('','1','1',NOW(),NOW(),'jostatus','".$job_status[$i]."','Y')";
		mysql_query($ins_status,$db);
	}
	for($j=0;$j<count($job_stage);$j++)
	{
		$ins_stage="INSERT INTO manage(sno,cuser,muser,cdate,mdate,type,name,status) values('','1','1',NOW(),NOW(),'jostage','".$job_stage[$j]."','Y')";
		mysql_query($ins_stage,$db);
	}
	
	//insertion stmt into manage table of type notes and name submission status
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'Notes','Submission Status','Y',NOW(),'1','1')";
	mysql_query($query,$db);
	
	$arr=array('Email','Left Voicemail','Received Voicemail','Spoke With','Support Call');
	$count=count($arr);
	for($i=0;$i<$count;$i++)
	{
		//Insert stmt for into manage table
		$ins_manage="INSERT INTO manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'Notes','".$arr[$i]."','N',NOW(),'1','1')";
		mysql_query($ins_manage,$db);
	}		
	
	// Insertion for tasks table

	$query="insert into tasks (taskid,taskname,username,cdate,status,muser,mdate) values ('', 'To Do','1',now(),'Y','1',now());";
	mysql_query($query,$db);
	
	$query="insert into tasks (taskid,taskname,username,cdate,status,muser,mdate) values ('', 'Reminder', '1',now(),'Y','1',now());";
	mysql_query($query,$db);


	// Default values for e_folder
	
	$queins="insert into e_folder(fid,username,foldername,dis,parent,total,unread) values('','1','inbox','','system',0,0);";
    mysql_query($queins,$db);

	$queins="insert into e_folder(fid,username,foldername,dis,parent,total,unread) values('','1','outbox','','system',0,0);";
    mysql_query($queins,$db);

	$queins="insert into e_folder(fid,username,foldername,dis,parent,total,unread) values('','1','sentmessages','','system',0,0);";
    mysql_query($queins,$db);

	$queins="insert into e_folder(fid,username,foldername,dis,parent,total,unread) values('','1','drafts','','system',0,0);";
    mysql_query($queins,$db);

	$queins="insert into e_folder(fid,username,foldername,dis,parent,total,unread) values('','1','trash','','system',0,0);";
    mysql_query($queins,$db);

	$queins="insert into e_folder(fid,username,foldername,dis,parent,total,unread) values('','1','unsubscribe','','system',0,0);";
    mysql_query($queins,$db);

	$queins="insert into e_folder(fid,username,foldername,dis,parent,total,unread) values('','1','failed','','system',0,0);";
    mysql_query($queins,$db);

	$queins="insert into e_folder(fid,username,foldername,dis,parent,total,unread) values('','1','spam','','system',0,0);";
    mysql_query($queins,$db);
	
	// IMPORTANT : If Country order is changed, please update workerscomp.country default value for USA in akken-db.php
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (1, 'Abkhazia', '')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (2, 'Afghanistan', 'AF')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (3, 'Akrotiri and Dhekelia', '')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (4, 'Aland', '')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (5, 'Albania', 'AL')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (6, 'Algeria', 'DZ')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (7, 'American Samoa', 'AS')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (8, 'Andorra', 'AD')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (9, 'Angola', 'AO')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (10, 'Anguilla', 'AI')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (11, 'Antartica', 'AQ')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (12, 'Antigua and Barbuda', 'AG')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (13, 'Argentina', 'AR')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (14, 'Armenia', 'AM')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (15, 'Aruba', 'AW')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (16, 'Ascension Island', '')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (17, 'Australia', 'AU')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (18, 'Austria', 'AT')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (19, 'Azerbaijan', 'AZ')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (20, 'Bahamas', 'BS')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (21, 'Bahrain', 'BH')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (22, 'Bangladesh', 'BD')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (23, 'Barbados', 'BB')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (24, 'Belarus', 'BY')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (25, 'Belgium', 'BE')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (26, 'Belize ', 'BZ')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (27, 'Benin', 'BJ')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (28, 'Bermuda', 'BM')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (29, 'Bhutan', 'BT')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (30, 'Bolivia', 'BO')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (31, 'Bosnia and Herzegovina', 'BA')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (32, 'Botswana', 'BW')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (33, 'Bouvet Island', 'BV')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (34, 'Brazil', 'BR')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (35, 'British Indian Ocean Territory', 'IO')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (36, 'Brunei Darussalam', 'BN')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (37, 'Bulgaria', 'BG')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (38, 'Burkina Faso', 'BF')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (39, 'Burundi', 'BI')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (40, 'Cambodia', 'KH')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (41, 'Cameroon', 'CM')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (42, 'Canada', 'CA')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (43, 'Cape Verde', 'CV')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (44, 'Cayman Islands', 'KY')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (45, 'Central African Republic', 'CF')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (46, 'Chad', 'TD')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (47, 'Chile', 'CL')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (48, 'China', 'CN')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (49, 'Christmas Island', 'CX')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (50, 'Cocos (Keeling) Islands', 'CC')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (51, 'Colombia', 'CO')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (52, 'Comoros', 'KM')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (53, 'Congo-Brazzaville ', 'CG')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (54, 'Congo-Kinshasa ', 'CD')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (55, 'Cook Islands', 'CK')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (56, 'Costa Rica', 'CR')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (57, 'Cote D''Ivoire (Ivory Coast Republic)', 'CI')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (58, 'Croatia', 'HR')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (59, 'Cuba', 'CU')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (60, 'Cyprus', 'CY')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (61, 'Czech Republic', 'CZ')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (62, 'Denmark', 'DK')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (63, 'Djibouti', 'DJ')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (64, 'Dominica', 'DM')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (65, 'Dominican Republic', 'DO')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (66, 'East Timor', 'TP')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (67, 'Ecuador', 'EC')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (68, 'Egypt', 'EG')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (69, 'El Salvador', 'SV')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (70, 'Equatorial Guinea', 'GQ')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (71, 'Eritrea', 'ER')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (72, 'Estonia', 'EE')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (73, 'Ethiopia', 'ET')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (74, 'Falkland Islands (Malvinas)', 'FK')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (75, 'Faroe Islands', 'FO')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (76, 'Fiji', 'FJ')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (77, 'Finland', 'FI')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (78, 'France', 'FR')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (79, 'French Guiana', 'GF')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (80, 'French Polynesia', 'PF')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (81, 'French Southern Territories', 'TF')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (82, 'Gabon', 'GA')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (83, 'Gambia', 'GM')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (84, 'Georgia', 'GE')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (85, 'Germany', 'DE')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (86, 'Ghana', 'GH')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (87, 'Gibraltar', 'GI')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (88, 'Greece', 'GR')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (89, 'Greenland', 'GL')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (90, 'Grenada', 'GD')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (91, 'Guadeloupe', 'GP')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (92, 'Guam', 'GU')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (93, 'Guatemala', 'GT')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (94, 'Guernsey', '')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (95, 'Guinea', 'GN')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (96, 'Guinea-Bissau', 'GW')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (97, 'Guyana', 'GY')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (98, 'Haiti', 'HT')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (99, 'Heard and Mc Donald Islands', 'HM')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (100, 'Honduras', 'HN')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (101, 'Hong Kong', 'HK')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (102, 'Hungary', 'HU')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (103, 'Iceland', 'IS')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (104, 'India', 'IN')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (105, 'Indonesia', 'ID')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (106, 'Iran', 'IR')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (107, 'Iraq', 'IQ')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (108, 'Ireland', 'IE')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (109, 'Isle of Man', '')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (110, 'Israel', 'IL')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (111, 'Italy', 'IT')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (112, 'Jamaica', 'JM')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (113, 'Japan', 'JP')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (114, 'Jersey', '')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (115, 'Jordan', 'JO')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (116, 'Kazakhstan', 'KZ')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (117, 'Kenya', 'KE')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (118, 'Kiribati', 'KI')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (119, 'Korea, North', 'KP')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (120, 'Korea, South', 'KR')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (121, 'Kuwait', 'KW')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (122, 'Kyrgyzstan', 'KG')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (123, 'Laos', 'LA')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (124, 'Latvia', 'LV')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (125, 'Lebanon', 'LB')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (126, 'Lesotho', 'LS')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (127, 'Liberia', 'LR')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (128, 'Libya', 'LY')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (129, 'Liechtenstein', 'LI')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (130, 'Lithuania', 'LT')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (131, 'Luxembourg', 'LU')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (132, 'Macao', 'MO')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (133, 'Macedonia', 'MK')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (134, 'Madagascar', 'MG')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (135, 'Malawi', 'MW')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (136, 'Malaysia', 'MY')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (137, 'Maldives', 'MV')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (138, 'Mali', 'ML')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (139, 'Malta', 'MT')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (140, 'Marshall Islands', 'MH')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (141, 'Martinique', 'MQ')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (142, 'Mauritania', 'MR')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (143, 'Mauritius', 'MU')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (144, 'Mayotte', 'YT')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (145, 'Mexico', 'MX')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (146, 'Micronesia', 'FM')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (147, 'Moldova', 'MD')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (148, 'Monaco', 'MC')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (149, 'Mongolia', 'MN')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (150, 'Montenegro', '')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (151, 'Montserrat', 'MS')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (152, 'Morocco', 'MA')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (153, 'Mozambique', 'MZ')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (154, 'Myanmar', 'MM')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (155, 'Nagorno-Karabakh', '')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (156, 'Namibia', 'NA')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (157, 'Nauru', 'NR')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (158, 'Nepal', 'NP')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (159, 'Netherlands', 'NL')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (160, 'Netherlands Antilles', 'AN')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (161, 'New Caledonia', 'NC')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (162, 'New Zealand', 'NZ')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (163, 'Nicaragua', 'NI')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (164, 'Niger', 'NE')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (165, 'Nigeria', 'NG')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (166, 'Niue', 'NU')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (167, 'Norfolk Island', 'NF')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (168, 'Northern Cyprus', '')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (169, 'Northern Mariana Islands', 'MP')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (170, 'Norway', 'NO')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (171, 'Oman', 'OM')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (172, 'Pakistan', 'PK')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (173, 'Palau', 'PW')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (174, 'Palestine', 'PS')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (175, 'Panama', 'PA')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (176, 'Papua New Guinea', 'PG')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (177, 'Paraguay', 'PY')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (178, 'Peru', 'PE')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (179, 'Philippines', 'PH')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (180, 'Pitcairn Islands', 'PN')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (181, 'Poland', 'PL')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (182, 'Portugal', 'PT')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (183, 'Puerto Rico', 'PR')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (184, 'Qatar', 'QA')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (185, 'Reunion', 'RE')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (186, 'Romania', 'RO')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (187, 'Russian Federation', 'RU')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (188, 'Rwanda', 'RW')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (189, 'Sahrawi Arab Democratic Republic', 'EH')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (190, 'Saint-Barthélemy', '')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (191, 'Saint Helena', 'SH')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (192, 'Saint Kitts and Nevis', 'KN')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (193, 'Saint Lucia', 'LC')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (194, 'Saint Martin', '')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (195, 'Saint Pierre and Miquelon', 'PM')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (196, 'Saint Vincent and the Grenadines', 'VC')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (197, 'Samoa', 'WS')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (198, 'San Marino', 'SM')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (199, 'Sao Tome and Principe', 'ST')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (200, 'Saudi Arabia', 'SA')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (201, 'Senegal', 'SN')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (202, 'Serbia and Montenegro', 'YU')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (203, 'Seychelles', 'SC')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (204, 'Sierra Leone', 'SL')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (205, 'Singapore', 'SG')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (206, 'Slovakia', 'SK')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (207, 'Slovenia', 'SI')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (208, 'Solomon Islands', 'SB')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (209, 'Somalia', 'SO')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (210, 'Somaliland', '')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (211, 'South Africa', 'ZA')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (212, 'South Georgia and the South Sandwich Islands', 'GS')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (213, 'South Ossetia', '')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (214, 'Spain', 'ES')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (215, 'Sri Lanka', 'LK')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (216, 'Sudan', 'SD')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (217, 'Suriname', 'SR')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (218, 'Svalbard and Jan Mayen Islands', 'SJ')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (219, 'Swaziland', 'SZ')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (220, 'Sweden', 'SE')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (221, 'Switzerland', 'CH')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (222, 'Syrian Arab Republic', 'SY')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (223, 'Taiwan', 'TW')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (224, 'Tajikistan', 'TJ')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (225, 'Tanzania', 'TZ')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (226, 'Thailand', 'TH')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (227, 'Timor-Leste', '')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (228, 'Togo', 'TG')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (229, 'Tokelau', 'TK')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (230, 'Tonga', 'TO')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (231, 'Transnistria', '')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (232, 'Trinidad and Tobago', 'TT')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (233, 'Tristan da Cunha', '')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (234, 'Tunisia', 'TN')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (235, 'Turkey', 'TR')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (236, 'Turkmenistan', 'TM')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (237, 'Turks and Caicos Islands', 'TC')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (238, 'Tuvalu', 'TV')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (239, 'Uganda', 'UG')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (240, 'Ukraine', 'UA')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (241, 'United Arab Emirates', 'AE')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (242, 'United Kingdom', 'UK')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (243, 'United States of America', 'US')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (244, 'United States Minor Outlying Islands', 'UM')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (245, 'Uruguay', 'UY')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (246, 'Uzbekistan', 'UZ')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (247, 'Vanuatu', 'VU')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (248, 'Vatican City', 'VA')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (249, 'Venezuela', 'VE')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (250, 'Vietnam', 'VN')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (251, 'Virgin Islands, British', 'VG')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (252, 'Virgin Islands, United States', 'VI')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (253, 'Wallis and Futuna', 'WF')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (254, 'Yemen', 'YE')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (255, 'Zambia', 'ZM')";
	mysql_query($que,$db);
	$que="INSERT INTO countries(sno,country,country_abbr) VALUES (256, 'Zimbabwe', 'ZW')";
	mysql_query($que,$db);
	
	// Company - company status
	$comp_status=array('Prospect','Client','Do Not Use','Partner','Vendor','Other');

	for($i=0;$i<count($comp_status);$i++)
	{
		$query="select sno from manage where type='compstatus' and name='".$comp_status[$i]."'";
		$res=mysql_query($query,$db);
		$row=mysql_num_rows($res);
		if($row==0)
		{
			$query="INSERT INTO manage(cuser,muser,cdate,mdate,type,name,status) values('1','1',NOW(),NOW(),'compstatus','".$comp_status[$i]."','N')";
			mysql_query($query,$db);
		}
	}
	
	// Job order type
	$jo_type=array('Temp/Contract','Direct','Temp/Contract to Direct','Internal Temp/Contract','Internal Direct');
	
	for($i=0;$i<count($jo_type);$i++)
	{
		$query="select sno from manage where type='jotype' and name='".$jo_type[$i]."'";
		$res=mysql_query($query,$db);
		$row=mysql_num_rows($res);
		if($row==0)
		{
			$query="INSERT INTO manage(cuser,muser,cdate,mdate,type,name,status) values('1','1',NOW(),NOW(),'jotype','".$jo_type[$i]."','Y')";
			mysql_query($query,$db);
		}
	}
	
	// Job order - status
	$query="INSERT INTO manage(cuser,muser,cdate,mdate,type,name,status) values('1','1',NOW(),NOW(),'jostatus','Marketing','N')";
	mysql_query($query,$db);	
	
	//  Job order - stage
	$jo_stage=array('New','UnFilled','Unresponsive');
	for($j=0;$j<count($jo_stage);$j++)
	{
		$query="select sno from manage where type='jostage' and name='".$jo_stage[$j]."'";
		$res=mysql_query($query,$db);
		$row=mysql_num_rows($res);
		if($row==0)
		{
			if($jo_stage[$j]=="Unresponsive")
				$query="INSERT INTO manage(cuser,muser,cdate,mdate,type,name,status) values('1','1',NOW(),NOW(),'jostage','".$jo_stage[$j]."','Y')";
			else
			$query="INSERT INTO manage(cuser,muser,cdate,mdate,type,name,status) values('1','1',NOW(),NOW(),'jostage','".$jo_stage[$j]."','N')";
			mysql_query($query,$db);
		}		
	}	
	
	// UnFilled Lost -- is not manageable, so status='Y'
	$query="INSERT INTO manage(cuser,muser,cdate,mdate,type,name,status) values('1','1',NOW(),NOW(),'jostage','UnFilled Lost','Y')";
	mysql_query($query,$db);
	$uid=mysql_insert_id($db);
	
	// Job order - stage - Unfilled lost , status=N is manageable by user.
	// Note: In application, check for type='jostage' for Unfilled Lost. for suboptions, check for parent too.
	$jo_stage=array('Competitor','No Candidate','Price','Time to Fill','Not a Good Fit','Candidate Not Chosen');	
	for($j=0;$j<count($jo_stage);$j++)
	{
		$query="select sno from manage where type='jostage' and name='".$jo_stage[$j]."'";
		$res=mysql_query($query,$db);
		$row=mysql_num_rows($res);
		if($row==0)
		{
			$query="INSERT INTO manage(cuser,muser,cdate,mdate,type,name,status,parent) values('1','1',NOW(),NOW(),'jostage','".$jo_stage[$j]."','N','".$uid."')";
			mysql_query($query,$db);
		}	
	}	
	
	// Job order - category.
	$jo_cat=array('Accounting/Auditing', 'Administrative and Support Services', 'Advertising/Marketing/Public Relations', 'Aerospace/Aviation/Defense',  'Agriculture, Forestry, & Fishing', 'Airlines', 'Architectural Services', 'Arts, Entertainment, and Media', 'Automotive/Motor Vehicle/Parts', 'Banking', 'Biotechnology and Pharmaceutical', 'Building and Grounds Maintenance', 'Business Opportunity/Investment Required', 'Career Fairs', 'Computer Services', 'Computers-Hardware', 'Computers-Software', 'Construction, Mining and Trades', 'Consulting Services', 'Consumer Products', 'Creative/Design', 'Customer Service and Call Center', 'Education, Training, and Library', 'Electronics', 'Employment Placement Agencies', 'Energy/Utilities', 'Engineering', 'Environmental Services', 'Executive Management', 'Finance/Economics', 'Financial Services', 'Government and Policy', 'Healthcare - Business Office & Finance', 'Healthcare - CNAs/Aides/MAs/Home Health', 'Healthcare - Laboratory/Pathology Services', 'Healthcare - LPNs & LVNs', 'Healthcare - Medical & Dental Practitioners', 'Healthcare - Medical Records', 'Health IT, Informatics', 'Healthcare - Optical', 'Healthcare - Other' ,  'Healthcare - Pharmacy', 'Healthcare - Radiology/Imaging', 'Healthcare - RNs & Nurse Management', 'Healthcare - Social Services/Mental Health', 'Healthcare - Support Services', 'Healthcare - Therapy/Rehab Services', 'Hospitality/Tourism', 'Human Resources/Recruiting', 'Information Technology',  'Installation, Maintenance, and Repair', 'Insurance', 'Internet/E-Commerce', 'Law Enforcement/Security Srvs', 'Legal', 'Manufacturing and Production',  'Military', 'Nonprofit', 'Operations Management', 'Other', 'Personal Care and Service', 'Printing/Editing/Writing', 'Product Management/Marketing', 'Project/Program Management', 'Purchasing', 'Quality Assurance/Safety', 'Real Estate/Mortgage', 'Research & Development', 'Restaurant and Food Service',  'Retail/Wholesale', 'Sales', 'Sales - Account Management', 'Sales - Telemarketing', 'Sales - Work at Home/Commission Only', 'Science', 'Sports and Recreation/Fitness', 'Supply Chain/Logistics', 'Telecommunications', 'Textiles', 'Transportation and Warehousing', 'Veterinary Services', 'Waste Management Services');
	for($j=0;$j<count($jo_cat);$j++)
	{
		$query="select sno from manage where type='jocategory' and name='".$jo_cat[$j]."'";
		$res=mysql_query($query,$db);
		$row=mysql_num_rows($res);
		
		if($row==0)
		{
			$query="INSERT INTO manage(cuser,muser,cdate,mdate,type,name,status) values('1','1',NOW(),NOW(),'jocategory','".$jo_cat[$j]."','N')";
			mysql_query($query,$db);

		}	
	}	
	
	$getInternalDirectID = "SELECT sno FROM manage WHERE name = 'Internal Direct' AND type = 'jotype'";
	$resInternalDirectID = mysql_query($getInternalDirectID,$db);
	$rowInternalDirectID = mysql_fetch_array($resInternalDirectID);
	
	$que="insert into hrcon_jobs (username,jtype,pusername,ustatus,jotype,udate) values ('".$legalname."','AS','AS','active','".$rowInternalDirectID[0]."',NOW())";
	mysql_query($que,$db);
	
	$hrconInsertId = mysql_insert_id($db);
	
	//// default insertions for multiple rates
	
	$insMultipleRates = "INSERT INTO multiplerates_assignment (sno, asgnid, asgn_mode, ratemasterid, ratetype, rate, period, currency, billable, taxable, status, parent_update, cuser, cdate, muser, mdate) VALUES ('', '".$hrconInsertId."', 'hrcon', 'rate1', 'payrate', '0.00', 'HOUR', 'USD', 'Y', 'N', 'ACTIVE', '0', '1', NOW(), '1', NOW())";
	mysql_query($insMultipleRates,$db);	
	
	$insMultipleRates = "INSERT INTO multiplerates_assignment (sno, asgnid, asgn_mode, ratemasterid, ratetype, rate, period, currency, billable, taxable, status, parent_update, cuser, cdate, muser, mdate) VALUES ('', '".$hrconInsertId."', 'hrcon', 'rate1', 'billrate', '0.00', 'HOUR', 'USD', 'Y', 'Y', 'ACTIVE', '0', '1', NOW(), '1', NOW())";
	mysql_query($insMultipleRates,$db);	
	
	$insMultipleRates = "INSERT INTO multiplerates_assignment (sno, asgnid, asgn_mode, ratemasterid, ratetype, rate, period, currency, billable, taxable, status, parent_update, cuser, cdate, muser, mdate) VALUES ('', '".$hrconInsertId."', 'hrcon', 'rate2', 'payrate', '0.00', 'HOUR', 'USD', 'Y', 'N', 'ACTIVE', '0', '1', NOW(), '1', NOW())";
	mysql_query($insMultipleRates,$db);	
	
	$insMultipleRates = "INSERT INTO multiplerates_assignment (sno, asgnid, asgn_mode, ratemasterid, ratetype, rate, period, currency, billable, taxable, status, parent_update, cuser, cdate, muser, mdate) VALUES ('', '".$hrconInsertId."', 'hrcon', 'rate2', 'billrate', '0.00', 'HOUR', 'USD', 'Y', 'Y', 'ACTIVE', '0', '1', NOW(), '1', NOW())";
	mysql_query($insMultipleRates,$db);	
	
	$insMultipleRates = "INSERT INTO multiplerates_assignment (sno, asgnid, asgn_mode, ratemasterid, ratetype, rate, period, currency, billable, taxable, status, parent_update, cuser, cdate, muser, mdate) VALUES ('', '".$hrconInsertId."', 'hrcon', 'rate3', 'payrate', '0.00', 'HOUR', 'USD', 'Y', 'N', 'ACTIVE', '0', '1', NOW(), '1', NOW())";
	mysql_query($insMultipleRates,$db);	
	
	$insMultipleRates = "INSERT INTO multiplerates_assignment (sno, asgnid, asgn_mode, ratemasterid, ratetype, rate, period, currency, billable, taxable, status, parent_update, cuser, cdate, muser, mdate) VALUES ('', '".$hrconInsertId."', 'hrcon', 'rate3', 'billrate', '0.00', 'HOUR', 'USD', 'Y', 'Y', 'ACTIVE', '0', '1', NOW(), '1', NOW())";
	mysql_query($insMultipleRates,$db);	
	
	
	//When a new company is created, need to create a default department named "Administration Department" in HRM - Deparments.
		
	 $hrm_deptqry = "insert into department(sno, deptname, createdby, parent, stime, permission, depcode, loc_id, deflt) values ('', 'Administration', '1', '' , now(), 1, '100', 1, 'Y')";
	 mysql_query($hrm_deptqry,$db);
	 
	 $depInsId = mysql_insert_id($db);
	 
	$query = "INSERT INTO department_accounts(sno, deptid, classid, income_acct, expense_acct, ar_acct, ap_acct, payliability_acct, payexpense_acct, status) VALUES ('',  '".$depInsId."', '0', '9', '12', '1', '7', '6', '11', 'ACTIVE');";
	mysql_query($query,$db);
	
	//For default record inserion of company payroll setup
	$query1 = "insert into cpaysetup(sno,username,payperiod,stdhours,status,paydays) values ('','".$username."','Weekly',40,'ACTIVE',5)";
	mysql_query($query1,$db);
	
	// Need to insert into employee_paysetup from cpaysetup as company level/employee level
	$que="INSERT INTO employee_paysetup (paysetup_username, payperiod_company, payperiod, stdhours_payperiod, no_days_payperiod) SELECT emp_list.username, 'Y', cpaysetup.payperiod, cpaysetup.stdhours, cpaysetup.paydays FROM emp_list, cpaysetup WHERE emp_list.username='".$username."'";
	mysql_query($que,$db);
		
	//Need to add interview status to manage table. Need default insertion values
	$arr=array("Pending Placement","Reject","Interview","Submitted","Tel-Pass","Tel-Fail","Personal-Pass","Personal-Fail","Offer-Pending","Offer-Accepted","Offer-Rejected","Withdrew","Placed","Needs Approval","Active","Closed","Cancelled");
	$int_status_count=count($arr);
	for($i=0;$i<$int_status_count;$i++)
	 {
	 	if($arr[$i]=="Submitted" || $arr[$i]=="Pending Placement" || $arr[$i]=="Reject" || $arr[$i]=="Placed" || $arr[$i]=="Needs Approval" || $arr[$i]=="Active" || $arr[$i]=="Closed" || $arr[$i]=="Cancelled" || $arr[$i]=="Interview")
			$val="Y";
		else
			$val="N";
			
	   	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'interviewstatus','".$arr[$i]."','".$val."',NOW(),'1','1');";
		mysql_query($query,$db);

	}
	// Inserting manage values for Candidate status.
	
	$candstatus=array('New','On Assignment','Actively Searching','Passively Searching','Do Not Use');
	for($j=0;$j<count($candstatus);$j++)
	{
		$query="select sno from manage where type='candstatus' and name='".$candstatus[$j]."'";
		$res=mysql_query($query,$db);
		$row=mysql_num_rows($res);
		
		if($row==0)
		{
			$query="INSERT INTO manage(cuser,muser,cdate,mdate,type,name,status) values('1','1',NOW(),NOW(),'candstatus','".$candstatus[$j]."','Y')";
			mysql_query($query,$db);

		}	
	}	
	
	// Inserting manage values for Pay check delivery methods.
	
	$delivery_method=array('Direct Deposit','Mailed','Corporate Office','Client Company','Other');
	for($j=0;$j<count($delivery_method);$j++)
	{
		$query="select sno from manage where type='candstatus' and name='".$delivery_method[$j]."'";
		$res=mysql_query($query,$db);
		$row=mysql_num_rows($res);
		
		if($delivery_method[$i]=="Direct Deposit")
			$val="N";
		else
			$val="Y";
		
		if($row==0)
		{
			$query="INSERT INTO manage(cuser,muser,cdate,mdate,type,name,status) values('1','1',NOW(),NOW(),'deliverymethod','".$delivery_method[$j]."','".$val."')";
			mysql_query($query,$db);

		}	
	}
	
	//For inserting default records for candidate source type in manage table.....start
    $query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'candsourcetype','Referral','N',NOW(),'1','1');";
	mysql_query($query,$db);
    
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'candsourcetype','Website','Y',NOW(),'1','1');";
	mysql_query($query,$db);

	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'candsourcetype','Job Fair','N',NOW(),'1','1');";
	mysql_query($query,$db);
    
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'candsourcetype','Job Board','N',NOW(),'1','1');";
	mysql_query($query,$db);
    
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'candsourcetype','Newspaper','N',NOW(),'1','1');";
	mysql_query($query,$db);
	
	$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'candsourcetype','Other','N',NOW(),'1','1');";
	mysql_query($query,$db);
	
	// Job Order Source Type
	$arr=array('Client','Self Service','Partner','Website','Job Board');
	for($i=0;$i<count($arr);$i++)
	{
		if($arr[$i]!='Self Service')
			$status='N';
		else
			$status='Y';
		$query="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'josourcetype','".$arr[$i]."','".$status."',now(),'1','1');";
		mysql_query($query,$db);
	}	
	
	// HRM - Personal Tab - Veteran, type='hrveteran'
	$arr=array("yes","no");
	for($i=0;$i<count($arr);$i++)
	{
			$ins_manage="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'hrveteran','".$arr[$i]."','Y',now(),'1','1')";
			mysql_query($ins_manage,$db);
	}
	
	// HRM - Personal Tab - Ethnicity, type='hrethnicity'
	$arr=array("Decline to Identify","White (Not of Hispanic Origin)","African American/Black (Not of Hispanic Origin)","Asian/Pacific Islander","American Indian/Alaskan Native","Hispanic","Other");
	for($i=0;$i<count($arr);$i++)
	{
			$ins_manage="insert into manage(cdate,type,name,status,mdate,cuser,muser) values(now(),'hrethnicity','".addslashes($arr[$i])."','Y',now(),'1','1')";
			mysql_query($ins_manage,$db);
	}
	
	/***************BCS SPEC STATEMENTS** july 1, 2008**************************************************************************************************/
	// Default template
	$query="INSERT INTO IT_Totals ( inv_tot_sno , inv_tot_subtot_chk , inv_tot_subtot , inv_tot_discount_chk , inv_tot_discount , inv_tot_deposit_chk , inv_tot_deposit , inv_tot_tax_chk , inv_tot_tax , inv_tot_total_chk , inv_tot_total ) VALUES ('', 'Y', 'Sub Total', 'Y', 'Discount', 'Y', 'Deposit', 'Y', 'Tax', 'Y', 'Total');";
	mysql_query($query,$db);
		
	$query="INSERT INTO IT_Custom ( inv_cust_sno , inv_cust_cuser , inv_cust_muser , inv_cust_cdate , inv_cust_mdate , inv_cust_name , inv_cust_body , inv_cust_status ) 	VALUES ('', 1, 1, now(), now(), 'Customer Message', 'Thank you for your business' , 'ACTIVE');";
	$res=mysql_query($query,$db);
		
	$query="INSERT INTO IT_Columns (inv_col_sno, inv_col_time_chk, inv_col_time, inv_col_tserv_date_chk, inv_col_tserv_date, inv_col_totalhr_chk, inv_col_totalhr, inv_col_ohcrg_chk, inv_col_ohcrg, inv_col_tname_chk, inv_col_tname, inv_col_regularhr_chk, inv_col_regularhr, inv_col_tamount_chk, inv_col_tamount, inv_col_assign_chk, inv_col_assign, inv_col_rhcrg_chk, inv_col_rhcrg, inv_col_ttax_chk, inv_col_ttax, inv_col_dthour_chk, inv_col_dthour, inv_col_dhcrg_chk, inv_col_dhcrg, inv_col_othour_chk, inv_col_othour, inv_col_expense_chk, inv_col_expense, inv_col_sserv_date_chk, inv_col_sserv_date, inv_col_quantity_chk, inv_col_quantity, inv_col_stax_chk, inv_col_stax, inv_col_sname_chk, inv_col_sname, inv_col_cost_chk, inv_col_cost, inv_col_sdesc_chk, inv_col_sdesc, inv_col_samount_chk, inv_col_samount, inv_col_charge_chk, inv_col_charge, inv_col_cserv_date_chk, inv_col_cserv_date, inv_col_type_chk, inv_col_type, inv_col_service_chk, inv_col_service, inv_col_camount_chk, inv_col_camount, inv_col_cdesc_chk, inv_col_cdesc, inv_col_ctax_chk, inv_col_ctax, inv_col_perdiem_chk, inv_col_perdiem, inv_col_asgnname_chk, inv_col_asgnname, inv_col_asgnrefcode_chk, inv_col_asgnrefcode, inv_col_po_chk, inv_col_po, inv_col_hourstype_chk, inv_col_hourstype, inv_col_hours_chk, inv_col_hours, inv_col_rate_chk, inv_col_rate, inv_col_uom_chk, inv_col_uom, inv_col_tclass_chk, inv_col_tclass, inv_col_tcustom1_chk, inv_col_tcustom1, inv_col_tcustom2_chk, inv_col_tcustom2, inv_col_sclass_chk, inv_col_sclass, inv_col_scustom1_chk, inv_col_scustom1, inv_col_scustom2_chk, inv_col_scustom2, inv_col_cclass_chk, inv_col_cclass, inv_col_ccustom1_chk, inv_col_ccustom1, inv_col_ccustom2_chk, inv_col_ccustom2) VALUES ('', 'Y', 'Time', 'Y', 'Service Date', 'Y', '', 'Y', '', 'Y', '', 'Y', '', 'Y', 'Charge', 'Y', 'Asgn ID', 'Y', '', 'Y', 'Tax', 'Y', '', 'Y', '', 'Y', '', 'Y', 'Expense / Items', 'Y', 'Service Date', 'Y', 'Quantity', 'Y', 'Tax', 'Y', 'Name', 'Y', 'Cost', 'Y', 'Description', 'Y', 'Amount', 'Y', 'Charges / Credits', 'Y', 'Service Date', 'Y', 'Type', 'Y', 'Service', 'Y', 'Amount', 'Y', 'Description', 'Y', 'Tax', 'Y', 'Per Diem', 'Y', 'Asgn Name', 'Y', 'Asgn Ref.Code', 'Y', 'PO Number', 'Y', 'Type', 'Y', 'Hours', 'Y', 'Rate', 'Y', 'UOM', 'N', 'Class', 'N', '', 'N', '', 'N', 'Class', 'N', '', 'N', '', 'N', 'Class', 'N', '', 'N', '');";
	mysql_query($query,$db);
	
	$query="INSERT INTO IT_Invoice_Details ( inv_det_sno , inv_det_title_chk , inv_det_title , inv_det_num_chk , inv_det_num , inv_det_custid_chk , inv_det_custid , inv_det_date_chk , inv_det_date , inv_det_due_chk , inv_det_due, inv_det_sortorder, inv_det_class_chk, inv_det_class, inv_det_class_isprint ) VALUES ('', 'Y', 'Invoice', 'Y', 'Invoice Number', 'Y', 'Customer ID', 'Y', 'Date', 'Y', 'Due Date', 'title,invnum,custid,invdate,duedate', 'Y', 'Class', 'Y' );";
	mysql_query($query,$db);
		
	$query="INSERT INTO IT_Billto ( inv_bill_sno , inv_bill_company_name , inv_bill_company_add , inv_bill_billing_ct , inv_bill_billing_ph ) VALUES ( '', 'Y', 'Y', 'Y', 'Y' );";
	mysql_query($query,$db);
			
	$query="INSERT INTO IT_Template_Name ( invtmp_sno , invtmp_name , invtmp_version ) VALUES ('', 'Invoice Template', '0'); ";
	mysql_query($query,$db);
	
	$query="INSERT INTO Invoice_Template ( invtmp_sno , invtmp_cuser , invtmp_muser , invtmp_cdate , invtmp_mdate , invtmp_template , invtmp_header , invtmp_billto , invtmp_invoice_details , invtmp_columns , invtmp_custmsg , invtmp_totals , invtmp_footer , invtmp_status , invtmp_default ) 
	VALUES ('', '1', '1', NOW( ) , NOW( ) , '1', '0', '1', '1', '1', '1', '1', '0', 'ACTIVE', 'Y');";
	mysql_query($query,$db);
	
	// Default Template for Manual Invoice
	$query="INSERT INTO IT_Totals ( inv_tot_sno , inv_tot_subtot_chk , inv_tot_subtot , inv_tot_discount_chk , inv_tot_discount , inv_tot_deposit_chk , inv_tot_deposit , inv_tot_tax_chk , inv_tot_tax , inv_tot_total_chk , inv_tot_total ) VALUES ('', 'Y', 'Sub Total', 'Y', 'Discount', 'Y', 'Deposit', 'Y', 'Tax', 'Y', 'Total');";
	mysql_query($query,$db);
	$it_totals = mysql_insert_id($db);

	$query="INSERT INTO IT_Columns (inv_col_sno, inv_col_time_chk, inv_col_time, inv_col_tserv_date_chk, inv_col_tserv_date, inv_col_totalhr_chk, inv_col_totalhr, inv_col_ohcrg_chk, inv_col_ohcrg, inv_col_tname_chk, inv_col_tname, inv_col_regularhr_chk, inv_col_regularhr, inv_col_tamount_chk, inv_col_tamount, inv_col_assign_chk, inv_col_assign, inv_col_rhcrg_chk, inv_col_rhcrg, inv_col_ttax_chk, inv_col_ttax, inv_col_dthour_chk, inv_col_dthour, inv_col_dhcrg_chk, inv_col_dhcrg, inv_col_othour_chk, inv_col_othour, inv_col_expense_chk, inv_col_expense, inv_col_sserv_date_chk, inv_col_sserv_date, inv_col_quantity_chk, inv_col_quantity, inv_col_stax_chk, inv_col_stax, inv_col_sname_chk, inv_col_sname, inv_col_cost_chk, inv_col_cost, inv_col_sdesc_chk, inv_col_sdesc, inv_col_samount_chk, inv_col_samount, inv_col_charge_chk, inv_col_charge, inv_col_cserv_date_chk, inv_col_cserv_date, inv_col_type_chk, inv_col_type, inv_col_service_chk, inv_col_service, inv_col_camount_chk, inv_col_camount, inv_col_cdesc_chk, inv_col_cdesc, inv_col_ctax_chk, inv_col_ctax, inv_col_perdiem_chk, inv_col_perdiem, inv_col_asgnname_chk, inv_col_asgnname, inv_col_asgnrefcode_chk, inv_col_asgnrefcode, inv_col_po_chk, inv_col_po, inv_col_hourstype_chk, inv_col_hourstype, inv_col_hours_chk, inv_col_hours, inv_col_rate_chk, inv_col_rate, inv_col_uom_chk, inv_col_uom, inv_col_tclass_chk, inv_col_tclass, inv_col_tcustom1_chk, inv_col_tcustom1, inv_col_tcustom2_chk, inv_col_tcustom2, inv_col_sclass_chk, inv_col_sclass, inv_col_scustom1_chk, inv_col_scustom1, inv_col_scustom2_chk, inv_col_scustom2, inv_col_cclass_chk, inv_col_cclass, inv_col_ccustom1_chk, inv_col_ccustom1, inv_col_ccustom2_chk, inv_col_ccustom2) VALUES ('', 'N', 'Time', 'N', 'Service Date', 'N', 'Total Hours', 'N', 'OHCrg', 'Y', 'Name', 'N', 'Regular Hours', 'N', 'Total Amount', 'N', 'Assignment', 'N', 'RHCrg', 'N', 'Tax', 'N', 'Double Time Hours', 'N', 'DHCrg', 'N', 'Overtime Hours', 'N', 'Expense/Items', 'N', 'Service Date', 'N', 'Quantity', 'N', 'Tax', 'N', 'Name', 'N', 'Cost', 'N', 'Description', 'N', 'Amount', 'Y', 'Charges/Credits', 'N', 'Service Date', 'Y', 'Type', 'Y', 'Service', 'Y', 'Amount', 'Y', 'Description', 'Y', 'Tax', 'N', 'Per Diem', 'N', 'Asgn Name', 'N', 'Asgn Ref.Code', 'N', '', 'N', 'Hours type', 'N', 'Hours', 'N', 'Rate', 'N', 'UOM', 'N', '', 'N', '', 'N', '', 'N', '', 'N', '', 'N', '', 'N', '', 'N', '', 'N', '');";
	mysql_query($query,$db);
	$it_columns = mysql_insert_id($db);

	$query="INSERT INTO IT_Invoice_Details ( inv_det_sno , inv_det_title_chk , inv_det_title , inv_det_num_chk , inv_det_num , inv_det_custid_chk , inv_det_custid , inv_det_date_chk , inv_det_date , inv_det_due_chk , inv_det_due, inv_det_sortorder, inv_det_class_chk, inv_det_class, inv_det_class_isprint ) VALUES ('', 'Y', 'Invoice', 'Y', 'Invoice Number', 'Y', 'Customer ID', 'Y', 'Date', 'Y', 'Due Date', 'title,invnum,custid,invdate,duedate', 'Y', 'Class', 'Y' );";
	mysql_query($query,$db);
	$it_invDetails = mysql_insert_id($db);
		
	$query="INSERT INTO IT_Billto ( inv_bill_sno , inv_bill_company_name , inv_bill_company_add , inv_bill_billing_ct , inv_bill_billing_ph ) VALUES ( '', 'Y', 'Y', 'Y', 'Y' );";
	mysql_query($query,$db);
	$it_billTo = mysql_insert_id($db);
	
	$query="INSERT INTO IT_Template_Name ( invtmp_sno , invtmp_name , invtmp_version ) VALUES ('', 'Manual Template', '0'); ";
	mysql_query($query,$db);
	$it_tempName = mysql_insert_id($db);
	
	$query="INSERT INTO Invoice_Template ( invtmp_sno , invtmp_cuser , invtmp_muser , invtmp_cdate , invtmp_mdate , invtmp_template , invtmp_header , invtmp_billto , invtmp_invoice_details , invtmp_columns , invtmp_custmsg , invtmp_totals , invtmp_footer , invtmp_status , invtmp_default, invtmp_manual ) 
	VALUES ('', '1', '1', NOW( ) , NOW( ) , '".$it_tempName."', '0', '".$it_billTo."', '".$it_invDetails."', '".$it_columns."', '1', '".$it_totals."', '0', 'ACTIVE', 'N', '1');";
	mysql_query($query,$db);

	
	//Query for inserting record into manage table with name -'applied'
	$manage_query1="INSERT INTO manage(cuser,muser,cdate,mdate,type,name,status) values('1','1',NOW(),NOW(),'interviewstatus','Applied','Y')";
	mysql_query($manage_query1,$db);	
	
	/*//Query for inserting record into manage table with name -'source'
	$manage_query2="INSERT INTO manage(cuser,muser,cdate,mdate,type,name,status) values('1','1',NOW(),NOW(),'appsourcetype','Website','Y')";
	mysql_query($manage_query2,$db);	
	*/	
	
	$query="insert into manage(cuser,cdate,type,name,status,parent) values('1',Now(),'events','Call','Y',0)";
	mysql_query($query,$db);
	
	$query="insert into manage(cuser,cdate,type,name,status,parent) values('1',Now(),'events','Meeting','Y',0)";
	mysql_query($query,$db);
	
	//For inserting Label default values into manage table
	$labelArray = array("Important","Business","Personal","Vacation","Must Attend","Travel Required","Needs Preparation","Birthday","Anniversary","Phone Call");

    $commaLabelVal="";
	$labelString="";
	
	foreach($labelArray as $labelVal)
	{
		$insQryVals="'', '1', '1', NOW(), NOW(), 'appt_label', '".$labelVal."', 'Y', 0";
		$labelString = $labelString.$commaLabelVal."(".$insQryVals.")";
		$commaLabelVal = ",";
	}
	$insQry = "INSERT INTO manage(sno, cuser, muser, cdate, mdate, type, name, status, parent) VALUES ".$labelString;
	mysql_query($insQry,$db);

	//For inserting Show Time As default values into manage table
	$showtimeArray = array("Free","Tentative","Busy","Out of Office");

	$commaShowVal="";
	$showString="";
	
	foreach($showtimeArray as $showtimeVal)
	{
		$insQryVals="'', '1', '1', NOW(), NOW(), 'appt_showtime', '".$showtimeVal."', 'Y', 0";
		$showString = $showString.$commaShowVal."(".$insQryVals.")";
		$commaShowVal = ",";
	}
	$insQry = "INSERT INTO manage(sno, cuser, muser, cdate, mdate, type, name, status, parent) VALUES ".$showString;
	mysql_query($insQry,$db);

	//Default Appointment Categories For inserting into manage Table
	$array = explode(",","Meeting,Anniversary,Appointment,Bill Payment,Birthday,Breakfast,Call,Chat,Class,Club Event,Concert,Holiday,Interview,Lunch,Movie,Other,Party,Travel,Vacation,Wedding");

	for($i=0;$i<count($array);$i++)
	{
		$iqry_manage = "insert into manage (name,type,status,cuser,cdate,parent) values('".trim($array[$i])."','appt_category','Y','1', NOW(),0)";
		mysql_query($iqry_manage,$db);
	}
	
	// For Calendar Labels
	$query = "INSERT INTO appt_label ( sno , cuser , muser , cdate , mdate , name , color_code , status ) VALUES ('', '1', '1', NOW(), NOW(), 'None', '0', 'Y');";
	mysql_query($query,$db);
	
	
	$query = "INSERT INTO appt_label ( sno , cuser , muser , cdate , mdate , name , color_code , status ) VALUES ('', '1', '1', NOW(), NOW(), 'Important', '1', 'Y');";
	mysql_query($query,$db);
	
	
	$query = "INSERT INTO appt_label ( sno , cuser , muser , cdate , mdate , name , color_code , status ) VALUES ('', '1', '1', NOW(), NOW(), 'Business', '2', 'Y');";
	mysql_query($query,$db);
	
	
	$query = "INSERT INTO appt_label ( sno , cuser , muser , cdate , mdate , name , color_code , status ) VALUES ('', '1', '1', NOW(), NOW(), 'Personal', '3', 'Y');";
	mysql_query($query,$db);
	
	
	$query = "INSERT INTO appt_label ( sno , cuser , muser , cdate , mdate , name , color_code , status ) VALUES ('', '1', '1', NOW(), NOW(), 'Vacation', '4', 'Y');";
	mysql_query($query,$db);
	
	
	$query = "INSERT INTO appt_label ( sno , cuser , muser , cdate , mdate , name , color_code , status ) VALUES ('', '1', '1', NOW(), NOW(), 'Must Attend', '5', 'Y');";
	mysql_query($query,$db);
	
	
	$query = "INSERT INTO appt_label ( sno , cuser , muser , cdate , mdate , name , color_code , status ) VALUES ('', '1', '1', NOW(), NOW(), 'Travel Required', '6', 'Y');";
	mysql_query($query,$db);
	
	
	$query = "INSERT INTO appt_label ( sno , cuser , muser , cdate , mdate , name , color_code , status ) VALUES ('', '1', '1', NOW(), NOW(), 'Needs Preparation', '7', 'Y');";
	mysql_query($query,$db);
	
	
	$query = "INSERT INTO appt_label ( sno , cuser , muser , cdate , mdate , name , color_code , status ) VALUES ('', '1', '1', NOW(), NOW(), 'Birthday', '8', 'Y');";
	mysql_query($query,$db);
	
	
	$query = "INSERT INTO appt_label ( sno , cuser , muser , cdate , mdate , name , color_code , status ) VALUES ('', '1', '1', NOW(), NOW(), 'Anniversary', '9', 'Y');";
	mysql_query($query,$db);
	
	
	$query = "INSERT INTO appt_label ( sno , cuser , muser , cdate , mdate , name , color_code , status ) VALUES ('', '1', '1', NOW(), NOW(), 'Phone Call', '10', 'Y');";
	mysql_query($query,$db);
	
	$query= "INSERT INTO manage ( sno , cuser , muser , cdate , mdate , type , name , status ) VALUES ('', '1', '1', NOW(), NOW(), 'Notes', 'Opportunity', 'Y')";
	mysql_query($query,$db);
		
	// Default Values for State Abbreviation
	$que="INSERT INTO state_codes VALUES ('', 'Alabama', 'AL', '03')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Alaska', 'AK', '10')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Arizona', 'AZ', '37')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Arkansas', 'AR', '18')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'California', 'CA', '25')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Colorado', 'CO', '15')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Connecticut', 'CT', '27')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Delaware', 'DE', '8')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Dist. of Columbia', 'DC', '7')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Florida', 'FL', '42')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Foreign', 'null', '69')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Georgia', 'GA', '23')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Guam', 'GU', '16')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Hawaii', 'HI', '19')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Idaho', 'ID', '40')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Illinois', 'IL', '43')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Indiana', 'IN', '21')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Iowa', 'IA', '11')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Kansas', 'KS', '44')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Kentucky', 'KY', '31')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Louisiana', 'LA', '24')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Maine', 'ME', '45')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Maryland', 'MD', '5')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Massachusetts', 'MA', '2')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Michigan', 'MI', '60')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Minnesota', 'MN', '20')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Mississippi', 'MS', '46')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Missouri', 'MO', '22')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Montana', 'MT', '41')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Nebraska', 'NE', '47')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Nevada', 'NV', '48')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'New Hampshire', 'NH', '58')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'New Jersey', 'NJ', '56')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'New Mexico', 'NM', '33')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'New York', 'NY', '1')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'North Carolina', 'NC', '12')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'North Dakota (NR)', 'ND', '65')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'North Dakota (RES)', 'ND', '29')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Ohio', 'OH', '30')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Oklahoma', 'OK', '36')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Oregon', 'OR', '39')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Pennsylvania', 'PA', '59')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Puerto Rico', 'PR', '34')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Rhode Island', 'RI', '50')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'South Carolina', 'SC', '35')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'South Dakota', 'SD', '51')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Tennessee', 'TN', '52')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Texas', 'TX', '53')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Utah', 'UT', '28')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Vermont', 'VT', '13')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Virgin Islands', 'VI', '6')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Virginia', 'VA', '17')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Washington', 'WA', '54')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'West Virginia', 'WV', '26')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Wisconsin', 'WI', '14')";
	mysql_query($que,$db);
	
	$que="INSERT INTO state_codes VALUES ('', 'Wyoming', 'WY', '55')";
	mysql_query($que,$db);
	
	$sn_arr = array("43things"=>"43 Things","amazon"=>"Amazon","bebo"=>"Bebo","bizshark"=>"BizShark","blogger"=>"Blogger","buzznet"=>"Buzznet","dailymotion"=>"dailymotion","deviantart"=>"deviantART","digg"=>"Digg","facebook"=>"Facebook","flickr"=>"Flickr","flickrg"=>"Flickr Group","flixster"=>"Flixster","fotolog"=>"Fotolog","friendster"=>"Friendster","goodreads"=>"Goodreads","hi5"=>"Hi5","ilike"=>"iLike","imeem"=>"imeem","lastfm"=>"Last.fm","linkedin"=>"LinkedIn","livejournal"=>"LiveJournal","multiply"=>"Multiply","myspace"=>"MySpace","facebox"=>"Netlog","pandora"=>"Pandora","photobucket"=>"PhotoBucket","picasa"=>"Picasa","picturetrail"=>"PictureTrail","slide"=>"Slide","stumbleupon"=>"Stumbleupon","tagged"=>"Tagged","twitter"=>"Twitter","upcoming"=>"Upcoming","veoh"=>"Veoh","vox"=>"Vox","spokeo_web_results"=>"Web Results","webshots"=>"WebShots","msn"=>"Windows Live Spaces","wretch"=>"Wretch","xanga"=>"Xanga","yelp"=>"Yelp","youtube"=>"YouTube");
		
	while(list($name,$disname) = each($sn_arr))
	{
		if($name == 'flickrg')
			$name = 'flickr';
		$iqry = "INSERT INTO social_networks(sn_name,sn_display,cdate,mdate) VALUES('".$name."','".$disname."',NOW(),NOW())";
		mysql_query($iqry,$db);
	}
	
	$iqry = "INSERT INTO snsusers(userid,status,cuser,muser,cdate,mdate) VALUES('1','ACTIVE','1','1',NOW(),NOW())";
	mysql_query($iqry,$db);
			
	$que="INSERT INTO ac_type VALUES (1, 'Asset', 'Asset');";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_type VALUES (2, 'Liability', 'Liability');";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_type VALUES (3, 'Equity', 'Equity');";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_type VALUES (4, 'Income', 'Income/Revenue');";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_type VALUES (5, 'Expense', 'Expense');";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_category (s_no, type_ref, type, tdesc, minval, maxval, seriesref, display) VALUES (1, 1, 'FIXASSET', 'Asset', 1000, 1999, 1, 'Y')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_category (s_no, type_ref, type, tdesc, minval, maxval, seriesref, display) VALUES (2, 1, 'AR', 'Accounts Receivable', 1000, 1999, 1, 'Y')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_category (s_no, type_ref, type, tdesc, minval, maxval, seriesref, display) VALUES (3, 1, 'BANK', 'Bank', 1000, 1999, 1, 'N')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_category (s_no, type_ref, type, tdesc, minval, maxval, seriesref, display) VALUES (4, 2, 'CLIAB', 'Liability', 2000, 2999, 2, 'Y')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_category (s_no, type_ref, type, tdesc, minval, maxval, seriesref, display) VALUES (5, 2, 'AP', 'Accounts Payable', 2000, 2999, 2, 'Y')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_category (s_no, type_ref, type, tdesc, minval, maxval, seriesref, display) VALUES (6, 2, 'CCARD', 'Credit Card', 2000, 2999, 2, 'N')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_category (s_no, type_ref, type, tdesc, minval, maxval, seriesref, display) VALUES (7, 3, 'EQUITY', 'Equity', 3000, 3999, 3, 'Y')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_category (s_no, type_ref, type, tdesc, minval, maxval, seriesref, display) VALUES (8, 4, 'INC', 'Income', 4000, 4999, 4, 'Y')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_category (s_no, type_ref, type, tdesc, minval, maxval, seriesref, display) VALUES (9, 4,'EXINC','Miscellaneous Income',7000,7999, 7, 'Y')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_category (s_no, type_ref, type, tdesc, minval, maxval, seriesref, display) VALUES (10, 5, 'EXP', 'Expense', 6000, 6999, 6, 'Y')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_category (s_no, type_ref, type, tdesc, minval, maxval, seriesref, display) VALUES (11, 5,'EXEXP','Miscellaneous Expense',8000,8999,8,'Y')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_chart (ChartID, ListID, DateModified, Sublevel, AccountCatRef, SpecialAccountType, BankNumber, Balance, TotalBalance, CashFlowClassification, OpenBalance, OpenBalanceDate, Muser, Location_ref, Department_ref, deflt) VALUES (1, '1001', now(), 0, 2, '', '', 0.00, 0.00, '', 0.00, now(), 1, 1, 1, 'Y')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_chart (ChartID, ListID, DateModified, Sublevel, AccountCatRef, SpecialAccountType, BankNumber, Balance, TotalBalance, CashFlowClassification, OpenBalance, OpenBalanceDate, Muser, Location_ref, Department_ref, deflt) VALUES (2, '1002', now(), 0, 1, '', '', 0.00, 0.00, '', 0.00, now(), 1, 1, 1, 'Y')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_chart (ChartID, ListID, DateModified, Sublevel, AccountCatRef, SpecialAccountType, BankNumber, Balance, TotalBalance, CashFlowClassification, OpenBalance, OpenBalanceDate, Muser, Location_ref, Department_ref, deflt) VALUES (3, '1003', now(), 1, 1, '', '', 0.00, 0.00, '', 0.00, now(), 1, 1, 1, 'Y')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_chart (ChartID, ListID, DateModified, Sublevel, AccountCatRef, SpecialAccountType, BankNumber, Balance, TotalBalance, CashFlowClassification, OpenBalance, OpenBalanceDate, Muser, Location_ref, Department_ref, deflt) VALUES (4, '2002', now(), 0, 4, '', '', 0.00, 0.00, '', 0.00, now(), 1, 1, 1, 'Y')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_chart (ChartID, ListID, DateModified, Sublevel, AccountCatRef, SpecialAccountType, BankNumber, Balance, TotalBalance, CashFlowClassification, OpenBalance, OpenBalanceDate, Muser, Location_ref, Department_ref, deflt) VALUES (5, '2003', now(), 1, 4, '', '', 0.00, 0.00, '', 0.00, now(), 1, 1, 1, 'Y')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_chart (ChartID, ListID, DateModified, Sublevel, AccountCatRef, SpecialAccountType, BankNumber, Balance, TotalBalance, CashFlowClassification, OpenBalance, OpenBalanceDate, Muser, Location_ref, Department_ref, deflt) VALUES (6, '2004', now(), 1, 4, '', '', 0.00, 0.00, '', 0.00, now(), 1, 1, 1, 'Y')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_chart (ChartID, ListID, DateModified, Sublevel, AccountCatRef, SpecialAccountType, BankNumber, Balance, TotalBalance, CashFlowClassification, OpenBalance, OpenBalanceDate, Muser, Location_ref, Department_ref, deflt) VALUES (7, '2001', now(), 0, 5, '', '', 0.00, 0.00, '', 0.00, now(), 1, 1, 1, 'Y')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_chart (ChartID, ListID, DateModified, Sublevel, AccountCatRef, SpecialAccountType, BankNumber, Balance, TotalBalance, CashFlowClassification, OpenBalance, OpenBalanceDate, Muser, Location_ref, Department_ref, deflt) VALUES (8, '3001', now(), 0, 7, '', '', 0.00, 0.00, '', 0.00, now(), 1, 1, 1, 'Y')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_chart (ChartID, ListID, DateModified, Sublevel, AccountCatRef, SpecialAccountType, BankNumber, Balance, TotalBalance, CashFlowClassification, OpenBalance, OpenBalanceDate, Muser, Location_ref, Department_ref, deflt) VALUES (9, '4001', now(), 0, 8, '', '', 0.00, 0.00, '', 0.00, now(), 1, 1, 1, 'Y')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_chart (ChartID, ListID, DateModified, Sublevel, AccountCatRef, SpecialAccountType, BankNumber, Balance, TotalBalance, CashFlowClassification, OpenBalance, OpenBalanceDate, Muser, Location_ref, Department_ref, deflt) VALUES (10, '7001', now(), 0, 9, '', '', 0.00, 0.00, '', 0.00, now(), 1, 1, 1, 'Y')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_chart (ChartID, ListID, DateModified, Sublevel, AccountCatRef, SpecialAccountType, BankNumber, Balance, TotalBalance, CashFlowClassification, OpenBalance, OpenBalanceDate, Muser, Location_ref, Department_ref, deflt) VALUES (11, '6002', now(), 0, 10, '', '', 0.00, 0.00, '', 0.00, now(), 1, 1, 1, 'Y')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_chart (ChartID, ListID, DateModified, Sublevel, AccountCatRef, SpecialAccountType, BankNumber, Balance, TotalBalance, CashFlowClassification, OpenBalance, OpenBalanceDate, Muser, Location_ref, Department_ref, deflt) VALUES (12, '6001', now(), 0, 10, '', '', 0.00, 0.00, '', 0.00, now(), 1, 1, 1, 'Y')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO ac_chart (ChartID, ListID, DateModified, Sublevel, AccountCatRef, SpecialAccountType, BankNumber, Balance, TotalBalance, CashFlowClassification, OpenBalance, OpenBalanceDate, Muser, Location_ref, Department_ref, deflt) VALUES (13, '8001', now(), 0, 11, '', '', 0.00, 0.00, '', 0.00, now(), 1, 1, 1, 'Y')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (1, '1', 'AR', 'Accounts Receivable', 0, 'ER', '2009-06-29 02:00:15', 'Accounts Receivable', '', '1001')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (2, '1', 'FIXASSET', 'Current Asset', 0, 'ER', '2009-06-29 02:00:15', 'Other Current Asset', '', '1002')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (3, '1', 'FIXASSET', 'Undeposited Funds', 2, 'ER', '2009-06-29 02:00:15', 'Undeposited Funds', '', '1003')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (4, '1', 'CLIAB', 'Current Liability', 0, 'ER', '2009-06-29 02:00:15', 'Other Current Liability', '', '2002')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (5, '1', 'CLIAB', 'Taxes Payable', 4, 'ER', '2009-06-29 02:00:15', 'Taxes Payable', '', '2003')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (6, '1', 'CLIAB', 'Payroll Liabilities', 4, 'ER', '2009-06-29 02:00:15', 'Payroll Liabilities', '', '2004')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (7, '1', 'AP', 'Accounts Payable', 0, 'ER', '2009-06-29 02:00:15', 'Accounts Payable', '', '2001')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (8, '1', 'EQUITY', 'Opening balance Equity', 0, 'ER', '2009-06-29 02:00:15', 'Opening balance Equity', '','3001')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (9, '1', 'INC', 'Service/Customer Income', 0, 'ER', '2009-06-29 02:00:15', 'Service/Customer Income', '', '4001')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (10, '1', 'EXINC', 'Uncategorized Income', 0, 'ER', '2009-06-29 02:00:15', 'Uncategorized Income', '', '7001')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (11, '1', 'EXP', 'Payroll Expenses', 0, 'ER', '2009-06-29 02:00:15', 'Payroll Expenses', '', '6002')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (12, '1', 'EXP', 'Consulting/Vendor Expenses', 0, 'ER', '2009-06-29 02:00:15', 'Consulting/Vendor Expenses', '', '6001')";
	mysql_query($que,$db);
		
	
	$que="INSERT INTO reg_category (sno, username, type, name, parent, status, c_date, regdesc, notes, acc_number) VALUES (13, '1', 'EXEXP', 'Uncategorized Expenses', 0, 'ER', '2009-06-29 02:00:15', 'Uncategorized Expenses', '', '8001')";
	mysql_query($que,$db);

	
	$que="INSERT INTO ac_number VALUES (1, 1003, '1', '1')";
	mysql_query($que,$db);
			
	$que="INSERT INTO ac_number VALUES (2, 2004, '1', '1')";
	mysql_query($que,$db);
			
	$que="INSERT INTO ac_number VALUES (3, 3001, '1', '1')";
	mysql_query($que,$db);
			
	$que="INSERT INTO ac_number VALUES (4, 4001, '1', '1')";
	mysql_query($que,$db);
	
	$que="INSERT INTO ac_number VALUES (5, 5000, '1', '1')";
	mysql_query($que,$db);
				
	$que="INSERT INTO ac_number VALUES (6, 6002, '1', '1')";
	mysql_query($que,$db);
			
	$que="INSERT INTO ac_number VALUES (7, 7001, '1', '1')";
	mysql_query($que,$db);
			
	$que="INSERT INTO ac_number VALUES (8, 8001, '1', '1')";
	mysql_query($que,$db);
			
		
	// Madison - need into insert into madison related tables for the employees
	$fid_que = "select contact_manage.feid, concat_ws(', ',contact_manage.city,contact_manage.state) from contact_manage, hrcon_compen where hrcon_compen.location=contact_manage.serial_no and hrcon_compen.ustatus = 'active'";
	$fid_res = mysql_query($fid_que,$db);
	$fid_row = mysql_fetch_row($fid_res);	
	
	 $que="insert into madison_paydata(paydata_emp_username, paydata_emp_status, paydata_emp_terminated, paydata_fein, paydata_ssn, paydata_firstname, paydata_middlename, paydata_lastname, paydata_payperiod, paydata_std_hours, paydata_no_days, paydata_emptype, paydata_emp_timesheet,paydata_branchname,EmployeeId,Cdate,HireDate,HomeAddress1) select emp_list.username, emp_list.lstatus, emp_list.empterminated, '".$fid_row[0]."', ifnull(replace(hrcon_personal.ssn,'-',''),''), hrcon_general.fname, hrcon_general.mname, hrcon_general.lname, employee_paysetup.payperiod, employee_paysetup.stdhours_payperiod, employee_paysetup.no_days_payperiod, manage.name, hrcon_compen.timesheet,'".$fid_row[1]."',emp_list.sno,emp_list.stime,date_format(str_to_date(hrcon_compen.date_hire, '%m-%d-%Y'),'%Y-%m-%d'),hrcon_general.address1 from hrcon_general, employee_paysetup, emp_list left join hrcon_personal on (emp_list.username=hrcon_personal.username and hrcon_personal.ustatus='active'), hrcon_compen left join manage on (hrcon_compen.emptype = manage.sno and manage.type = 'jotype') where hrcon_general.ustatus='active' and emp_list.username=hrcon_general.username and emp_list.lstatus not in('DA','INACTIVE') and emp_list.username=employee_paysetup.paysetup_username and emp_list.username=hrcon_compen.username  and hrcon_compen.ustatus = 'active'";
	 mysql_query($que,$db);

	$bque="insert into madison_paydata_expense(paydata_id, paydata_emp_username, paydata_compen_id, paydata_paytype, paydata_unitspay, paydata_unitspayrate, paydata_unitsbillrate) select  '', hrcon_compen.username, hrcon_compen.sno, 'Bonus', 1, hrcon_compen.bonus, hrcon_compen.bonus_billrate from hrcon_compen,emp_list where hrcon_compen.bonus!='0.00' and hrcon_compen.ustatus='active' and emp_list.lstatus not in('DA','INACTIVE') and emp_list.username=hrcon_compen.username and hrcon_compen.bonus_billable='Y'";
	mysql_query($bque,$db);
	
	// make relation ship
	$upd="update madison_paydata_expense, madison_paydata set madison_paydata_expense.paydata_id=madison_paydata.paydata_sno where madison_paydata_expense.paydata_emp_username=madison_paydata.paydata_emp_username";
	mysql_query($upd,$db);
	
	// No timesheet records
	 $que="insert into madison_paydata_rate(paydata_id, paydata_emp_username, paydata_paytype, paydata_rhours, paydata_salary) select '',hrcon_compen.username,'Reg',employee_paysetup.stdhours_payperiod,hrcon_compen.salary from hrcon_compen,employee_paysetup where hrcon_compen.username = employee_paysetup.paysetup_username and hrcon_compen.ustatus = 'active' and hrcon_compen.timesheet='Y'";
	 mysql_query($que,$db);
	 	 
	// make relation ship
	$upd="update madison_paydata_rate, madison_paydata set madison_paydata_rate.paydata_id=madison_paydata.paydata_sno where madison_paydata_rate.paydata_emp_username=madison_paydata.paydata_emp_username";
	mysql_query($upd,$db);
	
	/////////////////////// default insertions for multiple payrates//////////////////////////////////////////////////////////
	
	$insDefault = "INSERT INTO multiplerates_master (sno, rateid, name, status, parent_update, default_status, cuser, cdate, muser, mdate) VALUES (1, 'rate1', 'Regular', 'ACTIVE', 0, 'Y', 1, NOW(), 1, NOW());";
	mysql_query($insDefault,$db);		
	
	$insDefault = "INSERT INTO multiplerates_master (sno, rateid, name, status, parent_update, default_status, cuser, cdate, muser, mdate) VALUES (2, 'rate2', 'OverTime', 'ACTIVE', 0, 'Y', 1, NOW(), 1, NOW());";
	mysql_query($insDefault,$db);		
	
	$insDefault = "INSERT INTO multiplerates_master (sno, rateid, name, status, parent_update, default_status, cuser, cdate, muser, mdate) VALUES (3, 'rate3', 'DoubleTime', 'ACTIVE', 0, 'Y', 1, NOW(), 1, NOW());";
	mysql_query($insDefault,$db);		
	
	$insDefault = "INSERT INTO multiplerates_master (sno, rateid, name, status, parent_update, default_status, cuser, cdate, muser, mdate) VALUES (4, 'rate4', 'PerDiem', 'ACTIVE', 0, 'Y', 1, NOW(), 1, NOW());";
	mysql_query($insDefault,$db);	
	
					
	//require(realpath("include/madison_employees.php"));
?>