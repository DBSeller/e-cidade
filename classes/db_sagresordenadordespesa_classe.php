<?php

class cl_sagresordenadordespesa
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
    public $c139_sequencial = 0; 
    public $c139_instit = 0; 
    public $c139_cgm = 0; 
    public $c139_cgmsubstituto = 0; 
    public $c139_principal = 'f'; 
    public $c139_substituto = 'f'; 
    public $c139_datainicio_dia = null; 
    public $c139_datainicio_mes = null; 
    public $c139_datainicio_ano = null; 
    public $c139_datainicio = null; 
    public $c139_datafim_dia = null; 
    public $c139_datafim_mes = null; 
    public $c139_datafim_ano = null; 
    public $c139_datafim = null; 
    public $c139_tipoatojuridico = 0; 
    public $c139_titulo = null; 
    public $c139_ativo = 'f'; 
    public $c139_datainatividade_dia = null; 
    public $c139_datainatividade_mes = null; 
    public $c139_datainatividade_ano = null; 
    public $c139_datainatividade = null; 
    public $c139_idusuario = 0; 
    public $c139_datainiciosub_dia = null; 
    public $c139_datainiciosub_mes = null; 
    public $c139_datainiciosub_ano = null; 
    public $c139_datainiciosub = null; 
    public $c139_datafimsub_dia = null; 
    public $c139_datafimsub_mes = null; 
    public $c139_datafimsub_ano = null; 
    public $c139_datafimsub = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 c139_sequencial = int4 = Sequencial 
                 c139_instit = int4 = Instituição 
                 c139_cgm = int4 = CGM 
                 c139_cgmsubstituto = int4 = CGM Substituto 
                 c139_principal = bool = Ordenador Principal 
                 c139_substituto = bool = Ordenador Substituto 
                 c139_datainicio = date = Data Inicio 
                 c139_datafim = date = Data fim 
                 c139_tipoatojuridico = int4 = Tipo ato juridico 
                 c139_titulo = varchar(50) = Titulo do Ordenador 
                 c139_ativo = bool = Ativo 
                 c139_datainatividade = date = Data de inativação 
                 c139_idusuario = int4 = Identificação do usuário 
                 c139_datainiciosub = date = Data Início Substituto 
                 c139_datafimsub = date = Data Fim Substituto 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("sagresordenadordespesa"); 
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
       $this->c139_sequencial = ($this->c139_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c139_sequencial"]:$this->c139_sequencial);
       $this->c139_instit = ($this->c139_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["c139_instit"]:$this->c139_instit);
       $this->c139_cgm = ($this->c139_cgm == ""?@$GLOBALS["HTTP_POST_VARS"]["c139_cgm"]:$this->c139_cgm);
       $this->c139_cgmsubstituto = ($this->c139_cgmsubstituto == ""?@$GLOBALS["HTTP_POST_VARS"]["c139_cgmsubstituto"]:$this->c139_cgmsubstituto);
       $this->c139_principal = ($this->c139_principal == "f"?@$GLOBALS["HTTP_POST_VARS"]["c139_principal"]:$this->c139_principal);
       $this->c139_substituto = ($this->c139_substituto == "f"?@$GLOBALS["HTTP_POST_VARS"]["c139_substituto"]:$this->c139_substituto);
       if($this->c139_datainicio == ""){
         $this->c139_datainicio_dia = ($this->c139_datainicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c139_datainicio_dia"]:$this->c139_datainicio_dia);
         $this->c139_datainicio_mes = ($this->c139_datainicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c139_datainicio_mes"]:$this->c139_datainicio_mes);
         $this->c139_datainicio_ano = ($this->c139_datainicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c139_datainicio_ano"]:$this->c139_datainicio_ano);
         if($this->c139_datainicio_dia != ""){
            $this->c139_datainicio = $this->c139_datainicio_ano."-".$this->c139_datainicio_mes."-".$this->c139_datainicio_dia;
         }
       }
       if($this->c139_datafim == ""){
         $this->c139_datafim_dia = ($this->c139_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c139_datafim_dia"]:$this->c139_datafim_dia);
         $this->c139_datafim_mes = ($this->c139_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c139_datafim_mes"]:$this->c139_datafim_mes);
         $this->c139_datafim_ano = ($this->c139_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c139_datafim_ano"]:$this->c139_datafim_ano);
         if($this->c139_datafim_dia != ""){
            $this->c139_datafim = $this->c139_datafim_ano."-".$this->c139_datafim_mes."-".$this->c139_datafim_dia;
         }
       }
       $this->c139_tipoatojuridico = ($this->c139_tipoatojuridico == ""?@$GLOBALS["HTTP_POST_VARS"]["c139_tipoatojuridico"]:$this->c139_tipoatojuridico);
       $this->c139_titulo = ($this->c139_titulo == ""?@$GLOBALS["HTTP_POST_VARS"]["c139_titulo"]:$this->c139_titulo);
       $this->c139_ativo = ($this->c139_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["c139_ativo"]:$this->c139_ativo);
       if($this->c139_datainatividade == ""){
         $this->c139_datainatividade_dia = ($this->c139_datainatividade_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c139_datainatividade_dia"]:$this->c139_datainatividade_dia);
         $this->c139_datainatividade_mes = ($this->c139_datainatividade_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c139_datainatividade_mes"]:$this->c139_datainatividade_mes);
         $this->c139_datainatividade_ano = ($this->c139_datainatividade_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c139_datainatividade_ano"]:$this->c139_datainatividade_ano);
         if($this->c139_datainatividade_dia != ""){
            $this->c139_datainatividade = $this->c139_datainatividade_ano."-".$this->c139_datainatividade_mes."-".$this->c139_datainatividade_dia;
         }
       }
       $this->c139_idusuario = ($this->c139_idusuario == ""?@$GLOBALS["HTTP_POST_VARS"]["c139_idusuario"]:$this->c139_idusuario);
       if($this->c139_datainiciosub == ""){
         $this->c139_datainiciosub_dia = ($this->c139_datainiciosub_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c139_datainiciosub_dia"]:$this->c139_datainiciosub_dia);
         $this->c139_datainiciosub_mes = ($this->c139_datainiciosub_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c139_datainiciosub_mes"]:$this->c139_datainiciosub_mes);
         $this->c139_datainiciosub_ano = ($this->c139_datainiciosub_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c139_datainiciosub_ano"]:$this->c139_datainiciosub_ano);
         if($this->c139_datainiciosub_dia != ""){
            $this->c139_datainiciosub = $this->c139_datainiciosub_ano."-".$this->c139_datainiciosub_mes."-".$this->c139_datainiciosub_dia;
         }
       }
       if($this->c139_datafimsub == ""){
         $this->c139_datafimsub_dia = ($this->c139_datafimsub_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c139_datafimsub_dia"]:$this->c139_datafimsub_dia);
         $this->c139_datafimsub_mes = ($this->c139_datafimsub_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c139_datafimsub_mes"]:$this->c139_datafimsub_mes);
         $this->c139_datafimsub_ano = ($this->c139_datafimsub_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c139_datafimsub_ano"]:$this->c139_datafimsub_ano);
         if($this->c139_datafimsub_dia != ""){
            $this->c139_datafimsub = $this->c139_datafimsub_ano."-".$this->c139_datafimsub_mes."-".$this->c139_datafimsub_dia;
         }
       }
     }else{
       $this->c139_sequencial = ($this->c139_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c139_sequencial"]:$this->c139_sequencial);
     }
   }

    public function incluir($c139_sequencial)
    {
      $this->atualizacampos();
     if($this->c139_instit == null ){ 
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "c139_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c139_cgm == null ){ 
       $this->erro_sql = " Campo CGM não informado.";
       $this->erro_campo = "c139_cgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c139_cgmsubstituto == null ){ 
       $this->c139_cgmsubstituto = "0";
     }
     if($this->c139_principal == null ){ 
       $this->erro_sql = " Campo Ordenador Principal não informado.";
       $this->erro_campo = "c139_principal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c139_substituto == null ){ 
       $this->erro_sql = " Campo Ordenador Substituto não informado.";
       $this->erro_campo = "c139_substituto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c139_datainicio == null ){ 
       $this->erro_sql = " Campo Data Inicio não informado.";
       $this->erro_campo = "c139_datainicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c139_datafim == null ){ 
       $this->c139_datafim = "null";
     }
     if($this->c139_tipoatojuridico == null ){ 
       $this->erro_sql = " Campo Tipo ato juridico não informado.";
       $this->erro_campo = "c139_tipoatojuridico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c139_ativo == null ){ 
       $this->erro_sql = " Campo Ativo não informado.";
       $this->erro_campo = "c139_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c139_datainatividade == null ){ 
       $this->c139_datainatividade = "null";
     }
     if($this->c139_idusuario == null ){ 
       $this->c139_idusuario = "0";
     }
     if($this->c139_datainiciosub == null ){ 
       $this->c139_datainiciosub = "null";
     }
     if($this->c139_datafimsub == null ){ 
       $this->c139_datafimsub = "null";
     }
     if($c139_sequencial == "" || $c139_sequencial == null ){
       $result = db_query("select nextval('sagresordenadordespesa_c139_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sagresordenadordespesa_c139_sequencial_seq do campo: c139_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c139_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sagresordenadordespesa_c139_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c139_sequencial)){
         $this->erro_sql = " Campo c139_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c139_sequencial = $c139_sequencial; 
       }
     }
     if(($this->c139_sequencial == null) || ($this->c139_sequencial == "") ){ 
       $this->erro_sql = " Campo c139_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sagresordenadordespesa(
                                       c139_sequencial 
                                      ,c139_instit 
                                      ,c139_cgm 
                                      ,c139_cgmsubstituto 
                                      ,c139_principal 
                                      ,c139_substituto 
                                      ,c139_datainicio 
                                      ,c139_datafim 
                                      ,c139_tipoatojuridico 
                                      ,c139_titulo 
                                      ,c139_ativo 
                                      ,c139_datainatividade 
                                      ,c139_idusuario 
                                      ,c139_datainiciosub 
                                      ,c139_datafimsub 
                       )
                values (
                                $this->c139_sequencial 
                               ,$this->c139_instit 
                               ,$this->c139_cgm 
                               ,$this->c139_cgmsubstituto 
                               ,'$this->c139_principal' 
                               ,'$this->c139_substituto' 
                               ,".($this->c139_datainicio == "null" || $this->c139_datainicio == ""?"null":"'".$this->c139_datainicio."'")." 
                               ,".($this->c139_datafim == "null" || $this->c139_datafim == ""?"null":"'".$this->c139_datafim."'")." 
                               ,$this->c139_tipoatojuridico 
                               ,'$this->c139_titulo' 
                               ,'$this->c139_ativo' 
                               ,".($this->c139_datainatividade == "null" || $this->c139_datainatividade == ""?"null":"'".$this->c139_datainatividade."'")." 
                               ,$this->c139_idusuario 
                               ,".($this->c139_datainiciosub == "null" || $this->c139_datainiciosub == ""?"null":"'".$this->c139_datainiciosub."'")." 
                               ,".($this->c139_datafimsub == "null" || $this->c139_datafimsub == ""?"null":"'".$this->c139_datafimsub."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro Ordenador de Despesa do SAGRES ($this->c139_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro Ordenador de Despesa do SAGRES já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro Ordenador de Despesa do SAGRES ($this->c139_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->c139_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->c139_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1013712,'$this->c139_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010862,1013712,'','".AddSlashes(pg_result($resaco,0,'c139_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010862,1013713,'','".AddSlashes(pg_result($resaco,0,'c139_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010862,1013714,'','".AddSlashes(pg_result($resaco,0,'c139_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010862,1013715,'','".AddSlashes(pg_result($resaco,0,'c139_cgmsubstituto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010862,1013716,'','".AddSlashes(pg_result($resaco,0,'c139_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010862,1013717,'','".AddSlashes(pg_result($resaco,0,'c139_substituto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010862,1013718,'','".AddSlashes(pg_result($resaco,0,'c139_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010862,1013719,'','".AddSlashes(pg_result($resaco,0,'c139_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010862,1013720,'','".AddSlashes(pg_result($resaco,0,'c139_tipoatojuridico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010862,1013721,'','".AddSlashes(pg_result($resaco,0,'c139_titulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010862,1013722,'','".AddSlashes(pg_result($resaco,0,'c139_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010862,1013723,'','".AddSlashes(pg_result($resaco,0,'c139_datainatividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010862,1013724,'','".AddSlashes(pg_result($resaco,0,'c139_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010862,1013986,'','".AddSlashes(pg_result($resaco,0,'c139_datainiciosub'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010862,1013987,'','".AddSlashes(pg_result($resaco,0,'c139_datafimsub'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($c139_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update sagresordenadordespesa set ";
     $virgula = "";
     if(trim($this->c139_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c139_sequencial"])){ 
       $sql  .= $virgula." c139_sequencial = $this->c139_sequencial ";
       $virgula = ",";
       if(trim($this->c139_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "c139_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c139_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c139_instit"])){ 
       $sql  .= $virgula." c139_instit = $this->c139_instit ";
       $virgula = ",";
       if(trim($this->c139_instit) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "c139_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c139_cgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c139_cgm"])){ 
       $sql  .= $virgula." c139_cgm = $this->c139_cgm ";
       $virgula = ",";
       if(trim($this->c139_cgm) == null ){ 
         $this->erro_sql = " Campo CGM não informado.";
         $this->erro_campo = "c139_cgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c139_cgmsubstituto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c139_cgmsubstituto"])){ 
        if(trim($this->c139_cgmsubstituto)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c139_cgmsubstituto"])){ 
           $this->c139_cgmsubstituto = "0" ; 
        } 
       $sql  .= $virgula." c139_cgmsubstituto = $this->c139_cgmsubstituto ";
       $virgula = ",";
     }
     if(trim($this->c139_principal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c139_principal"])){ 
       $sql  .= $virgula." c139_principal = '$this->c139_principal' ";
       $virgula = ",";
       if(trim($this->c139_principal) == null ){ 
         $this->erro_sql = " Campo Ordenador Principal não informado.";
         $this->erro_campo = "c139_principal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c139_substituto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c139_substituto"])){ 
       $sql  .= $virgula." c139_substituto = '$this->c139_substituto' ";
       $virgula = ",";
       if(trim($this->c139_substituto) == null ){ 
         $this->erro_sql = " Campo Ordenador Substituto não informado.";
         $this->erro_campo = "c139_substituto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c139_datainicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c139_datainicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c139_datainicio_dia"] !="") ){ 
       $sql  .= $virgula." c139_datainicio = '$this->c139_datainicio' ";
       $virgula = ",";
       if(trim($this->c139_datainicio) == null ){ 
         $this->erro_sql = " Campo Data Inicio não informado.";
         $this->erro_campo = "c139_datainicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c139_datainicio_dia"])){ 
         $sql  .= $virgula." c139_datainicio = null ";
         $virgula = ",";
         if(trim($this->c139_datainicio) == null ){ 
           $this->erro_sql = " Campo Data Inicio não informado.";
           $this->erro_campo = "c139_datainicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->c139_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c139_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c139_datafim_dia"] !="") ){ 
       $sql  .= $virgula." c139_datafim = '$this->c139_datafim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c139_datafim_dia"])){ 
         $sql  .= $virgula." c139_datafim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->c139_tipoatojuridico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c139_tipoatojuridico"])){ 
       $sql  .= $virgula." c139_tipoatojuridico = $this->c139_tipoatojuridico ";
       $virgula = ",";
       if(trim($this->c139_tipoatojuridico) == null ){ 
         $this->erro_sql = " Campo Tipo ato juridico não informado.";
         $this->erro_campo = "c139_tipoatojuridico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c139_titulo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c139_titulo"])){ 
       $sql  .= $virgula." c139_titulo = '$this->c139_titulo' ";
       $virgula = ",";
     }
     if(trim($this->c139_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c139_ativo"])){ 
       $sql  .= $virgula." c139_ativo = '$this->c139_ativo' ";
       $virgula = ",";
       if(trim($this->c139_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo não informado.";
         $this->erro_campo = "c139_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c139_datainatividade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c139_datainatividade_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c139_datainatividade_dia"] !="") ){ 
       $sql  .= $virgula." c139_datainatividade = '$this->c139_datainatividade' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c139_datainatividade_dia"])){ 
         $sql  .= $virgula." c139_datainatividade = null ";
         $virgula = ",";
       }
     }
     if(trim($this->c139_idusuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c139_idusuario"])){ 
        if(trim($this->c139_idusuario)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c139_idusuario"])){ 
           $this->c139_idusuario = "0" ; 
        } 
       $sql  .= $virgula." c139_idusuario = $this->c139_idusuario ";
       $virgula = ",";
     }
     if(trim($this->c139_datainiciosub)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c139_datainiciosub_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c139_datainiciosub_dia"] !="") ){ 
       $sql  .= $virgula." c139_datainiciosub = '$this->c139_datainiciosub' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c139_datainiciosub_dia"])){ 
         $sql  .= $virgula." c139_datainiciosub = null ";
         $virgula = ",";
       }
     }
     if(trim($this->c139_datafimsub)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c139_datafimsub_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c139_datafimsub_dia"] !="") ){ 
       $sql  .= $virgula." c139_datafimsub = '$this->c139_datafimsub' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c139_datafimsub_dia"])){ 
         $sql  .= $virgula." c139_datafimsub = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($c139_sequencial!=null){
       $sql .= " c139_sequencial = $this->c139_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->c139_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1013712,'$this->c139_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c139_sequencial"]) || $this->c139_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010862,1013712,'".AddSlashes(pg_result($resaco,$conresaco,'c139_sequencial'))."','$this->c139_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c139_instit"]) || $this->c139_instit != "")
             $resac = db_query("insert into db_acount values($acount,1010862,1013713,'".AddSlashes(pg_result($resaco,$conresaco,'c139_instit'))."','$this->c139_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c139_cgm"]) || $this->c139_cgm != "")
             $resac = db_query("insert into db_acount values($acount,1010862,1013714,'".AddSlashes(pg_result($resaco,$conresaco,'c139_cgm'))."','$this->c139_cgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c139_cgmsubstituto"]) || $this->c139_cgmsubstituto != "")
             $resac = db_query("insert into db_acount values($acount,1010862,1013715,'".AddSlashes(pg_result($resaco,$conresaco,'c139_cgmsubstituto'))."','$this->c139_cgmsubstituto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c139_principal"]) || $this->c139_principal != "")
             $resac = db_query("insert into db_acount values($acount,1010862,1013716,'".AddSlashes(pg_result($resaco,$conresaco,'c139_principal'))."','$this->c139_principal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c139_substituto"]) || $this->c139_substituto != "")
             $resac = db_query("insert into db_acount values($acount,1010862,1013717,'".AddSlashes(pg_result($resaco,$conresaco,'c139_substituto'))."','$this->c139_substituto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c139_datainicio"]) || $this->c139_datainicio != "")
             $resac = db_query("insert into db_acount values($acount,1010862,1013718,'".AddSlashes(pg_result($resaco,$conresaco,'c139_datainicio'))."','$this->c139_datainicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c139_datafim"]) || $this->c139_datafim != "")
             $resac = db_query("insert into db_acount values($acount,1010862,1013719,'".AddSlashes(pg_result($resaco,$conresaco,'c139_datafim'))."','$this->c139_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c139_tipoatojuridico"]) || $this->c139_tipoatojuridico != "")
             $resac = db_query("insert into db_acount values($acount,1010862,1013720,'".AddSlashes(pg_result($resaco,$conresaco,'c139_tipoatojuridico'))."','$this->c139_tipoatojuridico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c139_titulo"]) || $this->c139_titulo != "")
             $resac = db_query("insert into db_acount values($acount,1010862,1013721,'".AddSlashes(pg_result($resaco,$conresaco,'c139_titulo'))."','$this->c139_titulo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c139_ativo"]) || $this->c139_ativo != "")
             $resac = db_query("insert into db_acount values($acount,1010862,1013722,'".AddSlashes(pg_result($resaco,$conresaco,'c139_ativo'))."','$this->c139_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c139_datainatividade"]) || $this->c139_datainatividade != "")
             $resac = db_query("insert into db_acount values($acount,1010862,1013723,'".AddSlashes(pg_result($resaco,$conresaco,'c139_datainatividade'))."','$this->c139_datainatividade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c139_idusuario"]) || $this->c139_idusuario != "")
             $resac = db_query("insert into db_acount values($acount,1010862,1013724,'".AddSlashes(pg_result($resaco,$conresaco,'c139_idusuario'))."','$this->c139_idusuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c139_datainiciosub"]) || $this->c139_datainiciosub != "")
             $resac = db_query("insert into db_acount values($acount,1010862,1013986,'".AddSlashes(pg_result($resaco,$conresaco,'c139_datainiciosub'))."','$this->c139_datainiciosub',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c139_datafimsub"]) || $this->c139_datafimsub != "")
             $resac = db_query("insert into db_acount values($acount,1010862,1013987,'".AddSlashes(pg_result($resaco,$conresaco,'c139_datafimsub'))."','$this->c139_datafimsub',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro Ordenador de Despesa do SAGRES não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c139_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro Ordenador de Despesa do SAGRES não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c139_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->c139_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($c139_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($c139_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1013712,'$c139_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010862,1013712,'','".AddSlashes(pg_result($resaco,$iresaco,'c139_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010862,1013713,'','".AddSlashes(pg_result($resaco,$iresaco,'c139_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010862,1013714,'','".AddSlashes(pg_result($resaco,$iresaco,'c139_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010862,1013715,'','".AddSlashes(pg_result($resaco,$iresaco,'c139_cgmsubstituto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010862,1013716,'','".AddSlashes(pg_result($resaco,$iresaco,'c139_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010862,1013717,'','".AddSlashes(pg_result($resaco,$iresaco,'c139_substituto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010862,1013718,'','".AddSlashes(pg_result($resaco,$iresaco,'c139_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010862,1013719,'','".AddSlashes(pg_result($resaco,$iresaco,'c139_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010862,1013720,'','".AddSlashes(pg_result($resaco,$iresaco,'c139_tipoatojuridico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010862,1013721,'','".AddSlashes(pg_result($resaco,$iresaco,'c139_titulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010862,1013722,'','".AddSlashes(pg_result($resaco,$iresaco,'c139_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010862,1013723,'','".AddSlashes(pg_result($resaco,$iresaco,'c139_datainatividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010862,1013724,'','".AddSlashes(pg_result($resaco,$iresaco,'c139_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010862,1013986,'','".AddSlashes(pg_result($resaco,$iresaco,'c139_datainiciosub'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010862,1013987,'','".AddSlashes(pg_result($resaco,$iresaco,'c139_datafimsub'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from sagresordenadordespesa
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($c139_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " c139_sequencial = $c139_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro Ordenador de Despesa do SAGRES não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c139_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro Ordenador de Despesa do SAGRES não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c139_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$c139_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:sagresordenadordespesa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($c139_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from sagresordenadordespesa ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = sagresordenadordespesa.c139_cgm";
     $sql .= "      left  join cgm as cgmsubstituto  on  cgmsubstituto.z01_numcgm = sagresordenadordespesa.c139_cgmsubstituto";
     $sql .= "      inner join db_config  on  db_config.codigo = sagresordenadordespesa.c139_instit";
     $sql .= "      left  join db_usuarios  on  db_usuarios.id_usuario = sagresordenadordespesa.c139_idusuario";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($c139_sequencial)) {
         $sql2 .= " where sagresordenadordespesa.c139_sequencial = $c139_sequencial "; 
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

    public function sql_query_file($c139_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from sagresordenadordespesa ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($c139_sequencial)){
         $sql2 .= " where sagresordenadordespesa.c139_sequencial = $c139_sequencial "; 
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
