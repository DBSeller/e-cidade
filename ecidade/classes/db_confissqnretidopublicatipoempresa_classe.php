<?php

class cl_confissqnretidopublicatipoempresa
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
    public $j171_sequencial = 0; 
    public $j171_confissqnretidopublica = 0; 
    public $j171_tipoempresa = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 j171_sequencial = int4 = Sequencial 
                 j171_confissqnretidopublica = int4 = Sequencial 
                 j171_tipoempresa = int4 = Tipo de Empresa 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("confissqnretidopublicatipoempresa"); 
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
       $this->j171_sequencial = ($this->j171_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j171_sequencial"]:$this->j171_sequencial);
       $this->j171_confissqnretidopublica = ($this->j171_confissqnretidopublica == ""?@$GLOBALS["HTTP_POST_VARS"]["j171_confissqnretidopublica"]:$this->j171_confissqnretidopublica);
       $this->j171_tipoempresa = ($this->j171_tipoempresa == ""?@$GLOBALS["HTTP_POST_VARS"]["j171_tipoempresa"]:$this->j171_tipoempresa);
     }else{
       $this->j171_sequencial = ($this->j171_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j171_sequencial"]:$this->j171_sequencial);
     }
   }

    public function incluir($j171_sequencial)
    {
      $this->atualizacampos();
     if($this->j171_confissqnretidopublica == null ){ 
       $this->erro_sql = " Campo Sequencial não informado.";
       $this->erro_campo = "j171_confissqnretidopublica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j171_tipoempresa == null ){ 
       $this->erro_sql = " Campo Tipo de Empresa não informado.";
       $this->erro_campo = "j171_tipoempresa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j171_sequencial == "" || $j171_sequencial == null ){
       $result = db_query("select nextval('confissqnretidopublicatipoempresa_j171_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: confissqnretidopublicatipoempresa_j171_sequencial_seq do campo: j171_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j171_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from confissqnretidopublicatipoempresa_j171_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $j171_sequencial)){
         $this->erro_sql = " Campo j171_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j171_sequencial = $j171_sequencial; 
       }
     }
     if(($this->j171_sequencial == null) || ($this->j171_sequencial == "") ){ 
       $this->erro_sql = " Campo j171_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into confissqnretidopublicatipoempresa(
                                       j171_sequencial 
                                      ,j171_confissqnretidopublica 
                                      ,j171_tipoempresa 
                       )
                values (
                                $this->j171_sequencial 
                               ,$this->j171_confissqnretidopublica 
                               ,$this->j171_tipoempresa 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tipo empresa publica ISSQN Retido ($this->j171_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tipo empresa publica ISSQN Retido já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tipo empresa publica ISSQN Retido ($this->j171_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->j171_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->j171_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1011996,'$this->j171_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010651,1011996,'','".AddSlashes(pg_result($resaco,0,'j171_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010651,1011997,'','".AddSlashes(pg_result($resaco,0,'j171_confissqnretidopublica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010651,1011998,'','".AddSlashes(pg_result($resaco,0,'j171_tipoempresa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($j171_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update confissqnretidopublicatipoempresa set ";
     $virgula = "";
     if(trim($this->j171_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j171_sequencial"])){ 
       $sql  .= $virgula." j171_sequencial = $this->j171_sequencial ";
       $virgula = ",";
       if(trim($this->j171_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "j171_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j171_confissqnretidopublica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j171_confissqnretidopublica"])){ 
       $sql  .= $virgula." j171_confissqnretidopublica = $this->j171_confissqnretidopublica ";
       $virgula = ",";
       if(trim($this->j171_confissqnretidopublica) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "j171_confissqnretidopublica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j171_tipoempresa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j171_tipoempresa"])){ 
       $sql  .= $virgula." j171_tipoempresa = $this->j171_tipoempresa ";
       $virgula = ",";
       if(trim($this->j171_tipoempresa) == null ){ 
         $this->erro_sql = " Campo Tipo de Empresa não informado.";
         $this->erro_campo = "j171_tipoempresa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j171_sequencial!=null){
       $sql .= " j171_sequencial = $this->j171_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->j171_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1011996,'$this->j171_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j171_sequencial"]) || $this->j171_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010651,1011996,'".AddSlashes(pg_result($resaco,$conresaco,'j171_sequencial'))."','$this->j171_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j171_confissqnretidopublica"]) || $this->j171_confissqnretidopublica != "")
             $resac = db_query("insert into db_acount values($acount,1010651,1011997,'".AddSlashes(pg_result($resaco,$conresaco,'j171_confissqnretidopublica'))."','$this->j171_confissqnretidopublica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j171_tipoempresa"]) || $this->j171_tipoempresa != "")
             $resac = db_query("insert into db_acount values($acount,1010651,1011998,'".AddSlashes(pg_result($resaco,$conresaco,'j171_tipoempresa'))."','$this->j171_tipoempresa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipo empresa publica ISSQN Retido não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j171_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tipo empresa publica ISSQN Retido não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j171_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->j171_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($j171_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($j171_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1011996,'$j171_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010651,1011996,'','".AddSlashes(pg_result($resaco,$iresaco,'j171_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010651,1011997,'','".AddSlashes(pg_result($resaco,$iresaco,'j171_confissqnretidopublica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010651,1011998,'','".AddSlashes(pg_result($resaco,$iresaco,'j171_tipoempresa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from confissqnretidopublicatipoempresa
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($j171_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " j171_sequencial = $j171_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipo empresa publica ISSQN Retido não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j171_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tipo empresa publica ISSQN Retido não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j171_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$j171_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:confissqnretidopublicatipoempresa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($j171_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from confissqnretidopublicatipoempresa ";
     $sql .= "      inner join tipoempresa  on  tipoempresa.db98_sequencial = confissqnretidopublicatipoempresa.j171_tipoempresa";
     $sql .= "      inner join confissqnretidopublica  on  confissqnretidopublica.j170_sequencial = confissqnretidopublicatipoempresa.j171_confissqnretidopublica";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = confissqnretidopublica.j170_receit";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($j171_sequencial)) {
         $sql2 .= " where confissqnretidopublicatipoempresa.j171_sequencial = $j171_sequencial "; 
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

    public function sql_query_file($j171_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from confissqnretidopublicatipoempresa ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($j171_sequencial)){
         $sql2 .= " where confissqnretidopublicatipoempresa.j171_sequencial = $j171_sequencial "; 
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

}
