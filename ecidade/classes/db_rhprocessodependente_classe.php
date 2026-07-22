<?php

class cl_rhprocessodependente
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
    public $rh304_sequencial = 0; 
    public $rh304_sequencialtributoirrf = 0; 
    public $rh304_tprend = 0; 
    public $rh304_cpfdep = null; 
    public $rh304_vlrdeducao = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh304_sequencial = int4 = Sequencial 
                 rh304_sequencialtributoirrf = int4 = Sequencial vinculo tributo 
                 rh304_tprend = int4 = Tipo de rendimento 
                 rh304_cpfdep = varchar(11) = CPF 
                 rh304_vlrdeducao = float4 = Valor da dedução 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhprocessodependente"); 
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
       $this->rh304_sequencial = ($this->rh304_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh304_sequencial"]:$this->rh304_sequencial);
       $this->rh304_sequencialtributoirrf = ($this->rh304_sequencialtributoirrf == ""?@$GLOBALS["HTTP_POST_VARS"]["rh304_sequencialtributoirrf"]:$this->rh304_sequencialtributoirrf);
       $this->rh304_tprend = ($this->rh304_tprend == ""?@$GLOBALS["HTTP_POST_VARS"]["rh304_tprend"]:$this->rh304_tprend);
       $this->rh304_cpfdep = ($this->rh304_cpfdep == ""?@$GLOBALS["HTTP_POST_VARS"]["rh304_cpfdep"]:$this->rh304_cpfdep);
       $this->rh304_vlrdeducao = ($this->rh304_vlrdeducao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh304_vlrdeducao"]:$this->rh304_vlrdeducao);
     }else{
       $this->rh304_sequencial = ($this->rh304_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh304_sequencial"]:$this->rh304_sequencial);
     }
   }

    public function incluir($rh304_sequencial)
    {
      $this->atualizacampos();
     if($this->rh304_sequencialtributoirrf == null ){ 
       $this->erro_sql = " Campo Sequencial vinculo tributo não informado.";
       $this->erro_campo = "rh304_sequencialtributoirrf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh304_tprend == null ){ 
       $this->rh304_tprend = "0";
     }
     if($this->rh304_vlrdeducao == null ){ 
       $this->rh304_vlrdeducao = "0";
     }
     if($rh304_sequencial == "" || $rh304_sequencial == null ){
       $result = db_query("select nextval('rhprocessodependente_rh304_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhprocessodependente_rh304_sequencial_seq do campo: rh304_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh304_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhprocessodependente_rh304_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh304_sequencial)){
         $this->erro_sql = " Campo rh304_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh304_sequencial = $rh304_sequencial; 
       }
     }
     if(($this->rh304_sequencial == null) || ($this->rh304_sequencial == "") ){ 
       $this->erro_sql = " Campo rh304_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhprocessodependente(
                                       rh304_sequencial 
                                      ,rh304_sequencialtributoirrf 
                                      ,rh304_tprend 
                                      ,rh304_cpfdep 
                                      ,rh304_vlrdeducao 
                       )
                values (
                                $this->rh304_sequencial 
                               ,$this->rh304_sequencialtributoirrf 
                               ,$this->rh304_tprend 
                               ,'$this->rh304_cpfdep' 
                               ,$this->rh304_vlrdeducao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tributável dependentes ($this->rh304_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tributável dependentes já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tributável dependentes ($this->rh304_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh304_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh304_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1015417,'$this->rh304_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1011138,1015417,'','".AddSlashes(pg_result($resaco,0,'rh304_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011138,1015418,'','".AddSlashes(pg_result($resaco,0,'rh304_sequencialtributoirrf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011138,1015419,'','".AddSlashes(pg_result($resaco,0,'rh304_tprend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011138,1015420,'','".AddSlashes(pg_result($resaco,0,'rh304_cpfdep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011138,1015421,'','".AddSlashes(pg_result($resaco,0,'rh304_vlrdeducao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh304_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhprocessodependente set ";
     $virgula = "";
     if(trim($this->rh304_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh304_sequencial"])){ 
       $sql  .= $virgula." rh304_sequencial = $this->rh304_sequencial ";
       $virgula = ",";
       if(trim($this->rh304_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh304_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh304_sequencialtributoirrf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh304_sequencialtributoirrf"])){ 
       $sql  .= $virgula." rh304_sequencialtributoirrf = $this->rh304_sequencialtributoirrf ";
       $virgula = ",";
       if(trim($this->rh304_sequencialtributoirrf) == null ){ 
         $this->erro_sql = " Campo Sequencial vinculo tributo não informado.";
         $this->erro_campo = "rh304_sequencialtributoirrf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh304_tprend)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh304_tprend"])){ 
        if(trim($this->rh304_tprend)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh304_tprend"])){ 
           $this->rh304_tprend = "0" ; 
        } 
       $sql  .= $virgula." rh304_tprend = $this->rh304_tprend ";
       $virgula = ",";
     }
     if(trim($this->rh304_cpfdep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh304_cpfdep"])){ 
       $sql  .= $virgula." rh304_cpfdep = '$this->rh304_cpfdep' ";
       $virgula = ",";
     }
     if(trim($this->rh304_vlrdeducao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh304_vlrdeducao"])){ 
        if(trim($this->rh304_vlrdeducao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh304_vlrdeducao"])){ 
           $this->rh304_vlrdeducao = "0" ; 
        } 
       $sql  .= $virgula." rh304_vlrdeducao = $this->rh304_vlrdeducao ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh304_sequencial!=null){
       $sql .= " rh304_sequencial = $this->rh304_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh304_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1015417,'$this->rh304_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh304_sequencial"]) || $this->rh304_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1011138,1015417,'".AddSlashes(pg_result($resaco,$conresaco,'rh304_sequencial'))."','$this->rh304_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh304_sequencialtributoirrf"]) || $this->rh304_sequencialtributoirrf != "")
             $resac = db_query("insert into db_acount values($acount,1011138,1015418,'".AddSlashes(pg_result($resaco,$conresaco,'rh304_sequencialtributoirrf'))."','$this->rh304_sequencialtributoirrf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh304_tprend"]) || $this->rh304_tprend != "")
             $resac = db_query("insert into db_acount values($acount,1011138,1015419,'".AddSlashes(pg_result($resaco,$conresaco,'rh304_tprend'))."','$this->rh304_tprend',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh304_cpfdep"]) || $this->rh304_cpfdep != "")
             $resac = db_query("insert into db_acount values($acount,1011138,1015420,'".AddSlashes(pg_result($resaco,$conresaco,'rh304_cpfdep'))."','$this->rh304_cpfdep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh304_vlrdeducao"]) || $this->rh304_vlrdeducao != "")
             $resac = db_query("insert into db_acount values($acount,1011138,1015421,'".AddSlashes(pg_result($resaco,$conresaco,'rh304_vlrdeducao'))."','$this->rh304_vlrdeducao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tributável dependentes não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh304_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tributável dependentes não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh304_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh304_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh304_sequencial=null, $dbwhere = null)
    {
     $sql = " delete from rhprocessodependente
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh304_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh304_sequencial = $rh304_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tributável dependentes não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh304_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tributável dependentes não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh304_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh304_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhprocessodependente";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh304_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhprocessodependente ";
     $sql .= "      inner join rhprocessotributoirrf  on  rhprocessotributoirrf.rh299_sequencial = rhprocessodependente.rh304_sequencialtributoirrf";
     $sql .= "      inner join rhpessoalprocessoservidor  on  rhpessoalprocessoservidor.rh271_sequencial = rhprocessotributoirrf.rh299_sequencialprocessoservidor";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh304_sequencial)) {
         $sql2 .= " where rhprocessodependente.rh304_sequencial = $rh304_sequencial "; 
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

    public function sql_query_file($rh304_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhprocessodependente ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh304_sequencial)){
         $sql2 .= " where rhprocessodependente.rh304_sequencial = $rh304_sequencial "; 
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
