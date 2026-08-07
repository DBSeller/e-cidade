<?php

class cl_rhlocaltrabequipamentoprotecaoepi
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
    public $rh259_sequencial = 0; 
    public $rh259_rhlocaltrabequipamentoprotecao = 0; 
    public $rh259_documentoavaliacao = null; 
    public $rh259_descricao = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh259_sequencial = int4 = Sequencial 
                 rh259_rhlocaltrabequipamentoprotecao = int4 = Sequencial equipamento proteção 
                 rh259_documentoavaliacao = varchar(255) = CA ou Documento de Avaliação 
                 rh259_descricao = text = Descrição 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhlocaltrabequipamentoprotecaoepi"); 
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
       $this->rh259_sequencial = ($this->rh259_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh259_sequencial"]:$this->rh259_sequencial);
       $this->rh259_rhlocaltrabequipamentoprotecao = ($this->rh259_rhlocaltrabequipamentoprotecao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh259_rhlocaltrabequipamentoprotecao"]:$this->rh259_rhlocaltrabequipamentoprotecao);
       $this->rh259_documentoavaliacao = ($this->rh259_documentoavaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh259_documentoavaliacao"]:$this->rh259_documentoavaliacao);
       $this->rh259_descricao = ($this->rh259_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh259_descricao"]:$this->rh259_descricao);
     }else{
       $this->rh259_sequencial = ($this->rh259_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh259_sequencial"]:$this->rh259_sequencial);
     }
   }

    public function incluir($rh259_sequencial)
    {
      $this->atualizacampos();
     if($this->rh259_rhlocaltrabequipamentoprotecao == null ){ 
       $this->erro_sql = " Campo Sequencial equipamento proteção não informado.";
       $this->erro_campo = "rh259_rhlocaltrabequipamentoprotecao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh259_sequencial == "" || $rh259_sequencial == null ){
       $result = db_query("select nextval('rhlocaltrabequipamentoprotecaoepi_rh259_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhlocaltrabequipamentoprotecaoepi_rh259_sequencial_seq do campo: rh259_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh259_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhlocaltrabequipamentoprotecaoepi_rh259_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh259_sequencial)){
         $this->erro_sql = " Campo rh259_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh259_sequencial = $rh259_sequencial; 
       }
     }
     if(($this->rh259_sequencial == null) || ($this->rh259_sequencial == "") ){ 
       $this->erro_sql = " Campo rh259_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhlocaltrabequipamentoprotecaoepi(
                                       rh259_sequencial 
                                      ,rh259_rhlocaltrabequipamentoprotecao 
                                      ,rh259_documentoavaliacao 
                                      ,rh259_descricao 
                       )
                values (
                                $this->rh259_sequencial 
                               ,$this->rh259_rhlocaltrabequipamentoprotecao 
                               ,'$this->rh259_documentoavaliacao' 
                               ,'$this->rh259_descricao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "EPI do Local de Trabalho ($this->rh259_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "EPI do Local de Trabalho já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "EPI do Local de Trabalho ($this->rh259_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh259_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh259_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1013702,'$this->rh259_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010861,1013702,'','".AddSlashes(pg_result($resaco,0,'rh259_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010861,1013703,'','".AddSlashes(pg_result($resaco,0,'rh259_rhlocaltrabequipamentoprotecao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010861,1013704,'','".AddSlashes(pg_result($resaco,0,'rh259_documentoavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010861,1013705,'','".AddSlashes(pg_result($resaco,0,'rh259_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh259_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhlocaltrabequipamentoprotecaoepi set ";
     $virgula = "";
     if(trim($this->rh259_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh259_sequencial"])){ 
       $sql  .= $virgula." rh259_sequencial = $this->rh259_sequencial ";
       $virgula = ",";
       if(trim($this->rh259_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh259_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh259_rhlocaltrabequipamentoprotecao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh259_rhlocaltrabequipamentoprotecao"])){ 
       $sql  .= $virgula." rh259_rhlocaltrabequipamentoprotecao = $this->rh259_rhlocaltrabequipamentoprotecao ";
       $virgula = ",";
       if(trim($this->rh259_rhlocaltrabequipamentoprotecao) == null ){ 
         $this->erro_sql = " Campo Sequencial equipamento proteção não informado.";
         $this->erro_campo = "rh259_rhlocaltrabequipamentoprotecao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh259_documentoavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh259_documentoavaliacao"])){ 
       $sql  .= $virgula." rh259_documentoavaliacao = '$this->rh259_documentoavaliacao' ";
       $virgula = ",";
     }
     if(trim($this->rh259_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh259_descricao"])){ 
       $sql  .= $virgula." rh259_descricao = '$this->rh259_descricao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh259_sequencial!=null){
       $sql .= " rh259_sequencial = $this->rh259_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh259_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1013702,'$this->rh259_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh259_sequencial"]) || $this->rh259_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010861,1013702,'".AddSlashes(pg_result($resaco,$conresaco,'rh259_sequencial'))."','$this->rh259_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh259_rhlocaltrabequipamentoprotecao"]) || $this->rh259_rhlocaltrabequipamentoprotecao != "")
             $resac = db_query("insert into db_acount values($acount,1010861,1013703,'".AddSlashes(pg_result($resaco,$conresaco,'rh259_rhlocaltrabequipamentoprotecao'))."','$this->rh259_rhlocaltrabequipamentoprotecao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh259_documentoavaliacao"]) || $this->rh259_documentoavaliacao != "")
             $resac = db_query("insert into db_acount values($acount,1010861,1013704,'".AddSlashes(pg_result($resaco,$conresaco,'rh259_documentoavaliacao'))."','$this->rh259_documentoavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh259_descricao"]) || $this->rh259_descricao != "")
             $resac = db_query("insert into db_acount values($acount,1010861,1013705,'".AddSlashes(pg_result($resaco,$conresaco,'rh259_descricao'))."','$this->rh259_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "EPI do Local de Trabalho não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh259_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "EPI do Local de Trabalho não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh259_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh259_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh259_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh259_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1013702,'$rh259_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010861,1013702,'','".AddSlashes(pg_result($resaco,$iresaco,'rh259_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010861,1013703,'','".AddSlashes(pg_result($resaco,$iresaco,'rh259_rhlocaltrabequipamentoprotecao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010861,1013704,'','".AddSlashes(pg_result($resaco,$iresaco,'rh259_documentoavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010861,1013705,'','".AddSlashes(pg_result($resaco,$iresaco,'rh259_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhlocaltrabequipamentoprotecaoepi
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh259_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh259_sequencial = $rh259_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "EPI do Local de Trabalho não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh259_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "EPI do Local de Trabalho não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh259_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh259_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhlocaltrabequipamentoprotecaoepi";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh259_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhlocaltrabequipamentoprotecaoepi ";
     $sql .= "      inner join rhlocaltrabequipamentoprotecao  on  rhlocaltrabequipamentoprotecao.rh257_sequencial = rhlocaltrabequipamentoprotecaoepi.rh259_rhlocaltrabequipamentoprotecao";
     $sql .= "      inner join rhlocaltrabagentesnocivos  on  rhlocaltrabagentesnocivos.rh256_sequencial = rhlocaltrabequipamentoprotecao.rh257_rhlocaltrabagentesnocivos";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh259_sequencial)) {
         $sql2 .= " where rhlocaltrabequipamentoprotecaoepi.rh259_sequencial = $rh259_sequencial "; 
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

    public function sql_query_file($rh259_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhlocaltrabequipamentoprotecaoepi ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh259_sequencial)){
         $sql2 .= " where rhlocaltrabequipamentoprotecaoepi.rh259_sequencial = $rh259_sequencial "; 
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
