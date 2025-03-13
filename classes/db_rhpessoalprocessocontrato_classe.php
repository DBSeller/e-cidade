<?php

class cl_rhpessoalprocessocontrato
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
    public $rh273_sequencial = 0; 
    public $rh273_sequencialprocessoservidor = 0; 
    public $rh273_tpcontr = 0; 
    public $rh273_indcontr = null; 
    public $rh273_dtadmorig_dia = null; 
    public $rh273_dtadmorig_mes = null; 
    public $rh273_dtadmorig_ano = null; 
    public $rh273_dtadmorig = null; 
    public $rh273_indreint = null; 
    public $rh273_indcateg = null; 
    public $rh273_indnatativ = null; 
    public $rh273_indmotdeslig = null; 
    public $rh273_dinicio_dia = null; 
    public $rh273_dinicio_mes = null; 
    public $rh273_dinicio_ano = null; 
    public $rh273_dinicio = null; 
    public $rh273_codcbo = null; 
    public $rh273_natatividade = 0; 
    public $rh273_compini = null; 
    public $rh273_compfim = null; 
    public $rh273_indreperc = 0; 
    public $rh273_indenabono = null; 
    public $rh273_indensd = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh273_sequencial = int4 = Número Sequencial 
                 rh273_sequencialprocessoservidor = int4 = Identificação única de processo 
                 rh273_tpcontr = int4 = Tipo de contrato 
                 rh273_indcontr = varchar(1) = Indicativo de contrato 
                 rh273_dtadmorig = date = Data de admissão 
                 rh273_indreint = varchar(1) = Indicativo de reintegração 
                 rh273_indcateg = varchar(1) = Categoria diferente 
                 rh273_indnatativ = varchar(1) = Natureza da atividade 
                 rh273_indmotdeslig = varchar(1) = Motivo Desligamento 
                 rh273_dinicio = date = Início de TSVE 
                 rh273_codcbo = varchar(6) = Código CBO 
                 rh273_natatividade = int4 = Natureza da atividade 
                 rh273_compini = varchar(7) = Competência inicial 
                 rh273_compfim = varchar(7) = Competência final 
                 rh273_indreperc = int4 = Indicativo de repercussão 
                 rh273_indenabono = varchar(10) = Indenização abono salarial 
                 rh273_indensd = varchar(10) = Indenização substitutiva 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhpessoalprocessocontrato"); 
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
       $this->rh273_sequencial = ($this->rh273_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh273_sequencial"]:$this->rh273_sequencial);
       $this->rh273_sequencialprocessoservidor = ($this->rh273_sequencialprocessoservidor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh273_sequencialprocessoservidor"]:$this->rh273_sequencialprocessoservidor);
       $this->rh273_tpcontr = ($this->rh273_tpcontr == ""?@$GLOBALS["HTTP_POST_VARS"]["rh273_tpcontr"]:$this->rh273_tpcontr);
       $this->rh273_indcontr = ($this->rh273_indcontr == ""?@$GLOBALS["HTTP_POST_VARS"]["rh273_indcontr"]:$this->rh273_indcontr);
       if($this->rh273_dtadmorig == ""){
         $this->rh273_dtadmorig_dia = ($this->rh273_dtadmorig_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh273_dtadmorig_dia"]:$this->rh273_dtadmorig_dia);
         $this->rh273_dtadmorig_mes = ($this->rh273_dtadmorig_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh273_dtadmorig_mes"]:$this->rh273_dtadmorig_mes);
         $this->rh273_dtadmorig_ano = ($this->rh273_dtadmorig_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh273_dtadmorig_ano"]:$this->rh273_dtadmorig_ano);
         if($this->rh273_dtadmorig_dia != ""){
            $this->rh273_dtadmorig = $this->rh273_dtadmorig_ano."-".$this->rh273_dtadmorig_mes."-".$this->rh273_dtadmorig_dia;
         }
       }
       $this->rh273_indreint = ($this->rh273_indreint == ""?@$GLOBALS["HTTP_POST_VARS"]["rh273_indreint"]:$this->rh273_indreint);
       $this->rh273_indcateg = ($this->rh273_indcateg == ""?@$GLOBALS["HTTP_POST_VARS"]["rh273_indcateg"]:$this->rh273_indcateg);
       $this->rh273_indnatativ = ($this->rh273_indnatativ == ""?@$GLOBALS["HTTP_POST_VARS"]["rh273_indnatativ"]:$this->rh273_indnatativ);
       $this->rh273_indmotdeslig = ($this->rh273_indmotdeslig == ""?@$GLOBALS["HTTP_POST_VARS"]["rh273_indmotdeslig"]:$this->rh273_indmotdeslig);
       if($this->rh273_dinicio == ""){
         $this->rh273_dinicio_dia = ($this->rh273_dinicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh273_dinicio_dia"]:$this->rh273_dinicio_dia);
         $this->rh273_dinicio_mes = ($this->rh273_dinicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh273_dinicio_mes"]:$this->rh273_dinicio_mes);
         $this->rh273_dinicio_ano = ($this->rh273_dinicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh273_dinicio_ano"]:$this->rh273_dinicio_ano);
         if($this->rh273_dinicio_dia != ""){
            $this->rh273_dinicio = $this->rh273_dinicio_ano."-".$this->rh273_dinicio_mes."-".$this->rh273_dinicio_dia;
         }
       }
       $this->rh273_codcbo = ($this->rh273_codcbo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh273_codcbo"]:$this->rh273_codcbo);
       $this->rh273_natatividade = ($this->rh273_natatividade == ""?@$GLOBALS["HTTP_POST_VARS"]["rh273_natatividade"]:$this->rh273_natatividade);
       $this->rh273_compini = ($this->rh273_compini == ""?@$GLOBALS["HTTP_POST_VARS"]["rh273_compini"]:$this->rh273_compini);
       $this->rh273_compfim = ($this->rh273_compfim == ""?@$GLOBALS["HTTP_POST_VARS"]["rh273_compfim"]:$this->rh273_compfim);
       $this->rh273_indreperc = ($this->rh273_indreperc == ""?@$GLOBALS["HTTP_POST_VARS"]["rh273_indreperc"]:$this->rh273_indreperc);
       $this->rh273_indenabono = ($this->rh273_indenabono == ""?@$GLOBALS["HTTP_POST_VARS"]["rh273_indenabono"]:$this->rh273_indenabono);
       $this->rh273_indensd = ($this->rh273_indensd == ""?@$GLOBALS["HTTP_POST_VARS"]["rh273_indensd"]:$this->rh273_indensd);
     }else{
       $this->rh273_sequencial = ($this->rh273_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh273_sequencial"]:$this->rh273_sequencial);
     }
   }

    public function incluir($rh273_sequencial)
    {
      $this->atualizacampos();
     if($this->rh273_sequencialprocessoservidor == null ){ 
       $this->erro_sql = " Campo Identificação única de processo não informado.";
       $this->erro_campo = "rh273_sequencialprocessoservidor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh273_tpcontr == null ){ 
       $this->rh273_tpcontr = "0";
     }
     if($this->rh273_dtadmorig == null ){ 
       $this->rh273_dtadmorig = "null";
     }
     if($this->rh273_indmotdeslig == null ){ 
       $this->erro_sql = " Campo Motivo Desligamento não informado.";
       $this->erro_campo = "rh273_indmotdeslig";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh273_dinicio == null ){ 
       $this->rh273_dinicio = "null";
     }
     if($this->rh273_natatividade == null ){ 
       $this->rh273_natatividade = "0";
     }
     if($this->rh273_indreperc == null ){ 
       $this->rh273_indreperc = "0";
     }
     if($rh273_sequencial == "" || $rh273_sequencial == null ){
       $result = db_query("select nextval('rhpessoalprocessocontrato_rh273_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhpessoalprocessocontrato_rh273_sequencial_seq do campo: rh273_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh273_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhpessoalprocessocontrato_rh273_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh273_sequencial)){
         $this->erro_sql = " Campo rh273_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh273_sequencial = $rh273_sequencial; 
       }
     }
     if(($this->rh273_sequencial == null) || ($this->rh273_sequencial == "") ){ 
       $this->erro_sql = " Campo rh273_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhpessoalprocessocontrato(
                                       rh273_sequencial 
                                      ,rh273_sequencialprocessoservidor 
                                      ,rh273_tpcontr 
                                      ,rh273_indcontr 
                                      ,rh273_dtadmorig 
                                      ,rh273_indreint 
                                      ,rh273_indcateg 
                                      ,rh273_indnatativ 
                                      ,rh273_indmotdeslig 
                                      ,rh273_dinicio 
                                      ,rh273_codcbo 
                                      ,rh273_natatividade 
                                      ,rh273_compini 
                                      ,rh273_compfim 
                                      ,rh273_indreperc 
                                      ,rh273_indenabono 
                                      ,rh273_indensd 
                       )
                values (
                                $this->rh273_sequencial 
                               ,$this->rh273_sequencialprocessoservidor 
                               ,$this->rh273_tpcontr 
                               ,'$this->rh273_indcontr' 
                               ,".($this->rh273_dtadmorig == "null" || $this->rh273_dtadmorig == ""?"null":"'".$this->rh273_dtadmorig."'")." 
                               ,'$this->rh273_indreint' 
                               ,'$this->rh273_indcateg' 
                               ,'$this->rh273_indnatativ' 
                               ,'$this->rh273_indmotdeslig' 
                               ,".($this->rh273_dinicio == "null" || $this->rh273_dinicio == ""?"null":"'".$this->rh273_dinicio."'")." 
                               ,'$this->rh273_codcbo' 
                               ,$this->rh273_natatividade 
                               ,'$this->rh273_compini' 
                               ,'$this->rh273_compfim' 
                               ,$this->rh273_indreperc 
                               ,'$this->rh273_indenabono' 
                               ,'$this->rh273_indensd' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Contrato de Trabalho ($this->rh273_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Contrato de Trabalho já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Contrato de Trabalho ($this->rh273_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh273_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   } 

    public function alterar($rh273_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhpessoalprocessocontrato set ";
     $virgula = "";
     if(trim($this->rh273_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh273_sequencial"])){ 
       $sql  .= $virgula." rh273_sequencial = $this->rh273_sequencial ";
       $virgula = ",";
       if(trim($this->rh273_sequencial) == null ){ 
         $this->erro_sql = " Campo Número Sequencial não informado.";
         $this->erro_campo = "rh273_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh273_sequencialprocessoservidor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh273_sequencialprocessoservidor"])){ 
       $sql  .= $virgula." rh273_sequencialprocessoservidor = $this->rh273_sequencialprocessoservidor ";
       $virgula = ",";
       if(trim($this->rh273_sequencialprocessoservidor) == null ){ 
         $this->erro_sql = " Campo Identificação única de processo não informado.";
         $this->erro_campo = "rh273_sequencialprocessoservidor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh273_tpcontr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh273_tpcontr"])){ 
        if(trim($this->rh273_tpcontr)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh273_tpcontr"])){ 
           $this->rh273_tpcontr = "0" ; 
        } 
       $sql  .= $virgula." rh273_tpcontr = $this->rh273_tpcontr ";
       $virgula = ",";
     }
     if(trim($this->rh273_indcontr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh273_indcontr"])){ 
       $sql  .= $virgula." rh273_indcontr = '$this->rh273_indcontr' ";
       $virgula = ",";
     }
     if(trim($this->rh273_dtadmorig)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh273_dtadmorig_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh273_dtadmorig_dia"] !="") ){ 
       $sql  .= $virgula." rh273_dtadmorig = '$this->rh273_dtadmorig' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh273_dtadmorig_dia"])){ 
         $sql  .= $virgula." rh273_dtadmorig = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh273_indreint)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh273_indreint"])){ 
       $sql  .= $virgula." rh273_indreint = '$this->rh273_indreint' ";
       $virgula = ",";
     }
     if(trim($this->rh273_indcateg)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh273_indcateg"])){ 
       $sql  .= $virgula." rh273_indcateg = '$this->rh273_indcateg' ";
       $virgula = ",";
     }
     if(trim($this->rh273_indnatativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh273_indnatativ"])){ 
       $sql  .= $virgula." rh273_indnatativ = '$this->rh273_indnatativ' ";
       $virgula = ",";
     }
     if(trim($this->rh273_indmotdeslig)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh273_indmotdeslig"])){ 
       $sql  .= $virgula." rh273_indmotdeslig = '$this->rh273_indmotdeslig' ";
       $virgula = ",";
       if(trim($this->rh273_indmotdeslig) == null ){ 
         $this->erro_sql = " Campo Motivo Desligamento não informado.";
         $this->erro_campo = "rh273_indmotdeslig";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh273_dinicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh273_dinicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh273_dinicio_dia"] !="") ){ 
       $sql  .= $virgula." rh273_dinicio = '$this->rh273_dinicio' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh273_dinicio_dia"])){ 
         $sql  .= $virgula." rh273_dinicio = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh273_codcbo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh273_codcbo"])){ 
       $sql  .= $virgula." rh273_codcbo = '$this->rh273_codcbo' ";
       $virgula = ",";
     }
     if(trim($this->rh273_natatividade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh273_natatividade"])){ 
        if(trim($this->rh273_natatividade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh273_natatividade"])){ 
           $this->rh273_natatividade = "0" ; 
        } 
       $sql  .= $virgula." rh273_natatividade = $this->rh273_natatividade ";
       $virgula = ",";
     }
     if(trim($this->rh273_compini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh273_compini"])){ 
       $sql  .= $virgula." rh273_compini = '$this->rh273_compini' ";
       $virgula = ",";
     }
     if(trim($this->rh273_compfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh273_compfim"])){ 
       $sql  .= $virgula." rh273_compfim = '$this->rh273_compfim' ";
       $virgula = ",";
     }
     if(trim($this->rh273_indreperc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh273_indreperc"])){ 
        if(trim($this->rh273_indreperc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh273_indreperc"])){ 
           $this->rh273_indreperc = "0" ; 
        } 
       $sql  .= $virgula." rh273_indreperc = $this->rh273_indreperc ";
       $virgula = ",";
     }
     if(trim($this->rh273_indenabono)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh273_indenabono"])){ 
       $sql  .= $virgula." rh273_indenabono = '$this->rh273_indenabono' ";
       $virgula = ",";
     }
     if(trim($this->rh273_indensd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh273_indensd"])){ 
       $sql  .= $virgula." rh273_indensd = '$this->rh273_indensd' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh273_sequencial!=null){
       $sql .= " rh273_sequencial = $this->rh273_sequencial";
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contrato de Trabalho não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh273_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Contrato de Trabalho não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh273_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh273_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh273_sequencial=null, $dbwhere = null)
    {
     $sql = " delete from rhpessoalprocessocontrato
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh273_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh273_sequencial = $rh273_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Contrato de Trabalho não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh273_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Contrato de Trabalho não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh273_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh273_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhpessoalprocessocontrato";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh273_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhpessoalprocessocontrato ";
     $sql .= "      inner join rhpessoalprocessoservidor  on  rhpessoalprocessoservidor.rh271_sequencial = rhpessoalprocessocontrato.rh273_sequencialprocessoservidor";
     $sql .= "      inner join rhpessoalprocessojudicialesocial  on  rhpessoalprocessojudicialesocial.rh270_sequencial = rhpessoalprocessoservidor.rh271_sequencialprocesso";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh273_sequencial)) {
         $sql2 .= " where rhpessoalprocessocontrato.rh273_sequencial = $rh273_sequencial "; 
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

    public function sql_query_file($rh273_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhpessoalprocessocontrato ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh273_sequencial)){
         $sql2 .= " where rhpessoalprocessocontrato.rh273_sequencial = $rh273_sequencial "; 
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
