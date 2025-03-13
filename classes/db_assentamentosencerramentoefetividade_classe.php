<?php

class cl_assentamentosencerramentoefetividade
{
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
    /**
     * @var int
     */
    public $rh230_instituicao = 0;
    /**
     * @var string
     */
    public $rh230_mes = '';
    /**
     * @var int
     */
    public $rh230_ano = 0;
    /**
     * @var int
     */
    public $rh230_assentamento = 0;
    /**
     * @var int
     */
    public $rh230_sequencial = 0;
    public $campos = "rh230_instituicao = int8 = Instituição
                      rh230_mes = char(2) = Mês
                      rh230_ano = int4 = Ano
                      rh230_assentamento = int8 = Assentamento
                      rh230_sequencial = int8 = Sequencial";
    /**
     * @var array
     */
    private $join = array();

    public function __construct()
    {
        $this->rotulo = new rotulo('assentamentosencerramentoefetividade');
        $this->pagina_retorno = basename($_SERVER['PHP_SELF']);
    }

    public function erro($mostra, $retorna)
    {
        if ($this->erro_status == '0' || ($mostra == true && $this->erro_status != null)) {
            echo "<script>alert('{$this->erro_msg}')</script>";
            if ($retorna == true) {
                echo "<script>location.href='" . $this->pagina_retorno . "'</script>";
            }
        }
    }

    public function incluir($rh230_sequencial)
    {
        if ($this->rh230_instituicao === '' || $this->rh230_instituicao === null) {
            $this->erro_sql = " Campo Instituição não informado.";
            $this->erro_campo = "rh230_instituicao";
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
        if ($this->rh230_mes === '' || $this->rh230_mes === null) {
            $this->erro_sql = " Campo Mês não informado.";
            $this->erro_campo = "rh230_mes";
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
        if ($this->rh230_ano === '' || $this->rh230_ano === null) {
            $this->erro_sql = " Campo Ano não informado.";
            $this->erro_campo = "rh230_ano";
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
        if ($this->rh230_assentamento === '' || $this->rh230_assentamento === null) {
            $this->erro_sql = " Campo Assentamento não informado.";
            $this->erro_campo = "rh230_assentamento";
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
        if ($rh230_sequencial === '' || $rh230_sequencial === null || $rh230_sequencial === 0) {
            $result = db_query("select nextval('assentamentosencerramentoefetividade_rh230_sequencial_seq')");
            if (!$result) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: assentamentosencerramentoefetividade_rh230_sequencial_seq do campo: rh230_sequencial";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace(
                    '"',
                    "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
                );
                $this->erro_status = "0";
                return false;
            }
            $this->rh230_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("SELECT last_value FROM assentamentosencerramentoefetividade_rh230_sequencial_seq");
            if ($result && pg_result($result, 0, 0) < $rh230_sequencial) {
                $this->erro_sql = " Campo rh230_sequencial maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace(
                    '"',
                    "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
                );
                $this->erro_status = "0";
                return false;
            } else {
                $this->rh230_sequencial = $rh230_sequencial;
            }
        }
        if ($this->rh230_sequencial === null || $this->rh230_sequencial === '' || $this->rh230_sequencial === 0) {
            $this->erro_sql = " Campo rh230_sequencial não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace(
                '"',
                "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
            );
            $this->erro_status = '0';
            return false;
        }
        $sql = "
            INSERT INTO assentamentosencerramentoefetividade (
                rh230_instituicao
                ,rh230_mes
                ,rh230_ano
                ,rh230_assentamento
                ,rh230_sequencial
            ) VALUES (
                 " . ($this->rh230_instituicao === null || $this->rh230_instituicao === '' ? 'NULL' : $this->rh230_instituicao) . "
                ," . ($this->rh230_mes === null || $this->rh230_mes === '' ? 'NULL' : "'{$this->rh230_mes}'") . "
                ," . ($this->rh230_ano === null || $this->rh230_ano === '' ? 'NULL' : $this->rh230_ano) . "
                ," . ($this->rh230_assentamento === null || $this->rh230_assentamento === '' ? 'NULL' : $this->rh230_assentamento) . "
                ," . ($this->rh230_sequencial === null || $this->rh230_sequencial === '' ? 'NULL' : $this->rh230_sequencial) . "
            )
        ";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = "Assentamentos criados ao encerrar efetividade () não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "Assentamentos criados ao encerrar efetividade já cadastrado";
                $this->erro_msg .= str_replace(
                    '"',
                    "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
                );
            } else {
                $this->erro_sql = "Assentamentos criados ao encerrar efetividade () não Incluído. Inclusão Abortada.";
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
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace(
            '"',
            "",
            str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
        );
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {
            $resaco = $this->sql_record($this->sql_query_file($this->rh230_sequencial));
            if ($resaco != false || $this->numrows != 0) {
                $resac = db_query("SELECT nextval('db_acount_id_acount_seq') AS acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("INSERT INTO db_acountacesso VALUES ($acount, " . db_getsession("DB_acessado") . ")");
                $resac = db_query("INSERT INTO db_acountkey VALUES ($acount,1010551,'$this->rh230_sequencial','I')");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010454,1010555,'','" . AddSlashes(pg_result(
                    $resaco,
                    0,
                    'rh230_instituicao'
                )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010454,1010554,'','" . AddSlashes(pg_result(
                    $resaco,
                    0,
                    'rh230_mes'
                )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010454,1010553,'','" . AddSlashes(pg_result(
                    $resaco,
                    0,
                    'rh230_ano'
                )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010454,1010552,'','" . AddSlashes(pg_result(
                    $resaco,
                    0,
                    'rh230_assentamento'
                )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010454,1010551,'','" . AddSlashes(pg_result(
                    $resaco,
                    0,
                    'rh230_sequencial'
                )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
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
            $this->erro_sql = "Record Vazio na Tabela:assentamentosencerramentoefetividade";
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

    public function sql_query_file($rh230_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos} ";
        $sql .= "  from assentamentosencerramentoefetividade ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($rh230_sequencial)) {
                $sql2 .= " where assentamentosencerramentoefetividade.rh230_sequencial = $rh230_sequencial ";
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

    public function alterar($rh230_sequencial = null)
    {
        $sql = "UPDATE assentamentosencerramentoefetividade SET ";
        $virgula = '';
        if (trim($this->rh230_instituicao) !== '' && $this->rh230_instituicao !== null) {
            $sql .= "{$virgula} rh230_instituicao = {$this->rh230_instituicao} ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Instituição" é obrigatório.');
        }
        if (trim($this->rh230_mes) !== '' && $this->rh230_mes !== null) {
            $sql .= "{$virgula} rh230_mes = '{$this->rh230_mes}' ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Mês" é obrigatório.');
        }
        if (trim($this->rh230_ano) !== '' && $this->rh230_ano !== null) {
            $sql .= "{$virgula} rh230_ano = {$this->rh230_ano} ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Ano" é obrigatório.');
        }
        if (trim($this->rh230_assentamento) !== '' && $this->rh230_assentamento !== null) {
            $sql .= "{$virgula} rh230_assentamento = {$this->rh230_assentamento} ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Assentamento" é obrigatório.');
        }
        if (trim($this->rh230_sequencial) !== '' && $this->rh230_sequencial !== null) {
            $sql .= "{$virgula} rh230_sequencial = {$this->rh230_sequencial} ";
        } else {
            throw new Exception('Campo "Sequencial" é obrigatório.');
        }

        if ($rh230_sequencial !== '' && $rh230_sequencial !== null && $rh230_sequencial !== 0) {
            $sql .= ' WHERE';
            $sql .= " rh230_sequencial = {$this->rh230_sequencial}";
        }

        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {
            $resaco = $this->sql_record($this->sql_query_file($this->rh230_sequencial));
            if ($this->numrows > 0) {
                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1010551,'$this->rh230_sequencial','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh230_instituicao"]) || $this->rh230_instituicao != "") {
                        $resac = db_query("insert into db_acount values($acount,1010454,1010555,'" . AddSlashes(pg_result(
                            $resaco,
                            $conresaco,
                            'rh230_instituicao'
                        )) . "','$this->rh230_instituicao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh230_mes"]) || $this->rh230_mes != "") {
                        $resac = db_query("insert into db_acount values($acount,1010454,1010554,'" . AddSlashes(pg_result(
                            $resaco,
                            $conresaco,
                            'rh230_mes'
                        )) . "','$this->rh230_mes'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh230_ano"]) || $this->rh230_ano != "") {
                        $resac = db_query("insert into db_acount values($acount,1010454,1010553,'" . AddSlashes(pg_result(
                            $resaco,
                            $conresaco,
                            'rh230_ano'
                        )) . "','$this->rh230_ano'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh230_assentamento"]) || $this->rh230_assentamento != "") {
                        $resac = db_query("insert into db_acount values($acount,1010454,1010552,'" . AddSlashes(pg_result(
                            $resaco,
                            $conresaco,
                            'rh230_assentamento'
                        )) . "','$this->rh230_assentamento'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh230_sequencial"]) || $this->rh230_sequencial != "") {
                        $resac = db_query("insert into db_acount values($acount,1010454,1010551,'" . AddSlashes(pg_result(
                            $resaco,
                            $conresaco,
                            'rh230_sequencial'
                        )) . "','$this->rh230_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Assentamentos criados ao encerrar efetividade não Alterado. Alteração Abortada.\\n";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace(
                '"',
                "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
            );
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Assentamentos criados ao encerrar efetividade não foi Alterado. Alteração Executada.\\n";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace(
                    '"',
                    "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
                );
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace(
                    '"',
                    "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
                );
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($rh230_sequencial = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {
            if (empty($dbwhere)) {
                $resaco = $this->sql_record($this->sql_query_file($rh230_sequencial));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {
                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1010551,'$rh230_sequencial','E')");
                    $resac = db_query("insert into db_acount values($acount,1010454,1010555,'','" . AddSlashes(pg_result(
                        $resaco,
                        $iresaco,
                        'rh230_instituicao'
                    )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010454,1010554,'','" . AddSlashes(pg_result(
                        $resaco,
                        $iresaco,
                        'rh230_mes'
                    )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010454,1010553,'','" . AddSlashes(pg_result(
                        $resaco,
                        $iresaco,
                        'rh230_ano'
                    )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010454,1010552,'','" . AddSlashes(pg_result(
                        $resaco,
                        $iresaco,
                        'rh230_assentamento'
                    )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010454,1010551,'','" . AddSlashes(pg_result(
                        $resaco,
                        $iresaco,
                        'rh230_sequencial'
                    )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " DELETE FROM assentamentosencerramentoefetividade
                    WHERE ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($rh230_sequencial)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " rh230_sequencial = $rh230_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Assentamentos criados ao encerrar efetividade não Excluído. Exclusão Abortada.\\n";
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
                $this->erro_sql = "Assentamentos criados ao encerrar efetividade não Encontrado. Exclusão não Efetuada.\\n";
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

    public function sql_query($rh230_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos}";
        $sql .= "  from assentamentosencerramentoefetividade ";
        $sql .= "      inner join db_config  on  db_config.codigo = assentamentosencerramentoefetividade.rh230_instituicao";
        $sql .= "      inner join assenta  on  assenta.h16_codigo = assentamentosencerramentoefetividade.rh230_assentamento";
        $sql .= "      inner join configuracoesdatasefetividade  on  configuracoesdatasefetividade.rh186_exercicio = assentamentosencerramentoefetividade.rh230_ano and  configuracoesdatasefetividade.rh186_competencia = assentamentosencerramentoefetividade.rh230_mes and  configuracoesdatasefetividade.rh186_instituicao = assentamentosencerramentoefetividade.rh230_instituicao";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
        $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
        $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = assenta.h16_login";
        $sql .= "      inner join tipoasse  on  tipoasse.h12_codigo = assenta.h16_assent";
        $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = assenta.h16_regist";
        $sql .= "      inner join db_config  on  db_config.codigo = configuracoesdatasefetividade.rh186_instituicao";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($rh230_sequencial)) {
                $sql2 .= " where assentamentosencerramentoefetividade.rh230_sequencial = $rh230_sequencial ";
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
     * @return cl_assentamentosencerramentoefetividade
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

        return "SELECT {$columns} FROM assentamentosencerramentoefetividade {$join} {$where} {$order}";
    }
}
