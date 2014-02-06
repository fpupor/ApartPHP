<?php

class System__Cache__Serialize extends System__Cache__Control{
	
	public static function put($key, $data){
		$data = serialize($data);
		return parent::put($key, $data);
	}
 
	public static function get($key, $exp_time = 3600){
		$data = parent::get($key, $exp_time);
		if($data){
			return unserialize($data);
		}
		return $data;
 	}
 	
}

