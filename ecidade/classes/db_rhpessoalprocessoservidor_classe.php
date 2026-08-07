<?php

class cl_rhpessoalprocessoservidor
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
    public $rh271_sequencial = 0; 
    public $rh271_sequencialprocesso = 0; 
    public $rh271_matricula = 0; 
    public $rh271_codcateg = 0; 
    public $rh271_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh271_sequencial = int4 = Número Sequencial 
                 rh271_sequencialprocesso = int4 = Identificação única de processo 
                 rh271_matricula = int4 = Matrícula servidor 
                 rh271_codcateg = int4 = Código Categoria 
                 rh271_instit = int4 = Instituição 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhpessoalprocessoservidor"); 
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
       $this->rh271_sequencial = ($this->rh271_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh271_sequencial"]:$this->rh271_sequencial);
       $this->rh271_sequencialprocesso = ($this->rh271_sequencialprocesso == ""?@$GLOBALS["HTTP_POST_VARS"]["rh271_sequencialprocesso"]:$this->rh271_sequencialprocesso);
       $this->rh271_matricula = ($this->rh271_matricula == ""?@$GLOBALS["HTTP_POST_VARS"]["rh271_matricula"]:$this->rh271_matricula);
       $this->rh271_codcateg = ($this->rh271_codcateg == ""?@$GLOBALS["HTTP_POST_VARS"]["rh271_codcateg"]:$this->rh271_codcateg);
       $this->rh271_instit = ($this->rh271_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh271_instit"]:$this->rh271_instit);
     }else{
       $this->rh271_sequencial = ($this->rh271_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh271_sequencial"]:$this->rh271_sequencial);
     }
   }

    public function incluir($rh271_sequencial)
    {
      $this->atualizacampos();
     if($this->rh271_sequencialprocesso == null ){ 
       $this->erro_sql = " Campo Identificação única de processo não informado.";
       $this->erro_campo = "rh271_sequencialprocesso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh271_matricula == null ){ 
       $this->rh271_matricula = "0";
     }
     if($this->rh271_codcateg == null ){ 
       $this->rh271_codcateg = "0";
     }
     if($this->rh271_instit == null ){ 
       $this->rh271_instit = "0";
     }
     if($rh271_sequencial == "" || $rh271_sequencial == null ){
       $result = db_query("select nextval('rhpessoalprocessoservidor_rh271_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhpessoalprocessoservidor_rh271_sequencial_seq do campo: rh271_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh271_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhpessoalprocessoservidor_rh271_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh271_sequencial)){
         $this->erro_sql = " Campo rh271_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh271_sequencial = $rh271_sequencial; 
       }
     }
     if(($this->rh271_sequencial == null) || ($this->rh271_sequencial == "") ){ 
       $this->erro_sql = " Campo rh271_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpessoalprocessoservidor(
                                       rh271_sequencial 
                                      ,rh271_sequencialprocesso 
                                      ,rh271_matricula 
                                      ,rh271_codcateg 
                                      ,rh271_instit 
                       )
                values (
                                $this->rh271_sequencial 
                               ,$this->rh271_sequencialprocesso 
                               ,$this->rh271_matricula 
                               ,$this->rh271_codcateg 
                               ,$this->rh271_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Processo Trabalhista Servidor ($this->rh271_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Processo Trabalhista Servidor já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Processo Trabalhista Servidor ($this->rh271_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh271_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh271_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1014819,'$this->rh271_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1011032,1014819,'','".AddSlashes(pg_result($resaco,0,'rh271_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011032,1014820,'','".AddSlashes(pg_result($resaco,0,'rh271_sequencialprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011032,1014821,'','".AddSlashes(pg_result($resaco,0,'rh271_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011032,1014823,'','".AddSlashes(pg_result($resaco,0,'rh271_codcateg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011032,1014929,'','".AddSlashes(pg_result($resaco,0,'rh271_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh271_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhpessoalprocessoservidor set ";
     $virgula = "";
     if(trim($this->rh271_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh271_sequencial"])){ 
       $sql  .= $virgula." rh271_sequencial = $this->rh271_sequencial ";
       $virgula = ",";
       if(trim($this->rh271_sequencial) == null ){ 
         $this->erro_sql = " Campo Número Sequencial não informado.";
         $this->erro_campo = "rh271_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh271_sequencialprocesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh271_sequencialprocesso"])){ 
       $sql  .= $virgula." rh271_sequencialprocesso = $this->rh271_sequencialprocesso ";
       $virgula = ",";
       if(trim($this->rh271_sequencialprocesso) == null ){ 
         $this->erro_sql = " Campo Identificação única de processo não informado.";
         $this->erro_campo = "rh271_sequencialprocesso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh271_matricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh271_matricula"])){ 
        if(trim($this->rh271_matricula)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh271_matricula"])){ 
           $this->rh271_matricula = "0" ; 
        } 
       $sql  .= $virgula." rh271_matricula = $this->rh271_matricula ";
       $virgula = ",";
     }
     if(trim($this->rh271_codcateg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh271_codcateg"])){ 
        if(trim($this->rh271_codcateg)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh271_codcateg"])){ 
           $this->rh271_codcateg = "0" ; 
        } 
       $sql  .= $virgula." rh271_codcateg = $this->rh271_codcateg ";
       $virgula = ",";
     }
     if(trim($this->rh271_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh271_instit"])){ 
        if(trim($this->rh271_instit)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh271_instit"])){ 
           $this->rh271_instit = "0" ; 
        } 
       $sql  .= $virgula." rh271_instit = $this->rh271_instit ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh271_sequencial!=null){
       $sql .= " rh271_sequencial = $this->rh271_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh271_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1014819,'$this->rh271_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh271_sequencial"]) || $this->rh271_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1011032,1014819,'".AddSlashes(pg_result($resaco,$conresaco,'rh271_sequencial'))."','$this->rh271_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh271_sequencialprocesso"]) || $this->rh271_sequencialprocesso != "")
             $resac = db_query("insert into db_acount values($acount,1011032,1014820,'".AddSlashes(pg_result($resaco,$conresaco,'rh271_sequencialprocesso'))."','$this->rh271_sequencialprocesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh271_matricula"]) || $this->rh271_matricula != "")
             $resac = db_query("insert into db_acount values($acount,1011032,1014821,'".AddSlashes(pg_result($resaco,$conresaco,'rh271_matricula'))."','$this->rh271_matricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh271_codcateg"]) || $this->rh271_codcateg != "")
             $resac = db_query("insert into db_acount values($acount,1011032,1014823,'".AddSlashes(pg_result($resaco,$conresaco,'rh271_codcateg'))."','$this->rh271_codcateg',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh271_instit"]) || $this->rh271_instit != "")
             $resac = db_query("insert into db_acount values($acount,1011032,1014929,'".AddSlashes(pg_result($resaco,$conresaco,'rh271_instit'))."','$this->rh271_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Processo Trabalhista Servidor não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh271_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Processo Trabalhista Servidor não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh271_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh271_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh271_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh271_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1014819,'$rh271_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1011032,1014819,'','".AddSlashes(pg_result($resaco,$iresaco,'rh271_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011032,1014820,'','".AddSlashes(pg_result($resaco,$iresaco,'rh271_sequencialprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011032,1014821,'','".AddSlashes(pg_result($resaco,$iresaco,'rh271_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011032,1014823,'','".AddSlashes(pg_result($resaco,$iresaco,'rh271_codcateg'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011032,1014929,'','".AddSlashes(pg_result($resaco,$iresaco,'rh271_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhpessoalprocessoservidor
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh271_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh271_sequencial = $rh271_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Processo Trabalhista Servidor não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh271_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Processo Trabalhista Servidor não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh271_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh271_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpessoalprocessoservidor";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh271_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhpessoalprocessoservidor ";
     $sql .= "      inner join rhpessoalprocessojudicialesocial  on  rhpessoalprocessojudicialesocial.rh270_sequencial = rhpessoalprocessoservidor.rh271_sequencialprocesso";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh271_sequencial)) {
         $sql2 .= " where rhpessoalprocessoservidor.rh271_sequencial = $rh271_sequencial "; 
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

    public function sql_query_file($rh271_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhpessoalprocessoservidor ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh271_sequencial)){
         $sql2 .= " where rhpessoalprocessoservidor.rh271_sequencial = $rh271_sequencial "; 
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
