<?php

use ECidade\Educacao\Secretaria\BNCC\Model\Etapa as EtapaBNCC;
use ECidade\Enum\Educacao\Escola\TipoEnsinoEnum;

class cl_seriebnccetapas
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
    public $ed154_sequencial = 0;
    /**
     * @var int
     */
    public $ed154_serie = 0;
    /**
     * @var int
     */
    public $ed154_bnccetapa = 0;
    public $campos = "ed154_sequencial = int4 = Código
                      ed154_serie = int4 = Etapa e-Cidade
                      ed154_bnccetapa = int4 = Etapa BNCC";

    public function __construct()
    {
        $this->rotulo = new rotulo('seriebnccetapas');
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

    public function incluir($ed154_sequencial)
    {
        if ($this->ed154_sequencial === '' || $this->ed154_sequencial === null) {
            $result = db_query("select nextval('seriebnccetapas_ed154_sequencial_seq')");
            if($result==false){
                $this->erro_banco = str_replace("\n","",@pg_last_error());
                $this->erro_sql   = "Verifique o cadastro da sequencia: seriebnccetapas_ed154_sequencial_seq do campo: ed154_sequencial";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->ed154_sequencial = pg_result($result,0,0);
        }
        if ($this->ed154_serie === '' || $this->ed154_serie === null) {
            $this->erro_sql = " Campo Etapa e-Cidade não informado.";
            $this->erro_campo = "ed154_serie";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed154_bnccetapa === '' || $this->ed154_bnccetapa === null) {
            $this->erro_sql = " Campo Etapa BNCC não informado.";
            $this->erro_campo = "ed154_bnccetapa";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($ed154_sequencial === '' || $ed154_sequencial === null || $ed154_sequencial === 0) {
            $result = db_query("select nextval('seriebnccetapas_ed154_sequencial_seq')");
            if (!$result) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: seriebnccetapas_ed154_sequencial_seq do campo: ed154_sequencial";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->ed154_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("SELECT last_value FROM seriebnccetapas_ed154_sequencial_seq");
            if ($result && pg_result($result, 0, 0) < $ed154_sequencial) {
                $this->erro_sql = " Campo ed154_sequencial maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->ed154_sequencial = $ed154_sequencial;
            }
        }
        if ($this->ed154_sequencial === null || $this->ed154_sequencial === '' || $this->ed154_sequencial === 0) {
            $this->erro_sql = " Campo ed154_sequencial não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = '0';
            return false;
        }
        $sql = "
            INSERT INTO seriebnccetapas (
                ed154_sequencial
                ,ed154_serie
                ,ed154_bnccetapa
            ) VALUES (
                 " . ($this->ed154_sequencial === null || $this->ed154_sequencial === '' ? 'NULL' : $this->ed154_sequencial) . "
                ," . ($this->ed154_serie === null || $this->ed154_serie === '' ? 'NULL' : $this->ed154_serie) . "
                ," . ($this->ed154_bnccetapa === null || $this->ed154_bnccetapa === '' ? 'NULL' : $this->ed154_bnccetapa) . "
            )
        ";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = " () não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = " já cadastrado";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql = " () não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->ed154_sequencial));
            if ($resaco != false || $this->numrows != 0) {
                $resac = db_query("SELECT nextval('db_acount_id_acount_seq') AS acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("INSERT INTO db_acountacesso VALUES ($acount, " . db_getsession("DB_acessado") . ")");
                $resac = db_query("INSERT INTO db_acountkey VALUES ($acount,1010933,'$this->ed154_sequencial','I')");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010507,1010933,'','" . AddSlashes(pg_result($resaco, 0, 'ed154_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010507,1010935,'','" . AddSlashes(pg_result($resaco, 0, 'ed154_serie')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010507,1010934,'','" . AddSlashes(pg_result($resaco, 0, 'ed154_bnccetapa')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
    }

    public function alterar($ed154_sequencial = null)
    {
        $sql = "UPDATE seriebnccetapas SET ";
        $virgula = '';
        if (empty($ed154_sequencial)) {
            throw new Exception('Campo ed154_sequencial é obrigatório!');
        }
        $this->ed154_sequencial = $ed154_sequencial;
        if (trim($this->ed154_serie) !== '' && $this->ed154_serie !== null) {
            $sql .= "{$virgula} ed154_serie = {$this->ed154_serie} ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Etapa e-Cidade" é obrigatório.');
        }
        if (trim($this->ed154_bnccetapa) !== '' && $this->ed154_bnccetapa !== null) {
            $sql .= "{$virgula} ed154_bnccetapa = {$this->ed154_bnccetapa} ";
        } else {
            throw new Exception('Campo "Etapa BNCC" é obrigatório.');
        }

        if ($ed154_sequencial !== '' && $ed154_sequencial !== null && $ed154_sequencial !== 0) {
            $sql .= ' WHERE';
            $sql .= " ed154_sequencial = {$ed154_sequencial}";
        }

        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->ed154_sequencial));
            if ($this->numrows > 0) {

                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1010933,'$this->ed154_sequencial','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed154_sequencial"]) || $this->ed154_sequencial != "")
                        $resac = db_query("insert into db_acount values($acount,1010507,1010933,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed154_sequencial')) . "','$this->ed154_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed154_serie"]) || $this->ed154_serie != "")
                        $resac = db_query("insert into db_acount values($acount,1010507,1010935,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed154_serie')) . "','$this->ed154_serie'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ed154_bnccetapa"]) || $this->ed154_bnccetapa != "")
                        $resac = db_query("insert into db_acount values($acount,1010507,1010934,'" . AddSlashes(pg_result($resaco, $conresaco, 'ed154_bnccetapa')) . "','$this->ed154_bnccetapa'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = " não Alterado. Alteração Abortada.\\n";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = " não foi Alterado. Alteração Executada.\\n";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($ed154_sequencial = null, $dbwhere = null)
    {
        if (empty($ed154_sequencial) && empty($dbwhere)) {
            throw new Exception('Informe o código ou uma condição para excluir.');
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {

            if (empty($dbwhere)) {

                $resaco = $this->sql_record($this->sql_query_file($ed154_sequencial));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,1010933,'$ed154_sequencial','E')");
                    $resac = db_query("insert into db_acount values($acount,1010507,1010933,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed154_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010507,1010935,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed154_serie')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1010507,1010934,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ed154_bnccetapa')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " delete from seriebnccetapas
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed154_sequencial)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " ed154_sequencial = $ed154_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = " não Excluído. Exclusão Abortada.\\n";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = " não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
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
            $this->erro_sql = "Record Vazio na Tabela:seriebnccetapas";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query($ed154_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos}";
        $sql .= "  from seriebnccetapas ";
        $sql .= "      inner join serie  on  serie.ed11_i_codigo = seriebnccetapas.ed154_serie";
        $sql .= "      inner join bnccetapas  on  bnccetapas.ed152_sequencial = seriebnccetapas.ed154_bnccetapa";
        $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = serie.ed11_i_ensino";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed154_sequencial)) {
                $sql2 .= " where seriebnccetapas.ed154_sequencial = $ed154_sequencial ";
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

    public function sql_query_file($ed154_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select {$campos} ";
        $sql .= "  from seriebnccetapas ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($ed154_sequencial)) {
                $sql2 .= " where seriebnccetapas.ed154_sequencial = $ed154_sequencial ";
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


    public function sqlEquivalencia(TipoEnsinoEnum $tipoEnsinoEnum, EtapaBNCC $etapa)
    {
        $sql = "
         select ed10_i_codigo as codigo_ensino,
                trim(ed10_c_descr) as ensino,
                serie.ed11_i_codigo as codigo_etapa,
                trim(serie.ed11_c_descr) as etapa,
                exists(
                select 1 from seriebnccetapas
                 where ed154_serie = ed11_i_codigo
                  and ed154_bnccetapa = {$etapa->getCodigo()}
                ) as equivalente
           from ensino
           join serie ON serie.ed11_i_ensino = ensino.ed10_i_codigo
           where ed10_tipo = {$tipoEnsinoEnum->value()}
           order by ed10_ordem, serie.ed11_i_sequencia
        ";

        return $sql;
    }
}
