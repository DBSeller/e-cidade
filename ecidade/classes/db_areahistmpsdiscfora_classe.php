<?php

class cl_areahistmpsdiscfora
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
    public $ed173_codigo = 0; 
    public $ed173_historicompsforaarea = 0; 
    public $ed173_histmpsdiscfora = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 ed173_codigo = int4 = Código 
                 ed173_historicompsforaarea = int4 = Histórico MPS Fora Área 
                 ed173_histmpsdiscfora = int4 = Histórico MPS Disciplina Fora 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("areahistmpsdiscfora"); 
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
       $this->ed173_codigo = ($this->ed173_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed173_codigo"]:$this->ed173_codigo);
       $this->ed173_historicompsforaarea = ($this->ed173_historicompsforaarea == ""?@$GLOBALS["HTTP_POST_VARS"]["ed173_historicompsforaarea"]:$this->ed173_historicompsforaarea);
       $this->ed173_histmpsdiscfora = ($this->ed173_histmpsdiscfora == ""?@$GLOBALS["HTTP_POST_VARS"]["ed173_histmpsdiscfora"]:$this->ed173_histmpsdiscfora);
     }else{
       $this->ed173_codigo = ($this->ed173_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed173_codigo"]:$this->ed173_codigo);
     }
   }

    public function incluir($ed173_codigo)
    {
      $this->atualizacampos();
     if($this->ed173_historicompsforaarea == null ){ 
       $this->erro_sql = " Campo Histórico MPS Fora Área não informado.";
       $this->erro_campo = "ed173_historicompsforaarea";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed173_histmpsdiscfora == null ){ 
       $this->erro_sql = " Campo Histórico MPS Disciplina Fora não informado.";
       $this->erro_campo = "ed173_histmpsdiscfora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed173_codigo == "" || $ed173_codigo == null ){
       $result = db_query("select nextval('areahistmpsdiscfora_ed173_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: areahistmpsdiscfora_ed173_codigo_seq do campo: ed173_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed173_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from areahistmpsdiscfora_ed173_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed173_codigo)){
         $this->erro_sql = " Campo ed173_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed173_codigo = $ed173_codigo; 
       }
     }
     if(($this->ed173_codigo == null) || ($this->ed173_codigo == "") ){ 
       $this->erro_sql = " Campo ed173_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into areahistmpsdiscfora(
                                       ed173_codigo 
                                      ,ed173_historicompsforaarea 
                                      ,ed173_histmpsdiscfora 
                       )
                values (
                                $this->ed173_codigo 
                               ,$this->ed173_historicompsforaarea 
                               ,$this->ed173_histmpsdiscfora 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Histórico Area de Conhecimento por Etapa Fora ($this->ed173_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Histórico Area de Conhecimento por Etapa Fora já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Histórico Area de Conhecimento por Etapa Fora ($this->ed173_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed173_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed173_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1012058,'$this->ed173_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010680,1012058,'','".AddSlashes(pg_result($resaco,0,'ed173_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010680,1012059,'','".AddSlashes(pg_result($resaco,0,'ed173_historicompsforaarea'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010680,1012060,'','".AddSlashes(pg_result($resaco,0,'ed173_histmpsdiscfora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($ed173_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update areahistmpsdiscfora set ";
     $virgula = "";
     if(trim($this->ed173_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed173_codigo"])){ 
       $sql  .= $virgula." ed173_codigo = $this->ed173_codigo ";
       $virgula = ",";
       if(trim($this->ed173_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed173_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed173_historicompsforaarea)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed173_historicompsforaarea"])){ 
       $sql  .= $virgula." ed173_historicompsforaarea = $this->ed173_historicompsforaarea ";
       $virgula = ",";
       if(trim($this->ed173_historicompsforaarea) == null ){ 
         $this->erro_sql = " Campo Histórico MPS Fora Área não informado.";
         $this->erro_campo = "ed173_historicompsforaarea";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed173_histmpsdiscfora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed173_histmpsdiscfora"])){ 
       $sql  .= $virgula." ed173_histmpsdiscfora = $this->ed173_histmpsdiscfora ";
       $virgula = ",";
       if(trim($this->ed173_histmpsdiscfora) == null ){ 
         $this->erro_sql = " Campo Histórico MPS Disciplina Fora não informado.";
         $this->erro_campo = "ed173_histmpsdiscfora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed173_codigo!=null){
       $sql .= " ed173_codigo = $this->ed173_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed173_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1012058,'$this->ed173_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed173_codigo"]) || $this->ed173_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010680,1012058,'".AddSlashes(pg_result($resaco,$conresaco,'ed173_codigo'))."','$this->ed173_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed173_historicompsforaarea"]) || $this->ed173_historicompsforaarea != "")
             $resac = db_query("insert into db_acount values($acount,1010680,1012059,'".AddSlashes(pg_result($resaco,$conresaco,'ed173_historicompsforaarea'))."','$this->ed173_historicompsforaarea',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed173_histmpsdiscfora"]) || $this->ed173_histmpsdiscfora != "")
             $resac = db_query("insert into db_acount values($acount,1010680,1012060,'".AddSlashes(pg_result($resaco,$conresaco,'ed173_histmpsdiscfora'))."','$this->ed173_histmpsdiscfora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Histórico Area de Conhecimento por Etapa Fora não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed173_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Histórico Area de Conhecimento por Etapa Fora não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed173_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed173_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($ed173_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed173_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1012058,'$ed173_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010680,1012058,'','".AddSlashes(pg_result($resaco,$iresaco,'ed173_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010680,1012059,'','".AddSlashes(pg_result($resaco,$iresaco,'ed173_historicompsforaarea'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010680,1012060,'','".AddSlashes(pg_result($resaco,$iresaco,'ed173_histmpsdiscfora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from areahistmpsdiscfora
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed173_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed173_codigo = $ed173_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Histórico Area de Conhecimento por Etapa Fora não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed173_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Histórico Area de Conhecimento por Etapa Fora não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed173_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ed173_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:areahistmpsdiscfora";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($ed173_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from areahistmpsdiscfora ";
     $sql .= "      inner join histmpsdiscfora  on  histmpsdiscfora.ed100_i_codigo = areahistmpsdiscfora.ed173_histmpsdiscfora";
     $sql .= "      inner join historicompsforaarea  on  historicompsforaarea.ed172_codigo = areahistmpsdiscfora.ed173_historicompsforaarea";
     $sql .= "      left  join justificativa  on  justificativa.ed06_i_codigo = histmpsdiscfora.ed100_i_justificativa";
     $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = histmpsdiscfora.ed100_i_disciplina";
     $sql .= "      inner join historicompsfora  as a on   a.ed99_i_codigo = histmpsdiscfora.ed100_i_historicompsfora";
     $sql .= "      inner join areaconhecimento  on  areaconhecimento.ed293_sequencial = historicompsforaarea.ed172_areaconhecimento";
     $sql .= "      inner join historicompsfora  as b on   b.ed99_i_codigo = historicompsforaarea.ed172_historicompsfora";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed173_codigo)) {
         $sql2 .= " where areahistmpsdiscfora.ed173_codigo = $ed173_codigo "; 
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

    public function sql_query_file($ed173_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from areahistmpsdiscfora ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed173_codigo)){
         $sql2 .= " where areahistmpsdiscfora.ed173_codigo = $ed173_codigo "; 
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
