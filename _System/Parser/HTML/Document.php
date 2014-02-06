<?php

class System__Parser__HTML__Document extends System__Parser__MarkupLanguage__Document{
	
	public function __construct($childNodes = array()){
		
		$this->classExtend('System__Parser__HTML__Node');
		
		$this->System__Parser__MarkupLanguage__Document($childNodes);
		
	}
	
	public function createElement($name, $namespace = null){
		return new System__Parser__HTML__Element($name, $namespace);
	}
	
	public function __title(){
		$titles = $this->getElementsByTagName('title');
		if(isset($titles[0])){
			return $titles[0]->getChildNode(0)->nodeValue;
		}
	}
	
	public function __forms(){
		return $this->getElementsByTagName('form');
	}
	
	public function __images(){
		return $this->getElementsByTagName('img');
	}
	
	public function __links(){
		return array_merge($this->getElementsByTagName('area'),$this->getElementsByTagName('a'));
	}
	
	public function __anchors(){
		$anchors = array();
		foreach($this->getElementsByTagName('a') as $position=>$anchor){
			if($anchor->getAttribute('name')){
				$anchors[] = $anchor;
			}
		}
		return $anchors;
	}
	
}
