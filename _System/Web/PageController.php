<?php

abstract class System__Web__PageController {
   protected $__documentPath;
   protected $__documentFile;
   protected $postBack;
   protected $POST;

   public $document;
   public $GET;

   final public function __construct($htmlPath, $GET, $POST) {
	$this->__documentPath = $htmlPath;
	$this->GET = $GET;
	$this->POST = $POST;
	
	$this->__documentParser();
	
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$http_referer = parse_url($_SERVER['HTTP_REFERER']);
		
		if(isset($http_referer) && ($http_referer['host'] == $_SERVER['SERVER_NAME'] && $http_referer['path'] == $_SERVER['REDIRECT_URL']) )
		{
			$this->postBack = true;
			
			if(method_exists($this, 'doPostBack')){
				$this->doPostBack($POST, $GET);
			}
		}
	}
	
	if(method_exists($this, 'pageLoad')){
		$this->pageLoad();
	}
   }

   final private function __documentParser(){
	$htmlPath = $this->__documentPath;
	
   	if(method_exists($this, 'pageParser')){   		
		if($this->pageParser()){
			return; 
		}
	}

	if($htmlPath && file_exists($htmlPath)){
		$this->__documentFile = file_get_contents($htmlPath);

		$parsed = new System__Parser__HTML__Parser($this->__documentFile);
		$this->document = $parsed->document;
	}
	
   }

   final private function __toString(){
   
    $masterPage = $this->document->getProperty('masterPage');
    
    if($masterPage){
    	return ''.$masterPage;
    }
   
	if($this->document){
		return ''.$this->document;
	}
	return '';
   }

}
