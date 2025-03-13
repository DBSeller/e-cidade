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

class cl_pctipocompra
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
    public $pc50_codcom = 0;
    public $pc50_descr = null;
    public $pc50_pctipocompratribunal = 0;
    public $pc50_ativo = 'f';
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 pc50_codcom = int4 = Tipo de Compra
                 pc50_descr = varchar(50) = Descrição do Tipo de Compra
                 pc50_pctipocompratribunal = int4 = Código Tribunal
                 pc50_ativo = bool = Ativo
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("pctipocompra");
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
            $this->pc50_codcom = ($this->pc50_codcom == "" ? @$GLOBALS["HTTP_POST_VARS"]["pc50_codcom"] : $this->pc50_codcom);
            $this->pc50_descr = ($this->pc50_descr == "" ? @$GLOBALS["HTTP_POST_VARS"]["pc50_descr"] : $this->pc50_descr);
            $this->pc50_pctipocompratribunal = ($this->pc50_pctipocompratribunal == "" ? @$GLOBALS["HTTP_POST_VARS"]["pc50_pctipocompratribunal"] : $this->pc50_pctipocompratribunal);
            $this->pc50_ativo = ($this->pc50_ativo == "f" ? @$GLOBALS["HTTP_POST_VARS"]["pc50_ativo"] : $this->pc50_ativo);
        } else {
            $this->pc50_codcom = ($this->pc50_codcom == "" ? @$GLOBALS["HTTP_POST_VARS"]["pc50_codcom"] : $this->pc50_codcom);
        }
    }

    public function incluir($pc50_codcom)
    {
        $this->atualizacampos();
        if ($this->pc50_descr == null) {
            $this->erro_sql = " Campo Descrição do Tipo de Compra não informado.";
            $this->erro_campo = "pc50_descr";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->pc50_pctipocompratribunal == null) {
            $this->erro_sql = " Campo Código Tribunal não informado.";
            $this->erro_campo = "pc50_pctipocompratribunal";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if (empty($this->pc50_ativo)) {
            $this->pc50_ativo = 'f';
        }

        $this->pc50_codcom = $pc50_codcom;
        if (($this->pc50_codcom == null) || ($this->pc50_codcom == "")) {
            $this->erro_sql = " Campo pc50_codcom não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into pctipocompra(
                                       pc50_codcom
                                      ,pc50_descr
                                      ,pc50_pctipocompratribunal
                                      ,pc50_ativo
                       )
                values (
                                $this->pc50_codcom
                               ,'$this->pc50_descr'
                               ,$this->pc50_pctipocompratribunal
                               ,'$this->pc50_ativo'
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = "Tipos de Compras ($this->pc50_codcom) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "Tipos de Compras já Cadastrado";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql = "Tipos de Compras ($this->pc50_codcom) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
        $this->erro_sql .= "Valores : " . $this->pc50_codcom;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->pc50_codcom));
            if (($resaco != false) || ($this->numrows != 0)) {

                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,5526,'$this->pc50_codcom','I')");
                $resac = db_query("insert into db_acount values($acount,866,5526,'','" . AddSlashes(pg_result($resaco, 0, 'pc50_codcom')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,866,5527,'','" . AddSlashes(pg_result($resaco, 0, 'pc50_descr')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,866,17817,'','" . AddSlashes(pg_result($resaco, 0, 'pc50_pctipocompratribunal')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,866,1011942,'','" . AddSlashes(pg_result($resaco, 0, 'pc50_ativo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
    }

    public function alterar($pc50_codcom = null)
    {
        $this->atualizacampos();
        $sql = " update pctipocompra set ";
        $virgula = "";
        if (trim($this->pc50_codcom) != "" || isset($GLOBALS["HTTP_POST_VARS"]["pc50_codcom"])) {
            $sql .= $virgula . " pc50_codcom = $this->pc50_codcom ";
            $virgula = ",";
            if (trim($this->pc50_codcom) == null) {
                $this->erro_sql = " Campo Tipo de Compra não informado.";
                $this->erro_campo = "pc50_codcom";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->pc50_descr) != "" || isset($GLOBALS["HTTP_POST_VARS"]["pc50_descr"])) {
            $sql .= $virgula . " pc50_descr = '$this->pc50_descr' ";
            $virgula = ",";
            if (trim($this->pc50_descr) == null) {
                $this->erro_sql = " Campo Descrição do Tipo de Compra não informado.";
                $this->erro_campo = "pc50_descr";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->pc50_pctipocompratribunal) != "" || isset($GLOBALS["HTTP_POST_VARS"]["pc50_pctipocompratribunal"])) {
            $sql .= $virgula . " pc50_pctipocompratribunal = $this->pc50_pctipocompratribunal ";
            $virgula = ",";
            if (trim($this->pc50_pctipocompratribunal) == null) {
                $this->erro_sql = " Campo Código Tribunal não informado.";
                $this->erro_campo = "pc50_pctipocompratribunal";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->pc50_ativo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["pc50_ativo"])) {
            $sql .= $virgula . " pc50_ativo = '$this->pc50_ativo' ";
            $virgula = ",";
            if (trim($this->pc50_ativo) == null) {
                $this->erro_sql = " Campo Ativo não informado.";
                $this->erro_campo = "pc50_ativo";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if ($pc50_codcom != null) {
            $sql .= " pc50_codcom = $this->pc50_codcom";
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->pc50_codcom));
            if ($this->numrows > 0) {

                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,5526,'$this->pc50_codcom','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["pc50_codcom"]) || $this->pc50_codcom != "")
                        $resac = db_query("insert into db_acount values($acount,866,5526,'" . AddSlashes(pg_result($resaco, $conresaco, 'pc50_codcom')) . "','$this->pc50_codcom'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["pc50_descr"]) || $this->pc50_descr != "")
                        $resac = db_query("insert into db_acount values($acount,866,5527,'" . AddSlashes(pg_result($resaco, $conresaco, 'pc50_descr')) . "','$this->pc50_descr'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["pc50_pctipocompratribunal"]) || $this->pc50_pctipocompratribunal != "")
                        $resac = db_query("insert into db_acount values($acount,866,17817,'" . AddSlashes(pg_result($resaco, $conresaco, 'pc50_pctipocompratribunal')) . "','$this->pc50_pctipocompratribunal'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["pc50_ativo"]) || $this->pc50_ativo != "")
                        $resac = db_query("insert into db_acount values($acount,866,1011942,'" . AddSlashes(pg_result($resaco, $conresaco, 'pc50_ativo')) . "','$this->pc50_ativo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Tipos de Compras não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->pc50_codcom;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Tipos de Compras não foi Alterado. Alteração Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->pc50_codcom;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $this->pc50_codcom;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($pc50_codcom = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            if (empty($dbwhere)) {

                $resaco = $this->sql_record($this->sql_query_file($pc50_codcom));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,5526,'$pc50_codcom','E')");
                    $resac = db_query("insert into db_acount values($acount,866,5526,'','" . AddSlashes(pg_result($resaco, $iresaco, 'pc50_codcom')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,866,5527,'','" . AddSlashes(pg_result($resaco, $iresaco, 'pc50_descr')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,866,17817,'','" . AddSlashes(pg_result($resaco, $iresaco, 'pc50_pctipocompratribunal')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,866,1011942,'','" . AddSlashes(pg_result($resaco, $iresaco, 'pc50_ativo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " delete from pctipocompra
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($pc50_codcom)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " pc50_codcom = $pc50_codcom ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Tipos de Compras não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $pc50_codcom;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Tipos de Compras não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $pc50_codcom;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $pc50_codcom;
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
            $this->erro_sql = "Record Vazio na Tabela:pctipocompra";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query($pc50_codcom = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos}";
        $sql .= "  from pctipocompra ";
        $sql .= "      inner join pctipocompratribunal  on  pctipocompratribunal.l44_sequencial = pctipocompra.pc50_pctipocompratribunal";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($pc50_codcom)) {
                $sql2 .= " where pctipocompra.pc50_codcom = $pc50_codcom ";
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

    public function sql_query_file($pc50_codcom = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos} ";
        $sql .= "  from pctipocompra ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($pc50_codcom)) {
                $sql2 .= " where pctipocompra.pc50_codcom = $pc50_codcom ";
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
