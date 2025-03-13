<?php

class cl_cnae
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
    public $q71_sequencial = 0; 
    public $q71_estrutural = null; 
    public $q71_descr = null; 
    public $q71_permitemei = 'f'; 
    public $q71_classificacaorisco = null; 
    public $q71_aliquota = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 q71_sequencial = int4 = Código sequencial 
                 q71_estrutural = varchar(8) = Código Cnae 
                 q71_descr = varchar(200) = Descrição 
                 q71_permitemei = bool = Permite MEI 
                 q71_classificacaorisco = char(1) = Classificação de Risco 
                 q71_aliquota = float4 = Alíquota 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("cnae"); 
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
       $this->q71_sequencial = ($this->q71_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q71_sequencial"]:$this->q71_sequencial);
       $this->q71_estrutural = ($this->q71_estrutural == ""?@$GLOBALS["HTTP_POST_VARS"]["q71_estrutural"]:$this->q71_estrutural);
       $this->q71_descr = ($this->q71_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["q71_descr"]:$this->q71_descr);
       $this->q71_permitemei = ($this->q71_permitemei == "f"?@$GLOBALS["HTTP_POST_VARS"]["q71_permitemei"]:$this->q71_permitemei);
       $this->q71_classificacaorisco = ($this->q71_classificacaorisco == ""?@$GLOBALS["HTTP_POST_VARS"]["q71_classificacaorisco"]:$this->q71_classificacaorisco);
       $this->q71_aliquota = ($this->q71_aliquota == ""?@$GLOBALS["HTTP_POST_VARS"]["q71_aliquota"]:$this->q71_aliquota);
     }else{
       $this->q71_sequencial = ($this->q71_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q71_sequencial"]:$this->q71_sequencial);
     }
   }

    public function incluir($q71_sequencial)
    {
      $this->atualizacampos();
     if($this->q71_estrutural == null ){ 
       $this->erro_sql = " Campo Código Cnae não informado.";
       $this->erro_campo = "q71_estrutural";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q71_descr == null ){ 
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "q71_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q71_permitemei == null ){ 
       $this->q71_permitemei = "f";
     }
     if($this->q71_classificacaorisco == null ){ 
       $this->erro_sql = " Campo Classificação de Risco não informado.";
       $this->erro_campo = "q71_classificacaorisco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q71_aliquota == null ){ 
       $this->erro_sql = " Campo Alíquota não informado.";
       $this->erro_campo = "q71_aliquota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q71_sequencial == "" || $q71_sequencial == null ){
       $result = db_query("select nextval('cnae_q71_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cnae_q71_sequencial_seq do campo: q71_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q71_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cnae_q71_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q71_sequencial)){
         $this->erro_sql = " Campo q71_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q71_sequencial = $q71_sequencial; 
       }
     }
     if(($this->q71_sequencial == null) || ($this->q71_sequencial == "") ){ 
       $this->erro_sql = " Campo q71_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cnae(
                                       q71_sequencial 
                                      ,q71_estrutural 
                                      ,q71_descr 
                                      ,q71_permitemei 
                                      ,q71_classificacaorisco 
                                      ,q71_aliquota 
                       )
                values (
                                $this->q71_sequencial 
                               ,'$this->q71_estrutural' 
                               ,'$this->q71_descr' 
                               ,'$this->q71_permitemei' 
                               ,'$this->q71_classificacaorisco' 
                               ,$this->q71_aliquota 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cnae ($this->q71_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cnae já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cnae ($this->q71_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->q71_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->q71_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10186,'$this->q71_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1752,10186,'','".AddSlashes(pg_result($resaco,0,'q71_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1752,10187,'','".AddSlashes(pg_result($resaco,0,'q71_estrutural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1752,10188,'','".AddSlashes(pg_result($resaco,0,'q71_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1752,1010753,'','".AddSlashes(pg_result($resaco,0,'q71_permitemei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1752,1010754,'','".AddSlashes(pg_result($resaco,0,'q71_classificacaorisco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1752,1011898,'','".AddSlashes(pg_result($resaco,0,'q71_aliquota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($q71_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update cnae set ";
     $virgula = "";
     if(trim($this->q71_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q71_sequencial"])){ 
       $sql  .= $virgula." q71_sequencial = $this->q71_sequencial ";
       $virgula = ",";
       if(trim($this->q71_sequencial) == null ){ 
         $this->erro_sql = " Campo Código sequencial não informado.";
         $this->erro_campo = "q71_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q71_estrutural)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q71_estrutural"])){ 
       $sql  .= $virgula." q71_estrutural = '$this->q71_estrutural' ";
       $virgula = ",";
       if(trim($this->q71_estrutural) == null ){ 
         $this->erro_sql = " Campo Código Cnae não informado.";
         $this->erro_campo = "q71_estrutural";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q71_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q71_descr"])){ 
       $sql  .= $virgula." q71_descr = '$this->q71_descr' ";
       $virgula = ",";
       if(trim($this->q71_descr) == null ){ 
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "q71_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q71_permitemei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q71_permitemei"])){ 
       $sql  .= $virgula." q71_permitemei = '$this->q71_permitemei' ";
       $virgula = ",";
     }
     if(trim($this->q71_classificacaorisco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q71_classificacaorisco"])){ 
       $sql  .= $virgula." q71_classificacaorisco = '$this->q71_classificacaorisco' ";
       $virgula = ",";
       if(trim($this->q71_classificacaorisco) == null ){ 
         $this->erro_sql = " Campo Classificação de Risco não informado.";
         $this->erro_campo = "q71_classificacaorisco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q71_aliquota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q71_aliquota"])){ 
       $sql  .= $virgula." q71_aliquota = $this->q71_aliquota ";
       $virgula = ",";
       if(trim($this->q71_aliquota) == null ){ 
         $this->erro_sql = " Campo Alíquota não informado.";
         $this->erro_campo = "q71_aliquota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q71_sequencial!=null){
       $sql .= " q71_sequencial = $this->q71_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->q71_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,10186,'$this->q71_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q71_sequencial"]) || $this->q71_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1752,10186,'".AddSlashes(pg_result($resaco,$conresaco,'q71_sequencial'))."','$this->q71_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q71_estrutural"]) || $this->q71_estrutural != "")
             $resac = db_query("insert into db_acount values($acount,1752,10187,'".AddSlashes(pg_result($resaco,$conresaco,'q71_estrutural'))."','$this->q71_estrutural',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q71_descr"]) || $this->q71_descr != "")
             $resac = db_query("insert into db_acount values($acount,1752,10188,'".AddSlashes(pg_result($resaco,$conresaco,'q71_descr'))."','$this->q71_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q71_permitemei"]) || $this->q71_permitemei != "")
             $resac = db_query("insert into db_acount values($acount,1752,1010753,'".AddSlashes(pg_result($resaco,$conresaco,'q71_permitemei'))."','$this->q71_permitemei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q71_classificacaorisco"]) || $this->q71_classificacaorisco != "")
             $resac = db_query("insert into db_acount values($acount,1752,1010754,'".AddSlashes(pg_result($resaco,$conresaco,'q71_classificacaorisco'))."','$this->q71_classificacaorisco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["q71_aliquota"]) || $this->q71_aliquota != "")
             $resac = db_query("insert into db_acount values($acount,1752,1011898,'".AddSlashes(pg_result($resaco,$conresaco,'q71_aliquota'))."','$this->q71_aliquota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cnae não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q71_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "cnae não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q71_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->q71_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($q71_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($q71_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,10186,'$q71_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1752,10186,'','".AddSlashes(pg_result($resaco,$iresaco,'q71_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1752,10187,'','".AddSlashes(pg_result($resaco,$iresaco,'q71_estrutural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1752,10188,'','".AddSlashes(pg_result($resaco,$iresaco,'q71_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1752,1010753,'','".AddSlashes(pg_result($resaco,$iresaco,'q71_permitemei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1752,1010754,'','".AddSlashes(pg_result($resaco,$iresaco,'q71_classificacaorisco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1752,1011898,'','".AddSlashes(pg_result($resaco,$iresaco,'q71_aliquota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cnae
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($q71_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " q71_sequencial = $q71_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cnae não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q71_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "cnae não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q71_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$q71_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cnae";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($q71_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from cnae ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($q71_sequencial)) {
         $sql2 .= " where cnae.q71_sequencial = $q71_sequencial "; 
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

    public function sql_query_file($q71_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from cnae ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($q71_sequencial)){
         $sql2 .= " where cnae.q71_sequencial = $q71_sequencial "; 
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
