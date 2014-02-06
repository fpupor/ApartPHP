<?php

class System__Parser__MarkupLanguage__Text extends System__Parser__MarkupLanguage__Node{
	
	public $nodeType = 2;
	public $parentNode;
	
	public function System__Parser__MarkupLanguage__Text($nodeValue = null){
		
		$this->nodeName			=	'text';
		$this->nodeValue		=	$nodeValue;
		
	}
	
	private function __textStr($scope = 0,$scopestr = ''){
		if(!isset($this->runatServer)){
			
			if(strstr($this->nodeValue,"\n") !== FALSE)
			{
				$text = $this->nodeValue;
				$formated_text = preg_replace('/([\n]+[\t]*)/is', "\n".$scopestr, $text);
				return rtrim($formated_text);
			}
			
			return $this->nodeValue;
			
		}
		return '';
	}
	
	public function __toString($scope = 0){
		
		$scopestr = '';
		
		for($x = 0; $x < $scope; $x++){
			$scopestr .= "\t";
		}
		return $this->__textStr($scope,$scopestr);
		
	}
		
	
}
