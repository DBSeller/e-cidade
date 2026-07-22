<?php

class cl_avaliacaogruporespostaremuneracaorgps
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
    public $eso28_sequencial = 0; 
    public $eso28_avaliacaogruporesposta = 0; 
    public $eso28_cgm = 0; 
    public $eso28_ano = 0; 
    public $eso28_mes = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 eso28_sequencial = int4 = Sequencial 
                 eso28_avaliacaogruporesposta = int4 = Avaliação Grupo Resposta 
                 eso28_cgm = int4 = CGM 
                 eso28_ano = int4 = Ano 
                 eso28_mes = int4 = Mês 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("avaliacaogruporespostaremuneracaorgps"); 
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
       $this->eso28_sequencial = ($this->eso28_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["eso28_sequencial"]:$this->eso28_sequencial);
       $this->eso28_avaliacaogruporesposta = ($this->eso28_avaliacaogruporesposta == ""?@$GLOBALS["HTTP_POST_VARS"]["eso28_avaliacaogruporesposta"]:$this->eso28_avaliacaogruporesposta);
       $this->eso28_cgm = ($this->eso28_cgm == ""?@$GLOBALS["HTTP_POST_VARS"]["eso28_cgm"]:$this->eso28_cgm);
       $this->eso28_ano = ($this->eso28_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["eso28_ano"]:$this->eso28_ano);
       $this->eso28_mes = ($this->eso28_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["eso28_mes"]:$this->eso28_mes);
     }else{
       $this->eso28_sequencial = ($this->eso28_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["eso28_sequencial"]:$this->eso28_sequencial);
     }
   }

    public function incluir($eso28_sequencial)
    {
      $this->atualizacampos();
     if($this->eso28_avaliacaogruporesposta == null ){ 
       $this->erro_sql = " Campo Avaliação Grupo Resposta não informado.";
       $this->erro_campo = "eso28_avaliacaogruporesposta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->eso28_cgm == null ){ 
       $this->erro_sql = " Campo CGM não informado.";
       $this->erro_campo = "eso28_cgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->eso28_ano == null ){ 
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "eso28_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->eso28_mes == null ){ 
       $this->erro_sql = " Campo Mês não informado.";
       $this->erro_campo = "eso28_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($eso28_sequencial == "" || $eso28_sequencial == null ){
       $result = db_query("select nextval('avaliacaogruporespostaremuneracaorgps_eso28_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: avaliacaogruporespostaremuneracaorgps_eso28_sequencial_seq do campo: eso28_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->eso28_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from avaliacaogruporespostaremuneracaorgps_eso28_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $eso28_sequencial)){
         $this->erro_sql = " Campo eso28_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->eso28_sequencial = $eso28_sequencial; 
       }
     }
     if(($this->eso28_sequencial == null) || ($this->eso28_sequencial == "") ){ 
       $this->erro_sql = " Campo eso28_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into avaliacaogruporespostaremuneracaorgps(
                                       eso28_sequencial 
                                      ,eso28_avaliacaogruporesposta 
                                      ,eso28_cgm 
                                      ,eso28_ano 
                                      ,eso28_mes 
                       )
                values (
                                $this->eso28_sequencial 
                               ,$this->eso28_avaliacaogruporesposta 
                               ,$this->eso28_cgm 
                               ,$this->eso28_ano 
                               ,$this->eso28_mes 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Remuneração RGPS eSocial ($this->eso28_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Remuneração RGPS eSocial já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Remuneração RGPS eSocial ($this->eso28_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->eso28_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->eso28_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1010192,'$this->eso28_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010356,1010192,'','".AddSlashes(pg_result($resaco,0,'eso28_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010356,1010193,'','".AddSlashes(pg_result($resaco,0,'eso28_avaliacaogruporesposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010356,1010194,'','".AddSlashes(pg_result($resaco,0,'eso28_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010356,1010195,'','".AddSlashes(pg_result($resaco,0,'eso28_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010356,1010196,'','".AddSlashes(pg_result($resaco,0,'eso28_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($eso28_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update avaliacaogruporespostaremuneracaorgps set ";
     $virgula = "";
     if(trim($this->eso28_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso28_sequencial"])){ 
       $sql  .= $virgula." eso28_sequencial = $this->eso28_sequencial ";
       $virgula = ",";
       if(trim($this->eso28_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "eso28_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->eso28_avaliacaogruporesposta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso28_avaliacaogruporesposta"])){ 
       $sql  .= $virgula." eso28_avaliacaogruporesposta = $this->eso28_avaliacaogruporesposta ";
       $virgula = ",";
       if(trim($this->eso28_avaliacaogruporesposta) == null ){ 
         $this->erro_sql = " Campo Avaliação Grupo Resposta não informado.";
         $this->erro_campo = "eso28_avaliacaogruporesposta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->eso28_cgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso28_cgm"])){ 
       $sql  .= $virgula." eso28_cgm = $this->eso28_cgm ";
       $virgula = ",";
       if(trim($this->eso28_cgm) == null ){ 
         $this->erro_sql = " Campo CGM não informado.";
         $this->erro_campo = "eso28_cgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->eso28_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso28_ano"])){ 
       $sql  .= $virgula." eso28_ano = $this->eso28_ano ";
       $virgula = ",";
       if(trim($this->eso28_ano) == null ){ 
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "eso28_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->eso28_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso28_mes"])){ 
       $sql  .= $virgula." eso28_mes = $this->eso28_mes ";
       $virgula = ",";
       if(trim($this->eso28_mes) == null ){ 
         $this->erro_sql = " Campo Mês não informado.";
         $this->erro_campo = "eso28_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($eso28_sequencial!=null){
       $sql .= " eso28_sequencial = $this->eso28_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->eso28_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1010192,'$this->eso28_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso28_sequencial"]) || $this->eso28_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010356,1010192,'".AddSlashes(pg_result($resaco,$conresaco,'eso28_sequencial'))."','$this->eso28_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso28_avaliacaogruporesposta"]) || $this->eso28_avaliacaogruporesposta != "")
             $resac = db_query("insert into db_acount values($acount,1010356,1010193,'".AddSlashes(pg_result($resaco,$conresaco,'eso28_avaliacaogruporesposta'))."','$this->eso28_avaliacaogruporesposta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso28_cgm"]) || $this->eso28_cgm != "")
             $resac = db_query("insert into db_acount values($acount,1010356,1010194,'".AddSlashes(pg_result($resaco,$conresaco,'eso28_cgm'))."','$this->eso28_cgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso28_ano"]) || $this->eso28_ano != "")
             $resac = db_query("insert into db_acount values($acount,1010356,1010195,'".AddSlashes(pg_result($resaco,$conresaco,'eso28_ano'))."','$this->eso28_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso28_mes"]) || $this->eso28_mes != "")
             $resac = db_query("insert into db_acount values($acount,1010356,1010196,'".AddSlashes(pg_result($resaco,$conresaco,'eso28_mes'))."','$this->eso28_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Remuneração RGPS eSocial não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->eso28_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Remuneração RGPS eSocial não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->eso28_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->eso28_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($eso28_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($eso28_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1010192,'$eso28_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010356,1010192,'','".AddSlashes(pg_result($resaco,$iresaco,'eso28_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010356,1010193,'','".AddSlashes(pg_result($resaco,$iresaco,'eso28_avaliacaogruporesposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010356,1010194,'','".AddSlashes(pg_result($resaco,$iresaco,'eso28_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010356,1010195,'','".AddSlashes(pg_result($resaco,$iresaco,'eso28_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010356,1010196,'','".AddSlashes(pg_result($resaco,$iresaco,'eso28_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from avaliacaogruporespostaremuneracaorgps
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($eso28_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " eso28_sequencial = $eso28_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Remuneração RGPS eSocial não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$eso28_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Remuneração RGPS eSocial não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$eso28_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$eso28_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:avaliacaogruporespostaremuneracaorgps";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($eso28_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from avaliacaogruporespostaremuneracaorgps ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = avaliacaogruporespostaremuneracaorgps.eso28_cgm";
     $sql .= "      inner join avaliacaogruporesposta  on  avaliacaogruporesposta.db107_sequencial = avaliacaogruporespostaremuneracaorgps.eso28_avaliacaogruporesposta";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($eso28_sequencial)) {
         $sql2 .= " where avaliacaogruporespostaremuneracaorgps.eso28_sequencial = $eso28_sequencial "; 
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

    public function sql_query_file($eso28_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from avaliacaogruporespostaremuneracaorgps ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($eso28_sequencial)){
         $sql2 .= " where avaliacaogruporespostaremuneracaorgps.eso28_sequencial = $eso28_sequencial "; 
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

    public function buscarRespostasPorPergunta($pergunta = null, $preenchimento = null, $campos = "*", $ordem = null, $agrupar = null)
    {
        $where = array();

        $where[] = "eso28_ano = " . DBPessoal::getAnoFolha();
        $where[] = "eso28_mes = " . DBPessoal::getMesFolha();

        if(!empty($pergunta)) {
            $where[] = "db103_sequencial = {$pergunta}";
        }

        if(!empty($preenchimento)) {
            $where[] = "db107_sequencial = {$preenchimento}";
        }

        $sql = "select {$campos}";
        $sql .= "  from avaliacaogruporespostaremuneracaorgps";
        $sql .= "      inner join avaliacaogruporesposta on db107_sequencial = eso28_avaliacaogruporesposta";
        $sql .= "      inner join avaliacaogrupoperguntaresposta on db108_avaliacaogruporesposta = db107_sequencial";
        $sql .= "      inner join avaliacaoresposta on db106_sequencial = db108_avaliacaoresposta";
        $sql .= "      inner join avaliacaoperguntaopcao on db104_sequencial = db106_avaliacaoperguntaopcao";
        $sql .= "      inner join avaliacaopergunta on db103_sequencial = db104_avaliacaopergunta";

        if(!empty($where)) {
            $sql .= " where " .  implode(' AND ', $where);
        }

        if (!empty($ordem)) {
            $sql .= " order by {$ordem}";
        }

        if(!empty($agrupar)) {
            $sql .= " group by {$agrupar}";
        }

        return $sql;
    }
}
