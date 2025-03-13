<?php

class cl_rhpessoalprocessoremuneracao
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
    public $rh272_sequencial = 0; 
    public $rh272_sequencialprocessocontrato = 0; 
    public $rh272_dtremun_dia = null; 
    public $rh272_dtremun_mes = null; 
    public $rh272_dtremun_ano = null; 
    public $rh272_dtremun = null; 
    public $rh272_vrsalfx = 0; 
    public $rh272_undSalFixo = 0; 
    public $rh272_dscSalVar = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh272_sequencial = int4 = Número Sequencial 
                 rh272_sequencialprocessocontrato = int4 = Sequencial contrato 
                 rh272_dtremun = date = Data Remuneração 
                 rh272_vrsalfx = float4 = Salário base 
                 rh272_undSalFixo = int4 = Unidade de pagamento 
                 rh272_dscSalVar = text = Descrição 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhpessoalprocessoremuneracao"); 
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
       $this->rh272_sequencial = ($this->rh272_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh272_sequencial"]:$this->rh272_sequencial);
       $this->rh272_sequencialprocessocontrato = ($this->rh272_sequencialprocessocontrato == ""?@$GLOBALS["HTTP_POST_VARS"]["rh272_sequencialprocessocontrato"]:$this->rh272_sequencialprocessocontrato);
       if($this->rh272_dtremun == ""){
         $this->rh272_dtremun_dia = ($this->rh272_dtremun_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh272_dtremun_dia"]:$this->rh272_dtremun_dia);
         $this->rh272_dtremun_mes = ($this->rh272_dtremun_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh272_dtremun_mes"]:$this->rh272_dtremun_mes);
         $this->rh272_dtremun_ano = ($this->rh272_dtremun_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh272_dtremun_ano"]:$this->rh272_dtremun_ano);
         if($this->rh272_dtremun_dia != ""){
            $this->rh272_dtremun = $this->rh272_dtremun_ano."-".$this->rh272_dtremun_mes."-".$this->rh272_dtremun_dia;
         }
       }
       $this->rh272_vrsalfx = ($this->rh272_vrsalfx == ""?@$GLOBALS["HTTP_POST_VARS"]["rh272_vrsalfx"]:$this->rh272_vrsalfx);
       $this->rh272_undSalFixo = ($this->rh272_undSalFixo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh272_undSalFixo"]:$this->rh272_undSalFixo);
       $this->rh272_dscSalVar = ($this->rh272_dscSalVar == ""?@$GLOBALS["HTTP_POST_VARS"]["rh272_dscSalVar"]:$this->rh272_dscSalVar);
     }else{
       $this->rh272_sequencial = ($this->rh272_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh272_sequencial"]:$this->rh272_sequencial);
     }
   }

    public function incluir($rh272_sequencial)
    {
      $this->atualizacampos();
     if($this->rh272_sequencialprocessocontrato == null ){ 
       $this->erro_sql = " Campo Sequencial contrato não informado.";
       $this->erro_campo = "rh272_sequencialprocessocontrato";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh272_dtremun == null ){ 
       $this->erro_sql = " Campo Data Remuneração não informado.";
       $this->erro_campo = "rh272_dtremun_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh272_vrsalfx == null ){ 
       $this->erro_sql = " Campo Salário base não informado.";
       $this->erro_campo = "rh272_vrsalfx";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh272_undSalFixo == null ){ 
       $this->rh272_undSalFixo = "0";
     }
     if($rh272_sequencial == "" || $rh272_sequencial == null ){
       $result = db_query("select nextval('rhpessoalprocessoremuneracao_rh272_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhpessoalprocessoremuneracao_rh272_sequencial_seq do campo: rh272_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh272_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhpessoalprocessoremuneracao_rh272_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh272_sequencial)){
         $this->erro_sql = " Campo rh272_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh272_sequencial = $rh272_sequencial; 
       }
     }
     if(($this->rh272_sequencial == null) || ($this->rh272_sequencial == "") ){ 
       $this->erro_sql = " Campo rh272_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpessoalprocessoremuneracao(
                                       rh272_sequencial 
                                      ,rh272_sequencialprocessocontrato 
                                      ,rh272_dtremun 
                                      ,rh272_vrsalfx 
                                      ,rh272_undSalFixo 
                                      ,rh272_dscSalVar 
                       )
                values (
                                $this->rh272_sequencial 
                               ,$this->rh272_sequencialprocessocontrato 
                               ,".($this->rh272_dtremun == "null" || $this->rh272_dtremun == ""?"null":"'".$this->rh272_dtremun."'")." 
                               ,$this->rh272_vrsalfx 
                               ,$this->rh272_undSalFixo 
                               ,'$this->rh272_dscSalVar' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Remuneração do servidor ($this->rh272_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Remuneração do servidor já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Remuneração do servidor ($this->rh272_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh272_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh272_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1014824,'$this->rh272_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1011033,1014824,'','".AddSlashes(pg_result($resaco,0,'rh272_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011033,1015481,'','".AddSlashes(pg_result($resaco,0,'rh272_sequencialprocessocontrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011033,1014826,'','".AddSlashes(pg_result($resaco,0,'rh272_dtremun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011033,1014827,'','".AddSlashes(pg_result($resaco,0,'rh272_vrsalfx'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011033,1014828,'','".AddSlashes(pg_result($resaco,0,'rh272_undSalFixo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011033,1014829,'','".AddSlashes(pg_result($resaco,0,'rh272_dscSalVar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh272_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhpessoalprocessoremuneracao set ";
     $virgula = "";
     if(trim($this->rh272_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh272_sequencial"])){ 
       $sql  .= $virgula." rh272_sequencial = $this->rh272_sequencial ";
       $virgula = ",";
       if(trim($this->rh272_sequencial) == null ){ 
         $this->erro_sql = " Campo Número Sequencial não informado.";
         $this->erro_campo = "rh272_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh272_sequencialprocessocontrato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh272_sequencialprocessocontrato"])){ 
       $sql  .= $virgula." rh272_sequencialprocessocontrato = $this->rh272_sequencialprocessocontrato ";
       $virgula = ",";
       if(trim($this->rh272_sequencialprocessocontrato) == null ){ 
         $this->erro_sql = " Campo Sequencial contrato não informado.";
         $this->erro_campo = "rh272_sequencialprocessocontrato";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh272_dtremun)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh272_dtremun_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh272_dtremun_dia"] !="") ){ 
       $sql  .= $virgula." rh272_dtremun = '$this->rh272_dtremun' ";
       $virgula = ",";
       if(trim($this->rh272_dtremun) == null ){ 
         $this->erro_sql = " Campo Data Remuneração não informado.";
         $this->erro_campo = "rh272_dtremun_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh272_dtremun_dia"])){ 
         $sql  .= $virgula." rh272_dtremun = null ";
         $virgula = ",";
         if(trim($this->rh272_dtremun) == null ){ 
           $this->erro_sql = " Campo Data Remuneração não informado.";
           $this->erro_campo = "rh272_dtremun_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh272_vrsalfx)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh272_vrsalfx"])){ 
       $sql  .= $virgula." rh272_vrsalfx = $this->rh272_vrsalfx ";
       $virgula = ",";
       if(trim($this->rh272_vrsalfx) == null ){ 
         $this->erro_sql = " Campo Salário base não informado.";
         $this->erro_campo = "rh272_vrsalfx";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh272_undSalFixo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh272_undSalFixo"])){ 
        if(trim($this->rh272_undSalFixo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh272_undSalFixo"])){ 
           $this->rh272_undSalFixo = "0" ; 
        } 
       $sql  .= $virgula." rh272_undSalFixo = $this->rh272_undSalFixo ";
       $virgula = ",";
     }
     if(trim($this->rh272_dscSalVar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh272_dscSalVar"])){ 
       $sql  .= $virgula." rh272_dscSalVar = '$this->rh272_dscSalVar' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh272_sequencial!=null){
       $sql .= " rh272_sequencial = $this->rh272_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh272_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1014824,'$this->rh272_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh272_sequencial"]) || $this->rh272_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1011033,1014824,'".AddSlashes(pg_result($resaco,$conresaco,'rh272_sequencial'))."','$this->rh272_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh272_sequencialprocessocontrato"]) || $this->rh272_sequencialprocessocontrato != "")
             $resac = db_query("insert into db_acount values($acount,1011033,1015481,'".AddSlashes(pg_result($resaco,$conresaco,'rh272_sequencialprocessocontrato'))."','$this->rh272_sequencialprocessocontrato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh272_dtremun"]) || $this->rh272_dtremun != "")
             $resac = db_query("insert into db_acount values($acount,1011033,1014826,'".AddSlashes(pg_result($resaco,$conresaco,'rh272_dtremun'))."','$this->rh272_dtremun',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh272_vrsalfx"]) || $this->rh272_vrsalfx != "")
             $resac = db_query("insert into db_acount values($acount,1011033,1014827,'".AddSlashes(pg_result($resaco,$conresaco,'rh272_vrsalfx'))."','$this->rh272_vrsalfx',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh272_undSalFixo"]) || $this->rh272_undSalFixo != "")
             $resac = db_query("insert into db_acount values($acount,1011033,1014828,'".AddSlashes(pg_result($resaco,$conresaco,'rh272_undSalFixo'))."','$this->rh272_undSalFixo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh272_dscSalVar"]) || $this->rh272_dscSalVar != "")
             $resac = db_query("insert into db_acount values($acount,1011033,1014829,'".AddSlashes(pg_result($resaco,$conresaco,'rh272_dscSalVar'))."','$this->rh272_dscSalVar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Remuneração do servidor não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh272_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Remuneração do servidor não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh272_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh272_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh272_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh272_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1014824,'$rh272_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1011033,1014824,'','".AddSlashes(pg_result($resaco,$iresaco,'rh272_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011033,1015481,'','".AddSlashes(pg_result($resaco,$iresaco,'rh272_sequencialprocessocontrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011033,1014826,'','".AddSlashes(pg_result($resaco,$iresaco,'rh272_dtremun'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011033,1014827,'','".AddSlashes(pg_result($resaco,$iresaco,'rh272_vrsalfx'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011033,1014828,'','".AddSlashes(pg_result($resaco,$iresaco,'rh272_undSalFixo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011033,1014829,'','".AddSlashes(pg_result($resaco,$iresaco,'rh272_dscSalVar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhpessoalprocessoremuneracao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh272_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh272_sequencial = $rh272_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Remuneração do servidor não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh272_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Remuneração do servidor não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh272_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh272_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpessoalprocessoremuneracao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh272_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhpessoalprocessoremuneracao ";
     $sql .= "      inner join rhpessoalprocessocontrato  on  rhpessoalprocessocontrato.rh273_sequencial = rhpessoalprocessoremuneracao.rh272_sequencialprocessocontrato";
     $sql .= "      inner join rhpessoalprocessoservidor  on  rhpessoalprocessoservidor.rh271_sequencial = rhpessoalprocessocontrato.rh273_sequencialprocessoservidor";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh272_sequencial)) {
         $sql2 .= " where rhpessoalprocessoremuneracao.rh272_sequencial = $rh272_sequencial "; 
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

    public function sql_query_file($rh272_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhpessoalprocessoremuneracao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh272_sequencial)){
         $sql2 .= " where rhpessoalprocessoremuneracao.rh272_sequencial = $rh272_sequencial "; 
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
