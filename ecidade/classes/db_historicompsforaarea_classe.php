<?php

class cl_historicompsforaarea
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
    public $ed172_codigo = 0; 
    public $ed172_historicompsfora = 0; 
    public $ed172_areaconhecimento = 0; 
    public $ed172_resultadoobtido = null; 
    public $ed172_resultadofinal = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 ed172_codigo = int4 = Código 
                 ed172_historicompsfora = int4 = Histórico MPS Fora 
                 ed172_areaconhecimento = float4 = Área de Conhecimento 
                 ed172_resultadoobtido = text = Resultado Obtido 
                 ed172_resultadofinal = char(1) = Resultado FInal 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("historicompsforaarea"); 
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
       $this->ed172_codigo = ($this->ed172_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed172_codigo"]:$this->ed172_codigo);
       $this->ed172_historicompsfora = ($this->ed172_historicompsfora == ""?@$GLOBALS["HTTP_POST_VARS"]["ed172_historicompsfora"]:$this->ed172_historicompsfora);
       $this->ed172_areaconhecimento = ($this->ed172_areaconhecimento == ""?@$GLOBALS["HTTP_POST_VARS"]["ed172_areaconhecimento"]:$this->ed172_areaconhecimento);
       $this->ed172_resultadoobtido = ($this->ed172_resultadoobtido == ""?@$GLOBALS["HTTP_POST_VARS"]["ed172_resultadoobtido"]:$this->ed172_resultadoobtido);
       $this->ed172_resultadofinal = ($this->ed172_resultadofinal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed172_resultadofinal"]:$this->ed172_resultadofinal);
     }else{
       $this->ed172_codigo = ($this->ed172_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed172_codigo"]:$this->ed172_codigo);
     }
   }

    public function incluir($ed172_codigo)
    {
      $this->atualizacampos();
     if($this->ed172_historicompsfora == null ){ 
       $this->erro_sql = " Campo Histórico MPS Fora não informado.";
       $this->erro_campo = "ed172_historicompsfora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed172_areaconhecimento == null ){ 
       $this->erro_sql = " Campo Área de Conhecimento não informado.";
       $this->erro_campo = "ed172_areaconhecimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed172_codigo == "" || $ed172_codigo == null ){
       $result = db_query("select nextval('historicompsforaarea_ed172_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: historicompsforaarea_ed172_codigo_seq do campo: ed172_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed172_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from historicompsforaarea_ed172_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed172_codigo)){
         $this->erro_sql = " Campo ed172_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed172_codigo = $ed172_codigo; 
       }
     }
     if(($this->ed172_codigo == null) || ($this->ed172_codigo == "") ){ 
       $this->erro_sql = " Campo ed172_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into historicompsforaarea(
                                       ed172_codigo 
                                      ,ed172_historicompsfora 
                                      ,ed172_areaconhecimento 
                                      ,ed172_resultadoobtido 
                                      ,ed172_resultadofinal 
                       )
                values (
                                $this->ed172_codigo 
                               ,$this->ed172_historicompsfora 
                               ,$this->ed172_areaconhecimento 
                               ,'$this->ed172_resultadoobtido' 
                               ,'$this->ed172_resultadofinal' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Histórico MPS Fora Area ($this->ed172_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Histórico MPS Fora Area já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Histórico MPS Fora Area ($this->ed172_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed172_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed172_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1012050,'$this->ed172_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010679,1012050,'','".AddSlashes(pg_result($resaco,0,'ed172_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010679,1012051,'','".AddSlashes(pg_result($resaco,0,'ed172_historicompsfora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010679,1012052,'','".AddSlashes(pg_result($resaco,0,'ed172_areaconhecimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010679,1012053,'','".AddSlashes(pg_result($resaco,0,'ed172_resultadoobtido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010679,1012054,'','".AddSlashes(pg_result($resaco,0,'ed172_resultadofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($ed172_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update historicompsforaarea set ";
     $virgula = "";
     if(trim($this->ed172_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed172_codigo"])){ 
       $sql  .= $virgula." ed172_codigo = $this->ed172_codigo ";
       $virgula = ",";
       if(trim($this->ed172_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed172_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed172_historicompsfora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed172_historicompsfora"])){ 
       $sql  .= $virgula." ed172_historicompsfora = $this->ed172_historicompsfora ";
       $virgula = ",";
       if(trim($this->ed172_historicompsfora) == null ){ 
         $this->erro_sql = " Campo Histórico MPS Fora não informado.";
         $this->erro_campo = "ed172_historicompsfora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed172_areaconhecimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed172_areaconhecimento"])){ 
       $sql  .= $virgula." ed172_areaconhecimento = $this->ed172_areaconhecimento ";
       $virgula = ",";
       if(trim($this->ed172_areaconhecimento) == null ){ 
         $this->erro_sql = " Campo Área de Conhecimento não informado.";
         $this->erro_campo = "ed172_areaconhecimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed172_resultadoobtido)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed172_resultadoobtido"])){ 
       $sql  .= $virgula." ed172_resultadoobtido = '$this->ed172_resultadoobtido' ";
       $virgula = ",";
     }
     if(trim($this->ed172_resultadofinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed172_resultadofinal"])){ 
       $sql  .= $virgula." ed172_resultadofinal = '$this->ed172_resultadofinal' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed172_codigo!=null){
       $sql .= " ed172_codigo = $this->ed172_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed172_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1012050,'$this->ed172_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed172_codigo"]) || $this->ed172_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010679,1012050,'".AddSlashes(pg_result($resaco,$conresaco,'ed172_codigo'))."','$this->ed172_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed172_historicompsfora"]) || $this->ed172_historicompsfora != "")
             $resac = db_query("insert into db_acount values($acount,1010679,1012051,'".AddSlashes(pg_result($resaco,$conresaco,'ed172_historicompsfora'))."','$this->ed172_historicompsfora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed172_areaconhecimento"]) || $this->ed172_areaconhecimento != "")
             $resac = db_query("insert into db_acount values($acount,1010679,1012052,'".AddSlashes(pg_result($resaco,$conresaco,'ed172_areaconhecimento'))."','$this->ed172_areaconhecimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed172_resultadoobtido"]) || $this->ed172_resultadoobtido != "")
             $resac = db_query("insert into db_acount values($acount,1010679,1012053,'".AddSlashes(pg_result($resaco,$conresaco,'ed172_resultadoobtido'))."','$this->ed172_resultadoobtido',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed172_resultadofinal"]) || $this->ed172_resultadofinal != "")
             $resac = db_query("insert into db_acount values($acount,1010679,1012054,'".AddSlashes(pg_result($resaco,$conresaco,'ed172_resultadofinal'))."','$this->ed172_resultadofinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Histórico MPS Fora Area não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed172_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Histórico MPS Fora Area não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed172_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed172_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($ed172_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed172_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1012050,'$ed172_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010679,1012050,'','".AddSlashes(pg_result($resaco,$iresaco,'ed172_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010679,1012051,'','".AddSlashes(pg_result($resaco,$iresaco,'ed172_historicompsfora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010679,1012052,'','".AddSlashes(pg_result($resaco,$iresaco,'ed172_areaconhecimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010679,1012053,'','".AddSlashes(pg_result($resaco,$iresaco,'ed172_resultadoobtido'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010679,1012054,'','".AddSlashes(pg_result($resaco,$iresaco,'ed172_resultadofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from historicompsforaarea
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed172_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed172_codigo = $ed172_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Histórico MPS Fora Area não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed172_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Histórico MPS Fora Area não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed172_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ed172_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:historicompsforaarea";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($ed172_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from historicompsforaarea ";
     $sql .= "      inner join areaconhecimento  on  areaconhecimento.ed293_sequencial = historicompsforaarea.ed172_areaconhecimento";
     $sql .= "      inner join historicompsfora  on  historicompsfora.ed99_i_codigo = historicompsforaarea.ed172_historicompsfora";
     $sql .= "      left  join justificativa  on  justificativa.ed06_i_codigo = historicompsfora.ed99_i_justificativa";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = historicompsfora.ed99_i_serie";
     $sql .= "      inner join historico  as a on   a.ed61_i_codigo = historicompsfora.ed99_i_historico";
     $sql .= "      inner join escolaproc  on  escolaproc.ed82_i_codigo = historicompsfora.ed99_i_escolaproc";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed172_codigo)) {
         $sql2 .= " where historicompsforaarea.ed172_codigo = $ed172_codigo "; 
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

    public function sql_query_file($ed172_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from historicompsforaarea ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed172_codigo)){
         $sql2 .= " where historicompsforaarea.ed172_codigo = $ed172_codigo "; 
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
