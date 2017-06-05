<?php
	header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

	$egdomain="akken.com";
	$PLUGIN_PATH="/usr/lib/nagios/plugins";
	$HTTP_HEALTH="$PLUGIN_PATH/check_http -H ";

	$time=time()+31536000;
	$rldfailed=array();

	if($HTTP_REFERER!="" && !strpos($HTTP_REFERER,$egdomain))
	{
		$referer_link=$HTTP_REFERER;
		setcookie("find_referer_link",$referer_link,$time,"/",".$egdomain");
	}

	function findLRDomain1($iscookie,$host)
	{
		global $egdomain,$rldfailed,$HTTP_HEALTH;
		if(!$iscookie)
		{
			$rand=rand(1,2);
			if(!in_array($rand,$rldfailed))
				$host="login".$rand;
			else
				return findLRDomain1(false,"");
		}
		else
		{
			$rand=substr($host,5,1);
			$host=substr($host,0,6);
		}

		$vhost="$host.$egdomain";
		system("$HTTP_HEALTH $vhost > /dev/null",$ret);

		if($ret<2)
		{
			return $rand;
		}
		else
		{
			if(!in_array($rand,$rldfailed))
				$rldfailed[count($rldfailed)+1]=$rand;

			if(count($rldfailed)<2)
			{
				return findLRDomain1(false,"");
			}
			else
			{
				Header("Location:http://www.$egdomain/showmes.php");
				exit();
			}
		}
	}

	if($_COOKIE['find_login_domain'])
		$rl_server_id=findLRDomain1(true,$find_login_domain);
	else
		$rl_server_id=findLRDomain1(false,"");

	$find_login_domain="login$rl_server_id.$egdomain";
	$find_admin_domain="admin$rl_server_id.$egdomain";
	$def_www_url="http://www.$egdomain/";

	if($_SERVER["SERVER_PORT"] == "443" && $_SERVER["HTTPS"] == "on")
		$find_api_domain="https://sapi.$egdomain/";
	else
		$find_api_domain="http://api.$egdomain/";

	if($HTTP_HOST=="login.$egdomain" || $HTTP_HOST=="admin.$egdomain")
	{
		if ($HTTP_HOST=="login.$egdomain")
			$rdomain=$find_login_domain;
		else
			$rdomain=$find_admin_domain;

		if($QUERY_STRING=="")
			$rurl="https://$rdomain$PHP_SELF";
		else
			$rurl="https://$rdomain$PHP_SELF?$QUERY_STRING";
		Header("Location:$rurl");
		exit();
	}
	else if ($HTTP_HOST=="login.akken.mobi")
	{
		$include_path="/var/www/html/mobile/include";
		ini_set("include_path",$include_path);
	}
	else
	{
		setcookie("find_login_domain",$find_login_domain,$time,"/",".$egdomain");
		setcookie("find_admin_domain",$find_admin_domain,$time,"/",".$egdomain");
		setcookie("find_api_domain",$find_api_domain,$time,"/",".$egdomain");
	}
?>
