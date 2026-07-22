<?php

class cl_documentos_movimentacao
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
    public $p117_codigo = 0;
    public $p117_documento_andamento = 0;
    public $p117_id_usuario = 0;
    public $p117_protprocessodocumento = 0;
    public $p117_processo_atividadesexecucao = 0;
    public $p117_data_dia = null;
    public $p117_data_mes = null;
    public $p117_data_ano = null;
    public $p117_data = null;
    public $p117_devolucao = 'f';
    public $p117_invalida = 'f';
   // cria propriedade com as variaveis do arquivo
    public $campos = "
                 p117_codigo = int8 = Código
                 p117_documento_andamento = int8 = Documento
                 p117_id_usuario = int8 = Usuário
                 p117_protprocessodocumento = int8 = Documento do processo
                 p117_processo_atividadesexecucao = int8 = Atividades de execução
                 p117_data = date = Data
                 p117_devolucao = bool = Devolucao
                 p117_invalida = bool = Inválida
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("documentos_movimentacao");
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
       $this->p117_codigo = ($this->p117_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["p117_codigo"]:$this->p117_codigo);
       $this->p117_documento_andamento = ($this->p117_documento_andamento == ""?@$GLOBALS["HTTP_POST_VARS"]["p117_documento_andamento"]:$this->p117_documento_andamento);
       $this->p117_id_usuario = ($this->p117_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["p117_id_usuario"]:$this->p117_id_usuario);
       $this->p117_protprocessodocumento = ($this->p117_protprocessodocumento == ""?@$GLOBALS["HTTP_POST_VARS"]["p117_protprocessodocumento"]:$this->p117_protprocessodocumento);
       $this->p117_processo_atividadesexecucao = ($this->p117_processo_atividadesexecucao == ""?@$GLOBALS["HTTP_POST_VARS"]["p117_processo_atividadesexecucao"]:$this->p117_processo_atividadesexecucao);
       if($this->p117_data == ""){
         $this->p117_data_dia = ($this->p117_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["p117_data_dia"]:$this->p117_data_dia);
         $this->p117_data_mes = ($this->p117_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["p117_data_mes"]:$this->p117_data_mes);
         $this->p117_data_ano = ($this->p117_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["p117_data_ano"]:$this->p117_data_ano);
         if($this->p117_data_dia != ""){
            $this->p117_data = $this->p117_data_ano."-".$this->p117_data_mes."-".$this->p117_data_dia;
         }
       }
     }else{
       $this->p117_codigo = ($this->p117_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["p117_codigo"]:$this->p117_codigo);
     }
   }

    public function incluir($p117_codigo)
    {
      $this->atualizacampos();
     if($this->p117_documento_andamento == null ){
       $this->erro_sql = " Campo Documento não informado.";
       $this->erro_campo = "p117_documento_andamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p117_id_usuario == null ){
       $this->erro_sql = " Campo Usuário não informado.";
       $this->erro_campo = "p117_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p117_protprocessodocumento == null ){
       $this->erro_sql = " Campo Documento do processo não informado.";
       $this->erro_campo = "p117_protprocessodocumento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p117_processo_atividadesexecucao == null ){
       $this->erro_sql = " Campo Atividades de execução não informado.";
       $this->erro_campo = "p117_processo_atividadesexecucao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p117_data == null ){
       $this->erro_sql = " Campo Data não informado.";
       $this->erro_campo = "p117_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p117_devolucao == null ){
       $this->erro_sql = " Campo Devolucao não informado.";
       $this->erro_campo = "p117_devolucao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p117_invalida == null ){
       $this->erro_sql = " Campo Inválida não informado.";
       $this->erro_campo = "p117_invalida";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($p117_codigo == "" || $p117_codigo == null ){
       $result = db_query("select nextval('documentos_movimentacao_p117_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: documentos_movimentacao_p117_codigo_seq do campo: p117_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->p117_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from documentos_movimentacao_p117_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $p117_codigo)){
         $this->erro_sql = " Campo p117_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->p117_codigo = $p117_codigo;
       }
     }
     if(($this->p117_codigo == null) || ($this->p117_codigo == "") ){
       $this->erro_sql = " Campo p117_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into documentos_movimentacao(
                                       p117_codigo
                                      ,p117_documento_andamento
                                      ,p117_id_usuario
                                      ,p117_protprocessodocumento
                                      ,p117_processo_atividadesexecucao
                                      ,p117_data
                                      ,p117_devolucao
                                      ,p117_invalida
                       )
                values (
                                $this->p117_codigo
                               ,$this->p117_documento_andamento
                               ,$this->p117_id_usuario
                               ,$this->p117_protprocessodocumento
                               ,$this->p117_processo_atividadesexecucao
                               ,".($this->p117_data == "null" || $this->p117_data == ""?"null":"'".$this->p117_data."'")."
                               ,'$this->p117_devolucao'
                               ,'$this->p117_invalida'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Movimentação dos Documentos ($this->p117_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Movimentação dos Documentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Movimentação dos Documentos ($this->p117_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->p117_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->p117_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1014023,'$this->p117_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010904,1014023,'','".AddSlashes(pg_result($resaco,0,'p117_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010904,1014024,'','".AddSlashes(pg_result($resaco,0,'p117_documento_andamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010904,1014025,'','".AddSlashes(pg_result($resaco,0,'p117_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010904,1014026,'','".AddSlashes(pg_result($resaco,0,'p117_protprocessodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010904,1014027,'','".AddSlashes(pg_result($resaco,0,'p117_processo_atividadesexecucao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010904,1014028,'','".AddSlashes(pg_result($resaco,0,'p117_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010904,1014160,'','".AddSlashes(pg_result($resaco,0,'p117_devolucao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010904,1014165,'','".AddSlashes(pg_result($resaco,0,'p117_invalida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }

    public function alterar($p117_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update documentos_movimentacao set ";
     $virgula = "";
     if(trim($this->p117_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p117_codigo"])){
       $sql  .= $virgula." p117_codigo = $this->p117_codigo ";
       $virgula = ",";
       if(trim($this->p117_codigo) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "p117_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p117_documento_andamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p117_documento_andamento"])){
       $sql  .= $virgula." p117_documento_andamento = $this->p117_documento_andamento ";
       $virgula = ",";
       if(trim($this->p117_documento_andamento) == null ){
         $this->erro_sql = " Campo Documento não informado.";
         $this->erro_campo = "p117_documento_andamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p117_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p117_id_usuario"])){
       $sql  .= $virgula." p117_id_usuario = $this->p117_id_usuario ";
       $virgula = ",";
       if(trim($this->p117_id_usuario) == null ){
         $this->erro_sql = " Campo Usuário não informado.";
         $this->erro_campo = "p117_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p117_protprocessodocumento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p117_protprocessodocumento"])){
       $sql  .= $virgula." p117_protprocessodocumento = $this->p117_protprocessodocumento ";
       $virgula = ",";
       if(trim($this->p117_protprocessodocumento) == null ){
         $this->erro_sql = " Campo Documento do processo não informado.";
         $this->erro_campo = "p117_protprocessodocumento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p117_processo_atividadesexecucao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p117_processo_atividadesexecucao"])){
       $sql  .= $virgula." p117_processo_atividadesexecucao = $this->p117_processo_atividadesexecucao ";
       $virgula = ",";
       if(trim($this->p117_processo_atividadesexecucao) == null ){
         $this->erro_sql = " Campo Atividades de execução não informado.";
         $this->erro_campo = "p117_processo_atividadesexecucao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p117_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p117_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["p117_data_dia"] !="") ){
       $sql  .= $virgula." p117_data = '$this->p117_data' ";
       $virgula = ",";
       if(trim($this->p117_data) == null ){
         $this->erro_sql = " Campo Data não informado.";
         $this->erro_campo = "p117_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["p117_data_dia"])){
         $sql  .= $virgula." p117_data = null ";
         $virgula = ",";
         if(trim($this->p117_data) == null ){
           $this->erro_sql = " Campo Data não informado.";
           $this->erro_campo = "p117_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->p117_devolucao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p117_devolucao"])){
       $sql  .= $virgula." p117_devolucao = '$this->p117_devolucao' ";
       $virgula = ",";
       if(trim($this->p117_devolucao) == null ){
         $this->erro_sql = " Campo Devolucao não informado.";
         $this->erro_campo = "p117_devolucao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p117_invalida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p117_invalida"])){
       $sql  .= $virgula." p117_invalida = '$this->p117_invalida' ";
       $virgula = ",";
       if(trim($this->p117_invalida) == null ){
         $this->erro_sql = " Campo Inválida não informado.";
         $this->erro_campo = "p117_invalida";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($p117_codigo!=null){
       $sql .= " p117_codigo = $this->p117_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->p117_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1014023,'$this->p117_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p117_codigo"]) || $this->p117_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010904,1014023,'".AddSlashes(pg_result($resaco,$conresaco,'p117_codigo'))."','$this->p117_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p117_documento_andamento"]) || $this->p117_documento_andamento != "")
             $resac = db_query("insert into db_acount values($acount,1010904,1014024,'".AddSlashes(pg_result($resaco,$conresaco,'p117_documento_andamento'))."','$this->p117_documento_andamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p117_id_usuario"]) || $this->p117_id_usuario != "")
             $resac = db_query("insert into db_acount values($acount,1010904,1014025,'".AddSlashes(pg_result($resaco,$conresaco,'p117_id_usuario'))."','$this->p117_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p117_protprocessodocumento"]) || $this->p117_protprocessodocumento != "")
             $resac = db_query("insert into db_acount values($acount,1010904,1014026,'".AddSlashes(pg_result($resaco,$conresaco,'p117_protprocessodocumento'))."','$this->p117_protprocessodocumento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p117_processo_atividadesexecucao"]) || $this->p117_processo_atividadesexecucao != "")
             $resac = db_query("insert into db_acount values($acount,1010904,1014027,'".AddSlashes(pg_result($resaco,$conresaco,'p117_processo_atividadesexecucao'))."','$this->p117_processo_atividadesexecucao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p117_data"]) || $this->p117_data != "")
             $resac = db_query("insert into db_acount values($acount,1010904,1014028,'".AddSlashes(pg_result($resaco,$conresaco,'p117_data'))."','$this->p117_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p117_devolucao"]) || $this->p117_devolucao != "")
             $resac = db_query("insert into db_acount values($acount,1010904,1014160,'".AddSlashes(pg_result($resaco,$conresaco,'p117_devolucao'))."','$this->p117_devolucao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p117_invalida"]) || $this->p117_invalida != "")
             $resac = db_query("insert into db_acount values($acount,1010904,1014165,'".AddSlashes(pg_result($resaco,$conresaco,'p117_invalida'))."','$this->p117_invalida',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentação dos Documentos não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->p117_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Movimentação dos Documentos não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->p117_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->p117_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }

    public function excluir($p117_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($p117_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1014023,'$p117_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010904,1014023,'','".AddSlashes(pg_result($resaco,$iresaco,'p117_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010904,1014024,'','".AddSlashes(pg_result($resaco,$iresaco,'p117_documento_andamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010904,1014025,'','".AddSlashes(pg_result($resaco,$iresaco,'p117_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010904,1014026,'','".AddSlashes(pg_result($resaco,$iresaco,'p117_protprocessodocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010904,1014027,'','".AddSlashes(pg_result($resaco,$iresaco,'p117_processo_atividadesexecucao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010904,1014028,'','".AddSlashes(pg_result($resaco,$iresaco,'p117_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010904,1014160,'','".AddSlashes(pg_result($resaco,$iresaco,'p117_devolucao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010904,1014165,'','".AddSlashes(pg_result($resaco,$iresaco,'p117_invalida'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from documentos_movimentacao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($p117_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " p117_codigo = $p117_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Movimentação dos Documentos não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$p117_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Movimentação dos Documentos não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$p117_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$p117_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:documentos_movimentacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($p117_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from documentos_movimentacao ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = documentos_movimentacao.p117_id_usuario";
     $sql .= "      inner join protprocessodocumento  on  protprocessodocumento.p01_sequencial = documentos_movimentacao.p117_protprocessodocumento";
     $sql .= "      inner join documentos_andamento  on  documentos_andamento.p116_codigo = documentos_movimentacao.p117_documento_andamento";
     $sql .= "      inner join processo_atividadesexecucao  on  processo_atividadesexecucao.p118_codigo = documentos_movimentacao.p117_processo_atividadesexecucao";
     $sql .= "      left  join db_usuarios  on  db_usuarios.id_usuario = protprocessodocumento.p01_usuario";
     $sql .= "      inner join protprocesso  as a on   a.p58_codproc = protprocessodocumento.p01_protprocesso";
     $sql .= "      left  join procandamint  on  procandamint.p78_sequencial = protprocessodocumento.p01_procandamint";
     $sql .= "      inner join protprocesso  as b on   b.p58_codproc = documentos_andamento.p116_protprocesso";
     $sql .= "      inner join protprocessodocumento  as c on   c.p01_sequencial = documentos_andamento.p116_protprocessodocumento";
     $sql .= "      inner join processo_atividadesexecucao  as d on   d.p118_codigo = documentos_andamento.p116_atividade_atual and   d.p118_codigo = documentos_andamento.p116_proxima_atividade";
     $sql .= "      inner join protprocesso  as d on   d.p58_codproc = processo_atividadesexecucao.p118_protprocesso";
     $sql .= "      inner join atividadesexecucao  on  atividadesexecucao.p114_codigo = processo_atividadesexecucao.p118_atividadesexecucao";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($p117_codigo)) {
         $sql2 .= " where documentos_movimentacao.p117_codigo = $p117_codigo ";
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

    public function sql_query_file($p117_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from documentos_movimentacao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($p117_codigo)){
         $sql2 .= " where documentos_movimentacao.p117_codigo = $p117_codigo ";
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
