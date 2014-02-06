<?php

class System__Parser__HTML__Comment extends System__Parser__MarkupLanguage__Comment{
	
	public function __construct($nodeValue = null){
		
		$this->classExtend('System__Parser__HTML__Node');
		
		$this->System__Parser__MarkupLanguage__Comment($nodeValue);
		
	}
		
	
}
