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
class cl_orcparamseqcoluna
{
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
    /**
     * @var int
     */
    public $o115_sequencial = 0;
    /**
     * @var int
     */
    public $o115_anousu = 0;
    /**
     * @var string
     */
    public $o115_descricao = '';
    /**
     * @var int
     */
    public $o115_tipo = 0;
    /**
     * @var string
     */
    public $o115_valoresdefault = null;
    /**
     * @var string
     */
    public $o115_nomecoluna = null;
    /**
     * @var string
     */
    public $o115_formula = null;
    /**
     * @var int
     */
    public $o115_origem = null;
    /**
     * @var int
     */
    public $o115_relatorio = null;
    public $campos = "o115_sequencial = int4 = Código Sequencial
                      o115_anousu = int4 = Ano
                      o115_descricao = varchar(100) = Descrição
                      o115_tipo = int4 = Tipo da coluna
                      o115_valoresdefault = text = Valor Default
                      o115_nomecoluna = text = Nome da Coluna
                      o115_formula = varchar(255) = Fórmula
                      o115_origem = int8 = Origem
                      o115_relatorio = int8 = Relatório";

    /**
     * @var array
     */
    private $join = array();
    
    public function __construct()
    {
        $this->rotulo = new rotulo('orcparamseqcoluna');
        $this->pagina_retorno = basename($_SERVER['PHP_SELF']);
    }

    public function erro($mostra, $retorna)
    {
        if ($this->erro_status == '0' || ($mostra == true && $this->erro_status != null)) {
            echo "<script>alert('{$this->erro_msg}')</script>";
            if ($retorna == true) {
                echo "<script>location.href='" . $this->pagina_retorno . "'</script>";
            }
        }
    }

    public function incluir($o115_sequencial)
    {
        if ($this->o115_sequencial === '' || $this->o115_sequencial === null) {
            $this->erro_sql = " Campo Código Sequencial não informado.";
            $this->erro_campo = "o115_sequencial";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace(
                '"',
                "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
            );
            $this->erro_status = "0";
            return false;
        }
        if ($this->o115_anousu === '' || $this->o115_anousu === null) {
            $this->erro_sql = " Campo Ano não informado.";
            $this->erro_campo = "o115_anousu";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace(
                '"',
                "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
            );
            $this->erro_status = "0";
            return false;
        }
        if ($this->o115_descricao === '' || $this->o115_descricao === null) {
            $this->erro_sql = " Campo Descrição não informado.";
            $this->erro_campo = "o115_descricao";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace(
                '"',
                "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
            );
            $this->erro_status = "0";
            return false;
        }
        if ($this->o115_tipo === '' || $this->o115_tipo === null) {
            $this->erro_sql = " Campo Tipo da coluna não informado.";
            $this->erro_campo = "o115_tipo";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace(
                '"',
                "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
            );
            $this->erro_status = "0";
            return false;
        }
        if ($this->o115_origem === null || $this->o115_origem === '') {
            $this->o115_origem = "0";
        }
        if ($this->o115_relatorio === null || $this->o115_relatorio === '') {
            $this->o115_relatorio = "0";
        }
        if ($o115_sequencial === '' || $o115_sequencial === null || $o115_sequencial === 0) {
            $result = db_query("select nextval('orcparamseqcoluna_o115_sequencial_seq')");
            if (!$result) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: orcparamseqcoluna_o115_sequencial_seq do campo: o115_sequencial";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace(
                    '"',
                    "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
                );
                $this->erro_status = "0";
                return false;
            }
            $this->o115_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("SELECT last_value FROM orcparamseqcoluna_o115_sequencial_seq");
            if ($result && pg_result($result, 0, 0) < $o115_sequencial) {
                $this->erro_sql = " Campo o115_sequencial maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace(
                    '"',
                    "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
                );
                $this->erro_status = "0";
                return false;
            } else {
                $this->o115_sequencial = $o115_sequencial;
            }
        }
        if ($this->o115_sequencial === null || $this->o115_sequencial === '' || $this->o115_sequencial === 0) {
            $this->erro_sql = " Campo o115_sequencial não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace(
                '"',
                "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
            );
            $this->erro_status = '0';
            return false;
        }
        $sql = "
            INSERT INTO orcparamseqcoluna (
                o115_sequencial
                ,o115_anousu
                ,o115_descricao
                ,o115_tipo
                ,o115_valoresdefault
                ,o115_nomecoluna
                ,o115_formula
                ,o115_origem
                ,o115_relatorio
            ) VALUES (
                 " . ($this->o115_sequencial === null || $this->o115_sequencial === '' ? 'NULL' : $this->o115_sequencial) . "
                ," . ($this->o115_anousu === null || $this->o115_anousu === '' ? 'NULL' : $this->o115_anousu) . "
                ," . ($this->o115_descricao === null || $this->o115_descricao === '' ? 'NULL' : "'{$this->o115_descricao}'") . "
                ," . ($this->o115_tipo === null || $this->o115_tipo === '' ? 'NULL' : $this->o115_tipo) . "
                ," . ($this->o115_valoresdefault === null || $this->o115_valoresdefault === '' ? 'NULL' : "'{$this->o115_valoresdefault}'") . "
                ," . ($this->o115_nomecoluna === null || $this->o115_nomecoluna === '' ? 'NULL' : "'{$this->o115_nomecoluna}'") . "
                ," . ($this->o115_formula === null || $this->o115_formula === '' ? 'NULL' : "'{$this->o115_formula}'") . "
                ," . ($this->o115_origem === null || $this->o115_origem === '' ? 'NULL' : $this->o115_origem) . "
                ," . ($this->o115_relatorio === null || $this->o115_relatorio === '' ? 'NULL' : $this->o115_relatorio) . "
            )
        ";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = "Colunas do relatorio () não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "Colunas do relatorio já cadastrado";
                $this->erro_msg .= str_replace(
                    '"',
                    "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
                );
            } else {
                $this->erro_sql = "Colunas do relatorio () não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace(
                    '"',
                    "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
                );
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace(
            '"',
            "",
            str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
        );
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {
            $resaco = $this->sql_record($this->sql_query_file($this->o115_sequencial));
            if ($resaco != false || $this->numrows != 0) {
                $resac = db_query("SELECT nextval('db_acount_id_acount_seq') AS acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("INSERT INTO db_acountacesso VALUES ($acount, " . db_getsession("DB_acessado") . ")");
                $resac = db_query("INSERT INTO db_acountkey VALUES ($acount,14112,'$this->o115_sequencial','I')");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,2482,14112,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'o115_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,2482,14115,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'o115_anousu')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,2482,14116,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'o115_descricao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,2482,14117,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'o115_tipo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,2482,15566,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'o115_valoresdefault')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,2482,17725,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'o115_nomecoluna')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,2482,1010348,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'o115_formula')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,2482,1010376,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'o115_origem')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,2482,1010457,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'o115_relatorio')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
    }

    public function alterar($o115_sequencial = null)
    {
        $sql = "UPDATE orcparamseqcoluna SET ";
        $virgula = '';
        $this->o115_sequencial = $o115_sequencial;
        if (trim($this->o115_anousu) !== '' && $this->o115_anousu !== null) {
            $sql .= "{$virgula} o115_anousu = {$this->o115_anousu} ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Ano" é obrigatório.');
        }
        if (trim($this->o115_descricao) !== '' && $this->o115_descricao !== null) {
            $sql .= "{$virgula} o115_descricao = '{$this->o115_descricao}' ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Descrição" é obrigatório.');
        }
        if (trim($this->o115_tipo) !== '' && $this->o115_tipo !== null) {
            $sql .= "{$virgula} o115_tipo = {$this->o115_tipo} ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Tipo da coluna" é obrigatório.');
        }
        if (trim($this->o115_valoresdefault) !== '' && $this->o115_valoresdefault !== null) {
            $sql .= "{$virgula} o115_valoresdefault = '{$this->o115_valoresdefault}' ";
            $virgula = ',';
        } else {
            $sql .= "{$virgula} o115_valoresdefault = NULL ";
            $virgula = ',';
        }
        if (trim($this->o115_nomecoluna) !== '' && $this->o115_nomecoluna !== null) {
            $sql .= "{$virgula} o115_nomecoluna = '{$this->o115_nomecoluna}' ";
            $virgula = ',';
        } else {
            $sql .= "{$virgula} o115_nomecoluna = NULL ";
            $virgula = ',';
        }
        if (trim($this->o115_formula) !== '' && $this->o115_formula !== null) {
            $sql .= "{$virgula} o115_formula = '{$this->o115_formula}' ";
            $virgula = ',';
        } else {
            $sql .= "{$virgula} o115_formula = NULL ";
            $virgula = ',';
        }
        if (trim($this->o115_origem) !== '' && $this->o115_origem !== null) {
            $sql .= "{$virgula} o115_origem = {$this->o115_origem} ";
            $virgula = ',';
        } else {
            $sql .= "{$virgula} o115_origem = NULL ";
            $virgula = ',';
        }
        if (trim($this->o115_relatorio) !== '' && $this->o115_relatorio !== null) {
            $sql .= "{$virgula} o115_relatorio = {$this->o115_relatorio} ";
        } else {
            $sql .= "{$virgula} o115_relatorio = NULL ";
        }

        if ($o115_sequencial !== '' && $o115_sequencial !== null && $o115_sequencial !== 0) {
            $sql .= ' WHERE';
            $sql .= " o115_sequencial = {$o115_sequencial}";
        }

        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {
            $resaco = $this->sql_record($this->sql_query_file($this->o115_sequencial));
            if ($this->numrows > 0) {
                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,14112,'$this->o115_sequencial','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o115_sequencial"]) || $this->o115_sequencial != "") {
                        $resac = db_query("insert into db_acount values($acount,2482,14112,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'o115_sequencial')) . "','$this->o115_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o115_anousu"]) || $this->o115_anousu != "") {
                        $resac = db_query("insert into db_acount values($acount,2482,14115,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'o115_anousu')) . "','$this->o115_anousu'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o115_descricao"]) || $this->o115_descricao != "") {
                        $resac = db_query("insert into db_acount values($acount,2482,14116,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'o115_descricao')) . "','$this->o115_descricao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o115_tipo"]) || $this->o115_tipo != "") {
                        $resac = db_query("insert into db_acount values($acount,2482,14117,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'o115_tipo')) . "','$this->o115_tipo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o115_valoresdefault"]) || $this->o115_valoresdefault != "") {
                        $resac = db_query("insert into db_acount values($acount,2482,15566,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'o115_valoresdefault')) . "','$this->o115_valoresdefault'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o115_nomecoluna"]) || $this->o115_nomecoluna != "") {
                        $resac = db_query("insert into db_acount values($acount,2482,17725,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'o115_nomecoluna')) . "','$this->o115_nomecoluna'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o115_formula"]) || $this->o115_formula != "") {
                        $resac = db_query("insert into db_acount values($acount,2482,1010348,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'o115_formula')) . "','$this->o115_formula'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o115_origem"]) || $this->o115_origem != "") {
                        $resac = db_query("insert into db_acount values($acount,2482,1010376,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'o115_origem')) . "','$this->o115_origem'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o115_relatorio"]) || $this->o115_relatorio != "") {
                        $resac = db_query("insert into db_acount values($acount,2482,1010457,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'o115_relatorio')) . "','$this->o115_relatorio'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Colunas do relatorio não Alterado. Alteração Abortada.\\n";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace(
                '"',
                "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
            );
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Colunas do relatorio não foi Alterado. Alteração Executada.\\n";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace(
                    '"',
                    "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
                );
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace(
                    '"',
                    "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
                );
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($o115_sequencial = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {
            if (empty($dbwhere)) {
                $resaco = $this->sql_record($this->sql_query_file($o115_sequencial));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {
                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,14112,'$o115_sequencial','E')");
                    $resac = db_query("insert into db_acount values($acount,2482,14112,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'o115_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,2482,14115,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'o115_anousu')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,2482,14116,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'o115_descricao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,2482,14117,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'o115_tipo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,2482,15566,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'o115_valoresdefault')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,2482,17725,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'o115_nomecoluna')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,2482,1010348,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'o115_formula')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,2482,1010376,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'o115_origem')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,2482,1010457,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'o115_relatorio')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " DELETE FROM orcparamseqcoluna
                    WHERE ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($o115_sequencial)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " o115_sequencial = $o115_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Colunas do relatorio não Excluído. Exclusão Abortada.\\n";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace(
                '"',
                "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
            );
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Colunas do relatorio não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace(
                    '"',
                    "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
                );
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace(
                    '"',
                    "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
                );
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
            $this->erro_msg .= str_replace(
                '"',
                "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
            );
            $this->erro_status = "0";
            return false;
        }
        $this->numrows = pg_num_rows($result);
        if ($this->numrows == 0) {
            $this->erro_banco = "";
            $this->erro_sql = "Record Vazio na Tabela:orcparamseqcoluna";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace(
                '"',
                "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n")
            );
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query($o115_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select {$campos}";
        $sql .= "  from orcparamseqcoluna ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($o115_sequencial)) {
                $sql2 .= " where orcparamseqcoluna.o115_sequencial = $o115_sequencial ";
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

    public function sql_query_file($o115_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select {$campos} ";
        $sql .= "  from orcparamseqcoluna ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($o115_sequencial)) {
                $sql2 .= " where orcparamseqcoluna.o115_sequencial = $o115_sequencial ";
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
    /**
     * @param null $o115_sequencial
     * @param string $campos
     * @param null $ordem
     * @param string $dbwhere
     * @return string
     */
    public function sql_query_vinculo_relatorio($o115_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select {$campos} ";
        $sql .= "  from orcparamseqcoluna ";
        $sql .= "  join orcparamseqorcparamseqcoluna on orcparamseqorcparamseqcoluna.o116_orcparamseqcoluna = orcparamseqcoluna.o115_sequencial ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($o115_sequencial)) {
                $sql2 .= " where orcparamseqcoluna.o115_sequencial = $o115_sequencial ";
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

    /**
     * @param null $o115_sequencial
     * @param string $campos
     * @param null $ordem
     * @param string $dbwhere
     * @return string
     */
    public function sql_query_relatorio($o115_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "SELECT {$campos} ";
        $sql .= "  FROM orcparamseqcoluna ";
        $sql .= "  LEFT JOIN orcparamrel ON orcparamrel.o42_codparrel = orcparamseqcoluna.o115_relatorio";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($o115_sequencial)) {
                $sql2 .= " WHERE orcparamseqcoluna.o115_sequencial = $o115_sequencial ";
            }
        } else {
            if (!empty($dbwhere)) {
                $sql2 = " WHERE $dbwhere";
            }
        }
        $sql .= $sql2;
        if (!empty($ordem)) {
            $sql .= " ORDER by {$ordem}";
        }
        return $sql;
    }

    /**
     * @param $table
     * @param $reference
     * @param $operator
     * @param $foreign
     * @return cl_orcparamseqcoluna
     */
    public function addJoin($table, $reference, $operator, $foreign)
    {
        if (array_key_exists($table, $this->join)) {
            $this->join[$table] .= " AND {$reference} {$operator} {$foreign}";
        } else {
            $this->join[$table] = "JOIN {$table} ON {$reference} {$operator} {$foreign}";
        }

        return $this;
    }

    /**
     * @param array $columns
     * @param array $where
     * @param array $order
     * @return string
     */
    public function sql($columns = array('*'), $where = array(), $order = array())
    {
        $columns = implode(', ', $columns);
        $where = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        $join = implode(' ', $this->join);
        $order = $order ? 'ORDER BY ' . implode(', ', $order) : '';

        return "SELECT {$columns} FROM orcparamseqcoluna {$join} {$where} {$order}";
    }

}
