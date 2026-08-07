<?php

class cl_matestoqueitemanulacao
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
    public $m104_codigo = 0;
    public $m104_matestoqueitemanular = 0;
    public $m104_matestoqueitemusasaldo = 0;
   // cria propriedade com as variaveis do arquivo
    public $campos = "
                 m104_codigo = int8 = Código
                 m104_matestoqueitemanular = int8 = Estoque Item Anulação
                 m104_matestoqueitemusasaldo = int8 = Estoque Item Com Saldo
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("matestoqueitemanulacao");
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
       $this->m104_codigo = ($this->m104_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m104_codigo"]:$this->m104_codigo);
       $this->m104_matestoqueitemanular = ($this->m104_matestoqueitemanular == ""?@$GLOBALS["HTTP_POST_VARS"]["m104_matestoqueitemanular"]:$this->m104_matestoqueitemanular);
       $this->m104_matestoqueitemusasaldo = ($this->m104_matestoqueitemusasaldo == ""?@$GLOBALS["HTTP_POST_VARS"]["m104_matestoqueitemusasaldo"]:$this->m104_matestoqueitemusasaldo);
     }else{
       $this->m104_codigo = ($this->m104_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m104_codigo"]:$this->m104_codigo);
     }
   }

    public function incluir($m104_codigo = null)
    {
      $this->atualizacampos();
     if($this->m104_matestoqueitemanular == null ){
       $this->erro_sql = " Campo Estoque Item Anulação não informado.";
       $this->erro_campo = "m104_matestoqueitemanular";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m104_matestoqueitemusasaldo == null ){
       $this->erro_sql = " Campo Estoque Item Com Saldo não informado.";
       $this->erro_campo = "m104_matestoqueitemusasaldo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m104_codigo == "" || $m104_codigo == null ){
       $result = db_query("select nextval('matestoqueitemanulacao_m104_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matestoqueitemanulacao_m104_codigo_seq do campo: m104_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->m104_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from matestoqueitemanulacao_m104_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $m104_codigo)){
         $this->erro_sql = " Campo m104_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m104_codigo = $m104_codigo;
       }
     }
     if(($this->m104_codigo == null) || ($this->m104_codigo == "") ){
       $this->erro_sql = " Campo m104_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matestoqueitemanulacao(
                                       m104_codigo
                                      ,m104_matestoqueitemanular
                                      ,m104_matestoqueitemusasaldo
                       )
                values (
                                $this->m104_codigo
                               ,$this->m104_matestoqueitemanular
                               ,$this->m104_matestoqueitemusasaldo
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "matestoqueitem anulacao ($this->m104_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "matestoqueitem anulacao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "matestoqueitem anulacao ($this->m104_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->m104_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->m104_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1013608,'$this->m104_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010851,1013608,'','".AddSlashes(pg_result($resaco,0,'m104_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010851,1013606,'','".AddSlashes(pg_result($resaco,0,'m104_matestoqueitemanular'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010851,1013607,'','".AddSlashes(pg_result($resaco,0,'m104_matestoqueitemusasaldo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }

    public function alterar($m104_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update matestoqueitemanulacao set ";
     $virgula = "";
     if(trim($this->m104_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m104_codigo"])){
       $sql  .= $virgula." m104_codigo = $this->m104_codigo ";
       $virgula = ",";
       if(trim($this->m104_codigo) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "m104_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m104_matestoqueitemanular)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m104_matestoqueitemanular"])){
       $sql  .= $virgula." m104_matestoqueitemanular = $this->m104_matestoqueitemanular ";
       $virgula = ",";
       if(trim($this->m104_matestoqueitemanular) == null ){
         $this->erro_sql = " Campo Estoque Item Anulação não informado.";
         $this->erro_campo = "m104_matestoqueitemanular";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m104_matestoqueitemusasaldo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m104_matestoqueitemusasaldo"])){
       $sql  .= $virgula." m104_matestoqueitemusasaldo = $this->m104_matestoqueitemusasaldo ";
       $virgula = ",";
       if(trim($this->m104_matestoqueitemusasaldo) == null ){
         $this->erro_sql = " Campo Estoque Item Com Saldo não informado.";
         $this->erro_campo = "m104_matestoqueitemusasaldo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m104_codigo!=null){
       $sql .= " m104_codigo = $this->m104_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->m104_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1013608,'$this->m104_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["m104_codigo"]) || $this->m104_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010851,1013608,'".AddSlashes(pg_result($resaco,$conresaco,'m104_codigo'))."','$this->m104_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["m104_matestoqueitemanular"]) || $this->m104_matestoqueitemanular != "")
             $resac = db_query("insert into db_acount values($acount,1010851,1013606,'".AddSlashes(pg_result($resaco,$conresaco,'m104_matestoqueitemanular'))."','$this->m104_matestoqueitemanular',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["m104_matestoqueitemusasaldo"]) || $this->m104_matestoqueitemusasaldo != "")
             $resac = db_query("insert into db_acount values($acount,1010851,1013607,'".AddSlashes(pg_result($resaco,$conresaco,'m104_matestoqueitemusasaldo'))."','$this->m104_matestoqueitemusasaldo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matestoqueitem anulacao não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m104_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "matestoqueitem anulacao não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m104_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->m104_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }

    public function excluir($m104_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($m104_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1013608,'$m104_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010851,1013608,'','".AddSlashes(pg_result($resaco,$iresaco,'m104_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010851,1013606,'','".AddSlashes(pg_result($resaco,$iresaco,'m104_matestoqueitemanular'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010851,1013607,'','".AddSlashes(pg_result($resaco,$iresaco,'m104_matestoqueitemusasaldo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from matestoqueitemanulacao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($m104_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " m104_codigo = $m104_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matestoqueitem anulacao não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m104_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "matestoqueitem anulacao não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m104_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$m104_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:matestoqueitemanulacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($m104_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from matestoqueitemanulacao ";
     $sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codlanc = matestoqueitemanulacao.m104_matestoqueitemanular and  matestoqueitem.m71_codlanc = matestoqueitemanulacao.m104_matestoqueitemusasaldo";
     $sql .= "      inner join matestoque  on  matestoque.m70_codigo = matestoqueitem.m71_codmatestoque";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($m104_codigo)) {
         $sql2 .= " where matestoqueitemanulacao.m104_codigo = $m104_codigo ";
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

    public function sql_query_file($m104_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from matestoqueitemanulacao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($m104_codigo)){
         $sql2 .= " where matestoqueitemanulacao.m104_codigo = $m104_codigo ";
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
