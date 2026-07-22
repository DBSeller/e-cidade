<?php

class cl_avaliacaogruporespostafechamentoefd
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
    public $eso32_sequencial = 0; 
    public $eso32_avaliacaogruporesposta = 0; 
    public $eso32_cgmcontribuinte = 0; 
    public $eso32_ano = 0; 
    public $eso32_mes = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 eso32_sequencial = int4 = Sequencial 
                 eso32_avaliacaogruporesposta = int4 = Código do Grupo de Resposta 
                 eso32_cgmcontribuinte = int4 = Cgm do Contribuinte 
                 eso32_ano = int4 = Ano 
                 eso32_mes = int4 = Mês 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("avaliacaogruporespostafechamentoefd"); 
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
       $this->eso32_sequencial = ($this->eso32_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["eso32_sequencial"]:$this->eso32_sequencial);
       $this->eso32_avaliacaogruporesposta = ($this->eso32_avaliacaogruporesposta == ""?@$GLOBALS["HTTP_POST_VARS"]["eso32_avaliacaogruporesposta"]:$this->eso32_avaliacaogruporesposta);
       $this->eso32_cgmcontribuinte = ($this->eso32_cgmcontribuinte == ""?@$GLOBALS["HTTP_POST_VARS"]["eso32_cgmcontribuinte"]:$this->eso32_cgmcontribuinte);
       $this->eso32_ano = ($this->eso32_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["eso32_ano"]:$this->eso32_ano);
       $this->eso32_mes = ($this->eso32_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["eso32_mes"]:$this->eso32_mes);
     }else{
       $this->eso32_sequencial = ($this->eso32_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["eso32_sequencial"]:$this->eso32_sequencial);
     }
   }

    public function incluir($eso32_sequencial)
    {
      $this->atualizacampos();
     if($this->eso32_avaliacaogruporesposta == null ){ 
       $this->erro_sql = " Campo Código do Grupo de Resposta não informado.";
       $this->erro_campo = "eso32_avaliacaogruporesposta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->eso32_cgmcontribuinte == null ){ 
       $this->erro_sql = " Campo Cgm do Contribuinte não informado.";
       $this->erro_campo = "eso32_cgmcontribuinte";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->eso32_ano == null ){ 
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "eso32_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->eso32_mes == null ){ 
       $this->erro_sql = " Campo Mês não informado.";
       $this->erro_campo = "eso32_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($eso32_sequencial == "" || $eso32_sequencial == null ){
       $result = db_query("select nextval('avaliacaogruporespostafechamentoefd_eso32_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: avaliacaogruporespostafechamentoefd_eso32_sequencial_seq do campo: eso32_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->eso32_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from avaliacaogruporespostafechamentoefd_eso32_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $eso32_sequencial)){
         $this->erro_sql = " Campo eso32_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->eso32_sequencial = $eso32_sequencial; 
       }
     }
     if(($this->eso32_sequencial == null) || ($this->eso32_sequencial == "") ){ 
       $this->erro_sql = " Campo eso32_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into avaliacaogruporespostafechamentoefd(
                                       eso32_sequencial 
                                      ,eso32_avaliacaogruporesposta 
                                      ,eso32_cgmcontribuinte 
                                      ,eso32_ano 
                                      ,eso32_mes 
                       )
                values (
                                $this->eso32_sequencial 
                               ,$this->eso32_avaliacaogruporesposta 
                               ,$this->eso32_cgmcontribuinte 
                               ,$this->eso32_ano 
                               ,$this->eso32_mes 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Vínculo entre preenchimento e fechamento do EFD ($this->eso32_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Vínculo entre preenchimento e fechamento do EFD já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Vínculo entre preenchimento e fechamento do EFD ($this->eso32_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->eso32_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->eso32_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1010280,'$this->eso32_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010403,1010280,'','".AddSlashes(pg_result($resaco,0,'eso32_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010403,1010281,'','".AddSlashes(pg_result($resaco,0,'eso32_avaliacaogruporesposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010403,1010282,'','".AddSlashes(pg_result($resaco,0,'eso32_cgmcontribuinte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010403,1010283,'','".AddSlashes(pg_result($resaco,0,'eso32_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010403,1010284,'','".AddSlashes(pg_result($resaco,0,'eso32_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($eso32_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update avaliacaogruporespostafechamentoefd set ";
     $virgula = "";
     if(trim($this->eso32_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso32_sequencial"])){ 
       $sql  .= $virgula." eso32_sequencial = $this->eso32_sequencial ";
       $virgula = ",";
       if(trim($this->eso32_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "eso32_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->eso32_avaliacaogruporesposta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso32_avaliacaogruporesposta"])){ 
       $sql  .= $virgula." eso32_avaliacaogruporesposta = $this->eso32_avaliacaogruporesposta ";
       $virgula = ",";
       if(trim($this->eso32_avaliacaogruporesposta) == null ){ 
         $this->erro_sql = " Campo Código do Grupo de Resposta não informado.";
         $this->erro_campo = "eso32_avaliacaogruporesposta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->eso32_cgmcontribuinte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso32_cgmcontribuinte"])){ 
       $sql  .= $virgula." eso32_cgmcontribuinte = $this->eso32_cgmcontribuinte ";
       $virgula = ",";
       if(trim($this->eso32_cgmcontribuinte) == null ){ 
         $this->erro_sql = " Campo Cgm do Contribuinte não informado.";
         $this->erro_campo = "eso32_cgmcontribuinte";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->eso32_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso32_ano"])){ 
       $sql  .= $virgula." eso32_ano = $this->eso32_ano ";
       $virgula = ",";
       if(trim($this->eso32_ano) == null ){ 
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "eso32_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->eso32_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso32_mes"])){ 
       $sql  .= $virgula." eso32_mes = $this->eso32_mes ";
       $virgula = ",";
       if(trim($this->eso32_mes) == null ){ 
         $this->erro_sql = " Campo Mês não informado.";
         $this->erro_campo = "eso32_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($eso32_sequencial!=null){
       $sql .= " eso32_sequencial = $this->eso32_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->eso32_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1010280,'$this->eso32_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso32_sequencial"]) || $this->eso32_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010403,1010280,'".AddSlashes(pg_result($resaco,$conresaco,'eso32_sequencial'))."','$this->eso32_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso32_avaliacaogruporesposta"]) || $this->eso32_avaliacaogruporesposta != "")
             $resac = db_query("insert into db_acount values($acount,1010403,1010281,'".AddSlashes(pg_result($resaco,$conresaco,'eso32_avaliacaogruporesposta'))."','$this->eso32_avaliacaogruporesposta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso32_cgmcontribuinte"]) || $this->eso32_cgmcontribuinte != "")
             $resac = db_query("insert into db_acount values($acount,1010403,1010282,'".AddSlashes(pg_result($resaco,$conresaco,'eso32_cgmcontribuinte'))."','$this->eso32_cgmcontribuinte',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso32_ano"]) || $this->eso32_ano != "")
             $resac = db_query("insert into db_acount values($acount,1010403,1010283,'".AddSlashes(pg_result($resaco,$conresaco,'eso32_ano'))."','$this->eso32_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso32_mes"]) || $this->eso32_mes != "")
             $resac = db_query("insert into db_acount values($acount,1010403,1010284,'".AddSlashes(pg_result($resaco,$conresaco,'eso32_mes'))."','$this->eso32_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vínculo entre preenchimento e fechamento do EFD não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->eso32_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Vínculo entre preenchimento e fechamento do EFD não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->eso32_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->eso32_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($eso32_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($eso32_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1010280,'$eso32_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010403,1010280,'','".AddSlashes(pg_result($resaco,$iresaco,'eso32_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010403,1010281,'','".AddSlashes(pg_result($resaco,$iresaco,'eso32_avaliacaogruporesposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010403,1010282,'','".AddSlashes(pg_result($resaco,$iresaco,'eso32_cgmcontribuinte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010403,1010283,'','".AddSlashes(pg_result($resaco,$iresaco,'eso32_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010403,1010284,'','".AddSlashes(pg_result($resaco,$iresaco,'eso32_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from avaliacaogruporespostafechamentoefd
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($eso32_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " eso32_sequencial = $eso32_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vínculo entre preenchimento e fechamento do EFD não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$eso32_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Vínculo entre preenchimento e fechamento do EFD não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$eso32_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$eso32_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:avaliacaogruporespostafechamentoefd";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($eso32_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from avaliacaogruporespostafechamentoefd ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = avaliacaogruporespostafechamentoefd.eso32_cgmcontribuinte";
     $sql .= "      inner join avaliacaogruporesposta  on  avaliacaogruporesposta.db107_sequencial = avaliacaogruporespostafechamentoefd.eso32_avaliacaogruporesposta";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($eso32_sequencial)) {
         $sql2 .= " where avaliacaogruporespostafechamentoefd.eso32_sequencial = $eso32_sequencial "; 
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

    public function sql_query_file($eso32_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from avaliacaogruporespostafechamentoefd ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($eso32_sequencial)){
         $sql2 .= " where avaliacaogruporespostafechamentoefd.eso32_sequencial = $eso32_sequencial "; 
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

  public function sqlDadosSugestaoContatoContribuinte($cgmcontribuinte)
  {
      $sql  = " WITH resultados AS (SELECT db103_identificadorcampo AS identificadorpergunta, db106_resposta AS resposta";
      $sql .= "                     FROM avaliacaogruporespostacontribuinte";
      $sql .= "                     INNER JOIN avaliacaogruporesposta ON db107_sequencial = eso27_avaliacaogruporesposta";
      $sql .= "                     INNER JOIN avaliacaogrupoperguntaresposta ON db108_avaliacaogruporesposta = db107_sequencial";
      $sql .= "                     INNER JOIN avaliacaoresposta ON db106_sequencial = db108_avaliacaoresposta";
      $sql .= "                     INNER JOIN avaliacaoperguntaopcao ON db104_sequencial = db106_avaliacaoperguntaopcao";
      $sql .= "                     INNER JOIN avaliacaopergunta ON db103_sequencial = db104_avaliacaopergunta";
      $sql .= "                     INNER JOIN avaliacaogrupopergunta ON db103_avaliacaogrupopergunta = db102_sequencial";
      $sql .= "                     INNER JOIN avaliacao ON db102_avaliacao = db101_sequencial";
      $sql .= "                     INNER JOIN cgm ON z01_numcgm = eso27_cgm";
      $sql .= "                     WHERE eso27_cgm = {$cgmcontribuinte}";
      $sql .= "                       AND db103_sequencial IN (3002386, 3002387, 3002388, 3002390))";
      $sql .= " SELECT ";
      $sql .= "   (SELECT resposta FROM resultados WHERE identificadorpergunta = 'nmCtt') AS nmCtt,";
      $sql .= "   (SELECT resposta FROM resultados WHERE identificadorpergunta = 'cpfCtt') AS cpfCtt,";
      $sql .= "   (SELECT resposta FROM resultados WHERE identificadorpergunta = 'foneFixo') AS foneFixo,";
      $sql .= "   (SELECT resposta FROM resultados WHERE identificadorpergunta = 'email') AS email";

      return $sql;
  }

}
