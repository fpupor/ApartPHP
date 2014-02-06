<?php

class System__Cache__Control{
	
	public static $TEMP_PATH = 'Temp/Cache/';
	
	private static function getName($key){
		$dir  = ROOT_DIR.self::$TEMP_PATH;
		$name = $dir.'cach_'.md5($key);
		 
		return $name; 
	}
 
	public static function put($key, $data){
		$filename = self::getName($key);
		$file = fopen($filename, 'w');
	    
		if ($file)
	    {
	        fwrite($file, $data);
	        fclose($file);
	        return true;
	    }
	    
	    return false;
	}
 
	public static function get($key, $exp_time = 3600){
		$filename = self::getName($key);
		
		if (!file_exists($filename) || !is_readable($filename))
		{
			return false;
		}
		
		if ( time() < (filemtime($filename) + $exp_time) )
		{
			$file = fopen($filename, "r");
			
	        if ($file)
	        {
	            $data = fread($file, filesize($filename));
	            fclose($file);
	            
	            return $data;
	        }
	        
	        return false;
		}
		
		return false;
 	}
 	
 	public static function clean($key){
 		$filename = self::getName($key);
 		unlink($filename);
 	}
 	
 	public static function cleanAll($key){
 		$dir = ROOT_DIR.self::$TEMP_PATH;
	 	if(is_dir($dir))
		{
			if($handle = opendir($dir))
			{
				while(($file = readdir($handle)) !== false)
				{
					if($file != '.' && $file != '..')
					{
						unlink($dir.$file);
					}
				}
			}
		}
		else
		{
			die("Erro ao abrir dir: $dir");
		}
	    return 0;
 	}

}

