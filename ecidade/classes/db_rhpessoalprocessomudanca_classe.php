<?php

class cl_rhpessoalprocessomudanca
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
    public $rh280_sequencial = 0; 
    public $rh280_sequencialprocessocontrato = 0; 
    public $rh280_codcateg = 0; 
    public $rh280_natividade = 0; 
    public $rh280_dtmudcategativ_dia = null; 
    public $rh280_dtmudcategativ_mes = null; 
    public $rh280_dtmudcategativ_ano = null; 
    public $rh280_dtmudcategativ = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh280_sequencial = int4 = Número Sequencial 
                 rh280_sequencialprocessocontrato = int4 = Processo contrato 
                 rh280_codcateg = int4 = Código da categoria 
                 rh280_natividade = int4 = Natureza da atividade 
                 rh280_dtmudcategativ = date = Data mudança 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhpessoalprocessomudanca"); 
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
       $this->rh280_sequencial = ($this->rh280_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh280_sequencial"]:$this->rh280_sequencial);
       $this->rh280_sequencialprocessocontrato = ($this->rh280_sequencialprocessocontrato == ""?@$GLOBALS["HTTP_POST_VARS"]["rh280_sequencialprocessocontrato"]:$this->rh280_sequencialprocessocontrato);
       $this->rh280_codcateg = ($this->rh280_codcateg == ""?@$GLOBALS["HTTP_POST_VARS"]["rh280_codcateg"]:$this->rh280_codcateg);
       $this->rh280_natividade = ($this->rh280_natividade == ""?@$GLOBALS["HTTP_POST_VARS"]["rh280_natividade"]:$this->rh280_natividade);
       if($this->rh280_dtmudcategativ == ""){
         $this->rh280_dtmudcategativ_dia = ($this->rh280_dtmudcategativ_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh280_dtmudcategativ_dia"]:$this->rh280_dtmudcategativ_dia);
         $this->rh280_dtmudcategativ_mes = ($this->rh280_dtmudcategativ_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh280_dtmudcategativ_mes"]:$this->rh280_dtmudcategativ_mes);
         $this->rh280_dtmudcategativ_ano = ($this->rh280_dtmudcategativ_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh280_dtmudcategativ_ano"]:$this->rh280_dtmudcategativ_ano);
         if($this->rh280_dtmudcategativ_dia != ""){
            $this->rh280_dtmudcategativ = $this->rh280_dtmudcategativ_ano."-".$this->rh280_dtmudcategativ_mes."-".$this->rh280_dtmudcategativ_dia;
         }
       }
     }else{
       $this->rh280_sequencial = ($this->rh280_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh280_sequencial"]:$this->rh280_sequencial);
     }
   }

    public function incluir($rh280_sequencial)
    {
      $this->atualizacampos();
     if($this->rh280_sequencialprocessocontrato == null ){ 
       $this->erro_sql = " Campo Processo contrato não informado.";
       $this->erro_campo = "rh280_sequencialprocessocontrato";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh280_codcateg == null ){ 
       $this->rh280_codcateg = "0";
     }
     if($this->rh280_natividade == null ){ 
       $this->rh280_natividade = "0";
     }
     if($this->rh280_dtmudcategativ == null ){ 
       $this->rh280_dtmudcategativ = "null";
     }
     if($rh280_sequencial == "" || $rh280_sequencial == null ){
       $result = db_query("select nextval('rhpessoalprocessomudanca_rh280_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhpessoalprocessomudanca_rh280_sequencial_seq do campo: rh280_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh280_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhpessoalprocessomudanca_rh280_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh280_sequencial)){
         $this->erro_sql = " Campo rh280_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh280_sequencial = $rh280_sequencial; 
       }
     }
     if(($this->rh280_sequencial == null) || ($this->rh280_sequencial == "") ){ 
       $this->erro_sql = " Campo rh280_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpessoalprocessomudanca(
                                       rh280_sequencial 
                                      ,rh280_sequencialprocessocontrato 
                                      ,rh280_codcateg 
                                      ,rh280_natividade 
                                      ,rh280_dtmudcategativ 
                       )
                values (
                                $this->rh280_sequencial 
                               ,$this->rh280_sequencialprocessocontrato 
                               ,$this->rh280_codcateg 
                               ,$this->rh280_natividade 
                               ,".($this->rh280_dtmudcategativ == "null" || $this->rh280_dtmudcategativ == ""?"null":"'".$this->rh280_dtmudcategativ."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Informação do novo código de categoria  ($this->rh280_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Informação do novo código de categoria  já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Informação do novo código de categoria  ($this->rh280_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh280_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh280_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1014888,'$this->rh280_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1011047,1014888,'','".AddSlashes(pg_result($resaco,0,'rh280_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011047,1014889,'','".AddSlashes(pg_result($resaco,0,'rh280_sequencialprocessocontrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011047,1014890,'','".AddSlashes(pg_result($resaco,0,'rh280_codcateg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011047,1014891,'','".AddSlashes(pg_result($resaco,0,'rh280_natividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011047,1014892,'','".AddSlashes(pg_result($resaco,0,'rh280_dtmudcategativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh280_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhpessoalprocessomudanca set ";
     $virgula = "";
     if(trim($this->rh280_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh280_sequencial"])){ 
       $sql  .= $virgula." rh280_sequencial = $this->rh280_sequencial ";
       $virgula = ",";
       if(trim($this->rh280_sequencial) == null ){ 
         $this->erro_sql = " Campo Número Sequencial não informado.";
         $this->erro_campo = "rh280_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh280_sequencialprocessocontrato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh280_sequencialprocessocontrato"])){ 
       $sql  .= $virgula." rh280_sequencialprocessocontrato = $this->rh280_sequencialprocessocontrato ";
       $virgula = ",";
       if(trim($this->rh280_sequencialprocessocontrato) == null ){ 
         $this->erro_sql = " Campo Processo contrato não informado.";
         $this->erro_campo = "rh280_sequencialprocessocontrato";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh280_codcateg)!="" || trim($this->rh280_codcateg)=="" || isset($GLOBALS["HTTP_POST_VARS"]["rh280_codcateg"])){ 
        if(trim($this->rh280_codcateg)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh280_codcateg"])){ 
           $this->rh280_codcateg = "0" ; 
        } 
        if(trim($this->rh280_codcateg)==""){ 
          $this->rh280_codcateg = "0" ; 
        } 
       $sql  .= $virgula." rh280_codcateg = $this->rh280_codcateg ";
       $virgula = ",";
     }
     if(trim($this->rh280_natividade)!="" || trim($this->rh280_natividade)=="" || isset($GLOBALS["HTTP_POST_VARS"]["rh280_natividade"])){ 
        if(trim($this->rh280_natividade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh280_natividade"])){ 
           $this->rh280_natividade = "0" ; 
        } 
        if(trim($this->rh280_natividade)==""){ 
          $this->rh280_natividade = "0" ; 
       } 
      $sql  .= $virgula." rh280_natividade = $this->rh280_natividade ";
       $virgula = ",";
     }
     if(trim($this->rh280_dtmudcategativ)!="" || trim($this->rh280_dtmudcategativ)=="" || isset($GLOBALS["HTTP_POST_VARS"]["rh280_dtmudcategativ_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh280_dtmudcategativ_dia"] !="") ){ 
       if (trim($this->rh280_dtmudcategativ)=="") {
         $sql  .= $virgula." rh280_dtmudcategativ = null ";
        } else {
         $sql  .= $virgula." rh280_dtmudcategativ = '$this->rh280_dtmudcategativ' ";
         $virgula = ",";

       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh280_dtmudcategativ_dia"])){ 
         $sql  .= $virgula." rh280_dtmudcategativ = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($rh280_sequencial!=null){
       $sql .= " rh280_sequencial = $this->rh280_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh280_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1014888,'$this->rh280_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh280_sequencial"]) || $this->rh280_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1011047,1014888,'".AddSlashes(pg_result($resaco,$conresaco,'rh280_sequencial'))."','$this->rh280_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh280_sequencialprocessocontrato"]) || $this->rh280_sequencialprocessocontrato != "")
             $resac = db_query("insert into db_acount values($acount,1011047,1014889,'".AddSlashes(pg_result($resaco,$conresaco,'rh280_sequencialprocessocontrato'))."','$this->rh280_sequencialprocessocontrato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh280_codcateg"]) || $this->rh280_codcateg != "")
             $resac = db_query("insert into db_acount values($acount,1011047,1014890,'".AddSlashes(pg_result($resaco,$conresaco,'rh280_codcateg'))."','$this->rh280_codcateg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh280_natividade"]) || $this->rh280_natividade != "")
             $resac = db_query("insert into db_acount values($acount,1011047,1014891,'".AddSlashes(pg_result($resaco,$conresaco,'rh280_natividade'))."','$this->rh280_natividade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh280_dtmudcategativ"]) || $this->rh280_dtmudcategativ != "")
             $resac = db_query("insert into db_acount values($acount,1011047,1014892,'".AddSlashes(pg_result($resaco,$conresaco,'rh280_dtmudcategativ'))."','$this->rh280_dtmudcategativ',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Informação do novo código de categoria  não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh280_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Informação do novo código de categoria  não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh280_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh280_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh280_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh280_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1014888,'$rh280_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1011047,1014888,'','".AddSlashes(pg_result($resaco,$iresaco,'rh280_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011047,1014889,'','".AddSlashes(pg_result($resaco,$iresaco,'rh280_sequencialprocessocontrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011047,1014890,'','".AddSlashes(pg_result($resaco,$iresaco,'rh280_codcateg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011047,1014891,'','".AddSlashes(pg_result($resaco,$iresaco,'rh280_natividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011047,1014892,'','".AddSlashes(pg_result($resaco,$iresaco,'rh280_dtmudcategativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhpessoalprocessomudanca
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh280_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh280_sequencial = $rh280_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Informação do novo código de categoria  não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh280_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Informação do novo código de categoria  não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh280_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh280_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpessoalprocessomudanca";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh280_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhpessoalprocessomudanca ";
     $sql .= "      inner join rhpessoalprocessocontrato  on  rhpessoalprocessocontrato.rh273_sequencial = rhpessoalprocessomudanca.rh280_sequencialprocessocontrato";
     $sql .= "      inner join rhpessoalprocessoservidor  on  rhpessoalprocessoservidor.rh271_sequencial = rhpessoalprocessocontrato.rh273_sequencialprocessoservidor";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh280_sequencial)) {
         $sql2 .= " where rhpessoalprocessomudanca.rh280_sequencial = $rh280_sequencial "; 
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

    public function sql_query_file($rh280_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhpessoalprocessomudanca ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh280_sequencial)){
         $sql2 .= " where rhpessoalprocessomudanca.rh280_sequencial = $rh280_sequencial "; 
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
