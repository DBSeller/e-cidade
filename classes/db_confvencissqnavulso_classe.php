<?php

class cl_confvencissqnavulso
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
    public $j178_receita = 0;
    public $j178_histdebito = 0;
    public $j178_tipodebito = 0;
    public $j178_diavenc = 0;
    public $j178_anousu_dia = null;
    public $j178_anousu_mes = null;
    public $j178_anousu_ano = null;
    public $j178_anousu = null;
   // cria propriedade com as variaveis do arquivo
    public $campos = "
                 j178_receita = int4 = Receita
                 j178_histdebito = int8 = Histórico Débito
                 j178_tipodebito = int8 = Tipo Débito
                 j178_diavenc = int8 = Dia Vencimento
                 j178_anousu = date = Ano
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("confvencissqnavulso");
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
       $this->j178_receita = ($this->j178_receita == ""?@$GLOBALS["HTTP_POST_VARS"]["j178_receita"]:$this->j178_receita);
       $this->j178_histdebito = ($this->j178_histdebito == ""?@$GLOBALS["HTTP_POST_VARS"]["j178_histdebito"]:$this->j178_histdebito);
       $this->j178_tipodebito = ($this->j178_tipodebito == ""?@$GLOBALS["HTTP_POST_VARS"]["j178_tipodebito"]:$this->j178_tipodebito);
       $this->j178_diavenc = ($this->j178_diavenc == ""?@$GLOBALS["HTTP_POST_VARS"]["j178_diavenc"]:$this->j178_diavenc);
       if($this->j178_anousu == ""){
         $this->j178_anousu_dia = ($this->j178_anousu_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j178_anousu_dia"]:$this->j178_anousu_dia);
         $this->j178_anousu_mes = ($this->j178_anousu_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j178_anousu_mes"]:$this->j178_anousu_mes);
         $this->j178_anousu_ano = ($this->j178_anousu_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j178_anousu_ano"]:$this->j178_anousu_ano);
         if($this->j178_anousu_dia != ""){
            $this->j178_anousu = $this->j178_anousu_ano."-".$this->j178_anousu_mes."-".$this->j178_anousu_dia;
         }
       }
     }else{
     }
   }

    public function incluir()
    {
      $this->atualizacampos();
     if($this->j178_receita == null ){
       $this->erro_sql = " Campo Receita não informado.";
       $this->erro_campo = "j178_receita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j178_histdebito == null ){
       $this->erro_sql = " Campo Histórico Débito não informado.";
       $this->erro_campo = "j178_histdebito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j178_tipodebito == null ){
       $this->erro_sql = " Campo Tipo Débito não informado.";
       $this->erro_campo = "j178_tipodebito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j178_diavenc == null ){
       $this->j178_diavenc = "0";
     }
     if($this->j178_anousu == null ){
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "j178_anousu_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into confvencissqnavulso(
                                       j178_receita
                                      ,j178_histdebito
                                      ,j178_tipodebito
                                      ,j178_diavenc
                                      ,j178_anousu
                       )
                values (
                                $this->j178_receita
                               ,$this->j178_histdebito
                               ,$this->j178_tipodebito
                               ,$this->j178_diavenc
                               ,".($this->j178_anousu == "null" || $this->j178_anousu == ""?"null":"'".$this->j178_anousu."'")."
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tabela de configuração de Vencimento issqn avulso () não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tabela de configuração de Vencimento issqn avulso já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tabela de configuração de Vencimento issqn avulso () não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

     }
     return true;
   }

    public function alterar( $sWhere )
    {
      $this->atualizacampos();
     $sql = " update confvencissqnavulso set ";
     $virgula = "";
     if(trim($this->j178_receita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j178_receita"])){
       $sql  .= $virgula." j178_receita = $this->j178_receita ";
       $virgula = ",";
       if(trim($this->j178_receita) == null ){
         $this->erro_sql = " Campo Receita não informado.";
         $this->erro_campo = "j178_receita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j178_histdebito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j178_histdebito"])){
       $sql  .= $virgula." j178_histdebito = $this->j178_histdebito ";
       $virgula = ",";
       if(trim($this->j178_histdebito) == null ){
         $this->erro_sql = " Campo Histórico Débito não informado.";
         $this->erro_campo = "j178_histdebito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j178_tipodebito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j178_tipodebito"])){
       $sql  .= $virgula." j178_tipodebito = $this->j178_tipodebito ";
       $virgula = ",";
       if(trim($this->j178_tipodebito) == null ){
         $this->erro_sql = " Campo Tipo Débito não informado.";
         $this->erro_campo = "j178_tipodebito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j178_diavenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j178_diavenc"])){
        if(trim($this->j178_diavenc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j178_diavenc"])){
           $this->j178_diavenc = "0" ;
        }
       $sql  .= $virgula." j178_diavenc = $this->j178_diavenc ";
       $virgula = ",";
     } else {
         $sql  .= $virgula." j178_diavenc = null ";
         $virgula = ",";
     }
     if(trim($this->j178_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j178_anousu_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j178_anousu_dia"] !="") ){
       $sql  .= $virgula." j178_anousu = '$this->j178_anousu' ";
       $virgula = ",";
       if(trim($this->j178_anousu) == null ){
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "j178_anousu_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["j178_anousu_dia"])){
         $sql  .= $virgula." j178_anousu = null ";
         $virgula = ",";
         if(trim($this->j178_anousu) == null ){
           $this->erro_sql = " Campo Ano não informado.";
           $this->erro_campo = "j178_anousu_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
$sql .= $sWhere;   $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela de configuração de Vencimento issqn avulso não Alterado. Alteração Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tabela de configuração de Vencimento issqn avulso não foi Alterado. Alteração Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }

    public function excluir( $oid=null , $dbwhere = null)
    {
     $sql = " delete from confvencissqnavulso
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
       $sql2 = "oid = '$oid'";
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela de configuração de Vencimento issqn avulso não Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tabela de configuração de Vencimento issqn avulso não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:confvencissqnavulso";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($oid = null, $campos = "confvencissqnavulso.oid,*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from confvencissqnavulso ";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = confvencissqnavulso.j178_histdebito";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = confvencissqnavulso.j178_receita";
     $sql .= "      inner join arretipo  on  arretipo.k00_tipo = confvencissqnavulso.j178_tipodebito";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join tabrectipo  on  tabrectipo.k116_sequencial = tabrec.k02_tabrectipo";
     $sql .= "      left  join tabdesc  on  tabdesc.codsubrec = arretipo.k00_taxaespecifica";
     $sql .= "      inner join db_config  on  db_config.codigo = arretipo.k00_instit";
     $sql .= "      inner join cadtipo  on  cadtipo.k03_tipo = arretipo.k03_tipo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($oid)) {
          $sql2 = " where confvencissqnavulso.oid = '$oid'";
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

    public function sql_query_file($oid = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from confvencissqnavulso ";
     $sql2 = "";
     if (empty($dbwhere)) {
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
