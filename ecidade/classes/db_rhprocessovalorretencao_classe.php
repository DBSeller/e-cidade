<?php

class cl_rhprocessovalorretencao
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
    public $rh307_sequencial = 0; 
    public $rh307_sequencialretencao = 0; 
    public $rh307_indapuracao = 0; 
    public $rh307_vlrnretido = 0; 
    public $rh307_vlrdepjud = 0; 
    public $rh307_vlrcmpanocal = 0; 
    public $rh307_vlrcmpanoant = 0; 
    public $rh307_vlrrendsusp = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh307_sequencial = int4 = Sequencial 
                 rh307_sequencialretencao = int4 = Sequencial vinculo retencao 
                 rh307_indapuracao = int4 = Período de apuração 
                 rh307_vlrnretido = float4 = Valor da retenção 
                 rh307_vlrdepjud = float4 = Valor do depósito judicial 
                 rh307_vlrcmpanocal = float4 = Valor da compensação 
                 rh307_vlrcmpanoant = float4 = Valor da compensação 
                 rh307_vlrrendsusp = float4 = Exigibilidade suspensa 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhprocessovalorretencao"); 
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
       $this->rh307_sequencial = ($this->rh307_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh307_sequencial"]:$this->rh307_sequencial);
       $this->rh307_sequencialretencao = ($this->rh307_sequencialretencao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh307_sequencialretencao"]:$this->rh307_sequencialretencao);
       $this->rh307_indapuracao = ($this->rh307_indapuracao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh307_indapuracao"]:$this->rh307_indapuracao);
       $this->rh307_vlrnretido = ($this->rh307_vlrnretido == ""?@$GLOBALS["HTTP_POST_VARS"]["rh307_vlrnretido"]:$this->rh307_vlrnretido);
       $this->rh307_vlrdepjud = ($this->rh307_vlrdepjud == ""?@$GLOBALS["HTTP_POST_VARS"]["rh307_vlrdepjud"]:$this->rh307_vlrdepjud);
       $this->rh307_vlrcmpanocal = ($this->rh307_vlrcmpanocal == ""?@$GLOBALS["HTTP_POST_VARS"]["rh307_vlrcmpanocal"]:$this->rh307_vlrcmpanocal);
       $this->rh307_vlrcmpanoant = ($this->rh307_vlrcmpanoant == ""?@$GLOBALS["HTTP_POST_VARS"]["rh307_vlrcmpanoant"]:$this->rh307_vlrcmpanoant);
       $this->rh307_vlrrendsusp = ($this->rh307_vlrrendsusp == ""?@$GLOBALS["HTTP_POST_VARS"]["rh307_vlrrendsusp"]:$this->rh307_vlrrendsusp);
     }else{
       $this->rh307_sequencial = ($this->rh307_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh307_sequencial"]:$this->rh307_sequencial);
     }
   }

    public function incluir($rh307_sequencial)
    {
      $this->atualizacampos();
     if($this->rh307_sequencialretencao == null ){ 
       $this->erro_sql = " Campo Sequencial vinculo retencao não informado.";
       $this->erro_campo = "rh307_sequencialretencao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh307_indapuracao == null ){ 
       $this->rh307_indapuracao = "0";
     }
     if($this->rh307_vlrnretido == null ){ 
       $this->rh307_vlrnretido = "0";
     }
     if($this->rh307_vlrdepjud == null ){ 
       $this->rh307_vlrdepjud = "0";
     }
     if($this->rh307_vlrcmpanocal == null ){ 
       $this->rh307_vlrcmpanocal = "0";
     }
     if($this->rh307_vlrcmpanoant == null ){ 
       $this->rh307_vlrcmpanoant = "0";
     }
     if($this->rh307_vlrrendsusp == null ){ 
       $this->rh307_vlrrendsusp = "0";
     }
     if($rh307_sequencial == "" || $rh307_sequencial == null ){
       $result = db_query("select nextval('rhprocessovalorretencao_rh307_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhprocessovalorretencao_rh307_sequencial_seq do campo: rh307_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh307_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhprocessovalorretencao_rh307_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh307_sequencial)){
         $this->erro_sql = " Campo rh307_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh307_sequencial = $rh307_sequencial; 
       }
     }
     if(($this->rh307_sequencial == null) || ($this->rh307_sequencial == "") ){ 
       $this->erro_sql = " Campo rh307_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhprocessovalorretencao(
                                       rh307_sequencial 
                                      ,rh307_sequencialretencao 
                                      ,rh307_indapuracao 
                                      ,rh307_vlrnretido 
                                      ,rh307_vlrdepjud 
                                      ,rh307_vlrcmpanocal 
                                      ,rh307_vlrcmpanoant 
                                      ,rh307_vlrrendsusp 
                       )
                values (
                                $this->rh307_sequencial 
                               ,$this->rh307_sequencialretencao 
                               ,$this->rh307_indapuracao 
                               ,$this->rh307_vlrnretido 
                               ,$this->rh307_vlrdepjud 
                               ,$this->rh307_vlrcmpanocal 
                               ,$this->rh307_vlrcmpanoant 
                               ,$this->rh307_vlrrendsusp 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valores retenção de tributos ($this->rh307_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valores retenção de tributos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valores retenção de tributos ($this->rh307_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh307_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh307_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1015432,'$this->rh307_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1011142,1015432,'','".AddSlashes(pg_result($resaco,0,'rh307_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011142,1015433,'','".AddSlashes(pg_result($resaco,0,'rh307_sequencialretencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011142,1015434,'','".AddSlashes(pg_result($resaco,0,'rh307_indapuracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011142,1015435,'','".AddSlashes(pg_result($resaco,0,'rh307_vlrnretido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011142,1015436,'','".AddSlashes(pg_result($resaco,0,'rh307_vlrdepjud'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011142,1015437,'','".AddSlashes(pg_result($resaco,0,'rh307_vlrcmpanocal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011142,1015438,'','".AddSlashes(pg_result($resaco,0,'rh307_vlrcmpanoant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011142,1015439,'','".AddSlashes(pg_result($resaco,0,'rh307_vlrrendsusp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh307_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhprocessovalorretencao set ";
     $virgula = "";
     if(trim($this->rh307_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh307_sequencial"])){ 
       $sql  .= $virgula." rh307_sequencial = $this->rh307_sequencial ";
       $virgula = ",";
       if(trim($this->rh307_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh307_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh307_sequencialretencao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh307_sequencialretencao"])){ 
       $sql  .= $virgula." rh307_sequencialretencao = $this->rh307_sequencialretencao ";
       $virgula = ",";
       if(trim($this->rh307_sequencialretencao) == null ){ 
         $this->erro_sql = " Campo Sequencial vinculo retencao não informado.";
         $this->erro_campo = "rh307_sequencialretencao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh307_indapuracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh307_indapuracao"])){ 
        if(trim($this->rh307_indapuracao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh307_indapuracao"])){ 
           $this->rh307_indapuracao = "0" ; 
        } 
       $sql  .= $virgula." rh307_indapuracao = $this->rh307_indapuracao ";
       $virgula = ",";
     }
     if(trim($this->rh307_vlrnretido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh307_vlrnretido"])){ 
        if(trim($this->rh307_vlrnretido)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh307_vlrnretido"])){ 
           $this->rh307_vlrnretido = "0" ; 
        } 
       $sql  .= $virgula." rh307_vlrnretido = $this->rh307_vlrnretido ";
       $virgula = ",";
     }
     if(trim($this->rh307_vlrdepjud)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh307_vlrdepjud"])){ 
        if(trim($this->rh307_vlrdepjud)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh307_vlrdepjud"])){ 
           $this->rh307_vlrdepjud = "0" ; 
        } 
       $sql  .= $virgula." rh307_vlrdepjud = $this->rh307_vlrdepjud ";
       $virgula = ",";
     }
     if(trim($this->rh307_vlrcmpanocal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh307_vlrcmpanocal"])){ 
        if(trim($this->rh307_vlrcmpanocal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh307_vlrcmpanocal"])){ 
           $this->rh307_vlrcmpanocal = "0" ; 
        } 
       $sql  .= $virgula." rh307_vlrcmpanocal = $this->rh307_vlrcmpanocal ";
       $virgula = ",";
     }
     if(trim($this->rh307_vlrcmpanoant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh307_vlrcmpanoant"])){ 
        if(trim($this->rh307_vlrcmpanoant)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh307_vlrcmpanoant"])){ 
           $this->rh307_vlrcmpanoant = "0" ; 
        } 
       $sql  .= $virgula." rh307_vlrcmpanoant = $this->rh307_vlrcmpanoant ";
       $virgula = ",";
     }
     if(trim($this->rh307_vlrrendsusp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh307_vlrrendsusp"])){ 
        if(trim($this->rh307_vlrrendsusp)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh307_vlrrendsusp"])){ 
           $this->rh307_vlrrendsusp = "0" ; 
        } 
       $sql  .= $virgula." rh307_vlrrendsusp = $this->rh307_vlrrendsusp ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh307_sequencial!=null){
       $sql .= " rh307_sequencial = $this->rh307_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh307_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1015432,'$this->rh307_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh307_sequencial"]) || $this->rh307_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1011142,1015432,'".AddSlashes(pg_result($resaco,$conresaco,'rh307_sequencial'))."','$this->rh307_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh307_sequencialretencao"]) || $this->rh307_sequencialretencao != "")
             $resac = db_query("insert into db_acount values($acount,1011142,1015433,'".AddSlashes(pg_result($resaco,$conresaco,'rh307_sequencialretencao'))."','$this->rh307_sequencialretencao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh307_indapuracao"]) || $this->rh307_indapuracao != "")
             $resac = db_query("insert into db_acount values($acount,1011142,1015434,'".AddSlashes(pg_result($resaco,$conresaco,'rh307_indapuracao'))."','$this->rh307_indapuracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh307_vlrnretido"]) || $this->rh307_vlrnretido != "")
             $resac = db_query("insert into db_acount values($acount,1011142,1015435,'".AddSlashes(pg_result($resaco,$conresaco,'rh307_vlrnretido'))."','$this->rh307_vlrnretido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh307_vlrdepjud"]) || $this->rh307_vlrdepjud != "")
             $resac = db_query("insert into db_acount values($acount,1011142,1015436,'".AddSlashes(pg_result($resaco,$conresaco,'rh307_vlrdepjud'))."','$this->rh307_vlrdepjud',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh307_vlrcmpanocal"]) || $this->rh307_vlrcmpanocal != "")
             $resac = db_query("insert into db_acount values($acount,1011142,1015437,'".AddSlashes(pg_result($resaco,$conresaco,'rh307_vlrcmpanocal'))."','$this->rh307_vlrcmpanocal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh307_vlrcmpanoant"]) || $this->rh307_vlrcmpanoant != "")
             $resac = db_query("insert into db_acount values($acount,1011142,1015438,'".AddSlashes(pg_result($resaco,$conresaco,'rh307_vlrcmpanoant'))."','$this->rh307_vlrcmpanoant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh307_vlrrendsusp"]) || $this->rh307_vlrrendsusp != "")
             $resac = db_query("insert into db_acount values($acount,1011142,1015439,'".AddSlashes(pg_result($resaco,$conresaco,'rh307_vlrrendsusp'))."','$this->rh307_vlrrendsusp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores retenção de tributos não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh307_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Valores retenção de tributos não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh307_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh307_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh307_sequencial=null, $dbwhere = null)
    {
     $sql = " delete from rhprocessovalorretencao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh307_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh307_sequencial = $rh307_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores retenção de tributos não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh307_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Valores retenção de tributos não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh307_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh307_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhprocessovalorretencao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh307_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhprocessovalorretencao ";
     $sql .= "      inner join rhprocessoretencao  on  rhprocessoretencao.rh306_sequencial = rhprocessovalorretencao.rh307_sequencialretencao";
     $sql .= "      inner join rhprocessotributoirrf  on  rhprocessotributoirrf.rh299_sequencial = rhprocessoretencao.rh306_sequencialtributoirrf";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh307_sequencial)) {
         $sql2 .= " where rhprocessovalorretencao.rh307_sequencial = $rh307_sequencial "; 
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

    public function sql_query_file($rh307_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhprocessovalorretencao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh307_sequencial)){
         $sql2 .= " where rhprocessovalorretencao.rh307_sequencial = $rh307_sequencial "; 
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
