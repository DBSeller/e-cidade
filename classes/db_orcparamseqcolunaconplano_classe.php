<?php

class cl_orcparamseqcolunaconplano
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
    public $o158_orcparamseqcoluna = 0; 
    public $o158_conplano = 0; 
    public $o158_exclusao = 'f'; 
    public $o158_sequencial = 0; 
    public $o158_ano = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 o158_orcparamseqcoluna = int8 = Coluna 
                 o158_conplano = int8 = Conta 
                 o158_exclusao = bool = Exclusão 
                 o158_sequencial = int8 = Sequencial 
                 o158_ano = int4 = Ano 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("orcparamseqcolunaconplano"); 
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
       $this->o158_orcparamseqcoluna = ($this->o158_orcparamseqcoluna == ""?@$GLOBALS["HTTP_POST_VARS"]["o158_orcparamseqcoluna"]:$this->o158_orcparamseqcoluna);
       $this->o158_conplano = ($this->o158_conplano == ""?@$GLOBALS["HTTP_POST_VARS"]["o158_conplano"]:$this->o158_conplano);
       $this->o158_exclusao = ($this->o158_exclusao == "f"?@$GLOBALS["HTTP_POST_VARS"]["o158_exclusao"]:$this->o158_exclusao);
       $this->o158_sequencial = ($this->o158_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o158_sequencial"]:$this->o158_sequencial);
       $this->o158_ano = ($this->o158_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["o158_ano"]:$this->o158_ano);
     }else{
       $this->o158_sequencial = ($this->o158_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o158_sequencial"]:$this->o158_sequencial);
     }
   }

    public function incluir($o158_sequencial)
    {
      $this->atualizacampos();
     if($this->o158_orcparamseqcoluna == null ){ 
       $this->erro_sql = " Campo Coluna não informado.";
       $this->erro_campo = "o158_orcparamseqcoluna";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o158_conplano == null ){ 
       $this->erro_sql = " Campo Conta não informado.";
       $this->erro_campo = "o158_conplano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o158_exclusao == null ){ 
       $this->erro_sql = " Campo Exclusão não informado.";
       $this->erro_campo = "o158_exclusao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o158_ano == null ){ 
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "o158_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o158_sequencial == "" || $o158_sequencial == null ){
       $result = db_query("select nextval('orcparamseqcolunaconplano_o158_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: orcparamseqcolunaconplano_o158_sequencial_seq do campo: o158_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o158_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from orcparamseqcolunaconplano_o158_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o158_sequencial)){
         $this->erro_sql = " Campo o158_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o158_sequencial = $o158_sequencial; 
       }
     }
     if(($this->o158_sequencial == null) || ($this->o158_sequencial == "") ){ 
       $this->erro_sql = " Campo o158_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcparamseqcolunaconplano(
                                       o158_orcparamseqcoluna 
                                      ,o158_conplano 
                                      ,o158_exclusao 
                                      ,o158_sequencial 
                                      ,o158_ano 
                       )
                values (
                                $this->o158_orcparamseqcoluna 
                               ,$this->o158_conplano 
                               ,'$this->o158_exclusao' 
                               ,$this->o158_sequencial 
                               ,$this->o158_ano 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Contas da Coluna ($this->o158_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Contas da Coluna já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Contas da Coluna ($this->o158_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->o158_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->o158_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1010350,'$this->o158_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010423,1010353,'','".AddSlashes(pg_result($resaco,0,'o158_orcparamseqcoluna'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010423,1010352,'','".AddSlashes(pg_result($resaco,0,'o158_conplano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010423,1010351,'','".AddSlashes(pg_result($resaco,0,'o158_exclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010423,1010350,'','".AddSlashes(pg_result($resaco,0,'o158_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010423,1010358,'','".AddSlashes(pg_result($resaco,0,'o158_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($o158_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update orcparamseqcolunaconplano set ";
     $virgula = "";
     if(trim($this->o158_orcparamseqcoluna)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o158_orcparamseqcoluna"])){ 
       $sql  .= $virgula." o158_orcparamseqcoluna = $this->o158_orcparamseqcoluna ";
       $virgula = ",";
       if(trim($this->o158_orcparamseqcoluna) == null ){ 
         $this->erro_sql = " Campo Coluna não informado.";
         $this->erro_campo = "o158_orcparamseqcoluna";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o158_conplano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o158_conplano"])){ 
       $sql  .= $virgula." o158_conplano = $this->o158_conplano ";
       $virgula = ",";
       if(trim($this->o158_conplano) == null ){ 
         $this->erro_sql = " Campo Conta não informado.";
         $this->erro_campo = "o158_conplano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o158_exclusao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o158_exclusao"])){ 
       $sql  .= $virgula." o158_exclusao = '$this->o158_exclusao' ";
       $virgula = ",";
       if(trim($this->o158_exclusao) == null ){ 
         $this->erro_sql = " Campo Exclusão não informado.";
         $this->erro_campo = "o158_exclusao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o158_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o158_sequencial"])){ 
       $sql  .= $virgula." o158_sequencial = $this->o158_sequencial ";
       $virgula = ",";
       if(trim($this->o158_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "o158_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o158_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o158_ano"])){ 
       $sql  .= $virgula." o158_ano = $this->o158_ano ";
       $virgula = ",";
       if(trim($this->o158_ano) == null ){ 
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "o158_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o158_sequencial!=null){
       $sql .= " o158_sequencial = $this->o158_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->o158_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1010350,'$this->o158_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o158_orcparamseqcoluna"]) || $this->o158_orcparamseqcoluna != "")
             $resac = db_query("insert into db_acount values($acount,1010423,1010353,'".AddSlashes(pg_result($resaco,$conresaco,'o158_orcparamseqcoluna'))."','$this->o158_orcparamseqcoluna',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o158_conplano"]) || $this->o158_conplano != "")
             $resac = db_query("insert into db_acount values($acount,1010423,1010352,'".AddSlashes(pg_result($resaco,$conresaco,'o158_conplano'))."','$this->o158_conplano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o158_exclusao"]) || $this->o158_exclusao != "")
             $resac = db_query("insert into db_acount values($acount,1010423,1010351,'".AddSlashes(pg_result($resaco,$conresaco,'o158_exclusao'))."','$this->o158_exclusao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o158_sequencial"]) || $this->o158_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010423,1010350,'".AddSlashes(pg_result($resaco,$conresaco,'o158_sequencial'))."','$this->o158_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o158_ano"]) || $this->o158_ano != "")
             $resac = db_query("insert into db_acount values($acount,1010423,1010358,'".AddSlashes(pg_result($resaco,$conresaco,'o158_ano'))."','$this->o158_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contas da Coluna não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o158_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Contas da Coluna não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o158_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->o158_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($o158_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($o158_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1010350,'$o158_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010423,1010353,'','".AddSlashes(pg_result($resaco,$iresaco,'o158_orcparamseqcoluna'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010423,1010352,'','".AddSlashes(pg_result($resaco,$iresaco,'o158_conplano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010423,1010351,'','".AddSlashes(pg_result($resaco,$iresaco,'o158_exclusao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010423,1010350,'','".AddSlashes(pg_result($resaco,$iresaco,'o158_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010423,1010358,'','".AddSlashes(pg_result($resaco,$iresaco,'o158_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from orcparamseqcolunaconplano
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($o158_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " o158_sequencial = $o158_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contas da Coluna não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o158_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Contas da Coluna não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o158_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$o158_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcparamseqcolunaconplano";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($o158_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from orcparamseqcolunaconplano ";
     $sql .= "      inner join conplano  on  conplano.c60_codcon = orcparamseqcolunaconplano.o158_conplano and  conplano.c60_anousu = orcparamseqcolunaconplano.o158_ano";
     $sql .= "      inner join orcparamseqcoluna  on  orcparamseqcoluna.o115_sequencial = orcparamseqcolunaconplano.o158_orcparamseqcoluna";
     $sql .= "      inner join conclass  on  conclass.c51_codcla = conplano.c60_codcla";
     $sql .= "      inner join consistema  on  consistema.c52_codsis = conplano.c60_codsis";
     $sql .= "      inner join consistemaconta  on  consistemaconta.c65_sequencial = conplano.c60_consistemaconta";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($o158_sequencial)) {
         $sql2 .= " where orcparamseqcolunaconplano.o158_sequencial = $o158_sequencial "; 
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

    public function sql_query_file($o158_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from orcparamseqcolunaconplano ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($o158_sequencial)){
         $sql2 .= " where orcparamseqcolunaconplano.o158_sequencial = $o158_sequencial "; 
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
