<?php

class cl_rhpessoalprocessojudicialesocial
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
    public $rh270_sequencial = 0; 
    public $rh270_nrproctrab = null; 
    public $rh270_obsproctrab = null; 
    public $rh270_dtsent = null; 
    public $rh270_ufvara = null; 
    public $rh270_codmunic = 0; 
    public $rh270_idvara = 0; 
    public $rh270_origem = 0; 
    public $rh270_dtccp_dia = null; 
    public $rh270_dtccp_mes = null; 
    public $rh270_dtccp_ano = null; 
    public $rh270_dtccp = null; 
    public $rh270_tpccp = 0; 
    public $rh270_cnpjccp = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh270_sequencial = int4 = Número Sequencial 
                 rh270_nrproctrab = varchar(20) = Número do Processo Trabalhista 
                 rh270_obsproctrab = text = Observações 
                 rh270_dtsent = varchar(20) = Data da Sentença 
                 rh270_ufvara = varchar(2) = Unidade da Federaçðo 
                 rh270_codmunic = int4 = Código Município (IBGE) 
                 rh270_idvara = int4 = Vara 
                 rh270_origem = int4 = Origem do Processo 
                 rh270_dtccp = date = Data Acordo 
                 rh270_tpccp = int4 = Âmbito Acordo 
                 rh270_cnpjccp = varchar(14) = CNPJ do Sindicato 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhpessoalprocessojudicialesocial"); 
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
       $this->rh270_sequencial = ($this->rh270_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh270_sequencial"]:$this->rh270_sequencial);
       $this->rh270_nrproctrab = ($this->rh270_nrproctrab == ""?@$GLOBALS["HTTP_POST_VARS"]["rh270_nrproctrab"]:$this->rh270_nrproctrab);
       $this->rh270_obsproctrab = ($this->rh270_obsproctrab == ""?@$GLOBALS["HTTP_POST_VARS"]["rh270_obsproctrab"]:$this->rh270_obsproctrab);
       $this->rh270_dtsent = ($this->rh270_dtsent == ""?@$GLOBALS["HTTP_POST_VARS"]["rh270_dtsent"]:$this->rh270_dtsent);
       $this->rh270_ufvara = ($this->rh270_ufvara == ""?@$GLOBALS["HTTP_POST_VARS"]["rh270_ufvara"]:$this->rh270_ufvara);
       $this->rh270_codmunic = ($this->rh270_codmunic == ""?@$GLOBALS["HTTP_POST_VARS"]["rh270_codmunic"]:$this->rh270_codmunic);
       $this->rh270_idvara = ($this->rh270_idvara == ""?@$GLOBALS["HTTP_POST_VARS"]["rh270_idvara"]:$this->rh270_idvara);
       $this->rh270_origem = ($this->rh270_origem == ""?@$GLOBALS["HTTP_POST_VARS"]["rh270_origem"]:$this->rh270_origem);
       if($this->rh270_dtccp == ""){
         $this->rh270_dtccp_dia = ($this->rh270_dtccp_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh270_dtccp_dia"]:$this->rh270_dtccp_dia);
         $this->rh270_dtccp_mes = ($this->rh270_dtccp_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh270_dtccp_mes"]:$this->rh270_dtccp_mes);
         $this->rh270_dtccp_ano = ($this->rh270_dtccp_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh270_dtccp_ano"]:$this->rh270_dtccp_ano);
         if($this->rh270_dtccp_dia != ""){
            $this->rh270_dtccp = $this->rh270_dtccp_ano."-".$this->rh270_dtccp_mes."-".$this->rh270_dtccp_dia;
         }
       }
       $this->rh270_tpccp = ($this->rh270_tpccp == ""?@$GLOBALS["HTTP_POST_VARS"]["rh270_tpccp"]:$this->rh270_tpccp);
       $this->rh270_cnpjccp = ($this->rh270_cnpjccp == ""?@$GLOBALS["HTTP_POST_VARS"]["rh270_cnpjccp"]:$this->rh270_cnpjccp);
     }else{
       $this->rh270_sequencial = ($this->rh270_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh270_sequencial"]:$this->rh270_sequencial);
     }
   }

    public function incluir($rh270_sequencial)
    {
      $this->atualizacampos();
     if($this->rh270_dtsent == null ){ 
       $this->rh270_dtsent = "null";
     }
     if($this->rh270_codmunic == null ){ 
       $this->rh270_codmunic = "0";
     }
     if($this->rh270_idvara == null ){ 
       $this->rh270_idvara = "0";
     }
     if($this->rh270_origem == null ){ 
       $this->rh270_origem = "0";
     }
     if($this->rh270_dtccp == null ){ 
       $this->rh270_dtccp = "null";
     }
     if($this->rh270_tpccp == null ){ 
       $this->rh270_tpccp = "0";
     }
     if($rh270_sequencial == "" || $rh270_sequencial == null ){
       $result = db_query("select nextval('rhpessoalprocessojudicialesocial_rh270_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhpessoalprocessojudicialesocial_rh270_sequencial_seq do campo: rh270_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh270_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhpessoalprocessojudicialesocial_rh270_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh270_sequencial)){
         $this->erro_sql = " Campo rh270_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh270_sequencial = $rh270_sequencial; 
       }
     }
     if(($this->rh270_sequencial == null) || ($this->rh270_sequencial == "") ){ 
       $this->erro_sql = " Campo rh270_sequencial nðo declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpessoalprocessojudicialesocial(
                                       rh270_sequencial 
                                      ,rh270_nrproctrab 
                                      ,rh270_obsproctrab 
                                      ,rh270_dtsent 
                                      ,rh270_ufvara 
                                      ,rh270_codmunic 
                                      ,rh270_idvara 
                                      ,rh270_origem 
                                      ,rh270_dtccp 
                                      ,rh270_tpccp 
                                      ,rh270_cnpjccp 
                       )
                values (
                                $this->rh270_sequencial 
                               ,'$this->rh270_nrproctrab' 
                               ,'$this->rh270_obsproctrab' 
                               ,".($this->rh270_dtsent == "null" || $this->rh270_dtsent == ""?"null":"'".$this->rh270_dtsent."'")." 
                               ,'$this->rh270_ufvara' 
                               ,$this->rh270_codmunic 
                               ,$this->rh270_idvara 
                               ,$this->rh270_origem 
                               ,".($this->rh270_dtccp == "null" || $this->rh270_dtccp == ""?"null":"'".$this->rh270_dtccp."'")." 
                               ,$this->rh270_tpccp 
                               ,'$this->rh270_cnpjccp' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Processo Trabalhista ($this->rh270_sequencial) nðo Incluído. Inclusðo Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Processo Trabalhista já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Processo Trabalhista ($this->rh270_sequencial) nðo Incluído. Inclusðo Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusðo efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh270_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh270_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1014804,'$this->rh270_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1011031,1014804,'','".AddSlashes(pg_result($resaco,0,'rh270_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011031,1014805,'','".AddSlashes(pg_result($resaco,0,'rh270_nrproctrab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011031,1014806,'','".AddSlashes(pg_result($resaco,0,'rh270_obsproctrab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011031,1014807,'','".AddSlashes(pg_result($resaco,0,'rh270_dtsent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011031,1014808,'','".AddSlashes(pg_result($resaco,0,'rh270_ufvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011031,1014809,'','".AddSlashes(pg_result($resaco,0,'rh270_codmunic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011031,1014810,'','".AddSlashes(pg_result($resaco,0,'rh270_idvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011031,1014811,'','".AddSlashes(pg_result($resaco,0,'rh270_origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011031,1014812,'','".AddSlashes(pg_result($resaco,0,'rh270_dtccp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011031,1014813,'','".AddSlashes(pg_result($resaco,0,'rh270_tpccp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011031,1014814,'','".AddSlashes(pg_result($resaco,0,'rh270_cnpjccp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh270_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhpessoalprocessojudicialesocial set ";
     $virgula = "";
     if(trim($this->rh270_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh270_sequencial"])){ 
       $sql  .= $virgula." rh270_sequencial = $this->rh270_sequencial ";
       $virgula = ",";
       if(trim($this->rh270_sequencial) == null ){ 
         $this->erro_sql = " Campo Número Sequencial nðo informado.";
         $this->erro_campo = "rh270_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh270_nrproctrab)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh270_nrproctrab"])){ 
       $sql  .= $virgula." rh270_nrproctrab = '$this->rh270_nrproctrab' ";
       $virgula = ",";
     }
     if(trim($this->rh270_obsproctrab)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh270_obsproctrab"])){ 
       $sql  .= $virgula." rh270_obsproctrab = '$this->rh270_obsproctrab' ";
       $virgula = ",";
     }
     if(trim($this->rh270_dtsent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh270_dtsent"])){ 
       $sql  .= $virgula." rh270_dtsent = '$this->rh270_dtsent' ";
       $virgula = ",";
     }
     if(trim($this->rh270_ufvara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh270_ufvara"])){ 
       $sql  .= $virgula." rh270_ufvara = '$this->rh270_ufvara' ";
       $virgula = ",";
     }
     if(trim($this->rh270_codmunic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh270_codmunic"])){ 
        if(trim($this->rh270_codmunic)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh270_codmunic"])){ 
           $this->rh270_codmunic = "0" ; 
        } 
       $sql  .= $virgula." rh270_codmunic = $this->rh270_codmunic ";
       $virgula = ",";
     }
     if(trim($this->rh270_idvara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh270_idvara"])){ 
        if(trim($this->rh270_idvara)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh270_idvara"])){ 
           $this->rh270_idvara = "0" ; 
        } 
       $sql  .= $virgula." rh270_idvara = $this->rh270_idvara ";
       $virgula = ",";
     }
     if(trim($this->rh270_origem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh270_origem"])){ 
        if(trim($this->rh270_origem)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh270_origem"])){ 
           $this->rh270_origem = "0" ; 
        } 
       $sql  .= $virgula." rh270_origem = $this->rh270_origem ";
       $virgula = ",";
     }
     if(trim($this->rh270_dtccp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh270_dtccp_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh270_dtccp_dia"] !="") ){ 
       $sql  .= $virgula." rh270_dtccp = '$this->rh270_dtccp' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh270_dtccp_dia"])){ 
         $sql  .= $virgula." rh270_dtccp = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh270_tpccp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh270_tpccp"])){ 
        if(trim($this->rh270_tpccp)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh270_tpccp"])){ 
           $this->rh270_tpccp = "0" ; 
        } 
       $sql  .= $virgula." rh270_tpccp = $this->rh270_tpccp ";
       $virgula = ",";
     }
     if(trim($this->rh270_cnpjccp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh270_cnpjccp"])){ 
       $sql  .= $virgula." rh270_cnpjccp = '$this->rh270_cnpjccp' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh270_sequencial!=null){
       $sql .= " rh270_sequencial = $this->rh270_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh270_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1014804,'$this->rh270_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh270_sequencial"]) || $this->rh270_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1011031,1014804,'".AddSlashes(pg_result($resaco,$conresaco,'rh270_sequencial'))."','$this->rh270_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh270_nrproctrab"]) || $this->rh270_nrproctrab != "")
             $resac = db_query("insert into db_acount values($acount,1011031,1014805,'".AddSlashes(pg_result($resaco,$conresaco,'rh270_nrproctrab'))."','$this->rh270_nrproctrab',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh270_obsproctrab"]) || $this->rh270_obsproctrab != "")
             $resac = db_query("insert into db_acount values($acount,1011031,1014806,'".AddSlashes(pg_result($resaco,$conresaco,'rh270_obsproctrab'))."','$this->rh270_obsproctrab',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh270_dtsent"]) || $this->rh270_dtsent != "")
             $resac = db_query("insert into db_acount values($acount,1011031,1014807,'".AddSlashes(pg_result($resaco,$conresaco,'rh270_dtsent'))."','$this->rh270_dtsent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh270_ufvara"]) || $this->rh270_ufvara != "")
             $resac = db_query("insert into db_acount values($acount,1011031,1014808,'".AddSlashes(pg_result($resaco,$conresaco,'rh270_ufvara'))."','$this->rh270_ufvara',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh270_codmunic"]) || $this->rh270_codmunic != "")
             $resac = db_query("insert into db_acount values($acount,1011031,1014809,'".AddSlashes(pg_result($resaco,$conresaco,'rh270_codmunic'))."','$this->rh270_codmunic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh270_idvara"]) || $this->rh270_idvara != "")
             $resac = db_query("insert into db_acount values($acount,1011031,1014810,'".AddSlashes(pg_result($resaco,$conresaco,'rh270_idvara'))."','$this->rh270_idvara',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh270_origem"]) || $this->rh270_origem != "")
             $resac = db_query("insert into db_acount values($acount,1011031,1014811,'".AddSlashes(pg_result($resaco,$conresaco,'rh270_origem'))."','$this->rh270_origem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh270_dtccp"]) || $this->rh270_dtccp != "")
             $resac = db_query("insert into db_acount values($acount,1011031,1014812,'".AddSlashes(pg_result($resaco,$conresaco,'rh270_dtccp'))."','$this->rh270_dtccp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh270_tpccp"]) || $this->rh270_tpccp != "")
             $resac = db_query("insert into db_acount values($acount,1011031,1014813,'".AddSlashes(pg_result($resaco,$conresaco,'rh270_tpccp'))."','$this->rh270_tpccp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh270_cnpjccp"]) || $this->rh270_cnpjccp != "")
             $resac = db_query("insert into db_acount values($acount,1011031,1014814,'".AddSlashes(pg_result($resaco,$conresaco,'rh270_cnpjccp'))."','$this->rh270_cnpjccp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Processo Trabalhista nðo Alterado. Alteraçðo Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh270_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Processo Trabalhista nðo foi Alterado. Alteraçðo Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh270_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteraçðo efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh270_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh270_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh270_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1014804,'$rh270_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1011031,1014804,'','".AddSlashes(pg_result($resaco,$iresaco,'rh270_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011031,1014805,'','".AddSlashes(pg_result($resaco,$iresaco,'rh270_nrproctrab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011031,1014806,'','".AddSlashes(pg_result($resaco,$iresaco,'rh270_obsproctrab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011031,1014807,'','".AddSlashes(pg_result($resaco,$iresaco,'rh270_dtsent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011031,1014808,'','".AddSlashes(pg_result($resaco,$iresaco,'rh270_ufvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011031,1014809,'','".AddSlashes(pg_result($resaco,$iresaco,'rh270_codmunic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011031,1014810,'','".AddSlashes(pg_result($resaco,$iresaco,'rh270_idvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011031,1014811,'','".AddSlashes(pg_result($resaco,$iresaco,'rh270_origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011031,1014812,'','".AddSlashes(pg_result($resaco,$iresaco,'rh270_dtccp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011031,1014813,'','".AddSlashes(pg_result($resaco,$iresaco,'rh270_tpccp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011031,1014814,'','".AddSlashes(pg_result($resaco,$iresaco,'rh270_cnpjccp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhpessoalprocessojudicialesocial
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh270_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh270_sequencial = $rh270_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Processo Trabalhista nðo Excluído. Exclusðo Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh270_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Processo Trabalhista nðo Encontrado. Exclusðo nðo Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh270_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusðo efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh270_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpessoalprocessojudicialesocial";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh270_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhpessoalprocessojudicialesocial ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh270_sequencial)) {
         $sql2 .= " where rhpessoalprocessojudicialesocial.rh270_sequencial = $rh270_sequencial "; 
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

    public function sql_query_file($rh270_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhpessoalprocessojudicialesocial ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh270_sequencial)){
         $sql2 .= " where rhpessoalprocessojudicialesocial.rh270_sequencial = $rh270_sequencial "; 
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
