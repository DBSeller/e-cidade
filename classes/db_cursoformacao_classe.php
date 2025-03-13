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


class cl_cursoformacao
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
    public $ed94_i_codigo = 0;
    public $ed94_i_codclasse = 0;
    public $ed94_c_descrclasse = null;
    public $ed94_c_codigocenso = null;
    public $ed94_c_descr = null;
    public $ed94_i_grauacademico = 0;
    public $ed94_ativo = 'f';
   // cria propriedade com as variaveis do arquivo
    public $campos = "
                 ed94_i_codigo = int8 = Código
                 ed94_i_codclasse = int4 = Código da Classe
                 ed94_c_descrclasse = char(100) = Descrição da Classe
                 ed94_c_codigocenso = char(10) = Código Censo
                 ed94_c_descr = char(150) = Descriçao do Curso
                 ed94_i_grauacademico = int4 = Grau Academico
                 ed94_ativo = bool = Ativo
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("cursoformacao");
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
       $this->ed94_i_codigo = ($this->ed94_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed94_i_codigo"]:$this->ed94_i_codigo);
       $this->ed94_i_codclasse = ($this->ed94_i_codclasse == ""?@$GLOBALS["HTTP_POST_VARS"]["ed94_i_codclasse"]:$this->ed94_i_codclasse);
       $this->ed94_c_descrclasse = ($this->ed94_c_descrclasse == ""?@$GLOBALS["HTTP_POST_VARS"]["ed94_c_descrclasse"]:$this->ed94_c_descrclasse);
       $this->ed94_c_codigocenso = ($this->ed94_c_codigocenso == ""?@$GLOBALS["HTTP_POST_VARS"]["ed94_c_codigocenso"]:$this->ed94_c_codigocenso);
       $this->ed94_c_descr = ($this->ed94_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["ed94_c_descr"]:$this->ed94_c_descr);
       $this->ed94_i_grauacademico = ($this->ed94_i_grauacademico == ""?@$GLOBALS["HTTP_POST_VARS"]["ed94_i_grauacademico"]:$this->ed94_i_grauacademico);
       $this->ed94_ativo = ($this->ed94_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed94_ativo"]:$this->ed94_ativo);
     }else{
       $this->ed94_i_codigo = ($this->ed94_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed94_i_codigo"]:$this->ed94_i_codigo);
     }
   }

    public function incluir($ed94_i_codigo)
    {
      $this->atualizacampos();
     if($this->ed94_i_codclasse == null ){
       $this->erro_sql = " Campo Código da Classe não informado.";
       $this->erro_campo = "ed94_i_codclasse";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed94_c_descrclasse == null ){
       $this->erro_sql = " Campo Descrição da Classe não informado.";
       $this->erro_campo = "ed94_c_descrclasse";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed94_c_codigocenso == null ){
       $this->erro_sql = " Campo Código Censo não informado.";
       $this->erro_campo = "ed94_c_codigocenso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed94_c_descr == null ){
       $this->erro_sql = " Campo Descriçao do Curso não informado.";
       $this->erro_campo = "ed94_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed94_i_grauacademico == null ){
       $this->erro_sql = " Campo Grau Academico não informado.";
       $this->erro_campo = "ed94_i_grauacademico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed94_ativo == null ){
       $this->erro_sql = " Campo Ativo não informado.";
       $this->erro_campo = "ed94_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed94_i_codigo == "" || $ed94_i_codigo == null ){
       $result = db_query("select nextval('cursoformacao_ed94_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cursoformacao_ed94_i_codigo_seq do campo: ed94_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed94_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from cursoformacao_ed94_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed94_i_codigo)){
         $this->erro_sql = " Campo ed94_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed94_i_codigo = $ed94_i_codigo;
       }
     }
     if(($this->ed94_i_codigo == null) || ($this->ed94_i_codigo == "") ){
       $this->erro_sql = " Campo ed94_i_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cursoformacao(
                                       ed94_i_codigo
                                      ,ed94_i_codclasse
                                      ,ed94_c_descrclasse
                                      ,ed94_c_codigocenso
                                      ,ed94_c_descr
                                      ,ed94_i_grauacademico
                                      ,ed94_ativo
                       )
                values (
                                $this->ed94_i_codigo
                               ,$this->ed94_i_codclasse
                               ,'$this->ed94_c_descrclasse'
                               ,'$this->ed94_c_codigocenso'
                               ,'$this->ed94_c_descr'
                               ,$this->ed94_i_grauacademico
                               ,'$this->ed94_ativo'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tabela Nacional-Curso Formação Superior - CE ($this->ed94_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tabela Nacional-Curso Formação Superior - CE já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tabela Nacional-Curso Formação Superior - CE ($this->ed94_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed94_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed94_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008953,'$this->ed94_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010152,1008953,'','".AddSlashes(pg_result($resaco,0,'ed94_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010152,13430,'','".AddSlashes(pg_result($resaco,0,'ed94_i_codclasse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010152,13431,'','".AddSlashes(pg_result($resaco,0,'ed94_c_descrclasse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010152,13432,'','".AddSlashes(pg_result($resaco,0,'ed94_c_codigocenso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010152,1008954,'','".AddSlashes(pg_result($resaco,0,'ed94_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010152,19051,'','".AddSlashes(pg_result($resaco,0,'ed94_i_grauacademico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010152,1011266,'','".AddSlashes(pg_result($resaco,0,'ed94_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }

    public function alterar($ed94_i_codigo=null)
    {
      $this->atualizacampos();
     $sql = " update cursoformacao set ";
     $virgula = "";
     if(trim($this->ed94_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed94_i_codigo"])){
       $sql  .= $virgula." ed94_i_codigo = $this->ed94_i_codigo ";
       $virgula = ",";
       if(trim($this->ed94_i_codigo) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed94_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed94_i_codclasse)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed94_i_codclasse"])){
       $sql  .= $virgula." ed94_i_codclasse = $this->ed94_i_codclasse ";
       $virgula = ",";
       if(trim($this->ed94_i_codclasse) == null ){
         $this->erro_sql = " Campo Código da Classe não informado.";
         $this->erro_campo = "ed94_i_codclasse";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed94_c_descrclasse)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed94_c_descrclasse"])){
       $sql  .= $virgula." ed94_c_descrclasse = '$this->ed94_c_descrclasse' ";
       $virgula = ",";
       if(trim($this->ed94_c_descrclasse) == null ){
         $this->erro_sql = " Campo Descrição da Classe não informado.";
         $this->erro_campo = "ed94_c_descrclasse";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed94_c_codigocenso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed94_c_codigocenso"])){
       $sql  .= $virgula." ed94_c_codigocenso = '$this->ed94_c_codigocenso' ";
       $virgula = ",";
       if(trim($this->ed94_c_codigocenso) == null ){
         $this->erro_sql = " Campo Código Censo não informado.";
         $this->erro_campo = "ed94_c_codigocenso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed94_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed94_c_descr"])){
       $sql  .= $virgula." ed94_c_descr = '$this->ed94_c_descr' ";
       $virgula = ",";
       if(trim($this->ed94_c_descr) == null ){
         $this->erro_sql = " Campo Descriçao do Curso não informado.";
         $this->erro_campo = "ed94_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed94_i_grauacademico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed94_i_grauacademico"])){
       $sql  .= $virgula." ed94_i_grauacademico = $this->ed94_i_grauacademico ";
       $virgula = ",";
       if(trim($this->ed94_i_grauacademico) == null ){
         $this->erro_sql = " Campo Grau Academico não informado.";
         $this->erro_campo = "ed94_i_grauacademico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed94_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed94_ativo"])){
       $sql  .= $virgula." ed94_ativo = '$this->ed94_ativo' ";
       $virgula = ",";
       if(trim($this->ed94_ativo) == null ){
         $this->erro_sql = " Campo Ativo não informado.";
         $this->erro_campo = "ed94_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed94_i_codigo!=null){
       $sql .= " ed94_i_codigo = $this->ed94_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed94_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008953,'$this->ed94_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed94_i_codigo"]) || $this->ed94_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010152,1008953,'".AddSlashes(pg_result($resaco,$conresaco,'ed94_i_codigo'))."','$this->ed94_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed94_i_codclasse"]) || $this->ed94_i_codclasse != "")
             $resac = db_query("insert into db_acount values($acount,1010152,13430,'".AddSlashes(pg_result($resaco,$conresaco,'ed94_i_codclasse'))."','$this->ed94_i_codclasse',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed94_c_descrclasse"]) || $this->ed94_c_descrclasse != "")
             $resac = db_query("insert into db_acount values($acount,1010152,13431,'".AddSlashes(pg_result($resaco,$conresaco,'ed94_c_descrclasse'))."','$this->ed94_c_descrclasse',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed94_c_codigocenso"]) || $this->ed94_c_codigocenso != "")
             $resac = db_query("insert into db_acount values($acount,1010152,13432,'".AddSlashes(pg_result($resaco,$conresaco,'ed94_c_codigocenso'))."','$this->ed94_c_codigocenso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed94_c_descr"]) || $this->ed94_c_descr != "")
             $resac = db_query("insert into db_acount values($acount,1010152,1008954,'".AddSlashes(pg_result($resaco,$conresaco,'ed94_c_descr'))."','$this->ed94_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed94_i_grauacademico"]) || $this->ed94_i_grauacademico != "")
             $resac = db_query("insert into db_acount values($acount,1010152,19051,'".AddSlashes(pg_result($resaco,$conresaco,'ed94_i_grauacademico'))."','$this->ed94_i_grauacademico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed94_ativo"]) || $this->ed94_ativo != "")
             $resac = db_query("insert into db_acount values($acount,1010152,1011266,'".AddSlashes(pg_result($resaco,$conresaco,'ed94_ativo'))."','$this->ed94_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela Nacional-Curso Formação Superior - CE não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed94_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tabela Nacional-Curso Formação Superior - CE não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed94_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->ed94_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }

    public function excluir($ed94_i_codigo=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed94_i_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1008953,'$ed94_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010152,1008953,'','".AddSlashes(pg_result($resaco,$iresaco,'ed94_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010152,13430,'','".AddSlashes(pg_result($resaco,$iresaco,'ed94_i_codclasse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010152,13431,'','".AddSlashes(pg_result($resaco,$iresaco,'ed94_c_descrclasse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010152,13432,'','".AddSlashes(pg_result($resaco,$iresaco,'ed94_c_codigocenso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010152,1008954,'','".AddSlashes(pg_result($resaco,$iresaco,'ed94_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010152,19051,'','".AddSlashes(pg_result($resaco,$iresaco,'ed94_i_grauacademico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010152,1011266,'','".AddSlashes(pg_result($resaco,$iresaco,'ed94_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from cursoformacao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed94_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed94_i_codigo = $ed94_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela Nacional-Curso Formação Superior - CE não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed94_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tabela Nacional-Curso Formação Superior - CE não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed94_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$ed94_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:cursoformacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($ed94_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from cursoformacao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed94_i_codigo)) {
         $sql2 .= " where cursoformacao.ed94_i_codigo = $ed94_i_codigo ";
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

    public function sql_query_file($ed94_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from cursoformacao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed94_i_codigo)){
         $sql2 .= " where cursoformacao.ed94_i_codigo = $ed94_i_codigo ";
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
