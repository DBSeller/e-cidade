<?
//MODULO: meioambiente
//CLASSE DA ENTIDADE licencaempreendimento
class cl_licencaempreendimento { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $am08_sequencial = 0; 
   var $am08_empreendimento = 0; 
   var $am08_protprocesso = 0; 
   var $am08_licencaanterior = 0; 
   var $am08_dataemissao_dia = null; 
   var $am08_dataemissao_mes = null; 
   var $am08_dataemissao_ano = null; 
   var $am08_dataemissao = null; 
   var $am08_datavencimento_dia = null; 
   var $am08_datavencimento_mes = null; 
   var $am08_datavencimento_ano = null; 
   var $am08_datavencimento = null; 
   var $am08_tipolicenca = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 am08_sequencial = int4 = Cod. Licença 
                 am08_empreendimento = int4 = Empreendimento 
                 am08_protprocesso = int4 = Protocolo 
                 am08_licencaanterior = int4 = Licença Anterior 
                 am08_dataemissao = date = Data de Emissão 
                 am08_datavencimento = date = Data de Vencimento 
                 am08_tipolicenca = int4 = Tipo da Licença 
                 ";
   //funcao construtor da classe 
   function cl_licencaempreendimento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("licencaempreendimento"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->am08_sequencial = ($this->am08_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_sequencial"]:$this->am08_sequencial);
       $this->am08_empreendimento = ($this->am08_empreendimento == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_empreendimento"]:$this->am08_empreendimento);
       $this->am08_protprocesso = ($this->am08_protprocesso == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_protprocesso"]:$this->am08_protprocesso);
       $this->am08_licencaanterior = ($this->am08_licencaanterior == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_licencaanterior"]:$this->am08_licencaanterior);
       if($this->am08_dataemissao == ""){
         $this->am08_dataemissao_dia = ($this->am08_dataemissao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_dataemissao_dia"]:$this->am08_dataemissao_dia);
         $this->am08_dataemissao_mes = ($this->am08_dataemissao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_dataemissao_mes"]:$this->am08_dataemissao_mes);
         $this->am08_dataemissao_ano = ($this->am08_dataemissao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_dataemissao_ano"]:$this->am08_dataemissao_ano);
         if($this->am08_dataemissao_dia != ""){
            $this->am08_dataemissao = $this->am08_dataemissao_ano."-".$this->am08_dataemissao_mes."-".$this->am08_dataemissao_dia;
         }
       }
       if($this->am08_datavencimento == ""){
         $this->am08_datavencimento_dia = ($this->am08_datavencimento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_datavencimento_dia"]:$this->am08_datavencimento_dia);
         $this->am08_datavencimento_mes = ($this->am08_datavencimento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_datavencimento_mes"]:$this->am08_datavencimento_mes);
         $this->am08_datavencimento_ano = ($this->am08_datavencimento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_datavencimento_ano"]:$this->am08_datavencimento_ano);
         if($this->am08_datavencimento_dia != ""){
            $this->am08_datavencimento = $this->am08_datavencimento_ano."-".$this->am08_datavencimento_mes."-".$this->am08_datavencimento_dia;
         }
       }
       $this->am08_tipolicenca = ($this->am08_tipolicenca == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_tipolicenca"]:$this->am08_tipolicenca);
     }else{
       $this->am08_sequencial = ($this->am08_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am08_sequencial"]:$this->am08_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($am08_sequencial){ 
      $this->atualizacampos();
     if($this->am08_empreendimento == null ){ 
       $this->erro_sql = " Campo Empreendimento não informado.";
       $this->erro_campo = "am08_empreendimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am08_protprocesso == null ){ 
       $this->erro_sql = " Campo Protocolo não informado.";
       $this->erro_campo = "am08_protprocesso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am08_licencaanterior == null ){ 
       $this->am08_licencaanterior = "0";
     }
     if($this->am08_dataemissao == null ){ 
       $this->erro_sql = " Campo Data de Emissão não informado.";
       $this->erro_campo = "am08_dataemissao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am08_datavencimento == null ){ 
       $this->erro_sql = " Campo Data de Vencimento não informado.";
       $this->erro_campo = "am08_datavencimento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am08_tipolicenca == null ){ 
       $this->erro_sql = " Campo Tipo da Licença não informado.";
       $this->erro_campo = "am08_tipolicenca";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($am08_sequencial == "" || $am08_sequencial == null ){
       $result = db_query("select nextval('licencaempreendimento_am08_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: licencaempreendimento_am08_sequencial_seq do campo: am08_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->am08_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from licencaempreendimento_am08_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $am08_sequencial)){
         $this->erro_sql = " Campo am08_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->am08_sequencial = $am08_sequencial; 
       }
     }
     if(($this->am08_sequencial == null) || ($this->am08_sequencial == "") ){ 
       $this->erro_sql = " Campo am08_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into licencaempreendimento(
                                       am08_sequencial 
                                      ,am08_empreendimento 
                                      ,am08_protprocesso 
                                      ,am08_licencaanterior 
                                      ,am08_dataemissao 
                                      ,am08_datavencimento 
                                      ,am08_tipolicenca 
                       )
                values (
                                $this->am08_sequencial 
                               ,$this->am08_empreendimento 
                               ,$this->am08_protprocesso 
                               ,$this->am08_licencaanterior 
                               ,".($this->am08_dataemissao == "null" || $this->am08_dataemissao == ""?"null":"'".$this->am08_dataemissao."'")." 
                               ,".($this->am08_datavencimento == "null" || $this->am08_datavencimento == ""?"null":"'".$this->am08_datavencimento."'")." 
                               ,$this->am08_tipolicenca 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Emissao de Licenças ($this->am08_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Emissao de Licenças já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Emissao de Licenças ($this->am08_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am08_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am08_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20805,'$this->am08_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3744,20805,'','".AddSlashes(pg_result($resaco,0,'am08_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3744,20806,'','".AddSlashes(pg_result($resaco,0,'am08_empreendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3744,20807,'','".AddSlashes(pg_result($resaco,0,'am08_protprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3744,20808,'','".AddSlashes(pg_result($resaco,0,'am08_licencaanterior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3744,20809,'','".AddSlashes(pg_result($resaco,0,'am08_dataemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3744,20810,'','".AddSlashes(pg_result($resaco,0,'am08_datavencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3744,20811,'','".AddSlashes(pg_result($resaco,0,'am08_tipolicenca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($am08_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update licencaempreendimento set ";
     $virgula = "";
     if(trim($this->am08_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am08_sequencial"])){ 
       $sql  .= $virgula." am08_sequencial = $this->am08_sequencial ";
       $virgula = ",";
       if(trim($this->am08_sequencial) == null ){ 
         $this->erro_sql = " Campo Cod. Licença não informado.";
         $this->erro_campo = "am08_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am08_empreendimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am08_empreendimento"])){ 
       $sql  .= $virgula." am08_empreendimento = $this->am08_empreendimento ";
       $virgula = ",";
       if(trim($this->am08_empreendimento) == null ){ 
         $this->erro_sql = " Campo Empreendimento não informado.";
         $this->erro_campo = "am08_empreendimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am08_protprocesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am08_protprocesso"])){ 
       $sql  .= $virgula." am08_protprocesso = $this->am08_protprocesso ";
       $virgula = ",";
       if(trim($this->am08_protprocesso) == null ){ 
         $this->erro_sql = " Campo Protocolo não informado.";
         $this->erro_campo = "am08_protprocesso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am08_licencaanterior)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am08_licencaanterior"])){ 
        if(trim($this->am08_licencaanterior)=="" && isset($GLOBALS["HTTP_POST_VARS"]["am08_licencaanterior"])){ 
           $this->am08_licencaanterior = "0" ; 
        } 
       $sql  .= $virgula." am08_licencaanterior = $this->am08_licencaanterior ";
       $virgula = ",";
     }
     if(trim($this->am08_dataemissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am08_dataemissao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["am08_dataemissao_dia"] !="") ){ 
       $sql  .= $virgula." am08_dataemissao = '$this->am08_dataemissao' ";
       $virgula = ",";
       if(trim($this->am08_dataemissao) == null ){ 
         $this->erro_sql = " Campo Data de Emissão não informado.";
         $this->erro_campo = "am08_dataemissao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["am08_dataemissao_dia"])){ 
         $sql  .= $virgula." am08_dataemissao = null ";
         $virgula = ",";
         if(trim($this->am08_dataemissao) == null ){ 
           $this->erro_sql = " Campo Data de Emissão não informado.";
           $this->erro_campo = "am08_dataemissao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->am08_datavencimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am08_datavencimento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["am08_datavencimento_dia"] !="") ){ 
       $sql  .= $virgula." am08_datavencimento = '$this->am08_datavencimento' ";
       $virgula = ",";
       if(trim($this->am08_datavencimento) == null ){ 
         $this->erro_sql = " Campo Data de Vencimento não informado.";
         $this->erro_campo = "am08_datavencimento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["am08_datavencimento_dia"])){ 
         $sql  .= $virgula." am08_datavencimento = null ";
         $virgula = ",";
         if(trim($this->am08_datavencimento) == null ){ 
           $this->erro_sql = " Campo Data de Vencimento não informado.";
           $this->erro_campo = "am08_datavencimento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->am08_tipolicenca)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am08_tipolicenca"])){ 
       $sql  .= $virgula." am08_tipolicenca = $this->am08_tipolicenca ";
       $virgula = ",";
       if(trim($this->am08_tipolicenca) == null ){ 
         $this->erro_sql = " Campo Tipo da Licença não informado.";
         $this->erro_campo = "am08_tipolicenca";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($am08_sequencial!=null){
       $sql .= " am08_sequencial = $this->am08_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am08_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20805,'$this->am08_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am08_sequencial"]) || $this->am08_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3744,20805,'".AddSlashes(pg_result($resaco,$conresaco,'am08_sequencial'))."','$this->am08_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am08_empreendimento"]) || $this->am08_empreendimento != "")
             $resac = db_query("insert into db_acount values($acount,3744,20806,'".AddSlashes(pg_result($resaco,$conresaco,'am08_empreendimento'))."','$this->am08_empreendimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am08_protprocesso"]) || $this->am08_protprocesso != "")
             $resac = db_query("insert into db_acount values($acount,3744,20807,'".AddSlashes(pg_result($resaco,$conresaco,'am08_protprocesso'))."','$this->am08_protprocesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am08_licencaanterior"]) || $this->am08_licencaanterior != "")
             $resac = db_query("insert into db_acount values($acount,3744,20808,'".AddSlashes(pg_result($resaco,$conresaco,'am08_licencaanterior'))."','$this->am08_licencaanterior',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am08_dataemissao"]) || $this->am08_dataemissao != "")
             $resac = db_query("insert into db_acount values($acount,3744,20809,'".AddSlashes(pg_result($resaco,$conresaco,'am08_dataemissao'))."','$this->am08_dataemissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am08_datavencimento"]) || $this->am08_datavencimento != "")
             $resac = db_query("insert into db_acount values($acount,3744,20810,'".AddSlashes(pg_result($resaco,$conresaco,'am08_datavencimento'))."','$this->am08_datavencimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am08_tipolicenca"]) || $this->am08_tipolicenca != "")
             $resac = db_query("insert into db_acount values($acount,3744,20811,'".AddSlashes(pg_result($resaco,$conresaco,'am08_tipolicenca'))."','$this->am08_tipolicenca',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Emissao de Licenças nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->am08_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Emissao de Licenças nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->am08_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am08_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($am08_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($am08_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20805,'$am08_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3744,20805,'','".AddSlashes(pg_result($resaco,$iresaco,'am08_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3744,20806,'','".AddSlashes(pg_result($resaco,$iresaco,'am08_empreendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3744,20807,'','".AddSlashes(pg_result($resaco,$iresaco,'am08_protprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3744,20808,'','".AddSlashes(pg_result($resaco,$iresaco,'am08_licencaanterior'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3744,20809,'','".AddSlashes(pg_result($resaco,$iresaco,'am08_dataemissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3744,20810,'','".AddSlashes(pg_result($resaco,$iresaco,'am08_datavencimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3744,20811,'','".AddSlashes(pg_result($resaco,$iresaco,'am08_tipolicenca'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from licencaempreendimento
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($am08_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " am08_sequencial = $am08_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Emissao de Licenças nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$am08_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Emissao de Licenças nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$am08_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$am08_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   public function sql_record($sql) { 
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
        $this->erro_sql   = "Record Vazio na Tabela:licencaempreendimento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($am08_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from licencaempreendimento ";
     $sql .= "      inner join protprocesso  on  protprocesso.p58_codproc = licencaempreendimento.am08_protprocesso";
     $sql .= "      inner join empreendimento  on  empreendimento.am05_sequencial = licencaempreendimento.am08_empreendimento";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = protprocesso.p58_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = protprocesso.p58_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = protprocesso.p58_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = protprocesso.p58_coddepto";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = protprocesso.p58_codigo";
     $sql .= "      inner join bairro  on  bairro.j13_codi = empreendimento.am05_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = empreendimento.am05_ruas";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = empreendimento.am05_cgm";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am08_sequencial)) {
         $sql2 .= " where licencaempreendimento.am08_sequencial = $am08_sequencial "; 
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
   // funcao do sql 
   public function sql_query_file ($am08_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from licencaempreendimento ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am08_sequencial)){
         $sql2 .= " where licencaempreendimento.am08_sequencial = $am08_sequencial "; 
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
