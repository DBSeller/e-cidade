<?php

class cl_rhprocessoretencao
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
    public $rh306_sequencial = 0; 
    public $rh306_sequencialtributoirrf = 0; 
    public $rh306_tpprocret = 0; 
    public $rh306_nrprocret = null; 
    public $rh306_codsusp = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh306_sequencial = int4 = Sequencial 
                 rh306_sequencialtributoirrf = int4 = Sequencial vinculo tributo 
                 rh306_tpprocret = int4 = Tipo de processo 
                 rh306_nrprocret = varchar(21) = Número do processo 
                 rh306_codsusp = varchar(14) = Indicativo da suspensão 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhprocessoretencao"); 
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
       $this->rh306_sequencial = ($this->rh306_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh306_sequencial"]:$this->rh306_sequencial);
       $this->rh306_sequencialtributoirrf = ($this->rh306_sequencialtributoirrf == ""?@$GLOBALS["HTTP_POST_VARS"]["rh306_sequencialtributoirrf"]:$this->rh306_sequencialtributoirrf);
       $this->rh306_tpprocret = ($this->rh306_tpprocret == ""?@$GLOBALS["HTTP_POST_VARS"]["rh306_tpprocret"]:$this->rh306_tpprocret);
       $this->rh306_nrprocret = ($this->rh306_nrprocret == ""?@$GLOBALS["HTTP_POST_VARS"]["rh306_nrprocret"]:$this->rh306_nrprocret);
       $this->rh306_codsusp = ($this->rh306_codsusp == ""?@$GLOBALS["HTTP_POST_VARS"]["rh306_codsusp"]:$this->rh306_codsusp);
     }else{
       $this->rh306_sequencial = ($this->rh306_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh306_sequencial"]:$this->rh306_sequencial);
     }
   }

    public function incluir($rh306_sequencial)
    {
      $this->atualizacampos();
     if($this->rh306_sequencialtributoirrf == null ){ 
       $this->erro_sql = " Campo Sequencial vinculo tributo não informado.";
       $this->erro_campo = "rh306_sequencialtributoirrf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh306_tpprocret == null ){ 
       $this->rh306_tpprocret = "0";
     }
     if($rh306_sequencial == "" || $rh306_sequencial == null ){
       $result = db_query("select nextval('rhprocessoretencao_rh306_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhprocessoretencao_rh306_sequencial_seq do campo: rh306_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh306_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhprocessoretencao_rh306_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh306_sequencial)){
         $this->erro_sql = " Campo rh306_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh306_sequencial = $rh306_sequencial; 
       }
     }
     if(($this->rh306_sequencial == null) || ($this->rh306_sequencial == "") ){ 
       $this->erro_sql = " Campo rh306_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhprocessoretencao(
                                       rh306_sequencial 
                                      ,rh306_sequencialtributoirrf 
                                      ,rh306_tpprocret 
                                      ,rh306_nrprocret 
                                      ,rh306_codsusp 
                       )
                values (
                                $this->rh306_sequencial 
                               ,$this->rh306_sequencialtributoirrf 
                               ,$this->rh306_tpprocret 
                               ,'$this->rh306_nrprocret' 
                               ,'$this->rh306_codsusp' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Retenção de tributos ($this->rh306_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Retenção de tributos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Retenção de tributos ($this->rh306_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh306_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh306_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1015427,'$this->rh306_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1011141,1015427,'','".AddSlashes(pg_result($resaco,0,'rh306_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011141,1015428,'','".AddSlashes(pg_result($resaco,0,'rh306_sequencialtributoirrf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011141,1015429,'','".AddSlashes(pg_result($resaco,0,'rh306_tpprocret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011141,1015430,'','".AddSlashes(pg_result($resaco,0,'rh306_nrprocret'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011141,1015431,'','".AddSlashes(pg_result($resaco,0,'rh306_codsusp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh306_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhprocessoretencao set ";
     $virgula = "";
     if(trim($this->rh306_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh306_sequencial"])){ 
       $sql  .= $virgula." rh306_sequencial = $this->rh306_sequencial ";
       $virgula = ",";
       if(trim($this->rh306_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh306_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh306_sequencialtributoirrf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh306_sequencialtributoirrf"])){ 
       $sql  .= $virgula." rh306_sequencialtributoirrf = $this->rh306_sequencialtributoirrf ";
       $virgula = ",";
       if(trim($this->rh306_sequencialtributoirrf) == null ){ 
         $this->erro_sql = " Campo Sequencial vinculo tributo não informado.";
         $this->erro_campo = "rh306_sequencialtributoirrf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh306_tpprocret)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh306_tpprocret"])){ 
        if(trim($this->rh306_tpprocret)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh306_tpprocret"])){ 
           $this->rh306_tpprocret = "0" ; 
        } 
       $sql  .= $virgula." rh306_tpprocret = $this->rh306_tpprocret ";
       $virgula = ",";
     }
     if(trim($this->rh306_nrprocret)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh306_nrprocret"])){ 
       $sql  .= $virgula." rh306_nrprocret = '$this->rh306_nrprocret' ";
       $virgula = ",";
     }
     if(trim($this->rh306_codsusp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh306_codsusp"])){ 
       $sql  .= $virgula." rh306_codsusp = '$this->rh306_codsusp' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh306_sequencial!=null){
       $sql .= " rh306_sequencial = $this->rh306_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh306_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1015427,'$this->rh306_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh306_sequencial"]) || $this->rh306_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1011141,1015427,'".AddSlashes(pg_result($resaco,$conresaco,'rh306_sequencial'))."','$this->rh306_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh306_sequencialtributoirrf"]) || $this->rh306_sequencialtributoirrf != "")
             $resac = db_query("insert into db_acount values($acount,1011141,1015428,'".AddSlashes(pg_result($resaco,$conresaco,'rh306_sequencialtributoirrf'))."','$this->rh306_sequencialtributoirrf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh306_tpprocret"]) || $this->rh306_tpprocret != "")
             $resac = db_query("insert into db_acount values($acount,1011141,1015429,'".AddSlashes(pg_result($resaco,$conresaco,'rh306_tpprocret'))."','$this->rh306_tpprocret',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh306_nrprocret"]) || $this->rh306_nrprocret != "")
             $resac = db_query("insert into db_acount values($acount,1011141,1015430,'".AddSlashes(pg_result($resaco,$conresaco,'rh306_nrprocret'))."','$this->rh306_nrprocret',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh306_codsusp"]) || $this->rh306_codsusp != "")
             $resac = db_query("insert into db_acount values($acount,1011141,1015431,'".AddSlashes(pg_result($resaco,$conresaco,'rh306_codsusp'))."','$this->rh306_codsusp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Retenção de tributos não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh306_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Retenção de tributos não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh306_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh306_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh306_sequencial=null, $dbwhere = null)
    {
     $sql = " delete from rhprocessoretencao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh306_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh306_sequencial = $rh306_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Retenção de tributos não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh306_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Retenção de tributos não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh306_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh306_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhprocessoretencao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh306_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhprocessoretencao ";
     $sql .= "      inner join rhprocessotributoirrf  on  rhprocessotributoirrf.rh299_sequencial = rhprocessoretencao.rh306_sequencialtributoirrf";
     $sql .= "      inner join rhpessoalprocessoservidor  on  rhpessoalprocessoservidor.rh271_sequencial = rhprocessotributoirrf.rh299_sequencialprocessoservidor";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh306_sequencial)) {
         $sql2 .= " where rhprocessoretencao.rh306_sequencial = $rh306_sequencial "; 
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

    public function sql_query_file($rh306_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhprocessoretencao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh306_sequencial)){
         $sql2 .= " where rhprocessoretencao.rh306_sequencial = $rh306_sequencial "; 
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
