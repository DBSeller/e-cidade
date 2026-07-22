<?php

class cl_rubricasubgrupotce
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
    public $rh263_sequencial = 0; 
    public $rh263_grupo = null; 
    public $rh263_subgrupo = null; 
    public $rh263_descricao = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh263_sequencial = int4 = Código Sequencial 
                 rh263_grupo = varchar(4) = Natureza do subgrupo 
                 rh263_subgrupo = varchar(2) = Subgrupo da Natureza 
                 rh263_descricao = varchar(100) = Descrição 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rubricasubgrupotce"); 
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
       $this->rh263_sequencial = ($this->rh263_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh263_sequencial"]:$this->rh263_sequencial);
       $this->rh263_grupo = ($this->rh263_grupo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh263_grupo"]:$this->rh263_grupo);
       $this->rh263_subgrupo = ($this->rh263_subgrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh263_subgrupo"]:$this->rh263_subgrupo);
       $this->rh263_descricao = ($this->rh263_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh263_descricao"]:$this->rh263_descricao);
     }else{
       $this->rh263_sequencial = ($this->rh263_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh263_sequencial"]:$this->rh263_sequencial);
     }
   }

    public function incluir($rh263_sequencial)
    {
      $this->atualizacampos();
     if($this->rh263_grupo == null ){ 
       $this->erro_sql = " Campo Natureza do subgrupo não informado.";
       $this->erro_campo = "rh263_grupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh263_subgrupo == null ){ 
       $this->erro_sql = " Campo Subgrupo da Natureza não informado.";
       $this->erro_campo = "rh263_subgrupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh263_descricao == null ){ 
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "rh263_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh263_sequencial == "" || $rh263_sequencial == null ){
       $result = db_query("select nextval('rubricasubgrupotce_rh263_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rubricasubgrupotce_rh263_sequencial_seq do campo: rh263_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh263_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rubricasubgrupotce_rh263_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh263_sequencial)){
         $this->erro_sql = " Campo rh263_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh263_sequencial = $rh263_sequencial; 
       }
     }
     if(($this->rh263_sequencial == null) || ($this->rh263_sequencial == "") ){ 
       $this->erro_sql = " Campo rh263_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rubricasubgrupotce(
                                       rh263_sequencial 
                                      ,rh263_grupo 
                                      ,rh263_subgrupo 
                                      ,rh263_descricao 
                       )
                values (
                                $this->rh263_sequencial 
                               ,'$this->rh263_grupo' 
                               ,'$this->rh263_subgrupo' 
                               ,'$this->rh263_descricao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Subgrupo da Rubrica ($this->rh263_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Subgrupo da Rubrica já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Subgrupo da Rubrica ($this->rh263_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh263_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh263_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1014218,'$this->rh263_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010945,1014218,'','".AddSlashes(pg_result($resaco,0,'rh263_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010945,1014219,'','".AddSlashes(pg_result($resaco,0,'rh263_grupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010945,1014220,'','".AddSlashes(pg_result($resaco,0,'rh263_subgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010945,1014221,'','".AddSlashes(pg_result($resaco,0,'rh263_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh263_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rubricasubgrupotce set ";
     $virgula = "";
     if(trim($this->rh263_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh263_sequencial"])){ 
       $sql  .= $virgula." rh263_sequencial = $this->rh263_sequencial ";
       $virgula = ",";
       if(trim($this->rh263_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial não informado.";
         $this->erro_campo = "rh263_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh263_grupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh263_grupo"])){ 
       $sql  .= $virgula." rh263_grupo = '$this->rh263_grupo' ";
       $virgula = ",";
       if(trim($this->rh263_grupo) == null ){ 
         $this->erro_sql = " Campo Natureza do subgrupo não informado.";
         $this->erro_campo = "rh263_grupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh263_subgrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh263_subgrupo"])){ 
       $sql  .= $virgula." rh263_subgrupo = '$this->rh263_subgrupo' ";
       $virgula = ",";
       if(trim($this->rh263_subgrupo) == null ){ 
         $this->erro_sql = " Campo Subgrupo da Natureza não informado.";
         $this->erro_campo = "rh263_subgrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh263_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh263_descricao"])){ 
       $sql  .= $virgula." rh263_descricao = '$this->rh263_descricao' ";
       $virgula = ",";
       if(trim($this->rh263_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "rh263_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh263_sequencial!=null){
       $sql .= " rh263_sequencial = $this->rh263_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh263_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1014218,'$this->rh263_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh263_sequencial"]) || $this->rh263_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010945,1014218,'".AddSlashes(pg_result($resaco,$conresaco,'rh263_sequencial'))."','$this->rh263_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh263_grupo"]) || $this->rh263_grupo != "")
             $resac = db_query("insert into db_acount values($acount,1010945,1014219,'".AddSlashes(pg_result($resaco,$conresaco,'rh263_grupo'))."','$this->rh263_grupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh263_subgrupo"]) || $this->rh263_subgrupo != "")
             $resac = db_query("insert into db_acount values($acount,1010945,1014220,'".AddSlashes(pg_result($resaco,$conresaco,'rh263_subgrupo'))."','$this->rh263_subgrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh263_descricao"]) || $this->rh263_descricao != "")
             $resac = db_query("insert into db_acount values($acount,1010945,1014221,'".AddSlashes(pg_result($resaco,$conresaco,'rh263_descricao'))."','$this->rh263_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Subgrupo da Rubrica não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh263_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Subgrupo da Rubrica não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh263_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh263_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh263_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh263_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1014218,'$rh263_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010945,1014218,'','".AddSlashes(pg_result($resaco,$iresaco,'rh263_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010945,1014219,'','".AddSlashes(pg_result($resaco,$iresaco,'rh263_grupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010945,1014220,'','".AddSlashes(pg_result($resaco,$iresaco,'rh263_subgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010945,1014221,'','".AddSlashes(pg_result($resaco,$iresaco,'rh263_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rubricasubgrupotce
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh263_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh263_sequencial = $rh263_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Subgrupo da Rubrica não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh263_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Subgrupo da Rubrica não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh263_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh263_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rubricasubgrupotce";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh263_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rubricasubgrupotce ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh263_sequencial)) {
         $sql2 .= " where rubricasubgrupotce.rh263_sequencial = $rh263_sequencial "; 
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

    public function sql_query_file($rh263_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rubricasubgrupotce ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh263_sequencial)){
         $sql2 .= " where rubricasubgrupotce.rh263_sequencial = $rh263_sequencial "; 
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
