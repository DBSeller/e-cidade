<?php

class cl_termoinscrreg
{
   // cria variaveis de erro 
    public $rotulo = null; 
    public $query_sql = null; 
    public $numrows = 0; 
    public $numrows_incluir = 0; 
    public $numrows_alterar = 0; 
    public $numrows_excluir = 0; 
    public $erro_status = null; 
    public $erro_sql = null; 
    public $erro_banco = null;  
    public $erro_msg = null;  
    public $erro_campo = null;  
    public $pagina_retorno = null; 
    /* Variáveis do Arquivo */
    public $v93_termo = 0; 
    public $v93_coddiv = 0; 
    public $v93_vlrhis = 0; 
    public $v93_vlrcor = 0; 
    public $v93_vlrjur = 0; 
    public $v93_vlrmul = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 v93_termo = int8 = Código 
                 v93_coddiv = int4 = Código dívida 
                 v93_vlrhis = float8 = valor historico 
                 v93_vlrcor = float8 = valor corrigido 
                 v93_vlrjur = float8 = valor juros 
                 v93_vlrmul = float8 = valor multa 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("termoinscrreg"); 
        $this->pagina_retorno = basename($_SERVER['PHP_SELF']);
    }

    public function erro($mostra, $retorna)
    {
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\")</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }

    public function atualizacampos($exclusao = false)
    {
     if($exclusao==false){
       $this->v93_termo = ($this->v93_termo == ""?@$GLOBALS["HTTP_POST_VARS"]["v93_termo"]:$this->v93_termo);
       $this->v93_coddiv = ($this->v93_coddiv == ""?@$GLOBALS["HTTP_POST_VARS"]["v93_coddiv"]:$this->v93_coddiv);
       $this->v93_vlrhis = ($this->v93_vlrhis == ""?@$GLOBALS["HTTP_POST_VARS"]["v93_vlrhis"]:$this->v93_vlrhis);
       $this->v93_vlrcor = ($this->v93_vlrcor == ""?@$GLOBALS["HTTP_POST_VARS"]["v93_vlrcor"]:$this->v93_vlrcor);
       $this->v93_vlrjur = ($this->v93_vlrjur == ""?@$GLOBALS["HTTP_POST_VARS"]["v93_vlrjur"]:$this->v93_vlrjur);
       $this->v93_vlrmul = ($this->v93_vlrmul == ""?@$GLOBALS["HTTP_POST_VARS"]["v93_vlrmul"]:$this->v93_vlrmul);
     }else{
     }
   }

    public function incluir()
    {
      $this->atualizacampos();
     if($this->v93_termo == null ){ 
       $this->erro_sql = " Campo Código não informado.";
       $this->erro_campo = "v93_termo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v93_coddiv == null ){ 
       $this->erro_sql = " Campo Código dívida não informado.";
       $this->erro_campo = "v93_coddiv";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v93_vlrhis == null ){ 
       $this->erro_sql = " Campo valor historico não informado.";
       $this->erro_campo = "v93_vlrhis";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v93_vlrcor == null ){ 
       $this->erro_sql = " Campo valor corrigido não informado.";
       $this->erro_campo = "v93_vlrcor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v93_vlrjur == null ){ 
       $this->v93_vlrjur = "0";
     }
     if($this->v93_vlrmul == null ){ 
       $this->v93_vlrmul = "0";
     }
     $sql = "insert into termoinscrreg(
                                       v93_termo 
                                      ,v93_coddiv 
                                      ,v93_vlrhis 
                                      ,v93_vlrcor 
                                      ,v93_vlrjur 
                                      ,v93_vlrmul 
                       )
                values (
                                $this->v93_termo 
                               ,$this->v93_coddiv 
                               ,$this->v93_vlrhis 
                               ,$this->v93_vlrcor 
                               ,$this->v93_vlrjur 
                               ,$this->v93_vlrmul 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "termoinscrreg () não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "termoinscrreg já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "termoinscrreg () não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

     }
     return true;
   } 

    public function alterar($v93_coddiv=null)
    {
      $this->atualizacampos();
     $sql = " update termoinscrreg set ";
     $virgula = "";
     if(trim($this->v93_termo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v93_termo"])){ 
       $sql  .= $virgula." v93_termo = $this->v93_termo ";
       $virgula = ",";
       if(trim($this->v93_termo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "v93_termo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v93_coddiv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v93_coddiv"])){ 
       $sql  .= $virgula." v93_coddiv = $this->v93_coddiv ";
       $virgula = ",";
       if(trim($this->v93_coddiv) == null ){ 
         $this->erro_sql = " Campo Código dívida não informado.";
         $this->erro_campo = "v93_coddiv";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v93_vlrhis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v93_vlrhis"])){ 
       $sql  .= $virgula." v93_vlrhis = $this->v93_vlrhis ";
       $virgula = ",";
       if(trim($this->v93_vlrhis) == null ){ 
         $this->erro_sql = " Campo valor historico não informado.";
         $this->erro_campo = "v93_vlrhis";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v93_vlrcor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v93_vlrcor"])){ 
       $sql  .= $virgula." v93_vlrcor = $this->v93_vlrcor ";
       $virgula = ",";
       if(trim($this->v93_vlrcor) == null ){ 
         $this->erro_sql = " Campo valor corrigido não informado.";
         $this->erro_campo = "v93_vlrcor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v93_vlrjur)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v93_vlrjur"])){ 
        if(trim($this->v93_vlrjur)=="" && isset($GLOBALS["HTTP_POST_VARS"]["v93_vlrjur"])){ 
           $this->v93_vlrjur = "0" ; 
        } 
       $sql  .= $virgula." v93_vlrjur = $this->v93_vlrjur ";
       $virgula = ",";
     }
     if(trim($this->v93_vlrmul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v93_vlrmul"])){ 
        if(trim($this->v93_vlrmul)=="" && isset($GLOBALS["HTTP_POST_VARS"]["v93_vlrmul"])){ 
           $this->v93_vlrmul = "0" ; 
        } 
       $sql  .= $virgula." v93_vlrmul = $this->v93_vlrmul ";
       $virgula = ",";
     }
     $sql .= " where ";
$sql .= "v93_coddiv = $v93_coddiv";     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "termoinscrreg não Alterado. Alteração Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "termoinscrreg não foi Alterado. Alteração Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($v01_coddiv , $dbwhere = null)
    {
     $sql = " delete from termoinscrreg
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
       $sql2 = "v93_coddiv = '$v01_coddiv'";
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "termoinscrreg não Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "termoinscrreg não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function sql_record($sql)
    {
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:termoinscrreg";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($oid = null, $campos = "termoinscrreg.oid,*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from termoinscrreg ";
     $sql .= "      inner join divida  on  divida.v01_coddiv = termoinscrreg.v93_coddiv";
     $sql .= "      inner join termoinscr  on  termoinscr.v92_termo = termoinscrreg.v93_termo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = divida.v01_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = divida.v01_instit";
     $sql .= "      inner join proced  on  proced.v03_codigo = divida.v01_proced";
     $sql .= "      inner join db_config  as a on   a.codigo = termoinscr.v92_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = termoinscr.v92_usuario";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($oid)) {
          $sql2 = " where termoinscrreg.oid = '$oid'";
       }
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

    public function sql_query_file($oid = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from termoinscrreg ";
     $sql2 = "";
     if (empty($dbwhere)) {
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

    public function sql_query_deb ( $v93_termo=null,$v93_coddiv=null,$campos="*",$ordem=null,$dbwhere="", $instit=""){ 
      
      if($instit==""){
      $instit = db_getsession('DB_instit');
      }
      
      $sql = "select ";
      if($campos != "*" ){
        $campos_sql = split("#",$campos);
        $virgula = "";
        for($i=0;$i<sizeof($campos_sql);$i++){
          $sql .= $virgula.$campos_sql[$i];
          $virgula = ",";
        }
      }else{
        $sql .= $campos;
      }
      $sql .= " from termoinscrreg ";
      $sql .= "      inner join divida		  on divida.v01_coddiv = termoinscrreg.v93_coddiv   ";
      $sql .= "                           and v01_instit = $instit										  ";
      $sql .= "      left  join arrematric on divida.v01_numpre = arrematric.k00_numpre";
      $sql .= "      left  join arreinscr  on divida.v01_numpre = arreinscr.k00_numpre ";
      $sql .= "      inner join termoinscr			on termoinscr.v92_termo = termoinscrreg.v93_termo   ";
      $sql .= "                           and v92_instit = $instit										  ";
      $sql .= "      inner join cgm			  on cgm.z01_numcgm    = divida.v01_numcgm	  ";
      $sql .= "      inner join proced		  on proced.v03_codigo = divida.v01_proced		";
      $sql2 = "";
      if($dbwhere==""){
        if($v93_termo!=null ){
          $sql2 .= " where termoinscrreg.v93_termo = $v93_termo "; 
        } 
        if($v93_coddiv!=null ){
          if($sql2!=""){
            $sql2 .= " and ";
          }else{
            $sql2 .= " where ";
          } 
          $sql2 .= " termoinscrreg.v93_coddiv = $v93_coddiv "; 
        } 
      }else if($dbwhere != ""){
        $sql2 = " where $dbwhere";
      }
      $sql .= $sql2;
      if($ordem != null ){
        $sql .= " order by ";
        $campos_sql = split("#",$ordem);
        $virgula = "";
        for($i=0;$i<sizeof($campos_sql);$i++){
          $sql .= $virgula.$campos_sql[$i];
          $virgula = ",";
        }
      }
      return $sql;
  }
}
