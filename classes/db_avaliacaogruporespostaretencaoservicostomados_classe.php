<?php

class cl_avaliacaogruporespostaretencaoservicostomados
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
    public $efd04_sequencial = 0; 
    public $efd04_avaliacaogruporesposta = 0; 
    public $efd04_cgmcontribuinte = 0; 
    public $efd04_cgmprestador = 0; 
    public $efd04_ano = 0; 
    public $efd04_mes = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 efd04_sequencial = int4 = Sequencial 
                 efd04_avaliacaogruporesposta = int4 = Avaliacao Gupo Resposta 
                 efd04_cgmcontribuinte = int4 = Cgm do contribuinte 
                 efd04_cgmprestador = int4 = Cgm Prestador 
                 efd04_ano = int4 = ano 
                 efd04_mes = int4 = Mes 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("avaliacaogruporespostaretencaoservicostomados"); 
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
       $this->efd04_sequencial = ($this->efd04_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["efd04_sequencial"]:$this->efd04_sequencial);
       $this->efd04_avaliacaogruporesposta = ($this->efd04_avaliacaogruporesposta == ""?@$GLOBALS["HTTP_POST_VARS"]["efd04_avaliacaogruporesposta"]:$this->efd04_avaliacaogruporesposta);
       $this->efd04_cgmcontribuinte = ($this->efd04_cgmcontribuinte == ""?@$GLOBALS["HTTP_POST_VARS"]["efd04_cgmcontribuinte"]:$this->efd04_cgmcontribuinte);
       $this->efd04_cgmprestador = ($this->efd04_cgmprestador == ""?@$GLOBALS["HTTP_POST_VARS"]["efd04_cgmprestador"]:$this->efd04_cgmprestador);
       $this->efd04_ano = ($this->efd04_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["efd04_ano"]:$this->efd04_ano);
       $this->efd04_mes = ($this->efd04_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["efd04_mes"]:$this->efd04_mes);
     }else{
       $this->efd04_sequencial = ($this->efd04_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["efd04_sequencial"]:$this->efd04_sequencial);
     }
   }

    public function incluir($efd04_sequencial)
    {
      $this->atualizacampos();
     if($this->efd04_avaliacaogruporesposta == null ){ 
       $this->erro_sql = " Campo Avaliacao Gupo Resposta não informado.";
       $this->erro_campo = "efd04_avaliacaogruporesposta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->efd04_cgmcontribuinte == null ){ 
       $this->erro_sql = " Campo Cgm do contribuinte não informado.";
       $this->erro_campo = "efd04_cgmcontribuinte";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->efd04_cgmprestador == null ){ 
       $this->erro_sql = " Campo Cgm Prestador não informado.";
       $this->erro_campo = "efd04_cgmprestador";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->efd04_ano == null ){ 
       $this->erro_sql = " Campo ano não informado.";
       $this->erro_campo = "efd04_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->efd04_mes == null ){ 
       $this->erro_sql = " Campo Mes não informado.";
       $this->erro_campo = "efd04_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }    
     $sql = "insert into avaliacaogruporespostaretencaoservicostomados(
                                       efd04_sequencial 
                                      ,efd04_avaliacaogruporesposta 
                                      ,efd04_cgmcontribuinte 
                                      ,efd04_cgmprestador 
                                      ,efd04_ano 
                                      ,efd04_mes 
                       )
                values (
                                nextval('avaliacaogruporespostaretencaoservicostomados_efd04_sequencial_seq')
                               ,$this->efd04_avaliacaogruporesposta 
                               ,$this->efd04_cgmcontribuinte 
                               ,$this->efd04_cgmprestador 
                               ,$this->efd04_ano 
                               ,$this->efd04_mes 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Retenção de serviços tomados ($this->efd04_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Retenção de serviços tomados já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Retenção de serviços tomados ($this->efd04_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->efd04_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->efd04_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1010237,'$this->efd04_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010364,1010237,'','".AddSlashes(pg_result($resaco,0,'efd04_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010364,1010238,'','".AddSlashes(pg_result($resaco,0,'efd04_avaliacaogruporesposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010364,1010239,'','".AddSlashes(pg_result($resaco,0,'efd04_cgmcontribuinte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010364,1010240,'','".AddSlashes(pg_result($resaco,0,'efd04_cgmprestador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010364,1010241,'','".AddSlashes(pg_result($resaco,0,'efd04_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010364,1010242,'','".AddSlashes(pg_result($resaco,0,'efd04_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($efd04_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update avaliacaogruporespostaretencaoservicostomados set ";
     $virgula = "";
     if(trim($this->efd04_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["efd04_sequencial"])){ 
       $sql  .= $virgula." efd04_sequencial = $this->efd04_sequencial ";
       $virgula = ",";
       if(trim($this->efd04_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "efd04_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->efd04_avaliacaogruporesposta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["efd04_avaliacaogruporesposta"])){ 
       $sql  .= $virgula." efd04_avaliacaogruporesposta = $this->efd04_avaliacaogruporesposta ";
       $virgula = ",";
       if(trim($this->efd04_avaliacaogruporesposta) == null ){ 
         $this->erro_sql = " Campo Avaliacao Gupo Resposta não informado.";
         $this->erro_campo = "efd04_avaliacaogruporesposta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->efd04_cgmcontribuinte)!="" || isset($GLOBALS["HTTP_POST_VARS"]["efd04_cgmcontribuinte"])){ 
       $sql  .= $virgula." efd04_cgmcontribuinte = $this->efd04_cgmcontribuinte ";
       $virgula = ",";
       if(trim($this->efd04_cgmcontribuinte) == null ){ 
         $this->erro_sql = " Campo Cgm do contribuinte não informado.";
         $this->erro_campo = "efd04_cgmcontribuinte";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->efd04_cgmprestador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["efd04_cgmprestador"])){ 
       $sql  .= $virgula." efd04_cgmprestador = $this->efd04_cgmprestador ";
       $virgula = ",";
       if(trim($this->efd04_cgmprestador) == null ){ 
         $this->erro_sql = " Campo Cgm Prestador não informado.";
         $this->erro_campo = "efd04_cgmprestador";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->efd04_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["efd04_ano"])){ 
       $sql  .= $virgula." efd04_ano = $this->efd04_ano ";
       $virgula = ",";
       if(trim($this->efd04_ano) == null ){ 
         $this->erro_sql = " Campo ano não informado.";
         $this->erro_campo = "efd04_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->efd04_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["efd04_mes"])){ 
       $sql  .= $virgula." efd04_mes = $this->efd04_mes ";
       $virgula = ",";
       if(trim($this->efd04_mes) == null ){ 
         $this->erro_sql = " Campo Mes não informado.";
         $this->erro_campo = "efd04_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($efd04_sequencial!=null){
       $sql .= " efd04_sequencial = $this->efd04_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->efd04_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1010237,'$this->efd04_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["efd04_sequencial"]) || $this->efd04_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010364,1010237,'".AddSlashes(pg_result($resaco,$conresaco,'efd04_sequencial'))."','$this->efd04_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["efd04_avaliacaogruporesposta"]) || $this->efd04_avaliacaogruporesposta != "")
             $resac = db_query("insert into db_acount values($acount,1010364,1010238,'".AddSlashes(pg_result($resaco,$conresaco,'efd04_avaliacaogruporesposta'))."','$this->efd04_avaliacaogruporesposta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["efd04_cgmcontribuinte"]) || $this->efd04_cgmcontribuinte != "")
             $resac = db_query("insert into db_acount values($acount,1010364,1010239,'".AddSlashes(pg_result($resaco,$conresaco,'efd04_cgmcontribuinte'))."','$this->efd04_cgmcontribuinte',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["efd04_cgmprestador"]) || $this->efd04_cgmprestador != "")
             $resac = db_query("insert into db_acount values($acount,1010364,1010240,'".AddSlashes(pg_result($resaco,$conresaco,'efd04_cgmprestador'))."','$this->efd04_cgmprestador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["efd04_ano"]) || $this->efd04_ano != "")
             $resac = db_query("insert into db_acount values($acount,1010364,1010241,'".AddSlashes(pg_result($resaco,$conresaco,'efd04_ano'))."','$this->efd04_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["efd04_mes"]) || $this->efd04_mes != "")
             $resac = db_query("insert into db_acount values($acount,1010364,1010242,'".AddSlashes(pg_result($resaco,$conresaco,'efd04_mes'))."','$this->efd04_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Retenção de serviços tomados não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->efd04_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Retenção de serviços tomados não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->efd04_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->efd04_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($efd04_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($efd04_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1010237,'$efd04_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010364,1010237,'','".AddSlashes(pg_result($resaco,$iresaco,'efd04_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010364,1010238,'','".AddSlashes(pg_result($resaco,$iresaco,'efd04_avaliacaogruporesposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010364,1010239,'','".AddSlashes(pg_result($resaco,$iresaco,'efd04_cgmcontribuinte'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010364,1010240,'','".AddSlashes(pg_result($resaco,$iresaco,'efd04_cgmprestador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010364,1010241,'','".AddSlashes(pg_result($resaco,$iresaco,'efd04_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010364,1010242,'','".AddSlashes(pg_result($resaco,$iresaco,'efd04_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from avaliacaogruporespostaretencaoservicostomados
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($efd04_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " efd04_sequencial = $efd04_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Retenção de serviços tomados não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$efd04_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Retenção de serviços tomados não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$efd04_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$efd04_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:avaliacaogruporespostaretencaoservicostomados";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($efd04_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from avaliacaogruporespostaretencaoservicostomados ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($efd04_sequencial)) {
         $sql2 .= " where avaliacaogruporespostaretencaoservicostomados.efd04_sequencial = $efd04_sequencial "; 
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

    public function sql_query_file($efd04_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from avaliacaogruporespostaretencaoservicostomados ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($efd04_sequencial)){
         $sql2 .= " where avaliacaogruporespostaretencaoservicostomados.efd04_sequencial = $efd04_sequencial "; 
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
