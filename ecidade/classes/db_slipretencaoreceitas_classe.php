<?php

class cl_slipretencaoreceitas
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
    public $k206_sequencial = 0; 
    public $k206_retencaoreceitas = 0; 
    public $k206_slip = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 k206_sequencial = int4 = Sequencial 
                 k206_retencaoreceitas = int4 = Receitas da Retenção 
                 k206_slip = int4 = Slip 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("slipretencaoreceitas"); 
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
       $this->k206_sequencial = ($this->k206_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k206_sequencial"]:$this->k206_sequencial);
       $this->k206_retencaoreceitas = ($this->k206_retencaoreceitas == ""?@$GLOBALS["HTTP_POST_VARS"]["k206_retencaoreceitas"]:$this->k206_retencaoreceitas);
       $this->k206_slip = ($this->k206_slip == ""?@$GLOBALS["HTTP_POST_VARS"]["k206_slip"]:$this->k206_slip);
     }else{
       $this->k206_sequencial = ($this->k206_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k206_sequencial"]:$this->k206_sequencial);
     }
   }

    public function incluir($k206_sequencial)
    {
      $this->atualizacampos();
     if($this->k206_retencaoreceitas == null ){ 
       $this->erro_sql = " Campo Receitas da Retenção não informado.";
       $this->erro_campo = "k206_retencaoreceitas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k206_slip == null ){ 
       $this->erro_sql = " Campo Slip não informado.";
       $this->erro_campo = "k206_slip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k206_sequencial == "" || $k206_sequencial == null ){
       $result = db_query("select nextval('slipretencaoreceitas_k206_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: slipretencaoreceitas_k206_sequencial_seq do campo: k206_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k206_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from slipretencaoreceitas_k206_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k206_sequencial)){
         $this->erro_sql = " Campo k206_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k206_sequencial = $k206_sequencial; 
       }
     }
     if(($this->k206_sequencial == null) || ($this->k206_sequencial == "") ){ 
       $this->erro_sql = " Campo k206_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into slipretencaoreceitas(
                                       k206_sequencial 
                                      ,k206_retencaoreceitas 
                                      ,k206_slip 
                       )
                values (
                                $this->k206_sequencial 
                               ,$this->k206_retencaoreceitas 
                               ,$this->k206_slip 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Slip de Receita de Retencao ($this->k206_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Slip de Receita de Retencao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Slip de Receita de Retencao ($this->k206_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->k206_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k206_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1013977,'$this->k206_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010897,1013977,'','".AddSlashes(pg_result($resaco,0,'k206_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010897,1013978,'','".AddSlashes(pg_result($resaco,0,'k206_retencaoreceitas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010897,1013979,'','".AddSlashes(pg_result($resaco,0,'k206_slip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($k206_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update slipretencaoreceitas set ";
     $virgula = "";
     if(trim($this->k206_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k206_sequencial"])){ 
       $sql  .= $virgula." k206_sequencial = $this->k206_sequencial ";
       $virgula = ",";
       if(trim($this->k206_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "k206_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k206_retencaoreceitas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k206_retencaoreceitas"])){ 
       $sql  .= $virgula." k206_retencaoreceitas = $this->k206_retencaoreceitas ";
       $virgula = ",";
       if(trim($this->k206_retencaoreceitas) == null ){ 
         $this->erro_sql = " Campo Receitas da Retenção não informado.";
         $this->erro_campo = "k206_retencaoreceitas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k206_slip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k206_slip"])){ 
       $sql  .= $virgula." k206_slip = $this->k206_slip ";
       $virgula = ",";
       if(trim($this->k206_slip) == null ){ 
         $this->erro_sql = " Campo Slip não informado.";
         $this->erro_campo = "k206_slip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k206_sequencial!=null){
       $sql .= " k206_sequencial = $this->k206_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k206_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1013977,'$this->k206_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k206_sequencial"]) || $this->k206_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010897,1013977,'".AddSlashes(pg_result($resaco,$conresaco,'k206_sequencial'))."','$this->k206_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k206_retencaoreceitas"]) || $this->k206_retencaoreceitas != "")
             $resac = db_query("insert into db_acount values($acount,1010897,1013978,'".AddSlashes(pg_result($resaco,$conresaco,'k206_retencaoreceitas'))."','$this->k206_retencaoreceitas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k206_slip"]) || $this->k206_slip != "")
             $resac = db_query("insert into db_acount values($acount,1010897,1013979,'".AddSlashes(pg_result($resaco,$conresaco,'k206_slip'))."','$this->k206_slip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Slip de Receita de Retencao não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k206_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Slip de Receita de Retencao não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k206_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->k206_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($k206_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($k206_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1013977,'$k206_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010897,1013977,'','".AddSlashes(pg_result($resaco,$iresaco,'k206_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010897,1013978,'','".AddSlashes(pg_result($resaco,$iresaco,'k206_retencaoreceitas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010897,1013979,'','".AddSlashes(pg_result($resaco,$iresaco,'k206_slip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from slipretencaoreceitas
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($k206_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " k206_sequencial = $k206_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Slip de Receita de Retencao não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k206_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Slip de Receita de Retencao não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k206_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$k206_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:slipretencaoreceitas";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($k206_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from slipretencaoreceitas ";
     $sql .= "      inner join slip  on  slip.k17_codigo = slipretencaoreceitas.k206_slip";
     $sql .= "      inner join retencaoreceitas  on  retencaoreceitas.e23_sequencial = slipretencaoreceitas.k206_retencaoreceitas";
     $sql .= "      inner join db_config  on  db_config.codigo = slip.k17_instit";
     $sql .= "      inner join retencaotiporec  on  retencaotiporec.e21_sequencial = retencaoreceitas.e23_retencaotiporec";
     $sql .= "      inner join retencaopagordem  on  retencaopagordem.e20_sequencial = retencaoreceitas.e23_retencaopagordem";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($k206_sequencial)) {
         $sql2 .= " where slipretencaoreceitas.k206_sequencial = $k206_sequencial "; 
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

    public function sql_query_file($k206_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from slipretencaoreceitas ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($k206_sequencial)){
         $sql2 .= " where slipretencaoreceitas.k206_sequencial = $k206_sequencial "; 
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
