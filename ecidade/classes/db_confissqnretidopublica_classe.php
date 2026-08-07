<?php

class cl_confissqnretidopublica
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
    public $j170_sequencial = 0; 
    public $j170_receit = 0; 
    public $j170_anousu = 0; 
    public $j170_tipo = 0; 
    public $j170_hist = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 j170_sequencial = int4 = Sequencial 
                 j170_receit = int4 = Receita 
                 j170_anousu = int4 = Exercício 
                 j170_tipo = int4 = Tipo de Débito 
                 j170_hist = int4 = Histórico 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("confissqnretidopublica"); 
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
       $this->j170_sequencial = ($this->j170_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j170_sequencial"]:$this->j170_sequencial);
       $this->j170_receit = ($this->j170_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["j170_receit"]:$this->j170_receit);
       $this->j170_anousu = ($this->j170_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j170_anousu"]:$this->j170_anousu);
       $this->j170_tipo = ($this->j170_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["j170_tipo"]:$this->j170_tipo);
       $this->j170_hist = ($this->j170_hist == ""?@$GLOBALS["HTTP_POST_VARS"]["j170_hist"]:$this->j170_hist);
     }else{
       $this->j170_sequencial = ($this->j170_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j170_sequencial"]:$this->j170_sequencial);
     }
   }

    public function incluir($j170_sequencial)
    {
      $this->atualizacampos();
     if($this->j170_receit == null ){ 
       $this->erro_sql = " Campo Receita não informado.";
       $this->erro_campo = "j170_receit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j170_anousu == null ){ 
       $this->erro_sql = " Campo Exercício não informado.";
       $this->erro_campo = "j170_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j170_hist == null ){ 
       $this->erro_sql = " Campo Histórico não informado.";
       $this->erro_campo = "j170_hist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j170_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de Débito não informado.";
       $this->erro_campo = "j170_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j170_sequencial == "" || $j170_sequencial == null ){
       $result = db_query("select nextval('confissqnretidopublica_j170_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: confissqnretidopublica_j170_sequencial_seq do campo: j170_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j170_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from confissqnretidopublica_j170_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $j170_sequencial)){
         $this->erro_sql = " Campo j170_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j170_sequencial = $j170_sequencial; 
       }
     }
     if(($this->j170_sequencial == null) || ($this->j170_sequencial == "") ){ 
       $this->erro_sql = " Campo j170_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into confissqnretidopublica(
                                       j170_sequencial 
                                      ,j170_receit 
                                      ,j170_anousu 
                                      ,j170_tipo 
                                      ,j170_hist 
                       )
                values (
                                $this->j170_sequencial 
                               ,$this->j170_receit 
                               ,$this->j170_anousu 
                               ,$this->j170_tipo 
                               ,$this->j170_hist 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Configuração ISSQN Retido Empresa Pública ($this->j170_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Configuração ISSQN Retido Empresa Pública já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Configuração ISSQN Retido Empresa Pública ($this->j170_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->j170_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->j170_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1011993,'$this->j170_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010650,1011993,'','".AddSlashes(pg_result($resaco,0,'j170_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010650,1011994,'','".AddSlashes(pg_result($resaco,0,'j170_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010650,1011995,'','".AddSlashes(pg_result($resaco,0,'j170_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010650,1014070,'','".AddSlashes(pg_result($resaco,0,'j170_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010650,1014071,'','".AddSlashes(pg_result($resaco,0,'j170_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($j170_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update confissqnretidopublica set ";
     $virgula = "";
     if(trim($this->j170_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j170_sequencial"])){ 
       $sql  .= $virgula." j170_sequencial = $this->j170_sequencial ";
       $virgula = ",";
       if(trim($this->j170_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "j170_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j170_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j170_receit"])){ 
       $sql  .= $virgula." j170_receit = $this->j170_receit ";
       $virgula = ",";
       if(trim($this->j170_receit) == null ){ 
         $this->erro_sql = " Campo Receita não informado.";
         $this->erro_campo = "j170_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j170_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j170_anousu"])){ 
       $sql  .= $virgula." j170_anousu = $this->j170_anousu ";
       $virgula = ",";
       if(trim($this->j170_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício não informado.";
         $this->erro_campo = "j170_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j170_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j170_tipo"])){ 
        if(trim($this->j170_tipo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j170_tipo"])){ 
           $this->j170_tipo = "0" ; 
        } 
       $sql  .= $virgula." j170_tipo = $this->j170_tipo ";
       $virgula = ",";
     }
     if(trim($this->j170_hist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j170_hist"])){ 
       $sql  .= $virgula." j170_hist = $this->j170_hist ";
       $virgula = ",";
       if(trim($this->j170_hist) == null ){ 
         $this->erro_sql = " Campo Histórico para retenção de empresa pública não informado.";
         $this->erro_campo = "j170_hist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j170_sequencial!=null){
       $sql .= " j170_sequencial = $this->j170_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->j170_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1011993,'$this->j170_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j170_sequencial"]) || $this->j170_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010650,1011993,'".AddSlashes(pg_result($resaco,$conresaco,'j170_sequencial'))."','$this->j170_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j170_receit"]) || $this->j170_receit != "")
             $resac = db_query("insert into db_acount values($acount,1010650,1011994,'".AddSlashes(pg_result($resaco,$conresaco,'j170_receit'))."','$this->j170_receit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j170_anousu"]) || $this->j170_anousu != "")
             $resac = db_query("insert into db_acount values($acount,1010650,1011995,'".AddSlashes(pg_result($resaco,$conresaco,'j170_anousu'))."','$this->j170_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j170_tipo"]) || $this->j170_tipo != "")
             $resac = db_query("insert into db_acount values($acount,1010650,1014070,'".AddSlashes(pg_result($resaco,$conresaco,'j170_tipo'))."','$this->j170_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j170_hist"]) || $this->j170_hist != "")
             $resac = db_query("insert into db_acount values($acount,1010650,1014071,'".AddSlashes(pg_result($resaco,$conresaco,'j170_hist'))."','$this->j170_hist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configuração ISSQN Retido Empresa Pública não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j170_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Configuração ISSQN Retido Empresa Pública não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j170_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->j170_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($j170_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($j170_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1011993,'$j170_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010650,1011993,'','".AddSlashes(pg_result($resaco,$iresaco,'j170_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010650,1011994,'','".AddSlashes(pg_result($resaco,$iresaco,'j170_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010650,1011995,'','".AddSlashes(pg_result($resaco,$iresaco,'j170_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010650,1014070,'','".AddSlashes(pg_result($resaco,$iresaco,'j170_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010650,1014071,'','".AddSlashes(pg_result($resaco,$iresaco,'j170_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from confissqnretidopublica
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($j170_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " j170_sequencial = $j170_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Configuração ISSQN Retido Empresa Pública não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j170_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Configuração ISSQN Retido Empresa Pública não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j170_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$j170_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:confissqnretidopublica";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($j170_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from confissqnretidopublica ";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = confissqnretidopublica.j170_hist";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = confissqnretidopublica.j170_receit";
     $sql .= "      left  join arretipo  on  arretipo.k00_tipo = confissqnretidopublica.j170_tipo";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join tabrectipo  on  tabrectipo.k116_sequencial = tabrec.k02_tabrectipo";
     $sql .= "      left  join tabdesc  on  tabdesc.codsubrec = arretipo.k00_taxaespecifica";
     $sql .= "      inner join db_config  on  db_config.codigo = arretipo.k00_instit";
     $sql .= "      inner join cadtipo  on  cadtipo.k03_tipo = arretipo.k03_tipo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($j170_sequencial)) {
         $sql2 .= " where confissqnretidopublica.j170_sequencial = $j170_sequencial "; 
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

    public function sql_query_file($j170_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from confissqnretidopublica ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($j170_sequencial)){
         $sql2 .= " where confissqnretidopublica.j170_sequencial = $j170_sequencial "; 
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
