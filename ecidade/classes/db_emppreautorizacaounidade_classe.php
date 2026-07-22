<?php

class cl_emppreautorizacaounidade
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
    public $exercicio = 0;
    public $orgao_id = 0;
    public $unidade_id = 0;
   // cria propriedade com as variaveis do arquivo
    public $campos = "
                 exercicio = int4 = Exercício 
                 orgao_id = int4 = Id do Orgao 
                 unidade_id = int4 = Id da unidade 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("emppreautorizacaounidade");
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
            $this->exercicio = ($this->exercicio == ""?@$GLOBALS["HTTP_POST_VARS"]["exercicio"]:$this->exercicio);
            $this->orgao_id = ($this->orgao_id == ""?@$GLOBALS["HTTP_POST_VARS"]["orgao_id"]:$this->orgao_id);
            $this->unidade_id = ($this->unidade_id == ""?@$GLOBALS["HTTP_POST_VARS"]["unidade_id"]:$this->unidade_id);
        } else {
            $this->exercicio = ($this->exercicio == ""?@$GLOBALS["HTTP_POST_VARS"]["exercicio"]:$this->exercicio);
            $this->orgao_id = ($this->orgao_id == ""?@$GLOBALS["HTTP_POST_VARS"]["orgao_id"]:$this->orgao_id);
            $this->unidade_id = ($this->unidade_id == ""?@$GLOBALS["HTTP_POST_VARS"]["unidade_id"]:$this->unidade_id);
        }
    }

    public function incluir($exercicio, $orgao_id, $unidade_id)
    {
        $this->atualizacampos();
        $this->exercicio = $exercicio;
        $this->orgao_id = $orgao_id;
        $this->unidade_id = $unidade_id;
        if (($this->exercicio == null) || ($this->exercicio == "")) {
            $this->erro_sql = " Campo exercicio não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if (($this->orgao_id == null) || ($this->orgao_id == "")) {
            $this->erro_sql = " Campo orgao_id não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if (($this->unidade_id == null) || ($this->unidade_id == "")) {
            $this->erro_sql = " Campo unidade_id não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into emppreautorizacaounidade(
                                       exercicio 
                                      ,orgao_id 
                                      ,unidade_id 
                       )
                values (
                                $this->exercicio 
                               ,$this->orgao_id 
                               ,$this->unidade_id 
                      )";
        $result = db_query($sql);
        if ($result==false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql   = " ($this->exercicio."-".$this->orgao_id."-".$this->unidade_id) não Incluído. Inclusão Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_banco = " já Cadastrado";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            } else {
                $this->erro_sql   = " ($this->exercicio."-".$this->orgao_id."-".$this->unidade_id) não Incluído. Inclusão Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir= 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->exercicio."-".$this->orgao_id."-".$this->unidade_id;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir= pg_affected_rows($result);
        return true;
    }

    public function alterar($exercicio = null, $orgao_id = null, $unidade_id = null)
    {
        $this->atualizacampos();
        $sql = " update emppreautorizacaounidade set ";
        $virgula = "";
        if (trim($this->exercicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["exercicio"])) {
            if (trim($this->exercicio)=="" && isset($GLOBALS["HTTP_POST_VARS"]["exercicio"])) {
                $this->exercicio = "0" ;
            }
            $sql  .= $virgula." exercicio = $this->exercicio ";
            $virgula = ",";
        }
        if (trim($this->orgao_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["orgao_id"])) {
            $sql  .= $virgula." orgao_id = $this->orgao_id ";
            $virgula = ",";
            if (trim($this->orgao_id) == null) {
                $this->erro_sql = " Campo Id do Orgao não informado.";
                $this->erro_campo = "orgao_id";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->unidade_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["unidade_id"])) {
            $sql  .= $virgula." unidade_id = $this->unidade_id ";
            $virgula = ",";
            if (trim($this->unidade_id) == null) {
                $this->erro_sql = " Campo Id da unidade não informado.";
                $this->erro_campo = "unidade_id";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if ($exercicio!=null) {
            $sql .= " exercicio = $this->exercicio";
        }
        if ($orgao_id!=null) {
            $sql .= " and  orgao_id = $this->orgao_id";
        }
        if ($unidade_id!=null) {
            $sql .= " and  unidade_id = $this->unidade_id";
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = " não Alterado. Alteração Abortada.\\n";
            $this->erro_sql .= "Valores : ".$this->exercicio."-".$this->orgao_id."-".$this->unidade_id;
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = " não foi Alterado. Alteração Executada.\\n";
                $this->erro_sql .= "Valores : ".$this->exercicio."-".$this->orgao_id."-".$this->unidade_id;
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : ".$this->exercicio."-".$this->orgao_id."-".$this->unidade_id;
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    public function excluir($exercicio = null, $orgao_id = null, $unidade_id = null, $dbwhere = null)
    {
        $sql = " delete from emppreautorizacaounidade
                    where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($exercicio)) {
                if (!empty($sql2)) {
                     $sql2 .= " and ";
                }
                $sql2 .= " exercicio = $exercicio ";
            }
            if (!empty($orgao_id)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " orgao_id = $orgao_id ";
            }
            if (!empty($unidade_id)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " unidade_id = $unidade_id ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql.$sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = " não Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : ".$exercicio."-".$orgao_id."-".$unidade_id;
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = " não Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : ".$exercicio."-".$orgao_id."-".$unidade_id;
                $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
                $this->erro_sql .= "Valores : ".$exercicio."-".$orgao_id."-".$unidade_id;
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
            $this->erro_sql   = "Record Vazio na Tabela:emppreautorizacaounidade";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    public function sql_query($exercicio = null, $orgao_id = null, $unidade_id = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql  = "select {$campos}";
        $sql .= "  from emppreautorizacaounidade ";
        $sql .= "      left  join orcunidade  on  orcunidade.o41_anousu = emppreautorizacaounidade.exercicio and  orcunidade.o41_orgao = emppreautorizacaounidade.orgao_id and  orcunidade.o41_unidade = emppreautorizacaounidade.unidade_id";
        $sql .= "      inner join db_config  on  db_config.codigo = orcunidade.o41_instit";
        $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcunidade.o41_anousu and  orcorgao.o40_orgao = orcunidade.o41_orgao";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($exercicio)) {
                $sql2 .= " where emppreautorizacaounidade.exercicio = $exercicio ";
            }
            if (!empty($orgao_id)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                } else {
                    $sql2 .= " where ";
                }
                $sql2 .= " emppreautorizacaounidade.orgao_id = $orgao_id ";
            }
            if (!empty($unidade_id)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                } else {
                    $sql2 .= " where ";
                }
                $sql2 .= " emppreautorizacaounidade.unidade_id = $unidade_id ";
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

    public function sql_query_file($exercicio = null, $orgao_id = null, $unidade_id = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql  = "select {$campos} ";
        $sql .= "  from emppreautorizacaounidade ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($exercicio)) {
                $sql2 .= " where emppreautorizacaounidade.exercicio = $exercicio ";
            }
            if (!empty($orgao_id)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                } else {
                    $sql2 .= " where ";
                }
                $sql2 .= " emppreautorizacaounidade.orgao_id = $orgao_id ";
            }
            if (!empty($unidade_id)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                } else {
                    $sql2 .= " where ";
                }
                $sql2 .= " emppreautorizacaounidade.unidade_id = $unidade_id ";
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

    private function possuiDotacao($empAutoriza_id)
    {
        $sql    = " select e56_autori ";
        $sql    .= "from empautidot ";
        $sql    .= "join orcdotacao on o58_anousu = e56_anousu and o58_coddot = e56_coddot ";
        $sql    .= "where e56_autori = $empAutoriza_id ";
        
        $rs = db_query($sql);
        if (pg_num_rows($rs) > 0) {
            return true;
        }

        return false;
    }

    public function obterInformacaoAutorizacao($empAutoriza_id)
    {
        if (!$this->possuiDotacao($empAutoriza_id)) {
            return null;
        }

        $sql = " select o58_anousu, o58_orgao, o58_unidade ";
        $sql .= "from empautoriza ";
        $sql .= "join empautidot on e56_autori = empautoriza.e54_autori ";
        $sql .= "join orcdotacao on o58_anousu = e56_anousu and o58_coddot = e56_coddot	";
        $sql .= "where empautoriza.e54_autori = $empAutoriza_id ";
        
        $retorno = new stdClass();
        $rs = db_query($sql);
        if (pg_num_rows($rs) > 0) {
            $dados = pg_fetch_array($rs);
            $retorno->exercicio = $dados['o58_anousu'];
            $retorno->orgao = $dados['o58_orgao'];
            $retorno->unidade = $dados['o58_unidade'];
        }

        return $retorno;
    }

    public function habilitadoParaLiberacaoAutorizacao($empAutoriza_id)
    {
        $sql = " select emppreautorizacaounidade.* ";
        $sql .= "from empautoriza ";
        $sql .= "join empautidot on e56_autori = empautoriza.e54_autori ";
        $sql .= "join orcdotacao on o58_anousu = e56_anousu and o58_coddot = e56_coddot	";
        $sql .= "join emppreautorizacaounidade on exercicio = o58_anousu 
                    and orgao_id = o58_orgao and unidade_id = o58_unidade ";
        $sql .= "where empautoriza.e54_autori = $empAutoriza_id ";
        $rs = db_query($sql);
        if (pg_num_rows($rs) > 0) {
            return true;
        }

        return false;
    }
}
