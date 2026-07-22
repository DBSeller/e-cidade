<?php

class cl_rhpessoalprocessounicidade
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
    public $rh281_sequencial = 0; 
    public $rh281_sequencialprocessocontrato = 0; 
    public $rh281_matunic = null; 
    public $rh281_codcateg = 0; 
    public $rh281_dtinicio_dia = null; 
    public $rh281_dtinicio_mes = null; 
    public $rh281_dtinicio_ano = null; 
    public $rh281_dtinicio = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh281_sequencial = int4 = Número Sequencial 
                 rh281_sequencialprocessocontrato = int4 = Processo Contrato 
                 rh281_matunic = varchar(30) = Matricula 
                 rh281_codcateg = int4 = Código da categoria 
                 rh281_dtinicio = date = Data de início 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhpessoalprocessounicidade"); 
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
       $this->rh281_sequencial = ($this->rh281_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh281_sequencial"]:$this->rh281_sequencial);
       $this->rh281_sequencialprocessocontrato = ($this->rh281_sequencialprocessocontrato == ""?@$GLOBALS["HTTP_POST_VARS"]["rh281_sequencialprocessocontrato"]:$this->rh281_sequencialprocessocontrato);
       $this->rh281_matunic = ($this->rh281_matunic == ""?@$GLOBALS["HTTP_POST_VARS"]["rh281_matunic"]:$this->rh281_matunic);
       $this->rh281_codcateg = ($this->rh281_codcateg == ""?@$GLOBALS["HTTP_POST_VARS"]["rh281_codcateg"]:$this->rh281_codcateg);
       if($this->rh281_dtinicio == ""){
         $this->rh281_dtinicio_dia = ($this->rh281_dtinicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh281_dtinicio_dia"]:$this->rh281_dtinicio_dia);
         $this->rh281_dtinicio_mes = ($this->rh281_dtinicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh281_dtinicio_mes"]:$this->rh281_dtinicio_mes);
         $this->rh281_dtinicio_ano = ($this->rh281_dtinicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh281_dtinicio_ano"]:$this->rh281_dtinicio_ano);
         if($this->rh281_dtinicio_dia != ""){
            $this->rh281_dtinicio = $this->rh281_dtinicio_ano."-".$this->rh281_dtinicio_mes."-".$this->rh281_dtinicio_dia;
         }
       }
     }else{
       $this->rh281_sequencial = ($this->rh281_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh281_sequencial"]:$this->rh281_sequencial);
     }
   }

    public function incluir($rh281_sequencial)
    {
      $this->atualizacampos();
     if($this->rh281_sequencialprocessocontrato == null ){ 
       $this->erro_sql = " Campo Processo Contrato não informado.";
       $this->erro_campo = "rh281_sequencialprocessocontrato";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh281_codcateg == null ){ 
       $this->rh281_codcateg = "0";
     }
     if($this->rh281_dtinicio == null ){ 
       $this->rh281_dtinicio = "null";
     }
     if($rh281_sequencial == "" || $rh281_sequencial == null ){
       $result = db_query("select nextval('rhpessoalprocessounicidade_rh281_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhpessoalprocessounicidade_rh281_sequencial_seq do campo: rh281_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh281_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhpessoalprocessounicidade_rh281_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh281_sequencial)){
         $this->erro_sql = " Campo rh281_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh281_sequencial = $rh281_sequencial; 
       }
     }
     if(($this->rh281_sequencial == null) || ($this->rh281_sequencial == "") ){ 
       $this->erro_sql = " Campo rh281_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpessoalprocessounicidade(
                                       rh281_sequencial 
                                      ,rh281_sequencialprocessocontrato 
                                      ,rh281_matunic 
                                      ,rh281_codcateg 
                                      ,rh281_dtinicio 
                       )
                values (
                                $this->rh281_sequencial 
                               ,$this->rh281_sequencialprocessocontrato 
                               ,'$this->rh281_matunic' 
                               ,$this->rh281_codcateg 
                               ,".($this->rh281_dtinicio == "null" || $this->rh281_dtinicio == ""?"null":"'".$this->rh281_dtinicio."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "unicidade contratual ($this->rh281_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "unicidade contratual já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "unicidade contratual ($this->rh281_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh281_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh281_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1014893,'$this->rh281_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1011048,1014893,'','".AddSlashes(pg_result($resaco,0,'rh281_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011048,1014894,'','".AddSlashes(pg_result($resaco,0,'rh281_sequencialprocessocontrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011048,1014895,'','".AddSlashes(pg_result($resaco,0,'rh281_matunic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011048,1014896,'','".AddSlashes(pg_result($resaco,0,'rh281_codcateg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011048,1014897,'','".AddSlashes(pg_result($resaco,0,'rh281_dtinicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh281_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhpessoalprocessounicidade set ";
     $virgula = "";
     if(trim($this->rh281_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh281_sequencial"])){ 
       $sql  .= $virgula." rh281_sequencial = $this->rh281_sequencial ";
       $virgula = ",";
       if(trim($this->rh281_sequencial) == null ){ 
         $this->erro_sql = " Campo Número Sequencial não informado.";
         $this->erro_campo = "rh281_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh281_sequencialprocessocontrato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh281_sequencialprocessocontrato"])){ 
       $sql  .= $virgula." rh281_sequencialprocessocontrato = $this->rh281_sequencialprocessocontrato ";
       $virgula = ",";
       if(trim($this->rh281_sequencialprocessocontrato) == null ){ 
         $this->erro_sql = " Campo Processo Contrato não informado.";
         $this->erro_campo = "rh281_sequencialprocessocontrato";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh281_matunic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh281_matunic"])){ 
       $sql  .= $virgula." rh281_matunic = '$this->rh281_matunic' ";
       $virgula = ",";
     }
     if(trim($this->rh281_codcateg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh281_codcateg"])){ 
        if(trim($this->rh281_codcateg)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh281_codcateg"])){ 
           $this->rh281_codcateg = "0" ; 
        } 
       $sql  .= $virgula." rh281_codcateg = $this->rh281_codcateg ";
       $virgula = ",";
     }
     if(trim($this->rh281_dtinicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh281_dtinicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh281_dtinicio_dia"] !="") ){ 
       $sql  .= $virgula." rh281_dtinicio = '$this->rh281_dtinicio' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh281_dtinicio_dia"])){ 
         $sql  .= $virgula." rh281_dtinicio = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($rh281_sequencial!=null){
       $sql .= " rh281_sequencial = $this->rh281_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh281_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1014893,'$this->rh281_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh281_sequencial"]) || $this->rh281_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1011048,1014893,'".AddSlashes(pg_result($resaco,$conresaco,'rh281_sequencial'))."','$this->rh281_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh281_sequencialprocessocontrato"]) || $this->rh281_sequencialprocessocontrato != "")
             $resac = db_query("insert into db_acount values($acount,1011048,1014894,'".AddSlashes(pg_result($resaco,$conresaco,'rh281_sequencialprocessocontrato'))."','$this->rh281_sequencialprocessocontrato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh281_matunic"]) || $this->rh281_matunic != "")
             $resac = db_query("insert into db_acount values($acount,1011048,1014895,'".AddSlashes(pg_result($resaco,$conresaco,'rh281_matunic'))."','$this->rh281_matunic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh281_codcateg"]) || $this->rh281_codcateg != "")
             $resac = db_query("insert into db_acount values($acount,1011048,1014896,'".AddSlashes(pg_result($resaco,$conresaco,'rh281_codcateg'))."','$this->rh281_codcateg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh281_dtinicio"]) || $this->rh281_dtinicio != "")
             $resac = db_query("insert into db_acount values($acount,1011048,1014897,'".AddSlashes(pg_result($resaco,$conresaco,'rh281_dtinicio'))."','$this->rh281_dtinicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "unicidade contratual não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh281_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "unicidade contratual não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh281_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh281_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh281_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh281_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1014893,'$rh281_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1011048,1014893,'','".AddSlashes(pg_result($resaco,$iresaco,'rh281_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011048,1014894,'','".AddSlashes(pg_result($resaco,$iresaco,'rh281_sequencialprocessocontrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011048,1014895,'','".AddSlashes(pg_result($resaco,$iresaco,'rh281_matunic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011048,1014896,'','".AddSlashes(pg_result($resaco,$iresaco,'rh281_codcateg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011048,1014897,'','".AddSlashes(pg_result($resaco,$iresaco,'rh281_dtinicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhpessoalprocessounicidade
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh281_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh281_sequencial = $rh281_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "unicidade contratual não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh281_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "unicidade contratual não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh281_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh281_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpessoalprocessounicidade";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh281_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhpessoalprocessounicidade ";
     $sql .= "      inner join rhpessoalprocessocontrato  on  rhpessoalprocessocontrato.rh273_sequencial = rhpessoalprocessounicidade.rh281_sequencialprocessocontrato";
     $sql .= "      inner join rhpessoalprocessoservidor  on  rhpessoalprocessoservidor.rh271_sequencial = rhpessoalprocessocontrato.rh273_sequencialprocessoservidor";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh281_sequencial)) {
         $sql2 .= " where rhpessoalprocessounicidade.rh281_sequencial = $rh281_sequencial "; 
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

    public function sql_query_file($rh281_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhpessoalprocessounicidade ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh281_sequencial)){
         $sql2 .= " where rhpessoalprocessounicidade.rh281_sequencial = $rh281_sequencial "; 
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
