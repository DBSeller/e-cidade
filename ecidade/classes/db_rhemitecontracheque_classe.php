<?php

class cl_rhemitecontracheque
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
    public $rh85_sequencial = 0;
    public $rh85_regist = 0;
    public $rh85_anousu = 0;
    public $rh85_mesusu = 0;
    public $rh85_dataemissao_dia = null;
    public $rh85_dataemissao_mes = null;
    public $rh85_dataemissao_ano = null;
    public $rh85_dataemissao = null;
    public $rh85_horaemissao = null;
    public $rh85_sigla = null;
    public $rh85_codautent = null;
    public $rh85_ip = null;
    public $rh85_externo = 'f';
    public $rh85_liquido = 0;
    public $rh85_desconto = 0;
    public $rh85_provento = 0;
    public $rh85_numero = 0;
    public $rh85_instit = null;
    public $rh85_estorage = null;
    public $rh85_tipofolha = null;
   // cria propriedade com as variaveis do arquivo
    public $campos = "
                 rh85_sequencial = int4 = rh85_sequencial
                 rh85_regist = int8 = rh85_regist
                 rh85_anousu = int4 = rh85_anousu
                 rh85_mesusu = int4 = rh85_mesusu
                 rh85_dataemissao = date = rh85_dataemissao
                 rh85_horaemissao = char(5) = rh85_horaemissao
                 rh85_sigla = char(3) = rh85_sigla
                 rh85_codautent = varchar(20) = rh85_codautent
                 rh85_ip = varchar(15) = rh85_ip
                 rh85_externo = bool = rh85_externo
                 rh85_liquido = float8 = Valor Líquido
                 rh85_desconto = float8 = Valor Desconto
                 rh85_provento = float8 = Total Provento
                 rh85_numero = int4 = Numero
                 rh85_instit = int4 = Instituição
                 rh85_estorage = int4 = Código arquivo
                 rh85_tipofolha = int4 = Tipo da Folha
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhemitecontracheque");
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
       $this->rh85_sequencial = ($this->rh85_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_sequencial"]:$this->rh85_sequencial);
       $this->rh85_regist = ($this->rh85_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_regist"]:$this->rh85_regist);
       $this->rh85_anousu = ($this->rh85_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_anousu"]:$this->rh85_anousu);
       $this->rh85_mesusu = ($this->rh85_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_mesusu"]:$this->rh85_mesusu);
       if($this->rh85_dataemissao == ""){
         $this->rh85_dataemissao_dia = ($this->rh85_dataemissao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_dataemissao_dia"]:$this->rh85_dataemissao_dia);
         $this->rh85_dataemissao_mes = ($this->rh85_dataemissao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_dataemissao_mes"]:$this->rh85_dataemissao_mes);
         $this->rh85_dataemissao_ano = ($this->rh85_dataemissao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_dataemissao_ano"]:$this->rh85_dataemissao_ano);
         if($this->rh85_dataemissao_dia != ""){
            $this->rh85_dataemissao = $this->rh85_dataemissao_ano."-".$this->rh85_dataemissao_mes."-".$this->rh85_dataemissao_dia;
         }
       }
       $this->rh85_horaemissao = ($this->rh85_horaemissao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_horaemissao"]:$this->rh85_horaemissao);
       $this->rh85_sigla = ($this->rh85_sigla == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_sigla"]:$this->rh85_sigla);
       $this->rh85_codautent = ($this->rh85_codautent == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_codautent"]:$this->rh85_codautent);
       $this->rh85_ip = ($this->rh85_ip == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_ip"]:$this->rh85_ip);
       $this->rh85_externo = ($this->rh85_externo == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh85_externo"]:$this->rh85_externo);
       $this->rh85_liquido = ($this->rh85_liquido == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_liquido"]:$this->rh85_liquido);
       $this->rh85_desconto = ($this->rh85_desconto == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_desconto"]:$this->rh85_desconto);
       $this->rh85_provento = ($this->rh85_provento == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_provento"]:$this->rh85_provento);
       $this->rh85_numero = ($this->rh85_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_numero"]:$this->rh85_numero);
       $this->rh85_instit = ($this->rh85_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_instit"]:$this->rh85_instit);
       $this->rh85_estorage = ($this->rh85_estorage == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_estorage"]:$this->rh85_estorage);
       $this->rh85_tipofolha = ($this->rh85_tipofolha == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_tipofolha"]:$this->rh85_tipofolha);
     }else{
       $this->rh85_sequencial = ($this->rh85_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh85_sequencial"]:$this->rh85_sequencial);
     }
   }

    public function incluir($rh85_sequencial)
    {
      $this->atualizacampos();
     if($this->rh85_regist == null ){
       $this->erro_sql = " Campo rh85_regist não informado.";
       $this->erro_campo = "rh85_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh85_anousu == null ){
       $this->erro_sql = " Campo rh85_anousu não informado.";
       $this->erro_campo = "rh85_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh85_mesusu == null ){
       $this->erro_sql = " Campo rh85_mesusu não informado.";
       $this->erro_campo = "rh85_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh85_dataemissao == null ){
       $this->erro_sql = " Campo rh85_dataemissao não informado.";
       $this->erro_campo = "rh85_dataemissao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh85_horaemissao == null ){
       $this->erro_sql = " Campo rh85_horaemissao não informado.";
       $this->erro_campo = "rh85_horaemissao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh85_sigla == null ){
       $this->erro_sql = " Campo rh85_sigla não informado.";
       $this->erro_campo = "rh85_sigla";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh85_codautent == null ){
       $this->erro_sql = " Campo rh85_codautent não informado.";
       $this->erro_campo = "rh85_codautent";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh85_ip == null ){
       $this->erro_sql = " Campo rh85_ip não informado.";
       $this->erro_campo = "rh85_ip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh85_externo == null ){
       $this->erro_sql = " Campo rh85_externo não informado.";
       $this->erro_campo = "rh85_externo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh85_liquido == null ){
       $this->rh85_liquido = "0";
     }
     if($this->rh85_desconto == null ){
       $this->rh85_desconto = "0";
     }
     if($this->rh85_provento == null ){
       $this->rh85_provento = "0";
     }
     if($this->rh85_numero == null ){
       $this->rh85_numero = "0";
     }
     if($this->rh85_instit == null ){
       $this->rh85_instit = "null";
     }
     if($this->rh85_estorage == null ){
       $this->rh85_estorage = "null";
     }
     if($this->rh85_tipofolha == null ){
       $this->rh85_tipofolha = "null";
     }
     if($rh85_sequencial == "" || $rh85_sequencial == null ){
       $result = db_query("select nextval('rhemitecontracheque_rh85_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhemitecontracheque_rh85_sequencial_seq do campo: rh85_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->rh85_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from rhemitecontracheque_rh85_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh85_sequencial)){
         $this->erro_sql = " Campo rh85_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh85_sequencial = $rh85_sequencial;
       }
     }
     if(($this->rh85_sequencial == null) || ($this->rh85_sequencial == "") ){
       $this->erro_sql = " Campo rh85_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhemitecontracheque(
                                       rh85_sequencial
                                      ,rh85_regist
                                      ,rh85_anousu
                                      ,rh85_mesusu
                                      ,rh85_dataemissao
                                      ,rh85_horaemissao
                                      ,rh85_sigla
                                      ,rh85_codautent
                                      ,rh85_ip
                                      ,rh85_externo
                                      ,rh85_liquido
                                      ,rh85_desconto
                                      ,rh85_provento
                                      ,rh85_numero
                                      ,rh85_instit
                                      ,rh85_estorage
                                      ,rh85_tipofolha
                       )
                values (
                                $this->rh85_sequencial
                               ,$this->rh85_regist
                               ,$this->rh85_anousu
                               ,$this->rh85_mesusu
                               ,".($this->rh85_dataemissao == "null" || $this->rh85_dataemissao == ""?"null":"'".$this->rh85_dataemissao."'")."
                               ,'$this->rh85_horaemissao'
                               ,'$this->rh85_sigla'
                               ,'$this->rh85_codautent'
                               ,'$this->rh85_ip'
                               ,'$this->rh85_externo'
                               ,$this->rh85_liquido
                               ,$this->rh85_desconto
                               ,$this->rh85_provento
                               ,$this->rh85_numero
                               ,$this->rh85_instit
                               ,$this->rh85_estorage
                               ,$this->rh85_tipofolha
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rhemitecontracheque ($this->rh85_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rhemitecontracheque já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rhemitecontracheque ($this->rh85_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh85_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh85_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14571,'$this->rh85_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,2563,14571,'','".AddSlashes(pg_result($resaco,0,'rh85_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2563,14562,'','".AddSlashes(pg_result($resaco,0,'rh85_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2563,14563,'','".AddSlashes(pg_result($resaco,0,'rh85_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2563,14564,'','".AddSlashes(pg_result($resaco,0,'rh85_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2563,14565,'','".AddSlashes(pg_result($resaco,0,'rh85_dataemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2563,14566,'','".AddSlashes(pg_result($resaco,0,'rh85_horaemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2563,14567,'','".AddSlashes(pg_result($resaco,0,'rh85_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2563,14568,'','".AddSlashes(pg_result($resaco,0,'rh85_codautent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2563,14569,'','".AddSlashes(pg_result($resaco,0,'rh85_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2563,14570,'','".AddSlashes(pg_result($resaco,0,'rh85_externo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2563,22161,'','".AddSlashes(pg_result($resaco,0,'rh85_liquido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2563,22162,'','".AddSlashes(pg_result($resaco,0,'rh85_desconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2563,22163,'','".AddSlashes(pg_result($resaco,0,'rh85_provento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2563,1014499,'','".AddSlashes(pg_result($resaco,0,'rh85_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2563,1014500,'','".AddSlashes(pg_result($resaco,0,'rh85_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2563,1014501,'','".AddSlashes(pg_result($resaco,0,'rh85_estorage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2563,1014502,'','".AddSlashes(pg_result($resaco,0,'rh85_tipofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }

    public function alterar($rh85_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhemitecontracheque set ";
     $virgula = "";
     if(trim($this->rh85_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh85_sequencial"])){
       $sql  .= $virgula." rh85_sequencial = $this->rh85_sequencial ";
       $virgula = ",";
       if(trim($this->rh85_sequencial) == null ){
         $this->erro_sql = " Campo rh85_sequencial não informado.";
         $this->erro_campo = "rh85_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh85_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh85_regist"])){
       $sql  .= $virgula." rh85_regist = $this->rh85_regist ";
       $virgula = ",";
       if(trim($this->rh85_regist) == null ){
         $this->erro_sql = " Campo rh85_regist não informado.";
         $this->erro_campo = "rh85_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh85_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh85_anousu"])){
       $sql  .= $virgula." rh85_anousu = $this->rh85_anousu ";
       $virgula = ",";
       if(trim($this->rh85_anousu) == null ){
         $this->erro_sql = " Campo rh85_anousu não informado.";
         $this->erro_campo = "rh85_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh85_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh85_mesusu"])){
       $sql  .= $virgula." rh85_mesusu = $this->rh85_mesusu ";
       $virgula = ",";
       if(trim($this->rh85_mesusu) == null ){
         $this->erro_sql = " Campo rh85_mesusu não informado.";
         $this->erro_campo = "rh85_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh85_dataemissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh85_dataemissao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh85_dataemissao_dia"] !="") ){
       $sql  .= $virgula." rh85_dataemissao = '$this->rh85_dataemissao' ";
       $virgula = ",";
       if(trim($this->rh85_dataemissao) == null ){
         $this->erro_sql = " Campo rh85_dataemissao não informado.";
         $this->erro_campo = "rh85_dataemissao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh85_dataemissao_dia"])){
         $sql  .= $virgula." rh85_dataemissao = null ";
         $virgula = ",";
         if(trim($this->rh85_dataemissao) == null ){
           $this->erro_sql = " Campo rh85_dataemissao não informado.";
           $this->erro_campo = "rh85_dataemissao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh85_horaemissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh85_horaemissao"])){
       $sql  .= $virgula." rh85_horaemissao = '$this->rh85_horaemissao' ";
       $virgula = ",";
       if(trim($this->rh85_horaemissao) == null ){
         $this->erro_sql = " Campo rh85_horaemissao não informado.";
         $this->erro_campo = "rh85_horaemissao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh85_sigla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh85_sigla"])){
       $sql  .= $virgula." rh85_sigla = '$this->rh85_sigla' ";
       $virgula = ",";
       if(trim($this->rh85_sigla) == null ){
         $this->erro_sql = " Campo rh85_sigla não informado.";
         $this->erro_campo = "rh85_sigla";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh85_codautent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh85_codautent"])){
       $sql  .= $virgula." rh85_codautent = '$this->rh85_codautent' ";
       $virgula = ",";
       if(trim($this->rh85_codautent) == null ){
         $this->erro_sql = " Campo rh85_codautent não informado.";
         $this->erro_campo = "rh85_codautent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh85_ip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh85_ip"])){
       $sql  .= $virgula." rh85_ip = '$this->rh85_ip' ";
       $virgula = ",";
       if(trim($this->rh85_ip) == null ){
         $this->erro_sql = " Campo rh85_ip não informado.";
         $this->erro_campo = "rh85_ip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh85_externo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh85_externo"])){
       $sql  .= $virgula." rh85_externo = '$this->rh85_externo' ";
       $virgula = ",";
       if(trim($this->rh85_externo) == null ){
         $this->erro_sql = " Campo rh85_externo não informado.";
         $this->erro_campo = "rh85_externo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh85_liquido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh85_liquido"])){
        if(trim($this->rh85_liquido)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh85_liquido"])){
           $this->rh85_liquido = "0" ;
        }
       $sql  .= $virgula." rh85_liquido = $this->rh85_liquido ";
       $virgula = ",";
     }
     if(trim($this->rh85_desconto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh85_desconto"])){
        if(trim($this->rh85_desconto)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh85_desconto"])){
           $this->rh85_desconto = "0" ;
        }
       $sql  .= $virgula." rh85_desconto = $this->rh85_desconto ";
       $virgula = ",";
     }
     if(trim($this->rh85_provento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh85_provento"])){
        if(trim($this->rh85_provento)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh85_provento"])){
           $this->rh85_provento = "0" ;
        }
       $sql  .= $virgula." rh85_provento = $this->rh85_provento ";
       $virgula = ",";
     }
     if(trim($this->rh85_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh85_numero"])){
        if(trim($this->rh85_numero)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh85_numero"])){
           $this->rh85_numero = "0" ;
        }
       $sql  .= $virgula." rh85_numero = $this->rh85_numero ";
       $virgula = ",";
     }
     if(trim($this->rh85_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh85_instit"])){
        if(trim($this->rh85_instit)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh85_instit"])){
           $this->rh85_instit = "0" ;
        }
       $sql  .= $virgula." rh85_instit = $this->rh85_instit ";
       $virgula = ",";
     }
     if(trim($this->rh85_estorage)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh85_estorage"])){
        if(trim($this->rh85_estorage)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh85_estorage"])){
           $this->rh85_estorage = "0" ;
        }
       $sql  .= $virgula." rh85_estorage = $this->rh85_estorage ";
       $virgula = ",";
     }
     if(trim($this->rh85_tipofolha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh85_tipofolha"])){
        if(trim($this->rh85_tipofolha)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh85_tipofolha"])){
           $this->rh85_tipofolha = "0" ;
        }
       $sql  .= $virgula." rh85_tipofolha = $this->rh85_tipofolha ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh85_sequencial!=null){
       $sql .= " rh85_sequencial = $this->rh85_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh85_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,14571,'$this->rh85_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh85_sequencial"]) || $this->rh85_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,2563,14571,'".AddSlashes(pg_result($resaco,$conresaco,'rh85_sequencial'))."','$this->rh85_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh85_regist"]) || $this->rh85_regist != "")
             $resac = db_query("insert into db_acount values($acount,2563,14562,'".AddSlashes(pg_result($resaco,$conresaco,'rh85_regist'))."','$this->rh85_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh85_anousu"]) || $this->rh85_anousu != "")
             $resac = db_query("insert into db_acount values($acount,2563,14563,'".AddSlashes(pg_result($resaco,$conresaco,'rh85_anousu'))."','$this->rh85_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh85_mesusu"]) || $this->rh85_mesusu != "")
             $resac = db_query("insert into db_acount values($acount,2563,14564,'".AddSlashes(pg_result($resaco,$conresaco,'rh85_mesusu'))."','$this->rh85_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh85_dataemissao"]) || $this->rh85_dataemissao != "")
             $resac = db_query("insert into db_acount values($acount,2563,14565,'".AddSlashes(pg_result($resaco,$conresaco,'rh85_dataemissao'))."','$this->rh85_dataemissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh85_horaemissao"]) || $this->rh85_horaemissao != "")
             $resac = db_query("insert into db_acount values($acount,2563,14566,'".AddSlashes(pg_result($resaco,$conresaco,'rh85_horaemissao'))."','$this->rh85_horaemissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh85_sigla"]) || $this->rh85_sigla != "")
             $resac = db_query("insert into db_acount values($acount,2563,14567,'".AddSlashes(pg_result($resaco,$conresaco,'rh85_sigla'))."','$this->rh85_sigla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh85_codautent"]) || $this->rh85_codautent != "")
             $resac = db_query("insert into db_acount values($acount,2563,14568,'".AddSlashes(pg_result($resaco,$conresaco,'rh85_codautent'))."','$this->rh85_codautent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh85_ip"]) || $this->rh85_ip != "")
             $resac = db_query("insert into db_acount values($acount,2563,14569,'".AddSlashes(pg_result($resaco,$conresaco,'rh85_ip'))."','$this->rh85_ip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh85_externo"]) || $this->rh85_externo != "")
             $resac = db_query("insert into db_acount values($acount,2563,14570,'".AddSlashes(pg_result($resaco,$conresaco,'rh85_externo'))."','$this->rh85_externo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh85_liquido"]) || $this->rh85_liquido != "")
             $resac = db_query("insert into db_acount values($acount,2563,22161,'".AddSlashes(pg_result($resaco,$conresaco,'rh85_liquido'))."','$this->rh85_liquido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh85_desconto"]) || $this->rh85_desconto != "")
             $resac = db_query("insert into db_acount values($acount,2563,22162,'".AddSlashes(pg_result($resaco,$conresaco,'rh85_desconto'))."','$this->rh85_desconto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh85_provento"]) || $this->rh85_provento != "")
             $resac = db_query("insert into db_acount values($acount,2563,22163,'".AddSlashes(pg_result($resaco,$conresaco,'rh85_provento'))."','$this->rh85_provento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh85_numero"]) || $this->rh85_numero != "")
             $resac = db_query("insert into db_acount values($acount,2563,1014499,'".AddSlashes(pg_result($resaco,$conresaco,'rh85_numero'))."','$this->rh85_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh85_instit"]) || $this->rh85_instit != "")
             $resac = db_query("insert into db_acount values($acount,2563,1014500,'".AddSlashes(pg_result($resaco,$conresaco,'rh85_instit'))."','$this->rh85_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh85_estorage"]) || $this->rh85_estorage != "")
             $resac = db_query("insert into db_acount values($acount,2563,1014501,'".AddSlashes(pg_result($resaco,$conresaco,'rh85_estorage'))."','$this->rh85_estorage',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh85_tipofolha"]) || $this->rh85_tipofolha != "")
             $resac = db_query("insert into db_acount values($acount,2563,1014502,'".AddSlashes(pg_result($resaco,$conresaco,'rh85_tipofolha'))."','$this->rh85_tipofolha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhemitecontracheque não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh85_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "rhemitecontracheque não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh85_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh85_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }

    public function excluir($rh85_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh85_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,14571,'$rh85_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,2563,14571,'','".AddSlashes(pg_result($resaco,$iresaco,'rh85_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2563,14562,'','".AddSlashes(pg_result($resaco,$iresaco,'rh85_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2563,14563,'','".AddSlashes(pg_result($resaco,$iresaco,'rh85_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2563,14564,'','".AddSlashes(pg_result($resaco,$iresaco,'rh85_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2563,14565,'','".AddSlashes(pg_result($resaco,$iresaco,'rh85_dataemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2563,14566,'','".AddSlashes(pg_result($resaco,$iresaco,'rh85_horaemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2563,14567,'','".AddSlashes(pg_result($resaco,$iresaco,'rh85_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2563,14568,'','".AddSlashes(pg_result($resaco,$iresaco,'rh85_codautent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2563,14569,'','".AddSlashes(pg_result($resaco,$iresaco,'rh85_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2563,14570,'','".AddSlashes(pg_result($resaco,$iresaco,'rh85_externo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2563,22161,'','".AddSlashes(pg_result($resaco,$iresaco,'rh85_liquido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2563,22162,'','".AddSlashes(pg_result($resaco,$iresaco,'rh85_desconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2563,22163,'','".AddSlashes(pg_result($resaco,$iresaco,'rh85_provento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2563,1014499,'','".AddSlashes(pg_result($resaco,$iresaco,'rh85_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2563,1014500,'','".AddSlashes(pg_result($resaco,$iresaco,'rh85_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2563,1014501,'','".AddSlashes(pg_result($resaco,$iresaco,'rh85_estorage'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2563,1014502,'','".AddSlashes(pg_result($resaco,$iresaco,'rh85_tipofolha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhemitecontracheque
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh85_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh85_sequencial = $rh85_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhemitecontracheque não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh85_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "rhemitecontracheque não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh85_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh85_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhemitecontracheque";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh85_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from rhemitecontracheque ";
     $sql .= "      left  join rhtipofolha  on  rhtipofolha.rh142_sequencial = rhemitecontracheque.rh85_tipofolha";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh85_sequencial)) {
         $sql2 .= " where rhemitecontracheque.rh85_sequencial = $rh85_sequencial ";
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

    public function sql_query_file($rh85_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhemitecontracheque ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh85_sequencial)){
         $sql2 .= " where rhemitecontracheque.rh85_sequencial = $rh85_sequencial ";
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
