<?php

class cl_acordoalteracaocontratado
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
    public $ac60_sequencial = 0;
    public $ac60_acordo = 0;
    public $ac60_posicao = 0;
    public $ac60_anterior = 0;
    public $ac60_novo = 0;
   // cria propriedade com as variaveis do arquivo
    public $campos = "
                 ac60_sequencial = int4 = Código Alteração Contratado
                 ac60_acordo = int4 = Acordo
                 ac60_posicao = int4 = Posição do Acordo
                 ac60_anterior = int4 = Contratado Anterior
                 ac60_novo = int4 = Contratado Novo
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("acordoalteracaocontratado");
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
       $this->ac60_sequencial = ($this->ac60_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac60_sequencial"]:$this->ac60_sequencial);
       $this->ac60_acordo = ($this->ac60_acordo == ""?@$GLOBALS["HTTP_POST_VARS"]["ac60_acordo"]:$this->ac60_acordo);
       $this->ac60_posicao = ($this->ac60_posicao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac60_posicao"]:$this->ac60_posicao);
       $this->ac60_anterior = ($this->ac60_anterior == ""?@$GLOBALS["HTTP_POST_VARS"]["ac60_anterior"]:$this->ac60_anterior);
       $this->ac60_novo = ($this->ac60_novo == ""?@$GLOBALS["HTTP_POST_VARS"]["ac60_novo"]:$this->ac60_novo);
     }else{
       $this->ac60_sequencial = ($this->ac60_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac60_sequencial"]:$this->ac60_sequencial);
     }
   }

    public function incluir($ac60_sequencial)
    {
      $this->atualizacampos();
     if($this->ac60_acordo == null ){
       $this->erro_sql = " Campo Acordo não informado.";
       $this->erro_campo = "ac60_acordo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac60_posicao == null ){
       $this->erro_sql = " Campo Posição do Acordo não informado.";
       $this->erro_campo = "ac60_posicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac60_anterior == null ){
       $this->erro_sql = " Campo Contratado Anterior não informado.";
       $this->erro_campo = "ac60_anterior";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac60_novo == null ){
       $this->erro_sql = " Campo Contratado Novo não informado.";
       $this->erro_campo = "ac60_novo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac60_sequencial == "" || $ac60_sequencial == null ){
       $result = db_query("select nextval('acordoalteracaocontratado_ac60_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordoalteracaocontratado_ac60_sequencial_seq do campo: ac60_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ac60_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from acordoalteracaocontratado_ac60_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac60_sequencial)){
         $this->erro_sql = " Campo ac60_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac60_sequencial = $ac60_sequencial;
       }
     }
     if(($this->ac60_sequencial == null) || ($this->ac60_sequencial == "") ){
       $this->erro_sql = " Campo ac60_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordoalteracaocontratado(
                                       ac60_sequencial
                                      ,ac60_acordo
                                      ,ac60_posicao
                                      ,ac60_anterior
                                      ,ac60_novo
                       )
                values (
                                $this->ac60_sequencial
                               ,$this->ac60_acordo
                               ,$this->ac60_posicao
                               ,$this->ac60_anterior
                               ,$this->ac60_novo
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Alteração ou Cessão do Contratado ($this->ac60_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Alteração ou Cessão do Contratado já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Alteração ou Cessão do Contratado ($this->ac60_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ac60_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ac60_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1014494,'$this->ac60_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010987,1014494,'','".AddSlashes(pg_result($resaco,0,'ac60_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010987,1014495,'','".AddSlashes(pg_result($resaco,0,'ac60_acordo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010987,1014496,'','".AddSlashes(pg_result($resaco,0,'ac60_posicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010987,1014497,'','".AddSlashes(pg_result($resaco,0,'ac60_anterior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010987,1014498,'','".AddSlashes(pg_result($resaco,0,'ac60_novo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }

    public function alterar($ac60_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update acordoalteracaocontratado set ";
     $virgula = "";
     if(trim($this->ac60_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac60_sequencial"])){
       $sql  .= $virgula." ac60_sequencial = $this->ac60_sequencial ";
       $virgula = ",";
       if(trim($this->ac60_sequencial) == null ){
         $this->erro_sql = " Campo Código Alteração Contratado não informado.";
         $this->erro_campo = "ac60_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac60_acordo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac60_acordo"])){
       $sql  .= $virgula." ac60_acordo = $this->ac60_acordo ";
       $virgula = ",";
       if(trim($this->ac60_acordo) == null ){
         $this->erro_sql = " Campo Acordo não informado.";
         $this->erro_campo = "ac60_acordo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac60_posicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac60_posicao"])){
       $sql  .= $virgula." ac60_posicao = $this->ac60_posicao ";
       $virgula = ",";
       if(trim($this->ac60_posicao) == null ){
         $this->erro_sql = " Campo Posição do Acordo não informado.";
         $this->erro_campo = "ac60_posicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac60_anterior)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac60_anterior"])){
       $sql  .= $virgula." ac60_anterior = $this->ac60_anterior ";
       $virgula = ",";
       if(trim($this->ac60_anterior) == null ){
         $this->erro_sql = " Campo Contratado Anterior não informado.";
         $this->erro_campo = "ac60_anterior";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac60_novo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac60_novo"])){
       $sql  .= $virgula." ac60_novo = $this->ac60_novo ";
       $virgula = ",";
       if(trim($this->ac60_novo) == null ){
         $this->erro_sql = " Campo Contratado Novo não informado.";
         $this->erro_campo = "ac60_novo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ac60_sequencial!=null){
       $sql .= " ac60_sequencial = $this->ac60_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ac60_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1014494,'$this->ac60_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac60_sequencial"]) || $this->ac60_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010987,1014494,'".AddSlashes(pg_result($resaco,$conresaco,'ac60_sequencial'))."','$this->ac60_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac60_acordo"]) || $this->ac60_acordo != "")
             $resac = db_query("insert into db_acount values($acount,1010987,1014495,'".AddSlashes(pg_result($resaco,$conresaco,'ac60_acordo'))."','$this->ac60_acordo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac60_posicao"]) || $this->ac60_posicao != "")
             $resac = db_query("insert into db_acount values($acount,1010987,1014496,'".AddSlashes(pg_result($resaco,$conresaco,'ac60_posicao'))."','$this->ac60_posicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac60_anterior"]) || $this->ac60_anterior != "")
             $resac = db_query("insert into db_acount values($acount,1010987,1014497,'".AddSlashes(pg_result($resaco,$conresaco,'ac60_anterior'))."','$this->ac60_anterior',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ac60_novo"]) || $this->ac60_novo != "")
             $resac = db_query("insert into db_acount values($acount,1010987,1014498,'".AddSlashes(pg_result($resaco,$conresaco,'ac60_novo'))."','$this->ac60_novo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Alteração ou Cessão do Contratado não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac60_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração ou Cessão do Contratado não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac60_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ac60_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }

    public function excluir($ac60_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ac60_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1014494,'$ac60_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010987,1014494,'','".AddSlashes(pg_result($resaco,$iresaco,'ac60_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010987,1014495,'','".AddSlashes(pg_result($resaco,$iresaco,'ac60_acordo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010987,1014496,'','".AddSlashes(pg_result($resaco,$iresaco,'ac60_posicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010987,1014497,'','".AddSlashes(pg_result($resaco,$iresaco,'ac60_anterior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010987,1014498,'','".AddSlashes(pg_result($resaco,$iresaco,'ac60_novo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from acordoalteracaocontratado
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ac60_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ac60_sequencial = $ac60_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Alteração ou Cessão do Contratado não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac60_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração ou Cessão do Contratado não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac60_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ac60_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordoalteracaocontratado";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($ac60_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from acordoalteracaocontratado ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = acordoalteracaocontratado.ac60_anterior and  cgm.z01_numcgm = acordoalteracaocontratado.ac60_novo";
     $sql .= "      inner join acordo  on  acordo.ac16_sequencial = acordoalteracaocontratado.ac60_acordo";
     $sql .= "      inner join acordoposicao  on  acordoposicao.ac26_sequencial = acordoalteracaocontratado.ac60_posicao";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = acordo.ac16_coddepto and  db_depart.coddepto = acordo.ac16_deptoresponsavel";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ac60_sequencial)) {
         $sql2 .= " where acordoalteracaocontratado.ac60_sequencial = $ac60_sequencial ";
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

    public function sql_query_file($ac60_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from acordoalteracaocontratado ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ac60_sequencial)){
         $sql2 .= " where acordoalteracaocontratado.ac60_sequencial = $ac60_sequencial ";
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

    public function sql_query_contratado($posicao, $campo)
    {
        $campos = 'z01_cgccpf, z01_numcgm, z09_documento';
        $sql  = "select {$campos}";
        $sql .= "  from acordoalteracaocontratado ";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = acordoalteracaocontratado.{$campo} ";
        $sql .= "      left join cgmestrangeiro on cgmestrangeiro.z09_numcgm = acordoalteracaocontratado.{$campo} ";
        $sql .= "where ac60_posicao = {$posicao};";

        return $sql;
  }
}
