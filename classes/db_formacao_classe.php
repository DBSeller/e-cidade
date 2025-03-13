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

/**
 * Class cl_formacao
 */
class cl_formacao
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
    public $ed27_i_codigo = 0;
    public $ed27_i_rechumano = 0;
    public $ed27_i_cursoformacao = 0;
    public $ed27_c_situacao = null;
    public $ed27_i_licenciatura = 0;
    public $ed27_i_anoconclusao = 0;
    public $ed27_i_censoinstsuperior = 0;
    public $ed27_i_anoinicio = 0;
    public $ed27_i_formacaopedag = 0;
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 ed27_i_codigo = int8 = Código
                 ed27_i_rechumano = int8 = Matrícula
                 ed27_i_cursoformacao = int8 = Curso
                 ed27_c_situacao = char(15) = Situação
                 ed27_i_licenciatura = int4 = Licenciatura
                 ed27_i_anoconclusao = int4 = Ano Conclusão
                 ed27_i_censoinstsuperior = int4 = Instituição
                 ed27_i_anoinicio = int4 = Ano Início
                 ed27_i_formacaopedag = int4 = Formação/Compl. Pedagógica
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("formacao");
        $this->pagina_retorno = basename($_SERVER['PHP_SELF']);
    }

    public function erro($mostra, $retorna)
    {
        if (($this->erro_status == "0") || ($mostra == true && $this->erro_status != null)) {
            echo "<script>alert(\"" . $this->erro_msg . "\")</script>";
            if ($retorna == true) {
                echo "<script>location.href='" . $this->pagina_retorno . "'</script>";
            }
        }
    }

    public function atualizacampos($exclusao = false)
    {
        if ($exclusao == false) {
            $this->ed27_i_codigo = ($this->ed27_i_codigo == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed27_i_codigo"] : $this->ed27_i_codigo);
            $this->ed27_i_rechumano = ($this->ed27_i_rechumano == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed27_i_rechumano"] : $this->ed27_i_rechumano);
            $this->ed27_i_cursoformacao = ($this->ed27_i_cursoformacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed27_i_cursoformacao"] : $this->ed27_i_cursoformacao);
            $this->ed27_c_situacao = ($this->ed27_c_situacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed27_c_situacao"] : $this->ed27_c_situacao);
            $this->ed27_i_licenciatura = ($this->ed27_i_licenciatura == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed27_i_licenciatura"] : $this->ed27_i_licenciatura);
            $this->ed27_i_anoconclusao = ($this->ed27_i_anoconclusao == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed27_i_anoconclusao"] : $this->ed27_i_anoconclusao);
            $this->ed27_i_censoinstsuperior = ($this->ed27_i_censoinstsuperior == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed27_i_censoinstsuperior"] : $this->ed27_i_censoinstsuperior);
            $this->ed27_i_anoinicio = ($this->ed27_i_anoinicio == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed27_i_anoinicio"] : $this->ed27_i_anoinicio);
            $this->ed27_i_formacaopedag = ($this->ed27_i_formacaopedag == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed27_i_formacaopedag"] : $this->ed27_i_formacaopedag);
        } else {
            $this->ed27_i_codigo = ($this->ed27_i_codigo == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed27_i_codigo"] : $this->ed27_i_codigo);
        }
    }

    public function incluir($ed27_i_codigo)
    {
        $this->atualizacampos();
        if ($this->ed27_i_rechumano == null) {
            $this->erro_sql = " Campo Matrícula não informado.";
            $this->erro_campo = "ed27_i_rechumano";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed27_i_cursoformacao == null) {
            $this->erro_sql = " Campo Curso não informado.";
            $this->erro_campo = "ed27_i_cursoformacao";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed27_c_situacao == null) {
            $this->erro_sql = " Campo Situação não informado.";
            $this->erro_campo = "ed27_c_situacao";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed27_i_licenciatura == null) {
            $this->erro_sql = " Campo Licenciatura não informado.";
            $this->erro_campo = "ed27_i_licenciatura";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed27_i_anoconclusao == null) {
            $this->ed27_i_anoconclusao = "0";
        }
        if ($this->ed27_i_censoinstsuperior == null) {
            $this->erro_sql = " Campo Instituição não informado.";
            $this->erro_campo = "ed27_i_censoinstsuperior";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed27_i_anoinicio == null) {
            $this->ed27_i_anoinicio = "null";
        }
        if ($this->ed27_i_formacaopedag == null) {
            $this->ed27_i_formacaopedag = "null";
        }
        if ($ed27_i_codigo == "" || $ed27_i_codigo == null) {
            $result = db_query("select nextval('formacao_ed27_i_codigo_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: formacao_ed27_i_codigo_seq do campo: ed27_i_codigo";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->ed27_i_codigo = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from formacao_ed27_i_codigo_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $ed27_i_codigo)) {
                $this->erro_sql = " Campo ed27_i_codigo maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->ed27_i_codigo = $ed27_i_codigo;
            }
        }
        if (($this->ed27_i_codigo == null) || ($this->ed27_i_codigo == "")) {
            $this->erro_sql = " Campo ed27_i_codigo não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into formacao(
                                       ed27_i_codigo
                                      ,ed27_i_rechumano
                                      ,ed27_i_cursoformacao
                                      ,ed27_c_situacao
                                      ,ed27_i_licenciatura
                                      ,ed27_i_anoconclusao
                                      ,ed27_i_censoinstsuperior
                                      ,ed27_i_anoinicio
                                      ,ed27_i_formacaopedag
                       )
                values (
                                $this->ed27_i_codigo
                               ,$this->ed27_i_rechumano
                               ,$this->ed27_i_cursoformacao
                               ,'$this->ed27_c_situacao'
                               ,$this->ed27_i_licenciatura
                               ,$this->ed27_i_anoconclusao
                               ,$this->ed27_i_censoinstsuperior
                               ,$this->ed27_i_anoinicio
                               ,$this->ed27_i_formacaopedag
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = "Formação do Recurso Humano ($this->ed27_i_codigo) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "Formação do Recurso Humano já Cadastrado";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql = "Formação do Recurso Humano ($this->ed27_i_codigo) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
        $this->erro_sql .= "Valores : " . $this->ed27_i_codigo;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->ed27_i_codigo));
            if (($resaco != false) || ($this->numrows != 0)) {

                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,1008520,'$this->ed27_i_codigo','I')");
                $resac = db_query("insert into db_acount values($acount,1010089,1008520,'','" . AddSlashes(pg_result($resaco, 0, 'ed27_i_codigo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010089,1008521,'','" . AddSlashes(pg_result($resaco, 0, 'ed27_i_rechumano')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010089,1008522,'','" . AddSlashes(pg_result($resaco, 0, 'ed27_i_cursoformacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010089,1008524,'','" . AddSlashes(pg_result($resaco, 0, 'ed27_c_situacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010089,13794,'','" . AddSlashes(pg_result($resaco, 0, 'ed27_i_licenciatura')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010089,13795,'','" . AddSlashes(pg_result($resaco, 0, 'ed27_i_anoconclusao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010089,13796,'','" . AddSlashes(pg_result($resaco, 0, 'ed27_i_censoinstsuperior')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010089,17988,'','" . AddSlashes(pg_result($resaco, 0, 'ed27_i_anoinicio')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010089,17990,'','" . AddSlashes(pg_result($resaco, 0, 'ed27_i_formacaopedag')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
    }

    public function alterar($ed27_i_codigo = null)
    {
        $this->atualizacampos();
        $sql = " update formacao set ";
        $virgula = "";
        if (trim($this->ed27_i_codigo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed27_i_codigo"])) {
            $sql .= $virgula . " ed27_i_codigo = $this->ed27_i_codigo ";
            $virgula = ",";
            if (trim($this->ed27_i_codigo) == null) {
                $this->erro_sql = " Campo Código não informado.";
                $this->erro_campo = "ed27_i_codigo";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed27_i_rechumano) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed27_i_rechumano"])) {
            $sql .= $virgula . " ed27_i_rechumano = $this->ed27_i_rechumano ";
            $virgula = ",";
            if (trim($this->ed27_i_rechumano) == null) {
                $this->erro_sql = " Campo Matrícula não informado.";
                $this->erro_campo = "ed27_i_rechumano";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed27_i_cursoformacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed27_i_cursoformacao"])) {
            $sql .= $virgula . " ed27_i_cursoformacao = $this->ed27_i_cursoformacao ";
            $virgula = ",";
            if (trim($this->ed27_i_cursoformacao) == null) {
                $this->erro_sql = " Campo Curso não informado.";
                $this->erro_campo = "ed27_i_cursoformacao";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed27_c_situacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed27_c_situacao"])) {
            $sql .= $virgula . " ed27_c_situacao = '$this->ed27_c_situacao' ";
            $virgula = ",";
            if (trim($this->ed27_c_situacao) == null) {
                $this->erro_sql = " Campo Situação não informado.";
                $this->erro_campo = "ed27_c_situacao";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed27_i_licenciatura) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed27_i_licenciatura"])) {
            $sql .= $virgula . " ed27_i_licenciatura = $this->ed27_i_licenciatura ";
            $virgula = ",";
            if (trim($this->ed27_i_licenciatura) == null) {
                $this->erro_sql = " Campo Licenciatura não informado.";
                $this->erro_campo = "ed27_i_licenciatura";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed27_i_anoconclusao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed27_i_anoconclusao"])) {
            if (trim($this->ed27_i_anoconclusao) == "" && isset($GLOBALS["HTTP_POST_VARS"]["ed27_i_anoconclusao"])) {
                $this->ed27_i_anoconclusao = "0";
            }
            $sql .= $virgula . " ed27_i_anoconclusao = $this->ed27_i_anoconclusao ";
            $virgula = ",";
        }
        if (trim($this->ed27_i_censoinstsuperior) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed27_i_censoinstsuperior"])) {
            $sql .= $virgula . " ed27_i_censoinstsuperior = $this->ed27_i_censoinstsuperior ";
            $virgula = ",";
            if (trim($this->ed27_i_censoinstsuperior) == null) {
                $this->erro_sql = " Campo Instituição não informado.";
                $this->erro_campo = "ed27_i_censoinstsuperior";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed27_i_anoinicio) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed27_i_anoinicio"])) {
            if (trim($this->ed27_i_anoinicio) == "" && isset($GLOBALS["HTTP_POST_VARS"]["ed27_i_anoinicio"])) {
                $this->ed27_i_anoinicio = "null";
            }
            $sql .= $virgula . " ed27_i_anoinicio = $this->ed27_i_anoinicio ";
            $virgula = ",";
        }
        if (trim($this->ed27_i_formacaopedag) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed27_i_formacaopedag"])) {
            if (trim($this->ed27_i_formacaopedag) == "" && isset($GLOBALS["HTTP_POST_VARS"]["ed27_i_formacaopedag"])) {
                $this->ed27_i_formacaopedag = "null";
            }
            $sql .= $virgula . " ed27_i_formacaopedag = $this->ed27_i_formacaopedag ";
            $virgula = ",";
        }
        $sql .= " where ";
        if ($ed27_i_codigo != null) {
            $sql .= " ed27_i_codigo = $this->ed27_i_codigo";
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->ed27_i_codigo));
            if ($this->numrows > 0) {

                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1008520,'$this->ed27_i_codigo','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed27_i_codigo"]) || $this->ed27_i_codigo != "")
                        $resac = db_query("insert into db_acount values($acount,1010089,1008520,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed27_i_codigo')) . "','$this->ed27_i_codigo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed27_i_rechumano"]) || $this->ed27_i_rechumano != "")
                        $resac = db_query("insert into db_acount values($acount,1010089,1008521,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed27_i_rechumano')) . "','$this->ed27_i_rechumano'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed27_i_cursoformacao"]) || $this->ed27_i_cursoformacao != "")
                        $resac = db_query("insert into db_acount values($acount,1010089,1008522,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed27_i_cursoformacao')) . "','$this->ed27_i_cursoformacao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed27_c_situacao"]) || $this->ed27_c_situacao != "")
                        $resac = db_query("insert into db_acount values($acount,1010089,1008524,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed27_c_situacao')) . "','$this->ed27_c_situacao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed27_i_licenciatura"]) || $this->ed27_i_licenciatura != "")
                        $resac = db_query("insert into db_acount values($acount,1010089,13794,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed27_i_licenciatura')) . "','$this->ed27_i_licenciatura'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed27_i_anoconclusao"]) || $this->ed27_i_anoconclusao != "")
                        $resac = db_query("insert into db_acount values($acount,1010089,13795,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed27_i_anoconclusao')) . "','$this->ed27_i_anoconclusao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed27_i_censoinstsuperior"]) || $this->ed27_i_censoinstsuperior != "")
                        $resac = db_query("insert into db_acount values($acount,1010089,13796,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed27_i_censoinstsuperior')) . "','$this->ed27_i_censoinstsuperior'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed27_i_anoinicio"]) || $this->ed27_i_anoinicio != "")
                        $resac = db_query("insert into db_acount values($acount,1010089,17988,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed27_i_anoinicio')) . "','$this->ed27_i_anoinicio'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed27_i_formacaopedag"]) || $this->ed27_i_formacaopedag != "")
                        $resac = db_query("insert into db_acount values($acount,1010089,17990,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed27_i_formacaopedag')) . "','$this->ed27_i_formacaopedag'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Formação do Recurso Humano não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->ed27_i_codigo;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Formação do Recurso Humano não foi Alterado. Alteração Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->ed27_i_codigo;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $this->ed27_i_codigo;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($ed27_i_codigo = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            if (empty($dbwhere)) {

                $resaco = $this->sql_record($this->sql_query_file($ed27_i_codigo));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1008520,'$ed27_i_codigo','E')");
                    $resac = db_query("insert into db_acount values($acount,1010089,1008520,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed27_i_codigo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010089,1008521,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed27_i_rechumano')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010089,1008522,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed27_i_cursoformacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010089,1008524,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed27_c_situacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010089,13794,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed27_i_licenciatura')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010089,13795,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed27_i_anoconclusao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010089,13796,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed27_i_censoinstsuperior')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010089,17988,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed27_i_anoinicio')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010089,17990,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed27_i_formacaopedag')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " delete from formacao
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed27_i_codigo)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " ed27_i_codigo = $ed27_i_codigo ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Formação do Recurso Humano não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $ed27_i_codigo;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Formação do Recurso Humano não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $ed27_i_codigo;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $ed27_i_codigo;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
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
            $this->numrows = 0;
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Erro ao selecionar os registros.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $this->numrows = pg_num_rows($result);
        if ($this->numrows == 0) {
            $this->erro_banco = "";
            $this->erro_sql = "Record Vazio na Tabela:formacao";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query($ed27_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select {$campos}";
        $sql .= "  from formacao ";
        $sql .= "      inner join censoinstsuperior  on  censoinstsuperior.ed257_i_codigo = formacao.ed27_i_censoinstsuperior";
        $sql .= "      inner join rechumano  on  rechumano.ed20_i_codigo = formacao.ed27_i_rechumano";
        $sql .= "      inner join cursoformacao  on  cursoformacao.ed94_i_codigo = formacao.ed27_i_cursoformacao";
        $sql .= "      inner join censomunic  on  censomunic.ed261_i_codigo = censoinstsuperior.ed257_i_censomunic";
        $sql .= "      left  join rhregime  on  rhregime.rh30_codreg = rechumano.ed20_i_rhregime";
        $sql .= "      inner join pais  on  pais.ed228_i_codigo = rechumano.ed20_i_pais";
        $sql .= "      left  join censouf  on  censouf.ed260_i_codigo = rechumano.ed20_i_censoufcert and  censouf.ed260_i_codigo = rechumano.ed20_i_censoufender and  censouf.ed260_i_codigo = rechumano.ed20_i_censoufnat and  censouf.ed260_i_codigo = rechumano.ed20_i_censoufident";
        $sql .= "      left  join censomunic  as a on   a.ed261_i_codigo = rechumano.ed20_i_censomunicender and   a.ed261_i_codigo = rechumano.ed20_i_censomunicnat";
        $sql .= "      left  join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = rechumano.ed20_i_censoorgemiss";
        $sql .= "      left  join rechumano  as b on   b.ed20_i_codigo = rechumano.ed20_i_censocartorio";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed27_i_codigo)) {
                $sql2 .= " where formacao.ed27_i_codigo = $ed27_i_codigo ";
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

    public function sql_query_file($ed27_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select {$campos} ";
        $sql .= "  from formacao ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed27_i_codigo)) {
                $sql2 .= " where formacao.ed27_i_codigo = $ed27_i_codigo ";
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
