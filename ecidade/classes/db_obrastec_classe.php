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

//MODULO: projetos
//CLASSE DA ENTIDADE obrastec
class cl_obrastec { 
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
    public $ob15_sequencial = 0; 
    public $ob15_numcgm = 0; 
    public $ob15_crea = null; 
    public $ob15_tipo = 0; 
    public $ob15_profissao = 0; 
    public $ob15_datalimite_dia = null; 
    public $ob15_datalimite_mes = null; 
    public $ob15_datalimite_ano = null; 
    public $ob15_datalimite = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 ob15_sequencial = int4 = Sequencial 
                 ob15_numcgm = int4 = Numcgm 
                 ob15_crea = varchar(20) = CREA 
                 ob15_tipo = int4 = Tipo 
                 ob15_profissao = int 4 = Referencia a tabela obrastecprofissao
                 ob15_datalimite = date = Data Limite
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("obrastec"); 
        $this->pagina_retorno = basename($_SERVER['PHP_SELF']);
    }

    public function erro($mostra, $retorna)
    {
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }

    public function atualizacampos($exclusao = false)
    {
     if($exclusao==false){
       $this->ob15_sequencial = ($this->ob15_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ob15_sequencial"]:$this->ob15_sequencial);
       $this->ob15_numcgm = ($this->ob15_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["ob15_numcgm"]:$this->ob15_numcgm);
       $this->ob15_crea = ($this->ob15_crea == ""?@$GLOBALS["HTTP_POST_VARS"]["ob15_crea"]:$this->ob15_crea);
       $this->ob15_tipo = ($this->ob15_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ob15_tipo"]:$this->ob15_tipo);
       $this->ob15_profissao = ($this->ob15_profissao == ""?@$GLOBALS["HTTP_POST_VARS"]["ob15_profissao"]:$this->ob15_profissao);
       if($this->ob15_datalimite == ""){
         $this->ob15_datalimite_dia = ($this->ob15_datalimite_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ob15_datalimite_dia"]:$this->ob15_datalimite_dia);
         $this->ob15_datalimite_mes = ($this->ob15_datalimite_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ob15_datalimite_mes"]:$this->ob15_datalimite_mes);
         $this->ob15_datalimite_ano = ($this->ob15_datalimite_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ob15_datalimite_ano"]:$this->ob15_datalimite_ano);
         if($this->ob15_datalimite_dia != ""){
            $this->ob15_datalimite = $this->ob15_datalimite_ano."-".$this->ob15_datalimite_mes."-".$this->ob15_datalimite_dia;
         }
       }
     }else{
       $this->ob15_sequencial = ($this->ob15_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ob15_sequencial"]:$this->ob15_sequencial);
     }
   }

    public function incluir($ob15_sequencial)
    {
      $this->atualizacampos();
     if($this->ob15_numcgm == null ){ 
       $this->erro_sql = " Campo Numcgm nao Informado.";
       $this->erro_campo = "ob15_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob15_crea == null ){ 
      $this->erro_sql = " Campo CREA/CAU nao Informado.";
      $this->erro_campo = "ob15_crea";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
     if($this->ob15_tipo == null ){ 
       $this->erro_sql = " Campo Tipo não informado.";
       $this->erro_campo = "ob15_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ob15_profissao == null ){ 
      $this->erro_sql = " Campo Profissão nao Informado.";
      $this->erro_campo = "ob15_profissao";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
    
     if($ob15_sequencial == "" || $ob15_sequencial == null ){
       $result = db_query("select nextval('obrastec_ob15_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: obrastec_ob15_sequencial_seq do campo: ob15_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ob15_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from obrastec_ob15_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ob15_sequencial)){
         $this->erro_sql = " Campo ob15_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ob15_sequencial = $ob15_sequencial; 
       }
     }
     if(($this->ob15_sequencial == null) || ($this->ob15_sequencial == "") ){ 
       $this->erro_sql = " Campo ob15_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into obrastec(
                                       ob15_sequencial 
                                      ,ob15_numcgm 
                                      ,ob15_crea 
                                      ,ob15_tipo 
                                      ,ob15_profissao 
                                      ,ob15_datalimite 
                       )
                values (
                                $this->ob15_sequencial 
                               ,$this->ob15_numcgm 
                               ,'$this->ob15_crea' 
                               ,$this->ob15_tipo 
                               ,$this->ob15_profissao 
                               ,".($this->ob15_datalimite == "null" || $this->ob15_datalimite == ""?"null":"'".$this->ob15_datalimite."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tecnicos autorizados com crea ($this->ob15_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tecnicos autorizados com crea já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tecnicos autorizados com crea ($this->ob15_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ob15_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ob15_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11384,'$this->ob15_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1001,11384,'','".AddSlashes(pg_result($resaco,0,'ob15_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1001,6191,'','".AddSlashes(pg_result($resaco,0,'ob15_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1001,6192,'','".AddSlashes(pg_result($resaco,0,'ob15_crea'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1001,11860,'','".AddSlashes(pg_result($resaco,0,'ob15_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1001,1013242,'','".AddSlashes(pg_result($resaco,0,'ob15_profissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1001,1014619,'','".AddSlashes(pg_result($resaco,0,'ob15_datalimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($ob15_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update obrastec set ";
     $virgula = "";
     if(trim($this->ob15_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob15_sequencial"])){ 
       $sql  .= $virgula." ob15_sequencial = $this->ob15_sequencial ";
       $virgula = ",";
       if(trim($this->ob15_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "ob15_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob15_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob15_numcgm"])){ 
       $sql  .= $virgula." ob15_numcgm = $this->ob15_numcgm ";
       $virgula = ",";
       if(trim($this->ob15_numcgm) == null ){ 
         $this->erro_sql = " Campo CGM não informado.";
         $this->erro_campo = "ob15_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob15_crea)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob15_crea"])){ 
       $sql  .= $virgula." ob15_crea = '$this->ob15_crea' ";
       $virgula = ",";
     }
     if(trim($this->ob15_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob15_tipo"])){ 
       $sql  .= $virgula." ob15_tipo = $this->ob15_tipo ";
       $virgula = ",";
       if(trim($this->ob15_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo não informado.";
         $this->erro_campo = "ob15_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob15_profissao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob15_profissao"])){ 
       $sql  .= $virgula." ob15_profissao = $this->ob15_profissao ";
       $virgula = ",";
       if(trim($this->ob15_profissao) == null ){ 
         $this->erro_sql = " Campo referencia a tabela obrastecprofissao não informado.";
         $this->erro_campo = "ob15_profissao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ob15_datalimite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ob15_datalimite_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ob15_datalimite_dia"] !="") ){ 
       $sql  .= $virgula." ob15_datalimite = '$this->ob15_datalimite' ";
       $virgula = ",";
       if(trim($this->ob15_datalimite) == null ){ 
         $this->erro_sql = " Campo Data Limite não informado.";
         $this->erro_campo = "ob15_datalimite_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ob15_datalimite_dia"])){ 
         $sql  .= $virgula." ob15_datalimite = null ";
         $virgula = ",";
         if(trim($this->ob15_datalimite) == null ){ 
           $this->erro_sql = " Campo Data Limite não informado.";
           $this->erro_campo = "ob15_datalimite_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     } 
     $sql .= " where ";
     if($ob15_sequencial!=null){
       $sql .= " ob15_sequencial = $this->ob15_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ob15_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,11384,'$this->ob15_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ob15_sequencial"]) || $this->ob15_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1001,11384,'".AddSlashes(pg_result($resaco,$conresaco,'ob15_sequencial'))."','$this->ob15_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ob15_numcgm"]) || $this->ob15_numcgm != "")
             $resac = db_query("insert into db_acount values($acount,1001,6191,'".AddSlashes(pg_result($resaco,$conresaco,'ob15_numcgm'))."','$this->ob15_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ob15_crea"]) || $this->ob15_crea != "")
             $resac = db_query("insert into db_acount values($acount,1001,6192,'".AddSlashes(pg_result($resaco,$conresaco,'ob15_crea'))."','$this->ob15_crea',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ob15_tipo"]) || $this->ob15_tipo != "")
             $resac = db_query("insert into db_acount values($acount,1001,11860,'".AddSlashes(pg_result($resaco,$conresaco,'ob15_tipo'))."','$this->ob15_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ob15_profissao"]) || $this->ob15_profissao != "")
             $resac = db_query("insert into db_acount values($acount,1001,1013242,'".AddSlashes(pg_result($resaco,$conresaco,'ob15_profissao'))."','$this->ob15_profissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ob15_datalimite"]) || $this->ob15_datalimite != "")
             $resac = db_query("insert into db_acount values($acount,1001,1014619,'".AddSlashes(pg_result($resaco,$conresaco,'ob15_datalimite'))."','$this->ob15_datalimite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tecnicos autorizados com crea não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob15_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "tecnicos autorizados com crea não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ob15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ob15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($ob15_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ob15_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,11384,'$ob15_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1001,11384,'','".AddSlashes(pg_result($resaco,$iresaco,'ob15_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1001,6191,'','".AddSlashes(pg_result($resaco,$iresaco,'ob15_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1001,6192,'','".AddSlashes(pg_result($resaco,$iresaco,'ob15_crea'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1001,11860,'','".AddSlashes(pg_result($resaco,$iresaco,'ob15_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1001,1013242,'','".AddSlashes(pg_result($resaco,$iresaco,'ob15_profissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1001,1014619,'','".AddSlashes(pg_result($resaco,$iresaco,'ob15_datalimite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from obrastec
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ob15_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ob15_sequencial = $ob15_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tecnicos autorizados com crea não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ob15_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "tecnicos autorizados com crea não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ob15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ob15_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:obrastec";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($ob15_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from obrastec ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = obrastec.ob15_numcgm";
     $sql .= "      left join obrastecprofissao  on  obrastecprofissao.ob30_sequencial = obrastec.ob15_profissao";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ob15_sequencial)) {
         $sql2 .= " where obrastec.ob15_sequencial = $ob15_sequencial "; 
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

    public function sql_query_file($ob15_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from obrastec ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ob15_sequencial)){
         $sql2 .= " where obrastec.ob15_sequencial = $ob15_sequencial "; 
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
