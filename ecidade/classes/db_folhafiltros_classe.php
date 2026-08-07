<?php

class cl_folhafiltros
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
    public $r39_instituicao = 0; 
    public $r39_filtros = null; 
    public $r39_processamento_dia = null; 
    public $r39_processamento_mes = null; 
    public $r39_processamento_ano = null; 
    public $r39_processamento = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 r39_instituicao = int4 = Instituição 
                 r39_filtros = text = Filtros 
                 r39_processamento = date = Dt. Processamento 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("folhafiltros"); 
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
       $this->r39_instituicao = ($this->r39_instituicao == ""?@$GLOBALS["HTTP_POST_VARS"]["r39_instituicao"]:$this->r39_instituicao);
       $this->r39_filtros = ($this->r39_filtros == ""?@$GLOBALS["HTTP_POST_VARS"]["r39_filtros"]:$this->r39_filtros);
       if($this->r39_processamento == ""){
         $this->r39_processamento_dia = ($this->r39_processamento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r39_processamento_dia"]:$this->r39_processamento_dia);
         $this->r39_processamento_mes = ($this->r39_processamento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r39_processamento_mes"]:$this->r39_processamento_mes);
         $this->r39_processamento_ano = ($this->r39_processamento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r39_processamento_ano"]:$this->r39_processamento_ano);
         if($this->r39_processamento_dia != ""){
            $this->r39_processamento = $this->r39_processamento_ano."-".$this->r39_processamento_mes."-".$this->r39_processamento_dia;
         }
       }
     }else{
       $this->r39_instituicao = ($this->r39_instituicao == ""?@$GLOBALS["HTTP_POST_VARS"]["r39_instituicao"]:$this->r39_instituicao);
     }
   }

    public function incluir($r39_instituicao)
    {
      $this->atualizacampos();
     if($this->r39_processamento == null ){ 
       $this->erro_sql = " Campo Dt. Processamento não informado.";
       $this->erro_campo = "r39_processamento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r39_instituicao = $r39_instituicao; 
     if(($this->r39_instituicao == null) || ($this->r39_instituicao == "") ){ 
       $this->erro_sql = " Campo r39_instituicao não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into folhafiltros(
                                       r39_instituicao 
                                      ,r39_filtros 
                                      ,r39_processamento 
                       )
                values (
                                $this->r39_instituicao 
                               ,'$this->r39_filtros' 
                               ,".($this->r39_processamento == "null" || $this->r39_processamento == ""?"null":"'".$this->r39_processamento."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Filtros do processamento da folha de pagamento ($this->r39_instituicao) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Filtros do processamento da folha de pagamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Filtros do processamento da folha de pagamento ($this->r39_instituicao) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->r39_instituicao;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->r39_instituicao  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1014228,'$this->r39_instituicao','I')");
         $resac = db_query("insert into db_acount values($acount,1010947,1014228,'','".AddSlashes(pg_result($resaco,0,'r39_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010947,1014229,'','".AddSlashes(pg_result($resaco,0,'r39_filtros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010947,1014230,'','".AddSlashes(pg_result($resaco,0,'r39_processamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($r39_instituicao=null)
    {
      $this->atualizacampos();
     $sql = " update folhafiltros set ";
     $virgula = "";
     if(trim($this->r39_instituicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r39_instituicao"])){ 
       $sql  .= $virgula." r39_instituicao = $this->r39_instituicao ";
       $virgula = ",";
       if(trim($this->r39_instituicao) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "r39_instituicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r39_filtros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r39_filtros"])){ 
       $sql  .= $virgula." r39_filtros = '$this->r39_filtros' ";
       $virgula = ",";
     }
     if(trim($this->r39_processamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r39_processamento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r39_processamento_dia"] !="") ){ 
       $sql  .= $virgula." r39_processamento = '$this->r39_processamento' ";
       $virgula = ",";
       if(trim($this->r39_processamento) == null ){ 
         $this->erro_sql = " Campo Dt. Processamento não informado.";
         $this->erro_campo = "r39_processamento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r39_processamento_dia"])){ 
         $sql  .= $virgula." r39_processamento = null ";
         $virgula = ",";
         if(trim($this->r39_processamento) == null ){ 
           $this->erro_sql = " Campo Dt. Processamento não informado.";
           $this->erro_campo = "r39_processamento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($r39_instituicao!=null){
       $sql .= " r39_instituicao = $this->r39_instituicao";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->r39_instituicao));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1014228,'$this->r39_instituicao','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r39_instituicao"]) || $this->r39_instituicao != "")
             $resac = db_query("insert into db_acount values($acount,1010947,1014228,'".AddSlashes(pg_result($resaco,$conresaco,'r39_instituicao'))."','$this->r39_instituicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r39_filtros"]) || $this->r39_filtros != "")
             $resac = db_query("insert into db_acount values($acount,1010947,1014229,'".AddSlashes(pg_result($resaco,$conresaco,'r39_filtros'))."','$this->r39_filtros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r39_processamento"]) || $this->r39_processamento != "")
             $resac = db_query("insert into db_acount values($acount,1010947,1014230,'".AddSlashes(pg_result($resaco,$conresaco,'r39_processamento'))."','$this->r39_processamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Filtros do processamento da folha de pagamento não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r39_instituicao;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Filtros do processamento da folha de pagamento não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r39_instituicao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->r39_instituicao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($r39_instituicao=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($r39_instituicao));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1014228,'$r39_instituicao','E')");
           $resac  = db_query("insert into db_acount values($acount,1010947,1014228,'','".AddSlashes(pg_result($resaco,$iresaco,'r39_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010947,1014229,'','".AddSlashes(pg_result($resaco,$iresaco,'r39_filtros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010947,1014230,'','".AddSlashes(pg_result($resaco,$iresaco,'r39_processamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from folhafiltros
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($r39_instituicao)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " r39_instituicao = $r39_instituicao ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Filtros do processamento da folha de pagamento não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r39_instituicao;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Filtros do processamento da folha de pagamento não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r39_instituicao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$r39_instituicao;
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
        $this->erro_sql   = "Record Vazio na Tabela:folhafiltros";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($r39_instituicao = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from folhafiltros ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($r39_instituicao)) {
         $sql2 .= " where folhafiltros.r39_instituicao = $r39_instituicao "; 
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

    public function sql_query_file($r39_instituicao = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from folhafiltros ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($r39_instituicao)){
         $sql2 .= " where folhafiltros.r39_instituicao = $r39_instituicao "; 
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
