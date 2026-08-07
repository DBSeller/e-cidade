<?php

class cl_avaliacaogruporespostaefdr2020
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
    public $efd05_sequencial = 0;
    public $efd05_cgm = 0;
    public $efd05_inscricaoprestadora = null;
    public $efd05_competencia = null;
    public $efd05_avaliacaogruporesposta = 0;
    public $efd05_avaliacao = 0;
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 efd05_sequencial = int4 = Código 
                 efd05_cgm = oid = CGM 
                 efd05_inscricaoprestadora = varchar(14) = CNPJ 
                 efd05_competencia = varchar(7) = Competência 
                 efd05_avaliacaogruporesposta = int4 = Preenchimento 
                 efd05_avaliacao = int4 = Avaliação 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("avaliacaogruporespostaefdr2020");
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

    public function incluir($efd05_sequencial)
    {
        $this->atualizacampos();
        if ($this->efd05_cgm == null) {
            $this->erro_sql = " Campo CGM não informado.";
            $this->erro_campo = "efd05_cgm";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->efd05_inscricaoprestadora == null) {
            $this->erro_sql = " Campo CNPJ não informado.";
            $this->erro_campo = "efd05_inscricaoprestadora";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->efd05_competencia == null) {
            $this->erro_sql = " Campo Competência não informado.";
            $this->erro_campo = "efd05_competencia";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->efd05_avaliacaogruporesposta == null) {
            $this->erro_sql = " Campo Preenchimento não informado.";
            $this->erro_campo = "efd05_avaliacaogruporesposta";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->efd05_avaliacao == null) {
            $this->erro_sql = " Campo Avaliação não informado.";
            $this->erro_campo = "efd05_avaliacao";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($efd05_sequencial == "" || $efd05_sequencial == null) {
            $result = db_query("select nextval('avaliacaogruporespostaefdr2020_efd05_sequencial_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: avaliacaogruporespostaefdr2020_efd05_sequencial_seq do campo: efd05_sequencial";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->efd05_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from avaliacaogruporespostaefdr2020_efd05_sequencial_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $efd05_sequencial)) {
                $this->erro_sql = " Campo efd05_sequencial maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->efd05_sequencial = $efd05_sequencial;
            }
        }
        if (($this->efd05_sequencial == null) || ($this->efd05_sequencial == "")) {
            $this->erro_sql = " Campo efd05_sequencial não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into avaliacaogruporespostaefdr2020(
                                       efd05_sequencial 
                                      ,efd05_cgm 
                                      ,efd05_inscricaoprestadora 
                                      ,efd05_competencia 
                                      ,efd05_avaliacaogruporesposta 
                                      ,efd05_avaliacao 
                       )
                values (
                                $this->efd05_sequencial 
                               ,$this->efd05_cgm 
                               ,'$this->efd05_inscricaoprestadora' 
                               ,'$this->efd05_competencia' 
                               ,$this->efd05_avaliacaogruporesposta 
                               ,$this->efd05_avaliacao 
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = "Registros do evento R-2020 ($this->efd05_sequencial) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "Registros do evento R-2020 já Cadastrado";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql = "Registros do evento R-2020 ($this->efd05_sequencial) não Incluído. Inclusão Abortada.";
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
        $this->erro_sql .= "Valores : " . $this->efd05_sequencial;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "",
            str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->efd05_sequencial));
            if (($resaco != false) || ($this->numrows != 0)) {

                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,1010246,'$this->efd05_sequencial','I')");
                $resac = db_query("insert into db_acount values($acount,1010397,1010246,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'efd05_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010397,1010247,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'efd05_cgm')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010397,1010248,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'efd05_inscricaoprestadora')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010397,1010249,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'efd05_competencia')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010397,1010250,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'efd05_avaliacaogruporesposta')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010397,1010251,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'efd05_avaliacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
    }

    public function atualizacampos($exclusao = false)
    {
        if ($exclusao == false) {
            $this->efd05_sequencial = ($this->efd05_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["efd05_sequencial"] : $this->efd05_sequencial);
            $this->efd05_cgm = ($this->efd05_cgm == "" ? @$GLOBALS["HTTP_POST_VARS"]["efd05_cgm"] : $this->efd05_cgm);
            $this->efd05_inscricaoprestadora = ($this->efd05_inscricaoprestadora == "" ? @$GLOBALS["HTTP_POST_VARS"]["efd05_inscricaoprestadora"] : $this->efd05_inscricaoprestadora);
            $this->efd05_competencia = ($this->efd05_competencia == "" ? @$GLOBALS["HTTP_POST_VARS"]["efd05_competencia"] : $this->efd05_competencia);
            $this->efd05_avaliacaogruporesposta = ($this->efd05_avaliacaogruporesposta == "" ? @$GLOBALS["HTTP_POST_VARS"]["efd05_avaliacaogruporesposta"] : $this->efd05_avaliacaogruporesposta);
            $this->efd05_avaliacao = ($this->efd05_avaliacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["efd05_avaliacao"] : $this->efd05_avaliacao);
        } else {
            $this->efd05_sequencial = ($this->efd05_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["efd05_sequencial"] : $this->efd05_sequencial);
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
            $this->erro_sql = "Record Vazio na Tabela:avaliacaogruporespostaefdr2020";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query_file($efd05_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos} ";
        $sql .= "  from avaliacaogruporespostaefdr2020 ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($efd05_sequencial)) {
                $sql2 .= " where avaliacaogruporespostaefdr2020.efd05_sequencial = $efd05_sequencial ";
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

    public function alterar($efd05_sequencial = null)
    {
        $this->atualizacampos();
        $sql = " update avaliacaogruporespostaefdr2020 set ";
        $virgula = "";
        if (trim($this->efd05_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["efd05_sequencial"])) {
            $sql .= $virgula . " efd05_sequencial = $this->efd05_sequencial ";
            $virgula = ",";
            if (trim($this->efd05_sequencial) == null) {
                $this->erro_sql = " Campo Código não informado.";
                $this->erro_campo = "efd05_sequencial";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->efd05_cgm) != "" || isset($GLOBALS["HTTP_POST_VARS"]["efd05_cgm"])) {
            $sql .= $virgula . " efd05_cgm = $this->efd05_cgm ";
            $virgula = ",";
            if (trim($this->efd05_cgm) == null) {
                $this->erro_sql = " Campo CGM não informado.";
                $this->erro_campo = "efd05_cgm";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->efd05_inscricaoprestadora) != "" || isset($GLOBALS["HTTP_POST_VARS"]["efd05_inscricaoprestadora"])) {
            $sql .= $virgula . " efd05_inscricaoprestadora = '$this->efd05_inscricaoprestadora' ";
            $virgula = ",";
            if (trim($this->efd05_inscricaoprestadora) == null) {
                $this->erro_sql = " Campo CNPJ não informado.";
                $this->erro_campo = "efd05_inscricaoprestadora";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->efd05_competencia) != "" || isset($GLOBALS["HTTP_POST_VARS"]["efd05_competencia"])) {
            $sql .= $virgula . " efd05_competencia = '$this->efd05_competencia' ";
            $virgula = ",";
            if (trim($this->efd05_competencia) == null) {
                $this->erro_sql = " Campo Competência não informado.";
                $this->erro_campo = "efd05_competencia";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->efd05_avaliacaogruporesposta) != "" || isset($GLOBALS["HTTP_POST_VARS"]["efd05_avaliacaogruporesposta"])) {
            $sql .= $virgula . " efd05_avaliacaogruporesposta = $this->efd05_avaliacaogruporesposta ";
            $virgula = ",";
            if (trim($this->efd05_avaliacaogruporesposta) == null) {
                $this->erro_sql = " Campo Preenchimento não informado.";
                $this->erro_campo = "efd05_avaliacaogruporesposta";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->efd05_avaliacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["efd05_avaliacao"])) {
            $sql .= $virgula . " efd05_avaliacao = $this->efd05_avaliacao ";
            $virgula = ",";
            if (trim($this->efd05_avaliacao) == null) {
                $this->erro_sql = " Campo Avaliação não informado.";
                $this->erro_campo = "efd05_avaliacao";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if ($efd05_sequencial != null) {
            $sql .= " efd05_sequencial = $this->efd05_sequencial";
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->efd05_sequencial));
            if ($this->numrows > 0) {

                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1010246,'$this->efd05_sequencial','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["efd05_sequencial"]) || $this->efd05_sequencial != "") {
                        $resac = db_query("insert into db_acount values($acount,1010397,1010246,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'efd05_sequencial')) . "','$this->efd05_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["efd05_cgm"]) || $this->efd05_cgm != "") {
                        $resac = db_query("insert into db_acount values($acount,1010397,1010247,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'efd05_cgm')) . "','$this->efd05_cgm'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["efd05_inscricaoprestadora"]) || $this->efd05_inscricaoprestadora != "") {
                        $resac = db_query("insert into db_acount values($acount,1010397,1010248,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'efd05_inscricaoprestadora')) . "','$this->efd05_inscricaoprestadora'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["efd05_competencia"]) || $this->efd05_competencia != "") {
                        $resac = db_query("insert into db_acount values($acount,1010397,1010249,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'efd05_competencia')) . "','$this->efd05_competencia'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["efd05_avaliacaogruporesposta"]) || $this->efd05_avaliacaogruporesposta != "") {
                        $resac = db_query("insert into db_acount values($acount,1010397,1010250,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'efd05_avaliacaogruporesposta')) . "','$this->efd05_avaliacaogruporesposta'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["efd05_avaliacao"]) || $this->efd05_avaliacao != "") {
                        $resac = db_query("insert into db_acount values($acount,1010397,1010251,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'efd05_avaliacao')) . "','$this->efd05_avaliacao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Registros do evento R-2020 não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->efd05_sequencial;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Registros do evento R-2020 não foi Alterado. Alteração Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->efd05_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $this->efd05_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($efd05_sequencial = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            if (empty($dbwhere)) {

                $resaco = $this->sql_record($this->sql_query_file($efd05_sequencial));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1010246,'$efd05_sequencial','E')");
                    $resac = db_query("insert into db_acount values($acount,1010397,1010246,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'efd05_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010397,1010247,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'efd05_cgm')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010397,1010248,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'efd05_inscricaoprestadora')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010397,1010249,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'efd05_competencia')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010397,1010250,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'efd05_avaliacaogruporesposta')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010397,1010251,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'efd05_avaliacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " delete from avaliacaogruporespostaefdr2020
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($efd05_sequencial)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " efd05_sequencial = $efd05_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Registros do evento R-2020 não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $efd05_sequencial;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Registros do evento R-2020 não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $efd05_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $efd05_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function sql_query($efd05_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos}";
        $sql .= "  from avaliacaogruporespostaefdr2020 ";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = avaliacaogruporespostaefdr2020.efd05_cgm";
        $sql .= "      inner join avaliacao  on  avaliacao.db101_sequencial = avaliacaogruporespostaefdr2020.efd05_avaliacao";
        $sql .= "      inner join avaliacaogruporesposta  on  avaliacaogruporesposta.db107_sequencial = avaliacaogruporespostaefdr2020.efd05_avaliacaogruporesposta";
        $sql .= "      inner join avaliacaotipo  on  avaliacaotipo.db100_sequencial = avaliacao.db101_avaliacaotipo";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($efd05_sequencial)) {
                $sql2 .= " where avaliacaogruporespostaefdr2020.efd05_sequencial = $efd05_sequencial ";
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
