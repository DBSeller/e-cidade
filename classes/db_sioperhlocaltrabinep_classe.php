<?php

class cl_sioperhlocaltrabinep
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
    public $si05_rhlocaltrab = 0; 
    public $si05_instit = 0; 
    public $si05_inep = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 si05_rhlocaltrab = int4 = Local de Trabalho 
                 si05_instit = int4 = Instituição 
                 si05_inep = int4 = Código do Inep 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("sioperhlocaltrabinep"); 
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
       $this->si05_rhlocaltrab = ($this->si05_rhlocaltrab == ""?@$GLOBALS["HTTP_POST_VARS"]["si05_rhlocaltrab"]:$this->si05_rhlocaltrab);
       $this->si05_instit = ($this->si05_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["si05_instit"]:$this->si05_instit);
       $this->si05_inep = ($this->si05_inep == ""?@$GLOBALS["HTTP_POST_VARS"]["si05_inep"]:$this->si05_inep);
     }else{
       $this->si05_rhlocaltrab = ($this->si05_rhlocaltrab == ""?@$GLOBALS["HTTP_POST_VARS"]["si05_rhlocaltrab"]:$this->si05_rhlocaltrab);
     }
   }

    public function incluir($si05_rhlocaltrab)
    {
      $this->atualizacampos();
     if($this->si05_instit == null ){ 
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "si05_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->si05_inep == null ){ 
       $this->erro_sql = " Campo Código do Inep não informado.";
       $this->erro_campo = "si05_inep";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->si05_rhlocaltrab = $si05_rhlocaltrab; 
     if(($this->si05_rhlocaltrab == null) || ($this->si05_rhlocaltrab == "") ){ 
       $this->erro_sql = " Campo si05_rhlocaltrab não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sioperhlocaltrabinep(
                                       si05_rhlocaltrab 
                                      ,si05_instit 
                                      ,si05_inep 
                       )
                values (
                                $this->si05_rhlocaltrab 
                               ,$this->si05_instit 
                               ,$this->si05_inep 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "sioperhlocaltrabinep ($this->si05_rhlocaltrab) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "sioperhlocaltrabinep já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "sioperhlocaltrabinep ($this->si05_rhlocaltrab) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->si05_rhlocaltrab;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->si05_rhlocaltrab  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,262347194,'$this->si05_rhlocaltrab','I')");
         $resac = db_query("insert into db_acount values($acount,111279381,262347194,'','".AddSlashes(pg_result($resaco,0,'si05_rhlocaltrab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,111279381,239460891,'','".AddSlashes(pg_result($resaco,0,'si05_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,111279381,203460522,'','".AddSlashes(pg_result($resaco,0,'si05_inep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($si05_rhlocaltrab=null)
    {
      $this->atualizacampos();
     $sql = " update sioperhlocaltrabinep set ";
     $virgula = "";
     if(trim($this->si05_rhlocaltrab)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si05_rhlocaltrab"])){ 
       $sql  .= $virgula." si05_rhlocaltrab = $this->si05_rhlocaltrab ";
       $virgula = ",";
       if(trim($this->si05_rhlocaltrab) == null ){ 
         $this->erro_sql = " Campo Local de Trabalho não informado.";
         $this->erro_campo = "si05_rhlocaltrab";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si05_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si05_instit"])){ 
       $sql  .= $virgula." si05_instit = $this->si05_instit ";
       $virgula = ",";
       if(trim($this->si05_instit) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "si05_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si05_inep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si05_inep"])){ 
       $sql  .= $virgula." si05_inep = $this->si05_inep ";
       $virgula = ",";
       if(trim($this->si05_inep) == null ){ 
         $this->erro_sql = " Campo Código do Inep não informado.";
         $this->erro_campo = "si05_inep";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($si05_rhlocaltrab!=null){
       $sql .= " si05_rhlocaltrab = $this->si05_rhlocaltrab";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->si05_rhlocaltrab));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,262347194,'$this->si05_rhlocaltrab','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["si05_rhlocaltrab"]) || $this->si05_rhlocaltrab != "")
             $resac = db_query("insert into db_acount values($acount,111279381,262347194,'".AddSlashes(pg_result($resaco,$conresaco,'si05_rhlocaltrab'))."','$this->si05_rhlocaltrab',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["si05_instit"]) || $this->si05_instit != "")
             $resac = db_query("insert into db_acount values($acount,111279381,239460891,'".AddSlashes(pg_result($resaco,$conresaco,'si05_instit'))."','$this->si05_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["si05_inep"]) || $this->si05_inep != "")
             $resac = db_query("insert into db_acount values($acount,111279381,203460522,'".AddSlashes(pg_result($resaco,$conresaco,'si05_inep'))."','$this->si05_inep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sioperhlocaltrabinep não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->si05_rhlocaltrab;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "sioperhlocaltrabinep não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->si05_rhlocaltrab;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->si05_rhlocaltrab;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($si05_rhlocaltrab=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($si05_rhlocaltrab));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,262347194,'$si05_rhlocaltrab','E')");
           $resac  = db_query("insert into db_acount values($acount,111279381,262347194,'','".AddSlashes(pg_result($resaco,$iresaco,'si05_rhlocaltrab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,111279381,239460891,'','".AddSlashes(pg_result($resaco,$iresaco,'si05_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,111279381,203460522,'','".AddSlashes(pg_result($resaco,$iresaco,'si05_inep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from sioperhlocaltrabinep
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($si05_rhlocaltrab)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " si05_rhlocaltrab = $si05_rhlocaltrab ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "sioperhlocaltrabinep não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$si05_rhlocaltrab;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "sioperhlocaltrabinep não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$si05_rhlocaltrab;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$si05_rhlocaltrab;
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
        $this->erro_sql   = "Record Vazio na Tabela:sioperhlocaltrabinep";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($si05_rhlocaltrab = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from sioperhlocaltrabinep ";
     $sql .= "      inner join rhlocaltrab  on  rhlocaltrab.rh55_codigo = sioperhlocaltrabinep.si05_rhlocaltrab and  rhlocaltrab.rh55_instit = sioperhlocaltrabinep.si05_instit";
     $sql .= "      inner join db_config  on  db_config.codigo = rhlocaltrab.rh55_instit";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($si05_rhlocaltrab)) {
         $sql2 .= " where sioperhlocaltrabinep.si05_rhlocaltrab = $si05_rhlocaltrab "; 
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

    public function sql_query_file($si05_rhlocaltrab = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from sioperhlocaltrabinep ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($si05_rhlocaltrab)){
         $sql2 .= " where sioperhlocaltrabinep.si05_rhlocaltrab = $si05_rhlocaltrab "; 
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
