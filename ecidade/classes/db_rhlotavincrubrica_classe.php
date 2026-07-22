<?php

class cl_rhlotavincrubrica
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
    public $rh239_sequencial = 0; 
    public $rh239_rhlota = 0; 
    public $rh239_rhrubricas = null; 
    public $rh239_instituicao = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh239_sequencial = int4 = Sequencial 
                 rh239_rhlota = int4 = Lotação 
                 rh239_rhrubricas = char(4) = Rubrica 
                 rh239_instituicao = int4 = Instituição 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhlotavincrubrica"); 
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
       $this->rh239_sequencial = ($this->rh239_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh239_sequencial"]:$this->rh239_sequencial);
       $this->rh239_rhlota = ($this->rh239_rhlota == ""?@$GLOBALS["HTTP_POST_VARS"]["rh239_rhlota"]:$this->rh239_rhlota);
       $this->rh239_rhrubricas = ($this->rh239_rhrubricas == ""?@$GLOBALS["HTTP_POST_VARS"]["rh239_rhrubricas"]:$this->rh239_rhrubricas);
       $this->rh239_instituicao = ($this->rh239_instituicao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh239_instituicao"]:$this->rh239_instituicao);
     }else{
       $this->rh239_sequencial = ($this->rh239_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh239_sequencial"]:$this->rh239_sequencial);
     }
   }

    public function incluir($rh239_sequencial)
    {
      $this->atualizacampos();
     if($this->rh239_rhlota == null ){ 
       $this->erro_sql = " Campo Lotação não informado.";
       $this->erro_campo = "rh239_rhlota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh239_rhrubricas == null ){ 
       $this->erro_sql = " Campo Rubrica não informado.";
       $this->erro_campo = "rh239_rhrubricas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh239_instituicao == null ){ 
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "rh239_instituicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh239_sequencial == "" || $rh239_sequencial == null ){
       $result = db_query("select nextval('rhlotavincrubrica_rh239_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhlotavincrubrica_rh239_sequencial_seq do campo: rh239_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh239_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhlotavincrubrica_rh239_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh239_sequencial)){
         $this->erro_sql = " Campo rh239_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh239_sequencial = $rh239_sequencial; 
       }
     }
     if(($this->rh239_sequencial == null) || ($this->rh239_sequencial == "") ){ 
       $this->erro_sql = " Campo rh239_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhlotavincrubrica(
                                       rh239_sequencial 
                                      ,rh239_rhlota 
                                      ,rh239_rhrubricas 
                                      ,rh239_instituicao 
                       )
                values (
                                $this->rh239_sequencial 
                               ,$this->rh239_rhlota 
                               ,'$this->rh239_rhrubricas' 
                               ,$this->rh239_instituicao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Vinculo entre lotação e rubrica ($this->rh239_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Vinculo entre lotação e rubrica já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Vinculo entre lotação e rubrica ($this->rh239_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh239_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh239_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1014232,'$this->rh239_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010948,1014232,'','".AddSlashes(pg_result($resaco,0,'rh239_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010948,1014233,'','".AddSlashes(pg_result($resaco,0,'rh239_rhlota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010948,1014234,'','".AddSlashes(pg_result($resaco,0,'rh239_rhrubricas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010948,1014235,'','".AddSlashes(pg_result($resaco,0,'rh239_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh239_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhlotavincrubrica set ";
     $virgula = "";
     if(trim($this->rh239_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh239_sequencial"])){ 
       $sql  .= $virgula." rh239_sequencial = $this->rh239_sequencial ";
       $virgula = ",";
       if(trim($this->rh239_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh239_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh239_rhlota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh239_rhlota"])){ 
       $sql  .= $virgula." rh239_rhlota = $this->rh239_rhlota ";
       $virgula = ",";
       if(trim($this->rh239_rhlota) == null ){ 
         $this->erro_sql = " Campo Lotação não informado.";
         $this->erro_campo = "rh239_rhlota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh239_rhrubricas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh239_rhrubricas"])){ 
       $sql  .= $virgula." rh239_rhrubricas = '$this->rh239_rhrubricas' ";
       $virgula = ",";
       if(trim($this->rh239_rhrubricas) == null ){ 
         $this->erro_sql = " Campo Rubrica não informado.";
         $this->erro_campo = "rh239_rhrubricas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh239_instituicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh239_instituicao"])){ 
       $sql  .= $virgula." rh239_instituicao = $this->rh239_instituicao ";
       $virgula = ",";
       if(trim($this->rh239_instituicao) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "rh239_instituicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh239_sequencial!=null){
       $sql .= " rh239_sequencial = $this->rh239_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh239_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1014232,'$this->rh239_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh239_sequencial"]) || $this->rh239_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010948,1014232,'".AddSlashes(pg_result($resaco,$conresaco,'rh239_sequencial'))."','$this->rh239_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh239_rhlota"]) || $this->rh239_rhlota != "")
             $resac = db_query("insert into db_acount values($acount,1010948,1014233,'".AddSlashes(pg_result($resaco,$conresaco,'rh239_rhlota'))."','$this->rh239_rhlota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh239_rhrubricas"]) || $this->rh239_rhrubricas != "")
             $resac = db_query("insert into db_acount values($acount,1010948,1014234,'".AddSlashes(pg_result($resaco,$conresaco,'rh239_rhrubricas'))."','$this->rh239_rhrubricas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh239_instituicao"]) || $this->rh239_instituicao != "")
             $resac = db_query("insert into db_acount values($acount,1010948,1014235,'".AddSlashes(pg_result($resaco,$conresaco,'rh239_instituicao'))."','$this->rh239_instituicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculo entre lotação e rubrica não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh239_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Vinculo entre lotação e rubrica não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh239_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh239_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh239_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh239_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1014232,'$rh239_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010948,1014232,'','".AddSlashes(pg_result($resaco,$iresaco,'rh239_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010948,1014233,'','".AddSlashes(pg_result($resaco,$iresaco,'rh239_rhlota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010948,1014234,'','".AddSlashes(pg_result($resaco,$iresaco,'rh239_rhrubricas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010948,1014235,'','".AddSlashes(pg_result($resaco,$iresaco,'rh239_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhlotavincrubrica
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh239_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh239_sequencial = $rh239_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculo entre lotação e rubrica não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh239_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Vinculo entre lotação e rubrica não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh239_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh239_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhlotavincrubrica";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh239_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhlotavincrubrica ";
     $sql .= "      inner join db_config  on  db_config.codigo = rhlotavincrubrica.rh239_instituicao";
     $sql .= "      inner join rhlota  on  rhlota.r70_codigo = rhlotavincrubrica.rh239_rhlota";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = rhlotavincrubrica.rh239_rhrubricas";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh239_sequencial)) {
         $sql2 .= " where rhlotavincrubrica.rh239_sequencial = $rh239_sequencial "; 
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

    public function sql_query_file($rh239_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhlotavincrubrica ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh239_sequencial)){
         $sql2 .= " where rhlotavincrubrica.rh239_sequencial = $rh239_sequencial "; 
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
