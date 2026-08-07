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

use ECidade\RecursosHumanos\Pessoal\Interfaces\PesquisaRubricas;

class cl_rubricasusuario implements PesquisaRubricas
{
    // cria variaveis de erro
    var $rotulo = null;
    var $query_sql = null;
    var $numrows = 0;
    var $numrows_incluir = 0;
    var $numrows_alterar = 0;
    var $numrows_excluir = 0;
    var $erro_status = null;
    var $erro_sql = null;
    var $erro_banco = null;
    var $erro_msg = null;
    var $erro_campo = null;
    var $pagina_retorno = null;
    // cria variaveis do arquivo
    var $rh219_sequencial = 0;
    var $rh219_usuario = 0;
    var $rh219_instituicao = 0;
    var $rh219_rubrica = null;
    // cria propriedade com as variaveis do arquivo
    var $campos = "
                 rh219_sequencial = int4 = Código 
                 rh219_usuario = int4 = Usuário 
                 rh219_instituicao = int4 = Instituição 
                 rh219_rubrica = varchar(4) = Rubrica 
                 ";

    //funcao construtor da classe
    public function __construct()
    {
        //classes dos rotulos dos campos
        $this->rotulo = new rotulo("rubricasusuario");
        $this->pagina_retorno = basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
    }

    //funcao erro
    public function erro($mostra, $retorna)
    {
        if (($this->erro_status == "0") || ($mostra == true && $this->erro_status != null)) {
            echo "<script>alert(\"" . $this->erro_msg . "\");</script>";
            if ($retorna == true) {
                echo "<script>location.href='" . $this->pagina_retorno . "'</script>";
            }
        }
    }

    // funcao para atualizar campos
    public function atualizacampos($exclusao = false)
    {
        if ($exclusao == false) {
            $this->rh219_sequencial = ($this->rh219_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh219_sequencial"] : $this->rh219_sequencial);
            $this->rh219_usuario = ($this->rh219_usuario == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh219_usuario"] : $this->rh219_usuario);
            $this->rh219_instituicao = ($this->rh219_instituicao == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh219_instituicao"] : $this->rh219_instituicao);
            $this->rh219_rubrica = ($this->rh219_rubrica == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh219_rubrica"] : $this->rh219_rubrica);
        } else {
            $this->rh219_sequencial = ($this->rh219_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh219_sequencial"] : $this->rh219_sequencial);
        }
    }

    // funcao para Inclusão
    public function incluir($rh219_sequencial)
    {
        $this->atualizacampos();
        if ($this->rh219_usuario == null) {
            $this->erro_sql = " Campo Usuário não informado.";
            $this->erro_campo = "rh219_usuario";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh219_instituicao == null) {
            $this->erro_sql = " Campo Instituição não informado.";
            $this->erro_campo = "rh219_instituicao";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh219_rubrica == null) {
            $this->erro_sql = " Campo Rubrica não informado.";
            $this->erro_campo = "rh219_rubrica";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($rh219_sequencial == "" || $rh219_sequencial == null) {
            $result = db_query("select nextval('rubricasusuario_rh219_sequencial_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: rubricasusuario_rh219_sequencial_seq do campo: rh219_sequencial";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->rh219_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from rubricasusuario_rh219_sequencial_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $rh219_sequencial)) {
                $this->erro_sql = " Campo rh219_sequencial maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->rh219_sequencial = $rh219_sequencial;
            }
        }
        if (($this->rh219_sequencial == null) || ($this->rh219_sequencial == "")) {
            $this->erro_sql = " Campo rh219_sequencial não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into rubricasusuario(
                                       rh219_sequencial 
                                      ,rh219_usuario 
                                      ,rh219_instituicao 
                                      ,rh219_rubrica 
                       )
                values (
                                $this->rh219_sequencial 
                               ,$this->rh219_usuario 
                               ,$this->rh219_instituicao 
                               ,'$this->rh219_rubrica' 
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = "Rubricas por usuário ($this->rh219_sequencial) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "Rubricas por usuário já Cadastrado";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql = "Rubricas por usuário ($this->rh219_sequencial) não Incluído. Inclusão Abortada.";
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
        $this->erro_sql .= "Valores : " . $this->rh219_sequencial;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "",
            str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->rh219_sequencial));
            if (($resaco != false) || ($this->numrows != 0)) {

                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,1010044,'$this->rh219_sequencial','I')");
                $resac = db_query("insert into db_acount values($acount,1010332,1010044,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'rh219_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010332,1010045,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'rh219_usuario')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010332,1010046,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'rh219_instituicao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010332,1010047,'','" . AddSlashes(pg_result($resaco,
                        0,
                        'rh219_rubrica')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
    }

    // funcao para alteracao
    public function alterar($rh219_sequencial = null)
    {
        $this->atualizacampos();
        $sql = " update rubricasusuario set ";
        $virgula = "";
        if (trim($this->rh219_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh219_sequencial"])) {
            $sql .= $virgula . " rh219_sequencial = $this->rh219_sequencial ";
            $virgula = ",";
            if (trim($this->rh219_sequencial) == null) {
                $this->erro_sql = " Campo Código não informado.";
                $this->erro_campo = "rh219_sequencial";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->rh219_usuario) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh219_usuario"])) {
            $sql .= $virgula . " rh219_usuario = $this->rh219_usuario ";
            $virgula = ",";
            if (trim($this->rh219_usuario) == null) {
                $this->erro_sql = " Campo Usuário não informado.";
                $this->erro_campo = "rh219_usuario";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->rh219_instituicao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh219_instituicao"])) {
            $sql .= $virgula . " rh219_instituicao = $this->rh219_instituicao ";
            $virgula = ",";
            if (trim($this->rh219_instituicao) == null) {
                $this->erro_sql = " Campo Instituição não informado.";
                $this->erro_campo = "rh219_instituicao";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->rh219_rubrica) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh219_rubrica"])) {
            $sql .= $virgula . " rh219_rubrica = '$this->rh219_rubrica' ";
            $virgula = ",";
            if (trim($this->rh219_rubrica) == null) {
                $this->erro_sql = " Campo Rubrica não informado.";
                $this->erro_campo = "rh219_rubrica";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if ($rh219_sequencial != null) {
            $sql .= " rh219_sequencial = $this->rh219_sequencial";
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->rh219_sequencial));
            if ($this->numrows > 0) {

                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1010044,'$this->rh219_sequencial','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh219_sequencial"]) || $this->rh219_sequencial != "") {
                        $resac = db_query("insert into db_acount values($acount,1010332,1010044,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'rh219_sequencial')) . "','$this->rh219_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh219_usuario"]) || $this->rh219_usuario != "") {
                        $resac = db_query("insert into db_acount values($acount,1010332,1010045,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'rh219_usuario')) . "','$this->rh219_usuario'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh219_instituicao"]) || $this->rh219_instituicao != "") {
                        $resac = db_query("insert into db_acount values($acount,1010332,1010046,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'rh219_instituicao')) . "','$this->rh219_instituicao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["rh219_rubrica"]) || $this->rh219_rubrica != "") {
                        $resac = db_query("insert into db_acount values($acount,1010332,1010047,'" . AddSlashes(pg_result($resaco,
                                $conresaco,
                                'rh219_rubrica')) . "','$this->rh219_rubrica'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    }
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Rubricas por usuário não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->rh219_sequencial;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Rubricas por usuário não foi Alterado. Alteração Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->rh219_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $this->rh219_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    // funcao para exclusao
    public function excluir($rh219_sequencial = null, $dbwhere = null)
    {

        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            if (empty($dbwhere)) {

                $resaco = $this->sql_record($this->sql_query_file($rh219_sequencial));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1010044,'$rh219_sequencial','E')");
                    $resac = db_query("insert into db_acount values($acount,1010332,1010044,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'rh219_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010332,1010045,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'rh219_usuario')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010332,1010046,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'rh219_instituicao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010332,1010047,'','" . AddSlashes(pg_result($resaco,
                            $iresaco,
                            'rh219_rubrica')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " delete from rubricasusuario
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($rh219_sequencial)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " rh219_sequencial = $rh219_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Rubricas por usuário não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $rh219_sequencial;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Rubricas por usuário não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $rh219_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $rh219_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "",
                    str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = pg_affected_rows($result);
                return true;
            }
        }
    }

    // funcao do recordset
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
            $this->erro_sql = "Record Vazio na Tabela:rubricasusuario";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "",
                str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    // funcao do sql
    public function sql_query($rh219_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos}";
        $sql .= "  from rubricasusuario ";
        $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = rubricasusuario.rh219_usuario";
        $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = rubricasusuario.rh219_rubrica 
                                          and  rhrubricas.rh27_instit = rubricasusuario.rh219_instituicao";
        $sql .= "      inner join db_config  on  db_config.codigo = rhrubricas.rh27_instit";
        $sql .= "      inner join rhtipomedia  on  rhtipomedia.rh29_tipo = rhrubricas.rh27_calc1";
        $sql .= "      left  join rhfundamentacaolegal  on  rhfundamentacaolegal.rh137_sequencial = rhrubricas.rh27_rhfundamentacaolegal";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($rh219_sequencial)) {
                $sql2 .= " where rubricasusuario.rh219_sequencial = $rh219_sequencial ";
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

    // funcao do sql
    public function sql_query_file($rh219_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos} ";
        $sql .= "  from rubricasusuario ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($rh219_sequencial)) {
                $sql2 .= " where rubricasusuario.rh219_sequencial = $rh219_sequencial ";
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
     * @param string $campos
     * @param array $where
     * @param array $ordem
     * @return string
     */
    public function sqlRubricas($campos = '*', $where = array(), $ordem = array())
    {
        $sql = "
            select {$campos}
            from rubricasusuario 
                 join db_usuarios on db_usuarios.id_usuario = rubricasusuario.rh219_usuario
                 join rhrubricas on rhrubricas.rh27_rubric = rubricasusuario.rh219_rubrica 
                                 and rhrubricas.rh27_instit = rubricasusuario.rh219_instituicao
                 join db_config on db_config.codigo = rhrubricas.rh27_instit
                 join cgm on cgm.z01_numcgm = db_config.numcgm
                 join rhtipomedia on rhtipomedia.rh29_tipo = rhrubricas.rh27_calc1
                 join rhtipomedia b on b.rh29_tipo = rhrubricas.rh27_calc2
                 left join rhfundamentacaolegal on rhfundamentacaolegal.rh137_sequencial = rhrubricas.rh27_rhfundamentacaolegal
                 left join cgm as previdencia_complementar on previdencia_complementar.z01_numcgm = rhrubricas.rh27_previdenciacomplementar
        ";

        if ($where) {
            $where = implode(' AND ', $where);
            $sql .= " WHERE {$where} ";
        }

        if ($ordem) {
            $ordem = implode(', ', $ordem);
            $sql .= " ORDER BY {$ordem} ";
        }

        return $sql;
    }
}
