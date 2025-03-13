<?php

class cl_avaliacaogruporespostacontribuinte
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
    public $eso27_sequencial = 0; 
    public $eso27_avaliacaogruporesposta = 0; 
    public $eso27_cgm = 0; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 eso27_sequencial = int4 = Sequencial 
                 eso27_avaliacaogruporesposta = int4 = Código do Grupo de Resposta 
                 eso27_cgm = int4 = Cgm do Contribuinte 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("avaliacaogruporespostacontribuinte"); 
        $this->pagina_retorno = basename($_SERVER['PHP_SELF']);
    }

    public function erro($mostra, $retorna)
    {
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\")</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }

    public function atualizacampos($exclusao = false)
    {
     if($exclusao==false){
       $this->eso27_sequencial = ($this->eso27_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["eso27_sequencial"]:$this->eso27_sequencial);
       $this->eso27_avaliacaogruporesposta = ($this->eso27_avaliacaogruporesposta == ""?@$GLOBALS["HTTP_POST_VARS"]["eso27_avaliacaogruporesposta"]:$this->eso27_avaliacaogruporesposta);
       $this->eso27_cgm = ($this->eso27_cgm == ""?@$GLOBALS["HTTP_POST_VARS"]["eso27_cgm"]:$this->eso27_cgm);
     }else{
       $this->eso27_sequencial = ($this->eso27_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["eso27_sequencial"]:$this->eso27_sequencial);
     }
   }

    public function incluir($eso27_sequencial)
    {
      $this->atualizacampos();
     if($this->eso27_avaliacaogruporesposta == null ){ 
       $this->erro_sql = " Campo Código do Grupo de Resposta não informado.";
       $this->erro_campo = "eso27_avaliacaogruporesposta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->eso27_cgm == null ){ 
       $this->erro_sql = " Campo Cgm do Contribuinte não informado.";
       $this->erro_campo = "eso27_cgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($eso27_sequencial == "" || $eso27_sequencial == null ){
       $result = db_query("select nextval('avaliacaogruporespostacontribuinte_eso27_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: avaliacaogruporespostacontribuinte_eso27_sequencial_seq do campo: eso27_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->eso27_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from avaliacaogruporespostacontribuinte_eso27_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $eso27_sequencial)){
         $this->erro_sql = " Campo eso27_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->eso27_sequencial = $eso27_sequencial; 
       }
     }
     if(($this->eso27_sequencial == null) || ($this->eso27_sequencial == "") ){ 
       $this->erro_sql = " Campo eso27_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into avaliacaogruporespostacontribuinte(
                                       eso27_sequencial 
                                      ,eso27_avaliacaogruporesposta 
                                      ,eso27_cgm 
                       )
                values (
                                $this->eso27_sequencial 
                               ,$this->eso27_avaliacaogruporesposta 
                               ,$this->eso27_cgm 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Vínculo entre preenchimento e contribuinte ($this->eso27_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Vínculo entre preenchimento e contribuinte já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Vínculo entre preenchimento e contribuinte ($this->eso27_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->eso27_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->eso27_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1010179,'$this->eso27_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010353,1010179,'','".AddSlashes(pg_result($resaco,0,'eso27_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010353,1010180,'','".AddSlashes(pg_result($resaco,0,'eso27_avaliacaogruporesposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010353,1010181,'','".AddSlashes(pg_result($resaco,0,'eso27_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($eso27_sequencial=null, $where = null)
    {
      $this->atualizacampos();
     $sql = " update avaliacaogruporespostacontribuinte set ";
     $virgula = "";
     if(trim($this->eso27_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso27_sequencial"])){ 
       $sql  .= $virgula." eso27_sequencial = $this->eso27_sequencial ";
       $virgula = ",";
       if(trim($this->eso27_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "eso27_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->eso27_avaliacaogruporesposta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso27_avaliacaogruporesposta"])){ 
       $sql  .= $virgula." eso27_avaliacaogruporesposta = $this->eso27_avaliacaogruporesposta ";
       $virgula = ",";
       if(trim($this->eso27_avaliacaogruporesposta) == null ){ 
         $this->erro_sql = " Campo Código do Grupo de Resposta não informado.";
         $this->erro_campo = "eso27_avaliacaogruporesposta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->eso27_cgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["eso27_cgm"])){ 
       $sql  .= $virgula." eso27_cgm = $this->eso27_cgm ";
       $virgula = ",";
       if(trim($this->eso27_cgm) == null ){ 
         $this->erro_sql = " Campo Cgm do Contribuinte não informado.";
         $this->erro_campo = "eso27_cgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }

     if($eso27_sequencial!=null){
       $sql .= " where eso27_sequencial = $this->eso27_sequencial ";
     } elseif (!empty($where)) {
         $sql .= " where {$where} ";
     }

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->eso27_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1010179,'$this->eso27_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso27_sequencial"]) || $this->eso27_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010353,1010179,'".AddSlashes(pg_result($resaco,$conresaco,'eso27_sequencial'))."','$this->eso27_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso27_avaliacaogruporesposta"]) || $this->eso27_avaliacaogruporesposta != "")
             $resac = db_query("insert into db_acount values($acount,1010353,1010180,'".AddSlashes(pg_result($resaco,$conresaco,'eso27_avaliacaogruporesposta'))."','$this->eso27_avaliacaogruporesposta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["eso27_cgm"]) || $this->eso27_cgm != "")
             $resac = db_query("insert into db_acount values($acount,1010353,1010181,'".AddSlashes(pg_result($resaco,$conresaco,'eso27_cgm'))."','$this->eso27_cgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vínculo entre preenchimento e contribuinte não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->eso27_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Vínculo entre preenchimento e contribuinte não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->eso27_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->eso27_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($eso27_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($eso27_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1010179,'$eso27_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010353,1010179,'','".AddSlashes(pg_result($resaco,$iresaco,'eso27_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010353,1010180,'','".AddSlashes(pg_result($resaco,$iresaco,'eso27_avaliacaogruporesposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010353,1010181,'','".AddSlashes(pg_result($resaco,$iresaco,'eso27_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from avaliacaogruporespostacontribuinte
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($eso27_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " eso27_sequencial = $eso27_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vínculo entre preenchimento e contribuinte não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$eso27_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Vínculo entre preenchimento e contribuinte não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$eso27_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$eso27_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:avaliacaogruporespostacontribuinte";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($eso27_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from avaliacaogruporespostacontribuinte ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = avaliacaogruporespostacontribuinte.eso27_cgm";
     $sql .= "      inner join avaliacaogruporesposta  on  avaliacaogruporesposta.db107_sequencial = avaliacaogruporespostacontribuinte.eso27_avaliacaogruporesposta";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($eso27_sequencial)) {
         $sql2 .= " where avaliacaogruporespostacontribuinte.eso27_sequencial = $eso27_sequencial "; 
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

    public function sql_query_file($eso27_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from avaliacaogruporespostacontribuinte ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($eso27_sequencial)){
         $sql2 .= " where avaliacaogruporespostacontribuinte.eso27_sequencial = $eso27_sequencial "; 
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

    public function sqlDadosSugestao($cgm)
    {
        $sql = "
            SELECT
                   z01_nomecomple as nmCtt,
                   z01_cgccpf as cpfCtt,
                   z01_telef as foneFixo,
                   z01_telcel as foneCel,
                   z01_email as email,
                   z01_nome as nmRazao
            FROM cgm
            WHERE z01_numcgm = {$cgm};
        ";

        return $sql;
    }

    public function buscarRespostasPreenchimento($campos = array('*'), $where = array(), $outrosComandos = "")
    {
        $sql  = " SELECT DISTINCT " . implode(', ', $campos);
        $sql .= "   FROM avaliacaogruporespostacontribuinte";
        $sql .= "  INNER JOIN avaliacaogruporesposta ON db107_sequencial = eso27_avaliacaogruporesposta ";
        $sql .= "  INNER JOIN avaliacaogrupoperguntaresposta ON db108_avaliacaogruporesposta = db107_sequencial";
        $sql .= "  INNER JOIN avaliacaoresposta ON db106_sequencial = db108_avaliacaoresposta ";
        $sql .= "  INNER JOIN avaliacaoperguntaopcao ON db104_sequencial = db106_avaliacaoperguntaopcao ";
        $sql .= "  INNER JOIN avaliacaopergunta ON db103_sequencial = db104_avaliacaopergunta ";
        $sql .= "  INNER JOIN avaliacaogrupopergunta ON db103_avaliacaogrupopergunta = db102_sequencial ";
        $sql .= "  INNER JOIN avaliacao ON db102_avaliacao = db101_sequencial ";
        $sql .= "  INNER JOIN cgm ON z01_numcgm = eso27_cgm";

        if(!empty($where)) {
           $sql .= " WHERE " . implode(' AND ', $where);
        }

        if(!empty($outrosComandos)) {
            $sql .= " {$outrosComandos} ";
        }

        return $sql;
    }

}
