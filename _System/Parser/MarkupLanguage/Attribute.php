<?php

class System__Parser__MarkupLanguage__Attribute extends System__Parser__MarkupLanguage__Node{
	
	public $nodeType = 5;
	
	public function System__Parser__MarkupLanguage__Attribute($nodeName, $nodeValue = null){
		
		$this->nodeName			=	$nodeName;
		$this->nodeValue		=	$nodeValue;
		
	}
	
	private function __attributeStr(){
		return ' ' . $this->nodeName . '="' . $this->nodeValue .'"';
	}
	
	public function __toString($scope = 0){
		
		return $this->__attributeStr();
		
	}
		
	
}
