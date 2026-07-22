<?php

class cl_complementofonterecurso
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
    public $o200_sequencial = 0;
    public $o200_descricao = null;
    public $o200_msc = 'f';
    public $o200_tribunal = 'f';
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 o200_sequencial = int4 = Código
                 o200_descricao = varchar(100) = Complemento
                 o200_msc = bool = MSC
                 o200_tribunal = bool = Tribunal de Contas
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("complementofonterecurso");
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
            $this->o200_sequencial = ($this->o200_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["o200_sequencial"] : $this->o200_sequencial);
            $this->o200_descricao = ($this->o200_descricao == "" ? @$GLOBALS["HTTP_POST_VARS"]["o200_descricao"] : $this->o200_descricao);
            $this->o200_msc = ($this->o200_msc == "" ? @$GLOBALS["HTTP_POST_VARS"]["o200_msc"] : $this->o200_msc);
            $this->o200_tribunal = ($this->o200_tribunal == "" ? @$GLOBALS["HTTP_POST_VARS"]["o200_tribunal"] : $this->o200_tribunal);
        } else {
            $this->o200_sequencial = ($this->o200_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["o200_sequencial"] : $this->o200_sequencial);
        }
    }

    public function incluir($o200_sequencial)
    {
        $this->atualizacampos();
        if ($this->o200_descricao == null) {
            $this->erro_sql = " Campo Complemento não informado.";
            $this->erro_campo = "o200_descricao";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->o200_msc == null) {
            $this->erro_sql = " Campo MSC não informado.";
            $this->erro_campo = "o200_msc";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->o200_tribunal == null) {
            $this->erro_sql = " Campo Tribunal de Contas não informado.";
            $this->erro_campo = "o200_tribunal";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if (($this->o200_sequencial == null) || ($this->o200_sequencial == "")) {
            $this->erro_sql = " Campo o200_sequencial não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into complementofonterecurso(
                                       o200_sequencial
                                      ,o200_descricao
                                      ,o200_msc
                                      ,o200_tribunal
                       )
                values (
                                $this->o200_sequencial
                               ,'$this->o200_descricao'
                               ,'$this->o200_msc'
                               ,'$this->o200_tribunal'
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = "complementofonterecurso ($this->o200_sequencial) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "complementofonterecurso já Cadastrado";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql = "complementofonterecurso ($this->o200_sequencial) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
        $this->erro_sql .= "Valores : " . $this->o200_sequencial;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->o200_sequencial));
            if (($resaco != false) || ($this->numrows != 0)) {

                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,1011274,'$this->o200_sequencial','I')");
                $resac = db_query("insert into db_acount values($acount,1010561,1011274,'','" . AddSlashes(pg_result($resaco, 0, 'o200_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010561,1011275,'','" . AddSlashes(pg_result($resaco, 0, 'o200_descricao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010561,1011277,'','" . AddSlashes(pg_result($resaco, 0, 'o200_msc')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010561,1011867,'','" . AddSlashes(pg_result($resaco, 0, 'o200_tribunal')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
    }

    public function alterar($o200_sequencial = null)
    {
        $this->atualizacampos();
        $sql = " update complementofonterecurso set ";
        $virgula = "";
        if (trim($this->o200_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o200_sequencial"])) {
            $sql .= $virgula . " o200_sequencial = $this->o200_sequencial ";
            $virgula = ",";
            if (trim($this->o200_sequencial) == null) {
                $this->erro_sql = " Campo Código não informado.";
                $this->erro_campo = "o200_sequencial";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->o200_descricao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o200_descricao"])) {
            $sql .= $virgula . " o200_descricao = '$this->o200_descricao' ";
            $virgula = ",";
            if (trim($this->o200_descricao) == null) {
                $this->erro_sql = " Campo Complemento não informado.";
                $this->erro_campo = "o200_descricao";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->o200_msc) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o200_msc"])) {
            $sql .= $virgula . " o200_msc = '$this->o200_msc' ";
            $virgula = ",";
            if (trim($this->o200_msc) == null) {
                $this->erro_sql = " Campo MSC não informado.";
                $this->erro_campo = "o200_msc";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->o200_tribunal) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o200_tribunal"])) {
            $sql .= $virgula . " o200_tribunal = '$this->o200_tribunal' ";
            $virgula = ",";
            if (trim($this->o200_tribunal) == null) {
                $this->erro_sql = " Campo Tribunal de Contas não informado.";
                $this->erro_campo = "o200_tribunal";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if ($o200_sequencial != null) {
            $sql .= " o200_sequencial = $this->o200_sequencial";
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->o200_sequencial));
            if ($this->numrows > 0) {

                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1011274,'$this->o200_sequencial','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o200_sequencial"]) || $this->o200_sequencial != "")
                        $resac = db_query("insert into db_acount values($acount,1010561,1011274,'" . AddSlashes(pg_result($resaco, $conresaco, 'o200_sequencial')) . "','$this->o200_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o200_descricao"]) || $this->o200_descricao != "")
                        $resac = db_query("insert into db_acount values($acount,1010561,1011275,'" . AddSlashes(pg_result($resaco, $conresaco, 'o200_descricao')) . "','$this->o200_descricao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o200_msc"]) || $this->o200_msc != "")
                        $resac = db_query("insert into db_acount values($acount,1010561,1011277,'" . AddSlashes(pg_result($resaco, $conresaco, 'o200_msc')) . "','$this->o200_msc'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o200_tribunal"]) || $this->o200_tribunal != "")
                        $resac = db_query("insert into db_acount values($acount,1010561,1011867,'" . AddSlashes(pg_result($resaco, $conresaco, 'o200_tribunal')) . "','$this->o200_tribunal'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "complementofonterecurso não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->o200_sequencial;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "complementofonterecurso não foi Alterado. Alteração Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->o200_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $this->o200_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($o200_sequencial = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            if (empty($dbwhere)) {

                $resaco = $this->sql_record($this->sql_query_file($o200_sequencial));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1011274,'$o200_sequencial','E')");
                    $resac = db_query("insert into db_acount values($acount,1010561,1011274,'','" . AddSlashes(pg_result($resaco, $iresaco, 'o200_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010561,1011275,'','" . AddSlashes(pg_result($resaco, $iresaco, 'o200_descricao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010561,1011277,'','" . AddSlashes(pg_result($resaco, $iresaco, 'o200_msc')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010561,1011867,'','" . AddSlashes(pg_result($resaco, $iresaco, 'o200_tribunal')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " delete from complementofonterecurso
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($o200_sequencial)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " o200_sequencial = $o200_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "complementofonterecurso não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $o200_sequencial;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "complementofonterecurso não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $o200_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $o200_sequencial;
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
            $this->erro_sql = "Record Vazio na Tabela:complementofonterecurso";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query($o200_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select {$campos}";
        $sql .= "  from complementofonterecurso ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($o200_sequencial)) {
                $sql2 .= " where complementofonterecurso.o200_sequencial = $o200_sequencial ";
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

    public function sql_query_file($o200_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select {$campos} ";
        $sql .= "  from complementofonterecurso ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($o200_sequencial)) {
                $sql2 .= " where complementofonterecurso.o200_sequencial = $o200_sequencial ";
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
