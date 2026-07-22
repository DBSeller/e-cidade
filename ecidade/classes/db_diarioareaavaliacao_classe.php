<?php

class cl_diarioareaavaliacao
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
    public $ed163_codigo = 0;
    public $ed163_diarioarea = 0;
    public $ed163_areaprocedimentoavaliacao = 0;
    public $ed163_nota = 0;
    public $ed163_parecer = null;
    public $ed163_conceito = null;
    public $ed163_amparado = 'f';
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 ed163_codigo = int4 = Código
                 ed163_diarioarea = int4 = Diário Área Conhecimento
                 ed163_areaprocedimentoavaliacao = int4 = Período de Avaliação
                 ed163_nota = float8 = Nota
                 ed163_parecer = text = Parecer
                 ed163_conceito = varchar(3) = Conceito
                 ed163_amparado = bool = Amparado
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("diarioareaavaliacao");
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
            $this->ed163_codigo = ($this->ed163_codigo == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed163_codigo"] : $this->ed163_codigo);
            $this->ed163_diarioarea = ($this->ed163_diarioarea == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed163_diarioarea"] : $this->ed163_diarioarea);
            $this->ed163_areaprocedimentoavaliacao = ($this->ed163_areaprocedimentoavaliacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed163_areaprocedimentoavaliacao"] : $this->ed163_areaprocedimentoavaliacao);
            $this->ed163_nota = ($this->ed163_nota == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed163_nota"] : $this->ed163_nota);
            $this->ed163_parecer = ($this->ed163_parecer == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed163_parecer"] : $this->ed163_parecer);
            $this->ed163_conceito = ($this->ed163_conceito == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed163_conceito"] : $this->ed163_conceito);
            $this->ed163_amparado = ($this->ed163_amparado == "f" ? @$GLOBALS["HTTP_POST_VARS"]["ed163_amparado"] : $this->ed163_amparado);
        } else {
            $this->ed163_codigo = ($this->ed163_codigo == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed163_codigo"] : $this->ed163_codigo);
        }
    }

    public function incluir($ed163_codigo)
    {
        $this->atualizacampos();
        if ($this->ed163_diarioarea == null) {
            $this->erro_sql = " Campo Diário Área Conhecimento não informado.";
            $this->erro_campo = "ed163_diarioarea";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed163_areaprocedimentoavaliacao == null) {
            $this->erro_sql = " Campo Período de Avaliação não informado.";
            $this->erro_campo = "ed163_areaprocedimentoavaliacao";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed163_nota == null) {
            $this->ed163_nota = "0";
        }
        if ($this->ed163_amparado == null) {
            $this->erro_sql = " Campo Amparado não informado.";
            $this->erro_campo = "ed163_amparado";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($ed163_codigo == "" || $ed163_codigo == null) {
            $result = db_query("select nextval('diarioareaavaliacao_ed163_codigo_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: diarioareaavaliacao_ed163_codigo_seq do campo: ed163_codigo";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->ed163_codigo = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from diarioareaavaliacao_ed163_codigo_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $ed163_codigo)) {
                $this->erro_sql = " Campo ed163_codigo maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->ed163_codigo = $ed163_codigo;
            }
        }
        if (($this->ed163_codigo == null) || ($this->ed163_codigo == "")) {
            $this->erro_sql = " Campo ed163_codigo não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into diarioareaavaliacao(
                                       ed163_codigo
                                      ,ed163_diarioarea
                                      ,ed163_areaprocedimentoavaliacao
                                      ,ed163_nota
                                      ,ed163_parecer
                                      ,ed163_conceito
                                      ,ed163_amparado
                       )
                values (
                                $this->ed163_codigo
                               ,$this->ed163_diarioarea
                               ,$this->ed163_areaprocedimentoavaliacao
                               ,$this->ed163_nota
                               ,'$this->ed163_parecer'
                               ,'$this->ed163_conceito'
                               ,'$this->ed163_amparado'
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = "Diário Avaliação ($this->ed163_codigo) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "Diário Avaliação já Cadastrado";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql = "Diário Avaliação ($this->ed163_codigo) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
        $this->erro_sql .= "Valores : " . $this->ed163_codigo;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->ed163_codigo));
            if (($resaco != false) || ($this->numrows != 0)) {

                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,1011121,'$this->ed163_codigo','I')");
                $resac = db_query("insert into db_acount values($acount,1010539,1011121,'','" . AddSlashes(pg_result($resaco, 0, 'ed163_codigo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010539,1011122,'','" . AddSlashes(pg_result($resaco, 0, 'ed163_diarioarea')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010539,1011123,'','" . AddSlashes(pg_result($resaco, 0, 'ed163_areaprocedimentoavaliacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010539,1011124,'','" . AddSlashes(pg_result($resaco, 0, 'ed163_nota')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010539,1011125,'','" . AddSlashes(pg_result($resaco, 0, 'ed163_parecer')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010539,1011126,'','" . AddSlashes(pg_result($resaco, 0, 'ed163_conceito')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010539,1011127,'','" . AddSlashes(pg_result($resaco, 0, 'ed163_amparado')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
    }

    public function alterar($ed163_codigo = null)
    {
        $this->atualizacampos();
        $sql = " update diarioareaavaliacao set ";
        $virgula = "";
        if (trim($this->ed163_codigo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed163_codigo"])) {
            $sql .= $virgula . " ed163_codigo = $this->ed163_codigo ";
            $virgula = ",";
            if (trim($this->ed163_codigo) == null) {
                $this->erro_sql = " Campo Código não informado.";
                $this->erro_campo = "ed163_codigo";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed163_diarioarea) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed163_diarioarea"])) {
            $sql .= $virgula . " ed163_diarioarea = $this->ed163_diarioarea ";
            $virgula = ",";
            if (trim($this->ed163_diarioarea) == null) {
                $this->erro_sql = " Campo Diário Área Conhecimento não informado.";
                $this->erro_campo = "ed163_diarioarea";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed163_areaprocedimentoavaliacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed163_areaprocedimentoavaliacao"])) {
            $sql .= $virgula . " ed163_areaprocedimentoavaliacao = $this->ed163_areaprocedimentoavaliacao ";
            $virgula = ",";
            if (trim($this->ed163_areaprocedimentoavaliacao) == null) {
                $this->erro_sql = " Campo Período de Avaliação não informado.";
                $this->erro_campo = "ed163_areaprocedimentoavaliacao";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed163_nota) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed163_nota"])) {
            if (trim($this->ed163_nota) == "" && isset($GLOBALS["HTTP_POST_VARS"]["ed163_nota"])) {
                $this->ed163_nota = "0";
            }
            $sql .= $virgula . " ed163_nota = $this->ed163_nota ";
            $virgula = ",";
        }
        if (trim($this->ed163_parecer) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed163_parecer"])) {
            $sql .= $virgula . " ed163_parecer = '$this->ed163_parecer' ";
            $virgula = ",";
        }
        if (trim($this->ed163_conceito) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed163_conceito"])) {
            $sql .= $virgula . " ed163_conceito = '$this->ed163_conceito' ";
            $virgula = ",";
        }
        if (trim($this->ed163_amparado) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed163_amparado"])) {
            $sql .= $virgula . " ed163_amparado = '$this->ed163_amparado' ";
            $virgula = ",";
            if (trim($this->ed163_amparado) == null) {
                $this->erro_sql = " Campo Amparado não informado.";
                $this->erro_campo = "ed163_amparado";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if ($ed163_codigo != null) {
            $sql .= " ed163_codigo = $this->ed163_codigo";
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->ed163_codigo));
            if ($this->numrows > 0) {

                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1011121,'$this->ed163_codigo','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed163_codigo"]) || $this->ed163_codigo != "")
                        $resac = db_query("insert into db_acount values($acount,1010539,1011121,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed163_codigo')) . "','$this->ed163_codigo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed163_diarioarea"]) || $this->ed163_diarioarea != "")
                        $resac = db_query("insert into db_acount values($acount,1010539,1011122,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed163_diarioarea')) . "','$this->ed163_diarioarea'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed163_areaprocedimentoavaliacao"]) || $this->ed163_areaprocedimentoavaliacao != "")
                        $resac = db_query("insert into db_acount values($acount,1010539,1011123,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed163_areaprocedimentoavaliacao')) . "','$this->ed163_areaprocedimentoavaliacao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed163_nota"]) || $this->ed163_nota != "")
                        $resac = db_query("insert into db_acount values($acount,1010539,1011124,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed163_nota')) . "','$this->ed163_nota'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed163_parecer"]) || $this->ed163_parecer != "")
                        $resac = db_query("insert into db_acount values($acount,1010539,1011125,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed163_parecer')) . "','$this->ed163_parecer'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed163_conceito"]) || $this->ed163_conceito != "")
                        $resac = db_query("insert into db_acount values($acount,1010539,1011126,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed163_conceito')) . "','$this->ed163_conceito'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed163_amparado"]) || $this->ed163_amparado != "")
                        $resac = db_query("insert into db_acount values($acount,1010539,1011127,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed163_amparado')) . "','$this->ed163_amparado'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Diário Avaliação não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->ed163_codigo;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Diário Avaliação não foi Alterado. Alteração Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->ed163_codigo;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $this->ed163_codigo;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($ed163_codigo = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            if (empty($dbwhere)) {

                $resaco = $this->sql_record($this->sql_query_file($ed163_codigo));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1011121,'$ed163_codigo','E')");
                    $resac = db_query("insert into db_acount values($acount,1010539,1011121,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed163_codigo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010539,1011122,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed163_diarioarea')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010539,1011123,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed163_areaprocedimentoavaliacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010539,1011124,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed163_nota')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010539,1011125,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed163_parecer')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010539,1011126,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed163_conceito')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010539,1011127,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed163_amparado')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " delete from diarioareaavaliacao
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed163_codigo)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " ed163_codigo = $ed163_codigo ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Diário Avaliação não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $ed163_codigo;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Diário Avaliação não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $ed163_codigo;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $ed163_codigo;
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
            $this->erro_sql = "Record Vazio na Tabela:diarioareaavaliacao";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query($ed163_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos}";
        $sql .= "  from diarioareaavaliacao ";
        $sql .= "      inner join areaprocedimentoavaliacao  on  areaprocedimentoavaliacao.ed158_codigo = diarioareaavaliacao.ed163_areaprocedimentoavaliacao";
        $sql .= "      inner join diarioarea  on  diarioarea.ed162_codigo = diarioareaavaliacao.ed163_diarioarea";
        $sql .= "      inner join periodoavaliacao  on  periodoavaliacao.ed09_i_codigo = areaprocedimentoavaliacao.ed158_periodoavaliacao";
        $sql .= "      inner join formaavaliacao  on  formaavaliacao.ed37_i_codigo = areaprocedimentoavaliacao.ed158_formaavaliacao";
        $sql .= "      inner join areaprocedimento  on  areaprocedimento.ed157_codigo = areaprocedimentoavaliacao.ed158_areaprocedimento";
        $sql .= "      inner join areaconhecimento  on  areaconhecimento.ed293_sequencial = diarioarea.ed162_areaconhecimento";
        $sql .= "      inner join diarioaluno  on  diarioaluno.ed161_codigo = diarioarea.ed162_diarioaluno";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed163_codigo)) {
                $sql2 .= " where diarioareaavaliacao.ed163_codigo = $ed163_codigo ";
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

    public function sql_query_file($ed163_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos} ";
        $sql .= "  from diarioareaavaliacao ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed163_codigo)) {
                $sql2 .= " where diarioareaavaliacao.ed163_codigo = $ed163_codigo ";
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
