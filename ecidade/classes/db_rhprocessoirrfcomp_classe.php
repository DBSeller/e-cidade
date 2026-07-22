<?php

class cl_rhprocessoirrfcomp
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
    public $rh310_sequencial = 0; 
    public $rh310_sequencialprocessoservidor = 0; 
    public $rh310_dtlaudo_dia = null; 
    public $rh310_dtlaudo_mes = null; 
    public $rh310_dtlaudo_ano = null; 
    public $rh310_dtlaudo = null; 
    public $rh310_cpfdep = null; 
    public $rh310_dtnascto_dia = null; 
    public $rh310_dtnascto_mes = null; 
    public $rh310_dtnascto_ano = null; 
    public $rh310_dtnascto = null; 
    public $rh310_nome = null; 
    public $rh310_depirrf = null; 
    public $rh310_tpdep = null; 
    public $rh310_descrdep = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh310_sequencial = int4 = Sequencial 
                 rh310_sequencialprocessoservidor = int4 = Sequencial vínculo servidor 
                 rh310_dtlaudo = date = Data laudo 
                 rh310_cpfdep = varchar(11) = CPF 
                 rh310_dtnascto = date = Data de nascimento 
                 rh310_nome = varchar(70) = Nome do dependente 
                 rh310_depirrf = varchar(1) = Dependente rendimento tributável 
                 rh310_tpdep = varchar(2) = Tipo de dependente 
                 rh310_descrdep = varchar(100) = Descrição da dependência 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhprocessoirrfcomp"); 
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
       $this->rh310_sequencial = ($this->rh310_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh310_sequencial"]:$this->rh310_sequencial);
       $this->rh310_sequencialprocessoservidor = ($this->rh310_sequencialprocessoservidor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh310_sequencialprocessoservidor"]:$this->rh310_sequencialprocessoservidor);
       if($this->rh310_dtlaudo == ""){
         $this->rh310_dtlaudo_dia = ($this->rh310_dtlaudo_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh310_dtlaudo_dia"]:$this->rh310_dtlaudo_dia);
         $this->rh310_dtlaudo_mes = ($this->rh310_dtlaudo_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh310_dtlaudo_mes"]:$this->rh310_dtlaudo_mes);
         $this->rh310_dtlaudo_ano = ($this->rh310_dtlaudo_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh310_dtlaudo_ano"]:$this->rh310_dtlaudo_ano);
         if($this->rh310_dtlaudo_dia != ""){
            $this->rh310_dtlaudo = $this->rh310_dtlaudo_ano."-".$this->rh310_dtlaudo_mes."-".$this->rh310_dtlaudo_dia;
         }
       }
       $this->rh310_cpfdep = ($this->rh310_cpfdep == ""?@$GLOBALS["HTTP_POST_VARS"]["rh310_cpfdep"]:$this->rh310_cpfdep);
       if($this->rh310_dtnascto == ""){
         $this->rh310_dtnascto_dia = ($this->rh310_dtnascto_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh310_dtnascto_dia"]:$this->rh310_dtnascto_dia);
         $this->rh310_dtnascto_mes = ($this->rh310_dtnascto_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh310_dtnascto_mes"]:$this->rh310_dtnascto_mes);
         $this->rh310_dtnascto_ano = ($this->rh310_dtnascto_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh310_dtnascto_ano"]:$this->rh310_dtnascto_ano);
         if($this->rh310_dtnascto_dia != ""){
            $this->rh310_dtnascto = $this->rh310_dtnascto_ano."-".$this->rh310_dtnascto_mes."-".$this->rh310_dtnascto_dia;
         }
       }
       $this->rh310_nome = ($this->rh310_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["rh310_nome"]:$this->rh310_nome);
       $this->rh310_depirrf = ($this->rh310_depirrf == ""?@$GLOBALS["HTTP_POST_VARS"]["rh310_depirrf"]:$this->rh310_depirrf);
       $this->rh310_tpdep = ($this->rh310_tpdep == ""?@$GLOBALS["HTTP_POST_VARS"]["rh310_tpdep"]:$this->rh310_tpdep);
       $this->rh310_descrdep = ($this->rh310_descrdep == ""?@$GLOBALS["HTTP_POST_VARS"]["rh310_descrdep"]:$this->rh310_descrdep);
     }else{
       $this->rh310_sequencial = ($this->rh310_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh310_sequencial"]:$this->rh310_sequencial);
     }
   }

    public function incluir($rh310_sequencial)
    {
      $this->atualizacampos();
     if($this->rh310_sequencialprocessoservidor == null ){ 
       $this->erro_sql = " Campo Sequencial vínculo servidor não informado.";
       $this->erro_campo = "rh310_sequencialprocessoservidor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh310_dtlaudo == null ){ 
       $this->rh310_dtlaudo = "null";
     }
     if($this->rh310_dtnascto == null ){ 
       $this->rh310_dtnascto = "null";
     }
     if($rh310_sequencial == "" || $rh310_sequencial == null ){
       $result = db_query("select nextval('rhprocessoirrfcomp_rh310_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhprocessoirrfcomp_rh310_sequencial_seq do campo: rh310_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh310_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhprocessoirrfcomp_rh310_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh310_sequencial)){
         $this->erro_sql = " Campo rh310_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh310_sequencial = $rh310_sequencial; 
       }
     }
     if(($this->rh310_sequencial == null) || ($this->rh310_sequencial == "") ){ 
       $this->erro_sql = " Campo rh310_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhprocessoirrfcomp(
                                       rh310_sequencial 
                                      ,rh310_sequencialprocessoservidor 
                                      ,rh310_dtlaudo 
                                      ,rh310_cpfdep 
                                      ,rh310_dtnascto 
                                      ,rh310_nome 
                                      ,rh310_depirrf 
                                      ,rh310_tpdep 
                                      ,rh310_descrdep 
                       )
                values (
                                $this->rh310_sequencial 
                               ,$this->rh310_sequencialprocessoservidor 
                               ,".($this->rh310_dtlaudo == "null" || $this->rh310_dtlaudo == ""?"null":"'".$this->rh310_dtlaudo."'")." 
                               ,'$this->rh310_cpfdep' 
                               ,".($this->rh310_dtnascto == "null" || $this->rh310_dtnascto == ""?"null":"'".$this->rh310_dtnascto."'")." 
                               ,'$this->rh310_nome' 
                               ,'$this->rh310_depirrf' 
                               ,'$this->rh310_tpdep' 
                               ,'$this->rh310_descrdep' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "IRRF complementar ($this->rh310_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "IRRF complementar já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "IRRF complementar ($this->rh310_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh310_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh310_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1015455,'$this->rh310_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1011147,1015455,'','".AddSlashes(pg_result($resaco,0,'rh310_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011147,1015456,'','".AddSlashes(pg_result($resaco,0,'rh310_sequencialprocessoservidor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011147,1015457,'','".AddSlashes(pg_result($resaco,0,'rh310_dtlaudo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011147,1015458,'','".AddSlashes(pg_result($resaco,0,'rh310_cpfdep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011147,1015459,'','".AddSlashes(pg_result($resaco,0,'rh310_dtnascto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011147,1015460,'','".AddSlashes(pg_result($resaco,0,'rh310_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011147,1015461,'','".AddSlashes(pg_result($resaco,0,'rh310_depirrf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011147,1015462,'','".AddSlashes(pg_result($resaco,0,'rh310_tpdep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011147,1015463,'','".AddSlashes(pg_result($resaco,0,'rh310_descrdep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh310_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhprocessoirrfcomp set ";
     $virgula = "";
     if(trim($this->rh310_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh310_sequencial"])){ 
       $sql  .= $virgula." rh310_sequencial = $this->rh310_sequencial ";
       $virgula = ",";
       if(trim($this->rh310_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh310_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh310_sequencialprocessoservidor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh310_sequencialprocessoservidor"])){ 
       $sql  .= $virgula." rh310_sequencialprocessoservidor = $this->rh310_sequencialprocessoservidor ";
       $virgula = ",";
       if(trim($this->rh310_sequencialprocessoservidor) == null ){ 
         $this->erro_sql = " Campo Sequencial vínculo servidor não informado.";
         $this->erro_campo = "rh310_sequencialprocessoservidor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh310_dtlaudo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh310_dtlaudo_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh310_dtlaudo_dia"] !="") ){ 
       $sql  .= $virgula." rh310_dtlaudo = '$this->rh310_dtlaudo' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh310_dtlaudo_dia"])){ 
         $sql  .= $virgula." rh310_dtlaudo = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh310_cpfdep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh310_cpfdep"])){ 
       $sql  .= $virgula." rh310_cpfdep = '$this->rh310_cpfdep' ";
       $virgula = ",";
     }
     if(trim($this->rh310_dtnascto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh310_dtnascto_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh310_dtnascto_dia"] !="") ){ 
       $sql  .= $virgula." rh310_dtnascto = '$this->rh310_dtnascto' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh310_dtnascto_dia"])){ 
         $sql  .= $virgula." rh310_dtnascto = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh310_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh310_nome"])){ 
       $sql  .= $virgula." rh310_nome = '$this->rh310_nome' ";
       $virgula = ",";
     }
     if(trim($this->rh310_depirrf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh310_depirrf"])){ 
       $sql  .= $virgula." rh310_depirrf = '$this->rh310_depirrf' ";
       $virgula = ",";
     }
     if(trim($this->rh310_tpdep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh310_tpdep"])){ 
       $sql  .= $virgula." rh310_tpdep = '$this->rh310_tpdep' ";
       $virgula = ",";
     }
     if(trim($this->rh310_descrdep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh310_descrdep"])){ 
       $sql  .= $virgula." rh310_descrdep = '$this->rh310_descrdep' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh310_sequencial!=null){
       $sql .= " rh310_sequencial = $this->rh310_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh310_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1015455,'$this->rh310_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh310_sequencial"]) || $this->rh310_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1011147,1015455,'".AddSlashes(pg_result($resaco,$conresaco,'rh310_sequencial'))."','$this->rh310_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh310_sequencialprocessoservidor"]) || $this->rh310_sequencialprocessoservidor != "")
             $resac = db_query("insert into db_acount values($acount,1011147,1015456,'".AddSlashes(pg_result($resaco,$conresaco,'rh310_sequencialprocessoservidor'))."','$this->rh310_sequencialprocessoservidor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh310_dtlaudo"]) || $this->rh310_dtlaudo != "")
             $resac = db_query("insert into db_acount values($acount,1011147,1015457,'".AddSlashes(pg_result($resaco,$conresaco,'rh310_dtlaudo'))."','$this->rh310_dtlaudo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh310_cpfdep"]) || $this->rh310_cpfdep != "")
             $resac = db_query("insert into db_acount values($acount,1011147,1015458,'".AddSlashes(pg_result($resaco,$conresaco,'rh310_cpfdep'))."','$this->rh310_cpfdep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh310_dtnascto"]) || $this->rh310_dtnascto != "")
             $resac = db_query("insert into db_acount values($acount,1011147,1015459,'".AddSlashes(pg_result($resaco,$conresaco,'rh310_dtnascto'))."','$this->rh310_dtnascto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh310_nome"]) || $this->rh310_nome != "")
             $resac = db_query("insert into db_acount values($acount,1011147,1015460,'".AddSlashes(pg_result($resaco,$conresaco,'rh310_nome'))."','$this->rh310_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh310_depirrf"]) || $this->rh310_depirrf != "")
             $resac = db_query("insert into db_acount values($acount,1011147,1015461,'".AddSlashes(pg_result($resaco,$conresaco,'rh310_depirrf'))."','$this->rh310_depirrf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh310_tpdep"]) || $this->rh310_tpdep != "")
             $resac = db_query("insert into db_acount values($acount,1011147,1015462,'".AddSlashes(pg_result($resaco,$conresaco,'rh310_tpdep'))."','$this->rh310_tpdep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh310_descrdep"]) || $this->rh310_descrdep != "")
             $resac = db_query("insert into db_acount values($acount,1011147,1015463,'".AddSlashes(pg_result($resaco,$conresaco,'rh310_descrdep'))."','$this->rh310_descrdep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "IRRF complementar não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh310_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "IRRF complementar não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh310_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh310_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh310_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh310_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1015455,'$rh310_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1011147,1015455,'','".AddSlashes(pg_result($resaco,$iresaco,'rh310_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011147,1015456,'','".AddSlashes(pg_result($resaco,$iresaco,'rh310_sequencialprocessoservidor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011147,1015457,'','".AddSlashes(pg_result($resaco,$iresaco,'rh310_dtlaudo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011147,1015458,'','".AddSlashes(pg_result($resaco,$iresaco,'rh310_cpfdep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011147,1015459,'','".AddSlashes(pg_result($resaco,$iresaco,'rh310_dtnascto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011147,1015460,'','".AddSlashes(pg_result($resaco,$iresaco,'rh310_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011147,1015461,'','".AddSlashes(pg_result($resaco,$iresaco,'rh310_depirrf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011147,1015462,'','".AddSlashes(pg_result($resaco,$iresaco,'rh310_tpdep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011147,1015463,'','".AddSlashes(pg_result($resaco,$iresaco,'rh310_descrdep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhprocessoirrfcomp
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh310_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh310_sequencial = $rh310_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "IRRF complementar não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh310_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "IRRF complementar não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh310_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh310_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhprocessoirrfcomp";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh310_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhprocessoirrfcomp ";
     $sql .= "      inner join rhpessoalprocessoservidor  on  rhpessoalprocessoservidor.rh271_sequencial = rhprocessoirrfcomp.rh310_sequencialprocessoservidor";
     $sql .= "      inner join rhpessoalprocessojudicialesocial  on  rhpessoalprocessojudicialesocial.rh270_sequencial = rhpessoalprocessoservidor.rh271_sequencialprocesso";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh310_sequencial)) {
         $sql2 .= " where rhprocessoirrfcomp.rh310_sequencial = $rh310_sequencial "; 
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

    public function sql_query_file($rh310_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhprocessoirrfcomp ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh310_sequencial)){
         $sql2 .= " where rhprocessoirrfcomp.rh310_sequencial = $rh310_sequencial "; 
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
