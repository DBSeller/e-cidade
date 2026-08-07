<?php

class cl_diario_classe_bncc
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
    public $ed155_codigo = 0;
    public $ed155_regencia = 0;
    public $ed155_db_usuarios = 0;
    public $ed155_data_dia = null;
    public $ed155_data_mes = null;
    public $ed155_data_ano = null;
    public $ed155_data = null;
    public $ed155_conteudo = null;
    public $ed155_turmaturnoreferente = null;
   // cria propriedade com as variaveis do arquivo
    public $campos = "
                 ed155_codigo = int4 = Código
                 ed155_regencia = int4 = Regência
                 ed155_db_usuarios = int4 = Professor
                 ed155_data = date = Data
                 ed155_conteudo = text = Conteúdo
                 ed155_turmaturnoreferente = int4 = TurmaTurnoReferente
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("diario_classe_bncc");
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
       $this->ed155_codigo = ($this->ed155_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed155_codigo"]:$this->ed155_codigo);
       $this->ed155_regencia = ($this->ed155_regencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed155_regencia"]:$this->ed155_regencia);
       $this->ed155_db_usuarios = ($this->ed155_db_usuarios == ""?@$GLOBALS["HTTP_POST_VARS"]["ed155_db_usuarios"]:$this->ed155_db_usuarios);
       if($this->ed155_data == ""){
         $this->ed155_data_dia = ($this->ed155_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed155_data_dia"]:$this->ed155_data_dia);
         $this->ed155_data_mes = ($this->ed155_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed155_data_mes"]:$this->ed155_data_mes);
         $this->ed155_data_ano = ($this->ed155_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed155_data_ano"]:$this->ed155_data_ano);
         if($this->ed155_data_dia != ""){
            $this->ed155_data = $this->ed155_data_ano."-".$this->ed155_data_mes."-".$this->ed155_data_dia;
         }
       }
       $this->ed155_conteudo = ($this->ed155_conteudo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed155_conteudo"]:$this->ed155_conteudo);
       $this->ed155_turmaturnoreferente = ($this->ed155_turmaturnoreferente == ""?@$GLOBALS["HTTP_POST_VARS"]["ed155_turmaturnoreferente"]:$this->ed155_turmaturnoreferente);
     }else{
       $this->ed155_codigo = ($this->ed155_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed155_codigo"]:$this->ed155_codigo);
     }
   }

    public function incluir($ed155_codigo)
    {
      $this->atualizacampos();
     if($this->ed155_regencia == null ){
       $this->erro_sql = " Campo Regência não informado.";
       $this->erro_campo = "ed155_regencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed155_db_usuarios == null ){
       $this->erro_sql = " Campo Professor não informado.";
       $this->erro_campo = "ed155_db_usuarios";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed155_data == null ){
       $this->erro_sql = " Campo Data não informado.";
       $this->erro_campo = "ed155_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed155_conteudo == null ){
       $this->erro_sql = " Campo Conteúdo não informado.";
       $this->erro_campo = "ed155_conteudo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed155_turmaturnoreferente == "" || $this->ed155_turmaturnoreferente == null ){
       $this->ed155_turmaturnoreferente = "null";
     }
     if($ed155_codigo == "" || $ed155_codigo == null ){
       $result = db_query("select nextval('diario_classe_bncc_ed155_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: diario_classe_bncc_ed155_codigo_seq do campo: ed155_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed155_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from diario_classe_bncc_ed155_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed155_codigo)){
         $this->erro_sql = " Campo ed155_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed155_codigo = $ed155_codigo;
       }
     }
     if(($this->ed155_codigo == null) || ($this->ed155_codigo == "") ){
       $this->erro_sql = " Campo ed155_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into diario_classe_bncc(
                                       ed155_codigo
                                      ,ed155_regencia
                                      ,ed155_db_usuarios
                                      ,ed155_data
                                      ,ed155_conteudo
                                      ,ed155_turmaturnoreferente
                       )
                values (
                                $this->ed155_codigo
                               ,$this->ed155_regencia
                               ,$this->ed155_db_usuarios
                               ,".($this->ed155_data == "null" || $this->ed155_data == ""?"null":"'".$this->ed155_data."'")."
                               ,'$this->ed155_conteudo'
                               ,$this->ed155_turmaturnoreferente
                      )";
        $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->ed155_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->ed155_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed155_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed155_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1011000,'$this->ed155_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010520,1011000,'','".AddSlashes(pg_result($resaco,0,'ed155_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010520,1011004,'','".AddSlashes(pg_result($resaco,0,'ed155_regencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010520,1011003,'','".AddSlashes(pg_result($resaco,0,'ed155_db_usuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010520,1011005,'','".AddSlashes(pg_result($resaco,0,'ed155_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010520,1011006,'','".AddSlashes(pg_result($resaco,0,'ed155_conteudo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010520,1013167,'','".AddSlashes(pg_result($resaco,0,'ed155_turmaturnoreferente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }

    public function alterar($ed155_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update diario_classe_bncc set ";
     $virgula = "";
     if(trim($this->ed155_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed155_codigo"])){
       $sql  .= $virgula." ed155_codigo = $this->ed155_codigo ";
       $virgula = ",";
       if(trim($this->ed155_codigo) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed155_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed155_regencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed155_regencia"])){
       $sql  .= $virgula." ed155_regencia = $this->ed155_regencia ";
       $virgula = ",";
       if(trim($this->ed155_regencia) == null ){
         $this->erro_sql = " Campo Regência não informado.";
         $this->erro_campo = "ed155_regencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed155_db_usuarios)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed155_db_usuarios"])){
       $sql  .= $virgula." ed155_db_usuarios = $this->ed155_db_usuarios ";
       $virgula = ",";
       if(trim($this->ed155_db_usuarios) == null ){
         $this->erro_sql = " Campo Professor não informado.";
         $this->erro_campo = "ed155_db_usuarios";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed155_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed155_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed155_data_dia"] !="") ){
       $sql  .= $virgula." ed155_data = '$this->ed155_data' ";
       $virgula = ",";
       if(trim($this->ed155_data) == null ){
         $this->erro_sql = " Campo Data não informado.";
         $this->erro_campo = "ed155_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed155_data_dia"])){
         $sql  .= $virgula." ed155_data = null ";
         $virgula = ",";
         if(trim($this->ed155_data) == null ){
           $this->erro_sql = " Campo Data não informado.";
           $this->erro_campo = "ed155_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed155_conteudo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed155_conteudo"])){
       $sql  .= $virgula." ed155_conteudo = '$this->ed155_conteudo' ";
       $virgula = ",";
       if(trim($this->ed155_conteudo) == null ){
         $this->erro_sql = " Campo Conteúdo não informado.";
         $this->erro_campo = "ed155_conteudo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed155_turmaturnoreferente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed155_turmaturnoreferente"])){
        if(trim($this->ed155_turmaturnoreferente)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed155_turmaturnoreferente"])){
           $this->ed155_turmaturnoreferente = null ;
        }
       $sql  .= $virgula." ed155_turmaturnoreferente = $this->ed155_turmaturnoreferente ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed155_codigo!=null){
       $sql .= " ed155_codigo = $this->ed155_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed155_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1011000,'$this->ed155_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed155_codigo"]) || $this->ed155_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010520,1011000,'".AddSlashes(pg_result($resaco,$conresaco,'ed155_codigo'))."','$this->ed155_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed155_regencia"]) || $this->ed155_regencia != "")
             $resac = db_query("insert into db_acount values($acount,1010520,1011004,'".AddSlashes(pg_result($resaco,$conresaco,'ed155_regencia'))."','$this->ed155_regencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed155_db_usuarios"]) || $this->ed155_db_usuarios != "")
             $resac = db_query("insert into db_acount values($acount,1010520,1011003,'".AddSlashes(pg_result($resaco,$conresaco,'ed155_db_usuarios'))."','$this->ed155_db_usuarios',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed155_data"]) || $this->ed155_data != "")
             $resac = db_query("insert into db_acount values($acount,1010520,1011005,'".AddSlashes(pg_result($resaco,$conresaco,'ed155_data'))."','$this->ed155_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed155_conteudo"]) || $this->ed155_conteudo != "")
             $resac = db_query("insert into db_acount values($acount,1010520,1011006,'".AddSlashes(pg_result($resaco,$conresaco,'ed155_conteudo'))."','$this->ed155_conteudo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed155_turmaturnoreferente"]) || $this->ed155_turmaturnoreferente != "")
             $resac = db_query("insert into db_acount values($acount,1010520,1013167,'".AddSlashes(pg_result($resaco,$conresaco,'ed155_turmaturnoreferente'))."','$this->ed155_turmaturnoreferente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed155_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = " não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed155_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed155_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }

    public function excluir($ed155_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed155_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1011000,'$ed155_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010520,1011000,'','".AddSlashes(pg_result($resaco,$iresaco,'ed155_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010520,1011004,'','".AddSlashes(pg_result($resaco,$iresaco,'ed155_regencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010520,1011003,'','".AddSlashes(pg_result($resaco,$iresaco,'ed155_db_usuarios'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010520,1011005,'','".AddSlashes(pg_result($resaco,$iresaco,'ed155_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010520,1011006,'','".AddSlashes(pg_result($resaco,$iresaco,'ed155_conteudo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010520,1013167,'','".AddSlashes(pg_result($resaco,$iresaco,'ed155_turmaturnoreferente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from diario_classe_bncc
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed155_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed155_codigo = $ed155_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed155_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = " não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed155_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ed155_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:diario_classe_bncc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($ed155_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from diario_classe_bncc ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = diario_classe_bncc.ed155_db_usuarios";
     $sql .= "      left  join turmaturnoreferente  on  turmaturnoreferente.ed336_codigo = diario_classe_bncc.ed155_turmaturnoreferente";
     $sql .= "      inner join regencia  on  regencia.ed59_i_codigo = diario_classe_bncc.ed155_regencia";
     $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = regencia.ed59_i_disciplina";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = regencia.ed59_i_serie";
     $sql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = regencia.ed59_procedimento";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = regencia.ed59_i_turma";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed155_codigo)) {
         $sql2 .= " where diario_classe_bncc.ed155_codigo = $ed155_codigo ";
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

    public function sql_query_file($ed155_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from diario_classe_bncc ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed155_codigo)){
         $sql2 .= " where diario_classe_bncc.ed155_codigo = $ed155_codigo ";
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
