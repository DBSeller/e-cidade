<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBSeller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

class cl_profissionais
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
    public $fm15_codigo = 0; 
    public $fm15_nome = null; 
    public $fm15_cpf = null; 
    public $fm15_cbo = 0; 
    public $fm15_regprof = null; 
    public $fm15_orgaoemissor = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 fm15_codigo = int4 = Código do Profissional 
                 fm15_nome = varchar(255) = Nome do profissional 
                 fm15_cpf = varchar(11) = CPF do Profissional 
                 fm15_cbo = int4 = CBO do Profissional 
                 fm15_regprof = varchar(10) = Registro Profissional 
                 fm15_orgaoemissor = int4 = Órgão emissor do documento 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("profissionais"); 
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
       $this->fm15_codigo = ($this->fm15_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fm15_codigo"]:$this->fm15_codigo);
       $this->fm15_nome = ($this->fm15_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["fm15_nome"]:$this->fm15_nome);
       $this->fm15_cpf = ($this->fm15_cpf == ""?@$GLOBALS["HTTP_POST_VARS"]["fm15_cpf"]:$this->fm15_cpf);
       $this->fm15_cbo = ($this->fm15_cbo == ""?@$GLOBALS["HTTP_POST_VARS"]["fm15_cbo"]:$this->fm15_cbo);
       $this->fm15_regprof = ($this->fm15_regprof == ""?@$GLOBALS["HTTP_POST_VARS"]["fm15_regprof"]:$this->fm15_regprof);
       $this->fm15_orgaoemissor = ($this->fm15_orgaoemissor == ""?@$GLOBALS["HTTP_POST_VARS"]["fm15_orgaoemissor"]:$this->fm15_orgaoemissor);
     }else{
       $this->fm15_codigo = ($this->fm15_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fm15_codigo"]:$this->fm15_codigo);
     }
   }

    public function incluir($fm15_codigo)
    {
      $this->atualizacampos();
     if($this->fm15_nome == null ){ 
       $this->erro_sql = " Campo Nome do profissional não informado.";
       $this->erro_campo = "fm15_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fm15_regprof == null ){ 
       $this->erro_sql = " Campo Registro Profissional não informado.";
       $this->erro_campo = "fm15_regprof";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fm15_orgaoemissor == null ){ 
       $this->erro_sql = " Campo Órgão emissor do documento não informado.";
       $this->erro_campo = "fm15_orgaoemissor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($fm15_codigo == "" || $fm15_codigo == null ){
       $result = db_query("select nextval('profissionais_fm15_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: profissionais_fm15_codigo_seq do campo: fm15_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->fm15_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from profissionais_fm15_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $fm15_codigo)){
         $this->erro_sql = " Campo fm15_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->fm15_codigo = $fm15_codigo; 
       }
     }
     if(($this->fm15_codigo == null) || ($this->fm15_codigo == "") ){ 
       $this->erro_sql = " Campo fm15_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if ($this->fm15_cbo == null) {
        $this->fm15_cbo = 0;
     }
     $sql = "insert into profissionais(
                                       fm15_codigo
                                      ,fm15_nome
                                      ,fm15_cpf
                                      ,fm15_cbo
                                      ,fm15_regprof
                                      ,fm15_orgaoemissor
                       )
                values (
                                $this->fm15_codigo
                               ,'$this->fm15_nome'
                               ,".($this->fm15_cpf == "null" || $this->fm15_cpf == ""?"null":"'".$this->fm15_cpf."'")."
                               ,$this->fm15_cbo
                               ,'$this->fm15_regprof'
                               ,$this->fm15_orgaoemissor
                      )";
     $result = db_query($sql); 
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"profissionais_cpf_uk") != 0 ){
           $this->erro_sql   = "Profissional ($this->fm15_codigo) não Incluído. Inclusão Abortada.";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_banco = "CPF $this->fm15_cpf já Cadastrado";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       if( strpos(strtolower($this->erro_banco),"profissionais_regprof_orgaoemissor_uk") != 0 ){
         $this->erro_sql   = "Profissional ($this->fm15_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Registro Profissional $this->fm15_regprof já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Profissional ($this->fm15_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Profissional já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Profissional ($this->fm15_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir = 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
     $this->erro_sql .= "Valores : ".$this->fm15_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->fm15_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,208423752,'$this->fm15_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,286218508,208423752,'','".AddSlashes(pg_result($resaco,0,'fm15_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,286218508,111543608,'','".AddSlashes(pg_result($resaco,0,'fm15_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,286218508,316809961,'','".AddSlashes(pg_result($resaco,0,'fm15_cpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,286218508,84969924,'','".AddSlashes(pg_result($resaco,0,'fm15_cbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,286218508,141215223,'','".AddSlashes(pg_result($resaco,0,'fm15_regprof'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,286218508,259654509,'','".AddSlashes(pg_result($resaco,0,'fm15_orgaoemissor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($fm15_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update profissionais set ";
     $virgula = "";
     if(trim($this->fm15_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fm15_codigo"])){ 
       $sql  .= $virgula." fm15_codigo = $this->fm15_codigo ";
       $virgula = ",";
       if(trim($this->fm15_codigo) == null ){ 
         $this->erro_sql = " Campo Código do Profissional não informado.";
         $this->erro_campo = "fm15_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fm15_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fm15_nome"])){ 
       $sql  .= $virgula." fm15_nome = '$this->fm15_nome' ";
       $virgula = ",";
       if(trim($this->fm15_nome) == null ){ 
         $this->erro_sql = " Campo Nome do profissional não informado.";
         $this->erro_campo = "fm15_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fm15_cpf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fm15_cpf"])){ 
       $sql  .= $virgula." fm15_cpf = ".($this->fm15_cpf == "null" || $this->fm15_cpf == ""?"null":"'".$this->fm15_cpf."'");
       $virgula = ",";
     }
     if(trim($this->fm15_cbo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fm15_cbo"])){ 
        if(trim($this->fm15_cbo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["fm15_cbo"])){ 
           $this->fm15_cbo = "0" ; 
        } 
       $sql  .= $virgula." fm15_cbo = $this->fm15_cbo ";
       $virgula = ",";
     }
     if(trim($this->fm15_regprof)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fm15_regprof"])){ 
       $sql  .= $virgula." fm15_regprof = '$this->fm15_regprof' ";
       $virgula = ",";
       if(trim($this->fm15_regprof) == null ){ 
         $this->erro_sql = " Campo Registro Profissional não informado.";
         $this->erro_campo = "fm15_regprof";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fm15_orgaoemissor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fm15_orgaoemissor"])){ 
       $sql  .= $virgula." fm15_orgaoemissor = $this->fm15_orgaoemissor ";
       $virgula = ",";
       if(trim($this->fm15_orgaoemissor) == null ){ 
         $this->erro_sql = " Campo Órgão emissor do documento não informado.";
         $this->erro_campo = "fm15_orgaoemissor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($fm15_codigo!=null){
       $sql .= " fm15_codigo = $this->fm15_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->fm15_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,208423752,'$this->fm15_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fm15_codigo"]) || $this->fm15_codigo != "")
             $resac = db_query("insert into db_acount values($acount,286218508,208423752,'".AddSlashes(pg_result($resaco,$conresaco,'fm15_codigo'))."','$this->fm15_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fm15_nome"]) || $this->fm15_nome != "")
             $resac = db_query("insert into db_acount values($acount,286218508,111543608,'".AddSlashes(pg_result($resaco,$conresaco,'fm15_nome'))."','$this->fm15_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fm15_cpf"]) || $this->fm15_cpf != "")
             $resac = db_query("insert into db_acount values($acount,286218508,316809961,'".AddSlashes(pg_result($resaco,$conresaco,'fm15_cpf'))."','$this->fm15_cpf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fm15_cbo"]) || $this->fm15_cbo != "")
             $resac = db_query("insert into db_acount values($acount,286218508,84969924,'".AddSlashes(pg_result($resaco,$conresaco,'fm15_cbo'))."','$this->fm15_cbo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fm15_regprof"]) || $this->fm15_regprof != "")
             $resac = db_query("insert into db_acount values($acount,286218508,141215223,'".AddSlashes(pg_result($resaco,$conresaco,'fm15_regprof'))."','$this->fm15_regprof',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fm15_orgaoemissor"]) || $this->fm15_orgaoemissor != "")
             $resac = db_query("insert into db_acount values($acount,286218508,259654509,'".AddSlashes(pg_result($resaco,$conresaco,'fm15_orgaoemissor'))."','$this->fm15_orgaoemissor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "profissionais não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->fm15_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "profissionais não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fm15_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->fm15_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($fm15_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($fm15_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,208423752,'$fm15_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,286218508,208423752,'','".AddSlashes(pg_result($resaco,$iresaco,'fm15_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,286218508,111543608,'','".AddSlashes(pg_result($resaco,$iresaco,'fm15_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,286218508,316809961,'','".AddSlashes(pg_result($resaco,$iresaco,'fm15_cpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,286218508,84969924,'','".AddSlashes(pg_result($resaco,$iresaco,'fm15_cbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,286218508,141215223,'','".AddSlashes(pg_result($resaco,$iresaco,'fm15_regprof'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,286218508,259654509,'','".AddSlashes(pg_result($resaco,$iresaco,'fm15_orgaoemissor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from profissionais
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($fm15_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " fm15_codigo = $fm15_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "profissionais não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fm15_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "profissionais não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fm15_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$fm15_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:profissionais";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($fm15_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from profissionais ";
     $sql .= "      left  join rhcbo  on  rhcbo.rh70_sequencial = profissionais.fm15_cbo";
     $sql .= "      inner join sau_orgaoemissor  on  sau_orgaoemissor.sd51_i_codigo = profissionais.fm15_orgaoemissor";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($fm15_codigo)) {
         $sql2 .= " where profissionais.fm15_codigo = $fm15_codigo "; 
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

    public function sql_query_file($fm15_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from profissionais ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($fm15_codigo)){
         $sql2 .= " where profissionais.fm15_codigo = $fm15_codigo "; 
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
