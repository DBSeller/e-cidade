<?php

class cl_rhreajustesalarialesocial
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
    public $eso39_sequencial = 0; 
    public $eso39_matricula = 0; 
    public $eso39_dataefeito_dia = null; 
    public $eso39_dataefeito_mes = null; 
    public $eso39_dataefeito_ano = null; 
    public $eso39_dataefeito = null; 
    public $eso39_tipo = null; 
    public $eso39_descricao = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 eso39_sequencial = int8 = Código Sequencial 
                 eso39_matricula = int8 = Matrícula 
                 eso39_dataefeito = date = Data Efetividade 
                 eso39_tipo = char(1) = Tipo do instrumento 
                 eso39_descricao = varchar(150) = Descrição 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhreajustesalarialesocial"); 
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
       $this->eso39_sequencial = ($this->eso39_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["eso39_sequencial"]:$this->eso39_sequencial);
       $this->eso39_matricula = ($this->eso39_matricula == ""?@$GLOBALS["HTTP_POST_VARS"]["eso39_matricula"]:$this->eso39_matricula);
       if($this->eso39_dataefeito == ""){
         $this->eso39_dataefeito_dia = ($this->eso39_dataefeito_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["eso39_dataefeito_dia"]:$this->eso39_dataefeito_dia);
         $this->eso39_dataefeito_mes = ($this->eso39_dataefeito_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["eso39_dataefeito_mes"]:$this->eso39_dataefeito_mes);
         $this->eso39_dataefeito_ano = ($this->eso39_dataefeito_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["eso39_dataefeito_ano"]:$this->eso39_dataefeito_ano);
         if($this->eso39_dataefeito_dia != ""){
            $this->eso39_dataefeito = $this->eso39_dataefeito_ano."-".$this->eso39_dataefeito_mes."-".$this->eso39_dataefeito_dia;
         }
       }
       $this->eso39_tipo = ($this->eso39_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["eso39_tipo"]:$this->eso39_tipo);
       $this->eso39_descricao = ($this->eso39_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["eso39_descricao"]:$this->eso39_descricao);
     }else{
       $this->eso39_sequencial = ($this->eso39_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["eso39_sequencial"]:$this->eso39_sequencial);
     }
   }

    public function incluir($eso39_sequencial)
    {
      $this->atualizacampos();
     if($this->eso39_matricula == null ){ 
       $this->erro_sql = " Campo Matrícula não informado.";
       $this->erro_campo = "eso39_matricula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->eso39_dataefeito == null ){ 
       $this->erro_sql = " Campo Data Efetividade não informado.";
       $this->erro_campo = "eso39_dataefeito_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->eso39_tipo == null ){ 
       $this->erro_sql = " Campo Tipo do instrumento não informado.";
       $this->erro_campo = "eso39_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($eso39_sequencial == "" || $eso39_sequencial == null ){
       $result = db_query("select nextval('rhreajustesalarialesocial_eso39_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhreajustesalarialesocial_eso39_sequencial_seq do campo: eso39_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->eso39_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhreajustesalarialesocial_eso39_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $eso39_sequencial)){
         $this->erro_sql = " Campo eso39_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->eso39_sequencial = $eso39_sequencial; 
       }
     }
     if(($this->eso39_sequencial == null) || ($this->eso39_sequencial == "") ){ 
       $this->erro_sql = " Campo eso39_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhreajustesalarialesocial(
                                       eso39_sequencial 
                                      ,eso39_matricula 
                                      ,eso39_dataefeito 
                                      ,eso39_tipo 
                                      ,eso39_descricao 
                       )
                values (
                                $this->eso39_sequencial 
                               ,$this->eso39_matricula 
                               ,".($this->eso39_dataefeito == "null" || $this->eso39_dataefeito == ""?"null":"'".$this->eso39_dataefeito."'")." 
                               ,'$this->eso39_tipo' 
                               ,'$this->eso39_descricao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Reajuste salarial eSocial ($this->eso39_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Reajuste salarial eSocial já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Reajuste salarial eSocial ($this->eso39_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->eso39_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->eso39_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1014464,'$this->eso39_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010984,1014464,'','".AddSlashes(pg_result($resaco,0,'eso39_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010984,1014465,'','".AddSlashes(pg_result($resaco,0,'eso39_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010984,1014466,'','".AddSlashes(pg_result($resaco,0,'eso39_dataefeito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010984,1014467,'','".AddSlashes(pg_result($resaco,0,'eso39_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010984,1014468,'','".AddSlashes(pg_result($resaco,0,'eso39_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($eso39_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhreajustesalarialesocial set ";
     $virgula = "";
     if(trim($this->eso39_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso39_sequencial"])){ 
       $sql  .= $virgula." eso39_sequencial = $this->eso39_sequencial ";
       $virgula = ",";
       if(trim($this->eso39_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial não informado.";
         $this->erro_campo = "eso39_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->eso39_matricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso39_matricula"])){ 
       $sql  .= $virgula." eso39_matricula = $this->eso39_matricula ";
       $virgula = ",";
       if(trim($this->eso39_matricula) == null ){ 
         $this->erro_sql = " Campo Matrícula não informado.";
         $this->erro_campo = "eso39_matricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->eso39_dataefeito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso39_dataefeito_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["eso39_dataefeito_dia"] !="") ){ 
       $sql  .= $virgula." eso39_dataefeito = '$this->eso39_dataefeito' ";
       $virgula = ",";
       if(trim($this->eso39_dataefeito) == null ){ 
         $this->erro_sql = " Campo Data Efetividade não informado.";
         $this->erro_campo = "eso39_dataefeito_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["eso39_dataefeito_dia"])){ 
         $sql  .= $virgula." eso39_dataefeito = null ";
         $virgula = ",";
         if(trim($this->eso39_dataefeito) == null ){ 
           $this->erro_sql = " Campo Data Efetividade não informado.";
           $this->erro_campo = "eso39_dataefeito_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->eso39_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso39_tipo"])){ 
       $sql  .= $virgula." eso39_tipo = '$this->eso39_tipo' ";
       $virgula = ",";
       if(trim($this->eso39_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo do instrumento não informado.";
         $this->erro_campo = "eso39_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->eso39_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso39_descricao"])){ 
       $sql  .= $virgula." eso39_descricao = '$this->eso39_descricao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($eso39_sequencial!=null){
       $sql .= " eso39_sequencial = $this->eso39_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->eso39_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1014464,'$this->eso39_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso39_sequencial"]) || $this->eso39_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010984,1014464,'".AddSlashes(pg_result($resaco,$conresaco,'eso39_sequencial'))."','$this->eso39_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso39_matricula"]) || $this->eso39_matricula != "")
             $resac = db_query("insert into db_acount values($acount,1010984,1014465,'".AddSlashes(pg_result($resaco,$conresaco,'eso39_matricula'))."','$this->eso39_matricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso39_dataefeito"]) || $this->eso39_dataefeito != "")
             $resac = db_query("insert into db_acount values($acount,1010984,1014466,'".AddSlashes(pg_result($resaco,$conresaco,'eso39_dataefeito'))."','$this->eso39_dataefeito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso39_tipo"]) || $this->eso39_tipo != "")
             $resac = db_query("insert into db_acount values($acount,1010984,1014467,'".AddSlashes(pg_result($resaco,$conresaco,'eso39_tipo'))."','$this->eso39_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso39_descricao"]) || $this->eso39_descricao != "")
             $resac = db_query("insert into db_acount values($acount,1010984,1014468,'".AddSlashes(pg_result($resaco,$conresaco,'eso39_descricao'))."','$this->eso39_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Reajuste salarial eSocial não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->eso39_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Reajuste salarial eSocial não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->eso39_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->eso39_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($eso39_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($eso39_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1014464,'$eso39_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010984,1014464,'','".AddSlashes(pg_result($resaco,$iresaco,'eso39_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010984,1014465,'','".AddSlashes(pg_result($resaco,$iresaco,'eso39_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010984,1014466,'','".AddSlashes(pg_result($resaco,$iresaco,'eso39_dataefeito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010984,1014467,'','".AddSlashes(pg_result($resaco,$iresaco,'eso39_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010984,1014468,'','".AddSlashes(pg_result($resaco,$iresaco,'eso39_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhreajustesalarialesocial
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($eso39_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " eso39_sequencial = $eso39_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Reajuste salarial eSocial não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$eso39_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Reajuste salarial eSocial não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$eso39_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$eso39_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhreajustesalarialesocial";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($eso39_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhreajustesalarialesocial ";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = rhreajustesalarialesocial.eso39_matricula";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      inner join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      inner join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      left  join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao and  rhfuncao.rh37_instit = rhpessoal.rh01_instit";
     $sql .= "      inner join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     $sql .= "      left  join rhsindicato  on  rhsindicato.rh116_sequencial = rhpessoal.rh01_rhsindicato";
     $sql .= "      inner join rhreajusteparidade  on  rhreajusteparidade.rh148_sequencial = rhpessoal.rh01_reajusteparidade";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($eso39_sequencial)) {
         $sql2 .= " where rhreajustesalarialesocial.eso39_sequencial = $eso39_sequencial "; 
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

    public function sql_query_file($eso39_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhreajustesalarialesocial ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($eso39_sequencial)){
         $sql2 .= " where rhreajustesalarialesocial.eso39_sequencial = $eso39_sequencial "; 
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
