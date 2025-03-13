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

class cl_diario_classe_bncc_habilidade_referencial
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
    public $ed169_codigo = 0;
    public $ed169_diario_classe_bncc_habilidade = 0;
    public $ed169_bnccreferencial = 0;
   // cria propriedade com as variaveis do arquivo
    public $campos = "
                 ed169_codigo = int4 = Código
                 ed169_diario_classe_bncc_habilidade = int4 = Código da Habilidade lançada
                 ed169_bnccreferencial = int4 = Código Referencial
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("diario_classe_bncc_habilidade_referencial");
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
       $this->ed169_codigo = ($this->ed169_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed169_codigo"]:$this->ed169_codigo);
       $this->ed169_diario_classe_bncc_habilidade = ($this->ed169_diario_classe_bncc_habilidade == ""?@$GLOBALS["HTTP_POST_VARS"]["ed169_diario_classe_bncc_habilidade"]:$this->ed169_diario_classe_bncc_habilidade);
       $this->ed169_bnccreferencial = ($this->ed169_bnccreferencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed169_bnccreferencial"]:$this->ed169_bnccreferencial);
     }else{
       $this->ed169_codigo = ($this->ed169_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed169_codigo"]:$this->ed169_codigo);
     }
   }

    public function incluir($ed169_codigo)
    {
      $this->atualizacampos();
     if($this->ed169_diario_classe_bncc_habilidade == null ){
       $this->erro_sql = " Campo Código da Habilidade lançada não informado.";
       $this->erro_campo = "ed169_diario_classe_bncc_habilidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed169_bnccreferencial == null ){
       $this->erro_sql = " Campo Código Referencial não informado.";
       $this->erro_campo = "ed169_bnccreferencial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed169_codigo == "" || $ed169_codigo == null ){
       $result = db_query("select nextval('diario_classe_bncc_habilidade_referencial_ed169_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: diario_classe_bncc_habilidade_referencial_ed169_codigo_seq do campo: ed169_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed169_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from diario_classe_bncc_habilidade_referencial_ed169_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed169_codigo)){
         $this->erro_sql = " Campo ed169_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed169_codigo = $ed169_codigo;
       }
     }
     if(($this->ed169_codigo == null) || ($this->ed169_codigo == "") ){
       $this->erro_sql = " Campo ed169_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into diario_classe_bncc_habilidade_referencial(
                                       ed169_codigo
                                      ,ed169_diario_classe_bncc_habilidade
                                      ,ed169_bnccreferencial
                       )
                values (
                                $this->ed169_codigo
                               ,$this->ed169_diario_classe_bncc_habilidade
                               ,$this->ed169_bnccreferencial
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Habilidade do referencial ($this->ed169_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Habilidade do referencial já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Habilidade do referencial ($this->ed169_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed169_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed169_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1011777,'$this->ed169_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010615,1011777,'','".AddSlashes(pg_result($resaco,0,'ed169_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010615,1011778,'','".AddSlashes(pg_result($resaco,0,'ed169_diario_classe_bncc_habilidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010615,1011779,'','".AddSlashes(pg_result($resaco,0,'ed169_bnccreferencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }

    public function alterar($ed169_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update diario_classe_bncc_habilidade_referencial set ";
     $virgula = "";
     if(trim($this->ed169_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed169_codigo"])){
       $sql  .= $virgula." ed169_codigo = $this->ed169_codigo ";
       $virgula = ",";
       if(trim($this->ed169_codigo) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed169_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed169_diario_classe_bncc_habilidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed169_diario_classe_bncc_habilidade"])){
       $sql  .= $virgula." ed169_diario_classe_bncc_habilidade = $this->ed169_diario_classe_bncc_habilidade ";
       $virgula = ",";
       if(trim($this->ed169_diario_classe_bncc_habilidade) == null ){
         $this->erro_sql = " Campo Código da Habilidade lançada não informado.";
         $this->erro_campo = "ed169_diario_classe_bncc_habilidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed169_bnccreferencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed169_bnccreferencial"])){
       $sql  .= $virgula." ed169_bnccreferencial = $this->ed169_bnccreferencial ";
       $virgula = ",";
       if(trim($this->ed169_bnccreferencial) == null ){
         $this->erro_sql = " Campo Código Referencial não informado.";
         $this->erro_campo = "ed169_bnccreferencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed169_codigo!=null){
       $sql .= " ed169_codigo = $this->ed169_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed169_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1011777,'$this->ed169_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed169_codigo"]) || $this->ed169_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010615,1011777,'".AddSlashes(pg_result($resaco,$conresaco,'ed169_codigo'))."','$this->ed169_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed169_diario_classe_bncc_habilidade"]) || $this->ed169_diario_classe_bncc_habilidade != "")
             $resac = db_query("insert into db_acount values($acount,1010615,1011778,'".AddSlashes(pg_result($resaco,$conresaco,'ed169_diario_classe_bncc_habilidade'))."','$this->ed169_diario_classe_bncc_habilidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed169_bnccreferencial"]) || $this->ed169_bnccreferencial != "")
             $resac = db_query("insert into db_acount values($acount,1010615,1011779,'".AddSlashes(pg_result($resaco,$conresaco,'ed169_bnccreferencial'))."','$this->ed169_bnccreferencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Habilidade do referencial não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed169_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Habilidade do referencial não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed169_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed169_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }

    public function excluir($ed169_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed169_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1011777,'$ed169_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010615,1011777,'','".AddSlashes(pg_result($resaco,$iresaco,'ed169_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010615,1011778,'','".AddSlashes(pg_result($resaco,$iresaco,'ed169_diario_classe_bncc_habilidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010615,1011779,'','".AddSlashes(pg_result($resaco,$iresaco,'ed169_bnccreferencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from diario_classe_bncc_habilidade_referencial
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed169_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed169_codigo = $ed169_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Habilidade do referencial não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed169_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Habilidade do referencial não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed169_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ed169_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:diario_classe_bncc_habilidade_referencial";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($ed169_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from diario_classe_bncc_habilidade_referencial ";
     $sql .= "      inner join diario_classe_bncc_habilidade  on  diario_classe_bncc_habilidade.ed156_codigo = diario_classe_bncc_habilidade_referencial.ed169_diario_classe_bncc_habilidade";
     $sql .= "      inner join bnccreferencial  on  bnccreferencial.ed168_codigo = diario_classe_bncc_habilidade_referencial.ed169_bnccreferencial";
     $sql .= "      inner join bnccdisciplinas  on  bnccdisciplinas.ed149_sequencial = diario_classe_bncc_habilidade.ed156_bnccdisciplinas";
     $sql .= "      inner join diario_classe_bncc  on  diario_classe_bncc.ed155_codigo = diario_classe_bncc_habilidade.ed156_diario_classe_bncc";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed169_codigo)) {
         $sql2 .= " where diario_classe_bncc_habilidade_referencial.ed169_codigo = $ed169_codigo ";
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

    public function sql_query_file($ed169_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from diario_classe_bncc_habilidade_referencial ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed169_codigo)){
         $sql2 .= " where diario_classe_bncc_habilidade_referencial.ed169_codigo = $ed169_codigo ";
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
