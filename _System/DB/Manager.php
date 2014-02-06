<?php
class System__DB__Manager {
    var $conect;
    var $database;
    var $query;
    var $db;
    var $host;
    var $usuario;
    private $senha;
      
    
    public function __construct($host,$db,$usuario,$senha){
    	$this->host 	=	$host;
    	$this->db		=	$db;
    	$this->usuario 	=	$usuario;
    	$this->senha	=	$senha;
    } 
    
    public function gravando_erros($erro){
        $arquivo = fopen('db_error.log','a');
        fwrite($arquivo,"[".date("r")."] Erro: $erro\r\n");
        fclose($arquivo);
    } 
    
    public function erro($msgerro){ 
        $exibemsg = "Ocorreu um problema durante a manipula��o dos dados! ";
        $exibemsg .= "O erro encontrado foi: <br />";
        $exibemsg .= "$msgerro"; //Erro MYSQL 
                
        $this->gravando_erros($msgerro);
                
        return $exibemsg;
    } 
    
    
    // Inicia a Conex�o com a Base de Dados
    function conectar(){
        $this->conect = mysql_connect($this->host, $this->usuario, $this->senha) or die ('erro');
        
        if($this->conect)
        {
                //return true;
        }
        else
        {
                $this->erro(mysql_error());
        }
        
        $this->database = mysql_select_db($this->db, $this->conect) or die ('Not select');
        
        if(mysql_select_db($this->db, $this->conect))
        {
                return true;
        }
        else
        {
                $this->erro(mysql_error());
        }
    } 

    // Fun��o Anti SQL Injection
    public function antiSQLinjection($recebe){
        $recebe = get_magic_quotes_gpc() == 0 ? addslashes($recebe) : $recebe; // Verifica a configura��o de "magic_quotes_gpc"
        $recebe = strip_tags($recebe); // Limpa as tags HTML
        $recebe = preg_replace(sql_regcase("/(from|select|insert|delete|where|drop table|show tables|\\\\)/"),"",$recebe);
        $recebe = preg_replace("@(--|\#|\*|;|original.gif@s", "", $recebe);
        return $recebe;
    } 

    // Usando INSERT
    function inserir($tabela,$dados){
		// Dividimos o Array dentro de $dados em Chaves e Valores
        $campos = array_keys($dados);
        $valores = array_values($dados);
		//Executamos a Query    
        $this->query = mysql_query("INSERT INTO ".$tabela." (".implode(', ', $campos).") VALUES ('" . implode('\', \'', $valores) . "')");
		//Verificamos se a Query foi executada corretamente
        if($this->query)
        {
                return true;
        }
        else
        {
                $this->erro(mysql_error());
        }
    }
    
    // Usando DELETE
    function deletar($tabela,$where){
        $this->query = mysql_query("DELETE FROM ".$tabela." WHERE ".$where."");
        if($this->query)
        {
                return true;
        }
        else
        {
                $this->erro(mysql_error());
        }
    } 
    
    // Usando UPDATE
    function atualizar($tabela,$valores){
        $this->query = mysql_query("UPDATE ".$tabela." SET ('".$valores."')");
        if($this->query)
        {
                return true;
        }
        else
        {
                $this->erro(mysql_error());
        }
    }
     
    // Usando SELECT
    function selecionar($campos,$tabela,$where = '',$orderby = '',$alinhamento = '',$limit = '')
{
        //SELECT campos FROM tabela
        if(!empty($campos) && !empty($tabela) && empty($where) && empty($orderby) && empty($alinhamento) && empty($limit))
        {
                $this->query = mysql_query("SELECT ".$campos." FROM ".$tabela."", $this->conect) or die("Could not connect: " . mysql_error());
                return true;
        }
        //SELECT campos FROM tabela WHERE variavel = variavel
        elseif(!empty($campos) && !empty($tabela) && !empty($where) && empty($orderby) && empty($alinhamento) && empty($limit))
        {
                $this->query = mysql_query("SELECT ".$campos." FROM ".$tabela." WHERE ".$where."", $this->conect) or die("Could not connect: " . mysql_error());
                return true;
        }
        //SELECT campos FROM tabela WHERE variavel = variavel LIMIT 5
        elseif(!empty($campos) && !empty($tabela) && !empty($where) && empty($orderby) && empty($alinhamento) && !empty($limit))
        {
                $this->query = mysql_query("SELECT ".$campos." FROM ".$tabela." WHERE ".$where." LIMIT ".$limit."", $this->conect) or die("Could not connect: " . mysql_error());
                return true;
        }
        //SELECT campos FROM tabela WHERE variavel = variavel DESC
        elseif(!empty($campos) && !empty($tabela) && !empty($where) && empty($orderby) && !empty($alinhamento) && empty($limit))
        {
                $this->query = mysql_query("SELECT ".$campos." FROM ".$tabela." WHERE ".$where." ".$alinhamento."", $this->conect) or die("Could not connect: " . mysql_error());
                return true;
        }
        //SELECT campos FROM tabela WHERE variavel = variavel ORDER BY variavel
        elseif(!empty($campos) && !empty($tabela) && !empty($where) && !empty($orderby) && empty($alinhamento) && empty($limit))
        {
                $this->query = mysql_query("SELECT ".$campos." FROM ".$tabela." WHERE ".$where." ORDER BY ".$orderby."", $this->conect) or die("Could not connect: " . mysql_error());
                return true;
        }
        //SELECT campos FROM tabela WHERE variavel = variavel ORDER BY variavel DESC
        elseif(!empty($campos) && !empty($tabela) && !empty($where) && !empty($orderby) && !empty($alinhamento) && empty($limit))
        {
                $this->query = mysql_query("SELECT ".$campos." FROM ".$tabela." WHERE ".$where." ORDER BY ".$orderby." ".$alinhamento."", $this->conect) or die("Could not connect: " . mysql_error());
                return true;
        }
        //SELECT campos FROM tabela WHERE variavel = variavel ORDER BY variavel DESC LIMIT 5
        elseif(!empty($campos) && !empty($tabela) && !empty($where) && !empty($orderby) && !empty($alinhamento) && !empty($limit))
        {
                $this->query = mysql_query("SELECT ".$campos." FROM ".$tabela." WHERE ".$where." ORDER BY ".$orderby." ".$alinhamento." LIMIT ".$limit."", $this->conect) or die("Could not connect: " . mysql_error());
                return true;
        }
        return false;
    } 
    
    // Retorna os Resultados de uma Consulta
    function resultado($rowSelect = null){
    	$linhas = 0;
    	
    	while ($row = mysql_fetch_array($this->query, MYSQL_NUM)) {
    		if ($linhas == $rowSelect) {
    			return $row;
			}
			$linhas++;
		}
    } 
    
    function allRes() {
        return mysql_fetch_all($this->query);
    } 
    
    // Fecha Conex�o com a Base de Dados
    function desconectar(){
        mysql_close($this->conect);
    }
    
    public function __destruct(){
        return $this->desconectar();
    }
                
}


/* MODO DE USO
// Inclui o arquivo da classe
include("classe.php"); 
// Estancia o objeto e j� faz a conex�o
$sql = new conectar('localhost','database','user','password'); 
// Executa INSERT de dados
$dados = array($campo => 'campo', $outro_campo => 'outro_campo');
$sql->inserir('tabela','dados'); 
// Executa DELETE de dados
$sql->deletar('tabela','where');
// Executa UPDATE de dados
$sql->atualizar('tabela','valores');
// Executa SELECT de dados
$sql->selecionar('campos','tabela','where','orderby'
,'alinhamento','limit'); 
// Fecha a conex�o com Banco de Dados
$sql->desconectar();
*/