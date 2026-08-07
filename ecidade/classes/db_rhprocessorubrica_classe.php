<?php

class cl_rhprocessorubrica
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
    public $rh287_sequencial = 0; 
    public $rh287_sequencialprocessoservidor = 0; 
    public $rh287_rubrica = null; 
    public $rh287_competencia = null; 
    public $rh287_quantidade = 0; 
    public $rh287_valor = 0; 
    public $rh287_evento = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh287_sequencial = int4 = Número Sequencial 
                 rh287_sequencialprocessoservidor = int4 = Identificação única de servidor 
                 rh287_rubrica = varchar(4) = Rubrica 
                 rh287_competencia = varchar(7) = Competência 
                 rh287_quantidade = float4 = Quantidade 
                 rh287_valor = float4 = Valor Rubrica 
                 rh287_evento = varchar(7) = Evento 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhprocessorubrica"); 
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
       $this->rh287_sequencial = ($this->rh287_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh287_sequencial"]:$this->rh287_sequencial);
       $this->rh287_sequencialprocessoservidor = ($this->rh287_sequencialprocessoservidor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh287_sequencialprocessoservidor"]:$this->rh287_sequencialprocessoservidor);
       $this->rh287_rubrica = ($this->rh287_rubrica == ""?@$GLOBALS["HTTP_POST_VARS"]["rh287_rubrica"]:$this->rh287_rubrica);
       $this->rh287_competencia = ($this->rh287_competencia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh287_competencia"]:$this->rh287_competencia);
       $this->rh287_quantidade = ($this->rh287_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["rh287_quantidade"]:$this->rh287_quantidade);
       $this->rh287_valor = ($this->rh287_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh287_valor"]:$this->rh287_valor);
       $this->rh287_evento = ($this->rh287_evento == ""?@$GLOBALS["HTTP_POST_VARS"]["rh287_evento"]:$this->rh287_evento);
     }else{
       $this->rh287_sequencial = ($this->rh287_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh287_sequencial"]:$this->rh287_sequencial);
     }
   }

    public function incluir($rh287_sequencial)
    {
      $this->atualizacampos();
     if($this->rh287_sequencialprocessoservidor == null ){ 
       $this->rh287_sequencialprocessoservidor = "0";
     }
     if($this->rh287_quantidade == null ){ 
       $this->rh287_quantidade = "0";
     }
     if($this->rh287_valor == null ){ 
       $this->rh287_valor = "0";
     }
     if($rh287_sequencial == "" || $rh287_sequencial == null ){
       $result = db_query("select nextval('rhprocessorubrica_rh287_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhprocessorubrica_rh287_sequencial_seq do campo: rh287_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh287_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhprocessorubrica_rh287_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh287_sequencial)){
         $this->erro_sql = " Campo rh287_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh287_sequencial = $rh287_sequencial; 
       }
     }
     if(($this->rh287_sequencial == null) || ($this->rh287_sequencial == "") ){ 
       $this->erro_sql = " Campo rh287_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhprocessorubrica(
                                       rh287_sequencial 
                                      ,rh287_sequencialprocessoservidor 
                                      ,rh287_rubrica 
                                      ,rh287_competencia 
                                      ,rh287_quantidade 
                                      ,rh287_valor 
                                      ,rh287_evento 
                       )
                values (
                                $this->rh287_sequencial 
                               ,$this->rh287_sequencialprocessoservidor 
                               ,'$this->rh287_rubrica' 
                               ,'$this->rh287_competencia' 
                               ,$this->rh287_quantidade 
                               ,$this->rh287_valor 
                               ,'$this->rh287_evento' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Rubrica vinculada processo ($this->rh287_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Rubrica vinculada processo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Rubrica vinculada processo ($this->rh287_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh287_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh287_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1015175,'$this->rh287_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1011100,1015175,'','".AddSlashes(pg_result($resaco,0,'rh287_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011100,1015176,'','".AddSlashes(pg_result($resaco,0,'rh287_sequencialprocessoservidor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011100,1015177,'','".AddSlashes(pg_result($resaco,0,'rh287_rubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011100,1015178,'','".AddSlashes(pg_result($resaco,0,'rh287_competencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011100,1015179,'','".AddSlashes(pg_result($resaco,0,'rh287_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011100,1015180,'','".AddSlashes(pg_result($resaco,0,'rh287_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011100,1015181,'','".AddSlashes(pg_result($resaco,0,'rh287_evento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh287_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhprocessorubrica set ";
     $virgula = "";
     if(trim($this->rh287_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh287_sequencial"])){ 
       $sql  .= $virgula." rh287_sequencial = $this->rh287_sequencial ";
       $virgula = ",";
       if(trim($this->rh287_sequencial) == null ){ 
         $this->erro_sql = " Campo Número Sequencial não informado.";
         $this->erro_campo = "rh287_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh287_sequencialprocessoservidor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh287_sequencialprocessoservidor"])){ 
        if(trim($this->rh287_sequencialprocessoservidor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh287_sequencialprocessoservidor"])){ 
           $this->rh287_sequencialprocessoservidor = "0" ; 
        } 
       $sql  .= $virgula." rh287_sequencialprocessoservidor = $this->rh287_sequencialprocessoservidor ";
       $virgula = ",";
     }
     if(trim($this->rh287_rubrica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh287_rubrica"])){ 
       $sql  .= $virgula." rh287_rubrica = '$this->rh287_rubrica' ";
       $virgula = ",";
     }
     if(trim($this->rh287_competencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh287_competencia"])){ 
       $sql  .= $virgula." rh287_competencia = '$this->rh287_competencia' ";
       $virgula = ",";
     }
     if(trim($this->rh287_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh287_quantidade"])){ 
        if(trim($this->rh287_quantidade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh287_quantidade"])){ 
           $this->rh287_quantidade = "0" ; 
        } 
       $sql  .= $virgula." rh287_quantidade = $this->rh287_quantidade ";
       $virgula = ",";
     }
     if(trim($this->rh287_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh287_valor"])){ 
        if(trim($this->rh287_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh287_valor"])){ 
           $this->rh287_valor = "0" ; 
        } 
       $sql  .= $virgula." rh287_valor = $this->rh287_valor ";
       $virgula = ",";
     }
     if(trim($this->rh287_evento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh287_evento"])){ 
       $sql  .= $virgula." rh287_evento = '$this->rh287_evento' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh287_sequencial!=null){
       $sql .= " rh287_sequencial = $this->rh287_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh287_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1015175,'$this->rh287_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh287_sequencial"]) || $this->rh287_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1011100,1015175,'".AddSlashes(pg_result($resaco,$conresaco,'rh287_sequencial'))."','$this->rh287_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh287_sequencialprocessoservidor"]) || $this->rh287_sequencialprocessoservidor != "")
             $resac = db_query("insert into db_acount values($acount,1011100,1015176,'".AddSlashes(pg_result($resaco,$conresaco,'rh287_sequencialprocessoservidor'))."','$this->rh287_sequencialprocessoservidor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh287_rubrica"]) || $this->rh287_rubrica != "")
             $resac = db_query("insert into db_acount values($acount,1011100,1015177,'".AddSlashes(pg_result($resaco,$conresaco,'rh287_rubrica'))."','$this->rh287_rubrica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh287_competencia"]) || $this->rh287_competencia != "")
             $resac = db_query("insert into db_acount values($acount,1011100,1015178,'".AddSlashes(pg_result($resaco,$conresaco,'rh287_competencia'))."','$this->rh287_competencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh287_quantidade"]) || $this->rh287_quantidade != "")
             $resac = db_query("insert into db_acount values($acount,1011100,1015179,'".AddSlashes(pg_result($resaco,$conresaco,'rh287_quantidade'))."','$this->rh287_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh287_valor"]) || $this->rh287_valor != "")
             $resac = db_query("insert into db_acount values($acount,1011100,1015180,'".AddSlashes(pg_result($resaco,$conresaco,'rh287_valor'))."','$this->rh287_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh287_evento"]) || $this->rh287_evento != "")
             $resac = db_query("insert into db_acount values($acount,1011100,1015181,'".AddSlashes(pg_result($resaco,$conresaco,'rh287_evento'))."','$this->rh287_evento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Rubrica vinculada processo não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh287_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Rubrica vinculada processo não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh287_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh287_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh287_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh287_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1015175,'$rh287_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1011100,1015175,'','".AddSlashes(pg_result($resaco,$iresaco,'rh287_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011100,1015176,'','".AddSlashes(pg_result($resaco,$iresaco,'rh287_sequencialprocessoservidor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011100,1015177,'','".AddSlashes(pg_result($resaco,$iresaco,'rh287_rubrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011100,1015178,'','".AddSlashes(pg_result($resaco,$iresaco,'rh287_competencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011100,1015179,'','".AddSlashes(pg_result($resaco,$iresaco,'rh287_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011100,1015180,'','".AddSlashes(pg_result($resaco,$iresaco,'rh287_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011100,1015181,'','".AddSlashes(pg_result($resaco,$iresaco,'rh287_evento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhprocessorubrica
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh287_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh287_sequencial = $rh287_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Rubrica vinculada processo não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh287_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Rubrica vinculada processo não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh287_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh287_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhprocessorubrica";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh287_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhprocessorubrica ";
     $sql .= "      left  join rhpessoalprocessoservidor  on  rhpessoalprocessoservidor.rh271_sequencial = rhprocessorubrica.rh287_sequencialprocessoservidor";
     $sql .= "      inner join rhpessoalprocessojudicialesocial  on  rhpessoalprocessojudicialesocial.rh270_sequencial = rhpessoalprocessoservidor.rh271_sequencialprocesso";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh287_sequencial)) {
         $sql2 .= " where rhprocessorubrica.rh287_sequencial = $rh287_sequencial "; 
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

    public function sql_query_file($rh287_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhprocessorubrica ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh287_sequencial)){
         $sql2 .= " where rhprocessorubrica.rh287_sequencial = $rh287_sequencial "; 
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
