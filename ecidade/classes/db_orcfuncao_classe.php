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

class cl_orcfuncao
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
    public $o52_funcao = 0;
    public $o52_descr = null;
    public $o52_codtri = null;
    public $o52_finali = null;
    public $o52_siconfi = null;
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 o52_funcao = int4 = Função
                 o52_descr = varchar(40) = Descrição
                 o52_codtri = varchar(10) = Código do tribunal
                 o52_finali = text = Finalidade
                 o52_siconfi = char(2) = Código Siconfi
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("orcfuncao");
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
            $this->o52_funcao = ($this->o52_funcao == "" ? @$GLOBALS["HTTP_POST_VARS"]["o52_funcao"] : $this->o52_funcao);
            $this->o52_descr = ($this->o52_descr == "" ? @$GLOBALS["HTTP_POST_VARS"]["o52_descr"] : $this->o52_descr);
            $this->o52_codtri = ($this->o52_codtri == "" ? @$GLOBALS["HTTP_POST_VARS"]["o52_codtri"] : $this->o52_codtri);
            $this->o52_finali = ($this->o52_finali == "" ? @$GLOBALS["HTTP_POST_VARS"]["o52_finali"] : $this->o52_finali);
            $this->o52_siconfi = ($this->o52_siconfi == "" ? @$GLOBALS["HTTP_POST_VARS"]["o52_siconfi"] : $this->o52_siconfi);
        } else {
            $this->o52_funcao = ($this->o52_funcao == "" ? @$GLOBALS["HTTP_POST_VARS"]["o52_funcao"] : $this->o52_funcao);
        }
    }

    public function incluir($o52_funcao)
    {
        $this->atualizacampos();
        if ($this->o52_descr == null) {
            $this->erro_sql = " Campo Descrição não informado.";
            $this->erro_campo = "o52_descr";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->o52_codtri == null) {
            $this->erro_sql = " Campo Código do tribunal não informado.";
            $this->erro_campo = "o52_codtri";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->o52_siconfi == null) {
            $this->erro_sql = " Campo Código Siconfi não informado.";
            $this->erro_campo = "o52_siconfi";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $this->o52_funcao = $o52_funcao;
        if (($this->o52_funcao == null) || ($this->o52_funcao == "")) {
            $this->erro_sql = " Campo o52_funcao não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into orcfuncao(
                                       o52_funcao
                                      ,o52_descr
                                      ,o52_codtri
                                      ,o52_finali
                                      ,o52_siconfi
                       )
                values (
                                $this->o52_funcao
                               ,'$this->o52_descr'
                               ,'$this->o52_codtri'
                               ,'$this->o52_finali'
                               ,'$this->o52_siconfi'
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = "Funções do orçamento ($this->o52_funcao) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "Funções do orçamento já Cadastrado";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql = "Funções do orçamento ($this->o52_funcao) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
        $this->erro_sql .= "Valores : " . $this->o52_funcao;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->o52_funcao));
            if (($resaco != false) || ($this->numrows != 0)) {

                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,5252,'$this->o52_funcao','I')");
                $resac = db_query("insert into db_acount values($acount,750,5252,'','" . AddSlashes(pg_result($resaco, 0, 'o52_funcao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,750,5253,'','" . AddSlashes(pg_result($resaco, 0, 'o52_descr')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,750,5254,'','" . AddSlashes(pg_result($resaco, 0, 'o52_codtri')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,750,5255,'','" . AddSlashes(pg_result($resaco, 0, 'o52_finali')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,750,1014659,'','" . AddSlashes(pg_result($resaco, 0, 'o52_siconfi')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
    }

    public function alterar($o52_funcao = null)
    {
        $this->atualizacampos();
        $sql = " update orcfuncao set ";
        $virgula = "";
        if (trim($this->o52_funcao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o52_funcao"])) {
            $sql .= $virgula . " o52_funcao = $this->o52_funcao ";
            $virgula = ",";
            if (trim($this->o52_funcao) == null) {
                $this->erro_sql = " Campo Função não informado.";
                $this->erro_campo = "o52_funcao";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->o52_descr) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o52_descr"])) {
            $sql .= $virgula . " o52_descr = '$this->o52_descr' ";
            $virgula = ",";
            if (trim($this->o52_descr) == null) {
                $this->erro_sql = " Campo Descrição não informado.";
                $this->erro_campo = "o52_descr";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->o52_codtri) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o52_codtri"])) {
            $sql .= $virgula . " o52_codtri = '$this->o52_codtri' ";
            $virgula = ",";
            if (trim($this->o52_codtri) == null) {
                $this->erro_sql = " Campo Código do tribunal não informado.";
                $this->erro_campo = "o52_codtri";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->o52_finali) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o52_finali"])) {
            $sql .= $virgula . " o52_finali = '$this->o52_finali' ";
            $virgula = ",";
        }
        if (trim($this->o52_siconfi) != "" || isset($GLOBALS["HTTP_POST_VARS"]["o52_siconfi"])) {
            $sql .= $virgula . " o52_siconfi = '$this->o52_siconfi' ";
            $virgula = ",";
            if (trim($this->o52_siconfi) == null) {
                $this->erro_sql = " Campo Código Siconfi não informado.";
                $this->erro_campo = "o52_siconfi";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if ($o52_funcao != null) {
            $sql .= " o52_funcao = $this->o52_funcao";
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->o52_funcao));
            if ($this->numrows > 0) {

                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,5252,'$this->o52_funcao','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o52_funcao"]) || $this->o52_funcao != "")
                        $resac = db_query("insert into db_acount values($acount,750,5252,'" . AddSlashes(pg_result($resaco, $conresaco, 'o52_funcao')) . "','$this->o52_funcao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o52_descr"]) || $this->o52_descr != "")
                        $resac = db_query("insert into db_acount values($acount,750,5253,'" . AddSlashes(pg_result($resaco, $conresaco, 'o52_descr')) . "','$this->o52_descr'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o52_codtri"]) || $this->o52_codtri != "")
                        $resac = db_query("insert into db_acount values($acount,750,5254,'" . AddSlashes(pg_result($resaco, $conresaco, 'o52_codtri')) . "','$this->o52_codtri'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o52_finali"]) || $this->o52_finali != "")
                        $resac = db_query("insert into db_acount values($acount,750,5255,'" . AddSlashes(pg_result($resaco, $conresaco, 'o52_finali')) . "','$this->o52_finali'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["o52_siconfi"]) || $this->o52_siconfi != "")
                        $resac = db_query("insert into db_acount values($acount,750,1014659,'" . AddSlashes(pg_result($resaco, $conresaco, 'o52_siconfi')) . "','$this->o52_siconfi'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Funções do orçamento não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->o52_funcao;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Funções do orçamento não foi Alterado. Alteração Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->o52_funcao;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $this->o52_funcao;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($o52_funcao = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            if (empty($dbwhere)) {

                $resaco = $this->sql_record($this->sql_query_file($o52_funcao));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,5252,'$o52_funcao','E')");
                    $resac = db_query("insert into db_acount values($acount,750,5252,'','" . AddSlashes(pg_result($resaco, $iresaco, 'o52_funcao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,750,5253,'','" . AddSlashes(pg_result($resaco, $iresaco, 'o52_descr')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,750,5254,'','" . AddSlashes(pg_result($resaco, $iresaco, 'o52_codtri')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,750,5255,'','" . AddSlashes(pg_result($resaco, $iresaco, 'o52_finali')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,750,1014659,'','" . AddSlashes(pg_result($resaco, $iresaco, 'o52_siconfi')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " delete from orcfuncao
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($o52_funcao)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " o52_funcao = $o52_funcao ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Funções do orçamento não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $o52_funcao;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Funções do orçamento não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $o52_funcao;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $o52_funcao;
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
            $this->erro_sql = "Record Vazio na Tabela:orcfuncao";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query($o52_funcao = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos}";
        $sql .= "  from orcfuncao ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($o52_funcao)) {
                $sql2 .= " where orcfuncao.o52_funcao = $o52_funcao ";
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

    public function sql_query_file($o52_funcao = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos} ";
        $sql .= "  from orcfuncao ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($o52_funcao)) {
                $sql2 .= " where orcfuncao.o52_funcao = $o52_funcao ";
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
