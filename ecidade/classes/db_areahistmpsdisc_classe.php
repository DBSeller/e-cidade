<?php

class cl_areahistmpsdisc
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
    public $ed171_codigo = 0; 
    public $ed171_historicompsarea = 0; 
    public $ed171_histmpsdisc = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 ed171_codigo = int4 = Código 
                 ed171_historicompsarea = int4 = Histórico MPS Área 
                 ed171_histmpsdisc = int4 = Histórico MPS Disciplina 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("areahistmpsdisc"); 
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
       $this->ed171_codigo = ($this->ed171_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed171_codigo"]:$this->ed171_codigo);
       $this->ed171_historicompsarea = ($this->ed171_historicompsarea == ""?@$GLOBALS["HTTP_POST_VARS"]["ed171_historicompsarea"]:$this->ed171_historicompsarea);
       $this->ed171_histmpsdisc = ($this->ed171_histmpsdisc == ""?@$GLOBALS["HTTP_POST_VARS"]["ed171_histmpsdisc"]:$this->ed171_histmpsdisc);
     }else{
       $this->ed171_codigo = ($this->ed171_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed171_codigo"]:$this->ed171_codigo);
     }
   }

    public function incluir($ed171_codigo)
    {
      $this->atualizacampos();
     if($this->ed171_historicompsarea == null ){ 
       $this->erro_sql = " Campo Histórico MPS Área não informado.";
       $this->erro_campo = "ed171_historicompsarea";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed171_histmpsdisc == null ){ 
       $this->erro_sql = " Campo Histórico MPS Disciplina não informado.";
       $this->erro_campo = "ed171_histmpsdisc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed171_codigo == "" || $ed171_codigo == null ){
       $result = db_query("select nextval('areahistmpsdisc_ed171_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: areahistmpsdisc_ed171_codigo_seq do campo: ed171_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed171_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from areahistmpsdisc_ed171_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed171_codigo)){
         $this->erro_sql = " Campo ed171_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed171_codigo = $ed171_codigo; 
       }
     }
     if(($this->ed171_codigo == null) || ($this->ed171_codigo == "") ){ 
       $this->erro_sql = " Campo ed171_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into areahistmpsdisc(
                                       ed171_codigo 
                                      ,ed171_historicompsarea 
                                      ,ed171_histmpsdisc 
                       )
                values (
                                $this->ed171_codigo 
                               ,$this->ed171_historicompsarea 
                               ,$this->ed171_histmpsdisc 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Histórico Area de Conhecimento por Etapa ($this->ed171_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Histórico Area de Conhecimento por Etapa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Histórico Area de Conhecimento por Etapa ($this->ed171_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed171_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed171_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1012055,'$this->ed171_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010678,1012055,'','".AddSlashes(pg_result($resaco,0,'ed171_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010678,1012056,'','".AddSlashes(pg_result($resaco,0,'ed171_historicompsarea'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010678,1012057,'','".AddSlashes(pg_result($resaco,0,'ed171_histmpsdisc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($ed171_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update areahistmpsdisc set ";
     $virgula = "";
     if(trim($this->ed171_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed171_codigo"])){ 
       $sql  .= $virgula." ed171_codigo = $this->ed171_codigo ";
       $virgula = ",";
       if(trim($this->ed171_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed171_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed171_historicompsarea)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed171_historicompsarea"])){ 
       $sql  .= $virgula." ed171_historicompsarea = $this->ed171_historicompsarea ";
       $virgula = ",";
       if(trim($this->ed171_historicompsarea) == null ){ 
         $this->erro_sql = " Campo Histórico MPS Área não informado.";
         $this->erro_campo = "ed171_historicompsarea";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed171_histmpsdisc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed171_histmpsdisc"])){ 
       $sql  .= $virgula." ed171_histmpsdisc = $this->ed171_histmpsdisc ";
       $virgula = ",";
       if(trim($this->ed171_histmpsdisc) == null ){ 
         $this->erro_sql = " Campo Histórico MPS Disciplina não informado.";
         $this->erro_campo = "ed171_histmpsdisc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed171_codigo!=null){
       $sql .= " ed171_codigo = $this->ed171_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed171_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1012055,'$this->ed171_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed171_codigo"]) || $this->ed171_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010678,1012055,'".AddSlashes(pg_result($resaco,$conresaco,'ed171_codigo'))."','$this->ed171_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed171_historicompsarea"]) || $this->ed171_historicompsarea != "")
             $resac = db_query("insert into db_acount values($acount,1010678,1012056,'".AddSlashes(pg_result($resaco,$conresaco,'ed171_historicompsarea'))."','$this->ed171_historicompsarea',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed171_histmpsdisc"]) || $this->ed171_histmpsdisc != "")
             $resac = db_query("insert into db_acount values($acount,1010678,1012057,'".AddSlashes(pg_result($resaco,$conresaco,'ed171_histmpsdisc'))."','$this->ed171_histmpsdisc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Histórico Area de Conhecimento por Etapa não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed171_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Histórico Area de Conhecimento por Etapa não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed171_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed171_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($ed171_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed171_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1012055,'$ed171_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010678,1012055,'','".AddSlashes(pg_result($resaco,$iresaco,'ed171_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010678,1012056,'','".AddSlashes(pg_result($resaco,$iresaco,'ed171_historicompsarea'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010678,1012057,'','".AddSlashes(pg_result($resaco,$iresaco,'ed171_histmpsdisc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from areahistmpsdisc
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed171_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed171_codigo = $ed171_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Histórico Area de Conhecimento por Etapa não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed171_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Histórico Area de Conhecimento por Etapa não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed171_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ed171_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:areahistmpsdisc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($ed171_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from areahistmpsdisc ";
     $sql .= "      inner join histmpsdisc  on  histmpsdisc.ed65_i_codigo = areahistmpsdisc.ed171_histmpsdisc";
     $sql .= "      inner join historicompsarea  on  historicompsarea.ed170_codigo = areahistmpsdisc.ed171_historicompsarea";
     $sql .= "      left  join justificativa  on  justificativa.ed06_i_codigo = histmpsdisc.ed65_i_justificativa";
     $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = histmpsdisc.ed65_i_disciplina";
     $sql .= "      inner join historicomps  as a on   a.ed62_i_codigo = histmpsdisc.ed65_i_historicomps";
     $sql .= "      inner join areaconhecimento  on  areaconhecimento.ed293_sequencial = historicompsarea.ed170_areaconhecimento";
     $sql .= "      inner join historicomps  as b on   b.ed62_i_codigo = historicompsarea.ed170_historicomps";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed171_codigo)) {
         $sql2 .= " where areahistmpsdisc.ed171_codigo = $ed171_codigo "; 
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

    public function sql_query_file($ed171_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from areahistmpsdisc ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed171_codigo)){
         $sql2 .= " where areahistmpsdisc.ed171_codigo = $ed171_codigo "; 
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
