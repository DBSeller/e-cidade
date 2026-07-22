<?php

class cl_diarioaluno
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
    public $ed161_codigo = 0; 
    public $ed161_aluno = 0; 
    public $ed161_turma = 0; 
    public $ed161_serie = 0; 
    public $ed161_encerrado = 'f'; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 ed161_codigo = int4 = Código 
                 ed161_aluno = int4 = Aluno 
                 ed161_turma = int4 = Turma 
                 ed161_serie = int4 = Etapa 
                 ed161_encerrado = bool = Encerrado 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("diarioaluno"); 
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
       $this->ed161_codigo = ($this->ed161_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed161_codigo"]:$this->ed161_codigo);
       $this->ed161_aluno = ($this->ed161_aluno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed161_aluno"]:$this->ed161_aluno);
       $this->ed161_turma = ($this->ed161_turma == ""?@$GLOBALS["HTTP_POST_VARS"]["ed161_turma"]:$this->ed161_turma);
       $this->ed161_serie = ($this->ed161_serie == ""?@$GLOBALS["HTTP_POST_VARS"]["ed161_serie"]:$this->ed161_serie);
       $this->ed161_encerrado = ($this->ed161_encerrado == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed161_encerrado"]:$this->ed161_encerrado);
     }else{
       $this->ed161_codigo = ($this->ed161_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed161_codigo"]:$this->ed161_codigo);
     }
   }

    public function incluir($ed161_codigo)
    {
      $this->atualizacampos();
     if($this->ed161_aluno == null ){ 
       $this->erro_sql = " Campo Aluno não informado.";
       $this->erro_campo = "ed161_aluno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed161_turma == null ){ 
       $this->erro_sql = " Campo Turma não informado.";
       $this->erro_campo = "ed161_turma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed161_serie == null ){ 
       $this->erro_sql = " Campo Etapa não informado.";
       $this->erro_campo = "ed161_serie";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed161_encerrado == null ){ 
       $this->erro_sql = " Campo Encerrado não informado.";
       $this->erro_campo = "ed161_encerrado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed161_codigo == "" || $ed161_codigo == null ){
       $result = db_query("select nextval('diarioaluno_ed161_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: diarioaluno_ed161_codigo_seq do campo: ed161_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed161_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from diarioaluno_ed161_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed161_codigo)){
         $this->erro_sql = " Campo ed161_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed161_codigo = $ed161_codigo; 
       }
     }
     if(($this->ed161_codigo == null) || ($this->ed161_codigo == "") ){ 
       $this->erro_sql = " Campo ed161_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into diarioaluno(
                                       ed161_codigo 
                                      ,ed161_aluno 
                                      ,ed161_turma 
                                      ,ed161_serie 
                                      ,ed161_encerrado 
                       )
                values (
                                $this->ed161_codigo 
                               ,$this->ed161_aluno 
                               ,$this->ed161_turma 
                               ,$this->ed161_serie 
                               ,'$this->ed161_encerrado' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Diário Aluno ($this->ed161_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Diário Aluno já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Diário Aluno ($this->ed161_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed161_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed161_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1011151,'$this->ed161_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010543,1011151,'','".AddSlashes(pg_result($resaco,0,'ed161_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010543,1011152,'','".AddSlashes(pg_result($resaco,0,'ed161_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010543,1011153,'','".AddSlashes(pg_result($resaco,0,'ed161_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010543,1011154,'','".AddSlashes(pg_result($resaco,0,'ed161_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010543,1011155,'','".AddSlashes(pg_result($resaco,0,'ed161_encerrado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($ed161_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update diarioaluno set ";
     $virgula = "";
     if(trim($this->ed161_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed161_codigo"])){ 
       $sql  .= $virgula." ed161_codigo = $this->ed161_codigo ";
       $virgula = ",";
       if(trim($this->ed161_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed161_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed161_aluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed161_aluno"])){ 
       $sql  .= $virgula." ed161_aluno = $this->ed161_aluno ";
       $virgula = ",";
       if(trim($this->ed161_aluno) == null ){ 
         $this->erro_sql = " Campo Aluno não informado.";
         $this->erro_campo = "ed161_aluno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed161_turma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed161_turma"])){ 
       $sql  .= $virgula." ed161_turma = $this->ed161_turma ";
       $virgula = ",";
       if(trim($this->ed161_turma) == null ){ 
         $this->erro_sql = " Campo Turma não informado.";
         $this->erro_campo = "ed161_turma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed161_serie)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed161_serie"])){ 
       $sql  .= $virgula." ed161_serie = $this->ed161_serie ";
       $virgula = ",";
       if(trim($this->ed161_serie) == null ){ 
         $this->erro_sql = " Campo Etapa não informado.";
         $this->erro_campo = "ed161_serie";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed161_encerrado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed161_encerrado"])){ 
       $sql  .= $virgula." ed161_encerrado = '$this->ed161_encerrado' ";
       $virgula = ",";
       if(trim($this->ed161_encerrado) == null ){ 
         $this->erro_sql = " Campo Encerrado não informado.";
         $this->erro_campo = "ed161_encerrado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed161_codigo!=null){
       $sql .= " ed161_codigo = $this->ed161_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed161_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1011151,'$this->ed161_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed161_codigo"]) || $this->ed161_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010543,1011151,'".AddSlashes(pg_result($resaco,$conresaco,'ed161_codigo'))."','$this->ed161_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed161_aluno"]) || $this->ed161_aluno != "")
             $resac = db_query("insert into db_acount values($acount,1010543,1011152,'".AddSlashes(pg_result($resaco,$conresaco,'ed161_aluno'))."','$this->ed161_aluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed161_turma"]) || $this->ed161_turma != "")
             $resac = db_query("insert into db_acount values($acount,1010543,1011153,'".AddSlashes(pg_result($resaco,$conresaco,'ed161_turma'))."','$this->ed161_turma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed161_serie"]) || $this->ed161_serie != "")
             $resac = db_query("insert into db_acount values($acount,1010543,1011154,'".AddSlashes(pg_result($resaco,$conresaco,'ed161_serie'))."','$this->ed161_serie',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed161_encerrado"]) || $this->ed161_encerrado != "")
             $resac = db_query("insert into db_acount values($acount,1010543,1011155,'".AddSlashes(pg_result($resaco,$conresaco,'ed161_encerrado'))."','$this->ed161_encerrado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Diário Aluno não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed161_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Diário Aluno não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed161_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed161_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($ed161_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed161_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1011151,'$ed161_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010543,1011151,'','".AddSlashes(pg_result($resaco,$iresaco,'ed161_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010543,1011152,'','".AddSlashes(pg_result($resaco,$iresaco,'ed161_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010543,1011153,'','".AddSlashes(pg_result($resaco,$iresaco,'ed161_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010543,1011154,'','".AddSlashes(pg_result($resaco,$iresaco,'ed161_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010543,1011155,'','".AddSlashes(pg_result($resaco,$iresaco,'ed161_encerrado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from diarioaluno
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed161_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed161_codigo = $ed161_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Diário Aluno não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed161_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Diário Aluno não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed161_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ed161_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:diarioaluno";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($ed161_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from diarioaluno ";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = diarioaluno.ed161_serie";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = diarioaluno.ed161_aluno";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = diarioaluno.ed161_turma";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = serie.ed11_i_ensino";
     $sql .= "      left  join bnccetapas  on  bnccetapas.ed152_sequencial = serie.ed11_bnccetapa";
     $sql .= "      inner join pais  on  pais.ed228_i_codigo = aluno.ed47_i_pais and  pais.ed228_i_codigo = aluno.ed47_paisresidencia";
     $sql .= "      left  join censouf  on  censouf.ed260_i_codigo = aluno.ed47_i_censoufident and  censouf.ed260_i_codigo = aluno.ed47_i_censoufcert and  censouf.ed260_i_codigo = aluno.ed47_i_censoufnat and  censouf.ed260_i_codigo = aluno.ed47_i_censoufend";
     $sql .= "      left  join censomunic  on  censomunic.ed261_i_codigo = aluno.ed47_i_censomunicnat and  censomunic.ed261_i_codigo = aluno.ed47_i_censomunicend and  censomunic.ed261_i_codigo = aluno.ed47_i_censomuniccert";
     $sql .= "      left  join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = aluno.ed47_i_censoorgemissrg";
     $sql .= "      left  join tiposanguineo  on  tiposanguineo.sd100_sequencial = aluno.ed47_tiposanguineo";
     $sql .= "      left  join aluno  as a on   a.ed47_i_codigo = aluno.ed47_i_censocartorio";
     $sql .= "      left  join censocursoprofiss  on  censocursoprofiss.ed247_i_codigo = turma.ed57_i_censocursoprofiss";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = turma.ed57_i_escola";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = turma.ed57_i_turno";
     $sql .= "      inner join sala  on  sala.ed16_i_codigo = turma.ed57_i_sala";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
     $sql .= "      inner join base  on  base.ed31_i_codigo = turma.ed57_i_base";
     $sql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = turma.ed57_i_procedimento";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed161_codigo)) {
         $sql2 .= " where diarioaluno.ed161_codigo = $ed161_codigo "; 
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

    public function sql_query_file($ed161_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from diarioaluno ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed161_codigo)){
         $sql2 .= " where diarioaluno.ed161_codigo = $ed161_codigo "; 
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
