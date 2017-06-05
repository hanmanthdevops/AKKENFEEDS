<?php
	$rootDir = "/var/www/feeds/archive";

	delFolder($rootDir);
	
	function delFolder($par_fol)
	{
		$val=8*86400;

		if(is_dir($par_fol))
		{
			$d=dir($par_fol);
			while($entry=$d->read())
			{
				if(($entry!=".") && ($entry!="..") && ($entry!=".htaccess"))
				{
					if(is_dir($par_fol."/".$entry))
					{
						delFolder($par_fol."/".$entry);
					}
					else
					{
						$ftime=filemtime($par_fol."/".$entry);
						$remtime=time()-$ftime;
						if($remtime>$val)
							unlink($par_fol."/".$entry);
					}
				}
			}
		}
	}
?>
