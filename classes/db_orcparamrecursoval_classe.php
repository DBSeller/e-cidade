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

class cl_orcparamrecursoval
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
    public $o48_seq = 0;
    public $o48_grupo = 0;
    public $o48_anousu = 0;
    public $o48_codrec = 0;
    public $o48_instit = 0;
    public $o48_valor = 0;
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 o48_seq = int8 = sequencial
                 o48_grupo = int4 = Agrupamento de registros
                 o48_anousu = int4 = Exercicio
                 o48_codrec = int4 = Recurso
                 o48_instit = int4 = Instituição
                 o48_valor = float8 = Valor
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("orcparamrecursoval");
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
            $this->o48_seq = ($this->o48_seq == "" ? @$GLOBALS["HTTP_POST_VARS"]["o48_seq"] : $this->o48_seq);
            $this->o48_grupo = ($this->o48_grupo == "" ? @$GLOBALS["HTTP_POST_VARS"]["o48_grupo"] : $this->o48_grupo);
            $this->o48_anousu = ($this->o48_anousu == "" ? @$GLOBALS["HTTP_POST_VARS"]["o48_anousu"] : $this->o48_anousu);
            $this->o48_codrec = ($this->o48_codrec == "" ? @$GLOBALS["HTTP_POST_VARS"]["o48_codrec"] : $this->o48_codrec);
            $this->o48_instit = ($this->o48_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["o48_instit"] : $this->o48_instit);
            $this->o48_valor = ($this->o48_valor == "" ? @$GLOBALS["HTTP_POST_VARS"]["o48_valor"] : $this->o48_valor);
        } else {
            $this->o48_seq = ($this->o48_seq == "" ? @$GLOBALS["HTTP_POST_VARS"]["o48_seq"] : $this->o48_seq);
        }
    }

    public function incluir($o48_seq)
    {
        $this->atualizacampos();
        if ($this->o48_grupo == null) {
            $this->erro_sql = " Campo Agrupamento de registros não informado.";
            $this->erro_campo = "o48_grupo";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->o48_anousu == null) {
            $this->erro_sql = " Campo Exercicio não informado.";
            $this->erro_campo = "o48_anousu";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->o48_codrec == null) {
            $this->erro_sql = " Campo Recurso não informado.";
            $this->erro_campo = "o48_codrec";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->o48_instit == null) {
            $this->erro_sql = " Campo Instituição não informado.";
            $this->erro_campo = "o48_instit";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->o48_valor == null) {
            $this->erro_sql = " Campo Valor não informado.";
            $this->erro_campo = "o48_valor";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($o48_seq == "" || $o48_seq == null) {
            $result = db_query("select nextval('orcparamrecursoval_o48_seq_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: orcparamrecursoval_o48_seq_seq do campo: o48_seq";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->o48_seq = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from orcparamrecursoval_o48_seq_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $o48_seq)) {
                $this->erro_sql = " Campo o48_seq maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->o48_seq = $o48_seq;
            }
        }
        if (($this->o48_seq == null) || ($this->o48_seq == "")) {
            $this->erro_sql = " Campo o48_seq não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into orcparamrecursoval(
                                       o48_seq
                                      ,o48_grupo
                                      ,o48_anousu
                                      ,o48_codrec
                                      ,o48_instit
                                      ,o48_valor
                       )
                values (
                                $this->o48_seq
                               ,$this->o48_grupo
                               ,$this->o48_anousu
                               ,$this->o48_codrec
                               ,$this->o48_instit
                               ,$this->o48_valor
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = " ($this->o48_seq) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = " já Cadastrado";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql = " ($this->o48_seq) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
        $this->erro_sql .= "Valores : " . $this->o48_seq;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->o48_seq));
            if (($resaco != false) || ($this->numrows != 0)) {

                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,7875,'$this->o48_seq','I')");
                $resac = db_query("insert into db_acount values($acount,1645,7875,'','" . AddSlashes(pg_result($resaco, 0, 'o48_seq')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1645,9763,'','" . AddSlashes(pg_result($resaco, 0, 'o48_grupo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1645,9586,'','" . AddSlashes(pg_result($resaco, 0, 'o48_anousu')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1645,9587,'','" . AddSlashes(pg_result($resaco, 0, 'o48_codrec')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1645,9589,'','" . AddSlashes(pg_result($resaco, 0, 'o48_instit')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1645,9588,'','" . AddSlashes(pg_result($resaco, 0, 'o48_valor')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
    }

    public function alterar($o48_seq = null)
    {
        $this->atualizacampos();
        $sql = " update orcparamrecursoval set ";
        $virgula = "";
        if (trim($this->o48_seq) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o48_seq"])) {
            $sql .= $virgula . " o48_seq = $this->o48_seq ";
            $virgula = ",";
            if (trim($this->o48_seq) == null) {
                $this->erro_sql = " Campo sequencial não informado.";
                $this->erro_campo = "o48_seq";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->o48_grupo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o48_grupo"])) {
            $sql .= $virgula . " o48_grupo = $this->o48_grupo ";
            $virgula = ",";
            if (trim($this->o48_grupo) == null) {
                $this->erro_sql = " Campo Agrupamento de registros não informado.";
                $this->erro_campo = "o48_grupo";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->o48_anousu) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o48_anousu"])) {
            $sql .= $virgula . " o48_anousu = $this->o48_anousu ";
            $virgula = ",";
            if (trim($this->o48_anousu) == null) {
                $this->erro_sql = " Campo Exercicio não informado.";
                $this->erro_campo = "o48_anousu";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->o48_codrec) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o48_codrec"])) {
            $sql .= $virgula . " o48_codrec = $this->o48_codrec ";
            $virgula = ",";
            if (trim($this->o48_codrec) == null) {
                $this->erro_sql = " Campo Recurso não informado.";
                $this->erro_campo = "o48_codrec";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->o48_instit) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o48_instit"])) {
            $sql .= $virgula . " o48_instit = $this->o48_instit ";
            $virgula = ",";
            if (trim($this->o48_instit) == null) {
                $this->erro_sql = " Campo Instituição não informado.";
                $this->erro_campo = "o48_instit";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->o48_valor) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o48_valor"])) {
            $sql .= $virgula . " o48_valor = $this->o48_valor ";
            $virgula = ",";
            if (trim($this->o48_valor) == null) {
                $this->erro_sql = " Campo Valor não informado.";
                $this->erro_campo = "o48_valor";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if ($o48_seq != null) {
            $sql .= " o48_seq = $this->o48_seq";
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->o48_seq));
            if ($this->numrows > 0) {

                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,7875,'$this->o48_seq','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o48_seq"]) || $this->o48_seq != "")
                        $resac = db_query("insert into db_acount values($acount,1645,7875,'" . AddSlashes(pg_result($resaco, $conresaco, 'o48_seq')) . "','$this->o48_seq'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o48_grupo"]) || $this->o48_grupo != "")
                        $resac = db_query("insert into db_acount values($acount,1645,9763,'" . AddSlashes(pg_result($resaco, $conresaco, 'o48_grupo')) . "','$this->o48_grupo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o48_anousu"]) || $this->o48_anousu != "")
                        $resac = db_query("insert into db_acount values($acount,1645,9586,'" . AddSlashes(pg_result($resaco, $conresaco, 'o48_anousu')) . "','$this->o48_anousu'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o48_codrec"]) || $this->o48_codrec != "")
                        $resac = db_query("insert into db_acount values($acount,1645,9587,'" . AddSlashes(pg_result($resaco, $conresaco, 'o48_codrec')) . "','$this->o48_codrec'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o48_instit"]) || $this->o48_instit != "")
                        $resac = db_query("insert into db_acount values($acount,1645,9589,'" . AddSlashes(pg_result($resaco, $conresaco, 'o48_instit')) . "','$this->o48_instit'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o48_valor"]) || $this->o48_valor != "")
                        $resac = db_query("insert into db_acount values($acount,1645,9588,'" . AddSlashes(pg_result($resaco, $conresaco, 'o48_valor')) . "','$this->o48_valor'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = " não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->o48_seq;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = " não foi Alterado. Alteração Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->o48_seq;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $this->o48_seq;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($o48_seq = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            if (empty($dbwhere)) {

                $resaco = $this->sql_record($this->sql_query_file($o48_seq));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,7875,'$o48_seq','E')");
                    $resac = db_query("insert into db_acount values($acount,1645,7875,'','" . AddSlashes(pg_result($resaco, $iresaco, 'o48_seq')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1645,9763,'','" . AddSlashes(pg_result($resaco, $iresaco, 'o48_grupo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1645,9586,'','" . AddSlashes(pg_result($resaco, $iresaco, 'o48_anousu')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1645,9587,'','" . AddSlashes(pg_result($resaco, $iresaco, 'o48_codrec')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1645,9589,'','" . AddSlashes(pg_result($resaco, $iresaco, 'o48_instit')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1645,9588,'','" . AddSlashes(pg_result($resaco, $iresaco, 'o48_valor')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " delete from orcparamrecursoval
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($o48_seq)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " o48_seq = $o48_seq ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = " não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $o48_seq;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = " não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $o48_seq;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $o48_seq;
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
            $this->erro_sql = "Record Vazio na Tabela:orcparamrecursoval";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query($o48_seq = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "
           select {$campos}
             from orcparamrecursoval
             join db_config  on  db_config.codigo = orcparamrecursoval.o48_instit
             join orctiporec  on  orctiporec.o15_codigo = orcparamrecursoval.o48_codrec
             join cgm  on  cgm.z01_numcgm = db_config.numcgm
             join complementofonterecurso on complementofonterecurso.o200_sequencial = orctiporec.o15_complemento
        ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($o48_seq)) {
                $sql2 .= " where orcparamrecursoval.o48_seq = $o48_seq ";
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

    public function sql_query_file($o48_seq = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos} ";
        $sql .= "  from orcparamrecursoval ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($o48_seq)) {
                $sql2 .= " where orcparamrecursoval.o48_seq = $o48_seq ";
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
