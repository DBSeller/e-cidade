<?php

class cl_cgmenderecoexterior
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
    public $z19_sequencial = 0; 
    public $z19_numcgm = 0; 
    public $z19_pais = 0; 
    public $z19_logradouro = null; 
    public $z19_numero = 0; 
    public $z19_complemento = null; 
    public $z19_bairro = null; 
    public $z19_cidade = null; 
    public $z19_codigopostal = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 z19_sequencial = int4 = Códidgo Sequencial 
                 z19_numcgm = int4 = Código do Cgm 
                 z19_pais = int4 = País 
                 z19_logradouro = varchar(100) = Logradouro 
                 z19_numero = int4 = Número 
                 z19_complemento = varchar(30) = Complemento 
                 z19_bairro = varchar(90) = Bairro 
                 z19_cidade = varchar(50) = Cidade 
                 z19_codigopostal = varchar(12) = Código Postal 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("cgmenderecoexterior"); 
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
       $this->z19_sequencial = ($this->z19_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["z19_sequencial"]:$this->z19_sequencial);
       $this->z19_numcgm = ($this->z19_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["z19_numcgm"]:$this->z19_numcgm);
       $this->z19_pais = ($this->z19_pais == ""?@$GLOBALS["HTTP_POST_VARS"]["z19_pais"]:$this->z19_pais);
       $this->z19_logradouro = ($this->z19_logradouro == ""?@$GLOBALS["HTTP_POST_VARS"]["z19_logradouro"]:$this->z19_logradouro);
       $this->z19_numero = ($this->z19_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["z19_numero"]:$this->z19_numero);
       $this->z19_complemento = ($this->z19_complemento == ""?@$GLOBALS["HTTP_POST_VARS"]["z19_complemento"]:$this->z19_complemento);
       $this->z19_bairro = ($this->z19_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["z19_bairro"]:$this->z19_bairro);
       $this->z19_cidade = ($this->z19_cidade == ""?@$GLOBALS["HTTP_POST_VARS"]["z19_cidade"]:$this->z19_cidade);
       $this->z19_codigopostal = ($this->z19_codigopostal == ""?@$GLOBALS["HTTP_POST_VARS"]["z19_codigopostal"]:$this->z19_codigopostal);
     }else{
       $this->z19_sequencial = ($this->z19_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["z19_sequencial"]:$this->z19_sequencial);
     }
   }

    public function incluir($z19_sequencial)
    {
      $this->atualizacampos();
     if($this->z19_numcgm == null ){ 
       $this->erro_sql = " Campo Código do Cgm não informado.";
       $this->erro_campo = "z19_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z19_pais == null ){ 
       $this->erro_sql = " Campo País não informado.";
       $this->erro_campo = "z19_pais";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z19_logradouro == null ){ 
       $this->erro_sql = " Campo Logradouro não informado.";
       $this->erro_campo = "z19_logradouro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z19_numero == null ){ 
       $this->z19_numero = "0";
     }
     if($this->z19_bairro == null ){ 
       $this->erro_sql = " Campo Bairro não informado.";
       $this->erro_campo = "z19_bairro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z19_cidade == null ){ 
       $this->erro_sql = " Campo Cidade não informado.";
       $this->erro_campo = "z19_cidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z19_codigopostal == null ){ 
       $this->erro_sql = " Campo Código Postal não informado.";
       $this->erro_campo = "z19_codigopostal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($z19_sequencial == "" || $z19_sequencial == null ){
       $result = db_query("select nextval('cgmenderecoexterior_z19_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cgmenderecoexterior_z19_sequencial_seq do campo: z19_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->z19_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cgmenderecoexterior_z19_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $z19_sequencial)){
         $this->erro_sql = " Campo z19_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->z19_sequencial = $z19_sequencial; 
       }
     }
     if(($this->z19_sequencial == null) || ($this->z19_sequencial == "") ){ 
       $this->erro_sql = " Campo z19_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cgmenderecoexterior(
                                       z19_sequencial 
                                      ,z19_numcgm 
                                      ,z19_pais 
                                      ,z19_logradouro 
                                      ,z19_numero 
                                      ,z19_complemento 
                                      ,z19_bairro 
                                      ,z19_cidade 
                                      ,z19_codigopostal 
                       )
                values (
                                $this->z19_sequencial 
                               ,$this->z19_numcgm 
                               ,$this->z19_pais 
                               ,'$this->z19_logradouro' 
                               ,$this->z19_numero 
                               ,'$this->z19_complemento' 
                               ,'$this->z19_bairro' 
                               ,'$this->z19_cidade' 
                               ,'$this->z19_codigopostal' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Endereço Exterior ($this->z19_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Endereço Exterior já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Endereço Exterior ($this->z19_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->z19_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->z19_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1013650,'$this->z19_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010855,1013650,'','".AddSlashes(pg_result($resaco,0,'z19_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010855,1013649,'','".AddSlashes(pg_result($resaco,0,'z19_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010855,1013642,'','".AddSlashes(pg_result($resaco,0,'z19_pais'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010855,1013643,'','".AddSlashes(pg_result($resaco,0,'z19_logradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010855,1013644,'','".AddSlashes(pg_result($resaco,0,'z19_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010855,1013645,'','".AddSlashes(pg_result($resaco,0,'z19_complemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010855,1013646,'','".AddSlashes(pg_result($resaco,0,'z19_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010855,1013647,'','".AddSlashes(pg_result($resaco,0,'z19_cidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010855,1013648,'','".AddSlashes(pg_result($resaco,0,'z19_codigopostal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($z19_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update cgmenderecoexterior set ";
     $virgula = "";
     if(trim($this->z19_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z19_sequencial"])){ 
       $sql  .= $virgula." z19_sequencial = $this->z19_sequencial ";
       $virgula = ",";
       if(trim($this->z19_sequencial) == null ){ 
         $this->erro_sql = " Campo Códidgo Sequencial não informado.";
         $this->erro_campo = "z19_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z19_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z19_numcgm"])){ 
       $sql  .= $virgula." z19_numcgm = $this->z19_numcgm ";
       $virgula = ",";
       if(trim($this->z19_numcgm) == null ){ 
         $this->erro_sql = " Campo Código do Cgm não informado.";
         $this->erro_campo = "z19_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z19_pais)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z19_pais"])){ 
       $sql  .= $virgula." z19_pais = $this->z19_pais ";
       $virgula = ",";
       if(trim($this->z19_pais) == null ){ 
         $this->erro_sql = " Campo País não informado.";
         $this->erro_campo = "z19_pais";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z19_logradouro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z19_logradouro"])){ 
       $sql  .= $virgula." z19_logradouro = '$this->z19_logradouro' ";
       $virgula = ",";
       if(trim($this->z19_logradouro) == null ){ 
         $this->erro_sql = " Campo Logradouro não informado.";
         $this->erro_campo = "z19_logradouro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z19_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z19_numero"])){ 
        if(trim($this->z19_numero)=="" && isset($GLOBALS["HTTP_POST_VARS"]["z19_numero"])){ 
           $this->z19_numero = "0" ; 
        } 
       $sql  .= $virgula." z19_numero = $this->z19_numero ";
       $virgula = ",";
     }
     if(trim($this->z19_complemento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z19_complemento"])){ 
       $sql  .= $virgula." z19_complemento = '$this->z19_complemento' ";
       $virgula = ",";
     }
     if(trim($this->z19_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z19_bairro"])){ 
       $sql  .= $virgula." z19_bairro = '$this->z19_bairro' ";
       $virgula = ",";
       if(trim($this->z19_bairro) == null ){ 
         $this->erro_sql = " Campo Bairro não informado.";
         $this->erro_campo = "z19_bairro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z19_cidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z19_cidade"])){ 
       $sql  .= $virgula." z19_cidade = '$this->z19_cidade' ";
       $virgula = ",";
       if(trim($this->z19_cidade) == null ){ 
         $this->erro_sql = " Campo Cidade não informado.";
         $this->erro_campo = "z19_cidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z19_codigopostal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z19_codigopostal"])){ 
       $sql  .= $virgula." z19_codigopostal = '$this->z19_codigopostal' ";
       $virgula = ",";
       if(trim($this->z19_codigopostal) == null ){ 
         $this->erro_sql = " Campo Código Postal não informado.";
         $this->erro_campo = "z19_codigopostal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($z19_sequencial!=null){
       $sql .= " z19_sequencial = $this->z19_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->z19_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1013650,'$this->z19_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z19_sequencial"]) || $this->z19_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010855,1013650,'".AddSlashes(pg_result($resaco,$conresaco,'z19_sequencial'))."','$this->z19_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z19_numcgm"]) || $this->z19_numcgm != "")
             $resac = db_query("insert into db_acount values($acount,1010855,1013649,'".AddSlashes(pg_result($resaco,$conresaco,'z19_numcgm'))."','$this->z19_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z19_pais"]) || $this->z19_pais != "")
             $resac = db_query("insert into db_acount values($acount,1010855,1013642,'".AddSlashes(pg_result($resaco,$conresaco,'z19_pais'))."','$this->z19_pais',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z19_logradouro"]) || $this->z19_logradouro != "")
             $resac = db_query("insert into db_acount values($acount,1010855,1013643,'".AddSlashes(pg_result($resaco,$conresaco,'z19_logradouro'))."','$this->z19_logradouro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z19_numero"]) || $this->z19_numero != "")
             $resac = db_query("insert into db_acount values($acount,1010855,1013644,'".AddSlashes(pg_result($resaco,$conresaco,'z19_numero'))."','$this->z19_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z19_complemento"]) || $this->z19_complemento != "")
             $resac = db_query("insert into db_acount values($acount,1010855,1013645,'".AddSlashes(pg_result($resaco,$conresaco,'z19_complemento'))."','$this->z19_complemento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z19_bairro"]) || $this->z19_bairro != "")
             $resac = db_query("insert into db_acount values($acount,1010855,1013646,'".AddSlashes(pg_result($resaco,$conresaco,'z19_bairro'))."','$this->z19_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z19_cidade"]) || $this->z19_cidade != "")
             $resac = db_query("insert into db_acount values($acount,1010855,1013647,'".AddSlashes(pg_result($resaco,$conresaco,'z19_cidade'))."','$this->z19_cidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z19_codigopostal"]) || $this->z19_codigopostal != "")
             $resac = db_query("insert into db_acount values($acount,1010855,1013648,'".AddSlashes(pg_result($resaco,$conresaco,'z19_codigopostal'))."','$this->z19_codigopostal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Endereço Exterior não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->z19_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Endereço Exterior não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->z19_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->z19_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($z19_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($z19_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1013650,'$z19_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010855,1013650,'','".AddSlashes(pg_result($resaco,$iresaco,'z19_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010855,1013649,'','".AddSlashes(pg_result($resaco,$iresaco,'z19_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010855,1013642,'','".AddSlashes(pg_result($resaco,$iresaco,'z19_pais'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010855,1013643,'','".AddSlashes(pg_result($resaco,$iresaco,'z19_logradouro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010855,1013644,'','".AddSlashes(pg_result($resaco,$iresaco,'z19_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010855,1013645,'','".AddSlashes(pg_result($resaco,$iresaco,'z19_complemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010855,1013646,'','".AddSlashes(pg_result($resaco,$iresaco,'z19_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010855,1013647,'','".AddSlashes(pg_result($resaco,$iresaco,'z19_cidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010855,1013648,'','".AddSlashes(pg_result($resaco,$iresaco,'z19_codigopostal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cgmenderecoexterior
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($z19_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " z19_sequencial = $z19_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Endereço Exterior não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$z19_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Endereço Exterior não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$z19_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$z19_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cgmenderecoexterior";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($z19_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from cgmenderecoexterior ";
     $sql .= " inner join cgm  on  cgm.z01_numcgm = cgmenderecoexterior.z19_numcgm";
     $sql .= " inner join cadenderpais  on  cadenderpais.db70_sequencial = cgmenderecoexterior.z19_pais";
     $sql .= "  left join cadenderpaissistema";
     $sql .= "    on cadenderpais.db70_sequencial = cadenderpaissistema.db135_db_cadenderpais";
     $sql .= "   and cadenderpaissistema.db135_db_sistemaexterno = 3";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($z19_sequencial)) {
         $sql2 .= " where cgmenderecoexterior.z19_sequencial = $z19_sequencial "; 
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

    public function sql_query_file($z19_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from cgmenderecoexterior ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($z19_sequencial)){
         $sql2 .= " where cgmenderecoexterior.z19_sequencial = $z19_sequencial "; 
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
