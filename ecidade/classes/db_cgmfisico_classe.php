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

class cl_cgmfisico
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
    public $z04_sequencial = 0; 
    public $z04_numcgm = 0; 
    public $z04_rhcbo = 0; 
    public $z04_nomesocial = null; 
    public $z04_paisnascimento = 0; 
    public $z04_paisnacionalidade = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 z04_sequencial = int4 = Código 
                 z04_numcgm = int4 = Código CGM 
                 z04_rhcbo = int4 = Código CBO 
                 z04_nomesocial = varchar(60) = Nome Social 
                 z04_paisnascimento = int4 = País Nascimento 
                 z04_paisnacionalidade = int4 = País Nacionalidade 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("cgmfisico"); 
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
       $this->z04_sequencial = ($this->z04_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["z04_sequencial"]:$this->z04_sequencial);
       $this->z04_numcgm = ($this->z04_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["z04_numcgm"]:$this->z04_numcgm);
       $this->z04_rhcbo = ($this->z04_rhcbo == ""?@$GLOBALS["HTTP_POST_VARS"]["z04_rhcbo"]:$this->z04_rhcbo);
       $this->z04_nomesocial = ($this->z04_nomesocial == ""?@$GLOBALS["HTTP_POST_VARS"]["z04_nomesocial"]:$this->z04_nomesocial);
       $this->z04_paisnascimento = ($this->z04_paisnascimento == ""?@$GLOBALS["HTTP_POST_VARS"]["z04_paisnascimento"]:$this->z04_paisnascimento);
       $this->z04_paisnacionalidade = ($this->z04_paisnacionalidade == ""?@$GLOBALS["HTTP_POST_VARS"]["z04_paisnacionalidade"]:$this->z04_paisnacionalidade);
     }else{
       $this->z04_sequencial = ($this->z04_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["z04_sequencial"]:$this->z04_sequencial);
     }
   }

    public function incluir($z04_sequencial)
    {
      $this->atualizacampos();
     if($this->z04_numcgm == null ){ 
       $this->erro_sql = " Campo Código CGM não informado.";
       $this->erro_campo = "z04_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z04_rhcbo == null ){ 
       $this->z04_rhcbo = "0";
     }
     
     if($this->z04_paisnascimento == null ){ 
       $this->z04_paisnascimento = 'null';
     }
     if($this->z04_paisnacionalidade == null ){ 
      $this->z04_paisnacionalidade = 'null';
     }
     if($z04_sequencial == "" || $z04_sequencial == null ){
       $result = db_query("select nextval('cgmfisico_z04_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cgmfisico_z04_sequencial_seq do campo: z04_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->z04_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cgmfisico_z04_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $z04_sequencial)){
         $this->erro_sql = " Campo z04_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->z04_sequencial = $z04_sequencial; 
       }
     }
     if(($this->z04_sequencial == null) || ($this->z04_sequencial == "") ){ 
       $this->erro_sql = " Campo z04_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cgmfisico(
                                       z04_sequencial 
                                      ,z04_numcgm 
                                      ,z04_rhcbo 
                                      ,z04_nomesocial 
                                      ,z04_paisnascimento 
                                      ,z04_paisnacionalidade 
                       )
                values (
                                $this->z04_sequencial 
                               ,$this->z04_numcgm 
                               ,$this->z04_rhcbo 
                               ,'$this->z04_nomesocial' 
                               ,$this->z04_paisnascimento 
                               ,$this->z04_paisnacionalidade 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cgm Físico ($this->z04_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cgm Físico já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cgm Físico ($this->z04_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->z04_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->z04_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16715,'$this->z04_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,2939,16715,'','".AddSlashes(pg_result($resaco,0,'z04_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2939,16716,'','".AddSlashes(pg_result($resaco,0,'z04_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2939,19585,'','".AddSlashes(pg_result($resaco,0,'z04_rhcbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2939,1013640,'','".AddSlashes(pg_result($resaco,0,'z04_nomesocial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2939,1013638,'','".AddSlashes(pg_result($resaco,0,'z04_paisnascimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2939,1013639,'','".AddSlashes(pg_result($resaco,0,'z04_paisnacionalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($z04_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update cgmfisico set ";
     $virgula = "";
     if(trim($this->z04_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z04_sequencial"])){ 
       $sql  .= $virgula." z04_sequencial = $this->z04_sequencial ";
       $virgula = ",";
       if(trim($this->z04_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "z04_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z04_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z04_numcgm"])){ 
       $sql  .= $virgula." z04_numcgm = $this->z04_numcgm ";
       $virgula = ",";
       if(trim($this->z04_numcgm) == null ){ 
         $this->erro_sql = " Campo Código CGM não informado.";
         $this->erro_campo = "z04_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z04_rhcbo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z04_rhcbo"])){ 
        if(trim($this->z04_rhcbo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["z04_rhcbo"])){ 
           $this->z04_rhcbo = "0" ; 
        } 
       $sql  .= $virgula." z04_rhcbo = $this->z04_rhcbo ";
       $virgula = ",";
     }
     if(trim($this->z04_nomesocial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z04_nomesocial"])){ 
       $sql  .= $virgula." z04_nomesocial = '$this->z04_nomesocial' ";
       $virgula = ",";
       if(trim($this->z04_nomesocial) == null ){ 
         $this->erro_sql = " Campo Nome Social não informado.";
         $this->erro_campo = "z04_nomesocial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z04_paisnascimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z04_paisnascimento"])){ 
       $sql  .= $virgula." z04_paisnascimento = $this->z04_paisnascimento ";
       $virgula = ",";
       if(trim($this->z04_paisnascimento) == null ){ 
         $this->erro_sql = " Campo País Nascimento não informado.";
         $this->erro_campo = "z04_paisnascimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z04_paisnacionalidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z04_paisnacionalidade"])){ 
       $sql  .= $virgula." z04_paisnacionalidade = $this->z04_paisnacionalidade ";
       $virgula = ",";
       if(trim($this->z04_paisnacionalidade) == null ){ 
         $this->erro_sql = " Campo País Nacionalidade não informado.";
         $this->erro_campo = "z04_paisnacionalidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($z04_sequencial!=null){
       $sql .= " z04_sequencial = $this->z04_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->z04_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,16715,'$this->z04_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z04_sequencial"]) || $this->z04_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,2939,16715,'".AddSlashes(pg_result($resaco,$conresaco,'z04_sequencial'))."','$this->z04_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z04_numcgm"]) || $this->z04_numcgm != "")
             $resac = db_query("insert into db_acount values($acount,2939,16716,'".AddSlashes(pg_result($resaco,$conresaco,'z04_numcgm'))."','$this->z04_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z04_rhcbo"]) || $this->z04_rhcbo != "")
             $resac = db_query("insert into db_acount values($acount,2939,19585,'".AddSlashes(pg_result($resaco,$conresaco,'z04_rhcbo'))."','$this->z04_rhcbo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z04_nomesocial"]) || $this->z04_nomesocial != "")
             $resac = db_query("insert into db_acount values($acount,2939,1013640,'".AddSlashes(pg_result($resaco,$conresaco,'z04_nomesocial'))."','$this->z04_nomesocial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z04_paisnascimento"]) || $this->z04_paisnascimento != "")
             $resac = db_query("insert into db_acount values($acount,2939,1013638,'".AddSlashes(pg_result($resaco,$conresaco,'z04_paisnascimento'))."','$this->z04_paisnascimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["z04_paisnacionalidade"]) || $this->z04_paisnacionalidade != "")
             $resac = db_query("insert into db_acount values($acount,2939,1013639,'".AddSlashes(pg_result($resaco,$conresaco,'z04_paisnacionalidade'))."','$this->z04_paisnacionalidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cgm Físico não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->z04_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cgm Físico não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->z04_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->z04_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($z04_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($z04_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,16715,'$z04_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,2939,16715,'','".AddSlashes(pg_result($resaco,$iresaco,'z04_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2939,16716,'','".AddSlashes(pg_result($resaco,$iresaco,'z04_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2939,19585,'','".AddSlashes(pg_result($resaco,$iresaco,'z04_rhcbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2939,1013640,'','".AddSlashes(pg_result($resaco,$iresaco,'z04_nomesocial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2939,1013638,'','".AddSlashes(pg_result($resaco,$iresaco,'z04_paisnascimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2939,1013639,'','".AddSlashes(pg_result($resaco,$iresaco,'z04_paisnacionalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cgmfisico
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($z04_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " z04_sequencial = $z04_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cgm Físico não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$z04_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cgm Físico não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$z04_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$z04_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cgmfisico";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($z04_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from cgmfisico ";
     $sql .= " inner join cgm  ";  
     $sql .= "    on cgm.z01_numcgm = cgmfisico.z04_numcgm";
     $sql .= "  left join rhcbo  ";
     $sql .= "    on rhcbo.rh70_sequencial = cgmfisico.z04_rhcbo";
     $sql .= "  left join cadenderpais paisnascimento ";
     $sql .= "    on  paisnascimento.db70_sequencial = cgmfisico.z04_paisnascimento ";
     $sql .= "  left join cadenderpaissistema paisnascimentocodigo ";
     $sql .= "    on paisnascimento.db70_sequencial = paisnascimentocodigo.db135_db_cadenderpais ";
     $sql .= "   and paisnascimentocodigo.db135_db_sistemaexterno = 3";
     $sql .= "  left join cadenderpais paisnacionalidade ";
     $sql .= "    on paisnacionalidade.db70_sequencial = cgmfisico.z04_paisnacionalidade ";
     $sql .= "  left join cadenderpaissistema paisnacionalidadecodigo ";
     $sql .= "    on paisnacionalidade.db70_sequencial = paisnacionalidadecodigo.db135_db_cadenderpais";
     $sql .= "   and paisnacionalidadecodigo.db135_db_sistemaexterno = 3";
     $sql2 = "";  
     if (empty($dbwhere)) {
       if (!empty($z04_sequencial)) {
         $sql2 .= " where cgmfisico.z04_sequencial = $z04_sequencial "; 
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

    public function sql_query_file($z04_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from cgmfisico ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($z04_sequencial)){
         $sql2 .= " where cgmfisico.z04_sequencial = $z04_sequencial "; 
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
