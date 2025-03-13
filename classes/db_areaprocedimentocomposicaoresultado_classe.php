<?php

class cl_areaprocedimentocomposicaoresultado
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
    public $ed160_codigo = 0; 
    public $ed160_areaprocedimentoresultado = 0; 
    public $ed160_areaprocedimentoavaliacao = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 ed160_codigo = int4 = Código 
                 ed160_areaprocedimentoresultado = int4 = Resultado do procedimento 
                 ed160_areaprocedimentoavaliacao = int4 = Avaliação do procedimento 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("areaprocedimentocomposicaoresultado"); 
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
       $this->ed160_codigo = ($this->ed160_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed160_codigo"]:$this->ed160_codigo);
       $this->ed160_areaprocedimentoresultado = ($this->ed160_areaprocedimentoresultado == ""?@$GLOBALS["HTTP_POST_VARS"]["ed160_areaprocedimentoresultado"]:$this->ed160_areaprocedimentoresultado);
       $this->ed160_areaprocedimentoavaliacao = ($this->ed160_areaprocedimentoavaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed160_areaprocedimentoavaliacao"]:$this->ed160_areaprocedimentoavaliacao);
     }else{
       $this->ed160_codigo = ($this->ed160_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed160_codigo"]:$this->ed160_codigo);
     }
   }

    public function incluir($ed160_codigo)
    {
      $this->atualizacampos();
     if($this->ed160_areaprocedimentoresultado == null ){ 
       $this->erro_sql = " Campo Resultado do procedimento não informado.";
       $this->erro_campo = "ed160_areaprocedimentoresultado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed160_areaprocedimentoavaliacao == null ){ 
       $this->erro_sql = " Campo Avaliação do procedimento não informado.";
       $this->erro_campo = "ed160_areaprocedimentoavaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed160_codigo == "" || $ed160_codigo == null ){
       $result = db_query("select nextval('areaprocedimentocomposicaoresultado_ed160_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: areaprocedimentocomposicaoresultado_ed160_codigo_seq do campo: ed160_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed160_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from areaprocedimentocomposicaoresultado_ed160_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed160_codigo)){
         $this->erro_sql = " Campo ed160_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed160_codigo = $ed160_codigo; 
       }
     }
     if(($this->ed160_codigo == null) || ($this->ed160_codigo == "") ){ 
       $this->erro_sql = " Campo ed160_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into areaprocedimentocomposicaoresultado(
                                       ed160_codigo 
                                      ,ed160_areaprocedimentoresultado 
                                      ,ed160_areaprocedimentoavaliacao 
                       )
                values (
                                $this->ed160_codigo 
                               ,$this->ed160_areaprocedimentoresultado 
                               ,$this->ed160_areaprocedimentoavaliacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Composição do Resultado ($this->ed160_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Composição do Resultado já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Composição do Resultado ($this->ed160_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed160_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed160_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1011109,'$this->ed160_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010536,1011109,'','".AddSlashes(pg_result($resaco,0,'ed160_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010536,1011110,'','".AddSlashes(pg_result($resaco,0,'ed160_areaprocedimentoresultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010536,1011111,'','".AddSlashes(pg_result($resaco,0,'ed160_areaprocedimentoavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($ed160_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update areaprocedimentocomposicaoresultado set ";
     $virgula = "";
     if(trim($this->ed160_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed160_codigo"])){ 
       $sql  .= $virgula." ed160_codigo = $this->ed160_codigo ";
       $virgula = ",";
       if(trim($this->ed160_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed160_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed160_areaprocedimentoresultado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed160_areaprocedimentoresultado"])){ 
       $sql  .= $virgula." ed160_areaprocedimentoresultado = $this->ed160_areaprocedimentoresultado ";
       $virgula = ",";
       if(trim($this->ed160_areaprocedimentoresultado) == null ){ 
         $this->erro_sql = " Campo Resultado do procedimento não informado.";
         $this->erro_campo = "ed160_areaprocedimentoresultado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed160_areaprocedimentoavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed160_areaprocedimentoavaliacao"])){ 
       $sql  .= $virgula." ed160_areaprocedimentoavaliacao = $this->ed160_areaprocedimentoavaliacao ";
       $virgula = ",";
       if(trim($this->ed160_areaprocedimentoavaliacao) == null ){ 
         $this->erro_sql = " Campo Avaliação do procedimento não informado.";
         $this->erro_campo = "ed160_areaprocedimentoavaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed160_codigo!=null){
       $sql .= " ed160_codigo = $this->ed160_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed160_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1011109,'$this->ed160_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed160_codigo"]) || $this->ed160_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010536,1011109,'".AddSlashes(pg_result($resaco,$conresaco,'ed160_codigo'))."','$this->ed160_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed160_areaprocedimentoresultado"]) || $this->ed160_areaprocedimentoresultado != "")
             $resac = db_query("insert into db_acount values($acount,1010536,1011110,'".AddSlashes(pg_result($resaco,$conresaco,'ed160_areaprocedimentoresultado'))."','$this->ed160_areaprocedimentoresultado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed160_areaprocedimentoavaliacao"]) || $this->ed160_areaprocedimentoavaliacao != "")
             $resac = db_query("insert into db_acount values($acount,1010536,1011111,'".AddSlashes(pg_result($resaco,$conresaco,'ed160_areaprocedimentoavaliacao'))."','$this->ed160_areaprocedimentoavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Composição do Resultado não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed160_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Composição do Resultado não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed160_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed160_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($ed160_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed160_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1011109,'$ed160_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010536,1011109,'','".AddSlashes(pg_result($resaco,$iresaco,'ed160_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010536,1011110,'','".AddSlashes(pg_result($resaco,$iresaco,'ed160_areaprocedimentoresultado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010536,1011111,'','".AddSlashes(pg_result($resaco,$iresaco,'ed160_areaprocedimentoavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from areaprocedimentocomposicaoresultado
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed160_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed160_codigo = $ed160_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Composição do Resultado não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed160_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Composição do Resultado não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed160_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ed160_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:areaprocedimentocomposicaoresultado";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($ed160_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from areaprocedimentocomposicaoresultado ";
     $sql .= "      inner join areaprocedimentoavaliacao  on  areaprocedimentoavaliacao.ed158_codigo = areaprocedimentocomposicaoresultado.ed160_areaprocedimentoavaliacao";
     $sql .= "      inner join areaprocedimentoresultado  on  areaprocedimentoresultado.ed159_codigo = areaprocedimentocomposicaoresultado.ed160_areaprocedimentoresultado";
     $sql .= "      inner join periodoavaliacao  on  periodoavaliacao.ed09_i_codigo = areaprocedimentoavaliacao.ed158_periodoavaliacao";
     $sql .= "      inner join formaavaliacao  on  formaavaliacao.ed37_i_codigo = areaprocedimentoavaliacao.ed158_formaavaliacao";
     $sql .= "      inner join areaprocedimento  on  areaprocedimento.ed157_codigo = areaprocedimentoavaliacao.ed158_areaprocedimento";
     $sql .= "      inner join formaavaliacao  as a on   a.ed37_i_codigo = areaprocedimentoresultado.ed159_formaavaliacao";
     $sql .= "      inner join resultado  on  resultado.ed42_i_codigo = areaprocedimentoresultado.ed159_resultado";
     $sql .= "      inner join areaprocedimento  on  areaprocedimento.ed157_codigo = areaprocedimentoresultado.ed159_areaprocedimento";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed160_codigo)) {
         $sql2 .= " where areaprocedimentocomposicaoresultado.ed160_codigo = $ed160_codigo "; 
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

    public function sql_query_file($ed160_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from areaprocedimentocomposicaoresultado ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed160_codigo)){
         $sql2 .= " where areaprocedimentocomposicaoresultado.ed160_codigo = $ed160_codigo "; 
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
