<?php

class cl_rhpessoalprocessovinculo
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
    public $rh274_sequencial = 0; 
    public $rh274_sequencialprocessoservidor = 0; 
    public $rh274_tpregtrab = 0; 
    public $rh274_tpregprev = 0; 
    public $rh274_dtadm_dia = null; 
    public $rh274_dtadm_mes = null; 
    public $rh274_dtadm_ano = null; 
    public $rh274_dtadm = null; 
    public $rh274_tmpparc = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh274_sequencial = int4 = Número Sequencial 
                 rh274_sequencialprocessoservidor = int4 = Servidor Processado 
                 rh274_tpregtrab = int4 = Tipo de regime 
                 rh274_tpregprev = int4 = Tipo de regime previdenciário 
                 rh274_dtadm = date = Data de admissão 
                 rh274_tmpparc = int4 = Tipo de contrato 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhpessoalprocessovinculo"); 
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
       $this->rh274_sequencial = ($this->rh274_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh274_sequencial"]:$this->rh274_sequencial);
       $this->rh274_sequencialprocessoservidor = ($this->rh274_sequencialprocessoservidor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh274_sequencialprocessoservidor"]:$this->rh274_sequencialprocessoservidor);
       $this->rh274_tpregtrab = ($this->rh274_tpregtrab == ""?@$GLOBALS["HTTP_POST_VARS"]["rh274_tpregtrab"]:$this->rh274_tpregtrab);
       $this->rh274_tpregprev = ($this->rh274_tpregprev == ""?@$GLOBALS["HTTP_POST_VARS"]["rh274_tpregprev"]:$this->rh274_tpregprev);
       if($this->rh274_dtadm == ""){
         $this->rh274_dtadm_dia = ($this->rh274_dtadm_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh274_dtadm_dia"]:$this->rh274_dtadm_dia);
         $this->rh274_dtadm_mes = ($this->rh274_dtadm_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh274_dtadm_mes"]:$this->rh274_dtadm_mes);
         $this->rh274_dtadm_ano = ($this->rh274_dtadm_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh274_dtadm_ano"]:$this->rh274_dtadm_ano);
         if($this->rh274_dtadm_dia != ""){
            $this->rh274_dtadm = $this->rh274_dtadm_ano."-".$this->rh274_dtadm_mes."-".$this->rh274_dtadm_dia;
         }
       }
       $this->rh274_tmpparc = ($this->rh274_tmpparc == ""?@$GLOBALS["HTTP_POST_VARS"]["rh274_tmpparc"]:$this->rh274_tmpparc);
     }else{
       $this->rh274_sequencial = ($this->rh274_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh274_sequencial"]:$this->rh274_sequencial);
     }
   }

    public function incluir($rh274_sequencial)
    {
      $this->atualizacampos();
     if($this->rh274_sequencialprocessoservidor == null ){ 
       $this->erro_sql = " Campo Servidor Processado não informado.";
       $this->erro_campo = "rh274_sequencialprocessoservidor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh274_tpregtrab == null ){ 
       $this->rh274_tpregtrab = "0";
     }
     if($this->rh274_tpregprev == null ){ 
       $this->rh274_tpregprev = "0";
     }
     if($this->rh274_dtadm == null ){ 
       $this->rh274_dtadm = "null";
     }
     if($this->rh274_tmpparc == null ){ 
       $this->rh274_tmpparc = "0";
     }
     if($rh274_sequencial == "" || $rh274_sequencial == null ){
       $result = db_query("select nextval('rhpessoalprocessovinculo_rh274_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhpessoalprocessovinculo_rh274_sequencial_seq do campo: rh274_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh274_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhpessoalprocessovinculo_rh274_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh274_sequencial)){
         $this->erro_sql = " Campo rh274_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh274_sequencial = $rh274_sequencial; 
       }
     }
     if(($this->rh274_sequencial == null) || ($this->rh274_sequencial == "") ){ 
       $this->erro_sql = " Campo rh274_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpessoalprocessovinculo(
                                       rh274_sequencial 
                                      ,rh274_sequencialprocessoservidor 
                                      ,rh274_tpregtrab 
                                      ,rh274_tpregprev 
                                      ,rh274_dtadm 
                                      ,rh274_tmpparc 
                       )
                values (
                                $this->rh274_sequencial 
                               ,$this->rh274_sequencialprocessoservidor 
                               ,$this->rh274_tpregtrab 
                               ,$this->rh274_tpregprev 
                               ,".($this->rh274_dtadm == "null" || $this->rh274_dtadm == ""?"null":"'".$this->rh274_dtadm."'")." 
                               ,$this->rh274_tmpparc 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Informações sobre o vínculo trabalhista. ($this->rh274_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Informações sobre o vínculo trabalhista. já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Informações sobre o vínculo trabalhista. ($this->rh274_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh274_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh274_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1014858,'$this->rh274_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1011036,1014858,'','".AddSlashes(pg_result($resaco,0,'rh274_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011036,1014859,'','".AddSlashes(pg_result($resaco,0,'rh274_sequencialprocessoservidor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011036,1014860,'','".AddSlashes(pg_result($resaco,0,'rh274_tpregtrab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011036,1014861,'','".AddSlashes(pg_result($resaco,0,'rh274_tpregprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011036,1014862,'','".AddSlashes(pg_result($resaco,0,'rh274_dtadm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011036,1014863,'','".AddSlashes(pg_result($resaco,0,'rh274_tmpparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh274_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhpessoalprocessovinculo set ";
     $virgula = "";
     if(trim($this->rh274_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh274_sequencial"])){ 
       $sql  .= $virgula." rh274_sequencial = $this->rh274_sequencial ";
       $virgula = ",";
       if(trim($this->rh274_sequencial) == null ){ 
         $this->erro_sql = " Campo Número Sequencial não informado.";
         $this->erro_campo = "rh274_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh274_sequencialprocessoservidor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh274_sequencialprocessoservidor"])){ 
       $sql  .= $virgula." rh274_sequencialprocessoservidor = $this->rh274_sequencialprocessoservidor ";
       $virgula = ",";
       if(trim($this->rh274_sequencialprocessoservidor) == null ){ 
         $this->erro_sql = " Campo Servidor Processado não informado.";
         $this->erro_campo = "rh274_sequencialprocessoservidor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh274_tpregtrab)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh274_tpregtrab"])){ 
        if(trim($this->rh274_tpregtrab)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh274_tpregtrab"])){ 
           $this->rh274_tpregtrab = "0" ; 
        } 
       $sql  .= $virgula." rh274_tpregtrab = $this->rh274_tpregtrab ";
       $virgula = ",";
     }
     if(trim($this->rh274_tpregprev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh274_tpregprev"])){ 
        if(trim($this->rh274_tpregprev)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh274_tpregprev"])){ 
           $this->rh274_tpregprev = "0" ; 
        } 
       $sql  .= $virgula." rh274_tpregprev = $this->rh274_tpregprev ";
       $virgula = ",";
     }
     if(trim($this->rh274_dtadm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh274_dtadm_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh274_dtadm_dia"] !="") ){ 
       $sql  .= $virgula." rh274_dtadm = '$this->rh274_dtadm' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh274_dtadm_dia"])){ 
         $sql  .= $virgula." rh274_dtadm = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh274_tmpparc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh274_tmpparc"])){ 
        if(trim($this->rh274_tmpparc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh274_tmpparc"])){ 
           $this->rh274_tmpparc = "0" ; 
        } 
       $sql  .= $virgula." rh274_tmpparc = $this->rh274_tmpparc ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh274_sequencial!=null){
       $sql .= " rh274_sequencial = $this->rh274_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh274_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1014858,'$this->rh274_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh274_sequencial"]) || $this->rh274_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1011036,1014858,'".AddSlashes(pg_result($resaco,$conresaco,'rh274_sequencial'))."','$this->rh274_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh274_sequencialprocessoservidor"]) || $this->rh274_sequencialprocessoservidor != "")
             $resac = db_query("insert into db_acount values($acount,1011036,1014859,'".AddSlashes(pg_result($resaco,$conresaco,'rh274_sequencialprocessoservidor'))."','$this->rh274_sequencialprocessoservidor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh274_tpregtrab"]) || $this->rh274_tpregtrab != "")
             $resac = db_query("insert into db_acount values($acount,1011036,1014860,'".AddSlashes(pg_result($resaco,$conresaco,'rh274_tpregtrab'))."','$this->rh274_tpregtrab',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh274_tpregprev"]) || $this->rh274_tpregprev != "")
             $resac = db_query("insert into db_acount values($acount,1011036,1014861,'".AddSlashes(pg_result($resaco,$conresaco,'rh274_tpregprev'))."','$this->rh274_tpregprev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh274_dtadm"]) || $this->rh274_dtadm != "")
             $resac = db_query("insert into db_acount values($acount,1011036,1014862,'".AddSlashes(pg_result($resaco,$conresaco,'rh274_dtadm'))."','$this->rh274_dtadm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh274_tmpparc"]) || $this->rh274_tmpparc != "")
             $resac = db_query("insert into db_acount values($acount,1011036,1014863,'".AddSlashes(pg_result($resaco,$conresaco,'rh274_tmpparc'))."','$this->rh274_tmpparc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Informações sobre o vínculo trabalhista. não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh274_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Informações sobre o vínculo trabalhista. não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh274_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh274_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh274_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh274_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1014858,'$rh274_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1011036,1014858,'','".AddSlashes(pg_result($resaco,$iresaco,'rh274_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011036,1014859,'','".AddSlashes(pg_result($resaco,$iresaco,'rh274_sequencialprocessoservidor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011036,1014860,'','".AddSlashes(pg_result($resaco,$iresaco,'rh274_tpregtrab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011036,1014861,'','".AddSlashes(pg_result($resaco,$iresaco,'rh274_tpregprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011036,1014862,'','".AddSlashes(pg_result($resaco,$iresaco,'rh274_dtadm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011036,1014863,'','".AddSlashes(pg_result($resaco,$iresaco,'rh274_tmpparc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhpessoalprocessovinculo
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh274_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh274_sequencial = $rh274_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Informações sobre o vínculo trabalhista. não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh274_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Informações sobre o vínculo trabalhista. não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh274_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh274_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpessoalprocessovinculo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh274_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhpessoalprocessovinculo ";
     $sql .= "      inner join rhpessoalprocessoservidor  on  rhpessoalprocessoservidor.rh271_sequencial = rhpessoalprocessovinculo.rh274_sequencialprocessoservidor";
     $sql .= "      inner join rhpessoalprocessojudicialesocial  on  rhpessoalprocessojudicialesocial.rh270_sequencial = rhpessoalprocessoservidor.rh271_sequencialprocesso";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh274_sequencial)) {
         $sql2 .= " where rhpessoalprocessovinculo.rh274_sequencial = $rh274_sequencial "; 
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

    public function sql_query_file($rh274_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhpessoalprocessovinculo ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh274_sequencial)){
         $sql2 .= " where rhpessoalprocessovinculo.rh274_sequencial = $rh274_sequencial "; 
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
