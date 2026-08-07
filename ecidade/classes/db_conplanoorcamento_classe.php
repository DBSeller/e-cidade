<?php

class cl_conplanoorcamento
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
    public $c60_codcon = 0;
    public $c60_anousu = 0;
    public $c60_estrut = null;
    public $c60_descr = null;
    public $c60_finali = null;
    public $c60_codsis = 0;
    public $c60_codcla = 0;
    public $c60_consistemaconta = 0;
    public $c60_identificadorfinanceiro = null;
    public $c60_naturezasaldo = 0;
    public $c60_funcao = null;
    public $c60_identificadoresultadoprimario = 0;
   // cria propriedade com as variaveis do arquivo
    public $campos = "
                 c60_codcon = int4 = Código
                 c60_anousu = int4 = Exercício
                 c60_estrut = varchar(15) = Estrutural
                 c60_descr = varchar(50) = Descrição da Conta
                 c60_finali = text = Finalidade
                 c60_codsis = int4 = Sistema
                 c60_codcla = int4 = Classificação
                 c60_consistemaconta = int4 = consistemaconta
                 c60_identificadorfinanceiro = char(1) = Identificador financeiro
                 c60_naturezasaldo = int4 = naturezasaldo
                 c60_funcao = text = Função
                 c60_identificadoresultadoprimario = int4 = Identificador do Resultado Primário
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("conplanoorcamento");
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
       $this->c60_codcon = ($this->c60_codcon == ""?@$GLOBALS["HTTP_POST_VARS"]["c60_codcon"]:$this->c60_codcon);
       $this->c60_anousu = ($this->c60_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c60_anousu"]:$this->c60_anousu);
       $this->c60_estrut = ($this->c60_estrut == ""?@$GLOBALS["HTTP_POST_VARS"]["c60_estrut"]:$this->c60_estrut);
       $this->c60_descr = ($this->c60_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["c60_descr"]:$this->c60_descr);
       $this->c60_finali = ($this->c60_finali == ""?@$GLOBALS["HTTP_POST_VARS"]["c60_finali"]:$this->c60_finali);
       $this->c60_codsis = ($this->c60_codsis == ""?@$GLOBALS["HTTP_POST_VARS"]["c60_codsis"]:$this->c60_codsis);
       $this->c60_codcla = ($this->c60_codcla == ""?@$GLOBALS["HTTP_POST_VARS"]["c60_codcla"]:$this->c60_codcla);
       $this->c60_consistemaconta = ($this->c60_consistemaconta == ""?@$GLOBALS["HTTP_POST_VARS"]["c60_consistemaconta"]:$this->c60_consistemaconta);
       $this->c60_identificadorfinanceiro = ($this->c60_identificadorfinanceiro == ""?@$GLOBALS["HTTP_POST_VARS"]["c60_identificadorfinanceiro"]:$this->c60_identificadorfinanceiro);
       $this->c60_naturezasaldo = ($this->c60_naturezasaldo == ""?@$GLOBALS["HTTP_POST_VARS"]["c60_naturezasaldo"]:$this->c60_naturezasaldo);
       $this->c60_funcao = ($this->c60_funcao == ""?@$GLOBALS["HTTP_POST_VARS"]["c60_funcao"]:$this->c60_funcao);
       $this->c60_identificadoresultadoprimario = ($this->c60_identificadoresultadoprimario == ""?@$GLOBALS["HTTP_POST_VARS"]["c60_identificadoresultadoprimario"]:$this->c60_identificadoresultadoprimario);
     }else{
       $this->c60_codcon = ($this->c60_codcon == ""?@$GLOBALS["HTTP_POST_VARS"]["c60_codcon"]:$this->c60_codcon);
       $this->c60_anousu = ($this->c60_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c60_anousu"]:$this->c60_anousu);
     }
   }

    public function incluir($c60_codcon,$c60_anousu)
    {
      $this->atualizacampos();
     if($this->c60_estrut == null ){
       $this->erro_sql = " Campo Estrutural não informado.";
       $this->erro_campo = "c60_estrut";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c60_descr == null ){
       $this->erro_sql = " Campo Descrição da Conta não informado.";
       $this->erro_campo = "c60_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c60_codsis == null ){
       $this->erro_sql = " Campo Sistema não informado.";
       $this->erro_campo = "c60_codsis";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c60_codcla == null ){
       $this->erro_sql = " Campo Classificação não informado.";
       $this->erro_campo = "c60_codcla";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c60_consistemaconta == null ){
       $this->erro_sql = " Campo consistemaconta não informado.";
       $this->erro_campo = "c60_consistemaconta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c60_identificadorfinanceiro == null ){
       $this->erro_sql = " Campo Identificador financeiro não informado.";
       $this->erro_campo = "c60_identificadorfinanceiro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c60_naturezasaldo == null ){
       $this->erro_sql = " Campo naturezasaldo não informado.";
       $this->erro_campo = "c60_naturezasaldo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c60_codcon == "" || $c60_codcon == null ){
       $result = db_query("select nextval('conplanoorcamento_c60_codcon_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conplanoorcamento_c60_codcon_seq do campo: c60_codcon";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->c60_codcon = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from conplanoorcamento_c60_codcon_seq");
       if(($result != false) && (pg_result($result,0,0) < $c60_codcon)){
         $this->erro_sql = " Campo c60_codcon maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c60_codcon = $c60_codcon;
       }
     }
     if(($this->c60_codcon == null) || ($this->c60_codcon == "") ){
       $this->erro_sql = " Campo c60_codcon não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->c60_anousu == null) || ($this->c60_anousu == "") ){
       $this->erro_sql = " Campo c60_anousu não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

     if (empty($this->c60_identificadoresultadoprimario)) {
         $this->c60_identificadoresultadoprimario = '0';
     }

     $sql = "insert into conplanoorcamento(
                                       c60_codcon
                                      ,c60_anousu
                                      ,c60_estrut
                                      ,c60_descr
                                      ,c60_finali
                                      ,c60_codsis
                                      ,c60_codcla
                                      ,c60_consistemaconta
                                      ,c60_identificadorfinanceiro
                                      ,c60_naturezasaldo
                                      ,c60_funcao
                                      ,c60_identificadoresultadoprimario
                       )
                values (
                                $this->c60_codcon
                               ,$this->c60_anousu
                               ,'$this->c60_estrut'
                               ,'$this->c60_descr'
                               ,'$this->c60_finali'
                               ,$this->c60_codsis
                               ,$this->c60_codcla
                               ,$this->c60_consistemaconta
                               ,'$this->c60_identificadorfinanceiro'
                               ,$this->c60_naturezasaldo
                               ,'$this->c60_funcao'
                               ,$this->c60_identificadoresultadoprimario
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tabela cópia da conplano ($this->c60_codcon."-".$this->c60_anousu) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tabela cópia da conplano já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tabela cópia da conplano ($this->c60_codcon."-".$this->c60_anousu) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->c60_codcon."-".$this->c60_anousu;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     return true;
   }

    public function alterar($c60_codcon=null,$c60_anousu=null)
    {
      $this->atualizacampos();
     $sql = " update conplanoorcamento set ";
     $virgula = "";
     if(trim($this->c60_codcon)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c60_codcon"])){
       $sql  .= $virgula." c60_codcon = $this->c60_codcon ";
       $virgula = ",";
       if(trim($this->c60_codcon) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "c60_codcon";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c60_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c60_anousu"])){
       $sql  .= $virgula." c60_anousu = $this->c60_anousu ";
       $virgula = ",";
       if(trim($this->c60_anousu) == null ){
         $this->erro_sql = " Campo Exercício não informado.";
         $this->erro_campo = "c60_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c60_estrut)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c60_estrut"])){
       $sql  .= $virgula." c60_estrut = '$this->c60_estrut' ";
       $virgula = ",";
       if(trim($this->c60_estrut) == null ){
         $this->erro_sql = " Campo Estrutural não informado.";
         $this->erro_campo = "c60_estrut";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c60_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c60_descr"])){
       $sql  .= $virgula." c60_descr = '$this->c60_descr' ";
       $virgula = ",";
       if(trim($this->c60_descr) == null ){
         $this->erro_sql = " Campo Descrição da Conta não informado.";
         $this->erro_campo = "c60_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c60_finali)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c60_finali"])){
       $sql  .= $virgula." c60_finali = '$this->c60_finali' ";
       $virgula = ",";
     }
     if(trim($this->c60_codsis)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c60_codsis"])){
       $sql  .= $virgula." c60_codsis = $this->c60_codsis ";
       $virgula = ",";
       if(trim($this->c60_codsis) == null ){
         $this->erro_sql = " Campo Sistema não informado.";
         $this->erro_campo = "c60_codsis";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c60_codcla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c60_codcla"])){
       $sql  .= $virgula." c60_codcla = $this->c60_codcla ";
       $virgula = ",";
       if(trim($this->c60_codcla) == null ){
         $this->erro_sql = " Campo Classificação não informado.";
         $this->erro_campo = "c60_codcla";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c60_consistemaconta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c60_consistemaconta"])){
       $sql  .= $virgula." c60_consistemaconta = $this->c60_consistemaconta ";
       $virgula = ",";
       if(trim($this->c60_consistemaconta) == null ){
         $this->erro_sql = " Campo consistemaconta não informado.";
         $this->erro_campo = "c60_consistemaconta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c60_identificadorfinanceiro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c60_identificadorfinanceiro"])){
       $sql  .= $virgula." c60_identificadorfinanceiro = '$this->c60_identificadorfinanceiro' ";
       $virgula = ",";
       if(trim($this->c60_identificadorfinanceiro) == null ){
         $this->erro_sql = " Campo Identificador financeiro não informado.";
         $this->erro_campo = "c60_identificadorfinanceiro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c60_naturezasaldo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c60_naturezasaldo"])){
       $sql  .= $virgula." c60_naturezasaldo = $this->c60_naturezasaldo ";
       $virgula = ",";
       if(trim($this->c60_naturezasaldo) == null ){
         $this->erro_sql = " Campo naturezasaldo não informado.";
         $this->erro_campo = "c60_naturezasaldo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c60_funcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c60_funcao"])){
       $sql  .= $virgula." c60_funcao = '$this->c60_funcao' ";
       $virgula = ",";
     }
     if(trim($this->c60_identificadoresultadoprimario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c60_identificadoresultadoprimario"])){
        if(trim($this->c60_identificadoresultadoprimario)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c60_identificadoresultadoprimario"])){
           $this->c60_identificadoresultadoprimario = "0" ;
        }
       $sql  .= $virgula." c60_identificadoresultadoprimario = $this->c60_identificadoresultadoprimario ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($c60_codcon!=null){
       $sql .= " c60_codcon = $this->c60_codcon";
     }
     if($c60_anousu!=null){
       $sql .= " and  c60_anousu = $this->c60_anousu";
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela cópia da conplano não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c60_codcon."-".$this->c60_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tabela cópia da conplano não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c60_codcon."-".$this->c60_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->c60_codcon."-".$this->c60_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }

    public function excluir($c60_codcon=null,$c60_anousu=null, $dbwhere = null)
    {
     $sql = " delete from conplanoorcamento
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($c60_codcon)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " c60_codcon = $c60_codcon ";
        }
        if (!empty($c60_anousu)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " c60_anousu = $c60_anousu ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela cópia da conplano não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c60_codcon."-".$c60_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tabela cópia da conplano não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c60_codcon."-".$c60_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$c60_codcon."-".$c60_anousu;
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
        $this->erro_sql   = "Record Vazio na Tabela:conplanoorcamento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    function sql_query($c60_codcon = null, $c60_anousu = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = explode("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from conplanoorcamento ";
        $sql .= "      inner join conclass  on  conclass.c51_codcla = conplanoorcamento.c60_codcla";
        $sql .= "      inner join consistema  on  consistema.c52_codsis = conplanoorcamento.c60_codsis";
        $sql .= "      inner join consistemaconta  on  consistemaconta.c65_sequencial = conplanoorcamento.c60_consistemaconta";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($c60_codcon != null) {
                $sql2 .= " where conplanoorcamento.c60_codcon = $c60_codcon ";
            }
            if ($c60_anousu != null) {
                if ($sql2 != "") {
                    $sql2 .= " and ";
                } else {
                    $sql2 .= " where ";
                }
                $sql2 .= " conplanoorcamento.c60_anousu = $c60_anousu ";
            }
        } else {
            if ($dbwhere != "") {
                $sql2 = " where $dbwhere";
            }
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = explode("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }

    // funcao do sql
    function sql_query_file($c60_codcon = null, $c60_anousu = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = explode("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from conplanoorcamento ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($c60_codcon != null) {
                $sql2 .= " where conplanoorcamento.c60_codcon = $c60_codcon ";
            }
            if ($c60_anousu != null) {
                if ($sql2 != "") {
                    $sql2 .= " and ";
                } else {
                    $sql2 .= " where ";
                }
                $sql2 .= " conplanoorcamento.c60_anousu = $c60_anousu ";
            }
        } else {
            if ($dbwhere != "") {
                $sql2 = " where $dbwhere";
            }
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = explode("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }

    /**
     * Busca o Plano PCASP
     * @return string
     */
    function sql_query_dados_plano($c60_anousu = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = explode("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from conplanoorcamento                                                                                           ";
        $sql .= "      left join conplanoorcamentoanalitica     on conplanoorcamento.c60_codcon         = conplanoorcamentoanalitica.c61_codcon      ";
        $sql .= "                                              and conplanoorcamento.c60_anousu         = conplanoorcamentoanalitica.c61_anousu      ";
        $sql .= "      left join conplanoorcamentoconta         on conplanoorcamento.c60_codcon         = conplanoorcamentoconta.c63_codcon          ";
        $sql .= "                                              and conplanoorcamento.c60_anousu         = conplanoorcamentoconta.c63_anousu          ";
        $sql .= "      left join conplanoorcamentocontabancaria on conplanoorcamento.c60_codcon         = conplanoorcamentocontabancaria.c56_codcon  ";
        $sql .= "                                              and conplanoorcamento.c60_anousu         = conplanoorcamentocontabancaria.c56_anousu  ";
        $sql .= "      inner join conclass                      on conplanoorcamento.c60_codcla         = conclass.c51_codcla                        ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($c60_anousu != null) {
                $sql2 .= " where conplanoorcamento.c60_anousu = $c60_anousu ";
            }
        } else {
            if ($dbwhere != "") {
                $sql2 = " where $dbwhere";
            }
        }

        //$sql2 .= ($sql2!=""?" and ":" where ") . " c61_instit = " . db_getsession("DB_instit");
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = explode("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }

    function sql_query_geral($c60_codcon = null, $c60_anousu = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = explode("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from conplanoorcamento ";
        $sql .= "      inner join conclass                   on  conclass.c51_codcla = conplanoorcamento.c60_codcla";
        $sql .= "      inner join consistema                 on  consistema.c52_codsis = conplanoorcamento.c60_codsis";
        $sql .= "      left join conplanoorcamentoanalitica  on conplanoorcamentoanalitica.c61_codcon = conplanoorcamento.c60_codcon";
        $sql .= "      																			and conplanoorcamentoanalitica.c61_anousu = c60_anousu";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($c60_codcon != null && $c60_anousu != null) {
                $sql2 .= " where conplanoorcamento.c60_codcon = $c60_codcon and conplanoorcamento.c60_anousu=" . $c60_anousu;
            } else {
                $sql2 .= " where conplanoorcamento.c60_anousu=" . db_getsession("DB_anousu");
            }
        } else {
            if ($dbwhere != "") {
                $sql2 = " where $dbwhere  ";
            }
        }
        $x = @db_query("select prefeitura from db_config where codigo=" . db_getsession("DB_instit"));
        $libera = @pg_result($x, 0, 0);
        $dbw = '';
        if ($libera == "t") {
            //$dbw = " c61_instit is null or ";
        } else {
            $sql2 .= ($sql2 != "" ? " and " : " where ") . " ( $dbw ( c61_instit is not null and c61_instit = " . db_getsession("DB_instit") . " ))";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = explode("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }

    /**
     * Busca inconsistencia no Plano Orcamentário
     * @return string
     */
    function sql_query_inconsistencia_plano($c60_anousu = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = explode("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from conplanoorcamento																									                                              ";
        $sql .= "left join conplanoorcamentoanalitica on conplanoorcamentoanalitica.c61_anousu            = conplanoorcamento.c60_anousu ";
        $sql .= "                                    and conplanoorcamentoanalitica.c61_codcon            = conplanoorcamento.c60_codcon ";
        $sql .= "left join conplanoconplanoorcamento  on conplanoconplanoorcamento.c72_anousu             = conplanoorcamento.c60_anousu ";
        $sql .= "                                    and conplanoconplanoorcamento.c72_conplanoorcamento  = conplanoorcamento.c60_codcon ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($c60_anousu != null) {
                $sql2 .= " where conplanoorcamento.c60_anousu = $c60_anousu ";
            }
        } else {
            if ($dbwhere != "") {
                $sql2 = " where $dbwhere";
            }
        }

        //$sql2 .= ($sql2!=""?" and ":" where ") . " c61_instit = " . db_getsession("DB_instit");
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = explode("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }

    /**
     * Busca o Plano Orcamentário
     * @return string
     */
    function sql_query_plano_orcamentario($c60_anousu = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = explode("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from conplanoorcamento																									                                         ";
        $sql .= "      left join conplanoorcamentoanalitica     on conplano.c60_codcon = conplanoorcamentoanalitica.c61_codcon     ";
        $sql .= "                                              and conplano.c60_anousu = conplanoorcamentoanalitica.c61_anousu     ";
        $sql .= "      left join conplanoorcamentoconta         on conplano.c60_codcon = conplanoorcamentoconta.c63_codcon         ";
        $sql .= "                                     		     and conplano.c60_anousu = conplanoorcamentoconta.c63_anousu         ";
        $sql .= "      left join conplanoorcamentocontabancaria on conplano.c60_codcon = conplanoorcamentocontabancaria.c56_codcon ";
        $sql .= "                                              and conplano.c60_anousu = conplanoorcamentocontabancaria.c56_anousu ";
        $sql .= "      inner join conclass   	                  on conplano.c60_codcla         = conclass.c51_codcla 							 ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($c60_anousu != null) {
                $sql2 .= " where conplano.c60_anousu = $c60_anousu ";
            }
        } else {
            if ($dbwhere != "") {
                $sql2 = " where $dbwhere";
            }
        }

        //$sql2 .= ($sql2!=""?" and ":" where ") . " c61_instit = " . db_getsession("DB_instit");
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = explode("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }

    function db_verifica_conplano($conplano, $anousu)
    {

        $nivel = db_le_mae_conplano($conplano, true);
        if ($nivel == 1) {
            return true;
        }

        $cod_mae = db_le_mae_conplano($conplano, false);
        $this->sql_record($this->sql_query_file("", "", "c60_estrut", "",
            " c60_anousu=$anousu and  c60_estrut='$cod_mae'"));

        if ($this->numrows < 1) {

            $this->erro_msg = 'Procedimento abortado. Estrutural acima não encontrado!';
            return false;
        }

        $this->erro_msg = 'Conplano válido!';
        return true;
    }

    public function sql_query_orcamento_receita($campos = '*', $where = null)
    {
        $sql  = " select {$campos} ";
        $sql .= "   from conplanoorcamento ";
        $sql .= "        left join orcreceita on orcreceita.o70_codfon = conplanoorcamento.c60_codcon ";
        $sql .= "                            and orcreceita.o70_anousu = conplanoorcamento.c60_anousu ";

        if (!empty($where)) {
            $sql .= " where {$where} ";
        }
        return $sql;
    }

    public function sql_query_orcamento_detalhe($campos = "*", $where = null)
    {

        $sql  = " select {$campos} ";
        $sql .= "   from conplanoorcamento ";
        $sql .= "        left join planocontadetalheconplanoorcamento on c97_conplanoorcamento = c60_codcon ";
        $sql .= "        left join orcreceita on orcreceita.o70_codfon = conplanoorcamento.c60_codcon ";
        $sql .= "                            and orcreceita.o70_anousu = conplanoorcamento.c60_anousu ";

        if (!empty($where)) {
            $sql .= " where {$where} ";
        }
        return $sql;
    }
}
