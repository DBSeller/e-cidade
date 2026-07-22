<?php

class cl_diarioarea
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
    public $ed162_codigo = 0;
    public $ed162_areaconhecimento = 0;
    public $ed162_diarioaluno = 0;
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 ed162_codigo = int4 = Código
                 ed162_areaconhecimento = int4 = Área de Conhecimento
                 ed162_diarioaluno = int4 = Diário Aluno
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("diarioarea");
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
            $this->ed162_codigo = ($this->ed162_codigo == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed162_codigo"] : $this->ed162_codigo);
            $this->ed162_areaconhecimento = ($this->ed162_areaconhecimento == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed162_areaconhecimento"] : $this->ed162_areaconhecimento);
            $this->ed162_diarioaluno = ($this->ed162_diarioaluno == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed162_diarioaluno"] : $this->ed162_diarioaluno);
        } else {
            $this->ed162_codigo = ($this->ed162_codigo == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed162_codigo"] : $this->ed162_codigo);
        }
    }

    public function incluir($ed162_codigo)
    {
        $this->atualizacampos();
        if ($this->ed162_areaconhecimento == null) {
            $this->erro_sql = " Campo Área de Conhecimento não informado.";
            $this->erro_campo = "ed162_areaconhecimento";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed162_diarioaluno == null) {
            $this->erro_sql = " Campo Diário Aluno não informado.";
            $this->erro_campo = "ed162_diarioaluno";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($ed162_codigo == "" || $ed162_codigo == null) {
            $result = db_query("select nextval('diarioarea_ed162_codigo_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: diarioarea_ed162_codigo_seq do campo: ed162_codigo";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->ed162_codigo = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from diarioarea_ed162_codigo_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $ed162_codigo)) {
                $this->erro_sql = " Campo ed162_codigo maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->ed162_codigo = $ed162_codigo;
            }
        }
        if (($this->ed162_codigo == null) || ($this->ed162_codigo == "")) {
            $this->erro_sql = " Campo ed162_codigo não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into diarioarea(
                                       ed162_codigo
                                      ,ed162_areaconhecimento
                                      ,ed162_diarioaluno
                       )
                values (
                                $this->ed162_codigo
                               ,$this->ed162_areaconhecimento
                               ,$this->ed162_diarioaluno
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = "Diário Área Conhecimento ($this->ed162_codigo) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "Diário Área Conhecimento já Cadastrado";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql = "Diário Área Conhecimento ($this->ed162_codigo) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
        $this->erro_sql .= "Valores : " . $this->ed162_codigo;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->ed162_codigo));
            if (($resaco != false) || ($this->numrows != 0)) {

                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,1011116,'$this->ed162_codigo','I')");
                $resac = db_query("insert into db_acount values($acount,1010538,1011116,'','" . AddSlashes(pg_result($resaco, 0, 'ed162_codigo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010538,1011117,'','" . AddSlashes(pg_result($resaco, 0, 'ed162_areaconhecimento')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010538,1011156,'','" . AddSlashes(pg_result($resaco, 0, 'ed162_diarioaluno')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
    }

    public function alterar($ed162_codigo = null)
    {
        $this->atualizacampos();
        $sql = " update diarioarea set ";
        $virgula = "";
        if (trim($this->ed162_codigo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed162_codigo"])) {
            $sql .= $virgula . " ed162_codigo = $this->ed162_codigo ";
            $virgula = ",";
            if (trim($this->ed162_codigo) == null) {
                $this->erro_sql = " Campo Código não informado.";
                $this->erro_campo = "ed162_codigo";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed162_areaconhecimento) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed162_areaconhecimento"])) {
            $sql .= $virgula . " ed162_areaconhecimento = $this->ed162_areaconhecimento ";
            $virgula = ",";
            if (trim($this->ed162_areaconhecimento) == null) {
                $this->erro_sql = " Campo Área de Conhecimento não informado.";
                $this->erro_campo = "ed162_areaconhecimento";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed162_diarioaluno) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed162_diarioaluno"])) {
            $sql .= $virgula . " ed162_diarioaluno = $this->ed162_diarioaluno ";
            $virgula = ",";
            if (trim($this->ed162_diarioaluno) == null) {
                $this->erro_sql = " Campo Diário Aluno não informado.";
                $this->erro_campo = "ed162_diarioaluno";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if ($ed162_codigo != null) {
            $sql .= " ed162_codigo = $this->ed162_codigo";
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->ed162_codigo));
            if ($this->numrows > 0) {

                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1011116,'$this->ed162_codigo','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed162_codigo"]) || $this->ed162_codigo != "")
                        $resac = db_query("insert into db_acount values($acount,1010538,1011116,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed162_codigo')) . "','$this->ed162_codigo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed162_areaconhecimento"]) || $this->ed162_areaconhecimento != "")
                        $resac = db_query("insert into db_acount values($acount,1010538,1011117,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed162_areaconhecimento')) . "','$this->ed162_areaconhecimento'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed162_diarioaluno"]) || $this->ed162_diarioaluno != "")
                        $resac = db_query("insert into db_acount values($acount,1010538,1011156,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed162_diarioaluno')) . "','$this->ed162_diarioaluno'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Diário Área Conhecimento não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->ed162_codigo;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Diário Área Conhecimento não foi Alterado. Alteração Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->ed162_codigo;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $this->ed162_codigo;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($ed162_codigo = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            if (empty($dbwhere)) {

                $resaco = $this->sql_record($this->sql_query_file($ed162_codigo));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1011116,'$ed162_codigo','E')");
                    $resac = db_query("insert into db_acount values($acount,1010538,1011116,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed162_codigo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010538,1011117,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed162_areaconhecimento')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010538,1011156,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed162_diarioaluno')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " delete from diarioarea
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed162_codigo)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " ed162_codigo = $ed162_codigo ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Diário Área Conhecimento não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $ed162_codigo;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Diário Área Conhecimento não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $ed162_codigo;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $ed162_codigo;
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
            $this->erro_sql = "Record Vazio na Tabela:diarioarea";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query($ed162_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos}";
        $sql .= "  from diarioarea ";
        $sql .= "      inner join areaconhecimento  on  areaconhecimento.ed293_sequencial = diarioarea.ed162_areaconhecimento";
        $sql .= "      inner join diarioaluno  on  diarioaluno.ed161_codigo = diarioarea.ed162_diarioaluno";
        $sql .= "      inner join serie  on  serie.ed11_i_codigo = diarioaluno.ed161_serie";
        $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = diarioaluno.ed161_aluno";
        $sql .= "      inner join turma  on  turma.ed57_i_codigo = diarioaluno.ed161_turma";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed162_codigo)) {
                $sql2 .= " where diarioarea.ed162_codigo = $ed162_codigo ";
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

    public function sql_query_file($ed162_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos} ";
        $sql .= "  from diarioarea ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed162_codigo)) {
                $sql2 .= " where diarioarea.ed162_codigo = $ed162_codigo ";
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
