<?php

class cl_far_tiporeceita
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
    public $fa03_i_codigo = 0;
    public $fa03_c_descr = null;
    public $fa03_c_profissional = null;
    public $fa03_c_posologia = null;
    public $fa03_c_requisitante = null;
    public $fa03_c_quant = null;
    public $fa03_c_numeroreceita = null;
    public $fa03_i_prescricaomedica = 0;
    public $fa03_i_ativa = 0;
    public $fa03_data_prescricao = 'f';
    public $fa03_dias_prescricao = 0;
    public $fa03_numero_notificacao = 'f';
   // cria propriedade com as variaveis do arquivo
    public $campos = "
                 fa03_i_codigo = int4 = Código
                 fa03_c_descr = char(50) = Descrição
                 fa03_c_profissional = char(1) = Profissional
                 fa03_c_posologia = char(1) = Posologia
                 fa03_c_requisitante = char(1) = Requisitante
                 fa03_c_quant = char(1) = Vias do Recibo
                 fa03_c_numeroreceita = char(1) = Número receita
                 fa03_i_prescricaomedica = int4 = Prescricao Médica
                 fa03_i_ativa = int4 = Ativa
                 fa03_data_prescricao = bool = Data Prescrição
                 fa03_dias_prescricao = int4 = N° de dias da validade da Prescrição
                 fa03_numero_notificacao = bool = Número de Notificação
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("far_tiporeceita");
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
       $this->fa03_i_codigo = ($this->fa03_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa03_i_codigo"]:$this->fa03_i_codigo);
       $this->fa03_c_descr = ($this->fa03_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["fa03_c_descr"]:$this->fa03_c_descr);
       $this->fa03_c_profissional = ($this->fa03_c_profissional == ""?@$GLOBALS["HTTP_POST_VARS"]["fa03_c_profissional"]:$this->fa03_c_profissional);
       $this->fa03_c_posologia = ($this->fa03_c_posologia == ""?@$GLOBALS["HTTP_POST_VARS"]["fa03_c_posologia"]:$this->fa03_c_posologia);
       $this->fa03_c_requisitante = ($this->fa03_c_requisitante == ""?@$GLOBALS["HTTP_POST_VARS"]["fa03_c_requisitante"]:$this->fa03_c_requisitante);
       $this->fa03_c_quant = ($this->fa03_c_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["fa03_c_quant"]:$this->fa03_c_quant);
       $this->fa03_c_numeroreceita = ($this->fa03_c_numeroreceita == ""?@$GLOBALS["HTTP_POST_VARS"]["fa03_c_numeroreceita"]:$this->fa03_c_numeroreceita);
       $this->fa03_i_prescricaomedica = ($this->fa03_i_prescricaomedica == ""?@$GLOBALS["HTTP_POST_VARS"]["fa03_i_prescricaomedica"]:$this->fa03_i_prescricaomedica);
       $this->fa03_i_ativa = ($this->fa03_i_ativa == ""?@$GLOBALS["HTTP_POST_VARS"]["fa03_i_ativa"]:$this->fa03_i_ativa);
       $this->fa03_data_prescricao = ($this->fa03_data_prescricao == "f"?@$GLOBALS["HTTP_POST_VARS"]["fa03_data_prescricao"]:$this->fa03_data_prescricao);
       $this->fa03_dias_prescricao = ($this->fa03_dias_prescricao == ""?@$GLOBALS["HTTP_POST_VARS"]["fa03_dias_prescricao"]:$this->fa03_dias_prescricao);
       $this->fa03_numero_notificacao = ($this->fa03_numero_notificacao == "f"?@$GLOBALS["HTTP_POST_VARS"]["fa03_numero_notificacao"]:$this->fa03_numero_notificacao);
     }else{
       $this->fa03_i_codigo = ($this->fa03_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa03_i_codigo"]:$this->fa03_i_codigo);
     }
   }

    public function incluir($fa03_i_codigo)
    {
      $this->atualizacampos();
     if($this->fa03_c_descr == null ){
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "fa03_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa03_c_profissional == null ){
       $this->erro_sql = " Campo Profissional não informado.";
       $this->erro_campo = "fa03_c_profissional";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa03_c_posologia == null ){
       $this->erro_sql = " Campo Posologia não informado.";
       $this->erro_campo = "fa03_c_posologia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa03_c_requisitante == null ){
       $this->erro_sql = " Campo Requisitante não informado.";
       $this->erro_campo = "fa03_c_requisitante";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa03_c_numeroreceita == null ){
       $this->erro_sql = " Campo Número receita não informado.";
       $this->erro_campo = "fa03_c_numeroreceita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa03_i_prescricaomedica == null ){
       $this->fa03_i_prescricaomedica = "null";
     }
     if($this->fa03_i_ativa == null ){
       $this->erro_sql = " Campo Ativa não informado.";
       $this->erro_campo = "fa03_i_ativa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa03_data_prescricao == null ){
        $this->fa03_data_prescricao = 'f';
     }
     if($this->fa03_dias_prescricao == null ){
        $this->fa03_dias_prescricao = 0;
     }
     if($this->fa03_numero_notificacao == null ){
        $this->fa03_numero_notificacao = 'f';
     }
     if($fa03_i_codigo == "" || $fa03_i_codigo == null ){
       $result = db_query("select nextval('fartiporeceita_fa03_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: fartiporeceita_fa03_i_codigo_seq do campo: fa03_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->fa03_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from fartiporeceita_fa03_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $fa03_i_codigo)){
         $this->erro_sql = " Campo fa03_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->fa03_i_codigo = $fa03_i_codigo;
       }
     }
     if(($this->fa03_i_codigo == null) || ($this->fa03_i_codigo == "") ){
       $this->erro_sql = " Campo fa03_i_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into far_tiporeceita(
                                       fa03_i_codigo
                                      ,fa03_c_descr
                                      ,fa03_c_profissional
                                      ,fa03_c_posologia
                                      ,fa03_c_requisitante
                                      ,fa03_c_quant
                                      ,fa03_c_numeroreceita
                                      ,fa03_i_prescricaomedica
                                      ,fa03_i_ativa
                                      ,fa03_data_prescricao
                                      ,fa03_dias_prescricao
                                      ,fa03_numero_notificacao
                       )
                values (
                                $this->fa03_i_codigo
                               ,'$this->fa03_c_descr'
                               ,'$this->fa03_c_profissional'
                               ,'$this->fa03_c_posologia'
                               ,'$this->fa03_c_requisitante'
                               ,'$this->fa03_c_quant'
                               ,'$this->fa03_c_numeroreceita'
                               ,$this->fa03_i_prescricaomedica
                               ,$this->fa03_i_ativa
                               ,'$this->fa03_data_prescricao'
                               ,$this->fa03_dias_prescricao
                               ,'$this->fa03_numero_notificacao'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "far_tiporeceita ($this->fa03_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "far_tiporeceita já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "far_tiporeceita ($this->fa03_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->fa03_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->fa03_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12126,'$this->fa03_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,2105,12126,'','".AddSlashes(pg_result($resaco,0,'fa03_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2105,12127,'','".AddSlashes(pg_result($resaco,0,'fa03_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2105,12128,'','".AddSlashes(pg_result($resaco,0,'fa03_c_profissional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2105,12129,'','".AddSlashes(pg_result($resaco,0,'fa03_c_posologia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2105,12130,'','".AddSlashes(pg_result($resaco,0,'fa03_c_requisitante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2105,12168,'','".AddSlashes(pg_result($resaco,0,'fa03_c_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2105,12132,'','".AddSlashes(pg_result($resaco,0,'fa03_c_numeroreceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2105,14054,'','".AddSlashes(pg_result($resaco,0,'fa03_i_prescricaomedica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2105,16770,'','".AddSlashes(pg_result($resaco,0,'fa03_i_ativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2105,1014567,'','".AddSlashes(pg_result($resaco,0,'fa03_data_prescricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2105,1014568,'','".AddSlashes(pg_result($resaco,0,'fa03_dias_prescricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2105,1014610,'','".AddSlashes(pg_result($resaco,0,'fa03_numero_notificacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }

    public function alterar($fa03_i_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update far_tiporeceita set ";
     $virgula = "";
     if(trim($this->fa03_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa03_i_codigo"])){
       $sql  .= $virgula." fa03_i_codigo = $this->fa03_i_codigo ";
       $virgula = ",";
       if(trim($this->fa03_i_codigo) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "fa03_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa03_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa03_c_descr"])){
       $sql  .= $virgula." fa03_c_descr = '$this->fa03_c_descr' ";
       $virgula = ",";
       if(trim($this->fa03_c_descr) == null ){
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "fa03_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa03_c_profissional)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa03_c_profissional"])){
       $sql  .= $virgula." fa03_c_profissional = '$this->fa03_c_profissional' ";
       $virgula = ",";
       if(trim($this->fa03_c_profissional) == null ){
         $this->erro_sql = " Campo Profissional não informado.";
         $this->erro_campo = "fa03_c_profissional";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa03_c_posologia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa03_c_posologia"])){
       $sql  .= $virgula." fa03_c_posologia = '$this->fa03_c_posologia' ";
       $virgula = ",";
       if(trim($this->fa03_c_posologia) == null ){
         $this->erro_sql = " Campo Posologia não informado.";
         $this->erro_campo = "fa03_c_posologia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa03_c_requisitante)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa03_c_requisitante"])){
       $sql  .= $virgula." fa03_c_requisitante = '$this->fa03_c_requisitante' ";
       $virgula = ",";
       if(trim($this->fa03_c_requisitante) == null ){
         $this->erro_sql = " Campo Requisitante não informado.";
         $this->erro_campo = "fa03_c_requisitante";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa03_c_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa03_c_quant"])){
       $sql  .= $virgula." fa03_c_quant = '$this->fa03_c_quant' ";
       $virgula = ",";
     }
     if(trim($this->fa03_c_numeroreceita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa03_c_numeroreceita"])){
       $sql  .= $virgula." fa03_c_numeroreceita = '$this->fa03_c_numeroreceita' ";
       $virgula = ",";
       if(trim($this->fa03_c_numeroreceita) == null ){
         $this->erro_sql = " Campo Número receita não informado.";
         $this->erro_campo = "fa03_c_numeroreceita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa03_i_prescricaomedica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa03_i_prescricaomedica"])){
        if(trim($this->fa03_i_prescricaomedica)=="" && isset($GLOBALS["HTTP_POST_VARS"]["fa03_i_prescricaomedica"])){
           $this->fa03_i_prescricaomedica = "null" ;
        }
       $sql  .= $virgula." fa03_i_prescricaomedica = $this->fa03_i_prescricaomedica ";
       $virgula = ",";
     }
     if(trim($this->fa03_i_ativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa03_i_ativa"])){
       $sql  .= $virgula." fa03_i_ativa = $this->fa03_i_ativa ";
       $virgula = ",";
       if(trim($this->fa03_i_ativa) == null ){
         $this->erro_sql = " Campo Ativa não informado.";
         $this->erro_campo = "fa03_i_ativa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa03_data_prescricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa03_data_prescricao"])){
       $sql  .= $virgula." fa03_data_prescricao = '$this->fa03_data_prescricao' ";
       $virgula = ",";
       if(trim($this->fa03_data_prescricao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["fa03_data_prescricao"])){
         $this->fa03_data_prescricao = "f" ;
       }
     }
     if(trim($this->fa03_dias_prescricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa03_dias_prescricao"])){
       $sql  .= $virgula." fa03_dias_prescricao = $this->fa03_dias_prescricao ";
       $virgula = ",";
       if(trim($this->fa03_dias_prescricao) == null ){
        $this->fa03_dias_prescricao = "0" ;
       }
     }
     if(trim($this->fa03_numero_notificacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa03_numero_notificacao"])){
       $sql  .= $virgula." fa03_numero_notificacao = '$this->fa03_numero_notificacao' ";
       $virgula = ",";
       if(trim($this->fa03_numero_notificacao) == null ){
        $this->fa03_numero_notificacao = "f" ;
       }
     }
     $sql .= " where ";
     if($fa03_i_codigo!=null){
       $sql .= " fa03_i_codigo = $this->fa03_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->fa03_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,12126,'$this->fa03_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa03_i_codigo"]) || $this->fa03_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,2105,12126,'".AddSlashes(pg_result($resaco,$conresaco,'fa03_i_codigo'))."','$this->fa03_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa03_c_descr"]) || $this->fa03_c_descr != "")
             $resac = db_query("insert into db_acount values($acount,2105,12127,'".AddSlashes(pg_result($resaco,$conresaco,'fa03_c_descr'))."','$this->fa03_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa03_c_profissional"]) || $this->fa03_c_profissional != "")
             $resac = db_query("insert into db_acount values($acount,2105,12128,'".AddSlashes(pg_result($resaco,$conresaco,'fa03_c_profissional'))."','$this->fa03_c_profissional',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa03_c_posologia"]) || $this->fa03_c_posologia != "")
             $resac = db_query("insert into db_acount values($acount,2105,12129,'".AddSlashes(pg_result($resaco,$conresaco,'fa03_c_posologia'))."','$this->fa03_c_posologia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa03_c_requisitante"]) || $this->fa03_c_requisitante != "")
             $resac = db_query("insert into db_acount values($acount,2105,12130,'".AddSlashes(pg_result($resaco,$conresaco,'fa03_c_requisitante'))."','$this->fa03_c_requisitante',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa03_c_quant"]) || $this->fa03_c_quant != "")
             $resac = db_query("insert into db_acount values($acount,2105,12168,'".AddSlashes(pg_result($resaco,$conresaco,'fa03_c_quant'))."','$this->fa03_c_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa03_c_numeroreceita"]) || $this->fa03_c_numeroreceita != "")
             $resac = db_query("insert into db_acount values($acount,2105,12132,'".AddSlashes(pg_result($resaco,$conresaco,'fa03_c_numeroreceita'))."','$this->fa03_c_numeroreceita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa03_i_prescricaomedica"]) || $this->fa03_i_prescricaomedica != "")
             $resac = db_query("insert into db_acount values($acount,2105,14054,'".AddSlashes(pg_result($resaco,$conresaco,'fa03_i_prescricaomedica'))."','$this->fa03_i_prescricaomedica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa03_i_ativa"]) || $this->fa03_i_ativa != "")
             $resac = db_query("insert into db_acount values($acount,2105,16770,'".AddSlashes(pg_result($resaco,$conresaco,'fa03_i_ativa'))."','$this->fa03_i_ativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa03_data_prescricao"]) || $this->fa03_data_prescricao != "")
             $resac = db_query("insert into db_acount values($acount,2105,1014567,'".AddSlashes(pg_result($resaco,$conresaco,'fa03_data_prescricao'))."','$this->fa03_data_prescricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa03_dias_prescricao"]) || $this->fa03_dias_prescricao != "")
             $resac = db_query("insert into db_acount values($acount,2105,1014568,'".AddSlashes(pg_result($resaco,$conresaco,'fa03_dias_prescricao'))."','$this->fa03_dias_prescricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fa03_numero_notificacao"]) || $this->fa03_numero_notificacao != "")
             $resac = db_query("insert into db_acount values($acount,2105,1014610,'".AddSlashes(pg_result($resaco,$conresaco,'fa03_numero_notificacao'))."','$this->fa03_numero_notificacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_tiporeceita não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa03_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "far_tiporeceita não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa03_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->fa03_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }

    public function excluir($fa03_i_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($fa03_i_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,12126,'$fa03_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,2105,12126,'','".AddSlashes(pg_result($resaco,$iresaco,'fa03_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2105,12127,'','".AddSlashes(pg_result($resaco,$iresaco,'fa03_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2105,12128,'','".AddSlashes(pg_result($resaco,$iresaco,'fa03_c_profissional'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2105,12129,'','".AddSlashes(pg_result($resaco,$iresaco,'fa03_c_posologia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2105,12130,'','".AddSlashes(pg_result($resaco,$iresaco,'fa03_c_requisitante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2105,12168,'','".AddSlashes(pg_result($resaco,$iresaco,'fa03_c_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2105,12132,'','".AddSlashes(pg_result($resaco,$iresaco,'fa03_c_numeroreceita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2105,14054,'','".AddSlashes(pg_result($resaco,$iresaco,'fa03_i_prescricaomedica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2105,16770,'','".AddSlashes(pg_result($resaco,$iresaco,'fa03_i_ativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2105,1014567,'','".AddSlashes(pg_result($resaco,$iresaco,'fa03_data_prescricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2105,1014568,'','".AddSlashes(pg_result($resaco,$iresaco,'fa03_dias_prescricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2105,1014610,'','".AddSlashes(pg_result($resaco,$iresaco,'fa03_numero_notificacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from far_tiporeceita
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($fa03_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " fa03_i_codigo = $fa03_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_tiporeceita não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fa03_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "far_tiporeceita não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fa03_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$fa03_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:far_tiporeceita";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($fa03_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from far_tiporeceita ";
     $sql .= "      left  join far_prescricaomedica  on  far_prescricaomedica.fa20_i_codigo = far_tiporeceita.fa03_i_prescricaomedica";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($fa03_i_codigo)) {
         $sql2 .= " where far_tiporeceita.fa03_i_codigo = $fa03_i_codigo ";
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

    public function sql_query_file($fa03_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from far_tiporeceita ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($fa03_i_codigo)){
         $sql2 .= " where far_tiporeceita.fa03_i_codigo = $fa03_i_codigo ";
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
