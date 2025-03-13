<?php

class cl_rhpessoalprocessoperiodo
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
    public $rh282_sequencial = 0; 
    public $rh282_sequencialprocessocontrato = 0; 
    public $rh282_perref = null; 
    public $rh282_vrbccp13 = 0; 
    public $rh282_vrbccpmensal = 0; 
    public $rh282_grauexp = 0; 
    public $rh282_codcateg = 0; 
    public $rh282_vrbccprev = 0; 
    public $rh282_vrbcfgtsdecant = 0; 
    public $rh282_vrbcfgtssefip = 0; 
    public $rh282_vrbcfgtsproctrab = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh282_sequencial = int4 = Número Sequencial 
                 rh282_sequencialprocessocontrato = int4 = Processo contrato 
                 rh282_perref = varchar(7) = Competência 
                 rh282_vrbccp13 = float4 = Valor da base de cálculo 13º 
                 rh282_vrbccpmensal = float4 = Valor da base 
                 rh282_grauexp = int4 = Grau Exposição 
                 rh282_codcateg = int4 = Código da categoria 
                 rh282_vrbccprev = float4 = Remuneração do trabalhador 
                 rh282_vrbcfgtsdecant = float4 = Valor da base FGTS não recolhida 
                 rh282_vrbcfgtssefip = float4 = Valor da base FGTS com SEFIP 
                 rh282_vrbcfgtsproctrab = float4 = Valor da base FGTS sem SEFIP 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhpessoalprocessoperiodo"); 
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
       $this->rh282_sequencial = ($this->rh282_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh282_sequencial"]:$this->rh282_sequencial);
       $this->rh282_sequencialprocessocontrato = ($this->rh282_sequencialprocessocontrato == ""?@$GLOBALS["HTTP_POST_VARS"]["rh282_sequencialprocessocontrato"]:$this->rh282_sequencialprocessocontrato);
       $this->rh282_perref = ($this->rh282_perref == ""?@$GLOBALS["HTTP_POST_VARS"]["rh282_perref"]:$this->rh282_perref);
       $this->rh282_vrbccp13 = ($this->rh282_vrbccp13 == ""?@$GLOBALS["HTTP_POST_VARS"]["rh282_vrbccp13"]:$this->rh282_vrbccp13);
       $this->rh282_vrbccpmensal = ($this->rh282_vrbccpmensal == ""?@$GLOBALS["HTTP_POST_VARS"]["rh282_vrbccpmensal"]:$this->rh282_vrbccpmensal);
       $this->rh282_grauexp = ($this->rh282_grauexp == ""?@$GLOBALS["HTTP_POST_VARS"]["rh282_grauexp"]:$this->rh282_grauexp);
       $this->rh282_codcateg = ($this->rh282_codcateg == ""?@$GLOBALS["HTTP_POST_VARS"]["rh282_codcateg"]:$this->rh282_codcateg);
       $this->rh282_vrbccprev = ($this->rh282_vrbccprev == ""?@$GLOBALS["HTTP_POST_VARS"]["rh282_vrbccprev"]:$this->rh282_vrbccprev);
       $this->rh282_vrbcfgtsdecant = ($this->rh282_vrbcfgtsdecant == ""?@$GLOBALS["HTTP_POST_VARS"]["rh282_vrbcfgtsdecant"]:$this->rh282_vrbcfgtsdecant);
       $this->rh282_vrbcfgtssefip = ($this->rh282_vrbcfgtssefip == ""?@$GLOBALS["HTTP_POST_VARS"]["rh282_vrbcfgtssefip"]:$this->rh282_vrbcfgtssefip);
       $this->rh282_vrbcfgtsproctrab = ($this->rh282_vrbcfgtsproctrab == ""?@$GLOBALS["HTTP_POST_VARS"]["rh282_vrbcfgtsproctrab"]:$this->rh282_vrbcfgtsproctrab);
     }else{
       $this->rh282_sequencial = ($this->rh282_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh282_sequencial"]:$this->rh282_sequencial);
     }
   }

    public function incluir($rh282_sequencial)
    {
      $this->atualizacampos();
     if($this->rh282_sequencialprocessocontrato == null ){ 
       $this->erro_sql = " Campo Processo contrato não informado.";
       $this->erro_campo = "rh282_sequencialprocessocontrato";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh282_vrbccp13 == null ){ 
       $this->rh282_vrbccp13 = "0";
     }
     if($this->rh282_vrbccpmensal == null ){ 
       $this->rh282_vrbccpmensal = "0";
     }
     if($this->rh282_grauexp == null ){ 
       $this->rh282_grauexp = "0";
     }
     if($this->rh282_codcateg == null ){ 
       $this->rh282_codcateg = "0";
     }
     if($this->rh282_vrbccprev == null ){ 
       $this->rh282_vrbccprev = "0";
     }
     if($this->rh282_vrbcfgtsdecant == null ){ 
       $this->rh282_vrbcfgtsdecant = "0";
     }
     if($this->rh282_vrbcfgtssefip == null ){ 
       $this->rh282_vrbcfgtssefip = "0";
     }
     if($this->rh282_vrbcfgtsproctrab == null ){ 
       $this->rh282_vrbcfgtsproctrab = "0";
     }
     if($rh282_sequencial == "" || $rh282_sequencial == null ){
       $result = db_query("select nextval('rhpessoalprocessoperiodo_rh282_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhpessoalprocessoperiodo_rh282_sequencial_seq do campo: rh282_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh282_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhpessoalprocessoperiodo_rh282_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh282_sequencial)){
         $this->erro_sql = " Campo rh282_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh282_sequencial = $rh282_sequencial; 
       }
     }
     if(($this->rh282_sequencial == null) || ($this->rh282_sequencial == "") ){ 
       $this->erro_sql = " Campo rh282_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpessoalprocessoperiodo(
                                       rh282_sequencial 
                                      ,rh282_sequencialprocessocontrato 
                                      ,rh282_perref 
                                      ,rh282_vrbccp13 
                                      ,rh282_vrbccpmensal 
                                      ,rh282_grauexp 
                                      ,rh282_codcateg 
                                      ,rh282_vrbccprev 
                                      ,rh282_vrbcfgtsdecant 
                                      ,rh282_vrbcfgtssefip 
                                      ,rh282_vrbcfgtsproctrab 
                       )
                values (
                                $this->rh282_sequencial 
                               ,$this->rh282_sequencialprocessocontrato 
                               ,'$this->rh282_perref' 
                               ,$this->rh282_vrbccp13 
                               ,$this->rh282_vrbccpmensal 
                               ,$this->rh282_grauexp 
                               ,$this->rh282_codcateg 
                               ,$this->rh282_vrbccprev 
                               ,$this->rh282_vrbcfgtsdecant 
                               ,$this->rh282_vrbcfgtssefip 
                               ,$this->rh282_vrbcfgtsproctrab 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Identificação do período ($this->rh282_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Identificação do período já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Identificação do período ($this->rh282_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh282_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh282_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1014898,'$this->rh282_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1011050,1014898,'','".AddSlashes(pg_result($resaco,0,'rh282_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011050,1014899,'','".AddSlashes(pg_result($resaco,0,'rh282_sequencialprocessocontrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011050,1014900,'','".AddSlashes(pg_result($resaco,0,'rh282_perref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011050,1014902,'','".AddSlashes(pg_result($resaco,0,'rh282_vrbccp13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011050,1014901,'','".AddSlashes(pg_result($resaco,0,'rh282_vrbccpmensal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011050,1014905,'','".AddSlashes(pg_result($resaco,0,'rh282_grauexp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011050,1014909,'','".AddSlashes(pg_result($resaco,0,'rh282_codcateg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011050,1014910,'','".AddSlashes(pg_result($resaco,0,'rh282_vrbccprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011050,1015368,'','".AddSlashes(pg_result($resaco,0,'rh282_vrbcfgtsdecant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011050,1015367,'','".AddSlashes(pg_result($resaco,0,'rh282_vrbcfgtssefip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011050,1015366,'','".AddSlashes(pg_result($resaco,0,'rh282_vrbcfgtsproctrab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh282_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhpessoalprocessoperiodo set ";
     $virgula = "";
     if(trim($this->rh282_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh282_sequencial"])){ 
       $sql  .= $virgula." rh282_sequencial = $this->rh282_sequencial ";
       $virgula = ",";
       if(trim($this->rh282_sequencial) == null ){ 
         $this->erro_sql = " Campo Número Sequencial não informado.";
         $this->erro_campo = "rh282_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh282_sequencialprocessocontrato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh282_sequencialprocessocontrato"])){ 
       $sql  .= $virgula." rh282_sequencialprocessocontrato = $this->rh282_sequencialprocessocontrato ";
       $virgula = ",";
       if(trim($this->rh282_sequencialprocessocontrato) == null ){ 
         $this->erro_sql = " Campo Processo contrato não informado.";
         $this->erro_campo = "rh282_sequencialprocessocontrato";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh282_perref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh282_perref"])){ 
       $sql  .= $virgula." rh282_perref = '$this->rh282_perref' ";
       $virgula = ",";
     }
     if(trim($this->rh282_vrbccp13)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh282_vrbccp13"])){ 
        if(trim($this->rh282_vrbccp13)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh282_vrbccp13"])){ 
           $this->rh282_vrbccp13 = "0" ; 
        } 
       $sql  .= $virgula." rh282_vrbccp13 = $this->rh282_vrbccp13 ";
       $virgula = ",";
     }
     if(trim($this->rh282_vrbccpmensal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh282_vrbccpmensal"])){ 
        if(trim($this->rh282_vrbccpmensal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh282_vrbccpmensal"])){ 
           $this->rh282_vrbccpmensal = "0" ; 
        } 
       $sql  .= $virgula." rh282_vrbccpmensal = $this->rh282_vrbccpmensal ";
       $virgula = ",";
     }
     if(trim($this->rh282_grauexp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh282_grauexp"])){ 
        if(trim($this->rh282_grauexp)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh282_grauexp"])){ 
           $this->rh282_grauexp = "0" ; 
        } 
       $sql  .= $virgula." rh282_grauexp = $this->rh282_grauexp ";
       $virgula = ",";
     }
     if(trim($this->rh282_codcateg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh282_codcateg"])){ 
        if(trim($this->rh282_codcateg)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh282_codcateg"])){ 
           $this->rh282_codcateg = "0" ; 
        } 
       $sql  .= $virgula." rh282_codcateg = $this->rh282_codcateg ";
       $virgula = ",";
     }
     if(trim($this->rh282_vrbccprev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh282_vrbccprev"])){ 
        if(trim($this->rh282_vrbccprev)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh282_vrbccprev"])){ 
           $this->rh282_vrbccprev = "0" ; 
        } 
       $sql  .= $virgula." rh282_vrbccprev = $this->rh282_vrbccprev ";
       $virgula = ",";
     }
     if(trim($this->rh282_vrbcfgtsdecant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh282_vrbcfgtsdecant"])){ 
        if(trim($this->rh282_vrbcfgtsdecant)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh282_vrbcfgtsdecant"])){ 
           $this->rh282_vrbcfgtsdecant = "0" ; 
        } 
       $sql  .= $virgula." rh282_vrbcfgtsdecant = $this->rh282_vrbcfgtsdecant ";
       $virgula = ",";
     }
     if(trim($this->rh282_vrbcfgtssefip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh282_vrbcfgtssefip"])){ 
        if(trim($this->rh282_vrbcfgtssefip)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh282_vrbcfgtssefip"])){ 
           $this->rh282_vrbcfgtssefip = "0" ; 
        } 
       $sql  .= $virgula." rh282_vrbcfgtssefip = $this->rh282_vrbcfgtssefip ";
       $virgula = ",";
     }
     if(trim($this->rh282_vrbcfgtsproctrab)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh282_vrbcfgtsproctrab"])){ 
        if(trim($this->rh282_vrbcfgtsproctrab)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh282_vrbcfgtsproctrab"])){ 
           $this->rh282_vrbcfgtsproctrab = "0" ; 
        } 
       $sql  .= $virgula." rh282_vrbcfgtsproctrab = $this->rh282_vrbcfgtsproctrab ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh282_sequencial!=null){
       $sql .= " rh282_sequencial = $this->rh282_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh282_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1014898,'$this->rh282_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh282_sequencial"]) || $this->rh282_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1011050,1014898,'".AddSlashes(pg_result($resaco,$conresaco,'rh282_sequencial'))."','$this->rh282_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh282_sequencialprocessocontrato"]) || $this->rh282_sequencialprocessocontrato != "")
             $resac = db_query("insert into db_acount values($acount,1011050,1014899,'".AddSlashes(pg_result($resaco,$conresaco,'rh282_sequencialprocessocontrato'))."','$this->rh282_sequencialprocessocontrato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh282_perref"]) || $this->rh282_perref != "")
             $resac = db_query("insert into db_acount values($acount,1011050,1014900,'".AddSlashes(pg_result($resaco,$conresaco,'rh282_perref'))."','$this->rh282_perref',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh282_vrbccp13"]) || $this->rh282_vrbccp13 != "")
             $resac = db_query("insert into db_acount values($acount,1011050,1014902,'".AddSlashes(pg_result($resaco,$conresaco,'rh282_vrbccp13'))."','$this->rh282_vrbccp13',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh282_vrbccpmensal"]) || $this->rh282_vrbccpmensal != "")
             $resac = db_query("insert into db_acount values($acount,1011050,1014901,'".AddSlashes(pg_result($resaco,$conresaco,'rh282_vrbccpmensal'))."','$this->rh282_vrbccpmensal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh282_grauexp"]) || $this->rh282_grauexp != "")
             $resac = db_query("insert into db_acount values($acount,1011050,1014905,'".AddSlashes(pg_result($resaco,$conresaco,'rh282_grauexp'))."','$this->rh282_grauexp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh282_codcateg"]) || $this->rh282_codcateg != "")
             $resac = db_query("insert into db_acount values($acount,1011050,1014909,'".AddSlashes(pg_result($resaco,$conresaco,'rh282_codcateg'))."','$this->rh282_codcateg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh282_vrbccprev"]) || $this->rh282_vrbccprev != "")
             $resac = db_query("insert into db_acount values($acount,1011050,1014910,'".AddSlashes(pg_result($resaco,$conresaco,'rh282_vrbccprev'))."','$this->rh282_vrbccprev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh282_vrbcfgtsdecant"]) || $this->rh282_vrbcfgtsdecant != "")
             $resac = db_query("insert into db_acount values($acount,1011050,1015368,'".AddSlashes(pg_result($resaco,$conresaco,'rh282_vrbcfgtsdecant'))."','$this->rh282_vrbcfgtsdecant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh282_vrbcfgtssefip"]) || $this->rh282_vrbcfgtssefip != "")
             $resac = db_query("insert into db_acount values($acount,1011050,1015367,'".AddSlashes(pg_result($resaco,$conresaco,'rh282_vrbcfgtssefip'))."','$this->rh282_vrbcfgtssefip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh282_vrbcfgtsproctrab"]) || $this->rh282_vrbcfgtsproctrab != "")
             $resac = db_query("insert into db_acount values($acount,1011050,1015366,'".AddSlashes(pg_result($resaco,$conresaco,'rh282_vrbcfgtsproctrab'))."','$this->rh282_vrbcfgtsproctrab',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Identificação do período não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh282_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Identificação do período não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh282_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh282_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh282_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh282_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1014898,'$rh282_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1011050,1014898,'','".AddSlashes(pg_result($resaco,$iresaco,'rh282_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011050,1014899,'','".AddSlashes(pg_result($resaco,$iresaco,'rh282_sequencialprocessocontrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011050,1014900,'','".AddSlashes(pg_result($resaco,$iresaco,'rh282_perref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011050,1014902,'','".AddSlashes(pg_result($resaco,$iresaco,'rh282_vrbccp13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011050,1014901,'','".AddSlashes(pg_result($resaco,$iresaco,'rh282_vrbccpmensal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011050,1014905,'','".AddSlashes(pg_result($resaco,$iresaco,'rh282_grauexp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011050,1014909,'','".AddSlashes(pg_result($resaco,$iresaco,'rh282_codcateg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011050,1014910,'','".AddSlashes(pg_result($resaco,$iresaco,'rh282_vrbccprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011050,1015368,'','".AddSlashes(pg_result($resaco,$iresaco,'rh282_vrbcfgtsdecant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011050,1015367,'','".AddSlashes(pg_result($resaco,$iresaco,'rh282_vrbcfgtssefip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011050,1015366,'','".AddSlashes(pg_result($resaco,$iresaco,'rh282_vrbcfgtsproctrab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhpessoalprocessoperiodo
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh282_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh282_sequencial = $rh282_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Identificação do período não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh282_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Identificação do período não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh282_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh282_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpessoalprocessoperiodo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh282_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhpessoalprocessoperiodo ";
     $sql .= "      inner join rhpessoalprocessocontrato  on  rhpessoalprocessocontrato.rh273_sequencial = rhpessoalprocessoperiodo.rh282_sequencialprocessocontrato";
     $sql .= "      inner join rhpessoalprocessoservidor  on  rhpessoalprocessoservidor.rh271_sequencial = rhpessoalprocessocontrato.rh273_sequencialprocessoservidor";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh282_sequencial)) {
         $sql2 .= " where rhpessoalprocessoperiodo.rh282_sequencial = $rh282_sequencial "; 
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

    public function sql_query_file($rh282_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhpessoalprocessoperiodo ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh282_sequencial)){
         $sql2 .= " where rhpessoalprocessoperiodo.rh282_sequencial = $rh282_sequencial "; 
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
