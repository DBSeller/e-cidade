<?php

class cl_pcorcamitemresultado
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
    public $pc220_orcamitem = 0; 
    public $pc220_resultado = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 pc220_orcamitem = int8 = Item Proposta 
                 pc220_resultado = char(1) = Resultado 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("pcorcamitemresultado"); 
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
       $this->pc220_orcamitem = ($this->pc220_orcamitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc220_orcamitem"]:$this->pc220_orcamitem);
       $this->pc220_resultado = ($this->pc220_resultado == ""?@$GLOBALS["HTTP_POST_VARS"]["pc220_resultado"]:$this->pc220_resultado);
     }else{
       $this->pc220_orcamitem = ($this->pc220_orcamitem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc220_orcamitem"]:$this->pc220_orcamitem);
     }
   }

    public function incluir($pc220_orcamitem)
    {
      $this->atualizacampos();
     if($this->pc220_resultado == null ){ 
       $this->erro_sql = " Campo Resultado não informado.";
       $this->erro_campo = "pc220_resultado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->pc220_orcamitem = $pc220_orcamitem; 
     if(($this->pc220_orcamitem == null) || ($this->pc220_orcamitem == "") ){ 
       $this->erro_sql = " Campo pc220_orcamitem não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcorcamitemresultado(
                                       pc220_orcamitem 
                                      ,pc220_resultado 
                       )
                values (
                                $this->pc220_orcamitem 
                               ,'$this->pc220_resultado' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Resultado do item ($this->pc220_orcamitem) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Resultado do item já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Resultado do item ($this->pc220_orcamitem) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->pc220_orcamitem;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->pc220_orcamitem  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1013452,'$this->pc220_orcamitem','I')");
         $resac = db_query("insert into db_acount values($acount,1010830,1013452,'','".AddSlashes(pg_result($resaco,0,'pc220_orcamitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010830,1013453,'','".AddSlashes(pg_result($resaco,0,'pc220_resultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($pc220_orcamitem=null)
    {
      $this->atualizacampos();
     $sql = " update pcorcamitemresultado set ";
     $virgula = "";
     if(trim($this->pc220_orcamitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc220_orcamitem"])){ 
       $sql  .= $virgula." pc220_orcamitem = $this->pc220_orcamitem ";
       $virgula = ",";
       if(trim($this->pc220_orcamitem) == null ){ 
         $this->erro_sql = " Campo Item Proposta não informado.";
         $this->erro_campo = "pc220_orcamitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc220_resultado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc220_resultado"])){ 
       $sql  .= $virgula." pc220_resultado = '$this->pc220_resultado' ";
       $virgula = ",";
       if(trim($this->pc220_resultado) == null ){ 
         $this->erro_sql = " Campo Resultado não informado.";
         $this->erro_campo = "pc220_resultado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc220_orcamitem!=null){
       $sql .= " pc220_orcamitem = $this->pc220_orcamitem";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->pc220_orcamitem));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1013452,'$this->pc220_orcamitem','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc220_orcamitem"]) || $this->pc220_orcamitem != "")
             $resac = db_query("insert into db_acount values($acount,1010830,1013452,'".AddSlashes(pg_result($resaco,$conresaco,'pc220_orcamitem'))."','$this->pc220_orcamitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc220_resultado"]) || $this->pc220_resultado != "")
             $resac = db_query("insert into db_acount values($acount,1010830,1013453,'".AddSlashes(pg_result($resaco,$conresaco,'pc220_resultado'))."','$this->pc220_resultado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Resultado do item não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc220_orcamitem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Resultado do item não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc220_orcamitem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->pc220_orcamitem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($pc220_orcamitem=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($pc220_orcamitem));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1013452,'$pc220_orcamitem','E')");
           $resac  = db_query("insert into db_acount values($acount,1010830,1013452,'','".AddSlashes(pg_result($resaco,$iresaco,'pc220_orcamitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010830,1013453,'','".AddSlashes(pg_result($resaco,$iresaco,'pc220_resultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from pcorcamitemresultado
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($pc220_orcamitem)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " pc220_orcamitem = $pc220_orcamitem ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Resultado do item não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc220_orcamitem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Resultado do item não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc220_orcamitem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$pc220_orcamitem;
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
        $this->erro_sql   = "Record Vazio na Tabela:pcorcamitemresultado";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($pc220_orcamitem = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from pcorcamitemresultado ";
     $sql .= "      inner join pcorcamitem  on  pcorcamitem.pc22_orcamitem = pcorcamitemresultado.pc220_orcamitem";
     $sql .= "      inner join pcorcam  on  pcorcam.pc20_codorc = pcorcamitem.pc22_codorc";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($pc220_orcamitem)) {
         $sql2 .= " where pcorcamitemresultado.pc220_orcamitem = $pc220_orcamitem "; 
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

    public function sql_query_file($pc220_orcamitem = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from pcorcamitemresultado ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($pc220_orcamitem)){
         $sql2 .= " where pcorcamitemresultado.pc220_orcamitem = $pc220_orcamitem "; 
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
