<?php

class cl_empagemovhistorico
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
    public $e141_sequencial = 0; 
    public $e141_empagemov = 0; 
    public $e141_historico = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 e141_sequencial = int4 = Sequencial do Historico do Movimento 
                 e141_empagemov = int4 = Movimento da Agenda 
                 e141_historico = varchar(255) = Historico do Movimento 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("empagemovhistorico"); 
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
       $this->e141_sequencial = ($this->e141_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e141_sequencial"]:$this->e141_sequencial);
       $this->e141_empagemov = ($this->e141_empagemov == ""?@$GLOBALS["HTTP_POST_VARS"]["e141_empagemov"]:$this->e141_empagemov);
       $this->e141_historico = ($this->e141_historico == ""?@$GLOBALS["HTTP_POST_VARS"]["e141_historico"]:$this->e141_historico);
     }else{
       $this->e141_sequencial = ($this->e141_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e141_sequencial"]:$this->e141_sequencial);
     }
   }

    public function incluir($e141_sequencial)
    {
      $this->atualizacampos();
     if($this->e141_empagemov == null ){ 
       $this->erro_sql = " Campo Movimento da Agenda não informado.";
       $this->erro_campo = "e141_empagemov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e141_historico == null ){ 
       $this->erro_sql = " Campo Historico do Movimento não informado.";
       $this->erro_campo = "e141_historico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($e141_sequencial == "" || $e141_sequencial == null ){
       $result = db_query("select nextval('empagemovhistorico_e141_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: empagemovhistorico_e141_sequencial_seq do campo: e141_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->e141_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from empagemovhistorico_e141_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e141_sequencial)){
         $this->erro_sql = " Campo e141_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e141_sequencial = $e141_sequencial; 
       }
     }
     if(($this->e141_sequencial == null) || ($this->e141_sequencial == "") ){ 
       $this->erro_sql = " Campo e141_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empagemovhistorico(
                                       e141_sequencial 
                                      ,e141_empagemov 
                                      ,e141_historico 
                       )
                values (
                                $this->e141_sequencial 
                               ,$this->e141_empagemov 
                               ,'$this->e141_historico' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Historico do Movimento ($this->e141_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Historico do Movimento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Historico do Movimento ($this->e141_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->e141_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e141_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1014067,'$this->e141_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010915,1014067,'','".AddSlashes(pg_result($resaco,0,'e141_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010915,1014068,'','".AddSlashes(pg_result($resaco,0,'e141_empagemov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010915,1014069,'','".AddSlashes(pg_result($resaco,0,'e141_historico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($e141_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update empagemovhistorico set ";
     $virgula = "";
     if(trim($this->e141_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e141_sequencial"])){ 
       $sql  .= $virgula." e141_sequencial = $this->e141_sequencial ";
       $virgula = ",";
       if(trim($this->e141_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial do Historico do Movimento não informado.";
         $this->erro_campo = "e141_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e141_empagemov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e141_empagemov"])){ 
       $sql  .= $virgula." e141_empagemov = $this->e141_empagemov ";
       $virgula = ",";
       if(trim($this->e141_empagemov) == null ){ 
         $this->erro_sql = " Campo Movimento da Agenda não informado.";
         $this->erro_campo = "e141_empagemov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e141_historico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e141_historico"])){ 
       $sql  .= $virgula." e141_historico = '$this->e141_historico' ";
       $virgula = ",";
       if(trim($this->e141_historico) == null ){ 
         $this->erro_sql = " Campo Historico do Movimento não informado.";
         $this->erro_campo = "e141_historico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e141_sequencial!=null){
       $sql .= " e141_sequencial = $this->e141_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e141_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1014067,'$this->e141_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e141_sequencial"]) || $this->e141_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010915,1014067,'".AddSlashes(pg_result($resaco,$conresaco,'e141_sequencial'))."','$this->e141_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e141_empagemov"]) || $this->e141_empagemov != "")
             $resac = db_query("insert into db_acount values($acount,1010915,1014068,'".AddSlashes(pg_result($resaco,$conresaco,'e141_empagemov'))."','$this->e141_empagemov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e141_historico"]) || $this->e141_historico != "")
             $resac = db_query("insert into db_acount values($acount,1010915,1014069,'".AddSlashes(pg_result($resaco,$conresaco,'e141_historico'))."','$this->e141_historico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Historico do Movimento não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e141_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Historico do Movimento não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e141_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->e141_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($e141_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($e141_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1014067,'$e141_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010915,1014067,'','".AddSlashes(pg_result($resaco,$iresaco,'e141_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010915,1014068,'','".AddSlashes(pg_result($resaco,$iresaco,'e141_empagemov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010915,1014069,'','".AddSlashes(pg_result($resaco,$iresaco,'e141_historico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from empagemovhistorico
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($e141_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " e141_sequencial = $e141_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Historico do Movimento não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e141_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Historico do Movimento não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e141_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$e141_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:empagemovhistorico";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($e141_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from empagemovhistorico ";
     $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empagemovhistorico.e141_empagemov";
     $sql .= "      inner join empage  on  empage.e80_codage = empagemov.e81_codage";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($e141_sequencial)) {
         $sql2 .= " where empagemovhistorico.e141_sequencial = $e141_sequencial "; 
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

    public function sql_query_file($e141_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from empagemovhistorico ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($e141_sequencial)){
         $sql2 .= " where empagemovhistorico.e141_sequencial = $e141_sequencial "; 
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
