<?php

class cl_rhsindicato
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
    public $rh116_sequencial = 0;
    /**
     * @var string
     */
    public $rh116_codigo = '';
    /**
     * @var string
     */
    public $rh116_cnpj = '';
    /**
     * @var string
     */
    public $rh116_descricao = '';
    /**
     * @var int
     */
    public $rh116_mesdatabase = null;
    public $campos = "rh116_sequencial = int4 = Sequencial
                      rh116_codigo = varchar(40) = Código Sindical
                      rh116_cnpj = varchar(40) = CNPJ
                      rh116_descricao = varchar(100) = Descrição
                      rh116_mesdatabase = int4 = Mês da Data Base";

    public function __construct()
    {
        $this->rotulo = new rotulo('rhsindicato');
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

    public function incluir($rh116_sequencial)
    {
        if ($this->rh116_sequencial === '' || $this->rh116_sequencial === null) {
            $this->erro_sql = " Campo Sequencial não informado.";
            $this->erro_campo = "rh116_sequencial";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh116_codigo === '' || $this->rh116_codigo === null) {
            $this->erro_sql = " Campo Código Sindical não informado.";
            $this->erro_campo = "rh116_codigo";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh116_cnpj === '' || $this->rh116_cnpj === null) {
            $this->erro_sql = " Campo CNPJ não informado.";
            $this->erro_campo = "rh116_cnpj";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh116_descricao === '' || $this->rh116_descricao === null) {
            $this->erro_sql = " Campo Descrição não informado.";
            $this->erro_campo = "rh116_descricao";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh116_mesdatabase === null || $this->rh116_mesdatabase === '') {
            $this->rh116_mesdatabase = "0";
        }
        if ($rh116_sequencial === '' || $rh116_sequencial === null || $rh116_sequencial === 0) {
            $result = db_query("select nextval('rhsindicato_rh116_sequencial_seq')");
            if (!$result) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: rhsindicato_rh116_sequencial_seq do campo: rh116_sequencial";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->rh116_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("SELECT last_value FROM rhsindicato_rh116_sequencial_seq");
            if ($result && pg_result($result, 0, 0) < $rh116_sequencial) {
                $this->erro_sql = " Campo rh116_sequencial maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->rh116_sequencial = $rh116_sequencial;
            }
        }
        if ($this->rh116_sequencial === null || $this->rh116_sequencial === '' || $this->rh116_sequencial === 0) {
            $this->erro_sql = " Campo rh116_sequencial não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = '0';
            return false;
        }
        $sql = "
            INSERT INTO rhsindicato (
                rh116_sequencial
                ,rh116_codigo
                ,rh116_cnpj
                ,rh116_descricao
                ,rh116_mesdatabase
            ) VALUES (
                 " . ($this->rh116_sequencial === null || $this->rh116_sequencial === '' ? 'NULL' : $this->rh116_sequencial) . "
                ," . ($this->rh116_codigo === null || $this->rh116_codigo === '' ? 'NULL' : "'{$this->rh116_codigo}'") . "
                ," . ($this->rh116_cnpj === null || $this->rh116_cnpj === '' ? 'NULL' : "'{$this->rh116_cnpj}'") . "
                ," . ($this->rh116_descricao === null || $this->rh116_descricao === '' ? 'NULL' : "'{$this->rh116_descricao}'") . "
                ," . ($this->rh116_mesdatabase === null || $this->rh116_mesdatabase === '' ? 'NULL' : $this->rh116_mesdatabase) . "
            )
        ";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = "Sindicato () não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "Sindicato já cadastrado";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql = "Sindicato () não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "",
            str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->rh116_sequencial));
            if ($resaco != false || $this->numrows != 0) {
                $resac = db_query("SELECT nextval('db_acount_id_acount_seq') AS acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("INSERT INTO db_acountacesso VALUES ($acount, " . db_getsession("DB_acessado") . ")");
                $resac = db_query("INSERT INTO db_acountkey VALUES ($acount,19592,'$this->rh116_sequencial','I')");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,3481,19592,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'rh116_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,3481,19593,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'rh116_codigo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,3481,19594,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'rh116_cnpj')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,3481,19595,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'rh116_descricao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,3481,1010584,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'rh116_mesdatabase')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
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
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $this->numrows = pg_num_rows($result);
        if ($this->numrows == 0) {
            $this->erro_banco = "";
            $this->erro_sql = "Record Vazio na Tabela:rhsindicato";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query_file($rh116_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos} ";
        $sql .= "  from rhsindicato ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($rh116_sequencial)) {
                $sql2 .= " where rhsindicato.rh116_sequencial = $rh116_sequencial ";
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

    public function alterar($rh116_sequencial = null)
    {
        $sql = "UPDATE rhsindicato SET ";
        $virgula = '';
        $this->rh116_sequencial = $rh116_sequencial;
        if (trim($this->rh116_codigo) !== '' && $this->rh116_codigo !== null) {
            $sql .= "{$virgula} rh116_codigo = '{$this->rh116_codigo}' ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Código Sindical" é obrigatório.');
        }
        if (trim($this->rh116_cnpj) !== '' && $this->rh116_cnpj !== null) {
            $sql .= "{$virgula} rh116_cnpj = '{$this->rh116_cnpj}' ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "CNPJ" é obrigatório.');
        }
        if (trim($this->rh116_descricao) !== '' && $this->rh116_descricao !== null) {
            $sql .= "{$virgula} rh116_descricao = '{$this->rh116_descricao}' ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Descrição" é obrigatório.');
        }
        if (trim($this->rh116_mesdatabase) !== '' && $this->rh116_mesdatabase !== null) {
            $sql .= "{$virgula} rh116_mesdatabase = {$this->rh116_mesdatabase} ";
        } else {
            $sql .= "{$virgula} rh116_mesdatabase = NULL ";
        }

        if ($rh116_sequencial !== '' && $rh116_sequencial !== null && $rh116_sequencial !== 0) {
            $sql .= ' WHERE';
            $sql .= " rh116_sequencial = {$rh116_sequencial}";
        }

        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->rh116_sequencial));
            if ($this->numrows > 0) {

                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,19592,'$this->rh116_sequencial','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh116_sequencial"]) || $this->rh116_sequencial != "") {
                        $resac = db_query("insert into db_acount values($acount,3481,19592,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'rh116_sequencial')) . "','$this->rh116_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh116_codigo"]) || $this->rh116_codigo != "") {
                        $resac = db_query("insert into db_acount values($acount,3481,19593,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'rh116_codigo')) . "','$this->rh116_codigo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh116_cnpj"]) || $this->rh116_cnpj != "") {
                        $resac = db_query("insert into db_acount values($acount,3481,19594,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'rh116_cnpj')) . "','$this->rh116_cnpj'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh116_descricao"]) || $this->rh116_descricao != "") {
                        $resac = db_query("insert into db_acount values($acount,3481,19595,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'rh116_descricao')) . "','$this->rh116_descricao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh116_mesdatabase"]) || $this->rh116_mesdatabase != "") {
                        $resac = db_query("insert into db_acount values($acount,3481,1010584,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'rh116_mesdatabase')) . "','$this->rh116_mesdatabase'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Sindicato não Alterado. Alteração Abortada.\\n";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Sindicato não foi Alterado. Alteração Executada.\\n";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($rh116_sequencial = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            if (empty($dbwhere)) {

                $resaco = $this->sql_record($this->sql_query_file($rh116_sequencial));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,19592,'$rh116_sequencial','E')");
                    $resac = db_query("insert into db_acount values($acount,3481,19592,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'rh116_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,3481,19593,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'rh116_codigo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,3481,19594,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'rh116_cnpj')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,3481,19595,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'rh116_descricao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,3481,1010584,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'rh116_mesdatabase')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " DELETE FROM rhsindicato
                    WHERE ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($rh116_sequencial)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " rh116_sequencial = $rh116_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Sindicato não Excluído. Exclusão Abortada.\\n";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Sindicato não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function sql_query($rh116_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos}";
        $sql .= "  from rhsindicato ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($rh116_sequencial)) {
                $sql2 .= " where rhsindicato.rh116_sequencial = $rh116_sequencial ";
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
}
