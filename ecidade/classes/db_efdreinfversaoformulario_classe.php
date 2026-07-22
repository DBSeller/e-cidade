<?php

class cl_efdreinfversaoformulario
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
    public $efd03_sequencial = 0; 
    public $efd03_versao = null; 
    public $efd03_avaliacao = 0; 
    public $efd03_esocialformulariotipo = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 efd03_sequencial = int4 = Código 
                 efd03_versao = varchar(10) = Versão 
                 efd03_avaliacao = int4 = Avaliação 
                 efd03_esocialformulariotipo = int4 = Tipo 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("efdreinfversaoformulario"); 
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
       $this->efd03_sequencial = ($this->efd03_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["efd03_sequencial"]:$this->efd03_sequencial);
       $this->efd03_versao = ($this->efd03_versao == ""?@$GLOBALS["HTTP_POST_VARS"]["efd03_versao"]:$this->efd03_versao);
       $this->efd03_avaliacao = ($this->efd03_avaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["efd03_avaliacao"]:$this->efd03_avaliacao);
       $this->efd03_esocialformulariotipo = ($this->efd03_esocialformulariotipo == ""?@$GLOBALS["HTTP_POST_VARS"]["efd03_esocialformulariotipo"]:$this->efd03_esocialformulariotipo);
     }else{
       $this->efd03_sequencial = ($this->efd03_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["efd03_sequencial"]:$this->efd03_sequencial);
     }
   }

    public function incluir($efd03_sequencial)
    {
      $this->atualizacampos();
     if($this->efd03_versao == null ){ 
       $this->erro_sql = " Campo Versão não informado.";
       $this->erro_campo = "efd03_versao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->efd03_avaliacao == null ){ 
       $this->erro_sql = " Campo Avaliação não informado.";
       $this->erro_campo = "efd03_avaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->efd03_esocialformulariotipo == null ){ 
       $this->erro_sql = " Campo Tipo não informado.";
       $this->erro_campo = "efd03_esocialformulariotipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($efd03_sequencial == "" || $efd03_sequencial == null ){
       $result = db_query("select nextval('efdreinfversaoformulario_efd03_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: efdreinfversaoformulario_efd03_sequencial_seq do campo: efd03_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->efd03_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from efdreinfversaoformulario_efd03_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $efd03_sequencial)){
         $this->erro_sql = " Campo efd03_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->efd03_sequencial = $efd03_sequencial; 
       }
     }
     if(($this->efd03_sequencial == null) || ($this->efd03_sequencial == "") ){ 
       $this->erro_sql = " Campo efd03_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into efdreinfversaoformulario(
                                       efd03_sequencial 
                                      ,efd03_versao 
                                      ,efd03_avaliacao 
                                      ,efd03_esocialformulariotipo 
                       )
                values (
                                $this->efd03_sequencial 
                               ,'$this->efd03_versao' 
                               ,$this->efd03_avaliacao 
                               ,$this->efd03_esocialformulariotipo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "formulários efd ($this->efd03_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "formulários efd já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "formulários efd ($this->efd03_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->efd03_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->efd03_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1010205,'$this->efd03_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010359,1010205,'','".AddSlashes(pg_result($resaco,0,'efd03_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010359,1010206,'','".AddSlashes(pg_result($resaco,0,'efd03_versao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010359,1010207,'','".AddSlashes(pg_result($resaco,0,'efd03_avaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010359,1010208,'','".AddSlashes(pg_result($resaco,0,'efd03_esocialformulariotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($efd03_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update efdreinfversaoformulario set ";
     $virgula = "";
     if(trim($this->efd03_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["efd03_sequencial"])){ 
       $sql  .= $virgula." efd03_sequencial = $this->efd03_sequencial ";
       $virgula = ",";
       if(trim($this->efd03_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "efd03_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->efd03_versao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["efd03_versao"])){ 
       $sql  .= $virgula." efd03_versao = '$this->efd03_versao' ";
       $virgula = ",";
       if(trim($this->efd03_versao) == null ){ 
         $this->erro_sql = " Campo Versão não informado.";
         $this->erro_campo = "efd03_versao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->efd03_avaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["efd03_avaliacao"])){ 
       $sql  .= $virgula." efd03_avaliacao = $this->efd03_avaliacao ";
       $virgula = ",";
       if(trim($this->efd03_avaliacao) == null ){ 
         $this->erro_sql = " Campo Avaliação não informado.";
         $this->erro_campo = "efd03_avaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->efd03_esocialformulariotipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["efd03_esocialformulariotipo"])){ 
       $sql  .= $virgula." efd03_esocialformulariotipo = $this->efd03_esocialformulariotipo ";
       $virgula = ",";
       if(trim($this->efd03_esocialformulariotipo) == null ){ 
         $this->erro_sql = " Campo Tipo não informado.";
         $this->erro_campo = "efd03_esocialformulariotipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($efd03_sequencial!=null){
       $sql .= " efd03_sequencial = $this->efd03_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->efd03_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1010205,'$this->efd03_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["efd03_sequencial"]) || $this->efd03_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010359,1010205,'".AddSlashes(pg_result($resaco,$conresaco,'efd03_sequencial'))."','$this->efd03_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["efd03_versao"]) || $this->efd03_versao != "")
             $resac = db_query("insert into db_acount values($acount,1010359,1010206,'".AddSlashes(pg_result($resaco,$conresaco,'efd03_versao'))."','$this->efd03_versao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["efd03_avaliacao"]) || $this->efd03_avaliacao != "")
             $resac = db_query("insert into db_acount values($acount,1010359,1010207,'".AddSlashes(pg_result($resaco,$conresaco,'efd03_avaliacao'))."','$this->efd03_avaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["efd03_esocialformulariotipo"]) || $this->efd03_esocialformulariotipo != "")
             $resac = db_query("insert into db_acount values($acount,1010359,1010208,'".AddSlashes(pg_result($resaco,$conresaco,'efd03_esocialformulariotipo'))."','$this->efd03_esocialformulariotipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "formulários efd não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->efd03_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "formulários efd não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->efd03_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->efd03_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($efd03_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($efd03_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1010205,'$efd03_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010359,1010205,'','".AddSlashes(pg_result($resaco,$iresaco,'efd03_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010359,1010206,'','".AddSlashes(pg_result($resaco,$iresaco,'efd03_versao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010359,1010207,'','".AddSlashes(pg_result($resaco,$iresaco,'efd03_avaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010359,1010208,'','".AddSlashes(pg_result($resaco,$iresaco,'efd03_esocialformulariotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from efdreinfversaoformulario
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($efd03_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " efd03_sequencial = $efd03_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "formulários efd não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$efd03_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "formulários efd não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$efd03_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$efd03_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:efdreinfversaoformulario";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($efd03_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from efdreinfversaoformulario ";
     $sql .= "      inner join avaliacao  on  avaliacao.db101_sequencial = efdreinfversaoformulario.efd03_avaliacao";
     $sql .= "      inner join afastamentoservidoresocial  on  afastamentoservidoresocial.eso12_sequencial = efdreinfversaoformulario.efd03_esocialformulariotipo";
     $sql .= "      inner join avaliacaotipo  on  avaliacaotipo.db100_sequencial = avaliacao.db101_avaliacaotipo";
     $sql .= "      left  join assenta  on  assenta.h16_codigo = afastamentoservidoresocial.eso12_assenta";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = afastamentoservidoresocial.eso12_rhpessoal";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($efd03_sequencial)) {
         $sql2 .= " where efdreinfversaoformulario.efd03_sequencial = $efd03_sequencial "; 
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

    public function sql_query_file($efd03_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from efdreinfversaoformulario ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($efd03_sequencial)){
         $sql2 .= " where efdreinfversaoformulario.efd03_sequencial = $efd03_sequencial "; 
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
