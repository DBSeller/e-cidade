<?php

class cl_pccampanhapublicitaria
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
    public $pc94_codigo = 0;
    public $pc94_datainicio_dia = null;
    public $pc94_datainicio_mes = null;
    public $pc94_datainicio_ano = null;
    public $pc94_datainicio = null;
    public $pc94_datafim_dia = null;
    public $pc94_datafim_mes = null;
    public $pc94_datafim_ano = null;
    public $pc94_datafim = null;
    public $pc94_pctipocampanhapublicitaria = 0;
    public $pc94_comissaoveiculacao = 0;
    public $pc94_comissaoproducao = 0;
    public $pc94_pcmater = 0;
    public $pc94_cgm = 0;
    public $pc94_valorcampanha = 0;
   // cria propriedade com as variaveis do arquivo
    public $campos = "
                 pc94_codigo = int8 = sequencial das campanhas publicatarias
                 pc94_datainicio = date = Data de início da campanha
                 pc94_datafim = date = Data de fim da campanha
                 pc94_pctipocampanhapublicitaria = int8 = codigo do tipo de campanha publicitaria
                 pc94_comissaoveiculacao = float8 = Comissão sobre serviços de veiculação
                 pc94_comissaoproducao = float8 = Comissao sobre serviços de produção
                 pc94_pcmater = int8 = Sequencial da pcmater
                 pc94_cgm = int8 = Cgm da agência contratada
                 pc94_valorcampanha = float8 = Valor total da campanhaa
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("pccampanhapublicitaria");
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
       $this->pc94_codigo = ($this->pc94_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc94_codigo"]:$this->pc94_codigo);
       if($this->pc94_datainicio == ""){
         $this->pc94_datainicio_dia = ($this->pc94_datainicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc94_datainicio_dia"]:$this->pc94_datainicio_dia);
         $this->pc94_datainicio_mes = ($this->pc94_datainicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc94_datainicio_mes"]:$this->pc94_datainicio_mes);
         $this->pc94_datainicio_ano = ($this->pc94_datainicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc94_datainicio_ano"]:$this->pc94_datainicio_ano);
         if($this->pc94_datainicio_dia != ""){
            $this->pc94_datainicio = $this->pc94_datainicio_ano."-".$this->pc94_datainicio_mes."-".$this->pc94_datainicio_dia;
         }
       }
       if($this->pc94_datafim == ""){
         $this->pc94_datafim_dia = ($this->pc94_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc94_datafim_dia"]:$this->pc94_datafim_dia);
         $this->pc94_datafim_mes = ($this->pc94_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc94_datafim_mes"]:$this->pc94_datafim_mes);
         $this->pc94_datafim_ano = ($this->pc94_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc94_datafim_ano"]:$this->pc94_datafim_ano);
         if($this->pc94_datafim_dia != ""){
            $this->pc94_datafim = $this->pc94_datafim_ano."-".$this->pc94_datafim_mes."-".$this->pc94_datafim_dia;
         }
       }
       $this->pc94_pctipocampanhapublicitaria = ($this->pc94_pctipocampanhapublicitaria == ""?@$GLOBALS["HTTP_POST_VARS"]["pc94_pctipocampanhapublicitaria"]:$this->pc94_pctipocampanhapublicitaria);
       $this->pc94_comissaoveiculacao = ($this->pc94_comissaoveiculacao == ""?@$GLOBALS["HTTP_POST_VARS"]["pc94_comissaoveiculacao"]:$this->pc94_comissaoveiculacao);
       $this->pc94_comissaoproducao = ($this->pc94_comissaoproducao == ""?@$GLOBALS["HTTP_POST_VARS"]["pc94_comissaoproducao"]:$this->pc94_comissaoproducao);
       $this->pc94_pcmater = ($this->pc94_pcmater == ""?@$GLOBALS["HTTP_POST_VARS"]["pc94_pcmater"]:$this->pc94_pcmater);
       $this->pc94_cgm = ($this->pc94_cgm == ""?@$GLOBALS["HTTP_POST_VARS"]["pc94_cgm"]:$this->pc94_cgm);
       $this->pc94_valorcampanha = ($this->pc94_valorcampanha == ""?@$GLOBALS["HTTP_POST_VARS"]["pc94_valorcampanha"]:$this->pc94_valorcampanha);
     }else{
       $this->pc94_codigo = ($this->pc94_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc94_codigo"]:$this->pc94_codigo);
     }
   }

    public function incluir($pc94_codigo = null)
    {
      $this->atualizacampos();
     if($this->pc94_datainicio == null ){
       $this->pc94_datainicio = "null";
     }
     if($this->pc94_datafim == null ){
       $this->pc94_datafim = "null";
     }
     if($this->pc94_pctipocampanhapublicitaria == null ){
       $this->erro_sql = " Campo codigo do tipo de campanha publicitaria não informado.";
       $this->erro_campo = "pc94_pctipocampanhapublicitaria";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc94_comissaoveiculacao == null ){
       $this->pc94_comissaoveiculacao = "0";
     }
     if($this->pc94_comissaoproducao == null ){
       $this->pc94_comissaoproducao = "0";
     }
     if($this->pc94_pcmater == null ){
       $this->erro_sql = " Campo Sequencial da pcmater não informado.";
       $this->erro_campo = "pc94_pcmater";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc94_cgm == null ){
       $this->pc94_cgm = "0";
     }
     if($this->pc94_valorcampanha == null ){
       $this->erro_sql = " Campo Valor total da campanhaa não informado.";
       $this->erro_campo = "pc94_valorcampanha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc94_codigo == "" || $pc94_codigo == null ){
       $result = db_query("select nextval('pccampanhapublicitaria_pc94_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pccampanhapublicitaria_pc94_codigo_seq do campo: pc94_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->pc94_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from pccampanhapublicitaria_pc94_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc94_codigo)){
         $this->erro_sql = " Campo pc94_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc94_codigo = $pc94_codigo;
       }
     }
     if(($this->pc94_codigo == null) || ($this->pc94_codigo == "") ){
       $this->erro_sql = " Campo pc94_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pccampanhapublicitaria(
                                       pc94_codigo
                                      ,pc94_datainicio
                                      ,pc94_datafim
                                      ,pc94_pctipocampanhapublicitaria
                                      ,pc94_comissaoveiculacao
                                      ,pc94_comissaoproducao
                                      ,pc94_pcmater
                                      ,pc94_cgm
                                      ,pc94_valorcampanha
                       )
                values (
                                $this->pc94_codigo
                               ,".($this->pc94_datainicio == "null" || $this->pc94_datainicio == ""?"null":"'".$this->pc94_datainicio."'")."
                               ,".($this->pc94_datafim == "null" || $this->pc94_datafim == ""?"null":"'".$this->pc94_datafim."'")."
                               ,$this->pc94_pctipocampanhapublicitaria
                               ,$this->pc94_comissaoveiculacao
                               ,$this->pc94_comissaoproducao
                               ,$this->pc94_pcmater
                               ,$this->pc94_cgm
                               ,$this->pc94_valorcampanha
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dados relacionados a campanha publicitaria ($this->pc94_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dados relacionados a campanha publicitaria já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dados relacionados a campanha publicitaria ($this->pc94_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->pc94_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->pc94_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1014090,'$this->pc94_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010918,1014090,'','".AddSlashes(pg_result($resaco,0,'pc94_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010918,1014094,'','".AddSlashes(pg_result($resaco,0,'pc94_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010918,1014095,'','".AddSlashes(pg_result($resaco,0,'pc94_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010918,1014100,'','".AddSlashes(pg_result($resaco,0,'pc94_pctipocampanhapublicitaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010918,1014093,'','".AddSlashes(pg_result($resaco,0,'pc94_comissaoveiculacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010918,1014092,'','".AddSlashes(pg_result($resaco,0,'pc94_comissaoproducao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010918,1014101,'','".AddSlashes(pg_result($resaco,0,'pc94_pcmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010918,1014132,'','".AddSlashes(pg_result($resaco,0,'pc94_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010918,1014133,'','".AddSlashes(pg_result($resaco,0,'pc94_valorcampanha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }

    public function alterar($pc94_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update pccampanhapublicitaria set ";
     $virgula = "";
     if(trim($this->pc94_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc94_codigo"])){
       $sql  .= $virgula." pc94_codigo = $this->pc94_codigo ";
       $virgula = ",";
       if(trim($this->pc94_codigo) == null ){
         $this->erro_sql = " Campo sequencial das campanhas publicatarias não informado.";
         $this->erro_campo = "pc94_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc94_datainicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc94_datainicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc94_datainicio_dia"] !="") ){
       $sql  .= $virgula." pc94_datainicio = '$this->pc94_datainicio' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc94_datainicio_dia"])){
         $sql  .= $virgula." pc94_datainicio = null ";
         $virgula = ",";
       }
     }
     if(trim($this->pc94_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc94_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc94_datafim_dia"] !="") ){
       $sql  .= $virgula." pc94_datafim = '$this->pc94_datafim' ";
       $virgula = ",";
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc94_datafim_dia"])){
         $sql  .= $virgula." pc94_datafim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->pc94_pctipocampanhapublicitaria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc94_pctipocampanhapublicitaria"])){
       $sql  .= $virgula." pc94_pctipocampanhapublicitaria = $this->pc94_pctipocampanhapublicitaria ";
       $virgula = ",";
       if(trim($this->pc94_pctipocampanhapublicitaria) == null ){
         $this->erro_sql = " Campo codigo do tipo de campanha publicitaria não informado.";
         $this->erro_campo = "pc94_pctipocampanhapublicitaria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc94_comissaoveiculacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc94_comissaoveiculacao"])){
        if(trim($this->pc94_comissaoveiculacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc94_comissaoveiculacao"])){
           $this->pc94_comissaoveiculacao = "0" ;
        }
       $sql  .= $virgula." pc94_comissaoveiculacao = $this->pc94_comissaoveiculacao ";
       $virgula = ",";
     }
     if(trim($this->pc94_comissaoproducao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc94_comissaoproducao"])){
        if(trim($this->pc94_comissaoproducao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc94_comissaoproducao"])){
           $this->pc94_comissaoproducao = "0" ;
        }
       $sql  .= $virgula." pc94_comissaoproducao = $this->pc94_comissaoproducao ";
       $virgula = ",";
     }
     if(trim($this->pc94_pcmater)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc94_pcmater"])){
       $sql  .= $virgula." pc94_pcmater = $this->pc94_pcmater ";
       $virgula = ",";
       if(trim($this->pc94_pcmater) == null ){
         $this->erro_sql = " Campo Sequencial da pcmater não informado.";
         $this->erro_campo = "pc94_pcmater";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc94_cgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc94_cgm"])){
        if(trim($this->pc94_cgm)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc94_cgm"])){
           $this->pc94_cgm = "0" ;
        }
       $sql  .= $virgula." pc94_cgm = $this->pc94_cgm ";
       $virgula = ",";
     }
     if(trim($this->pc94_valorcampanha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc94_valorcampanha"])){
       $sql  .= $virgula." pc94_valorcampanha = $this->pc94_valorcampanha ";
       $virgula = ",";
       if(trim($this->pc94_valorcampanha) == null ){
         $this->erro_sql = " Campo Valor total da campanhaa não informado.";
         $this->erro_campo = "pc94_valorcampanha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc94_codigo!=null){
       $sql .= " pc94_codigo = $pc94_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->pc94_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1014090,'$this->pc94_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc94_codigo"]) || $this->pc94_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010918,1014090,'".AddSlashes(pg_result($resaco,$conresaco,'pc94_codigo'))."','$this->pc94_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc94_datainicio"]) || $this->pc94_datainicio != "")
             $resac = db_query("insert into db_acount values($acount,1010918,1014094,'".AddSlashes(pg_result($resaco,$conresaco,'pc94_datainicio'))."','$this->pc94_datainicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc94_datafim"]) || $this->pc94_datafim != "")
             $resac = db_query("insert into db_acount values($acount,1010918,1014095,'".AddSlashes(pg_result($resaco,$conresaco,'pc94_datafim'))."','$this->pc94_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc94_pctipocampanhapublicitaria"]) || $this->pc94_pctipocampanhapublicitaria != "")
             $resac = db_query("insert into db_acount values($acount,1010918,1014100,'".AddSlashes(pg_result($resaco,$conresaco,'pc94_pctipocampanhapublicitaria'))."','$this->pc94_pctipocampanhapublicitaria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc94_comissaoveiculacao"]) || $this->pc94_comissaoveiculacao != "")
             $resac = db_query("insert into db_acount values($acount,1010918,1014093,'".AddSlashes(pg_result($resaco,$conresaco,'pc94_comissaoveiculacao'))."','$this->pc94_comissaoveiculacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc94_comissaoproducao"]) || $this->pc94_comissaoproducao != "")
             $resac = db_query("insert into db_acount values($acount,1010918,1014092,'".AddSlashes(pg_result($resaco,$conresaco,'pc94_comissaoproducao'))."','$this->pc94_comissaoproducao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc94_pcmater"]) || $this->pc94_pcmater != "")
             $resac = db_query("insert into db_acount values($acount,1010918,1014101,'".AddSlashes(pg_result($resaco,$conresaco,'pc94_pcmater'))."','$this->pc94_pcmater',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc94_cgm"]) || $this->pc94_cgm != "")
             $resac = db_query("insert into db_acount values($acount,1010918,1014132,'".AddSlashes(pg_result($resaco,$conresaco,'pc94_cgm'))."','$this->pc94_cgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["pc94_valorcampanha"]) || $this->pc94_valorcampanha != "")
             $resac = db_query("insert into db_acount values($acount,1010918,1014133,'".AddSlashes(pg_result($resaco,$conresaco,'pc94_valorcampanha'))."','$this->pc94_valorcampanha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados relacionados a campanha publicitaria não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc94_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Dados relacionados a campanha publicitaria não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc94_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->pc94_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }

    public function excluir($pc94_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($pc94_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1014090,'$pc94_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010918,1014090,'','".AddSlashes(pg_result($resaco,$iresaco,'pc94_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010918,1014094,'','".AddSlashes(pg_result($resaco,$iresaco,'pc94_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010918,1014095,'','".AddSlashes(pg_result($resaco,$iresaco,'pc94_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010918,1014100,'','".AddSlashes(pg_result($resaco,$iresaco,'pc94_pctipocampanhapublicitaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010918,1014093,'','".AddSlashes(pg_result($resaco,$iresaco,'pc94_comissaoveiculacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010918,1014092,'','".AddSlashes(pg_result($resaco,$iresaco,'pc94_comissaoproducao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010918,1014101,'','".AddSlashes(pg_result($resaco,$iresaco,'pc94_pcmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010918,1014132,'','".AddSlashes(pg_result($resaco,$iresaco,'pc94_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010918,1014133,'','".AddSlashes(pg_result($resaco,$iresaco,'pc94_valorcampanha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from pccampanhapublicitaria
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($pc94_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " pc94_codigo = $pc94_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados relacionados a campanha publicitaria não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc94_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Dados relacionados a campanha publicitaria não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc94_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$pc94_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:pccampanhapublicitaria";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($pc94_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from pccampanhapublicitaria ";
     $sql .= "      left  join cgm  on  cgm.z01_numcgm = pccampanhapublicitaria.pc94_cgm";
     $sql .= "      inner join pcmater  on  pcmater.pc01_codmater = pccampanhapublicitaria.pc94_pcmater";
     $sql .= "      inner join pctipocampanhapublicitaria  on  pctipocampanhapublicitaria.pc95_codigo = pccampanhapublicitaria.pc94_pctipocampanhapublicitaria";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = pcmater.pc01_id_usuario";
     $sql .= "      inner join pcsubgrupo  on  pcsubgrupo.pc04_codsubgrupo = pcmater.pc01_codsubgrupo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($pc94_codigo)) {
         $sql2 .= " where pccampanhapublicitaria.pc94_codigo = $pc94_codigo ";
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

    public function sql_query_file($pc94_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from pccampanhapublicitaria ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($pc94_codigo)){
         $sql2 .= " where pccampanhapublicitaria.pc94_codigo = $pc94_codigo ";
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
