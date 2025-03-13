<?php

class cl_andamentoemppreautorizacao
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
    public $id = 0;
    public $empautoriza_id = 0;
    public $status_id = 0;
    public $observacao = null;
    public $id_usuario = 0;
    public $data_dia = null;
    public $data_mes = null;
    public $data_ano = null;
    public $data = null;
   // cria propriedade com as variaveis do arquivo
    public $campos = "
                 id = int8 = id
                 empautoriza_id = int4 = IdAutorizacao
                 status_id = int4 = Status
                 observacao = varchar(200) = Observação
                 id_usuario = int4 = Código do Usuário
                 data = date = Data
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("andamentoemppreautorizacao");
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
            $this->id = ($this->id == ""?@$GLOBALS["HTTP_POST_VARS"]["id"]:$this->id);
            $this->empautoriza_id = ($this->empautoriza_id == ""?@$GLOBALS["HTTP_POST_VARS"]["empautoriza_id"]:$this->empautoriza_id);
            $this->status_id = ($this->status_id == ""?@$GLOBALS["HTTP_POST_VARS"]["status_id"]:$this->status_id);
            $this->observacao = ($this->observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["observacao"]:$this->observacao);
            $this->id_usuario = ($this->id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["id_usuario"]:$this->id_usuario);
            if ($this->data == "") {
                $this->data_dia = ($this->data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["data_dia"]:$this->data_dia);
                $this->data_mes = ($this->data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["data_mes"]:$this->data_mes);
                $this->data_ano = ($this->data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["data_ano"]:$this->data_ano);
                if ($this->data_dia != "") {
                    $this->data = $this->data_ano."-".$this->data_mes."-".$this->data_dia;
                }
            }
        } else {
            $this->id = ($this->id == ""?@$GLOBALS["HTTP_POST_VARS"]["id"]:$this->id);
        }
    }

    public function incluir($id = null)
    {
        $this->atualizacampos();
        if ($this->empautoriza_id == null) {
            $this->erro_sql = " Campo IdAutorizacao não informado.";
            $this->erro_campo = "empautoriza_id";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->status_id == null) {
            $this->erro_sql = " Campo Status não informado.";
            $this->erro_campo = "status_id";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->id_usuario == null) {
            $this->erro_sql = " Campo Código do Usuário não informado.";
            $this->erro_campo = "id_usuario";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->data == null) {
            $this->erro_sql = " Campo Data não informado.";
            $this->erro_campo = "data_dia";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }

        $this->id = db_utils::fieldsMemory(db_query("select nextval('andamentoemppreautorizacao_id_seq') as id"), 0)->id;
        if (($this->id == null) || ($this->id == "")) {
            $this->erro_sql = " Campo id não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into andamentoemppreautorizacao(
                                       id
                                      ,empautoriza_id
                                      ,status_id
                                      ,observacao
                                      ,id_usuario
                                      ,data
                       )
                values (
                                $this->id
                               ,$this->empautoriza_id
                               ,$this->status_id
                               ,'$this->observacao'
                               ,$this->id_usuario
                               ,".($this->data == "null" || $this->data == ""?"null":"'".$this->data."'")."
                      )";
        $result = db_query($sql);
        if ($result==false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql   = " ($this->id) não Incluído. Inclusão Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_banco = " já Cadastrado";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            } else {
                $this->erro_sql   = " ($this->id) não Incluído. Inclusão Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir= 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->id;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir= pg_affected_rows($result);

        return true;
    }

    public function alterar($id = null)
    {
        $this->atualizacampos();
        $sql = " update andamentoemppreautorizacao set ";
        $virgula = "";
        if (trim($this->id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["id"])) {
            $sql  .= $virgula." id = $this->id ";
            $virgula = ",";
            if (trim($this->id) == null) {
                $this->erro_sql = " Campo id não informado.";
                $this->erro_campo = "id";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->empautoriza_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["empautoriza_id"])) {
            $sql  .= $virgula." empautoriza_id = $this->empautoriza_id ";
            $virgula = ",";
            if (trim($this->empautoriza_id) == null) {
                $this->erro_sql = " Campo IdAutorizacao não informado.";
                $this->erro_campo = "empautoriza_id";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->status_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["status_id"])) {
            $sql  .= $virgula." status_id = $this->status_id ";
            $virgula = ",";
            if (trim($this->status_id) == null) {
                $this->erro_sql = " Campo Status não informado.";
                $this->erro_campo = "status_id";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["observacao"])) {
            $sql  .= $virgula." observacao = '$this->observacao' ";
            $virgula = ",";
        }
        if (trim($this->id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["id_usuario"])) {
            $sql  .= $virgula." id_usuario = $this->id_usuario ";
            $virgula = ",";
            if (trim($this->id_usuario) == null) {
                $this->erro_sql = " Campo Código do Usuário não informado.";
                $this->erro_campo = "id_usuario";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["data_dia"] !="")) {
            $sql  .= $virgula." data = '$this->data' ";
            $virgula = ",";
            if (trim($this->data) == null) {
                $this->erro_sql = " Campo Data não informado.";
                $this->erro_campo = "data_dia";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        } else {
            if (isset($GLOBALS["HTTP_POST_VARS"]["data_dia"])) {
                $sql  .= $virgula." data = null ";
                $virgula = ",";
                if (trim($this->data) == null) {
                      $this->erro_sql = " Campo Data não informado.";
                      $this->erro_campo = "data_dia";
                      $this->erro_banco = "";
                      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                      $this->erro_status = "0";
                      return false;
                }
            }
        }
        $sql .= " where ";
        if ($id!=null) {
            $sql .= " id = $this->id";
        }

        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = " não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : ".$this->id;
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = " não foi Alterado. Alteração Executada.\\n";
                $this->erro_sql .= "Valores : ".$this->id;
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : ".$this->id;
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($id = null, $dbwhere = null)
    {
        $sql = " delete from andamentoemppreautorizacao
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($id)) {
                if (!empty($sql2)) {
                     $sql2 .= " and ";
                }
                $sql2 .= " id = $id ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql.$sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = " não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : ".$id;
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = " não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : ".$id;
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : ".$id;
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
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
            $this->erro_sql   = "Record Vazio na Tabela:andamentoemppreautorizacao";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query($id = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql  = "select {$campos}";
        $sql .= "  from andamentoemppreautorizacao ";
        $sql .= "      inner join empautoriza  on  empautoriza.e54_autori = andamentoemppreautorizacao.empautoriza_id";
        $sql .= "      inner join andamentoemppreautorizacaostatus  on  andamentoemppreautorizacaostatus.id = andamentoemppreautorizacao.status_id";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = empautoriza.e54_numcgm";
        $sql .= "      inner join db_config  on  db_config.codigo = empautoriza.e54_instit";
        $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = empautoriza.e54_login";
        $sql .= "      inner join db_depart  on  db_depart.coddepto = empautoriza.e54_depto";
        $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empautoriza.e54_codcom";
        $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = empautoriza.e54_concarpeculiar";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($id)) {
                $sql2 .= " where andamentoemppreautorizacao.id = $id ";
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

    public function sql_query_file($id = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql  = "select {$campos} ";
        $sql .= "  from andamentoemppreautorizacao ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($id)) {
                $sql2 .= " where andamentoemppreautorizacao.id = $id ";
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

    public function sqlUltimoAndamentoAutorizacao()
    {

        $sql = "select id from
                 (select id,
                         empautoriza_id,
                         row_number() OVER(PARTITION BY least(empautoriza_id) ORDER BY id DESC) as row
                    from andamentoemppreautorizacao
                ) as t1 where row = 1";

        return $sql;
    }

    public function ultimoStatusAndamentoAutorizacao($empautoriza_id)
    {
        $where = "empautoriza_id  = ".$empautoriza_id;
        $order = "id DESC";
        $sqlUltimoStatus = $this->sql_query_file(null, "status_id", $order, $where);
        $rsUltimoStatus = db_query($sqlUltimoStatus);
        return db_utils::fieldsMemory($rsUltimoStatus, 0)->status_id;
    }

    public function travaAutorizacaoAndamento($empautoriza_id)
    {

        $ultimoStatus = $this->ultimoStatusAndamentoAutorizacao($empautoriza_id);
        // removido status 3 , o 3 é autorizado: status
        /**
         *  id |        status        | descricao
         * ----+----------------------+-----------
         *   1 | Aguardando Liberação |
         *   2 | Em Análise           |
         *   3 | Autorizado           |
         *   4 | Não Autorizado       |
         *   5 | Revisar / Pendências |
         */
        $statusTrava = ['2'];
        if (!empty($ultimoStatus)) {
            if (in_array($ultimoStatus, $statusTrava)) {
                return true;
            }
        }
        return false;
    }

    public function existeAndamentoAutorizacao($empautoriza_id)
    {
        $sql     = "select id ";
        $sql    .= "from andamentoemppreautorizacao ";
        $sql    .= "where empautoriza_id = $empautoriza_id ";

        $rs = db_query($sql);
        if (pg_num_rows($rs) > 0) {
            return true;
        }
        return false;
    }

    public function existeStatusAndamentoAutorizacao($empautoriza_id, $status_id)
    {
        $sql     = "select id ";
        $sql    .= "from andamentoemppreautorizacao ";
        $sql    .= "where empautoriza_id = ".$empautoriza_id;
        $sql    .= " and status_id = .$status_id ";

        $rs = db_query($sql);
        if (pg_num_rows($rs) > 0) {
            return true;
        }
        return false;
    }
}
