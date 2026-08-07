<?php

class cl_rhpessoalprocessoestatutario
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
    public $rh278_sequencial = 0; 
    public $rh278_sequencialprocessovinculo = 0; 
    public $rh278_tplnsc = 0; 
    public $rh278_nrlnsc = null; 
    public $rh278_matricant = null; 
    public $rh278_dttransf_dia = null; 
    public $rh278_dttransf_mes = null; 
    public $rh278_dttransf_ano = null; 
    public $rh278_dttransf = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh278_sequencial = int4 = Número Sequencial 
                 rh278_sequencialprocessovinculo = int4 = Processo vínculo 
                 rh278_tplnsc = int4 = Tipo de inscrição 
                 rh278_nrlnsc = varchar(14) = Inscrição do empregador 
                 rh278_matricant = varchar(30) = Matrícula Anterior 
                 rh278_dttransf = date = Data da transferência 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhpessoalprocessoestatutario"); 
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
       $this->rh278_sequencial = ($this->rh278_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh278_sequencial"]:$this->rh278_sequencial);
       $this->rh278_sequencialprocessovinculo = ($this->rh278_sequencialprocessovinculo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh278_sequencialprocessovinculo"]:$this->rh278_sequencialprocessovinculo);
       $this->rh278_tplnsc = ($this->rh278_tplnsc == ""?@$GLOBALS["HTTP_POST_VARS"]["rh278_tplnsc"]:$this->rh278_tplnsc);
       $this->rh278_nrlnsc = ($this->rh278_nrlnsc == ""?@$GLOBALS["HTTP_POST_VARS"]["rh278_nrlnsc"]:$this->rh278_nrlnsc);
       $this->rh278_matricant = ($this->rh278_matricant == ""?@$GLOBALS["HTTP_POST_VARS"]["rh278_matricant"]:$this->rh278_matricant);
       if($this->rh278_dttransf == ""){
         $this->rh278_dttransf_dia = ($this->rh278_dttransf_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh278_dttransf_dia"]:$this->rh278_dttransf_dia);
         $this->rh278_dttransf_mes = ($this->rh278_dttransf_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh278_dttransf_mes"]:$this->rh278_dttransf_mes);
         $this->rh278_dttransf_ano = ($this->rh278_dttransf_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh278_dttransf_ano"]:$this->rh278_dttransf_ano);
         if($this->rh278_dttransf_dia != ""){
            $this->rh278_dttransf = $this->rh278_dttransf_ano."-".$this->rh278_dttransf_mes."-".$this->rh278_dttransf_dia;
         }
       }
     }else{
       $this->rh278_sequencial = ($this->rh278_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh278_sequencial"]:$this->rh278_sequencial);
     }
   }

    public function incluir($rh278_sequencial)
    {
      $this->atualizacampos();
     if($this->rh278_sequencialprocessovinculo == null ){ 
       $this->erro_sql = " Campo Processo vínculo não informado.";
       $this->erro_campo = "rh278_sequencialprocessovinculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh278_tplnsc == null ){ 
       $this->erro_sql = " Campo Tipo de inscrição não informado.";
       $this->erro_campo = "rh278_tplnsc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh278_nrlnsc == null ){ 
       $this->erro_sql = " Campo Inscrição do empregador não informado.";
       $this->erro_campo = "rh278_nrlnsc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh278_matricant == null ){ 
       $this->erro_sql = " Campo Matrícula Anterior não informado.";
       $this->erro_campo = "rh278_matricant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh278_dttransf == null ){ 
       $this->rh278_dttransf = "null";
     }
     if($rh278_sequencial == "" || $rh278_sequencial == null ){
       $result = db_query("select nextval('rhpessoalprocessoestatutario_rh278_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhpessoalprocessoestatutario_rh278_sequencial_seq do campo: rh278_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh278_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhpessoalprocessoestatutario_rh278_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh278_sequencial)){
         $this->erro_sql = " Campo rh278_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh278_sequencial = $rh278_sequencial; 
       }
     }
     if(($this->rh278_sequencial == null) || ($this->rh278_sequencial == "") ){ 
       $this->erro_sql = " Campo rh278_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpessoalprocessoestatutario(
                                       rh278_sequencial 
                                      ,rh278_sequencialprocessovinculo 
                                      ,rh278_tplnsc 
                                      ,rh278_nrlnsc 
                                      ,rh278_matricant 
                                      ,rh278_dttransf 
                       )
                values (
                                $this->rh278_sequencial 
                               ,$this->rh278_sequencialprocessovinculo 
                               ,$this->rh278_tplnsc 
                               ,'$this->rh278_nrlnsc' 
                               ,'$this->rh278_matricant' 
                               ,".($this->rh278_dttransf == "null" || $this->rh278_dttransf == ""?"null":"'".$this->rh278_dttransf."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Sucessão de vínculo ($this->rh278_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Sucessão de vínculo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Sucessão de vínculo ($this->rh278_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh278_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh278_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1014877,'$this->rh278_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1011042,1014877,'','".AddSlashes(pg_result($resaco,0,'rh278_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011042,1014878,'','".AddSlashes(pg_result($resaco,0,'rh278_sequencialprocessovinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011042,1014879,'','".AddSlashes(pg_result($resaco,0,'rh278_tplnsc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011042,1014880,'','".AddSlashes(pg_result($resaco,0,'rh278_nrlnsc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011042,1014881,'','".AddSlashes(pg_result($resaco,0,'rh278_matricant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011042,1014882,'','".AddSlashes(pg_result($resaco,0,'rh278_dttransf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh278_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhpessoalprocessoestatutario set ";
     $virgula = "";
     if(trim($this->rh278_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh278_sequencial"])){ 
       $sql  .= $virgula." rh278_sequencial = $this->rh278_sequencial ";
       $virgula = ",";
       if(trim($this->rh278_sequencial) == null ){ 
         $this->erro_sql = " Campo Número Sequencial não informado.";
         $this->erro_campo = "rh278_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh278_sequencialprocessovinculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh278_sequencialprocessovinculo"])){ 
       $sql  .= $virgula." rh278_sequencialprocessovinculo = $this->rh278_sequencialprocessovinculo ";
       $virgula = ",";
       if(trim($this->rh278_sequencialprocessovinculo) == null ){ 
         $this->erro_sql = " Campo Processo vínculo não informado.";
         $this->erro_campo = "rh278_sequencialprocessovinculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh278_tplnsc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh278_tplnsc"])){ 
       $sql  .= $virgula." rh278_tplnsc = $this->rh278_tplnsc ";
       $virgula = ",";
       if(trim($this->rh278_tplnsc) == null ){ 
         $this->erro_sql = " Campo Tipo de inscrição não informado.";
         $this->erro_campo = "rh278_tplnsc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh278_nrlnsc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh278_nrlnsc"])){ 
       $sql  .= $virgula." rh278_nrlnsc = '$this->rh278_nrlnsc' ";
       $virgula = ",";
       if(trim($this->rh278_nrlnsc) == null ){ 
         $this->erro_sql = " Campo Inscrição do empregador não informado.";
         $this->erro_campo = "rh278_nrlnsc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh278_matricant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh278_matricant"])){ 
       $sql  .= $virgula." rh278_matricant = '$this->rh278_matricant' ";
       $virgula = ",";
       if(trim($this->rh278_matricant) == null ){ 
         $this->erro_sql = " Campo Matrícula Anterior não informado.";
         $this->erro_campo = "rh278_matricant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh278_dttransf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh278_dttransf_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh278_dttransf_dia"] !="") ){ 
       $sql  .= $virgula." rh278_dttransf = '$this->rh278_dttransf' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh278_dttransf_dia"])){ 
         $sql  .= $virgula." rh278_dttransf = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($rh278_sequencial!=null){
       $sql .= " rh278_sequencial = $this->rh278_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh278_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1014877,'$this->rh278_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh278_sequencial"]) || $this->rh278_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1011042,1014877,'".AddSlashes(pg_result($resaco,$conresaco,'rh278_sequencial'))."','$this->rh278_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh278_sequencialprocessovinculo"]) || $this->rh278_sequencialprocessovinculo != "")
             $resac = db_query("insert into db_acount values($acount,1011042,1014878,'".AddSlashes(pg_result($resaco,$conresaco,'rh278_sequencialprocessovinculo'))."','$this->rh278_sequencialprocessovinculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh278_tplnsc"]) || $this->rh278_tplnsc != "")
             $resac = db_query("insert into db_acount values($acount,1011042,1014879,'".AddSlashes(pg_result($resaco,$conresaco,'rh278_tplnsc'))."','$this->rh278_tplnsc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh278_nrlnsc"]) || $this->rh278_nrlnsc != "")
             $resac = db_query("insert into db_acount values($acount,1011042,1014880,'".AddSlashes(pg_result($resaco,$conresaco,'rh278_nrlnsc'))."','$this->rh278_nrlnsc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh278_matricant"]) || $this->rh278_matricant != "")
             $resac = db_query("insert into db_acount values($acount,1011042,1014881,'".AddSlashes(pg_result($resaco,$conresaco,'rh278_matricant'))."','$this->rh278_matricant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh278_dttransf"]) || $this->rh278_dttransf != "")
             $resac = db_query("insert into db_acount values($acount,1011042,1014882,'".AddSlashes(pg_result($resaco,$conresaco,'rh278_dttransf'))."','$this->rh278_dttransf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Sucessão de vínculo não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh278_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Sucessão de vínculo não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh278_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh278_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh278_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh278_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1014877,'$rh278_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1011042,1014877,'','".AddSlashes(pg_result($resaco,$iresaco,'rh278_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011042,1014878,'','".AddSlashes(pg_result($resaco,$iresaco,'rh278_sequencialprocessovinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011042,1014879,'','".AddSlashes(pg_result($resaco,$iresaco,'rh278_tplnsc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011042,1014880,'','".AddSlashes(pg_result($resaco,$iresaco,'rh278_nrlnsc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011042,1014881,'','".AddSlashes(pg_result($resaco,$iresaco,'rh278_matricant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011042,1014882,'','".AddSlashes(pg_result($resaco,$iresaco,'rh278_dttransf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhpessoalprocessoestatutario
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh278_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh278_sequencial = $rh278_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Sucessão de vínculo não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh278_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Sucessão de vínculo não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh278_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh278_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpessoalprocessoestatutario";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh278_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhpessoalprocessoestatutario ";
     $sql .= "      inner join rhpessoalprocessovinculo  on  rhpessoalprocessovinculo.rh274_sequencial = rhpessoalprocessoestatutario.rh278_sequencialprocessovinculo";
     $sql .= "      inner join rhpessoalprocessoservidor  on  rhpessoalprocessoservidor.rh271_sequencial = rhpessoalprocessovinculo.rh274_sequencialprocessoservidor";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh278_sequencial)) {
         $sql2 .= " where rhpessoalprocessoestatutario.rh278_sequencial = $rh278_sequencial "; 
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

    public function sql_query_file($rh278_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhpessoalprocessoestatutario ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh278_sequencial)){
         $sql2 .= " where rhpessoalprocessoestatutario.rh278_sequencial = $rh278_sequencial "; 
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
