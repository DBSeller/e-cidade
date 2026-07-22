<?php

class cl_sagresarquivogerado
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
    public $c141_sequencial = 0; 
    public $c141_usuario = 0; 
    public $c141_data_dia = null; 
    public $c141_data_mes = null; 
    public $c141_data_ano = null; 
    public $c141_data = null; 
    public $c141_codlayout = 0; 
    public $c141_nomearquivo = null; 
    public $c141_json = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 c141_sequencial = int4 = Sequencial 
                 c141_usuario = int4 = Usuário 
                 c141_data = date = Data de execução 
                 c141_codlayout = int4 = Código de identificação do layout 
                 c141_nomearquivo = varchar(70) = Nome do arquivo 
                 c141_json = text = JSON 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("sagresarquivogerado"); 
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
       $this->c141_sequencial = ($this->c141_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c141_sequencial"]:$this->c141_sequencial);
       $this->c141_usuario = ($this->c141_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["c141_usuario"]:$this->c141_usuario);
       if($this->c141_data == ""){
         $this->c141_data_dia = ($this->c141_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c141_data_dia"]:$this->c141_data_dia);
         $this->c141_data_mes = ($this->c141_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c141_data_mes"]:$this->c141_data_mes);
         $this->c141_data_ano = ($this->c141_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c141_data_ano"]:$this->c141_data_ano);
         if($this->c141_data_dia != ""){
            $this->c141_data = $this->c141_data_ano."-".$this->c141_data_mes."-".$this->c141_data_dia;
         }
       }
       $this->c141_codlayout = ($this->c141_codlayout == ""?@$GLOBALS["HTTP_POST_VARS"]["c141_codlayout"]:$this->c141_codlayout);
       $this->c141_nomearquivo = ($this->c141_nomearquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["c141_nomearquivo"]:$this->c141_nomearquivo);
       $this->c141_json = ($this->c141_json == ""?@$GLOBALS["HTTP_POST_VARS"]["c141_json"]:$this->c141_json);
     }else{
       $this->c141_sequencial = ($this->c141_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c141_sequencial"]:$this->c141_sequencial);
     }
   }

    public function incluir($c141_sequencial)
    {
      $this->atualizacampos();
     if($this->c141_usuario == null ){ 
       $this->erro_sql = " Campo Usuário não informado.";
       $this->erro_campo = "c141_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c141_data == null ){ 
       $this->erro_sql = " Campo Data de execução não informado.";
       $this->erro_campo = "c141_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c141_codlayout == null ){ 
       $this->erro_sql = " Campo Código de identificação do layout não informado.";
       $this->erro_campo = "c141_codlayout";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c141_nomearquivo == null ){ 
       $this->erro_sql = " Campo Nome do arquivo não informado.";
       $this->erro_campo = "c141_nomearquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c141_json == null ){ 
       $this->erro_sql = " Campo JSON não informado.";
       $this->erro_campo = "c141_json";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c141_sequencial == "" || $c141_sequencial == null ){
       $result = db_query("select nextval('sagresarquivogerado_c141_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sagresarquivogerado_c141_sequencial_seq do campo: c141_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c141_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sagresarquivogerado_c141_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c141_sequencial)){
         $this->erro_sql = " Campo c141_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c141_sequencial = $c141_sequencial; 
       }
     }
     if(($this->c141_sequencial == null) || ($this->c141_sequencial == "") ){ 
       $this->erro_sql = " Campo c141_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sagresarquivogerado(
                                       c141_sequencial 
                                      ,c141_usuario 
                                      ,c141_data 
                                      ,c141_codlayout 
                                      ,c141_nomearquivo 
                                      ,c141_json 
                       )
                values (
                                $this->c141_sequencial 
                               ,$this->c141_usuario 
                               ,".($this->c141_data == "null" || $this->c141_data == ""?"null":"'".$this->c141_data."'")." 
                               ,$this->c141_codlayout 
                               ,'$this->c141_nomearquivo' 
                               ,'$this->c141_json' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Arquivos gerados no SAGRES ($this->c141_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Arquivos gerados no SAGRES já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Arquivos gerados no SAGRES ($this->c141_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->c141_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->c141_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1013741,'$this->c141_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010864,1013741,'','".AddSlashes(pg_result($resaco,0,'c141_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010864,1013742,'','".AddSlashes(pg_result($resaco,0,'c141_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010864,1013743,'','".AddSlashes(pg_result($resaco,0,'c141_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010864,1013744,'','".AddSlashes(pg_result($resaco,0,'c141_codlayout'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010864,1013745,'','".AddSlashes(pg_result($resaco,0,'c141_nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010864,1013746,'','".AddSlashes(pg_result($resaco,0,'c141_json'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($c141_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update sagresarquivogerado set ";
     $virgula = "";
     if(trim($this->c141_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c141_sequencial"])){ 
       $sql  .= $virgula." c141_sequencial = $this->c141_sequencial ";
       $virgula = ",";
       if(trim($this->c141_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "c141_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c141_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c141_usuario"])){ 
       $sql  .= $virgula." c141_usuario = $this->c141_usuario ";
       $virgula = ",";
       if(trim($this->c141_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário não informado.";
         $this->erro_campo = "c141_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c141_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c141_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c141_data_dia"] !="") ){ 
       $sql  .= $virgula." c141_data = '$this->c141_data' ";
       $virgula = ",";
       if(trim($this->c141_data) == null ){ 
         $this->erro_sql = " Campo Data de execução não informado.";
         $this->erro_campo = "c141_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c141_data_dia"])){ 
         $sql  .= $virgula." c141_data = null ";
         $virgula = ",";
         if(trim($this->c141_data) == null ){ 
           $this->erro_sql = " Campo Data de execução não informado.";
           $this->erro_campo = "c141_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->c141_codlayout)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c141_codlayout"])){ 
       $sql  .= $virgula." c141_codlayout = $this->c141_codlayout ";
       $virgula = ",";
       if(trim($this->c141_codlayout) == null ){ 
         $this->erro_sql = " Campo Código de identificação do layout não informado.";
         $this->erro_campo = "c141_codlayout";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c141_nomearquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c141_nomearquivo"])){ 
       $sql  .= $virgula." c141_nomearquivo = '$this->c141_nomearquivo' ";
       $virgula = ",";
       if(trim($this->c141_nomearquivo) == null ){ 
         $this->erro_sql = " Campo Nome do arquivo não informado.";
         $this->erro_campo = "c141_nomearquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c141_json)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c141_json"])){ 
       $sql  .= $virgula." c141_json = '$this->c141_json' ";
       $virgula = ",";
       if(trim($this->c141_json) == null ){ 
         $this->erro_sql = " Campo JSON não informado.";
         $this->erro_campo = "c141_json";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c141_sequencial!=null){
       $sql .= " c141_sequencial = $this->c141_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->c141_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1013741,'$this->c141_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c141_sequencial"]) || $this->c141_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010864,1013741,'".AddSlashes(pg_result($resaco,$conresaco,'c141_sequencial'))."','$this->c141_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c141_usuario"]) || $this->c141_usuario != "")
             $resac = db_query("insert into db_acount values($acount,1010864,1013742,'".AddSlashes(pg_result($resaco,$conresaco,'c141_usuario'))."','$this->c141_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c141_data"]) || $this->c141_data != "")
             $resac = db_query("insert into db_acount values($acount,1010864,1013743,'".AddSlashes(pg_result($resaco,$conresaco,'c141_data'))."','$this->c141_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c141_codlayout"]) || $this->c141_codlayout != "")
             $resac = db_query("insert into db_acount values($acount,1010864,1013744,'".AddSlashes(pg_result($resaco,$conresaco,'c141_codlayout'))."','$this->c141_codlayout',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c141_nomearquivo"]) || $this->c141_nomearquivo != "")
             $resac = db_query("insert into db_acount values($acount,1010864,1013745,'".AddSlashes(pg_result($resaco,$conresaco,'c141_nomearquivo'))."','$this->c141_nomearquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c141_json"]) || $this->c141_json != "")
             $resac = db_query("insert into db_acount values($acount,1010864,1013746,'".AddSlashes(pg_result($resaco,$conresaco,'c141_json'))."','$this->c141_json',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivos gerados no SAGRES não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c141_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Arquivos gerados no SAGRES não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c141_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->c141_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($c141_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($c141_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1013741,'$c141_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010864,1013741,'','".AddSlashes(pg_result($resaco,$iresaco,'c141_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010864,1013742,'','".AddSlashes(pg_result($resaco,$iresaco,'c141_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010864,1013743,'','".AddSlashes(pg_result($resaco,$iresaco,'c141_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010864,1013744,'','".AddSlashes(pg_result($resaco,$iresaco,'c141_codlayout'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010864,1013745,'','".AddSlashes(pg_result($resaco,$iresaco,'c141_nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010864,1013746,'','".AddSlashes(pg_result($resaco,$iresaco,'c141_json'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from sagresarquivogerado
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($c141_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " c141_sequencial = $c141_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivos gerados no SAGRES não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c141_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Arquivos gerados no SAGRES não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c141_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$c141_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:sagresarquivogerado";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($c141_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from sagresarquivogerado ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = sagresarquivogerado.c141_usuario";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($c141_sequencial)) {
         $sql2 .= " where sagresarquivogerado.c141_sequencial = $c141_sequencial "; 
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

    public function sql_query_file($c141_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from sagresarquivogerado ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($c141_sequencial)){
         $sql2 .= " where sagresarquivogerado.c141_sequencial = $c141_sequencial "; 
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
