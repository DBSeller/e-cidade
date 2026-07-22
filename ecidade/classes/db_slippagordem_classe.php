<?php

class cl_slippagordem
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
    public $k209_sequencial = 0; 
    public $k209_pagordem = 0; 
    public $k209_slip = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 k209_sequencial = int4 = Sequencial Slip Ordem de Pagamento 
                 k209_pagordem = int4 = Codigo da OP 
                 k209_slip = int4 = Codigo do Slip da OP 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("slippagordem"); 
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
       $this->k209_sequencial = ($this->k209_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k209_sequencial"]:$this->k209_sequencial);
       $this->k209_pagordem = ($this->k209_pagordem == ""?@$GLOBALS["HTTP_POST_VARS"]["k209_pagordem"]:$this->k209_pagordem);
       $this->k209_slip = ($this->k209_slip == ""?@$GLOBALS["HTTP_POST_VARS"]["k209_slip"]:$this->k209_slip);
     }else{
       $this->k209_sequencial = ($this->k209_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k209_sequencial"]:$this->k209_sequencial);
     }
   }

    public function incluir($k209_sequencial)
    {
      $this->atualizacampos();
     if($this->k209_pagordem == null ){ 
       $this->erro_sql = " Campo Codigo da OP não informado.";
       $this->erro_campo = "k209_pagordem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k209_slip == null ){ 
       $this->erro_sql = " Campo Codigo do Slip da OP não informado.";
       $this->erro_campo = "k209_slip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k209_sequencial == "" || $k209_sequencial == null ){
       $result = db_query("select nextval('slippagordem_k209_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: slippagordem_k209_sequencial_seq do campo: k209_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k209_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from slippagordem_k209_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k209_sequencial)){
         $this->erro_sql = " Campo k209_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k209_sequencial = $k209_sequencial; 
       }
     }
     if(($this->k209_sequencial == null) || ($this->k209_sequencial == "") ){ 
       $this->erro_sql = " Campo k209_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into slippagordem(
                                       k209_sequencial 
                                      ,k209_pagordem 
                                      ,k209_slip 
                       )
                values (
                                $this->k209_sequencial 
                               ,$this->k209_pagordem 
                               ,$this->k209_slip 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "slippagordem ($this->k209_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "slippagordem já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "slippagordem ($this->k209_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->k209_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k209_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1014121,'$this->k209_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010921,1014121,'','".AddSlashes(pg_result($resaco,0,'k209_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010921,1014122,'','".AddSlashes(pg_result($resaco,0,'k209_pagordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010921,1014123,'','".AddSlashes(pg_result($resaco,0,'k209_slip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($k209_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update slippagordem set ";
     $virgula = "";
     if(trim($this->k209_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k209_sequencial"])){ 
       $sql  .= $virgula." k209_sequencial = $this->k209_sequencial ";
       $virgula = ",";
       if(trim($this->k209_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial Slip Ordem de Pagamento não informado.";
         $this->erro_campo = "k209_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k209_pagordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k209_pagordem"])){ 
       $sql  .= $virgula." k209_pagordem = $this->k209_pagordem ";
       $virgula = ",";
       if(trim($this->k209_pagordem) == null ){ 
         $this->erro_sql = " Campo Codigo da OP não informado.";
         $this->erro_campo = "k209_pagordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k209_slip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k209_slip"])){ 
       $sql  .= $virgula." k209_slip = $this->k209_slip ";
       $virgula = ",";
       if(trim($this->k209_slip) == null ){ 
         $this->erro_sql = " Campo Codigo do Slip da OP não informado.";
         $this->erro_campo = "k209_slip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k209_sequencial!=null){
       $sql .= " k209_sequencial = $this->k209_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k209_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1014121,'$this->k209_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k209_sequencial"]) || $this->k209_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010921,1014121,'".AddSlashes(pg_result($resaco,$conresaco,'k209_sequencial'))."','$this->k209_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k209_pagordem"]) || $this->k209_pagordem != "")
             $resac = db_query("insert into db_acount values($acount,1010921,1014122,'".AddSlashes(pg_result($resaco,$conresaco,'k209_pagordem'))."','$this->k209_pagordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k209_slip"]) || $this->k209_slip != "")
             $resac = db_query("insert into db_acount values($acount,1010921,1014123,'".AddSlashes(pg_result($resaco,$conresaco,'k209_slip'))."','$this->k209_slip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "slippagordem não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k209_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "slippagordem não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k209_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->k209_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($k209_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($k209_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1014121,'$k209_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010921,1014121,'','".AddSlashes(pg_result($resaco,$iresaco,'k209_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010921,1014122,'','".AddSlashes(pg_result($resaco,$iresaco,'k209_pagordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010921,1014123,'','".AddSlashes(pg_result($resaco,$iresaco,'k209_slip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from slippagordem
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($k209_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " k209_sequencial = $k209_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "slippagordem não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k209_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "slippagordem não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k209_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$k209_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:slippagordem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($k209_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from slippagordem ";
     $sql .= "      inner join slip  on  slip.k17_codigo = slippagordem.k209_slip";
     $sql .= "      inner join pagordem  on  pagordem.e50_codord = slippagordem.k209_pagordem";
     $sql .= "      inner join db_config  on  db_config.codigo = slip.k17_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = pagordem.e50_id_usuario";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = pagordem.e50_numemp";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($k209_sequencial)) {
         $sql2 .= " where slippagordem.k209_sequencial = $k209_sequencial "; 
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

    public function sql_query_file($k209_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from slippagordem ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($k209_sequencial)){
         $sql2 .= " where slippagordem.k209_sequencial = $k209_sequencial "; 
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
