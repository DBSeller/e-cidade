<?php

class cl_censoregiao
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
    public $ed174_codigo = 0; 
    public $ed174_nome = null; 
    public $ed174_censomunic = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 ed174_codigo = int4 = Codigo 
                 ed174_nome = varchar(50) = Nome da Região 
                 ed174_censomunic = int4 = Municipio 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("censoregiao"); 
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
       $this->ed174_codigo = ($this->ed174_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed174_codigo"]:$this->ed174_codigo);
       $this->ed174_nome = ($this->ed174_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["ed174_nome"]:$this->ed174_nome);
       $this->ed174_censomunic = ($this->ed174_censomunic == ""?@$GLOBALS["HTTP_POST_VARS"]["ed174_censomunic"]:$this->ed174_censomunic);
     }else{
       $this->ed174_codigo = ($this->ed174_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed174_codigo"]:$this->ed174_codigo);
     }
   }

    public function incluir($ed174_codigo)
    {
      $this->atualizacampos();
     if($this->ed174_nome == null ){ 
       $this->erro_sql = " Campo Nome da Região não informado.";
       $this->erro_campo = "ed174_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed174_censomunic == null ){ 
       $this->erro_sql = " Campo Municipio não informado.";
       $this->erro_campo = "ed174_censomunic";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->ed174_codigo = $ed174_codigo; 
     if(($this->ed174_codigo == null) || ($this->ed174_codigo == "") ){ 
       $this->erro_sql = " Campo ed174_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into censoregiao(
                                       ed174_codigo 
                                      ,ed174_nome 
                                      ,ed174_censomunic 
                       )
                values (
                                $this->ed174_codigo 
                               ,'$this->ed174_nome' 
                               ,$this->ed174_censomunic 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Regiões Administrativas ($this->ed174_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Regiões Administrativas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Regiões Administrativas ($this->ed174_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed174_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed174_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1013159,'$this->ed174_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010784,1013159,'','".AddSlashes(pg_result($resaco,0,'ed174_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010784,1013160,'','".AddSlashes(pg_result($resaco,0,'ed174_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010784,1013161,'','".AddSlashes(pg_result($resaco,0,'ed174_censomunic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($ed174_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update censoregiao set ";
     $virgula = "";
     if(trim($this->ed174_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed174_codigo"])){ 
       $sql  .= $virgula." ed174_codigo = $this->ed174_codigo ";
       $virgula = ",";
       if(trim($this->ed174_codigo) == null ){ 
         $this->erro_sql = " Campo Codigo não informado.";
         $this->erro_campo = "ed174_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed174_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed174_nome"])){ 
       $sql  .= $virgula." ed174_nome = '$this->ed174_nome' ";
       $virgula = ",";
       if(trim($this->ed174_nome) == null ){ 
         $this->erro_sql = " Campo Nome da Região não informado.";
         $this->erro_campo = "ed174_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed174_censomunic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed174_censomunic"])){ 
       $sql  .= $virgula." ed174_censomunic = $this->ed174_censomunic ";
       $virgula = ",";
       if(trim($this->ed174_censomunic) == null ){ 
         $this->erro_sql = " Campo Municipio não informado.";
         $this->erro_campo = "ed174_censomunic";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed174_codigo!=null){
       $sql .= " ed174_codigo = $this->ed174_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed174_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1013159,'$this->ed174_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed174_codigo"]) || $this->ed174_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010784,1013159,'".AddSlashes(pg_result($resaco,$conresaco,'ed174_codigo'))."','$this->ed174_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed174_nome"]) || $this->ed174_nome != "")
             $resac = db_query("insert into db_acount values($acount,1010784,1013160,'".AddSlashes(pg_result($resaco,$conresaco,'ed174_nome'))."','$this->ed174_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed174_censomunic"]) || $this->ed174_censomunic != "")
             $resac = db_query("insert into db_acount values($acount,1010784,1013161,'".AddSlashes(pg_result($resaco,$conresaco,'ed174_censomunic'))."','$this->ed174_censomunic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Regiões Administrativas não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed174_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Regiões Administrativas não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed174_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed174_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($ed174_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed174_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1013159,'$ed174_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010784,1013159,'','".AddSlashes(pg_result($resaco,$iresaco,'ed174_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010784,1013160,'','".AddSlashes(pg_result($resaco,$iresaco,'ed174_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010784,1013161,'','".AddSlashes(pg_result($resaco,$iresaco,'ed174_censomunic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from censoregiao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed174_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed174_codigo = $ed174_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Regiões Administrativas não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed174_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Regiões Administrativas não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed174_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ed174_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:censoregiao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($ed174_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from censoregiao ";
     $sql .= "      inner join censomunic  on  censomunic.ed261_i_codigo = censoregiao.ed174_censomunic";
     $sql .= "      inner join censouf  on  censouf.ed260_i_codigo = censomunic.ed261_i_censouf";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed174_codigo)) {
         $sql2 .= " where censoregiao.ed174_codigo = $ed174_codigo "; 
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

    public function sql_query_file($ed174_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from censoregiao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed174_codigo)){
         $sql2 .= " where censoregiao.ed174_codigo = $ed174_codigo "; 
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
