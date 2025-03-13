<?php

class cl_lab_grupoexame
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
    public $la68_codigo = 0; 
    public $la68_exame = 0; 
    public $la68_labgrupoexame = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 la68_codigo = int4 = Código do Grupo de Exames 
                 la68_exame = int4 = Código do Exame 
                 la68_labgrupoexame = int4 = Grupo Laboratório 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("lab_grupoexame"); 
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
       $this->la68_codigo = ($this->la68_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la68_codigo"]:$this->la68_codigo);
       $this->la68_exame = ($this->la68_exame == ""?@$GLOBALS["HTTP_POST_VARS"]["la68_exame"]:$this->la68_exame);
       $this->la68_labgrupoexame = ($this->la68_labgrupoexame == ""?@$GLOBALS["HTTP_POST_VARS"]["la68_labgrupoexame"]:$this->la68_labgrupoexame);
     }else{
       $this->la68_codigo = ($this->la68_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la68_codigo"]:$this->la68_codigo);
     }
   }

    public function incluir($la68_codigo)
    {
      $this->atualizacampos();
     if($this->la68_exame == null ){ 
       $this->erro_sql = " Campo Código do Exame não informado.";
       $this->erro_campo = "la68_exame";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la68_labgrupoexame == null ){ 
       $this->erro_sql = " Campo Grupo Laboratório não informado.";
       $this->erro_campo = "la68_labgrupoexame";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($la68_codigo == "" || $la68_codigo == null ){
       $result = db_query("select nextval('lab_grupoexame_la68_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lab_grupoexame_la68_codigo_seq do campo: la68_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->la68_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from lab_grupoexame_la68_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $la68_codigo)){
         $this->erro_sql = " Campo la68_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la68_codigo = $la68_codigo; 
       }
     }
     if(($this->la68_codigo == null) || ($this->la68_codigo == "") ){ 
       $this->erro_sql = " Campo la68_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lab_grupoexame(
                                       la68_codigo 
                                      ,la68_exame 
                                      ,la68_labgrupoexame 
                       )
                values (
                                $this->la68_codigo 
                               ,$this->la68_exame 
                               ,$this->la68_labgrupoexame 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Vinculo de Grupo com Exames ($this->la68_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Vinculo de Grupo com Exames já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Vinculo de Grupo com Exames ($this->la68_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->la68_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->la68_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1012010,'$this->la68_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010668,1012010,'','".AddSlashes(pg_result($resaco,0,'la68_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010668,1012012,'','".AddSlashes(pg_result($resaco,0,'la68_exame'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010668,1012120,'','".AddSlashes(pg_result($resaco,0,'la68_labgrupoexame'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($la68_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update lab_grupoexame set ";
     $virgula = "";
     if(trim($this->la68_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la68_codigo"])){ 
       $sql  .= $virgula." la68_codigo = $this->la68_codigo ";
       $virgula = ",";
       if(trim($this->la68_codigo) == null ){ 
         $this->erro_sql = " Campo Código do Grupo de Exames não informado.";
         $this->erro_campo = "la68_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la68_exame)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la68_exame"])){ 
       $sql  .= $virgula." la68_exame = $this->la68_exame ";
       $virgula = ",";
       if(trim($this->la68_exame) == null ){ 
         $this->erro_sql = " Campo Código do Exame não informado.";
         $this->erro_campo = "la68_exame";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la68_labgrupoexame)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la68_labgrupoexame"])){ 
       $sql  .= $virgula." la68_labgrupoexame = $this->la68_labgrupoexame ";
       $virgula = ",";
       if(trim($this->la68_labgrupoexame) == null ){ 
         $this->erro_sql = " Campo Grupo Laboratório não informado.";
         $this->erro_campo = "la68_labgrupoexame";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($la68_codigo!=null){
       $sql .= " la68_codigo = $this->la68_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->la68_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1012010,'$this->la68_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la68_codigo"]) || $this->la68_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010668,1012010,'".AddSlashes(pg_result($resaco,$conresaco,'la68_codigo'))."','$this->la68_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la68_exame"]) || $this->la68_exame != "")
             $resac = db_query("insert into db_acount values($acount,1010668,1012012,'".AddSlashes(pg_result($resaco,$conresaco,'la68_exame'))."','$this->la68_exame',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la68_labgrupoexame"]) || $this->la68_labgrupoexame != "")
             $resac = db_query("insert into db_acount values($acount,1010668,1012120,'".AddSlashes(pg_result($resaco,$conresaco,'la68_labgrupoexame'))."','$this->la68_labgrupoexame',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculo de Grupo com Exames não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la68_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Vinculo de Grupo com Exames não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la68_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->la68_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($la68_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($la68_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1012010,'$la68_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010668,1012010,'','".AddSlashes(pg_result($resaco,$iresaco,'la68_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010668,1012012,'','".AddSlashes(pg_result($resaco,$iresaco,'la68_exame'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010668,1012120,'','".AddSlashes(pg_result($resaco,$iresaco,'la68_labgrupoexame'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from lab_grupoexame
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($la68_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " la68_codigo = $la68_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculo de Grupo com Exames não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la68_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Vinculo de Grupo com Exames não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la68_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$la68_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:lab_grupoexame";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($la68_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from lab_grupoexame ";
     $sql .= "      inner join lab_exame  on  lab_exame.la08_i_codigo = lab_grupoexame.la68_exame";
     $sql .= "      inner join lab_labgrupoexame  on  lab_labgrupoexame.la67_codigo = lab_grupoexame.la68_labgrupoexame";
     $sql .= "      inner join lab_laboratorio  on  lab_laboratorio.la02_i_codigo = lab_labgrupoexame.la67_laboratorio";
     $sql .= "      inner join lab_grupo  on  lab_grupo.la66_codigo = lab_labgrupoexame.la67_grupo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($la68_codigo)) {
         $sql2 .= " where lab_grupoexame.la68_codigo = $la68_codigo "; 
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

    public function sql_query_file($la68_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from lab_grupoexame ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($la68_codigo)){
         $sql2 .= " where lab_grupoexame.la68_codigo = $la68_codigo "; 
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
