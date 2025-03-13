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

//MODULO: cadastro
//CLASSE DA ENTIDADE isentaxa
class cl_isentaxa {
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
    public $j56_codigo = 0;
    public $j56_receit = 0;
    public $j56_perc = 0;
    public $j56_iptucadtaxaexe = null;
   // cria propriedade com as variaveis do arquivo
    public $campos = "
                 j56_codigo = int4 = Codigo Isencao
                 j56_receit = int4 = Receita
                 j56_perc = float8 = Percentual
                 j56_iptucadtaxaexe = int4 = Código da Taxa de IPTU no exercicio
                 ";

    public function __construct()
    {
     $this->rotulo = new rotulo("isentaxa");
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
       $this->j56_codigo = ($this->j56_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j56_codigo"]:$this->j56_codigo);
       $this->j56_receit = ($this->j56_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["j56_receit"]:$this->j56_receit);
       $this->j56_perc = ($this->j56_perc == ""?@$GLOBALS["HTTP_POST_VARS"]["j56_perc"]:$this->j56_perc);
       $this->j56_iptucadtaxaexe = ($this->j56_iptucadtaxaexe === "" ? @$GLOBALS["HTTP_POST_VARS"]["j56_iptucadtaxaexe"] : $this->j56_iptucadtaxaexe);
     }else{
       $this->j56_codigo = ($this->j56_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j56_codigo"]:$this->j56_codigo);
       $this->j56_receit = ($this->j56_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["j56_receit"]:$this->j56_receit);
     }
   }

    public function incluir($j56_codigo,$j56_receit)
    {
      $this->atualizacampos();
     if($this->j56_perc == null ){
       $this->erro_sql = " Campo Percentual não informado.";
       $this->erro_campo = "j56_perc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->j56_codigo = $j56_codigo;
       $this->j56_receit = $j56_receit;
     if(($this->j56_codigo == null) || ($this->j56_codigo == "") ){
       $this->erro_sql = " Campo j56_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->j56_receit == null) || ($this->j56_receit == "") ){
       $this->erro_sql = " Campo j56_receit não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into isentaxa(
                                       j56_codigo
                                      ,j56_receit
                                      ,j56_perc
                                      ,j56_iptucadtaxaexe
                       )
                values (
                                $this->j56_codigo
                               ,$this->j56_receit
                               ,$this->j56_perc
                               ,$this->j56_iptucadtaxaexe
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->j56_codigo."-".$this->j56_receit) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->j56_codigo."-".$this->j56_receit) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->j56_codigo."-".$this->j56_receit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

     $resaco = $this->sql_record($this->sql_query_file($this->j56_codigo,$this->j56_receit));
     if(($resaco!=false)||($this->numrows!=0)){

       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,577,'$this->j56_codigo','I')");
       $resac = db_query("insert into db_acountkey values($acount,578,'$this->j56_receit','I')");
       $resac = db_query("insert into db_acount values($acount,113,577,'','".AddSlashes(pg_result($resaco,0,'j56_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,113,578,'','".AddSlashes(pg_result($resaco,0,'j56_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,113,579,'','".AddSlashes(pg_result($resaco,0,'j56_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,113,1010316,'','".AddSlashes(pg_result($resaco,0,'j56_iptucadtaxaexe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }

    public function alterar($j56_codigo=null,$j56_receit=null)
    {
      $this->atualizacampos();
     $sql = " update isentaxa set ";
     $virgula = "";
     if(trim($this->j56_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j56_codigo"])){
       $sql  .= $virgula." j56_codigo = $this->j56_codigo ";
       $virgula = ",";
       if(trim($this->j56_codigo) == null ){
         $this->erro_sql = " Campo Codigo Isencao não informado.";
         $this->erro_campo = "j56_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j56_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j56_receit"])){
       $sql  .= $virgula." j56_receit = $this->j56_receit ";
       $virgula = ",";
       if(trim($this->j56_receit) == null ){
         $this->erro_sql = " Campo Receita não informado.";
         $this->erro_campo = "j56_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j56_perc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j56_perc"])){
       $sql  .= $virgula." j56_perc = $this->j56_perc ";
       $virgula = ",";
       if(trim($this->j56_perc) == null ){
         $this->erro_sql = " Campo Percentual não informado.";
         $this->erro_campo = "j56_perc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j56_iptucadtaxaexe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j56_iptucadtaxaexe"])){
       $sql  .= $virgula." j56_iptucadtaxaexe = $this->j56_iptucadtaxaexe ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($j56_codigo!=null){
       $sql .= " j56_codigo = $j56_codigo";
     }
     if($j56_receit!=null){
       $sql .= " and  j56_receit = $j56_receit";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

     $resaco = $this->sql_record($this->sql_query_file($this->j56_codigo,$this->j56_receit));
     if($this->numrows>0){

       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,577,'$this->j56_codigo','A')");
         $resac = db_query("insert into db_acountkey values($acount,578,'$this->j56_receit','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j56_codigo"]) || $this->j56_codigo != "")
           $resac = db_query("insert into db_acount values($acount,113,577,'".AddSlashes(pg_result($resaco,$conresaco,'j56_codigo'))."','$this->j56_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j56_receit"]) || $this->j56_receit != "")
           $resac = db_query("insert into db_acount values($acount,113,578,'".AddSlashes(pg_result($resaco,$conresaco,'j56_receit'))."','$this->j56_receit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j56_perc"]) || $this->j56_perc != "")
           $resac = db_query("insert into db_acount values($acount,113,579,'".AddSlashes(pg_result($resaco,$conresaco,'j56_perc'))."','$this->j56_perc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j56_iptucadtaxaexe"]) || $this->j56_iptucadtaxaexe != "")
             $resac = db_query("insert into db_acount values($acount,113,1010316,'".AddSlashes(pg_result($resaco,$conresaco,'j56_iptucadtaxaexe'))."','$this->j56_iptucadtaxaexe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j56_codigo."-".$this->j56_receit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j56_codigo."-".$this->j56_receit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->j56_codigo."-".$this->j56_receit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }

    public function excluir($j56_codigo=null,$j56_receit=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

       $resaco = $this->sql_record($this->sql_query_file($j56_codigo,$j56_receit));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){

       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,577,'$j56_codigo','E')");
         $resac = db_query("insert into db_acountkey values($acount,578,'$j56_receit','E')");
         $resac = db_query("insert into db_acount values($acount,113,577,'','".AddSlashes(pg_result($resaco,$iresaco,'j56_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,113,578,'','".AddSlashes(pg_result($resaco,$iresaco,'j56_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,113,579,'','".AddSlashes(pg_result($resaco,$iresaco,'j56_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,113,1010316,'','".AddSlashes(pg_result($resaco,$iresaco,'j56_iptucadtaxaexe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from isentaxa
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($j56_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " j56_codigo = $j56_codigo ";
        }
        if (!empty($j56_receit)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " j56_receit = $j56_receit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j56_codigo."-".$j56_receit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j56_codigo."-".$j56_receit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$j56_codigo."-".$j56_receit;
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
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:isentaxa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($j56_codigo = null,$j56_receit = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= " from isentaxa ";
     $sql .= "      inner join iptuisen  on  iptuisen.j46_codigo = isentaxa.j56_codigo";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = iptuisen.j46_matric";
     $sql .= "      inner join tipoisen  on  tipoisen.j45_tipo = iptuisen.j46_tipo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($j56_codigo)) {
         $sql2 .= " where isentaxa.j56_codigo = $j56_codigo ";
       }
       if (!empty($j56_receit)) {
         if (!empty($sql2)) {
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " isentaxa.j56_receit = $j56_receit ";
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

    public function sql_query_file($j56_codigo = null,$j56_receit = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= " from isentaxa ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($j56_codigo)){
         $sql2 .= " where isentaxa.j56_codigo = $j56_codigo ";
       }
       if (!empty($j56_receit)){
         if ( !empty($sql2) ) {
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         }
         $sql2 .= " isentaxa.j56_receit = $j56_receit ";
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
