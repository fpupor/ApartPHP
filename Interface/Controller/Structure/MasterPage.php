<?php

final class Structure__MasterPage__Controller extends System__Web__MasterPageController{
	private $cacheRefresh = true;
	private $cacheName = 'StructureMasterPage';
	
	public function pageParser(){
		if(isset($_GET['cache']) && $_GET['cache'] == 'clear') return;	
	
		$this->document = System__Cache__Serialize::get($this->cacheName);
		
		if($this->document){
			$this->cacheRefresh = false;
			return true;
		}
	}
	public function pageLoad(){
		//cria ou atualiza cache
		if($this->cacheRefresh){
			System__Cache__Serialize::put($this->cacheName, $this->document);
		}
	}
}