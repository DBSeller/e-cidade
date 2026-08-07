<?php

class cl_bnccdisciplinas
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
    public $ed149_sequencial = 0;
    /**
     * @var string
     */
    public $ed149_nome = '';
    /**
     * @var string
     */
    public $ed149_sigla = '';
    /**
     * @var string
     */
    public $ed149_area_conhecimento = null;
    /**
     * @var string
     */
    public $ed149_ensino = null;
    public $campos = "ed149_sequencial = int4 = Código
                      ed149_nome = varchar(100) = Nome
                      ed149_sigla = varchar(3) = Sigla
                      ed149_area_conhecimento = varchar(100) = Área de Conhecimento
                      ed149_ensino = varchar(2) = Ensino";

    public function __construct()
    {
        $this->rotulo = new rotulo('bnccdisciplinas');
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

    public function incluir($ed149_sequencial)
    {
        if ($this->ed149_sequencial === '' || $this->ed149_sequencial === null) {
            $result = db_query("select nextval('bnccdisciplinas_ed149_sequencial_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: bnccdisciplinas_ed149_sequencial_seq do campo: ed149_sequencial";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->ed154_sequencial = pg_result($result, 0, 0);
        }
        if ($this->ed149_nome === '' || $this->ed149_nome === null) {
            $this->erro_sql = " Campo Nome não informado.";
            $this->erro_campo = "ed149_nome";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed149_sigla === '' || $this->ed149_sigla === null) {
            $this->erro_sql = " Campo Sigla não informado.";
            $this->erro_campo = "ed149_sigla";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($ed149_sequencial === '' || $ed149_sequencial === null || $ed149_sequencial === 0) {
            $result = db_query("select nextval('bnccdisciplinas_ed149_sequencial_seq')");
            if (!$result) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: bnccdisciplinas_ed149_sequencial_seq do campo: ed149_sequencial";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->ed149_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("SELECT last_value FROM bnccdisciplinas_ed149_sequencial_seq");
            if ($result && pg_result($result, 0, 0) < $ed149_sequencial) {
                $this->erro_sql = " Campo ed149_sequencial maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->ed149_sequencial = $ed149_sequencial;
            }
        }
        if ($this->ed149_sequencial === null || $this->ed149_sequencial === '' || $this->ed149_sequencial === 0) {
            $this->erro_sql = " Campo ed149_sequencial não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = '0';
            return false;
        }
        $sql = "
            INSERT INTO bnccdisciplinas (
                ed149_sequencial
                ,ed149_nome
                ,ed149_sigla
                ,ed149_area_conhecimento
                ,ed149_ensino
            ) VALUES (
                 " . ($this->ed149_sequencial === null || $this->ed149_sequencial === '' ? 'NULL' : $this->ed149_sequencial) . "
                ," . ($this->ed149_nome === null || $this->ed149_nome === '' ? 'NULL' : "'{$this->ed149_nome}'") . "
                ," . ($this->ed149_sigla === null || $this->ed149_sigla === '' ? 'NULL' : "'{$this->ed149_sigla}'") . "
                ," . ($this->ed149_area_conhecimento === null || $this->ed149_area_conhecimento === '' ? 'NULL' : "'{$this->ed149_area_conhecimento}'") . "
                ," . ($this->ed149_ensino === null || $this->ed149_ensino === '' ? 'NULL' : "'{$this->ed149_ensino}'") . "
            )
        ";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = " () não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = " já cadastrado";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql = " () não Incluído. Inclusão Abortada.";
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

            $resaco = $this->sql_record($this->sql_query_file($this->ed149_sequencial));
            if ($resaco != false || $this->numrows != 0) {
                $resac = db_query("SELECT nextval('db_acount_id_acount_seq') AS acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("INSERT INTO db_acountacesso VALUES ($acount, " . db_getsession("DB_acessado") . ")");
                $resac = db_query("INSERT INTO db_acountkey VALUES ($acount,1010922,'$this->ed149_sequencial','I')");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010504,1010922,'','" . AddSlashes(pg_result($resaco, 0, 'ed149_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010504,1010923,'','" . AddSlashes(pg_result($resaco, 0, 'ed149_nome')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010504,1010924,'','" . AddSlashes(pg_result($resaco, 0, 'ed149_sigla')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010504,1010925,'','" . AddSlashes(pg_result($resaco, 0, 'ed149_area_conhecimento')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010504,1010926,'','" . AddSlashes(pg_result($resaco, 0, 'ed149_ensino')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
    }

    public function alterar($ed149_sequencial = null)
    {
        $sql = "UPDATE bnccdisciplinas SET ";
        $virgula = '';
        if (empty($ed149_sequencial)) {
            throw new Exception('Campo ed149_sequencial é obrigatório!');
        }
        $this->ed149_sequencial = $ed149_sequencial;
        if (trim($this->ed149_nome) !== '' && $this->ed149_nome !== null) {
            $sql .= "{$virgula} ed149_nome = '{$this->ed149_nome}' ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Nome" é obrigatório.');
        }
        if (trim($this->ed149_sigla) !== '' && $this->ed149_sigla !== null) {
            $sql .= "{$virgula} ed149_sigla = '{$this->ed149_sigla}' ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Sigla" é obrigatório.');
        }
        if (trim($this->ed149_area_conhecimento) !== '' && $this->ed149_area_conhecimento !== null) {
            $sql .= "{$virgula} ed149_area_conhecimento = '{$this->ed149_area_conhecimento}' ";
            $virgula = ',';
        } else {
            $sql .= "{$virgula} ed149_area_conhecimento = NULL ";
            $virgula = ',';
        }
        if (trim($this->ed149_ensino) !== '' && $this->ed149_ensino !== null) {
            $sql .= "{$virgula} ed149_ensino = '{$this->ed149_ensino}' ";
        } else {
            $sql .= "{$virgula} ed149_ensino = NULL ";
        }

        if ($ed149_sequencial !== '' && $ed149_sequencial !== null && $ed149_sequencial !== 0) {
            $sql .= ' WHERE';
            $sql .= " ed149_sequencial = {$ed149_sequencial}";
        }

        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->ed149_sequencial));
            if ($this->numrows > 0) {

                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1010922,'$this->ed149_sequencial','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed149_sequencial"]) || $this->ed149_sequencial != "")
                        $resac = db_query("insert into db_acount values($acount,1010504,1010922,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed149_sequencial')) . "','$this->ed149_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed149_nome"]) || $this->ed149_nome != "")
                        $resac = db_query("insert into db_acount values($acount,1010504,1010923,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed149_nome')) . "','$this->ed149_nome'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed149_sigla"]) || $this->ed149_sigla != "")
                        $resac = db_query("insert into db_acount values($acount,1010504,1010924,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed149_sigla')) . "','$this->ed149_sigla'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed149_area_conhecimento"]) || $this->ed149_area_conhecimento != "")
                        $resac = db_query("insert into db_acount values($acount,1010504,1010925,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed149_area_conhecimento')) . "','$this->ed149_area_conhecimento'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed149_ensino"]) || $this->ed149_ensino != "")
                        $resac = db_query("insert into db_acount values($acount,1010504,1010926,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed149_ensino')) . "','$this->ed149_ensino'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = " não Alterado. Alteração Abortada.\\n";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = " não foi Alterado. Alteração Executada.\\n";
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

    public function excluir($ed149_sequencial = null, $dbwhere = null)
    {
        if (empty($ed149_sequencial) && empty($dbwhere)) {
            throw new Exception('Campo ed149_sequencial é obrigatório!');
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            if (empty($dbwhere)) {

                $resaco = $this->sql_record($this->sql_query_file($ed149_sequencial));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1010922,'$ed149_sequencial','E')");
                    $resac = db_query("insert into db_acount values($acount,1010504,1010922,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed149_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010504,1010923,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed149_nome')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010504,1010924,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed149_sigla')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010504,1010925,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed149_area_conhecimento')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010504,1010926,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed149_ensino')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " delete from bnccdisciplinas
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed149_sequencial)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " ed149_sequencial = $ed149_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = " não Excluído. Exclusão Abortada.\\n";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = " não Encontrado. Exclusão não Efetuada.\\n";
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
            $this->erro_sql = "Record Vazio na Tabela:bnccdisciplinas";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query($ed149_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos}";
        $sql .= "  from bnccdisciplinas ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed149_sequencial)) {
                $sql2 .= " where bnccdisciplinas.ed149_sequencial = $ed149_sequencial ";
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

    public function sql_query_file($ed149_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos} ";
        $sql .= "  from bnccdisciplinas ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed149_sequencial)) {
                $sql2 .= " where bnccdisciplinas.ed149_sequencial = $ed149_sequencial ";
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
