<?php

class cl_rhprocessotributobase
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
    public $rh288_sequencial = 0; 
    public $rh288_sequencialprocessoservidor = 0; 
    public $rh288_peref = null; 
    public $rh288_vrbccpmensal = 0; 
    public $rh288_vrbccp13 = 0; 
    public $rh288_vrrendirrf = 0; 
    public $rh288_vrrendirrf13 = 0; 
    public $rh288_pagamento = null; 
    public $rh288_observacao = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh288_sequencial = int4 = Número Sequencial 
                 rh288_sequencialprocessoservidor = int4 = Identificação única de servidor 
                 rh288_peref = varchar(7) = Competência 
                 rh288_vrbccpmensal = float4 = Cálculo da contribuição previdenciária 
                 rh288_vrbccp13 = float4 = Contribuição previdenciária 13 
                 rh288_vrrendirrf = float4 = Rendimento tributável 
                 rh288_vrrendirrf13 = float4 = Rendimento tributável 13 
                 rh288_pagamento = varchar(7) = Mês/ano pagamento 
                 rh288_observacao = text = Observação 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhprocessotributobase"); 
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
       $this->rh288_sequencial = ($this->rh288_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh288_sequencial"]:$this->rh288_sequencial);
       $this->rh288_sequencialprocessoservidor = ($this->rh288_sequencialprocessoservidor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh288_sequencialprocessoservidor"]:$this->rh288_sequencialprocessoservidor);
       $this->rh288_peref = ($this->rh288_peref == ""?@$GLOBALS["HTTP_POST_VARS"]["rh288_peref"]:$this->rh288_peref);
       $this->rh288_vrbccpmensal = ($this->rh288_vrbccpmensal == ""?@$GLOBALS["HTTP_POST_VARS"]["rh288_vrbccpmensal"]:$this->rh288_vrbccpmensal);
       $this->rh288_vrbccp13 = ($this->rh288_vrbccp13 == ""?@$GLOBALS["HTTP_POST_VARS"]["rh288_vrbccp13"]:$this->rh288_vrbccp13);
       $this->rh288_vrrendirrf = ($this->rh288_vrrendirrf == ""?@$GLOBALS["HTTP_POST_VARS"]["rh288_vrrendirrf"]:$this->rh288_vrrendirrf);
       $this->rh288_vrrendirrf13 = ($this->rh288_vrrendirrf13 == ""?@$GLOBALS["HTTP_POST_VARS"]["rh288_vrrendirrf13"]:$this->rh288_vrrendirrf13);
       $this->rh288_pagamento = ($this->rh288_pagamento == ""?@$GLOBALS["HTTP_POST_VARS"]["rh288_pagamento"]:$this->rh288_pagamento);
       $this->rh288_observacao = ($this->rh288_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh288_observacao"]:$this->rh288_observacao);
     }else{
       $this->rh288_sequencial = ($this->rh288_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh288_sequencial"]:$this->rh288_sequencial);
     }
   }

    public function incluir($rh288_sequencial)
    {
      $this->atualizacampos();
     if($this->rh288_sequencialprocessoservidor == null ){ 
       $this->erro_sql = " Campo Identificação única de servidor não informado.";
       $this->erro_campo = "rh288_sequencialprocessoservidor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh288_vrbccpmensal == null ){ 
       $this->rh288_vrbccpmensal = "0";
     }
     if($this->rh288_vrbccp13 == null ){ 
       $this->rh288_vrbccp13 = "0";
     }
     if($this->rh288_vrrendirrf == null ){ 
       $this->rh288_vrrendirrf = "0";
     }
     if($this->rh288_vrrendirrf13 == null ){ 
       $this->rh288_vrrendirrf13 = "0";
     }
     if($this->rh288_pagamento == null ){ 
       $this->erro_sql = " Campo Mês/ano pagamento não informado.";
       $this->erro_campo = "rh288_pagamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh288_sequencial == "" || $rh288_sequencial == null ){
       $result = db_query("select nextval('rhprocessotributobase_rh288_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhprocessotributobase_rh288_sequencial_seq do campo: rh288_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh288_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhprocessotributobase_rh288_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh288_sequencial)){
         $this->erro_sql = " Campo rh288_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh288_sequencial = $rh288_sequencial; 
       }
     }
     if(($this->rh288_sequencial == null) || ($this->rh288_sequencial == "") ){ 
       $this->erro_sql = " Campo rh288_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhprocessotributobase(
                                       rh288_sequencial 
                                      ,rh288_sequencialprocessoservidor 
                                      ,rh288_peref 
                                      ,rh288_vrbccpmensal 
                                      ,rh288_vrbccp13 
                                      ,rh288_vrrendirrf 
                                      ,rh288_vrrendirrf13 
                                      ,rh288_pagamento 
                                      ,rh288_observacao 
                       )
                values (
                                $this->rh288_sequencial 
                               ,$this->rh288_sequencialprocessoservidor 
                               ,'$this->rh288_peref' 
                               ,$this->rh288_vrbccpmensal 
                               ,$this->rh288_vrbccp13 
                               ,$this->rh288_vrrendirrf 
                               ,$this->rh288_vrrendirrf13 
                               ,'$this->rh288_pagamento' 
                               ,'$this->rh288_observacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tributos de Processo ($this->rh288_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tributos de Processo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tributos de Processo ($this->rh288_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh288_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh288_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1015182,'$this->rh288_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1011101,1015182,'','".AddSlashes(pg_result($resaco,0,'rh288_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011101,1015183,'','".AddSlashes(pg_result($resaco,0,'rh288_sequencialprocessoservidor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011101,1015184,'','".AddSlashes(pg_result($resaco,0,'rh288_peref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011101,1015185,'','".AddSlashes(pg_result($resaco,0,'rh288_vrbccpmensal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011101,1015188,'','".AddSlashes(pg_result($resaco,0,'rh288_vrbccp13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011101,1015189,'','".AddSlashes(pg_result($resaco,0,'rh288_vrrendirrf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011101,1015190,'','".AddSlashes(pg_result($resaco,0,'rh288_vrrendirrf13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011101,1015234,'','".AddSlashes(pg_result($resaco,0,'rh288_pagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011101,1015235,'','".AddSlashes(pg_result($resaco,0,'rh288_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh288_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhprocessotributobase set ";
     $virgula = "";
     if(trim($this->rh288_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh288_sequencial"])){ 
       $sql  .= $virgula." rh288_sequencial = $this->rh288_sequencial ";
       $virgula = ",";
       if(trim($this->rh288_sequencial) == null ){ 
         $this->erro_sql = " Campo Número Sequencial não informado.";
         $this->erro_campo = "rh288_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh288_sequencialprocessoservidor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh288_sequencialprocessoservidor"])){ 
       $sql  .= $virgula." rh288_sequencialprocessoservidor = $this->rh288_sequencialprocessoservidor ";
       $virgula = ",";
       if(trim($this->rh288_sequencialprocessoservidor) == null ){ 
         $this->erro_sql = " Campo Identificação única de servidor não informado.";
         $this->erro_campo = "rh288_sequencialprocessoservidor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh288_peref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh288_peref"])){ 
       $sql  .= $virgula." rh288_peref = '$this->rh288_peref' ";
       $virgula = ",";
     }
     if(trim($this->rh288_vrbccpmensal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh288_vrbccpmensal"])){ 
        if(trim($this->rh288_vrbccpmensal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh288_vrbccpmensal"])){ 
           $this->rh288_vrbccpmensal = "0" ; 
        } 
       $sql  .= $virgula." rh288_vrbccpmensal = $this->rh288_vrbccpmensal ";
       $virgula = ",";
     }
     if(trim($this->rh288_vrbccp13)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh288_vrbccp13"])){ 
        if(trim($this->rh288_vrbccp13)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh288_vrbccp13"])){ 
           $this->rh288_vrbccp13 = "0" ; 
        } 
       $sql  .= $virgula." rh288_vrbccp13 = $this->rh288_vrbccp13 ";
       $virgula = ",";
     }
     if(trim($this->rh288_vrrendirrf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh288_vrrendirrf"])){ 
        if(trim($this->rh288_vrrendirrf)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh288_vrrendirrf"])){ 
           $this->rh288_vrrendirrf = "0" ; 
        } 
       $sql  .= $virgula." rh288_vrrendirrf = $this->rh288_vrrendirrf ";
       $virgula = ",";
     }
     if(trim($this->rh288_vrrendirrf13)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh288_vrrendirrf13"])){ 
        if(trim($this->rh288_vrrendirrf13)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh288_vrrendirrf13"])){ 
           $this->rh288_vrrendirrf13 = "0" ; 
        } 
       $sql  .= $virgula." rh288_vrrendirrf13 = $this->rh288_vrrendirrf13 ";
       $virgula = ",";
     }
     if(trim($this->rh288_pagamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh288_pagamento"])){ 
       $sql  .= $virgula." rh288_pagamento = '$this->rh288_pagamento' ";
       $virgula = ",";
       if(trim($this->rh288_pagamento) == null ){ 
         $this->erro_sql = " Campo Mês/ano pagamento não informado.";
         $this->erro_campo = "rh288_pagamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh288_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh288_observacao"])){ 
       $sql  .= $virgula." rh288_observacao = '$this->rh288_observacao' ";
       $virgula = ",";
       if(trim($this->rh288_observacao) == null ){ 
         $this->erro_sql = " Campo Observação não informado.";
         $this->erro_campo = "rh288_observacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh288_sequencial!=null){
       $sql .= " rh288_sequencial = $this->rh288_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh288_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1015182,'$this->rh288_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh288_sequencial"]) || $this->rh288_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1011101,1015182,'".AddSlashes(pg_result($resaco,$conresaco,'rh288_sequencial'))."','$this->rh288_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh288_sequencialprocessoservidor"]) || $this->rh288_sequencialprocessoservidor != "")
             $resac = db_query("insert into db_acount values($acount,1011101,1015183,'".AddSlashes(pg_result($resaco,$conresaco,'rh288_sequencialprocessoservidor'))."','$this->rh288_sequencialprocessoservidor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh288_peref"]) || $this->rh288_peref != "")
             $resac = db_query("insert into db_acount values($acount,1011101,1015184,'".AddSlashes(pg_result($resaco,$conresaco,'rh288_peref'))."','$this->rh288_peref',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh288_vrbccpmensal"]) || $this->rh288_vrbccpmensal != "")
             $resac = db_query("insert into db_acount values($acount,1011101,1015185,'".AddSlashes(pg_result($resaco,$conresaco,'rh288_vrbccpmensal'))."','$this->rh288_vrbccpmensal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh288_vrbccp13"]) || $this->rh288_vrbccp13 != "")
             $resac = db_query("insert into db_acount values($acount,1011101,1015188,'".AddSlashes(pg_result($resaco,$conresaco,'rh288_vrbccp13'))."','$this->rh288_vrbccp13',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh288_vrrendirrf"]) || $this->rh288_vrrendirrf != "")
             $resac = db_query("insert into db_acount values($acount,1011101,1015189,'".AddSlashes(pg_result($resaco,$conresaco,'rh288_vrrendirrf'))."','$this->rh288_vrrendirrf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh288_vrrendirrf13"]) || $this->rh288_vrrendirrf13 != "")
             $resac = db_query("insert into db_acount values($acount,1011101,1015190,'".AddSlashes(pg_result($resaco,$conresaco,'rh288_vrrendirrf13'))."','$this->rh288_vrrendirrf13',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh288_pagamento"]) || $this->rh288_pagamento != "")
             $resac = db_query("insert into db_acount values($acount,1011101,1015234,'".AddSlashes(pg_result($resaco,$conresaco,'rh288_pagamento'))."','$this->rh288_pagamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh288_observacao"]) || $this->rh288_observacao != "")
             $resac = db_query("insert into db_acount values($acount,1011101,1015235,'".AddSlashes(pg_result($resaco,$conresaco,'rh288_observacao'))."','$this->rh288_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tributos de Processo não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh288_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tributos de Processo não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh288_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh288_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh288_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh288_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1015182,'$rh288_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1011101,1015182,'','".AddSlashes(pg_result($resaco,$iresaco,'rh288_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011101,1015183,'','".AddSlashes(pg_result($resaco,$iresaco,'rh288_sequencialprocessoservidor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011101,1015184,'','".AddSlashes(pg_result($resaco,$iresaco,'rh288_peref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011101,1015185,'','".AddSlashes(pg_result($resaco,$iresaco,'rh288_vrbccpmensal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011101,1015188,'','".AddSlashes(pg_result($resaco,$iresaco,'rh288_vrbccp13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011101,1015189,'','".AddSlashes(pg_result($resaco,$iresaco,'rh288_vrrendirrf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011101,1015190,'','".AddSlashes(pg_result($resaco,$iresaco,'rh288_vrrendirrf13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011101,1015234,'','".AddSlashes(pg_result($resaco,$iresaco,'rh288_pagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011101,1015235,'','".AddSlashes(pg_result($resaco,$iresaco,'rh288_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhprocessotributobase
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh288_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh288_sequencial = $rh288_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tributos de Processo não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh288_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tributos de Processo não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh288_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh288_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhprocessotributobase";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh288_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhprocessotributobase ";
     $sql .= "      inner join rhpessoalprocessoservidor  on  rhpessoalprocessoservidor.rh271_sequencial = rhprocessotributobase.rh288_sequencialprocessoservidor";
     $sql .= "      inner join rhpessoalprocessojudicialesocial  on  rhpessoalprocessojudicialesocial.rh270_sequencial = rhpessoalprocessoservidor.rh271_sequencialprocesso";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh288_sequencial)) {
         $sql2 .= " where rhprocessotributobase.rh288_sequencial = $rh288_sequencial "; 
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

    public function sql_query_file($rh288_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhprocessotributobase ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh288_sequencial)){
         $sql2 .= " where rhprocessotributobase.rh288_sequencial = $rh288_sequencial "; 
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
