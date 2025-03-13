<?php

class cl_orcparamseqinfocomplementar
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
    public $o157_relatorio = 0;
    public $o157_conplanoinfocomplementar = 0;
    public $o157_valor = null;
    public $o157_sequencial = 0;
    public $o157_linha = 0;
    public $o157_padrao = false;
    public $o157_infocomplementarlancamento = 0;
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 o157_relatorio = int8 = Relatório 
                 o157_conplanoinfocomplementar = int8 = Informação Complementar 
                 o157_valor = text = Valor 
                 o157_sequencial = int8 = Sequencial 
                 o157_linha = int8 = Linha 
                 o157_padrao = bool = Padrão 
                 o157_infocomplementarlancamento = int8 = Linha Informação Complementar 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("orcparamseqinfocomplementar");
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

    public function incluir($o157_sequencial)
    {
        if ($this->o157_conplanoinfocomplementar == null) {
            $this->erro_sql = " Campo Informação Complementar não informado.";
            $this->erro_campo = "o157_conplanoinfocomplementar";
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
        if ($this->o157_valor == null) {
            $this->erro_sql = " Campo Valor não informado.";
            $this->erro_campo = "o157_valor";
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
        if ($this->o157_linha == null) {
            $this->erro_sql = " Campo Linha não informado.";
            $this->erro_campo = "o157_linha";
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
        if ($this->o157_padrao === null || $this->o157_padrao === '') {
            $this->erro_sql = " Campo Padrão não informado.";
            $this->erro_campo = "o157_padrao";
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
        if ($this->o157_infocomplementarlancamento == null) {
            $this->erro_sql = " Campo Linha Informação Complementar não informado.";
            $this->erro_campo = "o157_infocomplementarlancamento";
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
        if ($o157_sequencial === '' || $o157_sequencial === null || $o157_sequencial === 0) {
            $result = db_query("SELECT nextval('orcparamseqinfocomplementar_o157_sequencial_seq') AS sequencial");

            if (!$result) {
                throw new Exception('Não foi possível buscar o próximo sequencial.');
            }

            $this->o157_sequencial = pg_fetch_object($result)->sequencial;
        } else {
            $result = db_query("SELECT last_value AS sequencial FROM orcparamseqinfocomplementar_o157_sequencial_seq");

            if ($result && pg_fetch_object($result)->sequencial < $o157_sequencial) {
                throw new Exception('Sequencial informado é inválido.');
            } else {
                $this->o157_sequencial = $o157_sequencial;
            }
        }

        if ($this->o157_sequencial === null || $this->o157_sequencial === '' || $this->o157_sequencial === 0) {
            $this->erro_sql = " Campo o157_sequencial não declarado.";
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

        $sql = "
            INSERT INTO orcparamseqinfocomplementar (
                 o157_relatorio
                ,o157_conplanoinfocomplementar
                ,o157_valor
                ,o157_sequencial
                ,o157_linha
                ,o157_padrao
                ,o157_infocomplementarlancamento
            ) VALUES (
                 " . ($this->o157_relatorio === null || $this->o157_relatorio === '' ? 'NULL' : $this->o157_relatorio) . "
                ," . ($this->o157_conplanoinfocomplementar === null || $this->o157_conplanoinfocomplementar === '' ? 'NULL' : $this->o157_conplanoinfocomplementar) . "
                ," . ($this->o157_valor === null || $this->o157_valor === '' ? 'NULL' : "'{$this->o157_valor}'") . "
                ," . ($this->o157_sequencial === null || $this->o157_sequencial === '' ? 'NULL' : $this->o157_sequencial) . "
                ," . ($this->o157_linha === null || $this->o157_linha === '' ? 'NULL' : $this->o157_linha) . "
                ," . ($this->o157_padrao === null || $this->o157_padrao === '' ? 'NULL' : ($this->o157_padrao ? 'TRUE' : 'FALSE')) . "
                ," . ($this->o157_infocomplementarlancamento === null || $this->o157_infocomplementarlancamento === '' ? 'NULL' : $this->o157_infocomplementarlancamento) . "
            )
        ";

        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = "Contas da Linha/Coluna ($this->o157_sequencial) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "Contas da Linha/Coluna já Cadastrado";
                $this->erro_msg .= str_replace(
                    '"',
                    "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
                );
            } else {
                $this->erro_sql = "Contas da Linha/Coluna ($this->o157_sequencial) não Incluído. Inclusão Abortada.";
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
        $this->erro_sql .= "Valores : " . $this->o157_sequencial;
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
            $resaco = $this->sql_record($this->sql_query_file($this->o157_sequencial));
            if (($resaco != false) || ($this->numrows != 0)) {
                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                db_query("insert into db_acountkey values($acount,1010354,'$this->o157_sequencial','I')");
                db_query("insert into db_acount values($acount,1010424,1010357,'','" . AddSlashes(pg_result(
                    $resaco,
                    0,
                    'o157_relatorio'
                )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                db_query("insert into db_acount values($acount,1010424,1010356,'','" . AddSlashes(pg_result(
                    $resaco,
                    0,
                    'o157_conplanoinfocomplementar'
                )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                db_query("insert into db_acount values($acount,1010424,1010355,'','" . AddSlashes(pg_result(
                    $resaco,
                    0,
                    'o157_valor'
                )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                db_query("insert into db_acount values($acount,1010424,1010354,'','" . AddSlashes(pg_result(
                    $resaco,
                    0,
                    'o157_sequencial'
                )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                db_query("insert into db_acount values($acount,1010424,1010364,'','" . AddSlashes(pg_result(
                    $resaco,
                    0,
                    'o157_linha'
                )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                db_query("insert into db_acount values($acount,1010424,1010407,'','" . AddSlashes(pg_result(
                    $resaco,
                    0,
                    'o157_padrao'
                )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                db_query("insert into db_acount values($acount,1010424,1010415,'','" . AddSlashes(pg_result(
                    $resaco,
                    0,
                    'o157_infocomplementarlancamento'
                )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        if ($o157_sequencial === '' || $o157_sequencial === null || $o157_sequencial === 0) {
            db_query("
                SELECT setval('orcparamseqinfocomplementar_o157_sequencial_seq',
                              (SELECT max(o157_sequencial) FROM orcparamseqinfocomplementar));
            ");
        }
        return true;
    }

    public function alterar($o157_sequencial = null)
    {
        $sql = " UPDATE orcparamseqinfocomplementar SET ";
        $virgula = "";
        $this->o157_sequencial = $o157_sequencial;
        if (trim($this->o157_relatorio) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o157_relatorio"])) {
            $sql .= $virgula . " o157_relatorio = $this->o157_relatorio ";
            $virgula = ",";
            if (trim($this->o157_relatorio) == null) {
                $this->erro_sql = " Campo Relatório não informado.";
                $this->erro_campo = "o157_relatorio";
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
        if (trim($this->o157_conplanoinfocomplementar) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o157_conplanoinfocomplementar"])) {
            $sql .= $virgula . " o157_conplanoinfocomplementar = $this->o157_conplanoinfocomplementar ";
            $virgula = ",";
            if (trim($this->o157_conplanoinfocomplementar) == null) {
                $this->erro_sql = " Campo Informação Complementar não informado.";
                $this->erro_campo = "o157_conplanoinfocomplementar";
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
        if (trim($this->o157_valor) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o157_valor"])) {
            $sql .= $virgula . " o157_valor = '$this->o157_valor' ";
            $virgula = ",";
            if (trim($this->o157_valor) == null) {
                $this->erro_sql = " Campo Valor não informado.";
                $this->erro_campo = "o157_valor";
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
        if (trim($this->o157_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o157_sequencial"])) {
            $sql .= $virgula . " o157_sequencial = $this->o157_sequencial ";
            $virgula = ",";
            if (trim($this->o157_sequencial) == null) {
                $this->erro_sql = " Campo Sequencial não informado.";
                $this->erro_campo = "o157_sequencial";
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
        if (trim($this->o157_linha) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o157_linha"])) {
            $sql .= $virgula . " o157_linha = $this->o157_linha ";
            $virgula = ",";
            if (trim($this->o157_linha) == null) {
                $this->erro_sql = " Campo Linha não informado.";
                $this->erro_campo = "o157_linha";
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

        if (trim($this->o157_padrao) !== '' && $this->o157_padrao !== null) {
            $sql .= "{$virgula} o157_padrao = " . ($this->o157_padrao ? 'TRUE' : 'FALSE') . ' ';
            $virgula = ',';
        } else {
            $sql .= "{$virgula} o157_padrao = NULL ";
            $virgula = ',';
        }

        if (trim($this->o157_infocomplementarlancamento) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o157_infocomplementarlancamento"])) {
            $sql .= $virgula . " o157_infocomplementarlancamento = $this->o157_infocomplementarlancamento ";
            if (trim($this->o157_infocomplementarlancamento) == null) {
                $this->erro_sql = " Campo Linha Informação Complementar não informado.";
                $this->erro_campo = "o157_infocomplementarlancamento";
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

        if ($o157_sequencial !== '' && $o157_sequencial !== null && $o157_sequencial !== 0) {
            $sql .= ' WHERE';
            $sql .= " o157_sequencial = {$o157_sequencial}";
        }

        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {
            $resaco = $this->sql_record($this->sql_query_file($this->o157_sequencial));
            if ($this->numrows > 0) {
                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    db_query("insert into db_acountkey values($acount,1010354,'$this->o157_sequencial','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o157_relatorio"]) || $this->o157_relatorio != "") {
                        db_query("insert into db_acount values($acount,1010424,1010357,'" . AddSlashes(pg_result(
                            $resaco,
                            $conresaco,
                            'o157_relatorio'
                        )) . "','$this->o157_relatorio'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o157_conplanoinfocomplementar"]) || $this->o157_conplanoinfocomplementar != "") {
                        db_query("insert into db_acount values($acount,1010424,1010356,'" . AddSlashes(pg_result(
                            $resaco,
                            $conresaco,
                            'o157_conplanoinfocomplementar'
                        )) . "','$this->o157_conplanoinfocomplementar'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o157_valor"]) || $this->o157_valor != "") {
                        db_query("insert into db_acount values($acount,1010424,1010355,'" . AddSlashes(pg_result(
                            $resaco,
                            $conresaco,
                            'o157_valor'
                        )) . "','$this->o157_valor'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o157_sequencial"]) || $this->o157_sequencial != "") {
                        db_query("insert into db_acount values($acount,1010424,1010354,'" . AddSlashes(pg_result(
                            $resaco,
                            $conresaco,
                            'o157_sequencial'
                        )) . "','$this->o157_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o157_linha"]) || $this->o157_linha != "") {
                        db_query("insert into db_acount values($acount,1010424,1010364,'" . AddSlashes(pg_result(
                            $resaco,
                            $conresaco,
                            'o157_linha'
                        )) . "','$this->o157_linha'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o157_padrao"]) || $this->o157_padrao != "") {
                        db_query("insert into db_acount values($acount,1010424,1010407,'" . AddSlashes(pg_result(
                            $resaco,
                            $conresaco,
                            'o157_padrao'
                        )) . "','$this->o157_padrao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o157_infocomplementarlancamento"]) || $this->o157_infocomplementarlancamento != "") {
                        db_query("insert into db_acount values($acount,1010424,1010415,'" . AddSlashes(pg_result(
                            $resaco,
                            $conresaco,
                            'o157_infocomplementarlancamento'
                        )) . "','$this->o157_infocomplementarlancamento'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Contas da Linha/Coluna não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->o157_sequencial;
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
                $this->erro_sql = "Contas da Linha/Coluna não foi Alterado. Alteração Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->o157_sequencial;
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
                $this->erro_sql .= "Valores : " . $this->o157_sequencial;
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

    public function excluir($o157_sequencial = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {
            if (empty($dbwhere)) {
                $resaco = $this->sql_record($this->sql_query_file($o157_sequencial));
            } else {
                $resaco = $this->sql_record($this->sql_query(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {
                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    db_query("insert into db_acountkey values($acount,1010354,'$o157_sequencial','E')");
                    db_query("insert into db_acount values($acount,1010424,1010357,'','" . AddSlashes(pg_result(
                        $resaco,
                        $iresaco,
                        'o157_relatorio'
                    )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    db_query("insert into db_acount values($acount,1010424,1010356,'','" . AddSlashes(pg_result(
                        $resaco,
                        $iresaco,
                        'o157_conplanoinfocomplementar'
                    )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    db_query("insert into db_acount values($acount,1010424,1010355,'','" . AddSlashes(pg_result(
                        $resaco,
                        $iresaco,
                        'o157_valor'
                    )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    db_query("insert into db_acount values($acount,1010424,1010354,'','" . AddSlashes(pg_result(
                        $resaco,
                        $iresaco,
                        'o157_sequencial'
                    )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    db_query("insert into db_acount values($acount,1010424,1010364,'','" . AddSlashes(pg_result(
                        $resaco,
                        $iresaco,
                        'o157_linha'
                    )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    db_query("insert into db_acount values($acount,1010424,1010407,'','" . AddSlashes(pg_result(
                        $resaco,
                        $iresaco,
                        'o157_padrao'
                    )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    db_query("insert into db_acount values($acount,1010424,1010415,'','" . AddSlashes(pg_result(
                        $resaco,
                        $iresaco,
                        'o157_infocomplementarlancamento'
                    )) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " DELETE FROM orcparamseqinfocomplementar USING orcparamseqinfocomplementarlancamento
                    WHERE o102_sequencial = o157_infocomplementarlancamento AND ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($o157_sequencial)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " o157_sequencial = $o157_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Contas da Linha/Coluna não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $o157_sequencial;
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
                $this->erro_sql = "Contas da Linha/Coluna não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $o157_sequencial;
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
                $this->erro_sql .= "Valores : " . $o157_sequencial;
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
            $this->erro_sql = "Record Vazio na Tabela:orcparamseqinfocomplementar";
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

    public function sql_query($o157_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select {$campos}";
        $sql .= "  from orcparamseqinfocomplementar ";
        $sql .= "      inner join orcparamseq  on  orcparamseq.o69_codparamrel = orcparamseqinfocomplementar.o157_relatorio and  orcparamseq.o69_codseq = orcparamseqinfocomplementar.o157_linha";
        $sql .= "      inner join conplanoinfocomplementar  on  conplanoinfocomplementar.c121_sequencial = orcparamseqinfocomplementar.o157_conplanoinfocomplementar";
        $sql .= "      inner join orcparamseqinfocomplementarlancamento  on  orcparamseqinfocomplementarlancamento.o102_sequencial = orcparamseqinfocomplementar.o157_infocomplementarlancamento";
        $sql .= "      inner join orcparamrel  on  orcparamrel.o42_codparrel = orcparamseq.o69_codparamrel";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($o157_sequencial)) {
                $sql2 .= " where orcparamseqinfocomplementar.o157_sequencial = $o157_sequencial ";
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

    public function sql_query_file($o157_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select {$campos} ";
        $sql .= "  from orcparamseqinfocomplementar ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($o157_sequencial)) {
                $sql2 .= " where orcparamseqinfocomplementar.o157_sequencial = $o157_sequencial ";
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
