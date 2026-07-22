<?php

class cl_historicompsarea
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
    public $ed170_codigo = 0; 
    public $ed170_historicomps = 0; 
    public $ed170_areaconhecimento = 0; 
    public $ed170_resultadoobtido = null; 
    public $ed170_resultadofinal = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 ed170_codigo = int4 = Código 
                 ed170_historicomps = int4 = Histórico Mps 
                 ed170_areaconhecimento = int4 = Área de Conhecimento 
                 ed170_resultadoobtido = text = Resultado Obtido 
                 ed170_resultadofinal = char(1) = Resultado Final 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("historicompsarea"); 
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
       $this->ed170_codigo = ($this->ed170_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed170_codigo"]:$this->ed170_codigo);
       $this->ed170_historicomps = ($this->ed170_historicomps == ""?@$GLOBALS["HTTP_POST_VARS"]["ed170_historicomps"]:$this->ed170_historicomps);
       $this->ed170_areaconhecimento = ($this->ed170_areaconhecimento == ""?@$GLOBALS["HTTP_POST_VARS"]["ed170_areaconhecimento"]:$this->ed170_areaconhecimento);
       $this->ed170_resultadoobtido = ($this->ed170_resultadoobtido == ""?@$GLOBALS["HTTP_POST_VARS"]["ed170_resultadoobtido"]:$this->ed170_resultadoobtido);
       $this->ed170_resultadofinal = ($this->ed170_resultadofinal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed170_resultadofinal"]:$this->ed170_resultadofinal);
     }else{
       $this->ed170_codigo = ($this->ed170_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed170_codigo"]:$this->ed170_codigo);
     }
   }

    public function incluir($ed170_codigo)
    {
      $this->atualizacampos();
     if($this->ed170_historicomps == null ){ 
       $this->erro_sql = " Campo Histórico Mps não informado.";
       $this->erro_campo = "ed170_historicomps";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed170_areaconhecimento == null ){ 
       $this->erro_sql = " Campo Área de Conhecimento não informado.";
       $this->erro_campo = "ed170_areaconhecimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed170_codigo == "" || $ed170_codigo == null ){
       $result = db_query("select nextval('historicompsarea_ed170_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: historicompsarea_ed170_codigo_seq do campo: ed170_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed170_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from historicompsarea_ed170_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed170_codigo)){
         $this->erro_sql = " Campo ed170_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed170_codigo = $ed170_codigo; 
       }
     }
     if(($this->ed170_codigo == null) || ($this->ed170_codigo == "") ){ 
       $this->erro_sql = " Campo ed170_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into historicompsarea(
                                       ed170_codigo 
                                      ,ed170_historicomps 
                                      ,ed170_areaconhecimento 
                                      ,ed170_resultadoobtido 
                                      ,ed170_resultadofinal 
                       )
                values (
                                $this->ed170_codigo 
                               ,$this->ed170_historicomps 
                               ,$this->ed170_areaconhecimento 
                               ,'$this->ed170_resultadoobtido' 
                               ,'$this->ed170_resultadofinal' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Histórico MPS Área ($this->ed170_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Histórico MPS Área já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Histórico MPS Área ($this->ed170_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed170_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed170_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1012045,'$this->ed170_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010677,1012045,'','".AddSlashes(pg_result($resaco,0,'ed170_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010677,1012046,'','".AddSlashes(pg_result($resaco,0,'ed170_historicomps'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010677,1012047,'','".AddSlashes(pg_result($resaco,0,'ed170_areaconhecimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010677,1012048,'','".AddSlashes(pg_result($resaco,0,'ed170_resultadoobtido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010677,1012049,'','".AddSlashes(pg_result($resaco,0,'ed170_resultadofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($ed170_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update historicompsarea set ";
     $virgula = "";
     if(trim($this->ed170_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed170_codigo"])){ 
       $sql  .= $virgula." ed170_codigo = $this->ed170_codigo ";
       $virgula = ",";
       if(trim($this->ed170_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed170_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed170_historicomps)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed170_historicomps"])){ 
       $sql  .= $virgula." ed170_historicomps = $this->ed170_historicomps ";
       $virgula = ",";
       if(trim($this->ed170_historicomps) == null ){ 
         $this->erro_sql = " Campo Histórico Mps não informado.";
         $this->erro_campo = "ed170_historicomps";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed170_areaconhecimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed170_areaconhecimento"])){ 
       $sql  .= $virgula." ed170_areaconhecimento = $this->ed170_areaconhecimento ";
       $virgula = ",";
       if(trim($this->ed170_areaconhecimento) == null ){ 
         $this->erro_sql = " Campo Área de Conhecimento não informado.";
         $this->erro_campo = "ed170_areaconhecimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed170_resultadoobtido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed170_resultadoobtido"])){ 
       $sql  .= $virgula." ed170_resultadoobtido = '$this->ed170_resultadoobtido' ";
       $virgula = ",";
     }
     if(trim($this->ed170_resultadofinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed170_resultadofinal"])){ 
       $sql  .= $virgula." ed170_resultadofinal = '$this->ed170_resultadofinal' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed170_codigo!=null){
       $sql .= " ed170_codigo = $this->ed170_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed170_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1012045,'$this->ed170_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed170_codigo"]) || $this->ed170_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010677,1012045,'".AddSlashes(pg_result($resaco,$conresaco,'ed170_codigo'))."','$this->ed170_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed170_historicomps"]) || $this->ed170_historicomps != "")
             $resac = db_query("insert into db_acount values($acount,1010677,1012046,'".AddSlashes(pg_result($resaco,$conresaco,'ed170_historicomps'))."','$this->ed170_historicomps',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed170_areaconhecimento"]) || $this->ed170_areaconhecimento != "")
             $resac = db_query("insert into db_acount values($acount,1010677,1012047,'".AddSlashes(pg_result($resaco,$conresaco,'ed170_areaconhecimento'))."','$this->ed170_areaconhecimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed170_resultadoobtido"]) || $this->ed170_resultadoobtido != "")
             $resac = db_query("insert into db_acount values($acount,1010677,1012048,'".AddSlashes(pg_result($resaco,$conresaco,'ed170_resultadoobtido'))."','$this->ed170_resultadoobtido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed170_resultadofinal"]) || $this->ed170_resultadofinal != "")
             $resac = db_query("insert into db_acount values($acount,1010677,1012049,'".AddSlashes(pg_result($resaco,$conresaco,'ed170_resultadofinal'))."','$this->ed170_resultadofinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Histórico MPS Área não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed170_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Histórico MPS Área não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed170_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed170_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($ed170_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed170_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1012045,'$ed170_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010677,1012045,'','".AddSlashes(pg_result($resaco,$iresaco,'ed170_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010677,1012046,'','".AddSlashes(pg_result($resaco,$iresaco,'ed170_historicomps'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010677,1012047,'','".AddSlashes(pg_result($resaco,$iresaco,'ed170_areaconhecimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010677,1012048,'','".AddSlashes(pg_result($resaco,$iresaco,'ed170_resultadoobtido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010677,1012049,'','".AddSlashes(pg_result($resaco,$iresaco,'ed170_resultadofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from historicompsarea
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed170_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed170_codigo = $ed170_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Histórico MPS Área não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed170_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Histórico MPS Área não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed170_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ed170_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:historicompsarea";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($ed170_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from historicompsarea ";
     $sql .= "      inner join areaconhecimento  on  areaconhecimento.ed293_sequencial = historicompsarea.ed170_areaconhecimento";
     $sql .= "      inner join historicomps  on  historicomps.ed62_i_codigo = historicompsarea.ed170_historicomps";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = historicomps.ed62_i_escola";
     $sql .= "      left  join justificativa  on  justificativa.ed06_i_codigo = historicomps.ed62_i_justificativa";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = historicomps.ed62_i_serie";
     $sql .= "      inner join historico  as a on   a.ed61_i_codigo = historicomps.ed62_i_historico";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed170_codigo)) {
         $sql2 .= " where historicompsarea.ed170_codigo = $ed170_codigo "; 
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

    public function sql_query_file($ed170_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from historicompsarea ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed170_codigo)){
         $sql2 .= " where historicompsarea.ed170_codigo = $ed170_codigo "; 
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
