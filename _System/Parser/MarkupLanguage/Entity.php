<?php

class System__Parser__MarkupLanguage__Entity extends System__Parser__MarkupLanguage__Node{
	
	public $nodeType = 2;
	public $parentNode;
	
	public function System__Parser__MarkupLanguage__Entity($nodeName = null){
		
		$this->nodeName			=	$nodeName;		
		
	}
	
	private function __entityStr($scope = 0,$scopestr = ''){
		if(!isset($this->runatServer)){
			
			$document = $this->parentNode->__ownerDocument();
			
			if(isset($document)){
				$this->nodeValue = $document->getEntity($this->nodeName);
				
				return "\n" . $scopestr . $this->nodeValue;
				
			}
		}
		
		return '';
	}
	
	public function __toString($scope = 0){
		
		$scopestr = '';
		
		for($x = 0; $x < $scope; $x++){
			$scopestr .= "\t";
		}
		
		return $this->__entityStr($scope,$scopestr);
		
	}
		
	
}
