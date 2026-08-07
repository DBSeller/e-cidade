<?php

class cl_bnccetapas
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
    public $ed152_sequencial = 0;
    /**
     * @var string
     */
    public $ed152_etapa = '';
    /**
     * @var string
     */
    public $ed152_ensino = '';
    public $campos = "ed152_sequencial = int4 = Código
                      ed152_etapa = varchar(10) = Etapa
                      ed152_ensino = varchar(2) = Ensino";

    public function __construct()
    {
        $this->rotulo = new rotulo('bnccetapas');
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

    public function incluir($ed152_sequencial)
    {
        if ($this->ed152_sequencial === '' || $this->ed152_sequencial === null) {
            $this->erro_sql = " Campo Código não informado.";
            $this->erro_campo = "ed152_sequencial";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed152_etapa === '' || $this->ed152_etapa === null) {
            $this->erro_sql = " Campo Etapa não informado.";
            $this->erro_campo = "ed152_etapa";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ed152_ensino === '' || $this->ed152_ensino === null) {
            $this->erro_sql = " Campo Ensino não informado.";
            $this->erro_campo = "ed152_ensino";
            $this->erro_banco = "";
            $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($ed152_sequencial === '' || $ed152_sequencial === null || $ed152_sequencial === 0) {
            $result = db_query("select nextval('bnccetapas_ed152_sequencial_seq')"); 
            if (!$result) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: bnccetapas_ed152_sequencial_seq do campo: ed152_sequencial"; 
                $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->ed152_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("SELECT last_value FROM bnccetapas_ed152_sequencial_seq");
            if ($result && pg_result($result, 0, 0) < $ed152_sequencial) {
                $this->erro_sql = " Campo ed152_sequencial maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
                $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->ed152_sequencial = $ed152_sequencial;
            }
        }
        if ($this->ed152_sequencial === null || $this->ed152_sequencial === '' || $this->ed152_sequencial === 0) {
            $this->erro_sql = " Campo ed152_sequencial não declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = '0';
            return false;
        }
        $sql = "
            INSERT INTO bnccetapas (
                ed152_sequencial
                ,ed152_etapa
                ,ed152_ensino
            ) VALUES (
                 " . ($this->ed152_sequencial === null || $this->ed152_sequencial === '' ? 'NULL' : $this->ed152_sequencial) . "
                ," . ($this->ed152_etapa === null || $this->ed152_etapa === '' ? 'NULL' : "'{$this->ed152_etapa}'") . "
                ," . ($this->ed152_ensino === null || $this->ed152_ensino === '' ? 'NULL' : "'{$this->ed152_ensino}'") . "
            )
        ";
     $result = db_query($sql);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n", "", @pg_last_error());
       if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
         $this->erro_sql = " () não Incluído. Inclusão Abortada.";
         $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já cadastrado";
         $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       } else {
         $this->erro_sql = " () não Incluído. Inclusão Abortada.";
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

       $resaco = $this->sql_record($this->sql_query_file($this->ed152_sequencial  ));
       if ($resaco != false || $this->numrows != 0) {
         $resac = db_query("SELECT nextval('db_acount_id_acount_seq') AS acount");
         $acount = pg_result($resac, 0, 0);
         $resac = db_query("INSERT INTO db_acountacesso VALUES ($acount, " . db_getsession("DB_acessado") . ")");
         $resac = db_query("INSERT INTO db_acountkey VALUES ($acount,1010927,'$this->ed152_sequencial','I')");
         $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010505,1010927,'','" . AddSlashes(pg_result($resaco,0,'ed152_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010505,1010928,'','" . AddSlashes(pg_result($resaco,0,'ed152_etapa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("INSERT INTO db_acount VALUES ($acount,1010505,1010929,'','" . AddSlashes(pg_result($resaco,0,'ed152_ensino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }

    public function alterar($ed152_sequencial = null)
    {
        $sql = "UPDATE bnccetapas SET ";
        $virgula = '';
        if (empty($ed152_sequencial)) {
            throw new Exception('Campo ed152_sequencial é obrigatório!');
        }
        $this->ed152_sequencial = $ed152_sequencial;
        if (trim($this->ed152_etapa) !== '' && $this->ed152_etapa !== null) {
            $sql .= "{$virgula} ed152_etapa = '{$this->ed152_etapa}' ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Etapa" é obrigatório.');
        }
        if (trim($this->ed152_ensino) !== '' && $this->ed152_ensino !== null) {
            $sql .= "{$virgula} ed152_ensino = '{$this->ed152_ensino}' ";
        } else {
            throw new Exception('Campo "Ensino" é obrigatório.');
        }

        if ($ed152_sequencial !== '' && $ed152_sequencial !== null && $ed152_sequencial !== 0) {
            $sql .= ' WHERE';
            $sql .= " ed152_sequencial = {$ed152_sequencial}";
        }

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed152_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1010927,'$this->ed152_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed152_sequencial"]) || $this->ed152_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010505,1010927,'".AddSlashes(pg_result($resaco,$conresaco,'ed152_sequencial'))."','$this->ed152_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed152_etapa"]) || $this->ed152_etapa != "")
             $resac = db_query("insert into db_acount values($acount,1010505,1010928,'".AddSlashes(pg_result($resaco,$conresaco,'ed152_etapa'))."','$this->ed152_etapa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed152_ensino"]) || $this->ed152_ensino != "")
             $resac = db_query("insert into db_acount values($acount,1010505,1010929,'".AddSlashes(pg_result($resaco,$conresaco,'ed152_ensino'))."','$this->ed152_ensino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " não Alterado. Alteração Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = " não foi Alterado. Alteração Executada.\\n";
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

    public function excluir($ed152_sequencial=null, $dbwhere = null)
    {
        if (empty($ed152_sequencial)) {
            throw new Exception('Campo ed152_sequencial é obrigatório!');
        }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed152_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1010927,'$ed152_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010505,1010927,'','".AddSlashes(pg_result($resaco,$iresaco,'ed152_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010505,1010928,'','".AddSlashes(pg_result($resaco,$iresaco,'ed152_etapa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010505,1010929,'','".AddSlashes(pg_result($resaco,$iresaco,'ed152_ensino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from bnccetapas
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed152_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed152_sequencial = $ed152_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " não Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = " não Encontrado. Exclusão não Efetuada.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:bnccetapas";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($ed152_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from bnccetapas ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed152_sequencial)) {
         $sql2 .= " where bnccetapas.ed152_sequencial = $ed152_sequencial "; 
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

    public function sql_query_file($ed152_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from bnccetapas ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed152_sequencial)){
         $sql2 .= " where bnccetapas.ed152_sequencial = $ed152_sequencial "; 
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
