<?php

abstract class System__Parser__MarkupLanguage__Node{
	
	public $nodeType; //1 element, 2 text, 3 comment, 4 document, 5 attributes, 6 entitys
	public $nodeName;
	public $nodeValue;
	public $nodeNameSpace;
	public $runatServer;
	
	protected $attributes = array();
	protected $propertys = array();
	protected $childNodes = array();
	
	private $extends = array();
	
	final protected function classExtend($name){
		array_unshift($this->extends, new $name($this));
	}
	
	final public function __call($name, $params = null){

		foreach($this->extends as $extend){
			try{
				return call_user_func_array(array($extend, $name), $params);
			}catch(Exception $e){
				//throw $e;
			}
		}
		
		throw new Exception( " Method " . $name . " not exist in this class " . get_class( $this ) . "." );
		
	}
		
	final public function __get($name){
		
		if(!(strpos($name, '__') === 0)){
			$name = '__'.$name;	
		}
		
		if(!method_exists($this,$name)){
						
			foreach($this->extends as $extend){
				try{
					$ret = $extend->$name;
				}catch(Exception $e){
					//throw $e;
				}
				
				if(isset($ret))
					return $ret;
			}
			
			throw new Exception( " Property " . $name . " not exist in this class " . get_class( $this ) . "." );
		}
		
		return $this->$name();
		
	}
	
	final public function __set($name, $value){
		
		if(!(strpos($name, '__') === 0)){
			$name = '__'.$name;	
		}
		
		if(!method_exists($this,$name)){
			
			foreach($this->extends as $extend){
				try{
					$extend->$name = $value;
					return;
				}
				catch(Exception $e){
					//throw $e;
				}
			}
			
			throw new Exception( " Property " . $name . " not exist in this class " . get_class( $this ) . "." );
		}
		
		$this->$name($value);
		
	}
	
	private function __cloneLoop($obj = null){
		
		if(!isset($obj)) return;
		
		$type = gettype($obj);
		
		
		foreach($obj as $name => $value){
			$valueType = gettype($value);
			    
			if($type == 'object' && $valueType == 'object' && ($name != 'parentNode'))
            {
                $obj->$name = clone($obj->$name);
            }
            else if($type == 'object' && $valueType == 'array' && ($name == 'extends' || $name == 'childNodes' || $name == 'attributes' || $name == 'propertys') )
	        {
	        	if($name == 'extends'){
	        		foreach($obj->$name as $position=>$extend){
	        			$nextend = clone $extend;
	        			$nextend->super = $obj;
	        			array_unshift($obj->extends, $nextend);
	        		}
	        	}
	        	
	        	if($name == 'childNodes' && $obj->nodeType == 1){
	        		$nodes = $obj->getChildNodes();
	        		$obj->clearChildNodes();
	        		
	        		foreach($nodes as $node){
	        			$nnode = clone $node;
						$obj->addChildNode($nnode);
	        		}
	        	}
	        	
	        	if($name == 'attributes' && $obj->nodeType == 1){
	        		$attrs = $obj->getAttributes();
	        		$obj->clearAttributes();
	        		
	        		foreach($attrs as $attr){
	        			$nattr = clone $attr;
						$obj->setAttribute($nattr->nodeName, $nattr->nodeValue);
	        		}
	        	}
	        	
	        	$this->__cloneLoop($obj->$name);
	        	
	        }
	        else if($type == 'array' && $valueType == 'object')
            {
            	$obj[$name] = clone($obj[$name]);
            }
            else if($type == 'array' && $valueType == 'array')
            {
            	$this->__cloneLoop($obj[$name]);
            }
        }
	}
	
	public function __clone(){
		if(isset($this->nodeType))
			$this->__cloneLoop($this);
	}
	
	
}
