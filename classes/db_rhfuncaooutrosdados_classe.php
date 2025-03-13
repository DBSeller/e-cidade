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

class cl_rhfuncaooutrosdados
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
    public $rh267_instit = 0; 
    public $rh267_rhfuncao = 0; 
    public $rh267_codigo = 0; 
    public $rh267_dados = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh267_instit = int4 = Chave Estrangeira 
                 rh267_rhfuncao = int8 = Chave Estrangeira 
                 rh267_codigo = int8 = Sequencial da tabela 
                 rh267_dados = text = json 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhfuncaooutrosdados"); 
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
       $this->rh267_instit = ($this->rh267_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh267_instit"]:$this->rh267_instit);
       $this->rh267_rhfuncao = ($this->rh267_rhfuncao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh267_rhfuncao"]:$this->rh267_rhfuncao);
       $this->rh267_codigo = ($this->rh267_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh267_codigo"]:$this->rh267_codigo);
       $this->rh267_dados = ($this->rh267_dados == ""?@$GLOBALS["HTTP_POST_VARS"]["rh267_dados"]:$this->rh267_dados);
     }else{
       $this->rh267_codigo = ($this->rh267_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh267_codigo"]:$this->rh267_codigo);
     }
   }

    public function incluir($rh267_codigo)
    {
      $this->atualizacampos();
     if($this->rh267_instit == null ){ 
       $this->erro_sql = " Campo Chave Estrangeira não informado.";
       $this->erro_campo = "rh267_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh267_rhfuncao == null ){ 
       $this->erro_sql = " Campo Chave Estrangeira não informado.";
       $this->erro_campo = "rh267_rhfuncao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh267_dados == null ){ 
       $this->erro_sql = " Campo json não informado.";
       $this->erro_campo = "rh267_dados";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh267_codigo == "" || $this->rh267_codigo == null ){
       $result = db_query("select nextval('rhfuncaooutrosdados_rh267_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhfuncaooutrosdados_rh267_codigo_seq do campo: rh267_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh267_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhfuncaooutrosdados_rh267_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh267_codigo)){
         $this->erro_sql = " Campo rh267_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh267_codigo = $rh267_codigo; 
       }
     }
     if(($this->rh267_codigo == null) || ($this->rh267_codigo == "") ){ 
       $this->erro_sql = " Campo rh267_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhfuncaooutrosdados(
                                       rh267_instit 
                                      ,rh267_rhfuncao 
                                      ,rh267_codigo 
                                      ,rh267_dados 
                       )
                values (
                                $this->rh267_instit 
                               ,$this->rh267_rhfuncao 
                               ,$this->rh267_codigo 
                               ,'$this->rh267_dados' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Informações Complementares ($this->rh267_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Informações Complementares já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Informações Complementares ($this->rh267_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh267_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh267_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1014436,'$this->rh267_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010980,1014438,'','".AddSlashes(pg_result($resaco,0,'rh267_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010980,1014437,'','".AddSlashes(pg_result($resaco,0,'rh267_rhfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010980,1014436,'','".AddSlashes(pg_result($resaco,0,'rh267_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010980,1014435,'','".AddSlashes(pg_result($resaco,0,'rh267_dados'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh267_rhfuncao=null, $rh267_instit=null)
    {
      $this->atualizacampos();
     $sql = " update rhfuncaooutrosdados set ";
     $virgula = "";
     if(trim($this->rh267_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh267_instit"])){ 
       $sql  .= $virgula." rh267_instit = $this->rh267_instit ";
       $virgula = ",";
       if(trim($this->rh267_instit) == null ){ 
         $this->erro_sql = " Campo Chave Estrangeira não informado.";
         $this->erro_campo = "rh267_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh267_rhfuncao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh267_rhfuncao"])){ 
       $sql  .= $virgula." rh267_rhfuncao = $this->rh267_rhfuncao ";
       $virgula = ",";
       if(trim($this->rh267_rhfuncao) == null ){ 
         $this->erro_sql = " Campo Chave Estrangeira não informado.";
         $this->erro_campo = "rh267_rhfuncao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh267_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh267_codigo"])){ 
       $sql  .= $virgula." rh267_codigo = $this->rh267_codigo ";
       $virgula = ",";
       if(trim($this->rh267_codigo) == null ){ 
         $this->erro_sql = " Campo Sequencial da tabela não informado.";
         $this->erro_campo = "rh267_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh267_dados)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh267_dados"])){ 
       $sql  .= $virgula." rh267_dados = '$this->rh267_dados' ";
       $virgula = ",";
       if(trim($this->rh267_dados) == null ){ 
         $this->erro_sql = " Campo json não informado.";
         $this->erro_campo = "rh267_dados";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh267_rhfuncao!=null && $rh267_instit){
       $sql .= " rh267_rhfuncao = $rh267_rhfuncao and rh267_instit = $rh267_instit";
     }

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh267_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1014436,'$this->rh267_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh267_instit"]) || $this->rh267_instit != "")
             $resac = db_query("insert into db_acount values($acount,1010980,1014438,'".AddSlashes(pg_result($resaco,$conresaco,'rh267_instit'))."','$this->rh267_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh267_rhfuncao"]) || $this->rh267_rhfuncao != "")
             $resac = db_query("insert into db_acount values($acount,1010980,1014437,'".AddSlashes(pg_result($resaco,$conresaco,'rh267_rhfuncao'))."','$this->rh267_rhfuncao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh267_codigo"]) || $this->rh267_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010980,1014436,'".AddSlashes(pg_result($resaco,$conresaco,'rh267_codigo'))."','$this->rh267_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh267_dados"]) || $this->rh267_dados != "")
             $resac = db_query("insert into db_acount values($acount,1010980,1014435,'".AddSlashes(pg_result($resaco,$conresaco,'rh267_dados'))."','$this->rh267_dados',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Informações Complementares não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh267_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Informações Complementares não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh267_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh267_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh267_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh267_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1014436,'$rh267_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010980,1014438,'','".AddSlashes(pg_result($resaco,$iresaco,'rh267_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010980,1014437,'','".AddSlashes(pg_result($resaco,$iresaco,'rh267_rhfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010980,1014436,'','".AddSlashes(pg_result($resaco,$iresaco,'rh267_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010980,1014435,'','".AddSlashes(pg_result($resaco,$iresaco,'rh267_dados'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhfuncaooutrosdados
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh267_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh267_codigo = $rh267_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Informações Complementares não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh267_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Informações Complementares não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh267_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh267_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhfuncaooutrosdados";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh267_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhfuncaooutrosdados ";
     $sql .= "      inner join rhfuncao  on  rhfuncao.rh37_funcao = rhfuncaooutrosdados.rh267_rhfuncao and  rhfuncao.rh37_instit = rhfuncaooutrosdados.rh267_instit";
     $sql .= "      inner join db_config  on  db_config.codigo = rhfuncao.rh37_instit";
     $sql .= "      left  join rhinstrucao  on  rhinstrucao.rh21_instru = rhfuncao.rh37_rhinstrucao";
     $sql .= "      inner join rhfuncaogrupo  on  rhfuncaogrupo.rh100_sequencial = rhfuncao.rh37_funcaogrupo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh267_codigo)) {
         $sql2 .= " where rhfuncaooutrosdados.rh267_codigo = $rh267_codigo "; 
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

    public function sql_query_file($rh267_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhfuncaooutrosdados ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh267_codigo)){
         $sql2 .= " where rhfuncaooutrosdados.rh267_codigo = $rh267_codigo "; 
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
