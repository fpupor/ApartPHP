<?php

class System__Parser__HTML__Parser extends System__Parser__MarkupLanguage__Parser {
   public $parserName = 'HTML';
   
   public function newDocument(){
   	return new System__Parser__HTML__Document();
   }
   
   public function newElement($name,$namespace = null,$attributes = array(),$propertys = array(),$childnodes = array(),$selfclose = false){
   	return new System__Parser__HTML__Element($name,$namespace,$attributes,$propertys,$childnodes,$selfclose);
   }
   
   public function newText($string){
   	return new System__Parser__HTML__Text($string);
   }
   
   public function newEntity($string){
   	return new System__Parser__HTML__Entity($string);
   }
   
   public function newComment($string){
   	return new System__Parser__HTML__Comment($string);
   }
  
}
