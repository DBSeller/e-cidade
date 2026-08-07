<?php

class cl_formacaocensodisciplina
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
    public $ed145_sequencial = 0; 
    public $ed145_formacao = 0; 
    public $ed145_censodisciplina = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 ed145_sequencial = int4 = Sequencial 
                 ed145_formacao = int4 = Formação 
                 ed145_censodisciplina = int4 = Censo 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("formacaocensodisciplina"); 
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
       $this->ed145_sequencial = ($this->ed145_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed145_sequencial"]:$this->ed145_sequencial);
       $this->ed145_formacao = ($this->ed145_formacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed145_formacao"]:$this->ed145_formacao);
       $this->ed145_censodisciplina = ($this->ed145_censodisciplina == ""?@$GLOBALS["HTTP_POST_VARS"]["ed145_censodisciplina"]:$this->ed145_censodisciplina);
     }else{
       $this->ed145_sequencial = ($this->ed145_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed145_sequencial"]:$this->ed145_sequencial);
     }
   }

    public function incluir()
    {
      $this->atualizacampos();
     if($this->ed145_formacao == null ){ 
       $this->erro_sql = " Campo Formação não informado.";
       $this->erro_campo = "ed145_formacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed145_censodisciplina == null ){ 
       $this->erro_sql = " Campo Censo não informado.";
       $this->erro_campo = "ed145_censodisciplina";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into formacaocensodisciplina(
                                      ed145_formacao 
                                      ,ed145_censodisciplina 
                       )
                values (
                                $this->ed145_formacao 
                               ,$this->ed145_censodisciplina 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "formacaocensodisciplina ($this->ed145_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "formacaocensodisciplina já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "formacaocensodisciplina ($this->ed145_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed145_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed145_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1010438,'$this->ed145_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010441,1010438,'','".AddSlashes(pg_result($resaco,0,'ed145_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010441,1010439,'','".AddSlashes(pg_result($resaco,0,'ed145_formacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010441,1010440,'','".AddSlashes(pg_result($resaco,0,'ed145_censodisciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($ed145_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update formacaocensodisciplina set ";
     $virgula = "";
     if(trim($this->ed145_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed145_sequencial"])){ 
       $sql  .= $virgula." ed145_sequencial = $this->ed145_sequencial ";
       $virgula = ",";
       if(trim($this->ed145_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "ed145_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed145_formacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed145_formacao"])){ 
       $sql  .= $virgula." ed145_formacao = $this->ed145_formacao ";
       $virgula = ",";
       if(trim($this->ed145_formacao) == null ){ 
         $this->erro_sql = " Campo Formação não informado.";
         $this->erro_campo = "ed145_formacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed145_censodisciplina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed145_censodisciplina"])){ 
       $sql  .= $virgula." ed145_censodisciplina = $this->ed145_censodisciplina ";
       $virgula = ",";
       if(trim($this->ed145_censodisciplina) == null ){ 
         $this->erro_sql = " Campo Censo não informado.";
         $this->erro_campo = "ed145_censodisciplina";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed145_sequencial!=null){
       $sql .= " ed145_sequencial = $this->ed145_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed145_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1010438,'$this->ed145_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed145_sequencial"]) || $this->ed145_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010441,1010438,'".AddSlashes(pg_result($resaco,$conresaco,'ed145_sequencial'))."','$this->ed145_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed145_formacao"]) || $this->ed145_formacao != "")
             $resac = db_query("insert into db_acount values($acount,1010441,1010439,'".AddSlashes(pg_result($resaco,$conresaco,'ed145_formacao'))."','$this->ed145_formacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed145_censodisciplina"]) || $this->ed145_censodisciplina != "")
             $resac = db_query("insert into db_acount values($acount,1010441,1010440,'".AddSlashes(pg_result($resaco,$conresaco,'ed145_censodisciplina'))."','$this->ed145_censodisciplina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "formacaocensodisciplina não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed145_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "formacaocensodisciplina não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed145_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed145_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($ed145_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed145_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1010438,'$ed145_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010441,1010438,'','".AddSlashes(pg_result($resaco,$iresaco,'ed145_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010441,1010439,'','".AddSlashes(pg_result($resaco,$iresaco,'ed145_formacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010441,1010440,'','".AddSlashes(pg_result($resaco,$iresaco,'ed145_censodisciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from formacaocensodisciplina
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed145_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed145_sequencial = $ed145_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "formacaocensodisciplina não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed145_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "formacaocensodisciplina não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed145_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ed145_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:formacaocensodisciplina";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($ed145_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from formacaocensodisciplina ";
     $sql .= "      inner join censodisciplina  on  censodisciplina.ed265_i_codigo = formacaocensodisciplina.ed145_censodisciplina";
     $sql .= "      inner join formacao  on  formacao.ed27_i_codigo = formacaocensodisciplina.ed145_formacao";
     $sql .= "      inner join censoinstsuperior  on  censoinstsuperior.ed257_i_codigo = formacao.ed27_i_censoinstsuperior";
     $sql .= "      inner join rechumano  on  rechumano.ed20_i_codigo = formacao.ed27_i_rechumano";
     $sql .= "      inner join cursoformacao  on  cursoformacao.ed94_i_codigo = formacao.ed27_i_cursoformacao";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed145_sequencial)) {
         $sql2 .= " where formacaocensodisciplina.ed145_sequencial = $ed145_sequencial "; 
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

    public function sql_query_file($ed145_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from formacaocensodisciplina ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed145_sequencial)){
         $sql2 .= " where formacaocensodisciplina.ed145_sequencial = $ed145_sequencial "; 
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
