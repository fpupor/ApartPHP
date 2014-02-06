<?php

class System__Parser__MarkupLanguage__Document extends System__Parser__MarkupLanguage__Node{
	
	public $nodeType = 4;
	
	protected $childNodes = array();
	protected $entitys = array();
	
	public function System__Parser__MarkupLanguage__Document($childNodes = array()){
		
		$this->nodeName			=	'document';
				
		foreach($childNodes as $element){
			$this->addChildNode($element);
		}
		
	}
	
	//ENTITYS
	public function setEntity($name, $value, $scope = null){
		
		foreach($this->entitys as $position=>$entity){
			if($entity['name'] == $name && $entity['scope'] === $scope){
				$this->entitys[$position]['value'] = $value;
				return;
			}
		}
		
		$this->entitys[] = array('name'=>$name, 'value'=>$value, 'scope'=>$scope);
	}
	
	public function getEntity($name, $scope = null){

		foreach($this->entitys as $entity){
			if($entity['name'] == $name && $entity['scope'] === $scope){
				return $entity['value'];
			}
		}
		
		return '';
	}
	
	//PROPERTYS
	public function setProperty($name, $value){
		$this->propertys[$name] = $value;
	}
	
	public function getProperty($name){
		return $this->propertys[$name];
	}
	
	
	//CHILD NODES
	public function addChildNode($element, $position = null){
		$element->parentNode = $this;
		
		if(isset($position)){
			array_splice($this->childNodes, $position, 0, $element);
			return true;
		}
		
		$this->childNodes[] = $element;
		
		return true;
	}
	
	public function getChildNodes(){
		return $this->childNodes;
	}
	
	public function getChildNode($lenght){
		return $this->childNodes[$lenght];
	}

	//autos
	public function __ownerDocument(){
		return $this;		
	}
   
	public function __firstChild(){
		return $this->childNodes[0];
	}
   
	public function __lastChild(){
		return end($this->childNodes);
	}
   

	//TO STRING
	public function __toString($scope = 0){
		
		if($this->runatServer === true)
			return '';
		
		$scopestr = '';
		
		for($x = 0; $x < $scope; $x++){
			$scopestr .= "\t";
		}
		
		$str = '';
		
	   	foreach($this->childNodes as $element){
	   		$str .= $element->__toString($scope);
   		}
   		
   		return $str;
		
	}
		
	
}
