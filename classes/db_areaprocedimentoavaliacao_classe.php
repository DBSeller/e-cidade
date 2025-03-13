<?php

class cl_areaprocedimentoavaliacao
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
    public $ed158_codigo = 0; 
    public $ed158_areaprocedimento = 0; 
    public $ed158_formaavaliacao = 0; 
    public $ed158_periodoavaliacao = 0; 
    public $ed158_tipo = null; 
    public $ed158_ordem_elemento = 0; 
    public $ed158_formaobtencao = null; 
    public $ed158_peso = 0; 
    public $ed158_ordem = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 ed158_codigo = int4 = Código 
                 ed158_areaprocedimento = int4 = Procedimento da área 
                 ed158_formaavaliacao = int4 = Forma de avaliação 
                 ed158_periodoavaliacao = int4 = Período de Avaliação 
                 ed158_tipo = char(1) = Tipo do Elemento 
                 ed158_ordem_elemento = int4 = Ordem do Elemento 
                 ed158_formaobtencao = char(10) = Forma de Cálculo 
                 ed158_peso = int4 = Peso da Avaliação 
                 ed158_ordem = int4 = Ordem do Elemento 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("areaprocedimentoavaliacao"); 
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
       $this->ed158_codigo = ($this->ed158_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed158_codigo"]:$this->ed158_codigo);
       $this->ed158_areaprocedimento = ($this->ed158_areaprocedimento == ""?@$GLOBALS["HTTP_POST_VARS"]["ed158_areaprocedimento"]:$this->ed158_areaprocedimento);
       $this->ed158_formaavaliacao = ($this->ed158_formaavaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed158_formaavaliacao"]:$this->ed158_formaavaliacao);
       $this->ed158_periodoavaliacao = ($this->ed158_periodoavaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed158_periodoavaliacao"]:$this->ed158_periodoavaliacao);
       $this->ed158_tipo = ($this->ed158_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed158_tipo"]:$this->ed158_tipo);
       $this->ed158_ordem_elemento = ($this->ed158_ordem_elemento == ""?@$GLOBALS["HTTP_POST_VARS"]["ed158_ordem_elemento"]:$this->ed158_ordem_elemento);
       $this->ed158_formaobtencao = ($this->ed158_formaobtencao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed158_formaobtencao"]:$this->ed158_formaobtencao);
       $this->ed158_peso = ($this->ed158_peso == ""?@$GLOBALS["HTTP_POST_VARS"]["ed158_peso"]:$this->ed158_peso);
       $this->ed158_ordem = ($this->ed158_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["ed158_ordem"]:$this->ed158_ordem);
     }else{
       $this->ed158_codigo = ($this->ed158_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed158_codigo"]:$this->ed158_codigo);
     }
   }

    public function incluir($ed158_codigo)
    {
      $this->atualizacampos();
     if($this->ed158_areaprocedimento == null ){ 
       $this->erro_sql = " Campo Procedimento da área não informado.";
       $this->erro_campo = "ed158_areaprocedimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed158_formaavaliacao == null ){ 
       $this->erro_sql = " Campo Forma de avaliação não informado.";
       $this->erro_campo = "ed158_formaavaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed158_periodoavaliacao == null ){ 
       $this->erro_sql = " Campo Período de Avaliação não informado.";
       $this->erro_campo = "ed158_periodoavaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed158_tipo == null ){ 
       $this->erro_sql = " Campo Tipo do Elemento não informado.";
       $this->erro_campo = "ed158_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed158_ordem_elemento == null ){ 
       $this->erro_sql = " Campo Ordem do Elemento não informado.";
       $this->erro_campo = "ed158_ordem_elemento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed158_formaobtencao == null ){ 
       $this->erro_sql = " Campo Forma de Cálculo não informado.";
       $this->erro_campo = "ed158_formaobtencao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed158_peso == null ){ 
       $this->ed158_peso = "0";
     }
     if($this->ed158_ordem == null ){ 
       $this->ed158_ordem = "0";
     }
     if($ed158_codigo == "" || $ed158_codigo == null ){
       $result = db_query("select nextval('areaprocedimentoavaliacao_ed158_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: areaprocedimentoavaliacao_ed158_codigo_seq do campo: ed158_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed158_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from areaprocedimentoavaliacao_ed158_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed158_codigo)){
         $this->erro_sql = " Campo ed158_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed158_codigo = $ed158_codigo; 
       }
     }
     if(($this->ed158_codigo == null) || ($this->ed158_codigo == "") ){ 
       $this->erro_sql = " Campo ed158_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into areaprocedimentoavaliacao(
                                       ed158_codigo 
                                      ,ed158_areaprocedimento 
                                      ,ed158_formaavaliacao 
                                      ,ed158_periodoavaliacao 
                                      ,ed158_tipo 
                                      ,ed158_ordem_elemento 
                                      ,ed158_formaobtencao 
                                      ,ed158_peso 
                                      ,ed158_ordem 
                       )
                values (
                                $this->ed158_codigo 
                               ,$this->ed158_areaprocedimento 
                               ,$this->ed158_formaavaliacao 
                               ,$this->ed158_periodoavaliacao 
                               ,'$this->ed158_tipo' 
                               ,$this->ed158_ordem_elemento 
                               ,'$this->ed158_formaobtencao' 
                               ,$this->ed158_peso 
                               ,$this->ed158_ordem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Avaliação da área ($this->ed158_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Avaliação da área já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Avaliação da área ($this->ed158_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed158_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed158_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1011093,'$this->ed158_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010534,1011093,'','".AddSlashes(pg_result($resaco,0,'ed158_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010534,1011094,'','".AddSlashes(pg_result($resaco,0,'ed158_areaprocedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010534,1011095,'','".AddSlashes(pg_result($resaco,0,'ed158_formaavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010534,1011096,'','".AddSlashes(pg_result($resaco,0,'ed158_periodoavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010534,1011097,'','".AddSlashes(pg_result($resaco,0,'ed158_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010534,1011098,'','".AddSlashes(pg_result($resaco,0,'ed158_ordem_elemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010534,1011099,'','".AddSlashes(pg_result($resaco,0,'ed158_formaobtencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010534,1011100,'','".AddSlashes(pg_result($resaco,0,'ed158_peso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010534,1011101,'','".AddSlashes(pg_result($resaco,0,'ed158_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($ed158_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update areaprocedimentoavaliacao set ";
     $virgula = "";
     if(trim($this->ed158_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed158_codigo"])){ 
       $sql  .= $virgula." ed158_codigo = $this->ed158_codigo ";
       $virgula = ",";
       if(trim($this->ed158_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed158_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed158_areaprocedimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed158_areaprocedimento"])){ 
       $sql  .= $virgula." ed158_areaprocedimento = $this->ed158_areaprocedimento ";
       $virgula = ",";
       if(trim($this->ed158_areaprocedimento) == null ){ 
         $this->erro_sql = " Campo Procedimento da área não informado.";
         $this->erro_campo = "ed158_areaprocedimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed158_formaavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed158_formaavaliacao"])){ 
       $sql  .= $virgula." ed158_formaavaliacao = $this->ed158_formaavaliacao ";
       $virgula = ",";
       if(trim($this->ed158_formaavaliacao) == null ){ 
         $this->erro_sql = " Campo Forma de avaliação não informado.";
         $this->erro_campo = "ed158_formaavaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed158_periodoavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed158_periodoavaliacao"])){ 
       $sql  .= $virgula." ed158_periodoavaliacao = $this->ed158_periodoavaliacao ";
       $virgula = ",";
       if(trim($this->ed158_periodoavaliacao) == null ){ 
         $this->erro_sql = " Campo Período de Avaliação não informado.";
         $this->erro_campo = "ed158_periodoavaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed158_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed158_tipo"])){ 
       $sql  .= $virgula." ed158_tipo = '$this->ed158_tipo' ";
       $virgula = ",";
       if(trim($this->ed158_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo do Elemento não informado.";
         $this->erro_campo = "ed158_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed158_ordem_elemento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed158_ordem_elemento"])){ 
       $sql  .= $virgula." ed158_ordem_elemento = $this->ed158_ordem_elemento ";
       $virgula = ",";
       if(trim($this->ed158_ordem_elemento) == null ){ 
         $this->erro_sql = " Campo Ordem do Elemento não informado.";
         $this->erro_campo = "ed158_ordem_elemento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed158_formaobtencao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed158_formaobtencao"])){ 
       $sql  .= $virgula." ed158_formaobtencao = '$this->ed158_formaobtencao' ";
       $virgula = ",";
       if(trim($this->ed158_formaobtencao) == null ){ 
         $this->erro_sql = " Campo Forma de Cálculo não informado.";
         $this->erro_campo = "ed158_formaobtencao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed158_peso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed158_peso"])){ 
        if(trim($this->ed158_peso)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed158_peso"])){ 
           $this->ed158_peso = "0" ; 
        } 
       $sql  .= $virgula." ed158_peso = $this->ed158_peso ";
       $virgula = ",";
     }
     if(trim($this->ed158_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed158_ordem"])){ 
        if(trim($this->ed158_ordem)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed158_ordem"])){ 
           $this->ed158_ordem = "0" ; 
        } 
       $sql  .= $virgula." ed158_ordem = $this->ed158_ordem ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed158_codigo!=null){
       $sql .= " ed158_codigo = $this->ed158_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed158_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1011093,'$this->ed158_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed158_codigo"]) || $this->ed158_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010534,1011093,'".AddSlashes(pg_result($resaco,$conresaco,'ed158_codigo'))."','$this->ed158_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed158_areaprocedimento"]) || $this->ed158_areaprocedimento != "")
             $resac = db_query("insert into db_acount values($acount,1010534,1011094,'".AddSlashes(pg_result($resaco,$conresaco,'ed158_areaprocedimento'))."','$this->ed158_areaprocedimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed158_formaavaliacao"]) || $this->ed158_formaavaliacao != "")
             $resac = db_query("insert into db_acount values($acount,1010534,1011095,'".AddSlashes(pg_result($resaco,$conresaco,'ed158_formaavaliacao'))."','$this->ed158_formaavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed158_periodoavaliacao"]) || $this->ed158_periodoavaliacao != "")
             $resac = db_query("insert into db_acount values($acount,1010534,1011096,'".AddSlashes(pg_result($resaco,$conresaco,'ed158_periodoavaliacao'))."','$this->ed158_periodoavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed158_tipo"]) || $this->ed158_tipo != "")
             $resac = db_query("insert into db_acount values($acount,1010534,1011097,'".AddSlashes(pg_result($resaco,$conresaco,'ed158_tipo'))."','$this->ed158_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed158_ordem_elemento"]) || $this->ed158_ordem_elemento != "")
             $resac = db_query("insert into db_acount values($acount,1010534,1011098,'".AddSlashes(pg_result($resaco,$conresaco,'ed158_ordem_elemento'))."','$this->ed158_ordem_elemento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed158_formaobtencao"]) || $this->ed158_formaobtencao != "")
             $resac = db_query("insert into db_acount values($acount,1010534,1011099,'".AddSlashes(pg_result($resaco,$conresaco,'ed158_formaobtencao'))."','$this->ed158_formaobtencao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed158_peso"]) || $this->ed158_peso != "")
             $resac = db_query("insert into db_acount values($acount,1010534,1011100,'".AddSlashes(pg_result($resaco,$conresaco,'ed158_peso'))."','$this->ed158_peso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed158_ordem"]) || $this->ed158_ordem != "")
             $resac = db_query("insert into db_acount values($acount,1010534,1011101,'".AddSlashes(pg_result($resaco,$conresaco,'ed158_ordem'))."','$this->ed158_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliação da área não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed158_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Avaliação da área não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed158_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed158_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($ed158_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed158_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1011093,'$ed158_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010534,1011093,'','".AddSlashes(pg_result($resaco,$iresaco,'ed158_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010534,1011094,'','".AddSlashes(pg_result($resaco,$iresaco,'ed158_areaprocedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010534,1011095,'','".AddSlashes(pg_result($resaco,$iresaco,'ed158_formaavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010534,1011096,'','".AddSlashes(pg_result($resaco,$iresaco,'ed158_periodoavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010534,1011097,'','".AddSlashes(pg_result($resaco,$iresaco,'ed158_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010534,1011098,'','".AddSlashes(pg_result($resaco,$iresaco,'ed158_ordem_elemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010534,1011099,'','".AddSlashes(pg_result($resaco,$iresaco,'ed158_formaobtencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010534,1011100,'','".AddSlashes(pg_result($resaco,$iresaco,'ed158_peso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010534,1011101,'','".AddSlashes(pg_result($resaco,$iresaco,'ed158_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from areaprocedimentoavaliacao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed158_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed158_codigo = $ed158_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliação da área não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed158_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Avaliação da área não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed158_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ed158_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:areaprocedimentoavaliacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($ed158_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from areaprocedimentoavaliacao ";
     $sql .= "      inner join periodoavaliacao  on  periodoavaliacao.ed09_i_codigo = areaprocedimentoavaliacao.ed158_periodoavaliacao";
     $sql .= "      inner join formaavaliacao  on  formaavaliacao.ed37_i_codigo = areaprocedimentoavaliacao.ed158_formaavaliacao";
     $sql .= "      inner join areaprocedimento  on  areaprocedimento.ed157_codigo = areaprocedimentoavaliacao.ed158_areaprocedimento";
     $sql .= "      left  join escola  on  escola.ed18_i_codigo = formaavaliacao.ed37_i_escola";
     $sql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = areaprocedimento.ed157_procedimento";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed158_codigo)) {
         $sql2 .= " where areaprocedimentoavaliacao.ed158_codigo = $ed158_codigo "; 
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

    public function sql_query_file($ed158_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from areaprocedimentoavaliacao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed158_codigo)){
         $sql2 .= " where areaprocedimentoavaliacao.ed158_codigo = $ed158_codigo "; 
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
