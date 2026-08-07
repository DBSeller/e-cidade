<?php

class cl_rhpessoalprocessoabono
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
    public $rh302_sequencial = 0; 
    public $rh302_sequencialprocessocontrato = 0; 
    public $rh302_anobase = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh302_sequencial = int8 = Número Sequencial 
                 rh302_sequencialprocessocontrato = int4 = Sequencial contrato 
                 rh302_anobase = varchar(4) = Ano abono 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhpessoalprocessoabono"); 
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
       $this->rh302_sequencial = ($this->rh302_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh302_sequencial"]:$this->rh302_sequencial);
       $this->rh302_sequencialprocessocontrato = ($this->rh302_sequencialprocessocontrato == ""?@$GLOBALS["HTTP_POST_VARS"]["rh302_sequencialprocessocontrato"]:$this->rh302_sequencialprocessocontrato);
       $this->rh302_anobase = ($this->rh302_anobase == ""?@$GLOBALS["HTTP_POST_VARS"]["rh302_anobase"]:$this->rh302_anobase);
     }else{
       $this->rh302_sequencial = ($this->rh302_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh302_sequencial"]:$this->rh302_sequencial);
     }
   }

    public function incluir($rh302_sequencial)
    {
      $this->atualizacampos();
     if($this->rh302_sequencialprocessocontrato == null ){ 
       $this->erro_sql = " Campo Sequencial contrato não informado.";
       $this->erro_campo = "rh302_sequencialprocessocontrato";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh302_anobase == null ){ 
       $this->erro_sql = " Campo Ano abono não informado.";
       $this->erro_campo = "rh302_anobase";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh302_sequencial == "" || $rh302_sequencial == null ){
       $result = db_query("select nextval('rhpessoalprocessoabono_rh302_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhpessoalprocessoabono_rh302_sequencial_seq do campo: rh302_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh302_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhpessoalprocessoabono_rh302_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh302_sequencial)){
         $this->erro_sql = " Campo rh302_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh302_sequencial = $rh302_sequencial; 
       }
     }
     if(($this->rh302_sequencial == null) || ($this->rh302_sequencial == "") ){ 
       $this->erro_sql = " Campo rh302_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpessoalprocessoabono(
                                       rh302_sequencial 
                                      ,rh302_sequencialprocessocontrato 
                                      ,rh302_anobase 
                       )
                values (
                                $this->rh302_sequencial 
                               ,$this->rh302_sequencialprocessocontrato 
                               ,'$this->rh302_anobase' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ano Abono ($this->rh302_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ano Abono já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ano Abono ($this->rh302_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh302_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh302_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1015373,'$this->rh302_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1011134,1015373,'','".AddSlashes(pg_result($resaco,0,'rh302_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011134,1015484,'','".AddSlashes(pg_result($resaco,0,'rh302_sequencialprocessocontrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011134,1015375,'','".AddSlashes(pg_result($resaco,0,'rh302_anobase'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh302_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhpessoalprocessoabono set ";
     $virgula = "";
     if(trim($this->rh302_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh302_sequencial"])){ 
       $sql  .= $virgula." rh302_sequencial = $this->rh302_sequencial ";
       $virgula = ",";
       if(trim($this->rh302_sequencial) == null ){ 
         $this->erro_sql = " Campo Número Sequencial não informado.";
         $this->erro_campo = "rh302_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh302_sequencialprocessocontrato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh302_sequencialprocessocontrato"])){ 
       $sql  .= $virgula." rh302_sequencialprocessocontrato = $this->rh302_sequencialprocessocontrato ";
       $virgula = ",";
       if(trim($this->rh302_sequencialprocessocontrato) == null ){ 
         $this->erro_sql = " Campo Sequencial contrato não informado.";
         $this->erro_campo = "rh302_sequencialprocessocontrato";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh302_anobase)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh302_anobase"])){ 
       $sql  .= $virgula." rh302_anobase = '$this->rh302_anobase' ";
       $virgula = ",";
       if(trim($this->rh302_anobase) == null ){ 
         $this->erro_sql = " Campo Ano abono não informado.";
         $this->erro_campo = "rh302_anobase";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh302_sequencial!=null){
       $sql .= " rh302_sequencial = $this->rh302_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh302_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1015373,'$this->rh302_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh302_sequencial"]) || $this->rh302_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1011134,1015373,'".AddSlashes(pg_result($resaco,$conresaco,'rh302_sequencial'))."','$this->rh302_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh302_sequencialprocessocontrato"]) || $this->rh302_sequencialprocessocontrato != "")
             $resac = db_query("insert into db_acount values($acount,1011134,1015484,'".AddSlashes(pg_result($resaco,$conresaco,'rh302_sequencialprocessocontrato'))."','$this->rh302_sequencialprocessocontrato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh302_anobase"]) || $this->rh302_anobase != "")
             $resac = db_query("insert into db_acount values($acount,1011134,1015375,'".AddSlashes(pg_result($resaco,$conresaco,'rh302_anobase'))."','$this->rh302_anobase',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ano Abono não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh302_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Ano Abono não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh302_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh302_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh302_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh302_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1015373,'$rh302_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1011134,1015373,'','".AddSlashes(pg_result($resaco,$iresaco,'rh302_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011134,1015484,'','".AddSlashes(pg_result($resaco,$iresaco,'rh302_sequencialprocessocontrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011134,1015375,'','".AddSlashes(pg_result($resaco,$iresaco,'rh302_anobase'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhpessoalprocessoabono
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh302_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh302_sequencial = $rh302_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ano Abono não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh302_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Ano Abono não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh302_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh302_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpessoalprocessoabono";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh302_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhpessoalprocessoabono ";
     $sql .= "      inner join rhpessoalprocessocontrato  on  rhpessoalprocessocontrato.rh273_sequencial = rhpessoalprocessoabono.rh302_sequencialprocessocontrato";
     $sql .= "      inner join rhpessoalprocessoservidor  on  rhpessoalprocessoservidor.rh271_sequencial = rhpessoalprocessocontrato.rh273_sequencialprocessoservidor";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh302_sequencial)) {
         $sql2 .= " where rhpessoalprocessoabono.rh302_sequencial = $rh302_sequencial "; 
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

    public function sql_query_file($rh302_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhpessoalprocessoabono ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh302_sequencial)){
         $sql2 .= " where rhpessoalprocessoabono.rh302_sequencial = $rh302_sequencial "; 
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
