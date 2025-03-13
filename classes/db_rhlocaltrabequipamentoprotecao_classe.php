<?php

class cl_rhlocaltrabequipamentoprotecao
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
    public $rh257_sequencial = 0; 
    public $rh257_rhlocaltrabagentesnocivos = 0; 
    public $rh257_utilizaepc = 0; 
    public $rh257_eficaciaepc = null; 
    public $rh257_utilizaepi = 0; 
    public $rh257_eficaciaepi = null; 
    public $rh257_medidaprotecaoepi = null; 
    public $rh257_funcionamentoepi = null; 
    public $rh257_usoininterruptoepi = null; 
    public $rh257_validadeepi = null; 
    public $rh257_periodicidadeepi = null; 
    public $rh257_higienizacaoepi = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh257_sequencial = int4 = Sequencial 
                 rh257_rhlocaltrabagentesnocivos = int4 = Sequencial Agente Nocivo 
                 rh257_utilizaepc = int4 = Utilização EPC 
                 rh257_eficaciaepc = varchar(1) = Eficacia EPC 
                 rh257_utilizaepi = int4 = Utiliza EPI? 
                 rh257_eficaciaepi = varchar(1) = Eficacia EPI 
                 rh257_medidaprotecaoepi = varchar(1) = Implementada medida de proteção? 
                 rh257_funcionamentoepi = varchar(1) = Observado funcionamento Epi? 
                 rh257_usoininterruptoepi = varchar(1) = Uso ininterrupto? 
                 rh257_validadeepi = varchar(1) = Observada Validade? 
                 rh257_periodicidadeepi = varchar(1) = Observada Periodicidade? 
                 rh257_higienizacaoepi = varchar(1) = Observada higienização? 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhlocaltrabequipamentoprotecao"); 
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
       $this->rh257_sequencial = ($this->rh257_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh257_sequencial"]:$this->rh257_sequencial);
       $this->rh257_rhlocaltrabagentesnocivos = ($this->rh257_rhlocaltrabagentesnocivos == ""?@$GLOBALS["HTTP_POST_VARS"]["rh257_rhlocaltrabagentesnocivos"]:$this->rh257_rhlocaltrabagentesnocivos);
       $this->rh257_utilizaepc = ($this->rh257_utilizaepc == ""?@$GLOBALS["HTTP_POST_VARS"]["rh257_utilizaepc"]:$this->rh257_utilizaepc);
       $this->rh257_eficaciaepc = ($this->rh257_eficaciaepc == ""?@$GLOBALS["HTTP_POST_VARS"]["rh257_eficaciaepc"]:$this->rh257_eficaciaepc);
       $this->rh257_utilizaepi = ($this->rh257_utilizaepi == ""?@$GLOBALS["HTTP_POST_VARS"]["rh257_utilizaepi"]:$this->rh257_utilizaepi);
       $this->rh257_eficaciaepi = ($this->rh257_eficaciaepi == ""?@$GLOBALS["HTTP_POST_VARS"]["rh257_eficaciaepi"]:$this->rh257_eficaciaepi);
       $this->rh257_medidaprotecaoepi = ($this->rh257_medidaprotecaoepi == ""?@$GLOBALS["HTTP_POST_VARS"]["rh257_medidaprotecaoepi"]:$this->rh257_medidaprotecaoepi);
       $this->rh257_funcionamentoepi = ($this->rh257_funcionamentoepi == ""?@$GLOBALS["HTTP_POST_VARS"]["rh257_funcionamentoepi"]:$this->rh257_funcionamentoepi);
       $this->rh257_usoininterruptoepi = ($this->rh257_usoininterruptoepi == ""?@$GLOBALS["HTTP_POST_VARS"]["rh257_usoininterruptoepi"]:$this->rh257_usoininterruptoepi);
       $this->rh257_validadeepi = ($this->rh257_validadeepi == ""?@$GLOBALS["HTTP_POST_VARS"]["rh257_validadeepi"]:$this->rh257_validadeepi);
       $this->rh257_periodicidadeepi = ($this->rh257_periodicidadeepi == ""?@$GLOBALS["HTTP_POST_VARS"]["rh257_periodicidadeepi"]:$this->rh257_periodicidadeepi);
       $this->rh257_higienizacaoepi = ($this->rh257_higienizacaoepi == ""?@$GLOBALS["HTTP_POST_VARS"]["rh257_higienizacaoepi"]:$this->rh257_higienizacaoepi);
     }else{
       $this->rh257_sequencial = ($this->rh257_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh257_sequencial"]:$this->rh257_sequencial);
     }
   }

    public function incluir($rh257_sequencial)
    {
      $this->atualizacampos();
     if($this->rh257_rhlocaltrabagentesnocivos == null ){ 
       $this->erro_sql = " Campo Sequencial Agente Nocivo não informado.";
       $this->erro_campo = "rh257_rhlocaltrabagentesnocivos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh257_utilizaepc == null ){ 
       $this->erro_sql = " Campo Utilização EPC não informado.";
       $this->erro_campo = "rh257_utilizaepc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh257_eficaciaepc == null ){ 
       $this->erro_sql = " Campo Eficacia EPC não informado.";
       $this->erro_campo = "rh257_eficaciaepc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh257_utilizaepi == null ){ 
       $this->erro_sql = " Campo Utiliza EPI? não informado.";
       $this->erro_campo = "rh257_utilizaepi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh257_eficaciaepi == null ){ 
       $this->erro_sql = " Campo Eficacia EPI não informado.";
       $this->erro_campo = "rh257_eficaciaepi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh257_medidaprotecaoepi == null ){ 
       $this->erro_sql = " Campo Implementada medida de proteção? não informado.";
       $this->erro_campo = "rh257_medidaprotecaoepi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh257_funcionamentoepi == null ){ 
       $this->erro_sql = " Campo Observado funcionamento Epi? não informado.";
       $this->erro_campo = "rh257_funcionamentoepi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh257_usoininterruptoepi == null ){ 
       $this->erro_sql = " Campo Uso ininterrupto? não informado.";
       $this->erro_campo = "rh257_usoininterruptoepi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh257_validadeepi == null ){ 
       $this->erro_sql = " Campo Observada Validade? não informado.";
       $this->erro_campo = "rh257_validadeepi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh257_periodicidadeepi == null ){ 
       $this->erro_sql = " Campo Observada Periodicidade? não informado.";
       $this->erro_campo = "rh257_periodicidadeepi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh257_higienizacaoepi == null ){ 
       $this->erro_sql = " Campo Observada higienização? não informado.";
       $this->erro_campo = "rh257_higienizacaoepi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh257_sequencial == "" || $rh257_sequencial == null ){
       $result = db_query("select nextval('rhlocaltrabequipamentoprotecao_rh257_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhlocaltrabequipamentoprotecao_rh257_sequencial_seq do campo: rh257_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh257_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhlocaltrabequipamentoprotecao_rh257_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh257_sequencial)){
         $this->erro_sql = " Campo rh257_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh257_sequencial = $rh257_sequencial; 
       }
     }
     if(($this->rh257_sequencial == null) || ($this->rh257_sequencial == "") ){ 
       $this->erro_sql = " Campo rh257_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhlocaltrabequipamentoprotecao(
                                       rh257_sequencial 
                                      ,rh257_rhlocaltrabagentesnocivos 
                                      ,rh257_utilizaepc 
                                      ,rh257_eficaciaepc 
                                      ,rh257_utilizaepi 
                                      ,rh257_eficaciaepi 
                                      ,rh257_medidaprotecaoepi 
                                      ,rh257_funcionamentoepi 
                                      ,rh257_usoininterruptoepi 
                                      ,rh257_validadeepi 
                                      ,rh257_periodicidadeepi 
                                      ,rh257_higienizacaoepi 
                       )
                values (
                                $this->rh257_sequencial 
                               ,$this->rh257_rhlocaltrabagentesnocivos 
                               ,$this->rh257_utilizaepc 
                               ,'$this->rh257_eficaciaepc' 
                               ,$this->rh257_utilizaepi 
                               ,'$this->rh257_eficaciaepi' 
                               ,'$this->rh257_medidaprotecaoepi' 
                               ,'$this->rh257_funcionamentoepi' 
                               ,'$this->rh257_usoininterruptoepi' 
                               ,'$this->rh257_validadeepi' 
                               ,'$this->rh257_periodicidadeepi' 
                               ,'$this->rh257_higienizacaoepi' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Equipamentos de proteção do local de trabalho ($this->rh257_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Equipamentos de proteção do local de trabalho já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Equipamentos de proteção do local de trabalho ($this->rh257_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh257_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh257_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1013677,'$this->rh257_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010859,1013677,'','".AddSlashes(pg_result($resaco,0,'rh257_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010859,1013739,'','".AddSlashes(pg_result($resaco,0,'rh257_rhlocaltrabagentesnocivos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010859,1013680,'','".AddSlashes(pg_result($resaco,0,'rh257_utilizaepc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010859,1013681,'','".AddSlashes(pg_result($resaco,0,'rh257_eficaciaepc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010859,1013682,'','".AddSlashes(pg_result($resaco,0,'rh257_utilizaepi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010859,1013683,'','".AddSlashes(pg_result($resaco,0,'rh257_eficaciaepi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010859,1013686,'','".AddSlashes(pg_result($resaco,0,'rh257_medidaprotecaoepi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010859,1013687,'','".AddSlashes(pg_result($resaco,0,'rh257_funcionamentoepi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010859,1013688,'','".AddSlashes(pg_result($resaco,0,'rh257_usoininterruptoepi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010859,1013689,'','".AddSlashes(pg_result($resaco,0,'rh257_validadeepi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010859,1013690,'','".AddSlashes(pg_result($resaco,0,'rh257_periodicidadeepi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010859,1013691,'','".AddSlashes(pg_result($resaco,0,'rh257_higienizacaoepi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh257_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhlocaltrabequipamentoprotecao set ";
     $virgula = "";
     if(trim($this->rh257_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh257_sequencial"])){ 
       $sql  .= $virgula." rh257_sequencial = $this->rh257_sequencial ";
       $virgula = ",";
       if(trim($this->rh257_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh257_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh257_rhlocaltrabagentesnocivos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh257_rhlocaltrabagentesnocivos"])){ 
       $sql  .= $virgula." rh257_rhlocaltrabagentesnocivos = $this->rh257_rhlocaltrabagentesnocivos ";
       $virgula = ",";
       if(trim($this->rh257_rhlocaltrabagentesnocivos) == null ){ 
         $this->erro_sql = " Campo Sequencial Agente Nocivo não informado.";
         $this->erro_campo = "rh257_rhlocaltrabagentesnocivos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh257_utilizaepc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh257_utilizaepc"])){ 
       $sql  .= $virgula." rh257_utilizaepc = $this->rh257_utilizaepc ";
       $virgula = ",";
       if(trim($this->rh257_utilizaepc) == null ){ 
         $this->erro_sql = " Campo Utilização EPC não informado.";
         $this->erro_campo = "rh257_utilizaepc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh257_eficaciaepc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh257_eficaciaepc"])){ 
       $sql  .= $virgula." rh257_eficaciaepc = '$this->rh257_eficaciaepc' ";
       $virgula = ",";
       if(trim($this->rh257_eficaciaepc) == null ){ 
         $this->erro_sql = " Campo Eficacia EPC não informado.";
         $this->erro_campo = "rh257_eficaciaepc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh257_utilizaepi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh257_utilizaepi"])){ 
       $sql  .= $virgula." rh257_utilizaepi = $this->rh257_utilizaepi ";
       $virgula = ",";
       if(trim($this->rh257_utilizaepi) == null ){ 
         $this->erro_sql = " Campo Utiliza EPI? não informado.";
         $this->erro_campo = "rh257_utilizaepi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh257_eficaciaepi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh257_eficaciaepi"])){ 
       $sql  .= $virgula." rh257_eficaciaepi = '$this->rh257_eficaciaepi' ";
       $virgula = ",";
       if(trim($this->rh257_eficaciaepi) == null ){ 
         $this->erro_sql = " Campo Eficacia EPI não informado.";
         $this->erro_campo = "rh257_eficaciaepi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh257_medidaprotecaoepi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh257_medidaprotecaoepi"])){ 
       $sql  .= $virgula." rh257_medidaprotecaoepi = '$this->rh257_medidaprotecaoepi' ";
       $virgula = ",";
       if(trim($this->rh257_medidaprotecaoepi) == null ){ 
         $this->erro_sql = " Campo Implementada medida de proteção? não informado.";
         $this->erro_campo = "rh257_medidaprotecaoepi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh257_funcionamentoepi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh257_funcionamentoepi"])){ 
       $sql  .= $virgula." rh257_funcionamentoepi = '$this->rh257_funcionamentoepi' ";
       $virgula = ",";
       if(trim($this->rh257_funcionamentoepi) == null ){ 
         $this->erro_sql = " Campo Observado funcionamento Epi? não informado.";
         $this->erro_campo = "rh257_funcionamentoepi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh257_usoininterruptoepi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh257_usoininterruptoepi"])){ 
       $sql  .= $virgula." rh257_usoininterruptoepi = '$this->rh257_usoininterruptoepi' ";
       $virgula = ",";
       if(trim($this->rh257_usoininterruptoepi) == null ){ 
         $this->erro_sql = " Campo Uso ininterrupto? não informado.";
         $this->erro_campo = "rh257_usoininterruptoepi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh257_validadeepi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh257_validadeepi"])){ 
       $sql  .= $virgula." rh257_validadeepi = '$this->rh257_validadeepi' ";
       $virgula = ",";
       if(trim($this->rh257_validadeepi) == null ){ 
         $this->erro_sql = " Campo Observada Validade? não informado.";
         $this->erro_campo = "rh257_validadeepi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh257_periodicidadeepi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh257_periodicidadeepi"])){ 
       $sql  .= $virgula." rh257_periodicidadeepi = '$this->rh257_periodicidadeepi' ";
       $virgula = ",";
       if(trim($this->rh257_periodicidadeepi) == null ){ 
         $this->erro_sql = " Campo Observada Periodicidade? não informado.";
         $this->erro_campo = "rh257_periodicidadeepi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh257_higienizacaoepi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh257_higienizacaoepi"])){ 
       $sql  .= $virgula." rh257_higienizacaoepi = '$this->rh257_higienizacaoepi' ";
       $virgula = ",";
       if(trim($this->rh257_higienizacaoepi) == null ){ 
         $this->erro_sql = " Campo Observada higienização? não informado.";
         $this->erro_campo = "rh257_higienizacaoepi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh257_sequencial!=null){
       $sql .= " rh257_sequencial = $this->rh257_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh257_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1013677,'$this->rh257_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh257_sequencial"]) || $this->rh257_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010859,1013677,'".AddSlashes(pg_result($resaco,$conresaco,'rh257_sequencial'))."','$this->rh257_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh257_rhlocaltrabagentesnocivos"]) || $this->rh257_rhlocaltrabagentesnocivos != "")
             $resac = db_query("insert into db_acount values($acount,1010859,1013739,'".AddSlashes(pg_result($resaco,$conresaco,'rh257_rhlocaltrabagentesnocivos'))."','$this->rh257_rhlocaltrabagentesnocivos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh257_utilizaepc"]) || $this->rh257_utilizaepc != "")
             $resac = db_query("insert into db_acount values($acount,1010859,1013680,'".AddSlashes(pg_result($resaco,$conresaco,'rh257_utilizaepc'))."','$this->rh257_utilizaepc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh257_eficaciaepc"]) || $this->rh257_eficaciaepc != "")
             $resac = db_query("insert into db_acount values($acount,1010859,1013681,'".AddSlashes(pg_result($resaco,$conresaco,'rh257_eficaciaepc'))."','$this->rh257_eficaciaepc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh257_utilizaepi"]) || $this->rh257_utilizaepi != "")
             $resac = db_query("insert into db_acount values($acount,1010859,1013682,'".AddSlashes(pg_result($resaco,$conresaco,'rh257_utilizaepi'))."','$this->rh257_utilizaepi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh257_eficaciaepi"]) || $this->rh257_eficaciaepi != "")
             $resac = db_query("insert into db_acount values($acount,1010859,1013683,'".AddSlashes(pg_result($resaco,$conresaco,'rh257_eficaciaepi'))."','$this->rh257_eficaciaepi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh257_medidaprotecaoepi"]) || $this->rh257_medidaprotecaoepi != "")
             $resac = db_query("insert into db_acount values($acount,1010859,1013686,'".AddSlashes(pg_result($resaco,$conresaco,'rh257_medidaprotecaoepi'))."','$this->rh257_medidaprotecaoepi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh257_funcionamentoepi"]) || $this->rh257_funcionamentoepi != "")
             $resac = db_query("insert into db_acount values($acount,1010859,1013687,'".AddSlashes(pg_result($resaco,$conresaco,'rh257_funcionamentoepi'))."','$this->rh257_funcionamentoepi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh257_usoininterruptoepi"]) || $this->rh257_usoininterruptoepi != "")
             $resac = db_query("insert into db_acount values($acount,1010859,1013688,'".AddSlashes(pg_result($resaco,$conresaco,'rh257_usoininterruptoepi'))."','$this->rh257_usoininterruptoepi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh257_validadeepi"]) || $this->rh257_validadeepi != "")
             $resac = db_query("insert into db_acount values($acount,1010859,1013689,'".AddSlashes(pg_result($resaco,$conresaco,'rh257_validadeepi'))."','$this->rh257_validadeepi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh257_periodicidadeepi"]) || $this->rh257_periodicidadeepi != "")
             $resac = db_query("insert into db_acount values($acount,1010859,1013690,'".AddSlashes(pg_result($resaco,$conresaco,'rh257_periodicidadeepi'))."','$this->rh257_periodicidadeepi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh257_higienizacaoepi"]) || $this->rh257_higienizacaoepi != "")
             $resac = db_query("insert into db_acount values($acount,1010859,1013691,'".AddSlashes(pg_result($resaco,$conresaco,'rh257_higienizacaoepi'))."','$this->rh257_higienizacaoepi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Equipamentos de proteção do local de trabalho não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh257_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Equipamentos de proteção do local de trabalho não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh257_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh257_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh257_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh257_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1013677,'$rh257_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010859,1013677,'','".AddSlashes(pg_result($resaco,$iresaco,'rh257_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010859,1013739,'','".AddSlashes(pg_result($resaco,$iresaco,'rh257_rhlocaltrabagentesnocivos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010859,1013680,'','".AddSlashes(pg_result($resaco,$iresaco,'rh257_utilizaepc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010859,1013681,'','".AddSlashes(pg_result($resaco,$iresaco,'rh257_eficaciaepc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010859,1013682,'','".AddSlashes(pg_result($resaco,$iresaco,'rh257_utilizaepi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010859,1013683,'','".AddSlashes(pg_result($resaco,$iresaco,'rh257_eficaciaepi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010859,1013686,'','".AddSlashes(pg_result($resaco,$iresaco,'rh257_medidaprotecaoepi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010859,1013687,'','".AddSlashes(pg_result($resaco,$iresaco,'rh257_funcionamentoepi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010859,1013688,'','".AddSlashes(pg_result($resaco,$iresaco,'rh257_usoininterruptoepi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010859,1013689,'','".AddSlashes(pg_result($resaco,$iresaco,'rh257_validadeepi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010859,1013690,'','".AddSlashes(pg_result($resaco,$iresaco,'rh257_periodicidadeepi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010859,1013691,'','".AddSlashes(pg_result($resaco,$iresaco,'rh257_higienizacaoepi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhlocaltrabequipamentoprotecao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh257_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh257_sequencial = $rh257_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Equipamentos de proteção do local de trabalho não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh257_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Equipamentos de proteção do local de trabalho não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh257_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh257_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhlocaltrabequipamentoprotecao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh257_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhlocaltrabequipamentoprotecao ";
     $sql .= "      inner join rhlocaltrabagentesnocivos  on  rhlocaltrabagentesnocivos.rh256_sequencial = rhlocaltrabequipamentoprotecao.rh257_rhlocaltrabagentesnocivos";
     $sql .= "      inner join rhlocaltrab  on  rhlocaltrab.rh55_codigo = rhlocaltrabagentesnocivos.rh256_rhlocaltrab and  rhlocaltrab.rh55_instit = rhlocaltrabagentesnocivos.rh256_instituicao";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh257_sequencial)) {
         $sql2 .= " where rhlocaltrabequipamentoprotecao.rh257_sequencial = $rh257_sequencial "; 
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

    public function sql_query_file($rh257_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhlocaltrabequipamentoprotecao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh257_sequencial)){
         $sql2 .= " where rhlocaltrabequipamentoprotecao.rh257_sequencial = $rh257_sequencial "; 
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
