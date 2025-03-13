<?php

class cl_contribuicaosindicalperiodo
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
    public $eso30_sequencial = 0;
    public $eso30_empregador = 0;
    public $eso30_indicativo_periodo = 0;
    public $eso30_periodo = null;
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 eso30_sequencial = int4 = Código 
                 eso30_empregador = int4 = Empregador 
                 eso30_indicativo_periodo = int4 = Indicativo de período 
                 eso30_periodo = varchar(7) = Período 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("contribuicaosindicalperiodo");
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

    public function incluir($eso30_sequencial)
    {
        $this->atualizacampos();
        if ($this->eso30_empregador == null) {
            $this->erro_sql = " Campo Empregador não informado.";
            $this->erro_campo = "eso30_empregador";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->eso30_indicativo_periodo == null) {
            $this->erro_sql = " Campo Indicativo de período não informado.";
            $this->erro_campo = "eso30_indicativo_periodo";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->eso30_periodo == null) {
            $this->erro_sql = " Campo Período não informado.";
            $this->erro_campo = "eso30_periodo";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($eso30_sequencial == "" || $eso30_sequencial == null) {
            $result = db_query("select nextval('contribuicaosindicalperiodo_eso30_sequencial_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: contribuicaosindicalperiodo_eso30_sequencial_seq do campo: eso30_sequencial";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->eso30_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from contribuicaosindicalperiodo_eso30_sequencial_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $eso30_sequencial)) {
                $this->erro_sql = " Campo eso30_sequencial maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->eso30_sequencial = $eso30_sequencial;
            }
        }
        if (($this->eso30_sequencial == null) || ($this->eso30_sequencial == "")) {
            $this->erro_sql = " Campo eso30_sequencial não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into contribuicaosindicalperiodo(
                                       eso30_sequencial 
                                      ,eso30_empregador 
                                      ,eso30_indicativo_periodo 
                                      ,eso30_periodo 
                       )
                values (
                                $this->eso30_sequencial 
                               ,$this->eso30_empregador 
                               ,$this->eso30_indicativo_periodo 
                               ,'$this->eso30_periodo' 
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = " ($this->eso30_sequencial) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = " já Cadastrado";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql = " ($this->eso30_sequencial) não Incluído. Inclusão Abortada.";
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
        $this->erro_sql .= "Valores : " . $this->eso30_sequencial;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "",
            str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->eso30_sequencial));
            if (($resaco != false) || ($this->numrows != 0)) {

                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,1010271,'$this->eso30_sequencial','I')");
                $resac = db_query("insert into db_acount values($acount,1010401,1010271,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'eso30_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010401,1010272,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'eso30_empregador')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010401,1010273,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'eso30_indicativo_periodo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010401,1010274,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'eso30_periodo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
    }

    public function atualizacampos($exclusao = false)
    {
        if ($exclusao == false) {
            $this->eso30_sequencial = ($this->eso30_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["eso30_sequencial"] : $this->eso30_sequencial);
            $this->eso30_empregador = ($this->eso30_empregador == "" ? @$GLOBALS["HTTP_POST_VARS"]["eso30_empregador"] : $this->eso30_empregador);
            $this->eso30_indicativo_periodo = ($this->eso30_indicativo_periodo == "" ? @$GLOBALS["HTTP_POST_VARS"]["eso30_indicativo_periodo"] : $this->eso30_indicativo_periodo);
            $this->eso30_periodo = ($this->eso30_periodo == "" ? @$GLOBALS["HTTP_POST_VARS"]["eso30_periodo"] : $this->eso30_periodo);
        } else {
            $this->eso30_sequencial = ($this->eso30_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["eso30_sequencial"] : $this->eso30_sequencial);
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
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $this->numrows = pg_num_rows($result);
        if ($this->numrows == 0) {
            $this->erro_banco = "";
            $this->erro_sql = "Record Vazio na Tabela:contribuicaosindicalperiodo";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query_file($eso30_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos} ";
        $sql .= "  from contribuicaosindicalperiodo ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($eso30_sequencial)) {
                $sql2 .= " where contribuicaosindicalperiodo.eso30_sequencial = $eso30_sequencial ";
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

    public function alterar($eso30_sequencial = null)
    {
        $this->atualizacampos();
        $sql = " update contribuicaosindicalperiodo set ";
        $virgula = "";
        if (trim($this->eso30_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["eso30_sequencial"])) {
            $sql .= $virgula . " eso30_sequencial = $this->eso30_sequencial ";
            $virgula = ",";
            if (trim($this->eso30_sequencial) == null) {
                $this->erro_sql = " Campo Código não informado.";
                $this->erro_campo = "eso30_sequencial";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->eso30_empregador) != "" || isset($GLOBALS["HTTP_POST_VARS"]["eso30_empregador"])) {
            $sql .= $virgula . " eso30_empregador = $this->eso30_empregador ";
            $virgula = ",";
            if (trim($this->eso30_empregador) == null) {
                $this->erro_sql = " Campo Empregador não informado.";
                $this->erro_campo = "eso30_empregador";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->eso30_indicativo_periodo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["eso30_indicativo_periodo"])) {
            $sql .= $virgula . " eso30_indicativo_periodo = $this->eso30_indicativo_periodo ";
            $virgula = ",";
            if (trim($this->eso30_indicativo_periodo) == null) {
                $this->erro_sql = " Campo Indicativo de período não informado.";
                $this->erro_campo = "eso30_indicativo_periodo";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->eso30_periodo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["eso30_periodo"])) {
            $sql .= $virgula . " eso30_periodo = '$this->eso30_periodo' ";
            $virgula = ",";
            if (trim($this->eso30_periodo) == null) {
                $this->erro_sql = " Campo Período não informado.";
                $this->erro_campo = "eso30_periodo";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if ($eso30_sequencial != null) {
            $sql .= " eso30_sequencial = $this->eso30_sequencial";
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->eso30_sequencial));
            if ($this->numrows > 0) {

                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1010271,'$this->eso30_sequencial','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["eso30_sequencial"]) || $this->eso30_sequencial != "") {
                        $resac = db_query("insert into db_acount values($acount,1010401,1010271,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'eso30_sequencial')) . "','$this->eso30_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["eso30_empregador"]) || $this->eso30_empregador != "") {
                        $resac = db_query("insert into db_acount values($acount,1010401,1010272,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'eso30_empregador')) . "','$this->eso30_empregador'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["eso30_indicativo_periodo"]) || $this->eso30_indicativo_periodo != "") {
                        $resac = db_query("insert into db_acount values($acount,1010401,1010273,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'eso30_indicativo_periodo')) . "','$this->eso30_indicativo_periodo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["eso30_periodo"]) || $this->eso30_periodo != "") {
                        $resac = db_query("insert into db_acount values($acount,1010401,1010274,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'eso30_periodo')) . "','$this->eso30_periodo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = " não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->eso30_sequencial;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = " não foi Alterado. Alteração Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->eso30_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $this->eso30_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($eso30_sequencial = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            if (empty($dbwhere)) {

                $resaco = $this->sql_record($this->sql_query_file($eso30_sequencial));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1010271,'$eso30_sequencial','E')");
                    $resac = db_query("insert into db_acount values($acount,1010401,1010271,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'eso30_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010401,1010272,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'eso30_empregador')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010401,1010273,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'eso30_indicativo_periodo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010401,1010274,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'eso30_periodo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " delete from contribuicaosindicalperiodo
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($eso30_sequencial)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " eso30_sequencial = $eso30_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = " não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $eso30_sequencial;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = " não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $eso30_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $eso30_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function sql_query($eso30_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos}";
        $sql .= "  from contribuicaosindicalperiodo ";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = contribuicaosindicalperiodo.eso30_empregador";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($eso30_sequencial)) {
                $sql2 .= " where contribuicaosindicalperiodo.eso30_sequencial = $eso30_sequencial ";
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
