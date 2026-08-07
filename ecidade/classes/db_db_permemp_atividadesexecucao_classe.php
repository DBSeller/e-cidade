<?php

class cl_db_permemp_atividadesexecucao
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
    public $db69_codperm = 0; 
    public $db69_atividadesexecucao = 0; 
    public $db69_tipoprocesso = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 db69_codperm = int4 = Código Permissão 
                 db69_atividadesexecucao = int4 = Atividades de execução 
                 db69_tipoprocesso = int8 = Tipo de Processo 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("db_permemp_atividadesexecucao"); 
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
       $this->db69_codperm = ($this->db69_codperm == ""?@$GLOBALS["HTTP_POST_VARS"]["db69_codperm"]:$this->db69_codperm);
       $this->db69_atividadesexecucao = ($this->db69_atividadesexecucao == ""?@$GLOBALS["HTTP_POST_VARS"]["db69_atividadesexecucao"]:$this->db69_atividadesexecucao);
       $this->db69_tipoprocesso = ($this->db69_tipoprocesso == ""?@$GLOBALS["HTTP_POST_VARS"]["db69_tipoprocesso"]:$this->db69_tipoprocesso);
     }else{
     }
   }

    public function incluir()
    {
      $this->atualizacampos();
     if($this->db69_codperm == null ){ 
       $this->erro_sql = " Campo Código Permissão não informado.";
       $this->erro_campo = "db69_codperm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db69_atividadesexecucao == null ){ 
       $this->erro_sql = " Campo Atividades de execução não informado.";
       $this->erro_campo = "db69_atividadesexecucao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db69_tipoprocesso == null ){ 
       $this->erro_sql = " Campo Tipo de Processo não informado.";
       $this->erro_campo = "db69_tipoprocesso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_permemp_atividadesexecucao(
                                       db69_codperm 
                                      ,db69_atividadesexecucao 
                                      ,db69_tipoprocesso 
                       )
                values (
                                $this->db69_codperm 
                               ,$this->db69_atividadesexecucao 
                               ,$this->db69_tipoprocesso 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Permissão Atividade () não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Permissão Atividade já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Permissão Atividade () não Incluído. Inclusão Abortada.";
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
     $sql = " update db_permemp_atividadesexecucao set ";
     $virgula = "";
     if(trim($this->db69_codperm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db69_codperm"])){ 
       $sql  .= $virgula." db69_codperm = $this->db69_codperm ";
       $virgula = ",";
       if(trim($this->db69_codperm) == null ){ 
         $this->erro_sql = " Campo Código Permissão não informado.";
         $this->erro_campo = "db69_codperm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db69_atividadesexecucao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db69_atividadesexecucao"])){ 
       $sql  .= $virgula." db69_atividadesexecucao = $this->db69_atividadesexecucao ";
       $virgula = ",";
       if(trim($this->db69_atividadesexecucao) == null ){ 
         $this->erro_sql = " Campo Atividades de execução não informado.";
         $this->erro_campo = "db69_atividadesexecucao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db69_tipoprocesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db69_tipoprocesso"])){ 
       $sql  .= $virgula." db69_tipoprocesso = $this->db69_tipoprocesso ";
       $virgula = ",";
       if(trim($this->db69_tipoprocesso) == null ){ 
         $this->erro_sql = " Campo Tipo de Processo não informado.";
         $this->erro_campo = "db69_tipoprocesso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Permissão Atividade não Alterado. Alteração Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Permissão Atividade não foi Alterado. Alteração Executada.\\n";
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
     $sql = " delete from db_permemp_atividadesexecucao
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
       $this->erro_sql   = "Permissão Atividade não Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Permissão Atividade não Encontrado. Exclusão não Efetuada.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:db_permemp_atividadesexecucao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($oid = null, $campos = "db_permemp_atividadesexecucao.oid,*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from db_permemp_atividadesexecucao ";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = db_permemp_atividadesexecucao.db69_tipoprocesso";
     $sql .= "      inner join db_permemp  on  db_permemp.db20_codperm = db_permemp_atividadesexecucao.db69_codperm";
     $sql .= "      inner join atividadesexecucao  on  atividadesexecucao.p114_codigo = db_permemp_atividadesexecucao.db69_atividadesexecucao";
     $sql .= "      inner join db_config  on  db_config.codigo = tipoproc.p51_instit";
     $sql .= "      inner join tipoprocgrupo  on  tipoprocgrupo.p40_sequencial = tipoproc.p51_tipoprocgrupo";
     $sql .= "      inner join prottipodocumentoprocesso  on  prottipodocumentoprocesso.p91_sequencial = tipoproc.p51_prottipodocumentoprocesso";
     $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = db_permemp.db20_anousu and  orcorgao.o40_orgao = db_permemp.db20_orgao";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($oid)) {
          $sql2 = " where db_permemp_atividadesexecucao.oid = '$oid'";
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
     $sql .= "  from db_permemp_atividadesexecucao ";
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
