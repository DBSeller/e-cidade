<?php

class cl_controleafastamento
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
    public $rh231_sequencial = 0;
    /**
     * @var int
     */
    public $rh231_afastamento = 0;
    /**
     * @var string
     */
    public $rh231_rubrica = '';
    /**
     * @var int
     */
    public $rh231_tabelaprevidencia = 0;
    /**
     * @var int
     */
    public $rh231_instituicao = 0;
    /**
     * @var int
     */
    public $rh231_ano = 0;
    /**
     * @var int
     */
    public $rh231_mes = 0;
    public $campos = "rh231_sequencial = int4 = Sequencial
                      rh231_afastamento = int4 = Afastamento
                      rh231_rubrica = char(4) = Rubrica
                      rh231_tabelaprevidencia = int4 = Tabela
                      rh231_instituicao = int4 = Instituição
                      rh231_ano = int4 = Ano
                      rh231_mes = int4 = Mês";

    public function __construct()
    {
        $this->rotulo = new rotulo('controleafastamento');
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

    public function incluir($rh231_sequencial)
    {
        if ($this->rh231_afastamento === '' || $this->rh231_afastamento === null) {
            $this->erro_sql = " Campo Afastamento não informado.";
            $this->erro_campo = "rh231_afastamento";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh231_rubrica === '' || $this->rh231_rubrica === null) {
            $this->erro_sql = " Campo Rubrica não informado.";
            $this->erro_campo = "rh231_rubrica";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh231_tabelaprevidencia === '' || $this->rh231_tabelaprevidencia === null) {
            $this->erro_sql = " Campo Tabela não informado.";
            $this->erro_campo = "rh231_tabelaprevidencia";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh231_instituicao === '' || $this->rh231_instituicao === null) {
            $this->erro_sql = " Campo Instituição não informado.";
            $this->erro_campo = "rh231_instituicao";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh231_ano === '' || $this->rh231_ano === null) {
            $this->erro_sql = " Campo Ano não informado.";
            $this->erro_campo = "rh231_ano";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh231_mes === '' || $this->rh231_mes === null) {
            $this->erro_sql = " Campo Mês não informado.";
            $this->erro_campo = "rh231_mes";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($rh231_sequencial === '' || $rh231_sequencial === null || $rh231_sequencial === 0) {
            $result = db_query("select nextval('controleafastamento_rh231_sequencial_seq')");
            if (!$result) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: controleafastamento_rh231_sequencial_seq do campo: rh231_sequencial";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->rh231_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("SELECT last_value FROM controleafastamento_rh231_sequencial_seq");
            if ($result && pg_result($result, 0, 0) < $rh231_sequencial) {
                $this->erro_sql = " Campo rh231_sequencial maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->rh231_sequencial = $rh231_sequencial;
            }
        }
        if ($this->rh231_sequencial === null || $this->rh231_sequencial === '' || $this->rh231_sequencial === 0) {
            $this->erro_sql = " Campo rh231_sequencial não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = '0';
            return false;
        }
        $sql = "
            INSERT INTO controleafastamento (
                rh231_sequencial
                ,rh231_afastamento
                ,rh231_rubrica
                ,rh231_tabelaprevidencia
                ,rh231_instituicao
                ,rh231_ano
                ,rh231_mes
            ) VALUES (
                 " . ($this->rh231_sequencial === null || $this->rh231_sequencial === '' ? 'NULL' : $this->rh231_sequencial) . "
                ," . ($this->rh231_afastamento === null || $this->rh231_afastamento === '' ? 'NULL' : $this->rh231_afastamento) . "
                ," . ($this->rh231_rubrica === null || $this->rh231_rubrica === '' ? 'NULL' : "'{$this->rh231_rubrica}'") . "
                ," . ($this->rh231_tabelaprevidencia === null || $this->rh231_tabelaprevidencia === '' ? 'NULL' : $this->rh231_tabelaprevidencia) . "
                ," . ($this->rh231_instituicao === null || $this->rh231_instituicao === '' ? 'NULL' : $this->rh231_instituicao) . "
                ," . ($this->rh231_ano === null || $this->rh231_ano === '' ? 'NULL' : $this->rh231_ano) . "
                ," . ($this->rh231_mes === null || $this->rh231_mes === '' ? 'NULL' : $this->rh231_mes) . "
            )
        ";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = "controleafastamento () não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "controleafastamento já cadastrado";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql = "controleafastamento () não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->rh231_sequencial));
            if ($resaco != false || $this->numrows != 0) {
                $resac = db_query("SELECT nextval('db_acount_id_acount_seq') AS acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("INSERT INTO db_acountacesso VALUES ($acount, " . db_getsession("DB_acessado") . ")");
                $resac = db_query("INSERT INTO db_acountkey VALUES ($acount,1010781,'$this->rh231_sequencial','I')");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010476,1010781,'','" . AddSlashes(pg_result($resaco, 0, 'rh231_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010476,1010778,'','" . AddSlashes(pg_result($resaco, 0, 'rh231_afastamento')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010476,1010775,'','" . AddSlashes(pg_result($resaco, 0, 'rh231_rubrica')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010476,1010777,'','" . AddSlashes(pg_result($resaco, 0, 'rh231_tabelaprevidencia')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010476,1010776,'','" . AddSlashes(pg_result($resaco, 0, 'rh231_instituicao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010476,1010779,'','" . AddSlashes(pg_result($resaco, 0, 'rh231_ano')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010476,1010780,'','" . AddSlashes(pg_result($resaco, 0, 'rh231_mes')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
    }

    public function alterar($rh231_sequencial = null)
    {
        $sql = "UPDATE controleafastamento SET ";
        $virgula = '';
        if (empty($rh231_sequencial)) {
            throw new Exception('Campo rh231_sequencial é obrigatório!');
        }
        $this->rh231_sequencial = $rh231_sequencial;
        if (trim($this->rh231_afastamento) !== '' && $this->rh231_afastamento !== null) {
            $sql .= "{$virgula} rh231_afastamento = {$this->rh231_afastamento} ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Afastamento" é obrigatório.');
        }
        if (trim($this->rh231_rubrica) !== '' && $this->rh231_rubrica !== null) {
            $sql .= "{$virgula} rh231_rubrica = '{$this->rh231_rubrica}' ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Rubrica" é obrigatório.');
        }
        if (trim($this->rh231_tabelaprevidencia) !== '' && $this->rh231_tabelaprevidencia !== null) {
            $sql .= "{$virgula} rh231_tabelaprevidencia = {$this->rh231_tabelaprevidencia} ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Tabela" é obrigatório.');
        }
        if (trim($this->rh231_instituicao) !== '' && $this->rh231_instituicao !== null) {
            $sql .= "{$virgula} rh231_instituicao = {$this->rh231_instituicao} ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Instituição" é obrigatório.');
        }
        if (trim($this->rh231_ano) !== '' && $this->rh231_ano !== null) {
            $sql .= "{$virgula} rh231_ano = {$this->rh231_ano} ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Ano" é obrigatório.');
        }
        if (trim($this->rh231_mes) !== '' && $this->rh231_mes !== null) {
            $sql .= "{$virgula} rh231_mes = {$this->rh231_mes} ";
        } else {
            throw new Exception('Campo "Mês" é obrigatório.');
        }

        if ($rh231_sequencial !== '' && $rh231_sequencial !== null && $rh231_sequencial !== 0) {
            $sql .= ' WHERE';
            $sql .= " rh231_sequencial = {$rh231_sequencial}";
        }

        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->rh231_sequencial));
            if ($this->numrows > 0) {

                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1010781,'$this->rh231_sequencial','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh231_sequencial"]) || $this->rh231_sequencial != "")
                        $resac = db_query("insert into db_acount values($acount,1010476,1010781,'" . AddSlashes(pg_result($resaco, $conresaco, 'rh231_sequencial')) . "','$this->rh231_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh231_afastamento"]) || $this->rh231_afastamento != "")
                        $resac = db_query("insert into db_acount values($acount,1010476,1010778,'" . AddSlashes(pg_result($resaco, $conresaco, 'rh231_afastamento')) . "','$this->rh231_afastamento'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh231_rubrica"]) || $this->rh231_rubrica != "")
                        $resac = db_query("insert into db_acount values($acount,1010476,1010775,'" . AddSlashes(pg_result($resaco, $conresaco, 'rh231_rubrica')) . "','$this->rh231_rubrica'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh231_tabelaprevidencia"]) || $this->rh231_tabelaprevidencia != "")
                        $resac = db_query("insert into db_acount values($acount,1010476,1010777,'" . AddSlashes(pg_result($resaco, $conresaco, 'rh231_tabelaprevidencia')) . "','$this->rh231_tabelaprevidencia'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh231_instituicao"]) || $this->rh231_instituicao != "")
                        $resac = db_query("insert into db_acount values($acount,1010476,1010776,'" . AddSlashes(pg_result($resaco, $conresaco, 'rh231_instituicao')) . "','$this->rh231_instituicao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh231_ano"]) || $this->rh231_ano != "")
                        $resac = db_query("insert into db_acount values($acount,1010476,1010779,'" . AddSlashes(pg_result($resaco, $conresaco, 'rh231_ano')) . "','$this->rh231_ano'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh231_mes"]) || $this->rh231_mes != "")
                        $resac = db_query("insert into db_acount values($acount,1010476,1010780,'" . AddSlashes(pg_result($resaco, $conresaco, 'rh231_mes')) . "','$this->rh231_mes'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "controleafastamento não Alterado. Alteração Abortada.\\n";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "controleafastamento não foi Alterado. Alteração Executada.\\n";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($rh231_sequencial = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            if (empty($dbwhere)) {

                $resaco = $this->sql_record($this->sql_query_file($rh231_sequencial));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1010781,'$rh231_sequencial','E')");
                    $resac = db_query("insert into db_acount values($acount,1010476,1010781,'','" . AddSlashes(pg_result($resaco, $iresaco, 'rh231_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010476,1010778,'','" . AddSlashes(pg_result($resaco, $iresaco, 'rh231_afastamento')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010476,1010775,'','" . AddSlashes(pg_result($resaco, $iresaco, 'rh231_rubrica')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010476,1010777,'','" . AddSlashes(pg_result($resaco, $iresaco, 'rh231_tabelaprevidencia')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010476,1010776,'','" . AddSlashes(pg_result($resaco, $iresaco, 'rh231_instituicao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010476,1010779,'','" . AddSlashes(pg_result($resaco, $iresaco, 'rh231_ano')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010476,1010780,'','" . AddSlashes(pg_result($resaco, $iresaco, 'rh231_mes')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " DELETE FROM controleafastamento
                    WHERE ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($rh231_sequencial)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " rh231_sequencial = $rh231_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "controleafastamento não Excluído. Exclusão Abortada.\\n";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "controleafastamento não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
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
            $this->erro_sql = "Record Vazio na Tabela:controleafastamento";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query($rh231_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos}";
        $sql .= "  from controleafastamento ";
        $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = controleafastamento.rh231_rubrica and  rhrubricas.rh27_instit = controleafastamento.rh231_instituicao";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($rh231_sequencial)) {
                $sql2 .= " where controleafastamento.rh231_sequencial = $rh231_sequencial ";
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

    public function sql_query_file($rh231_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos} ";
        $sql .= "  from controleafastamento ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($rh231_sequencial)) {
                $sql2 .= " where controleafastamento.rh231_sequencial = $rh231_sequencial ";
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
