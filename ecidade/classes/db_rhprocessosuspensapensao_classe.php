<?php

class cl_rhprocessosuspensapensao
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
    public $rh309_sequencial = 0; 
    public $rh309_sequencialreducaosuspensa = 0; 
    public $rh309_cpfdep = null; 
    public $rh309_vlrdepensusp = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh309_sequencial = int4 = Sequencial 
                 rh309_sequencialreducaosuspensa = int4 = Sequencial vinculo 
                 rh309_cpfdep = varchar(11) = CPF 
                 rh309_vlrdepensusp = float4 = Valor da dedução 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhprocessosuspensapensao"); 
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
       $this->rh309_sequencial = ($this->rh309_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh309_sequencial"]:$this->rh309_sequencial);
       $this->rh309_sequencialreducaosuspensa = ($this->rh309_sequencialreducaosuspensa == ""?@$GLOBALS["HTTP_POST_VARS"]["rh309_sequencialreducaosuspensa"]:$this->rh309_sequencialreducaosuspensa);
       $this->rh309_cpfdep = ($this->rh309_cpfdep == ""?@$GLOBALS["HTTP_POST_VARS"]["rh309_cpfdep"]:$this->rh309_cpfdep);
       $this->rh309_vlrdepensusp = ($this->rh309_vlrdepensusp == ""?@$GLOBALS["HTTP_POST_VARS"]["rh309_vlrdepensusp"]:$this->rh309_vlrdepensusp);
     }else{
       $this->rh309_sequencial = ($this->rh309_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh309_sequencial"]:$this->rh309_sequencial);
     }
   }

    public function incluir($rh309_sequencial)
    {
      $this->atualizacampos();
     if($this->rh309_sequencialreducaosuspensa == null ){ 
       $this->erro_sql = " Campo Sequencial vinculo não informado.";
       $this->erro_campo = "rh309_sequencialreducaosuspensa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh309_vlrdepensusp == null ){ 
       $this->rh309_vlrdepensusp = "0";
     }
     if($rh309_sequencial == "" || $rh309_sequencial == null ){
       $result = db_query("select nextval('rhprocessosuspensapensao_rh309_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhprocessosuspensapensao_rh309_sequencial_seq do campo: rh309_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh309_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhprocessosuspensapensao_rh309_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh309_sequencial)){
         $this->erro_sql = " Campo rh309_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh309_sequencial = $rh309_sequencial; 
       }
     }
     if(($this->rh309_sequencial == null) || ($this->rh309_sequencial == "") ){ 
       $this->erro_sql = " Campo rh309_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhprocessosuspensapensao(
                                       rh309_sequencial 
                                      ,rh309_sequencialreducaosuspensa 
                                      ,rh309_cpfdep 
                                      ,rh309_vlrdepensusp 
                       )
                values (
                                $this->rh309_sequencial 
                               ,$this->rh309_sequencialreducaosuspensa 
                               ,'$this->rh309_cpfdep' 
                               ,$this->rh309_vlrdepensusp 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Deduções suspensas pensão ($this->rh309_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Deduções suspensas pensão já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Deduções suspensas pensão ($this->rh309_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh309_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh309_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1015444,'$this->rh309_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1011144,1015444,'','".AddSlashes(pg_result($resaco,0,'rh309_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011144,1015445,'','".AddSlashes(pg_result($resaco,0,'rh309_sequencialreducaosuspensa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011144,1015446,'','".AddSlashes(pg_result($resaco,0,'rh309_cpfdep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011144,1015447,'','".AddSlashes(pg_result($resaco,0,'rh309_vlrdepensusp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh309_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhprocessosuspensapensao set ";
     $virgula = "";
     if(trim($this->rh309_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh309_sequencial"])){ 
       $sql  .= $virgula." rh309_sequencial = $this->rh309_sequencial ";
       $virgula = ",";
       if(trim($this->rh309_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh309_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh309_sequencialreducaosuspensa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh309_sequencialreducaosuspensa"])){ 
       $sql  .= $virgula." rh309_sequencialreducaosuspensa = $this->rh309_sequencialreducaosuspensa ";
       $virgula = ",";
       if(trim($this->rh309_sequencialreducaosuspensa) == null ){ 
         $this->erro_sql = " Campo Sequencial vinculo não informado.";
         $this->erro_campo = "rh309_sequencialreducaosuspensa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh309_cpfdep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh309_cpfdep"])){ 
       $sql  .= $virgula." rh309_cpfdep = '$this->rh309_cpfdep' ";
       $virgula = ",";
     }
     if(trim($this->rh309_vlrdepensusp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh309_vlrdepensusp"])){ 
        if(trim($this->rh309_vlrdepensusp)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh309_vlrdepensusp"])){ 
           $this->rh309_vlrdepensusp = "0" ; 
        } 
       $sql  .= $virgula." rh309_vlrdepensusp = $this->rh309_vlrdepensusp ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh309_sequencial!=null){
       $sql .= " rh309_sequencial = $this->rh309_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh309_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1015444,'$this->rh309_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh309_sequencial"]) || $this->rh309_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1011144,1015444,'".AddSlashes(pg_result($resaco,$conresaco,'rh309_sequencial'))."','$this->rh309_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh309_sequencialreducaosuspensa"]) || $this->rh309_sequencialreducaosuspensa != "")
             $resac = db_query("insert into db_acount values($acount,1011144,1015445,'".AddSlashes(pg_result($resaco,$conresaco,'rh309_sequencialreducaosuspensa'))."','$this->rh309_sequencialreducaosuspensa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh309_cpfdep"]) || $this->rh309_cpfdep != "")
             $resac = db_query("insert into db_acount values($acount,1011144,1015446,'".AddSlashes(pg_result($resaco,$conresaco,'rh309_cpfdep'))."','$this->rh309_cpfdep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh309_vlrdepensusp"]) || $this->rh309_vlrdepensusp != "")
             $resac = db_query("insert into db_acount values($acount,1011144,1015447,'".AddSlashes(pg_result($resaco,$conresaco,'rh309_vlrdepensusp'))."','$this->rh309_vlrdepensusp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Deduções suspensas pensão não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh309_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Deduções suspensas pensão não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh309_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh309_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh309_sequencial=null, $dbwhere = null)
    {
     $sql = " delete from rhprocessosuspensapensao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh309_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh309_sequencial = $rh309_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Deduções suspensas pensão não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh309_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Deduções suspensas pensão não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh309_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh309_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhprocessosuspensapensao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh309_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhprocessosuspensapensao ";
     $sql .= "      inner join rhprocessoreducaosuspensa  on  rhprocessoreducaosuspensa.rh308_sequencial = rhprocessosuspensapensao.rh309_sequencialreducaosuspensa";
     $sql .= "      inner join rhprocessovalorretencao  on  rhprocessovalorretencao.rh307_sequencial = rhprocessoreducaosuspensa.rh308_sequencialvalorretencao";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh309_sequencial)) {
         $sql2 .= " where rhprocessosuspensapensao.rh309_sequencial = $rh309_sequencial "; 
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

    public function sql_query_file($rh309_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhprocessosuspensapensao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh309_sequencial)){
         $sql2 .= " where rhprocessosuspensapensao.rh309_sequencial = $rh309_sequencial "; 
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
