<?php

class cl_slipoperacaoextra
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
    public $k208_sequencial = 0;
    public $k208_recebimento = 0;
    public $k208_pagamento = 0;
   // cria propriedade com as variaveis do arquivo
    public $campos = "
                 k208_sequencial = int4 = Sequencial
                 k208_recebimento = int4 = Slip de Recebimento
                 k208_pagamento = int4 = Slip de Pagamento
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("slipoperacaoextra");
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
       $this->k208_sequencial = ($this->k208_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k208_sequencial"]:$this->k208_sequencial);
       $this->k208_recebimento = ($this->k208_recebimento == ""?@$GLOBALS["HTTP_POST_VARS"]["k208_recebimento"]:$this->k208_recebimento);
       $this->k208_pagamento = ($this->k208_pagamento == ""?@$GLOBALS["HTTP_POST_VARS"]["k208_pagamento"]:$this->k208_pagamento);
     }else{
       $this->k208_sequencial = ($this->k208_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k208_sequencial"]:$this->k208_sequencial);
     }
   }

    public function incluir($k208_sequencial)
    {
      $this->atualizacampos();
     if($this->k208_recebimento == null ){
       $this->erro_sql = " Campo Slip de Recebimento não informado.";
       $this->erro_campo = "k208_recebimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k208_pagamento == null ){
       $this->erro_sql = " Campo Slip de Pagamento não informado.";
       $this->erro_campo = "k208_pagamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k208_sequencial == "" || $k208_sequencial == null ){
       $result = db_query("select nextval('slipoperacaoextra_k208_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: slipoperacaoextra_k208_sequencial_seq do campo: k208_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->k208_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from slipoperacaoextra_k208_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k208_sequencial)){
         $this->erro_sql = " Campo k208_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k208_sequencial = $k208_sequencial;
       }
     }
     if(($this->k208_sequencial == null) || ($this->k208_sequencial == "") ){
       $this->erro_sql = " Campo k208_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into slipoperacaoextra(
                                       k208_sequencial
                                      ,k208_recebimento
                                      ,k208_pagamento
                       )
                values (
                                $this->k208_sequencial
                               ,$this->k208_recebimento
                               ,$this->k208_pagamento
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "slipoperacaoextra ($this->k208_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "slipoperacaoextra já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "slipoperacaoextra ($this->k208_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->k208_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k208_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1014003,'$this->k208_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010901,1014003,'','".AddSlashes(pg_result($resaco,0,'k208_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010901,1014004,'','".AddSlashes(pg_result($resaco,0,'k208_recebimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010901,1014005,'','".AddSlashes(pg_result($resaco,0,'k208_pagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }

    public function alterar($k208_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update slipoperacaoextra set ";
     $virgula = "";
     if(trim($this->k208_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k208_sequencial"])){
       $sql  .= $virgula." k208_sequencial = $this->k208_sequencial ";
       $virgula = ",";
       if(trim($this->k208_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "k208_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k208_recebimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k208_recebimento"])){
       $sql  .= $virgula." k208_recebimento = $this->k208_recebimento ";
       $virgula = ",";
       if(trim($this->k208_recebimento) == null ){
         $this->erro_sql = " Campo Slip de Recebimento não informado.";
         $this->erro_campo = "k208_recebimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k208_pagamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k208_pagamento"])){
       $sql  .= $virgula." k208_pagamento = $this->k208_pagamento ";
       $virgula = ",";
       if(trim($this->k208_pagamento) == null ){
         $this->erro_sql = " Campo Slip de Pagamento não informado.";
         $this->erro_campo = "k208_pagamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k208_sequencial!=null){
       $sql .= " k208_sequencial = $this->k208_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k208_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1014003,'$this->k208_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k208_sequencial"]) || $this->k208_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010901,1014003,'".AddSlashes(pg_result($resaco,$conresaco,'k208_sequencial'))."','$this->k208_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k208_recebimento"]) || $this->k208_recebimento != "")
             $resac = db_query("insert into db_acount values($acount,1010901,1014004,'".AddSlashes(pg_result($resaco,$conresaco,'k208_recebimento'))."','$this->k208_recebimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k208_pagamento"]) || $this->k208_pagamento != "")
             $resac = db_query("insert into db_acount values($acount,1010901,1014005,'".AddSlashes(pg_result($resaco,$conresaco,'k208_pagamento'))."','$this->k208_pagamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "slipoperacaoextra não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k208_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "slipoperacaoextra não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k208_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->k208_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }

    public function excluir($k208_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($k208_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1014003,'$k208_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010901,1014003,'','".AddSlashes(pg_result($resaco,$iresaco,'k208_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010901,1014004,'','".AddSlashes(pg_result($resaco,$iresaco,'k208_recebimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010901,1014005,'','".AddSlashes(pg_result($resaco,$iresaco,'k208_pagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from slipoperacaoextra
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($k208_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " k208_sequencial = $k208_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "slipoperacaoextra não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k208_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "slipoperacaoextra não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k208_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$k208_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:slipoperacaoextra";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($k208_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from slipoperacaoextra ";
     $sql .= "      inner join slip sliprecebimento on sliprecebimento.k17_codigo = slipoperacaoextra.k208_recebimento";
     $sql .= "      inner join slip slippagamento  on slippagamento.k17_codigo = slipoperacaoextra.k208_pagamento";
     $sql .= "      inner join db_config  on  db_config.codigo = sliprecebimento.k17_instit";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($k208_sequencial)) {
         $sql2 .= " where slipoperacaoextra.k208_sequencial = $k208_sequencial ";
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

    public function sql_query_file($k208_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from slipoperacaoextra ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($k208_sequencial)){
         $sql2 .= " where slipoperacaoextra.k208_sequencial = $k208_sequencial ";
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
