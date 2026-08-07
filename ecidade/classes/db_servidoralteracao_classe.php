<?php

class cl_servidoralteracao
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
    public $eso38_sequencial = 0;
    public $eso38_matricula = 0;
    public $eso38_s2205data_dia = null;
    public $eso38_s2205data_mes = null;
    public $eso38_s2205data_ano = null;
    public $eso38_s2205data = null;
    public $eso38_s2205processado = 'f';
    public $eso38_s2206data_dia = null;
    public $eso38_s2206data_mes = null;
    public $eso38_s2206data_ano = null;
    public $eso38_s2206data = null;
    public $eso38_s2206processado = 'f';
    public $eso38_s2306data_dia = null;
    public $eso38_s2306data_mes = null;
    public $eso38_s2306data_ano = null;
    public $eso38_s2306data = null;
    public $eso38_s2306processado = 'f';
    public $eso38_s2405data_dia = null;
    public $eso38_s2405data_mes = null;
    public $eso38_s2405data_ano = null;
    public $eso38_s2405data = null;
    public $eso38_s2405processado = 'f';
    public $eso38_s2416data_dia = null;
    public $eso38_s2416data_mes = null;
    public $eso38_s2416data_ano = null;
    public $eso38_s2416data = null;
    public $eso38_s2416processado = 'f';
   // cria propriedade com as variaveis do arquivo
    public $campos = "
                 eso38_sequencial = int4 = Código Sequencial
                 eso38_matricula = int4 = Matrícula
                 eso38_s2205data = date = Data S2205
                 eso38_s2205processado = bool = Processamento S2205
                 eso38_s2206data = date = Data S2206
                 eso38_s2206processado = bool = Processamento S2206
                 eso38_s2306data = date = Data S2306
                 eso38_s2306processado = bool = Processamento S2306
                 eso38_s2405data = date = Data S2405
                 eso38_s2405processado = bool = Processamento S2405
                 eso38_s2416data = date = Data S2416
                 eso38_s2416processado = bool = Processamento S2416
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("servidoralteracao");
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
       $this->eso38_sequencial = ($this->eso38_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["eso38_sequencial"]:$this->eso38_sequencial);
       $this->eso38_matricula = ($this->eso38_matricula == ""?@$GLOBALS["HTTP_POST_VARS"]["eso38_matricula"]:$this->eso38_matricula);
       if($this->eso38_s2205data == ""){
         $this->eso38_s2205data_dia = ($this->eso38_s2205data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["eso38_s2205data_dia"]:$this->eso38_s2205data_dia);
         $this->eso38_s2205data_mes = ($this->eso38_s2205data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["eso38_s2205data_mes"]:$this->eso38_s2205data_mes);
         $this->eso38_s2205data_ano = ($this->eso38_s2205data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["eso38_s2205data_ano"]:$this->eso38_s2205data_ano);
         if($this->eso38_s2205data_dia != ""){
            $this->eso38_s2205data = $this->eso38_s2205data_ano."-".$this->eso38_s2205data_mes."-".$this->eso38_s2205data_dia;
         }
       }
       if($this->eso38_s2206data == ""){
         $this->eso38_s2206data_dia = ($this->eso38_s2206data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["eso38_s2206data_dia"]:$this->eso38_s2206data_dia);
         $this->eso38_s2206data_mes = ($this->eso38_s2206data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["eso38_s2206data_mes"]:$this->eso38_s2206data_mes);
         $this->eso38_s2206data_ano = ($this->eso38_s2206data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["eso38_s2206data_ano"]:$this->eso38_s2206data_ano);
         if($this->eso38_s2206data_dia != ""){
            $this->eso38_s2206data = $this->eso38_s2206data_ano."-".$this->eso38_s2206data_mes."-".$this->eso38_s2206data_dia;
         }
       }
       if($this->eso38_s2306data == ""){
         $this->eso38_s2306data_dia = ($this->eso38_s2306data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["eso38_s2306data_dia"]:$this->eso38_s2306data_dia);
         $this->eso38_s2306data_mes = ($this->eso38_s2306data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["eso38_s2306data_mes"]:$this->eso38_s2306data_mes);
         $this->eso38_s2306data_ano = ($this->eso38_s2306data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["eso38_s2306data_ano"]:$this->eso38_s2306data_ano);
         if($this->eso38_s2306data_dia != ""){
            $this->eso38_s2306data = $this->eso38_s2306data_ano."-".$this->eso38_s2306data_mes."-".$this->eso38_s2306data_dia;
         }
       }
       if($this->eso38_s2405data == ""){
         $this->eso38_s2405data_dia = ($this->eso38_s2405data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["eso38_s2405data_dia"]:$this->eso38_s2405data_dia);
         $this->eso38_s2405data_mes = ($this->eso38_s2405data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["eso38_s2405data_mes"]:$this->eso38_s2405data_mes);
         $this->eso38_s2405data_ano = ($this->eso38_s2405data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["eso38_s2405data_ano"]:$this->eso38_s2405data_ano);
         if($this->eso38_s2405data_dia != ""){
            $this->eso38_s2405data = $this->eso38_s2405data_ano."-".$this->eso38_s2405data_mes."-".$this->eso38_s2405data_dia;
         }
       }
       if($this->eso38_s2416data == ""){
         $this->eso38_s2416data_dia = ($this->eso38_s2416data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["eso38_s2416data_dia"]:$this->eso38_s2416data_dia);
         $this->eso38_s2416data_mes = ($this->eso38_s2416data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["eso38_s2416data_mes"]:$this->eso38_s2416data_mes);
         $this->eso38_s2416data_ano = ($this->eso38_s2416data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["eso38_s2416data_ano"]:$this->eso38_s2416data_ano);
         if($this->eso38_s2416data_dia != ""){
            $this->eso38_s2416data = $this->eso38_s2416data_ano."-".$this->eso38_s2416data_mes."-".$this->eso38_s2416data_dia;
         }
       }
     }else{
       $this->eso38_sequencial = ($this->eso38_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["eso38_sequencial"]:$this->eso38_sequencial);
     }
   }

    public function incluir($eso38_sequencial)
    {
      $this->atualizacampos();
     if($this->eso38_matricula == null ){
       $this->erro_sql = " Campo Matrícula não informado.";
       $this->erro_campo = "eso38_matricula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->eso38_s2205data == null ){
       $this->eso38_s2205data = "null";
     }
     if($this->eso38_s2205processado == null ){
       $this->erro_sql = " Campo Processamento S2205 não informado.";
       $this->erro_campo = "eso38_s2205processado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->eso38_s2206data == null ){
       $this->eso38_s2206data = "null";
     }
     if($this->eso38_s2206processado == null ){
       $this->erro_sql = " Campo Processamento S2206 não informado.";
       $this->erro_campo = "eso38_s2206processado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->eso38_s2306data == null ){
       $this->eso38_s2306data = "null";
     }
     if($this->eso38_s2306processado == null ){
       $this->erro_sql = " Campo Processamento S2306 não informado.";
       $this->erro_campo = "eso38_s2306processado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->eso38_s2405data == null ){
       $this->eso38_s2405data = "null";
     }
     if($this->eso38_s2405processado == null ){
       $this->erro_sql = " Campo Processamento S2405 não informado.";
       $this->erro_campo = "eso38_s2405processado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->eso38_s2416data == null ){
       $this->eso38_s2416data = "null";
     }
     if($this->eso38_s2416processado == null ){
       $this->erro_sql = " Campo Processamento S2416 não informado.";
       $this->erro_campo = "eso38_s2416processado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($eso38_sequencial == "" || $eso38_sequencial == null ){
       $result = db_query("select nextval('servidoralteracao_eso38_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: servidoralteracao_eso38_sequencial_seq do campo: eso38_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->eso38_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from servidoralteracao_eso38_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $eso38_sequencial)){
         $this->erro_sql = " Campo eso38_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->eso38_sequencial = $eso38_sequencial;
       }
     }
     if(($this->eso38_sequencial == null) || ($this->eso38_sequencial == "") ){
       $this->erro_sql = " Campo eso38_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into servidoralteracao(
                                       eso38_sequencial
                                      ,eso38_matricula
                                      ,eso38_s2205data
                                      ,eso38_s2205processado
                                      ,eso38_s2206data
                                      ,eso38_s2206processado
                                      ,eso38_s2306data
                                      ,eso38_s2306processado
                                      ,eso38_s2405data
                                      ,eso38_s2405processado
                                      ,eso38_s2416data
                                      ,eso38_s2416processado
                       )
                values (
                                $this->eso38_sequencial
                               ,$this->eso38_matricula
                               ,".($this->eso38_s2205data == "null" || $this->eso38_s2205data == ""?"null":"'".$this->eso38_s2205data."'")."
                               ,'$this->eso38_s2205processado'
                               ,".($this->eso38_s2206data == "null" || $this->eso38_s2206data == ""?"null":"'".$this->eso38_s2206data."'")."
                               ,'$this->eso38_s2206processado'
                               ,".($this->eso38_s2306data == "null" || $this->eso38_s2306data == ""?"null":"'".$this->eso38_s2306data."'")."
                               ,'$this->eso38_s2306processado'
                               ,".($this->eso38_s2405data == "null" || $this->eso38_s2405data == ""?"null":"'".$this->eso38_s2405data."'")."
                               ,'$this->eso38_s2405processado'
                               ,".($this->eso38_s2416data == "null" || $this->eso38_s2416data == ""?"null":"'".$this->eso38_s2416data."'")."
                               ,'$this->eso38_s2416processado'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Alterações do Servidor ($this->eso38_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Alterações do Servidor já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Alterações do Servidor ($this->eso38_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->eso38_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->eso38_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1014178,'$this->eso38_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010939,1014178,'','".AddSlashes(pg_result($resaco,0,'eso38_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010939,1014189,'','".AddSlashes(pg_result($resaco,0,'eso38_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010939,1014179,'','".AddSlashes(pg_result($resaco,0,'eso38_s2205data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010939,1014180,'','".AddSlashes(pg_result($resaco,0,'eso38_s2205processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010939,1014181,'','".AddSlashes(pg_result($resaco,0,'eso38_s2206data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010939,1014182,'','".AddSlashes(pg_result($resaco,0,'eso38_s2206processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010939,1014183,'','".AddSlashes(pg_result($resaco,0,'eso38_s2306data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010939,1014184,'','".AddSlashes(pg_result($resaco,0,'eso38_s2306processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010939,1014185,'','".AddSlashes(pg_result($resaco,0,'eso38_s2405data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010939,1014186,'','".AddSlashes(pg_result($resaco,0,'eso38_s2405processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010939,1014187,'','".AddSlashes(pg_result($resaco,0,'eso38_s2416data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010939,1014188,'','".AddSlashes(pg_result($resaco,0,'eso38_s2416processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }

    public function alterar($eso38_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update servidoralteracao set ";
     $virgula = "";
     if(trim($this->eso38_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso38_sequencial"])){
       $sql  .= $virgula." eso38_sequencial = $this->eso38_sequencial ";
       $virgula = ",";
       if(trim($this->eso38_sequencial) == null ){
         $this->erro_sql = " Campo Código Sequencial não informado.";
         $this->erro_campo = "eso38_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->eso38_matricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso38_matricula"])){
       $sql  .= $virgula." eso38_matricula = $this->eso38_matricula ";
       $virgula = ",";
       if(trim($this->eso38_matricula) == null ){
         $this->erro_sql = " Campo Matrícula não informado.";
         $this->erro_campo = "eso38_matricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->eso38_s2205data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso38_s2205data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["eso38_s2205data_dia"] !="") ){
       $sql  .= $virgula." eso38_s2205data = '$this->eso38_s2205data' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["eso38_s2205data_dia"])){
         $sql  .= $virgula." eso38_s2205data = null ";
         $virgula = ",";
       }
     }
     if(trim($this->eso38_s2205processado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso38_s2205processado"])){
       $sql  .= $virgula." eso38_s2205processado = '$this->eso38_s2205processado' ";
       $virgula = ",";
       if(trim($this->eso38_s2205processado) == null ){
         $this->erro_sql = " Campo Processamento S2205 não informado.";
         $this->erro_campo = "eso38_s2205processado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->eso38_s2206data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso38_s2206data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["eso38_s2206data_dia"] !="") ){
       $sql  .= $virgula." eso38_s2206data = '$this->eso38_s2206data' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["eso38_s2206data_dia"])){
         $sql  .= $virgula." eso38_s2206data = null ";
         $virgula = ",";
       }
     }
     if(trim($this->eso38_s2206processado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso38_s2206processado"])){
       $sql  .= $virgula." eso38_s2206processado = '$this->eso38_s2206processado' ";
       $virgula = ",";
       if(trim($this->eso38_s2206processado) == null ){
         $this->erro_sql = " Campo Processamento S2206 não informado.";
         $this->erro_campo = "eso38_s2206processado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->eso38_s2306data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso38_s2306data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["eso38_s2306data_dia"] !="") ){
       $sql  .= $virgula." eso38_s2306data = '$this->eso38_s2306data' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["eso38_s2306data_dia"])){
         $sql  .= $virgula." eso38_s2306data = null ";
         $virgula = ",";
       }
     }
     if(trim($this->eso38_s2306processado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso38_s2306processado"])){
       $sql  .= $virgula." eso38_s2306processado = '$this->eso38_s2306processado' ";
       $virgula = ",";
       if(trim($this->eso38_s2306processado) == null ){
         $this->erro_sql = " Campo Processamento S2306 não informado.";
         $this->erro_campo = "eso38_s2306processado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->eso38_s2405data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso38_s2405data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["eso38_s2405data_dia"] !="") ){
       $sql  .= $virgula." eso38_s2405data = '$this->eso38_s2405data' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["eso38_s2405data_dia"])){
         $sql  .= $virgula." eso38_s2405data = null ";
         $virgula = ",";
       }
     }
     if(trim($this->eso38_s2405processado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso38_s2405processado"])){
       $sql  .= $virgula." eso38_s2405processado = '$this->eso38_s2405processado' ";
       $virgula = ",";
       if(trim($this->eso38_s2405processado) == null ){
         $this->erro_sql = " Campo Processamento S2405 não informado.";
         $this->erro_campo = "eso38_s2405processado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->eso38_s2416data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso38_s2416data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["eso38_s2416data_dia"] !="") ){
       $sql  .= $virgula." eso38_s2416data = '$this->eso38_s2416data' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["eso38_s2416data_dia"])){
         $sql  .= $virgula." eso38_s2416data = null ";
         $virgula = ",";
       }
     }
     if(trim($this->eso38_s2416processado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso38_s2416processado"])){
       $sql  .= $virgula." eso38_s2416processado = '$this->eso38_s2416processado' ";
       $virgula = ",";
       if(trim($this->eso38_s2416processado) == null ){
         $this->erro_sql = " Campo Processamento S2416 não informado.";
         $this->erro_campo = "eso38_s2416processado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($eso38_sequencial!=null){
       $sql .= " eso38_sequencial = $this->eso38_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->eso38_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1014178,'$this->eso38_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso38_sequencial"]) || $this->eso38_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010939,1014178,'".AddSlashes(pg_result($resaco,$conresaco,'eso38_sequencial'))."','$this->eso38_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso38_matricula"]) || $this->eso38_matricula != "")
             $resac = db_query("insert into db_acount values($acount,1010939,1014189,'".AddSlashes(pg_result($resaco,$conresaco,'eso38_matricula'))."','$this->eso38_matricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso38_s2205data"]) || $this->eso38_s2205data != "")
             $resac = db_query("insert into db_acount values($acount,1010939,1014179,'".AddSlashes(pg_result($resaco,$conresaco,'eso38_s2205data'))."','$this->eso38_s2205data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso38_s2205processado"]) || $this->eso38_s2205processado != "")
             $resac = db_query("insert into db_acount values($acount,1010939,1014180,'".AddSlashes(pg_result($resaco,$conresaco,'eso38_s2205processado'))."','$this->eso38_s2205processado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso38_s2206data"]) || $this->eso38_s2206data != "")
             $resac = db_query("insert into db_acount values($acount,1010939,1014181,'".AddSlashes(pg_result($resaco,$conresaco,'eso38_s2206data'))."','$this->eso38_s2206data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso38_s2206processado"]) || $this->eso38_s2206processado != "")
             $resac = db_query("insert into db_acount values($acount,1010939,1014182,'".AddSlashes(pg_result($resaco,$conresaco,'eso38_s2206processado'))."','$this->eso38_s2206processado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso38_s2306data"]) || $this->eso38_s2306data != "")
             $resac = db_query("insert into db_acount values($acount,1010939,1014183,'".AddSlashes(pg_result($resaco,$conresaco,'eso38_s2306data'))."','$this->eso38_s2306data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso38_s2306processado"]) || $this->eso38_s2306processado != "")
             $resac = db_query("insert into db_acount values($acount,1010939,1014184,'".AddSlashes(pg_result($resaco,$conresaco,'eso38_s2306processado'))."','$this->eso38_s2306processado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso38_s2405data"]) || $this->eso38_s2405data != "")
             $resac = db_query("insert into db_acount values($acount,1010939,1014185,'".AddSlashes(pg_result($resaco,$conresaco,'eso38_s2405data'))."','$this->eso38_s2405data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso38_s2405processado"]) || $this->eso38_s2405processado != "")
             $resac = db_query("insert into db_acount values($acount,1010939,1014186,'".AddSlashes(pg_result($resaco,$conresaco,'eso38_s2405processado'))."','$this->eso38_s2405processado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso38_s2416data"]) || $this->eso38_s2416data != "")
             $resac = db_query("insert into db_acount values($acount,1010939,1014187,'".AddSlashes(pg_result($resaco,$conresaco,'eso38_s2416data'))."','$this->eso38_s2416data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso38_s2416processado"]) || $this->eso38_s2416processado != "")
             $resac = db_query("insert into db_acount values($acount,1010939,1014188,'".AddSlashes(pg_result($resaco,$conresaco,'eso38_s2416processado'))."','$this->eso38_s2416processado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Alterações do Servidor não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->eso38_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Alterações do Servidor não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->eso38_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->eso38_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }

    public function excluir($eso38_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($eso38_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1014178,'$eso38_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010939,1014178,'','".AddSlashes(pg_result($resaco,$iresaco,'eso38_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010939,1014189,'','".AddSlashes(pg_result($resaco,$iresaco,'eso38_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010939,1014179,'','".AddSlashes(pg_result($resaco,$iresaco,'eso38_s2205data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010939,1014180,'','".AddSlashes(pg_result($resaco,$iresaco,'eso38_s2205processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010939,1014181,'','".AddSlashes(pg_result($resaco,$iresaco,'eso38_s2206data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010939,1014182,'','".AddSlashes(pg_result($resaco,$iresaco,'eso38_s2206processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010939,1014183,'','".AddSlashes(pg_result($resaco,$iresaco,'eso38_s2306data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010939,1014184,'','".AddSlashes(pg_result($resaco,$iresaco,'eso38_s2306processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010939,1014185,'','".AddSlashes(pg_result($resaco,$iresaco,'eso38_s2405data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010939,1014186,'','".AddSlashes(pg_result($resaco,$iresaco,'eso38_s2405processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010939,1014187,'','".AddSlashes(pg_result($resaco,$iresaco,'eso38_s2416data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010939,1014188,'','".AddSlashes(pg_result($resaco,$iresaco,'eso38_s2416processado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from servidoralteracao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($eso38_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " eso38_sequencial = $eso38_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Alterações do Servidor não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$eso38_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Alterações do Servidor não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$eso38_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$eso38_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:servidoralteracao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($eso38_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from servidoralteracao ";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = servidoralteracao.eso38_matricula";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      inner join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      inner join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      left  join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao and  rhfuncao.rh37_instit = rhpessoal.rh01_instit";
     $sql .= "      inner join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     $sql .= "      left  join rhsindicato  on  rhsindicato.rh116_sequencial = rhpessoal.rh01_rhsindicato";
     $sql .= "      inner join rhreajusteparidade  on  rhreajusteparidade.rh148_sequencial = rhpessoal.rh01_reajusteparidade";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($eso38_sequencial)) {
         $sql2 .= " where servidoralteracao.eso38_sequencial = $eso38_sequencial ";
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

    public function sql_query_file($eso38_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from servidoralteracao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($eso38_sequencial)){
         $sql2 .= " where servidoralteracao.eso38_sequencial = $eso38_sequencial ";
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
