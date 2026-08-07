<?php

class cl_rhlotacaotributaria
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
    public $rh268_sequencial = 0; 
    public $rh268_numcgm = 0; 
    public $rh268_codigolotacao = null; 
   // cria propriedade com as variaveis do arquivo 
    public $campos = "
                 rh268_sequencial = int8 = Código 
                 rh268_numcgm = int8 = CGM 
                 rh268_codigolotacao = varchar(50) = Código Lotação 
                 ";

    public function __construct()
    {
        $this->rotulo = new rotulo("rhlotacaotributaria"); 
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
       $this->rh268_sequencial = ($this->rh268_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh268_sequencial"]:$this->rh268_sequencial);
       $this->rh268_numcgm = ($this->rh268_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["rh268_numcgm"]:$this->rh268_numcgm);
       $this->rh268_codigolotacao = ($this->rh268_codigolotacao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh268_codigolotacao"]:$this->rh268_codigolotacao);
     }else{
       $this->rh268_sequencial = ($this->rh268_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh268_sequencial"]:$this->rh268_sequencial);
     }
   }

    public function incluir($rh268_sequencial)
    {
      $this->atualizacampos();
     if($this->rh268_numcgm == null ){ 
       $this->erro_sql = " Campo CGM não informado.";
       $this->erro_campo = "rh268_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh268_codigolotacao == null ){ 
       $this->erro_sql = " Campo Código Lotação não informado.";
       $this->erro_campo = "rh268_codigolotacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh268_sequencial == "" || $rh268_sequencial == null ){
       $result = db_query("select nextval('rhlotacaotributaria_rh268_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhlotacaotributaria_rh268_sequencial_seq do campo: rh268_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh268_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhlotacaotributaria_rh268_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh268_sequencial)){
         $this->erro_sql = " Campo rh268_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh268_sequencial = $rh268_sequencial; 
       }
     }
     if(($this->rh268_sequencial == null) || ($this->rh268_sequencial == "") ){ 
       $this->erro_sql = " Campo rh268_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhlotacaotributaria(
                                       rh268_sequencial 
                                      ,rh268_numcgm 
                                      ,rh268_codigolotacao 
                       )
                values (
                                $this->rh268_sequencial 
                               ,$this->rh268_numcgm 
                               ,'$this->rh268_codigolotacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Lotação Tributária ($this->rh268_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Lotação Tributária já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Lotação Tributária ($this->rh268_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh268_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh268_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1014439,'$this->rh268_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1010981,1014439,'','".AddSlashes(pg_result($resaco,0,'rh268_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010981,1014440,'','".AddSlashes(pg_result($resaco,0,'rh268_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010981,1014441,'','".AddSlashes(pg_result($resaco,0,'rh268_codigolotacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 

    public function alterar($rh268_sequencial=null)
    {
      $this->atualizacampos();
     $sql = " update rhlotacaotributaria set ";
     $virgula = "";
     if(trim($this->rh268_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh268_sequencial"])){ 
       $sql  .= $virgula." rh268_sequencial = $this->rh268_sequencial ";
       $virgula = ",";
       if(trim($this->rh268_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "rh268_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh268_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh268_numcgm"])){ 
       $sql  .= $virgula." rh268_numcgm = $this->rh268_numcgm ";
       $virgula = ",";
       if(trim($this->rh268_numcgm) == null ){ 
         $this->erro_sql = " Campo CGM não informado.";
         $this->erro_campo = "rh268_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh268_codigolotacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh268_codigolotacao"])){ 
       $sql  .= $virgula." rh268_codigolotacao = '$this->rh268_codigolotacao' ";
       $virgula = ",";
       if(trim($this->rh268_codigolotacao) == null ){ 
         $this->erro_sql = " Campo Código Lotação não informado.";
         $this->erro_campo = "rh268_codigolotacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh268_sequencial!=null){
       $sql .= " rh268_sequencial = $this->rh268_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh268_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1014439,'$this->rh268_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh268_sequencial"]) || $this->rh268_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1010981,1014439,'".AddSlashes(pg_result($resaco,$conresaco,'rh268_sequencial'))."','$this->rh268_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh268_numcgm"]) || $this->rh268_numcgm != "")
             $resac = db_query("insert into db_acount values($acount,1010981,1014440,'".AddSlashes(pg_result($resaco,$conresaco,'rh268_numcgm'))."','$this->rh268_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["rh268_codigolotacao"]) || $this->rh268_codigolotacao != "")
             $resac = db_query("insert into db_acount values($acount,1010981,1014441,'".AddSlashes(pg_result($resaco,$conresaco,'rh268_codigolotacao'))."','$this->rh268_codigolotacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lotação Tributária não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh268_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Lotação Tributária não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh268_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->rh268_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 

    public function excluir($rh268_sequencial=null, $dbwhere = null)
    {
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($rh268_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1014439,'$rh268_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1010981,1014439,'','".AddSlashes(pg_result($resaco,$iresaco,'rh268_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010981,1014440,'','".AddSlashes(pg_result($resaco,$iresaco,'rh268_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010981,1014441,'','".AddSlashes(pg_result($resaco,$iresaco,'rh268_codigolotacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhlotacaotributaria
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($rh268_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " rh268_sequencial = $rh268_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Lotação Tributária não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh268_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Lotação Tributária não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh268_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$rh268_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhlotacaotributaria";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }

    public function sql_query($rh268_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from rhlotacaotributaria ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhlotacaotributaria.rh268_numcgm";
     $sql .= "      inner join configuracoes.db_config on numcgm = rhlotacaotributaria.rh268_numcgm";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh268_sequencial)) {
         $sql2 .= " where rhlotacaotributaria.rh268_sequencial = $rh268_sequencial "; 
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

    public function sql_query_file($rh268_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rhlotacaotributaria ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($rh268_sequencial)){
         $sql2 .= " where rhlotacaotributaria.rh268_sequencial = $rh268_sequencial "; 
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


  public function buscaPreenchimento(array $campos, array $where, array $order, $instit = null)
  {
      $sql = " select  " . implode(', ', $campos);
      $sql .= " from esocial.avaliacaogruporespostalotacao ";
      $sql .= " join habitacao.avaliacaogruporesposta on db107_sequencial = eso04_avaliacaogruporesposta ";
      $sql .= " join habitacao.avaliacaogrupoperguntaresposta on avaliacaogrupoperguntaresposta.db108_avaliacaogruporesposta = avaliacaogruporesposta.db107_sequencial ";
      $sql .= " join habitacao.avaliacaoresposta on avaliacaoresposta.db106_sequencial = avaliacaogrupoperguntaresposta.db108_avaliacaoresposta  ";
      $sql .= " join habitacao.avaliacaoperguntaopcao on avaliacaoperguntaopcao.db104_sequencial = avaliacaoresposta.db106_avaliacaoperguntaopcao and avaliacaoperguntaopcao.db104_sequencial = 3003555";
      $sql .= " join habitacao.avaliacaopergunta on avaliacaopergunta.db103_sequencial = avaliacaoperguntaopcao.db104_avaliacaopergunta ";
      $sql .= " join habitacao.avaliacaogrupopergunta on avaliacaogrupopergunta.db102_sequencial = avaliacaopergunta.db103_avaliacaogrupopergunta ";
      $sql .= " join protocolo.cgm on cgm.z01_numcgm = avaliacaogruporespostalotacao.eso04_cgm ";
      $sql .= " join recursoshumanos.rhlotacaotributaria on rh268_numcgm = eso04_cgm and db106_resposta = rh268_codigolotacao";

      if($instit) {
          $sql .= " join pessoal.rhlota on cgm.z01_numcgm = rhlota.r70_numcgm and rhlota.r70_instit = {$instit}";
      }

      if (!empty($where)) {
          $sql .= " where " . implode(' and ', $where);
      }

      if (!empty($order)) {
          $sql .= " order by " . implode(', ', $order);
      }

      return $sql;
  }


  public function buscaLotacaoTributariaPorInstituicao(array $campos, array $where, array $order, $instit = null)
  {
  $sql = "select
              " . implode(', ', $campos) . "
          from
            recursoshumanos.rhlotacaotributaria
          inner join protocolo.cgm on
            cgm.z01_numcgm = rhlotacaotributaria.rh268_numcgm
          inner join configuracoes.db_config on
            db_config.numcgm = cgm.z01_numcgm
            and db_config.codigo = {$instit} ";

      if (!empty($where)) {
          $sql .= " where " . implode(' and ', $where);
      }

      if (!empty($order)) {
          $sql .= " order by " . implode(', ', $order);
      }

      return $sql;
  }



}
