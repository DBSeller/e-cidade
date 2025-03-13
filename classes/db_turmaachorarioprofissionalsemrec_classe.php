<?php

class cl_turmaachorarioprofissionalsemrec
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
    public $ed176_sequencial = 0;
    public $ed176_turmaac = 0;
    public $ed176_funcaoatividade = 0;
    public $ed176_rechumano = 0;
    public $ed176_diasemana = 0;
    public $ed176_horainicial = null;
    public $ed176_horafinal = null;
   // cria propriedade com as variaveis do arquivo
    public $campos = "
                 ed176_sequencial = int8 = Codigo
                 ed176_turmaac = int8 = turmaac
                 ed176_funcaoatividade = int8 = funcaoatividade
                 ed176_rechumano = int8 = rechumano
                 ed176_diasemana = int8 = diasemana
                 ed176_horainicial = varchar(5) = horainicial
                 ed176_horafinal = varchar(5) = horafinal
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("turmaachorarioprofissionalsemrec");
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
       $this->ed176_sequencial = ($this->ed176_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed176_sequencial"]:$this->ed176_sequencial);
       $this->ed176_turmaac = ($this->ed176_turmaac == ""?@$GLOBALS["HTTP_POST_VARS"]["ed176_turmaac"]:$this->ed176_turmaac);
       $this->ed176_funcaoatividade = ($this->ed176_funcaoatividade == ""?@$GLOBALS["HTTP_POST_VARS"]["ed176_funcaoatividade"]:$this->ed176_funcaoatividade);
       $this->ed176_rechumano = ($this->ed176_rechumano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed176_rechumano"]:$this->ed176_rechumano);
       $this->ed176_diasemana = ($this->ed176_diasemana == ""?@$GLOBALS["HTTP_POST_VARS"]["ed176_diasemana"]:$this->ed176_diasemana);
       $this->ed176_horainicial = ($this->ed176_horainicial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed176_horainicial"]:$this->ed176_horainicial);
       $this->ed176_horafinal = ($this->ed176_horafinal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed176_horafinal"]:$this->ed176_horafinal);
     }else{
       $this->ed176_sequencial = ($this->ed176_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed176_sequencial"]:$this->ed176_sequencial);
     }
   }

    public function incluir($ed176_sequencial)
    {
      $this->atualizacampos();
     if($this->ed176_turmaac == null ){
       $this->erro_sql = " Campo turmaac não informado.";
       $this->erro_campo = "ed176_turmaac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed176_funcaoatividade == null ){
       $this->ed176_funcaoatividade = "0";
     }
     if($this->ed176_rechumano == null ){
       $this->ed176_rechumano = "0";
     }
     if($this->ed176_diasemana == null ){
       $this->erro_sql = " Campo diasemana não informado.";
       $this->erro_campo = "ed176_diasemana";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed176_horainicial == null ){
       $this->erro_sql = " Campo horainicial não informado.";
       $this->erro_campo = "ed176_horainicial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
        if ($ed176_sequencial == "" || $ed176_sequencial == null) {
            $result = db_query("select nextval('turmaachorarioprofissionalsemrec_ed176_sequencial_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: turmaachorarioprofissionalsemrec_ed176_sequencial_seq do campo: ed176_sequencial";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->ed176_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from turmaachorarioprofissionalsemrec_ed176_sequencial_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $ed176_sequencial)) {
                $this->erro_sql = " Campo ed176_sequencial maior que Último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->ed176_sequencial = $ed176_sequencial;
            }
        }
        if (($this->ed176_sequencial == null) || ($this->ed176_sequencial == "")) {
            $this->erro_sql = " Campo ed176_sequencial no declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        $sql = "insert into turmaachorarioprofissionalsemrec(
                                       ed176_sequencial
                                      ,ed176_turmaac
                                      ,ed176_funcaoatividade
                                      ,ed176_rechumano
                                      ,ed176_diasemana
                                      ,ed176_horainicial
                                      ,ed176_horafinal
                       )
                values (
                                $this->ed176_sequencial
                               ,$this->ed176_turmaac
                               ,$this->ed176_funcaoatividade
                               ,$this->ed176_rechumano
                               ,$this->ed176_diasemana
                               ,'$this->ed176_horainicial'
                               ,'$this->ed176_horafinal'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "turmaac horario profissional sem rechumano ($this->ed176_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "turmaac horario profissional sem rechumano já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "turmaac horario profissional sem rechumano ($this->ed176_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed176_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed176_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1013299,'$this->ed176_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010808,1013299,'','".AddSlashes(pg_result($resaco,0,'ed176_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010808,1013300,'','".AddSlashes(pg_result($resaco,0,'ed176_turmaac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010808,1013301,'','".AddSlashes(pg_result($resaco,0,'ed176_funcaoatividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010808,1013302,'','".AddSlashes(pg_result($resaco,0,'ed176_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010808,1013303,'','".AddSlashes(pg_result($resaco,0,'ed176_diasemana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010808,1013304,'','".AddSlashes(pg_result($resaco,0,'ed176_horainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010808,1013305,'','".AddSlashes(pg_result($resaco,0,'ed176_horafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }

    public function alterar($ed176_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update turmaachorarioprofissionalsemrec set ";
     $virgula = "";
     if(trim($this->ed176_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed176_sequencial"])){
       $sql  .= $virgula." ed176_sequencial = $this->ed176_sequencial ";
       $virgula = ",";
       if(trim($this->ed176_sequencial) == null ){
         $this->erro_sql = " Campo Codigo não informado.";
         $this->erro_campo = "ed176_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed176_turmaac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed176_turmaac"])){
       $sql  .= $virgula." ed176_turmaac = $this->ed176_turmaac ";
       $virgula = ",";
       if(trim($this->ed176_turmaac) == null ){
         $this->erro_sql = " Campo turmaac não informado.";
         $this->erro_campo = "ed176_turmaac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed176_funcaoatividade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed176_funcaoatividade"])){
        if(trim($this->ed176_funcaoatividade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed176_funcaoatividade"])){
           $this->ed176_funcaoatividade = "0" ;
        }
       $sql  .= $virgula." ed176_funcaoatividade = $this->ed176_funcaoatividade ";
       $virgula = ",";
     }
     if(trim($this->ed176_rechumano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed176_rechumano"])){
        if(trim($this->ed176_rechumano)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed176_rechumano"])){
           $this->ed176_rechumano = "0" ;
        }
       $sql  .= $virgula." ed176_rechumano = $this->ed176_rechumano ";
       $virgula = ",";
     }
     if(trim($this->ed176_diasemana)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed176_diasemana"])){
       $sql  .= $virgula." ed176_diasemana = $this->ed176_diasemana ";
       $virgula = ",";
       if(trim($this->ed176_diasemana) == null ){
         $this->erro_sql = " Campo diasemana não informado.";
         $this->erro_campo = "ed176_diasemana";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed176_horainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed176_horainicial"])){
       $sql  .= $virgula." ed176_horainicial = '$this->ed176_horainicial' ";
       $virgula = ",";
       if(trim($this->ed176_horainicial) == null ){
         $this->erro_sql = " Campo horainicial não informado.";
         $this->erro_campo = "ed176_horainicial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed176_horafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed176_horafinal"])){
       $sql  .= $virgula." ed176_horafinal = '$this->ed176_horafinal' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed176_sequencial!=null){
       $sql .= " ed176_sequencial = $this->ed176_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed176_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1013299,'$this->ed176_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed176_sequencial"]) || $this->ed176_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010808,1013299,'".AddSlashes(pg_result($resaco,$conresaco,'ed176_sequencial'))."','$this->ed176_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed176_turmaac"]) || $this->ed176_turmaac != "")
             $resac = db_query("insert into db_acount values($acount,1010808,1013300,'".AddSlashes(pg_result($resaco,$conresaco,'ed176_turmaac'))."','$this->ed176_turmaac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed176_funcaoatividade"]) || $this->ed176_funcaoatividade != "")
             $resac = db_query("insert into db_acount values($acount,1010808,1013301,'".AddSlashes(pg_result($resaco,$conresaco,'ed176_funcaoatividade'))."','$this->ed176_funcaoatividade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed176_rechumano"]) || $this->ed176_rechumano != "")
             $resac = db_query("insert into db_acount values($acount,1010808,1013302,'".AddSlashes(pg_result($resaco,$conresaco,'ed176_rechumano'))."','$this->ed176_rechumano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed176_diasemana"]) || $this->ed176_diasemana != "")
             $resac = db_query("insert into db_acount values($acount,1010808,1013303,'".AddSlashes(pg_result($resaco,$conresaco,'ed176_diasemana'))."','$this->ed176_diasemana',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed176_horainicial"]) || $this->ed176_horainicial != "")
             $resac = db_query("insert into db_acount values($acount,1010808,1013304,'".AddSlashes(pg_result($resaco,$conresaco,'ed176_horainicial'))."','$this->ed176_horainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed176_horafinal"]) || $this->ed176_horafinal != "")
             $resac = db_query("insert into db_acount values($acount,1010808,1013305,'".AddSlashes(pg_result($resaco,$conresaco,'ed176_horafinal'))."','$this->ed176_horafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "turmaac horario profissional sem rechumano não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed176_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "turmaac horario profissional sem rechumano não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed176_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed176_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }

    public function excluir($ed176_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed176_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1013299,'$ed176_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010808,1013299,'','".AddSlashes(pg_result($resaco,$iresaco,'ed176_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010808,1013300,'','".AddSlashes(pg_result($resaco,$iresaco,'ed176_turmaac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010808,1013301,'','".AddSlashes(pg_result($resaco,$iresaco,'ed176_funcaoatividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010808,1013302,'','".AddSlashes(pg_result($resaco,$iresaco,'ed176_rechumano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010808,1013303,'','".AddSlashes(pg_result($resaco,$iresaco,'ed176_diasemana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010808,1013304,'','".AddSlashes(pg_result($resaco,$iresaco,'ed176_horainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010808,1013305,'','".AddSlashes(pg_result($resaco,$iresaco,'ed176_horafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from turmaachorarioprofissionalsemrec
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed176_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed176_sequencial = $ed176_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "turmaac horario profissional sem rechumano não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed176_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "turmaac horario profissional sem rechumano não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed176_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ed176_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:turmaachorarioprofissionalsemrec";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($ed176_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from turmaachorarioprofissionalsemrec ";
     $sql .= "      inner join turmaac  on  turmaac.ed268_i_codigo = turmaachorarioprofissionalsemrec.ed176_turmaac";
     $sql .= "      inner join diasemana  on  diasemana.ed32_i_codigo = turmaachorarioprofissionalsemrec.ed176_diasemana";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = turmaac.ed268_i_escola";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = turmaac.ed268_i_turno";
     $sql .= "      left  join sala  on  sala.ed16_i_codigo = turmaac.ed268_i_sala";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turmaac.ed268_i_calendario";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed176_sequencial)) {
         $sql2 .= " where turmaachorarioprofissionalsemrec.ed176_sequencial = $ed176_sequencial ";
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

    public function sql_query_file($ed176_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from turmaachorarioprofissionalsemrec ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed176_sequencial)){
         $sql2 .= " where turmaachorarioprofissionalsemrec.ed176_sequencial = $ed176_sequencial ";
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
