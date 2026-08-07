<?php

class cl_escolacensolinguaindig
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
    public $ed144_i_escola = 0; 
    public $ed144_i_linguaindigena1 = 0; 
    public $ed144_i_linguaindigena2 = 0; 
    public $ed144_i_linguaindigena3 = 0; 
    public $ed144_i_codigo = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 ed144_i_escola = int4 = Escola 
                 ed144_i_linguaindigena1 = int4 = Lingua Indigena 1 
                 ed144_i_linguaindigena2 = int4 = Lingua Indigena 2 
                 ed144_i_linguaindigena3 = int4 = Lingua Indigena 3 
                 ed144_i_codigo = int4 = Código 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("escolacensolinguaindig"); 
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
       $this->ed144_i_escola = ($this->ed144_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed144_i_escola"]:$this->ed144_i_escola);
       $this->ed144_i_linguaindigena1 = ($this->ed144_i_linguaindigena1 == ""?@$GLOBALS["HTTP_POST_VARS"]["ed144_i_linguaindigena1"]:$this->ed144_i_linguaindigena1);
       $this->ed144_i_linguaindigena2 = ($this->ed144_i_linguaindigena2 == ""?@$GLOBALS["HTTP_POST_VARS"]["ed144_i_linguaindigena2"]:$this->ed144_i_linguaindigena2);
       $this->ed144_i_linguaindigena3 = ($this->ed144_i_linguaindigena3 == ""?@$GLOBALS["HTTP_POST_VARS"]["ed144_i_linguaindigena3"]:$this->ed144_i_linguaindigena3);
       $this->ed144_i_codigo = ($this->ed144_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed144_i_codigo"]:$this->ed144_i_codigo);
     }else{
       $this->ed144_i_codigo = ($this->ed144_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed144_i_codigo"]:$this->ed144_i_codigo);
     }
   }

    public function incluir($ed144_i_codigo)
    {
      $this->atualizacampos();
     if($this->ed144_i_escola == null ){ 
       $this->erro_sql = " Campo Escola não informado.";
       $this->erro_campo = "ed144_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed144_i_linguaindigena1 == null ){ 
       $this->ed144_i_linguaindigena1 = "0";
     }
     if($this->ed144_i_linguaindigena2 == null ){ 
       $this->ed144_i_linguaindigena2 = "0";
     }
     if($this->ed144_i_linguaindigena3 == null ){ 
       $this->ed144_i_linguaindigena3 = "0";
     }
     if($ed144_i_codigo == "" || $ed144_i_codigo == null ){
       $result = db_query("select nextval('escolacensolinguaindig_ed144_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: escolacensolinguaindig_ed144_i_codigo_seq do campo: ed144_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed144_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from escolacensolinguaindig_ed144_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed144_i_codigo)){
         $this->erro_sql = " Campo ed144_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed144_i_codigo = $ed144_i_codigo; 
       }
     }
     if(($this->ed144_i_codigo == null) || ($this->ed144_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed144_i_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into escolacensolinguaindig(
                                       ed144_i_escola 
                                      ,ed144_i_linguaindigena1 
                                      ,ed144_i_linguaindigena2 
                                      ,ed144_i_linguaindigena3 
                                      ,ed144_i_codigo 
                       )
                values (
                                $this->ed144_i_escola 
                               ,$this->ed144_i_linguaindigena1 
                               ,$this->ed144_i_linguaindigena2 
                               ,$this->ed144_i_linguaindigena3 
                               ,$this->ed144_i_codigo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Escola Censo Lingua Indigena ($this->ed144_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Escola Censo Lingua Indigena já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Escola Censo Lingua Indigena ($this->ed144_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed144_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed144_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1010424,'$this->ed144_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010440,1010428,'','".AddSlashes(pg_result($resaco,0,'ed144_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010440,1010425,'','".AddSlashes(pg_result($resaco,0,'ed144_i_linguaindigena1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010440,1010426,'','".AddSlashes(pg_result($resaco,0,'ed144_i_linguaindigena2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010440,1010427,'','".AddSlashes(pg_result($resaco,0,'ed144_i_linguaindigena3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010440,1010424,'','".AddSlashes(pg_result($resaco,0,'ed144_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($ed144_i_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update escolacensolinguaindig set ";
     $virgula = "";
     if(trim($this->ed144_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed144_i_escola"])){ 
       $sql  .= $virgula." ed144_i_escola = $this->ed144_i_escola ";
       $virgula = ",";
       if(trim($this->ed144_i_escola) == null ){ 
         $this->erro_sql = " Campo Escola não informado.";
         $this->erro_campo = "ed144_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed144_i_linguaindigena1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed144_i_linguaindigena1"])){ 
        if(trim($this->ed144_i_linguaindigena1)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed144_i_linguaindigena1"])){ 
           $this->ed144_i_linguaindigena1 = "0" ; 
        } 
       $sql  .= $virgula." ed144_i_linguaindigena1 = $this->ed144_i_linguaindigena1 ";
       $virgula = ",";
     }
     if(trim($this->ed144_i_linguaindigena2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed144_i_linguaindigena2"])){ 
        if(trim($this->ed144_i_linguaindigena2)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed144_i_linguaindigena2"])){ 
           $this->ed144_i_linguaindigena2 = "0" ; 
        } 
       $sql  .= $virgula." ed144_i_linguaindigena2 = $this->ed144_i_linguaindigena2 ";
       $virgula = ",";
     }
     if(trim($this->ed144_i_linguaindigena3)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed144_i_linguaindigena3"])){ 
        if(trim($this->ed144_i_linguaindigena3)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed144_i_linguaindigena3"])){ 
           $this->ed144_i_linguaindigena3 = "0" ; 
        } 
       $sql  .= $virgula." ed144_i_linguaindigena3 = $this->ed144_i_linguaindigena3 ";
       $virgula = ",";
     }
     if(trim($this->ed144_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed144_i_codigo"])){ 
       $sql  .= $virgula." ed144_i_codigo = $this->ed144_i_codigo ";
       $virgula = ",";
       if(trim($this->ed144_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed144_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed144_i_codigo!=null){
       $sql .= " ed144_i_codigo = $this->ed144_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed144_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1010424,'$this->ed144_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed144_i_escola"]) || $this->ed144_i_escola != "")
             $resac = db_query("insert into db_acount values($acount,1010440,1010428,'".AddSlashes(pg_result($resaco,$conresaco,'ed144_i_escola'))."','$this->ed144_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed144_i_linguaindigena1"]) || $this->ed144_i_linguaindigena1 != "")
             $resac = db_query("insert into db_acount values($acount,1010440,1010425,'".AddSlashes(pg_result($resaco,$conresaco,'ed144_i_linguaindigena1'))."','$this->ed144_i_linguaindigena1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed144_i_linguaindigena2"]) || $this->ed144_i_linguaindigena2 != "")
             $resac = db_query("insert into db_acount values($acount,1010440,1010426,'".AddSlashes(pg_result($resaco,$conresaco,'ed144_i_linguaindigena2'))."','$this->ed144_i_linguaindigena2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed144_i_linguaindigena3"]) || $this->ed144_i_linguaindigena3 != "")
             $resac = db_query("insert into db_acount values($acount,1010440,1010427,'".AddSlashes(pg_result($resaco,$conresaco,'ed144_i_linguaindigena3'))."','$this->ed144_i_linguaindigena3',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed144_i_codigo"]) || $this->ed144_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010440,1010424,'".AddSlashes(pg_result($resaco,$conresaco,'ed144_i_codigo'))."','$this->ed144_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Escola Censo Lingua Indigena não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed144_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Escola Censo Lingua Indigena não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed144_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed144_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($ed144_i_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed144_i_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1010424,'$ed144_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010440,1010428,'','".AddSlashes(pg_result($resaco,$iresaco,'ed144_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010440,1010425,'','".AddSlashes(pg_result($resaco,$iresaco,'ed144_i_linguaindigena1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010440,1010426,'','".AddSlashes(pg_result($resaco,$iresaco,'ed144_i_linguaindigena2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010440,1010427,'','".AddSlashes(pg_result($resaco,$iresaco,'ed144_i_linguaindigena3'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010440,1010424,'','".AddSlashes(pg_result($resaco,$iresaco,'ed144_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from escolacensolinguaindig
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed144_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed144_i_codigo = $ed144_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Escola Censo Lingua Indigena não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed144_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Escola Censo Lingua Indigena não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed144_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ed144_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:escolacensolinguaindig";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($ed144_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from escolacensolinguaindig ";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = escolacensolinguaindig.ed144_i_escola";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf";
     $sql .= "      inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic";
     $sql .= "      inner join censodistrito  on  censodistrito.ed262_i_codigo = escola.ed18_i_censodistrito";
     $sql .= "      left  join censolinguaindig  on  censolinguaindig.ed264_i_codigo = escola.ed18_i_linguaindigena";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed144_i_codigo)) {
         $sql2 .= " where escolacensolinguaindig.ed144_i_codigo = $ed144_i_codigo "; 
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

    public function sql_query_file($ed144_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from escolacensolinguaindig ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed144_i_codigo)){
         $sql2 .= " where escolacensolinguaindig.ed144_i_codigo = $ed144_i_codigo "; 
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

    public function sql_query_lingua_nome($ed144_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from escolacensolinguaindig ";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = escolacensolinguaindig.ed144_i_escola";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf";
     $sql .= "      inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic";
     $sql .= "      inner join censodistrito  on  censodistrito.ed262_i_codigo = escola.ed18_i_censodistrito";
     $sql .= "      left  join censolinguaindig  on  censolinguaindig.ed264_i_codigo = escola.ed18_i_linguaindigena";
     $sql .= "      left join censolinguaindig l1 on l1.ed264_i_codigo = ed144_i_linguaindigena1 ";
     $sql .= "      left join censolinguaindig l2 on l2.ed264_i_codigo = ed144_i_linguaindigena2 ";
     $sql .= "      left join censolinguaindig l3 on l3.ed264_i_codigo = ed144_i_linguaindigena3 ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed144_i_codigo)) {
         $sql2 .= " where escolacensolinguaindig.ed144_i_codigo = $ed144_i_codigo "; 
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
