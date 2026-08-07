<?php

class cl_rhprocessopensao
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
    public $rh305_sequencial = 0; 
    public $rh305_sequencialtributoirrf = 0; 
    public $rh305_tprend = 0; 
    public $rh305_cpfdep = null; 
    public $rh305_vlrpensao = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh305_sequencial = int4 = Sequencial 
                 rh305_sequencialtributoirrf = float4 = Sequencial vinculo tributo 
                 rh305_tprend = int4 = Tipo de rendimento 
                 rh305_cpfdep = varchar(11) = CPF 
                 rh305_vlrpensao = float4 = Valor pensão 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhprocessopensao"); 
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
       $this->rh305_sequencial = ($this->rh305_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh305_sequencial"]:$this->rh305_sequencial);
       $this->rh305_sequencialtributoirrf = ($this->rh305_sequencialtributoirrf == ""?@$GLOBALS["HTTP_POST_VARS"]["rh305_sequencialtributoirrf"]:$this->rh305_sequencialtributoirrf);
       $this->rh305_tprend = ($this->rh305_tprend == ""?@$GLOBALS["HTTP_POST_VARS"]["rh305_tprend"]:$this->rh305_tprend);
       $this->rh305_cpfdep = ($this->rh305_cpfdep == ""?@$GLOBALS["HTTP_POST_VARS"]["rh305_cpfdep"]:$this->rh305_cpfdep);
       $this->rh305_vlrpensao = ($this->rh305_vlrpensao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh305_vlrpensao"]:$this->rh305_vlrpensao);
     }else{
       $this->rh305_sequencial = ($this->rh305_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh305_sequencial"]:$this->rh305_sequencial);
     }
   }

    public function incluir($rh305_sequencial)
    {
      $this->atualizacampos();
     if($this->rh305_sequencialtributoirrf == null ){ 
       $this->erro_sql = " Campo Sequencial vinculo tributo não informado.";
       $this->erro_campo = "rh305_sequencialtributoirrf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh305_tprend == null ){ 
       $this->rh305_tprend = "0";
     }
     if($this->rh305_vlrpensao == null ){ 
       $this->rh305_vlrpensao = "0";
     }
     if($rh305_sequencial == "" || $rh305_sequencial == null ){
       $result = db_query("select nextval('rhprocessoretencao_rh306_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhprocessoretencao_rh306_sequencial_seq do campo: rh305_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh305_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhprocessoretencao_rh306_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh305_sequencial)){
         $this->erro_sql = " Campo rh305_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh305_sequencial = $rh305_sequencial; 
       }
     }
     if(($this->rh305_sequencial == null) || ($this->rh305_sequencial == "") ){ 
       $this->erro_sql = " Campo rh305_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhprocessopensao(
                                       rh305_sequencial 
                                      ,rh305_sequencialtributoirrf 
                                      ,rh305_tprend 
                                      ,rh305_cpfdep 
                                      ,rh305_vlrpensao 
                       )
                values (
                                $this->rh305_sequencial 
                               ,$this->rh305_sequencialtributoirrf 
                               ,$this->rh305_tprend 
                               ,'$this->rh305_cpfdep' 
                               ,$this->rh305_vlrpensao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Pensão alimentícia ($this->rh305_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Pensão alimentícia já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Pensão alimentícia ($this->rh305_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh305_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh305_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1015422,'$this->rh305_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1011140,1015422,'','".AddSlashes(pg_result($resaco,0,'rh305_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011140,1015423,'','".AddSlashes(pg_result($resaco,0,'rh305_sequencialtributoirrf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011140,1015424,'','".AddSlashes(pg_result($resaco,0,'rh305_tprend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011140,1015425,'','".AddSlashes(pg_result($resaco,0,'rh305_cpfdep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011140,1015426,'','".AddSlashes(pg_result($resaco,0,'rh305_vlrpensao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh305_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhprocessopensao set ";
     $virgula = "";
     if(trim($this->rh305_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh305_sequencial"])){ 
       $sql  .= $virgula." rh305_sequencial = $this->rh305_sequencial ";
       $virgula = ",";
       if(trim($this->rh305_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh305_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh305_sequencialtributoirrf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh305_sequencialtributoirrf"])){ 
       $sql  .= $virgula." rh305_sequencialtributoirrf = $this->rh305_sequencialtributoirrf ";
       $virgula = ",";
       if(trim($this->rh305_sequencialtributoirrf) == null ){ 
         $this->erro_sql = " Campo Sequencial vinculo tributo não informado.";
         $this->erro_campo = "rh305_sequencialtributoirrf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh305_tprend)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh305_tprend"])){ 
        if(trim($this->rh305_tprend)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh305_tprend"])){ 
           $this->rh305_tprend = "0" ; 
        } 
       $sql  .= $virgula." rh305_tprend = $this->rh305_tprend ";
       $virgula = ",";
     }
     if(trim($this->rh305_cpfdep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh305_cpfdep"])){ 
       $sql  .= $virgula." rh305_cpfdep = '$this->rh305_cpfdep' ";
       $virgula = ",";
     }
     if(trim($this->rh305_vlrpensao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh305_vlrpensao"])){ 
        if(trim($this->rh305_vlrpensao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh305_vlrpensao"])){ 
           $this->rh305_vlrpensao = "0" ; 
        } 
       $sql  .= $virgula." rh305_vlrpensao = $this->rh305_vlrpensao ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh305_sequencial!=null){
       $sql .= " rh305_sequencial = $this->rh305_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh305_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1015422,'$this->rh305_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh305_sequencial"]) || $this->rh305_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1011140,1015422,'".AddSlashes(pg_result($resaco,$conresaco,'rh305_sequencial'))."','$this->rh305_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh305_sequencialtributoirrf"]) || $this->rh305_sequencialtributoirrf != "")
             $resac = db_query("insert into db_acount values($acount,1011140,1015423,'".AddSlashes(pg_result($resaco,$conresaco,'rh305_sequencialtributoirrf'))."','$this->rh305_sequencialtributoirrf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh305_tprend"]) || $this->rh305_tprend != "")
             $resac = db_query("insert into db_acount values($acount,1011140,1015424,'".AddSlashes(pg_result($resaco,$conresaco,'rh305_tprend'))."','$this->rh305_tprend',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh305_cpfdep"]) || $this->rh305_cpfdep != "")
             $resac = db_query("insert into db_acount values($acount,1011140,1015425,'".AddSlashes(pg_result($resaco,$conresaco,'rh305_cpfdep'))."','$this->rh305_cpfdep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh305_vlrpensao"]) || $this->rh305_vlrpensao != "")
             $resac = db_query("insert into db_acount values($acount,1011140,1015426,'".AddSlashes(pg_result($resaco,$conresaco,'rh305_vlrpensao'))."','$this->rh305_vlrpensao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Pensão alimentícia não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh305_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Pensão alimentícia não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh305_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh305_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh305_sequencial=null, $dbwhere = null)
    {
     $sql = " delete from rhprocessopensao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh305_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh305_sequencial = $rh305_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Pensão alimentícia não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh305_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Pensão alimentícia não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh305_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh305_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhprocessopensao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh305_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhprocessopensao ";
     $sql .= "      inner join rhprocessotributoirrf  on  rhprocessotributoirrf.rh299_sequencial = rhprocessopensao.rh305_sequencialtributoirrf";
     $sql .= "      inner join rhpessoalprocessoservidor  on  rhpessoalprocessoservidor.rh271_sequencial = rhprocessotributoirrf.rh299_sequencialprocessoservidor";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh305_sequencial)) {
         $sql2 .= " where rhprocessopensao.rh305_sequencial = $rh305_sequencial "; 
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

    public function sql_query_file($rh305_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhprocessopensao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh305_sequencial)){
         $sql2 .= " where rhprocessopensao.rh305_sequencial = $rh305_sequencial "; 
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
