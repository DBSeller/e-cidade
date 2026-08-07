<?php

class cl_parprojetos
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
    public $ob21_anousu = 0; 
    public $ob21_numeracaohabite = 0; 
    public $ob21_ultnumerohabite = 0; 
    public $ob21_grupotipoocupacao = 0; 
    public $ob21_grupotipoconstrucao = 0; 
    public $ob21_grupotipolancamento = 0; 
    public $ob21_tipocartaalvara = 0; 
    public $ob21_tipocartahabite = 0; 
    public $ob21_localcertificadoa1 = null; 
    public $ob21_senhacertificadoa1 = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 ob21_anousu = int4 = Exercício 
                 ob21_numeracaohabite = int4 = Numeração Habite-se 
                 ob21_ultnumerohabite = int4 = Número Habite-se 
                 ob21_grupotipoocupacao = int4 = Grupo Tipo Ocupação 
                 ob21_grupotipoconstrucao = int4 = Grupo Tipo Construção 
                 ob21_grupotipolancamento = int4 = Grupo Tipo Lançamento 
                 ob21_tipocartaalvara = int4 = Tipo Carta Alvará 
                 ob21_tipocartahabite = int4 = Tipo Carta Habite-se 
                 ob21_localcertificadoa1 = text = Local Certificado A1 
                 ob21_senhacertificadoa1 = text = Senha Certificado A1 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("parprojetos"); 
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
       $this->ob21_anousu = ($this->ob21_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["ob21_anousu"]:$this->ob21_anousu);
       $this->ob21_numeracaohabite = ($this->ob21_numeracaohabite == ""?@$GLOBALS["HTTP_POST_VARS"]["ob21_numeracaohabite"]:$this->ob21_numeracaohabite);
       $this->ob21_ultnumerohabite = ($this->ob21_ultnumerohabite == ""?@$GLOBALS["HTTP_POST_VARS"]["ob21_ultnumerohabite"]:$this->ob21_ultnumerohabite);
       $this->ob21_grupotipoocupacao = ($this->ob21_grupotipoocupacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ob21_grupotipoocupacao"]:$this->ob21_grupotipoocupacao);
       $this->ob21_grupotipoconstrucao = ($this->ob21_grupotipoconstrucao == ""?@$GLOBALS["HTTP_POST_VARS"]["ob21_grupotipoconstrucao"]:$this->ob21_grupotipoconstrucao);
       $this->ob21_grupotipolancamento = ($this->ob21_grupotipolancamento == ""?@$GLOBALS["HTTP_POST_VARS"]["ob21_grupotipolancamento"]:$this->ob21_grupotipolancamento);
       $this->ob21_tipocartaalvara = ($this->ob21_tipocartaalvara == ""?@$GLOBALS["HTTP_POST_VARS"]["ob21_tipocartaalvara"]:$this->ob21_tipocartaalvara);
       $this->ob21_tipocartahabite = ($this->ob21_tipocartahabite == ""?@$GLOBALS["HTTP_POST_VARS"]["ob21_tipocartahabite"]:$this->ob21_tipocartahabite);
       $this->ob21_localcertificadoa1 = ($this->ob21_localcertificadoa1 == ""?@$GLOBALS["HTTP_POST_VARS"]["ob21_localcertificadoa1"]:$this->ob21_localcertificadoa1);
       $this->ob21_senhacertificadoa1 = ($this->ob21_senhacertificadoa1 == ""?@$GLOBALS["HTTP_POST_VARS"]["ob21_senhacertificadoa1"]:$this->ob21_senhacertificadoa1);
     }else{
       $this->ob21_anousu = ($this->ob21_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["ob21_anousu"]:$this->ob21_anousu);
     }
   }

    public function incluir($ob21_anousu)
    {
      $this->atualizacampos();
     if($this->ob21_numeracaohabite == null ){ 
       $this->erro_sql = " Campo Numeração Habite-se não informado.";
       $this->erro_campo = "ob21_numeracaohabite";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob21_ultnumerohabite == null ){ 
       $this->erro_sql = " Campo Número Habite-se não informado.";
       $this->erro_campo = "ob21_ultnumerohabite";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob21_grupotipoocupacao == null ){ 
       $this->erro_sql = " Campo Grupo Tipo Ocupação não informado.";
       $this->erro_campo = "ob21_grupotipoocupacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob21_grupotipoconstrucao == null ){ 
       $this->erro_sql = " Campo Grupo Tipo Construção não informado.";
       $this->erro_campo = "ob21_grupotipoconstrucao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob21_grupotipolancamento == null ){ 
       $this->erro_sql = " Campo Grupo Tipo Lançamento não informado.";
       $this->erro_campo = "ob21_grupotipolancamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob21_tipocartaalvara == null ){ 
       $this->erro_sql = " Campo Tipo Carta Alvará não informado.";
       $this->erro_campo = "ob21_tipocartaalvara";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob21_tipocartahabite == null ){ 
       $this->erro_sql = " Campo Tipo Carta Habite-se não informado.";
       $this->erro_campo = "ob21_tipocartahabite";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob21_senhacertificadoa1 == null ){ 
       $this->ob21_senhacertificadoa1 = "0";
     }
       $this->ob21_anousu = $ob21_anousu; 
     if(($this->ob21_anousu == null) || ($this->ob21_anousu == "") ){ 
       $this->erro_sql = " Campo ob21_anousu não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into parprojetos(
                                       ob21_anousu 
                                      ,ob21_numeracaohabite 
                                      ,ob21_ultnumerohabite 
                                      ,ob21_grupotipoocupacao 
                                      ,ob21_grupotipoconstrucao 
                                      ,ob21_grupotipolancamento 
                                      ,ob21_tipocartaalvara 
                                      ,ob21_tipocartahabite 
                                      ,ob21_localcertificadoa1 
                                      ,ob21_senhacertificadoa1 
                       )
                values (
                                $this->ob21_anousu 
                               ,$this->ob21_numeracaohabite 
                               ,$this->ob21_ultnumerohabite 
                               ,$this->ob21_grupotipoocupacao 
                               ,$this->ob21_grupotipoconstrucao 
                               ,$this->ob21_grupotipolancamento 
                               ,$this->ob21_tipocartaalvara 
                               ,$this->ob21_tipocartahabite 
                               ,'$this->ob21_localcertificadoa1' 
                               ,'$this->ob21_senhacertificadoa1' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Parametros do Módulo Projetos ($this->ob21_anousu) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Parametros do Módulo Projetos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Parametros do Módulo Projetos ($this->ob21_anousu) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ob21_anousu;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ob21_anousu  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11870,'$this->ob21_anousu','I')");
         $resac = db_query("insert into db_acount values($acount,2051,11870,'','".AddSlashes(pg_result($resaco,0,'ob21_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2051,11872,'','".AddSlashes(pg_result($resaco,0,'ob21_numeracaohabite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2051,11873,'','".AddSlashes(pg_result($resaco,0,'ob21_ultnumerohabite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2051,18648,'','".AddSlashes(pg_result($resaco,0,'ob21_grupotipoocupacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2051,18649,'','".AddSlashes(pg_result($resaco,0,'ob21_grupotipoconstrucao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2051,18650,'','".AddSlashes(pg_result($resaco,0,'ob21_grupotipolancamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2051,18651,'','".AddSlashes(pg_result($resaco,0,'ob21_tipocartaalvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2051,18652,'','".AddSlashes(pg_result($resaco,0,'ob21_tipocartahabite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2051,1013146,'','".AddSlashes(pg_result($resaco,0,'ob21_localcertificadoa1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2051,1013147,'','".AddSlashes(pg_result($resaco,0,'ob21_senhacertificadoa1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($ob21_anousu=null)
    {
      $this->atualizacampos();
     $sql = " update parprojetos set ";
     $virgula = "";
     if(trim($this->ob21_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob21_anousu"])){ 
       $sql  .= $virgula." ob21_anousu = $this->ob21_anousu ";
       $virgula = ",";
       if(trim($this->ob21_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício não informado.";
         $this->erro_campo = "ob21_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob21_numeracaohabite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob21_numeracaohabite"])){ 
       $sql  .= $virgula." ob21_numeracaohabite = $this->ob21_numeracaohabite ";
       $virgula = ",";
       if(trim($this->ob21_numeracaohabite) == null ){ 
         $this->erro_sql = " Campo Numeração Habite-se não informado.";
         $this->erro_campo = "ob21_numeracaohabite";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob21_ultnumerohabite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob21_ultnumerohabite"])){ 
       $sql  .= $virgula." ob21_ultnumerohabite = $this->ob21_ultnumerohabite ";
       $virgula = ",";
       if(trim($this->ob21_ultnumerohabite) == null ){ 
         $this->erro_sql = " Campo Número Habite-se não informado.";
         $this->erro_campo = "ob21_ultnumerohabite";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob21_grupotipoocupacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob21_grupotipoocupacao"])){ 
       $sql  .= $virgula." ob21_grupotipoocupacao = $this->ob21_grupotipoocupacao ";
       $virgula = ",";
       if(trim($this->ob21_grupotipoocupacao) == null ){ 
         $this->erro_sql = " Campo Grupo Tipo Ocupação não informado.";
         $this->erro_campo = "ob21_grupotipoocupacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob21_grupotipoconstrucao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob21_grupotipoconstrucao"])){ 
       $sql  .= $virgula." ob21_grupotipoconstrucao = $this->ob21_grupotipoconstrucao ";
       $virgula = ",";
       if(trim($this->ob21_grupotipoconstrucao) == null ){ 
         $this->erro_sql = " Campo Grupo Tipo Construção não informado.";
         $this->erro_campo = "ob21_grupotipoconstrucao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob21_grupotipolancamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob21_grupotipolancamento"])){ 
       $sql  .= $virgula." ob21_grupotipolancamento = $this->ob21_grupotipolancamento ";
       $virgula = ",";
       if(trim($this->ob21_grupotipolancamento) == null ){ 
         $this->erro_sql = " Campo Grupo Tipo Lançamento não informado.";
         $this->erro_campo = "ob21_grupotipolancamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob21_tipocartaalvara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob21_tipocartaalvara"])){ 
       $sql  .= $virgula." ob21_tipocartaalvara = $this->ob21_tipocartaalvara ";
       $virgula = ",";
       if(trim($this->ob21_tipocartaalvara) == null ){ 
         $this->erro_sql = " Campo Tipo Carta Alvará não informado.";
         $this->erro_campo = "ob21_tipocartaalvara";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob21_tipocartahabite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob21_tipocartahabite"])){ 
       $sql  .= $virgula." ob21_tipocartahabite = $this->ob21_tipocartahabite ";
       $virgula = ",";
       if(trim($this->ob21_tipocartahabite) == null ){ 
         $this->erro_sql = " Campo Tipo Carta Habite-se não informado.";
         $this->erro_campo = "ob21_tipocartahabite";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob21_localcertificadoa1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob21_localcertificadoa1"])){ 
       $sql  .= $virgula." ob21_localcertificadoa1 = '$this->ob21_localcertificadoa1' ";
       $virgula = ",";
     }
     if(trim($this->ob21_senhacertificadoa1)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob21_senhacertificadoa1"])){ 
       $sql  .= $virgula." ob21_senhacertificadoa1 = '$this->ob21_senhacertificadoa1' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ob21_anousu!=null){
       $sql .= " ob21_anousu = $this->ob21_anousu";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ob21_anousu));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,11870,'$this->ob21_anousu','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ob21_anousu"]) || $this->ob21_anousu != "")
             $resac = db_query("insert into db_acount values($acount,2051,11870,'".AddSlashes(pg_result($resaco,$conresaco,'ob21_anousu'))."','$this->ob21_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ob21_numeracaohabite"]) || $this->ob21_numeracaohabite != "")
             $resac = db_query("insert into db_acount values($acount,2051,11872,'".AddSlashes(pg_result($resaco,$conresaco,'ob21_numeracaohabite'))."','$this->ob21_numeracaohabite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ob21_ultnumerohabite"]) || $this->ob21_ultnumerohabite != "")
             $resac = db_query("insert into db_acount values($acount,2051,11873,'".AddSlashes(pg_result($resaco,$conresaco,'ob21_ultnumerohabite'))."','$this->ob21_ultnumerohabite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ob21_grupotipoocupacao"]) || $this->ob21_grupotipoocupacao != "")
             $resac = db_query("insert into db_acount values($acount,2051,18648,'".AddSlashes(pg_result($resaco,$conresaco,'ob21_grupotipoocupacao'))."','$this->ob21_grupotipoocupacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ob21_grupotipoconstrucao"]) || $this->ob21_grupotipoconstrucao != "")
             $resac = db_query("insert into db_acount values($acount,2051,18649,'".AddSlashes(pg_result($resaco,$conresaco,'ob21_grupotipoconstrucao'))."','$this->ob21_grupotipoconstrucao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ob21_grupotipolancamento"]) || $this->ob21_grupotipolancamento != "")
             $resac = db_query("insert into db_acount values($acount,2051,18650,'".AddSlashes(pg_result($resaco,$conresaco,'ob21_grupotipolancamento'))."','$this->ob21_grupotipolancamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ob21_tipocartaalvara"]) || $this->ob21_tipocartaalvara != "")
             $resac = db_query("insert into db_acount values($acount,2051,18651,'".AddSlashes(pg_result($resaco,$conresaco,'ob21_tipocartaalvara'))."','$this->ob21_tipocartaalvara',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ob21_tipocartahabite"]) || $this->ob21_tipocartahabite != "")
             $resac = db_query("insert into db_acount values($acount,2051,18652,'".AddSlashes(pg_result($resaco,$conresaco,'ob21_tipocartahabite'))."','$this->ob21_tipocartahabite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ob21_localcertificadoa1"]) || $this->ob21_localcertificadoa1 != "")
             $resac = db_query("insert into db_acount values($acount,2051,1013146,'".AddSlashes(pg_result($resaco,$conresaco,'ob21_localcertificadoa1'))."','$this->ob21_localcertificadoa1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ob21_senhacertificadoa1"]) || $this->ob21_senhacertificadoa1 != "")
             $resac = db_query("insert into db_acount values($acount,2051,1013147,'".AddSlashes(pg_result($resaco,$conresaco,'ob21_senhacertificadoa1'))."','$this->ob21_senhacertificadoa1',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros do Módulo Projetos não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob21_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Parametros do Módulo Projetos não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob21_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ob21_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($ob21_anousu=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ob21_anousu));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,11870,'$ob21_anousu','E')");
           $resac  = db_query("insert into db_acount values($acount,2051,11870,'','".AddSlashes(pg_result($resaco,$iresaco,'ob21_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2051,11872,'','".AddSlashes(pg_result($resaco,$iresaco,'ob21_numeracaohabite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2051,11873,'','".AddSlashes(pg_result($resaco,$iresaco,'ob21_ultnumerohabite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2051,18648,'','".AddSlashes(pg_result($resaco,$iresaco,'ob21_grupotipoocupacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2051,18649,'','".AddSlashes(pg_result($resaco,$iresaco,'ob21_grupotipoconstrucao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2051,18650,'','".AddSlashes(pg_result($resaco,$iresaco,'ob21_grupotipolancamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2051,18651,'','".AddSlashes(pg_result($resaco,$iresaco,'ob21_tipocartaalvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2051,18652,'','".AddSlashes(pg_result($resaco,$iresaco,'ob21_tipocartahabite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2051,1013146,'','".AddSlashes(pg_result($resaco,$iresaco,'ob21_localcertificadoa1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2051,1013147,'','".AddSlashes(pg_result($resaco,$iresaco,'ob21_senhacertificadoa1'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from parprojetos
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ob21_anousu)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ob21_anousu = $ob21_anousu ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros do Módulo Projetos não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ob21_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Parametros do Módulo Projetos não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ob21_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ob21_anousu;
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
        $this->erro_sql   = "Record Vazio na Tabela:parprojetos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($ob21_anousu = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from parprojetos ";
     $sql .= "      inner join cargrup  on  cargrup.j32_grupo = parprojetos.ob21_grupotipoocupacao and  cargrup.j32_grupo = parprojetos.ob21_grupotipoconstrucao and  cargrup.j32_grupo = parprojetos.ob21_grupotipolancamento";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ob21_anousu)) {
         $sql2 .= " where parprojetos.ob21_anousu = $ob21_anousu "; 
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

    public function sql_query_file($ob21_anousu = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from parprojetos ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ob21_anousu)){
         $sql2 .= " where parprojetos.ob21_anousu = $ob21_anousu "; 
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

   /**
   * Busca os Parâmetros do Ano base
   * @param integer $iAnoBase
   */
  function sql_query_pesquisaParametros($iAnoBase, $sCampos = "*") {
  	
  	$sSql = "select {$sCampos},                                                                                                 ";
    $sSql.= "       grupo_ocupacao  .j32_grupo as ocupacao_codigo,                                                              ";
  	$sSql.= "       grupo_ocupacao  .j32_descr as ocupacao_descricao,                                                           ";
    $sSql.= "       grupo_construcao.j32_grupo as construcao_codigo,                                                            ";
    $sSql.= "       grupo_construcao.j32_descr as construcao_descricao,                                                         ";
    $sSql.= "       grupo_lancamento.j32_grupo as lancamento_codigo,                                                            ";
    $sSql.= "       grupo_lancamento.j32_descr as lancamento_descricao                                                          ";
  	$sSql.= "  from parprojetos                                                                                                 ";
  	$sSql.= "       inner join cargrup as grupo_ocupacao   on grupo_ocupacao.j32_grupo   = parprojetos.ob21_grupotipoocupacao   ";
  	$sSql.= "       inner join cargrup as grupo_construcao on grupo_construcao.j32_grupo = parprojetos.ob21_grupotipoconstrucao ";
    $sSql.= "       inner join cargrup as grupo_lancamento on grupo_lancamento.j32_grupo = parprojetos.ob21_grupotipolancamento ";
  	$sSql.= " where ob21_anousu = {$iAnoBase}                                                                                   ";
  	return $sSql;
  }
}
