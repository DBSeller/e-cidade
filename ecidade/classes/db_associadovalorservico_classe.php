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


class cl_associadovalorservico
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
    public $fm13_codigo = 0; 
    public $fm13_servico = 0; 
    public $fm13_valor = 0; 
    public $fm13_vigencia_dia = null; 
    public $fm13_vigencia_mes = null; 
    public $fm13_vigencia_ano = null; 
    public $fm13_vigencia = null;
    // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 fm13_codigo = int4 = Cód Vlr Serv Prestado 
                 fm13_servico = int4 = Cód Cad Serv Prestado 
                 fm13_valor = numeric = Valor FUMAM 
                 fm13_vigencia = date = Data da Vigência 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("associadovalorservico"); 
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
       $this->fm13_codigo = ($this->fm13_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fm13_codigo"]:$this->fm13_codigo);
       $this->fm13_servico = ($this->fm13_servico == ""?@$GLOBALS["HTTP_POST_VARS"]["fm13_servico"]:$this->fm13_servico);
       $this->fm13_valor = ($this->fm13_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["fm13_valor"]:$this->fm13_valor);
       if($this->fm13_vigencia == ""){
         $this->fm13_vigencia_dia = ($this->fm13_vigencia_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["fm13_vigencia_dia"]:$this->fm13_vigencia_dia);
         $this->fm13_vigencia_mes = ($this->fm13_vigencia_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["fm13_vigencia_mes"]:$this->fm13_vigencia_mes);
         $this->fm13_vigencia_ano = ($this->fm13_vigencia_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["fm13_vigencia_ano"]:$this->fm13_vigencia_ano);
         if($this->fm13_vigencia_dia != ""){
            $this->fm13_vigencia = $this->fm13_vigencia_ano."-".$this->fm13_vigencia_mes."-".$this->fm13_vigencia_dia;
         }
       }
     }else{
       $this->fm13_codigo = ($this->fm13_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fm13_codigo"]:$this->fm13_codigo);
     }
   }

    public function incluir($fm13_codigo)
    {
      $this->atualizacampos();
     if($this->fm13_servico == null ){ 
       $this->erro_sql = " Campo Cód Cad Serv Prestado não informado.";
       $this->erro_campo = "fm13_servico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fm13_valor == null ){ 
       $this->erro_sql = " Campo Valor FUMAM não informado.";
       $this->erro_campo = "fm13_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($fm13_codigo == "" || $fm13_codigo == null ){
       $result = db_query("select nextval('associadovalorservico_fm13_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: associadovalorservico_fm13_codigo_seq do campo: fm13_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->fm13_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from associadovalorservico_fm13_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $fm13_codigo)){
         $this->erro_sql = " Campo fm13_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->fm13_codigo = $fm13_codigo; 
       }
     }
     if(($this->fm13_codigo == null) || ($this->fm13_codigo == "") ){ 
       $this->erro_sql = " Campo fm13_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into associadovalorservico(
                                       fm13_codigo 
                                      ,fm13_servico 
                                      ,fm13_valor 
                                      ,fm13_vigencia 
                       )
                values (
                                $this->fm13_codigo 
                               ,$this->fm13_servico 
                               ,$this->fm13_valor 
                               ,".($this->fm13_vigencia == "null" || $this->fm13_vigencia == ""?"null":"'".$this->fm13_vigencia."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Associado Valor Servico ($this->fm13_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "associadovalorservico já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Associado Valor Servico ($this->fm13_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
     $this->erro_sql .= "Valores : ".$this->fm13_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->fm13_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,124157575,'$this->fm13_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,116802261,124157575,'','".AddSlashes(pg_result($resaco,0,'fm13_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,116802261,306631516,'','".AddSlashes(pg_result($resaco,0,'fm13_servico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,116802261,222560603,'','".AddSlashes(pg_result($resaco,0,'fm13_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,116802261,220346436,'','".AddSlashes(pg_result($resaco,0,'fm13_vigencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($fm13_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update associadovalorservico set ";
     $virgula = "";
     if(trim($this->fm13_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fm13_codigo"])){ 
       $sql  .= $virgula." fm13_codigo = $this->fm13_codigo ";
       $virgula = ",";
       if(trim($this->fm13_codigo) == null ){ 
         $this->erro_sql = " Campo Cód Vlr Serv Prestado não informado.";
         $this->erro_campo = "fm13_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fm13_servico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fm13_servico"])){ 
       $sql  .= $virgula." fm13_servico = $this->fm13_servico ";
       $virgula = ",";
       if(trim($this->fm13_servico) == null ){ 
         $this->erro_sql = " Campo Cód Cad Serv Prestado não informado.";
         $this->erro_campo = "fm13_servico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fm13_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fm13_valor"])){ 
       $sql  .= $virgula." fm13_valor = $this->fm13_valor ";
       $virgula = ",";
       if(trim($this->fm13_valor) == null ){ 
         $this->erro_sql = " Campo Valor FUMAM não informado.";
         $this->erro_campo = "fm13_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fm13_vigencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fm13_vigencia_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["fm13_vigencia_dia"] !="") ){ 
       $sql  .= $virgula." fm13_vigencia = '$this->fm13_vigencia' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["fm13_vigencia_dia"])){ 
         $sql  .= $virgula." fm13_vigencia = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($fm13_codigo!=null){
       $sql .= " fm13_codigo = $this->fm13_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->fm13_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,124157575,'$this->fm13_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fm13_codigo"]) || $this->fm13_codigo != "")
             $resac = db_query("insert into db_acount values($acount,116802261,124157575,'".AddSlashes(pg_result($resaco,$conresaco,'fm13_codigo'))."','$this->fm13_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fm13_servico"]) || $this->fm13_servico != "")
             $resac = db_query("insert into db_acount values($acount,116802261,306631516,'".AddSlashes(pg_result($resaco,$conresaco,'fm13_servico'))."','$this->fm13_servico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fm13_valor"]) || $this->fm13_valor != "")
             $resac = db_query("insert into db_acount values($acount,116802261,222560603,'".AddSlashes(pg_result($resaco,$conresaco,'fm13_valor'))."','$this->fm13_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["fm13_vigencia"]) || $this->fm13_vigencia != "")
             $resac = db_query("insert into db_acount values($acount,116802261,220346436,'".AddSlashes(pg_result($resaco,$conresaco,'fm13_vigencia'))."','$this->fm13_vigencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql = "Associado Valor Servico não Alterado. Alteração Abortada.\\n";
       $this->erro_sql .= "Valores : ".$this->fm13_codigo;
       $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Associado Valor Servico não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fm13_codigo;
         $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->fm13_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($fm13_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($fm13_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,124157575,'$fm13_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,116802261,124157575,'','".AddSlashes(pg_result($resaco,$iresaco,'fm13_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,116802261,306631516,'','".AddSlashes(pg_result($resaco,$iresaco,'fm13_servico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,116802261,222560603,'','".AddSlashes(pg_result($resaco,$iresaco,'fm13_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,116802261,220346436,'','".AddSlashes(pg_result($resaco,$iresaco,'fm13_vigencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from associadovalorservico
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($fm13_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " fm13_codigo = $fm13_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql = "Associado Valor Servico não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fm13_codigo;
       $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Associado Valor Servico não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fm13_codigo;
         $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$fm13_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:associadovalorservico";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($fm13_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from associadovalorservico ";
     $sql .= "       join associadoservicos on associadoservicos.fm12_codigo = associadovalorservico.fm13_servico";
     $sql .= "       join associadotiposservicos on associadotiposservicos.fm09_codigo = associadoservicos.fm12_tpservico";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($fm13_codigo)) {
         $sql2 .= " where associadovalorservico.fm13_codigo = $fm13_codigo "; 
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

    public function sql_query_file($fm13_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from associadovalorservico ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($fm13_codigo)){
         $sql2 .= " where associadovalorservico.fm13_codigo = $fm13_codigo "; 
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
