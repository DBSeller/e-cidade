<?php

class cl_rhpessoalprocessotermtsve
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
    public $rh275_sequencial = 0; 
    public $rh275_sequencialprocessoservidor = 0; 
    public $rh275_dtterm_dia = null; 
    public $rh275_dtterm_mes = null; 
    public $rh275_dtterm_ano = null; 
    public $rh275_dtterm = null; 
    public $rh275_mtvdesligtsv = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh275_sequencial = int4 = Número Sequencial 
                 rh275_sequencialprocessoservidor = int4 = Servidor Processado 
                 rh275_dtterm = date = Data do término 
                 rh275_mtvdesligtsv = varchar(2) = Motivo do término 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhpessoalprocessotermtsve"); 
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
       $this->rh275_sequencial = ($this->rh275_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh275_sequencial"]:$this->rh275_sequencial);
       $this->rh275_sequencialprocessoservidor = ($this->rh275_sequencialprocessoservidor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh275_sequencialprocessoservidor"]:$this->rh275_sequencialprocessoservidor);
       if($this->rh275_dtterm == ""){
         $this->rh275_dtterm_dia = ($this->rh275_dtterm_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh275_dtterm_dia"]:$this->rh275_dtterm_dia);
         $this->rh275_dtterm_mes = ($this->rh275_dtterm_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh275_dtterm_mes"]:$this->rh275_dtterm_mes);
         $this->rh275_dtterm_ano = ($this->rh275_dtterm_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh275_dtterm_ano"]:$this->rh275_dtterm_ano);
         if($this->rh275_dtterm_dia != ""){
            $this->rh275_dtterm = $this->rh275_dtterm_ano."-".$this->rh275_dtterm_mes."-".$this->rh275_dtterm_dia;
         }
       }
       $this->rh275_mtvdesligtsv = ($this->rh275_mtvdesligtsv == ""?@$GLOBALS["HTTP_POST_VARS"]["rh275_mtvdesligtsv"]:$this->rh275_mtvdesligtsv);
     }else{
       $this->rh275_sequencial = ($this->rh275_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh275_sequencial"]:$this->rh275_sequencial);
     }
   }

    public function incluir($rh275_sequencial)
    {
      $this->atualizacampos();
     if($this->rh275_sequencialprocessoservidor == null ){ 
       $this->erro_sql = " Campo Servidor Processado não informado.";
       $this->erro_campo = "rh275_sequencialprocessoservidor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh275_dtterm == null ){ 
       $this->rh275_dtterm = "null";
     }
       $this->rh275_sequencial = $rh275_sequencial; 
     if(($this->rh275_sequencial == null) || ($this->rh275_sequencial == "") ){ 
       $this->erro_sql = " Campo rh275_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpessoalprocessotermtsve(
                                       rh275_sequencial 
                                      ,rh275_sequencialprocessoservidor 
                                      ,rh275_dtterm 
                                      ,rh275_mtvdesligtsv 
                       )
                values (
                                $this->rh275_sequencial 
                               ,$this->rh275_sequencialprocessoservidor 
                               ,".($this->rh275_dtterm == "null" || $this->rh275_dtterm == ""?"null":"'".$this->rh275_dtterm."'")." 
                               ,'$this->rh275_mtvdesligtsv' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Informações de término de TSVE ($this->rh275_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Informações de término de TSVE já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Informações de término de TSVE ($this->rh275_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh275_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh275_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1014864,'$this->rh275_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1011038,1014864,'','".AddSlashes(pg_result($resaco,0,'rh275_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011038,1014865,'','".AddSlashes(pg_result($resaco,0,'rh275_sequencialprocessoservidor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011038,1014866,'','".AddSlashes(pg_result($resaco,0,'rh275_dtterm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011038,1014867,'','".AddSlashes(pg_result($resaco,0,'rh275_mtvdesligtsv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh275_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhpessoalprocessotermtsve set ";
     $virgula = "";
     if(trim($this->rh275_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh275_sequencial"])){ 
       $sql  .= $virgula." rh275_sequencial = $this->rh275_sequencial ";
       $virgula = ",";
       if(trim($this->rh275_sequencial) == null ){ 
         $this->erro_sql = " Campo Número Sequencial não informado.";
         $this->erro_campo = "rh275_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh275_sequencialprocessoservidor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh275_sequencialprocessoservidor"])){ 
       $sql  .= $virgula." rh275_sequencialprocessoservidor = $this->rh275_sequencialprocessoservidor ";
       $virgula = ",";
       if(trim($this->rh275_sequencialprocessoservidor) == null ){ 
         $this->erro_sql = " Campo Servidor Processado não informado.";
         $this->erro_campo = "rh275_sequencialprocessoservidor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh275_dtterm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh275_dtterm_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh275_dtterm_dia"] !="") ){ 
       $sql  .= $virgula." rh275_dtterm = '$this->rh275_dtterm' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh275_dtterm_dia"])){ 
         $sql  .= $virgula." rh275_dtterm = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh275_mtvdesligtsv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh275_mtvdesligtsv"])){ 
       $sql  .= $virgula." rh275_mtvdesligtsv = '$this->rh275_mtvdesligtsv' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh275_sequencial!=null){
       $sql .= " rh275_sequencial = $this->rh275_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh275_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1014864,'$this->rh275_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh275_sequencial"]) || $this->rh275_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1011038,1014864,'".AddSlashes(pg_result($resaco,$conresaco,'rh275_sequencial'))."','$this->rh275_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh275_sequencialprocessoservidor"]) || $this->rh275_sequencialprocessoservidor != "")
             $resac = db_query("insert into db_acount values($acount,1011038,1014865,'".AddSlashes(pg_result($resaco,$conresaco,'rh275_sequencialprocessoservidor'))."','$this->rh275_sequencialprocessoservidor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh275_dtterm"]) || $this->rh275_dtterm != "")
             $resac = db_query("insert into db_acount values($acount,1011038,1014866,'".AddSlashes(pg_result($resaco,$conresaco,'rh275_dtterm'))."','$this->rh275_dtterm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh275_mtvdesligtsv"]) || $this->rh275_mtvdesligtsv != "")
             $resac = db_query("insert into db_acount values($acount,1011038,1014867,'".AddSlashes(pg_result($resaco,$conresaco,'rh275_mtvdesligtsv'))."','$this->rh275_mtvdesligtsv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Informações de término de TSVE não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh275_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Informações de término de TSVE não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh275_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh275_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh275_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh275_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1014864,'$rh275_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1011038,1014864,'','".AddSlashes(pg_result($resaco,$iresaco,'rh275_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011038,1014865,'','".AddSlashes(pg_result($resaco,$iresaco,'rh275_sequencialprocessoservidor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011038,1014866,'','".AddSlashes(pg_result($resaco,$iresaco,'rh275_dtterm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011038,1014867,'','".AddSlashes(pg_result($resaco,$iresaco,'rh275_mtvdesligtsv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhpessoalprocessotermtsve
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh275_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh275_sequencial = $rh275_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Informações de término de TSVE não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh275_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Informações de término de TSVE não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh275_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh275_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpessoalprocessotermtsve";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh275_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhpessoalprocessotermtsve ";
     $sql .= "      inner join rhpessoalprocessoservidor  on  rhpessoalprocessoservidor.rh271_sequencial = rhpessoalprocessotermtsve.rh275_sequencialprocessoservidor";
     $sql .= "      inner join rhpessoalprocessojudicialesocial  on  rhpessoalprocessojudicialesocial.rh270_sequencial = rhpessoalprocessoservidor.rh271_sequencialprocesso";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh275_sequencial)) {
         $sql2 .= " where rhpessoalprocessotermtsve.rh275_sequencial = $rh275_sequencial "; 
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

    public function sql_query_file($rh275_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhpessoalprocessotermtsve ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh275_sequencial)){
         $sql2 .= " where rhpessoalprocessotermtsve.rh275_sequencial = $rh275_sequencial "; 
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
