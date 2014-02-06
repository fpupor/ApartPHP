<?php

class System__Parser__MarkupLanguage__Comment extends System__Parser__MarkupLanguage__Node{
	
	public $nodeType = 3;
	public $parentNode;
	
	public function System__Parser__MarkupLanguage__Comment($nodeValue = null){
		
		$this->nodeName			=	'comment';
		$this->nodeValue		=	$nodeValue;
		
	}
	
	private function __commentStr($scope = 0,$scopestr = ''){
		if(!isset($this->runatServer)){
			return "\n".$scopestr.'<!--'.$this->nodeValue.'-->';
		}
		return '';
	}
	
	public function __toString($scope = 0){
		
		$scopestr = '';
		
		for($x = 0; $x < $scope; $x++){
			$scopestr .= "\t";
		}
		
		return $this->__commentStr($scope,$scopestr);
		
	}
		
	
}
