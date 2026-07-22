<?php

class cl_turmaatividadecomplementar
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
    public $ed146_sequencial = 0;
    public $ed146_turma = 0;
    public $ed146_censoativcompl = 0;
    public $ed146_funcaoatividade = 0;
    public $ed146_rechumanoescola = 0;
    public $ed146_diasemana = 0;
    public $ed146_horainicial = null;
    public $ed146_horafinal = null;
    public $ed146_turnoreferente = 0;
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 ed146_sequencial = int4 = Código
                 ed146_turma = int4 = Turma
                 ed146_censoativcompl = int4 = Turma Atividade Complementar
                 ed146_funcaoatividade = int4 = Função/Atividade
                 ed146_rechumanoescola = int4 = Professor
                 ed146_diasemana = int4 = Dia da Semana
                 ed146_horainicial = varchar(8) = Hora inicial
                 ed146_horafinal = varchar(8) = Hora final
                 ed146_turnoreferente = int4 = Turno Referente
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("turmaatividadecomplementar");
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
            $this->ed146_sequencial = ($this->ed146_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed146_sequencial"] : $this->ed146_sequencial);
            $this->ed146_turma = ($this->ed146_turma == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed146_turma"] : $this->ed146_turma);
            $this->ed146_censoativcompl = ($this->ed146_censoativcompl == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed146_censoativcompl"] : $this->ed146_censoativcompl);
            $this->ed146_funcaoatividade = ($this->ed146_funcaoatividade == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed146_funcaoatividade"] : $this->ed146_funcaoatividade);
            $this->ed146_rechumanoescola = ($this->ed146_rechumanoescola == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed146_rechumanoescola"] : $this->ed146_rechumanoescola);
            $this->ed146_diasemana = ($this->ed146_diasemana == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed146_diasemana"] : $this->ed146_diasemana);
            $this->ed146_horainicial = ($this->ed146_horainicial == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed146_horainicial"] : $this->ed146_horainicial);
            $this->ed146_horafinal = ($this->ed146_horafinal == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed146_horafinal"] : $this->ed146_horafinal);
            $this->ed146_turnoreferente = ($this->ed146_turnoreferente == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed146_turnoreferente"] : $this->ed146_turnoreferente);
        } else {
            $this->ed146_sequencial = ($this->ed146_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed146_sequencial"] : $this->ed146_sequencial);
        }
    }

    public function incluir($ed146_sequencial)
    {
        $this->atualizacampos();
        if ($this->ed146_turma == null) {
            $this->erro_sql = " Campo Turma não informado.";
            $this->erro_campo = "ed146_turma";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed146_censoativcompl == null) {
            $this->erro_sql = " Campo Turma Atividade Complementar não informado.";
            $this->erro_campo = "ed146_censoativcompl";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed146_funcaoatividade == null) {
            $this->erro_sql = " Campo Função/Atividade não informado.";
            $this->erro_campo = "ed146_funcaoatividade";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed146_rechumanoescola == null) {
            $this->erro_sql = " Campo Professor não informado.";
            $this->erro_campo = "ed146_rechumanoescola";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed146_diasemana == null) {
            $this->erro_sql = " Campo Dia da Semana não informado.";
            $this->erro_campo = "ed146_diasemana";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed146_horainicial == null) {
            $this->erro_sql = " Campo Hora inicial não informado.";
            $this->erro_campo = "ed146_horainicial";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed146_horafinal == null) {
            $this->erro_sql = " Campo Hora final não informado.";
            $this->erro_campo = "ed146_horafinal";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed146_turnoreferente == null) {
            $this->erro_sql = " Campo Turno Referente não informado.";
            $this->erro_campo = "ed146_turnoreferente";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($ed146_sequencial == "" || $ed146_sequencial == null) {
            $result = db_query("select nextval('turmaatividadecomplementar_ed146_sequencial_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: turmaatividadecomplementar_ed146_sequencial_seq do campo: ed146_sequencial";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->ed146_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from turmaatividadecomplementar_ed146_sequencial_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $ed146_sequencial)) {
                $this->erro_sql = " Campo ed146_sequencial maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->ed146_sequencial = $ed146_sequencial;
            }
        }
        if (($this->ed146_sequencial == null) || ($this->ed146_sequencial == "")) {
            $this->erro_sql = " Campo ed146_sequencial não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into turmaatividadecomplementar(
                                       ed146_sequencial
                                      ,ed146_turma
                                      ,ed146_censoativcompl
                                      ,ed146_funcaoatividade
                                      ,ed146_rechumanoescola
                                      ,ed146_diasemana
                                      ,ed146_horainicial
                                      ,ed146_horafinal
                                      ,ed146_turnoreferente
                       )
                values (
                                $this->ed146_sequencial
                               ,$this->ed146_turma
                               ,$this->ed146_censoativcompl
                               ,$this->ed146_funcaoatividade
                               ,$this->ed146_rechumanoescola
                               ,$this->ed146_diasemana
                               ,'$this->ed146_horainicial'
                               ,'$this->ed146_horafinal'
                               ,$this->ed146_turnoreferente
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = "Turma Atividade Complementar ($this->ed146_sequencial) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "Turma Atividade Complementar já Cadastrado";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql = "Turma Atividade Complementar ($this->ed146_sequencial) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
        $this->erro_sql .= "Valores : " . $this->ed146_sequencial;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->ed146_sequencial));
            if (($resaco != false) || ($this->numrows != 0)) {

                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,1010449,'$this->ed146_sequencial','I')");
                $resac = db_query("insert into db_acount values($acount,1010443,1010449,'','" . AddSlashes(pg_result($resaco, 0, 'ed146_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010443,1010450,'','" . AddSlashes(pg_result($resaco, 0, 'ed146_turma')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010443,1010451,'','" . AddSlashes(pg_result($resaco, 0, 'ed146_censoativcompl')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010443,1010452,'','" . AddSlashes(pg_result($resaco, 0, 'ed146_funcaoatividade')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010443,1010453,'','" . AddSlashes(pg_result($resaco, 0, 'ed146_rechumanoescola')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010443,1010454,'','" . AddSlashes(pg_result($resaco, 0, 'ed146_diasemana')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010443,1010455,'','" . AddSlashes(pg_result($resaco, 0, 'ed146_horainicial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010443,1010456,'','" . AddSlashes(pg_result($resaco, 0, 'ed146_horafinal')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010443,1010458,'','" . AddSlashes(pg_result($resaco, 0, 'ed146_turnoreferente')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
    }

    public function alterar($ed146_sequencial = null)
    {
        $this->atualizacampos();
        $sql = " update turmaatividadecomplementar set ";
        $virgula = "";
        if (trim($this->ed146_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed146_sequencial"])) {
            $sql .= $virgula . " ed146_sequencial = $this->ed146_sequencial ";
            $virgula = ",";
            if (trim($this->ed146_sequencial) == null) {
                $this->erro_sql = " Campo Código não informado.";
                $this->erro_campo = "ed146_sequencial";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed146_turma) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed146_turma"])) {
            $sql .= $virgula . " ed146_turma = $this->ed146_turma ";
            $virgula = ",";
            if (trim($this->ed146_turma) == null) {
                $this->erro_sql = " Campo Turma não informado.";
                $this->erro_campo = "ed146_turma";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed146_censoativcompl) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed146_censoativcompl"])) {
            $sql .= $virgula . " ed146_censoativcompl = $this->ed146_censoativcompl ";
            $virgula = ",";
            if (trim($this->ed146_censoativcompl) == null) {
                $this->erro_sql = " Campo Turma Atividade Complementar não informado.";
                $this->erro_campo = "ed146_censoativcompl";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed146_funcaoatividade) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed146_funcaoatividade"])) {
            $sql .= $virgula . " ed146_funcaoatividade = $this->ed146_funcaoatividade ";
            $virgula = ",";
            if (trim($this->ed146_funcaoatividade) == null) {
                $this->erro_sql = " Campo Função/Atividade não informado.";
                $this->erro_campo = "ed146_funcaoatividade";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed146_rechumanoescola) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed146_rechumanoescola"])) {
            $sql .= $virgula . " ed146_rechumanoescola = $this->ed146_rechumanoescola ";
            $virgula = ",";
            if (trim($this->ed146_rechumanoescola) == null) {
                $this->erro_sql = " Campo Professor não informado.";
                $this->erro_campo = "ed146_rechumanoescola";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed146_diasemana) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed146_diasemana"])) {
            $sql .= $virgula . " ed146_diasemana = $this->ed146_diasemana ";
            $virgula = ",";
            if (trim($this->ed146_diasemana) == null) {
                $this->erro_sql = " Campo Dia da Semana não informado.";
                $this->erro_campo = "ed146_diasemana";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed146_horainicial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed146_horainicial"])) {
            $sql .= $virgula . " ed146_horainicial = '$this->ed146_horainicial' ";
            $virgula = ",";
            if (trim($this->ed146_horainicial) == null) {
                $this->erro_sql = " Campo Hora inicial não informado.";
                $this->erro_campo = "ed146_horainicial";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed146_horafinal) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed146_horafinal"])) {
            $sql .= $virgula . " ed146_horafinal = '$this->ed146_horafinal' ";
            $virgula = ",";
            if (trim($this->ed146_horafinal) == null) {
                $this->erro_sql = " Campo Hora final não informado.";
                $this->erro_campo = "ed146_horafinal";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed146_turnoreferente) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed146_turnoreferente"])) {
            $sql .= $virgula . " ed146_turnoreferente = $this->ed146_turnoreferente ";
            $virgula = ",";
            if (trim($this->ed146_turnoreferente) == null) {
                $this->erro_sql = " Campo Turno Referente não informado.";
                $this->erro_campo = "ed146_turnoreferente";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if ($ed146_sequencial != null) {
            $sql .= " ed146_sequencial = $this->ed146_sequencial";
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->ed146_sequencial));
            if ($this->numrows > 0) {

                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1010449,'$this->ed146_sequencial','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed146_sequencial"]) || $this->ed146_sequencial != "")
                        $resac = db_query("insert into db_acount values($acount,1010443,1010449,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed146_sequencial')) . "','$this->ed146_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed146_turma"]) || $this->ed146_turma != "")
                        $resac = db_query("insert into db_acount values($acount,1010443,1010450,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed146_turma')) . "','$this->ed146_turma'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed146_censoativcompl"]) || $this->ed146_censoativcompl != "")
                        $resac = db_query("insert into db_acount values($acount,1010443,1010451,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed146_censoativcompl')) . "','$this->ed146_censoativcompl'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed146_funcaoatividade"]) || $this->ed146_funcaoatividade != "")
                        $resac = db_query("insert into db_acount values($acount,1010443,1010452,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed146_funcaoatividade')) . "','$this->ed146_funcaoatividade'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed146_rechumanoescola"]) || $this->ed146_rechumanoescola != "")
                        $resac = db_query("insert into db_acount values($acount,1010443,1010453,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed146_rechumanoescola')) . "','$this->ed146_rechumanoescola'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed146_diasemana"]) || $this->ed146_diasemana != "")
                        $resac = db_query("insert into db_acount values($acount,1010443,1010454,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed146_diasemana')) . "','$this->ed146_diasemana'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed146_horainicial"]) || $this->ed146_horainicial != "")
                        $resac = db_query("insert into db_acount values($acount,1010443,1010455,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed146_horainicial')) . "','$this->ed146_horainicial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed146_horafinal"]) || $this->ed146_horafinal != "")
                        $resac = db_query("insert into db_acount values($acount,1010443,1010456,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed146_horafinal')) . "','$this->ed146_horafinal'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed146_turnoreferente"]) || $this->ed146_turnoreferente != "")
                        $resac = db_query("insert into db_acount values($acount,1010443,1010458,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed146_turnoreferente')) . "','$this->ed146_turnoreferente'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Turma Atividade Complementar não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->ed146_sequencial;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Turma Atividade Complementar não foi Alterado. Alteração Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->ed146_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $this->ed146_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($ed146_sequencial = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            if (empty($dbwhere)) {

                $resaco = $this->sql_record($this->sql_query_file($ed146_sequencial));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1010449,'$ed146_sequencial','E')");
                    $resac = db_query("insert into db_acount values($acount,1010443,1010449,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed146_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010443,1010450,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed146_turma')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010443,1010451,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed146_censoativcompl')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010443,1010452,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed146_funcaoatividade')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010443,1010453,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed146_rechumanoescola')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010443,1010454,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed146_diasemana')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010443,1010455,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed146_horainicial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010443,1010456,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed146_horafinal')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010443,1010458,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed146_turnoreferente')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " delete from turmaatividadecomplementar
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed146_sequencial)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " ed146_sequencial = $ed146_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Turma Atividade Complementar não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $ed146_sequencial;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Turma Atividade Complementar não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $ed146_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $ed146_sequencial;
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
            $this->erro_sql = "Record Vazio na Tabela:turmaatividadecomplementar";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query($ed146_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos}";
        $sql .= "  from turmaatividadecomplementar ";
        $sql .= "      inner join censoativcompl  on  censoativcompl.ed133_i_codigo = turmaatividadecomplementar.ed146_censoativcompl";
        $sql .= "      inner join funcaoatividade  on  funcaoatividade.ed119_sequencial = turmaatividadecomplementar.ed146_funcaoatividade";
        $sql .= "      inner join turma  on  turma.ed57_i_codigo = turmaatividadecomplementar.ed146_turma";
        $sql .= "      inner join diasemana  on  diasemana.ed32_i_codigo = turmaatividadecomplementar.ed146_diasemana";
        $sql .= "      inner join rechumanoescola  on  rechumanoescola.ed75_i_codigo = turmaatividadecomplementar.ed146_rechumanoescola";
        $sql .= "      left  join censocursoprofiss  on  censocursoprofiss.ed247_i_codigo = turma.ed57_i_censocursoprofiss";
        $sql .= "      inner join escola  on  escola.ed18_i_codigo = turma.ed57_i_escola";
        $sql .= "      inner join turno  on  turno.ed15_i_codigo = turma.ed57_i_turno";
        $sql .= "      inner join sala  on  sala.ed16_i_codigo = turma.ed57_i_sala";
        $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
        $sql .= "      inner join base  on  base.ed31_i_codigo = turma.ed57_i_base";
        $sql .= "      inner join escola  as a on   a.ed18_i_codigo = rechumanoescola.ed75_i_escola";
        $sql .= "      inner join rechumano  as b on   b.ed20_i_codigo = rechumanoescola.ed75_i_rechumano";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed146_sequencial)) {
                $sql2 .= " where turmaatividadecomplementar.ed146_sequencial = $ed146_sequencial ";
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

    public function sql_query_file($ed146_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos} ";
        $sql .= "  from turmaatividadecomplementar ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed146_sequencial)) {
                $sql2 .= " where turmaatividadecomplementar.ed146_sequencial = $ed146_sequencial ";
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

    public function sql_query_profissional($ed146_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select {$campos}";
        $sql .= "  from turmaatividadecomplementar ";
        $sql .= "      inner join censoativcompl  on  censoativcompl.ed133_i_codigo = turmaatividadecomplementar.ed146_censoativcompl";
        $sql .= "      inner join funcaoatividade  on  funcaoatividade.ed119_sequencial = turmaatividadecomplementar.ed146_funcaoatividade";
        $sql .= "      inner join turma  on  turma.ed57_i_codigo = turmaatividadecomplementar.ed146_turma";
        $sql .= "      inner join diasemana  on  diasemana.ed32_i_codigo = turmaatividadecomplementar.ed146_diasemana";
        $sql .= "      inner join rechumanoescola  on  rechumanoescola.ed75_i_codigo = turmaatividadecomplementar.ed146_rechumanoescola";
        $sql .= "      left  join censocursoprofiss  on  censocursoprofiss.ed247_i_codigo = turma.ed57_i_censocursoprofiss";
        $sql .= "      inner join escola  on  escola.ed18_i_codigo = turma.ed57_i_escola";
        $sql .= "      inner join turno  on  turno.ed15_i_codigo = turma.ed57_i_turno";
        $sql .= "      inner join sala  on  sala.ed16_i_codigo = turma.ed57_i_sala";
        $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turma.ed57_i_calendario";
        $sql .= "      inner join base  on  base.ed31_i_codigo = turma.ed57_i_base";
        $sql .= "      inner join rechumano  as b on   b.ed20_i_codigo = rechumanoescola.ed75_i_rechumano";
        $sql .= "       left join rechumanopessoal on rechumanopessoal.ed284_i_rechumano = b.ed20_i_codigo";
        $sql .= "       left join rhpessoal        on rhpessoal.rh01_regist              = rechumanopessoal.ed284_i_rhpessoal";
        $sql .= "       left join cgm as cgmrh     on cgmrh.z01_numcgm                   = rhpessoal.rh01_numcgm";
        $sql .= "       left join rechumanocgm     on rechumanocgm.ed285_i_rechumano     = b.ed20_i_codigo";
        $sql .= "       left join cgm as cgmcgm    on cgmcgm.z01_numcgm                  = rechumanocgm.ed285_i_cgm";

        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed146_sequencial)) {
                $sql2 .= " where turmaatividadecomplementar.ed146_sequencial = $ed146_sequencial ";
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

    public function sqlDadosCenso($where = array())
    {
        $sql = "
        select distinct ed146_rechumanoescola as vinculo_escola,
                ed146_funcaoatividade as funcao
          from turmaatividadecomplementar
          join turma on turma.ed57_i_codigo = turmaatividadecomplementar.ed146_turma
          join rechumanoescola on rechumanoescola.ed75_i_codigo = turmaatividadecomplementar.ed146_rechumanoescola
          join rechumano on rechumano.ed20_i_codigo = rechumanoescola.ed75_i_rechumano
        ";

        if ($where) {
            $sql .= " where " . implode(' and ', $where);
        }

        return $sql;
    }
}
