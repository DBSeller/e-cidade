<?php

class cl_processo_atividadesexecucao
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
    public $p118_codigo = 0; 
    public $p118_protprocesso = 0; 
    public $p118_atividadesexecucao = 0; 
    public $p118_ordem = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 p118_codigo = int8 = Código 
                 p118_protprocesso = int8 = Processo 
                 p118_atividadesexecucao = int8 = Atividades de execução 
                 p118_ordem = int4 = Ordem 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("processo_atividadesexecucao"); 
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
       $this->p118_codigo = ($this->p118_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["p118_codigo"]:$this->p118_codigo);
       $this->p118_protprocesso = ($this->p118_protprocesso == ""?@$GLOBALS["HTTP_POST_VARS"]["p118_protprocesso"]:$this->p118_protprocesso);
       $this->p118_atividadesexecucao = ($this->p118_atividadesexecucao == ""?@$GLOBALS["HTTP_POST_VARS"]["p118_atividadesexecucao"]:$this->p118_atividadesexecucao);
       $this->p118_ordem = ($this->p118_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["p118_ordem"]:$this->p118_ordem);
     }else{
       $this->p118_codigo = ($this->p118_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["p118_codigo"]:$this->p118_codigo);
     }
   }

    public function incluir($p118_codigo)
    {
      $this->atualizacampos();
     if($this->p118_protprocesso == null ){ 
       $this->erro_sql = " Campo Processo não informado.";
       $this->erro_campo = "p118_protprocesso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p118_atividadesexecucao == null ){ 
       $this->erro_sql = " Campo Atividades de execução não informado.";
       $this->erro_campo = "p118_atividadesexecucao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p118_ordem == null ){ 
       $this->erro_sql = " Campo Ordem não informado.";
       $this->erro_campo = "p118_ordem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($p118_codigo == "" || $p118_codigo == null ){
       $result = db_query("select nextval('processo_atividadesexecucao_p118_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: processo_atividadesexecucao_p118_codigo_seq do campo: p118_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->p118_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from processo_atividadesexecucao_p118_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $p118_codigo)){
         $this->erro_sql = " Campo p118_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->p118_codigo = $p118_codigo; 
       }
     }
     if(($this->p118_codigo == null) || ($this->p118_codigo == "") ){ 
       $this->erro_sql = " Campo p118_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into processo_atividadesexecucao(
                                       p118_codigo 
                                      ,p118_protprocesso 
                                      ,p118_atividadesexecucao 
                                      ,p118_ordem 
                       )
                values (
                                $this->p118_codigo 
                               ,$this->p118_protprocesso 
                               ,$this->p118_atividadesexecucao 
                               ,$this->p118_ordem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Atividades de execução ($this->p118_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Atividades de execução já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Atividades de execução ($this->p118_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->p118_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->p118_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1014029,'$this->p118_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010905,1014029,'','".AddSlashes(pg_result($resaco,0,'p118_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010905,1014030,'','".AddSlashes(pg_result($resaco,0,'p118_protprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010905,1014031,'','".AddSlashes(pg_result($resaco,0,'p118_atividadesexecucao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010905,1014032,'','".AddSlashes(pg_result($resaco,0,'p118_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($p118_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update processo_atividadesexecucao set ";
     $virgula = "";
     if(trim($this->p118_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p118_codigo"])){ 
       $sql  .= $virgula." p118_codigo = $this->p118_codigo ";
       $virgula = ",";
       if(trim($this->p118_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "p118_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p118_protprocesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p118_protprocesso"])){ 
       $sql  .= $virgula." p118_protprocesso = $this->p118_protprocesso ";
       $virgula = ",";
       if(trim($this->p118_protprocesso) == null ){ 
         $this->erro_sql = " Campo Processo não informado.";
         $this->erro_campo = "p118_protprocesso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p118_atividadesexecucao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p118_atividadesexecucao"])){ 
       $sql  .= $virgula." p118_atividadesexecucao = $this->p118_atividadesexecucao ";
       $virgula = ",";
       if(trim($this->p118_atividadesexecucao) == null ){ 
         $this->erro_sql = " Campo Atividades de execução não informado.";
         $this->erro_campo = "p118_atividadesexecucao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p118_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p118_ordem"])){ 
       $sql  .= $virgula." p118_ordem = $this->p118_ordem ";
       $virgula = ",";
       if(trim($this->p118_ordem) == null ){ 
         $this->erro_sql = " Campo Ordem não informado.";
         $this->erro_campo = "p118_ordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($p118_codigo!=null){
       $sql .= " p118_codigo = $this->p118_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->p118_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1014029,'$this->p118_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p118_codigo"]) || $this->p118_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010905,1014029,'".AddSlashes(pg_result($resaco,$conresaco,'p118_codigo'))."','$this->p118_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p118_protprocesso"]) || $this->p118_protprocesso != "")
             $resac = db_query("insert into db_acount values($acount,1010905,1014030,'".AddSlashes(pg_result($resaco,$conresaco,'p118_protprocesso'))."','$this->p118_protprocesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p118_atividadesexecucao"]) || $this->p118_atividadesexecucao != "")
             $resac = db_query("insert into db_acount values($acount,1010905,1014031,'".AddSlashes(pg_result($resaco,$conresaco,'p118_atividadesexecucao'))."','$this->p118_atividadesexecucao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["p118_ordem"]) || $this->p118_ordem != "")
             $resac = db_query("insert into db_acount values($acount,1010905,1014032,'".AddSlashes(pg_result($resaco,$conresaco,'p118_ordem'))."','$this->p118_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atividades de execução não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->p118_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Atividades de execução não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->p118_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->p118_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($p118_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($p118_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1014029,'$p118_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010905,1014029,'','".AddSlashes(pg_result($resaco,$iresaco,'p118_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010905,1014030,'','".AddSlashes(pg_result($resaco,$iresaco,'p118_protprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010905,1014031,'','".AddSlashes(pg_result($resaco,$iresaco,'p118_atividadesexecucao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010905,1014032,'','".AddSlashes(pg_result($resaco,$iresaco,'p118_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from processo_atividadesexecucao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($p118_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " p118_codigo = $p118_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atividades de execução não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$p118_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Atividades de execução não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$p118_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$p118_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:processo_atividadesexecucao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($p118_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from processo_atividadesexecucao ";
     $sql .= "      inner join protprocesso  on  protprocesso.p58_codproc = processo_atividadesexecucao.p118_protprocesso";
     $sql .= "      inner join atividadesexecucao  on  atividadesexecucao.p114_codigo = processo_atividadesexecucao.p118_atividadesexecucao";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = protprocesso.p58_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = protprocesso.p58_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = protprocesso.p58_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = protprocesso.p58_coddepto";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = protprocesso.p58_codigo";
     $sql .= "      inner join tipoprocesso  on  tipoprocesso.p109_sequencial = protprocesso.p58_tipoprocesso";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($p118_codigo)) {
         $sql2 .= " where processo_atividadesexecucao.p118_codigo = $p118_codigo "; 
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

    public function sql_query_file($p118_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from processo_atividadesexecucao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($p118_codigo)){
         $sql2 .= " where processo_atividadesexecucao.p118_codigo = $p118_codigo "; 
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
