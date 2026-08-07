<?php

class cl_empagemovretencoes
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
    public $e145_codigo = 0;
    public $e145_pagordem_id = 0;
    public $e145_movimento_original = 0;
    public $e145_movimento_retencao = 0;
    public $e145_valor_retencao = 0;
    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 e145_codigo = int4 = Código
                 e145_pagordem_id = int4 = Ordem de Compra
                 e145_movimento_original = int4 = Movimento Original
                 e145_movimento_retencao = int4 = Movimento Retenção
                 e145_valor_retencao = float4 = Valor total da retenção
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("empagemovretencoes");
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
            $this->e145_codigo = ($this->e145_codigo == "" ? @$GLOBALS["HTTP_POST_VARS"]["e145_codigo"] : $this->e145_codigo);
            $this->e145_pagordem_id = ($this->e145_pagordem_id == "" ? @$GLOBALS["HTTP_POST_VARS"]["e145_pagordem_id"] : $this->e145_pagordem_id);
            $this->e145_movimento_original = ($this->e145_movimento_original == "" ? @$GLOBALS["HTTP_POST_VARS"]["e145_movimento_original"] : $this->e145_movimento_original);
            $this->e145_movimento_retencao = ($this->e145_movimento_retencao == "" ? @$GLOBALS["HTTP_POST_VARS"]["e145_movimento_retencao"] : $this->e145_movimento_retencao);
            $this->e145_valor_retencao = ($this->e145_valor_retencao == "" ? @$GLOBALS["HTTP_POST_VARS"]["e145_valor_retencao"] : $this->e145_valor_retencao);
        } else {
            $this->e145_codigo = ($this->e145_codigo == "" ? @$GLOBALS["HTTP_POST_VARS"]["e145_codigo"] : $this->e145_codigo);
        }
    }

    public function incluir($e145_codigo = null)
    {
        $this->atualizacampos();
        if ($this->e145_pagordem_id == null) {
            $this->erro_sql = " Campo Ordem de Compra não informado.";
            $this->erro_campo = "e145_pagordem_id";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->e145_movimento_original == null) {
            $this->erro_sql = " Campo Movimento Original não informado.";
            $this->erro_campo = "e145_movimento_original";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->e145_movimento_retencao == null) {
            $this->erro_sql = " Campo Movimento Retenção não informado.";
            $this->erro_campo = "e145_movimento_retencao";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->e145_valor_retencao == null) {
            $this->erro_sql = " Campo Valor total da retenção não informado.";
            $this->erro_campo = "e145_valor_retencao";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($e145_codigo == "" || $e145_codigo == null) {
            $result = db_query("select nextval('empagemovretencoes_e145_codigo_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: empagemovretencoes_e145_codigo_seq do campo: e145_codigo";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->e145_codigo = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from empagemovretencoes_e145_codigo_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $e145_codigo)) {
                $this->erro_sql = " Campo e145_codigo maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->e145_codigo = $e145_codigo;
            }
        }
        if (($this->e145_codigo == null) || ($this->e145_codigo == "")) {
            $this->erro_sql = " Campo e145_codigo não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into empagemovretencoes(
                                       e145_codigo
                                      ,e145_pagordem_id
                                      ,e145_movimento_original
                                      ,e145_movimento_retencao
                                      ,e145_valor_retencao
                       )
                values (
                                $this->e145_codigo
                               ,$this->e145_pagordem_id
                               ,$this->e145_movimento_original
                               ,$this->e145_movimento_retencao
                               ,$this->e145_valor_retencao
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = "Movimento Retenções ($this->e145_codigo) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "Movimento Retenções já Cadastrado";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql = "Movimento Retenções ($this->e145_codigo) não Incluído. Inclusão Abortada.";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
        $this->erro_sql .= "Valores : " . $this->e145_codigo;
        $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);

        return true;
    }

    public function alterar($e145_codigo = null)
    {
        $this->atualizacampos();
        $sql = " update empagemovretencoes set ";
        $virgula = "";
        if (trim($this->e145_codigo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e145_codigo"])) {
            $sql .= $virgula . " e145_codigo = $this->e145_codigo ";
            $virgula = ",";
            if (trim($this->e145_codigo) == null) {
                $this->erro_sql = " Campo Código não informado.";
                $this->erro_campo = "e145_codigo";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->e145_pagordem_id) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e145_pagordem_id"])) {
            $sql .= $virgula . " e145_pagordem_id = $this->e145_pagordem_id ";
            $virgula = ",";
            if (trim($this->e145_pagordem_id) == null) {
                $this->erro_sql = " Campo Ordem de Compra não informado.";
                $this->erro_campo = "e145_pagordem_id";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->e145_movimento_original) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e145_movimento_original"])) {
            $sql .= $virgula . " e145_movimento_original = $this->e145_movimento_original ";
            $virgula = ",";
            if (trim($this->e145_movimento_original) == null) {
                $this->erro_sql = " Campo Movimento Original não informado.";
                $this->erro_campo = "e145_movimento_original";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->e145_movimento_retencao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e145_movimento_retencao"])) {
            $sql .= $virgula . " e145_movimento_retencao = $this->e145_movimento_retencao ";
            $virgula = ",";
            if (trim($this->e145_movimento_retencao) == null) {
                $this->erro_sql = " Campo Movimento Retenção não informado.";
                $this->erro_campo = "e145_movimento_retencao";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->e145_valor_retencao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e145_valor_retencao"])) {
            $sql .= $virgula . " e145_valor_retencao = $this->e145_valor_retencao ";
            $virgula = ",";
            if (trim($this->e145_valor_retencao) == null) {
                $this->erro_sql = " Campo Valor total da retenção não informado.";
                $this->erro_campo = "e145_valor_retencao";
                $this->erro_banco = "";
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if ($e145_codigo != null) {
            $sql .= " e145_codigo = $this->e145_codigo";
        }

        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Movimento Retenções não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->e145_codigo;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Movimento Retenções não foi Alterado. Alteração Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->e145_codigo;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $this->e145_codigo;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($e145_codigo = null, $dbwhere = null)
    {
        $sql = " delete from empagemovretencoes where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($e145_codigo)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " e145_codigo = $e145_codigo ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Movimento Retenções não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $e145_codigo;
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Movimento Retenções não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $e145_codigo;
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : " . $e145_codigo;
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
            $this->erro_sql = "Record Vazio na Tabela:empagemovretencoes";
            $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query($e145_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "
        select {$campos}
          from empagemovretencoes
           inner join pagordem on pagordem.e50_codord = empagemovretencoes.e145_pagordem_id
           inner join empagemov as original on original.e81_codmov = empagemovretencoes.e145_movimento_original
           inner join empagemov as retencao on retencao.e81_codmov = empagemovretencoes.e145_movimento_retencao
           inner join db_usuarios on db_usuarios.id_usuario = pagordem.e50_id_usuario
           inner join empempenho on empempenho.e60_numemp = pagordem.e50_numemp
           inner join empage as a on a.e80_codage = empagemov.e81_codage
        ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($e145_codigo)) {
                $sql2 .= " where empagemovretencoes.e145_codigo = $e145_codigo ";
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

    public function sql_query_file($e145_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select {$campos} ";
        $sql .= "  from empagemovretencoes ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($e145_codigo)) {
                $sql2 .= " where empagemovretencoes.e145_codigo = $e145_codigo ";
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
