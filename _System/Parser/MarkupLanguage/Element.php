<?php

class System__Parser__MarkupLanguage__Element extends System__Parser__MarkupLanguage__Node{
	
	public $nodeType = 1;
	public $selfClose;
	public $parentNode;
	
	protected $attributes = array();
	protected $childNodes = array();
	
	public function System__Parser__MarkupLanguage__Element($nodeName, $nodeNameSpace = null, $attributes = array(), $propertys = array(), $childNodes = array(), $selfClose = false){
		
		$this->selfClose		=	$selfClose;
		$this->nodeName			=	$nodeName;
		$this->nodeNameSpace	=	$nodeNameSpace;
		
		foreach($attributes as $name=>$value){
			$this->setAttribute($name,$value);
		}
		
		foreach($childNodes as $element){
			$this->addChildNode($element);
		}
		
	}
	
	
	//CHILD NODES
	public function addChildNode($element, $position = null){
		$element->parentNode = $this;
		
		if(isset($position)){
			array_splice($this->childNodes, $position, 0, array($element));
			return true;
		}
		
		$this->childNodes[] = $element;
		
		return true;
	}
	
	public function replaceChildNode($element, $position = null){
		if(isset($position) && isset($this->childNodes[$position])){
			$this->childNodes[$position] = $element;
			return true;
		}
	}
	
	public function getChildNodes(){
		return $this->childNodes;
	}
	
	public function getChildNode($lenght){
		return $this->childNodes[$lenght];
	}
	
	public function removeChildNode($position){
		unset($this->childNodes[$position]);
	}
	
	public function clearChildNodes(){
		$this->childNodes = array();
	}
	
	
	//ATTRIBUTES
	public function setAttribute($name, $value, $permission = 2){
		foreach($this->attributes as $attribute){
			if($attribute->nodeName == $name){
				$this->changeAttribute($name, $value);
				return;
			}
		}
		
		$this->attributes[] = new System__Parser__MarkupLanguage__Attribute($name, $value);
	}
	
	public function changeAttribute($name, $value){
		foreach($this->attributes as $attribute){
			if($attribute->nodeName == $name){
				$attribute->nodeValue = $value;
			}
		}
	}
	
	public function getAttributeObject($name){
		foreach($this->attributes as $attribute){
			if($attribute->nodeName == $name){
				return $attribute;
			}
		}
	}
	
	public function getAttribute($name){
		$attribute = $this->getAttributeObject($name);
		
		if($attribute){
			return $attribute->nodeValue;
		}
		
	}
	
	
	public function getAttributes(){		
		return $this->attributes;
	}
	
	public function clearAttributes(){
		$this->attributes = array();
	}
	
	
	
	//autos
	public function __ownerDocument(){
	
		if(isset($this->parentNode)){
			return $this->parentNode->__ownerDocument();
		}
		
	}
   
	public function __firstChild(){
		return $this->childNodes[0];
	}
   
	public function __lastChild(){
		return end($this->childNodes);
	}
   
	public function __nextSibling(){
		$childNodes = $this->parentNode->getChildNodes();
		foreach($childNodes as $position=>$childNode){
			if($childNode === $this){
				return $childNodes[$position+1];
			}
		}
	}
   
	public function __previousSibling(){
		$childNodes = $this->parentNode->getChildNodes();
		foreach($childNodes as $position=>$childNode){
			if($childNode === $this){
				return $childNodes[$position-1];
			}
		}
	}
   
	
	
	
	//TO STRING
	private function __childNodesStr($scope,$scopestr){
		$scope++;
		$str = '';
	   	foreach($this->childNodes as $element){
	   		$str .= $element->__toString($scope);
   		}
   		return $str;
	}
	
	private function __attributesStr(){
		$str = '';
	   	foreach($this->attributes as $attribute){
	   		$str .= $attribute->__toString();
   		}
   		return $str;
	}
	
	private function __elementStr($scope = 0,$scopestr = ''){
		
		$name = $this->nodeName;
		
		if(isset($this->nodeNameSpace)){
			$name = $this->nodeName.':'.$this->nodeNameSpace;
		}
		
		if($this->selfClose){
			return "\n".$scopestr.'<' . $name . $this->__attributesStr() . ' />';
		}
		
		if(count($this->childNodes) == 0 || (count($this->childNodes) <= 1 && $this->childNodes[0]->nodeType == 2 && strstr($this->childNodes[0]->nodeValue,"\n") === FALSE) )
		{
			return "\n".$scopestr.'<' . $name . $this->__attributesStr() . '>' . $this->__childNodesStr($scope,$scopestr). '</' . $name .'>';
		}
		
		return "\n".$scopestr.'<' . $name . $this->__attributesStr() . '>' . $this->__childNodesStr($scope,$scopestr). "\n" .$scopestr . '</' . $name .'>';
	}
	
	
	public function __toString($scope = 0){
		
		if($this->runatServer === true)
			return '';
		
		$scopestr = '';
		
		for($x = 0; $x < $scope; $x++){
			$scopestr .= "\t";
		}
		
		return $this->__elementStr($scope,$scopestr);
		
	}
		
	
}
