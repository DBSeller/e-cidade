<?php

class cl_rhprocessoadvogado
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
    public $rh303_sequencial = 0; 
    public $rh303_sequencialtributoirrf = 0; 
    public $rh303_tpInsc = 0; 
    public $rh303_nrInsc = null; 
    public $rh303_vlradv = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh303_sequencial = int8 = Sequencial 
                 rh303_sequencialtributoirrf = int8 = Sequencial vinculo tributo 
                 rh303_tpInsc = int4 = Tipo de inscrição 
                 rh303_nrInsc = varchar(14) = Número de inscrição 
                 rh303_vlradv = float4 = Valor despesa 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhprocessoadvogado"); 
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
       $this->rh303_sequencial = ($this->rh303_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh303_sequencial"]:$this->rh303_sequencial);
       $this->rh303_sequencialtributoirrf = ($this->rh303_sequencialtributoirrf == ""?@$GLOBALS["HTTP_POST_VARS"]["rh303_sequencialtributoirrf"]:$this->rh303_sequencialtributoirrf);
       $this->rh303_tpInsc = ($this->rh303_tpInsc == ""?@$GLOBALS["HTTP_POST_VARS"]["rh303_tpInsc"]:$this->rh303_tpInsc);
       $this->rh303_nrInsc = ($this->rh303_nrInsc == ""?@$GLOBALS["HTTP_POST_VARS"]["rh303_nrInsc"]:$this->rh303_nrInsc);
       $this->rh303_vlradv = ($this->rh303_vlradv == ""?@$GLOBALS["HTTP_POST_VARS"]["rh303_vlradv"]:$this->rh303_vlradv);
     }else{
       $this->rh303_sequencial = ($this->rh303_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh303_sequencial"]:$this->rh303_sequencial);
     }
   }

    public function incluir($rh303_sequencial)
    {
      $this->atualizacampos();
     if($this->rh303_sequencialtributoirrf == null ){ 
       $this->erro_sql = " Campo Sequencial vinculo tributo não informado.";
       $this->erro_campo = "rh303_sequencialtributoirrf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh303_tpInsc == null ){ 
       $this->rh303_tpInsc = "0";
     }
     if($this->rh303_vlradv == null ){ 
       $this->rh303_vlradv = "0";
     }
     if($rh303_sequencial == "" || $rh303_sequencial == null ){
       $result = db_query("select nextval('rhprocessoadvogado_rh303_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhprocessoadvogado_rh303_sequencial_seq do campo: rh303_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh303_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhprocessoadvogado_rh303_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh303_sequencial)){
         $this->erro_sql = " Campo rh303_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh303_sequencial = $rh303_sequencial; 
       }
     }
     if(($this->rh303_sequencial == null) || ($this->rh303_sequencial == "") ){ 
       $this->erro_sql = " Campo rh303_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhprocessoadvogado(
                                       rh303_sequencial 
                                      ,rh303_sequencialtributoirrf 
                                      ,rh303_tpInsc 
                                      ,rh303_nrInsc 
                                      ,rh303_vlradv 
                       )
                values (
                                $this->rh303_sequencial 
                               ,$this->rh303_sequencialtributoirrf 
                               ,$this->rh303_tpInsc 
                               ,'$this->rh303_nrInsc' 
                               ,$this->rh303_vlradv 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Identificação dos advogados ($this->rh303_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Identificação dos advogados já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Identificação dos advogados ($this->rh303_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh303_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh303_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1015412,'$this->rh303_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1011137,1015412,'','".AddSlashes(pg_result($resaco,0,'rh303_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011137,1015413,'','".AddSlashes(pg_result($resaco,0,'rh303_sequencialtributoirrf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011137,1015414,'','".AddSlashes(pg_result($resaco,0,'rh303_tpInsc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011137,1015415,'','".AddSlashes(pg_result($resaco,0,'rh303_nrInsc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011137,1015416,'','".AddSlashes(pg_result($resaco,0,'rh303_vlradv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh303_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhprocessoadvogado set ";
     $virgula = "";
     if(trim($this->rh303_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh303_sequencial"])){ 
       $sql  .= $virgula." rh303_sequencial = $this->rh303_sequencial ";
       $virgula = ",";
       if(trim($this->rh303_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh303_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh303_sequencialtributoirrf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh303_sequencialtributoirrf"])){ 
       $sql  .= $virgula." rh303_sequencialtributoirrf = $this->rh303_sequencialtributoirrf ";
       $virgula = ",";
       if(trim($this->rh303_sequencialtributoirrf) == null ){ 
         $this->erro_sql = " Campo Sequencial vinculo tributo não informado.";
         $this->erro_campo = "rh303_sequencialtributoirrf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh303_tpInsc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh303_tpInsc"])){ 
        if(trim($this->rh303_tpInsc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh303_tpInsc"])){ 
           $this->rh303_tpInsc = "0" ; 
        } 
       $sql  .= $virgula." rh303_tpInsc = $this->rh303_tpInsc ";
       $virgula = ",";
     }
     if(trim($this->rh303_nrInsc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh303_nrInsc"])){ 
       $sql  .= $virgula." rh303_nrInsc = '$this->rh303_nrInsc' ";
       $virgula = ",";
     }
     if(trim($this->rh303_vlradv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh303_vlradv"])){ 
        if(trim($this->rh303_vlradv)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh303_vlradv"])){ 
           $this->rh303_vlradv = "0" ; 
        } 
       $sql  .= $virgula." rh303_vlradv = $this->rh303_vlradv ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh303_sequencial!=null){
       $sql .= " rh303_sequencial = $this->rh303_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh303_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1015412,'$this->rh303_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh303_sequencial"]) || $this->rh303_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1011137,1015412,'".AddSlashes(pg_result($resaco,$conresaco,'rh303_sequencial'))."','$this->rh303_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh303_sequencialtributoirrf"]) || $this->rh303_sequencialtributoirrf != "")
             $resac = db_query("insert into db_acount values($acount,1011137,1015413,'".AddSlashes(pg_result($resaco,$conresaco,'rh303_sequencialtributoirrf'))."','$this->rh303_sequencialtributoirrf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh303_tpInsc"]) || $this->rh303_tpInsc != "")
             $resac = db_query("insert into db_acount values($acount,1011137,1015414,'".AddSlashes(pg_result($resaco,$conresaco,'rh303_tpInsc'))."','$this->rh303_tpInsc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh303_nrInsc"]) || $this->rh303_nrInsc != "")
             $resac = db_query("insert into db_acount values($acount,1011137,1015415,'".AddSlashes(pg_result($resaco,$conresaco,'rh303_nrInsc'))."','$this->rh303_nrInsc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh303_vlradv"]) || $this->rh303_vlradv != "")
             $resac = db_query("insert into db_acount values($acount,1011137,1015416,'".AddSlashes(pg_result($resaco,$conresaco,'rh303_vlradv'))."','$this->rh303_vlradv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Identificação dos advogados não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh303_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Identificação dos advogados não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh303_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh303_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh303_sequencial=null, $dbwhere = null)
    {
     $sql = " delete from rhprocessoadvogado
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh303_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh303_sequencial = $rh303_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }

     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Identificação dos advogados não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh303_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Identificação dos advogados não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh303_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh303_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhprocessoadvogado";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh303_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhprocessoadvogado ";
     $sql .= "      inner join rhprocessotributoirrf  on  rhprocessotributoirrf.rh299_sequencial = rhprocessoadvogado.rh303_sequencialtributoirrf";
     $sql .= "      inner join rhpessoalprocessoservidor  on  rhpessoalprocessoservidor.rh271_sequencial = rhprocessotributoirrf.rh299_sequencialprocessoservidor";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh303_sequencial)) {
         $sql2 .= " where rhprocessoadvogado.rh303_sequencial = $rh303_sequencial "; 
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

    public function sql_query_file($rh303_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhprocessoadvogado ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh303_sequencial)){
         $sql2 .= " where rhprocessoadvogado.rh303_sequencial = $rh303_sequencial "; 
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
