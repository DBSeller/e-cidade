<?php

class cl_slipplacaixarec
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
    public $k207_sequencial = 0; 
    public $k207_placaixarec = 0; 
    public $k207_slip = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 k207_sequencial = int4 = Sequencial 
                 k207_placaixarec = int4 = Sequencial da receita de uma planilha 
                 k207_slip = int4 = Codigo de um Slip 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("slipplacaixarec"); 
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
       $this->k207_sequencial = ($this->k207_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k207_sequencial"]:$this->k207_sequencial);
       $this->k207_placaixarec = ($this->k207_placaixarec == ""?@$GLOBALS["HTTP_POST_VARS"]["k207_placaixarec"]:$this->k207_placaixarec);
       $this->k207_slip = ($this->k207_slip == ""?@$GLOBALS["HTTP_POST_VARS"]["k207_slip"]:$this->k207_slip);
     }else{
       $this->k207_sequencial = ($this->k207_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k207_sequencial"]:$this->k207_sequencial);
     }
   }

    public function incluir($k207_sequencial)
    {
      $this->atualizacampos();
     if($this->k207_placaixarec == null ){ 
       $this->erro_sql = " Campo Sequencial da receita de uma planilha não informado.";
       $this->erro_campo = "k207_placaixarec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k207_slip == null ){ 
       $this->erro_sql = " Campo Codigo de um Slip não informado.";
       $this->erro_campo = "k207_slip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k207_sequencial == "" || $k207_sequencial == null ){
       $result = db_query("select nextval('slipplacaixarec_k207_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: slipplacaixarec_k207_sequencial_seq do campo: k207_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k207_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from slipplacaixarec_k207_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k207_sequencial)){
         $this->erro_sql = " Campo k207_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k207_sequencial = $k207_sequencial; 
       }
     }
     if(($this->k207_sequencial == null) || ($this->k207_sequencial == "") ){ 
       $this->erro_sql = " Campo k207_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into slipplacaixarec(
                                       k207_sequencial 
                                      ,k207_placaixarec 
                                      ,k207_slip 
                       )
                values (
                                $this->k207_sequencial 
                               ,$this->k207_placaixarec 
                               ,$this->k207_slip 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "slip das receitas de planilha ($this->k207_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "slip das receitas de planilha já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "slip das receitas de planilha ($this->k207_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->k207_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k207_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1013991,'$this->k207_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010899,1013991,'','".AddSlashes(pg_result($resaco,0,'k207_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010899,1013992,'','".AddSlashes(pg_result($resaco,0,'k207_placaixarec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010899,1013993,'','".AddSlashes(pg_result($resaco,0,'k207_slip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($k207_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update slipplacaixarec set ";
     $virgula = "";
     if(trim($this->k207_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k207_sequencial"])){ 
       $sql  .= $virgula." k207_sequencial = $this->k207_sequencial ";
       $virgula = ",";
       if(trim($this->k207_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "k207_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k207_placaixarec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k207_placaixarec"])){ 
       $sql  .= $virgula." k207_placaixarec = $this->k207_placaixarec ";
       $virgula = ",";
       if(trim($this->k207_placaixarec) == null ){ 
         $this->erro_sql = " Campo Sequencial da receita de uma planilha não informado.";
         $this->erro_campo = "k207_placaixarec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k207_slip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k207_slip"])){ 
       $sql  .= $virgula." k207_slip = $this->k207_slip ";
       $virgula = ",";
       if(trim($this->k207_slip) == null ){ 
         $this->erro_sql = " Campo Codigo de um Slip não informado.";
         $this->erro_campo = "k207_slip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k207_sequencial!=null){
       $sql .= " k207_sequencial = $this->k207_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k207_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1013991,'$this->k207_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k207_sequencial"]) || $this->k207_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010899,1013991,'".AddSlashes(pg_result($resaco,$conresaco,'k207_sequencial'))."','$this->k207_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k207_placaixarec"]) || $this->k207_placaixarec != "")
             $resac = db_query("insert into db_acount values($acount,1010899,1013992,'".AddSlashes(pg_result($resaco,$conresaco,'k207_placaixarec'))."','$this->k207_placaixarec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k207_slip"]) || $this->k207_slip != "")
             $resac = db_query("insert into db_acount values($acount,1010899,1013993,'".AddSlashes(pg_result($resaco,$conresaco,'k207_slip'))."','$this->k207_slip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "slip das receitas de planilha não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k207_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "slip das receitas de planilha não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k207_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->k207_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($k207_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($k207_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1013991,'$k207_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010899,1013991,'','".AddSlashes(pg_result($resaco,$iresaco,'k207_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010899,1013992,'','".AddSlashes(pg_result($resaco,$iresaco,'k207_placaixarec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010899,1013993,'','".AddSlashes(pg_result($resaco,$iresaco,'k207_slip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from slipplacaixarec
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($k207_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " k207_sequencial = $k207_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "slip das receitas de planilha não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k207_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "slip das receitas de planilha não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k207_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$k207_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:slipplacaixarec";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($k207_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from slipplacaixarec ";
     $sql .= "      inner join slip  on  slip.k17_codigo = slipplacaixarec.k207_slip";
     $sql .= "      inner join placaixarec  on  placaixarec.k81_seqpla = slipplacaixarec.k207_placaixarec";
     $sql .= "      inner join db_config  on  db_config.codigo = slip.k17_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = placaixarec.k81_numcgm";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = placaixarec.k81_receita";
     $sql .= "      inner join saltes  on  saltes.k13_conta = placaixarec.k81_conta";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = placaixarec.k81_codigo";
     $sql .= "      inner join placaixa  as a on   a.k80_codpla = placaixarec.k81_codpla";
     $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = placaixarec.k81_concarpeculiar";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($k207_sequencial)) {
         $sql2 .= " where slipplacaixarec.k207_sequencial = $k207_sequencial "; 
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

    public function sql_query_file($k207_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from slipplacaixarec ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($k207_sequencial)){
         $sql2 .= " where slipplacaixarec.k207_sequencial = $k207_sequencial "; 
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
