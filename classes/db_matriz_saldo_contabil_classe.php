<?php

class cl_matriz_saldo_contabil
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
    public $c132_ano = 0;
    /**
     * @var int
     */
    public $c132_mes = 0;
    /**
     * @var int
     */
    public $c132_sequencial = 0;
    public $campos = "c132_ano = int8 = Ano
                      c132_mes = int8 = Mês
                      c132_sequencial = int8 = Sequencial";

    public function __construct()
    {
        $this->rotulo = new rotulo('matriz_saldo_contabil');
        $this->pagina_retorno = basename($_SERVER['PHP_SELF']);
    }

    public function erro($mostra, $retorna)
    {
        if ($this->erro_status == '0' || ($mostra == true && $this->erro_status != null)) {
            echo "<script>alert('{$this->erro_msg}')</script>";
            if ($retorna == true) {
                echo "<script>location.href='".$this->pagina_retorno."'</script>";
            }
        }
    }

    public function incluir($c132_sequencial)
    {
        if ($this->c132_ano === '' || $this->c132_ano === null) {
            $this->erro_sql = " Campo Ano não informado.";
            $this->erro_campo = "c132_ano";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->c132_mes === '' || $this->c132_mes === null) {
            $this->erro_sql = " Campo Mês não informado.";
            $this->erro_campo = "c132_mes";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->c132_sequencial === '' || $this->c132_sequencial === null) {
            $this->erro_sql = " Campo Sequencial não informado.";
            $this->erro_campo = "c132_sequencial";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($c132_sequencial === '' || $c132_sequencial === null || $c132_sequencial === 0) {
            $result = db_query("select nextval('matriz_saldo_contabil_c132_sequencial_seq')"); 
            if (!$result) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: matriz_saldo_contabil_c132_sequencial_seq do campo: c132_sequencial"; 
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->c132_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("SELECT last_value FROM matriz_saldo_contabil_c132_sequencial_seq");
            if ($result && pg_result($result, 0, 0) < $c132_sequencial) {
                $this->erro_sql = " Campo c132_sequencial maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->c132_sequencial = $c132_sequencial;
            }
        }
        if ($this->c132_sequencial === null || $this->c132_sequencial === '' || $this->c132_sequencial === 0) {
            $this->erro_sql = " Campo c132_sequencial não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = '0';
            return false;
        }
        $sql = "
            INSERT INTO matriz_saldo_contabil (
                c132_ano
                ,c132_mes
                ,c132_sequencial
            ) VALUES (
                 " . ($this->c132_ano === null || $this->c132_ano === '' ? 'NULL' : $this->c132_ano) . "
                ," . ($this->c132_mes === null || $this->c132_mes === '' ? 'NULL' : $this->c132_mes) . "
                ," . ($this->c132_sequencial === null || $this->c132_sequencial === '' ? 'NULL' : $this->c132_sequencial) . "
            )
        ";
     $result = db_query($sql);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n", "", @pg_last_error());
       if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
         $this->erro_sql = "Matriz Saldo Contábil () não Incluído. Inclusão Abortada.";
         $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Matriz Saldo Contábil já cadastrado";
         $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       } else {
         $this->erro_sql = "Matriz Saldo Contábil () não Incluído. Inclusão Abortada.";
         $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
     $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->c132_sequencial  ));
       if ($resaco != false || $this->numrows != 0) {
         $resac = db_query("SELECT nextval('db_acount_id_acount_seq') AS acount");
         $acount = pg_result($resac, 0, 0);
         $resac = db_query("INSERT INTO db_acountacesso VALUES ($acount, " . db_getsession("DB_acessado") . ")");
         $resac = db_query("INSERT INTO db_acountkey VALUES ($acount,1010461,'$this->c132_sequencial','I')");
         $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010444,1010463,'','" . AddSlashes(pg_result($resaco,0,'c132_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010444,1010462,'','" . AddSlashes(pg_result($resaco,0,'c132_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010444,1010461,'','" . AddSlashes(pg_result($resaco,0,'c132_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }

    public function alterar($c132_sequencial = null)
    {
        $sql = "UPDATE matriz_saldo_contabil SET ";
        $virgula = '';
        $this->c132_sequencial = $c132_sequencial;
        if (trim($this->c132_ano) !== '' && $this->c132_ano !== null) {
            $sql .= "{$virgula} c132_ano = {$this->c132_ano} ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Ano" é obrigatório.');
        }
        if (trim($this->c132_mes) !== '' && $this->c132_mes !== null) {
            $sql .= "{$virgula} c132_mes = {$this->c132_mes} ";
        } else {
            throw new Exception('Campo "Mês" é obrigatório.');
        }

        if ($c132_sequencial !== '' && $c132_sequencial !== null && $c132_sequencial !== 0) {
            $sql .= ' WHERE';
            $sql .= " c132_sequencial = {$c132_sequencial}";
        }

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->c132_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1010461,'$this->c132_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c132_ano"]) || $this->c132_ano != "")
             $resac = db_query("insert into db_acount values($acount,1010444,1010463,'".AddSlashes(pg_result($resaco,$conresaco,'c132_ano'))."','$this->c132_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c132_mes"]) || $this->c132_mes != "")
             $resac = db_query("insert into db_acount values($acount,1010444,1010462,'".AddSlashes(pg_result($resaco,$conresaco,'c132_mes'))."','$this->c132_mes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["c132_sequencial"]) || $this->c132_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010444,1010461,'".AddSlashes(pg_result($resaco,$conresaco,'c132_sequencial'))."','$this->c132_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Matriz Saldo Contábil não Alterado. Alteração Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Matriz Saldo Contábil não foi Alterado. Alteração Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($c132_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($c132_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1010461,'$c132_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010444,1010463,'','".AddSlashes(pg_result($resaco,$iresaco,'c132_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010444,1010462,'','".AddSlashes(pg_result($resaco,$iresaco,'c132_mes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010444,1010461,'','".AddSlashes(pg_result($resaco,$iresaco,'c132_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from matriz_saldo_contabil
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($c132_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " c132_sequencial = $c132_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Matriz Saldo Contábil não Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Matriz Saldo Contábil não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
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
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:matriz_saldo_contabil";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($c132_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from matriz_saldo_contabil ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($c132_sequencial)) {
         $sql2 .= " where matriz_saldo_contabil.c132_sequencial = $c132_sequencial "; 
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

    public function sql_query_file($c132_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from matriz_saldo_contabil ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($c132_sequencial)){
         $sql2 .= " where matriz_saldo_contabil.c132_sequencial = $c132_sequencial "; 
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
        $order = $order ? 'ORDER BY ' . implode(', ', $order) : '';

        return "SELECT {$columns} FROM matriz_saldo_contabil {$where} {$order}";
    }

}
