<?php

class cl_areaprocedimentoresultado
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
    public $ed159_codigo = 0; 
    public $ed159_areaprocedimento = 0; 
    public $ed159_formaavaliacao = 0; 
    public $ed159_resultado = 0; 
    public $ed159_formaobtencao = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 ed159_codigo = int4 = Código 
                 ed159_areaprocedimento = int4 = Procedimento da área 
                 ed159_formaavaliacao = int4 = Forma de avaliação 
                 ed159_resultado = int4 = Resultado do procedimento 
                 ed159_formaobtencao = varchar(2) = Forma de Cálculo 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("areaprocedimentoresultado"); 
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
       $this->ed159_codigo = ($this->ed159_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed159_codigo"]:$this->ed159_codigo);
       $this->ed159_areaprocedimento = ($this->ed159_areaprocedimento == ""?@$GLOBALS["HTTP_POST_VARS"]["ed159_areaprocedimento"]:$this->ed159_areaprocedimento);
       $this->ed159_formaavaliacao = ($this->ed159_formaavaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed159_formaavaliacao"]:$this->ed159_formaavaliacao);
       $this->ed159_resultado = ($this->ed159_resultado == ""?@$GLOBALS["HTTP_POST_VARS"]["ed159_resultado"]:$this->ed159_resultado);
       $this->ed159_formaobtencao = ($this->ed159_formaobtencao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed159_formaobtencao"]:$this->ed159_formaobtencao);
     }else{
       $this->ed159_codigo = ($this->ed159_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed159_codigo"]:$this->ed159_codigo);
     }
   }

    public function incluir($ed159_codigo)
    {
      $this->atualizacampos();
     if($this->ed159_areaprocedimento == null ){ 
       $this->erro_sql = " Campo Procedimento da área não informado.";
       $this->erro_campo = "ed159_areaprocedimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed159_formaavaliacao == null ){ 
       $this->erro_sql = " Campo Forma de avaliação não informado.";
       $this->erro_campo = "ed159_formaavaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed159_resultado == null ){ 
       $this->erro_sql = " Campo Resultado do procedimento não informado.";
       $this->erro_campo = "ed159_resultado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed159_formaobtencao == null ){ 
       $this->erro_sql = " Campo Forma de Cálculo não informado.";
       $this->erro_campo = "ed159_formaobtencao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed159_codigo == "" || $ed159_codigo == null ){
       $result = db_query("select nextval('areaprocedimentoresultado_ed159_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: areaprocedimentoresultado_ed159_codigo_seq do campo: ed159_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed159_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from areaprocedimentoresultado_ed159_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed159_codigo)){
         $this->erro_sql = " Campo ed159_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed159_codigo = $ed159_codigo; 
       }
     }
     if(($this->ed159_codigo == null) || ($this->ed159_codigo == "") ){ 
       $this->erro_sql = " Campo ed159_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into areaprocedimentoresultado(
                                       ed159_codigo 
                                      ,ed159_areaprocedimento 
                                      ,ed159_formaavaliacao 
                                      ,ed159_resultado 
                                      ,ed159_formaobtencao 
                       )
                values (
                                $this->ed159_codigo 
                               ,$this->ed159_areaprocedimento 
                               ,$this->ed159_formaavaliacao 
                               ,$this->ed159_resultado 
                               ,'$this->ed159_formaobtencao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Resultado do procedimento ($this->ed159_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Resultado do procedimento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Resultado do procedimento ($this->ed159_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed159_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed159_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1011102,'$this->ed159_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010535,1011102,'','".AddSlashes(pg_result($resaco,0,'ed159_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010535,1011103,'','".AddSlashes(pg_result($resaco,0,'ed159_areaprocedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010535,1011104,'','".AddSlashes(pg_result($resaco,0,'ed159_formaavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010535,1011105,'','".AddSlashes(pg_result($resaco,0,'ed159_resultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010535,1011106,'','".AddSlashes(pg_result($resaco,0,'ed159_formaobtencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($ed159_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update areaprocedimentoresultado set ";
     $virgula = "";
     if(trim($this->ed159_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed159_codigo"])){ 
       $sql  .= $virgula." ed159_codigo = $this->ed159_codigo ";
       $virgula = ",";
       if(trim($this->ed159_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed159_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed159_areaprocedimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed159_areaprocedimento"])){ 
       $sql  .= $virgula." ed159_areaprocedimento = $this->ed159_areaprocedimento ";
       $virgula = ",";
       if(trim($this->ed159_areaprocedimento) == null ){ 
         $this->erro_sql = " Campo Procedimento da área não informado.";
         $this->erro_campo = "ed159_areaprocedimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed159_formaavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed159_formaavaliacao"])){ 
       $sql  .= $virgula." ed159_formaavaliacao = $this->ed159_formaavaliacao ";
       $virgula = ",";
       if(trim($this->ed159_formaavaliacao) == null ){ 
         $this->erro_sql = " Campo Forma de avaliação não informado.";
         $this->erro_campo = "ed159_formaavaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed159_resultado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed159_resultado"])){ 
       $sql  .= $virgula." ed159_resultado = $this->ed159_resultado ";
       $virgula = ",";
       if(trim($this->ed159_resultado) == null ){ 
         $this->erro_sql = " Campo Resultado do procedimento não informado.";
         $this->erro_campo = "ed159_resultado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed159_formaobtencao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed159_formaobtencao"])){ 
       $sql  .= $virgula." ed159_formaobtencao = '$this->ed159_formaobtencao' ";
       $virgula = ",";
       if(trim($this->ed159_formaobtencao) == null ){ 
         $this->erro_sql = " Campo Forma de Cálculo não informado.";
         $this->erro_campo = "ed159_formaobtencao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed159_codigo!=null){
       $sql .= " ed159_codigo = $this->ed159_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed159_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1011102,'$this->ed159_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed159_codigo"]) || $this->ed159_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010535,1011102,'".AddSlashes(pg_result($resaco,$conresaco,'ed159_codigo'))."','$this->ed159_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed159_areaprocedimento"]) || $this->ed159_areaprocedimento != "")
             $resac = db_query("insert into db_acount values($acount,1010535,1011103,'".AddSlashes(pg_result($resaco,$conresaco,'ed159_areaprocedimento'))."','$this->ed159_areaprocedimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed159_formaavaliacao"]) || $this->ed159_formaavaliacao != "")
             $resac = db_query("insert into db_acount values($acount,1010535,1011104,'".AddSlashes(pg_result($resaco,$conresaco,'ed159_formaavaliacao'))."','$this->ed159_formaavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed159_resultado"]) || $this->ed159_resultado != "")
             $resac = db_query("insert into db_acount values($acount,1010535,1011105,'".AddSlashes(pg_result($resaco,$conresaco,'ed159_resultado'))."','$this->ed159_resultado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed159_formaobtencao"]) || $this->ed159_formaobtencao != "")
             $resac = db_query("insert into db_acount values($acount,1010535,1011106,'".AddSlashes(pg_result($resaco,$conresaco,'ed159_formaobtencao'))."','$this->ed159_formaobtencao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Resultado do procedimento não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed159_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Resultado do procedimento não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed159_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed159_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($ed159_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed159_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1011102,'$ed159_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010535,1011102,'','".AddSlashes(pg_result($resaco,$iresaco,'ed159_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010535,1011103,'','".AddSlashes(pg_result($resaco,$iresaco,'ed159_areaprocedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010535,1011104,'','".AddSlashes(pg_result($resaco,$iresaco,'ed159_formaavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010535,1011105,'','".AddSlashes(pg_result($resaco,$iresaco,'ed159_resultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010535,1011106,'','".AddSlashes(pg_result($resaco,$iresaco,'ed159_formaobtencao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from areaprocedimentoresultado
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed159_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed159_codigo = $ed159_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Resultado do procedimento não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed159_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Resultado do procedimento não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed159_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ed159_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:areaprocedimentoresultado";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($ed159_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from areaprocedimentoresultado ";
     $sql .= "      inner join formaavaliacao  on  formaavaliacao.ed37_i_codigo = areaprocedimentoresultado.ed159_formaavaliacao";
     $sql .= "      inner join resultado  on  resultado.ed42_i_codigo = areaprocedimentoresultado.ed159_resultado";
     $sql .= "      inner join areaprocedimento  on  areaprocedimento.ed157_codigo = areaprocedimentoresultado.ed159_areaprocedimento";
     $sql .= "      left  join escola  on  escola.ed18_i_codigo = formaavaliacao.ed37_i_escola";
     $sql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = areaprocedimento.ed157_procedimento";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed159_codigo)) {
         $sql2 .= " where areaprocedimentoresultado.ed159_codigo = $ed159_codigo "; 
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

    public function sql_query_file($ed159_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from areaprocedimentoresultado ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed159_codigo)){
         $sql2 .= " where areaprocedimentoresultado.ed159_codigo = $ed159_codigo "; 
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
