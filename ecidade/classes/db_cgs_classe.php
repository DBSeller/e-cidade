<?php

class cl_cgs
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
    public $z01_i_numcgs = 0; 
    public $z01_i_tiposangue = 0; 
    public $z01_i_fatorrh = 0; 
    public $z01_c_cartaosus = null; 
    public $z01_v_familia = null; 
    public $z01_v_microarea = null; 
    public $z01_c_municipio = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 z01_i_numcgs = int4 = CGS 
                 z01_i_tiposangue = int4 = Tipo Sangue 
                 z01_i_fatorrh = int4 = Fator RH 
                 z01_c_cartaosus = char(15) = Cartão SUS
                 z01_v_familia = varchar(10) = Família Saúde 
                 z01_v_microarea = varchar(10) = Micro Área Saúde 
                 z01_c_municipio = char(1) = CGS do Município 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("cgs"); 
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
       $this->z01_i_numcgs = ($this->z01_i_numcgs == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_i_numcgs"]:$this->z01_i_numcgs);
       $this->z01_i_tiposangue = ($this->z01_i_tiposangue == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_i_tiposangue"]:$this->z01_i_tiposangue);
       $this->z01_i_fatorrh = ($this->z01_i_fatorrh == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_i_fatorrh"]:$this->z01_i_fatorrh);
       $this->z01_c_cartaosus = ($this->z01_c_cartaosus == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_cartaosus"]:$this->z01_c_cartaosus);
       $this->z01_v_familia = ($this->z01_v_familia == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_familia"]:$this->z01_v_familia);
       $this->z01_v_microarea = ($this->z01_v_microarea == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_v_microarea"]:$this->z01_v_microarea);
       $this->z01_c_municipio = ($this->z01_c_municipio == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_c_municipio"]:$this->z01_c_municipio);
     }else{
       $this->z01_i_numcgs = ($this->z01_i_numcgs == ""?@$GLOBALS["HTTP_POST_VARS"]["z01_i_numcgs"]:$this->z01_i_numcgs);
     }
   }

    public function incluir($z01_i_numcgs)
    {
      $this->atualizacampos();
     if($this->z01_i_tiposangue == null ){ 
       $this->z01_i_tiposangue = "0";
     }
     if($this->z01_i_fatorrh == null ){ 
       $this->z01_i_fatorrh = "0";
     }
     if($this->z01_c_cartaosus == null ){
       $this->z01_c_cartaosus = "'||null||'";
     }
     if($this->z01_c_municipio == null ){ 
       $this->z01_c_municipio = "S";
     }
     if($z01_i_numcgs == "" || $z01_i_numcgs == null ){
       $result = db_query("select nextval('cgs_z01_i_numcgs_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cgs_z01_i_numcgs_seq do campo: z01_i_numcgs"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->z01_i_numcgs = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cgs_z01_i_numcgs_seq");
       if(($result != false) && (pg_result($result,0,0) < $z01_i_numcgs)){
         $this->erro_sql = " Campo z01_i_numcgs maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->z01_i_numcgs = $z01_i_numcgs; 
       }
     }
     if(($this->z01_i_numcgs == null) || ($this->z01_i_numcgs == "") ){ 
       $this->erro_sql = " Campo z01_i_numcgs não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cgs(
                                       z01_i_numcgs 
                                      ,z01_i_tiposangue 
                                      ,z01_i_fatorrh 
                                      ,z01_c_cartaosus
                                      ,z01_v_familia 
                                      ,z01_v_microarea 
                                      ,z01_c_municipio 
                       )
                values (
                                $this->z01_i_numcgs 
                               ,$this->z01_i_tiposangue 
                               ,$this->z01_i_fatorrh 
                               ,'$this->z01_c_cartaosus'
                               ,'$this->z01_v_familia' 
                               ,'$this->z01_v_microarea' 
                               ,'$this->z01_c_municipio' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cgs ($this->z01_i_numcgs) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cgs já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cgs ($this->z01_i_numcgs) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->z01_i_numcgs;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->z01_i_numcgs  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008839,'$this->z01_i_numcgs','I')");
         $resac = db_query("insert into db_acount values($acount,1010142,1008839,'','".AddSlashes(pg_result($resaco,0,'z01_i_numcgs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010142,1008861,'','".AddSlashes(pg_result($resaco,0,'z01_i_tiposangue'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010142,1008862,'','".AddSlashes(pg_result($resaco,0,'z01_i_fatorrh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010142,1008863,'','".AddSlashes(pg_result($resaco,0,'z01_c_cartaosus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010142,11210,'','".AddSlashes(pg_result($resaco,0,'z01_v_familia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010142,11211,'','".AddSlashes(pg_result($resaco,0,'z01_v_microarea'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010142,12263,'','".AddSlashes(pg_result($resaco,0,'z01_c_municipio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($z01_i_numcgs=null)
    {
      $this->atualizacampos();
     $sql = " update cgs set ";
     $virgula = "";
     if(trim($this->z01_i_numcgs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_i_numcgs"])){ 
       $sql  .= $virgula." z01_i_numcgs = $this->z01_i_numcgs ";
       $virgula = ",";
       if(trim($this->z01_i_numcgs) == null ){ 
         $this->erro_sql = " Campo CGS não informado.";
         $this->erro_campo = "z01_i_numcgs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z01_i_tiposangue)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_i_tiposangue"])){ 
        if(trim($this->z01_i_tiposangue)=="" && isset($GLOBALS["HTTP_POST_VARS"]["z01_i_tiposangue"])){ 
           $this->z01_i_tiposangue = "0" ; 
        } 
       $sql  .= $virgula." z01_i_tiposangue = $this->z01_i_tiposangue ";
       $virgula = ",";
     }
     if(trim($this->z01_i_fatorrh)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_i_fatorrh"])){ 
        if(trim($this->z01_i_fatorrh)=="" && isset($GLOBALS["HTTP_POST_VARS"]["z01_i_fatorrh"])){ 
           $this->z01_i_fatorrh = "0" ; 
        } 
       $sql  .= $virgula." z01_i_fatorrh = $this->z01_i_fatorrh ";
       $virgula = ",";
     }
     if(trim($this->z01_c_cartaosus)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_cartaosus"])){
       $sql  .= $virgula." z01_c_cartaosus = '$this->z01_c_cartaosus' ";
       $virgula = ",";
     } else {
       $sql  .= $virgula." z01_c_cartaosus = NULL ";
     }
     if(trim($this->z01_v_familia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_familia"])){ 
       $sql  .= $virgula." z01_v_familia = '$this->z01_v_familia' ";
       $virgula = ",";
     }
     if(trim($this->z01_v_microarea)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_v_microarea"])){ 
       $sql  .= $virgula." z01_v_microarea = '$this->z01_v_microarea' ";
       $virgula = ",";
     }
     if(trim($this->z01_c_municipio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z01_c_municipio"])){ 
       $sql  .= $virgula." z01_c_municipio = '$this->z01_c_municipio' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($z01_i_numcgs!=null){
       $sql .= " z01_i_numcgs = $this->z01_i_numcgs";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->z01_i_numcgs));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008839,'$this->z01_i_numcgs','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_i_numcgs"]) || $this->z01_i_numcgs != "")
             $resac = db_query("insert into db_acount values($acount,1010142,1008839,'".AddSlashes(pg_result($resaco,$conresaco,'z01_i_numcgs'))."','$this->z01_i_numcgs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_i_tiposangue"]) || $this->z01_i_tiposangue != "")
             $resac = db_query("insert into db_acount values($acount,1010142,1008861,'".AddSlashes(pg_result($resaco,$conresaco,'z01_i_tiposangue'))."','$this->z01_i_tiposangue',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_i_fatorrh"]) || $this->z01_i_fatorrh != "")
             $resac = db_query("insert into db_acount values($acount,1010142,1008862,'".AddSlashes(pg_result($resaco,$conresaco,'z01_i_fatorrh'))."','$this->z01_i_fatorrh',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["z01_c_cartaosus"]))
            $resac = db_query("insert into db_acount values($acount,1010142,1008863,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_cartaosus'))."','$this->z01_c_cartaosus',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_familia"]) || $this->z01_v_familia != "")
             $resac = db_query("insert into db_acount values($acount,1010142,11210,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_familia'))."','$this->z01_v_familia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_v_microarea"]) || $this->z01_v_microarea != "")
             $resac = db_query("insert into db_acount values($acount,1010142,11211,'".AddSlashes(pg_result($resaco,$conresaco,'z01_v_microarea'))."','$this->z01_v_microarea',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z01_c_municipio"]) || $this->z01_c_municipio != "")
             $resac = db_query("insert into db_acount values($acount,1010142,12263,'".AddSlashes(pg_result($resaco,$conresaco,'z01_c_municipio'))."','$this->z01_c_municipio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cgs não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->z01_i_numcgs;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "cgs não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->z01_i_numcgs;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->z01_i_numcgs;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($z01_i_numcgs=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($z01_i_numcgs));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1008839,'$z01_i_numcgs','E')");
           $resac  = db_query("insert into db_acount values($acount,1010142,1008839,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_i_numcgs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010142,1008861,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_i_tiposangue'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010142,1008862,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_i_fatorrh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010142,11210,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_familia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010142,11211,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_v_microarea'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010142,12263,'','".AddSlashes(pg_result($resaco,$iresaco,'z01_c_municipio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cgs
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($z01_i_numcgs)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " z01_i_numcgs = $z01_i_numcgs ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cgs não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$z01_i_numcgs;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "cgs não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$z01_i_numcgs;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$z01_i_numcgs;
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
        $this->erro_sql   = "Record Vazio na Tabela:cgs";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($z01_i_numcgs = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from cgs ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($z01_i_numcgs)) {
         $sql2 .= " where cgs.z01_i_numcgs = $z01_i_numcgs "; 
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

    public function sql_query_file($z01_i_numcgs = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from cgs ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($z01_i_numcgs)){
         $sql2 .= " where cgs.z01_i_numcgs = $z01_i_numcgs "; 
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
