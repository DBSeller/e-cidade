<?php

class cl_escoladiretor
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
    public $ed254_i_codigo = 0;
    public $ed254_i_rechumano = 0;
    public $ed254_i_escola = 0;
    public $ed254_i_turno = 0;
    public $ed254_i_atolegal = null;
    public $ed254_c_email = null;
    public $ed254_d_dataini_dia = null;
    public $ed254_d_dataini_mes = null;
    public $ed254_d_dataini_ano = null;
    public $ed254_d_dataini = null;
    public $ed254_d_datafim_dia = null;
    public $ed254_d_datafim_mes = null;
    public $ed254_d_datafim_ano = null;
    public $ed254_d_datafim = null;
    public $ed254_c_tipo = null;
    public $ed254_i_usuario = 0;
    public $ed254_d_datacad_dia = null;
    public $ed254_d_datacad_mes = null;
    public $ed254_d_datacad_ano = null;
    public $ed254_d_datacad = null;
    public $ed254_especificacaocriteriooutros = null;
    public $ed254_criterioacessofuncao = 0;
   // cria propriedade com as variaveis do arquivo
    public $campos = "
                 ed254_i_codigo = int8 = Código
                 ed254_i_rechumano = int8 = Diretor
                 ed254_i_escola = int8 = Escola
                 ed254_i_turno = int8 = Turno
                 ed254_i_atolegal = int8 = Ato Legal
                 ed254_c_email = char(100) = Email
                 ed254_d_dataini = date = Data Inicial do Exercício
                 ed254_d_datafim = date = Data Final do Exercício
                 ed254_c_tipo = char(1) = Situação do Exercício
                 ed254_i_usuario = int8 = Usuário
                 ed254_d_datacad = date = Data do Cadastro
                 ed254_especificacaocriteriooutros = varchar(100) = Especificação do critério de acesso
                 ed254_criterioacessofuncao = int4 = Critério de acesso a função
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("escoladiretor");
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
       $this->ed254_i_codigo = ($this->ed254_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_i_codigo"]:$this->ed254_i_codigo);
       $this->ed254_i_rechumano = ($this->ed254_i_rechumano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_i_rechumano"]:$this->ed254_i_rechumano);
       $this->ed254_i_escola = ($this->ed254_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_i_escola"]:$this->ed254_i_escola);
       $this->ed254_i_turno = ($this->ed254_i_turno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_i_turno"]:$this->ed254_i_turno);
       $this->ed254_i_atolegal = ($this->ed254_i_atolegal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_i_atolegal"]:$this->ed254_i_atolegal);
       $this->ed254_c_email = ($this->ed254_c_email == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_c_email"]:$this->ed254_c_email);
       if($this->ed254_d_dataini == ""){
         $this->ed254_d_dataini_dia = ($this->ed254_d_dataini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_d_dataini_dia"]:$this->ed254_d_dataini_dia);
         $this->ed254_d_dataini_mes = ($this->ed254_d_dataini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_d_dataini_mes"]:$this->ed254_d_dataini_mes);
         $this->ed254_d_dataini_ano = ($this->ed254_d_dataini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_d_dataini_ano"]:$this->ed254_d_dataini_ano);
         if($this->ed254_d_dataini_dia != ""){
            $this->ed254_d_dataini = $this->ed254_d_dataini_ano."-".$this->ed254_d_dataini_mes."-".$this->ed254_d_dataini_dia;
         }
       }
       if($this->ed254_d_datafim == ""){
         $this->ed254_d_datafim_dia = ($this->ed254_d_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_d_datafim_dia"]:$this->ed254_d_datafim_dia);
         $this->ed254_d_datafim_mes = ($this->ed254_d_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_d_datafim_mes"]:$this->ed254_d_datafim_mes);
         $this->ed254_d_datafim_ano = ($this->ed254_d_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_d_datafim_ano"]:$this->ed254_d_datafim_ano);
         if($this->ed254_d_datafim_dia != ""){
            $this->ed254_d_datafim = $this->ed254_d_datafim_ano."-".$this->ed254_d_datafim_mes."-".$this->ed254_d_datafim_dia;
         }
       }
       $this->ed254_c_tipo = ($this->ed254_c_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_c_tipo"]:$this->ed254_c_tipo);
       $this->ed254_i_usuario = ($this->ed254_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_i_usuario"]:$this->ed254_i_usuario);
       if($this->ed254_d_datacad == ""){
         $this->ed254_d_datacad_dia = ($this->ed254_d_datacad_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_d_datacad_dia"]:$this->ed254_d_datacad_dia);
         $this->ed254_d_datacad_mes = ($this->ed254_d_datacad_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_d_datacad_mes"]:$this->ed254_d_datacad_mes);
         $this->ed254_d_datacad_ano = ($this->ed254_d_datacad_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_d_datacad_ano"]:$this->ed254_d_datacad_ano);
         if($this->ed254_d_datacad_dia != ""){
            $this->ed254_d_datacad = $this->ed254_d_datacad_ano."-".$this->ed254_d_datacad_mes."-".$this->ed254_d_datacad_dia;
         }
       }
       $this->ed254_especificacaocriteriooutros = ($this->ed254_especificacaocriteriooutros == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_especificacaocriteriooutros"]:$this->ed254_especificacaocriteriooutros);
       $this->ed254_criterioacessofuncao = ($this->ed254_criterioacessofuncao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_criterioacessofuncao"]:$this->ed254_criterioacessofuncao);
     }else{
       $this->ed254_i_codigo = ($this->ed254_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed254_i_codigo"]:$this->ed254_i_codigo);
     }
   }

    public function incluir($ed254_i_codigo)
    {
      $this->atualizacampos();
     if($this->ed254_i_rechumano == null ){
       $this->erro_sql = " Campo Diretor não informado.";
       $this->erro_campo = "ed254_i_rechumano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed254_i_escola == null ){
       $this->erro_sql = " Campo Escola não informado.";
       $this->erro_campo = "ed254_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed254_i_turno == null ){
       $this->erro_sql = " Campo Turno não informado.";
       $this->erro_campo = "ed254_i_turno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed254_i_atolegal == null ){
       $this->ed254_i_atolegal = "null";
     }
     if($this->ed254_d_dataini == null ){
       $this->erro_sql = " Campo Data Inicial do Exercício não informado.";
       $this->erro_campo = "ed254_d_dataini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed254_d_datafim == null ){
       $this->ed254_d_datafim = "null";
     }
     if($this->ed254_c_tipo == null ){
       $this->erro_sql = " Campo Situação do Exercício não informado.";
       $this->erro_campo = "ed254_c_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed254_i_usuario == null ){
       $this->erro_sql = " Campo Usuário não informado.";
       $this->erro_campo = "ed254_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed254_d_datacad == null ){
       $this->erro_sql = " Campo Data do Cadastro não informado.";
       $this->erro_campo = "ed254_d_datacad_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed254_criterioacessofuncao == null ){
       $this->ed254_criterioacessofuncao = "0";
     }
     if($ed254_i_codigo == "" || $ed254_i_codigo == null ){
       $result = db_query("select nextval('escoladiretor_ed254_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: escoladiretor_ed254_i_codigo_seq do campo: ed254_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed254_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from escoladiretor_ed254_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed254_i_codigo)){
         $this->erro_sql = " Campo ed254_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed254_i_codigo = $ed254_i_codigo;
       }
     }
     if(($this->ed254_i_codigo == null) || ($this->ed254_i_codigo == "") ){
       $this->erro_sql = " Campo ed254_i_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into escoladiretor(
                                       ed254_i_codigo
                                      ,ed254_i_rechumano
                                      ,ed254_i_escola
                                      ,ed254_i_turno
                                      ,ed254_i_atolegal
                                      ,ed254_c_email
                                      ,ed254_d_dataini
                                      ,ed254_d_datafim
                                      ,ed254_c_tipo
                                      ,ed254_i_usuario
                                      ,ed254_d_datacad
                                      ,ed254_especificacaocriteriooutros
                                      ,ed254_criterioacessofuncao
                       )
                values (
                                $this->ed254_i_codigo
                               ,$this->ed254_i_rechumano
                               ,$this->ed254_i_escola
                               ,$this->ed254_i_turno
                               ,".($this->ed254_i_atolegal == "null" || $this->ed254_i_atolegal == ""?"null":$this->ed254_i_atolegal)."
                               ,'$this->ed254_c_email'
                               ,".($this->ed254_d_dataini == "null" || $this->ed254_d_dataini == ""?"null":"'".$this->ed254_d_dataini."'")."
                               ,".($this->ed254_d_datafim == "null" || $this->ed254_d_datafim == ""?"null":"'".$this->ed254_d_datafim."'")."
                               ,'$this->ed254_c_tipo'
                               ,$this->ed254_i_usuario
                               ,".($this->ed254_d_datacad == "null" || $this->ed254_d_datacad == ""?"null":"'".$this->ed254_d_datacad."'")."
                               ,'$this->ed254_especificacaocriteriooutros'
                               ,$this->ed254_criterioacessofuncao
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Diretores da Escola ($this->ed254_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Diretores da Escola já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Diretores da Escola ($this->ed254_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed254_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed254_i_codigo));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12514,'$this->ed254_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,2183,12514,'','".AddSlashes(pg_result($resaco,0,'ed254_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2183,12515,'','".AddSlashes(pg_result($resaco,0,'ed254_i_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2183,12516,'','".AddSlashes(pg_result($resaco,0,'ed254_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2183,12517,'','".AddSlashes(pg_result($resaco,0,'ed254_i_turno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2183,12551,'','".AddSlashes(pg_result($resaco,0,'ed254_i_atolegal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2183,12557,'','".AddSlashes(pg_result($resaco,0,'ed254_c_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2183,12553,'','".AddSlashes(pg_result($resaco,0,'ed254_d_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2183,12554,'','".AddSlashes(pg_result($resaco,0,'ed254_d_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2183,12555,'','".AddSlashes(pg_result($resaco,0,'ed254_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2183,12552,'','".AddSlashes(pg_result($resaco,0,'ed254_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2183,12556,'','".AddSlashes(pg_result($resaco,0,'ed254_d_datacad'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2183,1010460,'','".AddSlashes(pg_result($resaco,0,'ed254_especificacaocriteriooutros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2183,1010459,'','".AddSlashes(pg_result($resaco,0,'ed254_criterioacessofuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }

    public function alterar($ed254_i_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update escoladiretor set ";
     $virgula = "";
     if(trim($this->ed254_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed254_i_codigo"])){
       $sql  .= $virgula." ed254_i_codigo = $this->ed254_i_codigo ";
       $virgula = ",";
       if(trim($this->ed254_i_codigo) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed254_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed254_i_rechumano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed254_i_rechumano"])){
       $sql  .= $virgula." ed254_i_rechumano = $this->ed254_i_rechumano ";
       $virgula = ",";
       if(trim($this->ed254_i_rechumano) == null ){
         $this->erro_sql = " Campo Diretor não informado.";
         $this->erro_campo = "ed254_i_rechumano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed254_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed254_i_escola"])){
       $sql  .= $virgula." ed254_i_escola = $this->ed254_i_escola ";
       $virgula = ",";
       if(trim($this->ed254_i_escola) == null ){
         $this->erro_sql = " Campo Escola não informado.";
         $this->erro_campo = "ed254_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed254_i_turno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed254_i_turno"])){
       $sql  .= $virgula." ed254_i_turno = $this->ed254_i_turno ";
       $virgula = ",";
       if(trim($this->ed254_i_turno) == null ){
         $this->erro_sql = " Campo Turno não informado.";
         $this->erro_campo = "ed254_i_turno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed254_i_atolegal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed254_i_atolegal"])){
        if(trim($this->ed254_i_atolegal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed254_i_atolegal"])){
           $this->ed254_i_atolegal = "null" ;
        }
       $sql  .= $virgula." ed254_i_atolegal = $this->ed254_i_atolegal ";
       $virgula = ",";
     }
     if(trim($this->ed254_c_email)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed254_c_email"])){
       $sql  .= $virgula." ed254_c_email = '$this->ed254_c_email' ";
       $virgula = ",";
     }
     if(trim($this->ed254_d_dataini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed254_d_dataini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed254_d_dataini_dia"] !="") ){
       $sql  .= $virgula." ed254_d_dataini = '$this->ed254_d_dataini' ";
       $virgula = ",";
       if(trim($this->ed254_d_dataini) == null ){
         $this->erro_sql = " Campo Data Inicial do Exercício não informado.";
         $this->erro_campo = "ed254_d_dataini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed254_d_dataini_dia"])){
         $sql  .= $virgula." ed254_d_dataini = null ";
         $virgula = ",";
         if(trim($this->ed254_d_dataini) == null ){
           $this->erro_sql = " Campo Data Inicial do Exercício não informado.";
           $this->erro_campo = "ed254_d_dataini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed254_d_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed254_d_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed254_d_datafim_dia"] !="") ){
       $sql  .= $virgula." ed254_d_datafim = '$this->ed254_d_datafim' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed254_d_datafim_dia"])){
         $sql  .= $virgula." ed254_d_datafim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ed254_c_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed254_c_tipo"])){
       $sql  .= $virgula." ed254_c_tipo = '$this->ed254_c_tipo' ";
       $virgula = ",";
       if(trim($this->ed254_c_tipo) == null ){
         $this->erro_sql = " Campo Situação do Exercício não informado.";
         $this->erro_campo = "ed254_c_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed254_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed254_i_usuario"])){
       $sql  .= $virgula." ed254_i_usuario = $this->ed254_i_usuario ";
       $virgula = ",";
       if(trim($this->ed254_i_usuario) == null ){
         $this->erro_sql = " Campo Usuário não informado.";
         $this->erro_campo = "ed254_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed254_d_datacad)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed254_d_datacad_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed254_d_datacad_dia"] !="") ){
       $sql  .= $virgula." ed254_d_datacad = '$this->ed254_d_datacad' ";
       $virgula = ",";
       if(trim($this->ed254_d_datacad) == null ){
         $this->erro_sql = " Campo Data do Cadastro não informado.";
         $this->erro_campo = "ed254_d_datacad_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed254_d_datacad_dia"])){
         $sql  .= $virgula." ed254_d_datacad = null ";
         $virgula = ",";
         if(trim($this->ed254_d_datacad) == null ){
           $this->erro_sql = " Campo Data do Cadastro não informado.";
           $this->erro_campo = "ed254_d_datacad_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed254_especificacaocriteriooutros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed254_especificacaocriteriooutros"])){
       $sql  .= $virgula." ed254_especificacaocriteriooutros = '$this->ed254_especificacaocriteriooutros' ";
       $virgula = ",";
     }
     if(trim($this->ed254_criterioacessofuncao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed254_criterioacessofuncao"])){
        if(trim($this->ed254_criterioacessofuncao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed254_criterioacessofuncao"])){
           $this->ed254_criterioacessofuncao = "0" ;
        }
       $sql  .= $virgula." ed254_criterioacessofuncao = $this->ed254_criterioacessofuncao ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed254_i_codigo!=null){
       $sql .= " ed254_i_codigo = $this->ed254_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed254_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,12514,'$this->ed254_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed254_i_codigo"]) || $this->ed254_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,2183,12514,'".AddSlashes(pg_result($resaco,$conresaco,'ed254_i_codigo'))."','$this->ed254_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed254_i_rechumano"]) || $this->ed254_i_rechumano != "")
             $resac = db_query("insert into db_acount values($acount,2183,12515,'".AddSlashes(pg_result($resaco,$conresaco,'ed254_i_rechumano'))."','$this->ed254_i_rechumano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed254_i_escola"]) || $this->ed254_i_escola != "")
             $resac = db_query("insert into db_acount values($acount,2183,12516,'".AddSlashes(pg_result($resaco,$conresaco,'ed254_i_escola'))."','$this->ed254_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed254_i_turno"]) || $this->ed254_i_turno != "")
             $resac = db_query("insert into db_acount values($acount,2183,12517,'".AddSlashes(pg_result($resaco,$conresaco,'ed254_i_turno'))."','$this->ed254_i_turno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed254_i_atolegal"]) || $this->ed254_i_atolegal != "")
             $resac = db_query("insert into db_acount values($acount,2183,12551,'".AddSlashes(pg_result($resaco,$conresaco,'ed254_i_atolegal'))."','$this->ed254_i_atolegal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed254_c_email"]) || $this->ed254_c_email != "")
             $resac = db_query("insert into db_acount values($acount,2183,12557,'".AddSlashes(pg_result($resaco,$conresaco,'ed254_c_email'))."','$this->ed254_c_email',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed254_d_dataini"]) || $this->ed254_d_dataini != "")
             $resac = db_query("insert into db_acount values($acount,2183,12553,'".AddSlashes(pg_result($resaco,$conresaco,'ed254_d_dataini'))."','$this->ed254_d_dataini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed254_d_datafim"]) || $this->ed254_d_datafim != "")
             $resac = db_query("insert into db_acount values($acount,2183,12554,'".AddSlashes(pg_result($resaco,$conresaco,'ed254_d_datafim'))."','$this->ed254_d_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed254_c_tipo"]) || $this->ed254_c_tipo != "")
             $resac = db_query("insert into db_acount values($acount,2183,12555,'".AddSlashes(pg_result($resaco,$conresaco,'ed254_c_tipo'))."','$this->ed254_c_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed254_i_usuario"]) || $this->ed254_i_usuario != "")
             $resac = db_query("insert into db_acount values($acount,2183,12552,'".AddSlashes(pg_result($resaco,$conresaco,'ed254_i_usuario'))."','$this->ed254_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed254_d_datacad"]) || $this->ed254_d_datacad != "")
             $resac = db_query("insert into db_acount values($acount,2183,12556,'".AddSlashes(pg_result($resaco,$conresaco,'ed254_d_datacad'))."','$this->ed254_d_datacad',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed254_especificacaocriteriooutros"]) || $this->ed254_especificacaocriteriooutros != "")
             $resac = db_query("insert into db_acount values($acount,2183,1010460,'".AddSlashes(pg_result($resaco,$conresaco,'ed254_especificacaocriteriooutros'))."','$this->ed254_especificacaocriteriooutros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed254_criterioacessofuncao"]) || $this->ed254_criterioacessofuncao != "")
             $resac = db_query("insert into db_acount values($acount,2183,1010459,'".AddSlashes(pg_result($resaco,$conresaco,'ed254_criterioacessofuncao'))."','$this->ed254_criterioacessofuncao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Diretores da Escola não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed254_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Diretores da Escola não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed254_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed254_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }

    public function excluir($ed254_i_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed254_i_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,12514,'$ed254_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,2183,12514,'','".AddSlashes(pg_result($resaco,$iresaco,'ed254_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2183,12515,'','".AddSlashes(pg_result($resaco,$iresaco,'ed254_i_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2183,12516,'','".AddSlashes(pg_result($resaco,$iresaco,'ed254_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2183,12517,'','".AddSlashes(pg_result($resaco,$iresaco,'ed254_i_turno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2183,12551,'','".AddSlashes(pg_result($resaco,$iresaco,'ed254_i_atolegal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2183,12557,'','".AddSlashes(pg_result($resaco,$iresaco,'ed254_c_email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2183,12553,'','".AddSlashes(pg_result($resaco,$iresaco,'ed254_d_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2183,12554,'','".AddSlashes(pg_result($resaco,$iresaco,'ed254_d_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2183,12555,'','".AddSlashes(pg_result($resaco,$iresaco,'ed254_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2183,12552,'','".AddSlashes(pg_result($resaco,$iresaco,'ed254_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2183,12556,'','".AddSlashes(pg_result($resaco,$iresaco,'ed254_d_datacad'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2183,1010460,'','".AddSlashes(pg_result($resaco,$iresaco,'ed254_especificacaocriteriooutros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2183,1010459,'','".AddSlashes(pg_result($resaco,$iresaco,'ed254_criterioacessofuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from escoladiretor
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed254_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed254_i_codigo = $ed254_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Diretores da Escola não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed254_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Diretores da Escola não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed254_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ed254_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:escoladiretor";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

   public function sql_query($ed254_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

    $instit = db_getsession("DB_instit");
    $ano    = db_anofolha();
    $mes    = db_mesfolha();
    $sql  = "select {$campos}";
    $sql .= " from escoladiretor";
    $sql .= " inner join db_usuarios on db_usuarios.id_usuario = escoladiretor.ed254_i_usuario";
    $sql .= " inner join escola on escola.ed18_i_codigo = escoladiretor.ed254_i_escola";
    $sql .= " inner join bairro on bairro.j13_codi = escola.ed18_i_bairro";
    $sql .= " inner join ruas on ruas.j14_codigo = escola.ed18_i_rua";
    $sql .= " inner join db_depart on db_depart.coddepto = escola.ed18_i_codigo";
    $sql .= " left join atolegal on atolegal.ed05_i_codigo = escoladiretor.ed254_i_atolegal";
    $sql .= " inner join turno on turno.ed15_i_codigo = escoladiretor.ed254_i_turno";
    $sql .= " inner join rechumano on rechumano.ed20_i_codigo = escoladiretor.ed254_i_rechumano";
    $sql .= " left join rechumanopessoal on rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
    $sql .= " left join rhpessoal on rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal";
    $sql .= " left join cgm as cgmrh on cgmrh.z01_numcgm = rhpessoal.rh01_numcgm";
    $sql .= " left join rhpessoalmov on rh02_anousu  = $ano";
    $sql .= "  and rh02_mesusu  = $mes";
    $sql .= "  and rh02_regist  = rh01_regist";
    $sql .= "  and rh02_instit  = $instit";
    $sql .= " left join rhfuncao on rhfuncao.rh37_funcao = rhpessoal.rh01_funcao";
    $sql .= " and rh37_instit = rh02_instit";
    $sql .= " left join rechumanocgm on rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo";
    $sql .= " left join cgm as cgmcgm on cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm";
    $sql .= " left join tipoato on tipoato.ed83_i_codigo = atolegal.ed05_i_tipoato";

    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($ed254_i_codigo)) {
        $sql2 .= " where escoladiretor.ed254_i_codigo = $ed254_i_codigo ";
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

    public function sql_query_file($ed254_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from escoladiretor ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed254_i_codigo)){
         $sql2 .= " where escoladiretor.ed254_i_codigo = $ed254_i_codigo ";
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

   function sql_query_relatorio($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = explode('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $instit = db_getsession("DB_instit");
    $ano    = db_anofolha();
    $mes    = db_mesfolha();
    $sSql  .= " from historico ";
    $sSql  .= "      left  join atolegal  on  atolegal.ed05_i_codigo = escoladiretor.ed254_i_atolegal";
    $sSql  .= "      left  join tipoato  on  tipoato.ed83_i_codigo = atolegal.ed05_i_tipoato";
    $sSql  .= "      inner join turno  on  turno.ed15_i_codigo = escoladiretor.ed254_i_turno";
    $sSql  .= "      inner join rechumano  on  rechumano.ed20_i_codigo = escoladiretor.ed254_i_rechumano";
    $sSql  .= "      left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
    $sSql  .= "      left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal";
    $sSql  .= "      left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm";
    $sSql  .= "      left join rhpessoalmov on rh02_anousu  = $ano";
    $sSql  .= "                                and rh02_mesusu  = $mes";
    $sSql  .= "                                and rh02_regist  = rh01_regist";
    $sSql  .= "                                and rh02_instit  = $instit";
    $sSql  .= "      left join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao";
    $sSql  .= "                              and rh37_instit  = rh02_instit";
    $sSql  .= "      left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo";
    $sSql  .= "      left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm";
    $sSql2  = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where escoladiretor.ed254_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = explode('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }
   function sql_query_resultadofinal($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = explode('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $instit = db_getsession("DB_instit");
    $ano    = db_anofolha();
    $mes    = db_mesfolha();
    $sSql  .= " from escoladiretor ";
    $sSql   .= "     left  join atolegal  on  atolegal.ed05_i_codigo = escoladiretor.ed254_i_atolegal ";
    $sSql   .= "     left  join tipoato  on  tipoato.ed83_i_codigo = atolegal.ed05_i_tipoato ";
    $sSql   .= "     left join turno  on  turno.ed15_i_codigo = escoladiretor.ed254_i_turno ";
    $sSql   .= "     inner join rechumano  on  rechumano.ed20_i_codigo = escoladiretor.ed254_i_rechumano ";
    $sSql  .= "      LEFT JOIN rechumanoescola ON rechumanoescola.ed75_i_rechumano = rechumano.ed20_i_codigo ";
    $sSql  .= "      LEFT JOIN rechumanoativ ON rechumanoativ.ed22_i_rechumanoescola = rechumanoescola.ed75_i_codigo ";
    $sSql  .= "      LEFT JOIN atividaderh ON atividaderh.ed01_i_codigo = rechumanoativ.ed22_i_atividade ";
    $sSql   .= "     left join rechumanopessoal on rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
    $sSql   .= "     left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal ";
    $sSql   .= "     left join rhpessoalmov on rh02_anousu  = $ano ";
    $sSql   .= "                                and rh02_mesusu  = $mes ";
    $sSql   .= "                                and rh02_regist  = rh01_regist ";
    $sSql   .= "                                and rh02_instit  = $instit ";
    $sSql   .= "     left join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao and rh37_instit  = rh02_instit ";
    $sSql   .= "     left join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm ";
    $sSql   .= "     left join cgm as cgmrh on cgmrh.z01_numcgm = rhpessoal.rh01_numcgm";
    $sSql   .= "     left join rechumanocgm on rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo";
    $sSql   .= "     left join cgm as cgmcgm on cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm";

    $sSql2  = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where escoladiretor.ed254_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = explode('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }
}
