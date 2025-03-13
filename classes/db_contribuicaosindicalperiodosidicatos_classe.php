<?php

class cl_contribuicaosindicalperiodosidicatos
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
    public $eso31_sequencial = 0;
    public $eso31_rhsindicato = 0;
    public $eso31_tipo = 0;
    public $eso31_valor = 0;
    public $eso31_contribuicaosindicalperiodo = 0;
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 eso31_sequencial = int4 = Código 
                 eso31_rhsindicato = int4 = Sindicato 
                 eso31_tipo = int4 = Tipo 
                 eso31_valor = float8 = Valor 
                 eso31_contribuicaosindicalperiodo = int4 = Período 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("contribuicaosindicalperiodosidicatos");
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

    public function incluir($eso31_sequencial)
    {
        $this->atualizacampos();
        if ($this->eso31_rhsindicato == null) {
            $this->erro_sql = " Campo Sindicato não informado.";
            $this->erro_campo = "eso31_rhsindicato";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->eso31_tipo == null) {
            $this->erro_sql = " Campo Tipo não informado.";
            $this->erro_campo = "eso31_tipo";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->eso31_valor == null) {
            $this->erro_sql = " Campo Valor não informado.";
            $this->erro_campo = "eso31_valor";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->eso31_contribuicaosindicalperiodo == null) {
            $this->erro_sql = " Campo Período não informado.";
            $this->erro_campo = "eso31_contribuicaosindicalperiodo";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($eso31_sequencial == "" || $eso31_sequencial == null) {
            $result = db_query("select nextval('contribuicaosindicalperiodosidicatos_eso31_sequencial_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: contribuicaosindicalperiodosidicatos_eso31_sequencial_seq do campo: eso31_sequencial";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->eso31_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from contribuicaosindicalperiodosidicatos_eso31_sequencial_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $eso31_sequencial)) {
                $this->erro_sql = " Campo eso31_sequencial maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->eso31_sequencial = $eso31_sequencial;
            }
        }
        if (($this->eso31_sequencial == null) || ($this->eso31_sequencial == "")) {
            $this->erro_sql = " Campo eso31_sequencial não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into contribuicaosindicalperiodosidicatos(
                                       eso31_sequencial 
                                      ,eso31_rhsindicato 
                                      ,eso31_tipo 
                                      ,eso31_valor 
                                      ,eso31_contribuicaosindicalperiodo 
                       )
                values (
                                $this->eso31_sequencial 
                               ,$this->eso31_rhsindicato 
                               ,$this->eso31_tipo 
                               ,$this->eso31_valor 
                               ,$this->eso31_contribuicaosindicalperiodo 
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = " ($this->eso31_sequencial) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = " já Cadastrado";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql = " ($this->eso31_sequencial) não Incluído. Inclusão Abortada.";
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
        $this->erro_sql .= "Valores : " . $this->eso31_sequencial;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "",
            str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->eso31_sequencial));
            if (($resaco != false) || ($this->numrows != 0)) {

                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,1010275,'$this->eso31_sequencial','I')");
                $resac = db_query("insert into db_acount values($acount,1010402,1010275,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'eso31_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010402,1010276,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'eso31_rhsindicato')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010402,1010277,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'eso31_tipo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010402,1010278,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'eso31_valor')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010402,1010279,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'eso31_contribuicaosindicalperiodo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
    }

    public function atualizacampos($exclusao = false)
    {
        if ($exclusao == false) {
            $this->eso31_sequencial = ($this->eso31_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["eso31_sequencial"] : $this->eso31_sequencial);
            $this->eso31_rhsindicato = ($this->eso31_rhsindicato == "" ? @$GLOBALS["HTTP_POST_VARS"]["eso31_rhsindicato"] : $this->eso31_rhsindicato);
            $this->eso31_tipo = ($this->eso31_tipo == "" ? @$GLOBALS["HTTP_POST_VARS"]["eso31_tipo"] : $this->eso31_tipo);
            $this->eso31_valor = ($this->eso31_valor == "" ? @$GLOBALS["HTTP_POST_VARS"]["eso31_valor"] : $this->eso31_valor);
            $this->eso31_contribuicaosindicalperiodo = ($this->eso31_contribuicaosindicalperiodo == "" ? @$GLOBALS["HTTP_POST_VARS"]["eso31_contribuicaosindicalperiodo"] : $this->eso31_contribuicaosindicalperiodo);
        } else {
            $this->eso31_sequencial = ($this->eso31_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["eso31_sequencial"] : $this->eso31_sequencial);
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
            $this->erro_sql = "Record Vazio na Tabela:contribuicaosindicalperiodosidicatos";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query_file($eso31_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos} ";
        $sql .= "  from contribuicaosindicalperiodosidicatos ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($eso31_sequencial)) {
                $sql2 .= " where contribuicaosindicalperiodosidicatos.eso31_sequencial = $eso31_sequencial ";
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

    public function alterar($eso31_sequencial = null)
    {
        $this->atualizacampos();
        $sql = " update contribuicaosindicalperiodosidicatos set ";
        $virgula = "";
        if (trim($this->eso31_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["eso31_sequencial"])) {
            $sql .= $virgula . " eso31_sequencial = $this->eso31_sequencial ";
            $virgula = ",";
            if (trim($this->eso31_sequencial) == null) {
                $this->erro_sql = " Campo Código não informado.";
                $this->erro_campo = "eso31_sequencial";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->eso31_rhsindicato) != "" || isset($GLOBALS["HTTP_POST_VARS"]["eso31_rhsindicato"])) {
            $sql .= $virgula . " eso31_rhsindicato = $this->eso31_rhsindicato ";
            $virgula = ",";
            if (trim($this->eso31_rhsindicato) == null) {
                $this->erro_sql = " Campo Sindicato não informado.";
                $this->erro_campo = "eso31_rhsindicato";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->eso31_tipo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["eso31_tipo"])) {
            $sql .= $virgula . " eso31_tipo = $this->eso31_tipo ";
            $virgula = ",";
            if (trim($this->eso31_tipo) == null) {
                $this->erro_sql = " Campo Tipo não informado.";
                $this->erro_campo = "eso31_tipo";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->eso31_valor) != "" || isset($GLOBALS["HTTP_POST_VARS"]["eso31_valor"])) {
            $sql .= $virgula . " eso31_valor = $this->eso31_valor ";
            $virgula = ",";
            if (trim($this->eso31_valor) == null) {
                $this->erro_sql = " Campo Valor não informado.";
                $this->erro_campo = "eso31_valor";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->eso31_contribuicaosindicalperiodo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["eso31_contribuicaosindicalperiodo"])) {
            $sql .= $virgula . " eso31_contribuicaosindicalperiodo = $this->eso31_contribuicaosindicalperiodo ";
            $virgula = ",";
            if (trim($this->eso31_contribuicaosindicalperiodo) == null) {
                $this->erro_sql = " Campo Período não informado.";
                $this->erro_campo = "eso31_contribuicaosindicalperiodo";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if ($eso31_sequencial != null) {
            $sql .= " eso31_sequencial = $this->eso31_sequencial";
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->eso31_sequencial));
            if ($this->numrows > 0) {

                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1010275,'$this->eso31_sequencial','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["eso31_sequencial"]) || $this->eso31_sequencial != "") {
                        $resac = db_query("insert into db_acount values($acount,1010402,1010275,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'eso31_sequencial')) . "','$this->eso31_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["eso31_rhsindicato"]) || $this->eso31_rhsindicato != "") {
                        $resac = db_query("insert into db_acount values($acount,1010402,1010276,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'eso31_rhsindicato')) . "','$this->eso31_rhsindicato'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["eso31_tipo"]) || $this->eso31_tipo != "") {
                        $resac = db_query("insert into db_acount values($acount,1010402,1010277,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'eso31_tipo')) . "','$this->eso31_tipo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["eso31_valor"]) || $this->eso31_valor != "") {
                        $resac = db_query("insert into db_acount values($acount,1010402,1010278,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'eso31_valor')) . "','$this->eso31_valor'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["eso31_contribuicaosindicalperiodo"]) || $this->eso31_contribuicaosindicalperiodo != "") {
                        $resac = db_query("insert into db_acount values($acount,1010402,1010279,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'eso31_contribuicaosindicalperiodo')) . "','$this->eso31_contribuicaosindicalperiodo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = " não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->eso31_sequencial;
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
                $this->erro_sql .= "Valores : " . $this->eso31_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $this->eso31_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($eso31_sequencial = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            if (empty($dbwhere)) {

                $resaco = $this->sql_record($this->sql_query_file($eso31_sequencial));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1010275,'$eso31_sequencial','E')");
                    $resac = db_query("insert into db_acount values($acount,1010402,1010275,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'eso31_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010402,1010276,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'eso31_rhsindicato')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010402,1010277,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'eso31_tipo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010402,1010278,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'eso31_valor')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010402,1010279,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'eso31_contribuicaosindicalperiodo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " delete from contribuicaosindicalperiodosidicatos
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($eso31_sequencial)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " eso31_sequencial = $eso31_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = " não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $eso31_sequencial;
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
                $this->erro_sql .= "Valores : " . $eso31_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $eso31_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function sql_query($eso31_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos}";
        $sql .= "  from contribuicaosindicalperiodosidicatos ";
        $sql .= "      inner join rhsindicato  on  rhsindicato.rh116_sequencial = contribuicaosindicalperiodosidicatos.eso31_rhsindicato";
        $sql .= "      inner join contribuicaosindicalperiodo  on  contribuicaosindicalperiodo.eso30_sequencial = contribuicaosindicalperiodosidicatos.eso31_contribuicaosindicalperiodo";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = contribuicaosindicalperiodo.eso30_empregador";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($eso31_sequencial)) {
                $sql2 .= " where contribuicaosindicalperiodosidicatos.eso31_sequencial = $eso31_sequencial ";
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
