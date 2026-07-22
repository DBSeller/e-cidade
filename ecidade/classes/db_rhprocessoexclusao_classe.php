<?php

class cl_rhprocessoexclusao
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
    public $rh300_sequencial = 0; 
    public $rh300_sequencialprocessoservidor = 0; 
    public $rh300_tpevento = null; 
    public $rh300_nrrecevt = null; 
    public $rh300_nrproctrab = null; 
    public $rh300_cpftrab = null; 
    public $rh300_perapurpgto = null; 
    public $rh300_dataexclusao_dia = null; 
    public $rh300_dataexclusao_mes = null; 
    public $rh300_dataexclusao_ano = null; 
    public $rh300_dataexclusao = null; 
    public $rh300_referencia = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh300_sequencial = int4 = Número Sequencial 
                 rh300_sequencialprocessoservidor = int4 = Código referente servidor 
                 rh300_tpevento = varchar(6) = Tipo de evento 
                 rh300_nrrecevt = varchar(23) = Número do recibo 
                 rh300_nrproctrab = varchar(20) = Número do Processo Trabalhista 
                 rh300_cpftrab = varchar(11) = CPF 
                 rh300_perapurpgto = varchar(7) = Pagamento 
                 rh300_dataexclusao = date = Data Exclusão 
                 rh300_referencia = varchar(255) = Referência 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhprocessoexclusao"); 
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
       $this->rh300_sequencial = ($this->rh300_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh300_sequencial"]:$this->rh300_sequencial);
       $this->rh300_sequencialprocessoservidor = ($this->rh300_sequencialprocessoservidor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh300_sequencialprocessoservidor"]:$this->rh300_sequencialprocessoservidor);
       $this->rh300_tpevento = ($this->rh300_tpevento == ""?@$GLOBALS["HTTP_POST_VARS"]["rh300_tpevento"]:$this->rh300_tpevento);
       $this->rh300_nrrecevt = ($this->rh300_nrrecevt == ""?@$GLOBALS["HTTP_POST_VARS"]["rh300_nrrecevt"]:$this->rh300_nrrecevt);
       $this->rh300_nrproctrab = ($this->rh300_nrproctrab == ""?@$GLOBALS["HTTP_POST_VARS"]["rh300_nrproctrab"]:$this->rh300_nrproctrab);
       $this->rh300_cpftrab = ($this->rh300_cpftrab == ""?@$GLOBALS["HTTP_POST_VARS"]["rh300_cpftrab"]:$this->rh300_cpftrab);
       $this->rh300_perapurpgto = ($this->rh300_perapurpgto == ""?@$GLOBALS["HTTP_POST_VARS"]["rh300_perapurpgto"]:$this->rh300_perapurpgto);
       if($this->rh300_dataexclusao == ""){
         $this->rh300_dataexclusao_dia = ($this->rh300_dataexclusao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh300_dataexclusao_dia"]:$this->rh300_dataexclusao_dia);
         $this->rh300_dataexclusao_mes = ($this->rh300_dataexclusao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh300_dataexclusao_mes"]:$this->rh300_dataexclusao_mes);
         $this->rh300_dataexclusao_ano = ($this->rh300_dataexclusao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh300_dataexclusao_ano"]:$this->rh300_dataexclusao_ano);
         if($this->rh300_dataexclusao_dia != ""){
            $this->rh300_dataexclusao = $this->rh300_dataexclusao_ano."-".$this->rh300_dataexclusao_mes."-".$this->rh300_dataexclusao_dia;
         }
       }
       $this->rh300_referencia = ($this->rh300_referencia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh300_referencia"]:$this->rh300_referencia);
     }else{
       $this->rh300_sequencial = ($this->rh300_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh300_sequencial"]:$this->rh300_sequencial);
     }
   }

    public function incluir($rh300_sequencial)
    {
      $this->atualizacampos();
     if($this->rh300_sequencialprocessoservidor == null ){ 
       $this->erro_sql = " Campo Código referente servidor não informado.";
       $this->erro_campo = "rh300_sequencialprocessoservidor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh300_dataexclusao == null ){ 
       $this->erro_sql = " Campo Data Exclusão não informado.";
       $this->erro_campo = "rh300_dataexclusao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh300_sequencial == "" || $rh300_sequencial == null ){
       $result = db_query("select nextval('rhprocessoexclusao_rh300_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhprocessoexclusao_rh300_sequencial_seq do campo: rh300_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh300_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhprocessoexclusao_rh300_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh300_sequencial)){
         $this->erro_sql = " Campo rh300_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh300_sequencial = $rh300_sequencial; 
       }
     }
     if(($this->rh300_sequencial == null) || ($this->rh300_sequencial == "") ){ 
       $this->erro_sql = " Campo rh300_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhprocessoexclusao(
                                       rh300_sequencial 
                                      ,rh300_sequencialprocessoservidor 
                                      ,rh300_tpevento 
                                      ,rh300_nrrecevt 
                                      ,rh300_nrproctrab 
                                      ,rh300_cpftrab 
                                      ,rh300_perapurpgto 
                                      ,rh300_dataexclusao 
                                      ,rh300_referencia 
                       )
                values (
                                $this->rh300_sequencial 
                               ,$this->rh300_sequencialprocessoservidor 
                               ,'$this->rh300_tpevento' 
                               ,'$this->rh300_nrrecevt' 
                               ,'$this->rh300_nrproctrab' 
                               ,'$this->rh300_cpftrab' 
                               ,'$this->rh300_perapurpgto' 
                               ,".($this->rh300_dataexclusao == "null" || $this->rh300_dataexclusao == ""?"null":"'".$this->rh300_dataexclusao."'")." 
                               ,'$this->rh300_referencia' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Exclusão processos judiciais ($this->rh300_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Exclusão processos judiciais já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Exclusão processos judiciais ($this->rh300_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh300_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh300_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1015306,'$this->rh300_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1011128,1015306,'','".AddSlashes(pg_result($resaco,0,'rh300_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011128,1015307,'','".AddSlashes(pg_result($resaco,0,'rh300_sequencialprocessoservidor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011128,1015308,'','".AddSlashes(pg_result($resaco,0,'rh300_tpevento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011128,1015309,'','".AddSlashes(pg_result($resaco,0,'rh300_nrrecevt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011128,1015310,'','".AddSlashes(pg_result($resaco,0,'rh300_nrproctrab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011128,1015311,'','".AddSlashes(pg_result($resaco,0,'rh300_cpftrab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011128,1015312,'','".AddSlashes(pg_result($resaco,0,'rh300_perapurpgto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011128,1015333,'','".AddSlashes(pg_result($resaco,0,'rh300_dataexclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011128,1015337,'','".AddSlashes(pg_result($resaco,0,'rh300_referencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh300_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhprocessoexclusao set ";
     $virgula = "";
     if(trim($this->rh300_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh300_sequencial"])){ 
       $sql  .= $virgula." rh300_sequencial = $this->rh300_sequencial ";
       $virgula = ",";
       if(trim($this->rh300_sequencial) == null ){ 
         $this->erro_sql = " Campo Número Sequencial não informado.";
         $this->erro_campo = "rh300_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh300_sequencialprocessoservidor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh300_sequencialprocessoservidor"])){ 
       $sql  .= $virgula." rh300_sequencialprocessoservidor = $this->rh300_sequencialprocessoservidor ";
       $virgula = ",";
       if(trim($this->rh300_sequencialprocessoservidor) == null ){ 
         $this->erro_sql = " Campo Código referente servidor não informado.";
         $this->erro_campo = "rh300_sequencialprocessoservidor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh300_tpevento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh300_tpevento"])){ 
       $sql  .= $virgula." rh300_tpevento = '$this->rh300_tpevento' ";
       $virgula = ",";
     }
     if(trim($this->rh300_nrrecevt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh300_nrrecevt"])){ 
       $sql  .= $virgula." rh300_nrrecevt = '$this->rh300_nrrecevt' ";
       $virgula = ",";
     }
     if(trim($this->rh300_nrproctrab)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh300_nrproctrab"])){ 
       $sql  .= $virgula." rh300_nrproctrab = '$this->rh300_nrproctrab' ";
       $virgula = ",";
     }
     if(trim($this->rh300_cpftrab)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh300_cpftrab"])){ 
       $sql  .= $virgula." rh300_cpftrab = '$this->rh300_cpftrab' ";
       $virgula = ",";
     }
     if(trim($this->rh300_perapurpgto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh300_perapurpgto"])){ 
       $sql  .= $virgula." rh300_perapurpgto = '$this->rh300_perapurpgto' ";
       $virgula = ",";
     }
     if(trim($this->rh300_dataexclusao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh300_dataexclusao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh300_dataexclusao_dia"] !="") ){ 
       $sql  .= $virgula." rh300_dataexclusao = '$this->rh300_dataexclusao' ";
       $virgula = ",";
       if(trim($this->rh300_dataexclusao) == null ){ 
         $this->erro_sql = " Campo Data Exclusão não informado.";
         $this->erro_campo = "rh300_dataexclusao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh300_dataexclusao_dia"])){ 
         $sql  .= $virgula." rh300_dataexclusao = null ";
         $virgula = ",";
         if(trim($this->rh300_dataexclusao) == null ){ 
           $this->erro_sql = " Campo Data Exclusão não informado.";
           $this->erro_campo = "rh300_dataexclusao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh300_referencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh300_referencia"])){ 
       $sql  .= $virgula." rh300_referencia = '$this->rh300_referencia' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh300_sequencial!=null){
       $sql .= " rh300_sequencial = $this->rh300_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh300_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1015306,'$this->rh300_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh300_sequencial"]) || $this->rh300_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1011128,1015306,'".AddSlashes(pg_result($resaco,$conresaco,'rh300_sequencial'))."','$this->rh300_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh300_sequencialprocessoservidor"]) || $this->rh300_sequencialprocessoservidor != "")
             $resac = db_query("insert into db_acount values($acount,1011128,1015307,'".AddSlashes(pg_result($resaco,$conresaco,'rh300_sequencialprocessoservidor'))."','$this->rh300_sequencialprocessoservidor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh300_tpevento"]) || $this->rh300_tpevento != "")
             $resac = db_query("insert into db_acount values($acount,1011128,1015308,'".AddSlashes(pg_result($resaco,$conresaco,'rh300_tpevento'))."','$this->rh300_tpevento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh300_nrrecevt"]) || $this->rh300_nrrecevt != "")
             $resac = db_query("insert into db_acount values($acount,1011128,1015309,'".AddSlashes(pg_result($resaco,$conresaco,'rh300_nrrecevt'))."','$this->rh300_nrrecevt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh300_nrproctrab"]) || $this->rh300_nrproctrab != "")
             $resac = db_query("insert into db_acount values($acount,1011128,1015310,'".AddSlashes(pg_result($resaco,$conresaco,'rh300_nrproctrab'))."','$this->rh300_nrproctrab',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh300_cpftrab"]) || $this->rh300_cpftrab != "")
             $resac = db_query("insert into db_acount values($acount,1011128,1015311,'".AddSlashes(pg_result($resaco,$conresaco,'rh300_cpftrab'))."','$this->rh300_cpftrab',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh300_perapurpgto"]) || $this->rh300_perapurpgto != "")
             $resac = db_query("insert into db_acount values($acount,1011128,1015312,'".AddSlashes(pg_result($resaco,$conresaco,'rh300_perapurpgto'))."','$this->rh300_perapurpgto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh300_dataexclusao"]) || $this->rh300_dataexclusao != "")
             $resac = db_query("insert into db_acount values($acount,1011128,1015333,'".AddSlashes(pg_result($resaco,$conresaco,'rh300_dataexclusao'))."','$this->rh300_dataexclusao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh300_referencia"]) || $this->rh300_referencia != "")
             $resac = db_query("insert into db_acount values($acount,1011128,1015337,'".AddSlashes(pg_result($resaco,$conresaco,'rh300_referencia'))."','$this->rh300_referencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Exclusão processos judiciais não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh300_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão processos judiciais não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh300_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh300_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh300_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh300_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1015306,'$rh300_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1011128,1015306,'','".AddSlashes(pg_result($resaco,$iresaco,'rh300_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011128,1015307,'','".AddSlashes(pg_result($resaco,$iresaco,'rh300_sequencialprocessoservidor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011128,1015308,'','".AddSlashes(pg_result($resaco,$iresaco,'rh300_tpevento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011128,1015309,'','".AddSlashes(pg_result($resaco,$iresaco,'rh300_nrrecevt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011128,1015310,'','".AddSlashes(pg_result($resaco,$iresaco,'rh300_nrproctrab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011128,1015311,'','".AddSlashes(pg_result($resaco,$iresaco,'rh300_cpftrab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011128,1015312,'','".AddSlashes(pg_result($resaco,$iresaco,'rh300_perapurpgto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011128,1015333,'','".AddSlashes(pg_result($resaco,$iresaco,'rh300_dataexclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011128,1015337,'','".AddSlashes(pg_result($resaco,$iresaco,'rh300_referencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhprocessoexclusao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh300_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh300_sequencial = $rh300_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Exclusão processos judiciais não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh300_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão processos judiciais não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh300_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh300_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhprocessoexclusao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh300_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhprocessoexclusao ";
     $sql .= "      inner join rhpessoalprocessoservidor  on  rhpessoalprocessoservidor.rh271_sequencial = rhprocessoexclusao.rh300_sequencialprocessoservidor";
     $sql .= "      inner join rhpessoalprocessojudicialesocial  on  rhpessoalprocessojudicialesocial.rh270_sequencial = rhpessoalprocessoservidor.rh271_sequencialprocesso";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh300_sequencial)) {
         $sql2 .= " where rhprocessoexclusao.rh300_sequencial = $rh300_sequencial "; 
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

    public function sql_query_file($rh300_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhprocessoexclusao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh300_sequencial)){
         $sql2 .= " where rhprocessoexclusao.rh300_sequencial = $rh300_sequencial "; 
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
