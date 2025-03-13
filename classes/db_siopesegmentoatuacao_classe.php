<?php

class cl_siopesegmentoatuacao
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
    public $si07_segmento = 0; 
    public $si07_descricao = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 si07_segmento = int4 = Código do Segmento de Atuação 
                 si07_descricao = varchar(255) = Segmento de Atuação 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("siopesegmentoatuacao"); 
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
       $this->si07_segmento = ($this->si07_segmento == ""?@$GLOBALS["HTTP_POST_VARS"]["si07_segmento"]:$this->si07_segmento);
       $this->si07_descricao = ($this->si07_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["si07_descricao"]:$this->si07_descricao);
     }else{
       $this->si07_segmento = ($this->si07_segmento == ""?@$GLOBALS["HTTP_POST_VARS"]["si07_segmento"]:$this->si07_segmento);
     }
   }

    public function incluir($si07_segmento)
    {
      $this->atualizacampos();
     if($this->si07_descricao == null ){ 
       $this->erro_sql = " Campo Segmento de Atuação não informado.";
       $this->erro_campo = "si07_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($si07_segmento == "" || $si07_segmento == null ){
       $result = db_query("select nextval('siopesegmentoatuacao_segmento_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: siopesegmentoatuacao_segmento_seq do campo: si07_segmento"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->si07_segmento = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from siopesegmentoatuacao_segmento_seq");
       if(($result != false) && (pg_result($result,0,0) < $si07_segmento)){
         $this->erro_sql = " Campo si07_segmento maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->si07_segmento = $si07_segmento; 
       }
     }
     if(($this->si07_segmento == null) || ($this->si07_segmento == "") ){ 
       $this->erro_sql = " Campo si07_segmento não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into siopesegmentoatuacao(
                                       si07_segmento 
                                      ,si07_descricao 
                       )
                values (
                                $this->si07_segmento 
                               ,'$this->si07_descricao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "siopesegmentoatuacao ($this->si07_segmento) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "siopesegmentoatuacao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "siopesegmentoatuacao ($this->si07_segmento) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->si07_segmento;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->si07_segmento  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,295376267,'$this->si07_segmento','I')");
         $resac = db_query("insert into db_acount values($acount,258028027,295376267,'','".AddSlashes(pg_result($resaco,0,'si07_segmento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,258028027,190195507,'','".AddSlashes(pg_result($resaco,0,'si07_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($si07_segmento=null)
    {
      $this->atualizacampos();
     $sql = " update siopesegmentoatuacao set ";
     $virgula = "";
     if(trim($this->si07_segmento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si07_segmento"])){ 
       $sql  .= $virgula." si07_segmento = $this->si07_segmento ";
       $virgula = ",";
       if(trim($this->si07_segmento) == null ){ 
         $this->erro_sql = " Campo Código do Segmento de Atuação não informado.";
         $this->erro_campo = "si07_segmento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->si07_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["si07_descricao"])){ 
       $sql  .= $virgula." si07_descricao = '$this->si07_descricao' ";
       $virgula = ",";
       if(trim($this->si07_descricao) == null ){ 
         $this->erro_sql = " Campo Segmento de Atuação não informado.";
         $this->erro_campo = "si07_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($si07_segmento!=null){
       $sql .= " si07_segmento = $this->si07_segmento";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->si07_segmento));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,295376267,'$this->si07_segmento','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["si07_segmento"]) || $this->si07_segmento != "")
             $resac = db_query("insert into db_acount values($acount,258028027,295376267,'".AddSlashes(pg_result($resaco,$conresaco,'si07_segmento'))."','$this->si07_segmento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["si07_descricao"]) || $this->si07_descricao != "")
             $resac = db_query("insert into db_acount values($acount,258028027,190195507,'".AddSlashes(pg_result($resaco,$conresaco,'si07_descricao'))."','$this->si07_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "siopesegmentoatuacao não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->si07_segmento;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "siopesegmentoatuacao não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->si07_segmento;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->si07_segmento;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($si07_segmento=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($si07_segmento));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,295376267,'$si07_segmento','E')");
           $resac  = db_query("insert into db_acount values($acount,258028027,295376267,'','".AddSlashes(pg_result($resaco,$iresaco,'si07_segmento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,258028027,190195507,'','".AddSlashes(pg_result($resaco,$iresaco,'si07_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from siopesegmentoatuacao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($si07_segmento)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " si07_segmento = $si07_segmento ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "siopesegmentoatuacao não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$si07_segmento;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "siopesegmentoatuacao não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$si07_segmento;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$si07_segmento;
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
        $this->erro_sql   = "Record Vazio na Tabela:siopesegmentoatuacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($si07_segmento = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from siopesegmentoatuacao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($si07_segmento)) {
         $sql2 .= " where siopesegmentoatuacao.si07_segmento = $si07_segmento "; 
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

    public function sql_query_file($si07_segmento = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from siopesegmentoatuacao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($si07_segmento)){
         $sql2 .= " where siopesegmentoatuacao.si07_segmento = $si07_segmento "; 
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
