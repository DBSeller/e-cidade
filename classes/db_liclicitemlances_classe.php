<?php

class cl_liclicitemlances
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
  public $l49_sequencial = 0;
  public $l49_liclicitem = 0;
  public $l49_data_dia = null;
  public $l49_data_mes = null;
  public $l49_data_ano = null;
  public $l49_data = null;
  public $l49_hora = null;
  public $l49_fornecedor = 0;
  public $l49_valido = 'f';
  public $l49_cancelado = 'f';
  public $l49_justificativa = null;
  public $l49_vlrun = 0;
  public $l49_vlrtot = 0;
  public $l49_vlrdesc = 0;
  // cria propriedade com as variaveis do arquivo 
  public $campos = "
                 l49_sequencial = int8 = Sequencial 
                 l49_liclicitem = int8 = Item Licitação 
                 l49_data = date = Data do Lance 
                 l49_hora = char(8) = Hora do lance 
                 l49_fornecedor = int8 = Fornecedor 
                 l49_valido = bool = Válido 
                 l49_cancelado = bool = Cancelado 
                 l49_justificativa = text = Justificativa 
                 l49_vlrun = float8 = Valor unitário 
                 l49_vlrtot = float8 = Valor total 
                 l49_vlrdesc = float8 = Valor Desconto 
                 ";

  public function __construct()
  {
    $this->rotulo = new rotulo("liclicitemlances");
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
      $this->l49_sequencial = ($this->l49_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["l49_sequencial"] : $this->l49_sequencial);
      $this->l49_liclicitem = ($this->l49_liclicitem == "" ? @$GLOBALS["HTTP_POST_VARS"]["l49_liclicitem"] : $this->l49_liclicitem);
      if ($this->l49_data == "") {
        $this->l49_data_dia = ($this->l49_data_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["l49_data_dia"] : $this->l49_data_dia);
        $this->l49_data_mes = ($this->l49_data_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["l49_data_mes"] : $this->l49_data_mes);
        $this->l49_data_ano = ($this->l49_data_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["l49_data_ano"] : $this->l49_data_ano);
        if ($this->l49_data_dia != "") {
          $this->l49_data = $this->l49_data_ano . "-" . $this->l49_data_mes . "-" . $this->l49_data_dia;
        }
      }
      $this->l49_hora = ($this->l49_hora == "" ? @$GLOBALS["HTTP_POST_VARS"]["l49_hora"] : $this->l49_hora);
      $this->l49_fornecedor = ($this->l49_fornecedor == "" ? @$GLOBALS["HTTP_POST_VARS"]["l49_fornecedor"] : $this->l49_fornecedor);
      $this->l49_valido = ($this->l49_valido == null ? @$GLOBALS["HTTP_POST_VARS"]["l49_valido"] : $this->l49_valido);
      $this->l49_cancelado = ($this->l49_cancelado == null ? @$GLOBALS["HTTP_POST_VARS"]["l49_cancelado"] : $this->l49_cancelado);
      $this->l49_justificativa = ($this->l49_justificativa == "" ? @$GLOBALS["HTTP_POST_VARS"]["l49_justificativa"] : $this->l49_justificativa);
      $this->l49_vlrun = ($this->l49_vlrun == "" ? @$GLOBALS["HTTP_POST_VARS"]["l49_vlrun"] : $this->l49_vlrun);
      $this->l49_vlrtot = ($this->l49_vlrtot == "" ? @$GLOBALS["HTTP_POST_VARS"]["l49_vlrtot"] : $this->l49_vlrtot);
      $this->l49_vlrdesc = ($this->l49_vlrdesc == "" ? @$GLOBALS["HTTP_POST_VARS"]["l49_vlrdesc"] : $this->l49_vlrdesc);
    } else {
      $this->l49_sequencial = ($this->l49_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["l49_sequencial"] : $this->l49_sequencial);
    }
  }

  public function incluir($l49_sequencial)
  { 
    
    $this->atualizacampos();    
    if ($this->l49_liclicitem == null) {
      $this->erro_sql = " Campo Item Licitação não informado.";
      $this->erro_campo = "l49_liclicitem";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->l49_data == null) {
      $this->erro_sql = " Campo Data do Lance não informado.";
      $this->erro_campo = "l49_data_dia";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->l49_hora == null) {
      $this->erro_sql = " Campo Hora do lance não informado.";
      $this->erro_campo = "l49_hora";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->l49_fornecedor == null) {
      $this->erro_sql = " Campo Fornecedor não informado.";
      $this->erro_campo = "l49_fornecedor";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->l49_valido == null) {
      $this->erro_sql = " Campo Válido não informado.";
      $this->erro_campo = "l49_valido";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->l49_cancelado == null) {
      $this->erro_sql = " Campo Cancelado não informado.";
      $this->erro_campo = "l49_cancelado";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    
    if ($this->l49_vlrun == null) {
      $this->erro_sql = " Campo Valor unitário não informado.";
      $this->erro_campo = "l49_vlrun";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->l49_vlrtot == null) {
      $this->erro_sql = " Campo Valor total não informado.";
      $this->erro_campo = "l49_vlrtot";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->l49_vlrdesc == null) {
      $this->erro_sql = " Campo Valor Desconto não informado.";
      $this->erro_campo = "l49_vlrdesc";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }

    if ($l49_sequencial == "" || $l49_sequencial == null) {
      $result = db_query("select nextval('liclicitemlances_l49_sequencial_seq')");
      if ($result == false) {
        $this->erro_banco = str_replace("\n", "", @pg_last_error());
        $this->erro_sql   = "Verifique o cadastro da sequencia: liclicitemlances_l49_sequencial_seq do campo: l49_sequencial";
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->l49_sequencial = pg_result($result, 0, 0);
    } else {
      $result = db_query("select last_value from liclicitemlances_l49_sequencial_seq");
      if (($result != false) && (pg_result($result, 0, 0) < $l49_sequencial)) {
        $this->erro_sql = " Campo l49_sequencial maior que último número da sequencia.";
        $this->erro_banco = "Sequencia menor que este número.";
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      } else {
        $this->l49_sequencial = $l49_sequencial;
      }
    }
    if (($this->l49_sequencial == null) || ($this->l49_sequencial == "")) {
      $this->erro_sql = " Campo l49_sequencial não declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into liclicitemlances(
                                       l49_sequencial 
                                      ,l49_liclicitem 
                                      ,l49_data 
                                      ,l49_hora 
                                      ,l49_fornecedor 
                                      ,l49_valido 
                                      ,l49_cancelado 
                                      ,l49_justificativa 
                                      ,l49_vlrun 
                                      ,l49_vlrtot 
                                      ,l49_vlrdesc 
                       )
                values (
                                $this->l49_sequencial 
                               ,$this->l49_liclicitem 
                               ," . ($this->l49_data == "null" || $this->l49_data == "" ? "null" : "'" . $this->l49_data . "'") . " 
                               ,'$this->l49_hora' 
                               ,$this->l49_fornecedor 
                               ,'$this->l49_valido' 
                               ,'$this->l49_cancelado' 
                               ,'$this->l49_justificativa' 
                               ,$this->l49_vlrun 
                               ,$this->l49_vlrtot 
                               ,$this->l49_vlrdesc 
                      )";
    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
        $this->erro_sql   = "liclicitemlances ($this->l49_sequencial) não Incluído. Inclusão Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_banco = "liclicitemlances já Cadastrado";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      } else {
        $this->erro_sql   = "liclicitemlances ($this->l49_sequencial) não Incluído. Inclusão Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir = 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
    $this->erro_sql .= "Valores : " . $this->l49_sequencial;
    $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
    $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir = pg_affected_rows($result);
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
      && ($lSessaoDesativarAccount === false))) {

      $resaco = $this->sql_record($this->sql_query_file($this->l49_sequencial));
      if (($resaco != false) || ($this->numrows != 0)) {

        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac, 0, 0);
        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        $resac = db_query("insert into db_acountkey values($acount,1013435,'$this->l49_sequencial','I')");
        $resac = db_query("insert into db_acount values($acount,1010829,1013435,'','" . AddSlashes(pg_result($resaco, 0, 'l49_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,1010829,1013436,'','" . AddSlashes(pg_result($resaco, 0, 'l49_liclicitem')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,1010829,1013438,'','" . AddSlashes(pg_result($resaco, 0, 'l49_data')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,1010829,1013439,'','" . AddSlashes(pg_result($resaco, 0, 'l49_hora')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,1010829,1013440,'','" . AddSlashes(pg_result($resaco, 0, 'l49_fornecedor')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,1010829,1013441,'','" . AddSlashes(pg_result($resaco, 0, 'l49_valido')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,1010829,1013442,'','" . AddSlashes(pg_result($resaco, 0, 'l49_cancelado')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,1010829,1013443,'','" . AddSlashes(pg_result($resaco, 0, 'l49_justificativa')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,1010829,1013444,'','" . AddSlashes(pg_result($resaco, 0, 'l49_vlrun')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,1010829,1013445,'','" . AddSlashes(pg_result($resaco, 0, 'l49_vlrtot')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,1010829,1013446,'','" . AddSlashes(pg_result($resaco, 0, 'l49_vlrdesc')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      }
    }
    return true;
  }

  public function alterar($l49_sequencial = null)
  {
    $this->atualizacampos();
    $sql = " update liclicitemlances set ";
    $virgula = "";
    if (trim($this->l49_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l49_sequencial"])) {
      $sql  .= $virgula . " l49_sequencial = $this->l49_sequencial ";
      $virgula = ",";
      if (trim($this->l49_sequencial) == null) {
        $this->erro_sql = " Campo Sequencial não informado.";
        $this->erro_campo = "l49_sequencial";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->l49_liclicitem) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l49_liclicitem"])) {
      $sql  .= $virgula . " l49_liclicitem = $this->l49_liclicitem ";
      $virgula = ",";
      if (trim($this->l49_liclicitem) == null) {
        $this->erro_sql = " Campo Item Licitação não informado.";
        $this->erro_campo = "l49_liclicitem";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->l49_data) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l49_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["l49_data_dia"] != "")) {
      $sql  .= $virgula . " l49_data = '$this->l49_data' ";
      $virgula = ",";
      if (trim($this->l49_data) == null) {
        $this->erro_sql = " Campo Data do Lance não informado.";
        $this->erro_campo = "l49_data_dia";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    } else {
      if (isset($GLOBALS["HTTP_POST_VARS"]["l49_data_dia"])) {
        $sql  .= $virgula . " l49_data = null ";
        $virgula = ",";
        if (trim($this->l49_data) == null) {
          $this->erro_sql = " Campo Data do Lance não informado.";
          $this->erro_campo = "l49_data_dia";
          $this->erro_banco = "";
          $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
          $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
          $this->erro_status = "0";
          return false;
        }
      }
    }
    if (trim($this->l49_hora) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l49_hora"])) {
      $sql  .= $virgula . " l49_hora = '$this->l49_hora' ";
      $virgula = ",";
      if (trim($this->l49_hora) == null) {
        $this->erro_sql = " Campo Hora do lance não informado.";
        $this->erro_campo = "l49_hora";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->l49_fornecedor) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l49_fornecedor"])) {
      $sql  .= $virgula . " l49_fornecedor = $this->l49_fornecedor ";
      $virgula = ",";
      if (trim($this->l49_fornecedor) == null) {
        $this->erro_sql = " Campo Fornecedor não informado.";
        $this->erro_campo = "l49_fornecedor";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->l49_valido) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l49_valido"])) {
      $sql  .= $virgula . " l49_valido = '$this->l49_valido' ";
      $virgula = ",";
      if (trim($this->l49_valido) == null) {
        $this->erro_sql = " Campo Válido não informado.";
        $this->erro_campo = "l49_valido";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->l49_cancelado) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l49_cancelado"])) {
      $sql  .= $virgula . " l49_cancelado = '$this->l49_cancelado' ";
      $virgula = ",";
      if (trim($this->l49_cancelado) == null) {
        $this->erro_sql = " Campo Cancelado não informado.";
        $this->erro_campo = "l49_cancelado";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->l49_justificativa) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l49_justificativa"])) {
      $sql  .= $virgula . " l49_justificativa = '$this->l49_justificativa' ";
      $virgula = ",";
      if (trim($this->l49_justificativa) == null) {
        $this->erro_sql = " Campo Justificativa não informado.";
        $this->erro_campo = "l49_justificativa";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->l49_vlrun) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l49_vlrun"])) {
      $sql  .= $virgula . " l49_vlrun = $this->l49_vlrun ";
      $virgula = ",";
      if (trim($this->l49_vlrun) == null) {
        $this->erro_sql = " Campo Valor unitário não informado.";
        $this->erro_campo = "l49_vlrun";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->l49_vlrtot) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l49_vlrtot"])) {
      $sql  .= $virgula . " l49_vlrtot = $this->l49_vlrtot ";
      $virgula = ",";
      if (trim($this->l49_vlrtot) == null) {
        $this->erro_sql = " Campo Valor total não informado.";
        $this->erro_campo = "l49_vlrtot";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->l49_vlrdesc) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l49_vlrdesc"])) {
      $sql  .= $virgula . " l49_vlrdesc = $this->l49_vlrdesc ";
      $virgula = ",";
      if (trim($this->l49_vlrdesc) == null) {
        $this->erro_sql = " Campo Valor Desconto não informado.";
        $this->erro_campo = "l49_vlrdesc";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    $sql .= " where ";
    if ($l49_sequencial != null) {
      $sql .= " l49_sequencial = $this->l49_sequencial";
    }
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
      && ($lSessaoDesativarAccount === false))) {

      $resaco = $this->sql_record($this->sql_query_file($this->l49_sequencial));
      if ($this->numrows > 0) {

        for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

          $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac, 0, 0);
          $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
          $resac = db_query("insert into db_acountkey values($acount,1013435,'$this->l49_sequencial','A')");
          if (isset($GLOBALS["HTTP_POST_VARS"]["l49_sequencial"]) || $this->l49_sequencial != "")
            $resac = db_query("insert into db_acount values($acount,1010829,1013435,'" . AddSlashes(pg_result($resaco, $conresaco, 'l49_sequencial')) . "','$this->l49_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["l49_liclicitem"]) || $this->l49_liclicitem != "")
            $resac = db_query("insert into db_acount values($acount,1010829,1013436,'" . AddSlashes(pg_result($resaco, $conresaco, 'l49_liclicitem')) . "','$this->l49_liclicitem'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["l49_data"]) || $this->l49_data != "")
            $resac = db_query("insert into db_acount values($acount,1010829,1013438,'" . AddSlashes(pg_result($resaco, $conresaco, 'l49_data')) . "','$this->l49_data'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["l49_hora"]) || $this->l49_hora != "")
            $resac = db_query("insert into db_acount values($acount,1010829,1013439,'" . AddSlashes(pg_result($resaco, $conresaco, 'l49_hora')) . "','$this->l49_hora'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["l49_fornecedor"]) || $this->l49_fornecedor != "")
            $resac = db_query("insert into db_acount values($acount,1010829,1013440,'" . AddSlashes(pg_result($resaco, $conresaco, 'l49_fornecedor')) . "','$this->l49_fornecedor'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["l49_valido"]) || $this->l49_valido != "")
            $resac = db_query("insert into db_acount values($acount,1010829,1013441,'" . AddSlashes(pg_result($resaco, $conresaco, 'l49_valido')) . "','$this->l49_valido'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["l49_cancelado"]) || $this->l49_cancelado != "")
            $resac = db_query("insert into db_acount values($acount,1010829,1013442,'" . AddSlashes(pg_result($resaco, $conresaco, 'l49_cancelado')) . "','$this->l49_cancelado'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["l49_justificativa"]) || $this->l49_justificativa != "")
            $resac = db_query("insert into db_acount values($acount,1010829,1013443,'" . AddSlashes(pg_result($resaco, $conresaco, 'l49_justificativa')) . "','$this->l49_justificativa'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["l49_vlrun"]) || $this->l49_vlrun != "")
            $resac = db_query("insert into db_acount values($acount,1010829,1013444,'" . AddSlashes(pg_result($resaco, $conresaco, 'l49_vlrun')) . "','$this->l49_vlrun'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["l49_vlrtot"]) || $this->l49_vlrtot != "")
            $resac = db_query("insert into db_acount values($acount,1010829,1013445,'" . AddSlashes(pg_result($resaco, $conresaco, 'l49_vlrtot')) . "','$this->l49_vlrtot'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["l49_vlrdesc"]) || $this->l49_vlrdesc != "")
            $resac = db_query("insert into db_acount values($acount,1010829,1013446,'" . AddSlashes(pg_result($resaco, $conresaco, 'l49_vlrdesc')) . "','$this->l49_vlrdesc'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
      }
    }
    $result = db_query($sql);
    if (!$result) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = "liclicitemlances não Alterado. Alteração Abortada.\\n";
      $this->erro_sql .= "Valores : " . $this->l49_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "liclicitemlances não foi Alterado. Alteração Executada.\\n";
        $this->erro_sql .= "Valores : " . $this->l49_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Alteração efetuada com sucesso.\\n";
        $this->erro_sql .= "Valores : " . $this->l49_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }

  public function excluir($l49_sequencial = null, $dbwhere = null)
  {
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
      && ($lSessaoDesativarAccount === false))) {

      if (empty($dbwhere)) {

        $resaco = $this->sql_record($this->sql_query_file($l49_sequencial));
      } else {
        $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
      }
      if (($resaco != false) || ($this->numrows != 0)) {

        for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

          $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac, 0, 0);
          $resac  = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
          $resac  = db_query("insert into db_acountkey values($acount,1013435,'$l49_sequencial','E')");
          $resac  = db_query("insert into db_acount values($acount,1010829,1013435,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l49_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          $resac  = db_query("insert into db_acount values($acount,1010829,1013436,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l49_liclicitem')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          $resac  = db_query("insert into db_acount values($acount,1010829,1013438,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l49_data')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          $resac  = db_query("insert into db_acount values($acount,1010829,1013439,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l49_hora')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          $resac  = db_query("insert into db_acount values($acount,1010829,1013440,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l49_fornecedor')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          $resac  = db_query("insert into db_acount values($acount,1010829,1013441,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l49_valido')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          $resac  = db_query("insert into db_acount values($acount,1010829,1013442,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l49_cancelado')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          $resac  = db_query("insert into db_acount values($acount,1010829,1013443,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l49_justificativa')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          $resac  = db_query("insert into db_acount values($acount,1010829,1013444,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l49_vlrun')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          $resac  = db_query("insert into db_acount values($acount,1010829,1013445,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l49_vlrtot')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          $resac  = db_query("insert into db_acount values($acount,1010829,1013446,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l49_vlrdesc')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
      }
    }
    $sql = " delete from liclicitemlances
                    where ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($l49_sequencial)) {
        if (!empty($sql2)) {
          $sql2 .= " and ";
        }
        $sql2 .= " l49_sequencial = $l49_sequencial ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql . $sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = "liclicitemlances não Excluído. Exclusão Abortada.\\n";
      $this->erro_sql .= "Valores : " . $l49_sequencial;
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "liclicitemlances não Encontrado. Exclusão não Efetuada.\\n";
        $this->erro_sql .= "Valores : " . $l49_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
        $this->erro_sql .= "Valores : " . $l49_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
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
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    $this->numrows = pg_num_rows($result);
    if ($this->numrows == 0) {
      $this->erro_banco = "";
      $this->erro_sql   = "Record Vazio na Tabela:liclicitemlances";
      $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }

  public function sql_query($l49_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
  {

    $sql  = "select {$campos}";
    $sql .= "  from liclicitemlances ";
    $sql .= "      inner join pcorcamforne  on  pcorcamforne.pc21_orcamforne = liclicitemlances.l49_fornecedor";
    $sql .= "      inner join liclicitem  on  liclicitem.l21_codigo = liclicitemlances.l49_liclicitem";
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = pcorcamforne.pc21_numcgm";
    $sql .= "      inner join pcorcam  on  pcorcam.pc20_codorc = pcorcamforne.pc21_codorc";
    $sql .= "      inner join pcprocitem  on  pcprocitem.pc81_codprocitem = liclicitem.l21_codpcprocitem";
    $sql .= "      inner join liclicita  on  liclicita.l20_codigo = liclicitem.l21_codliclicita";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($l49_sequencial)) {
        $sql2 .= " where liclicitemlances.l49_sequencial = $l49_sequencial ";
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

  public function sql_query_file($l49_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
  {

    $sql  = "select {$campos} ";
    $sql .= "  from liclicitemlances ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($l49_sequencial)) {
        $sql2 .= " where liclicitemlances.l49_sequencial = $l49_sequencial ";
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
