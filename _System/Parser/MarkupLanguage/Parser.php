<?php

class System__Parser__MarkupLanguage__Parser {
   public $document;
   public $parserName = 'MarkupLanguage';
   
   public function newDocument(){
   	return new System__Parser__MarkupLanguage__Document();
   }
   
   public function newElement($name,$namespace = null,$attributes = array(),$propertys = array(),$childnodes = array(),$selfclose = false){
   	return new System__Parser__MarkupLanguage__Element($name,$namespace,$attributes,$propertys,$childnodes,$selfclose);
   }
   
   public function newText($string){
   	return new System__Parser__MarkupLanguage__Text($string);
   }
   
   public function newEntity($string){
   	return new System__Parser__MarkupLanguage__Entity($string);
   }
   
   public function newComment($string){
   	return new System__Parser__MarkupLanguage__Comment($string);
   }

   public function __construct ($string) {
	$this->document = $this->newDocument();
	$this->parser($string);
   }

   private function parser($string){
   	/*						tag                fechamento ou conteudo fechamento | comentario
   	/([^>^<]*)?<([^A-z])?(.[^\s^>]*)([^><]*)>([^>^<]*)?/is
   	*/
	
	//  @([a-z^\s]+);|
	$quant = preg_match_all('/([^>^<]*)?<([^A-z])?(.[^\s^>]*)([^><]*)>([^>^<]*)?/is', $string, $nodes, PREG_OFFSET_CAPTURE);
	$scope = $this->document;
	
	$node = 0;
	
	while($node < $quant){
		
		$nodeRealName = trim($nodes[3][$node][0]);
		
		preg_match('/([A-z0-9]+):?(.*)?/is', $nodeRealName, $nodeAlias );
		
		$nodeType = $nodes[2][$node][0];
		$nodeTypePos = $nodes[2][$node][1];
		
		$nodeName = $nodeAlias[1];
		$nodeNamespace = $nodeAlias[2] != '' ? $nodeAlias[2] : null;
		$nodeAlias = $nodeAlias[0];
		
		$nodeAttr = $nodes[4][$node][0];
		$nodeAttrPos = $nodes[4][$node][1];
		
		$nodePrevText = $nodes[1][$node][0];
		$nodePrevTextPos = $nodes[1][$node][1];
		$nodeNextText = $nodes[5][$node][0];
		$nodeNextTextPos = $nodes[5][$node][1];
		
		$classNS = 'System__Parser__'.$this->parserName.'__NS__'.ucwords($nodeName).'__'.ucwords($nodeNamespace);
		
		if( trim($nodePrevText) != '' )
		{
			$prevText = $this->newText($nodePrevText);
			$scope->addChildNode($prevText);
		}
		
		//COMENTARIOS
		if($nodeType == '!')
		{
			if( substr($nodeRealName, 0, 2) == '--' )
			{
				$nodeAttr = substr($nodeAttr,0,-2);
				$n = $this->newComment($nodeAttr);
				
				//INTERNO
				if(substr($nodeRealName, 0, 3) == '---')
					$n->runatServer = true;
			
				$scope->addChildNode($n); 
			}
			else if($nodeName == 'DOCTYPE')
			{
				$n = $this->newText('<!DOCTYPE '.trim($nodeAttr).' >');
				$scope->addChildNode($n);
			}
			else
			{
				
			}
				
		}
		
		//CLOSE TAG
		else if($nodeType == '/')
		{
			
			if($scope->nodeName != $nodeName || $scope->nodeNameSpace != $nodeNamespace)
			{
				echo 'Error: end tag for element "'.$nodeAlias.'" which is not open';
				exit;
			}
			
			if(isset($nodeNamespace))
			{
				if(class_exists($classNS))
				{
					call_user_func(array($classNS, 'afterParser'), $scope, $scope->parentNode);
				}
			}
			
			$scope = $scope->parentNode;
			$lastScope = $scope;
						
		}
		
		//OPEN TAG
		else
		{
			$inline = false;
			
			if(substr($nodeAttr, -1) == '/')
			{
				$inline = true;
				$nodeAttr = substr($nodeAttr,0,-1); 
			}
		
			$n = $this->newElement($nodeName,$nodeNamespace,$this->parserAttr($nodeAttr),array(),array(),$inline);
			
			if($inline == true)
			{
				$scope->addChildNode($n);
			}
			
			if(isset($nodeNamespace))
			{
				if(class_exists($classNS))
				{
					call_user_func(array($classNS, 'beforeParser'), $n, $scope);
				}
			}
			
			if($inline == false)
			{
				$scope->addChildNode($n);	
				$scope = $n;
			}
			
			$lastScope = $n;
			
		}
		
		if(  trim($nodeNextText) != '' )
		{
			$nextText = $this->newText($nodeNextText);
			$scope->addChildNode($nextText);
		}
		
		$node++;
	}
	
	
	return true;
   }
   
   private function parserAttr($attr_string){
   	$t = preg_match_all("/([^\s=]+)\s*=\s*(\'[^<\']*\'|\"[^<\"]*\")/", $attr_string, $properts, PREG_SET_ORDER);
   	$attr_array = array();
   	
   	if($t > 0){
   		for($c = 0; $c < $t; $c++){
   			$attr_array[$properts[$c][1]] = substr($properts[$c][2],1, -1);
   		}
   	}
   	
   	return $attr_array;
   }

   public function __toString(){
	return ''.$this->document;
   }
  
}
