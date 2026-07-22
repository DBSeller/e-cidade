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

class cl_bncceducacaoinfantil
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
    public $ed147_sequencial = 0;
    public $ed147_disciplina = null;
    public $ed147_faixa_etaria = null;
    public $ed147_codigo = null;
    public $ed147_habilidade = null;
    public $ed147_ano = 0;
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 ed147_sequencial = int4 = ID
                 ed147_disciplina = varchar(100) = Disciplina
                 ed147_faixa_etaria = varchar(100) = Faixa Etaria
                  ed147_codigo = varchar(8) = Código BNCC
                 ed147_habilidade = varchar(255) = Habilidade
                 ed147_ano = int4 = Ano
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("bncceducacaoinfantil");
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
            $this->ed147_sequencial = ($this->ed147_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed147_sequencial"] : $this->ed147_sequencial);
            $this->ed147_disciplina = ($this->ed147_disciplina == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed147_disciplina"] : $this->ed147_disciplina);
            $this->ed147_faixa_etaria = ($this->ed147_faixa_etaria == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed147_faixa_etaria"] : $this->ed147_faixa_etaria);
            $this->ed147_codigo = ($this->ed147_codigo == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed147_codigo"] : $this->ed147_codigo);
            $this->ed147_habilidade = ($this->ed147_habilidade == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed147_habilidade"] : $this->ed147_habilidade);
            $this->ed147_ano = ($this->ed147_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed147_ano"] : $this->ed147_ano);
        } else {
            $this->ed147_sequencial = ($this->ed147_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["ed147_sequencial"] : $this->ed147_sequencial);
        }
    }

    public function incluir($ed147_sequencial)
    {
        $this->atualizacampos();
        if ($this->ed147_disciplina == null) {
            $this->erro_sql = " Campo Disciplina não informado.";
            $this->erro_campo = "ed147_disciplina";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed147_faixa_etaria == null) {
            $this->erro_sql = " Campo Faixa Etaria não informado.";
            $this->erro_campo = "ed147_faixa_etaria";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed147_codigo == null) {
            $this->erro_sql = " Campo Código BNCC não informado.";
            $this->erro_campo = "ed147_codigo";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed147_habilidade == null) {
            $this->erro_sql = " Campo Habilidade não informado.";
            $this->erro_campo = "ed147_habilidade";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed147_ano == null) {
            $this->erro_sql = " Campo Ano não informado.";
            $this->erro_campo = "ed147_ano";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($ed147_sequencial == "" || $ed147_sequencial == null) {
            $result = db_query("select nextval('bncceducacaoinfantil_ed147_sequencial_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: bncceducacaoinfantil_ed147_sequencial_seq do campo: ed147_sequencial";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->ed147_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from bncceducacaoinfantil_ed147_sequencial_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $ed147_sequencial)) {
                $this->erro_sql = " Campo ed147_sequencial maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->ed147_sequencial = $ed147_sequencial;
            }
        }
        if (($this->ed147_sequencial == null) || ($this->ed147_sequencial == "")) {
            $this->erro_sql = " Campo ed147_sequencial não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into bncceducacaoinfantil(
                                       ed147_sequencial
                                      ,ed147_disciplina
                                      ,ed147_faixa_etaria
                                      ,ed147_codigo
                                      ,ed147_habilidade
                                      ,ed147_ano
                       )
                values (
                                $this->ed147_sequencial
                               ,'$this->ed147_disciplina'
                               ,'$this->ed147_faixa_etaria'
                               ,'$this->ed147_codigo'
                               ,'$this->ed147_habilidade'
                               ,$this->ed147_ano
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = " ($this->ed147_sequencial) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = " já Cadastrado";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql = " ($this->ed147_sequencial) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
        $this->erro_sql .= "Valores : " . $this->ed147_sequencial;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->ed147_sequencial));
            if (($resaco != false) || ($this->numrows != 0)) {

                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,1010910,'$this->ed147_sequencial','I')");
                $resac = db_query("insert into db_acount values($acount,1010502,1010910,'','" . AddSlashes(pg_result($resaco, 0, 'ed147_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010502,1010911,'','" . AddSlashes(pg_result($resaco, 0, 'ed147_disciplina')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010502,1010912,'','" . AddSlashes(pg_result($resaco, 0, 'ed147_faixa_etaria')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010502,1010913,'','" . AddSlashes(pg_result($resaco, 0, 'ed147_codigo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010502,1010914,'','" . AddSlashes(pg_result($resaco, 0, 'ed147_habilidade')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1010502,1011782,'','" . AddSlashes(pg_result($resaco, 0, 'ed147_ano')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
    }

    public function alterar($ed147_sequencial = null)
    {
        $this->atualizacampos();
        $sql = " update bncceducacaoinfantil set ";
        $virgula = "";
        if (trim($this->ed147_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed147_sequencial"])) {
            $sql .= $virgula . " ed147_sequencial = $this->ed147_sequencial ";
            $virgula = ",";
            if (trim($this->ed147_sequencial) == null) {
                $this->erro_sql = " Campo ID não informado.";
                $this->erro_campo = "ed147_sequencial";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed147_disciplina) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed147_disciplina"])) {
            $sql .= $virgula . " ed147_disciplina = '$this->ed147_disciplina' ";
            $virgula = ",";
            if (trim($this->ed147_disciplina) == null) {
                $this->erro_sql = " Campo Disciplina não informado.";
                $this->erro_campo = "ed147_disciplina";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed147_faixa_etaria) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed147_faixa_etaria"])) {
            $sql .= $virgula . " ed147_faixa_etaria = '$this->ed147_faixa_etaria' ";
            $virgula = ",";
            if (trim($this->ed147_faixa_etaria) == null) {
                $this->erro_sql = " Campo Faixa Etaria não informado.";
                $this->erro_campo = "ed147_faixa_etaria";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed147_codigo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed147_codigo"])) {
            $sql .= $virgula . " ed147_codigo = '$this->ed147_codigo' ";
            $virgula = ",";
            if (trim($this->ed147_codigo) == null) {
                $this->erro_sql = " Campo Código BNCC não informado.";
                $this->erro_campo = "ed147_codigo";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed147_habilidade) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed147_habilidade"])) {
            $sql .= $virgula . " ed147_habilidade = '$this->ed147_habilidade' ";
            $virgula = ",";
            if (trim($this->ed147_habilidade) == null) {
                $this->erro_sql = " Campo Habilidade não informado.";
                $this->erro_campo = "ed147_habilidade";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ed147_ano) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ed147_ano"])) {
            $sql .= $virgula . " ed147_ano = $this->ed147_ano ";
            $virgula = ",";
            if (trim($this->ed147_ano) == null) {
                $this->erro_sql = " Campo Ano não informado.";
                $this->erro_campo = "ed147_ano";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if ($ed147_sequencial != null) {
            $sql .= " ed147_sequencial = $this->ed147_sequencial";
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->ed147_sequencial));
            if ($this->numrows > 0) {

                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1010910,'$this->ed147_sequencial','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed147_sequencial"]) || $this->ed147_sequencial != "")
                        $resac = db_query("insert into db_acount values($acount,1010502,1010910,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed147_sequencial')) . "','$this->ed147_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed147_disciplina"]) || $this->ed147_disciplina != "")
                        $resac = db_query("insert into db_acount values($acount,1010502,1010911,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed147_disciplina')) . "','$this->ed147_disciplina'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed147_faixa_etaria"]) || $this->ed147_faixa_etaria != "")
                        $resac = db_query("insert into db_acount values($acount,1010502,1010912,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed147_faixa_etaria')) . "','$this->ed147_faixa_etaria'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed147_codigo"]) || $this->ed147_codigo != "")
                        $resac = db_query("insert into db_acount values($acount,1010502,1010913,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed147_codigo')) . "','$this->ed147_codigo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed147_habilidade"]) || $this->ed147_habilidade != "")
                        $resac = db_query("insert into db_acount values($acount,1010502,1010914,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed147_habilidade')) . "','$this->ed147_habilidade'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed147_ano"]) || $this->ed147_ano != "")
                        $resac = db_query("insert into db_acount values($acount,1010502,1011782,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed147_ano')) . "','$this->ed147_ano'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = " não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->ed147_sequencial;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = " não foi Alterado. Alteração Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->ed147_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $this->ed147_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($ed147_sequencial = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            if (empty($dbwhere)) {

                $resaco = $this->sql_record($this->sql_query_file($ed147_sequencial));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1010910,'$ed147_sequencial','E')");
                    $resac = db_query("insert into db_acount values($acount,1010502,1010910,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed147_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010502,1010911,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed147_disciplina')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010502,1010912,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed147_faixa_etaria')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010502,1010913,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed147_codigo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010502,1010914,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed147_habilidade')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010502,1011782,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed147_ano')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " delete from bncceducacaoinfantil
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed147_sequencial)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " ed147_sequencial = $ed147_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = " não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $ed147_sequencial;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = " não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $ed147_sequencial;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $ed147_sequencial;
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
            $this->erro_sql = "Record Vazio na Tabela:bncceducacaoinfantil";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query($ed147_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos}";
        $sql .= "  from bncceducacaoinfantil ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed147_sequencial)) {
                $sql2 .= " where bncceducacaoinfantil.ed147_sequencial = $ed147_sequencial ";
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

    public function sql_query_file($ed147_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos} ";
        $sql .= "  from bncceducacaoinfantil ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed147_sequencial)) {
                $sql2 .= " where bncceducacaoinfantil.ed147_sequencial = $ed147_sequencial ";
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
