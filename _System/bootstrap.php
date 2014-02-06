<?php

if (!defined('START_TIME')) define ('START_TIME', microtime(1));

// Seta o diretorio deste script como SYSTEM_DIR

if (!defined('ROOT_DIR')) define ('ROOT_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');

if (!defined('SYSTEM_DIR')) define ('SYSTEM_DIR', '_' . dirname(__FILE__) . '/');

if (!defined('CLASSES_DIR')) define ('CLASSES_DIR', '_Classes/');


//Carrega classes automaticamente
function __autoload($caller){
	//caso seja um controle
	if(stripos($caller, '__Controller'))
	{
		//Pasta__Pasta__Arquivo
		//Web__Control__Page
		$namespace = str_replace('__Controller', '', $caller);
		$namespace = str_replace('__', '/', $namespace);
		$filePath = ROOT_DIR.'Interface/Controller/'.$namespace.'.php';
	}
	// caso encontrar __ significa que estamos carregando um modulo que deve estar dentro do Apart
	else if(stripos($caller, '__'))
	{
		//Pasta__Pasta__Arquivo
		//Web__Control__Page
		$namespace = str_replace('__', '/', $caller);
		$filePath = ROOT_DIR.'_'.$namespace.'.php';
	}
	//caso contrario vamos verificar se a classe existe na pasta Classes
	else
	{
		$filePath = ROOT_DIR.'_Classes/'.$caller.'.php';
	}
	
	$filePath = preg_replace('/\/+/', '/', $filePath);
	
	if(file_exists($filePath))
		require_once($filePath);
	else
		die('O arquivo '.$filePath.' não existe.');

	class_exists($caller) or die('Classe '.$caller.' não foi declarada no arquivo: '.$filePath);
}

function is_allsubclass_of($class, $subclass){
	$verify = get_parent_class($class);
	
	if($verify && $verify == $subclass) 
		return true;

	return $verify ? is_allsubclass_of($verify, $subclass) : false;
}

function __btsLoadPage($pagename, $param_get = false, $param_post = false, $secury = false){
	
	//converte ifen para underline
	$pagename = preg_replace('/[\-]{1}/', '_',$pagename);

	$fileCS = preg_replace('/\/+/', '/', ROOT_DIR.'Interface/Controller/'.$pagename.'.php');
	$fileTM = preg_replace('/\/+/', '/', ROOT_DIR.'Interface/View/'.$pagename.'.html' );
	
	if(file_exists($fileCS))
	{
		require_once($fileCS);
		
		$pagename = preg_replace('/\/+/', '__', $pagename);
		$pagename .= '__Controller';

		class_exists($pagename) or die('Classe '.$pagename.' não foi declarada em: '.$fileCS);
		
		is_allsubclass_of($pagename, 'System__Web__PageController') or die('Classe '.$pagename.' não é instancia de System__Web__PageController.');
		
		if( !$secury || is_allsubclass_of($pagename, 'System__Web__StandardPageController') ){
			
			if(file_exists($fileTM))
			{
				$reflectionClass = new ReflectionClass($pagename);
				
				if(!$reflectionClass->isFinal())
					die('A pagina ou controle "'.$pagename.'" chamada foi negada por segurança. Controles que contem referencia a views, devem ser declaradas como "final".');
			}
			else
			{
				$fileTM = false;
			}
			
			$page = new $pagename($fileTM, $param_get, $param_post);
			return $page;
		}
		else if(is_allsubclass_of($pagename, 'System__Web__InternalPageController'))
			die('Voce não pode chamar paginas com instancia de System__Web__InternalPageController diretamente.');
		else if(is_allsubclass_of($pagename, 'System__Web__MasterPageController'))
			die('Voce não pode chamar controles com instancia de System__Web__MasterPageController diretamente.');
		else
			die('A pagina que voce tentou acessar foi negada por segurança, verifique se é instancia de System__Web__StandardPageController.');

	}
	else
		die('Arquivo do controle não foi encontrado em:'.$fileCS);
		
	die('Não foi possivel carregar o controle.');
}

//Carrega o bootstrap
function __btsload(){
	$clone_get = $_GET;
	unset($_GET);
	
	$clone_post = $_POST;
	unset($_POST);

	//Varre os parametros enviados por get
	foreach($clone_get as $param=>$value){
		//echo $value;
		switch($param){
			case 'bts_load_page' :
				echo __btsLoadPage(  $value , $_GET, $_POST,  true);					
			break;
		}
	}
}

__btsload();

if (!defined('END_TIME')) define ('END_TIME', microtime(1));
if (!defined('EXECUTION_TIME')) define ('EXECUTION_TIME', END_TIME - START_TIME);

//echo "<br/>Tempo para execução: <b>" .sprintf ( "%02.3f", EXECUTION_TIME );