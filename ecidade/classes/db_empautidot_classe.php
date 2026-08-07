<?php

class cl_empautidot
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
    public $e56_autori = 0; 
    public $e56_anousu = 0; 
    public $e56_coddot = 0; 
    public $e56_orctiporec = 0; 
    public $e56_planoorcamentariolinhapacto = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 e56_autori = int4 = Autorização 
                 e56_anousu = int4 = Ano 
                 e56_coddot = int4 = Dotação 
                 e56_orctiporec = int4 = Contrapartida 
                 e56_planoorcamentariolinhapacto = int4 = LInha de Pacto 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("empautidot"); 
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
       $this->e56_autori = ($this->e56_autori == ""?@$GLOBALS["HTTP_POST_VARS"]["e56_autori"]:$this->e56_autori);
       $this->e56_anousu = ($this->e56_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["e56_anousu"]:$this->e56_anousu);
       $this->e56_coddot = ($this->e56_coddot == ""?@$GLOBALS["HTTP_POST_VARS"]["e56_coddot"]:$this->e56_coddot);
       $this->e56_orctiporec = ($this->e56_orctiporec == ""?@$GLOBALS["HTTP_POST_VARS"]["e56_orctiporec"]:$this->e56_orctiporec);
       $this->e56_planoorcamentariolinhapacto = ($this->e56_planoorcamentariolinhapacto == ""?@$GLOBALS["HTTP_POST_VARS"]["e56_planoorcamentariolinhapacto"]:$this->e56_planoorcamentariolinhapacto);
     }else{
       $this->e56_autori = ($this->e56_autori == ""?@$GLOBALS["HTTP_POST_VARS"]["e56_autori"]:$this->e56_autori);
     }
   }

    public function incluir($e56_autori)
    {
      $this->atualizacampos();
     if($this->e56_anousu == null ){ 
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "e56_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e56_coddot == null ){ 
       $this->erro_sql = " Campo Dotação não informado.";
       $this->erro_campo = "e56_coddot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e56_orctiporec == null ){ 
       $this->e56_orctiporec = "null";
     }
     if($this->e56_planoorcamentariolinhapacto == null ){ 
       $this->e56_planoorcamentariolinhapacto = "null";
     }
       $this->e56_autori = $e56_autori; 
     if(($this->e56_autori == null) || ($this->e56_autori == "") ){ 
       $this->erro_sql = " Campo e56_autori não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empautidot(
                                       e56_autori 
                                      ,e56_anousu 
                                      ,e56_coddot 
                                      ,e56_orctiporec 
                                      ,e56_planoorcamentariolinhapacto 
                       )
                values (
                                $this->e56_autori 
                               ,$this->e56_anousu 
                               ,$this->e56_coddot 
                               ,$this->e56_orctiporec 
                               ,$this->e56_planoorcamentariolinhapacto 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dotação de empenho ($this->e56_autori) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dotação de empenho já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dotação de empenho ($this->e56_autori) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->e56_autori;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e56_autori  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5474,'$this->e56_autori','I')");
         $resac = db_query("insert into db_acount values($acount,812,5474,'','".AddSlashes(pg_result($resaco,0,'e56_autori'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,812,5475,'','".AddSlashes(pg_result($resaco,0,'e56_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,812,5476,'','".AddSlashes(pg_result($resaco,0,'e56_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,812,11915,'','".AddSlashes(pg_result($resaco,0,'e56_orctiporec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,812,1010136,'','".AddSlashes(pg_result($resaco,0,'e56_planoorcamentariolinhapacto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($e56_autori=null)
    {
      $this->atualizacampos();
     $sql = " update empautidot set ";
     $virgula = "";
     if(trim($this->e56_autori)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e56_autori"])){ 
       $sql  .= $virgula." e56_autori = $this->e56_autori ";
       $virgula = ",";
       if(trim($this->e56_autori) == null ){ 
         $this->erro_sql = " Campo Autorização não informado.";
         $this->erro_campo = "e56_autori";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e56_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e56_anousu"])){ 
       $sql  .= $virgula." e56_anousu = $this->e56_anousu ";
       $virgula = ",";
       if(trim($this->e56_anousu) == null ){ 
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "e56_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e56_coddot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e56_coddot"])){ 
       $sql  .= $virgula." e56_coddot = $this->e56_coddot ";
       $virgula = ",";
       if(trim($this->e56_coddot) == null ){ 
         $this->erro_sql = " Campo Dotação não informado.";
         $this->erro_campo = "e56_coddot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e56_orctiporec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e56_orctiporec"])){ 
        if(trim($this->e56_orctiporec)=="" && isset($GLOBALS["HTTP_POST_VARS"]["e56_orctiporec"])){ 
           $this->e56_orctiporec = "null" ;
        } 
       $sql  .= $virgula." e56_orctiporec = $this->e56_orctiporec ";
       $virgula = ",";
     }
     if(trim($this->e56_planoorcamentariolinhapacto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e56_planoorcamentariolinhapacto"])){ 
        if(trim($this->e56_planoorcamentariolinhapacto)=="" && isset($GLOBALS["HTTP_POST_VARS"]["e56_planoorcamentariolinhapacto"])){ 
           $this->e56_planoorcamentariolinhapacto = "null" ;
        } 
       $sql  .= $virgula." e56_planoorcamentariolinhapacto = $this->e56_planoorcamentariolinhapacto ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($e56_autori!=null){
       $sql .= " e56_autori = $this->e56_autori";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->e56_autori));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,5474,'$this->e56_autori','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e56_autori"]) || $this->e56_autori != "")
             $resac = db_query("insert into db_acount values($acount,812,5474,'".AddSlashes(pg_result($resaco,$conresaco,'e56_autori'))."','$this->e56_autori',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e56_anousu"]) || $this->e56_anousu != "")
             $resac = db_query("insert into db_acount values($acount,812,5475,'".AddSlashes(pg_result($resaco,$conresaco,'e56_anousu'))."','$this->e56_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e56_coddot"]) || $this->e56_coddot != "")
             $resac = db_query("insert into db_acount values($acount,812,5476,'".AddSlashes(pg_result($resaco,$conresaco,'e56_coddot'))."','$this->e56_coddot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e56_orctiporec"]) || $this->e56_orctiporec != "")
             $resac = db_query("insert into db_acount values($acount,812,11915,'".AddSlashes(pg_result($resaco,$conresaco,'e56_orctiporec'))."','$this->e56_orctiporec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["e56_planoorcamentariolinhapacto"]) || $this->e56_planoorcamentariolinhapacto != "")
             $resac = db_query("insert into db_acount values($acount,812,1010136,'".AddSlashes(pg_result($resaco,$conresaco,'e56_planoorcamentariolinhapacto'))."','$this->e56_planoorcamentariolinhapacto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dotação de empenho não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e56_autori;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Dotação de empenho não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e56_autori;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->e56_autori;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($e56_autori=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($e56_autori));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,5474,'$e56_autori','E')");
           $resac  = db_query("insert into db_acount values($acount,812,5474,'','".AddSlashes(pg_result($resaco,$iresaco,'e56_autori'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,812,5475,'','".AddSlashes(pg_result($resaco,$iresaco,'e56_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,812,5476,'','".AddSlashes(pg_result($resaco,$iresaco,'e56_coddot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,812,11915,'','".AddSlashes(pg_result($resaco,$iresaco,'e56_orctiporec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,812,1010136,'','".AddSlashes(pg_result($resaco,$iresaco,'e56_planoorcamentariolinhapacto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from empautidot
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($e56_autori)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " e56_autori = $e56_autori ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dotação de empenho não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e56_autori;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Dotação de empenho não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e56_autori;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$e56_autori;
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
        $this->erro_sql   = "Record Vazio na Tabela:empautidot";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $e56_autori=null,$campos="*",$ordem=null,$dbwhere=""){
        $sql = "select ";
        if($campos != "*" ){
            $campos_sql = explode("#",$campos);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }else{
            $sql .= $campos;
        }
        $sql .= " from empautidot ";
        $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empautidot.e56_anousu and  orcdotacao.o58_coddot = empautidot.e56_coddot";
        $sql .= "      inner join empautoriza  on  empautoriza.e54_autori = empautidot.e56_autori";
        $sql .= "      inner join db_config  on  db_config.codigo = orcdotacao.o58_instit";
        $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
        $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
        $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
        $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa = orcdotacao.o58_programa";
        $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele and  orcelemento.o56_anousu = orcdotacao.o58_anousu";
        $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ = orcdotacao.o58_projativ";
        $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao = orcdotacao.o58_orgao";
        $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
        $sql .= "      inner join db_config  as a on   a.codigo = orcdotacao.o58_instit";
        $sql .= "      inner join orctiporec  as b on   b.o15_codigo = orcdotacao.o58_codigo";
        $sql .= "      inner join orcfuncao  as c on   c.o52_funcao = orcdotacao.o58_funcao";
        $sql .= "      inner join orcsubfuncao  as d on   d.o53_subfuncao = orcdotacao.o58_subfuncao";
        $sql .= "      inner join orcprograma  as d on   d.o54_anousu = orcdotacao.o58_anousu and   d.o54_programa = orcdotacao.o58_programa";
        $sql .= "      inner join orcelemento  as d on   d.o56_codele = orcdotacao.o58_codele and   d.o56_anousu = orcdotacao.o58_anousu";
        $sql .= "      inner join orcprojativ  as d on   d.o55_anousu = orcdotacao.o58_anousu and   d.o55_projativ = orcdotacao.o58_projativ";
        $sql .= "      inner join orcorgao  as d on   d.o40_anousu = orcdotacao.o58_anousu and   d.o40_orgao = orcdotacao.o58_orgao";
        $sql .= "      inner join orcunidade  as d on   d.o41_anousu = orcdotacao.o58_anousu and   d.o41_orgao = orcdotacao.o58_orgao and   d.o41_unidade = orcdotacao.o58_unidade";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = empautoriza.e54_numcgm";
        $sql .= "      inner join db_config  as d on   d.codigo = empautoriza.e54_instit";
        $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empautoriza.e54_login";
        $sql .= "      inner join db_depart  on  db_depart.coddepto = empautoriza.e54_depto";
        $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empautoriza.e54_codcom";
        $sql2 = "";
        if($dbwhere==""){
            if($e56_autori!=null ){
                $sql2 .= " where empautidot.e56_autori = $e56_autori ";
            }
        }else if($dbwhere != ""){
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if($ordem != null ){
            $sql .= " order by ";
            $campos_sql = explode("#",$ordem);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }
    public function sql_query_dotacao ($e56_autori=null,$campos="*",$ordem=null,$dbwhere=""){
        $sql = "select ";
        if($campos != "*" ){
            $campos_sql = explode("#",$campos);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }else{
            $sql .= $campos;
        }
        $sql .= " from empautidot ";
        $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empautidot.e56_anousu and  orcdotacao.o58_coddot = empautidot.e56_coddot";
        $sql .= "      inner join empautoriza  on  empautoriza.e54_autori = empautidot.e56_autori";
        $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele 
                                           and orcelemento.o56_anousu = orcdotacao.o58_anousu";
        $sql .= "  left join orctiporec on o15_codigo = e56_orctiporec";
        $sql2 = "";
        if($dbwhere==""){
            if($e56_autori!=null ){
                $sql2 .= " where empautidot.e56_autori = $e56_autori ";
            }
        }else if($dbwhere != ""){
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if($ordem != null ){
            $sql .= " order by ";
            $campos_sql = explode("#",$ordem);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }
    public function sql_query_file ( $e56_autori=null,$campos="*",$ordem=null,$dbwhere=""){
        $sql = "select ";
        if($campos != "*" ){
            $campos_sql = explode("#",$campos);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }else{
            $sql .= $campos;
        }
        $sql .= " from empautidot ";
        $sql2 = "";
        if($dbwhere==""){
            if($e56_autori!=null ){
                $sql2 .= " where empautidot.e56_autori = $e56_autori ";
            }
        }else if($dbwhere != ""){
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if($ordem != null ){
            $sql .= " order by ";
            $campos_sql = explode("#",$ordem);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }

    public function sql_query_linhapacto( $e56_autori=null,$campos="*",$ordem=null,$dbwhere=""){
        $sql = "select ";
        $sql .= $campos;

        $sql .= " from empautidot ";
        $sql .= " left join planoorcamentariolinhapacto on e56_planoorcamentariolinhapacto = o156_sequencial ";
        $sql2 = "";
        if($dbwhere==""){
            if($e56_autori!=null ){
                $sql2 .= " where empautidot.e56_autori = $e56_autori ";
            }
        }else if($dbwhere != ""){
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if($ordem != null ){
            $sql .= " order by ";
            $campos_sql = explode("#",$ordem);
            $virgula = "";
            for($i=0;$i<sizeof($campos_sql);$i++){
                $sql .= $virgula.$campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }
}
