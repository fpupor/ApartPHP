<?php

class System__Parser__HTML__Element extends System__Parser__MarkupLanguage__Element{
	
	public function __construct($nodeName, $nodeNameSpace = null, $attributes = array(), $propertys = array(), $childNodes = array(), $selfClose = false){
		
		$this->classExtend('System__Parser__HTML__Node');
				
		$this->System__Parser__MarkupLanguage__Element($nodeName, $nodeNameSpace, $attributes, $propertys, $childNodes, $selfClose);
		
	}
	
	public function __className($name = null){
		if(!isset($name)){
			return $this->getAttribute('class');
		}
		
		$this->setAttribute('class',$name);
		
	}
	
}
