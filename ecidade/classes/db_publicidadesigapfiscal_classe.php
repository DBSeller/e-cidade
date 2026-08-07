<?php

class cl_publicidadesigapfiscal
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
    public $c136_codigo = 0; 
    public $c136_ano = 0; 
    public $c136_descricao = null; 
    public $c136_data_publicacao_dia = null; 
    public $c136_data_publicacao_mes = null; 
    public $c136_data_publicacao_ano = null; 
    public $c136_data_publicacao = null; 
    public $c136_tipo_relatorio = 0; 
    public $c136_meio_comunicacao = 0; 
    public $c136_periodo = 0; 
    public $c136_link = null; 
    public $c136_local_publicacao = null; 
    public $c136_instituicao = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 c136_codigo = int4 = Código 
                 c136_ano = int4 = Ano 
                 c136_descricao = varchar(255) = Descrição 
                 c136_data_publicacao = date = Data da Publicação 
                 c136_tipo_relatorio = int4 = Tipo do Relatório 
                 c136_meio_comunicacao = int4 = Meio de Comunicação 
                 c136_periodo = int4 = Período 
                 c136_link = text = Link da Transparência 
                 c136_local_publicacao = text = Local de Publicação 
                 c136_instituicao = int4 = Instituição 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("publicidadesigapfiscal"); 
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
       $this->c136_codigo = ($this->c136_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["c136_codigo"]:$this->c136_codigo);
       $this->c136_ano = ($this->c136_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c136_ano"]:$this->c136_ano);
       $this->c136_descricao = ($this->c136_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["c136_descricao"]:$this->c136_descricao);
       if($this->c136_data_publicacao == ""){
         $this->c136_data_publicacao_dia = ($this->c136_data_publicacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c136_data_publicacao_dia"]:$this->c136_data_publicacao_dia);
         $this->c136_data_publicacao_mes = ($this->c136_data_publicacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c136_data_publicacao_mes"]:$this->c136_data_publicacao_mes);
         $this->c136_data_publicacao_ano = ($this->c136_data_publicacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c136_data_publicacao_ano"]:$this->c136_data_publicacao_ano);
         if($this->c136_data_publicacao_dia != ""){
            $this->c136_data_publicacao = $this->c136_data_publicacao_ano."-".$this->c136_data_publicacao_mes."-".$this->c136_data_publicacao_dia;
         }
       }
       $this->c136_tipo_relatorio = ($this->c136_tipo_relatorio == ""?@$GLOBALS["HTTP_POST_VARS"]["c136_tipo_relatorio"]:$this->c136_tipo_relatorio);
       $this->c136_meio_comunicacao = ($this->c136_meio_comunicacao == ""?@$GLOBALS["HTTP_POST_VARS"]["c136_meio_comunicacao"]:$this->c136_meio_comunicacao);
       $this->c136_periodo = ($this->c136_periodo == ""?@$GLOBALS["HTTP_POST_VARS"]["c136_periodo"]:$this->c136_periodo);
       $this->c136_link = ($this->c136_link == ""?@$GLOBALS["HTTP_POST_VARS"]["c136_link"]:$this->c136_link);
       $this->c136_local_publicacao = ($this->c136_local_publicacao == ""?@$GLOBALS["HTTP_POST_VARS"]["c136_local_publicacao"]:$this->c136_local_publicacao);
       $this->c136_instituicao = ($this->c136_instituicao == ""?@$GLOBALS["HTTP_POST_VARS"]["c136_instituicao"]:$this->c136_instituicao);
     }else{
       $this->c136_codigo = ($this->c136_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["c136_codigo"]:$this->c136_codigo);
     }
   }

    public function incluir($c136_codigo)
    {
      $this->atualizacampos();
     if($this->c136_ano == null ){ 
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "c136_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c136_descricao == null ){ 
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "c136_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c136_data_publicacao == null ){ 
       $this->erro_sql = " Campo Data da Publicação não informado.";
       $this->erro_campo = "c136_data_publicacao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c136_tipo_relatorio == null ){ 
       $this->erro_sql = " Campo Tipo do Relatório não informado.";
       $this->erro_campo = "c136_tipo_relatorio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c136_meio_comunicacao == null ){ 
       $this->erro_sql = " Campo Meio de Comunicação não informado.";
       $this->erro_campo = "c136_meio_comunicacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c136_periodo == null ){ 
       $this->erro_sql = " Campo Período não informado.";
       $this->erro_campo = "c136_periodo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c136_instituicao == null ){ 
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "c136_instituicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c136_codigo == "" || $c136_codigo == null ){
       $result = db_query("select nextval('publicidadesigapfiscal_c136_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: publicidadesigapfiscal_c136_codigo_seq do campo: c136_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c136_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from publicidadesigapfiscal_c136_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $c136_codigo)){
         $this->erro_sql = " Campo c136_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c136_codigo = $c136_codigo; 
       }
     }
     if(($this->c136_codigo == null) || ($this->c136_codigo == "") ){ 
       $this->erro_sql = " Campo c136_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into publicidadesigapfiscal(
                                       c136_codigo 
                                      ,c136_ano 
                                      ,c136_descricao 
                                      ,c136_data_publicacao 
                                      ,c136_tipo_relatorio 
                                      ,c136_meio_comunicacao 
                                      ,c136_periodo 
                                      ,c136_link 
                                      ,c136_local_publicacao 
                                      ,c136_instituicao 
                       )
                values (
                                $this->c136_codigo 
                               ,$this->c136_ano 
                               ,'$this->c136_descricao' 
                               ,".($this->c136_data_publicacao == "null" || $this->c136_data_publicacao == ""?"null":"'".$this->c136_data_publicacao."'")." 
                               ,$this->c136_tipo_relatorio 
                               ,$this->c136_meio_comunicacao 
                               ,$this->c136_periodo 
                               ,'$this->c136_link' 
                               ,'$this->c136_local_publicacao' 
                               ,$this->c136_instituicao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Publicidade Sigap Fiscal ($this->c136_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Publicidade Sigap Fiscal já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Publicidade Sigap Fiscal ($this->c136_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->c136_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->c136_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1011320,'$this->c136_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010572,1011320,'','".AddSlashes(pg_result($resaco,0,'c136_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010572,1011321,'','".AddSlashes(pg_result($resaco,0,'c136_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010572,1011328,'','".AddSlashes(pg_result($resaco,0,'c136_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010572,1011323,'','".AddSlashes(pg_result($resaco,0,'c136_data_publicacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010572,1011322,'','".AddSlashes(pg_result($resaco,0,'c136_tipo_relatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010572,1011324,'','".AddSlashes(pg_result($resaco,0,'c136_meio_comunicacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010572,1011325,'','".AddSlashes(pg_result($resaco,0,'c136_periodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010572,1011326,'','".AddSlashes(pg_result($resaco,0,'c136_link'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010572,1011327,'','".AddSlashes(pg_result($resaco,0,'c136_local_publicacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010572,1011334,'','".AddSlashes(pg_result($resaco,0,'c136_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($c136_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update publicidadesigapfiscal set ";
     $virgula = "";
     if(trim($this->c136_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c136_codigo"])){ 
       $sql  .= $virgula." c136_codigo = $this->c136_codigo ";
       $virgula = ",";
       if(trim($this->c136_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "c136_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c136_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c136_ano"])){ 
       $sql  .= $virgula." c136_ano = $this->c136_ano ";
       $virgula = ",";
       if(trim($this->c136_ano) == null ){ 
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "c136_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c136_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c136_descricao"])){ 
       $sql  .= $virgula." c136_descricao = '$this->c136_descricao' ";
       $virgula = ",";
       if(trim($this->c136_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "c136_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c136_data_publicacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c136_data_publicacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c136_data_publicacao_dia"] !="") ){ 
       $sql  .= $virgula." c136_data_publicacao = '$this->c136_data_publicacao' ";
       $virgula = ",";
       if(trim($this->c136_data_publicacao) == null ){ 
         $this->erro_sql = " Campo Data da Publicação não informado.";
         $this->erro_campo = "c136_data_publicacao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c136_data_publicacao_dia"])){ 
         $sql  .= $virgula." c136_data_publicacao = null ";
         $virgula = ",";
         if(trim($this->c136_data_publicacao) == null ){ 
           $this->erro_sql = " Campo Data da Publicação não informado.";
           $this->erro_campo = "c136_data_publicacao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->c136_tipo_relatorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c136_tipo_relatorio"])){ 
       $sql  .= $virgula." c136_tipo_relatorio = $this->c136_tipo_relatorio ";
       $virgula = ",";
       if(trim($this->c136_tipo_relatorio) == null ){ 
         $this->erro_sql = " Campo Tipo do Relatório não informado.";
         $this->erro_campo = "c136_tipo_relatorio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c136_meio_comunicacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c136_meio_comunicacao"])){ 
       $sql  .= $virgula." c136_meio_comunicacao = $this->c136_meio_comunicacao ";
       $virgula = ",";
       if(trim($this->c136_meio_comunicacao) == null ){ 
         $this->erro_sql = " Campo Meio de Comunicação não informado.";
         $this->erro_campo = "c136_meio_comunicacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c136_periodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c136_periodo"])){ 
       $sql  .= $virgula." c136_periodo = $this->c136_periodo ";
       $virgula = ",";
       if(trim($this->c136_periodo) == null ){ 
         $this->erro_sql = " Campo Período não informado.";
         $this->erro_campo = "c136_periodo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c136_link)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c136_link"])){ 
       $sql  .= $virgula." c136_link = '$this->c136_link' ";
       $virgula = ",";
     }
     if(trim($this->c136_local_publicacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c136_local_publicacao"])){ 
       $sql  .= $virgula." c136_local_publicacao = '$this->c136_local_publicacao' ";
       $virgula = ",";
     }
     if(trim($this->c136_instituicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c136_instituicao"])){ 
       $sql  .= $virgula." c136_instituicao = $this->c136_instituicao ";
       $virgula = ",";
       if(trim($this->c136_instituicao) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "c136_instituicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c136_codigo!=null){
       $sql .= " c136_codigo = $this->c136_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->c136_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1011320,'$this->c136_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c136_codigo"]) || $this->c136_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010572,1011320,'".AddSlashes(pg_result($resaco,$conresaco,'c136_codigo'))."','$this->c136_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c136_ano"]) || $this->c136_ano != "")
             $resac = db_query("insert into db_acount values($acount,1010572,1011321,'".AddSlashes(pg_result($resaco,$conresaco,'c136_ano'))."','$this->c136_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c136_descricao"]) || $this->c136_descricao != "")
             $resac = db_query("insert into db_acount values($acount,1010572,1011328,'".AddSlashes(pg_result($resaco,$conresaco,'c136_descricao'))."','$this->c136_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c136_data_publicacao"]) || $this->c136_data_publicacao != "")
             $resac = db_query("insert into db_acount values($acount,1010572,1011323,'".AddSlashes(pg_result($resaco,$conresaco,'c136_data_publicacao'))."','$this->c136_data_publicacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c136_tipo_relatorio"]) || $this->c136_tipo_relatorio != "")
             $resac = db_query("insert into db_acount values($acount,1010572,1011322,'".AddSlashes(pg_result($resaco,$conresaco,'c136_tipo_relatorio'))."','$this->c136_tipo_relatorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c136_meio_comunicacao"]) || $this->c136_meio_comunicacao != "")
             $resac = db_query("insert into db_acount values($acount,1010572,1011324,'".AddSlashes(pg_result($resaco,$conresaco,'c136_meio_comunicacao'))."','$this->c136_meio_comunicacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c136_periodo"]) || $this->c136_periodo != "")
             $resac = db_query("insert into db_acount values($acount,1010572,1011325,'".AddSlashes(pg_result($resaco,$conresaco,'c136_periodo'))."','$this->c136_periodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c136_link"]) || $this->c136_link != "")
             $resac = db_query("insert into db_acount values($acount,1010572,1011326,'".AddSlashes(pg_result($resaco,$conresaco,'c136_link'))."','$this->c136_link',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c136_local_publicacao"]) || $this->c136_local_publicacao != "")
             $resac = db_query("insert into db_acount values($acount,1010572,1011327,'".AddSlashes(pg_result($resaco,$conresaco,'c136_local_publicacao'))."','$this->c136_local_publicacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c136_instituicao"]) || $this->c136_instituicao != "")
             $resac = db_query("insert into db_acount values($acount,1010572,1011334,'".AddSlashes(pg_result($resaco,$conresaco,'c136_instituicao'))."','$this->c136_instituicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Publicidade Sigap Fiscal não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c136_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Publicidade Sigap Fiscal não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c136_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->c136_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($c136_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($c136_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1011320,'$c136_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010572,1011320,'','".AddSlashes(pg_result($resaco,$iresaco,'c136_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010572,1011321,'','".AddSlashes(pg_result($resaco,$iresaco,'c136_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010572,1011328,'','".AddSlashes(pg_result($resaco,$iresaco,'c136_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010572,1011323,'','".AddSlashes(pg_result($resaco,$iresaco,'c136_data_publicacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010572,1011322,'','".AddSlashes(pg_result($resaco,$iresaco,'c136_tipo_relatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010572,1011324,'','".AddSlashes(pg_result($resaco,$iresaco,'c136_meio_comunicacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010572,1011325,'','".AddSlashes(pg_result($resaco,$iresaco,'c136_periodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010572,1011326,'','".AddSlashes(pg_result($resaco,$iresaco,'c136_link'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010572,1011327,'','".AddSlashes(pg_result($resaco,$iresaco,'c136_local_publicacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010572,1011334,'','".AddSlashes(pg_result($resaco,$iresaco,'c136_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from publicidadesigapfiscal
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($c136_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " c136_codigo = $c136_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Publicidade Sigap Fiscal não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c136_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Publicidade Sigap Fiscal não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c136_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$c136_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:publicidadesigapfiscal";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($c136_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from publicidadesigapfiscal ";
     $sql .= "      inner join db_config  on  db_config.codigo = publicidadesigapfiscal.c136_instituicao";
     $sql .= "      inner join periodo  on  periodo.o114_sequencial = publicidadesigapfiscal.c136_periodo";
     $sql .= "      inner join meiocomunicacaosigap  on  meiocomunicacaosigap.c49_sequencial = publicidadesigapfiscal.c136_meio_comunicacao";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($c136_codigo)) {
         $sql2 .= " where publicidadesigapfiscal.c136_codigo = $c136_codigo "; 
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

    public function sql_query_file($c136_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from publicidadesigapfiscal ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($c136_codigo)){
         $sql2 .= " where publicidadesigapfiscal.c136_codigo = $c136_codigo "; 
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
