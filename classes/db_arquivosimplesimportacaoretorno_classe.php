<?php

class cl_arquivosimplesimportacaoretorno
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
    public $q182_sequencial = 0; 
    public $q182_id_usuario = 0; 
    public $q182_nomearquivo = null; 
    public $q182_id_storage = 0; 
    public $q182_arquivosimplesimportacao = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 q182_sequencial = int8 = Sequencial 
                 q182_id_usuario = int4 = Usuário 
                 q182_nomearquivo = varchar(50) = Nome Arquivo 
                 q182_id_storage = int8 = id arquivo e-storage 
                 q182_arquivosimplesimportacao = int4 = Arquivo simples importação 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("arquivosimplesimportacaoretorno"); 
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
       $this->q182_sequencial = ($this->q182_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q182_sequencial"]:$this->q182_sequencial);
       $this->q182_id_usuario = ($this->q182_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["q182_id_usuario"]:$this->q182_id_usuario);
       $this->q182_nomearquivo = ($this->q182_nomearquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["q182_nomearquivo"]:$this->q182_nomearquivo);
       $this->q182_id_storage = ($this->q182_id_storage == ""?@$GLOBALS["HTTP_POST_VARS"]["q182_id_storage"]:$this->q182_id_storage);
       $this->q182_arquivosimplesimportacao = ($this->q182_arquivosimplesimportacao == ""?@$GLOBALS["HTTP_POST_VARS"]["q182_arquivosimplesimportacao"]:$this->q182_arquivosimplesimportacao);
     }else{
       $this->q182_sequencial = ($this->q182_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q182_sequencial"]:$this->q182_sequencial);
     }
   }

    public function incluir($q182_sequencial)
    {
      $this->atualizacampos();
     if($this->q182_id_usuario == null ){ 
       $this->erro_sql = " Campo Usuário não informado.";
       $this->erro_campo = "q182_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q182_nomearquivo == null ){ 
       $this->erro_sql = " Campo Nome Arquivo não informado.";
       $this->erro_campo = "q182_nomearquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q182_id_storage == null ){ 
       $this->erro_sql = " Campo id arquivo e-storage não informado.";
       $this->erro_campo = "q182_id_storage";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q182_arquivosimplesimportacao == null ){ 
       $this->erro_sql = " Campo Arquivo simples importação não informado.";
       $this->erro_campo = "q182_arquivosimplesimportacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q182_sequencial == "" || $q182_sequencial == null ){
       $result = db_query("select nextval('arquivosimplesimportacaoretorno_q182_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: arquivosimplesimportacaoretorno_q182_sequencial_seq do campo: q182_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q182_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from arquivosimplesimportacaoretorno_q182_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q182_sequencial)){
         $this->erro_sql = " Campo q182_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q182_sequencial = $q182_sequencial; 
       }
     }
     if(($this->q182_sequencial == null) || ($this->q182_sequencial == "") ){ 
       $this->erro_sql = " Campo q182_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into arquivosimplesimportacaoretorno(
                                       q182_sequencial 
                                      ,q182_id_usuario 
                                      ,q182_nomearquivo 
                                      ,q182_id_storage 
                                      ,q182_arquivosimplesimportacao 
                       )
                values (
                                $this->q182_sequencial 
                               ,$this->q182_id_usuario 
                               ,'$this->q182_nomearquivo' 
                               ,$this->q182_id_storage 
                               ,$this->q182_arquivosimplesimportacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "arquivosimplesimportacaoretorno ($this->q182_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "arquivosimplesimportacaoretorno já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "arquivosimplesimportacaoretorno ($this->q182_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->q182_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->q182_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1014662,'$this->q182_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1011010,1014662,'','".AddSlashes(pg_result($resaco,0,'q182_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011010,1014663,'','".AddSlashes(pg_result($resaco,0,'q182_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011010,1014664,'','".AddSlashes(pg_result($resaco,0,'q182_nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011010,1014665,'','".AddSlashes(pg_result($resaco,0,'q182_id_storage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011010,1014666,'','".AddSlashes(pg_result($resaco,0,'q182_arquivosimplesimportacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($q182_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update arquivosimplesimportacaoretorno set ";
     $virgula = "";
     if(trim($this->q182_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q182_sequencial"])){ 
       $sql  .= $virgula." q182_sequencial = $this->q182_sequencial ";
       $virgula = ",";
       if(trim($this->q182_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "q182_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q182_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q182_id_usuario"])){ 
       $sql  .= $virgula." q182_id_usuario = $this->q182_id_usuario ";
       $virgula = ",";
       if(trim($this->q182_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário não informado.";
         $this->erro_campo = "q182_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q182_nomearquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q182_nomearquivo"])){ 
       $sql  .= $virgula." q182_nomearquivo = '$this->q182_nomearquivo' ";
       $virgula = ",";
       if(trim($this->q182_nomearquivo) == null ){ 
         $this->erro_sql = " Campo Nome Arquivo não informado.";
         $this->erro_campo = "q182_nomearquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q182_id_storage)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q182_id_storage"])){ 
       $sql  .= $virgula." q182_id_storage = $this->q182_id_storage ";
       $virgula = ",";
       if(trim($this->q182_id_storage) == null ){ 
         $this->erro_sql = " Campo id arquivo e-storage não informado.";
         $this->erro_campo = "q182_id_storage";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q182_arquivosimplesimportacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q182_arquivosimplesimportacao"])){ 
       $sql  .= $virgula." q182_arquivosimplesimportacao = $this->q182_arquivosimplesimportacao ";
       $virgula = ",";
       if(trim($this->q182_arquivosimplesimportacao) == null ){ 
         $this->erro_sql = " Campo Arquivo simples importação não informado.";
         $this->erro_campo = "q182_arquivosimplesimportacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q182_sequencial!=null){
       $sql .= " q182_sequencial = $this->q182_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->q182_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1014662,'$this->q182_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q182_sequencial"]) || $this->q182_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1011010,1014662,'".AddSlashes(pg_result($resaco,$conresaco,'q182_sequencial'))."','$this->q182_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q182_id_usuario"]) || $this->q182_id_usuario != "")
             $resac = db_query("insert into db_acount values($acount,1011010,1014663,'".AddSlashes(pg_result($resaco,$conresaco,'q182_id_usuario'))."','$this->q182_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q182_nomearquivo"]) || $this->q182_nomearquivo != "")
             $resac = db_query("insert into db_acount values($acount,1011010,1014664,'".AddSlashes(pg_result($resaco,$conresaco,'q182_nomearquivo'))."','$this->q182_nomearquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q182_id_storage"]) || $this->q182_id_storage != "")
             $resac = db_query("insert into db_acount values($acount,1011010,1014665,'".AddSlashes(pg_result($resaco,$conresaco,'q182_id_storage'))."','$this->q182_id_storage',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q182_arquivosimplesimportacao"]) || $this->q182_arquivosimplesimportacao != "")
             $resac = db_query("insert into db_acount values($acount,1011010,1014666,'".AddSlashes(pg_result($resaco,$conresaco,'q182_arquivosimplesimportacao'))."','$this->q182_arquivosimplesimportacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "arquivosimplesimportacaoretorno não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q182_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "arquivosimplesimportacaoretorno não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q182_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->q182_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($q182_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($q182_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1014662,'$q182_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1011010,1014662,'','".AddSlashes(pg_result($resaco,$iresaco,'q182_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011010,1014663,'','".AddSlashes(pg_result($resaco,$iresaco,'q182_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011010,1014664,'','".AddSlashes(pg_result($resaco,$iresaco,'q182_nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011010,1014665,'','".AddSlashes(pg_result($resaco,$iresaco,'q182_id_storage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011010,1014666,'','".AddSlashes(pg_result($resaco,$iresaco,'q182_arquivosimplesimportacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from arquivosimplesimportacaoretorno
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($q182_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " q182_sequencial = $q182_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "arquivosimplesimportacaoretorno não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q182_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "arquivosimplesimportacaoretorno não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q182_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$q182_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:arquivosimplesimportacaoretorno";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($q182_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from arquivosimplesimportacaoretorno ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = arquivosimplesimportacaoretorno.q182_id_usuario";
     $sql .= "      inner join arquivosimplesimportacao  on  arquivosimplesimportacao.q64_sequencial = arquivosimplesimportacaoretorno.q182_arquivosimplesimportacao";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($q182_sequencial)) {
         $sql2 .= " where arquivosimplesimportacaoretorno.q182_sequencial = $q182_sequencial "; 
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

    public function sql_query_file($q182_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from arquivosimplesimportacaoretorno ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($q182_sequencial)){
         $sql2 .= " where arquivosimplesimportacaoretorno.q182_sequencial = $q182_sequencial "; 
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
