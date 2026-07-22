<?php

class cl_regenciahorariodiscsemreg
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
    public $ed175_codigo = 0;
    public $ed175_regencia = 0;
    public $ed175_diasemana = 0;
    public $ed175_periodo = 0;
    public $ed175_rechumano = 0;
    public $ed175_ativo = 'f';
    public $ed175_tipovinculo = 0;
    public $ed175_datainicio_dia = null;
    public $ed175_datainicio_mes = null;
    public $ed175_datainicio_ano = null;
    public $ed175_datainicio = null;
    public $ed175_datafim_dia = null;
    public $ed175_datafim_mes = null;
    public $ed175_datafim_ano = null;
    public $ed175_datafim = null;
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 ed175_codigo = int8 = Código
                 ed175_regencia = int8 = Regencia
                 ed175_diasemana = int8 = Dias Semana
                 ed175_periodo = int8 = Periodo
                 ed175_rechumano = int8 = RecHumano
                 ed175_ativo = bool = Ativo
                 ed175_tipovinculo = int8 = TipoVinculo
                 ed175_datainicio = date = Data Inicio
                 ed175_datafim = date = Data Fim
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("regenciahorariodiscsemreg");
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
            $this->ed175_codigo = ($this->ed175_codigo == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed175_codigo"] : $this->ed175_codigo);
            $this->ed175_regencia = ($this->ed175_regencia == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed175_regencia"] : $this->ed175_regencia);
            $this->ed175_diasemana = ($this->ed175_diasemana == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed175_diasemana"] : $this->ed175_diasemana);
            $this->ed175_periodo = ($this->ed175_periodo == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed175_periodo"] : $this->ed175_periodo);
            $this->ed175_rechumano = ($this->ed175_rechumano == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed175_rechumano"] : $this->ed175_rechumano);
            $this->ed175_ativo = ($this->ed175_ativo == "f" ? @$GLOBALS["HTTP_POST_VARS"]["ed175_ativo"] : $this->ed175_ativo);
            $this->ed175_tipovinculo = ($this->ed175_tipovinculo == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed175_tipovinculo"] : $this->ed175_tipovinculo);
            if ($this->ed175_datainicio == "") {
                $this->ed175_datainicio_dia = ($this->ed175_datainicio_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed175_datainicio_dia"] : $this->ed175_datainicio_dia);
                $this->ed175_datainicio_mes = ($this->ed175_datainicio_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed175_datainicio_mes"] : $this->ed175_datainicio_mes);
                $this->ed175_datainicio_ano = ($this->ed175_datainicio_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed175_datainicio_ano"] : $this->ed175_datainicio_ano);
                if ($this->ed175_datainicio_dia != "") {
                    $this->ed175_datainicio = $this->ed175_datainicio_ano . "-" . $this->ed175_datainicio_mes . "-" . $this->ed175_datainicio_dia;
                }
            }
            if ($this->ed175_datafim == "") {
                $this->ed175_datafim_dia = ($this->ed175_datafim_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed175_datafim_dia"] : $this->ed175_datafim_dia);
                $this->ed175_datafim_mes = ($this->ed175_datafim_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed175_datafim_mes"] : $this->ed175_datafim_mes);
                $this->ed175_datafim_ano = ($this->ed175_datafim_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed175_datafim_ano"] : $this->ed175_datafim_ano);
                if ($this->ed175_datafim_dia != "") {
                    $this->ed175_datafim = $this->ed175_datafim_ano . "-" . $this->ed175_datafim_mes . "-" . $this->ed175_datafim_dia;
                }
            }
        } else {
            $this->ed175_codigo = ($this->ed175_codigo == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed175_codigo"] : $this->ed175_codigo);
        }
    }

    public function incluir($ed175_codigo)
    {
        $this->atualizacampos();
        if ($this->ed175_regencia == null) {
            $this->erro_sql = " Campo Regencia não informado.";
            $this->erro_campo = "ed175_regencia";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed175_diasemana == null) {
            $this->erro_sql = " Campo Dias Semana não informado.";
            $this->erro_campo = "ed175_diasemana";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed175_periodo == null) {
            $this->erro_sql = " Campo Periodo não informado.";
            $this->erro_campo = "ed175_periodo";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed175_rechumano == null) {
            $this->ed175_rechumano = "0";
        }
        if ($this->ed175_ativo == null) {
            $this->ed175_ativo = "f";
        }
        if ($this->ed175_tipovinculo == null) {
            $this->ed175_tipovinculo = "0";
        }
        if ($this->ed175_datainicio == null) {
            $this->erro_sql = " Campo Data Inicio não informado.";
            $this->erro_campo = "ed175_datainicio_dia";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed175_datafim == null) {
            $this->erro_sql = " Campo Data Fim não informado.";
            $this->erro_campo = "ed175_datafim_dia";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($ed175_codigo == "" || $ed175_codigo == null) {
            $result = db_query("select nextval('regenciahorariodiscsemreg_ed175_codigo_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: regenciahorariodiscsemreg_ed175_codigo_seq do campo: ed175_codigo";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->ed175_codigo = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from regenciahorariodiscsemreg_ed175_codigo_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $ed175_codigo)) {
                $this->erro_sql = " Campo ed175_codigo maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->ed175_codigo = $ed175_codigo;
            }
        }
        if (($this->ed175_codigo == null) || ($this->ed175_codigo == "")) {
            $this->erro_sql = " Campo ed175_codigo não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into regenciahorariodiscsemreg(
                                       ed175_codigo
                                      ,ed175_regencia
                                      ,ed175_diasemana
                                      ,ed175_periodo
                                      ,ed175_rechumano
                                      ,ed175_ativo
                                      ,ed175_tipovinculo
                                      ,ed175_datainicio
                                      ,ed175_datafim
                       )
                values (
                                $this->ed175_codigo
                               ,$this->ed175_regencia
                               ,$this->ed175_diasemana
                               ,$this->ed175_periodo
                               ,$this->ed175_rechumano
                               ,'$this->ed175_ativo'
                               ,$this->ed175_tipovinculo
                               ," . ($this->ed175_datainicio == "null" || $this->ed175_datainicio == "" ? "null" : "'" . $this->ed175_datainicio . "'") . "
                               ," . ($this->ed175_datafim == "null" || $this->ed175_datafim == "" ? "null" : "'" . $this->ed175_datafim . "'") . "
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = "regencia horario disciplina sem regente ($this->ed175_codigo) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "regencia horario disciplina sem regente já Cadastrado";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql = "regencia horario disciplina sem regente ($this->ed175_codigo) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
        $this->erro_sql .= "Valores : " . $this->ed175_codigo;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {
            $resaco = $this->sql_record($this->sql_query_file($this->ed175_codigo));
            if (($resaco != false) || ($this->numrows != 0)) {
                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,1013280,'$this->ed175_codigo','I')");
                $resac = db_query("insert into db_acount values($acount,1010804,1013280,'','" . AddSlashes(pg_result($resaco, 0, 'ed175_codigo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010804,1013281,'','" . AddSlashes(pg_result($resaco, 0, 'ed175_regencia')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010804,1013282,'','" . AddSlashes(pg_result($resaco, 0, 'ed175_diasemana')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010804,1013283,'','" . AddSlashes(pg_result($resaco, 0, 'ed175_periodo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010804,1013284,'','" . AddSlashes(pg_result($resaco, 0, 'ed175_rechumano')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010804,1013285,'','" . AddSlashes(pg_result($resaco, 0, 'ed175_ativo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010804,1013286,'','" . AddSlashes(pg_result($resaco, 0, 'ed175_tipovinculo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010804,1013287,'','" . AddSlashes(pg_result($resaco, 0, 'ed175_datainicio')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010804,1013288,'','" . AddSlashes(pg_result($resaco, 0, 'ed175_datafim')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
    }

    public function alterar($ed175_codigo = null)
    {
        $this->atualizacampos();
        $sql = " update regenciahorariodiscsemreg set ";
        $virgula = "";
        if (trim($this->ed175_codigo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed175_codigo"])) {
            $sql .= $virgula . " ed175_codigo = $this->ed175_codigo ";
            $virgula = ",";
            if (trim($this->ed175_codigo) == null) {
                $this->erro_sql = " Campo Código não informado.";
                $this->erro_campo = "ed175_codigo";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed175_regencia) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed175_regencia"])) {
            $sql .= $virgula . " ed175_regencia = $this->ed175_regencia ";
            $virgula = ",";
            if (trim($this->ed175_regencia) == null) {
                $this->erro_sql = " Campo Regencia não informado.";
                $this->erro_campo = "ed175_regencia";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed175_diasemana) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed175_diasemana"])) {
            $sql .= $virgula . " ed175_diasemana = $this->ed175_diasemana ";
            $virgula = ",";
            if (trim($this->ed175_diasemana) == null) {
                $this->erro_sql = " Campo Dias Semana não informado.";
                $this->erro_campo = "ed175_diasemana";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed175_periodo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed175_periodo"])) {
            $sql .= $virgula . " ed175_periodo = $this->ed175_periodo ";
            $virgula = ",";
            if (trim($this->ed175_periodo) == null) {
                $this->erro_sql = " Campo Periodo não informado.";
                $this->erro_campo = "ed175_periodo";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed175_rechumano) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed175_rechumano"])) {
            if (trim($this->ed175_rechumano) == "" && isset($GLOBALS["HTTP_POST_VARS"]["ed175_rechumano"])) {
                $this->ed175_rechumano = "0";
            }
            $sql .= $virgula . " ed175_rechumano = $this->ed175_rechumano ";
            $virgula = ",";
        }
        if (trim($this->ed175_ativo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed175_ativo"])) {
            $sql .= $virgula . " ed175_ativo = '$this->ed175_ativo' ";
            $virgula = ",";
        }
        if (trim($this->ed175_tipovinculo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed175_tipovinculo"])) {
            if (trim($this->ed175_tipovinculo) == "" && isset($GLOBALS["HTTP_POST_VARS"]["ed175_tipovinculo"])) {
                $this->ed175_tipovinculo = "0";
            }
            $sql .= $virgula . " ed175_tipovinculo = $this->ed175_tipovinculo ";
            $virgula = ",";
        }
        if (trim($this->ed175_datainicio) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed175_datainicio_dia"]) && ($GLOBALS["HTTP_POST_VARS"]["ed175_datainicio_dia"] != "")) {
            $sql .= $virgula . " ed175_datainicio = '$this->ed175_datainicio' ";
            $virgula = ",";
            if (trim($this->ed175_datainicio) == null) {
                $this->erro_sql = " Campo Data Inicio não informado.";
                $this->erro_campo = "ed175_datainicio_dia";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        } else {
            if (isset($GLOBALS["HTTP_POST_VARS"]["ed175_datainicio_dia"])) {
                $sql .= $virgula . " ed175_datainicio = null ";
                $virgula = ",";
                if (trim($this->ed175_datainicio) == null) {
                    $this->erro_sql = " Campo Data Inicio não informado.";
                    $this->erro_campo = "ed175_datainicio_dia";
                    $this->erro_banco = "";
                    $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                    $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                    $this->erro_status = "0";
                    return false;
                }
            }
        }
        if (trim($this->ed175_datafim) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed175_datafim_dia"]) && ($GLOBALS["HTTP_POST_VARS"]["ed175_datafim_dia"] != "")) {
            $sql .= $virgula . " ed175_datafim = '$this->ed175_datafim' ";
            $virgula = ",";
            if (trim($this->ed175_datafim) == null) {
                $this->erro_sql = " Campo Data Fim não informado.";
                $this->erro_campo = "ed175_datafim_dia";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        } else {
            if (isset($GLOBALS["HTTP_POST_VARS"]["ed175_datafim_dia"])) {
                $sql .= $virgula . " ed175_datafim = null ";
                $virgula = ",";
                if (trim($this->ed175_datafim) == null) {
                    $this->erro_sql = " Campo Data Fim não informado.";
                    $this->erro_campo = "ed175_datafim_dia";
                    $this->erro_banco = "";
                    $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                    $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                    $this->erro_status = "0";
                    return false;
                }
            }
        }
        $sql .= " where ";
        if ($ed175_codigo != null) {
            $sql .= " ed175_codigo = $this->ed175_codigo";
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {
            $resaco = $this->sql_record($this->sql_query_file($this->ed175_codigo));
            if ($this->numrows > 0) {
                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1013280,'$this->ed175_codigo','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed175_codigo"]) || $this->ed175_codigo != "") {
                        $resac = db_query("insert into db_acount values($acount,1010804,1013280,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed175_codigo')) . "','$this->ed175_codigo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed175_regencia"]) || $this->ed175_regencia != "") {
                        $resac = db_query("insert into db_acount values($acount,1010804,1013281,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed175_regencia')) . "','$this->ed175_regencia'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed175_diasemana"]) || $this->ed175_diasemana != "") {
                        $resac = db_query("insert into db_acount values($acount,1010804,1013282,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed175_diasemana')) . "','$this->ed175_diasemana'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed175_periodo"]) || $this->ed175_periodo != "") {
                        $resac = db_query("insert into db_acount values($acount,1010804,1013283,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed175_periodo')) . "','$this->ed175_periodo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed175_rechumano"]) || $this->ed175_rechumano != "") {
                        $resac = db_query("insert into db_acount values($acount,1010804,1013284,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed175_rechumano')) . "','$this->ed175_rechumano'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed175_ativo"]) || $this->ed175_ativo != "") {
                        $resac = db_query("insert into db_acount values($acount,1010804,1013285,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed175_ativo')) . "','$this->ed175_ativo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed175_tipovinculo"]) || $this->ed175_tipovinculo != "") {
                        $resac = db_query("insert into db_acount values($acount,1010804,1013286,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed175_tipovinculo')) . "','$this->ed175_tipovinculo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed175_datainicio"]) || $this->ed175_datainicio != "") {
                        $resac = db_query("insert into db_acount values($acount,1010804,1013287,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed175_datainicio')) . "','$this->ed175_datainicio'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed175_datafim"]) || $this->ed175_datafim != "") {
                        $resac = db_query("insert into db_acount values($acount,1010804,1013288,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed175_datafim')) . "','$this->ed175_datafim'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "regencia horario disciplina sem regente não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->ed175_codigo;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "regencia horario disciplina sem regente não foi Alterado. Alteração Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->ed175_codigo;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $this->ed175_codigo;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($ed175_codigo = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {
            if (empty($dbwhere)) {
                $resaco = $this->sql_record($this->sql_query_file($ed175_codigo));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {
                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1013280,'$ed175_codigo','E')");
                    $resac = db_query("insert into db_acount values($acount,1010804,1013280,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed175_codigo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010804,1013281,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed175_regencia')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010804,1013282,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed175_diasemana')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010804,1013283,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed175_periodo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010804,1013284,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed175_rechumano')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010804,1013285,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed175_ativo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010804,1013286,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed175_tipovinculo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010804,1013287,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed175_datainicio')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010804,1013288,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed175_datafim')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " delete from regenciahorariodiscsemreg
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed175_codigo)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " ed175_codigo = $ed175_codigo ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "regencia horario disciplina sem regente não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $ed175_codigo;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "regencia horario disciplina sem regente não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $ed175_codigo;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $ed175_codigo;
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
            $this->erro_sql = "Record Vazio na Tabela:regenciahorariodiscsemreg";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query($ed175_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos}";
        $sql .= "  from regenciahorariodiscsemreg ";
        $sql .= "      inner join periodoescola  on  periodoescola.ed17_i_codigo = regenciahorariodiscsemreg.ed175_periodo";
        $sql .= "      inner join regencia  on  regencia.ed59_i_codigo = regenciahorariodiscsemreg.ed175_regencia";
        $sql .= "      inner join diasemana  on  diasemana.ed32_i_codigo = regenciahorariodiscsemreg.ed175_diasemana";
        $sql .= "      inner join escola  on  escola.ed18_i_codigo = periodoescola.ed17_i_escola";
        $sql .= "      inner join periodoaula  on  periodoaula.ed08_i_codigo = periodoescola.ed17_i_periodoaula";
        $sql .= "      inner join turno  on  turno.ed15_i_codigo = periodoescola.ed17_i_turno";
        $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = regencia.ed59_i_disciplina";
        $sql .= "      inner join serie  on  serie.ed11_i_codigo = regencia.ed59_i_serie";
        $sql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = regencia.ed59_procedimento";
        $sql .= "      inner join turma  on  turma.ed57_i_codigo = regencia.ed59_i_turma";
        $sql .= "      left  join regencia  as a on   a.ed59_i_codigo = regencia.ed59_areaconhecimento";
        $sql2 = "";

        if (empty($dbwhere)) {
            if (!empty($ed175_codigo)) {
                $sql2 .= " where regenciahorariodiscsemreg.ed175_codigo = $ed175_codigo ";
            }
        } elseif (!empty($dbwhere)) {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if (!empty($ordem)) {
            $sql .= " order by {$ordem}";
        }
        return $sql;
    }

    public function sql_query_file($ed175_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos} ";
        $sql .= "  from regenciahorariodiscsemreg ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed175_codigo)) {
                $sql2 .= " where regenciahorariodiscsemreg.ed175_codigo = $ed175_codigo ";
            }
        } elseif (!empty($dbwhere)) {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if (!empty($ordem)) {
            $sql .= " order by {$ordem}";
        }
        return $sql;
    }
}
