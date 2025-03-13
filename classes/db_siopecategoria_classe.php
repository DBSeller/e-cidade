<?php

class cl_siopecategoria
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
    public $si03_id = 0; 
    public $si03_siopecategoriatipo = 0; 
    public $si03_descricao = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 si03_id = int4 = Código da Categoria 
                 si03_siopecategoriatipo = int4 = Código do Tipo de Categoria 
                 si03_descricao = varchar = Categoria 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("siopecategoria"); 
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
       $this->si03_id = ($this->si03_id == ""?@$GLOBALS["HTTP_POST_VARS"]["si03_id"]:$this->si03_id);
       $this->si03_siopecategoriatipo = ($this->si03_siopecategoriatipo == ""?@$GLOBALS["HTTP_POST_VARS"]["si03_siopecategoriatipo"]:$this->si03_siopecategoriatipo);
       $this->si03_descricao = ($this->si03_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["si03_descricao"]:$this->si03_descricao);
     }else{
       $this->si03_id = ($this->si03_id == ""?@$GLOBALS["HTTP_POST_VARS"]["si03_id"]:$this->si03_id);
     }
   }

    public function incluir($si03_id)
    {
      $this->atualizacampos();
     if($this->si03_siopecategoriatipo == null ){ 
       $this->erro_sql = " Campo Código do Tipo de Categoria não informado.";
       $this->erro_campo = "si03_siopecategoriatipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($si03_id == "" || $si03_id == null ){
       $result = db_query("select nextval('siopecategoria_id_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: siopecategoria_id_seq do campo: si03_id"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->si03_id = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from siopecategoria_id_seq");
       if(($result != false) && (pg_result($result,0,0) < $si03_id)){
         $this->erro_sql = " Campo si03_id maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->si03_id = $si03_id; 
       }
     }
     if(($this->si03_id == null) || ($this->si03_id == "") ){ 
       $this->erro_sql = " Campo si03_id não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into siopecategoria(
                                       si03_id 
                                      ,si03_siopecategoriatipo 
                                      ,si03_descricao 
                       )
                values (
                                $this->si03_id 
                               ,$this->si03_siopecategoriatipo 
                               ,'$this->si03_descricao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "siopecategoria ($this->si03_id) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "siopecategoria já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "siopecategoria ($this->si03_id) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->si03_id;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->si03_id  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,59832679,'$this->si03_id','I')");
         $resac = db_query("insert into db_acount values($acount,102001306,59832679,'','".AddSlashes(pg_result($resaco,0,'si03_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,102001306,157840730,'','".AddSlashes(pg_result($resaco,0,'si03_siopecategoriatipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,102001306,296720739,'','".AddSlashes(pg_result($resaco,0,'si03_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($si03_id=null)
    {
      $this->atualizacampos();
     $sql = " update siopecategoria set ";
     $virgula = "";
     if(trim($this->si03_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si03_id"])){ 
       $sql  .= $virgula." si03_id = $this->si03_id ";
       $virgula = ",";
       if(trim($this->si03_id) == null ){ 
         $this->erro_sql = " Campo Código da Categoria não informado.";
         $this->erro_campo = "si03_id";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si03_siopecategoriatipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si03_siopecategoriatipo"])){ 
       $sql  .= $virgula." si03_siopecategoriatipo = $this->si03_siopecategoriatipo ";
       $virgula = ",";
       if(trim($this->si03_siopecategoriatipo) == null ){ 
         $this->erro_sql = " Campo Código do Tipo de Categoria não informado.";
         $this->erro_campo = "si03_siopecategoriatipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si03_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si03_descricao"])){ 
       $sql  .= $virgula." si03_descricao = '$this->si03_descricao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($si03_id!=null){
       $sql .= " si03_id = $this->si03_id";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->si03_id));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,59832679,'$this->si03_id','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["si03_id"]) || $this->si03_id != "")
             $resac = db_query("insert into db_acount values($acount,102001306,59832679,'".AddSlashes(pg_result($resaco,$conresaco,'si03_id'))."','$this->si03_id',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["si03_siopecategoriatipo"]) || $this->si03_siopecategoriatipo != "")
             $resac = db_query("insert into db_acount values($acount,102001306,157840730,'".AddSlashes(pg_result($resaco,$conresaco,'si03_siopecategoriatipo'))."','$this->si03_siopecategoriatipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["si03_descricao"]) || $this->si03_descricao != "")
             $resac = db_query("insert into db_acount values($acount,102001306,296720739,'".AddSlashes(pg_result($resaco,$conresaco,'si03_descricao'))."','$this->si03_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "siopecategoria não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->si03_id;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "siopecategoria não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->si03_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->si03_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($si03_id=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($si03_id));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,59832679,'$si03_id','E')");
           $resac  = db_query("insert into db_acount values($acount,102001306,59832679,'','".AddSlashes(pg_result($resaco,$iresaco,'si03_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,102001306,157840730,'','".AddSlashes(pg_result($resaco,$iresaco,'si03_siopecategoriatipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,102001306,296720739,'','".AddSlashes(pg_result($resaco,$iresaco,'si03_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from siopecategoria
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($si03_id)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " si03_id = $si03_id ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "siopecategoria não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$si03_id;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "siopecategoria não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$si03_id;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$si03_id;
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
        $this->erro_sql   = "Record Vazio na Tabela:siopecategoria";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($si03_id = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from siopecategoria ";
     $sql .= "      inner join siopecategoriatipo  on  siopecategoriatipo.si02_id = siopecategoria.si03_siopecategoriatipo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($si03_id)) {
         $sql2 .= " where siopecategoria.si03_id = $si03_id "; 
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

    public function sql_query_file($si03_id = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from siopecategoria ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($si03_id)){
         $sql2 .= " where siopecategoria.si03_id = $si03_id "; 
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
