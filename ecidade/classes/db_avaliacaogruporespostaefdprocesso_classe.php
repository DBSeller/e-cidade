<?php

class cl_avaliacaogruporespostaefdprocesso
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
    public $efd02_sequencial = 0; 
    public $efd02_cgm = 0; 
    public $efd02_processo = null; 
    public $efd02_tipoprocesso = 0; 
    public $efd02_avaliacaogruporesposta = 0; 
    public $efd02_avaliacao = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 efd02_sequencial = int4 = Código 
                 efd02_cgm = int4 = CGM 
                 efd02_processo = varchar(100) = Processo 
                 efd02_tipoprocesso = int4 = Tipo de processo 
                 efd02_avaliacaogruporesposta = int4 = Preenchimento 
                 efd02_avaliacao = int4 = Avaliação 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("avaliacaogruporespostaefdprocesso"); 
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
       $this->efd02_sequencial = ($this->efd02_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["efd02_sequencial"]:$this->efd02_sequencial);
       $this->efd02_cgm = ($this->efd02_cgm == ""?@$GLOBALS["HTTP_POST_VARS"]["efd02_cgm"]:$this->efd02_cgm);
       $this->efd02_processo = ($this->efd02_processo == ""?@$GLOBALS["HTTP_POST_VARS"]["efd02_processo"]:$this->efd02_processo);
       $this->efd02_tipoprocesso = ($this->efd02_tipoprocesso == ""?@$GLOBALS["HTTP_POST_VARS"]["efd02_tipoprocesso"]:$this->efd02_tipoprocesso);
       $this->efd02_avaliacaogruporesposta = ($this->efd02_avaliacaogruporesposta == ""?@$GLOBALS["HTTP_POST_VARS"]["efd02_avaliacaogruporesposta"]:$this->efd02_avaliacaogruporesposta);
       $this->efd02_avaliacao = ($this->efd02_avaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["efd02_avaliacao"]:$this->efd02_avaliacao);
     }else{
       $this->efd02_sequencial = ($this->efd02_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["efd02_sequencial"]:$this->efd02_sequencial);
     }
   }

    public function incluir($efd02_sequencial)
    {
      $this->atualizacampos();
     if($this->efd02_cgm == null ){ 
       $this->erro_sql = " Campo CGM não informado.";
       $this->erro_campo = "efd02_cgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->efd02_processo == null ){ 
       $this->erro_sql = " Campo Processo não informado.";
       $this->erro_campo = "efd02_processo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->efd02_tipoprocesso == null ){ 
       $this->erro_sql = " Campo Tipo de processo não informado.";
       $this->erro_campo = "efd02_tipoprocesso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->efd02_avaliacaogruporesposta == null ){ 
       $this->erro_sql = " Campo Preenchimento não informado.";
       $this->erro_campo = "efd02_avaliacaogruporesposta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->efd02_avaliacao == null ){ 
       $this->erro_sql = " Campo Avaliação não informado.";
       $this->erro_campo = "efd02_avaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($efd02_sequencial == "" || $efd02_sequencial == null ){
       $result = db_query("select nextval('avaliacaogruporespostaefdprocesso_efd02_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: avaliacaogruporespostaefdprocesso_efd02_sequencial_seq do campo: efd02_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->efd02_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from avaliacaogruporespostaefdprocesso_efd02_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $efd02_sequencial)){
         $this->erro_sql = " Campo efd02_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->efd02_sequencial = $efd02_sequencial; 
       }
     }
     if(($this->efd02_sequencial == null) || ($this->efd02_sequencial == "") ){ 
       $this->erro_sql = " Campo efd02_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into avaliacaogruporespostaefdprocesso(
                                       efd02_sequencial 
                                      ,efd02_cgm 
                                      ,efd02_processo 
                                      ,efd02_tipoprocesso 
                                      ,efd02_avaliacaogruporesposta 
                                      ,efd02_avaliacao 
                       )
                values (
                                $this->efd02_sequencial 
                               ,$this->efd02_cgm 
                               ,'$this->efd02_processo' 
                               ,$this->efd02_tipoprocesso 
                               ,$this->efd02_avaliacaogruporesposta 
                               ,$this->efd02_avaliacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->efd02_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->efd02_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->efd02_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->efd02_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1010197,'$this->efd02_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010357,1010197,'','".AddSlashes(pg_result($resaco,0,'efd02_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010357,1010198,'','".AddSlashes(pg_result($resaco,0,'efd02_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010357,1010199,'','".AddSlashes(pg_result($resaco,0,'efd02_processo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010357,1010200,'','".AddSlashes(pg_result($resaco,0,'efd02_tipoprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010357,1010201,'','".AddSlashes(pg_result($resaco,0,'efd02_avaliacaogruporesposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010357,1010202,'','".AddSlashes(pg_result($resaco,0,'efd02_avaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($efd02_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update avaliacaogruporespostaefdprocesso set ";
     $virgula = "";
     if(trim($this->efd02_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["efd02_sequencial"])){ 
       $sql  .= $virgula." efd02_sequencial = $this->efd02_sequencial ";
       $virgula = ",";
       if(trim($this->efd02_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "efd02_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->efd02_cgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["efd02_cgm"])){ 
       $sql  .= $virgula." efd02_cgm = $this->efd02_cgm ";
       $virgula = ",";
       if(trim($this->efd02_cgm) == null ){ 
         $this->erro_sql = " Campo CGM não informado.";
         $this->erro_campo = "efd02_cgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->efd02_processo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["efd02_processo"])){ 
       $sql  .= $virgula." efd02_processo = '$this->efd02_processo' ";
       $virgula = ",";
       if(trim($this->efd02_processo) == null ){ 
         $this->erro_sql = " Campo Processo não informado.";
         $this->erro_campo = "efd02_processo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->efd02_tipoprocesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["efd02_tipoprocesso"])){ 
       $sql  .= $virgula." efd02_tipoprocesso = $this->efd02_tipoprocesso ";
       $virgula = ",";
       if(trim($this->efd02_tipoprocesso) == null ){ 
         $this->erro_sql = " Campo Tipo de processo não informado.";
         $this->erro_campo = "efd02_tipoprocesso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->efd02_avaliacaogruporesposta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["efd02_avaliacaogruporesposta"])){ 
       $sql  .= $virgula." efd02_avaliacaogruporesposta = $this->efd02_avaliacaogruporesposta ";
       $virgula = ",";
       if(trim($this->efd02_avaliacaogruporesposta) == null ){ 
         $this->erro_sql = " Campo Preenchimento não informado.";
         $this->erro_campo = "efd02_avaliacaogruporesposta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->efd02_avaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["efd02_avaliacao"])){ 
       $sql  .= $virgula." efd02_avaliacao = $this->efd02_avaliacao ";
       $virgula = ",";
       if(trim($this->efd02_avaliacao) == null ){ 
         $this->erro_sql = " Campo Avaliação não informado.";
         $this->erro_campo = "efd02_avaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($efd02_sequencial!=null){
       $sql .= " efd02_sequencial = $this->efd02_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->efd02_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1010197,'$this->efd02_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["efd02_sequencial"]) || $this->efd02_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010357,1010197,'".AddSlashes(pg_result($resaco,$conresaco,'efd02_sequencial'))."','$this->efd02_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["efd02_cgm"]) || $this->efd02_cgm != "")
             $resac = db_query("insert into db_acount values($acount,1010357,1010198,'".AddSlashes(pg_result($resaco,$conresaco,'efd02_cgm'))."','$this->efd02_cgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["efd02_processo"]) || $this->efd02_processo != "")
             $resac = db_query("insert into db_acount values($acount,1010357,1010199,'".AddSlashes(pg_result($resaco,$conresaco,'efd02_processo'))."','$this->efd02_processo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["efd02_tipoprocesso"]) || $this->efd02_tipoprocesso != "")
             $resac = db_query("insert into db_acount values($acount,1010357,1010200,'".AddSlashes(pg_result($resaco,$conresaco,'efd02_tipoprocesso'))."','$this->efd02_tipoprocesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["efd02_avaliacaogruporesposta"]) || $this->efd02_avaliacaogruporesposta != "")
             $resac = db_query("insert into db_acount values($acount,1010357,1010201,'".AddSlashes(pg_result($resaco,$conresaco,'efd02_avaliacaogruporesposta'))."','$this->efd02_avaliacaogruporesposta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["efd02_avaliacao"]) || $this->efd02_avaliacao != "")
             $resac = db_query("insert into db_acount values($acount,1010357,1010202,'".AddSlashes(pg_result($resaco,$conresaco,'efd02_avaliacao'))."','$this->efd02_avaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->efd02_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = " não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->efd02_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->efd02_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($efd02_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($efd02_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1010197,'$efd02_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010357,1010197,'','".AddSlashes(pg_result($resaco,$iresaco,'efd02_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010357,1010198,'','".AddSlashes(pg_result($resaco,$iresaco,'efd02_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010357,1010199,'','".AddSlashes(pg_result($resaco,$iresaco,'efd02_processo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010357,1010200,'','".AddSlashes(pg_result($resaco,$iresaco,'efd02_tipoprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010357,1010201,'','".AddSlashes(pg_result($resaco,$iresaco,'efd02_avaliacaogruporesposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010357,1010202,'','".AddSlashes(pg_result($resaco,$iresaco,'efd02_avaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from avaliacaogruporespostaefdprocesso
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($efd02_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " efd02_sequencial = $efd02_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$efd02_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = " não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$efd02_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$efd02_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:avaliacaogruporespostaefdprocesso";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($efd02_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from avaliacaogruporespostaefdprocesso ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = avaliacaogruporespostaefdprocesso.efd02_cgm";
     $sql .= "      inner join avaliacao  on  avaliacao.db101_sequencial = avaliacaogruporespostaefdprocesso.efd02_avaliacao";
     $sql .= "      inner join avaliacaogruporesposta  on  avaliacaogruporesposta.db107_sequencial = avaliacaogruporespostaefdprocesso.efd02_avaliacaogruporesposta";
     $sql .= "      inner join avaliacaotipo  on  avaliacaotipo.db100_sequencial = avaliacao.db101_avaliacaotipo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($efd02_sequencial)) {
         $sql2 .= " where avaliacaogruporespostaefdprocesso.efd02_sequencial = $efd02_sequencial "; 
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

    public function sql_query_file($efd02_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from avaliacaogruporespostaefdprocesso ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($efd02_sequencial)){
         $sql2 .= " where avaliacaogruporespostaefdprocesso.efd02_sequencial = $efd02_sequencial "; 
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
