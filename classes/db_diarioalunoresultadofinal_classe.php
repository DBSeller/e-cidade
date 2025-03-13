<?php

class cl_diarioalunoresultadofinal
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
    public $ed165_codigo = 0;
    public $ed165_diarioaluno = 0;
    public $ed165_resultado_final = null;
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 ed165_codigo = int4 = Código
                 ed165_diarioaluno = int4 = Diário Aluno
                 ed165_resultado_final = varchar(1) = Resultado final
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("diarioalunoresultadofinal");
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
            $this->ed165_codigo = ($this->ed165_codigo == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed165_codigo"] : $this->ed165_codigo);
            $this->ed165_diarioaluno = ($this->ed165_diarioaluno == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed165_diarioaluno"] : $this->ed165_diarioaluno);
            $this->ed165_resultado_final = ($this->ed165_resultado_final == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed165_resultado_final"] : $this->ed165_resultado_final);
        } else {
            $this->ed165_codigo = ($this->ed165_codigo == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed165_codigo"] : $this->ed165_codigo);
        }
    }

    public function incluir($ed165_codigo)
    {
        $this->atualizacampos();
        if ($this->ed165_diarioaluno == null) {
            $this->erro_sql = " Campo Diário Aluno não informado.";
            $this->erro_campo = "ed165_diarioaluno";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if ($ed165_codigo == "" || $ed165_codigo == null) {
            $result = db_query("select nextval('diarioalunoresultadofinal_ed165_codigo_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: diarioalunoresultadofinal_ed165_codigo_seq do campo: ed165_codigo";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->ed165_codigo = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from diarioalunoresultadofinal_ed165_codigo_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $ed165_codigo)) {
                $this->erro_sql = " Campo ed165_codigo maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->ed165_codigo = $ed165_codigo;
            }
        }
        if (($this->ed165_codigo == null) || ($this->ed165_codigo == "")) {
            $this->erro_sql = " Campo ed165_codigo não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        $resultadao = empty($this->ed165_resultado_final) ? "null" : "'$this->ed165_resultado_final'";

        $sql = "insert into diarioalunoresultadofinal(
                                       ed165_codigo
                                      ,ed165_diarioaluno
                                      ,ed165_resultado_final
                       )
                values (
                                {$this->ed165_codigo}
                               ,{$this->ed165_diarioaluno}
                               ,{$resultadao}
                       )";

        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = "Diário resultado final ($this->ed165_codigo) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "Diário resultado final já Cadastrado";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql = "Diário resultado final ($this->ed165_codigo) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
        $this->erro_sql .= "Valores : " . $this->ed165_codigo;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->ed165_codigo));
            if (($resaco != false) || ($this->numrows != 0)) {

                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,1011136,'$this->ed165_codigo','I')");
                $resac = db_query("insert into db_acount values($acount,1010541,1011136,'','" . AddSlashes(pg_result($resaco, 0, 'ed165_codigo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010541,1011137,'','" . AddSlashes(pg_result($resaco, 0, 'ed165_diarioaluno')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010541,1011138,'','" . AddSlashes(pg_result($resaco, 0, 'ed165_resultado_final')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
    }

    public function alterar($ed165_codigo = null)
    {
        $this->atualizacampos();
        $sql = " update diarioalunoresultadofinal set ";
        $virgula = "";
        if (trim($this->ed165_codigo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed165_codigo"])) {
            $sql .= $virgula . " ed165_codigo = $this->ed165_codigo ";
            $virgula = ",";
            if (trim($this->ed165_codigo) == null) {
                $this->erro_sql = " Campo Código não informado.";
                $this->erro_campo = "ed165_codigo";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed165_diarioaluno) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed165_diarioaluno"])) {
            $sql .= $virgula . " ed165_diarioaluno = $this->ed165_diarioaluno ";
            $virgula = ",";
            if (trim($this->ed165_diarioaluno) == null) {
                $this->erro_sql = " Campo Diário Aluno não informado.";
                $this->erro_campo = "ed165_diarioaluno";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->ed165_resultado_final) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed165_resultado_final"])) {
            $sql .= $virgula . " ed165_resultado_final = '$this->ed165_resultado_final' ";
            $virgula = ",";
        }

        if (empty($this->ed165_resultado_final)) {
            $sql .= $virgula . " ed165_resultado_final = null ";
        }

        $sql .= " where ";
        if ($ed165_codigo != null) {
            $sql .= " ed165_codigo = $this->ed165_codigo";
        }

        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->ed165_codigo));
            if ($this->numrows > 0) {

                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1011136,'$this->ed165_codigo','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed165_codigo"]) || $this->ed165_codigo != "")
                        $resac = db_query("insert into db_acount values($acount,1010541,1011136,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed165_codigo')) . "','$this->ed165_codigo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed165_diarioaluno"]) || $this->ed165_diarioaluno != "")
                        $resac = db_query("insert into db_acount values($acount,1010541,1011137,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed165_diarioaluno')) . "','$this->ed165_diarioaluno'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed165_resultado_final"]) || $this->ed165_resultado_final != "")
                        $resac = db_query("insert into db_acount values($acount,1010541,1011138,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed165_resultado_final')) . "','$this->ed165_resultado_final'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Diário resultado final não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->ed165_codigo;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Diário resultado final não foi Alterado. Alteração Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->ed165_codigo;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $this->ed165_codigo;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($ed165_codigo = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            if (empty($dbwhere)) {

                $resaco = $this->sql_record($this->sql_query_file($ed165_codigo));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1011136,'$ed165_codigo','E')");
                    $resac = db_query("insert into db_acount values($acount,1010541,1011136,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed165_codigo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010541,1011137,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed165_diarioaluno')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010541,1011138,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed165_resultado_final')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " delete from diarioalunoresultadofinal
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed165_codigo)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " ed165_codigo = $ed165_codigo ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Diário resultado final não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $ed165_codigo;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Diário resultado final não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $ed165_codigo;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $ed165_codigo;
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
            $this->erro_sql = "Record Vazio na Tabela:diarioalunoresultadofinal";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query($ed165_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos}";
        $sql .= "  from diarioalunoresultadofinal ";
        $sql .= "      inner join diarioaluno  on  diarioaluno.ed161_codigo = diarioalunoresultadofinal.ed165_diarioaluno";
        $sql .= "      inner join serie  on  serie.ed11_i_codigo = diarioaluno.ed161_serie";
        $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = diarioaluno.ed161_aluno";
        $sql .= "      inner join turma  on  turma.ed57_i_codigo = diarioaluno.ed161_turma";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed165_codigo)) {
                $sql2 .= " where diarioalunoresultadofinal.ed165_codigo = $ed165_codigo ";
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

    public function sql_query_file($ed165_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos} ";
        $sql .= "  from diarioalunoresultadofinal ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed165_codigo)) {
                $sql2 .= " where diarioalunoresultadofinal.ed165_codigo = $ed165_codigo ";
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
