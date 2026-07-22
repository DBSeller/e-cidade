<?php

class cl_rhpessoalprocessoduracao
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
    public $rh276_sequencial = 0; 
    public $rh276_sequencialprocessovinculo = 0; 
    public $rh276_tpcontr = 0; 
    public $rh276_dtterm_dia = null; 
    public $rh276_dtterm_mes = null; 
    public $rh276_dtterm_ano = null; 
    public $rh276_dtterm = null; 
    public $rh276_clauassec = null; 
    public $rh276_objdet = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh276_sequencial = int4 = Número Sequencial 
                 rh276_sequencialprocessovinculo = int4 = Processo vinculo 
                 rh276_tpcontr = int4 = Tipo de contrato 
                 rh276_dtterm = date = Data do término 
                 rh276_clauassec = varchar(1) = Cláusula assecuratória 
                 rh276_objdet = varchar(255) = Objeto determinante 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhpessoalprocessoduracao"); 
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
       $this->rh276_sequencial = ($this->rh276_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh276_sequencial"]:$this->rh276_sequencial);
       $this->rh276_sequencialprocessovinculo = ($this->rh276_sequencialprocessovinculo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh276_sequencialprocessovinculo"]:$this->rh276_sequencialprocessovinculo);
       $this->rh276_tpcontr = ($this->rh276_tpcontr == ""?@$GLOBALS["HTTP_POST_VARS"]["rh276_tpcontr"]:$this->rh276_tpcontr);
       if($this->rh276_dtterm == ""){
         $this->rh276_dtterm_dia = ($this->rh276_dtterm_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh276_dtterm_dia"]:$this->rh276_dtterm_dia);
         $this->rh276_dtterm_mes = ($this->rh276_dtterm_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh276_dtterm_mes"]:$this->rh276_dtterm_mes);
         $this->rh276_dtterm_ano = ($this->rh276_dtterm_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh276_dtterm_ano"]:$this->rh276_dtterm_ano);
         if($this->rh276_dtterm_dia != ""){
            $this->rh276_dtterm = $this->rh276_dtterm_ano."-".$this->rh276_dtterm_mes."-".$this->rh276_dtterm_dia;
         }
       }
       $this->rh276_clauassec = ($this->rh276_clauassec == ""?@$GLOBALS["HTTP_POST_VARS"]["rh276_clauassec"]:$this->rh276_clauassec);
       $this->rh276_objdet = ($this->rh276_objdet == ""?@$GLOBALS["HTTP_POST_VARS"]["rh276_objdet"]:$this->rh276_objdet);
     }else{
       $this->rh276_sequencial = ($this->rh276_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh276_sequencial"]:$this->rh276_sequencial);
     }
   }

    public function incluir($rh276_sequencial)
    {
      $this->atualizacampos();
     if($this->rh276_sequencialprocessovinculo == null ){ 
       $this->erro_sql = " Campo Processo vinculo não informado.";
       $this->erro_campo = "rh276_sequencialprocessovinculo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh276_tpcontr == null ){ 
       $this->rh276_tpcontr = "0";
     }
     if($this->rh276_dtterm == null ){ 
       $this->rh276_dtterm = "null";
     }

     if($rh276_sequencial == "" || $rh276_sequencial == null ){
      $result = db_query("select nextval('rhpessoalprocessoduracao_rh276_sequencial_seq')"); 
      if($result==false){
        $this->erro_banco = str_replace("\n","",@pg_last_error());
        $this->erro_sql   = "Verifique o cadastro da sequencia: rhpessoalprocessoduracao_rh276_sequencial_seq do campo: rh276_sequencial"; 
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false; 
      }
      $this->rh276_sequencial = pg_result($result,0,0); 
    }else{
      $result = db_query("select last_value from rhpessoalprocessoduracao_rh276_sequencial_seq");
      if(($result != false) && (pg_result($result,0,0) < $rh276_sequencial)){
        $this->erro_sql = " Campo rh276_sequencial maior que último número da sequencia.";
        $this->erro_banco = "Sequencia menor que este número.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }else{
        $this->rh276_sequencial = $rh276_sequencial; 
      }
    }
     if(($this->rh276_sequencial == null) || ($this->rh276_sequencial == "") ){ 
       $this->erro_sql = " Campo rh276_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpessoalprocessoduracao(
                                       rh276_sequencial 
                                      ,rh276_sequencialprocessovinculo 
                                      ,rh276_tpcontr 
                                      ,rh276_dtterm 
                                      ,rh276_clauassec 
                                      ,rh276_objdet 
                       )
                values (
                                $this->rh276_sequencial 
                               ,$this->rh276_sequencialprocessovinculo 
                               ,$this->rh276_tpcontr 
                               ,".($this->rh276_dtterm == "null" || $this->rh276_dtterm == ""?"null":"'".$this->rh276_dtterm."'")." 
                               ,'$this->rh276_clauassec' 
                               ,'$this->rh276_objdet' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Duração do contrato de trabalho. ($this->rh276_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Duração do contrato de trabalho. já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Duração do contrato de trabalho. ($this->rh276_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh276_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh276_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1014868,'$this->rh276_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1011040,1014868,'','".AddSlashes(pg_result($resaco,0,'rh276_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011040,1014869,'','".AddSlashes(pg_result($resaco,0,'rh276_sequencialprocessovinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011040,1014870,'','".AddSlashes(pg_result($resaco,0,'rh276_tpcontr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011040,1014871,'','".AddSlashes(pg_result($resaco,0,'rh276_dtterm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011040,1014872,'','".AddSlashes(pg_result($resaco,0,'rh276_clauassec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011040,1014873,'','".AddSlashes(pg_result($resaco,0,'rh276_objdet'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh276_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhpessoalprocessoduracao set ";
     $virgula = "";
     if(trim($this->rh276_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh276_sequencial"])){ 
       $sql  .= $virgula." rh276_sequencial = $this->rh276_sequencial ";
       $virgula = ",";
       if(trim($this->rh276_sequencial) == null ){ 
         $this->erro_sql = " Campo Número Sequencial não informado.";
         $this->erro_campo = "rh276_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh276_sequencialprocessovinculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh276_sequencialprocessovinculo"])){ 
       $sql  .= $virgula." rh276_sequencialprocessovinculo = $this->rh276_sequencialprocessovinculo ";
       $virgula = ",";
       if(trim($this->rh276_sequencialprocessovinculo) == null ){ 
         $this->erro_sql = " Campo Processo vinculo não informado.";
         $this->erro_campo = "rh276_sequencialprocessovinculo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh276_tpcontr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh276_tpcontr"])){ 
        if(trim($this->rh276_tpcontr)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh276_tpcontr"])){ 
           $this->rh276_tpcontr = "0" ; 
        } 
       $sql  .= $virgula." rh276_tpcontr = $this->rh276_tpcontr ";
       $virgula = ",";
     }
     if(trim($this->rh276_dtterm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh276_dtterm_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh276_dtterm_dia"] !="") ){ 
       $sql  .= $virgula." rh276_dtterm = '$this->rh276_dtterm' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh276_dtterm_dia"])){ 
         $sql  .= $virgula." rh276_dtterm = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh276_clauassec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh276_clauassec"])){ 
       $sql  .= $virgula." rh276_clauassec = '$this->rh276_clauassec' ";
       $virgula = ",";
     }
     if(trim($this->rh276_objdet)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh276_objdet"])){ 
       $sql  .= $virgula." rh276_objdet = '$this->rh276_objdet' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh276_sequencial!=null){
       $sql .= " rh276_sequencial = $this->rh276_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh276_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1014868,'$this->rh276_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh276_sequencial"]) || $this->rh276_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1011040,1014868,'".AddSlashes(pg_result($resaco,$conresaco,'rh276_sequencial'))."','$this->rh276_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh276_sequencialprocessovinculo"]) || $this->rh276_sequencialprocessovinculo != "")
             $resac = db_query("insert into db_acount values($acount,1011040,1014869,'".AddSlashes(pg_result($resaco,$conresaco,'rh276_sequencialprocessovinculo'))."','$this->rh276_sequencialprocessovinculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh276_tpcontr"]) || $this->rh276_tpcontr != "")
             $resac = db_query("insert into db_acount values($acount,1011040,1014870,'".AddSlashes(pg_result($resaco,$conresaco,'rh276_tpcontr'))."','$this->rh276_tpcontr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh276_dtterm"]) || $this->rh276_dtterm != "")
             $resac = db_query("insert into db_acount values($acount,1011040,1014871,'".AddSlashes(pg_result($resaco,$conresaco,'rh276_dtterm'))."','$this->rh276_dtterm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh276_clauassec"]) || $this->rh276_clauassec != "")
             $resac = db_query("insert into db_acount values($acount,1011040,1014872,'".AddSlashes(pg_result($resaco,$conresaco,'rh276_clauassec'))."','$this->rh276_clauassec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh276_objdet"]) || $this->rh276_objdet != "")
             $resac = db_query("insert into db_acount values($acount,1011040,1014873,'".AddSlashes(pg_result($resaco,$conresaco,'rh276_objdet'))."','$this->rh276_objdet',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Duração do contrato de trabalho. não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh276_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Duração do contrato de trabalho. não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh276_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh276_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh276_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh276_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1014868,'$rh276_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1011040,1014868,'','".AddSlashes(pg_result($resaco,$iresaco,'rh276_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011040,1014869,'','".AddSlashes(pg_result($resaco,$iresaco,'rh276_sequencialprocessovinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011040,1014870,'','".AddSlashes(pg_result($resaco,$iresaco,'rh276_tpcontr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011040,1014871,'','".AddSlashes(pg_result($resaco,$iresaco,'rh276_dtterm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011040,1014872,'','".AddSlashes(pg_result($resaco,$iresaco,'rh276_clauassec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1011040,1014873,'','".AddSlashes(pg_result($resaco,$iresaco,'rh276_objdet'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhpessoalprocessoduracao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh276_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh276_sequencial = $rh276_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Duração do contrato de trabalho. não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh276_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Duração do contrato de trabalho. não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh276_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh276_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpessoalprocessoduracao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh276_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhpessoalprocessoduracao ";
     $sql .= "      inner join rhpessoalprocessovinculo  on  rhpessoalprocessovinculo.rh274_sequencial = rhpessoalprocessoduracao.rh276_sequencialprocessovinculo";
     $sql .= "      inner join rhpessoalprocessoservidor  on  rhpessoalprocessoservidor.rh271_sequencial = rhpessoalprocessovinculo.rh274_sequencialprocessoservidor";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh276_sequencial)) {
         $sql2 .= " where rhpessoalprocessoduracao.rh276_sequencial = $rh276_sequencial "; 
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

    public function sql_query_file($rh276_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhpessoalprocessoduracao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh276_sequencial)){
         $sql2 .= " where rhpessoalprocessoduracao.rh276_sequencial = $rh276_sequencial "; 
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
