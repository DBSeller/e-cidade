<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

/**
 * Class cl_diarioarearesultado
 */
class cl_diarioarearesultado
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
    public $ed164_codigo = 0;
    public $ed164_diarioarea = 0;
    public $ed164_areaprocedimentoresultado = 0;
    public $ed164_nota = null;
    public $ed164_parecer = null;
    public $ed164_conceito = null;
    public $ed164_resultado_avaliacao = null;
    public $ed164_resultado_frequencia = null;
    public $ed164_amparado = 'f';
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 ed164_codigo = int4 = Código
                 ed164_diarioarea = int4 = Diário Área Conhecimento
                 ed164_areaprocedimentoresultado = int4 = Resultado
                 ed164_nota = float8 = Nota
                 ed164_parecer = text = Parecer
                 ed164_conceito = varchar(3) = Conceito
                 ed164_resultado_avaliacao = varchar(1) = Resultado da Avaliação
                 ed164_resultado_frequencia = varchar(1) = Resultado da Frequência
                 ed164_amparado = bool = Amparo
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("diarioarearesultado");
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
            $this->ed164_codigo = ($this->ed164_codigo == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed164_codigo"] : $this->ed164_codigo);
            $this->ed164_diarioarea = ($this->ed164_diarioarea == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed164_diarioarea"] : $this->ed164_diarioarea);
            $this->ed164_areaprocedimentoresultado = ($this->ed164_areaprocedimentoresultado == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed164_areaprocedimentoresultado"] : $this->ed164_areaprocedimentoresultado);
            $this->ed164_nota = ($this->ed164_nota === "" ? @$GLOBALS["HTTP_POST_VARS"]["ed164_nota"] : $this->ed164_nota);
            $this->ed164_parecer = ($this->ed164_parecer == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed164_parecer"] : $this->ed164_parecer);
            $this->ed164_conceito = ($this->ed164_conceito == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed164_conceito"] : $this->ed164_conceito);
            $this->ed164_resultado_avaliacao = ($this->ed164_resultado_avaliacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed164_resultado_avaliacao"] : $this->ed164_resultado_avaliacao);
            $this->ed164_resultado_frequencia = ($this->ed164_resultado_frequencia == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed164_resultado_frequencia"] : $this->ed164_resultado_frequencia);
            $this->ed164_amparado = ($this->ed164_amparado == "f" ? @$GLOBALS["HTTP_POST_VARS"]["ed164_amparado"] : $this->ed164_amparado);
        } else {
            $this->ed164_codigo = ($this->ed164_codigo == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed164_codigo"] : $this->ed164_codigo);
        }
    }

    public function incluir($ed164_codigo)
    {
        $this->atualizacampos();
        if ($this->ed164_diarioarea == null) {
            $this->erro_sql = " Campo Diário Área Conhecimento não informado.";
            $this->erro_campo = "ed164_diarioarea";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed164_areaprocedimentoresultado == null) {
            $this->erro_sql = " Campo Resultado não informado.";
            $this->erro_campo = "ed164_areaprocedimentoresultado";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed164_nota == null) {
            $this->ed164_nota = "0";
        }
        if ($this->ed164_resultado_avaliacao == null) {
            $this->erro_sql = " Campo Resultado da Avaliação não informado.";
            $this->erro_campo = "ed164_resultado_avaliacao";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed164_resultado_frequencia == null) {
            $this->erro_sql = " Campo Resultado da Frequência não informado.";
            $this->erro_campo = "ed164_resultado_frequencia";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed164_amparado == null) {
            $this->erro_sql = " Campo Amparo não informado.";
            $this->erro_campo = "ed164_amparado";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($ed164_codigo == "" || $ed164_codigo == null) {
            $result = db_query("select nextval('diarioarearesultado_ed164_codigo_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: diarioarearesultado_ed164_codigo_seq do campo: ed164_codigo";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->ed164_codigo = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from diarioarearesultado_ed164_codigo_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $ed164_codigo)) {
                $this->erro_sql = " Campo ed164_codigo maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->ed164_codigo = $ed164_codigo;
            }
        }
        if (($this->ed164_codigo == null) || ($this->ed164_codigo == "")) {
            $this->erro_sql = " Campo ed164_codigo não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into diarioarearesultado(
                                       ed164_codigo
                                      ,ed164_diarioarea
                                      ,ed164_areaprocedimentoresultado
                                      ,ed164_nota
                                      ,ed164_parecer
                                      ,ed164_conceito
                                      ,ed164_resultado_avaliacao
                                      ,ed164_resultado_frequencia
                                      ,ed164_amparado
                       )
                values (
                                $this->ed164_codigo
                               ,$this->ed164_diarioarea
                               ,$this->ed164_areaprocedimentoresultado
                               ,$this->ed164_nota
                               ,'$this->ed164_parecer'
                               ,'$this->ed164_conceito'
                               ,'$this->ed164_resultado_avaliacao'
                               ,'$this->ed164_resultado_frequencia'
                               ,'$this->ed164_amparado'
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = "Diário Resultado ($this->ed164_codigo) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "Diário Resultado já Cadastrado";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql = "Diário Resultado ($this->ed164_codigo) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
        $this->erro_sql .= "Valores : " . $this->ed164_codigo;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->ed164_codigo));
            if (($resaco != false) || ($this->numrows != 0)) {

                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,1011128,'$this->ed164_codigo','I')");
                $resac = db_query("insert into db_acount values($acount,1010540,1011128,'','" . AddSlashes(pg_result($resaco, 0, 'ed164_codigo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010540,1011129,'','" . AddSlashes(pg_result($resaco, 0, 'ed164_diarioarea')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010540,1011130,'','" . AddSlashes(pg_result($resaco, 0, 'ed164_areaprocedimentoresultado')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010540,1011131,'','" . AddSlashes(pg_result($resaco, 0, 'ed164_nota')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010540,1011132,'','" . AddSlashes(pg_result($resaco, 0, 'ed164_parecer')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010540,1011133,'','" . AddSlashes(pg_result($resaco, 0, 'ed164_conceito')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010540,1011134,'','" . AddSlashes(pg_result($resaco, 0, 'ed164_resultado_avaliacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010540,1011135,'','" . AddSlashes(pg_result($resaco, 0, 'ed164_resultado_frequencia')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010540,1011242,'','" . AddSlashes(pg_result($resaco, 0, 'ed164_amparado')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
    }

    public function alterar($ed164_codigo = null)
    {
        $this->atualizacampos();
        $sql = " update diarioarearesultado set ";
        $virgula = "";
        if (trim($this->ed164_codigo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed164_codigo"])) {
            $sql .= $virgula . " ed164_codigo = $this->ed164_codigo ";
            $virgula = ",";
            if (trim($this->ed164_codigo) == null) {
                $this->erro_sql = " Campo Código não informado.";
                $this->erro_campo = "ed164_codigo";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed164_diarioarea) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed164_diarioarea"])) {
            $sql .= $virgula . " ed164_diarioarea = $this->ed164_diarioarea ";
            $virgula = ",";
            if (trim($this->ed164_diarioarea) == null) {
                $this->erro_sql = " Campo Diário Área Conhecimento não informado.";
                $this->erro_campo = "ed164_diarioarea";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed164_areaprocedimentoresultado) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed164_areaprocedimentoresultado"])) {
            $sql .= $virgula . " ed164_areaprocedimentoresultado = $this->ed164_areaprocedimentoresultado ";
            $virgula = ",";
            if (trim($this->ed164_areaprocedimentoresultado) == null) {
                $this->erro_sql = " Campo Resultado não informado.";
                $this->erro_campo = "ed164_areaprocedimentoresultado";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed164_nota) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed164_nota"])) {
            if (trim($this->ed164_nota) == "" && isset($GLOBALS["HTTP_POST_VARS"]["ed164_nota"])) {
                $this->ed164_nota = "0";
            }
            $sql .= $virgula . " ed164_nota = $this->ed164_nota ";
            $virgula = ",";
        }
        if (trim($this->ed164_parecer) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed164_parecer"])) {
            $sql .= $virgula . " ed164_parecer = '$this->ed164_parecer' ";
            $virgula = ",";
        }
        if (trim($this->ed164_conceito) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed164_conceito"])) {
            $sql .= $virgula . " ed164_conceito = '$this->ed164_conceito' ";
            $virgula = ",";
        }
        if (trim($this->ed164_resultado_avaliacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed164_resultado_avaliacao"])) {
            $sql .= $virgula . " ed164_resultado_avaliacao = '$this->ed164_resultado_avaliacao' ";
            $virgula = ",";
            if (trim($this->ed164_resultado_avaliacao) == null) {
                $this->erro_sql = " Campo Resultado da Avaliação não informado.";
                $this->erro_campo = "ed164_resultado_avaliacao";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed164_resultado_frequencia) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed164_resultado_frequencia"])) {
            $sql .= $virgula . " ed164_resultado_frequencia = '$this->ed164_resultado_frequencia' ";
            $virgula = ",";
            if (trim($this->ed164_resultado_frequencia) == null) {
                $this->erro_sql = " Campo Resultado da Frequência não informado.";
                $this->erro_campo = "ed164_resultado_frequencia";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed164_amparado) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed164_amparado"])) {
            $sql .= $virgula . " ed164_amparado = '$this->ed164_amparado' ";
            $virgula = ",";
            if (trim($this->ed164_amparado) == null) {
                $this->erro_sql = " Campo Amparo não informado.";
                $this->erro_campo = "ed164_amparado";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if ($ed164_codigo != null) {
            $sql .= " ed164_codigo = $this->ed164_codigo";
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->ed164_codigo));
            if ($this->numrows > 0) {

                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1011128,'$this->ed164_codigo','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed164_codigo"]) || $this->ed164_codigo != "")
                        $resac = db_query("insert into db_acount values($acount,1010540,1011128,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed164_codigo')) . "','$this->ed164_codigo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed164_diarioarea"]) || $this->ed164_diarioarea != "")
                        $resac = db_query("insert into db_acount values($acount,1010540,1011129,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed164_diarioarea')) . "','$this->ed164_diarioarea'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed164_areaprocedimentoresultado"]) || $this->ed164_areaprocedimentoresultado != "")
                        $resac = db_query("insert into db_acount values($acount,1010540,1011130,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed164_areaprocedimentoresultado')) . "','$this->ed164_areaprocedimentoresultado'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed164_nota"]) || $this->ed164_nota != "")
                        $resac = db_query("insert into db_acount values($acount,1010540,1011131,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed164_nota')) . "','$this->ed164_nota'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed164_parecer"]) || $this->ed164_parecer != "")
                        $resac = db_query("insert into db_acount values($acount,1010540,1011132,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed164_parecer')) . "','$this->ed164_parecer'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed164_conceito"]) || $this->ed164_conceito != "")
                        $resac = db_query("insert into db_acount values($acount,1010540,1011133,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed164_conceito')) . "','$this->ed164_conceito'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed164_resultado_avaliacao"]) || $this->ed164_resultado_avaliacao != "")
                        $resac = db_query("insert into db_acount values($acount,1010540,1011134,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed164_resultado_avaliacao')) . "','$this->ed164_resultado_avaliacao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed164_resultado_frequencia"]) || $this->ed164_resultado_frequencia != "")
                        $resac = db_query("insert into db_acount values($acount,1010540,1011135,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed164_resultado_frequencia')) . "','$this->ed164_resultado_frequencia'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed164_amparado"]) || $this->ed164_amparado != "")
                        $resac = db_query("insert into db_acount values($acount,1010540,1011242,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed164_amparado')) . "','$this->ed164_amparado'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Diário Resultado não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->ed164_codigo;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Diário Resultado não foi Alterado. Alteração Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->ed164_codigo;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $this->ed164_codigo;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($ed164_codigo = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            if (empty($dbwhere)) {

                $resaco = $this->sql_record($this->sql_query_file($ed164_codigo));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1011128,'$ed164_codigo','E')");
                    $resac = db_query("insert into db_acount values($acount,1010540,1011128,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed164_codigo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010540,1011129,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed164_diarioarea')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010540,1011130,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed164_areaprocedimentoresultado')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010540,1011131,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed164_nota')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010540,1011132,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed164_parecer')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010540,1011133,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed164_conceito')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010540,1011134,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed164_resultado_avaliacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010540,1011135,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed164_resultado_frequencia')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010540,1011242,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed164_amparado')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " delete from diarioarearesultado
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed164_codigo)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " ed164_codigo = $ed164_codigo ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Diário Resultado não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $ed164_codigo;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Diário Resultado não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $ed164_codigo;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $ed164_codigo;
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
            $this->erro_sql = "Record Vazio na Tabela:diarioarearesultado";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query($ed164_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos}";
        $sql .= "  from diarioarearesultado ";
        $sql .= "      inner join areaprocedimentoresultado  on  areaprocedimentoresultado.ed159_codigo = diarioarearesultado.ed164_areaprocedimentoresultado";
        $sql .= "      inner join diarioarea  on  diarioarea.ed162_codigo = diarioarearesultado.ed164_diarioarea";
        $sql .= "      inner join formaavaliacao  on  formaavaliacao.ed37_i_codigo = areaprocedimentoresultado.ed159_formaavaliacao";
        $sql .= "      inner join resultado  on  resultado.ed42_i_codigo = areaprocedimentoresultado.ed159_resultado";
        $sql .= "      inner join areaprocedimento  on  areaprocedimento.ed157_codigo = areaprocedimentoresultado.ed159_areaprocedimento";
        $sql .= "      inner join areaconhecimento  on  areaconhecimento.ed293_sequencial = diarioarea.ed162_areaconhecimento";
        $sql .= "      inner join diarioaluno  on  diarioaluno.ed161_codigo = diarioarea.ed162_diarioaluno";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed164_codigo)) {
                $sql2 .= " where diarioarearesultado.ed164_codigo = $ed164_codigo ";
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

    public function sql_query_file($ed164_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos} ";
        $sql .= "  from diarioarearesultado ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed164_codigo)) {
                $sql2 .= " where diarioarearesultado.ed164_codigo = $ed164_codigo ";
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
