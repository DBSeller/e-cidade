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

class cl_bnccensinofundamentaloriginal
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
    public $ed166_sequencial = 0;
    public $ed166_disciplina = null;
    public $ed166_etapa = null;
    public $ed166_codigo = null;
    public $ed166_unidade_tematica = null;
    public $ed166_objeto_conhecimento = null;
    public $ed166_habilidade = null;
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 ed166_sequencial = int4 = Código PK
                 ed166_disciplina = varchar(100) = Disciplina BNCC
                 ed166_etapa = varchar(100) = Etapa BNCC
                 ed166_codigo = varchar(8) = Código BNCC
                 ed166_unidade_tematica = varchar(150) = Unidade Temática
                 ed166_objeto_conhecimento = text = Objeto de Conhecimento
                 ed166_habilidade = text = Habilidade
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("bnccensinofundamentaloriginal");
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
            $this->ed166_sequencial = ($this->ed166_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed166_sequencial"] : $this->ed166_sequencial);
            $this->ed166_disciplina = ($this->ed166_disciplina == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed166_disciplina"] : $this->ed166_disciplina);
            $this->ed166_etapa = ($this->ed166_etapa == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed166_etapa"] : $this->ed166_etapa);
            $this->ed166_codigo = ($this->ed166_codigo == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed166_codigo"] : $this->ed166_codigo);
            $this->ed166_unidade_tematica = ($this->ed166_unidade_tematica == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed166_unidade_tematica"] : $this->ed166_unidade_tematica);
            $this->ed166_objeto_conhecimento = ($this->ed166_objeto_conhecimento == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed166_objeto_conhecimento"] : $this->ed166_objeto_conhecimento);
            $this->ed166_habilidade = ($this->ed166_habilidade == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed166_habilidade"] : $this->ed166_habilidade);
        } else {
            $this->ed166_sequencial = ($this->ed166_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed166_sequencial"] : $this->ed166_sequencial);
        }
    }

    public function incluir($ed166_sequencial)
    {
        $this->atualizacampos();
        if ($this->ed166_disciplina == null) {
            $this->erro_sql = " Campo Disciplina BNCC não informado.";
            $this->erro_campo = "ed166_disciplina";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed166_etapa == null) {
            $this->erro_sql = " Campo Etapa BNCC não informado.";
            $this->erro_campo = "ed166_etapa";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed166_codigo == null) {
            $this->erro_sql = " Campo Código BNCC não informado.";
            $this->erro_campo = "ed166_codigo";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed166_unidade_tematica == null) {
            $this->erro_sql = " Campo Unidade Temática não informado.";
            $this->erro_campo = "ed166_unidade_tematica";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed166_objeto_conhecimento == null) {
            $this->erro_sql = " Campo Objeto de Conhecimento não informado.";
            $this->erro_campo = "ed166_objeto_conhecimento";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed166_habilidade == null) {
            $this->erro_sql = " Campo Habilidade não informado.";
            $this->erro_campo = "ed166_habilidade";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($ed166_sequencial == "" || $ed166_sequencial == null) {
            $result = db_query("select nextval('bnccensinofundamentaloriginal_ed166_sequencial_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: bnccensinofundamentaloriginal_ed166_sequencial_seq do campo: ed166_sequencial";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->ed166_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from bnccensinofundamentaloriginal_ed166_sequencial_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $ed166_sequencial)) {
                $this->erro_sql = " Campo ed166_sequencial maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->ed166_sequencial = $ed166_sequencial;
            }
        }
        if (($this->ed166_sequencial == null) || ($this->ed166_sequencial == "")) {
            $this->erro_sql = " Campo ed166_sequencial não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into bnccensinofundamentaloriginal(
                                       ed166_sequencial
                                      ,ed166_disciplina
                                      ,ed166_etapa
                                      ,ed166_codigo
                                      ,ed166_unidade_tematica
                                      ,ed166_objeto_conhecimento
                                      ,ed166_habilidade
                       )
                values (
                                $this->ed166_sequencial
                               ,'$this->ed166_disciplina'
                               ,'$this->ed166_etapa'
                               ,'$this->ed166_codigo'
                               ,'$this->ed166_unidade_tematica'
                               ,'$this->ed166_objeto_conhecimento'
                               ,'$this->ed166_habilidade'
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = "Estrutura da BNCC ($this->ed166_sequencial) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "Estrutura da BNCC já Cadastrado";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql = "Estrutura da BNCC ($this->ed166_sequencial) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
        $this->erro_sql .= "Valores : " . $this->ed166_sequencial;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->ed166_sequencial));
            if (($resaco != false) || ($this->numrows != 0)) {

                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,1011759,'$this->ed166_sequencial','I')");
                $resac = db_query("insert into db_acount values($acount,1010612,1011759,'','" . AddSlashes(pg_result($resaco, 0, 'ed166_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010612,1011760,'','" . AddSlashes(pg_result($resaco, 0, 'ed166_disciplina')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010612,1011761,'','" . AddSlashes(pg_result($resaco, 0, 'ed166_etapa')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010612,1011762,'','" . AddSlashes(pg_result($resaco, 0, 'ed166_codigo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010612,1011763,'','" . AddSlashes(pg_result($resaco, 0, 'ed166_unidade_tematica')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010612,1011764,'','" . AddSlashes(pg_result($resaco, 0, 'ed166_objeto_conhecimento')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010612,1011765,'','" . AddSlashes(pg_result($resaco, 0, 'ed166_habilidade')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
    }

    public function alterar($ed166_sequencial = null)
    {
        $this->atualizacampos();
        $sql = " update bnccensinofundamentaloriginal set ";
        $virgula = "";
        if (trim($this->ed166_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed166_sequencial"])) {
            $sql .= $virgula . " ed166_sequencial = $this->ed166_sequencial ";
            $virgula = ",";
            if (trim($this->ed166_sequencial) == null) {
                $this->erro_sql = " Campo Código PK não informado.";
                $this->erro_campo = "ed166_sequencial";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed166_disciplina) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed166_disciplina"])) {
            $sql .= $virgula . " ed166_disciplina = '$this->ed166_disciplina' ";
            $virgula = ",";
            if (trim($this->ed166_disciplina) == null) {
                $this->erro_sql = " Campo Disciplina BNCC não informado.";
                $this->erro_campo = "ed166_disciplina";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed166_etapa) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed166_etapa"])) {
            $sql .= $virgula . " ed166_etapa = '$this->ed166_etapa' ";
            $virgula = ",";
            if (trim($this->ed166_etapa) == null) {
                $this->erro_sql = " Campo Etapa BNCC não informado.";
                $this->erro_campo = "ed166_etapa";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed166_codigo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed166_codigo"])) {
            $sql .= $virgula . " ed166_codigo = '$this->ed166_codigo' ";
            $virgula = ",";
            if (trim($this->ed166_codigo) == null) {
                $this->erro_sql = " Campo Código BNCC não informado.";
                $this->erro_campo = "ed166_codigo";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed166_unidade_tematica) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed166_unidade_tematica"])) {
            $sql .= $virgula . " ed166_unidade_tematica = '$this->ed166_unidade_tematica' ";
            $virgula = ",";
            if (trim($this->ed166_unidade_tematica) == null) {
                $this->erro_sql = " Campo Unidade Temática não informado.";
                $this->erro_campo = "ed166_unidade_tematica";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed166_objeto_conhecimento) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed166_objeto_conhecimento"])) {
            $sql .= $virgula . " ed166_objeto_conhecimento = '$this->ed166_objeto_conhecimento' ";
            $virgula = ",";
            if (trim($this->ed166_objeto_conhecimento) == null) {
                $this->erro_sql = " Campo Objeto de Conhecimento não informado.";
                $this->erro_campo = "ed166_objeto_conhecimento";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed166_habilidade) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed166_habilidade"])) {
            $sql .= $virgula . " ed166_habilidade = '$this->ed166_habilidade' ";
            $virgula = ",";
            if (trim($this->ed166_habilidade) == null) {
                $this->erro_sql = " Campo Habilidade não informado.";
                $this->erro_campo = "ed166_habilidade";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if ($ed166_sequencial != null) {
            $sql .= " ed166_sequencial = $this->ed166_sequencial";
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->ed166_sequencial));
            if ($this->numrows > 0) {

                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1011759,'$this->ed166_sequencial','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed166_sequencial"]) || $this->ed166_sequencial != "")
                        $resac = db_query("insert into db_acount values($acount,1010612,1011759,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed166_sequencial')) . "','$this->ed166_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed166_disciplina"]) || $this->ed166_disciplina != "")
                        $resac = db_query("insert into db_acount values($acount,1010612,1011760,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed166_disciplina')) . "','$this->ed166_disciplina'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed166_etapa"]) || $this->ed166_etapa != "")
                        $resac = db_query("insert into db_acount values($acount,1010612,1011761,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed166_etapa')) . "','$this->ed166_etapa'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed166_codigo"]) || $this->ed166_codigo != "")
                        $resac = db_query("insert into db_acount values($acount,1010612,1011762,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed166_codigo')) . "','$this->ed166_codigo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed166_unidade_tematica"]) || $this->ed166_unidade_tematica != "")
                        $resac = db_query("insert into db_acount values($acount,1010612,1011763,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed166_unidade_tematica')) . "','$this->ed166_unidade_tematica'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed166_objeto_conhecimento"]) || $this->ed166_objeto_conhecimento != "")
                        $resac = db_query("insert into db_acount values($acount,1010612,1011764,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed166_objeto_conhecimento')) . "','$this->ed166_objeto_conhecimento'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed166_habilidade"]) || $this->ed166_habilidade != "")
                        $resac = db_query("insert into db_acount values($acount,1010612,1011765,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed166_habilidade')) . "','$this->ed166_habilidade'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Estrutura da BNCC não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->ed166_sequencial;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Estrutura da BNCC não foi Alterado. Alteração Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->ed166_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $this->ed166_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($ed166_sequencial = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            if (empty($dbwhere)) {

                $resaco = $this->sql_record($this->sql_query_file($ed166_sequencial));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1011759,'$ed166_sequencial','E')");
                    $resac = db_query("insert into db_acount values($acount,1010612,1011759,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed166_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010612,1011760,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed166_disciplina')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010612,1011761,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed166_etapa')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010612,1011762,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed166_codigo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010612,1011763,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed166_unidade_tematica')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010612,1011764,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed166_objeto_conhecimento')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010612,1011765,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed166_habilidade')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " delete from bnccensinofundamentaloriginal
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed166_sequencial)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " ed166_sequencial = $ed166_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Estrutura da BNCC não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $ed166_sequencial;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Estrutura da BNCC não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $ed166_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $ed166_sequencial;
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
            $this->erro_sql = "Record Vazio na Tabela:bnccensinofundamentaloriginal";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query($ed166_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos}";
        $sql .= "  from bnccensinofundamentaloriginal ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed166_sequencial)) {
                $sql2 .= " where bnccensinofundamentaloriginal.ed166_sequencial = $ed166_sequencial ";
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

    public function sql_query_file($ed166_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos} ";
        $sql .= "  from bnccensinofundamentaloriginal ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed166_sequencial)) {
                $sql2 .= " where bnccensinofundamentaloriginal.ed166_sequencial = $ed166_sequencial ";
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

    public function sql_query_completa($ed166_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "", $ano)
    {
        $sql = "select {$campos}
                from bnccensinofundamentaloriginal
    left join bnccensinofundamental on bnccensinofundamental.ed148_codigo = bnccensinofundamentaloriginal.ed166_codigo
                and ed148_ano = {$ano}
            ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed166_sequencial)) {
                $sql2 .= " where bnccensinofundamentaloriginal.ed166_sequencial = $ed166_sequencial ";
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
