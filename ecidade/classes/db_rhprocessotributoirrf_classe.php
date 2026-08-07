<?php

class cl_rhprocessotributoirrf
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
    public $rh299_sequencial = 0; 
    public $rh299_sequencialprocessoservidor = 0; 
    public $rh299_tpcr = 0; 
    public $rh299_vcr = 0; 
    public $rh299_pagamento = null; 
    public $rh299_vrrendtrib = 0; 
    public $rh299_vrrendtrib13 = 0; 
    public $rh299_vrrendmolegrave = 0; 
    public $rh299_vrrendisen65 = 0; 
    public $rh299_vrjurosmora = 0; 
    public $rh299_vrrendisenntrib = 0; 
    public $rh299_descisenntrib = null; 
    public $rh299_vrprevoficial = 0; 
    public $rh299_descrra = null; 
    public $rh299_qtdmesesrra = 0; 
    public $rh299_vlrdespcustas = 0; 
    public $rh299_vlrdespadvogados = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh299_sequencial = int4 = Número Sequencial 
                 rh299_sequencialprocessoservidor = int4 = Identificação única de servidor 
                 rh299_tpcr = int4 = Relativo IRRF 
                 rh299_vcr = float4 = Valor IRRF 
                 rh299_pagamento = varchar(7) = Data Contemplado 
                 rh299_vrrendtrib = float4 = Rendimento tributável 
                 rh299_vrrendtrib13 = float4 = Rendimento tributável 13 
                 rh299_vrrendmolegrave = float4 = Valor moléstia grave 
                 rh299_vrrendisen65 = float4 = Aposentadoria 65 anos 
                 rh299_vrjurosmora = float4 = Juros de mora 
                 rh299_vrrendisenntrib = float4 = Rendimentos isentos 
                 rh299_descisenntrib = varchar(60) = Rendimento isento 
                 rh299_vrprevoficial = float4 = Previdência oficial 
                 rh299_descrra = varchar(50) = Rendimentos Recebidos Acumuladamente 
                 rh299_qtdmesesrra = int4 = Número de meses 
                 rh299_vlrdespcustas = float4 = Custas judiciais 
                 rh299_vlrdespadvogados = float4 = Despesas com advogado(s) 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhprocessotributoirrf"); 
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
       $this->rh299_sequencial = ($this->rh299_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh299_sequencial"]:$this->rh299_sequencial);
       $this->rh299_sequencialprocessoservidor = ($this->rh299_sequencialprocessoservidor == ""?@$GLOBALS["HTTP_POST_VARS"]["rh299_sequencialprocessoservidor"]:$this->rh299_sequencialprocessoservidor);
       $this->rh299_tpcr = ($this->rh299_tpcr == ""?@$GLOBALS["HTTP_POST_VARS"]["rh299_tpcr"]:$this->rh299_tpcr);
       $this->rh299_vcr = ($this->rh299_vcr == ""?@$GLOBALS["HTTP_POST_VARS"]["rh299_vcr"]:$this->rh299_vcr);
       $this->rh299_pagamento = ($this->rh299_pagamento == ""?@$GLOBALS["HTTP_POST_VARS"]["rh299_pagamento"]:$this->rh299_pagamento);
       $this->rh299_vrrendtrib = ($this->rh299_vrrendtrib == ""?@$GLOBALS["HTTP_POST_VARS"]["rh299_vrrendtrib"]:$this->rh299_vrrendtrib);
       $this->rh299_vrrendtrib13 = ($this->rh299_vrrendtrib13 == ""?@$GLOBALS["HTTP_POST_VARS"]["rh299_vrrendtrib13"]:$this->rh299_vrrendtrib13);
       $this->rh299_vrrendmolegrave = ($this->rh299_vrrendmolegrave == ""?@$GLOBALS["HTTP_POST_VARS"]["rh299_vrrendmolegrave"]:$this->rh299_vrrendmolegrave);
       $this->rh299_vrrendisen65 = ($this->rh299_vrrendisen65 == ""?@$GLOBALS["HTTP_POST_VARS"]["rh299_vrrendisen65"]:$this->rh299_vrrendisen65);
       $this->rh299_vrjurosmora = ($this->rh299_vrjurosmora == ""?@$GLOBALS["HTTP_POST_VARS"]["rh299_vrjurosmora"]:$this->rh299_vrjurosmora);
       $this->rh299_vrrendisenntrib = ($this->rh299_vrrendisenntrib == ""?@$GLOBALS["HTTP_POST_VARS"]["rh299_vrrendisenntrib"]:$this->rh299_vrrendisenntrib);
       $this->rh299_descisenntrib = ($this->rh299_descisenntrib == ""?@$GLOBALS["HTTP_POST_VARS"]["rh299_descisenntrib"]:$this->rh299_descisenntrib);
       $this->rh299_vrprevoficial = ($this->rh299_vrprevoficial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh299_vrprevoficial"]:$this->rh299_vrprevoficial);
       $this->rh299_descrra = ($this->rh299_descrra == ""?@$GLOBALS["HTTP_POST_VARS"]["rh299_descrra"]:$this->rh299_descrra);
       $this->rh299_qtdmesesrra = ($this->rh299_qtdmesesrra == ""?@$GLOBALS["HTTP_POST_VARS"]["rh299_qtdmesesrra"]:$this->rh299_qtdmesesrra);
       $this->rh299_vlrdespcustas = ($this->rh299_vlrdespcustas == ""?@$GLOBALS["HTTP_POST_VARS"]["rh299_vlrdespcustas"]:$this->rh299_vlrdespcustas);
       $this->rh299_vlrdespadvogados = ($this->rh299_vlrdespadvogados == ""?@$GLOBALS["HTTP_POST_VARS"]["rh299_vlrdespadvogados"]:$this->rh299_vlrdespadvogados);
     }else{
       $this->rh299_sequencial = ($this->rh299_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh299_sequencial"]:$this->rh299_sequencial);
     }
   }

    public function incluir($rh299_sequencial)
    {
      $this->atualizacampos();
     if($this->rh299_sequencialprocessoservidor == null ){ 
       $this->erro_sql = " Campo Identificação única de servidor não informado.";
       $this->erro_campo = "rh299_sequencialprocessoservidor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh299_tpcr == null ){ 
       $this->erro_sql = " Campo Relativo IRRF não informado.";
       $this->erro_campo = "rh299_tpcr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh299_vcr == null ){ 
       $this->rh299_vcr = "0";
     }
     if($this->rh299_vrrendtrib == null ){ 
       $this->rh299_vrrendtrib = "0";
     }
     if($this->rh299_vrrendtrib13 == null ){ 
       $this->rh299_vrrendtrib13 = "0";
     }
     if($this->rh299_vrrendmolegrave == null ){ 
       $this->rh299_vrrendmolegrave = "0";
     }
     if($this->rh299_vrrendisen65 == null ){ 
       $this->rh299_vrrendisen65 = "0";
     }
     if($this->rh299_vrjurosmora == null ){ 
       $this->rh299_vrjurosmora = "0";
     }
     if($this->rh299_vrrendisenntrib == null ){ 
       $this->rh299_vrrendisenntrib = "0";
     }
     if($this->rh299_vrprevoficial == null ){ 
       $this->rh299_vrprevoficial = "0";
     }
     if($this->rh299_qtdmesesrra == null ){ 
       $this->rh299_qtdmesesrra = "0";
     }
     if($this->rh299_vlrdespcustas == null ){ 
       $this->rh299_vlrdespcustas = "0";
     }
     if($this->rh299_vlrdespadvogados == null ){ 
       $this->rh299_vlrdespadvogados = "0";
     }
     if($rh299_sequencial == "" || $rh299_sequencial == null ){
       $result = db_query("select nextval('rhprocessotributoirrf_rh299_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhprocessotributoirrf_rh299_sequencial_seq do campo: rh299_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh299_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhprocessotributoirrf_rh299_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh299_sequencial)){
         $this->erro_sql = " Campo rh299_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh299_sequencial = $rh299_sequencial; 
       }
     }
     if(($this->rh299_sequencial == null) || ($this->rh299_sequencial == "") ){ 
       $this->erro_sql = " Campo rh299_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhprocessotributoirrf(
                                       rh299_sequencial 
                                      ,rh299_sequencialprocessoservidor 
                                      ,rh299_tpcr 
                                      ,rh299_vcr 
                                      ,rh299_pagamento 
                                      ,rh299_vrrendtrib 
                                      ,rh299_vrrendtrib13 
                                      ,rh299_vrrendmolegrave 
                                      ,rh299_vrrendisen65 
                                      ,rh299_vrjurosmora 
                                      ,rh299_vrrendisenntrib 
                                      ,rh299_descisenntrib 
                                      ,rh299_vrprevoficial 
                                      ,rh299_descrra 
                                      ,rh299_qtdmesesrra 
                                      ,rh299_vlrdespcustas 
                                      ,rh299_vlrdespadvogados 
                       )
                values (
                                $this->rh299_sequencial 
                               ,$this->rh299_sequencialprocessoservidor 
                               ,$this->rh299_tpcr 
                               ,$this->rh299_vcr 
                               ,'$this->rh299_pagamento' 
                               ,$this->rh299_vrrendtrib 
                               ,$this->rh299_vrrendtrib13 
                               ,$this->rh299_vrrendmolegrave 
                               ,$this->rh299_vrrendisen65 
                               ,$this->rh299_vrjurosmora 
                               ,$this->rh299_vrrendisenntrib 
                               ,'$this->rh299_descisenntrib' 
                               ,$this->rh299_vrprevoficial 
                               ,'$this->rh299_descrra' 
                               ,$this->rh299_qtdmesesrra 
                               ,$this->rh299_vlrdespcustas 
                               ,$this->rh299_vlrdespadvogados 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tributos IRRF ($this->rh299_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tributos IRRF já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tributos IRRF ($this->rh299_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh299_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh299_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1015195,'$this->rh299_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1011103,1015195,'','".AddSlashes(pg_result($resaco,0,'rh299_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011103,1015196,'','".AddSlashes(pg_result($resaco,0,'rh299_sequencialprocessoservidor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011103,1015197,'','".AddSlashes(pg_result($resaco,0,'rh299_tpcr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011103,1015198,'','".AddSlashes(pg_result($resaco,0,'rh299_vcr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011103,1015294,'','".AddSlashes(pg_result($resaco,0,'rh299_pagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011103,1015400,'','".AddSlashes(pg_result($resaco,0,'rh299_vrrendtrib'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011103,1015401,'','".AddSlashes(pg_result($resaco,0,'rh299_vrrendtrib13'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011103,1015402,'','".AddSlashes(pg_result($resaco,0,'rh299_vrrendmolegrave'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011103,1015403,'','".AddSlashes(pg_result($resaco,0,'rh299_vrrendisen65'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011103,1015404,'','".AddSlashes(pg_result($resaco,0,'rh299_vrjurosmora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011103,1015405,'','".AddSlashes(pg_result($resaco,0,'rh299_vrrendisenntrib'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011103,1015406,'','".AddSlashes(pg_result($resaco,0,'rh299_descisenntrib'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011103,1015407,'','".AddSlashes(pg_result($resaco,0,'rh299_vrprevoficial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011103,1015408,'','".AddSlashes(pg_result($resaco,0,'rh299_descrra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011103,1015409,'','".AddSlashes(pg_result($resaco,0,'rh299_qtdmesesrra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011103,1015410,'','".AddSlashes(pg_result($resaco,0,'rh299_vlrdespcustas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1011103,1015411,'','".AddSlashes(pg_result($resaco,0,'rh299_vlrdespadvogados'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh299_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhprocessotributoirrf set ";
     $virgula = "";
     if(trim($this->rh299_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh299_sequencial"])){ 
       $sql  .= $virgula." rh299_sequencial = $this->rh299_sequencial ";
       $virgula = ",";
       if(trim($this->rh299_sequencial) == null ){ 
         $this->erro_sql = " Campo Número Sequencial não informado.";
         $this->erro_campo = "rh299_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh299_sequencialprocessoservidor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh299_sequencialprocessoservidor"])){ 
       $sql  .= $virgula." rh299_sequencialprocessoservidor = $this->rh299_sequencialprocessoservidor ";
       $virgula = ",";
       if(trim($this->rh299_sequencialprocessoservidor) == null ){ 
         $this->erro_sql = " Campo Identificação única de servidor não informado.";
         $this->erro_campo = "rh299_sequencialprocessoservidor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh299_tpcr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh299_tpcr"])){ 
       $sql  .= $virgula." rh299_tpcr = $this->rh299_tpcr ";
       $virgula = ",";
       if(trim($this->rh299_tpcr) == null ){ 
         $this->erro_sql = " Campo Relativo IRRF não informado.";
         $this->erro_campo = "rh299_tpcr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh299_vcr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh299_vcr"])){ 
        if(trim($this->rh299_vcr)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh299_vcr"])){ 
           $this->rh299_vcr = "0" ; 
        } 
       $sql  .= $virgula." rh299_vcr = $this->rh299_vcr ";
       $virgula = ",";
     }
     if(trim($this->rh299_pagamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh299_pagamento"])){ 
       $sql  .= $virgula." rh299_pagamento = '$this->rh299_pagamento' ";
       $virgula = ",";
     }
     if(trim($this->rh299_vrrendtrib)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh299_vrrendtrib"])){ 
        if(trim($this->rh299_vrrendtrib)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh299_vrrendtrib"])){ 
           $this->rh299_vrrendtrib = "0" ; 
        } 
       $sql  .= $virgula." rh299_vrrendtrib = $this->rh299_vrrendtrib ";
       $virgula = ",";
     }
     if(trim($this->rh299_vrrendtrib13)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh299_vrrendtrib13"])){ 
        if(trim($this->rh299_vrrendtrib13)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh299_vrrendtrib13"])){ 
           $this->rh299_vrrendtrib13 = "0" ; 
        } 
       $sql  .= $virgula." rh299_vrrendtrib13 = $this->rh299_vrrendtrib13 ";
       $virgula = ",";
     }
     if(trim($this->rh299_vrrendmolegrave)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh299_vrrendmolegrave"])){ 
        if(trim($this->rh299_vrrendmolegrave)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh299_vrrendmolegrave"])){ 
           $this->rh299_vrrendmolegrave = "0" ; 
        } 
       $sql  .= $virgula." rh299_vrrendmolegrave = $this->rh299_vrrendmolegrave ";
       $virgula = ",";
     }
     if(trim($this->rh299_vrrendisen65)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh299_vrrendisen65"])){ 
        if(trim($this->rh299_vrrendisen65)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh299_vrrendisen65"])){ 
           $this->rh299_vrrendisen65 = "0" ; 
        } 
       $sql  .= $virgula." rh299_vrrendisen65 = $this->rh299_vrrendisen65 ";
       $virgula = ",";
     }
     if(trim($this->rh299_vrjurosmora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh299_vrjurosmora"])){ 
        if(trim($this->rh299_vrjurosmora)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh299_vrjurosmora"])){ 
           $this->rh299_vrjurosmora = "0" ; 
        } 
       $sql  .= $virgula." rh299_vrjurosmora = $this->rh299_vrjurosmora ";
       $virgula = ",";
     }
     if(trim($this->rh299_vrrendisenntrib)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh299_vrrendisenntrib"])){ 
        if(trim($this->rh299_vrrendisenntrib)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh299_vrrendisenntrib"])){ 
           $this->rh299_vrrendisenntrib = "0" ; 
        } 
       $sql  .= $virgula." rh299_vrrendisenntrib = $this->rh299_vrrendisenntrib ";
       $virgula = ",";
     }
     if(trim($this->rh299_descisenntrib)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh299_descisenntrib"])){ 
       $sql  .= $virgula." rh299_descisenntrib = '$this->rh299_descisenntrib' ";
       $virgula = ",";
     }
     if(trim($this->rh299_vrprevoficial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh299_vrprevoficial"])){ 
        if(trim($this->rh299_vrprevoficial)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh299_vrprevoficial"])){ 
           $this->rh299_vrprevoficial = "0" ; 
        } 
       $sql  .= $virgula." rh299_vrprevoficial = $this->rh299_vrprevoficial ";
       $virgula = ",";
     }
     if(trim($this->rh299_descrra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh299_descrra"])){ 
       $sql  .= $virgula." rh299_descrra = '$this->rh299_descrra' ";
       $virgula = ",";
     }
     if(trim($this->rh299_qtdmesesrra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh299_qtdmesesrra"])){ 
        if(trim($this->rh299_qtdmesesrra)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh299_qtdmesesrra"])){ 
           $this->rh299_qtdmesesrra = "0" ; 
        } 
       $sql  .= $virgula." rh299_qtdmesesrra = $this->rh299_qtdmesesrra ";
       $virgula = ",";
     }
     if(trim($this->rh299_vlrdespcustas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh299_vlrdespcustas"])){ 
        if(trim($this->rh299_vlrdespcustas)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh299_vlrdespcustas"])){ 
           $this->rh299_vlrdespcustas = "0" ; 
        } 
       $sql  .= $virgula." rh299_vlrdespcustas = $this->rh299_vlrdespcustas ";
       $virgula = ",";
     }
     if(trim($this->rh299_vlrdespadvogados)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh299_vlrdespadvogados"])){ 
        if(trim($this->rh299_vlrdespadvogados)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh299_vlrdespadvogados"])){ 
           $this->rh299_vlrdespadvogados = "0" ; 
        } 
       $sql  .= $virgula." rh299_vlrdespadvogados = $this->rh299_vlrdespadvogados ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh299_sequencial!=null){
       $sql .= " rh299_sequencial = $this->rh299_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh299_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1015195,'$this->rh299_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh299_sequencial"]) || $this->rh299_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1011103,1015195,'".AddSlashes(pg_result($resaco,$conresaco,'rh299_sequencial'))."','$this->rh299_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh299_sequencialprocessoservidor"]) || $this->rh299_sequencialprocessoservidor != "")
             $resac = db_query("insert into db_acount values($acount,1011103,1015196,'".AddSlashes(pg_result($resaco,$conresaco,'rh299_sequencialprocessoservidor'))."','$this->rh299_sequencialprocessoservidor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh299_tpcr"]) || $this->rh299_tpcr != "")
             $resac = db_query("insert into db_acount values($acount,1011103,1015197,'".AddSlashes(pg_result($resaco,$conresaco,'rh299_tpcr'))."','$this->rh299_tpcr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh299_vcr"]) || $this->rh299_vcr != "")
             $resac = db_query("insert into db_acount values($acount,1011103,1015198,'".AddSlashes(pg_result($resaco,$conresaco,'rh299_vcr'))."','$this->rh299_vcr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh299_pagamento"]) || $this->rh299_pagamento != "")
             $resac = db_query("insert into db_acount values($acount,1011103,1015294,'".AddSlashes(pg_result($resaco,$conresaco,'rh299_pagamento'))."','$this->rh299_pagamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh299_vrrendtrib"]) || $this->rh299_vrrendtrib != "")
             $resac = db_query("insert into db_acount values($acount,1011103,1015400,'".AddSlashes(pg_result($resaco,$conresaco,'rh299_vrrendtrib'))."','$this->rh299_vrrendtrib',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh299_vrrendtrib13"]) || $this->rh299_vrrendtrib13 != "")
             $resac = db_query("insert into db_acount values($acount,1011103,1015401,'".AddSlashes(pg_result($resaco,$conresaco,'rh299_vrrendtrib13'))."','$this->rh299_vrrendtrib13',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh299_vrrendmolegrave"]) || $this->rh299_vrrendmolegrave != "")
             $resac = db_query("insert into db_acount values($acount,1011103,1015402,'".AddSlashes(pg_result($resaco,$conresaco,'rh299_vrrendmolegrave'))."','$this->rh299_vrrendmolegrave',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh299_vrrendisen65"]) || $this->rh299_vrrendisen65 != "")
             $resac = db_query("insert into db_acount values($acount,1011103,1015403,'".AddSlashes(pg_result($resaco,$conresaco,'rh299_vrrendisen65'))."','$this->rh299_vrrendisen65',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh299_vrjurosmora"]) || $this->rh299_vrjurosmora != "")
             $resac = db_query("insert into db_acount values($acount,1011103,1015404,'".AddSlashes(pg_result($resaco,$conresaco,'rh299_vrjurosmora'))."','$this->rh299_vrjurosmora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh299_vrrendisenntrib"]) || $this->rh299_vrrendisenntrib != "")
             $resac = db_query("insert into db_acount values($acount,1011103,1015405,'".AddSlashes(pg_result($resaco,$conresaco,'rh299_vrrendisenntrib'))."','$this->rh299_vrrendisenntrib',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh299_descisenntrib"]) || $this->rh299_descisenntrib != "")
             $resac = db_query("insert into db_acount values($acount,1011103,1015406,'".AddSlashes(pg_result($resaco,$conresaco,'rh299_descisenntrib'))."','$this->rh299_descisenntrib',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh299_vrprevoficial"]) || $this->rh299_vrprevoficial != "")
             $resac = db_query("insert into db_acount values($acount,1011103,1015407,'".AddSlashes(pg_result($resaco,$conresaco,'rh299_vrprevoficial'))."','$this->rh299_vrprevoficial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh299_descrra"]) || $this->rh299_descrra != "")
             $resac = db_query("insert into db_acount values($acount,1011103,1015408,'".AddSlashes(pg_result($resaco,$conresaco,'rh299_descrra'))."','$this->rh299_descrra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh299_qtdmesesrra"]) || $this->rh299_qtdmesesrra != "")
             $resac = db_query("insert into db_acount values($acount,1011103,1015409,'".AddSlashes(pg_result($resaco,$conresaco,'rh299_qtdmesesrra'))."','$this->rh299_qtdmesesrra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh299_vlrdespcustas"]) || $this->rh299_vlrdespcustas != "")
             $resac = db_query("insert into db_acount values($acount,1011103,1015410,'".AddSlashes(pg_result($resaco,$conresaco,'rh299_vlrdespcustas'))."','$this->rh299_vlrdespcustas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh299_vlrdespadvogados"]) || $this->rh299_vlrdespadvogados != "")
             $resac = db_query("insert into db_acount values($acount,1011103,1015411,'".AddSlashes(pg_result($resaco,$conresaco,'rh299_vlrdespadvogados'))."','$this->rh299_vlrdespadvogados',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tributos IRRF não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh299_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tributos IRRF não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh299_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh299_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh299_sequencial=null, $dbwhere = null)
    {
     $sql = " delete from rhprocessotributoirrf
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh299_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh299_sequencial = $rh299_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tributos IRRF não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh299_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tributos IRRF não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh299_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh299_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhprocessotributoirrf";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh299_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhprocessotributoirrf ";
     $sql .= "      inner join rhpessoalprocessoservidor  on  rhpessoalprocessoservidor.rh271_sequencial = rhprocessotributoirrf.rh299_sequencialprocessoservidor";
     $sql .= "      inner join rhpessoalprocessojudicialesocial  on  rhpessoalprocessojudicialesocial.rh270_sequencial = rhpessoalprocessoservidor.rh271_sequencialprocesso";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh299_sequencial)) {
         $sql2 .= " where rhprocessotributoirrf.rh299_sequencial = $rh299_sequencial "; 
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

    public function sql_query_file($rh299_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhprocessotributoirrf ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh299_sequencial)){
         $sql2 .= " where rhprocessotributoirrf.rh299_sequencial = $rh299_sequencial "; 
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
