<?php

class cl_termoinscr
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
    public $v92_termo = 0; 
    public $v92_dtinsc_dia = null; 
    public $v92_dtinsc_mes = null; 
    public $v92_dtinsc_ano = null; 
    public $v92_dtinsc = null; 
    public $v92_usuario = 0; 
    public $v92_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 v92_termo = int8 = Código 
                 v92_dtinsc = date = Data de inscrição 
                 v92_usuario = int4 = Usuário 
                 v92_instit = int4 = Instituição 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("termoinscr"); 
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
       $this->v92_termo = ($this->v92_termo == ""?@$GLOBALS["HTTP_POST_VARS"]["v92_termo"]:$this->v92_termo);
       if($this->v92_dtinsc == ""){
         $this->v92_dtinsc_dia = ($this->v92_dtinsc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v92_dtinsc_dia"]:$this->v92_dtinsc_dia);
         $this->v92_dtinsc_mes = ($this->v92_dtinsc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v92_dtinsc_mes"]:$this->v92_dtinsc_mes);
         $this->v92_dtinsc_ano = ($this->v92_dtinsc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v92_dtinsc_ano"]:$this->v92_dtinsc_ano);
         if($this->v92_dtinsc_dia != ""){
            $this->v92_dtinsc = $this->v92_dtinsc_ano."-".$this->v92_dtinsc_mes."-".$this->v92_dtinsc_dia;
         }
       }
       $this->v92_usuario = ($this->v92_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["v92_usuario"]:$this->v92_usuario);
       $this->v92_instit = ($this->v92_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["v92_instit"]:$this->v92_instit);
     }else{
       $this->v92_termo = ($this->v92_termo == ""?@$GLOBALS["HTTP_POST_VARS"]["v92_termo"]:$this->v92_termo);
     }
   }

    public function incluir($v92_termo)
    {
      $this->atualizacampos();
     if($this->v92_dtinsc == null ){ 
       $this->erro_sql = " Campo Data de inscrição não informado.";
       $this->erro_campo = "v92_dtinsc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v92_usuario == null ){ 
       $this->erro_sql = " Campo Usuário não informado.";
       $this->erro_campo = "v92_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v92_instit == null ){ 
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "v92_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v92_termo == "" || $v92_termo == null ){
       $result = db_query("select nextval('termoinscr_v92_termo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: termoinscr_v92_termo_seq do campo: v92_termo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->v92_termo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from termoinscr_v92_termo_seq");
       if(($result != false) && (pg_result($result,0,0) < $v92_termo)){
         $this->erro_sql = " Campo v92_termo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v92_termo = $v92_termo; 
       }
     }
     if(($this->v92_termo == null) || ($this->v92_termo == "") ){ 
       $this->erro_sql = " Campo v92_termo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into termoinscr(
                                       v92_termo 
                                      ,v92_dtinsc 
                                      ,v92_usuario 
                                      ,v92_instit 
                       )
                values (
                                $this->v92_termo 
                               ,".($this->v92_dtinsc == "null" || $this->v92_dtinsc == ""?"null":"'".$this->v92_dtinsc."'")." 
                               ,$this->v92_usuario 
                               ,$this->v92_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "termoinscr ($this->v92_termo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "termoinscr já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "termoinscr ($this->v92_termo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->v92_termo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->v92_termo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1013798,'$this->v92_termo','I')");
         $resac = db_query("insert into db_acount values($acount,1010872,1013798,'','".AddSlashes(pg_result($resaco,0,'v92_termo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010872,1013799,'','".AddSlashes(pg_result($resaco,0,'v92_dtinsc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010872,1013800,'','".AddSlashes(pg_result($resaco,0,'v92_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010872,1013802,'','".AddSlashes(pg_result($resaco,0,'v92_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($v92_termo=null)
    {
      $this->atualizacampos();
     $sql = " update termoinscr set ";
     $virgula = "";
     if(trim($this->v92_termo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v92_termo"])){ 
       $sql  .= $virgula." v92_termo = $this->v92_termo ";
       $virgula = ",";
       if(trim($this->v92_termo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "v92_termo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v92_dtinsc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v92_dtinsc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v92_dtinsc_dia"] !="") ){ 
       $sql  .= $virgula." v92_dtinsc = '$this->v92_dtinsc' ";
       $virgula = ",";
       if(trim($this->v92_dtinsc) == null ){ 
         $this->erro_sql = " Campo Data de inscrição não informado.";
         $this->erro_campo = "v92_dtinsc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["v92_dtinsc_dia"])){ 
         $sql  .= $virgula." v92_dtinsc = null ";
         $virgula = ",";
         if(trim($this->v92_dtinsc) == null ){ 
           $this->erro_sql = " Campo Data de inscrição não informado.";
           $this->erro_campo = "v92_dtinsc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v92_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v92_usuario"])){ 
       $sql  .= $virgula." v92_usuario = $this->v92_usuario ";
       $virgula = ",";
       if(trim($this->v92_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário não informado.";
         $this->erro_campo = "v92_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v92_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v92_instit"])){ 
       $sql  .= $virgula." v92_instit = $this->v92_instit ";
       $virgula = ",";
       if(trim($this->v92_instit) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "v92_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v92_termo!=null){
       $sql .= " v92_termo = $this->v92_termo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->v92_termo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1013798,'$this->v92_termo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v92_termo"]) || $this->v92_termo != "")
             $resac = db_query("insert into db_acount values($acount,1010872,1013798,'".AddSlashes(pg_result($resaco,$conresaco,'v92_termo'))."','$this->v92_termo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v92_dtinsc"]) || $this->v92_dtinsc != "")
             $resac = db_query("insert into db_acount values($acount,1010872,1013799,'".AddSlashes(pg_result($resaco,$conresaco,'v92_dtinsc'))."','$this->v92_dtinsc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v92_usuario"]) || $this->v92_usuario != "")
             $resac = db_query("insert into db_acount values($acount,1010872,1013800,'".AddSlashes(pg_result($resaco,$conresaco,'v92_usuario'))."','$this->v92_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["v92_instit"]) || $this->v92_instit != "")
             $resac = db_query("insert into db_acount values($acount,1010872,1013802,'".AddSlashes(pg_result($resaco,$conresaco,'v92_instit'))."','$this->v92_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "termoinscr não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v92_termo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "termoinscr não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v92_termo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->v92_termo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($v92_termo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($v92_termo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1013798,'$v92_termo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010872,1013798,'','".AddSlashes(pg_result($resaco,$iresaco,'v92_termo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010872,1013799,'','".AddSlashes(pg_result($resaco,$iresaco,'v92_dtinsc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010872,1013800,'','".AddSlashes(pg_result($resaco,$iresaco,'v92_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010872,1013802,'','".AddSlashes(pg_result($resaco,$iresaco,'v92_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from termoinscr
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($v92_termo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " v92_termo = $v92_termo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "termoinscr não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v92_termo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "termoinscr não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v92_termo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$v92_termo;
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
        $this->erro_sql   = "Record Vazio na Tabela:termoinscr";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($v92_termo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from termoinscr ";
     $sql .= "      inner join db_config  on  db_config.codigo = termoinscr.v92_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = termoinscr.v92_usuario";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      left  join db_depart  on  db_depart.coddepto = db_config.db21_departamento";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($v92_termo)) {
         $sql2 .= " where termoinscr.v92_termo = $v92_termo "; 
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

    public function sql_query_file($v92_termo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from termoinscr ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($v92_termo)){
         $sql2 .= " where termoinscr.v92_termo = $v92_termo "; 
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
