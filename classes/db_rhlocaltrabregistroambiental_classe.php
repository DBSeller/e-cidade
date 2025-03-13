<?php

class cl_rhlocaltrabregistroambiental
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
    public $rh258_sequencial = 0; 
    public $rh258_rhlocaltrab = 0; 
    public $rh258_instituicao = 0; 
    public $rh258_cpfresponsavel = null; 
    public $rh258_identificacaoorgao = 0; 
    public $rh258_numeroinscricaoorgao = null; 
    public $rh258_descricaoorgao = null; 
    public $rh258_uforgao = null; 
    public $rh258_periodoinicial_dia = null; 
    public $rh258_periodoinicial_mes = null; 
    public $rh258_periodoinicial_ano = null; 
    public $rh258_periodoinicial = null; 
    public $rh258_periodofinal_dia = null; 
    public $rh258_periodofinal_mes = null; 
    public $rh258_periodofinal_ano = null; 
    public $rh258_periodofinal = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh258_sequencial = int4 = Sequencial 
                 rh258_rhlocaltrab = int4 = Local de trabalho 
                 rh258_instituicao = int4 = Instituição 
                 rh258_cpfresponsavel = varchar(11) = Cpf responsável 
                 rh258_identificacaoorgao = int4 = Identificação do Orgão 
                 rh258_numeroinscricaoorgao = varchar(14) = Número de inscrição no órgão 
                 rh258_descricaoorgao = varchar(20) = Descrição (sigla) órgão 
                 rh258_uforgao = varchar(2) = UF do órgão de classe 
                 rh258_periodoinicial = date = Período avaliação inicial 
                 rh258_periodofinal = date = Período avaliação final 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhlocaltrabregistroambiental"); 
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
       $this->rh258_sequencial = ($this->rh258_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh258_sequencial"]:$this->rh258_sequencial);
       $this->rh258_rhlocaltrab = ($this->rh258_rhlocaltrab == ""?@$GLOBALS["HTTP_POST_VARS"]["rh258_rhlocaltrab"]:$this->rh258_rhlocaltrab);
       $this->rh258_instituicao = ($this->rh258_instituicao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh258_instituicao"]:$this->rh258_instituicao);
       $this->rh258_cpfresponsavel = ($this->rh258_cpfresponsavel == ""?@$GLOBALS["HTTP_POST_VARS"]["rh258_cpfresponsavel"]:$this->rh258_cpfresponsavel);
       $this->rh258_identificacaoorgao = ($this->rh258_identificacaoorgao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh258_identificacaoorgao"]:$this->rh258_identificacaoorgao);
       $this->rh258_numeroinscricaoorgao = ($this->rh258_numeroinscricaoorgao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh258_numeroinscricaoorgao"]:$this->rh258_numeroinscricaoorgao);
       $this->rh258_descricaoorgao = ($this->rh258_descricaoorgao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh258_descricaoorgao"]:$this->rh258_descricaoorgao);
       $this->rh258_uforgao = ($this->rh258_uforgao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh258_uforgao"]:$this->rh258_uforgao);
       if($this->rh258_periodoinicial == ""){
         $this->rh258_periodoinicial_dia = ($this->rh258_periodoinicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh258_periodoinicial_dia"]:$this->rh258_periodoinicial_dia);
         $this->rh258_periodoinicial_mes = ($this->rh258_periodoinicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh258_periodoinicial_mes"]:$this->rh258_periodoinicial_mes);
         $this->rh258_periodoinicial_ano = ($this->rh258_periodoinicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh258_periodoinicial_ano"]:$this->rh258_periodoinicial_ano);
         if($this->rh258_periodoinicial_dia != ""){
            $this->rh258_periodoinicial = $this->rh258_periodoinicial_ano."-".$this->rh258_periodoinicial_mes."-".$this->rh258_periodoinicial_dia;
         }
       }
       if($this->rh258_periodofinal == ""){
         $this->rh258_periodofinal_dia = ($this->rh258_periodofinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh258_periodofinal_dia"]:$this->rh258_periodofinal_dia);
         $this->rh258_periodofinal_mes = ($this->rh258_periodofinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh258_periodofinal_mes"]:$this->rh258_periodofinal_mes);
         $this->rh258_periodofinal_ano = ($this->rh258_periodofinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh258_periodofinal_ano"]:$this->rh258_periodofinal_ano);
         if($this->rh258_periodofinal_dia != ""){
            $this->rh258_periodofinal = $this->rh258_periodofinal_ano."-".$this->rh258_periodofinal_mes."-".$this->rh258_periodofinal_dia;
         }
       }
     }else{
       $this->rh258_sequencial = ($this->rh258_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh258_sequencial"]:$this->rh258_sequencial);
     }
   }

    public function incluir($rh258_sequencial)
    {
      $this->atualizacampos();
     if($this->rh258_rhlocaltrab == null ){ 
       $this->erro_sql = " Campo Local de trabalho não informado.";
       $this->erro_campo = "rh258_rhlocaltrab";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh258_instituicao == null ){ 
       $this->erro_sql = " Campo Instituição não informado.";
       $this->erro_campo = "rh258_instituicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh258_cpfresponsavel == null ){ 
       $this->erro_sql = " Campo Cpf responsável não informado.";
       $this->erro_campo = "rh258_cpfresponsavel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh258_identificacaoorgao == null ){ 
       $this->erro_sql = " Campo Identificação do Orgão não informado.";
       $this->erro_campo = "rh258_identificacaoorgao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh258_numeroinscricaoorgao == null ){ 
       $this->erro_sql = " Campo Número de inscrição no órgão não informado.";
       $this->erro_campo = "rh258_numeroinscricaoorgao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh258_uforgao == null ){ 
       $this->erro_sql = " Campo UF do órgão de classe não informado.";
       $this->erro_campo = "rh258_uforgao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh258_periodoinicial == null ){ 
       $this->erro_sql = " Campo Período avaliação inicial não informado.";
       $this->erro_campo = "rh258_periodoinicial_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh258_periodofinal == null ){ 
       $this->rh258_periodofinal = "null";
     }
     if($rh258_sequencial == "" || $rh258_sequencial == null ){
       $result = db_query("select nextval('rhlocaltrabregistroambiental_rh258_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhlocaltrabregistroambiental_rh258_sequencial_seq do campo: rh258_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh258_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhlocaltrabregistroambiental_rh258_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh258_sequencial)){
         $this->erro_sql = " Campo rh258_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh258_sequencial = $rh258_sequencial; 
       }
     }
     if(($this->rh258_sequencial == null) || ($this->rh258_sequencial == "") ){ 
       $this->erro_sql = " Campo rh258_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhlocaltrabregistroambiental(
                                       rh258_sequencial 
                                      ,rh258_rhlocaltrab 
                                      ,rh258_instituicao 
                                      ,rh258_cpfresponsavel 
                                      ,rh258_identificacaoorgao 
                                      ,rh258_numeroinscricaoorgao 
                                      ,rh258_descricaoorgao 
                                      ,rh258_uforgao 
                                      ,rh258_periodoinicial 
                                      ,rh258_periodofinal 
                       )
                values (
                                $this->rh258_sequencial 
                               ,$this->rh258_rhlocaltrab 
                               ,$this->rh258_instituicao 
                               ,'$this->rh258_cpfresponsavel' 
                               ,$this->rh258_identificacaoorgao 
                               ,'$this->rh258_numeroinscricaoorgao' 
                               ,'$this->rh258_descricaoorgao' 
                               ,'$this->rh258_uforgao' 
                               ,".($this->rh258_periodoinicial == "null" || $this->rh258_periodoinicial == ""?"null":"'".$this->rh258_periodoinicial."'")." 
                               ,".($this->rh258_periodofinal == "null" || $this->rh258_periodofinal == ""?"null":"'".$this->rh258_periodofinal."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Registros ambientais do local de trabalho ($this->rh258_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Registros ambientais do local de trabalho já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Registros ambientais do local de trabalho ($this->rh258_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh258_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh258_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1013692,'$this->rh258_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010860,1013692,'','".AddSlashes(pg_result($resaco,0,'rh258_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010860,1013693,'','".AddSlashes(pg_result($resaco,0,'rh258_rhlocaltrab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010860,1013694,'','".AddSlashes(pg_result($resaco,0,'rh258_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010860,1013695,'','".AddSlashes(pg_result($resaco,0,'rh258_cpfresponsavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010860,1013696,'','".AddSlashes(pg_result($resaco,0,'rh258_identificacaoorgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010860,1013697,'','".AddSlashes(pg_result($resaco,0,'rh258_numeroinscricaoorgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010860,1013698,'','".AddSlashes(pg_result($resaco,0,'rh258_descricaoorgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010860,1013699,'','".AddSlashes(pg_result($resaco,0,'rh258_uforgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010860,1013700,'','".AddSlashes(pg_result($resaco,0,'rh258_periodoinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010860,1013701,'','".AddSlashes(pg_result($resaco,0,'rh258_periodofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh258_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhlocaltrabregistroambiental set ";
     $virgula = "";
     if(trim($this->rh258_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh258_sequencial"])){ 
       $sql  .= $virgula." rh258_sequencial = $this->rh258_sequencial ";
       $virgula = ",";
       if(trim($this->rh258_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "rh258_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh258_rhlocaltrab)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh258_rhlocaltrab"])){ 
       $sql  .= $virgula." rh258_rhlocaltrab = $this->rh258_rhlocaltrab ";
       $virgula = ",";
       if(trim($this->rh258_rhlocaltrab) == null ){ 
         $this->erro_sql = " Campo Local de trabalho não informado.";
         $this->erro_campo = "rh258_rhlocaltrab";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh258_instituicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh258_instituicao"])){ 
       $sql  .= $virgula." rh258_instituicao = $this->rh258_instituicao ";
       $virgula = ",";
       if(trim($this->rh258_instituicao) == null ){ 
         $this->erro_sql = " Campo Instituição não informado.";
         $this->erro_campo = "rh258_instituicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh258_cpfresponsavel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh258_cpfresponsavel"])){ 
       $sql  .= $virgula." rh258_cpfresponsavel = '$this->rh258_cpfresponsavel' ";
       $virgula = ",";
       if(trim($this->rh258_cpfresponsavel) == null ){ 
         $this->erro_sql = " Campo Cpf responsável não informado.";
         $this->erro_campo = "rh258_cpfresponsavel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh258_identificacaoorgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh258_identificacaoorgao"])){ 
       $sql  .= $virgula." rh258_identificacaoorgao = $this->rh258_identificacaoorgao ";
       $virgula = ",";
       if(trim($this->rh258_identificacaoorgao) == null ){ 
         $this->erro_sql = " Campo Identificação do Orgão não informado.";
         $this->erro_campo = "rh258_identificacaoorgao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh258_numeroinscricaoorgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh258_numeroinscricaoorgao"])){ 
       $sql  .= $virgula." rh258_numeroinscricaoorgao = '$this->rh258_numeroinscricaoorgao' ";
       $virgula = ",";
       if(trim($this->rh258_numeroinscricaoorgao) == null ){ 
         $this->erro_sql = " Campo Número de inscrição no órgão não informado.";
         $this->erro_campo = "rh258_numeroinscricaoorgao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh258_descricaoorgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh258_descricaoorgao"])){ 
       $sql  .= $virgula." rh258_descricaoorgao = '$this->rh258_descricaoorgao' ";
       $virgula = ",";
     }
     if(trim($this->rh258_uforgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh258_uforgao"])){ 
       $sql  .= $virgula." rh258_uforgao = '$this->rh258_uforgao' ";
       $virgula = ",";
       if(trim($this->rh258_uforgao) == null ){ 
         $this->erro_sql = " Campo UF do órgão de classe não informado.";
         $this->erro_campo = "rh258_uforgao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh258_periodoinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh258_periodoinicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh258_periodoinicial_dia"] !="") ){ 
       $sql  .= $virgula." rh258_periodoinicial = '$this->rh258_periodoinicial' ";
       $virgula = ",";
       if(trim($this->rh258_periodoinicial) == null ){ 
         $this->erro_sql = " Campo Período avaliação inicial não informado.";
         $this->erro_campo = "rh258_periodoinicial_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh258_periodoinicial_dia"])){ 
         $sql  .= $virgula." rh258_periodoinicial = null ";
         $virgula = ",";
         if(trim($this->rh258_periodoinicial) == null ){ 
           $this->erro_sql = " Campo Período avaliação inicial não informado.";
           $this->erro_campo = "rh258_periodoinicial_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->rh258_periodofinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh258_periodofinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh258_periodofinal_dia"] !="") ){ 
       $sql  .= $virgula." rh258_periodofinal = '$this->rh258_periodofinal' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh258_periodofinal_dia"])){ 
         $sql  .= $virgula." rh258_periodofinal = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($rh258_sequencial!=null){
       $sql .= " rh258_sequencial = $this->rh258_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh258_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1013692,'$this->rh258_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh258_sequencial"]) || $this->rh258_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010860,1013692,'".AddSlashes(pg_result($resaco,$conresaco,'rh258_sequencial'))."','$this->rh258_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh258_rhlocaltrab"]) || $this->rh258_rhlocaltrab != "")
             $resac = db_query("insert into db_acount values($acount,1010860,1013693,'".AddSlashes(pg_result($resaco,$conresaco,'rh258_rhlocaltrab'))."','$this->rh258_rhlocaltrab',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh258_instituicao"]) || $this->rh258_instituicao != "")
             $resac = db_query("insert into db_acount values($acount,1010860,1013694,'".AddSlashes(pg_result($resaco,$conresaco,'rh258_instituicao'))."','$this->rh258_instituicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh258_cpfresponsavel"]) || $this->rh258_cpfresponsavel != "")
             $resac = db_query("insert into db_acount values($acount,1010860,1013695,'".AddSlashes(pg_result($resaco,$conresaco,'rh258_cpfresponsavel'))."','$this->rh258_cpfresponsavel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh258_identificacaoorgao"]) || $this->rh258_identificacaoorgao != "")
             $resac = db_query("insert into db_acount values($acount,1010860,1013696,'".AddSlashes(pg_result($resaco,$conresaco,'rh258_identificacaoorgao'))."','$this->rh258_identificacaoorgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh258_numeroinscricaoorgao"]) || $this->rh258_numeroinscricaoorgao != "")
             $resac = db_query("insert into db_acount values($acount,1010860,1013697,'".AddSlashes(pg_result($resaco,$conresaco,'rh258_numeroinscricaoorgao'))."','$this->rh258_numeroinscricaoorgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh258_descricaoorgao"]) || $this->rh258_descricaoorgao != "")
             $resac = db_query("insert into db_acount values($acount,1010860,1013698,'".AddSlashes(pg_result($resaco,$conresaco,'rh258_descricaoorgao'))."','$this->rh258_descricaoorgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh258_uforgao"]) || $this->rh258_uforgao != "")
             $resac = db_query("insert into db_acount values($acount,1010860,1013699,'".AddSlashes(pg_result($resaco,$conresaco,'rh258_uforgao'))."','$this->rh258_uforgao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh258_periodoinicial"]) || $this->rh258_periodoinicial != "")
             $resac = db_query("insert into db_acount values($acount,1010860,1013700,'".AddSlashes(pg_result($resaco,$conresaco,'rh258_periodoinicial'))."','$this->rh258_periodoinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh258_periodofinal"]) || $this->rh258_periodofinal != "")
             $resac = db_query("insert into db_acount values($acount,1010860,1013701,'".AddSlashes(pg_result($resaco,$conresaco,'rh258_periodofinal'))."','$this->rh258_periodofinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registros ambientais do local de trabalho não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh258_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Registros ambientais do local de trabalho não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh258_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh258_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh258_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh258_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1013692,'$rh258_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010860,1013692,'','".AddSlashes(pg_result($resaco,$iresaco,'rh258_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010860,1013693,'','".AddSlashes(pg_result($resaco,$iresaco,'rh258_rhlocaltrab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010860,1013694,'','".AddSlashes(pg_result($resaco,$iresaco,'rh258_instituicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010860,1013695,'','".AddSlashes(pg_result($resaco,$iresaco,'rh258_cpfresponsavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010860,1013696,'','".AddSlashes(pg_result($resaco,$iresaco,'rh258_identificacaoorgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010860,1013697,'','".AddSlashes(pg_result($resaco,$iresaco,'rh258_numeroinscricaoorgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010860,1013698,'','".AddSlashes(pg_result($resaco,$iresaco,'rh258_descricaoorgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010860,1013699,'','".AddSlashes(pg_result($resaco,$iresaco,'rh258_uforgao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010860,1013700,'','".AddSlashes(pg_result($resaco,$iresaco,'rh258_periodoinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010860,1013701,'','".AddSlashes(pg_result($resaco,$iresaco,'rh258_periodofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhlocaltrabregistroambiental
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh258_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh258_sequencial = $rh258_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registros ambientais do local de trabalho não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh258_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Registros ambientais do local de trabalho não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh258_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh258_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhlocaltrabregistroambiental";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh258_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhlocaltrabregistroambiental ";
     $sql .= "      inner join rhlocaltrab  on  rhlocaltrab.rh55_codigo = rhlocaltrabregistroambiental.rh258_rhlocaltrab and  rhlocaltrab.rh55_instit = rhlocaltrabregistroambiental.rh258_instituicao";
     $sql .= "      inner join db_config  on  db_config.codigo = rhlocaltrab.rh55_instit";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh258_sequencial)) {
         $sql2 .= " where rhlocaltrabregistroambiental.rh258_sequencial = $rh258_sequencial "; 
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

    public function sql_query_file($rh258_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhlocaltrabregistroambiental ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh258_sequencial)){
         $sql2 .= " where rhlocaltrabregistroambiental.rh258_sequencial = $rh258_sequencial "; 
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
