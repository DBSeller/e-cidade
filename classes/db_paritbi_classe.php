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

//MODULO: itbi
//CLASSE DA ENTIDADE paritbi
class cl_paritbi {
   // cria variaveis de erro
   public $rotulo     = null;
   public $query_sql  = null;
   public $numrows    = 0;
   public $numrows_incluir = 0;
   public $numrows_alterar = 0;
   public $numrows_excluir = 0;
   public $erro_status= null;
   public $erro_sql   = null;
   public $erro_banco = null;
   public $erro_msg   = null;
   public $erro_campo = null;
   public $pagina_retorno = null;
   /* Variáveis do Arquivo */
   public $it24_anousu = 0;
   public $it24_grupoespbenfurbana = 0;
   public $it24_grupotipobenfurbana = 0;
   public $it24_grupoespbenfrural = 0;
   public $it24_grupotipobenfrural = 0;
   public $it24_grupoutilterrarural = 0;
   public $it24_grupodistrterrarural = 0;
   public $it24_grupopadraoconstrutivobenurbana = 0;
   public $it24_diasvctoitbi = 0;
   public $it24_alteraguialib = 0;
   public $it24_impsituacaodeb = 'f';
   public $it24_taxabancaria = 0;
   public $it24_cgmobrigatorio = 'f';
   public $it24_solicitanotificacao = 'f';
   public $it24_carregavalorvenal = 'f';
   public $it24_matricrural = 'f';
   public $it24_comparavaloresavaliacao = 'f';
   public $it24_padraoconstrutivobrigatorio = 'f';
   public $it24_carregaconstrucoesbenfeitoriasitbi = 'f';
   public $it24_emiteguiaquitacao = 'f';
   // cria propriedade com as variaveis do arquivo
   public $campos = "
                 it24_anousu = int4 = Ano
                 it24_grupoespbenfurbana = int4 = Espécie de Benfeitoria
                 it24_grupotipobenfurbana = int4 = Tipo de Benfeitoria
                 it24_grupoespbenfrural = int4 = Espécie de Benfeitoria
                 it24_grupotipobenfrural = int4 = Tipo de Benfeitoria
                 it24_grupoutilterrarural = int4 = Utilização das Terra
                 it24_grupodistrterrarural = int4 = Distribuição das Terras
                 it24_grupopadraoconstrutivobenurbana = int4 = Padrão Construtivo
                 it24_diasvctoitbi = int4 = Vencimento
                 it24_alteraguialib = int4 = Alterar na Guia Liberada
                 it24_impsituacaodeb = bool = Imprime Situação de Débito na Guia
                 it24_taxabancaria = float8 = Tarifa Bancária
                 it24_cgmobrigatorio = bool = CGM Obrigatório Transmitente/Adquirente
                 it24_solicitanotificacao = bool = Solicita Notificação
                 it24_carregavalorvenal = bool = Carrega Valor Venal
                 it24_matricrural = bool = Matrícula Imóvel Rural
                 it24_comparavaloresavaliacao = bool = Compara Valores Avaliação
                 it24_padraoconstrutivobrigatorio = bool = Padrão Construtivo Obrigatório
                 it24_carregaconstrucoesbenfeitoriasitbi = bool = Carrega Contruções Benfeitoria ITBI
                 it24_emiteguiaquitacao = bool = Emite Guia de Quitação 
                 ";
    public function __construct()
    {
        $this->rotulo = new rotulo("paritbi"); 
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
   // funcao para atualizar campos
   public function atualizacampos($exclusao = false)
   {
     if($exclusao==false){
       $this->it24_anousu = ($this->it24_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["it24_anousu"]:$this->it24_anousu);
       $this->it24_grupoespbenfurbana = ($this->it24_grupoespbenfurbana == ""?@$GLOBALS["HTTP_POST_VARS"]["it24_grupoespbenfurbana"]:$this->it24_grupoespbenfurbana);
       $this->it24_grupotipobenfurbana = ($this->it24_grupotipobenfurbana == ""?@$GLOBALS["HTTP_POST_VARS"]["it24_grupotipobenfurbana"]:$this->it24_grupotipobenfurbana);
       $this->it24_grupoespbenfrural = ($this->it24_grupoespbenfrural == ""?@$GLOBALS["HTTP_POST_VARS"]["it24_grupoespbenfrural"]:$this->it24_grupoespbenfrural);
       $this->it24_grupotipobenfrural = ($this->it24_grupotipobenfrural == ""?@$GLOBALS["HTTP_POST_VARS"]["it24_grupotipobenfrural"]:$this->it24_grupotipobenfrural);
       $this->it24_grupoutilterrarural = ($this->it24_grupoutilterrarural == ""?@$GLOBALS["HTTP_POST_VARS"]["it24_grupoutilterrarural"]:$this->it24_grupoutilterrarural);
       $this->it24_grupodistrterrarural = ($this->it24_grupodistrterrarural == ""?@$GLOBALS["HTTP_POST_VARS"]["it24_grupodistrterrarural"]:$this->it24_grupodistrterrarural);
       $this->it24_grupopadraoconstrutivobenurbana = ($this->it24_grupopadraoconstrutivobenurbana == ""?@$GLOBALS["HTTP_POST_VARS"]["it24_grupopadraoconstrutivobenurbana"]:$this->it24_grupopadraoconstrutivobenurbana);
       $this->it24_diasvctoitbi = ($this->it24_diasvctoitbi == ""?@$GLOBALS["HTTP_POST_VARS"]["it24_diasvctoitbi"]:$this->it24_diasvctoitbi);
       $this->it24_alteraguialib = ($this->it24_alteraguialib == ""?@$GLOBALS["HTTP_POST_VARS"]["it24_alteraguialib"]:$this->it24_alteraguialib);
       $this->it24_impsituacaodeb = ($this->it24_impsituacaodeb == "f"?@$GLOBALS["HTTP_POST_VARS"]["it24_impsituacaodeb"]:$this->it24_impsituacaodeb);
       $this->it24_taxabancaria = ($this->it24_taxabancaria == ""?@$GLOBALS["HTTP_POST_VARS"]["it24_taxabancaria"]:$this->it24_taxabancaria);
       $this->it24_cgmobrigatorio = ($this->it24_cgmobrigatorio == "f"?@$GLOBALS["HTTP_POST_VARS"]["it24_cgmobrigatorio"]:$this->it24_cgmobrigatorio);
       $this->it24_solicitanotificacao = ($this->it24_solicitanotificacao == "f"?@$GLOBALS["HTTP_POST_VARS"]["it24_solicitanotificacao"]:$this->it24_solicitanotificacao);
       $this->it24_carregavalorvenal = ($this->it24_carregavalorvenal == "f"?@$GLOBALS["HTTP_POST_VARS"]["it24_carregavalorvenal"]:$this->it24_carregavalorvenal);
       $this->it24_matricrural = ($this->it24_matricrural == "f"?@$GLOBALS["HTTP_POST_VARS"]["it24_matricrural"]:$this->it24_matricrural);
       $this->it24_comparavaloresavaliacao = ($this->it24_comparavaloresavaliacao == "f"?@$GLOBALS["HTTP_POST_VARS"]["it24_comparavaloresavaliacao"]:$this->it24_comparavaloresavaliacao);
       $this->it24_padraoconstrutivobrigatorio = ($this->it24_padraoconstrutivobrigatorio == "f"?@$GLOBALS["HTTP_POST_VARS"]["it24_padraoconstrutivobrigatorio"]:$this->it24_padraoconstrutivobrigatorio);
       $this->it24_carregaconstrucoesbenfeitoriasitbi = ($this->it24_carregaconstrucoesbenfeitoriasitbi == "f"?@$GLOBALS["HTTP_POST_VARS"]["it24_carregaconstrucoesbenfeitoriasitbi"]:$this->it24_carregaconstrucoesbenfeitoriasitbi);
       $this->it24_emiteguiaquitacao = ($this->it24_emiteguiaquitacao == "f"?@$GLOBALS["HTTP_POST_VARS"]["it24_emiteguiaquitacao"]:$this->it24_emiteguiaquitacao);
     }else{
       $this->it24_anousu = ($this->it24_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["it24_anousu"]:$this->it24_anousu);
     }
   }
   // funcao para inclusao
   public function incluir ($it24_anousu){
      $this->atualizacampos();
     if($this->it24_grupoespbenfurbana == null ){
       $this->erro_sql = " Campo Espécie de Benfeitoria não informado.";
       $this->erro_campo = "it24_grupoespbenfurbana";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it24_grupotipobenfurbana == null ){
       $this->erro_sql = " Campo Tipo de Benfeitoria não informado.";
       $this->erro_campo = "it24_grupotipobenfurbana";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it24_grupoespbenfrural == null ){
       $this->erro_sql = " Campo Espécie de Benfeitoria não informado.";
       $this->erro_campo = "it24_grupoespbenfrural";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it24_grupotipobenfrural == null ){
       $this->erro_sql = " Campo Tipo de Benfeitoria não informado.";
       $this->erro_campo = "it24_grupotipobenfrural";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it24_grupoutilterrarural == null ){
       $this->erro_sql = " Campo Utilização das Terra não informado.";
       $this->erro_campo = "it24_grupoutilterrarural";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it24_grupodistrterrarural == null ){
       $this->erro_sql = " Campo Distribuição das Terras não informado.";
       $this->erro_campo = "it24_grupodistrterrarural";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it24_grupopadraoconstrutivobenurbana == null ){
       $this->erro_sql = " Campo Padrão Construtivo não informado.";
       $this->erro_campo = "it24_grupopadraoconstrutivobenurbana";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it24_diasvctoitbi == null ){
       $this->erro_sql = " Campo Vencimento não informado.";
       $this->erro_campo = "it24_diasvctoitbi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it24_alteraguialib == null ){
       $this->erro_sql = " Campo Alterar na Guia Liberada não informado.";
       $this->erro_campo = "it24_alteraguialib";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it24_impsituacaodeb == null ){
       $this->erro_sql = " Campo Imprime Situação de Débito na Guia não informado.";
       $this->erro_campo = "it24_impsituacaodeb";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it24_taxabancaria == null ){
       $this->erro_sql = " Campo Tarifa Bancária não informado.";
       $this->erro_campo = "it24_taxabancaria";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if( !DBNumber::isFloat( trim($this->it24_taxabancaria) ) ){
     	$this->k03_diasreemissaocertidao = '';
     	$this->erro_sql = " Campo Tarifa Bancária deve ser preenchido somente com números!";
     	$this->erro_campo = "k03_diasvalidadecertidao";
     	$this->erro_banco = "";
     	$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     	$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     	$this->erro_status = "0";
     	return false;
     }
     if($this->it24_cgmobrigatorio == null ){
       $this->erro_sql = " Campo CGM Obrigatório Transmitente/Adquirente não informado.";
       $this->erro_campo = "it24_cgmobrigatorio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it24_matricrural == null ){
      $this->erro_sql = " Campo Matrícula Imóvel RUral não informado.";
      $this->erro_campo = "it24_matricrural";
      $this->erro_banco = "";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }
       $this->it24_anousu = $it24_anousu;
     if(($this->it24_anousu == null) || ($this->it24_anousu == "") ){
       $this->erro_sql = " Campo it24_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it24_solicitanotificacao == null ){
        $this->erro_sql = " Campo Solicita Notificação.";
        $this->erro_campo = "it24_solicitanotificacao";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }

      if($this->it24_carregavalorvenal == null ){
        $this->erro_sql = " Campo Carrega Valor Venal..";
        $this->erro_campo = "it24_carregavalorvenal";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }

      if($this->it24_comparavaloresavaliacao == null ){
        $this->erro_sql = " Compara Valores Avaliação..";
        $this->erro_campo = "it24_comparavaloresavaliacao";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }

       if($this->it24_padraoconstrutivobrigatorio == null ){
           $this->erro_sql = " Padrão Construtivo Obrigatório..";
           $this->erro_campo = "it24_padraoconstrutivobrigatorio";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
       }

       if($this->it24_carregaconstrucoesbenfeitoriasitbi == null ){
           $this->erro_sql = " Carrega Contruções Benfeitoria ITBI..";
           $this->erro_campo = "it24_carregaconstrucoesbenfeitoriasitbi";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
       }

       if($this->it24_emiteguiaquitacao == null ){ 
        $this->erro_sql = " Campo Emite Guia de Quitação não informado.";
        $this->erro_campo = "it24_emiteguiaquitacao";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }


     $sql = "insert into paritbi(
                                       it24_anousu
                                      ,it24_grupoespbenfurbana
                                      ,it24_grupotipobenfurbana
                                      ,it24_grupoespbenfrural
                                      ,it24_grupotipobenfrural
                                      ,it24_grupoutilterrarural
                                      ,it24_grupodistrterrarural
                                      ,it24_grupopadraoconstrutivobenurbana
                                      ,it24_diasvctoitbi
                                      ,it24_alteraguialib
                                      ,it24_impsituacaodeb
                                      ,it24_taxabancaria
                                      ,it24_cgmobrigatorio
                                      ,it24_solicitanotificacao
                                      ,it24_carregavalorvenal
                                      ,it24_matricrural
                                      ,it24_comparavaloresavaliacao
                                      ,it24_padraoconstrutivobrigatorio
                                      ,it24_carregaconstrucoesbenfeitoriasitbi
                                      ,it24_emiteguiaquitacao 
                       )
                values (
                                $this->it24_anousu
                               ,$this->it24_grupoespbenfurbana
                               ,$this->it24_grupotipobenfurbana
                               ,$this->it24_grupoespbenfrural
                               ,$this->it24_grupotipobenfrural
                               ,$this->it24_grupoutilterrarural
                               ,$this->it24_grupodistrterrarural
                               ,$this->it24_grupopadraoconstrutivobenurbana
                               ,$this->it24_diasvctoitbi
                               ,$this->it24_alteraguialib
                               ,'$this->it24_impsituacaodeb'
                               ,$this->it24_taxabancaria
                               ,'$this->it24_cgmobrigatorio'
                               ,'$this->it24_solicitanotificacao'
                               ,'$this->it24_carregavalorvenal'
                               ,'$this->it24_matricrural'
                               ,'$this->it24_comparavaloresavaliacao'
                               ,'$this->it24_padraoconstrutivobrigatorio'
                               ,'$this->it24_carregaconstrucoesbenfeitoriasitbi'
                               ,'$this->it24_emiteguiaquitacao' 
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Parametros de ITBI ($this->it24_anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Parametros de ITBI já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Parametros de ITBI ($this->it24_anousu) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it24_anousu;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->it24_anousu  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13501,'$this->it24_anousu','I')");
         $resac = db_query("insert into db_acount values($acount,2362,13501,'','".AddSlashes(pg_result($resaco,0,'it24_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2362,13502,'','".AddSlashes(pg_result($resaco,0,'it24_grupoespbenfurbana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2362,13503,'','".AddSlashes(pg_result($resaco,0,'it24_grupotipobenfurbana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2362,13504,'','".AddSlashes(pg_result($resaco,0,'it24_grupoespbenfrural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2362,13505,'','".AddSlashes(pg_result($resaco,0,'it24_grupotipobenfrural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2362,13506,'','".AddSlashes(pg_result($resaco,0,'it24_grupoutilterrarural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2362,13507,'','".AddSlashes(pg_result($resaco,0,'it24_grupodistrterrarural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2362,20280,'','".AddSlashes(pg_result($resaco,0,'it24_grupopadraoconstrutivobenurbana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2362,13508,'','".AddSlashes(pg_result($resaco,0,'it24_diasvctoitbi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2362,15476,'','".AddSlashes(pg_result($resaco,0,'it24_alteraguialib'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2362,15477,'','".AddSlashes(pg_result($resaco,0,'it24_impsituacaodeb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2362,20242,'','".AddSlashes(pg_result($resaco,0,'it24_taxabancaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2362,20656,'','".AddSlashes(pg_result($resaco,0,'it24_cgmobrigatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2362,1011724,'','".AddSlashes(pg_result($resaco,0,'it24_solicitanotificacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2362,1011739,'','".AddSlashes(pg_result($resaco,0,'it24_carregavalorvenal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2362,1011737,'','".AddSlashes(pg_result($resaco,0,'it24_matricrural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2362,1011827,'','".AddSlashes(pg_result($resaco,0,'it24_comparavaloresavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2362,1011922,'','".AddSlashes(pg_result($resaco,0,'it24_padraoconstrutivobrigatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2362,1011923,'','".AddSlashes(pg_result($resaco,0,'it24_carregaconstrucoesbenfeitoriasitbi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2362,1014485,'','".AddSlashes(pg_result($resaco,0,'it24_emiteguiaquitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($it24_anousu=null) {
      $this->atualizacampos();
     $sql = " update paritbi set ";
     $virgula = "";
     if(trim($this->it24_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it24_anousu"])){
       $sql  .= $virgula." it24_anousu = $this->it24_anousu ";
       $virgula = ",";
       if(trim($this->it24_anousu) == null ){
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "it24_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it24_grupoespbenfurbana)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it24_grupoespbenfurbana"])){
       $sql  .= $virgula." it24_grupoespbenfurbana = $this->it24_grupoespbenfurbana ";
       $virgula = ",";
       if(trim($this->it24_grupoespbenfurbana) == null ){
         $this->erro_sql = " Campo Espécie de Benfeitoria não informado.";
         $this->erro_campo = "it24_grupoespbenfurbana";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it24_grupotipobenfurbana)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it24_grupotipobenfurbana"])){
       $sql  .= $virgula." it24_grupotipobenfurbana = $this->it24_grupotipobenfurbana ";
       $virgula = ",";
       if(trim($this->it24_grupotipobenfurbana) == null ){
         $this->erro_sql = " Campo Tipo de Benfeitoria não informado.";
         $this->erro_campo = "it24_grupotipobenfurbana";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it24_grupoespbenfrural)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it24_grupoespbenfrural"])){
       $sql  .= $virgula." it24_grupoespbenfrural = $this->it24_grupoespbenfrural ";
       $virgula = ",";
       if(trim($this->it24_grupoespbenfrural) == null ){
         $this->erro_sql = " Campo Espécie de Benfeitoria não informado.";
         $this->erro_campo = "it24_grupoespbenfrural";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it24_grupotipobenfrural)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it24_grupotipobenfrural"])){
       $sql  .= $virgula." it24_grupotipobenfrural = $this->it24_grupotipobenfrural ";
       $virgula = ",";
       if(trim($this->it24_grupotipobenfrural) == null ){
         $this->erro_sql = " Campo Tipo de Benfeitoria não informado.";
         $this->erro_campo = "it24_grupotipobenfrural";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it24_grupoutilterrarural)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it24_grupoutilterrarural"])){
       $sql  .= $virgula." it24_grupoutilterrarural = $this->it24_grupoutilterrarural ";
       $virgula = ",";
       if(trim($this->it24_grupoutilterrarural) == null ){
         $this->erro_sql = " Campo Utilização das Terra não informado.";
         $this->erro_campo = "it24_grupoutilterrarural";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it24_grupodistrterrarural)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it24_grupodistrterrarural"])){
       $sql  .= $virgula." it24_grupodistrterrarural = $this->it24_grupodistrterrarural ";
       $virgula = ",";
       if(trim($this->it24_grupodistrterrarural) == null ){
         $this->erro_sql = " Campo Distribuição das Terras não informado.";
         $this->erro_campo = "it24_grupodistrterrarural";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it24_grupopadraoconstrutivobenurbana)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it24_grupopadraoconstrutivobenurbana"])){
       $sql  .= $virgula." it24_grupopadraoconstrutivobenurbana = $this->it24_grupopadraoconstrutivobenurbana ";
       $virgula = ",";
       if(trim($this->it24_grupopadraoconstrutivobenurbana) == null ){
         $this->erro_sql = " Campo Padrão Construtivo não informado.";
         $this->erro_campo = "it24_grupopadraoconstrutivobenurbana";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it24_diasvctoitbi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it24_diasvctoitbi"])){
       $sql  .= $virgula." it24_diasvctoitbi = $this->it24_diasvctoitbi ";
       $virgula = ",";
       if(trim($this->it24_diasvctoitbi) == null ){
         $this->erro_sql = " Campo Vencimento não informado.";
         $this->erro_campo = "it24_diasvctoitbi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it24_alteraguialib)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it24_alteraguialib"])){
       $sql  .= $virgula." it24_alteraguialib = $this->it24_alteraguialib ";
       $virgula = ",";
       if(trim($this->it24_alteraguialib) == null ){
         $this->erro_sql = " Campo Alterar na Guia Liberada não informado.";
         $this->erro_campo = "it24_alteraguialib";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it24_impsituacaodeb)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it24_impsituacaodeb"])){
       $sql  .= $virgula." it24_impsituacaodeb = '$this->it24_impsituacaodeb' ";
       $virgula = ",";
       if(trim($this->it24_impsituacaodeb) == null ){
         $this->erro_sql = " Campo Imprime Situação de Débito na Guia não informado.";
         $this->erro_campo = "it24_impsituacaodeb";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it24_taxabancaria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it24_taxabancaria"])){
       $sql  .= $virgula." it24_taxabancaria = $this->it24_taxabancaria ";
       $virgula = ",";
       if(trim($this->it24_taxabancaria) == null ){
         $this->erro_sql = " Campo Tarifa Bancária não informado.";
         $this->erro_campo = "it24_taxabancaria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       if( !DBNumber::isFloat( trim($this->it24_taxabancaria) ) ){
       	$this->k03_diasreemissaocertidao = '';
       	$this->erro_sql = " Campo Tarifa Bancária deve ser preenchido somente com números!";
       	$this->erro_campo = "k03_diasvalidadecertidao";
       	$this->erro_banco = "";
       	$this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       	$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       	$this->erro_status = "0";
       	return false;
       }
     }
     if(trim($this->it24_cgmobrigatorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it24_cgmobrigatorio"])){
       $sql  .= $virgula." it24_cgmobrigatorio = '$this->it24_cgmobrigatorio' ";
       $virgula = ",";
       if(trim($this->it24_cgmobrigatorio) == null ){
         $this->erro_sql = " Campo CGM Obrigatório Transmitente/Adquirente não informado.";
         $this->erro_campo = "it24_cgmobrigatorio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it24_matricrural)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it24_matricrural"])){
      $sql  .= $virgula." it24_matricrural = '$this->it24_matricrural' ";
      $virgula = ",";
      if(trim($this->it24_matricrural) == null ){
        $this->erro_sql = " Campo Matrícula Imóvel Rural não informado.";
        $this->erro_campo = "it24_matricrural";
        $this->erro_banco = "";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     }
     if(trim($this->it24_solicitanotificacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it24_solicitanotificacao"])){
        $sql  .= $virgula." it24_solicitanotificacao = '$this->it24_solicitanotificacao' ";
        $virgula = ",";
        if(trim($this->it24_solicitanotificacao) == null ){
          $this->erro_sql = " Campo Solicita Notificação.";
          $this->erro_campo = "it24_solicitanotificacao";
          $this->erro_banco = "";
          $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          return false;
        }
     }
     if(trim($this->it24_carregavalorvenal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it24_carregavalorvenal"])){
        $sql  .= $virgula." it24_carregavalorvenal = '$this->it24_carregavalorvenal' ";
        $virgula = ",";
        if(trim($this->it24_carregavalorvenal) == null ){
          $this->erro_sql = " Campo Carrega Valor Venal.";
          $this->erro_campo = "it24_carregavalorvenal";
          $this->erro_banco = "";
          $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          return false;
        }
     }

    if(trim($this->it24_comparavaloresavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it24_comparavaloresavaliacao"])){
       $sql  .= $virgula." it24_comparavaloresavaliacao = '$this->it24_comparavaloresavaliacao' ";
       $virgula = ",";
       if(trim($this->it24_comparavaloresavaliacao) == null ){
         $this->erro_sql = " Compara Valores Avaliação.";
         $this->erro_campo = "it24_comparavaloresavaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
    }

       if(trim($this->it24_padraoconstrutivobrigatorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it24_padraoconstrutivobrigatorio"])){
           $sql  .= $virgula." it24_padraoconstrutivobrigatorio = '$this->it24_padraoconstrutivobrigatorio' ";
           $virgula = ",";
           if(trim($this->it24_padraoconstrutivobrigatorio) == null ){
               $this->erro_sql = " Padrão Construtivo Obrigatório.";
               $this->erro_campo = "it24_padraoconstrutivobrigatorio";
               $this->erro_banco = "";
               $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
               $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
               $this->erro_status = "0";
               return false;
           }
       }

       if(trim($this->it24_carregaconstrucoesbenfeitoriasitbi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it24_carregaconstrucoesbenfeitoriasitbi"])){
           $sql  .= $virgula." it24_carregaconstrucoesbenfeitoriasitbi = '$this->it24_carregaconstrucoesbenfeitoriasitbi' ";
           $virgula = ",";
           if(trim($this->it24_carregaconstrucoesbenfeitoriasitbi) == null ){
               $this->erro_sql = " Carrega Contruções Benfeitoria ITBI.";
               $this->erro_campo = "it24_carregaconstrucoesbenfeitoriasitbi";
               $this->erro_banco = "";
               $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
               $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
               $this->erro_status = "0";
               return false;
           }
       }
       if(trim($this->it24_emiteguiaquitacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it24_emiteguiaquitacao"])){ 
        $sql  .= $virgula." it24_emiteguiaquitacao = '$this->it24_emiteguiaquitacao' ";
        $virgula = ",";
        if(trim($this->it24_emiteguiaquitacao) == null ){ 
          $this->erro_sql = " Campo Emite Guia de Quitação não informado.";
          $this->erro_campo = "it24_emiteguiaquitacao";
          $this->erro_banco = "";
          $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
          $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
          $this->erro_status = "0";
          return false;
        }
      }

     $sql .= " where ";
     if($it24_anousu!=null){
       $sql .= " it24_anousu = $this->it24_anousu";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->it24_anousu));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,13501,'$this->it24_anousu','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it24_anousu"]) || $this->it24_anousu != "")
             $resac = db_query("insert into db_acount values($acount,2362,13501,'".AddSlashes(pg_result($resaco,$conresaco,'it24_anousu'))."','$this->it24_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it24_grupoespbenfurbana"]) || $this->it24_grupoespbenfurbana != "")
             $resac = db_query("insert into db_acount values($acount,2362,13502,'".AddSlashes(pg_result($resaco,$conresaco,'it24_grupoespbenfurbana'))."','$this->it24_grupoespbenfurbana',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it24_grupotipobenfurbana"]) || $this->it24_grupotipobenfurbana != "")
             $resac = db_query("insert into db_acount values($acount,2362,13503,'".AddSlashes(pg_result($resaco,$conresaco,'it24_grupotipobenfurbana'))."','$this->it24_grupotipobenfurbana',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it24_grupoespbenfrural"]) || $this->it24_grupoespbenfrural != "")
             $resac = db_query("insert into db_acount values($acount,2362,13504,'".AddSlashes(pg_result($resaco,$conresaco,'it24_grupoespbenfrural'))."','$this->it24_grupoespbenfrural',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it24_grupotipobenfrural"]) || $this->it24_grupotipobenfrural != "")
             $resac = db_query("insert into db_acount values($acount,2362,13505,'".AddSlashes(pg_result($resaco,$conresaco,'it24_grupotipobenfrural'))."','$this->it24_grupotipobenfrural',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it24_grupoutilterrarural"]) || $this->it24_grupoutilterrarural != "")
             $resac = db_query("insert into db_acount values($acount,2362,13506,'".AddSlashes(pg_result($resaco,$conresaco,'it24_grupoutilterrarural'))."','$this->it24_grupoutilterrarural',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it24_grupodistrterrarural"]) || $this->it24_grupodistrterrarural != "")
             $resac = db_query("insert into db_acount values($acount,2362,13507,'".AddSlashes(pg_result($resaco,$conresaco,'it24_grupodistrterrarural'))."','$this->it24_grupodistrterrarural',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it24_grupopadraoconstrutivobenurbana"]) || $this->it24_grupopadraoconstrutivobenurbana != "")
             $resac = db_query("insert into db_acount values($acount,2362,20280,'".AddSlashes(pg_result($resaco,$conresaco,'it24_grupopadraoconstrutivobenurbana'))."','$this->it24_grupopadraoconstrutivobenurbana',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it24_diasvctoitbi"]) || $this->it24_diasvctoitbi != "")
             $resac = db_query("insert into db_acount values($acount,2362,13508,'".AddSlashes(pg_result($resaco,$conresaco,'it24_diasvctoitbi'))."','$this->it24_diasvctoitbi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it24_alteraguialib"]) || $this->it24_alteraguialib != "")
             $resac = db_query("insert into db_acount values($acount,2362,15476,'".AddSlashes(pg_result($resaco,$conresaco,'it24_alteraguialib'))."','$this->it24_alteraguialib',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it24_impsituacaodeb"]) || $this->it24_impsituacaodeb != "")
             $resac = db_query("insert into db_acount values($acount,2362,15477,'".AddSlashes(pg_result($resaco,$conresaco,'it24_impsituacaodeb'))."','$this->it24_impsituacaodeb',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it24_taxabancaria"]) || $this->it24_taxabancaria != "")
             $resac = db_query("insert into db_acount values($acount,2362,20242,'".AddSlashes(pg_result($resaco,$conresaco,'it24_taxabancaria'))."','$this->it24_taxabancaria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it24_cgmobrigatorio"]) || $this->it24_cgmobrigatorio != "")
             $resac = db_query("insert into db_acount values($acount,2362,20656,'".AddSlashes(pg_result($resaco,$conresaco,'it24_cgmobrigatorio'))."','$this->it24_cgmobrigatorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it24_matricrural"]) || $this->it24_matricrural != "")
             $resac = db_query("insert into db_acount values($acount,2362,1011737,'".AddSlashes(pg_result($resaco,$conresaco,'it24_matricrural'))."','$this->it24_matricrural',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it24_solicitanotificacao"]) || $this->it24_solicitanotificacao != "")
             $resac = db_query("insert into db_acount values($acount,2362,1011724,'".AddSlashes(pg_result($resaco,$conresaco,'it24_solicitanotificacao'))."','$this->it24_solicitanotificacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["it24_carregavalorvenal"]) || $this->it24_carregavalorvenal != "")
             $resac = db_query("insert into db_acount values($acount,2362,1011739,'".AddSlashes(pg_result($resaco,$conresaco,'it24_carregavalorvenal'))."','$this->it24_carregavalorvenal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
            if(isset($GLOBALS["HTTP_POST_VARS"]["it24_comparavaloresavaliacao"]) || $this->it24_comparavaloresavaliacao != "")
             $resac = db_query("insert into db_acount values($acount,2362,1011827,'".AddSlashes(pg_result($resaco,$conresaco,'it24_comparavaloresavaliacao'))."','$this->it24_comparavaloresavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
             if(isset($GLOBALS["HTTP_POST_VARS"]["it24_padraoconstrutivobrigatorio"]) || $this->it24_padraoconstrutivobrigatorio != "")
                 $resac = db_query("insert into db_acount values($acount,2362,1011922,'".AddSlashes(pg_result($resaco,$conresaco,'it24_padraoconstrutivobrigatorio'))."','$this->it24_padraoconstrutivobrigatorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
             if(isset($GLOBALS["HTTP_POST_VARS"]["it24_carregaconstrucoesbenfeitoriasitbi"]) || $this->it24_carregaconstrucoesbenfeitoriasitbi != "")
                 $resac = db_query("insert into db_acount values($acount,2362,1011923,'".AddSlashes(pg_result($resaco,$conresaco,'it24_carregaconstrucoesbenfeitoriasitbi'))."','$this->it24_carregaconstrucoesbenfeitoriasitbi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
            if (isset($GLOBALS["HTTP_POST_VARS"]["it24_emiteguiaquitacao"]) || $this->it24_emiteguiaquitacao != "")
              $resac = db_query("insert into db_acount values($acount,2362,1014485,'".AddSlashes(pg_result($resaco,$conresaco,'it24_emiteguiaquitacao'))."','$this->it24_emiteguiaquitacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");     
         }
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros de ITBI nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->it24_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parametros de ITBI nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->it24_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it24_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($it24_anousu=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($it24_anousu));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,13501,'$it24_anousu','E')");
           $resac  = db_query("insert into db_acount values($acount,2362,13501,'','".AddSlashes(pg_result($resaco,$iresaco,'it24_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2362,13502,'','".AddSlashes(pg_result($resaco,$iresaco,'it24_grupoespbenfurbana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2362,13503,'','".AddSlashes(pg_result($resaco,$iresaco,'it24_grupotipobenfurbana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2362,13504,'','".AddSlashes(pg_result($resaco,$iresaco,'it24_grupoespbenfrural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2362,13505,'','".AddSlashes(pg_result($resaco,$iresaco,'it24_grupotipobenfrural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2362,13506,'','".AddSlashes(pg_result($resaco,$iresaco,'it24_grupoutilterrarural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2362,13507,'','".AddSlashes(pg_result($resaco,$iresaco,'it24_grupodistrterrarural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2362,20280,'','".AddSlashes(pg_result($resaco,$iresaco,'it24_grupopadraoconstrutivobenurbana'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2362,13508,'','".AddSlashes(pg_result($resaco,$iresaco,'it24_diasvctoitbi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2362,15476,'','".AddSlashes(pg_result($resaco,$iresaco,'it24_alteraguialib'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2362,15477,'','".AddSlashes(pg_result($resaco,$iresaco,'it24_impsituacaodeb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2362,20242,'','".AddSlashes(pg_result($resaco,$iresaco,'it24_taxabancaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2362,20656,'','".AddSlashes(pg_result($resaco,$iresaco,'it24_cgmobrigatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2362,1011724,'','".AddSlashes(pg_result($resaco,$iresaco,'it24_solicitanotificacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2362,1011739,'','".AddSlashes(pg_result($resaco,$iresaco,'it24_carregavalorvenal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2362,1011737,'','".AddSlashes(pg_result($resaco,$iresaco,'it24_matricrural'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,2362,1011922,'','".AddSlashes(pg_result($resaco,$iresaco,'it24_padraoconstrutivobrigatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,2362,1011923,'','".AddSlashes(pg_result($resaco,$iresaco,'it24_carregaconstrucoesbenfeitoriasitbi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2362,1014485,'','".AddSlashes(pg_result($resaco,$iresaco,'it24_emiteguiaquitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from paritbi
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($it24_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " it24_anousu = $it24_anousu ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros de ITBI nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$it24_anousu;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parametros de ITBI nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$it24_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$it24_anousu;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   public function sql_record($sql) {
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:paritbi";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ( $it24_anousu=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = explode("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from paritbi ";
     $sql .= "      inner join cargrup a on  a.j32_grupo = paritbi.it24_grupoespbenfurbana   ";
     $sql .= "      inner join cargrup b on  b.j32_grupo = paritbi.it24_grupotipobenfurbana  ";
	 $sql .= "      inner join cargrup c on  c.j32_grupo = paritbi.it24_grupoespbenfrural    ";
	 $sql .= "      inner join cargrup d on  d.j32_grupo = paritbi.it24_grupotipobenfrural   ";
	 $sql .= "      inner join cargrup e on  e.j32_grupo = paritbi.it24_grupoutilterrarural  ";
	 $sql .= "      inner join cargrup f on  f.j32_grupo = paritbi.it24_grupodistrterrarural ";

     $sql2 = "";
     if($dbwhere==""){
       if($it24_anousu!=null ){
         $sql2 .= " where paritbi.it24_anousu = $it24_anousu ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = explode("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql
  public function sql_query_file ( $it24_anousu=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = explode("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from paritbi ";
     $sql2 = "";
     if($dbwhere==""){
       if($it24_anousu!=null ){
         $sql2 .= " where paritbi.it24_anousu = $it24_anousu ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = explode("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }

  public function sql_query_dados_paritbi ( $it24_anousu=null,$campos="*",$ordem=null,$dbwhere=""){
  	$sql = "select ";
  	if($campos != "*" ){
  		$campos_sql = explode("#",$campos);
  		$virgula = "";
  		for($i=0;$i<sizeof($campos_sql);$i++){
  			$sql .= $virgula.$campos_sql[$i];
  			$virgula = ",";
  		}
  	}else{
  		$sql .= $campos;
  	}
  	$sql .= " from paritbi ";
  	$sql .= "      inner join cargrup a on  a.j32_grupo = paritbi.it24_grupoespbenfurbana             ";
  	$sql .= "      inner join cargrup b on  b.j32_grupo = paritbi.it24_grupotipobenfurbana            ";
  	$sql .= "      inner join cargrup c on  c.j32_grupo = paritbi.it24_grupoespbenfrural              ";
  	$sql .= "      inner join cargrup d on  d.j32_grupo = paritbi.it24_grupotipobenfrural             ";
  	$sql .= "      inner join cargrup e on  e.j32_grupo = paritbi.it24_grupoutilterrarural            ";
  	$sql .= "      inner join cargrup f on  f.j32_grupo = paritbi.it24_grupodistrterrarural           ";
  	$sql .= "      left join cargrup g on  g.j32_grupo = paritbi.it24_grupopadraoconstrutivobenurbana ";

  	$sql2 = "";
  	if($dbwhere==""){

  		if($it24_anousu!=null ){
  			$sql2 .= " where paritbi.it24_anousu = $it24_anousu ";
  		}
  	} else if ( $dbwhere != "" ) {
  		$sql2 = " where $dbwhere";
  	}

  	$sql .= $sql2;

  	if($ordem != null ){

  		$sql .= " order by ";
	  	$campos_sql = explode("#",$ordem);
	  	$virgula = "";

		  for($i=0;$i<sizeof($campos_sql);$i++){

		    $sql .= $virgula.$campos_sql[$i];
		    $virgula = ",";
		  }

	  }

  	return $sql;
  }
}
?>
