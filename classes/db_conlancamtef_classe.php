<?php

class cl_conlancamtef
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
    public $c137_sequencial = 0;
    public $c137_codlan = 0;
    public $c137_operacoesrealizadastef = 0;
   // cria propriedade com as variaveis do arquivo
    public $campos = "
                 c137_sequencial = int4 = Sequencial
                 c137_codlan = int4 = Codigo do lancamento
                 c137_operacoesrealizadastef = int4 = Operação TEF
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("conlancamtef");
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
       $this->c137_sequencial = ($this->c137_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c137_sequencial"]:$this->c137_sequencial);
       $this->c137_codlan = ($this->c137_codlan == ""?@$GLOBALS["HTTP_POST_VARS"]["c137_codlan"]:$this->c137_codlan);
       $this->c137_operacoesrealizadastef = ($this->c137_operacoesrealizadastef == ""?@$GLOBALS["HTTP_POST_VARS"]["c137_operacoesrealizadastef"]:$this->c137_operacoesrealizadastef);
     }else{
       $this->c137_sequencial = ($this->c137_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c137_sequencial"]:$this->c137_sequencial);
     }
   }

    public function incluir($c137_sequencial)
    {
      $this->atualizacampos();
     if($this->c137_codlan == null ){
       $this->erro_sql = " Campo Codigo do lancamento não informado.";
       $this->erro_campo = "c137_codlan";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c137_operacoesrealizadastef == null ){
       $this->erro_sql = " Campo Operação TEF não informado.";
       $this->erro_campo = "c137_operacoesrealizadastef";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c137_sequencial == "" || $c137_sequencial == null ){
       $result = db_query("select nextval('conlancamtef_c137_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conlancamtef_c137_sequencial_seq do campo: c137_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->c137_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from conlancamtef_c137_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c137_sequencial)){
         $this->erro_sql = " Campo c137_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c137_sequencial = $c137_sequencial;
       }
     }
     if(($this->c137_sequencial == null) || ($this->c137_sequencial == "") ){
       $this->erro_sql = " Campo c137_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conlancamtef(
                                       c137_sequencial
                                      ,c137_codlan
                                      ,c137_operacoesrealizadastef
                       )
                values (
                                $this->c137_sequencial
                               ,$this->c137_codlan
                               ,$this->c137_operacoesrealizadastef
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "conlancamtef ($this->c137_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "conlancamtef já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "conlancamtef ($this->c137_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->c137_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->c137_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1013258,'$this->c137_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010801,1013258,'','".AddSlashes(pg_result($resaco,0,'c137_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010801,1013259,'','".AddSlashes(pg_result($resaco,0,'c137_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010801,1013260,'','".AddSlashes(pg_result($resaco,0,'c137_operacoesrealizadastef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }

    public function alterar($c137_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update conlancamtef set ";
     $virgula = "";
     if(trim($this->c137_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c137_sequencial"])){
       $sql  .= $virgula." c137_sequencial = $this->c137_sequencial ";
       $virgula = ",";
       if(trim($this->c137_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "c137_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c137_codlan)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c137_codlan"])){
       $sql  .= $virgula." c137_codlan = $this->c137_codlan ";
       $virgula = ",";
       if(trim($this->c137_codlan) == null ){
         $this->erro_sql = " Campo Codigo do lancamento não informado.";
         $this->erro_campo = "c137_codlan";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c137_operacoesrealizadastef)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c137_operacoesrealizadastef"])){
       $sql  .= $virgula." c137_operacoesrealizadastef = $this->c137_operacoesrealizadastef ";
       $virgula = ",";
       if(trim($this->c137_operacoesrealizadastef) == null ){
         $this->erro_sql = " Campo Operação TEF não informado.";
         $this->erro_campo = "c137_operacoesrealizadastef";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c137_sequencial!=null){
       $sql .= " c137_sequencial = $this->c137_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->c137_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1013258,'$this->c137_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c137_sequencial"]) || $this->c137_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010801,1013258,'".AddSlashes(pg_result($resaco,$conresaco,'c137_sequencial'))."','$this->c137_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c137_codlan"]) || $this->c137_codlan != "")
             $resac = db_query("insert into db_acount values($acount,1010801,1013259,'".AddSlashes(pg_result($resaco,$conresaco,'c137_codlan'))."','$this->c137_codlan',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c137_operacoesrealizadastef"]) || $this->c137_operacoesrealizadastef != "")
             $resac = db_query("insert into db_acount values($acount,1010801,1013260,'".AddSlashes(pg_result($resaco,$conresaco,'c137_operacoesrealizadastef'))."','$this->c137_operacoesrealizadastef',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "conlancamtef não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c137_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "conlancamtef não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c137_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->c137_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }

    public function excluir($c137_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($c137_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1013258,'$c137_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010801,1013258,'','".AddSlashes(pg_result($resaco,$iresaco,'c137_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010801,1013259,'','".AddSlashes(pg_result($resaco,$iresaco,'c137_codlan'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010801,1013260,'','".AddSlashes(pg_result($resaco,$iresaco,'c137_operacoesrealizadastef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from conlancamtef
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($c137_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " c137_sequencial = $c137_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "conlancamtef não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c137_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "conlancamtef não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c137_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$c137_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:conlancamtef";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }



   public function sql_query_dadoslancamento($c137_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos}";
    $sql .= "  from conlancamtef ";
    $sql .= "      inner join conlancam  on  conlancam.c70_codlan = conlancamtef.c137_codlan";
    $sql .= "      inner join operacoesrealizadastef  on  operacoesrealizadastef.k198_sequencial = conlancamtef.c137_operacoesrealizadastef";
    $sql .= "      inner join operacoestef  on  operacoestef.k195_sequencial = operacoesrealizadastef.k198_operacaotef";
    $sql .= "      inner join conlancamcompl 	on  conlancam.c70_codlan  = conlancamcompl.c72_codlan ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($c137_sequencial)) {
        $sql2 .= " where conlancamtef.c137_sequencial = $c137_sequencial ";
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

    public function sql_query($c137_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from conlancamtef ";
     $sql .= "      inner join conlancam  on  conlancam.c70_codlan = conlancamtef.c137_codlan";
     $sql .= "      inner join operacoesrealizadastef  on  operacoesrealizadastef.k198_sequencial = conlancamtef.c137_operacoesrealizadastef";
     $sql .= "      inner join operacoestef  on  operacoestef.k195_sequencial = operacoesrealizadastef.k198_operacaotef";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($c137_sequencial)) {
         $sql2 .= " where conlancamtef.c137_sequencial = $c137_sequencial ";
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

    public function sql_query_file($c137_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from conlancamtef ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($c137_sequencial)){
         $sql2 .= " where conlancamtef.c137_sequencial = $c137_sequencial ";
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
