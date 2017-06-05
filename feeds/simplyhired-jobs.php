<?php
	$include_path=dirname(__FILE__)."/include";
	ini_set("include_path",$include_path);

	require("global.inc");

	$fsource="SimplyHired";
	$zipfolder = $WDOCUMENT_ROOT."/".$fsource;

	if (is_dir($zipfolder))
		rmdir($zipfolder);

	if(!is_dir($zipfolder))
		mkdir($zipfolder, 0777);

	$archive_fol="/var/www/feeds/archive/".$fsource."/";
	$archive_dst=$archive_fol.date("Y-m-d H:i");

	$log_file=$archive_dst."-http_status.log";
	$arc_file=$archive_dst."-akken-feeds.zip";

	$simplyParam = array();

	$i=0;

	$fullState = array("/Dist. of Columbia/","/New Hampshire/","/New Jersey/","/New Mexico/","/New York/","/North Carolina/","/North Dakota/","/Puerto Rico/","/Rhode Island/","/South Carolina/","/South Dakota/","/Virgin Islands/","/West Virginia/");
	$abbrState = array("DC","NH","NJ","NM","NY","NC","ND","PR","RI","SC","SD","VI","WV");

	$rabbrState = array("/DC/","/NH/","/NJ/","/NM/","/NY/","/NC/","/ND/","/PR/","/RI/","/SC/","/SD/","/VI/","/WV/");
	$rfullState = array("Dist. of Columbia","New Hampshire","New Jersey","New Mexico","New York","North Carolina","North Dakota","Puerto Rico","Rhode Island","South Carolina","South Dakota","Virgin Islands","West Virginia");

	$dque="SELECT capp_info.comp_id,company_info.company_name,company_info.country,options.webapi,options.seowebapi FROM company_info LEFT JOIN capp_info ON capp_info.sno=company_info.sno LEFT JOIN options ON options.sno=company_info.sno WHERE ((options.webapi='Y' OR options.seowebapi='Y') AND options.simply='Y') AND company_info.status='ER' ORDER BY capp_info.comp_id";
	$dres=mysql_query($dque,$maindb);
	while($drow=mysql_fetch_row($dres))
	{
		$companyuser=strtolower($drow[0]);
		$compcountry=$drow[2];
		$webapi=$drow[3];
		$seowebapi=$drow[4];

		require("slavedatabase.inc");

		$cque = "SELECT company_name FROM company_info";
		$cres = mysql_query($cque,$db);
		$crow = mysql_fetch_row($cres);

		$companyname = ($crow[0]!="") ? $crow[0] : $drow[1];

		$prefQuery = "SELECT expire_days,cand_acc_chk,dn_dis_rates FROM jobposting_pref";
		$prefRes = mysql_query($prefQuery,$db);
		$prefInfo = mysql_fetch_row($prefRes);

		$expiredCount = $prefInfo[0];
		$candAccount = $prefInfo[1];
		$dn_dis_rates = $prefInfo[2];

		$expiredExpr = "";
		if($expiredCount != "")
			$expiredExpr = " AND IF(pd.status='P',DATE_ADD(pd.posted_date,INTERVAL ".$expiredCount." DAY),DATE_ADD(p.refresh_date,INTERVAL ".$expiredCount." DAY)) >= now()";

		$sqlManage = "SELECT GROUP_CONCAT(sno) FROM manage WHERE name IN ('Closed', 'Cancelled', 'Filled') AND type='jostatus' AND status='Y'";
		$resManage = mysql_query($sqlManage,$db);
		$rowManage = mysql_fetch_row($resManage);

		$juque="SELECT serial_no, jobs_url FROM contact_manage WHERE jobs_url!=''";
		$jures=mysql_query($juque,$db);
		while($jurow=mysql_fetch_row($jures))
		{
			$jlsno = $jurow[0];

			if($seowebapi=="Y" || validateURL($jurow[1],1,$log_file))
			{
				$jobsquery = "SELECT pd.sno as jobid, 
				TRIM(pd.postitle) as jobtitle, 
				DATE_FORMAT(IF(pd.status='P',pd.posted_date,IF(p.refresh_date='0000-00-00 00:00:00',pd.posted_date,p.refresh_date)), '%M %d %Y %H:%i:%s') as posted_date, 
				pd.posdesc as description, 
				pd.requirements as requirements, 
				pd.education as education, 
				TRIM(pd.joblocation) as joblocation, 
				TRIM(s.city) as city, 
				TRIM(s.state) as state, 
				TRIM(s.zipcode) as postalcode, 
				c.country_abbr as country,
				IF(mj.custom_name='',mj.name,mj.custom_name) as jobtype, 
				mc.name as category,
				l.jobs_url,
				IF(r.prateopen='Y',r.prateopen_amt,r.pamount) as salary,
				r.pcurrency as salcur,
				r.pperiod as salper,
				IF(r.sal_type='amount',r.salary,IF(r.sal_type='range',CONCAT(r.salary,' - ',r.sal_range_to),r.salary)) as dsalary,
				r.salary_currency as dsalcur,
				r.salary_period as dsalper
				FROM api_jobs AS pd 
				LEFT JOIN hotjobs h ON h.sno = pd.req_id 
				LEFT JOIN posdesc p ON h.req_id = p.posid 
				LEFT JOIN req_pref r ON h.req_id = r.posid
				LEFT JOIN manage AS mj ON pd.postype = mj.sno 
				LEFT JOIN manage AS mc ON pd.catid = mc.sno 
				LEFT JOIN staffoppr_location AS s ON pd.location = s.sno 
				LEFT JOIN countries AS c ON s.country = c.sno 
				LEFT JOIN department as d ON d.sno = p.deptid
				LEFT JOIN contact_manage as l ON l.serial_no = d.loc_id
				WHERE l.serial_no=$jlsno AND TRIM(l.jobs_url!='') AND TRIM(pd.postitle)!='' AND TRIM(pd.posdesc)!='' AND pd.status IN('P','R') AND pd.posstatus NOT IN (".$rowManage[0].") ".$expiredExpr;
		
				$jobsres = mysql_query($jobsquery,$db);
				$jobscount = mysql_num_rows($jobsres);
				while($jobsinfo = mysql_fetch_array($jobsres))
				{
					if($jobsinfo['country']=="")
					{
						if($compcountry>0)
						{
							$ccque="SELECT country_abbr FROM countries WHERE sno=$compcountry";
							$ccres=mysql_query($ccque,$db);
							$ccrow=mysql_fetch_row($ccres);
							$jobsinfo['country']=$ccrow[0];
						}
						else
						{
							$jobsinfo['country']="US";
						}
					}
		
					$jobsinfo['jobtitle']=charEncoding($jobsinfo['jobtitle']);
					$jobsinfo['country']=charEncoding($jobsinfo['country']);
					$jobsinfo['description']=charEncoding(html2text($jobsinfo['description']));
					$companyname=charEncoding($companyname);
		
					$jobsinfo['joblocation']=charEncoding($jobsinfo['joblocation']);
					$jobsinfo['dcity']=charEncoding($jobsinfo['city']);
					$jobsinfo['dstate']=charEncoding($jobsinfo['state']);
		
					if(trim($jobsinfo['joblocation'])!="")
					{
						$jobsinfo['joblocation'] = str_replace("  "," ",$jobsinfo['joblocation']);
						if(strpos($jobsinfo['joblocation'],","))
						{
							$sloc=explode(",",$jobsinfo['joblocation']);
							$jobsinfo['city']=trim($sloc[0]);
							$jobsinfo['state']=trim($sloc[1]);
						}
						else
						{
							$temp_location = preg_replace($fullState, $abbrState, $jobsinfo['joblocation'],-1,$prcount);

							if($prcount>1)
								$temp_location = preg_replace($rabbrState, $rfullState, $temp_location,1);

							$sloc=explode(" ",$temp_location);
							if(count($sloc)>0)
							{
								$jobsinfo['state']=trim($sloc[count($sloc)-1]);
								$jobsinfo['city']=trim(str_replace($jobsinfo['state'],"",$temp_location));
							}
							else
							{
								$jobsinfo['city']=$jobsinfo['joblocation'];
							}
						}
					}

					if(trim($jobsinfo['city'])=="")
						$jobsinfo['city']=$jobsinfo['dcity'];

					if(trim($jobsinfo['state'])=="")
						$jobsinfo['state']=$jobsinfo['dstate'];
		
					$jobsinfo['postalcode']=charEncoding($jobsinfo['postalcode']);
					$jobsinfo['salary']=charEncoding($jobsinfo['salary']);
					$jobsinfo['jobtype']=charEncoding($jobsinfo['jobtype']);
					$jobsinfo['category']=charEncoding($jobsinfo['category']);
		
					$simplyParam['job'][$i]['title']['@cdata'] = charEncoding($jobsinfo['jobtitle']);
					$simplyParam['job'][$i]['job-code']['@cdata'] = $companyuser."-".$jobsinfo['jobid'];


					if($seowebapi=="Y")
					{
						if($jobsinfo['joblocation']!="")
							$seotitle=strtolower(str_replace(" ","-",str_replace("/","-",$jobsinfo['jobtitle'])))."/".strtolower(str_replace(" ","-",str_replace("/","-",$jobsinfo['joblocation'])));
						else
							$seotitle=strtolower(str_replace(" ","-",str_replace("/","-",$jobsinfo['jobtitle'])));
						$seotitle=preg_replace('/-$/','',preg_replace('/-+/','-',preg_replace('/[^a-zA-Z0-9\s]/','-',$seotitle)));
						$simplyParam['job'][$i]['detail-url']['@cdata'] = $jobsinfo['jobs_url']."/jobdetails/".$seotitle."/".$jobsinfo['jobid'];
					}
					else
					{
						$simplyParam['job'][$i]['detail-url']['@cdata'] = $jobsinfo['jobs_url']."?wsource=$fsource&companyuser=$companyuser&jobOrdrNo=".$jobsinfo['jobid'];
					}

					if($jobsinfo['category']!="")
						$simplyParam['job'][$i]['job-category']['@cdata'] = charEncoding($jobsinfo['category']);
		
					// Description
					$simplyParam['job'][$i]['description']['summary']['@cdata'] = charEncoding($jobsinfo['description']);
		
					if($jobsinfo['education']!="")
						$simplyParam['job'][$i]['description']['required-education']['@cdata'] = charEncoding($jobsinfo['education']);
		
					if($jobsinfo['requirements']!="")
						$simplyParam['job'][$i]['description']['required-experience']['@cdata'] = charEncoding(html2text($jobsinfo['requirements']));
		
					if($jobsinfo['jobtype']=="Direct" || $jobsinfo['jobtype']=="Internal Direct" || $jobsinfo['jobtype']=="Temp/Contract to Direct")
						$simplyParam['job'][$i]['description']['permanent'] = "true";
					else if($jobsinfo['jobtype']=="Temp/Contract" || $jobsinfo['jobtype']=="Internal Temp/Contract")
						$simplyParam['job'][$i]['description']['contract'] = "true";
		
					// SALARY 
					if($dn_dis_rates!="Y")
					{
						if($jobsinfo['jobtype']=="Direct" || $jobsinfo['jobtype']=="Internal Direct")
						{
							$simplyParam['job'][$i]['compensation']['salary-amount'] = charEncoding($jobsinfo['dsalary']);
							$simplyParam['job'][$i]['compensation']['salary-range'] = charEncoding($jobsinfo['dsalper']);
							$simplyParam['job'][$i]['compensation']['salary-currency'] = charEncoding($jobsinfo['dsalcur']);
						}
						else
						{
							$simplyParam['job'][$i]['compensation']['salary-amount'] = charEncoding($jobsinfo['salary']);
							$simplyParam['job'][$i]['compensation']['salary-range'] = charEncoding($jobsinfo['salper']);
							$simplyParam['job'][$i]['compensation']['salary-currency'] = charEncoding($jobsinfo['salcur']);
						}
					}
		
					// Posted Date
					$simplyParam['job'][$i]['posted-date']['@cdata'] = charEncoding($jobsinfo['posted_date']);
		
					// LOCATION
					if($jobsinfo['city']!="")
						$simplyParam['job'][$i]['location']['city']['@cdata'] = charEncoding($jobsinfo['city']);
		
					if($jobsinfo['state']!="")
						$simplyParam['job'][$i]['location']['state']['@cdata'] = charEncoding($jobsinfo['state']);
		
					if($jobsinfo['postalcode']!="")
						$simplyParam['job'][$i]['location']['zip']['@cdata'] = charEncoding($jobsinfo['postalcode']);
		
					$simplyParam['job'][$i]['location']['country']['@cdata'] = charEncoding($jobsinfo['country']);
		
					// COMPANY 
					if($companyname!="")
						$simplyParam['job'][$i]['company']['name']['@cdata'] = charEncoding($companyname);
		
					$i++;
				}
			}
		}
	}

	$xml = Array2XML::createXML('jobs', $simplyParam);

	chdir($zipfolder);

	$zipfile = "akken-feeds.xml";
	$zipFileName = $zipfolder."/akken-feeds.xml.zip";

	$fp = fopen($zipfile, 'wb');
	fwrite($fp, iconv("ISO-8859-1","UTF-8",$xml->saveXML()));
	fclose($fp);

	exec("zip ".$zipfile.".zip ".$zipfile);
	copy($zipFileName,$arc_file);
?>