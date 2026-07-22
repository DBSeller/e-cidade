<?php

class cl_linhacolunavalorinformacaocomplementar
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
    public $o157_orcparamseqorcparamseqcoluna = 0; 
    public $o157_conplanoinfocomplementar = 0; 
    public $o157_valor = null; 
    public $o157_sequencial = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 o157_orcparamseqorcparamseqcoluna = int8 = Linha/Coluna 
                 o157_conplanoinfocomplementar = int8 = Informação Complementar 
                 o157_valor = varchar(255) = Valor 
                 o157_sequencial = int8 = Sequencial 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("linhacolunavalorinformacaocomplementar"); 
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
       $this->o157_orcparamseqorcparamseqcoluna = ($this->o157_orcparamseqorcparamseqcoluna == ""?@$GLOBALS["HTTP_POST_VARS"]["o157_orcparamseqorcparamseqcoluna"]:$this->o157_orcparamseqorcparamseqcoluna);
       $this->o157_conplanoinfocomplementar = ($this->o157_conplanoinfocomplementar == ""?@$GLOBALS["HTTP_POST_VARS"]["o157_conplanoinfocomplementar"]:$this->o157_conplanoinfocomplementar);
       $this->o157_valor = ($this->o157_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["o157_valor"]:$this->o157_valor);
       $this->o157_sequencial = ($this->o157_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o157_sequencial"]:$this->o157_sequencial);
     }else{
       $this->o157_sequencial = ($this->o157_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["o157_sequencial"]:$this->o157_sequencial);
     }
   }

    public function incluir($o157_sequencial)
    {
      $this->atualizacampos();
     if($this->o157_orcparamseqorcparamseqcoluna == null ){ 
       $this->erro_sql = " Campo Linha/Coluna não informado.";
       $this->erro_campo = "o157_orcparamseqorcparamseqcoluna";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o157_conplanoinfocomplementar == null ){ 
       $this->erro_sql = " Campo Informação Complementar não informado.";
       $this->erro_campo = "o157_conplanoinfocomplementar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o157_valor == null ){ 
       $this->erro_sql = " Campo Valor não informado.";
       $this->erro_campo = "o157_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($o157_sequencial == "" || $o157_sequencial == null ){
       $result = db_query("select nextval('linhacolunavalorinformacaocomplementar_o157_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: linhacolunavalorinformacaocomplementar_o157_sequencial_seq do campo: o157_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->o157_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from linhacolunavalorinformacaocomplementar_o157_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $o157_sequencial)){
         $this->erro_sql = " Campo o157_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->o157_sequencial = $o157_sequencial; 
       }
     }
     if(($this->o157_sequencial == null) || ($this->o157_sequencial == "") ){ 
       $this->erro_sql = " Campo o157_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into linhacolunavalorinformacaocomplementar(
                                       o157_orcparamseqorcparamseqcoluna 
                                      ,o157_conplanoinfocomplementar 
                                      ,o157_valor 
                                      ,o157_sequencial 
                       )
                values (
                                $this->o157_orcparamseqorcparamseqcoluna 
                               ,$this->o157_conplanoinfocomplementar 
                               ,'$this->o157_valor' 
                               ,$this->o157_sequencial 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Contas da Linha/Coluna ($this->o157_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Contas da Linha/Coluna já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Contas da Linha/Coluna ($this->o157_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->o157_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->o157_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1010354,'$this->o157_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010424,1010357,'','".AddSlashes(pg_result($resaco,0,'o157_orcparamseqorcparamseqcoluna'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010424,1010356,'','".AddSlashes(pg_result($resaco,0,'o157_conplanoinfocomplementar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010424,1010355,'','".AddSlashes(pg_result($resaco,0,'o157_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010424,1010354,'','".AddSlashes(pg_result($resaco,0,'o157_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($o157_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update linhacolunavalorinformacaocomplementar set ";
     $virgula = "";
     if(trim($this->o157_orcparamseqorcparamseqcoluna)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o157_orcparamseqorcparamseqcoluna"])){ 
       $sql  .= $virgula." o157_orcparamseqorcparamseqcoluna = $this->o157_orcparamseqorcparamseqcoluna ";
       $virgula = ",";
       if(trim($this->o157_orcparamseqorcparamseqcoluna) == null ){ 
         $this->erro_sql = " Campo Linha/Coluna não informado.";
         $this->erro_campo = "o157_orcparamseqorcparamseqcoluna";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o157_conplanoinfocomplementar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o157_conplanoinfocomplementar"])){ 
       $sql  .= $virgula." o157_conplanoinfocomplementar = $this->o157_conplanoinfocomplementar ";
       $virgula = ",";
       if(trim($this->o157_conplanoinfocomplementar) == null ){ 
         $this->erro_sql = " Campo Informação Complementar não informado.";
         $this->erro_campo = "o157_conplanoinfocomplementar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o157_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o157_valor"])){ 
       $sql  .= $virgula." o157_valor = '$this->o157_valor' ";
       $virgula = ",";
       if(trim($this->o157_valor) == null ){ 
         $this->erro_sql = " Campo Valor não informado.";
         $this->erro_campo = "o157_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o157_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o157_sequencial"])){ 
       $sql  .= $virgula." o157_sequencial = $this->o157_sequencial ";
       $virgula = ",";
       if(trim($this->o157_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "o157_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o157_sequencial!=null){
       $sql .= " o157_sequencial = $this->o157_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->o157_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1010354,'$this->o157_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o157_orcparamseqorcparamseqcoluna"]) || $this->o157_orcparamseqorcparamseqcoluna != "")
             $resac = db_query("insert into db_acount values($acount,1010424,1010357,'".AddSlashes(pg_result($resaco,$conresaco,'o157_orcparamseqorcparamseqcoluna'))."','$this->o157_orcparamseqorcparamseqcoluna',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o157_conplanoinfocomplementar"]) || $this->o157_conplanoinfocomplementar != "")
             $resac = db_query("insert into db_acount values($acount,1010424,1010356,'".AddSlashes(pg_result($resaco,$conresaco,'o157_conplanoinfocomplementar'))."','$this->o157_conplanoinfocomplementar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o157_valor"]) || $this->o157_valor != "")
             $resac = db_query("insert into db_acount values($acount,1010424,1010355,'".AddSlashes(pg_result($resaco,$conresaco,'o157_valor'))."','$this->o157_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o157_sequencial"]) || $this->o157_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010424,1010354,'".AddSlashes(pg_result($resaco,$conresaco,'o157_sequencial'))."','$this->o157_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contas da Linha/Coluna não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o157_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Contas da Linha/Coluna não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o157_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->o157_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($o157_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($o157_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1010354,'$o157_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010424,1010357,'','".AddSlashes(pg_result($resaco,$iresaco,'o157_orcparamseqorcparamseqcoluna'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010424,1010356,'','".AddSlashes(pg_result($resaco,$iresaco,'o157_conplanoinfocomplementar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010424,1010355,'','".AddSlashes(pg_result($resaco,$iresaco,'o157_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010424,1010354,'','".AddSlashes(pg_result($resaco,$iresaco,'o157_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from linhacolunavalorinformacaocomplementar
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($o157_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " o157_sequencial = $o157_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contas da Linha/Coluna não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o157_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Contas da Linha/Coluna não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o157_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$o157_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:linhacolunavalorinformacaocomplementar";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($o157_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from linhacolunavalorinformacaocomplementar ";
     $sql .= "      inner join orcparamseqorcparamseqcoluna  on  orcparamseqorcparamseqcoluna.o116_sequencial = linhacolunavalorinformacaocomplementar.o157_orcparamseqorcparamseqcoluna";
     $sql .= "      inner join conplanoinfocomplementar  on  conplanoinfocomplementar.c121_sequencial = linhacolunavalorinformacaocomplementar.o157_conplanoinfocomplementar";
     $sql .= "      inner join orcparamseq  on  orcparamseq.o69_codparamrel = orcparamseqorcparamseqcoluna.o116_codparamrel and  orcparamseq.o69_codseq = orcparamseqorcparamseqcoluna.o116_codseq";
     $sql .= "      left  join periodo  on  periodo.o114_sequencial = orcparamseqorcparamseqcoluna.o116_periodo";
     $sql .= "      inner join orcparamseqcoluna  on  orcparamseqcoluna.o115_sequencial = orcparamseqorcparamseqcoluna.o116_orcparamseqcoluna";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($o157_sequencial)) {
         $sql2 .= " where linhacolunavalorinformacaocomplementar.o157_sequencial = $o157_sequencial "; 
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

    public function sql_query_file($o157_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from linhacolunavalorinformacaocomplementar ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($o157_sequencial)){
         $sql2 .= " where linhacolunavalorinformacaocomplementar.o157_sequencial = $o157_sequencial "; 
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
