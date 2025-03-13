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

class cl_orcsubfuncao
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
    public $o53_subfuncao = 0;
    public $o53_descr = null;
    public $o53_codtri = null;
    public $o53_finali = null;
    public $o53_siconfi = null;
   // cria propriedade com as variaveis do arquivo
    public $campos = "
                 o53_subfuncao = int4 = Sub Função
                 o53_descr = varchar(40) = Descrição
                 o53_codtri = varchar(10) = Código tribunal
                 o53_finali = text = Finalidade
                 o53_siconfi = char(3) = Código Siconfi
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("orcsubfuncao");
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
       $this->o53_subfuncao = ($this->o53_subfuncao == ""?@$GLOBALS["HTTP_POST_VARS"]["o53_subfuncao"]:$this->o53_subfuncao);
       $this->o53_descr = ($this->o53_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["o53_descr"]:$this->o53_descr);
       $this->o53_codtri = ($this->o53_codtri == ""?@$GLOBALS["HTTP_POST_VARS"]["o53_codtri"]:$this->o53_codtri);
       $this->o53_finali = ($this->o53_finali == ""?@$GLOBALS["HTTP_POST_VARS"]["o53_finali"]:$this->o53_finali);
       $this->o53_siconfi = ($this->o53_siconfi == ""?@$GLOBALS["HTTP_POST_VARS"]["o53_siconfi"]:$this->o53_siconfi);
     }else{
       $this->o53_subfuncao = ($this->o53_subfuncao == ""?@$GLOBALS["HTTP_POST_VARS"]["o53_subfuncao"]:$this->o53_subfuncao);
     }
   }

    public function incluir($o53_subfuncao)
    {
      $this->atualizacampos();
     if($this->o53_descr == null ){
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "o53_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o53_codtri == null ){
       $this->erro_sql = " Campo Código tribunal não informado.";
       $this->erro_campo = "o53_codtri";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o53_siconfi == null ){
       $this->erro_sql = " Campo Código Siconfi não informado.";
       $this->erro_campo = "o53_siconfi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->o53_subfuncao = $o53_subfuncao;
     if(($this->o53_subfuncao == null) || ($this->o53_subfuncao == "") ){
       $this->erro_sql = " Campo o53_subfuncao não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcsubfuncao(
                                       o53_subfuncao
                                      ,o53_descr
                                      ,o53_codtri
                                      ,o53_finali
                                      ,o53_siconfi
                       )
                values (
                                $this->o53_subfuncao
                               ,'$this->o53_descr'
                               ,'$this->o53_codtri'
                               ,'$this->o53_finali'
                               ,'$this->o53_siconfi'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Sub Função Orçamento ($this->o53_subfuncao) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Sub Função Orçamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Sub Função Orçamento ($this->o53_subfuncao) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->o53_subfuncao;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->o53_subfuncao  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5256,'$this->o53_subfuncao','I')");
         $resac = db_query("insert into db_acount values($acount,751,5256,'','".AddSlashes(pg_result($resaco,0,'o53_subfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,751,5257,'','".AddSlashes(pg_result($resaco,0,'o53_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,751,5258,'','".AddSlashes(pg_result($resaco,0,'o53_codtri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,751,5259,'','".AddSlashes(pg_result($resaco,0,'o53_finali'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,751,1014660,'','".AddSlashes(pg_result($resaco,0,'o53_siconfi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }

    public function alterar($o53_subfuncao=null)
    {
      $this->atualizacampos();
     $sql = " update orcsubfuncao set ";
     $virgula = "";
     if(trim($this->o53_subfuncao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o53_subfuncao"])){
       $sql  .= $virgula." o53_subfuncao = $this->o53_subfuncao ";
       $virgula = ",";
       if(trim($this->o53_subfuncao) == null ){
         $this->erro_sql = " Campo Sub Função não informado.";
         $this->erro_campo = "o53_subfuncao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o53_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o53_descr"])){
       $sql  .= $virgula." o53_descr = '$this->o53_descr' ";
       $virgula = ",";
       if(trim($this->o53_descr) == null ){
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "o53_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o53_codtri)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o53_codtri"])){
       $sql  .= $virgula." o53_codtri = '$this->o53_codtri' ";
       $virgula = ",";
       if(trim($this->o53_codtri) == null ){
         $this->erro_sql = " Campo Código tribunal não informado.";
         $this->erro_campo = "o53_codtri";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o53_finali)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o53_finali"])){
       $sql  .= $virgula." o53_finali = '$this->o53_finali' ";
       $virgula = ",";
     }
     if(trim($this->o53_siconfi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o53_siconfi"])){
       $sql  .= $virgula." o53_siconfi = '$this->o53_siconfi' ";
       $virgula = ",";
       if(trim($this->o53_siconfi) == null ){
         $this->erro_sql = " Campo Código Siconfi não informado.";
         $this->erro_campo = "o53_siconfi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($o53_subfuncao!=null){
       $sql .= " o53_subfuncao = $this->o53_subfuncao";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->o53_subfuncao));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,5256,'$this->o53_subfuncao','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o53_subfuncao"]) || $this->o53_subfuncao != "")
             $resac = db_query("insert into db_acount values($acount,751,5256,'".AddSlashes(pg_result($resaco,$conresaco,'o53_subfuncao'))."','$this->o53_subfuncao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o53_descr"]) || $this->o53_descr != "")
             $resac = db_query("insert into db_acount values($acount,751,5257,'".AddSlashes(pg_result($resaco,$conresaco,'o53_descr'))."','$this->o53_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o53_codtri"]) || $this->o53_codtri != "")
             $resac = db_query("insert into db_acount values($acount,751,5258,'".AddSlashes(pg_result($resaco,$conresaco,'o53_codtri'))."','$this->o53_codtri',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o53_finali"]) || $this->o53_finali != "")
             $resac = db_query("insert into db_acount values($acount,751,5259,'".AddSlashes(pg_result($resaco,$conresaco,'o53_finali'))."','$this->o53_finali',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["o53_siconfi"]) || $this->o53_siconfi != "")
             $resac = db_query("insert into db_acount values($acount,751,1014660,'".AddSlashes(pg_result($resaco,$conresaco,'o53_siconfi'))."','$this->o53_siconfi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Sub Função Orçamento não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o53_subfuncao;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Sub Função Orçamento não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o53_subfuncao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->o53_subfuncao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }

    public function excluir($o53_subfuncao=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($o53_subfuncao));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,5256,'$o53_subfuncao','E')");
           $resac  = db_query("insert into db_acount values($acount,751,5256,'','".AddSlashes(pg_result($resaco,$iresaco,'o53_subfuncao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,751,5257,'','".AddSlashes(pg_result($resaco,$iresaco,'o53_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,751,5258,'','".AddSlashes(pg_result($resaco,$iresaco,'o53_codtri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,751,5259,'','".AddSlashes(pg_result($resaco,$iresaco,'o53_finali'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,751,1014660,'','".AddSlashes(pg_result($resaco,$iresaco,'o53_siconfi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from orcsubfuncao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($o53_subfuncao)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " o53_subfuncao = $o53_subfuncao ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Sub Função Orçamento não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o53_subfuncao;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Sub Função Orçamento não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o53_subfuncao;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$o53_subfuncao;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcsubfuncao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($o53_subfuncao = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from orcsubfuncao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($o53_subfuncao)) {
         $sql2 .= " where orcsubfuncao.o53_subfuncao = $o53_subfuncao ";
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

    public function sql_query_file($o53_subfuncao = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from orcsubfuncao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($o53_subfuncao)){
         $sql2 .= " where orcsubfuncao.o53_subfuncao = $o53_subfuncao ";
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
