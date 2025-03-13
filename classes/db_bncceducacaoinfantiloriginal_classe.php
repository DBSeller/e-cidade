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

class cl_bncceducacaoinfantiloriginal
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
    public $ed167_sequencial = 0;
    public $ed167_disciplina = null;
    public $ed167_faixa_etaria = null;
    public $ed167_codigo = null;
    public $ed167_habilidade = null;
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 ed167_sequencial = int4 = Código
                 ed167_disciplina = varchar(100) = Disciplina BNCC
                 ed167_faixa_etaria = varchar(100) = Faixa Etaria
                 ed167_codigo = varchar(8) = Código BNCC
                 ed167_habilidade = text = Habilidade
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("bncceducacaoinfantiloriginal");
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
            $this->ed167_sequencial = ($this->ed167_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed167_sequencial"] : $this->ed167_sequencial);
            $this->ed167_disciplina = ($this->ed167_disciplina == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed167_disciplina"] : $this->ed167_disciplina);
            $this->ed167_faixa_etaria = ($this->ed167_faixa_etaria == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed167_faixa_etaria"] : $this->ed167_faixa_etaria);
            $this->ed167_codigo = ($this->ed167_codigo == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed167_codigo"] : $this->ed167_codigo);
            $this->ed167_habilidade = ($this->ed167_habilidade == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed167_habilidade"] : $this->ed167_habilidade);
        } else {
            $this->ed167_sequencial = ($this->ed167_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed167_sequencial"] : $this->ed167_sequencial);
        }
    }

    public function incluir($ed167_sequencial)
    {
        $this->atualizacampos();
        if ($this->ed167_disciplina == null) {
            $this->erro_sql = " Campo Disciplina BNCC não informado.";
            $this->erro_campo = "ed167_disciplina";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed167_faixa_etaria == null) {
            $this->erro_sql = " Campo Faixa Etaria não informado.";
            $this->erro_campo = "ed167_faixa_etaria";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed167_codigo == null) {
            $this->erro_sql = " Campo Código BNCC não informado.";
            $this->erro_campo = "ed167_codigo";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed167_habilidade == null) {
            $this->erro_sql = " Campo Habilidade não informado.";
            $this->erro_campo = "ed167_habilidade";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($ed167_sequencial == "" || $ed167_sequencial == null) {
            $result = db_query("select nextval('bncceducacaoinfantiloriginal_ed167_sequencial_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: bncceducacaoinfantiloriginal_ed167_sequencial_seq do campo: ed167_sequencial";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->ed167_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from bncceducacaoinfantiloriginal_ed167_sequencial_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $ed167_sequencial)) {
                $this->erro_sql = " Campo ed167_sequencial maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->ed167_sequencial = $ed167_sequencial;
            }
        }
        if (($this->ed167_sequencial == null) || ($this->ed167_sequencial == "")) {
            $this->erro_sql = " Campo ed167_sequencial não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into bncceducacaoinfantiloriginal(
                                       ed167_sequencial
                                      ,ed167_disciplina
                                      ,ed167_faixa_etaria
                                      ,ed167_codigo
                                      ,ed167_habilidade
                       )
                values (
                                $this->ed167_sequencial
                               ,'$this->ed167_disciplina'
                               ,'$this->ed167_faixa_etaria'
                               ,'$this->ed167_codigo'
                               ,'$this->ed167_habilidade'
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = "Estrutura da BNCC EI ($this->ed167_sequencial) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "Estrutura da BNCC EI já Cadastrado";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql = "Estrutura da BNCC EI ($this->ed167_sequencial) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
        $this->erro_sql .= "Valores : " . $this->ed167_sequencial;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {
            $resaco = $this->sql_record($this->sql_query_file($this->ed167_sequencial));
            if (($resaco != false) || ($this->numrows != 0)) {
                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,1011766,'$this->ed167_sequencial','I')");
                $resac = db_query("insert into db_acount values($acount,1010613,1011766,'','" . AddSlashes(pg_result($resaco, 0, 'ed167_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010613,1011767,'','" . AddSlashes(pg_result($resaco, 0, 'ed167_disciplina')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010613,1011768,'','" . AddSlashes(pg_result($resaco, 0, 'ed167_faixa_etaria')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010613,1011769,'','" . AddSlashes(pg_result($resaco, 0, 'ed167_codigo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010613,1011770,'','" . AddSlashes(pg_result($resaco, 0, 'ed167_habilidade')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
    }

    public function alterar($ed167_sequencial = null)
    {
        $this->atualizacampos();
        $sql = " update bncceducacaoinfantiloriginal set ";
        $virgula = "";
        if (trim($this->ed167_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed167_sequencial"])) {
            $sql .= $virgula . " ed167_sequencial = $this->ed167_sequencial ";
            $virgula = ",";
            if (trim($this->ed167_sequencial) == null) {
                $this->erro_sql = " Campo Código não informado.";
                $this->erro_campo = "ed167_sequencial";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed167_disciplina) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed167_disciplina"])) {
            $sql .= $virgula . " ed167_disciplina = '$this->ed167_disciplina' ";
            $virgula = ",";
            if (trim($this->ed167_disciplina) == null) {
                $this->erro_sql = " Campo Disciplina BNCC não informado.";
                $this->erro_campo = "ed167_disciplina";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed167_faixa_etaria) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed167_faixa_etaria"])) {
            $sql .= $virgula . " ed167_faixa_etaria = '$this->ed167_faixa_etaria' ";
            $virgula = ",";
            if (trim($this->ed167_faixa_etaria) == null) {
                $this->erro_sql = " Campo Faixa Etaria não informado.";
                $this->erro_campo = "ed167_faixa_etaria";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed167_codigo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed167_codigo"])) {
            $sql .= $virgula . " ed167_codigo = '$this->ed167_codigo' ";
            $virgula = ",";
            if (trim($this->ed167_codigo) == null) {
                $this->erro_sql = " Campo Código BNCC não informado.";
                $this->erro_campo = "ed167_codigo";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed167_habilidade) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed167_habilidade"])) {
            $sql .= $virgula . " ed167_habilidade = '$this->ed167_habilidade' ";
            $virgula = ",";
            if (trim($this->ed167_habilidade) == null) {
                $this->erro_sql = " Campo Habilidade não informado.";
                $this->erro_campo = "ed167_habilidade";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if ($ed167_sequencial != null) {
            $sql .= " ed167_sequencial = $this->ed167_sequencial";
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {
            $resaco = $this->sql_record($this->sql_query_file($this->ed167_sequencial));
            if ($this->numrows > 0) {
                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1011766,'$this->ed167_sequencial','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed167_sequencial"]) || $this->ed167_sequencial != "") {
                        $resac = db_query("insert into db_acount values($acount,1010613,1011766,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed167_sequencial')) . "','$this->ed167_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed167_disciplina"]) || $this->ed167_disciplina != "") {
                        $resac = db_query("insert into db_acount values($acount,1010613,1011767,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed167_disciplina')) . "','$this->ed167_disciplina'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed167_faixa_etaria"]) || $this->ed167_faixa_etaria != "") {
                        $resac = db_query("insert into db_acount values($acount,1010613,1011768,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed167_faixa_etaria')) . "','$this->ed167_faixa_etaria'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed167_codigo"]) || $this->ed167_codigo != "") {
                        $resac = db_query("insert into db_acount values($acount,1010613,1011769,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed167_codigo')) . "','$this->ed167_codigo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed167_habilidade"]) || $this->ed167_habilidade != "") {
                        $resac = db_query("insert into db_acount values($acount,1010613,1011770,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed167_habilidade')) . "','$this->ed167_habilidade'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Estrutura da BNCC EI não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->ed167_sequencial;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Estrutura da BNCC EI não foi Alterado. Alteração Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->ed167_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $this->ed167_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($ed167_sequencial = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {
            if (empty($dbwhere)) {
                $resaco = $this->sql_record($this->sql_query_file($ed167_sequencial));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {
                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1011766,'$ed167_sequencial','E')");
                    $resac = db_query("insert into db_acount values($acount,1010613,1011766,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed167_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010613,1011767,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed167_disciplina')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010613,1011768,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed167_faixa_etaria')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010613,1011769,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed167_codigo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010613,1011770,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed167_habilidade')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " delete from bncceducacaoinfantiloriginal
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed167_sequencial)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " ed167_sequencial = $ed167_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Estrutura da BNCC EI não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $ed167_sequencial;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Estrutura da BNCC EI não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $ed167_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $ed167_sequencial;
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
            $this->erro_sql = "Record Vazio na Tabela:bncceducacaoinfantiloriginal";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query($ed167_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos}";
        $sql .= "  from bncceducacaoinfantiloriginal ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed167_sequencial)) {
                $sql2 .= " where bncceducacaoinfantiloriginal.ed167_sequencial = $ed167_sequencial ";
            }
        } elseif (!empty($dbwhere)) {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if (!empty($ordem)) {
            $sql .= " order by {$ordem}";
        }
        return $sql;
    }

    public function sql_query_file($ed167_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select {$campos} ";
        $sql .= "  from bncceducacaoinfantiloriginal ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed167_sequencial)) {
                $sql2 .= " where bncceducacaoinfantiloriginal.ed167_sequencial = $ed167_sequencial ";
            }
        } elseif (!empty($dbwhere)) {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if (!empty($ordem)) {
            $sql .= " order by {$ordem}";
        }
        return $sql;
    }

    public function sql_query_completa($ed167_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "", $ano)
    {
        $sql = "
        select {$campos}
          from bncceducacaoinfantiloriginal
        left join bncceducacaoinfantil on bncceducacaoinfantil.ed147_codigo = bncceducacaoinfantiloriginal.ed167_codigo
                                      and bncceducacaoinfantil.ed147_ano = $ano
        ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed167_sequencial)) {
                $sql2 .= " where bncceducacaoinfantiloriginal.ed167_sequencial = $ed167_sequencial ";
            }
        } else {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if (!empty($ordem)) {
            $sql .= " order by {$ordem}";
        }
        return $sql;
    }
}
