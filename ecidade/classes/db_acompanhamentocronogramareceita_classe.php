<?php

class cl_acompanhamentocronogramareceita
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
    public $id = 0;
    public $receita_id = 0;
    public $exercicio = 0;
    public $base_calculo = 0;
    public $janeiro = 0;
    public $fevereiro = 0;
    public $marco = 0;
    public $junho = 0;
    public $abril = 0;
    public $maio = 0;
    public $julho = 0;
    public $agosto = 0;
    public $setembro = 0;
    public $outubro = 0;
    public $novembro = 0;
    public $dezembro = 0;
    // cria propriedade com as variaveis do arquivo
    public $campos = "
        id = int8 = id
        receita_id = int4 = Receita
        exercicio = int4 = Exercício
        base_calculo = int4 = Base de cálculo
        janeiro = float8 = janeiro
        fevereiro = float8 = Fevereiro
        marco = float8 = Março
        junho = float8 = Junho
        abril = float8 = Abril
        maio = float8 = Maio
        julho = float4 = Julho
        agosto = float8 = Agosto
        setembro = float8 = Setembro
        outubro = float8 = Outubro
        novembro = float8 = Novembro
        dezembro = float8 = Dezembro
    ";

    public function __construct()
    {
        $this->rotulo = new rotulo("acompanhamentocronogramareceita");
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
            $this->id = ($this->id === "" ? @$GLOBALS["HTTP_POST_VARS"]["id"] : $this->id);
            $this->receita_id = ($this->receita_id === "" ? @$GLOBALS["HTTP_POST_VARS"]["receita_id"] : $this->receita_id);
            $this->exercicio = ($this->exercicio === "" ? @$GLOBALS["HTTP_POST_VARS"]["exercicio"] : $this->exercicio);
            $this->base_calculo = ($this->base_calculo === "" ? @$GLOBALS["HTTP_POST_VARS"]["base_calculo"] : $this->base_calculo);
            $this->janeiro = ($this->janeiro === null ? 0 : $this->janeiro);
            $this->fevereiro = ($this->fevereiro === null ? 0 : $this->fevereiro);
            $this->marco = ($this->marco === null ? 0 : $this->marco);
            $this->junho = ($this->junho === null ? 0 : $this->junho);
            $this->abril = ($this->abril === null ? 0 : $this->abril);
            $this->maio = ($this->maio === null ? 0 : $this->maio);
            $this->julho = ($this->julho === null ? 0 : $this->julho);
            $this->agosto = ($this->agosto === null ? 0 : $this->agosto);
            $this->setembro = ($this->setembro === null ? 0 : $this->setembro);
            $this->outubro = ($this->outubro === null ? 0 : $this->outubro);
            $this->novembro = ($this->novembro === null ? 0 : $this->novembro);
            $this->dezembro = ($this->dezembro === null ? 0 : $this->dezembro);
        } else {
            $this->id = ($this->id == "" ? @$GLOBALS["HTTP_POST_VARS"]["id"] : $this->id);
        }
    }

    public function incluir($id)
    {
        $this->atualizacampos();
        if ($this->receita_id == null) {
            $this->erro_sql = " Campo Receita não informado.";
            $this->erro_campo = "receita_id";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->exercicio == null) {
            $this->exercicio = "0";
        }
        if ($this->base_calculo == null) {
            $this->erro_sql = " Campo Base de cálculo não informado.";
            $this->erro_campo = "base_calculo";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->janeiro == null) {
            $this->erro_sql = " Campo janeiro não informado.";
            $this->erro_campo = "janeiro";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->fevereiro == null) {
            $this->erro_sql = " Campo Fevereiro não informado.";
            $this->erro_campo = "fevereiro";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->marco == null) {
            $this->erro_sql = " Campo Março não informado.";
            $this->erro_campo = "marco";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->junho == null) {
            $this->erro_sql = " Campo Junho não informado.";
            $this->erro_campo = "junho";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->abril == null) {
            $this->erro_sql = " Campo Abril não informado.";
            $this->erro_campo = "abril";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->maio == null) {
            $this->erro_sql = " Campo Maio não informado.";
            $this->erro_campo = "maio";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->julho == null) {
            $this->erro_sql = " Campo Julho não informado.";
            $this->erro_campo = "julho";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->agosto == null) {
            $this->erro_sql = " Campo Agosto não informado.";
            $this->erro_campo = "agosto";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->setembro == null) {
            $this->erro_sql = " Campo Setembro não informado.";
            $this->erro_campo = "setembro";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->outubro == null) {
            $this->erro_sql = " Campo Outubro não informado.";
            $this->erro_campo = "outubro";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->novembro == null) {
            $this->erro_sql = " Campo Novembro não informado.";
            $this->erro_campo = "novembro";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->dezembro == null) {
            $this->erro_sql = " Campo Dezembro não informado.";
            $this->erro_campo = "dezembro";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($id == "" || $id == null) {
            $result = db_query("select nextval('acompanhamentocronogramareceita_id_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: acompanhamentocronogramareceita_id_seq do campo: id";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->id = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from acompanhamentocronogramareceita_id_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $id)) {
                $this->erro_sql = " Campo id maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->id = $id;
            }
        }
        if (($this->id == null) || ($this->id == "")) {
            $this->erro_sql = " Campo id não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into acompanhamentocronogramareceita(
                                       id
                                      ,receita_id
                                      ,exercicio
                                      ,base_calculo
                                      ,janeiro
                                      ,fevereiro
                                      ,marco
                                      ,junho
                                      ,abril
                                      ,maio
                                      ,julho
                                      ,agosto
                                      ,setembro
                                      ,outubro
                                      ,novembro
                                      ,dezembro
                       )
                values (
                                $this->id
                               ,$this->receita_id
                               ,$this->exercicio
                               ,$this->base_calculo
                               ,$this->janeiro
                               ,$this->fevereiro
                               ,$this->marco
                               ,$this->junho
                               ,$this->abril
                               ,$this->maio
                               ,$this->julho
                               ,$this->agosto
                               ,$this->setembro
                               ,$this->outubro
                               ,$this->novembro
                               ,$this->dezembro
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = "Acompanhamento do cronograma de desembolso da rec ($this->id) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "Acompanhamento do cronograma de desembolso da rec já Cadastrado";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql = "Acompanhamento do cronograma de desembolso da rec ($this->id) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
        $this->erro_sql .= "Valores : " . $this->id;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        return true;
    }

    public function alterar($id = null)
    {
        $this->atualizacampos();
        $sql = " update acompanhamentocronogramareceita set ";
        $virgula = "";
        if (trim($this->id) != "" || isset($GLOBALS["HTTP_POST_VARS"]["id"])) {
            $sql .= $virgula . " id = $this->id ";
            $virgula = ",";
            if (trim($this->id) == null) {
                $this->erro_sql = " Campo id não informado.";
                $this->erro_campo = "id";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->receita_id) != "" || isset($GLOBALS["HTTP_POST_VARS"]["receita_id"])) {
            $sql .= $virgula . " receita_id = $this->receita_id ";
            $virgula = ",";
            if (trim($this->receita_id) == null) {
                $this->erro_sql = " Campo Receita não informado.";
                $this->erro_campo = "receita_id";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->exercicio) != "" || isset($GLOBALS["HTTP_POST_VARS"]["exercicio"])) {
            if (trim($this->exercicio) == "" && isset($GLOBALS["HTTP_POST_VARS"]["exercicio"])) {
                $this->exercicio = "0";
            }
            $sql .= $virgula . " exercicio = $this->exercicio ";
            $virgula = ",";
        }
        if (trim($this->base_calculo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["base_calculo"])) {
            $sql .= $virgula . " base_calculo = $this->base_calculo ";
            $virgula = ",";
            if (trim($this->base_calculo) == null) {
                $this->erro_sql = " Campo Base de cálculo não informado.";
                $this->erro_campo = "base_calculo";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->janeiro) !== "" || isset($GLOBALS["HTTP_POST_VARS"]["janeiro"])) {
            $sql .= $virgula . " janeiro = $this->janeiro ";
            $virgula = ",";
            if (trim($this->janeiro) == null) {
                $this->erro_sql = " Campo janeiro não informado.";
                $this->erro_campo = "janeiro";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->fevereiro) !== "" || isset($GLOBALS["HTTP_POST_VARS"]["fevereiro"])) {
            $sql .= $virgula . " fevereiro = $this->fevereiro ";
            $virgula = ",";
            if (trim($this->fevereiro) == null) {
                $this->erro_sql = " Campo Fevereiro não informado.";
                $this->erro_campo = "fevereiro";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->marco) !== "" || isset($GLOBALS["HTTP_POST_VARS"]["marco"])) {
            $sql .= $virgula . " marco = $this->marco ";
            $virgula = ",";
            if (trim($this->marco) == null) {
                $this->erro_sql = " Campo Março não informado.";
                $this->erro_campo = "marco";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->junho) !== "" || isset($GLOBALS["HTTP_POST_VARS"]["junho"])) {
            $sql .= $virgula . " junho = $this->junho ";
            $virgula = ",";
            if (trim($this->junho) == null) {
                $this->erro_sql = " Campo Junho não informado.";
                $this->erro_campo = "junho";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->abril) !== "" || isset($GLOBALS["HTTP_POST_VARS"]["abril"])) {
            $sql .= $virgula . " abril = $this->abril ";
            $virgula = ",";
            if (trim($this->abril) == null) {
                $this->erro_sql = " Campo Abril não informado.";
                $this->erro_campo = "abril";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->maio) !== "" || isset($GLOBALS["HTTP_POST_VARS"]["maio"])) {
            $sql .= $virgula . " maio = $this->maio ";
            $virgula = ",";
            if (trim($this->maio) == null) {
                $this->erro_sql = " Campo Maio não informado.";
                $this->erro_campo = "maio";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->julho) !== "" || isset($GLOBALS["HTTP_POST_VARS"]["julho"])) {
            $sql .= $virgula . " julho = $this->julho ";
            $virgula = ",";
            if (trim($this->julho) == null) {
                $this->erro_sql = " Campo Julho não informado.";
                $this->erro_campo = "julho";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->agosto) !== "" || isset($GLOBALS["HTTP_POST_VARS"]["agosto"])) {
            $sql .= $virgula . " agosto = $this->agosto ";
            $virgula = ",";
            if (trim($this->agosto) == null) {
                $this->erro_sql = " Campo Agosto não informado.";
                $this->erro_campo = "agosto";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->setembro) !== "" || isset($GLOBALS["HTTP_POST_VARS"]["setembro"])) {
            $sql .= $virgula . " setembro = $this->setembro ";
            $virgula = ",";
            if (trim($this->setembro) == null) {
                $this->erro_sql = " Campo Setembro não informado.";
                $this->erro_campo = "setembro";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->outubro) !== "" || isset($GLOBALS["HTTP_POST_VARS"]["outubro"])) {
            $sql .= $virgula . " outubro = $this->outubro ";
            $virgula = ",";
            if (trim($this->outubro) == null) {
                $this->erro_sql = " Campo Outubro não informado.";
                $this->erro_campo = "outubro";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->novembro) !== "" || isset($GLOBALS["HTTP_POST_VARS"]["novembro"])) {
            $sql .= $virgula . " novembro = $this->novembro ";
            $virgula = ",";
            if (trim($this->novembro) == null) {
                $this->erro_sql = " Campo Novembro não informado.";
                $this->erro_campo = "novembro";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->dezembro) !== "" || isset($GLOBALS["HTTP_POST_VARS"]["dezembro"])) {
            $sql .= $virgula . " dezembro = $this->dezembro ";
            $virgula = ",";
            if (trim($this->dezembro) == null) {
                $this->erro_sql = " Campo Dezembro não informado.";
                $this->erro_campo = "dezembro";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if ($id != null) {
            $sql .= " id = $this->id";
        }

        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Acompanhamento do cronograma de desembolso da rec não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->id;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Acompanhamento do cronograma de desembolso da rec não foi Alterado. Alteração Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->id;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $this->id;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($id = null, $dbwhere = null)
    {
        $sql = " delete from acompanhamentocronogramareceita
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($id)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " id = $id ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Acompanhamento do cronograma de desembolso da rec não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $id;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Acompanhamento do cronograma de desembolso da rec não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $id;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $id;
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
            $this->erro_sql = "Record Vazio na Tabela:acompanhamentocronogramareceita";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query($id = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select {$campos}";
        $sql .= "  from acompanhamentocronogramareceita ";
        $sql .= "      left  join orcreceita  on  orcreceita.o70_anousu = acompanhamentocronogramareceita.exercicio and  orcreceita.o70_codrec = acompanhamentocronogramareceita.receita_id";
        $sql .= "      inner join db_config  on  db_config.codigo = orcreceita.o70_instit";
        $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcreceita.o70_codigo";
        $sql .= "      inner join orcfontes  on  orcfontes.o57_codfon = orcreceita.o70_codfon and  orcfontes.o57_anousu = orcreceita.o70_anousu";
        $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcreceita.o70_anousu and  orcunidade.o41_orgao = orcreceita.o70_orcorgao and  orcunidade.o41_unidade = orcreceita.o70_orcunidade";
        $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = orcreceita.o70_concarpeculiar";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($id)) {
                $sql2 .= " where acompanhamentocronogramareceita.id = $id ";
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

    public function sql_query_file($id = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select {$campos} ";
        $sql .= "  from acompanhamentocronogramareceita ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($id)) {
                $sql2 .= " where acompanhamentocronogramareceita.id = $id ";
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

    public function sql_query_receita($id = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "
        select {$campos}
          from acompanhamentocronogramareceita
          join orcreceita on orcreceita.o70_anousu = acompanhamentocronogramareceita.exercicio
               and orcreceita.o70_codrec = acompanhamentocronogramareceita.receita_id
        ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($id)) {
                $sql2 .= " where acompanhamentocronogramadespesa.id = $id ";
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
