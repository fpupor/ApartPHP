<?php

class System__Parser__HTML__Entity extends System__Parser__MarkupLanguage__Entity{
	
	public function __construct($nodeName = null){
		
		$this->classExtend('System__Parser__HTML__Node');
		
		$this->System__Parser__MarkupLanguage__Entity($nodeName);
		
	}
	
	
}