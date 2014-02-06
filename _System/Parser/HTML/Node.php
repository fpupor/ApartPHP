<?php

class System__Parser__HTML__Node extends System__Parser__MarkupLanguage__Node{

	public $super;
	
	public function System__Parser__HTML__Node($super){
		$this->super = $super;
	}

	public function searchElementsByFeature($property, $value, $andself = true){
		$found = array();
		
		if($andself && isset($this->super->$property) && $this->super->$property == $value){
   			$found[] = $this->super;
   		}
	   	
   		if($this->super->nodeType == 1 || $this->super->nodeType == 4){
	   		$childNodes = $this->super->getChildNodes();
	   		
		   	foreach($childNodes as $position=>$childNode){
		   		$childFound = $childNode->searchElementsByFeature($property, $value);
				$found = array_merge($found, $childFound);
		   	}
		}
	   	
	   	return $found; 
   	
   }
   
   public function searchElementsByAttribute($property, $value, $andself = true){
		$found = array();
		
		if($andself && $this->super->nodeType == 1){
			$attribute = $this->super->getAttribute($property);
			
			if( isset($attribute) && $attribute == $value){
				$found[] = $this->super;
	   		}
   		}
   		
   		if($this->super->nodeType == 1 || $this->super->nodeType == 4){
	   		$childNodes = $this->super->getChildNodes();
	   		
		   	foreach($childNodes as $position=>$childNode){
		   		$childFound = $childNode->searchElementsByAttribute($property, $value);
				$found = array_merge($found, $childFound);
		   	}
		}
	   	
	   	return $found; 
   	
   }
   
	public function getElementsByTagName($name){
		return $this->super->searchElementsByFeature('nodeName',$name, false);
	}
	
	public function getElementsByTagNamespace($name){
		return $this->super->searchElementsByFeature('nodeNameSpace',$name, false);
	}
	
	public function getElementsByAttribute($name, $value){
		return $this->super->searchElementsByAttribute($name, $value, false);
	}
	
	public function getElementById($name){
		$element = $this->super->getElementsByAttribute('id',$name);
		return $element[0];
	}
	
	public function getElementsByName($name){
		return $this->super->getElementsByAttribute('name',$name);
	}
	  
	public function __innerHTML($html = false){
		if(!$html) {
			$html = '';
			foreach($this->super->getChildNodes() as $childNode){
				$html .= $childNode	. '';
			}
			return $html;
		}
		
		$this->super->clearChildNodes();
		
		$parsed = new System__Parser__HTML__Parser($html);
		
		foreach($parsed->document->getChildNodes() as $childNode){
		$this->super->addChildNode($childNode);
	}
		
	}
	
	
	public function __innerText($text = false){
		
		if(!isset($text)) {
			$text = '';
			foreach($this->super->searchElementsByFeature('nodeType', 2, false) as $childNode){
				$text .= $childNode	. '';
			}
			return $text;
		}
		
		$this->super->clearChildNodes();
		
		$text = new System__Parser__HTML__Text(htmlspecialchars($text)	);
		
		$this->super->addChildNode($text);
	
	}
	
	
	public function feed($array){
		if($this->super->nodeType == 1 || $this->super->nodeType == 4)
		{
			$childs = $this->super->getChildNodes();
			$this->super->clearChildNodes();
			
			foreach($array as $position=>$value)
			{
				foreach($childs as $child)
				{
					$n = clone $child;
					$this->super->addChildNode($n);
				}
			}
		}
	}
   
   
   
}
