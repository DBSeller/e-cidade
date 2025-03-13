<?php

class cl_obrasrenovacaoalvara
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
    public $ob33_codobra = 0; 
    public $ob33_dtrenovacao_dia = null; 
    public $ob33_dtrenovacao_mes = null; 
    public $ob33_dtrenovacao_ano = null; 
    public $ob33_dtrenovacao = null; 
    public $ob33_dtvalidade_dia = null; 
    public $ob33_dtvalidade_mes = null; 
    public $ob33_dtvalidade_ano = null; 
    public $ob33_dtvalidade = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 ob33_codobra = int4 = ob33_codobra 
                 ob33_dtrenovacao = date = Data de renovação do alvará 
                 ob33_dtvalidade = date = Data de validade do alvará 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("obrasrenovacaoalvara"); 
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
       $this->ob33_codobra = ($this->ob33_codobra == ""?@$GLOBALS["HTTP_POST_VARS"]["ob33_codobra"]:$this->ob33_codobra);
       if($this->ob33_dtrenovacao == ""){
         $this->ob33_dtrenovacao_dia = ($this->ob33_dtrenovacao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ob33_dtrenovacao_dia"]:$this->ob33_dtrenovacao_dia);
         $this->ob33_dtrenovacao_mes = ($this->ob33_dtrenovacao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ob33_dtrenovacao_mes"]:$this->ob33_dtrenovacao_mes);
         $this->ob33_dtrenovacao_ano = ($this->ob33_dtrenovacao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ob33_dtrenovacao_ano"]:$this->ob33_dtrenovacao_ano);
         if($this->ob33_dtrenovacao_dia != ""){
            $this->ob33_dtrenovacao = $this->ob33_dtrenovacao_ano."-".$this->ob33_dtrenovacao_mes."-".$this->ob33_dtrenovacao_dia;
         }
       }
       if($this->ob33_dtvalidade == ""){
         $this->ob33_dtvalidade_dia = ($this->ob33_dtvalidade_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ob33_dtvalidade_dia"]:$this->ob33_dtvalidade_dia);
         $this->ob33_dtvalidade_mes = ($this->ob33_dtvalidade_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ob33_dtvalidade_mes"]:$this->ob33_dtvalidade_mes);
         $this->ob33_dtvalidade_ano = ($this->ob33_dtvalidade_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ob33_dtvalidade_ano"]:$this->ob33_dtvalidade_ano);
         if($this->ob33_dtvalidade_dia != ""){
            $this->ob33_dtvalidade = $this->ob33_dtvalidade_ano."-".$this->ob33_dtvalidade_mes."-".$this->ob33_dtvalidade_dia;
         }
       }
     }else{
     }
   }

    public function incluir()
    {
      $this->atualizacampos();
     if($this->ob33_codobra == null ){ 
       $this->erro_sql = " Campo ob33_codobra não informado.";
       $this->erro_campo = "ob33_codobra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob33_dtrenovacao == null ){ 
       $this->erro_sql = " Campo Data de renovação do alvará não informado.";
       $this->erro_campo = "ob33_dtrenovacao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob33_dtvalidade == null ){ 
       $this->erro_sql = " Campo Data de validade do alvará não informado.";
       $this->erro_campo = "ob33_dtvalidade_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into obrasrenovacaoalvara(
                                       ob33_codobra 
                                      ,ob33_dtrenovacao 
                                      ,ob33_dtvalidade 
                       )
                values (
                                $this->ob33_codobra 
                               ,".($this->ob33_dtrenovacao == "null" || $this->ob33_dtrenovacao == ""?"null":"'".$this->ob33_dtrenovacao."'")." 
                               ,".($this->ob33_dtvalidade == "null" || $this->ob33_dtvalidade == ""?"null":"'".$this->ob33_dtvalidade."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Data de renovação do alvará () não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Data de renovação do alvará já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Data de renovação do alvará () não Incluído. Inclusão Abortada.";
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

    public function alterar( $oid=null )
    {
      $this->atualizacampos();
     $sql = " update obrasrenovacaoalvara set ";
     $virgula = "";
     if(trim($this->ob33_codobra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob33_codobra"])){ 
       $sql  .= $virgula." ob33_codobra = $this->ob33_codobra ";
       $virgula = ",";
       if(trim($this->ob33_codobra) == null ){ 
         $this->erro_sql = " Campo ob33_codobra não informado.";
         $this->erro_campo = "ob33_codobra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob33_dtrenovacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob33_dtrenovacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ob33_dtrenovacao_dia"] !="") ){ 
       $sql  .= $virgula." ob33_dtrenovacao = '$this->ob33_dtrenovacao' ";
       $virgula = ",";
       if(trim($this->ob33_dtrenovacao) == null ){ 
         $this->erro_sql = " Campo Data de renovação do alvará não informado.";
         $this->erro_campo = "ob33_dtrenovacao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ob33_dtrenovacao_dia"])){ 
         $sql  .= $virgula." ob33_dtrenovacao = null ";
         $virgula = ",";
         if(trim($this->ob33_dtrenovacao) == null ){ 
           $this->erro_sql = " Campo Data de renovação do alvará não informado.";
           $this->erro_campo = "ob33_dtrenovacao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ob33_dtvalidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob33_dtvalidade_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ob33_dtvalidade_dia"] !="") ){ 
       $sql  .= $virgula." ob33_dtvalidade = '$this->ob33_dtvalidade' ";
       $virgula = ",";
       if(trim($this->ob33_dtvalidade) == null ){ 
         $this->erro_sql = " Campo Data de validade do alvará não informado.";
         $this->erro_campo = "ob33_dtvalidade_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ob33_dtvalidade_dia"])){ 
         $sql  .= $virgula." ob33_dtvalidade = null ";
         $virgula = ",";
         if(trim($this->ob33_dtvalidade) == null ){ 
           $this->erro_sql = " Campo Data de validade do alvará não informado.";
           $this->erro_campo = "ob33_dtvalidade_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     $sql .= "ob33_codobra = '$oid'"; $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Data de renovação do alvará não Alterado. Alteração Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Data de renovação do alvará não foi Alterado. Alteração Executada.\\n";
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
     $sql = " delete from obrasrenovacaoalvara
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
       $this->erro_sql   = "Data de renovação do alvará não Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Data de renovação do alvará não Encontrado. Exclusão não Efetuada.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:obrasrenovacaoalvara";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($oid = null, $campos = "obrasrenovacaoalvara.oid,*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from obrasrenovacaoalvara ";
     $sql .= "      inner join obrasalvara  on  obrasalvara.ob04_codobra = obrasrenovacaoalvara.ob33_codobra";
     $sql .= "      inner join obras  on  obras.ob01_codobra = obrasalvara.ob04_codobra";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($oid)) {
          $sql2 = " where obrasrenovacaoalvara.oid = '$oid'";
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
     $sql .= "  from obrasrenovacaoalvara ";
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
