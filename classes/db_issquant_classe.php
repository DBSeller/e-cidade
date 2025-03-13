<?php

class cl_issquant
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
    public $q30_anousu = 0;
    public $q30_inscr = 0;
    public $q30_quant = 0;
    public $q30_mult = 0;
    public $q30_area = 0;
    public $q30_tempofuncionamento = 0;
    public $q30_areapublicidade = 0;
    public $q30_graurisco = null;
   // cria propriedade com as variaveis do arquivo
    public $campos = "
                 q30_anousu = int4 = ano
                 q30_inscr = int4 = inscricao
                 q30_quant = float8 = Empregados
                 q30_mult = float8 = multiplicador
                 q30_area = float8 = Area
                 q30_tempofuncionamento = float8 = Tempo Funcionamento
                 q30_areapublicidade = float4 = Área de Publicidade
                 q30_graurisco = char(1) = Grau de Risco
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("issquant");
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
       $this->q30_anousu = ($this->q30_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["q30_anousu"]:$this->q30_anousu);
       $this->q30_inscr = ($this->q30_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["q30_inscr"]:$this->q30_inscr);
       $this->q30_quant = ($this->q30_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["q30_quant"]:$this->q30_quant);
       $this->q30_mult = ($this->q30_mult == ""?@$GLOBALS["HTTP_POST_VARS"]["q30_mult"]:$this->q30_mult);
       $this->q30_area = ($this->q30_area == ""?@$GLOBALS["HTTP_POST_VARS"]["q30_area"]:$this->q30_area);
       $this->q30_tempofuncionamento = ($this->q30_tempofuncionamento == ""?@$GLOBALS["HTTP_POST_VARS"]["q30_tempofuncionamento"]:$this->q30_tempofuncionamento);
       $this->q30_areapublicidade = ($this->q30_areapublicidade == ""?@$GLOBALS["HTTP_POST_VARS"]["q30_areapublicidade"]:$this->q30_areapublicidade);
       $this->q30_graurisco = (empty($this->q30_graurisco) ? @$GLOBALS["HTTP_POST_VARS"]["q30_graurisco"]:$this->q30_graurisco);
     }else{
       $this->q30_anousu = ($this->q30_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["q30_anousu"]:$this->q30_anousu);
       $this->q30_inscr = ($this->q30_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["q30_inscr"]:$this->q30_inscr);
     }
   }

    public function incluir($q30_anousu,$q30_inscr)
    {
      $this->atualizacampos();
     if($this->q30_quant == null ){
       $this->erro_sql = " Campo Empregados não informado.";
       $this->erro_campo = "q30_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q30_mult == null ){
       $this->erro_sql = " Campo multiplicador não informado.";
       $this->erro_campo = "q30_mult";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q30_area == null ){
       $this->erro_sql = " Campo Area não informado.";
       $this->erro_campo = "q30_area";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q30_tempofuncionamento == null ){
       $this->q30_tempofuncionamento = "0";
     }
     if($this->q30_areapublicidade == null ){
       $this->q30_areapublicidade = "0";
     }
       $this->q30_anousu = $q30_anousu;
       $this->q30_inscr = $q30_inscr;
     if(($this->q30_anousu == null) || ($this->q30_anousu == "") ){
       $this->erro_sql = " Campo q30_anousu não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->q30_inscr == null) || ($this->q30_inscr == "") ){
       $this->erro_sql = " Campo q30_inscr não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }

    if(empty($this->q30_graurisco)){
        $this->q30_graurisco = "";
    }
     $sql = "insert into issquant(
                                       q30_anousu
                                      ,q30_inscr
                                      ,q30_quant
                                      ,q30_mult
                                      ,q30_area
                                      ,q30_tempofuncionamento
                                      ,q30_areapublicidade
                                      ,q30_graurisco
                       )
                values (
                                $this->q30_anousu
                               ,$this->q30_inscr
                               ,$this->q30_quant
                               ,$this->q30_mult
                               ,$this->q30_area
                               ,$this->q30_tempofuncionamento
                               ,$this->q30_areapublicidade
                               ,'$this->q30_graurisco'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->q30_anousu."-".$this->q30_inscr) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->q30_anousu."-".$this->q30_inscr) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->q30_anousu."-".$this->q30_inscr;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->q30_anousu,$this->q30_inscr  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,285,'$this->q30_anousu','I')");
         $resac = db_query("insert into db_acountkey values($acount,286,'$this->q30_inscr','I')");
         $resac = db_query("insert into db_acount values($acount,47,285,'','".AddSlashes(pg_result($resaco,0,'q30_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,47,286,'','".AddSlashes(pg_result($resaco,0,'q30_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,47,287,'','".AddSlashes(pg_result($resaco,0,'q30_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,47,288,'','".AddSlashes(pg_result($resaco,0,'q30_mult'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,47,7428,'','".AddSlashes(pg_result($resaco,0,'q30_area'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,47,1011839,'','".AddSlashes(pg_result($resaco,0,'q30_tempofuncionamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,47,1011869,'','".AddSlashes(pg_result($resaco,0,'q30_areapublicidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,47,1013278,'','".AddSlashes(pg_result($resaco,0,'q30_graurisco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }

    public function alterar($q30_anousu=null,$q30_inscr=null)
    {
      $this->atualizacampos();
     $sql = " update issquant set ";
     $virgula = "";
     if(trim($this->q30_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q30_anousu"])){
       $sql  .= $virgula." q30_anousu = $this->q30_anousu ";
       $virgula = ",";
       if(trim($this->q30_anousu) == null ){
         $this->erro_sql = " Campo ano não informado.";
         $this->erro_campo = "q30_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q30_inscr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q30_inscr"])){
       $sql  .= $virgula." q30_inscr = $this->q30_inscr ";
       $virgula = ",";
       if(trim($this->q30_inscr) == null ){
         $this->erro_sql = " Campo inscricao não informado.";
         $this->erro_campo = "q30_inscr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q30_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q30_quant"])){
       $sql  .= $virgula." q30_quant = $this->q30_quant ";
       $virgula = ",";
       if(trim($this->q30_quant) == null ){
         $this->erro_sql = " Campo Empregados não informado.";
         $this->erro_campo = "q30_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q30_mult)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q30_mult"])){
       $sql  .= $virgula." q30_mult = $this->q30_mult ";
       $virgula = ",";
       if(trim($this->q30_mult) == null ){
         $this->erro_sql = " Campo multiplicador não informado.";
         $this->erro_campo = "q30_mult";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q30_area)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q30_area"])){
       $sql  .= $virgula." q30_area = $this->q30_area ";
       $virgula = ",";
       if(trim($this->q30_area) == null ){
         $this->erro_sql = " Campo Area não informado.";
         $this->erro_campo = "q30_area";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q30_tempofuncionamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q30_tempofuncionamento"])){
        if(trim($this->q30_tempofuncionamento)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q30_tempofuncionamento"])){
           $this->q30_tempofuncionamento = "0" ;
        }
       $sql  .= $virgula." q30_tempofuncionamento = $this->q30_tempofuncionamento ";
       $virgula = ",";
     }
     if(trim($this->q30_areapublicidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q30_areapublicidade"])){
        if(trim($this->q30_areapublicidade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q30_areapublicidade"])){
           $this->q30_areapublicidade = "0" ;
        }
       $sql  .= $virgula." q30_areapublicidade = $this->q30_areapublicidade ";
       $virgula = ",";
     }

        if(trim($this->q30_graurisco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q30_graurisco"])){
            if(empty($this->q30_graurisco) && isset($GLOBALS["HTTP_POST_VARS"]["q30_graurisco"])){
                $this->q30_graurisco = "" ;
            }
            $sql  .= $virgula." q30_graurisco = '$this->q30_graurisco' ";
            $virgula = ",";
        }
     $sql .= " where ";
     if($q30_anousu!=null){
       $sql .= " q30_anousu = $this->q30_anousu";
     }
     if($q30_inscr!=null){
       $sql .= " and  q30_inscr = $this->q30_inscr";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->q30_anousu,$this->q30_inscr));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,285,'$this->q30_anousu','A')");
           $resac = db_query("insert into db_acountkey values($acount,286,'$this->q30_inscr','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q30_anousu"]) || $this->q30_anousu != "")
             $resac = db_query("insert into db_acount values($acount,47,285,'".AddSlashes(pg_result($resaco,$conresaco,'q30_anousu'))."','$this->q30_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q30_inscr"]) || $this->q30_inscr != "")
             $resac = db_query("insert into db_acount values($acount,47,286,'".AddSlashes(pg_result($resaco,$conresaco,'q30_inscr'))."','$this->q30_inscr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q30_quant"]) || $this->q30_quant != "")
             $resac = db_query("insert into db_acount values($acount,47,287,'".AddSlashes(pg_result($resaco,$conresaco,'q30_quant'))."','$this->q30_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q30_mult"]) || $this->q30_mult != "")
             $resac = db_query("insert into db_acount values($acount,47,288,'".AddSlashes(pg_result($resaco,$conresaco,'q30_mult'))."','$this->q30_mult',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q30_area"]) || $this->q30_area != "")
             $resac = db_query("insert into db_acount values($acount,47,7428,'".AddSlashes(pg_result($resaco,$conresaco,'q30_area'))."','$this->q30_area',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q30_tempofuncionamento"]) || $this->q30_tempofuncionamento != "")
             $resac = db_query("insert into db_acount values($acount,47,1011839,'".AddSlashes(pg_result($resaco,$conresaco,'q30_tempofuncionamento'))."','$this->q30_tempofuncionamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q30_areapublicidade"]) || $this->q30_areapublicidade != "")
             $resac = db_query("insert into db_acount values($acount,47,1011869,'".AddSlashes(pg_result($resaco,$conresaco,'q30_areapublicidade'))."','$this->q30_areapublicidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q30_anousu."-".$this->q30_inscr;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = " não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q30_anousu."-".$this->q30_inscr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->q30_anousu."-".$this->q30_inscr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }

    public function excluir($q30_anousu=null,$q30_inscr=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($q30_anousu,$q30_inscr));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,285,'$q30_anousu','E')");
           $resac  = db_query("insert into db_acountkey values($acount,286,'$q30_inscr','E')");
           $resac  = db_query("insert into db_acount values($acount,47,285,'','".AddSlashes(pg_result($resaco,$iresaco,'q30_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,47,286,'','".AddSlashes(pg_result($resaco,$iresaco,'q30_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,47,287,'','".AddSlashes(pg_result($resaco,$iresaco,'q30_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,47,288,'','".AddSlashes(pg_result($resaco,$iresaco,'q30_mult'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,47,7428,'','".AddSlashes(pg_result($resaco,$iresaco,'q30_area'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,47,1011839,'','".AddSlashes(pg_result($resaco,$iresaco,'q30_tempofuncionamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,47,1011869,'','".AddSlashes(pg_result($resaco,$iresaco,'q30_areapublicidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from issquant
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($q30_anousu)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " q30_anousu = $q30_anousu ";
        }
        if (!empty($q30_inscr)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " q30_inscr = $q30_inscr ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q30_anousu."-".$q30_inscr;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = " não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q30_anousu."-".$q30_inscr;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$q30_anousu."-".$q30_inscr;
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
        $this->erro_sql   = "Record Vazio na Tabela:issquant";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($q30_anousu = null,$q30_inscr = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from issquant ";
     $sql .= "      inner join issbase  on  issbase.q02_inscr = issquant.q30_inscr";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = issbase.q02_numcgm";
     $sql .= "      inner join formalocalvara  on  formalocalvara.q167_sequencial = issbase.q02_formalocalvara";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($q30_anousu)) {
         $sql2 .= " where issquant.q30_anousu = $q30_anousu ";
       }
       if (!empty($q30_inscr)) {
         if (!empty($sql2)) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         }
         $sql2 .= " issquant.q30_inscr = $q30_inscr ";
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

    public function sql_query_file($q30_anousu = null,$q30_inscr = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from issquant ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($q30_anousu)){
         $sql2 .= " where issquant.q30_anousu = $q30_anousu ";
       }
       if (!empty($q30_inscr)){
         if ( !empty($sql2) ) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         }
         $sql2 .= " issquant.q30_inscr = $q30_inscr ";
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
