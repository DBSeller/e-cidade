<?php

class cl_avaliacaogruporespostaesocials1299
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
    public $eso33_sequencial = 0;
    public $eso33_empregador = 0;
    public $eso33_avaliacaogruporesposta = 0;
    public $eso33_indicativoapuracao = 0;
    public $eso33_periodo = null;
    public $eso33_avaliacao = 0;
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 eso33_sequencial = int4 = Código 
                 eso33_empregador = int4 = Empregador 
                 eso33_avaliacaogruporesposta = int4 = Preenchimento 
                 eso33_indicativoapuracao = int4 = Inficativo de Apuração 
                 eso33_periodo = varchar(7) = Período 
                 eso33_avaliacao = int4 = Avaliação 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("avaliacaogruporespostaesocials1299");
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
            $this->eso33_sequencial = ($this->eso33_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["eso33_sequencial"] : $this->eso33_sequencial);
            $this->eso33_empregador = ($this->eso33_empregador == "" ? @$GLOBALS["HTTP_POST_VARS"]["eso33_empregador"] : $this->eso33_empregador);
            $this->eso33_avaliacaogruporesposta = ($this->eso33_avaliacaogruporesposta == "" ? @$GLOBALS["HTTP_POST_VARS"]["eso33_avaliacaogruporesposta"] : $this->eso33_avaliacaogruporesposta);
            $this->eso33_indicativoapuracao = ($this->eso33_indicativoapuracao == "" ? @$GLOBALS["HTTP_POST_VARS"]["eso33_indicativoapuracao"] : $this->eso33_indicativoapuracao);
            $this->eso33_periodo = ($this->eso33_periodo == "" ? @$GLOBALS["HTTP_POST_VARS"]["eso33_periodo"] : $this->eso33_periodo);
            $this->eso33_avaliacao = ($this->eso33_avaliacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["eso33_avaliacao"] : $this->eso33_avaliacao);
        } else {
            $this->eso33_sequencial = ($this->eso33_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["eso33_sequencial"] : $this->eso33_sequencial);
        }
    }

    public function incluir($eso33_sequencial)
    {
        $this->atualizacampos();
        if ($this->eso33_empregador == null) {
            $this->erro_sql = " Campo Empregador não informado.";
            $this->erro_campo = "eso33_empregador";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->eso33_avaliacaogruporesposta == null) {
            $this->erro_sql = " Campo Preenchimento não informado.";
            $this->erro_campo = "eso33_avaliacaogruporesposta";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->eso33_indicativoapuracao == null) {
            $this->erro_sql = " Campo Inficativo de Apuração não informado.";
            $this->erro_campo = "eso33_indicativoapuracao";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->eso33_periodo == null) {
            $this->erro_sql = " Campo Período não informado.";
            $this->erro_campo = "eso33_periodo";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->eso33_avaliacao == null) {
            $this->erro_sql = " Campo Avaliação não informado.";
            $this->erro_campo = "eso33_avaliacao";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($eso33_sequencial == "" || $eso33_sequencial == null) {
            $result = db_query("select nextval('avaliacaogruporespostaesocials1299_eso33_sequencial_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: avaliacaogruporespostaesocials1299_eso33_sequencial_seq do campo: eso33_sequencial";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->eso33_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from avaliacaogruporespostaesocials1299_eso33_sequencial_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $eso33_sequencial)) {
                $this->erro_sql = " Campo eso33_sequencial maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->eso33_sequencial = $eso33_sequencial;
            }
        }
        if (($this->eso33_sequencial == null) || ($this->eso33_sequencial == "")) {
            $this->erro_sql = " Campo eso33_sequencial não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into avaliacaogruporespostaesocials1299(
                                       eso33_sequencial 
                                      ,eso33_empregador 
                                      ,eso33_avaliacaogruporesposta 
                                      ,eso33_indicativoapuracao 
                                      ,eso33_periodo 
                                      ,eso33_avaliacao 
                       )
                values (
                                $this->eso33_sequencial 
                               ,$this->eso33_empregador 
                               ,$this->eso33_avaliacaogruporesposta 
                               ,$this->eso33_indicativoapuracao 
                               ,'$this->eso33_periodo' 
                               ,$this->eso33_avaliacao 
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = " ($this->eso33_sequencial) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = " já Cadastrado";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql = " ($this->eso33_sequencial) não Incluído. Inclusão Abortada.";
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
        $this->erro_sql .= "Valores : " . $this->eso33_sequencial;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "",
            str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->eso33_sequencial));
            if (($resaco != false) || ($this->numrows != 0)) {

                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,1010295,'$this->eso33_sequencial','I')");
                $resac = db_query("insert into db_acount values($acount,1010407,1010295,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'eso33_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010407,1010296,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'eso33_empregador')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010407,1010299,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'eso33_avaliacaogruporesposta')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010407,1010297,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'eso33_indicativoapuracao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010407,1010300,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'eso33_periodo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010407,1010301,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'eso33_avaliacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
    }

    public function alterar($eso33_sequencial = null)
    {
        $this->atualizacampos();
        $sql = " update avaliacaogruporespostaesocials1299 set ";
        $virgula = "";
        if (trim($this->eso33_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["eso33_sequencial"])) {
            $sql .= $virgula . " eso33_sequencial = $this->eso33_sequencial ";
            $virgula = ",";
            if (trim($this->eso33_sequencial) == null) {
                $this->erro_sql = " Campo Código não informado.";
                $this->erro_campo = "eso33_sequencial";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->eso33_empregador) != "" || isset($GLOBALS["HTTP_POST_VARS"]["eso33_empregador"])) {
            $sql .= $virgula . " eso33_empregador = $this->eso33_empregador ";
            $virgula = ",";
            if (trim($this->eso33_empregador) == null) {
                $this->erro_sql = " Campo Empregador não informado.";
                $this->erro_campo = "eso33_empregador";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->eso33_avaliacaogruporesposta) != "" || isset($GLOBALS["HTTP_POST_VARS"]["eso33_avaliacaogruporesposta"])) {
            $sql .= $virgula . " eso33_avaliacaogruporesposta = $this->eso33_avaliacaogruporesposta ";
            $virgula = ",";
            if (trim($this->eso33_avaliacaogruporesposta) == null) {
                $this->erro_sql = " Campo Preenchimento não informado.";
                $this->erro_campo = "eso33_avaliacaogruporesposta";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->eso33_indicativoapuracao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["eso33_indicativoapuracao"])) {
            $sql .= $virgula . " eso33_indicativoapuracao = $this->eso33_indicativoapuracao ";
            $virgula = ",";
            if (trim($this->eso33_indicativoapuracao) == null) {
                $this->erro_sql = " Campo Inficativo de Apuração não informado.";
                $this->erro_campo = "eso33_indicativoapuracao";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->eso33_periodo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["eso33_periodo"])) {
            $sql .= $virgula . " eso33_periodo = '$this->eso33_periodo' ";
            $virgula = ",";
            if (trim($this->eso33_periodo) == null) {
                $this->erro_sql = " Campo Período não informado.";
                $this->erro_campo = "eso33_periodo";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->eso33_avaliacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["eso33_avaliacao"])) {
            $sql .= $virgula . " eso33_avaliacao = $this->eso33_avaliacao ";
            $virgula = ",";
            if (trim($this->eso33_avaliacao) == null) {
                $this->erro_sql = " Campo Avaliação não informado.";
                $this->erro_campo = "eso33_avaliacao";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if ($eso33_sequencial != null) {
            $sql .= " eso33_sequencial = $this->eso33_sequencial";
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->eso33_sequencial));
            if ($this->numrows > 0) {

                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1010295,'$this->eso33_sequencial','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["eso33_sequencial"]) || $this->eso33_sequencial != "") {
                        $resac = db_query("insert into db_acount values($acount,1010407,1010295,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'eso33_sequencial')) . "','$this->eso33_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["eso33_empregador"]) || $this->eso33_empregador != "") {
                        $resac = db_query("insert into db_acount values($acount,1010407,1010296,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'eso33_empregador')) . "','$this->eso33_empregador'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["eso33_avaliacaogruporesposta"]) || $this->eso33_avaliacaogruporesposta != "") {
                        $resac = db_query("insert into db_acount values($acount,1010407,1010299,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'eso33_avaliacaogruporesposta')) . "','$this->eso33_avaliacaogruporesposta'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["eso33_indicativoapuracao"]) || $this->eso33_indicativoapuracao != "") {
                        $resac = db_query("insert into db_acount values($acount,1010407,1010297,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'eso33_indicativoapuracao')) . "','$this->eso33_indicativoapuracao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["eso33_periodo"]) || $this->eso33_periodo != "") {
                        $resac = db_query("insert into db_acount values($acount,1010407,1010300,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'eso33_periodo')) . "','$this->eso33_periodo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["eso33_avaliacao"]) || $this->eso33_avaliacao != "") {
                        $resac = db_query("insert into db_acount values($acount,1010407,1010301,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'eso33_avaliacao')) . "','$this->eso33_avaliacao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = " não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->eso33_sequencial;
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
                $this->erro_sql .= "Valores : " . $this->eso33_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $this->eso33_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($eso33_sequencial = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            if (empty($dbwhere)) {

                $resaco = $this->sql_record($this->sql_query_file($eso33_sequencial));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1010295,'$eso33_sequencial','E')");
                    $resac = db_query("insert into db_acount values($acount,1010407,1010295,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'eso33_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010407,1010296,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'eso33_empregador')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010407,1010299,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'eso33_avaliacaogruporesposta')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010407,1010297,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'eso33_indicativoapuracao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010407,1010300,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'eso33_periodo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010407,1010301,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'eso33_avaliacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " delete from avaliacaogruporespostaesocials1299
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($eso33_sequencial)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " eso33_sequencial = $eso33_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = " não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $eso33_sequencial;
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
                $this->erro_sql .= "Valores : " . $eso33_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $eso33_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
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
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $this->numrows = pg_num_rows($result);
        if ($this->numrows == 0) {
            $this->erro_banco = "";
            $this->erro_sql = "Record Vazio na Tabela:avaliacaogruporespostaesocials1299";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query($eso33_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos}";
        $sql .= "  from avaliacaogruporespostaesocials1299 ";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = avaliacaogruporespostaesocials1299.eso33_empregador";
        $sql .= "      inner join avaliacao  on  avaliacao.db101_sequencial = avaliacaogruporespostaesocials1299.eso33_avaliacao";
        $sql .= "      inner join avaliacaogruporesposta  on  avaliacaogruporesposta.db107_sequencial = avaliacaogruporespostaesocials1299.eso33_avaliacaogruporesposta";
        $sql .= "      inner join avaliacaotipo  on  avaliacaotipo.db100_sequencial = avaliacao.db101_avaliacaotipo";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($eso33_sequencial)) {
                $sql2 .= " where avaliacaogruporespostaesocials1299.eso33_sequencial = $eso33_sequencial ";
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

    public function sql_query_file($eso33_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos} ";
        $sql .= "  from avaliacaogruporespostaesocials1299 ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($eso33_sequencial)) {
                $sql2 .= " where avaliacaogruporespostaesocials1299.eso33_sequencial = $eso33_sequencial ";
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

    public function preenchimentos($campos = array(), $ordem = array(), $where = array())
    {
        $campos = empty($campos) ? " * " : implode(', ', $campos);
        $sql = " 
            select {$campos}
              from avaliacaogruporespostaesocials1299
              join cgm on cgm.z01_numcgm = avaliacaogruporespostaesocials1299.eso33_empregador
        ";

        if (!empty($where)) {
            $sql .= " where " . implode(' and ', $where);
        }

        if (!empty($ordem)) {
            $sql .= " order by " . implode(', ', $ordem);
        }

        return $sql;
    }

}
