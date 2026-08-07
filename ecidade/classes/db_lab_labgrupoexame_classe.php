<?php

class cl_lab_labgrupoexame
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
    public $la67_codigo = 0; 
    public $la67_laboratorio = 0; 
    public $la67_grupo = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 la67_codigo = int4 = Código 
                 la67_laboratorio = int4 = Código do laboratório 
                 la67_grupo = int4 = Grupo 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("lab_labgrupoexame"); 
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
       $this->la67_codigo = ($this->la67_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la67_codigo"]:$this->la67_codigo);
       $this->la67_laboratorio = ($this->la67_laboratorio == ""?@$GLOBALS["HTTP_POST_VARS"]["la67_laboratorio"]:$this->la67_laboratorio);
       $this->la67_grupo = ($this->la67_grupo == ""?@$GLOBALS["HTTP_POST_VARS"]["la67_grupo"]:$this->la67_grupo);
     }else{
       $this->la67_codigo = ($this->la67_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la67_codigo"]:$this->la67_codigo);
     }
   }

    public function incluir($la67_codigo)
    {
      $this->atualizacampos();
     if($this->la67_laboratorio == null ){ 
       $this->erro_sql = " Campo Código do laboratório não informado.";
       $this->erro_campo = "la67_laboratorio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la67_grupo == null ){ 
       $this->erro_sql = " Campo Grupo não informado.";
       $this->erro_campo = "la67_grupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($la67_codigo == "" || $la67_codigo == null ){
       $result = db_query("select nextval('lab_labgrupoexame_la67_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lab_labgrupoexame_la67_codigo_seq do campo: la67_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->la67_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from lab_labgrupoexame_la67_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $la67_codigo)){
         $this->erro_sql = " Campo la67_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la67_codigo = $la67_codigo; 
       }
     }
     if(($this->la67_codigo == null) || ($this->la67_codigo == "") ){ 
       $this->erro_sql = " Campo la67_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lab_labgrupoexame(
                                       la67_codigo 
                                      ,la67_laboratorio 
                                      ,la67_grupo 
                       )
                values (
                                $this->la67_codigo 
                               ,$this->la67_laboratorio 
                               ,$this->la67_grupo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Vinculo de Grupo de Exames com Laboratório ($this->la67_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Vinculo de Grupo de Exames com Laboratório já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Vinculo de Grupo de Exames com Laboratório ($this->la67_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->la67_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->la67_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1012004,'$this->la67_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010667,1012004,'','".AddSlashes(pg_result($resaco,0,'la67_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010667,1012005,'','".AddSlashes(pg_result($resaco,0,'la67_laboratorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010667,1012119,'','".AddSlashes(pg_result($resaco,0,'la67_grupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($la67_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update lab_labgrupoexame set ";
     $virgula = "";
     if(trim($this->la67_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la67_codigo"])){ 
       $sql  .= $virgula." la67_codigo = $this->la67_codigo ";
       $virgula = ",";
       if(trim($this->la67_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "la67_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la67_laboratorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la67_laboratorio"])){ 
       $sql  .= $virgula." la67_laboratorio = $this->la67_laboratorio ";
       $virgula = ",";
       if(trim($this->la67_laboratorio) == null ){ 
         $this->erro_sql = " Campo Código do laboratório não informado.";
         $this->erro_campo = "la67_laboratorio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la67_grupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la67_grupo"])){ 
       $sql  .= $virgula." la67_grupo = $this->la67_grupo ";
       $virgula = ",";
       if(trim($this->la67_grupo) == null ){ 
         $this->erro_sql = " Campo Grupo não informado.";
         $this->erro_campo = "la67_grupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($la67_codigo!=null){
       $sql .= " la67_codigo = $this->la67_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->la67_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1012004,'$this->la67_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la67_codigo"]) || $this->la67_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010667,1012004,'".AddSlashes(pg_result($resaco,$conresaco,'la67_codigo'))."','$this->la67_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la67_laboratorio"]) || $this->la67_laboratorio != "")
             $resac = db_query("insert into db_acount values($acount,1010667,1012005,'".AddSlashes(pg_result($resaco,$conresaco,'la67_laboratorio'))."','$this->la67_laboratorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la67_grupo"]) || $this->la67_grupo != "")
             $resac = db_query("insert into db_acount values($acount,1010667,1012119,'".AddSlashes(pg_result($resaco,$conresaco,'la67_grupo'))."','$this->la67_grupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculo de Grupo de Exames com Laboratório não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la67_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Vinculo de Grupo de Exames com Laboratório não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la67_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->la67_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($la67_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($la67_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1012004,'$la67_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010667,1012004,'','".AddSlashes(pg_result($resaco,$iresaco,'la67_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010667,1012005,'','".AddSlashes(pg_result($resaco,$iresaco,'la67_laboratorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010667,1012119,'','".AddSlashes(pg_result($resaco,$iresaco,'la67_grupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from lab_labgrupoexame
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($la67_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " la67_codigo = $la67_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculo de Grupo de Exames com Laboratório não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la67_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Vinculo de Grupo de Exames com Laboratório não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la67_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$la67_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:lab_labgrupoexame";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($la67_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from lab_labgrupoexame ";
     $sql .= "      inner join lab_laboratorio  on  lab_laboratorio.la02_i_codigo = lab_labgrupoexame.la67_laboratorio";
     $sql .= "      inner join lab_grupo  on  lab_grupo.la66_codigo = lab_labgrupoexame.la67_grupo";
     $sql .= "      left  join sau_turnoatend  on  sau_turnoatend.sd43_cod_turnat = lab_laboratorio.la02_i_turnoatend";
     $sql .= "      inner join lab_labdepart  on  lab_laboratorio.la02_i_codigo = lab_labdepart.la03_i_laboratorio";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($la67_codigo)) {
         $sql2 .= " where lab_labgrupoexame.la67_codigo = $la67_codigo "; 
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

    public function sql_query_file($la67_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from lab_labgrupoexame ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($la67_codigo)){
         $sql2 .= " where lab_labgrupoexame.la67_codigo = $la67_codigo "; 
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
