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

//MODULO: caixa
//CLASSE DA ENTIDADE taborc
class cl_taborc
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
    public $k02_codigo = 0;
    public $k02_anousu = 0;
    public $k02_codrec = 0;
    public $k02_estorc = null;
    public $k02_complemento = 0;
   // cria propriedade com as variaveis do arquivo
    public $campos = "
                 k02_codigo = int4 = Receita
                 k02_anousu = int4 = ano
                 k02_codrec = int4 = Código Reduzido
                 k02_estorc = varchar(15) = Fonte da Receita
                 k02_complemento = int4 = Complemento do Recurso
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("taborc");
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
       $this->k02_codigo = ($this->k02_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_codigo"]:$this->k02_codigo);
       $this->k02_anousu = ($this->k02_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_anousu"]:$this->k02_anousu);
       $this->k02_codrec = ($this->k02_codrec == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_codrec"]:$this->k02_codrec);
       $this->k02_estorc = ($this->k02_estorc == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_estorc"]:$this->k02_estorc);
       $this->k02_complemento = ($this->k02_complemento == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_complemento"]:$this->k02_complemento);
     }else{
       $this->k02_codigo = ($this->k02_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_codigo"]:$this->k02_codigo);
       $this->k02_anousu = ($this->k02_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["k02_anousu"]:$this->k02_anousu);
     }
   }

    public function incluir($k02_anousu,$k02_codigo)
    {
      $this->atualizacampos();
     if($this->k02_codrec == null ){
       $this->erro_sql = " Campo Código Reduzido não informado.";
       $this->erro_campo = "k02_codrec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k02_estorc == null ){
       $this->erro_sql = " Campo Fonte da Receita não informado.";
       $this->erro_campo = "k02_estorc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k02_complemento == null ){
       $this->k02_complemento = "0";
     }
     if($k02_codigo == "" || $k02_codigo == null ){
       $result = db_query("select nextval('tabrec_k02_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tabrec_k02_codigo_seq do campo: k02_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->k02_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from tabrec_k02_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $k02_codigo)){
         $this->erro_sql = " Campo k02_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k02_codigo = $k02_codigo;
       }
     }
     if(($this->k02_anousu == null) || ($this->k02_anousu == "") ){
       $this->erro_sql = " Campo k02_anousu não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->k02_codigo == null) || ($this->k02_codigo == "") ){
       $this->erro_sql = " Campo k02_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into taborc(
                                       k02_codigo
                                      ,k02_anousu
                                      ,k02_codrec
                                      ,k02_estorc
                                      ,k02_complemento
                       )
                values (
                                $this->k02_codigo
                               ,$this->k02_anousu
                               ,$this->k02_codrec
                               ,'$this->k02_estorc'
                               ,$this->k02_complemento
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->k02_anousu."-".$this->k02_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->k02_anousu."-".$this->k02_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->k02_anousu."-".$this->k02_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);

     return true;
   }

    public function alterar($k02_anousu=null,$k02_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update taborc set ";
     $virgula = "";
     if(trim($this->k02_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_codigo"])){
       $sql  .= $virgula." k02_codigo = $this->k02_codigo ";
       $virgula = ",";
       if(trim($this->k02_codigo) == null ){
         $this->erro_sql = " Campo Receita não informado.";
         $this->erro_campo = "k02_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k02_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_anousu"])){
       $sql  .= $virgula." k02_anousu = $this->k02_anousu ";
       $virgula = ",";
       if(trim($this->k02_anousu) == null ){
         $this->erro_sql = " Campo ano não informado.";
         $this->erro_campo = "k02_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k02_codrec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_codrec"])){
       $sql  .= $virgula." k02_codrec = $this->k02_codrec ";
       $virgula = ",";
       if(trim($this->k02_codrec) == null ){
         $this->erro_sql = " Campo Código Reduzido não informado.";
         $this->erro_campo = "k02_codrec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k02_estorc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_estorc"])){
       $sql  .= $virgula." k02_estorc = '$this->k02_estorc' ";
       $virgula = ",";
       if(trim($this->k02_estorc) == null ){
         $this->erro_sql = " Campo Fonte da Receita não informado.";
         $this->erro_campo = "k02_estorc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k02_complemento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k02_complemento"])){
        if(trim($this->k02_complemento)=="" && isset($GLOBALS["HTTP_POST_VARS"]["k02_complemento"])){
           $this->k02_complemento = "0" ;
        }
       $sql  .= $virgula." k02_complemento = $this->k02_complemento ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($k02_anousu!=null){
       $sql .= " k02_anousu = $this->k02_anousu";
     }
     if($k02_codigo!=null){
       $sql .= " and  k02_codigo = $this->k02_codigo";
     }

     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k02_anousu."-".$this->k02_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = " não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k02_anousu."-".$this->k02_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->k02_anousu."-".$this->k02_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }

    public function excluir($k02_anousu=null,$k02_codigo=null, $dbwhere = null)
    {
     $sql = " delete from taborc
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($k02_anousu)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " k02_anousu = $k02_anousu ";
        }
        if (!empty($k02_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " k02_codigo = $k02_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k02_anousu."-".$k02_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = " não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k02_anousu."-".$k02_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$k02_anousu."-".$k02_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:taborc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($k02_anousu = null,$k02_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from taborc ";
     $sql .= "      inner join orcreceita  on  orcreceita.o70_codrec = taborc.k02_codrec
                                          and  orcreceita.o70_anousu = taborc.k02_anousu";
     $sql .= "      inner join db_config  on  db_config.codigo = orcreceita.o70_instit";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcreceita.o70_codigo";
     $sql .= "      inner join orcfontes  on  orcfontes.o57_codfon = orcreceita.o70_codfon and  orcfontes.o57_anousu = orcreceita.o70_anousu";
     $sql .= "      inner join db_config  as a on   a.codigo = orcreceita.o70_instit";
     $sql .= "      inner join orctiporec  as b on   b.o15_codigo = orcreceita.o70_codigo";
     $sql .= "      inner join orcfontes  as c on   c.o57_codfon = orcreceita.o70_codfon and c.o57_anousu = orcreceita.o70_anousu ";
     $sql2 = "";
     if($dbwhere==""){
       if($k02_anousu!=null ){
         $sql2 .= " where taborc.k02_anousu = $k02_anousu ";
       }
       if($k02_codigo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         }
         $sql2 .= " taborc.k02_codigo = $k02_codigo ";
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

    public function sql_query_file($k02_anousu = null,$k02_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from taborc ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($k02_anousu)){
         $sql2 .= " where taborc.k02_anousu = $k02_anousu ";
       }
       if (!empty($k02_codigo)){
         if ( !empty($sql2) ) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         }
         $sql2 .= " taborc.k02_codigo = $k02_codigo ";
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
    public function sql_query_receita($campos = "*", $where = null) {

        $sql  = "  select {$campos} ";
        $sql .= "    from taborc ";
        $sql .= "         inner join orcreceita on orcreceita.o70_codrec = taborc.k02_codrec ";
        $sql .= "                              and orcreceita.o70_anousu = taborc.k02_anousu";
        $sql .= "         inner join orcfontes  on orcfontes.o57_codfon = orcreceita.o70_codfon ";
        $sql .= "                              and orcfontes.o57_anousu = orcreceita.o70_anousu ";
        $sql .= "         inner join conplanoorcamento on conplanoorcamento.c60_codcon = orcreceita.o70_codfon ";
        $sql .= "                                     and conplanoorcamento.c60_anousu = orcreceita.o70_anousu";

        if (!empty($where)) {
            $sql .= " where {$where} ";
        }
        return $sql;
    }

}
