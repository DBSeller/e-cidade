<?php

class cl_slip
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
    public $k17_codigo = 0;
    public $k17_data_dia = null;
    public $k17_data_mes = null;
    public $k17_data_ano = null;
    public $k17_data = null;
    public $k17_debito = 0;
    public $k17_credito = 0;
    public $k17_valor = 0;
    public $k17_hist = 0;
    public $k17_texto = null;
    public $k17_dtaut_dia = null;
    public $k17_dtaut_mes = null;
    public $k17_dtaut_ano = null;
    public $k17_dtaut = null;
    public $k17_autent = 0;
    public $k17_instit = 0;
    public $k17_dtanu_dia = null;
    public $k17_dtanu_mes = null;
    public $k17_dtanu_ano = null;
    public $k17_dtanu = null;
    public $k17_situacao = 0;
    public $k17_tipopagamento = 0;
    public $k17_dtestorno_dia = null;
    public $k17_dtestorno_mes = null;
    public $k17_dtestorno_ano = null;
    public $k17_dtestorno = null;
    public $k17_motivoestorno = null;
    public $k17_idusuario = 0;
   // cria propriedade com as variaveis do arquivo
    public $campos = "
                 k17_codigo = int4 = Código Slip
                 k17_data = date = Data
                 k17_debito = int4 = Conta Débito
                 k17_credito = int4 = Conta Crédito
                 k17_valor = float8 = Valor
                 k17_hist = int4 = Histórico
                 k17_texto = text = Observação
                 k17_dtaut = date = Data Autenticacao
                 k17_autent = int4 = Código Autent.
                 k17_instit = int4 = Instituição
                 k17_dtanu = date = Data da anulação
                 k17_situacao = int4 = Situação
                 k17_tipopagamento = int4 = Tipo de Pagamento
                 k17_dtestorno = date = Data do estorno
                 k17_motivoestorno = text = Motivo do estorno
                 k17_idusuario = int4 = Código do Usuário
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("slip");
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
       $this->k17_codigo = ($this->k17_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["k17_codigo"]:$this->k17_codigo);
       if($this->k17_data == ""){
         $this->k17_data_dia = ($this->k17_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k17_data_dia"]:$this->k17_data_dia);
         $this->k17_data_mes = ($this->k17_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k17_data_mes"]:$this->k17_data_mes);
         $this->k17_data_ano = ($this->k17_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k17_data_ano"]:$this->k17_data_ano);
         if($this->k17_data_dia != ""){
            $this->k17_data = $this->k17_data_ano."-".$this->k17_data_mes."-".$this->k17_data_dia;
         }
       }
       $this->k17_debito = ($this->k17_debito == ""?@$GLOBALS["HTTP_POST_VARS"]["k17_debito"]:$this->k17_debito);
       $this->k17_credito = ($this->k17_credito == ""?@$GLOBALS["HTTP_POST_VARS"]["k17_credito"]:$this->k17_credito);
       $this->k17_valor = ($this->k17_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["k17_valor"]:$this->k17_valor);
       $this->k17_hist = ($this->k17_hist == ""?@$GLOBALS["HTTP_POST_VARS"]["k17_hist"]:$this->k17_hist);
       $this->k17_texto = ($this->k17_texto == ""?@$GLOBALS["HTTP_POST_VARS"]["k17_texto"]:$this->k17_texto);
       if($this->k17_dtaut == ""){
         $this->k17_dtaut_dia = ($this->k17_dtaut_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k17_dtaut_dia"]:$this->k17_dtaut_dia);
         $this->k17_dtaut_mes = ($this->k17_dtaut_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k17_dtaut_mes"]:$this->k17_dtaut_mes);
         $this->k17_dtaut_ano = ($this->k17_dtaut_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k17_dtaut_ano"]:$this->k17_dtaut_ano);
         if($this->k17_dtaut_dia != ""){
            $this->k17_dtaut = $this->k17_dtaut_ano."-".$this->k17_dtaut_mes."-".$this->k17_dtaut_dia;
         }
       }
       $this->k17_autent = ($this->k17_autent == ""?@$GLOBALS["HTTP_POST_VARS"]["k17_autent"]:$this->k17_autent);
       $this->k17_instit = ($this->k17_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["k17_instit"]:$this->k17_instit);
       if($this->k17_dtanu == ""){
         $this->k17_dtanu_dia = ($this->k17_dtanu_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k17_dtanu_dia"]:$this->k17_dtanu_dia);
         $this->k17_dtanu_mes = ($this->k17_dtanu_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k17_dtanu_mes"]:$this->k17_dtanu_mes);
         $this->k17_dtanu_ano = ($this->k17_dtanu_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k17_dtanu_ano"]:$this->k17_dtanu_ano);
         if($this->k17_dtanu_dia != ""){
            $this->k17_dtanu = $this->k17_dtanu_ano."-".$this->k17_dtanu_mes."-".$this->k17_dtanu_dia;
         }
       }
       $this->k17_situacao = ($this->k17_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["k17_situacao"]:$this->k17_situacao);
       $this->k17_tipopagamento = ($this->k17_tipopagamento == ""?@$GLOBALS["HTTP_POST_VARS"]["k17_tipopagamento"]:$this->k17_tipopagamento);
       if($this->k17_dtestorno == ""){
         $this->k17_dtestorno_dia = ($this->k17_dtestorno_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k17_dtestorno_dia"]:$this->k17_dtestorno_dia);
         $this->k17_dtestorno_mes = ($this->k17_dtestorno_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k17_dtestorno_mes"]:$this->k17_dtestorno_mes);
         $this->k17_dtestorno_ano = ($this->k17_dtestorno_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k17_dtestorno_ano"]:$this->k17_dtestorno_ano);
         if($this->k17_dtestorno_dia != ""){
            $this->k17_dtestorno = $this->k17_dtestorno_ano."-".$this->k17_dtestorno_mes."-".$this->k17_dtestorno_dia;
         }
       }
       $this->k17_motivoestorno = ($this->k17_motivoestorno == ""?@$GLOBALS["HTTP_POST_VARS"]["k17_motivoestorno"]:$this->k17_motivoestorno);
       $this->k17_idusuario = ($this->k17_idusuario == ""?@$GLOBALS["HTTP_POST_VARS"]["k17_idusuario"]:$this->k17_idusuario);
     }else{
       $this->k17_codigo = ($this->k17_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["k17_codigo"]:$this->k17_codigo);
     }
   }

    public function incluir($k17_codigo)
    {
      $this->atualizacampos();
     if($this->k17_data == null ){
       $this->erro_sql = " Campo Data não informado.";
       $this->erro_campo = "k17_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k17_debito == null ){
       $this->erro_sql = " Campo Conta Débito não informado.";
       $this->erro_campo = "k17_debito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k17_credito == null ){
       $this->erro_sql = " Campo Conta Crédito não informado.";
       $this->erro_campo = "k17_credito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k17_valor == null ){
       $this->erro_sql = " Campo Valor não informado.";
       $this->erro_campo = "k17_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k17_hist == null ){
       $this->erro_sql = " Campo Histórico não informado.";
       $this->erro_campo = "k17_hist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k17_autent == null ){
       $this->k17_autent = "0";
     }
     if($this->k17_instit == null ){
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "k17_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k17_dtanu == null ){
       $this->k17_dtanu = "null";
     }
     if($this->k17_situacao == null ){
       $this->k17_situacao = "0";
     }
     if($this->k17_tipopagamento == null ){
       $this->k17_tipopagamento = "0";
     }
     if($this->k17_dtestorno == null ){
       $this->k17_dtestorno = "null";
     }
     if($this->k17_idusuario == null ){
       $this->k17_idusuario = "null";
       $usuarioSessao = db_getsession('DB_id_usuario', false);
       if (!empty($usuarioSessao)) {
           $this->k17_idusuario = $usuarioSessao;
       }
     }
     if($k17_codigo == "" || $k17_codigo == null ){
       $result = db_query("select nextval('slip_k17_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: slip_k17_codigo_seq do campo: k17_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->k17_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from slip_k17_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $k17_codigo)){
         $this->erro_sql = " Campo k17_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k17_codigo = $k17_codigo;
       }
     }
     if(($this->k17_codigo == null) || ($this->k17_codigo == "") ){
       $this->erro_sql = " Campo k17_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into slip(
                                       k17_codigo
                                      ,k17_data
                                      ,k17_debito
                                      ,k17_credito
                                      ,k17_valor
                                      ,k17_hist
                                      ,k17_texto
                                      ,k17_dtaut
                                      ,k17_autent
                                      ,k17_instit
                                      ,k17_dtanu
                                      ,k17_situacao
                                      ,k17_tipopagamento
                                      ,k17_dtestorno
                                      ,k17_motivoestorno
                                      ,k17_idusuario
                       )
                values (
                                $this->k17_codigo
                               ,".($this->k17_data == "null" || $this->k17_data == ""?"null":"'".$this->k17_data."'")."
                               ,$this->k17_debito
                               ,$this->k17_credito
                               ,$this->k17_valor
                               ,$this->k17_hist
                               ,'$this->k17_texto'
                               ,".($this->k17_dtaut == "null" || $this->k17_dtaut == ""?"null":"'".$this->k17_dtaut."'")."
                               ,$this->k17_autent
                               ,$this->k17_instit
                               ,".($this->k17_dtanu == "null" || $this->k17_dtanu == ""?"null":"'".$this->k17_dtanu."'")."
                               ,$this->k17_situacao
                               ,$this->k17_tipopagamento
                               ,".($this->k17_dtestorno == "null" || $this->k17_dtestorno == ""?"null":"'".$this->k17_dtestorno."'")."
                               ,'$this->k17_motivoestorno'
                               ,$this->k17_idusuario
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Slips Transferência ($this->k17_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Slips Transferência já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Slips Transferência ($this->k17_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->k17_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k17_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1114,'$this->k17_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,196,1114,'','".AddSlashes(pg_result($resaco,0,'k17_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,196,1115,'','".AddSlashes(pg_result($resaco,0,'k17_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,196,1116,'','".AddSlashes(pg_result($resaco,0,'k17_debito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,196,1117,'','".AddSlashes(pg_result($resaco,0,'k17_credito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,196,1118,'','".AddSlashes(pg_result($resaco,0,'k17_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,196,1119,'','".AddSlashes(pg_result($resaco,0,'k17_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,196,1120,'','".AddSlashes(pg_result($resaco,0,'k17_texto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,196,1121,'','".AddSlashes(pg_result($resaco,0,'k17_dtaut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,196,1122,'','".AddSlashes(pg_result($resaco,0,'k17_autent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,196,6314,'','".AddSlashes(pg_result($resaco,0,'k17_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,196,6317,'','".AddSlashes(pg_result($resaco,0,'k17_dtanu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,196,10490,'','".AddSlashes(pg_result($resaco,0,'k17_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,196,14532,'','".AddSlashes(pg_result($resaco,0,'k17_tipopagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,196,15070,'','".AddSlashes(pg_result($resaco,0,'k17_dtestorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,196,15071,'','".AddSlashes(pg_result($resaco,0,'k17_motivoestorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,196,1010475,'','".AddSlashes(pg_result($resaco,0,'k17_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }

    public function alterar($k17_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update slip set ";
     $virgula = "";
     if(trim($this->k17_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k17_codigo"])){
       $sql  .= $virgula." k17_codigo = $this->k17_codigo ";
       $virgula = ",";
       if(trim($this->k17_codigo) == null ){
         $this->erro_sql = " Campo Código Slip não informado.";
         $this->erro_campo = "k17_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k17_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k17_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k17_data_dia"] !="") ){
       $sql  .= $virgula." k17_data = '$this->k17_data' ";
       $virgula = ",";
       if(trim($this->k17_data) == null ){
         $this->erro_sql = " Campo Data não informado.";
         $this->erro_campo = "k17_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["k17_data_dia"])){
         $sql  .= $virgula." k17_data = null ";
         $virgula = ",";
         if(trim($this->k17_data) == null ){
           $this->erro_sql = " Campo Data não informado.";
           $this->erro_campo = "k17_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k17_debito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k17_debito"])){
       $sql  .= $virgula." k17_debito = $this->k17_debito ";
       $virgula = ",";
       if(trim($this->k17_debito) == null ){
         $this->erro_sql = " Campo Conta Débito não informado.";
         $this->erro_campo = "k17_debito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k17_credito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k17_credito"])){
       $sql  .= $virgula." k17_credito = $this->k17_credito ";
       $virgula = ",";
       if(trim($this->k17_credito) == null ){
         $this->erro_sql = " Campo Conta Crédito não informado.";
         $this->erro_campo = "k17_credito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k17_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k17_valor"])){
       $sql  .= $virgula." k17_valor = $this->k17_valor ";
       $virgula = ",";
       if(trim($this->k17_valor) == null ){
         $this->erro_sql = " Campo Valor não informado.";
         $this->erro_campo = "k17_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k17_hist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k17_hist"])){
       $sql  .= $virgula." k17_hist = $this->k17_hist ";
       $virgula = ",";
       if(trim($this->k17_hist) == null ){
         $this->erro_sql = " Campo Histórico não informado.";
         $this->erro_campo = "k17_hist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k17_texto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k17_texto"])){
       $sql  .= $virgula." k17_texto = '$this->k17_texto' ";
       $virgula = ",";
     }
     if(trim($this->k17_dtaut)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k17_dtaut_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k17_dtaut_dia"] !="") ){
       $sql  .= $virgula." k17_dtaut = '$this->k17_dtaut' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["k17_dtaut_dia"])){
         $sql  .= $virgula." k17_dtaut = null ";
         $virgula = ",";
       }
     }
     if(trim($this->k17_autent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k17_autent"])){
        if(trim($this->k17_autent)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k17_autent"])){
           $this->k17_autent = "0" ;
        }
       $sql  .= $virgula." k17_autent = $this->k17_autent ";
       $virgula = ",";
     }
     if(trim($this->k17_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k17_instit"])){
       $sql  .= $virgula." k17_instit = $this->k17_instit ";
       $virgula = ",";
       if(trim($this->k17_instit) == null ){
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "k17_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k17_dtanu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k17_dtanu_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k17_dtanu_dia"] !="") ){
       $sql  .= $virgula." k17_dtanu = '$this->k17_dtanu' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["k17_dtanu_dia"])){
         $sql  .= $virgula." k17_dtanu = null ";
         $virgula = ",";
       }
     }
     if(trim($this->k17_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k17_situacao"])){
        if(trim($this->k17_situacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k17_situacao"])){
           $this->k17_situacao = "0" ;
        }
       $sql  .= $virgula." k17_situacao = $this->k17_situacao ";
       $virgula = ",";
     }
     if(trim($this->k17_tipopagamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k17_tipopagamento"])){
        if(trim($this->k17_tipopagamento)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k17_tipopagamento"])){
           $this->k17_tipopagamento = "0" ;
        }
       $sql  .= $virgula." k17_tipopagamento = $this->k17_tipopagamento ";
       $virgula = ",";
     }
     if(trim($this->k17_dtestorno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k17_dtestorno_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k17_dtestorno_dia"] !="") ){
       $sql  .= $virgula." k17_dtestorno = '$this->k17_dtestorno' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["k17_dtestorno_dia"])){
         $sql  .= $virgula." k17_dtestorno = null ";
         $virgula = ",";
       }
     }
     if(trim($this->k17_motivoestorno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k17_motivoestorno"])){
       $sql  .= $virgula." k17_motivoestorno = '$this->k17_motivoestorno' ";
       $virgula = ",";
     }
     if(trim($this->k17_idusuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k17_idusuario"])){
        if(trim($this->k17_idusuario)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k17_idusuario"])){
           $this->k17_idusuario = "0" ;
        }
       $sql  .= $virgula." k17_idusuario = $this->k17_idusuario ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($k17_codigo!=null){
       $sql .= " k17_codigo = $this->k17_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k17_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1114,'$this->k17_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k17_codigo"]) || $this->k17_codigo != "")
             $resac = db_query("insert into db_acount values($acount,196,1114,'".AddSlashes(pg_result($resaco,$conresaco,'k17_codigo'))."','$this->k17_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k17_data"]) || $this->k17_data != "")
             $resac = db_query("insert into db_acount values($acount,196,1115,'".AddSlashes(pg_result($resaco,$conresaco,'k17_data'))."','$this->k17_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k17_debito"]) || $this->k17_debito != "")
             $resac = db_query("insert into db_acount values($acount,196,1116,'".AddSlashes(pg_result($resaco,$conresaco,'k17_debito'))."','$this->k17_debito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k17_credito"]) || $this->k17_credito != "")
             $resac = db_query("insert into db_acount values($acount,196,1117,'".AddSlashes(pg_result($resaco,$conresaco,'k17_credito'))."','$this->k17_credito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k17_valor"]) || $this->k17_valor != "")
             $resac = db_query("insert into db_acount values($acount,196,1118,'".AddSlashes(pg_result($resaco,$conresaco,'k17_valor'))."','$this->k17_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k17_hist"]) || $this->k17_hist != "")
             $resac = db_query("insert into db_acount values($acount,196,1119,'".AddSlashes(pg_result($resaco,$conresaco,'k17_hist'))."','$this->k17_hist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k17_texto"]) || $this->k17_texto != "")
             $resac = db_query("insert into db_acount values($acount,196,1120,'".AddSlashes(pg_result($resaco,$conresaco,'k17_texto'))."','$this->k17_texto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k17_dtaut"]) || $this->k17_dtaut != "")
             $resac = db_query("insert into db_acount values($acount,196,1121,'".AddSlashes(pg_result($resaco,$conresaco,'k17_dtaut'))."','$this->k17_dtaut',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k17_autent"]) || $this->k17_autent != "")
             $resac = db_query("insert into db_acount values($acount,196,1122,'".AddSlashes(pg_result($resaco,$conresaco,'k17_autent'))."','$this->k17_autent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k17_instit"]) || $this->k17_instit != "")
             $resac = db_query("insert into db_acount values($acount,196,6314,'".AddSlashes(pg_result($resaco,$conresaco,'k17_instit'))."','$this->k17_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k17_dtanu"]) || $this->k17_dtanu != "")
             $resac = db_query("insert into db_acount values($acount,196,6317,'".AddSlashes(pg_result($resaco,$conresaco,'k17_dtanu'))."','$this->k17_dtanu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k17_situacao"]) || $this->k17_situacao != "")
             $resac = db_query("insert into db_acount values($acount,196,10490,'".AddSlashes(pg_result($resaco,$conresaco,'k17_situacao'))."','$this->k17_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k17_tipopagamento"]) || $this->k17_tipopagamento != "")
             $resac = db_query("insert into db_acount values($acount,196,14532,'".AddSlashes(pg_result($resaco,$conresaco,'k17_tipopagamento'))."','$this->k17_tipopagamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k17_dtestorno"]) || $this->k17_dtestorno != "")
             $resac = db_query("insert into db_acount values($acount,196,15070,'".AddSlashes(pg_result($resaco,$conresaco,'k17_dtestorno'))."','$this->k17_dtestorno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k17_motivoestorno"]) || $this->k17_motivoestorno != "")
             $resac = db_query("insert into db_acount values($acount,196,15071,'".AddSlashes(pg_result($resaco,$conresaco,'k17_motivoestorno'))."','$this->k17_motivoestorno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k17_idusuario"]) || $this->k17_idusuario != "")
             $resac = db_query("insert into db_acount values($acount,196,1010475,'".AddSlashes(pg_result($resaco,$conresaco,'k17_idusuario'))."','$this->k17_idusuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Slips Transferência não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k17_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Slips Transferência não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k17_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->k17_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }

    public function excluir($k17_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($k17_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1114,'$k17_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,196,1114,'','".AddSlashes(pg_result($resaco,$iresaco,'k17_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,196,1115,'','".AddSlashes(pg_result($resaco,$iresaco,'k17_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,196,1116,'','".AddSlashes(pg_result($resaco,$iresaco,'k17_debito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,196,1117,'','".AddSlashes(pg_result($resaco,$iresaco,'k17_credito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,196,1118,'','".AddSlashes(pg_result($resaco,$iresaco,'k17_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,196,1119,'','".AddSlashes(pg_result($resaco,$iresaco,'k17_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,196,1120,'','".AddSlashes(pg_result($resaco,$iresaco,'k17_texto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,196,1121,'','".AddSlashes(pg_result($resaco,$iresaco,'k17_dtaut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,196,1122,'','".AddSlashes(pg_result($resaco,$iresaco,'k17_autent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,196,6314,'','".AddSlashes(pg_result($resaco,$iresaco,'k17_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,196,6317,'','".AddSlashes(pg_result($resaco,$iresaco,'k17_dtanu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,196,10490,'','".AddSlashes(pg_result($resaco,$iresaco,'k17_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,196,14532,'','".AddSlashes(pg_result($resaco,$iresaco,'k17_tipopagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,196,15070,'','".AddSlashes(pg_result($resaco,$iresaco,'k17_dtestorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,196,15071,'','".AddSlashes(pg_result($resaco,$iresaco,'k17_motivoestorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,196,1010475,'','".AddSlashes(pg_result($resaco,$iresaco,'k17_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from slip
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($k17_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " k17_codigo = $k17_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Slips Transferência não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k17_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Slips Transferência não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k17_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$k17_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:slip";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    // funcao do sql
    public function sql_query ( $k17_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
        $sql = "select ";
        if($campos != "*" ){
            $campos_sql = explode("#",$campos);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }else{
            $sql .= $campos;
        }
        $sql .= " from slip ";
        $sql .= "      inner join db_config  on  db_config.codigo = slip.k17_instit";
        $sql2 = "";
        if($dbwhere==""){
            if($k17_codigo!=null ){
                $sql2 .= " where slip.k17_codigo = $k17_codigo ";
            }
        }else if($dbwhere != ""){
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if($ordem != null ){
            $sql .= " order by ";
            $campos_sql = explode("#",$ordem);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }
    // funcao do sql
    public function sql_query_file ( $k17_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
        $sql = "select ";
        if($campos != "*" ){
            $campos_sql = explode("#",$campos);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }else{
            $sql .= $campos;
        }
        $sql .= " from slip ";
        $sql2 = "";
        if($dbwhere==""){
            if($k17_codigo!=null ){
                $sql2 .= " where slip.k17_codigo = $k17_codigo ";
            }
        }else if($dbwhere != ""){
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if($ordem != null ){
            $sql .= " order by ";
            $campos_sql = explode("#",$ordem);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }
    public function sql_query_alteracao ( $k17_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
        $sql = "select ";
        if($campos != "*" ){
            $campos_sql = explode("#",$campos);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }else{
            $sql .= $campos;
        }
        $sql .= " from slip ";
        $sql .= "      left outer join conhist on conhist.c50_codhist = slip.k17_hist ";
        $sql .= "      left outer join slipnum on slip.k17_codigo     = slipnum.k17_codigo ";
        $sql .= "      left outer join cgm     on slipnum.k17_numcgm  = cgm.z01_numcgm ";
        $sql .= "      left outer join saltes  on k17_credito         = k13_conta  ";
        $sql .= "      left outer join conplanoreduz  on k17_debito   = c61_reduz  ";
        $sql .= "                                    and c61_anousu   = ".db_getsession("DB_anousu");
        $sql .= "                                    and c61_instit   = ".db_getsession("DB_instit");
        $sql .= "      left outer join conplano       on c61_codcon   = c60_codcon  ";
        $sql .= "                                    and c61_anousu   = c60_anousu  ";
        $sql2 = "";
        if($dbwhere==""){
            if($k17_codigo!=null ){
                $sql2 .= " where slip.k17_codigo = $k17_codigo ";
            }
        }else if($dbwhere != ""){
            $sql2 = " where $dbwhere";
        }
        $sql2 .= ($sql2!=""?" and ":" where ") . " k17_instit = " . db_getsession("DB_instit");
        $sql .= $sql2;
        if($ordem != null ){
            $sql .= " order by ";
            $campos_sql = explode("#",$ordem);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }
    public function sql_query_cheque ( $k17_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
        $sql = "select ";
        if($campos != "*" ){
            $campos_sql = explode("#",$campos);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }else{
            $sql .= $campos;
        }
        $sql .= " from slip s";
        $sql .= "  left  join emphist on s.k17_hist = e40_codhist";
        $sql .= "  inner join conplanoreduz x on x.c61_reduz = s.k17_debito and x.c61_anousu=".db_getsession("DB_anousu");
        $sql .= "  inner join conplano z on z.c60_codcon = x.c61_codcon and z.c60_anousu = x.c61_anousu ";
        $sql .= "  inner join empageslip on e89_codigo = s.k17_codigo  ";
        $sql .= "  inner join empagemov on e89_codmov = e81_codmov     ";
        $sql .= "  inner join empage    on e81_codage = e80_codage ";
        $sql .= "  inner join empageconf on e89_codmov = e86_codmov    ";
        $sql .= "  inner join empageconfche on e91_codmov = e89_codmov and e91_ativo is true ";
        $sql .= "  left join slipnum o on o.k17_codigo = s.k17_codigo";
        $sql .= "  left join cgm on z01_numcgm = o.k17_numcgm";
        $sql2 = "";
        if($dbwhere==""){
            if($k17_codigo!=null ){
                $sql2 .= " where s.k17_codigo = $k17_codigo ";
            }
        }else if($dbwhere != ""){
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if($ordem != null ){
            $sql .= " order by ";
            $campos_sql = explode("#",$ordem);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }







    /**
     * verifica se o slip é da folha
     * sendo ou gerado na rotina da Folha ou na rotina do
     * financeiro de retenção
     * se o Slip for gerado pela folha sendo pagto extra de folha
     * se o slip da folha for gerado pelo financeiro que é o recolhimento
     * de retenções da folha
     */

    public function sql_query_folhaRetencao( $k17_codigo=null,$campos="*",$ordem=null,$dbwhere=""){

        $ano = db_getsession("DB_anousu");

        $sql = "select ";
        if($campos != "*" ){
            $campos_sql = explode("#",$campos);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }else{
            $sql .= $campos;
        }

        $sql .= "      from retencaoreceitas ";
        $sql .= "inner join retencaopagordem on e20_sequencial = e23_retencaopagordem ";
        $sql .= "inner join pagordem on e50_codord = e20_pagordem ";
        $sql .= "inner join empempenho on e50_numemp = e60_numemp ";
        $sql .= "inner join rhempenhofolhaempenho on rh76_numemp = e60_numemp ";
        $sql .= "inner join rhempenhofolha on rh76_rhempenhofolha = rh72_sequencial ";
        $sql .= "inner join retencaocorgrupocorrente on e47_retencaoreceita = e23_sequencial ";
        $sql .= "inner join retencaotiporec on e23_retencaotiporec = e21_sequencial ";
        $sql .= "inner join retencaotiporeccgm on retencaotiporeccgm.e48_retencaotiporec = retencaotiporec.e21_sequencial ";
        $sql .= "inner join cgm as cgm_credor on cgm_credor.z01_numcgm = retencaotiporeccgm.e48_cgm ";
        $sql .= "inner join corgrupocorrente on k105_sequencial = e47_corgrupocorrente ";
        $sql .= "inner join corrente on k105_data = corrente.k12_data ";
        $sql .= "                 and k105_id = corrente.k12_id ";
        $sql .= "                 and k105_autent = k12_autent ";
        $sql .= "inner join slipcorrente on k112_data = corrente.k12_data ";
        $sql .= "                       and k112_id = corrente.k12_id ";
        $sql .= "                       and k112_autent = corrente.k12_autent ";
        $sql .= "                       and k112_ativo is true ";
        $sql .= "inner join slip on k112_slip = k17_codigo ";
        $sql .= " left join caixa.empagemovslips on e23_sequencial = k107_retencao ";

/*
         $sql .= " inner join cornump on corrente.k12_data = cornump.k12_data ";
         $sql .= "                   and corrente.k12_id = cornump.k12_id ";
         $sql .= "                   and corrente.k12_autent = cornump.k12_autent ";
         $sql .= " inner join tabrec on e21_receita = tabrec.k02_codigo ";
         $sql .= " inner join tabplan on tabrec.k02_codigo = tabplan.k02_codigo ";
         $sql .= "                   and tabplan.k02_anousu = 2022 ";
         $sql .= " inner join cgm on cgm.z01_numcgm = e60_numcgm ";
         $sql .= "  left join saltes on corrente.k12_conta = k13_conta ";
         $sql .= "  left join conplanoreduz on c61_reduz = k02_reduz ";
         $sql .= "                         and tabplan.k02_anousu = c61_anousu ";
         $sql .= "  left join conplano on c61_codcon = c60_codcon ";
         $sql .= "                   and c60_anousu = c61_anousu ";
         $sql .= " inner join reciborecurso on k12_numpre = k00_numpre ";
         $sql .= " inner join orctiporec on o15_codigo = k00_recurso ";
         $sql .= " inner join orcdotacao on e60_coddot = o58_coddot ";
         $sql .= "                     and e60_anousu = o58_anousu ";
*/


        $sql2 = "";
        if($dbwhere==""){
            if($k17_codigo != null ){
                $sql2 .= " where s.k17_codigo = $k17_codigo ";
            }
        }else if($dbwhere != ""){
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if($ordem != null ){
            $sql .= " order by ";
            $campos_sql = explode("#",$ordem);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }

    public function sql_query_rhemprubricas( $k17_codigo=null,$campos="*",$ordem=null,$dbwhere=""){

        $sql = "select ";
        if($campos != "*" ){
            $campos_sql = explode("#",$campos);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }else{
            $sql .= $campos;
        }

        $sql .= " from slip ";
        $sql .= "      inner join rhslipfolhaslip         on rhslipfolhaslip.rh82_slip                = slip.k17_codigo                                             ";
        $sql .= "      inner join rhslipfolha             on rhslipfolha.rh79_sequencial              = rhslipfolhaslip.rh82_rhslipfolha                            ";
        $sql .= "      inner join rhslipfolharhemprubrica on rhslipfolharhemprubrica.rh80_rhslipfolha = rhslipfolha.rh79_sequencial                                 ";
        $sql .= "      inner join rhempenhofolharubrica   on rhempenhofolharubrica.rh73_sequencial    = rhslipfolharhemprubrica.rh80_rhempenhofolharubrica          ";
        $sql .= "      left  join rhempenhofolharubricaretencao on rhempenhofolharubricaretencao.rh78_rhempenhofolharubrica = rhempenhofolharubrica.rh73_sequencial ";
        $sql .= "      left  join retencaotiporec         on retencaotiporec.e21_sequencial           = rhempenhofolharubricaretencao.rh78_retencaotiporec          ";
        $sql .= "      left  join retencaotiporeccgm      on e48_retencaotiporec                      = e21_sequencial           ";
        $sql .= "      left  join rhcontasrec             on rh41_codigo                              = rh79_recurso             ";
        $sql .= "                                        and rh41_anousu                              = rh79_anousu              ";
        $sql .= "                                        and rh41_instit                              = rh73_instit              ";
        $sql .= "      left  join placaixarecslip         on k110_slip                                = rh82_slip                ";

        $sql2 = "";
        if($dbwhere==""){
            if($k17_codigo!=null ){
                $sql2 .= " where s.k17_codigo = $k17_codigo ";
            }
        }else if($dbwhere != ""){
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if($ordem != null ){
            $sql .= " order by ";
            $campos_sql = explode("#",$ordem);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }
    public function sql_query_tipo ( $k17_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
        $sql = "select ";
        if($campos != "*" ){
            $campos_sql = explode("#",$campos);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }else{
            $sql .= $campos;
        }
        $sql .= " from slip s";
        $sql .= "  left  join emphist on s.k17_hist = e40_codhist";
        $sql .= "  left  join conhist on s.k17_hist = c50_codhist";
        $sql .= "  inner join conplanoreduz x on x.c61_reduz = s.k17_debito and x.c61_anousu=".db_getsession("DB_anousu");
        $sql .= "  inner join conplano z on z.c60_codcon = x.c61_codcon and z.c60_anousu = x.c61_anousu ";
        $sql .= "  left join slipnum o on o.k17_codigo = s.k17_codigo";
        $sql .= "  left join cgm on z01_numcgm = o.k17_numcgm";
        $sql .= "  left join empageslip on e89_codigo = s.k17_codigo ";
        $sql .= "  left join empagemov on e89_codmov      = e81_codmov ";
        $sql .= "  left join empageconf on e81_codmov     = e86_codmov ";
        $sql .= "  left join empageconfche on e91_codmov  = e86_codmov and e91_ativo is true ";
        $sql .= "  left join empagemovforma on e97_codmov = e81_codmov ";

        $sql2 = "";
        if($dbwhere==""){
            if($k17_codigo!=null ){
                $sql2 .= " where s.k17_codigo = $k17_codigo ";
            }
        }else if($dbwhere != ""){
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if($ordem != null ){
            $sql .= " order by ";
            $campos_sql = explode("#",$ordem);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }

    public function sql_query_tipo_vinculo ( $k17_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
        $sql = "select ";
        if($campos != "*" ){
            $campos_sql = explode("#",$campos);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }else{
            $sql .= $campos;
        }
        $sql .= " from slip ";
        $sql .= "  inner join sliptipooperacaovinculo  on sliptipooperacaovinculo.k153_slip = slip.k17_codigo ";
        $sql .= "  inner join sliptipooperacao         on sliptipooperacao.k152_sequencial  = sliptipooperacaovinculo.k153_slipoperacaotipo";

        /** [Extensão] - [AutorizacaoRepasse] */

        $sql2 = "";
        if($dbwhere==""){
            if($k17_codigo!=null ){
                $sql2 .= " where s.k17_codigo = $k17_codigo ";
            }
        }else if($dbwhere != ""){
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if($ordem != null ){
            $sql .= " order by ";
            $campos_sql = explode("#",$ordem);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }

    public function sql_queryOperacaoExtraOrcamentaria ( $k17_codigo=null,$campos="*",$ordem=null,$dbwhere=""){

        $sql = "select ";

        if($campos != "*" ){

            $campos_sql = explode("#",$campos);
            $virgula = "";

            for( $i = 0; $i <sizeof ($campos_sql); $i++){

                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }else{
            $sql .= $campos;
        }
        $sql .= "        from slip ";
        $sql .= "  inner join db_config     on db_config.codigo = slip.k17_instit                 ";
        $sql .= "  inner join conlancamslip on slip.k17_codigo = conlancamslip.c84_slip           ";
        $sql .= "  inner join conlancam     on conlancamslip.c84_conlancam = conlancam.c70_codlan ";
        $sql .= "  inner join conlancamdoc  on conlancam.c70_codlan = conlancamdoc.c71_codlan     ";

        $sql2 = "";

        if ( $dbwhere == "" ) {

            if ($k17_codigo != null ) {
                $sql2 .= " where s.k17_codigo = $k17_codigo ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;

        if($ordem != null ){

            $sql .= " order by ";
            $campos_sql = explode("#",$ordem);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }

    public function sql_query_pagamento_cheque ( $k17_codigo=null,$campos="*",$ordem=null,$dbwhere=""){

        $sql  = " select {$campos}";
        $sql .= "   from slip ";
        $sql .= "        inner join corlanc       on slip.k17_codigo = corlanc.k12_codigo";
        $sql .= "        inner join empageslip    on empageslip.e89_codigo = slip.k17_codigo";
        $sql .= "        inner join empagemov     on empagemov.e81_codmov = empageslip.e89_codmov";
        $sql .= "        inner join empageconfche on empageconfche.e91_codmov = empagemov.e81_codmov";

        $sql .= " where ";
        if (!empty($k17_codigo)) {
            $sql .= "slip.k17_codigo = {$k17_codigo}";
        }

        if (!empty($dbwhere) && empty($k17_codigo)) {
            $sql .= $dbwhere;
        }

        if (!empty($ordem)) {
            $sql .= " order by {$ordem} ";
        }

        return $sql;
    }

    public function sql_query_slip_autenticado($sCampos = "*", $sOrder = null, $sWhere = null) {

        $sSql  = " select {$sCampos} ";
        $sSql .= "   from slip ";
        $sSql .= "        inner join conlancamslip on conlancamslip.c84_slip = slip.k17_codigo ";
        $sSql .= "        inner join conlancam     on conlancam.c70_codlan = conlancamslip.c84_conlancam ";
        $sSql .= "        inner join slipconcarpeculiar on slipconcarpeculiar.k131_slip = slip.k17_codigo ";

        if (!empty($sWhere)) {
            $sSql .= " where {$sWhere} ";
        }

        if (!empty($sOrder)) {
            $sSql .= " order by {$sOrder} ";
        }
        return $sSql;
    }

    public function sql_querySlipVinculo($situacaoSlip){
      $sql = "
       select k17_codigo
         from slip 
         left join slipretencaoreceitas ON slipretencaoreceitas.k206_slip     = slip.k17_codigo 
         left join slipplacaixarec ON slipplacaixarec.k207_slip               = slip.k17_codigo 
         left join slipoperacaoextra ON slipoperacaoextra.k208_pagamento      = slip.k17_codigo 
        where k17_situacao IN ($situacaoSlip) AND (          
            k206_retencaoreceitas IN (
               select k206_retencaoreceitas 
                 from slip 
                inner join slipretencaoreceitas ON slipretencaoreceitas.k206_slip = slip.k17_codigo 
                  group by k206_retencaoreceitas having COUNT(k206_retencaoreceitas) > 1) OR 
            k208_recebimento IN (
               select k208_recebimento 
                 from slip 
                inner join slipoperacaoextra ON slipoperacaoextra.k208_pagamento = slip.k17_codigo
                GROUP BY k208_recebimento having COUNT(k208_recebimento) > 1) OR
            k207_placaixarec IN (
               select k207_placaixarec 
                 from slip 
                inner join slipplacaixarec ON slipplacaixarec.k207_slip = slip.k17_codigo
                GROUP BY k207_placaixarec having COUNT(k207_placaixarec) > 1 )
         )
     ";
     return $sql;
    } 
}
