<?php

class cl_diario_classe_bncc_habilidade
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
    public $ed156_codigo = 0; 
    public $ed156_diario_classe_bncc = 0; 
    public $ed156_bnccdisciplinas = 0; 
    public $ed156_habilidade = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 ed156_codigo = int4 = Código 
                 ed156_diario_classe_bncc = int4 = Diário de Classe BNCC 
                 ed156_bnccdisciplinas = int4 = Disciplina BNCC 
                 ed156_habilidade = varchar(10) = Habilidade 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("diario_classe_bncc_habilidade"); 
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
       $this->ed156_codigo = ($this->ed156_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed156_codigo"]:$this->ed156_codigo);
       $this->ed156_diario_classe_bncc = ($this->ed156_diario_classe_bncc == ""?@$GLOBALS["HTTP_POST_VARS"]["ed156_diario_classe_bncc"]:$this->ed156_diario_classe_bncc);
       $this->ed156_bnccdisciplinas = ($this->ed156_bnccdisciplinas == ""?@$GLOBALS["HTTP_POST_VARS"]["ed156_bnccdisciplinas"]:$this->ed156_bnccdisciplinas);
       $this->ed156_habilidade = ($this->ed156_habilidade == ""?@$GLOBALS["HTTP_POST_VARS"]["ed156_habilidade"]:$this->ed156_habilidade);
     }else{
       $this->ed156_codigo = ($this->ed156_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed156_codigo"]:$this->ed156_codigo);
     }
   }

    public function incluir($ed156_codigo)
    {
      $this->atualizacampos();
     if($this->ed156_diario_classe_bncc == null ){ 
       $this->erro_sql = " Campo Diário de Classe BNCC não informado.";
       $this->erro_campo = "ed156_diario_classe_bncc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed156_bnccdisciplinas == null ){ 
       $this->erro_sql = " Campo Disciplina BNCC não informado.";
       $this->erro_campo = "ed156_bnccdisciplinas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed156_habilidade == null ){ 
       $this->erro_sql = " Campo Habilidade não informado.";
       $this->erro_campo = "ed156_habilidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed156_codigo == "" || $ed156_codigo == null ){
       $result = db_query("select nextval('diario_classe_bncc_habilidade_ed156_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: diario_classe_bncc_habilidade_ed156_codigo_seq do campo: ed156_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed156_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from diario_classe_bncc_habilidade_ed156_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed156_codigo)){
         $this->erro_sql = " Campo ed156_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed156_codigo = $ed156_codigo; 
       }
     }
     if(($this->ed156_codigo == null) || ($this->ed156_codigo == "") ){ 
       $this->erro_sql = " Campo ed156_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into diario_classe_bncc_habilidade(
                                       ed156_codigo 
                                      ,ed156_diario_classe_bncc 
                                      ,ed156_bnccdisciplinas 
                                      ,ed156_habilidade 
                       )
                values (
                                $this->ed156_codigo 
                               ,$this->ed156_diario_classe_bncc 
                               ,$this->ed156_bnccdisciplinas 
                               ,'$this->ed156_habilidade' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->ed156_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->ed156_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed156_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed156_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1011007,'$this->ed156_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010521,1011007,'','".AddSlashes(pg_result($resaco,0,'ed156_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010521,1011008,'','".AddSlashes(pg_result($resaco,0,'ed156_diario_classe_bncc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010521,1011009,'','".AddSlashes(pg_result($resaco,0,'ed156_bnccdisciplinas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010521,1011010,'','".AddSlashes(pg_result($resaco,0,'ed156_habilidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($ed156_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update diario_classe_bncc_habilidade set ";
     $virgula = "";
     if(trim($this->ed156_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed156_codigo"])){ 
       $sql  .= $virgula." ed156_codigo = $this->ed156_codigo ";
       $virgula = ",";
       if(trim($this->ed156_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed156_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed156_diario_classe_bncc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed156_diario_classe_bncc"])){ 
       $sql  .= $virgula." ed156_diario_classe_bncc = $this->ed156_diario_classe_bncc ";
       $virgula = ",";
       if(trim($this->ed156_diario_classe_bncc) == null ){ 
         $this->erro_sql = " Campo Diário de Classe BNCC não informado.";
         $this->erro_campo = "ed156_diario_classe_bncc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed156_bnccdisciplinas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed156_bnccdisciplinas"])){ 
       $sql  .= $virgula." ed156_bnccdisciplinas = $this->ed156_bnccdisciplinas ";
       $virgula = ",";
       if(trim($this->ed156_bnccdisciplinas) == null ){ 
         $this->erro_sql = " Campo Disciplina BNCC não informado.";
         $this->erro_campo = "ed156_bnccdisciplinas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed156_habilidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed156_habilidade"])){ 
       $sql  .= $virgula." ed156_habilidade = '$this->ed156_habilidade' ";
       $virgula = ",";
       if(trim($this->ed156_habilidade) == null ){ 
         $this->erro_sql = " Campo Habilidade não informado.";
         $this->erro_campo = "ed156_habilidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed156_codigo!=null){
       $sql .= " ed156_codigo = $this->ed156_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed156_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1011007,'$this->ed156_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed156_codigo"]) || $this->ed156_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010521,1011007,'".AddSlashes(pg_result($resaco,$conresaco,'ed156_codigo'))."','$this->ed156_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed156_diario_classe_bncc"]) || $this->ed156_diario_classe_bncc != "")
             $resac = db_query("insert into db_acount values($acount,1010521,1011008,'".AddSlashes(pg_result($resaco,$conresaco,'ed156_diario_classe_bncc'))."','$this->ed156_diario_classe_bncc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed156_bnccdisciplinas"]) || $this->ed156_bnccdisciplinas != "")
             $resac = db_query("insert into db_acount values($acount,1010521,1011009,'".AddSlashes(pg_result($resaco,$conresaco,'ed156_bnccdisciplinas'))."','$this->ed156_bnccdisciplinas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed156_habilidade"]) || $this->ed156_habilidade != "")
             $resac = db_query("insert into db_acount values($acount,1010521,1011010,'".AddSlashes(pg_result($resaco,$conresaco,'ed156_habilidade'))."','$this->ed156_habilidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed156_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = " não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed156_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed156_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($ed156_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed156_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1011007,'$ed156_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010521,1011007,'','".AddSlashes(pg_result($resaco,$iresaco,'ed156_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010521,1011008,'','".AddSlashes(pg_result($resaco,$iresaco,'ed156_diario_classe_bncc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010521,1011009,'','".AddSlashes(pg_result($resaco,$iresaco,'ed156_bnccdisciplinas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010521,1011010,'','".AddSlashes(pg_result($resaco,$iresaco,'ed156_habilidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from diario_classe_bncc_habilidade
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed156_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed156_codigo = $ed156_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed156_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = " não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed156_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ed156_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:diario_classe_bncc_habilidade";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($ed156_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from diario_classe_bncc_habilidade ";
     $sql .= "      inner join bnccdisciplinas  on  bnccdisciplinas.ed149_sequencial = diario_classe_bncc_habilidade.ed156_bnccdisciplinas";
     $sql .= "      inner join diario_classe_bncc  on  diario_classe_bncc.ed155_codigo = diario_classe_bncc_habilidade.ed156_diario_classe_bncc";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = diario_classe_bncc.ed155_db_usuarios";
     $sql .= "      inner join regencia  on  regencia.ed59_i_codigo = diario_classe_bncc.ed155_regencia";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed156_codigo)) {
         $sql2 .= " where diario_classe_bncc_habilidade.ed156_codigo = $ed156_codigo "; 
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

    public function sql_query_file($ed156_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from diario_classe_bncc_habilidade ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed156_codigo)){
         $sql2 .= " where diario_classe_bncc_habilidade.ed156_codigo = $ed156_codigo "; 
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
