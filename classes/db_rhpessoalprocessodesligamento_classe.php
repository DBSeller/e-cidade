<?php

class cl_rhpessoalprocessodesligamento
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
    public $rh279_sequencial = 0; 
    public $rh279_sequencialprocessovinculo = 0; 
    public $rh279_dtdeslig_dia = null; 
    public $rh279_dtdeslig_mes = null; 
    public $rh279_dtdeslig_ano = null; 
    public $rh279_dtdeslig = null; 
    public $rh279_mtvdeslig = null; 
    public $rh279_dtprojfimapi_dia = null; 
    public $rh279_dtprojfimapi_mes = null; 
    public $rh279_dtprojfimapi_ano = null; 
    public $rh279_dtprojfimapi = null; 
    public $rh279_pensalim = 0; 
    public $rh279_percaliment = 0; 
    public $rh279_vlralim = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh279_sequencial = int4 = Número Sequencial 
                 rh279_sequencialprocessovinculo = int4 = Processo vínculo 
                 rh279_dtdeslig = date = Data de desligamento 
                 rh279_mtvdeslig = varchar(2) = Motivo do desligamento 
                 rh279_dtprojfimapi = date = Término do aviso 
                 rh279_pensalim = int4 = Indicativo de pensão 
                 rh279_percaliment = float4 = Percentual pensão 
                 rh279_vlralim = float4 = Valor da pensão 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhpessoalprocessodesligamento"); 
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
       $this->rh279_sequencial = ($this->rh279_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh279_sequencial"]:$this->rh279_sequencial);
       $this->rh279_sequencialprocessovinculo = ($this->rh279_sequencialprocessovinculo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh279_sequencialprocessovinculo"]:$this->rh279_sequencialprocessovinculo);
       if($this->rh279_dtdeslig == ""){
         $this->rh279_dtdeslig_dia = ($this->rh279_dtdeslig_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh279_dtdeslig_dia"]:$this->rh279_dtdeslig_dia);
         $this->rh279_dtdeslig_mes = ($this->rh279_dtdeslig_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh279_dtdeslig_mes"]:$this->rh279_dtdeslig_mes);
         $this->rh279_dtdeslig_ano = ($this->rh279_dtdeslig_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh279_dtdeslig_ano"]:$this->rh279_dtdeslig_ano);
         if($this->rh279_dtdeslig_dia != ""){
            $this->rh279_dtdeslig = $this->rh279_dtdeslig_ano."-".$this->rh279_dtdeslig_mes."-".$this->rh279_dtdeslig_dia;
         }
       }
       $this->rh279_mtvdeslig = ($this->rh279_mtvdeslig == ""?@$GLOBALS["HTTP_POST_VARS"]["rh279_mtvdeslig"]:$this->rh279_mtvdeslig);
       if($this->rh279_dtprojfimapi == ""){
         $this->rh279_dtprojfimapi_dia = ($this->rh279_dtprojfimapi_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh279_dtprojfimapi_dia"]:$this->rh279_dtprojfimapi_dia);
         $this->rh279_dtprojfimapi_mes = ($this->rh279_dtprojfimapi_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh279_dtprojfimapi_mes"]:$this->rh279_dtprojfimapi_mes);
         $this->rh279_dtprojfimapi_ano = ($this->rh279_dtprojfimapi_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh279_dtprojfimapi_ano"]:$this->rh279_dtprojfimapi_ano);
         if($this->rh279_dtprojfimapi_dia != ""){
            $this->rh279_dtprojfimapi = $this->rh279_dtprojfimapi_ano."-".$this->rh279_dtprojfimapi_mes."-".$this->rh279_dtprojfimapi_dia;
         }
       }
       $this->rh279_pensalim = ($this->rh279_pensalim == ""?@$GLOBALS["HTTP_POST_VARS"]["rh279_pensalim"]:$this->rh279_pensalim);
       $this->rh279_percaliment = ($this->rh279_percaliment == ""?@$GLOBALS["HTTP_POST_VARS"]["rh279_percaliment"]:$this->rh279_percaliment);
       $this->rh279_vlralim = ($this->rh279_vlralim == ""?@$GLOBALS["HTTP_POST_VARS"]["rh279_vlralim"]:$this->rh279_vlralim);
     }else{
       $this->rh279_sequencial = ($this->rh279_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh279_sequencial"]:$this->rh279_sequencial);
     }
   }

    public function incluir($rh279_sequencial)
    {
      $this->atualizacampos();
     if($this->rh279_sequencialprocessovinculo == null ){ 
       $this->erro_sql = " Campo Processo vínculo não informado.";
       $this->erro_campo = "rh279_sequencialprocessovinculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh279_dtdeslig == null ){ 
       $this->rh279_dtdeslig = "null";
     }
     if($this->rh279_dtprojfimapi == null ){ 
       $this->rh279_dtprojfimapi = "null";
     }
     if($this->rh279_pensalim == null ){ 
       $this->rh279_pensalim = "0";
     }
     if($this->rh279_percaliment == null ){ 
       $this->rh279_percaliment = "0";
     }
     if($this->rh279_vlralim == null ){ 
       $this->rh279_vlralim = "0";
     }
     if($rh279_sequencial == "" || $rh279_sequencial == null ){
       $result = db_query("select nextval('recursoshumanos.rhpessoalprocessodesligamento_rh279_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: recursoshumanos.rhpessoalprocessodesligamento_rh279_sequencial_seq do campo: rh279_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh279_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from recursoshumanos.rhpessoalprocessodesligamento_rh279_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh279_sequencial)){
         $this->erro_sql = " Campo rh279_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh279_sequencial = $rh279_sequencial; 
       }
     }
     if(($this->rh279_sequencial == null) || ($this->rh279_sequencial == "") ){ 
       $this->erro_sql = " Campo rh279_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpessoalprocessodesligamento(
                                       rh279_sequencial 
                                      ,rh279_sequencialprocessovinculo 
                                      ,rh279_dtdeslig 
                                      ,rh279_mtvdeslig 
                                      ,rh279_dtprojfimapi 
                                      ,rh279_pensalim 
                                      ,rh279_percaliment 
                                      ,rh279_vlralim 
                       )
                values (
                                $this->rh279_sequencial 
                               ,$this->rh279_sequencialprocessovinculo 
                               ,".($this->rh279_dtdeslig == "null" || $this->rh279_dtdeslig == ""?"null":"'".$this->rh279_dtdeslig."'")." 
                               ,'$this->rh279_mtvdeslig' 
                               ,".($this->rh279_dtprojfimapi == "null" || $this->rh279_dtprojfimapi == ""?"null":"'".$this->rh279_dtprojfimapi."'")." 
                               ,$this->rh279_pensalim 
                               ,$this->rh279_percaliment 
                               ,$this->rh279_vlralim 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Informações do desligamento. ($this->rh279_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Informações do desligamento. já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Informações do desligamento. ($this->rh279_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh279_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   } 

    public function alterar($rh279_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhpessoalprocessodesligamento set ";
     $virgula = "";
     if(trim($this->rh279_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh279_sequencial"])){ 
       $sql  .= $virgula." rh279_sequencial = $this->rh279_sequencial ";
       $virgula = ",";
       if(trim($this->rh279_sequencial) == null ){ 
         $this->erro_sql = " Campo Número Sequencial não informado.";
         $this->erro_campo = "rh279_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh279_sequencialprocessovinculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh279_sequencialprocessovinculo"])){ 
       $sql  .= $virgula." rh279_sequencialprocessovinculo = $this->rh279_sequencialprocessovinculo ";
       $virgula = ",";
       if(trim($this->rh279_sequencialprocessovinculo) == null ){ 
         $this->erro_sql = " Campo Processo vínculo não informado.";
         $this->erro_campo = "rh279_sequencialprocessovinculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh279_dtdeslig)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh279_dtdeslig_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh279_dtdeslig_dia"] !="") ){ 
       $sql  .= $virgula." rh279_dtdeslig = '$this->rh279_dtdeslig' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh279_dtdeslig_dia"])){ 
         $sql  .= $virgula." rh279_dtdeslig = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh279_mtvdeslig)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh279_mtvdeslig"])){ 
       $sql  .= $virgula." rh279_mtvdeslig = '$this->rh279_mtvdeslig' ";
       $virgula = ",";
     }
     if(trim($this->rh279_dtprojfimapi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh279_dtprojfimapi_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh279_dtprojfimapi_dia"] !="") ){ 
       $sql  .= $virgula." rh279_dtprojfimapi = '$this->rh279_dtprojfimapi' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh279_dtprojfimapi_dia"])){ 
         $sql  .= $virgula." rh279_dtprojfimapi = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh279_pensalim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh279_pensalim"])){ 
        if(trim($this->rh279_pensalim)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh279_pensalim"])){ 
           $this->rh279_pensalim = "0" ; 
        } 
       $sql  .= $virgula." rh279_pensalim = $this->rh279_pensalim ";
       $virgula = ",";
     }
     if(trim($this->rh279_percaliment)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh279_percaliment"])){ 
        if(trim($this->rh279_percaliment)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh279_percaliment"])){ 
           $this->rh279_percaliment = "0" ; 
        } 
       $sql  .= $virgula." rh279_percaliment = $this->rh279_percaliment ";
       $virgula = ",";
     }
     if(trim($this->rh279_vlralim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh279_vlralim"])){ 
        if(trim($this->rh279_vlralim)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh279_vlralim"])){ 
           $this->rh279_vlralim = "0" ; 
        } 
       $sql  .= $virgula." rh279_vlralim = $this->rh279_vlralim ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh279_sequencial!=null){
       $sql .= " rh279_sequencial = $this->rh279_sequencial";
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Informações do desligamento. não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh279_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Informações do desligamento. não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh279_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh279_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh279_sequencial=null, $dbwhere = null)
    {
     $sql = " delete from rhpessoalprocessodesligamento
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh279_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh279_sequencial = $rh279_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Informações do desligamento. não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh279_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Informações do desligamento. não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh279_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh279_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpessoalprocessodesligamento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh279_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhpessoalprocessodesligamento ";
     $sql .= "      inner join rhpessoalprocessovinculo  on  rhpessoalprocessovinculo.rh274_sequencial = rhpessoalprocessodesligamento.rh279_sequencialprocessovinculo";
     $sql .= "      inner join rhpessoalprocessoservidor  on  rhpessoalprocessoservidor.rh271_sequencial = rhpessoalprocessovinculo.rh274_sequencialprocessoservidor";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh279_sequencial)) {
         $sql2 .= " where rhpessoalprocessodesligamento.rh279_sequencial = $rh279_sequencial "; 
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

    public function sql_query_file($rh279_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhpessoalprocessodesligamento ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh279_sequencial)){
         $sql2 .= " where rhpessoalprocessodesligamento.rh279_sequencial = $rh279_sequencial "; 
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
