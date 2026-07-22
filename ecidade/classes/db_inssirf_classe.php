<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

class cl_inssirf
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
    public $r33_instit = 0;
    public $r33_codigo = 0;
    public $r33_anousu = 0;
    public $r33_mesusu = 0;
    public $r33_codtab = 0;
    public $r33_inic = 0;
    public $r33_fim = 0;
    public $r33_perc = 0;
    public $r33_deduzi = 0;
    public $r33_nome = null;
    public $r33_tipo = null;
    public $r33_rubmat = null;
    public $r33_ppatro = null;
    public $r33_rubsau = null;
    public $r33_rubaci = null;
    public $r33_basfer = null;
    public $r33_basfet = null;
    public $r33_tinati = null;
    public $r33_codele = null;
    public $r33_rubprorrogacaomaternidade = null;
    public $r33_rubfamiliar = null;
    public $r33_rublicencapremio = null;
    public $r33_tiposegregacao = null;
   // cria propriedade com as variaveis do arquivo
    public $campos = "
                 r33_instit = int4 = Cod. Instituição
                 r33_codigo = int8 = Código da tabela
                 r33_anousu = int4 = Ano do Exercicio
                 r33_mesusu = int4 = Mes do Exercicio
                 r33_codtab = int4 = Tabela
                 r33_inic = float8 = Valor Inicial da Faixa
                 r33_fim = float8 = Valor Final da Faixa
                 r33_perc = float8 = Percentual
                 r33_deduzi = float8 = Deduzir
                 r33_nome = varchar(15) = Tabela
                 r33_tipo = varchar(1) = Tipo
                 r33_rubmat = varchar(4) = Rubrica salário maternidade
                 r33_ppatro = float8 = Percentual Previdência Patronal
                 r33_rubsau = varchar(4) = Rubrica Licença Saúde
                 r33_rubaci = varchar(4) = Rubrica Acidente de Trabalho
                 r33_basfer = varchar(4) = Base Previdência Férias
                 r33_basfet = varchar(4) = Base Previdência Férias (Total)
                 r33_tinati = float8 = Teto para Inativos
                 r33_codele = int4 = Código do Desdobramento
                 r33_rubprorrogacaomaternidade = varchar(4) = Rubrica prorrogação maternidade
                 r33_rubfamiliar = varchar(4) = Rubrica Cuidar de Familiar
                 r33_rublicencapremio = varchar(4) = Rubrica Licença Prêmio
                 r33_tiposegregacao = int4 = Campo Tipo Segregação
                 ";

    /**
     * cl_inssirf constructor.
     */
    public function __construct()
    {
        $this->rotulo = new rotulo("inssirf");
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
       $this->r33_instit = ($this->r33_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_instit"]:$this->r33_instit);
       $this->r33_codigo = ($this->r33_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_codigo"]:$this->r33_codigo);
       $this->r33_anousu = ($this->r33_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_anousu"]:$this->r33_anousu);
       $this->r33_mesusu = ($this->r33_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_mesusu"]:$this->r33_mesusu);
       $this->r33_codtab = ($this->r33_codtab == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_codtab"]:$this->r33_codtab);
       $this->r33_inic = ($this->r33_inic == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_inic"]:$this->r33_inic);
       $this->r33_fim = ($this->r33_fim == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_fim"]:$this->r33_fim);
       $this->r33_perc = ($this->r33_perc == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_perc"]:$this->r33_perc);
       $this->r33_deduzi = ($this->r33_deduzi == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_deduzi"]:$this->r33_deduzi);
       $this->r33_nome = ($this->r33_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_nome"]:$this->r33_nome);
       $this->r33_tipo = ($this->r33_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_tipo"]:$this->r33_tipo);
       $this->r33_rubmat = ($this->r33_rubmat == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_rubmat"]:$this->r33_rubmat);
       $this->r33_ppatro = ($this->r33_ppatro == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_ppatro"]:$this->r33_ppatro);
       $this->r33_rubsau = ($this->r33_rubsau == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_rubsau"]:$this->r33_rubsau);
       $this->r33_rubaci = ($this->r33_rubaci == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_rubaci"]:$this->r33_rubaci);
       $this->r33_basfer = ($this->r33_basfer == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_basfer"]:$this->r33_basfer);
       $this->r33_basfet = ($this->r33_basfet == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_basfet"]:$this->r33_basfet);
       $this->r33_tinati = ($this->r33_tinati == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_tinati"]:$this->r33_tinati);
       $this->r33_codele = ($this->r33_codele == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_codele"]:$this->r33_codele);
       $this->r33_rubprorrogacaomaternidade = ($this->r33_rubprorrogacaomaternidade == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_rubprorrogacaomaternidade"]:$this->r33_rubprorrogacaomaternidade);
       $this->r33_rubfamiliar = ($this->r33_rubfamiliar == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_rubfamiliar"]:$this->r33_rubfamiliar);
       $this->r33_rublicencapremio = ($this->r33_rublicencapremio == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_rublicencapremio"]:$this->r33_rublicencapremio);
       $this->r33_tiposegregacao = ($this->r33_tiposegregacao == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_tiposegregacao"]:$this->r33_tiposegregacao);
     }else{
       $this->r33_instit = ($this->r33_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_instit"]:$this->r33_instit);
       $this->r33_codigo = ($this->r33_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["r33_codigo"]:$this->r33_codigo);
     }
   }

    public function incluir($r33_codigo,$r33_instit)
    {
      $this->atualizacampos();
      if ($this->r33_instit === '' || $this->r33_instit === null) {
          $this->erro_sql = " Campo Cod. Instituição não informado.";
          $this->erro_campo = "r33_instit";
          $this->erro_banco = "";
          $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          return false;
      }
      if ($this->r33_anousu === '' || $this->r33_anousu === null) {
          $this->erro_sql = " Campo Ano do Exercicio não informado.";
          $this->erro_campo = "r33_anousu";
          $this->erro_banco = "";
          $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          return false;
      }
      if ($this->r33_mesusu === '' || $this->r33_mesusu === null) {
          $this->erro_sql = " Campo Mes do Exercicio não informado.";
          $this->erro_campo = "r33_mesusu";
          $this->erro_banco = "";
          $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          return false;
      }
      if ($this->r33_codtab === '' || $this->r33_codtab === null) {
          $this->erro_sql = " Campo Tabela não informado.";
          $this->erro_campo = "r33_codtab";
          $this->erro_banco = "";
          $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          return false;
      }
      if ($this->r33_inic === '' || $this->r33_inic === null) {
          $this->erro_sql = " Campo Valor Inicial da Faixa não informado.";
          $this->erro_campo = "r33_inic";
          $this->erro_banco = "";
          $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          return false;
      }
      if ($this->r33_fim === '' || $this->r33_fim === null) {
          $this->erro_sql = " Campo Valor Final da Faixa não informado.";
          $this->erro_campo = "r33_fim";
          $this->erro_banco = "";
          $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          return false;
      }
      if ($this->r33_perc === '' || $this->r33_perc === null) {
          $this->erro_sql = " Campo Percentual não informado.";
          $this->erro_campo = "r33_perc";
          $this->erro_banco = "";
          $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          return false;
      }
      if ($this->r33_deduzi === '' || $this->r33_deduzi === null) {
          $this->erro_sql = " Campo Deduzir não informado.";
          $this->erro_campo = "r33_deduzi";
          $this->erro_banco = "";
          $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          return false;
      }
      if ($this->r33_ppatro === null || $this->r33_ppatro === '') {
          $this->r33_ppatro = "0";
      }
      if ($this->r33_tinati === null || $this->r33_tinati === '') {
          $this->r33_tinati = "0";
      }
      if ($this->r33_rubfamiliar === null || $this->r33_rubfamiliar === '') {
          $this->r33_rubfamiliar = "0";
      }
      if ($r33_codigo === '' || $r33_codigo === null || $r33_codigo === 0) {
           $result = db_query("select nextval('inssirf_r33_codigo_seq')");
           if (!$result) {
               $this->erro_banco = str_replace("\n", "", @pg_last_error());
               $this->erro_sql = "Verifique o cadastro da sequencia: inssirf_r33_codigo_seq do campo: r33_codigo";
               $this->erro_msg = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
               $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
               $this->erro_status = "0";
               return false;
           }
           $this->r33_codigo = pg_result($result, 0, 0);
      } else {
          $result = db_query("SELECT last_value FROM inssirf_r33_codigo_seq");
          if ($result && pg_result($result, 0, 0) < $r33_codigo) {
              $this->erro_sql = " Campo r33_codigo maior que último número da sequencia.";
              $this->erro_banco = "Sequencia menor que este número.";
              $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
              $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
              $this->erro_status = "0";
              return false;
          } else {
              $this->r33_codigo = $r33_codigo;
          }
      }
      if ($this->r33_codigo === null || $this->r33_codigo === '' || $this->r33_codigo === 0) {
          $this->erro_sql = " Campo r33_codigo não declarado.";
          $this->erro_banco = "Chave Primaria zerada.";
          $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = '0';
          return false;
      }
      if ($this->r33_instit === null || $this->r33_instit === '' || $this->r33_instit === 0) {
          $this->erro_sql = " Campo r33_instit não declarado.";
          $this->erro_banco = "Chave Primaria zerada.";
          $this->erro_msg = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = '0';
          return false;
      }
      $sql = "
            INSERT INTO inssirf (
                r33_instit
                ,r33_codigo
                ,r33_anousu
                ,r33_mesusu
                ,r33_codtab
                ,r33_inic
                ,r33_fim
                ,r33_perc
                ,r33_deduzi
                ,r33_nome
                ,r33_tipo
                ,r33_rubmat
                ,r33_ppatro
                ,r33_rubsau
                ,r33_rubaci
                ,r33_basfer
                ,r33_basfet
                ,r33_tinati
                ,r33_codele
                ,r33_rubprorrogacaomaternidade
                ,r33_rubfamiliar
                ,r33_rublicencapremio
                ,r33_tiposegregacao
            ) VALUES (
                 " . ($this->r33_instit === null || $this->r33_instit === '' ? 'NULL' : $this->r33_instit) . "
                ," . ($this->r33_codigo === null || $this->r33_codigo === '' ? 'NULL' : $this->r33_codigo) . "
                ," . ($this->r33_anousu === null || $this->r33_anousu === '' ? 'NULL' : $this->r33_anousu) . "
                ," . ($this->r33_mesusu === null || $this->r33_mesusu === '' ? 'NULL' : $this->r33_mesusu) . "
                ," . ($this->r33_codtab === null || $this->r33_codtab === '' ? 'NULL' : $this->r33_codtab) . "
                ," . ($this->r33_inic === null || $this->r33_inic === '' ? 'NULL' : $this->r33_inic) . "
                ," . ($this->r33_fim === null || $this->r33_fim === '' ? 'NULL' : $this->r33_fim) . "
                ," . ($this->r33_perc === null || $this->r33_perc === '' ? 'NULL' : $this->r33_perc) . "
                ," . ($this->r33_deduzi === null || $this->r33_deduzi === '' ? 'NULL' : $this->r33_deduzi) . "
                ," . ($this->r33_nome === null || $this->r33_nome === '' ? 'NULL' : "'{$this->r33_nome}'") . "
                ," . ($this->r33_tipo === null || $this->r33_tipo === '' ? 'NULL' : "'{$this->r33_tipo}'") . "
                ," . ($this->r33_rubmat === null || $this->r33_rubmat === '' ? 'NULL' : "'{$this->r33_rubmat}'") . "
                ," . ($this->r33_ppatro === null || $this->r33_ppatro === '' ? 'NULL' : $this->r33_ppatro) . "
                ," . ($this->r33_rubsau === null || $this->r33_rubsau === '' ? 'NULL' : "'{$this->r33_rubsau}'") . "
                ," . ($this->r33_rubaci === null || $this->r33_rubaci === '' ? 'NULL' : "'{$this->r33_rubaci}'") . "
                ," . ($this->r33_basfer === null || $this->r33_basfer === '' ? 'NULL' : "'{$this->r33_basfer}'") . "
                ," . ($this->r33_basfet === null || $this->r33_basfet === '' ? 'NULL' : "'{$this->r33_basfet}'") . "
                ," . ($this->r33_tinati === null || $this->r33_tinati === '' ? 'NULL' : $this->r33_tinati) . "
                ," . ($this->r33_codele === null || $this->r33_codele === '' ? 'NULL' : $this->r33_codele) . "
                ," . ($this->r33_rubprorrogacaomaternidade === null || $this->r33_rubprorrogacaomaternidade === '' ? 'NULL' : "'{$this->r33_rubprorrogacaomaternidade}'") . "
                ," . ($this->r33_rubfamiliar === null || $this->r33_rubfamiliar === '' ? 'NULL' : "'{$this->r33_rubfamiliar}'") . "
                ," . ($this->r33_rublicencapremio === null || $this->r33_rublicencapremio === '' ? 'NULL' : "'{$this->r33_rublicencapremio}'") . "
                ," . ($this->r33_tiposegregacao === null || $this->r33_tiposegregacao === '' ? 'NULL' : "'{$this->r33_tiposegregacao}'") . "
            )
        ";
      $result = db_query($sql);
      if($result==false){
        $this->erro_banco = str_replace("\n","",@pg_last_error());
        if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
          $this->erro_sql   = "Cadastro das Tabelas ($this->r33_codigo."-".$this->r33_instit) não Incluído. Inclusão Abortada.";
          $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_banco = "Cadastro das Tabelas já Cadastrado";
          $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        }else{
          $this->erro_sql   = "Cadastro das Tabelas ($this->r33_codigo."-".$this->r33_instit) não Incluído. Inclusão Abortada.";
          $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        }
        $this->erro_status = "0";
        $this->numrows_incluir= 0;
        return false;
      }
      $this->erro_banco = "";
      $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
          $this->erro_sql .= "Valores : ".$this->r33_codigo."-".$this->r33_instit;
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "1";
      $this->numrows_incluir= pg_affected_rows($result);
      $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
      if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
        && ($lSessaoDesativarAccount === false))) {
      
        $resaco = $this->sql_record($this->sql_query_file($this->r33_codigo,$this->r33_instit  ));
        if(($resaco!=false)||($this->numrows!=0)){
      
          $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac,0,0);
          $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
          $resac = db_query("insert into db_acountkey values($acount,8826,'$this->r33_codigo','I')");
          $resac = db_query("insert into db_acountkey values($acount,9894,'$this->r33_instit','I')");
          $resac = db_query("insert into db_acount values($acount,561,9894,'','".AddSlashes(pg_result($resaco,0,'r33_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac = db_query("insert into db_acount values($acount,561,8826,'','".AddSlashes(pg_result($resaco,0,'r33_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac = db_query("insert into db_acount values($acount,561,4009,'','".AddSlashes(pg_result($resaco,0,'r33_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac = db_query("insert into db_acount values($acount,561,4010,'','".AddSlashes(pg_result($resaco,0,'r33_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac = db_query("insert into db_acount values($acount,561,4011,'','".AddSlashes(pg_result($resaco,0,'r33_codtab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac = db_query("insert into db_acount values($acount,561,4012,'','".AddSlashes(pg_result($resaco,0,'r33_inic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac = db_query("insert into db_acount values($acount,561,4013,'','".AddSlashes(pg_result($resaco,0,'r33_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac = db_query("insert into db_acount values($acount,561,4014,'','".AddSlashes(pg_result($resaco,0,'r33_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac = db_query("insert into db_acount values($acount,561,4015,'','".AddSlashes(pg_result($resaco,0,'r33_deduzi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac = db_query("insert into db_acount values($acount,561,4016,'','".AddSlashes(pg_result($resaco,0,'r33_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac = db_query("insert into db_acount values($acount,561,4017,'','".AddSlashes(pg_result($resaco,0,'r33_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac = db_query("insert into db_acount values($acount,561,4018,'','".AddSlashes(pg_result($resaco,0,'r33_rubmat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac = db_query("insert into db_acount values($acount,561,4019,'','".AddSlashes(pg_result($resaco,0,'r33_ppatro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac = db_query("insert into db_acount values($acount,561,4020,'','".AddSlashes(pg_result($resaco,0,'r33_rubsau'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac = db_query("insert into db_acount values($acount,561,8828,'','".AddSlashes(pg_result($resaco,0,'r33_rubaci'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac = db_query("insert into db_acount values($acount,561,4597,'','".AddSlashes(pg_result($resaco,0,'r33_basfer'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac = db_query("insert into db_acount values($acount,561,4598,'','".AddSlashes(pg_result($resaco,0,'r33_basfet'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac = db_query("insert into db_acount values($acount,561,8830,'','".AddSlashes(pg_result($resaco,0,'r33_tinati'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac = db_query("insert into db_acount values($acount,561,19145,'','".AddSlashes(pg_result($resaco,0,'r33_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac = db_query("insert into db_acount values($acount,561,1010763,'','".AddSlashes(pg_result($resaco,0,'r33_rubprorrogacaomaternidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac = db_query("insert into db_acount values($acount,561,1010771,'','".AddSlashes(pg_result($resaco,0,'r33_rubfamiliar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac = db_query("insert into db_acount values($acount,561,1010799,'','".AddSlashes(pg_result($resaco,0,'r33_rublicencapremio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
          $resac = db_query("insert into db_acount values($acount,561,1013710,'','".AddSlashes(pg_result($resaco,0,'r33_tiposegregacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
        }
      }
      return true;
    }

    public function alterar($r33_codigo = null,$r33_instit = null)
    {
        $this->atualizacampos();
        $sql = " update inssirf set ";
        $virgula = "";
        if (empty($r33_instit)) {
            throw new Exception('Campo r33_instit é obrigatório!');
        }
        $this->r33_instit = $r33_instit;
        if (empty($r33_codigo)) {
            throw new Exception('Campo r33_codigo é obrigatório!');
        }
        $this->r33_codigo = $r33_codigo;
        if (trim($this->r33_anousu) !== '' && $this->r33_anousu !== null) {
            $sql .= "{$virgula} r33_anousu = {$this->r33_anousu} ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Ano do Exercicio" é obrigatório.');
        }
        if (trim($this->r33_mesusu) !== '' && $this->r33_mesusu !== null) {
            $sql .= "{$virgula} r33_mesusu = {$this->r33_mesusu} ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Mes do Exercicio" é obrigatório.');
        }
        if (trim($this->r33_codtab) !== '' && $this->r33_codtab !== null) {
            $sql .= "{$virgula} r33_codtab = {$this->r33_codtab} ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Tabela" é obrigatório.');
        }
        if (trim($this->r33_inic) !== '' && $this->r33_inic !== null) {
            $sql .= "{$virgula} r33_inic = {$this->r33_inic} ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Valor Inicial da Faixa" é obrigatório.');
        }
        if (trim($this->r33_fim) !== '' && $this->r33_fim !== null) {
            $sql .= "{$virgula} r33_fim = {$this->r33_fim} ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Valor Final da Faixa" é obrigatório.');
        }
        if (trim($this->r33_perc) !== '' && $this->r33_perc !== null) {
            $sql .= "{$virgula} r33_perc = {$this->r33_perc} ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Percentual" é obrigatório.');
        }
        if (trim($this->r33_deduzi) !== '' && $this->r33_deduzi !== null) {
            $sql .= "{$virgula} r33_deduzi = {$this->r33_deduzi} ";
            $virgula = ',';
        } else {
            throw new Exception('Campo "Deduzir" é obrigatório.');
        }
        if (trim($this->r33_nome) !== '' && $this->r33_nome !== null) {
            $sql .= "{$virgula} r33_nome = '{$this->r33_nome}' ";
            $virgula = ',';
        } else {
            $sql .= "{$virgula} r33_nome = NULL ";
            $virgula = ',';
        }
        if (trim($this->r33_tipo) !== '' && $this->r33_tipo !== null) {
            $sql .= "{$virgula} r33_tipo = '{$this->r33_tipo}' ";
            $virgula = ',';
        } else {
            $sql .= "{$virgula} r33_tipo = NULL ";
            $virgula = ',';
        }
        if (trim($this->r33_rubmat) !== '' && $this->r33_rubmat !== null) {
            $sql .= "{$virgula} r33_rubmat = '{$this->r33_rubmat}' ";
            $virgula = ',';
        } else {
            $sql .= "{$virgula} r33_rubmat = NULL ";
            $virgula = ',';
        }
        if (trim($this->r33_ppatro) !== '' && $this->r33_ppatro !== null) {
            $sql .= "{$virgula} r33_ppatro = {$this->r33_ppatro} ";
            $virgula = ',';
        } else {
            $sql .= "{$virgula} r33_ppatro = NULL ";
            $virgula = ',';
        }
        if (trim($this->r33_rubsau) !== '' && $this->r33_rubsau !== null) {
            $sql .= "{$virgula} r33_rubsau = '{$this->r33_rubsau}' ";
            $virgula = ',';
        } else {
            $sql .= "{$virgula} r33_rubsau = NULL ";
            $virgula = ',';
        }
        if (trim($this->r33_rubaci) !== '' && $this->r33_rubaci !== null) {
            $sql .= "{$virgula} r33_rubaci = '{$this->r33_rubaci}' ";
            $virgula = ',';
        } else {
            $sql .= "{$virgula} r33_rubaci = NULL ";
            $virgula = ',';
        }
        if (trim($this->r33_basfer) !== '' && $this->r33_basfer !== null) {
            $sql .= "{$virgula} r33_basfer = '{$this->r33_basfer}' ";
            $virgula = ',';
        } else {
            $sql .= "{$virgula} r33_basfer = NULL ";
            $virgula = ',';
        }
        if (trim($this->r33_basfet) !== '' && $this->r33_basfet !== null) {
            $sql .= "{$virgula} r33_basfet = '{$this->r33_basfet}' ";
            $virgula = ',';
        } else {
            $sql .= "{$virgula} r33_basfet = NULL ";
            $virgula = ',';
        }
        if (trim($this->r33_tinati) !== '' && $this->r33_tinati !== null) {
            $sql .= "{$virgula} r33_tinati = {$this->r33_tinati} ";
            $virgula = ',';
        } else {
            $sql .= "{$virgula} r33_tinati = NULL ";
            $virgula = ',';
        }
        if (trim($this->r33_codele) !== '' && $this->r33_codele !== null) {
            $sql .= "{$virgula} r33_codele = {$this->r33_codele} ";
            $virgula = ',';
        } else {
            $sql .= "{$virgula} r33_codele = NULL ";
            $virgula = ',';
        }
        if (trim($this->r33_rubprorrogacaomaternidade) !== '' && $this->r33_rubprorrogacaomaternidade !== null) {
            $sql .= "{$virgula} r33_rubprorrogacaomaternidade = '{$this->r33_rubprorrogacaomaternidade}' ";
            $virgula = ',';
        } else {
            $sql .= "{$virgula} r33_rubprorrogacaomaternidade = NULL ";
            $virgula = ',';
        }
        if (trim($this->r33_rubfamiliar) !== '' && $this->r33_rubfamiliar !== null) {
            $sql .= "{$virgula} r33_rubfamiliar = '{$this->r33_rubfamiliar}' ";
            $virgula = ',';
        } else {
            $sql .= "{$virgula} r33_rubfamiliar = NULL ";
            $virgula = ',';
        }
        if (trim($this->r33_rublicencapremio) !== '' && $this->r33_rublicencapremio !== null) {
            $sql .= "{$virgula} r33_rublicencapremio = '{$this->r33_rublicencapremio}' ";
        } else {
            $sql .= "{$virgula} r33_rublicencapremio = NULL ";
        }

        if(trim($this->r33_tiposegregacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r33_tiposegregacao"])){
           if(trim($this->r33_tiposegregacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["r33_tiposegregacao"])){
              $this->r33_tiposegregacao = "0" ;
           }
          $sql  .= $virgula." r33_tiposegregacao = $this->r33_tiposegregacao ";
          $virgula = ",";
        }
        
        if ($r33_codigo !== '' && $r33_codigo !== null && $r33_codigo !== 0) {
          $sql .= ' WHERE';
          $sql .= " r33_codigo = {$r33_codigo}";
        }
        
        if ($r33_instit !== '' && $r33_instit !== null && $r33_instit !== 0) {
          $sql .= " AND r33_instit = {$r33_instit}";
        }
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount) && ($lSessaoDesativarAccount === false))) {
          $resaco = $this->sql_record($this->sql_query_file($this->r33_codigo,$this->r33_instit));
          if ($this->numrows > 0) {
            for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
              $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
              $acount = pg_result($resac,0,0);
              $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
              $resac = db_query("insert into db_acountkey values($acount,8826,'$this->r33_codigo','A')");
              $resac = db_query("insert into db_acountkey values($acount,9894,'$this->r33_instit','A')");
              if (isset($GLOBALS["HTTP_POST_VARS"]["r33_instit"]) || $this->r33_instit != "")
                $resac = db_query("insert into db_acount values($acount,561,9894,'".AddSlashes(pg_result($resaco,$conresaco,'r33_instit'))."','$this->r33_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              if (isset($GLOBALS["HTTP_POST_VARS"]["r33_codigo"]) || $this->r33_codigo != "")
                $resac = db_query("insert into db_acount values($acount,561,8826,'".AddSlashes(pg_result($resaco,$conresaco,'r33_codigo'))."','$this->r33_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              if (isset($GLOBALS["HTTP_POST_VARS"]["r33_anousu"]) || $this->r33_anousu != "")
                $resac = db_query("insert into db_acount values($acount,561,4009,'".AddSlashes(pg_result($resaco,$conresaco,'r33_anousu'))."','$this->r33_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              if (isset($GLOBALS["HTTP_POST_VARS"]["r33_mesusu"]) || $this->r33_mesusu != "")
                $resac = db_query("insert into db_acount values($acount,561,4010,'".AddSlashes(pg_result($resaco,$conresaco,'r33_mesusu'))."','$this->r33_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              if (isset($GLOBALS["HTTP_POST_VARS"]["r33_codtab"]) || $this->r33_codtab != "")
                $resac = db_query("insert into db_acount values($acount,561,4011,'".AddSlashes(pg_result($resaco,$conresaco,'r33_codtab'))."','$this->r33_codtab',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              if (isset($GLOBALS["HTTP_POST_VARS"]["r33_inic"]) || $this->r33_inic != "")
                $resac = db_query("insert into db_acount values($acount,561,4012,'".AddSlashes(pg_result($resaco,$conresaco,'r33_inic'))."','$this->r33_inic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              if (isset($GLOBALS["HTTP_POST_VARS"]["r33_fim"]) || $this->r33_fim != "")
                $resac = db_query("insert into db_acount values($acount,561,4013,'".AddSlashes(pg_result($resaco,$conresaco,'r33_fim'))."','$this->r33_fim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              if (isset($GLOBALS["HTTP_POST_VARS"]["r33_perc"]) || $this->r33_perc != "")
                $resac = db_query("insert into db_acount values($acount,561,4014,'".AddSlashes(pg_result($resaco,$conresaco,'r33_perc'))."','$this->r33_perc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              if (isset($GLOBALS["HTTP_POST_VARS"]["r33_deduzi"]) || $this->r33_deduzi != "")
                $resac = db_query("insert into db_acount values($acount,561,4015,'".AddSlashes(pg_result($resaco,$conresaco,'r33_deduzi'))."','$this->r33_deduzi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              if (isset($GLOBALS["HTTP_POST_VARS"]["r33_nome"]) || $this->r33_nome != "")
                $resac = db_query("insert into db_acount values($acount,561,4016,'".AddSlashes(pg_result($resaco,$conresaco,'r33_nome'))."','$this->r33_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              if (isset($GLOBALS["HTTP_POST_VARS"]["r33_tipo"]) || $this->r33_tipo != "")
                $resac = db_query("insert into db_acount values($acount,561,4017,'".AddSlashes(pg_result($resaco,$conresaco,'r33_tipo'))."','$this->r33_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              if (isset($GLOBALS["HTTP_POST_VARS"]["r33_rubmat"]) || $this->r33_rubmat != "")
                $resac = db_query("insert into db_acount values($acount,561,4018,'".AddSlashes(pg_result($resaco,$conresaco,'r33_rubmat'))."','$this->r33_rubmat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              if (isset($GLOBALS["HTTP_POST_VARS"]["r33_ppatro"]) || $this->r33_ppatro != "")
                $resac = db_query("insert into db_acount values($acount,561,4019,'".AddSlashes(pg_result($resaco,$conresaco,'r33_ppatro'))."','$this->r33_ppatro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              if (isset($GLOBALS["HTTP_POST_VARS"]["r33_rubsau"]) || $this->r33_rubsau != "")
                $resac = db_query("insert into db_acount values($acount,561,4020,'".AddSlashes(pg_result($resaco,$conresaco,'r33_rubsau'))."','$this->r33_rubsau',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              if (isset($GLOBALS["HTTP_POST_VARS"]["r33_rubaci"]) || $this->r33_rubaci != "")
                $resac = db_query("insert into db_acount values($acount,561,8828,'".AddSlashes(pg_result($resaco,$conresaco,'r33_rubaci'))."','$this->r33_rubaci',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              if (isset($GLOBALS["HTTP_POST_VARS"]["r33_basfer"]) || $this->r33_basfer != "")
                $resac = db_query("insert into db_acount values($acount,561,4597,'".AddSlashes(pg_result($resaco,$conresaco,'r33_basfer'))."','$this->r33_basfer',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              if (isset($GLOBALS["HTTP_POST_VARS"]["r33_basfet"]) || $this->r33_basfet != "")
                $resac = db_query("insert into db_acount values($acount,561,4598,'".AddSlashes(pg_result($resaco,$conresaco,'r33_basfet'))."','$this->r33_basfet',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              if (isset($GLOBALS["HTTP_POST_VARS"]["r33_tinati"]) || $this->r33_tinati != "")
                $resac = db_query("insert into db_acount values($acount,561,8830,'".AddSlashes(pg_result($resaco,$conresaco,'r33_tinati'))."','$this->r33_tinati',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              if (isset($GLOBALS["HTTP_POST_VARS"]["r33_codele"]) || $this->r33_codele != "")
                $resac = db_query("insert into db_acount values($acount,561,19145,'".AddSlashes(pg_result($resaco,$conresaco,'r33_codele'))."','$this->r33_codele',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              if (isset($GLOBALS["HTTP_POST_VARS"]["r33_rubprorrogacaomaternidade"]) || $this->r33_rubprorrogacaomaternidade != "")
                $resac = db_query("insert into db_acount values($acount,561,1010763,'".AddSlashes(pg_result($resaco,$conresaco,'r33_rubprorrogacaomaternidade'))."','$this->r33_rubprorrogacaomaternidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              if (isset($GLOBALS["HTTP_POST_VARS"]["r33_rubfamiliar"]) || $this->r33_rubfamiliar != "")
                $resac = db_query("insert into db_acount values($acount,561,1010771,'".AddSlashes(pg_result($resaco,$conresaco,'r33_rubfamiliar'))."','$this->r33_rubfamiliar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              if (isset($GLOBALS["HTTP_POST_VARS"]["r33_rublicencapremio"]) || $this->r33_rublicencapremio != "")
                $resac = db_query("insert into db_acount values($acount,561,1010799,'".AddSlashes(pg_result($resaco,$conresaco,'r33_rublicencapremio'))."','$this->r33_rublicencapremio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
              if (isset($GLOBALS["HTTP_POST_VARS"]["r33_tiposegregacao"]) || $this->r33_tiposegregacao != "")
                $resac = db_query("insert into db_acount values($acount,561,1013710,'".AddSlashes(pg_result($resaco,$conresaco,'r33_tiposegregacao'))."','$this->r33_tiposegregacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
            }
          }
        }
        $result = db_query($sql);
        if (!$result) {
          $this->erro_banco = str_replace("\n","",@pg_last_error());
          $this->erro_sql   = "Cadastro das Tabelas não Alterado. Alteração Abortada.\\n";
          $this->erro_sql .= "Valores : ".$this->r33_codigo."-".$this->r33_instit;
          $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          $this->numrows_alterar = 0;
          return false;
        } else {
          if (pg_affected_rows($result) == 0) {
            $this->erro_banco = "";
            $this->erro_sql = "Cadastro das Tabelas não foi Alterado. Alteração Executada.\\n";
            $this->erro_sql .= "Valores : ".$this->r33_codigo."-".$this->r33_instit;
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "1";
            $this->numrows_alterar = 0;
            return true;
          } else {
            $this->erro_banco = "";
            $this->erro_sql = "Alteração efetuada com sucesso.\\n";
            $this->erro_sql .= "Valores : ".$this->r33_codigo."-".$this->r33_instit;
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "1";
            $this->numrows_alterar = pg_affected_rows($result);
            return true;
          }
        }
    }

    public function excluir($r33_codigo=null,$r33_instit=null, $dbwhere = null)
    {
      if (empty($r33_codigo)) {
          throw new Exception('Campo r33_codigo é obrigatório!');
      }
      if (empty($r33_instit)) {
          throw new Exception('Campo r33_instit é obrigatório!');
      }
      $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
      if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount) && ($lSessaoDesativarAccount === false))) {
       if (empty($dbwhere)) {
         $resaco = $this->sql_record($this->sql_query_file($r33_codigo,$r33_instit));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {
         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,8826,'$r33_codigo','E')");
           $resac  = db_query("insert into db_acountkey values($acount,9894,'$r33_instit','E')");
           $resac  = db_query("insert into db_acount values($acount,561,9894,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,561,8826,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,561,4009,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,561,4010,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,561,4011,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_codtab'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,561,4012,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_inic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,561,4013,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,561,4014,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,561,4015,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_deduzi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,561,4016,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,561,4017,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,561,4018,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_rubmat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,561,4019,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_ppatro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,561,4020,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_rubsau'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,561,8828,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_rubaci'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,561,4597,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_basfer'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,561,4598,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_basfet'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,561,8830,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_tinati'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,561,19145,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_codele'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,561,1010763,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_rubprorrogacaomaternidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,561,1010771,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_rubfamiliar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,561,1010799,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_rublicencapremio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,561,1013710,'','".AddSlashes(pg_result($resaco,$iresaco,'r33_tiposegregacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
      }
      $sql = " delete from inssirf
                     where ";
      $sql2 = "";
      if (empty($dbwhere)) {
         if (!empty($r33_codigo)){
           if (!empty($sql2)) {
             $sql2 .= " and ";
           }
           $sql2 .= " r33_codigo = $r33_codigo ";
         }
         if (!empty($r33_instit)){
           if (!empty($sql2)) {
             $sql2 .= " and ";
           }
           $sql2 .= " r33_instit = $r33_instit ";
         }
      } else {
        $sql2 = $dbwhere;
      }
      $result = db_query($sql.$sql2);
      if ($result == false) {
        $this->erro_banco = str_replace("\n","",@pg_last_error());
        $this->erro_sql   = "Cadastro das Tabelas não Excluído. Exclusão Abortada.\\n";
        $this->erro_sql .= "Valores : ".$r33_codigo."-".$r33_instit;
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        $this->numrows_excluir = 0;
        return false;
      } else {
        if (pg_affected_rows($result) == 0) {
          $this->erro_banco = "";
          $this->erro_sql = "Cadastro das Tabelas não Encontrado. Exclusão não Efetuada.\\n";
          $this->erro_sql .= "Valores : ".$r33_codigo."-".$r33_instit;
          $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "1";
          $this->numrows_excluir = 0;
          return true;
        } else {
          $this->erro_banco = "";
          $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
          $this->erro_sql .= "Valores : ".$r33_codigo."-".$r33_instit;
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
          $this->erro_sql   = "Record Vazio na Tabela:inssirf";
          $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          return false;
       }
       return $result;
    }

    public function sql_query($r33_codigo = null,$r33_instit = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from inssirf ";
     $sql .= "      inner join db_config  on  db_config.codigo = inssirf.r33_instit";
     $sql .= "      left  join orcelemento  on  orcelemento.o56_codele = inssirf.r33_codele and  orcelemento.o56_anousu = inssirf.r33_anousu";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($r33_codigo)) {
         $sql2 .= " where inssirf.r33_codigo = $r33_codigo ";
       }
       if (!empty($r33_instit)) {
         if (!empty($sql2)) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         }
         $sql2 .= " inssirf.r33_instit = $r33_instit ";
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

    public function sql_query_file($r33_codigo = null,$r33_instit = null, $campos = "*", $ordem = null, $dbwhere = "", $limit = null) {

     $sql  = "select {$campos} ";
     $sql .= "  from inssirf ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($r33_codigo)){
         $sql2 .= " where inssirf.r33_codigo = $r33_codigo ";
       }
       if (!empty($r33_instit)){
         if ( !empty($sql2) ) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         }
         $sql2 .= " inssirf.r33_instit = $r33_instit ";
       }
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }

     if (!empty($limit)) {
         $sql .= " LIMIT {$limit} ";
     }

     return $sql;
  }

   function atualiza_incluir (){
  	 $this->incluir($this->r33_anousu,$this->r33_mesusu,$this->r33_codtab);
   }

   /**
 * sql_query_dados
 * @param null $r33_codigo
 * @param string $campos
 * @param null $ordem
 * @param string $dbwhere
 * @param null $limit
 * @return string
 */
function sql_query_dados ($r33_codigo = null, $campos = "*", $ordem = null, $dbwhere = "", $limit = null) {

	$sql = "select ";

	if ($campos != "*" ) {

        $campos_sql = explode("#", $campos);
		$virgula    = "";

		for ( $i=0; $i < sizeof($campos_sql); $i++ ) {

			$sql .= $virgula.$campos_sql[$i];
			$virgula = ",";
		}

	} else {
		$sql .= $campos;
	}

	$sql .= " from inssirf ";
	$sql .= "      left join rhrubricas a on a.rh27_rubric = inssirf.r33_rubmat and a.rh27_instit =  inssirf.r33_instit ";
	$sql .= "      left join rhrubricas b on b.rh27_rubric = inssirf.r33_rubsau and b.rh27_instit =  inssirf.r33_instit ";
	$sql .= "      left join rhrubricas c on c.rh27_rubric = inssirf.r33_rubaci and c.rh27_instit =  inssirf.r33_instit ";
	$sql .= "      left join bases      d on d.r08_codigo  = inssirf.r33_basfer                                         ";
	$sql .= "                             and d.r08_instit = inssirf.r33_instit                                         ";
	$sql .= "                             and d.r08_anousu = ".db_anofolha()."                                          ";
	$sql .= "                             and d.r08_mesusu = ".db_mesfolha()."                                          ";
	$sql .= "      left join bases      e on e.r08_codigo  = inssirf.r33_basfet                                         ";
	$sql .= "                             and d.r08_instit = inssirf.r33_instit                                         ";
	$sql .= "                             and d.r08_anousu = ".db_anofolha()."                                          ";
	$sql .= "                             and d.r08_mesusu = ".db_mesfolha()."                                          ";
	$sql .= "      left join orcelemento on orcelemento.o56_codele = inssirf.r33_codele                                 ";
	$sql .= "			                     and orcelemento.o56_anousu = ".db_anofolha()."                                  ";
    $sql .= "      left join rhrubricas f on f.rh27_rubric = inssirf.r33_rubprorrogacaomaternidade and f.rh27_instit =  inssirf.r33_instit ";
    $sql .= "      left join rhrubricas g on g.rh27_rubric = inssirf.r33_rubfamiliar and g.rh27_instit = inssirf.r33_instit ";
    $sql .= "      left join rhrubricas h on h.rh27_rubric = inssirf.r33_rublicencapremio and h.rh27_instit = inssirf.r33_instit ";

    $sql2 = "";

	if ( $dbwhere == "" ) {

		if ( $r33_codigo != null ){
			$sql2 .= " where inssirf.r33_codigo = $r33_codigo ";
		}

	} elseif ( $dbwhere != "" ) {
		$sql2 = " where $dbwhere";
	}

	$sql .= $sql2;

	if ( $ordem != null ) {

		$sql       .= " order by ";
		$campos_sql = explode("#",$ordem);
		$virgula    = "";

		for ( $i = 0; $i < sizeof($campos_sql); $i++ ) {

			$sql    .= $virgula.$campos_sql[$i];
			$virgula = ",";
		}
	}

	if (!empty($limit)) {
	    $sql .= " LIMIT {$limit}";
	}

	return $sql;
}

   /**
   * Retorna percentual patronal
   *
   * @param integer $iAno
   * @param integer $iMes
   * @param integer $iInstituicao
   * @access public
   * @return stdClass
   */
  public function getPercentuaisPatronais($iAno, $iMes, $iInstituicao = null ) {

    $iInstituicao = empty($iInstituicao) ? db_getsession("DB_instit") : $iInstituicao;

    /**
     * Monta SQL dos Valores Patronais
     */
    $sValoresPatronais  = " select distinct                     ";
    $sValoresPatronais .= "        r33_codtab,                  ";
    $sValoresPatronais .= "        r33_nome,                    ";
    $sValoresPatronais .= "        r33_ppatro                   ";
    $sValoresPatronais .= "   from inssirf                      ";
    $sValoresPatronais .= "  where r33_anousu = {$iAno}         ";
    $sValoresPatronais .= "    and r33_mesusu = {$iMes}         ";
    $sValoresPatronais .= "    and r33_codtab > 2               ";
    $sValoresPatronais .= "    and r33_instit = {$iInstituicao} ";

    $rsValoresPatronais    = db_query($sValoresPatronais);
    $iRowsValoresPatronais = pg_num_rows($rsValoresPatronais);

    if( !$rsValoresPatronais || $iRowsValoresPatronais == 0 ) {
      throw new BusinessException("Sem Valores Patronais Configurados!");
    }

    /**
     * Valores padrao para base dos valores patronais
     */
    $oValoresPatronais = new stdClass();
    $oValoresPatronais->aBasePrevidencia1 = (object) array("sNome" => "BASE PREV.1", "nValor" => 0);
    $oValoresPatronais->aBasePrevidencia2 = (object) array("sNome" => "BASE PREV.2", "nValor" => 0);
    $oValoresPatronais->aBasePrevidencia3 = (object) array("sNome" => "BASE PREV.3", "nValor" => 0);
    $oValoresPatronais->aBasePrevidencia4 = (object) array("sNome" => "BASE PREV.4", "nValor" => 0);

    $aValoresPatronais = db_utils::getCollectionByRecord($rsValoresPatronais);

    foreach ($aValoresPatronais as $oRowValPatronais) {

      switch ($oRowValPatronais->r33_codtab) {

        case 3:

          $oValoresPatronais->aBasePrevidencia1->sNome  = $oRowValPatronais->r33_nome;
          $oValoresPatronais->aBasePrevidencia1->nValor = $oRowValPatronais->r33_ppatro;
          break;
        case 4:

          $oValoresPatronais->aBasePrevidencia2->sNome  = $oRowValPatronais->r33_nome;
          $oValoresPatronais->aBasePrevidencia2->nValor = $oRowValPatronais->r33_ppatro;
          break;
        case 5:

          $oValoresPatronais->aBasePrevidencia3->sNome  = $oRowValPatronais->r33_nome;
          $oValoresPatronais->aBasePrevidencia3->nValor = $oRowValPatronais->r33_ppatro;
          break;
        case 6:

          $oValoresPatronais->aBasePrevidencia4->sNome  = $oRowValPatronais->r33_nome;
          $oValoresPatronais->aBasePrevidencia4->nValor = $oRowValPatronais->r33_ppatro;
          break;
      }
    }

    return $oValoresPatronais;
  }
}
