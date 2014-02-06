<?php

class System__Parser__HTML__Attribute extends System__Parser__MarkupLanguage__Attribute{
	
	public function __construct($nodeName, $nodeValue = null){
		
		$this->classExtend('System__Parser__HTML__Node');
		
		$this->System__Parser__MarkupLanguage__Attribute($nodeName, $nodeValue);
		
	}
	
}
