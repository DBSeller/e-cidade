<?php

class cl_jetomsessao
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
    public $rh247_sequencial = 0;
    /**
     * @var int
     */
    public $rh247_comissao = 0;
    /**
     * @var string
     */
    public $rh247_data = null;
    /**
     * @var bool
     */
    public $rh247_processada = false;
    /**
     * @var int
     */
    public $rh247_tiposessao = 0;
    public $campos = "rh247_sequencial = int4 = Código
                      rh247_comissao = int4 = Codigo Comissao
                      rh247_data = date = Data da Sessao
                      rh247_processada = bool = Processada
                      rh247_tiposessao = int4 = Tipo Sessao";

    public function __construct()
    {
        $this->rotulo = new rotulo('jetomsessao');
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

    public function incluir($rh247_sequencial)
    {
        if ($this->rh247_sequencial === '' || $this->rh247_sequencial === null) {
            $this->erro_sql = " Campo Código não informado.";
            $this->erro_campo = "rh247_sequencial";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh247_comissao === '' || $this->rh247_comissao === null) {
            $this->erro_sql = " Campo Codigo Comissao não informado.";
            $this->erro_campo = "rh247_comissao";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh247_data === '' || $this->rh247_data === null) {
            $this->erro_sql = " Campo Data da Sessao não informado.";
            $this->erro_campo = "rh247_data_dia";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh247_processada === '' || $this->rh247_processada === null) {
            $this->erro_sql = " Campo Processada não informado.";
            $this->erro_campo = "rh247_processada";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh247_tiposessao === '' || $this->rh247_tiposessao === null) {
            $this->erro_sql = " Campo Tipo Sessao não informado.";
            $this->erro_campo = "rh247_tiposessao";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($rh247_sequencial === '' || $rh247_sequencial === null || $rh247_sequencial === 0) {
            $result = db_query("select nextval('jetomsessao_rh247_sequencial_seq')"); 
            if (!$result) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: jetomsessao_rh247_sequencial_seq do campo: rh247_sequencial"; 
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->rh247_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("SELECT last_value FROM jetomsessao_rh247_sequencial_seq");
            if ($result && pg_result($result, 0, 0) < $rh247_sequencial) {
                $this->erro_sql = " Campo rh247_sequencial maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->rh247_sequencial = $rh247_sequencial;
            }
        }
        if ($this->rh247_sequencial === null || $this->rh247_sequencial === '' || $this->rh247_sequencial === 0) {
            $this->erro_sql = " Campo rh247_sequencial não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = '0';
            return false;
        }
        $sql = "
            INSERT INTO jetomsessao (
                rh247_sequencial
                ,rh247_comissao
                ,rh247_data
                ,rh247_processada
                ,rh247_tiposessao
            ) VALUES (
                 " . ($this->rh247_sequencial === null || $this->rh247_sequencial === '' ? 'NULL' : $this->rh247_sequencial) . "
                ," . ($this->rh247_comissao === null || $this->rh247_comissao === '' ? 'NULL' : $this->rh247_comissao) . "
                ," . ($this->rh247_data === null || $this->rh247_data === '' ? 'NULL' : "'{$this->rh247_data}'") . "
                ," . ($this->rh247_processada === null || $this->rh247_processada === '' ? 'NULL' : ($this->rh247_processada ? 'TRUE' : 'FALSE')) . "
                ," . ($this->rh247_tiposessao === null || $this->rh247_tiposessao === '' ? 'NULL' : $this->rh247_tiposessao) . "
            )
        ";
     $result = db_query($sql);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n", "", @pg_last_error());
       if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
         $this->erro_sql = "Jetom Sessao () não Incluído. Inclusão Abortada.";
         $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Jetom Sessao já cadastrado";
         $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       } else {
         $this->erro_sql = "Jetom Sessao () não Incluído. Inclusão Abortada.";
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

       $resaco = $this->sql_record($this->sql_query_file($this->rh247_sequencial  ));
       if ($resaco != false || $this->numrows != 0) {
         $resac = db_query("SELECT nextval('db_acount_id_acount_seq') AS acount");
         $acount = pg_result($resac, 0, 0);
         $resac = db_query("INSERT INTO db_acountacesso VALUES ($acount, " . db_getsession("DB_acessado") . ")");
         $resac = db_query("INSERT INTO db_acountkey VALUES ($acount,1010885,'$this->rh247_sequencial','I')");
         $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010497,1010885,'','" . AddSlashes(pg_result($resaco,0,'rh247_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010497,1010886,'','" . AddSlashes(pg_result($resaco,0,'rh247_comissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010497,1010887,'','" . AddSlashes(pg_result($resaco,0,'rh247_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010497,1010888,'','" . AddSlashes(pg_result($resaco,0,'rh247_processada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010497,1010889,'','" . AddSlashes(pg_result($resaco,0,'rh247_tiposessao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }

    public function alterar($rh247_sequencial = null)
    {
        $sql = "UPDATE jetomsessao SET ";
        $virgula = '';
        if (empty($rh247_sequencial)) {
            throw new Exception('Campo rh247_sequencial é obrigatório!');
        }
        $this->rh247_sequencial = $rh247_sequencial;
        if (trim($this->rh247_comissao) !== '' && $this->rh247_comissao !== null) {
            $sql .= "{$virgula} rh247_comissao = {$this->rh247_comissao} ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Codigo Comissao" é obrigatório.');
        }
        if (trim($this->rh247_data) !== '' && $this->rh247_data !== null) {
            $sql .= "{$virgula} rh247_data = '{$this->rh247_data}' ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Data da Sessao" é obrigatório.');
        }
        if ($this->rh247_processada !== '' && $this->rh247_processada !== null) {
            $sql .= "{$virgula} rh247_processada = " . ($this->rh247_processada === true ? 'TRUE' : 'FALSE') . " ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Processada" é obrigatório.');
        }
        if (trim($this->rh247_tiposessao) !== '' && $this->rh247_tiposessao !== null) {
            $sql .= "{$virgula} rh247_tiposessao = {$this->rh247_tiposessao} ";
        } else {
            throw new Exception('Campo "Tipo Sessao" é obrigatório.');
        }

        if ($rh247_sequencial !== '' && $rh247_sequencial !== null && $rh247_sequencial !== 0) {
            $sql .= ' WHERE';
            $sql .= " rh247_sequencial = {$rh247_sequencial}";
        }

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh247_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1010885,'$this->rh247_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh247_sequencial"]) || $this->rh247_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010497,1010885,'".AddSlashes(pg_result($resaco,$conresaco,'rh247_sequencial'))."','$this->rh247_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh247_comissao"]) || $this->rh247_comissao != "")
             $resac = db_query("insert into db_acount values($acount,1010497,1010886,'".AddSlashes(pg_result($resaco,$conresaco,'rh247_comissao'))."','$this->rh247_comissao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh247_data"]) || $this->rh247_data != "")
             $resac = db_query("insert into db_acount values($acount,1010497,1010887,'".AddSlashes(pg_result($resaco,$conresaco,'rh247_data'))."','$this->rh247_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh247_processada"]) || $this->rh247_processada != "")
             $resac = db_query("insert into db_acount values($acount,1010497,1010888,'".AddSlashes(pg_result($resaco,$conresaco,'rh247_processada'))."','$this->rh247_processada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh247_tiposessao"]) || $this->rh247_tiposessao != "")
             $resac = db_query("insert into db_acount values($acount,1010497,1010889,'".AddSlashes(pg_result($resaco,$conresaco,'rh247_tiposessao'))."','$this->rh247_tiposessao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Jetom Sessao não Alterado. Alteração Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Jetom Sessao não foi Alterado. Alteração Executada.\\n";
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

    public function excluir($rh247_sequencial=null, $dbwhere = null)
    {
        if (empty($rh247_sequencial)) {
            throw new Exception('Campo rh247_sequencial é obrigatório!');
        }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh247_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1010885,'$rh247_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010497,1010885,'','".AddSlashes(pg_result($resaco,$iresaco,'rh247_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010497,1010886,'','".AddSlashes(pg_result($resaco,$iresaco,'rh247_comissao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010497,1010887,'','".AddSlashes(pg_result($resaco,$iresaco,'rh247_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010497,1010888,'','".AddSlashes(pg_result($resaco,$iresaco,'rh247_processada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010497,1010889,'','".AddSlashes(pg_result($resaco,$iresaco,'rh247_tiposessao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from jetomsessao
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh247_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh247_sequencial = $rh247_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Jetom Sessao não Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Jetom Sessao não Encontrado. Exclusão não Efetuada.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:jetomsessao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh247_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from jetomsessao ";
     $sql .= "      inner join jetomtiposessao  on  jetomtiposessao.rh240_sequencial = jetomsessao.rh247_tiposessao";
     $sql .= "      inner join jetomcomissao  on  jetomcomissao.rh242_sequencial = jetomsessao.rh247_comissao";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh247_sequencial)) {
         $sql2 .= " where jetomsessao.rh247_sequencial = $rh247_sequencial "; 
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

    public function sql_query_file($rh247_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from jetomsessao ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh247_sequencial)){
         $sql2 .= " where jetomsessao.rh247_sequencial = $rh247_sequencial "; 
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
