<?php

class cl_sagresresponsavelunidadeorcamentaria
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
    public $c140_sequencial = 0; 
    public $c140_orgao = 0; 
    public $c140_unidade = 0; 
    public $c140_cgm = 0; 
    public $c140_cgmsubstituto = 0; 
    public $c140_principal = 'f'; 
    public $c140_substituto = 'f'; 
    public $c140_datainicio_dia = null; 
    public $c140_datainicio_mes = null; 
    public $c140_datainicio_ano = null; 
    public $c140_datainicio = null; 
    public $c140_datafim_dia = null; 
    public $c140_datafim_mes = null; 
    public $c140_datafim_ano = null; 
    public $c140_datafim = null; 
    public $c140_tipoatojuridico = 0; 
    public $c140_ativo = 'f'; 
    public $c140_datainatividade_dia = null; 
    public $c140_datainatividade_mes = null; 
    public $c140_datainatividade_ano = null; 
    public $c140_datainatividade = null; 
    public $c140_idusuario = 0; 
    public $c140_anousu = 0; 
    public $c140_instit = 0; 
    public $c140_datainiciosub_dia = null; 
    public $c140_datainiciosub_mes = null; 
    public $c140_datainiciosub_ano = null; 
    public $c140_datainiciosub = null; 
    public $c140_datafimsub_dia = null; 
    public $c140_datafimsub_mes = null; 
    public $c140_datafimsub_ano = null; 
    public $c140_datafimsub = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 c140_sequencial = int4 = Sequencial 
                 c140_orgao = int4 = Código do Órgão 
                 c140_unidade = int4 = Código da Unidade 
                 c140_cgm = int4 = CGM 
                 c140_cgmsubstituto = int4 = CGM Substituto 
                 c140_principal = bool = Responsável Principal 
                 c140_substituto = bool = Responsável Substituto 
                 c140_datainicio = date = Data Inicio 
                 c140_datafim = date = Data fim 
                 c140_tipoatojuridico = int4 = Tipo ato juridico 
                 c140_ativo = bool = Ativo 
                 c140_datainatividade = date = Data de inativação 
                 c140_idusuario = int4 = Identificação do usuário 
                 c140_anousu = int4 = Campo anousu 
                 c140_instit = int4 = Código Instituição 
                 c140_datainiciosub = date = Data Inicio Substituto 
                 c140_datafimsub = date = Data Fim Substituto 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("sagresresponsavelunidadeorcamentaria"); 
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
       $this->c140_sequencial = ($this->c140_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c140_sequencial"]:$this->c140_sequencial);
       $this->c140_orgao = ($this->c140_orgao == ""?@$GLOBALS["HTTP_POST_VARS"]["c140_orgao"]:$this->c140_orgao);
       $this->c140_unidade = ($this->c140_unidade == ""?@$GLOBALS["HTTP_POST_VARS"]["c140_unidade"]:$this->c140_unidade);
       $this->c140_cgm = ($this->c140_cgm == ""?@$GLOBALS["HTTP_POST_VARS"]["c140_cgm"]:$this->c140_cgm);
       $this->c140_cgmsubstituto = ($this->c140_cgmsubstituto == ""?@$GLOBALS["HTTP_POST_VARS"]["c140_cgmsubstituto"]:$this->c140_cgmsubstituto);
       $this->c140_principal = ($this->c140_principal == "f"?@$GLOBALS["HTTP_POST_VARS"]["c140_principal"]:$this->c140_principal);
       $this->c140_substituto = ($this->c140_substituto == "f"?@$GLOBALS["HTTP_POST_VARS"]["c140_substituto"]:$this->c140_substituto);
       if($this->c140_datainicio == ""){
         $this->c140_datainicio_dia = ($this->c140_datainicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c140_datainicio_dia"]:$this->c140_datainicio_dia);
         $this->c140_datainicio_mes = ($this->c140_datainicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c140_datainicio_mes"]:$this->c140_datainicio_mes);
         $this->c140_datainicio_ano = ($this->c140_datainicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c140_datainicio_ano"]:$this->c140_datainicio_ano);
         if($this->c140_datainicio_dia != ""){
            $this->c140_datainicio = $this->c140_datainicio_ano."-".$this->c140_datainicio_mes."-".$this->c140_datainicio_dia;
         }
       }
       if($this->c140_datafim == ""){
         $this->c140_datafim_dia = ($this->c140_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c140_datafim_dia"]:$this->c140_datafim_dia);
         $this->c140_datafim_mes = ($this->c140_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c140_datafim_mes"]:$this->c140_datafim_mes);
         $this->c140_datafim_ano = ($this->c140_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c140_datafim_ano"]:$this->c140_datafim_ano);
         if($this->c140_datafim_dia != ""){
            $this->c140_datafim = $this->c140_datafim_ano."-".$this->c140_datafim_mes."-".$this->c140_datafim_dia;
         }
       }
       $this->c140_tipoatojuridico = ($this->c140_tipoatojuridico == ""?@$GLOBALS["HTTP_POST_VARS"]["c140_tipoatojuridico"]:$this->c140_tipoatojuridico);
       $this->c140_ativo = ($this->c140_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["c140_ativo"]:$this->c140_ativo);
       if($this->c140_datainatividade == ""){
         $this->c140_datainatividade_dia = ($this->c140_datainatividade_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c140_datainatividade_dia"]:$this->c140_datainatividade_dia);
         $this->c140_datainatividade_mes = ($this->c140_datainatividade_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c140_datainatividade_mes"]:$this->c140_datainatividade_mes);
         $this->c140_datainatividade_ano = ($this->c140_datainatividade_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c140_datainatividade_ano"]:$this->c140_datainatividade_ano);
         if($this->c140_datainatividade_dia != ""){
            $this->c140_datainatividade = $this->c140_datainatividade_ano."-".$this->c140_datainatividade_mes."-".$this->c140_datainatividade_dia;
         }
       }
       $this->c140_idusuario = ($this->c140_idusuario == ""?@$GLOBALS["HTTP_POST_VARS"]["c140_idusuario"]:$this->c140_idusuario);
       $this->c140_anousu = ($this->c140_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c140_anousu"]:$this->c140_anousu);
       $this->c140_instit = ($this->c140_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["c140_instit"]:$this->c140_instit);
       if($this->c140_datainiciosub == ""){
         $this->c140_datainiciosub_dia = ($this->c140_datainiciosub_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c140_datainiciosub_dia"]:$this->c140_datainiciosub_dia);
         $this->c140_datainiciosub_mes = ($this->c140_datainiciosub_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c140_datainiciosub_mes"]:$this->c140_datainiciosub_mes);
         $this->c140_datainiciosub_ano = ($this->c140_datainiciosub_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c140_datainiciosub_ano"]:$this->c140_datainiciosub_ano);
         if($this->c140_datainiciosub_dia != ""){
            $this->c140_datainiciosub = $this->c140_datainiciosub_ano."-".$this->c140_datainiciosub_mes."-".$this->c140_datainiciosub_dia;
         }
       }
       if($this->c140_datafimsub == ""){
         $this->c140_datafimsub_dia = ($this->c140_datafimsub_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c140_datafimsub_dia"]:$this->c140_datafimsub_dia);
         $this->c140_datafimsub_mes = ($this->c140_datafimsub_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c140_datafimsub_mes"]:$this->c140_datafimsub_mes);
         $this->c140_datafimsub_ano = ($this->c140_datafimsub_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c140_datafimsub_ano"]:$this->c140_datafimsub_ano);
         if($this->c140_datafimsub_dia != ""){
            $this->c140_datafimsub = $this->c140_datafimsub_ano."-".$this->c140_datafimsub_mes."-".$this->c140_datafimsub_dia;
         }
       }
     }else{
       $this->c140_sequencial = ($this->c140_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c140_sequencial"]:$this->c140_sequencial);
     }
   }

    public function incluir($c140_sequencial)
    {
      $this->atualizacampos();
     if($this->c140_orgao == null ){ 
       $this->erro_sql = " Campo Código do Órgão não informado.";
       $this->erro_campo = "c140_orgao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c140_unidade == null ){ 
       $this->erro_sql = " Campo Código da Unidade não informado.";
       $this->erro_campo = "c140_unidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c140_cgm == null ){ 
       $this->erro_sql = " Campo CGM não informado.";
       $this->erro_campo = "c140_cgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c140_cgmsubstituto == null ){ 
       $this->c140_cgmsubstituto = "0";
     }
     if($this->c140_principal == null ){ 
       $this->erro_sql = " Campo Responsável Principal não informado.";
       $this->erro_campo = "c140_principal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c140_substituto == null ){ 
       $this->erro_sql = " Campo Responsável Substituto não informado.";
       $this->erro_campo = "c140_substituto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c140_datainicio == null ){ 
       $this->erro_sql = " Campo Data Inicio não informado.";
       $this->erro_campo = "c140_datainicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c140_datafim == null ){ 
       $this->c140_datafim = "null";
     }
     if($this->c140_tipoatojuridico == null ){ 
       $this->erro_sql = " Campo Tipo ato juridico não informado.";
       $this->erro_campo = "c140_tipoatojuridico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c140_ativo == null ){ 
       $this->erro_sql = " Campo Ativo não informado.";
       $this->erro_campo = "c140_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c140_datainatividade == null ){ 
       $this->c140_datainatividade = "null";
     }
     if($this->c140_idusuario == null ){ 
       $this->c140_idusuario = "0";
     }
     if($this->c140_anousu == null ){ 
       $this->erro_sql = " Campo Campo anousu não informado.";
       $this->erro_campo = "c140_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c140_instit == null ){ 
       $this->erro_sql = " Campo Código Instituição não informado.";
       $this->erro_campo = "c140_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c140_datainiciosub == null ){ 
       $this->c140_datainiciosub = "null";
     }
     if($this->c140_datafimsub == null ){ 
       $this->c140_datafimsub = "null";
     }
     if($c140_sequencial == "" || $c140_sequencial == null ){
       $result = db_query("select nextval('sagresresponsavelunidadeorcamentaria_c140_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sagresresponsavelunidadeorcamentaria_c140_sequencial_seq do campo: c140_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c140_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sagresresponsavelunidadeorcamentaria_c140_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c140_sequencial)){
         $this->erro_sql = " Campo c140_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c140_sequencial = $c140_sequencial; 
       }
     }
     if(($this->c140_sequencial == null) || ($this->c140_sequencial == "") ){ 
       $this->erro_sql = " Campo c140_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sagresresponsavelunidadeorcamentaria(
                                       c140_sequencial 
                                      ,c140_orgao 
                                      ,c140_unidade 
                                      ,c140_cgm 
                                      ,c140_cgmsubstituto 
                                      ,c140_principal 
                                      ,c140_substituto 
                                      ,c140_datainicio 
                                      ,c140_datafim 
                                      ,c140_tipoatojuridico 
                                      ,c140_ativo 
                                      ,c140_datainatividade 
                                      ,c140_idusuario 
                                      ,c140_anousu 
                                      ,c140_instit 
                                      ,c140_datainiciosub 
                                      ,c140_datafimsub 
                       )
                values (
                                $this->c140_sequencial 
                               ,$this->c140_orgao 
                               ,$this->c140_unidade 
                               ,$this->c140_cgm 
                               ,$this->c140_cgmsubstituto 
                               ,'$this->c140_principal' 
                               ,'$this->c140_substituto' 
                               ,".($this->c140_datainicio == "null" || $this->c140_datainicio == ""?"null":"'".$this->c140_datainicio."'")." 
                               ,".($this->c140_datafim == "null" || $this->c140_datafim == ""?"null":"'".$this->c140_datafim."'")." 
                               ,$this->c140_tipoatojuridico 
                               ,'$this->c140_ativo' 
                               ,".($this->c140_datainatividade == "null" || $this->c140_datainatividade == ""?"null":"'".$this->c140_datainatividade."'")." 
                               ,$this->c140_idusuario 
                               ,$this->c140_anousu 
                               ,$this->c140_instit 
                               ,".($this->c140_datainiciosub == "null" || $this->c140_datainiciosub == ""?"null":"'".$this->c140_datainiciosub."'")." 
                               ,".($this->c140_datafimsub == "null" || $this->c140_datafimsub == ""?"null":"'".$this->c140_datafimsub."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Responsável pela Unidade Orçamentária do SAGRES ($this->c140_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Responsável pela Unidade Orçamentária do SAGRES já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Responsável pela Unidade Orçamentária do SAGRES ($this->c140_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->c140_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->c140_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1013725,'$this->c140_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010863,1013725,'','".AddSlashes(pg_result($resaco,0,'c140_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010863,1013726,'','".AddSlashes(pg_result($resaco,0,'c140_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010863,1013727,'','".AddSlashes(pg_result($resaco,0,'c140_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010863,1013728,'','".AddSlashes(pg_result($resaco,0,'c140_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010863,1013729,'','".AddSlashes(pg_result($resaco,0,'c140_cgmsubstituto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010863,1013730,'','".AddSlashes(pg_result($resaco,0,'c140_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010863,1013731,'','".AddSlashes(pg_result($resaco,0,'c140_substituto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010863,1013732,'','".AddSlashes(pg_result($resaco,0,'c140_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010863,1013733,'','".AddSlashes(pg_result($resaco,0,'c140_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010863,1013734,'','".AddSlashes(pg_result($resaco,0,'c140_tipoatojuridico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010863,1013735,'','".AddSlashes(pg_result($resaco,0,'c140_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010863,1013736,'','".AddSlashes(pg_result($resaco,0,'c140_datainatividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010863,1013737,'','".AddSlashes(pg_result($resaco,0,'c140_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010863,1013738,'','".AddSlashes(pg_result($resaco,0,'c140_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010863,1013983,'','".AddSlashes(pg_result($resaco,0,'c140_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010863,1013984,'','".AddSlashes(pg_result($resaco,0,'c140_datainiciosub'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010863,1013985,'','".AddSlashes(pg_result($resaco,0,'c140_datafimsub'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($c140_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update sagresresponsavelunidadeorcamentaria set ";
     $virgula = "";
     if(trim($this->c140_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c140_sequencial"])){ 
       $sql  .= $virgula." c140_sequencial = $this->c140_sequencial ";
       $virgula = ",";
       if(trim($this->c140_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "c140_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c140_orgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c140_orgao"])){ 
       $sql  .= $virgula." c140_orgao = $this->c140_orgao ";
       $virgula = ",";
       if(trim($this->c140_orgao) == null ){ 
         $this->erro_sql = " Campo Código do Órgão não informado.";
         $this->erro_campo = "c140_orgao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c140_unidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c140_unidade"])){ 
       $sql  .= $virgula." c140_unidade = $this->c140_unidade ";
       $virgula = ",";
       if(trim($this->c140_unidade) == null ){ 
         $this->erro_sql = " Campo Código da Unidade não informado.";
         $this->erro_campo = "c140_unidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c140_cgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c140_cgm"])){ 
       $sql  .= $virgula." c140_cgm = $this->c140_cgm ";
       $virgula = ",";
       if(trim($this->c140_cgm) == null ){ 
         $this->erro_sql = " Campo CGM não informado.";
         $this->erro_campo = "c140_cgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c140_cgmsubstituto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c140_cgmsubstituto"])){ 
        if(trim($this->c140_cgmsubstituto)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c140_cgmsubstituto"])){ 
           $this->c140_cgmsubstituto = "0" ; 
        } 
       $sql  .= $virgula." c140_cgmsubstituto = $this->c140_cgmsubstituto ";
       $virgula = ",";
     }
     if(trim($this->c140_principal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c140_principal"])){ 
       $sql  .= $virgula." c140_principal = '$this->c140_principal' ";
       $virgula = ",";
       if(trim($this->c140_principal) == null ){ 
         $this->erro_sql = " Campo Responsável Principal não informado.";
         $this->erro_campo = "c140_principal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c140_substituto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c140_substituto"])){ 
       $sql  .= $virgula." c140_substituto = '$this->c140_substituto' ";
       $virgula = ",";
       if(trim($this->c140_substituto) == null ){ 
         $this->erro_sql = " Campo Responsável Substituto não informado.";
         $this->erro_campo = "c140_substituto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c140_datainicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c140_datainicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c140_datainicio_dia"] !="") ){ 
       $sql  .= $virgula." c140_datainicio = '$this->c140_datainicio' ";
       $virgula = ",";
       if(trim($this->c140_datainicio) == null ){ 
         $this->erro_sql = " Campo Data Inicio não informado.";
         $this->erro_campo = "c140_datainicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c140_datainicio_dia"])){ 
         $sql  .= $virgula." c140_datainicio = null ";
         $virgula = ",";
         if(trim($this->c140_datainicio) == null ){ 
           $this->erro_sql = " Campo Data Inicio não informado.";
           $this->erro_campo = "c140_datainicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->c140_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c140_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c140_datafim_dia"] !="") ){ 
       $sql  .= $virgula." c140_datafim = '$this->c140_datafim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c140_datafim_dia"])){ 
         $sql  .= $virgula." c140_datafim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->c140_tipoatojuridico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c140_tipoatojuridico"])){ 
       $sql  .= $virgula." c140_tipoatojuridico = $this->c140_tipoatojuridico ";
       $virgula = ",";
       if(trim($this->c140_tipoatojuridico) == null ){ 
         $this->erro_sql = " Campo Tipo ato juridico não informado.";
         $this->erro_campo = "c140_tipoatojuridico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c140_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c140_ativo"])){ 
       $sql  .= $virgula." c140_ativo = '$this->c140_ativo' ";
       $virgula = ",";
       if(trim($this->c140_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo não informado.";
         $this->erro_campo = "c140_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c140_datainatividade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c140_datainatividade_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c140_datainatividade_dia"] !="") ){ 
       $sql  .= $virgula." c140_datainatividade = '$this->c140_datainatividade' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c140_datainatividade_dia"])){ 
         $sql  .= $virgula." c140_datainatividade = null ";
         $virgula = ",";
       }
     }
     if(trim($this->c140_idusuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c140_idusuario"])){ 
        if(trim($this->c140_idusuario)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c140_idusuario"])){ 
           $this->c140_idusuario = "0" ; 
        } 
       $sql  .= $virgula." c140_idusuario = $this->c140_idusuario ";
       $virgula = ",";
     }
     if(trim($this->c140_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c140_anousu"])){ 
       $sql  .= $virgula." c140_anousu = $this->c140_anousu ";
       $virgula = ",";
       if(trim($this->c140_anousu) == null ){ 
         $this->erro_sql = " Campo Campo anousu não informado.";
         $this->erro_campo = "c140_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c140_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c140_instit"])){ 
       $sql  .= $virgula." c140_instit = $this->c140_instit ";
       $virgula = ",";
       if(trim($this->c140_instit) == null ){ 
         $this->erro_sql = " Campo Código Instituição não informado.";
         $this->erro_campo = "c140_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c140_datainiciosub)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c140_datainiciosub_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c140_datainiciosub_dia"] !="") ){ 
       $sql  .= $virgula." c140_datainiciosub = '$this->c140_datainiciosub' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c140_datainiciosub_dia"])){ 
         $sql  .= $virgula." c140_datainiciosub = null ";
         $virgula = ",";
       }
     }
     if(trim($this->c140_datafimsub)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c140_datafimsub_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c140_datafimsub_dia"] !="") ){ 
       $sql  .= $virgula." c140_datafimsub = '$this->c140_datafimsub' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c140_datafimsub_dia"])){ 
         $sql  .= $virgula." c140_datafimsub = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($c140_sequencial!=null){
       $sql .= " c140_sequencial = $this->c140_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->c140_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1013725,'$this->c140_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c140_sequencial"]) || $this->c140_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010863,1013725,'".AddSlashes(pg_result($resaco,$conresaco,'c140_sequencial'))."','$this->c140_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c140_orgao"]) || $this->c140_orgao != "")
             $resac = db_query("insert into db_acount values($acount,1010863,1013726,'".AddSlashes(pg_result($resaco,$conresaco,'c140_orgao'))."','$this->c140_orgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c140_unidade"]) || $this->c140_unidade != "")
             $resac = db_query("insert into db_acount values($acount,1010863,1013727,'".AddSlashes(pg_result($resaco,$conresaco,'c140_unidade'))."','$this->c140_unidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c140_cgm"]) || $this->c140_cgm != "")
             $resac = db_query("insert into db_acount values($acount,1010863,1013728,'".AddSlashes(pg_result($resaco,$conresaco,'c140_cgm'))."','$this->c140_cgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c140_cgmsubstituto"]) || $this->c140_cgmsubstituto != "")
             $resac = db_query("insert into db_acount values($acount,1010863,1013729,'".AddSlashes(pg_result($resaco,$conresaco,'c140_cgmsubstituto'))."','$this->c140_cgmsubstituto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c140_principal"]) || $this->c140_principal != "")
             $resac = db_query("insert into db_acount values($acount,1010863,1013730,'".AddSlashes(pg_result($resaco,$conresaco,'c140_principal'))."','$this->c140_principal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c140_substituto"]) || $this->c140_substituto != "")
             $resac = db_query("insert into db_acount values($acount,1010863,1013731,'".AddSlashes(pg_result($resaco,$conresaco,'c140_substituto'))."','$this->c140_substituto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c140_datainicio"]) || $this->c140_datainicio != "")
             $resac = db_query("insert into db_acount values($acount,1010863,1013732,'".AddSlashes(pg_result($resaco,$conresaco,'c140_datainicio'))."','$this->c140_datainicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c140_datafim"]) || $this->c140_datafim != "")
             $resac = db_query("insert into db_acount values($acount,1010863,1013733,'".AddSlashes(pg_result($resaco,$conresaco,'c140_datafim'))."','$this->c140_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c140_tipoatojuridico"]) || $this->c140_tipoatojuridico != "")
             $resac = db_query("insert into db_acount values($acount,1010863,1013734,'".AddSlashes(pg_result($resaco,$conresaco,'c140_tipoatojuridico'))."','$this->c140_tipoatojuridico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c140_ativo"]) || $this->c140_ativo != "")
             $resac = db_query("insert into db_acount values($acount,1010863,1013735,'".AddSlashes(pg_result($resaco,$conresaco,'c140_ativo'))."','$this->c140_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c140_datainatividade"]) || $this->c140_datainatividade != "")
             $resac = db_query("insert into db_acount values($acount,1010863,1013736,'".AddSlashes(pg_result($resaco,$conresaco,'c140_datainatividade'))."','$this->c140_datainatividade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c140_idusuario"]) || $this->c140_idusuario != "")
             $resac = db_query("insert into db_acount values($acount,1010863,1013737,'".AddSlashes(pg_result($resaco,$conresaco,'c140_idusuario'))."','$this->c140_idusuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c140_anousu"]) || $this->c140_anousu != "")
             $resac = db_query("insert into db_acount values($acount,1010863,1013738,'".AddSlashes(pg_result($resaco,$conresaco,'c140_anousu'))."','$this->c140_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c140_instit"]) || $this->c140_instit != "")
             $resac = db_query("insert into db_acount values($acount,1010863,1013983,'".AddSlashes(pg_result($resaco,$conresaco,'c140_instit'))."','$this->c140_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c140_datainiciosub"]) || $this->c140_datainiciosub != "")
             $resac = db_query("insert into db_acount values($acount,1010863,1013984,'".AddSlashes(pg_result($resaco,$conresaco,'c140_datainiciosub'))."','$this->c140_datainiciosub',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c140_datafimsub"]) || $this->c140_datafimsub != "")
             $resac = db_query("insert into db_acount values($acount,1010863,1013985,'".AddSlashes(pg_result($resaco,$conresaco,'c140_datafimsub'))."','$this->c140_datafimsub',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Responsável pela Unidade Orçamentária do SAGRES não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c140_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Responsável pela Unidade Orçamentária do SAGRES não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c140_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->c140_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($c140_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($c140_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1013725,'$c140_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010863,1013725,'','".AddSlashes(pg_result($resaco,$iresaco,'c140_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010863,1013726,'','".AddSlashes(pg_result($resaco,$iresaco,'c140_orgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010863,1013727,'','".AddSlashes(pg_result($resaco,$iresaco,'c140_unidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010863,1013728,'','".AddSlashes(pg_result($resaco,$iresaco,'c140_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010863,1013729,'','".AddSlashes(pg_result($resaco,$iresaco,'c140_cgmsubstituto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010863,1013730,'','".AddSlashes(pg_result($resaco,$iresaco,'c140_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010863,1013731,'','".AddSlashes(pg_result($resaco,$iresaco,'c140_substituto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010863,1013732,'','".AddSlashes(pg_result($resaco,$iresaco,'c140_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010863,1013733,'','".AddSlashes(pg_result($resaco,$iresaco,'c140_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010863,1013734,'','".AddSlashes(pg_result($resaco,$iresaco,'c140_tipoatojuridico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010863,1013735,'','".AddSlashes(pg_result($resaco,$iresaco,'c140_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010863,1013736,'','".AddSlashes(pg_result($resaco,$iresaco,'c140_datainatividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010863,1013737,'','".AddSlashes(pg_result($resaco,$iresaco,'c140_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010863,1013738,'','".AddSlashes(pg_result($resaco,$iresaco,'c140_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010863,1013983,'','".AddSlashes(pg_result($resaco,$iresaco,'c140_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010863,1013984,'','".AddSlashes(pg_result($resaco,$iresaco,'c140_datainiciosub'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010863,1013985,'','".AddSlashes(pg_result($resaco,$iresaco,'c140_datafimsub'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from sagresresponsavelunidadeorcamentaria
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($c140_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " c140_sequencial = $c140_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Responsável pela Unidade Orçamentária do SAGRES não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c140_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Responsável pela Unidade Orçamentária do SAGRES não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c140_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$c140_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:sagresresponsavelunidadeorcamentaria";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($c140_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from sagresresponsavelunidadeorcamentaria ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = sagresresponsavelunidadeorcamentaria.c140_cgm";
     $sql .= "      left  join cgm as cgmsubstituto on cgmsubstituto.z01_numcgm = sagresresponsavelunidadeorcamentaria.c140_cgmsubstituto";
     $sql .= "      left  join db_usuarios  on  db_usuarios.id_usuario = sagresresponsavelunidadeorcamentaria.c140_idusuario";
     $sql .= "      inner join db_config  on  db_config.codigo = sagresresponsavelunidadeorcamentaria.c140_instit";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = sagresresponsavelunidadeorcamentaria.c140_anousu and  orcorgao.o40_orgao = sagresresponsavelunidadeorcamentaria.c140_orgao";
     $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = sagresresponsavelunidadeorcamentaria.c140_anousu and  orcunidade.o41_orgao = sagresresponsavelunidadeorcamentaria.c140_orgao and  orcunidade.o41_unidade = sagresresponsavelunidadeorcamentaria.c140_unidade";
     $sql .= "      inner join db_config  as a on   a.codigo = orcorgao.o40_instit";
     $sql .= "      inner join db_config  as b on   b.codigo = orcunidade.o41_instit";
     $sql .= "      inner join orcorgao  as c on   c.o40_anousu = orcunidade.o41_anousu and   c.o40_orgao = orcunidade.o41_orgao";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($c140_sequencial)) {
         $sql2 .= " where sagresresponsavelunidadeorcamentaria.c140_sequencial = $c140_sequencial "; 
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

    public function sql_query_file($c140_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from sagresresponsavelunidadeorcamentaria ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($c140_sequencial)){
         $sql2 .= " where sagresresponsavelunidadeorcamentaria.c140_sequencial = $c140_sequencial "; 
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
