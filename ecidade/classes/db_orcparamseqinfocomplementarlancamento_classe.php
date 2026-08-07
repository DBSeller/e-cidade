<?php

use ECidade\Configuracao\RelatorioLegal\Repositorio\InformacaoComplementarLancamentoRepositorio;

class cl_orcparamseqinfocomplementarlancamento
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
    public $o102_sequencial = 0;
    public $o102_exclusao = 'f';
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 o102_sequencial = int8 = Código 
                 o102_exclusao = bool = Exclusão 
                 ";
    /**
     * @var array
     */
    private $join = array();

    public function __construct()
    {
        $this->rotulo = new rotulo("orcparamseqinfocomplementarlancamento");
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
            $this->o102_sequencial = ($this->o102_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["o102_sequencial"] : $this->o102_sequencial);
            $this->o102_exclusao = empty($this->o102_exclusao) ? 'f' : $this->o102_exclusao;
        } else {
            $this->o102_sequencial = ($this->o102_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["o102_sequencial"] : $this->o102_sequencial);
        }
    }

    public function incluir($o102_sequencial)
    {
        $this->atualizacampos();
        if ($this->o102_exclusao == null) {
            $this->erro_sql = " Campo Exclusão não informado.";
            $this->erro_campo = "o102_exclusao";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace(
                '"',
                "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
            );
            $this->erro_status = "0";
            return false;
        }
        if ($o102_sequencial == "" || $o102_sequencial == null) {
            $result = db_query("select nextval('orcparamseqinfocomplementarlancamento_o102_sequencial_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: orcparamseqinfocomplementarlancamento_o102_sequencial_seq do campo: o102_sequencial";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace(
                    '"',
                    "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
                );
                $this->erro_status = "0";
                return false;
            }
            $this->o102_sequencial = pg_result($result, 0, 0);
        } else {
            $this->o102_sequencial = $o102_sequencial;
        }
        if (($this->o102_sequencial == null) || ($this->o102_sequencial == "")) {
            $this->erro_sql = " Campo o102_sequencial não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace(
                '"',
                "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
            );
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into orcparamseqinfocomplementarlancamento(
                                       o102_sequencial 
                                      ,o102_exclusao 
                       )
                values (
                                $this->o102_sequencial 
                               ,'$this->o102_exclusao' 
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = "Linha Informação Complementar Conta Corrente ($this->o102_sequencial) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "Linha Informação Complementar Conta Corrente já Cadastrado";
                $this->erro_msg .= str_replace(
                    '"',
                    "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
                );
            } else {
                $this->erro_sql = "Linha Informação Complementar Conta Corrente ($this->o102_sequencial) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace(
                    '"',
                    "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
                );
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
        $this->erro_sql .= "Valores : " . $this->o102_sequencial;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace(
            '"',
            "",
            str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
        );
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession('DB_desativar_account', false);

        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false)) {
            $sql = $this->sql_query_file($this->o102_sequencial);
            $rsSql = db_query($sql);

            if ($rsSql || pg_num_rows($rsSql) > 0) {
                $rs = db_query("SELECT nextval('db_acount_id_acount_seq') AS acount");

                if (!$rs) {
                    throw new Exception(pg_last_error());
                }

                $acessado = db_getsession('DB_acessado');
                $acount = pg_fetch_object($rs)->acount;

                $rs = db_query("INSERT INTO db_acountacesso VALUES ({$acount}, {$acessado})");

                if (!$rs) {
                    throw new Exception(pg_last_error());
                }

                $rs = db_query("INSERT INTO db_acountkey VALUES ({$acount}, 13912, '{$this->o102_sequencial}', 'I')");

                if (!$rs) {
                    throw new Exception(pg_last_error());
                }

                $rs = db_query("INSERT INTO db_acount VALUES ({$acount}, 1010439, 13912, '','" . AddSlashes(pg_result(
                        $rsSql,
                        0,
                        'o102_sequencial'
                    )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");

                if (!$rs) {
                    throw new Exception(pg_last_error());
                }

                $rs = db_query("insert into db_acount values($acount,1010439,1010422,'','" . AddSlashes(pg_result(
                        $rsSql,
                        0,
                        'o102_exclusao'
                    )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");

                if (!$rs) {
                    throw new Exception(pg_last_error());
                }
            }
        }

        if (!empty($o102_sequencial)) {
            InformacaoComplementarLancamentoRepositorio::setval(InformacaoComplementarLancamentoRepositorio::nextval());
        }

        return true;
    }

    public function alterar($o102_sequencial = null)
    {
        $this->atualizacampos();
        $sql = " UPDATE orcparamseqinfocomplementarlancamento SET ";
        $virgula = "";
        if (trim($this->o102_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o102_sequencial"])) {
            $sql .= $virgula . " o102_sequencial = $this->o102_sequencial ";
            $virgula = ",";
            if (trim($this->o102_sequencial) == null) {
                $this->erro_sql = " Campo Código não informado.";
                $this->erro_campo = "o102_sequencial";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace(
                    '"',
                    "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
                );
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->o102_exclusao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o102_exclusao"])) {
            $sql .= $virgula . " o102_exclusao = '$this->o102_exclusao' ";
            if (trim($this->o102_exclusao) == null) {
                $this->erro_sql = " Campo Exclusão não informado.";
                $this->erro_campo = "o102_exclusao";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace(
                    '"',
                    "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
                );
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if ($o102_sequencial != null) {
            $sql .= " o102_sequencial = $this->o102_sequencial";
        }

        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Linha Informação Complementar Conta Corrente não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->o102_sequencial;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace(
                '"',
                "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
            );
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        }

        if (pg_affected_rows($result) == 0) {
            $this->erro_banco = "";
            $this->erro_sql = "Linha Informação Complementar Conta Corrente não foi Alterado. Alteração Executada.\\n";
            $this->erro_sql .= "Valores : " . $this->o102_sequencial;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace(
                '"',
                "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
            );
            $this->erro_status = "1";
            $this->numrows_alterar = 0;
        } else {
            $this->erro_banco = "";
            $this->erro_sql = "Alteração efetuada com sucesso.\\n";
            $this->erro_sql .= "Valores : " . $this->o102_sequencial;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace(
                '"',
                "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
            );
            $this->erro_status = "1";
            $this->numrows_alterar = pg_affected_rows($result);
        }

        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);

        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false)) {
            $sql = $this->sql_query_file($this->o102_sequencial);
            $rs = db_query($sql);

            if ($rs || pg_num_rows($rs) > 0) {
                $rs = db_query("SELECT nextval('db_acount_id_acount_seq') AS acount");

                if (!$rs) {
                    throw new Exception(pg_last_error());
                }

                $acessado = db_getsession('DB_acessado');
                $acount = pg_fetch_object($rs)->acount;

                $rs = db_query("INSERT INTO db_acountacesso VALUES ({$acount}, {$acessado})");

                if (!$rs) {
                    throw new Exception(pg_last_error());
                }

                $rs = db_query("INSERT INTO db_acountkey VALUES ({$acount}, 13912, '{$this->o102_sequencial}', 'A')");

                if (!$rs) {
                    throw new Exception(pg_last_error());
                }

                $rs = db_query("INSERT INTO db_acount VALUES ({$acount}, 1010439, 13912, '','" . AddSlashes(pg_result(
                        $rs,
                        0,
                        'o102_sequencial'
                    )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");

                if (!$rs) {
                    throw new Exception(pg_last_error());
                }

                $rs = db_query("insert into db_acount values($acount,1010439,1010422,'','" . AddSlashes(pg_result(
                        $rs,
                        0,
                        'o102_exclusao'
                    )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");

                if (!$rs) {
                    throw new Exception(pg_last_error());
                }
            }
        }

        return true;
    }

    public function excluir($o102_sequencial = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {
            if (empty($dbwhere)) {
                $resaco = $this->sql_record($this->sql_query_file($o102_sequencial));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {
                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,13912,'$o102_sequencial','E')");
                    $resac = db_query("insert into db_acount values($acount,1010439,13912,'','" . AddSlashes(pg_result(
                            $resaco,
                            $iresaco,
                            'o102_sequencial'
                        )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010439,1010422,'','" . AddSlashes(pg_result(
                            $resaco,
                            $iresaco,
                            'o102_exclusao'
                        )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " DELETE FROM orcparamseqinfocomplementarlancamento
                    WHERE ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($o102_sequencial)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " o102_sequencial = $o102_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Linha Informação Complementar Conta Corrente não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $o102_sequencial;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace(
                '"',
                "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
            );
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Linha Informação Complementar Conta Corrente não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $o102_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace(
                    '"',
                    "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
                );
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $o102_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace(
                    '"',
                    "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
                );
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
            $this->erro_msg .= str_replace(
                '"',
                "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
            );
            $this->erro_status = "0";
            return false;
        }
        $this->numrows = pg_num_rows($result);
        if ($this->numrows == 0) {
            $this->erro_banco = "";
            $this->erro_sql = "Record Vazio na Tabela:orcparamseqinfocomplementarlancamento";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace(
                '"',
                "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
            );
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query($o102_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select {$campos}";
        $sql .= "  from orcparamseqinfocomplementarlancamento ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($o102_sequencial)) {
                $sql2 .= " where orcparamseqinfocomplementarlancamento.o102_sequencial = $o102_sequencial ";
            }
        } else {
            if (!empty($dbwhere)) {
                $sql2 = " where $dbwhere";
            }
        }
        $sql .= $sql2;
        if (!empty($ordem)) {
            $sql .= " order by {$ordem}";
        }
        return $sql;
    }

    public function sql_query_file($o102_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select {$campos} ";
        $sql .= "  from orcparamseqinfocomplementarlancamento ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($o102_sequencial)) {
                $sql2 .= " where orcparamseqinfocomplementarlancamento.o102_sequencial = $o102_sequencial ";
            }
        } else {
            if (!empty($dbwhere)) {
                $sql2 = " where $dbwhere";
            }
        }
        $sql .= $sql2;
        if (!empty($ordem)) {
            $sql .= " order by {$ordem}";
        }
        return $sql;
    }

    /**
     * @param $table
     * @param $reference
     * @param $operator
     * @param $foreign
     * @return cl_orcparamseqinfocomplementarlancamento
     */
    public function addJoin($table, $reference, $operator, $foreign)
    {
        if (array_key_exists($table, $this->join)) {
            $this->join[$table] .= " AND {$reference} {$operator} {$foreign}";
        } else {
            $this->join[$table] = "JOIN {$table} ON {$reference} {$operator} {$foreign}";
        }

        return $this;
    }

    /**
     * @param array $columns
     * @param array $where
     * @param array $order
     * @return string
     */
    public function sql($columns = array('*'), $where = array(), $order = array())
    {
        $columns = implode(', ', $columns);
        $where = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $join = implode(' ', $this->join);
        $order = $order ? 'ORDER BY ' . implode(', ', $order) : '';

        return "SELECT {$columns} FROM orcparamseqinfocomplementarlancamento {$join} {$where} {$order}";
    }
}
