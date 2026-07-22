<?php

class cl_caddisciplinabnccdisciplinas
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
    public $ed153_sequencial = 0;
    /**
     * @var int
     */
    public $ed153_caddisciplina = 0;
    /**
     * @var int
     */
    public $ed153_bnccdisciplina = 0;
    public $campos = "ed153_sequencial = int4 = Código
                      ed153_caddisciplina = int4 = Disciplina e-cidade
                      ed153_bnccdisciplina = int4 = Disciplina BNCC";

    public function __construct()
    {
        $this->rotulo = new rotulo('caddisciplinabnccdisciplinas');
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

    public function incluir($ed153_sequencial)
    {
        if ($this->ed153_sequencial === '' || $this->ed153_sequencial === null) {
            $result = db_query("select nextval('caddisciplinabnccdisciplinas_ed153_sequencial_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: caddisciplinabnccdisciplinas_ed153_sequencial_seq do campo: ed153_sequencial";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->ed153_sequencial = pg_result($result, 0, 0);
        }
        if ($this->ed153_caddisciplina === '' || $this->ed153_caddisciplina === null) {
            $this->erro_sql = " Campo Disciplina e-cidade não informado.";
            $this->erro_campo = "ed153_caddisciplina";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed153_bnccdisciplina === '' || $this->ed153_bnccdisciplina === null) {
            $this->erro_sql = " Campo Disciplina BNCC não informado.";
            $this->erro_campo = "ed153_bnccdisciplina";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($ed153_sequencial === '' || $ed153_sequencial === null || $ed153_sequencial === 0) {
            $result = db_query("select nextval('caddisciplinabnccdisciplinas_ed153_sequencial_seq')");
            if (!$result) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: caddisciplinabnccdisciplinas_ed153_sequencial_seq do campo: ed153_sequencial";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->ed153_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("SELECT last_value FROM caddisciplinabnccdisciplinas_ed153_sequencial_seq");
            if ($result && pg_result($result, 0, 0) < $ed153_sequencial) {
                $this->erro_sql = " Campo ed153_sequencial maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->ed153_sequencial = $ed153_sequencial;
            }
        }
        if ($this->ed153_sequencial === null || $this->ed153_sequencial === '' || $this->ed153_sequencial === 0) {
            $this->erro_sql = " Campo ed153_sequencial não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = '0';
            return false;
        }
        $sql = "
            INSERT INTO caddisciplinabnccdisciplinas (
                ed153_sequencial
                ,ed153_caddisciplina
                ,ed153_bnccdisciplina
            ) VALUES (
                 " . ($this->ed153_sequencial === null || $this->ed153_sequencial === '' ? 'NULL' : $this->ed153_sequencial) . "
                ," . ($this->ed153_caddisciplina === null || $this->ed153_caddisciplina === '' ? 'NULL' : $this->ed153_caddisciplina) . "
                ," . ($this->ed153_bnccdisciplina === null || $this->ed153_bnccdisciplina === '' ? 'NULL' : $this->ed153_bnccdisciplina) . "
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

            $resaco = $this->sql_record($this->sql_query_file($this->ed153_sequencial));
            if ($resaco != false || $this->numrows != 0) {
                $resac = db_query("SELECT nextval('db_acount_id_acount_seq') AS acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("INSERT INTO db_acountacesso VALUES ($acount, " . db_getsession("DB_acessado") . ")");
                $resac = db_query("INSERT INTO db_acountkey VALUES ($acount,1010930,'$this->ed153_sequencial','I')");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010506,1010930,'','" . AddSlashes(pg_result($resaco, 0, 'ed153_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010506,1010931,'','" . AddSlashes(pg_result($resaco, 0, 'ed153_caddisciplina')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010506,1010932,'','" . AddSlashes(pg_result($resaco, 0, 'ed153_bnccdisciplina')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
    }

    public function alterar($ed153_sequencial = null)
    {
        $sql = "UPDATE caddisciplinabnccdisciplinas SET ";
        $virgula = '';
        if (empty($ed153_sequencial)) {
            throw new Exception('Campo ed153_sequencial é obrigatório!');
        }
        $this->ed153_sequencial = $ed153_sequencial;
        if (trim($this->ed153_caddisciplina) !== '' && $this->ed153_caddisciplina !== null) {
            $sql .= "{$virgula} ed153_caddisciplina = {$this->ed153_caddisciplina} ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Disciplina e-cidade" é obrigatório.');
        }
        if (trim($this->ed153_bnccdisciplina) !== '' && $this->ed153_bnccdisciplina !== null) {
            $sql .= "{$virgula} ed153_bnccdisciplina = {$this->ed153_bnccdisciplina} ";
        } else {
            throw new Exception('Campo "Disciplina BNCC" é obrigatório.');
        }

        if ($ed153_sequencial !== '' && $ed153_sequencial !== null && $ed153_sequencial !== 0) {
            $sql .= ' WHERE';
            $sql .= " ed153_sequencial = {$ed153_sequencial}";
        }

        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->ed153_sequencial));
            if ($this->numrows > 0) {

                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1010930,'$this->ed153_sequencial','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed153_sequencial"]) || $this->ed153_sequencial != "")
                        $resac = db_query("insert into db_acount values($acount,1010506,1010930,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed153_sequencial')) . "','$this->ed153_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed153_caddisciplina"]) || $this->ed153_caddisciplina != "")
                        $resac = db_query("insert into db_acount values($acount,1010506,1010931,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed153_caddisciplina')) . "','$this->ed153_caddisciplina'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed153_bnccdisciplina"]) || $this->ed153_bnccdisciplina != "")
                        $resac = db_query("insert into db_acount values($acount,1010506,1010932,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed153_bnccdisciplina')) . "','$this->ed153_bnccdisciplina'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
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

    public function excluir($ed153_sequencial = null, $dbwhere = null)
    {
        if (empty($ed153_sequencial) && empty($dbwhere)) {
            throw new Exception('Campo ed153_sequencial é obrigatório!');
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            if (empty($dbwhere)) {

                $resaco = $this->sql_record($this->sql_query_file($ed153_sequencial));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1010930,'$ed153_sequencial','E')");
                    $resac = db_query("insert into db_acount values($acount,1010506,1010930,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed153_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010506,1010931,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed153_caddisciplina')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010506,1010932,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed153_bnccdisciplina')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " delete from caddisciplinabnccdisciplinas
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed153_sequencial)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " ed153_sequencial = $ed153_sequencial ";
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
            $this->erro_sql = "Record Vazio na Tabela:caddisciplinabnccdisciplinas";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query($ed153_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos}";
        $sql .= "  from caddisciplinabnccdisciplinas ";
        $sql .= "      inner join caddisciplina  on  caddisciplina.ed232_i_codigo = caddisciplinabnccdisciplinas.ed153_caddisciplina";
        $sql .= "      inner join bnccdisciplinas  on  bnccdisciplinas.ed149_sequencial = caddisciplinabnccdisciplinas.ed153_bnccdisciplina";
        $sql .= "      left  join areaconhecimento  on  areaconhecimento.ed293_sequencial = caddisciplina.ed232_areaconhecimento";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed153_sequencial)) {
                $sql2 .= " where caddisciplinabnccdisciplinas.ed153_sequencial = $ed153_sequencial ";
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

    public function sql_query_file($ed153_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos} ";
        $sql .= "  from caddisciplinabnccdisciplinas ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed153_sequencial)) {
                $sql2 .= " where caddisciplinabnccdisciplinas.ed153_sequencial = $ed153_sequencial ";
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
