<?php

class cl_empempenhooutrosdados
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
    public $e171_numdadosemp = 0;
    public $e171_numemp = 0;
    public $e171_dados = null;
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 e171_numdadosemp = int4 = Sequencial dos outros dados do empenho
                 e171_numemp = int4 = sequencial do empenho
                 e171_dados = text = Outros dados relacionados ao empenho
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("empempenhooutrosdados");
        $this->pagina_retorno = basename($_SERVER['PHP_SELF']);
    }

    public function erro($mostra, $retorna)
    {
        if (($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )) {
            echo "<script>alert(\"".$this->erro_msg."\")</script>";
            if ($retorna==true) {
                echo "<script>location.href='".$this->pagina_retorno."'</script>";
            }
        }
    }

    public function atualizacampos($exclusao = false)
    {
        if ($exclusao==false) {
            $this->e171_numdadosemp = ($this->e171_numdadosemp == ""?@$GLOBALS["HTTP_POST_VARS"]["e171_numdadosemp"]:$this->e171_numdadosemp);
            $this->e171_numemp = ($this->e171_numemp == ""?@$GLOBALS["HTTP_POST_VARS"]["e171_numemp"]:$this->e171_numemp);
            $this->e171_dados = ($this->e171_dados == ""?@$GLOBALS["HTTP_POST_VARS"]["e171_dados"]:$this->e171_dados);
        } else {
            $this->e171_numdadosemp = ($this->e171_numdadosemp == ""?@$GLOBALS["HTTP_POST_VARS"]["e171_numdadosemp"]:$this->e171_numdadosemp);
        }
    }

    public function incluir($e171_numdadosemp = null)
    {
        $this->atualizacampos();
        if ($this->e171_numemp == null) {
            $this->erro_sql = " Campo sequencial do empenho não informado.";
            $this->erro_campo = "e171_numemp";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($e171_numdadosemp == "" || $e171_numdadosemp == null) {
            $result = db_query("select nextval('empempenhooutrosdados_e171_numdadosemp_seq')");
            if ($result==false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql   = "Verifique o cadastro da sequencia: empempenhooutrosdados_e171_numdadosemp_seq do campo: e171_numdadosemp";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->e171_numdadosemp = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from empempenhooutrosdados_e171_numdadosemp_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $e171_numdadosemp)) {
                $this->erro_sql = " Campo e171_numdadosemp maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->e171_numdadosemp = $e171_numdadosemp;
            }
        }
        if (($this->e171_numdadosemp == null) || ($this->e171_numdadosemp == "")) {
            $this->erro_sql = " Campo e171_numdadosemp não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into empempenhooutrosdados(
                                       e171_numdadosemp
                                      ,e171_numemp
                                      ,e171_dados
                       )
                values (
                                $this->e171_numdadosemp
                               ,$this->e171_numemp
                               ,'$this->e171_dados'
                      );";
        $result = db_query($sql);

        if ($result==false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql   = "Outros dados relacionados ao empenho ($this->e171_numdadosemp) não Incluído. Inclusão Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_banco = "Outros dados relacionados ao empenho já Cadastrado";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            } else {
                $this->erro_sql   = "Outros dados relacionados ao empenho ($this->e171_numdadosemp) não Incluído. Inclusão Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir= 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
        $this->erro_sql .= "Valores : ".$this->e171_numdadosemp;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir= pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {
            $resaco = $this->sql_record($this->sql_query_file($this->e171_numdadosemp));
            if (($resaco!=false)||($this->numrows!=0)) {
                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
                $resac = db_query("insert into db_acountkey values($acount,1013848,'$this->e171_numdadosemp','I')");
                $resac = db_query("insert into db_acount values($acount,1010877,1013848,'','".AddSlashes(pg_result($resaco, 0, 'e171_numdadosemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010877,1013842,'','".AddSlashes(pg_result($resaco, 0, 'e171_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                $resac = db_query("insert into db_acount values($acount,1010877,1013845,'','".AddSlashes(pg_result($resaco, 0, 'e171_dados'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
            }
        }
        return true;
    }

    public function alterar($e171_numdadosemp = null, $e171_numemp = null)
    {
        $this->atualizacampos();
        $sql = " update empempenhooutrosdados set ";
        $virgula = "";
        if (trim($this->e171_numdadosemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e171_numdadosemp"])) {
            $sql  .= $virgula." e171_numdadosemp = $this->e171_numdadosemp ";
            $virgula = ",";
            if (trim($this->e171_numdadosemp) == null) {
                $this->erro_sql = " Campo Sequencial dos outros dados do empenho não informado.";
                $this->erro_campo = "e171_numdadosemp";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->e171_numemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e171_numemp"])) {
            $sql  .= $virgula." e171_numemp = $this->e171_numemp ";
            $virgula = ",";
            if (trim($this->e171_numemp) == null) {
                $this->erro_sql = " Campo sequencial do empenho não informado.";
                $this->erro_campo = "e171_numemp";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->e171_dados)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e171_dados"])) {
            $sql  .= $virgula." e171_dados = '$this->e171_dados' ";
            $virgula = ",";
        }
        $sql .= " where ";
        if ($e171_numdadosemp!=null) {
            $sql .= " e171_numdadosemp = $this->e171_numdadosemp";
        } else {
            $sql .= " e171_numemp = $e171_numemp;";
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {
            $resaco = $this->sql_record($this->sql_query_file($this->e171_numdadosemp));
            if ($this->numrows > 0) {
                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
                    $resac = db_query("insert into db_acountkey values($acount,1013848,'$this->e171_numdadosemp','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["e171_numdadosemp"]) || $this->e171_numdadosemp != "") {
                        $resac = db_query("insert into db_acount values($acount,1010877,1013848,'".AddSlashes(pg_result($resaco, $conresaco, 'e171_numdadosemp'))."','$this->e171_numdadosemp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["e171_numemp"]) || $this->e171_numemp != "") {
                        $resac = db_query("insert into db_acount values($acount,1010877,1013842,'".AddSlashes(pg_result($resaco, $conresaco, 'e171_numemp'))."','$this->e171_numemp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    }
                    if (isset($GLOBALS["HTTP_POST_VARS"]["e171_dados"]) || $this->e171_dados != "") {
                        $resac = db_query("insert into db_acount values($acount,1010877,1013845,'".AddSlashes(pg_result($resaco, $conresaco, 'e171_dados'))."','$this->e171_dados',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    }
                }
            }
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "Outros dados relacionados ao empenho não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : ".$this->e171_numdadosemp;
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Outros dados relacionados ao empenho não foi Alterado. Alteração Executada.\\n";
                $this->erro_sql .= "Valores : ".$this->e171_numdadosemp;
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : ".$this->e171_numdadosemp;
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($e171_numdadosemp = null, $dbwhere = null)
    {
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))) {
            if (empty($dbwhere)) {
                $resaco = $this->sql_record($this->sql_query_file($e171_numdadosemp));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows!=0)) {
                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
                    $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
                    $resac  = db_query("insert into db_acountkey values($acount,1013848,'$e171_numdadosemp','E')");
                    $resac  = db_query("insert into db_acount values($acount,1010877,1013848,'','".AddSlashes(pg_result($resaco, $iresaco, 'e171_numdadosemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,1010877,1013842,'','".AddSlashes(pg_result($resaco, $iresaco, 'e171_numemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                    $resac  = db_query("insert into db_acount values($acount,1010877,1013845,'','".AddSlashes(pg_result($resaco, $iresaco, 'e171_dados'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
                }
            }
        }
        $sql = " delete from empempenhooutrosdados
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($e171_numdadosemp)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " e171_numdadosemp = $e171_numdadosemp ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql.$sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "Outros dados relacionados ao empenho não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : ".$e171_numdadosemp;
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Outros dados relacionados ao empenho não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : ".$e171_numdadosemp;
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : ".$e171_numdadosemp;
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function existe($numemp = null){
        $retorno = false;
        if ($numemp) {
            $sql = "SELECT * from empempenhooutrosdados where e171_numemp = {$numemp}";

            $rsExists = db_query($sql);
            if($rsExists == false){
                Throw new Exception("Erro ao consultar se existe outros dados relacionados ao empenho");
            }
            if (pg_num_rows($rsExists) > 0){
                $retorno = true;
            }
        }
        return $retorno;
    }

    public function sql_record($sql)
    {
        $result = db_query($sql);
        if (!$result) {
            $this->numrows    = 0;
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "Erro ao selecionar os registros.";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        $this->numrows = pg_num_rows($result);
        if ($this->numrows == 0) {
            $this->erro_banco = "";
            $this->erro_sql   = "Record Vazio na Tabela:empempenhooutrosdados";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query($e171_numdadosemp = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql  = "select {$campos}";
        $sql .= "  from empempenhooutrosdados ";
        $sql .= "      inner join empempenho  on  empempenho.e60_numemp = empempenhooutrosdados.e171_numemp";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
        $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
        $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
        $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
        $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
        $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = empempenho.e60_concarpeculiar";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($e171_numdadosemp)) {
                $sql2 .= " where empempenhooutrosdados.e171_numdadosemp = $e171_numdadosemp ";
            }
        } elseif (!empty($dbwhere)) {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if (!empty($ordem)) {
            $sql .= " order by {$ordem}";
        }
        return $sql;
    }

    public function sql_query_file($e171_numdadosemp = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql  = "select {$campos} ";
        $sql .= "  from empempenhooutrosdados ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($e171_numdadosemp)) {
                $sql2 .= " where empempenhooutrosdados.e171_numdadosemp = $e171_numdadosemp ";
            }
        } elseif (!empty($dbwhere)) {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if (!empty($ordem)) {
            $sql .= " order by {$ordem}";
        }
        return $sql;
    }
}
