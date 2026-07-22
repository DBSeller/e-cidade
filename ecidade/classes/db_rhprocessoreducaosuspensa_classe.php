<?php

class cl_rhprocessoreducaosuspensa
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
    public $rh308_sequencial = 0; 
    public $rh308_sequencialvalorretencao = 0; 
    public $rh308_indtpdeducao = 0; 
    public $rh308_vlrdedsusp = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh308_sequencial = int4 = Sequencial 
                 rh308_sequencialvalorretencao = int4 = Sequencial vinculo valor retenção 
                 rh308_indtpdeducao = int4 = Tipo de dedução 
                 rh308_vlrdedsusp = float4 = Valor da dedução 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhprocessoreducaosuspensa"); 
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
       $this->rh308_sequencial = ($this->rh308_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh308_sequencial"]:$this->rh308_sequencial);
       $this->rh308_sequencialvalorretencao = ($this->rh308_sequencialvalorretencao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh308_sequencialvalorretencao"]:$this->rh308_sequencialvalorretencao);
       $this->rh308_indtpdeducao = ($this->rh308_indtpdeducao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh308_indtpdeducao"]:$this->rh308_indtpdeducao);
       $this->rh308_vlrdedsusp = ($this->rh308_vlrdedsusp == ""?@$GLOBALS["HTTP_POST_VARS"]["rh308_vlrdedsusp"]:$this->rh308_vlrdedsusp);
     }else{
       $this->rh308_sequencial = ($this->rh308_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh308_sequencial"]:$this->rh308_sequencial);
     }
   }

    public function incluir($rh308_sequencial)
    {
      $this->atualizacampos();
     if($this->rh308_sequencialvalorretencao == null ){ 
       $this->erro_sql = " Campo Sequencial vinculo valor retenção não informado.";
       $this->erro_campo = "rh308_sequencialvalorretencao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh308_indtpdeducao == null ){ 
       $this->rh308_indtpdeducao = "0";
     }
     if($this->rh308_vlrdedsusp == null ){ 
       $this->erro_sql = " Campo Valor da dedução não informado.";
       $this->erro_campo = "rh308_vlrdedsusp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh308_sequencial == "" || $rh308_sequencial == null ){
       $result = db_query("select nextval('rhprocessoreducaosuspensa_rh308_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhprocessoreducaosuspensa_rh308_sequencial_seq do campo: rh308_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh308_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhprocessoreducaosuspensa_rh308_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh308_sequencial)){
         $this->erro_sql = " Campo rh308_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh308_sequencial = $rh308_sequencial; 
       }
     }
     if(($this->rh308_sequencial == null) || ($this->rh308_sequencial == "") ){ 
       $this->erro_sql = " Campo rh308_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhprocessoreducaosuspensa(
                                       rh308_sequencial 
                                      ,rh308_sequencialvalorretencao 
                                      ,rh308_indtpdeducao 
                                      ,rh308_vlrdedsusp 
                       )
                values (
                                $this->rh308_sequencial 
                               ,$this->rh308_sequencialvalorretencao 
                               ,$this->rh308_indtpdeducao 
                               ,$this->rh308_vlrdedsusp 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Exigibilidade suspensa. ($this->rh308_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Exigibilidade suspensa. já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Exigibilidade suspensa. ($this->rh308_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh308_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh308_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1015440,'$this->rh308_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1011143,1015440,'','".AddSlashes(pg_result($resaco,0,'rh308_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011143,1015441,'','".AddSlashes(pg_result($resaco,0,'rh308_sequencialvalorretencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011143,1015442,'','".AddSlashes(pg_result($resaco,0,'rh308_indtpdeducao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011143,1015443,'','".AddSlashes(pg_result($resaco,0,'rh308_vlrdedsusp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh308_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhprocessoreducaosuspensa set ";
     $virgula = "";
     if(trim($this->rh308_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh308_sequencial"])){ 
       $sql  .= $virgula." rh308_sequencial = $this->rh308_sequencial ";
       $virgula = ",";
       if(trim($this->rh308_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh308_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh308_sequencialvalorretencao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh308_sequencialvalorretencao"])){ 
       $sql  .= $virgula." rh308_sequencialvalorretencao = $this->rh308_sequencialvalorretencao ";
       $virgula = ",";
       if(trim($this->rh308_sequencialvalorretencao) == null ){ 
         $this->erro_sql = " Campo Sequencial vinculo valor retenção não informado.";
         $this->erro_campo = "rh308_sequencialvalorretencao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh308_indtpdeducao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh308_indtpdeducao"])){ 
        if(trim($this->rh308_indtpdeducao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh308_indtpdeducao"])){ 
           $this->rh308_indtpdeducao = "0" ; 
        } 
       $sql  .= $virgula." rh308_indtpdeducao = $this->rh308_indtpdeducao ";
       $virgula = ",";
     }
     if(trim($this->rh308_vlrdedsusp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh308_vlrdedsusp"])){ 
       $sql  .= $virgula." rh308_vlrdedsusp = $this->rh308_vlrdedsusp ";
       $virgula = ",";
       if(trim($this->rh308_vlrdedsusp) == null ){ 
         $this->erro_sql = " Campo Valor da dedução não informado.";
         $this->erro_campo = "rh308_vlrdedsusp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh308_sequencial!=null){
       $sql .= " rh308_sequencial = $this->rh308_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh308_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1015440,'$this->rh308_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh308_sequencial"]) || $this->rh308_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1011143,1015440,'".AddSlashes(pg_result($resaco,$conresaco,'rh308_sequencial'))."','$this->rh308_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh308_sequencialvalorretencao"]) || $this->rh308_sequencialvalorretencao != "")
             $resac = db_query("insert into db_acount values($acount,1011143,1015441,'".AddSlashes(pg_result($resaco,$conresaco,'rh308_sequencialvalorretencao'))."','$this->rh308_sequencialvalorretencao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh308_indtpdeducao"]) || $this->rh308_indtpdeducao != "")
             $resac = db_query("insert into db_acount values($acount,1011143,1015442,'".AddSlashes(pg_result($resaco,$conresaco,'rh308_indtpdeducao'))."','$this->rh308_indtpdeducao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh308_vlrdedsusp"]) || $this->rh308_vlrdedsusp != "")
             $resac = db_query("insert into db_acount values($acount,1011143,1015443,'".AddSlashes(pg_result($resaco,$conresaco,'rh308_vlrdedsusp'))."','$this->rh308_vlrdedsusp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Exigibilidade suspensa. não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh308_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Exigibilidade suspensa. não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh308_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh308_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh308_sequencial=null, $dbwhere = null)
    {
     $sql = " delete from rhprocessoreducaosuspensa
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh308_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh308_sequencial = $rh308_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Exigibilidade suspensa. não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh308_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Exigibilidade suspensa. não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh308_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh308_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhprocessoreducaosuspensa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh308_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhprocessoreducaosuspensa ";
     $sql .= "      inner join rhprocessovalorretencao  on  rhprocessovalorretencao.rh307_sequencial = rhprocessoreducaosuspensa.rh308_sequencialvalorretencao";
     $sql .= "      inner join rhprocessoretencao  on  rhprocessoretencao.rh306_sequencial = rhprocessovalorretencao.rh307_sequencialretencao";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh308_sequencial)) {
         $sql2 .= " where rhprocessoreducaosuspensa.rh308_sequencial = $rh308_sequencial "; 
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

    public function sql_query_file($rh308_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhprocessoreducaosuspensa ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh308_sequencial)){
         $sql2 .= " where rhprocessoreducaosuspensa.rh308_sequencial = $rh308_sequencial "; 
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
