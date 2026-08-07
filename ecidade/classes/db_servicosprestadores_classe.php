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

class cl_servicosprestadores
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
    public $fm08_codigo = 0; 
    public $fm08_prestador = 0; 
    public $fm08_servico = 0; 
    public $fm08_autoriza = 'f'; 
    public $fm08_situacao = 'f'; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 fm08_codigo = int4 = Código dos Serviços Prestados 
                 fm08_prestador = int4 = Código do Prestador 
                 fm08_servico = int4 = Código do Serviço Prestado 
                 fm08_autoriza = bool = Autoriza Pedido 
                 fm08_situacao = bool = Situação do Serv. Prestado 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("servicosprestadores"); 
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
       $this->fm08_codigo = ($this->fm08_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fm08_codigo"]:$this->fm08_codigo);
       $this->fm08_prestador = ($this->fm08_prestador == ""?@$GLOBALS["HTTP_POST_VARS"]["fm08_prestador"]:$this->fm08_prestador);
       $this->fm08_servico = ($this->fm08_servico == ""?@$GLOBALS["HTTP_POST_VARS"]["fm08_servico"]:$this->fm08_servico);
       $this->fm08_autoriza = ($this->fm08_autoriza == "f"?@$GLOBALS["HTTP_POST_VARS"]["fm08_autoriza"]:$this->fm08_autoriza);
       $this->fm08_situacao = ($this->fm08_situacao == "f"?@$GLOBALS["HTTP_POST_VARS"]["fm08_situacao"]:$this->fm08_situacao);
     }else{
       $this->fm08_codigo = ($this->fm08_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fm08_codigo"]:$this->fm08_codigo);
     }
   }

    public function incluir($fm08_codigo)
    {
      $this->atualizacampos();
     if($this->fm08_prestador == null ){ 
       $this->erro_sql = " Campo Código do Prestador não informado.";
       $this->erro_campo = "fm08_prestador";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fm08_servico == null ){ 
       $this->erro_sql = " Campo Código do Serviço Prestado não informado.";
       $this->erro_campo = "fm08_servico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fm08_autoriza == null ){ 
       $this->erro_sql = " Campo Autoriza Pedido não informado.";
       $this->erro_campo = "fm08_autoriza";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fm08_situacao == null ){ 
       $this->erro_sql = " Campo Situação do Serv. Prestado não informado.";
       $this->erro_campo = "fm08_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($fm08_codigo == "" || $fm08_codigo == null ){
       $result = db_query("select nextval('servicosprestadores_fm08_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: servicosprestadores_fm08_codigo_seq do campo: fm08_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->fm08_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from servicosprestadores_fm08_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $fm08_codigo)){
         $this->erro_sql = " Campo fm08_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->fm08_codigo = $fm08_codigo; 
       }
     }
     if(($this->fm08_codigo == null) || ($this->fm08_codigo == "") ){ 
       $this->erro_sql = " Campo fm08_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into servicosprestadores(
                                       fm08_codigo 
                                      ,fm08_prestador 
                                      ,fm08_servico 
                                      ,fm08_autoriza 
                                      ,fm08_situacao 
                       )
                values (
                                $this->fm08_codigo 
                               ,$this->fm08_prestador 
                               ,$this->fm08_servico 
                               ,'$this->fm08_autoriza' 
                               ,'$this->fm08_situacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "servicosprestadores ($this->fm08_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "servicosprestadores já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "servicosprestadores ($this->fm08_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->fm08_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->fm08_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,241577305,'$this->fm08_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,77848461,241577305,'','".AddSlashes(pg_result($resaco,0,'fm08_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,77848461,132426792,'','".AddSlashes(pg_result($resaco,0,'fm08_prestador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,77848461,65864867,'','".AddSlashes(pg_result($resaco,0,'fm08_servico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,77848461,158987181,'','".AddSlashes(pg_result($resaco,0,'fm08_autoriza'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,77848461,165275374,'','".AddSlashes(pg_result($resaco,0,'fm08_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($fm08_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update servicosprestadores set ";
     $virgula = "";
     if(trim($this->fm08_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fm08_codigo"])){ 
       $sql  .= $virgula." fm08_codigo = $this->fm08_codigo ";
       $virgula = ",";
       if(trim($this->fm08_codigo) == null ){ 
         $this->erro_sql = " Campo Código dos Serviços Prestados não informado.";
         $this->erro_campo = "fm08_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fm08_prestador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fm08_prestador"])){ 
       $sql  .= $virgula." fm08_prestador = $this->fm08_prestador ";
       $virgula = ",";
       if(trim($this->fm08_prestador) == null ){ 
         $this->erro_sql = " Campo Código do Prestador não informado.";
         $this->erro_campo = "fm08_prestador";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fm08_servico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fm08_servico"])){ 
       $sql  .= $virgula." fm08_servico = $this->fm08_servico ";
       $virgula = ",";
       if(trim($this->fm08_servico) == null ){ 
         $this->erro_sql = " Campo Código do Serviço Prestado não informado.";
         $this->erro_campo = "fm08_servico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fm08_autoriza)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fm08_autoriza"])){ 
       $sql  .= $virgula." fm08_autoriza = '$this->fm08_autoriza' ";
       $virgula = ",";
       if(trim($this->fm08_autoriza) == null ){ 
         $this->erro_sql = " Campo Autoriza Pedido não informado.";
         $this->erro_campo = "fm08_autoriza";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fm08_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fm08_situacao"])){ 
       $sql  .= $virgula." fm08_situacao = '$this->fm08_situacao' ";
       $virgula = ",";
       if(trim($this->fm08_situacao) == null ){ 
         $this->erro_sql = " Campo Situação do Serv. Prestado não informado.";
         $this->erro_campo = "fm08_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($fm08_codigo!=null){
       $sql .= " fm08_codigo = $this->fm08_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->fm08_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,241577305,'$this->fm08_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fm08_codigo"]) || $this->fm08_codigo != "")
             $resac = db_query("insert into db_acount values($acount,77848461,241577305,'".AddSlashes(pg_result($resaco,$conresaco,'fm08_codigo'))."','$this->fm08_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fm08_prestador"]) || $this->fm08_prestador != "")
             $resac = db_query("insert into db_acount values($acount,77848461,132426792,'".AddSlashes(pg_result($resaco,$conresaco,'fm08_prestador'))."','$this->fm08_prestador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fm08_servico"]) || $this->fm08_servico != "")
             $resac = db_query("insert into db_acount values($acount,77848461,65864867,'".AddSlashes(pg_result($resaco,$conresaco,'fm08_servico'))."','$this->fm08_servico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fm08_autoriza"]) || $this->fm08_autoriza != "")
             $resac = db_query("insert into db_acount values($acount,77848461,158987181,'".AddSlashes(pg_result($resaco,$conresaco,'fm08_autoriza'))."','$this->fm08_autoriza',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fm08_situacao"]) || $this->fm08_situacao != "")
             $resac = db_query("insert into db_acount values($acount,77848461,165275374,'".AddSlashes(pg_result($resaco,$conresaco,'fm08_situacao'))."','$this->fm08_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "servicosprestadores não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->fm08_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "servicosprestadores não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fm08_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->fm08_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($fm08_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($fm08_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,241577305,'$fm08_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,77848461,241577305,'','".AddSlashes(pg_result($resaco,$iresaco,'fm08_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,77848461,132426792,'','".AddSlashes(pg_result($resaco,$iresaco,'fm08_prestador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,77848461,65864867,'','".AddSlashes(pg_result($resaco,$iresaco,'fm08_servico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,77848461,158987181,'','".AddSlashes(pg_result($resaco,$iresaco,'fm08_autoriza'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,77848461,165275374,'','".AddSlashes(pg_result($resaco,$iresaco,'fm08_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from servicosprestadores
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($fm08_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " fm08_codigo = $fm08_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "servicosprestadores não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fm08_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "servicosprestadores não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fm08_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$fm08_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:servicosprestadores";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($fm08_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from servicosprestadores ";
     $sql .= "      inner join associadoservicos  on  associadoservicos.fm12_codigo = servicosprestadores.fm08_servico";
     $sql .= "      inner join prestadores  on  prestadores.fm06_codigo = servicosprestadores.fm08_prestador";
     $sql .= "      inner join associadotiposservicos  on  associadotiposservicos.fm09_codigo = associadoservicos.fm12_tpservico";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = prestadores.fm06_numcgm";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = prestadores.fm06_depart";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($fm08_codigo)) {
         $sql2 .= " where servicosprestadores.fm08_codigo = $fm08_codigo "; 
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

    public function sql_query_file($fm08_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from servicosprestadores ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($fm08_codigo)){
         $sql2 .= " where servicosprestadores.fm08_codigo = $fm08_codigo "; 
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
