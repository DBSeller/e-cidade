<?php

class cl_avaliacaogruporespostaexclusaoeventosefd
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
    public $eso29_sequencial = 0;
    public $eso29_avaliacaogruporesposta = 0;
    public $eso29_cgm = 0;
    public $eso29_protocolo = null;
    public $eso29_periodo = null;
    public $eso29_data = null;
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 eso29_sequencial = int4 = Sequencial 
                 eso29_avaliacaogruporesposta = int4 = Código do Grupo de Resposta 
                 eso29_cgm = int4 = Cgm do Contribuinte 
                 eso29_protocolo = varchar(255) = Protocolo 
                 eso29_periodo = varchar(10) = Período 
                 eso29_data = varchar(100) = data 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("avaliacaogruporespostaexclusaoeventosefd"); 
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
            $this->eso29_sequencial = ($this->eso29_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["eso29_sequencial"] : $this->eso29_sequencial);
            $this->eso29_avaliacaogruporesposta = ($this->eso29_avaliacaogruporesposta == "" ? @$GLOBALS["HTTP_POST_VARS"]["eso29_avaliacaogruporesposta"] : $this->eso29_avaliacaogruporesposta);
            $this->eso29_cgm = ($this->eso29_cgm == "" ? @$GLOBALS["HTTP_POST_VARS"]["eso29_cgm"] : $this->eso29_cgm);
            $this->eso29_protocolo = ($this->eso29_protocolo == "" ? @$GLOBALS["HTTP_POST_VARS"]["eso29_protocolo"] : $this->eso29_protocolo);
            $this->eso29_periodo = ($this->eso29_periodo == "" ? @$GLOBALS["HTTP_POST_VARS"]["eso29_periodo"] : $this->eso29_periodo);
            $this->eso29_data = ($this->eso29_data == "" ? @$GLOBALS["HTTP_POST_VARS"]["eso29_data"] : $this->eso29_data);
        } else {
            $this->eso29_sequencial = ($this->eso29_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["eso29_sequencial"] : $this->eso29_sequencial);
        }
    }

    public function incluir($eso29_sequencial)
    {
        $this->atualizacampos();
        if ($this->eso29_avaliacaogruporesposta == null) {
            $this->erro_sql = " Campo Código do Grupo de Resposta não informado.";
            $this->erro_campo = "eso29_avaliacaogruporesposta";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->eso29_cgm == null) {
            $this->erro_sql = " Campo Cgm do Contribuinte não informado.";
            $this->erro_campo = "eso29_cgm";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->eso29_protocolo == null) {
            $this->erro_sql = " Campo Protocolo não informado.";
            $this->erro_campo = "eso29_protocolo";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->eso29_periodo == null) {
            $this->erro_sql = " Campo Período não informado.";
            $this->erro_campo = "eso29_periodo";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if ($eso29_sequencial == "" || $eso29_sequencial == null) {
            $result = db_query("select nextval('avaliacaogruporespostaexclusaoeventosefd_eso29_sequencial_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: avaliacaogruporespostaexclusaoeventosefd_eso29_sequencial_seq do campo: eso29_sequencial";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->eso29_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from avaliacaogruporespostaexclusaoeventosefd_eso29_sequencial_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $eso29_sequencial)) {
                $this->erro_sql = " Campo eso29_sequencial maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->eso29_sequencial = $eso29_sequencial;
            }
        }
        if (($this->eso29_sequencial == null) || ($this->eso29_sequencial == "")) {
            $this->erro_sql = " Campo eso29_sequencial não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into avaliacaogruporespostaexclusaoeventosefd(
                                       eso29_sequencial 
                                      ,eso29_avaliacaogruporesposta 
                                      ,eso29_cgm 
                                      ,eso29_protocolo 
                                      ,eso29_periodo 
                       )
                values (
                                $this->eso29_sequencial 
                               ,$this->eso29_avaliacaogruporesposta 
                               ,$this->eso29_cgm 
                               ,'$this->eso29_protocolo' 
                               ,'$this->eso29_periodo'
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = "avaliacaogruporespostaexclusaoeventosefd ($this->eso29_sequencial) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "avaliacaogruporespostaexclusaoeventosefd já Cadastrado";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql = "avaliacaogruporespostaexclusaoeventosefd ($this->eso29_sequencial) não Incluído. Inclusão Abortada.";
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
        $this->erro_sql .= "Valores : " . $this->eso29_sequencial;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "",
            str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->eso29_sequencial));
            if (($resaco != false) || ($this->numrows != 0)) {

                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,1010211,'$this->eso29_sequencial','I')");
                $resac = db_query("insert into db_acount values($acount,1010360,1010211,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'eso29_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010360,1010212,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'eso29_avaliacaogruporesposta')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010360,1010213,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'eso29_cgm')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010360,1010214,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'eso29_protocolo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010360,1010215,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'eso29_periodo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010360,1010270,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'eso29_data')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
    }

    public function alterar($eso29_sequencial = null, $where = null)
    {
        $this->atualizacampos();
        $sql = " update avaliacaogruporespostaexclusaoeventosefd set ";
        $virgula = "";
        if (trim($this->eso29_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["eso29_sequencial"])) {
            $sql .= $virgula . " eso29_sequencial = $this->eso29_sequencial ";
            $virgula = ",";
            if (trim($this->eso29_sequencial) == null) {
                $this->erro_sql = " Campo Sequencial não informado.";
                $this->erro_campo = "eso29_sequencial";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->eso29_avaliacaogruporesposta) != "" || isset($GLOBALS["HTTP_POST_VARS"]["eso29_avaliacaogruporesposta"])) {
            $sql .= $virgula . " eso29_avaliacaogruporesposta = $this->eso29_avaliacaogruporesposta ";
            $virgula = ",";
            if (trim($this->eso29_avaliacaogruporesposta) == null) {
                $this->erro_sql = " Campo Código do Grupo de Resposta não informado.";
                $this->erro_campo = "eso29_avaliacaogruporesposta";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->eso29_cgm) != "" || isset($GLOBALS["HTTP_POST_VARS"]["eso29_cgm"])) {
            $sql .= $virgula . " eso29_cgm = $this->eso29_cgm ";
            $virgula = ",";
            if (trim($this->eso29_cgm) == null) {
                $this->erro_sql = " Campo Cgm do Contribuinte não informado.";
                $this->erro_campo = "eso29_cgm";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->eso29_protocolo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["eso29_protocolo"])) {
            $sql .= $virgula . " eso29_protocolo = '$this->eso29_protocolo' ";
            $virgula = ",";
            if (trim($this->eso29_protocolo) == null) {
                $this->erro_sql = " Campo Protocolo não informado.";
                $this->erro_campo = "eso29_protocolo";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->eso29_periodo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["eso29_periodo"])) {
            $sql .= $virgula . " eso29_periodo = '$this->eso29_periodo' ";
            $virgula = ",";
            if (trim($this->eso29_periodo) == null) {
                $this->erro_sql = " Campo Período não informado.";
                $this->erro_campo = "eso29_periodo";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        $sql .= $virgula . " eso29_data = now() ";

        if ($eso29_sequencial != null) {
            $sql .= " where eso29_sequencial = $this->eso29_sequencial ";
        } elseif (!empty($where)) {
            $sql .= " where {$where} ";
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->eso29_sequencial));
            if ($this->numrows > 0) {

                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1010211,'$this->eso29_sequencial','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["eso29_sequencial"]) || $this->eso29_sequencial != "") {
                        $resac = db_query("insert into db_acount values($acount,1010360,1010211,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'eso29_sequencial')) . "','$this->eso29_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["eso29_avaliacaogruporesposta"]) || $this->eso29_avaliacaogruporesposta != "") {
                        $resac = db_query("insert into db_acount values($acount,1010360,1010212,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'eso29_avaliacaogruporesposta')) . "','$this->eso29_avaliacaogruporesposta'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["eso29_cgm"]) || $this->eso29_cgm != "") {
                        $resac = db_query("insert into db_acount values($acount,1010360,1010213,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'eso29_cgm')) . "','$this->eso29_cgm'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["eso29_protocolo"]) || $this->eso29_protocolo != "") {
                        $resac = db_query("insert into db_acount values($acount,1010360,1010214,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'eso29_protocolo')) . "','$this->eso29_protocolo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["eso29_periodo"]) || $this->eso29_periodo != "") {
                        $resac = db_query("insert into db_acount values($acount,1010360,1010215,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'eso29_periodo')) . "','$this->eso29_periodo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["eso29_data"]) || $this->eso29_data != "") {
                        $resac = db_query("insert into db_acount values($acount,1010360,1010270,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'eso29_data')) . "','$this->eso29_data'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "avaliacaogruporespostaexclusaoeventosefd não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->eso29_sequencial;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "avaliacaogruporespostaexclusaoeventosefd não foi Alterado. Alteração Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->eso29_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $this->eso29_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($eso29_sequencial = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            if (empty($dbwhere)) {

                $resaco = $this->sql_record($this->sql_query_file($eso29_sequencial));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1010211,'$eso29_sequencial','E')");
                    $resac = db_query("insert into db_acount values($acount,1010360,1010211,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'eso29_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010360,1010212,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'eso29_avaliacaogruporesposta')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010360,1010213,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'eso29_cgm')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010360,1010214,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'eso29_protocolo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010360,1010215,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'eso29_periodo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010360,1010270,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'eso29_data')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " delete from avaliacaogruporespostaexclusaoeventosefd
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($eso29_sequencial)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " eso29_sequencial = $eso29_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "avaliacaogruporespostaexclusaoeventosefd não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $eso29_sequencial;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "avaliacaogruporespostaexclusaoeventosefd não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $eso29_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $eso29_sequencial;
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
            $this->erro_sql = "Record Vazio na Tabela:avaliacaogruporespostaexclusaoeventosefd";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query($eso29_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos}";
        $sql .= "  from avaliacaogruporespostaexclusaoeventosefd ";
        $sql .= "      inner join avaliacaogruporesposta  on  avaliacaogruporesposta.db107_sequencial = avaliacaogruporespostaexclusaoeventosefd.eso29_avaliacaogruporesposta";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($eso29_sequencial)) {
                $sql2 .= " where avaliacaogruporespostaexclusaoeventosefd.eso29_sequencial = $eso29_sequencial ";
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

    public function sql_query_file($eso29_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos} ";
        $sql .= "  from avaliacaogruporespostaexclusaoeventosefd ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($eso29_sequencial)) {
                $sql2 .= " where avaliacaogruporespostaexclusaoeventosefd.eso29_sequencial = $eso29_sequencial ";
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

    public function buscarRespostasPreenchimento($campos = array('*'), $where = array(), $outrosComandos = "")
    {
        $sql = " SELECT DISTINCT " . implode(', ', $campos);
        $sql .= "   FROM avaliacaogruporespostaexclusaoeventosefd";
        $sql .= "  INNER JOIN avaliacaogruporesposta ON db107_sequencial = eso29_avaliacaogruporesposta ";
        $sql .= "  INNER JOIN avaliacaogrupoperguntaresposta ON db108_avaliacaogruporesposta = db107_sequencial";
        $sql .= "  INNER JOIN avaliacaoresposta ON db106_sequencial = db108_avaliacaoresposta ";
        $sql .= "  INNER JOIN avaliacaoperguntaopcao ON db104_sequencial = db106_avaliacaoperguntaopcao ";
        $sql .= "  INNER JOIN avaliacaopergunta ON db103_sequencial = db104_avaliacaopergunta ";
        $sql .= "  INNER JOIN avaliacaogrupopergunta ON db103_avaliacaogrupopergunta = db102_sequencial ";
        $sql .= "  INNER JOIN avaliacao ON db102_avaliacao = db101_sequencial ";
        $sql .= "  INNER JOIN cgm ON z01_numcgm = eso29_cgm";

        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        if (!empty($outrosComandos)) {
            $sql .= " {$outrosComandos} ";
        }

        return $sql;
    }

}
