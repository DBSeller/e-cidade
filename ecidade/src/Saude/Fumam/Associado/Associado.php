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

namespace ECidade\Saude\Fumam\Associado;

use DBDate;
use DBException;
use Servidor;
use stdClass;
use cl_associado;
use cl_associadosituacao;
use cl_associadomovimentacao;
use cl_cgs_cartaosus;
use db_utils;

// phpcs:disable
require_once(modification("libs/db_stdlib.php"));
// phpcs:enable

class Associado
{

    /**
     * @var Object
     */
    private $oDadosMatricula = null;

    /**
     * Matricula do funcionário
     * @var Integer
     */
    private $iMatricula = null;

    /**
     * Código da situação do associado
     * @var Integer
     */
    private $iCodigoSituacao = null;

    /**
     * Código do Associado
     * @var Integer
     */
    private $iAssociado = null;

    public function __construct($iMatricula = null, $iAssociado = null, $iInstituicao = null)
    {
        if ($iAssociado == null) {
            $this->iAssociado = 0;
        } else {
            $this->iAssociado = $iAssociado;
        }

        if ($iMatricula == null) {
            $this->iMatricula = 0;
        } else {
            $this->iMatricula = $iMatricula;
            $this->oDadosMatricula = $this->buscaDadosAssociado($iMatricula, $iInstituicao);
        }
    }

    public function buscaDadosAssociado($iMatricula, $iInstituicao)
    {
        $oRetornoDadosServidor = new stdClass();
        $oServidor = new Servidor($iMatricula, null, null, $iInstituicao);

        $sCpf = $oServidor->getCgm()->getCpf();
        $oRetornoDadosServidor->dNascimento = $oServidor->getDataNascimento()->getDate(DBDate::DATA_PTBR);
        $oRetornoDadosServidor->sCpf = db_formatar($sCpf, 'cpf');
        $oRetornoDadosServidor->sIdentidade = $oServidor->getCgm()->getIdentidade();
        $oRetornoDadosServidor->sEstadoCivil = $oServidor->getDescricaoEstadoCivil();
        $oRetornoDadosServidor->iCodEstadoCivil = $oServidor->getCodigoEstadoCivil();
        $oRetornoDadosServidor->sNacionalidade = $oServidor->getDescricaoNacionalidade();
        $oRetornoDadosServidor->iCodNacionalidade = $oServidor->getCodigoNacionalidade();
        $oRetornoDadosServidor->sTelefone = db_formatar($oServidor->getCgm()->getTelefone(), 'telefone');
        $oRetornoDadosServidor->sCargo = $oServidor->getDadosCargo()->rh37_descr;

        $oDaoCgsCartaosus = new cl_cgs_cartaosus;
        $sCampos = "s115_c_cartaosus, case when s115_c_tipo = 'D' then 'DEFINITIVO'
                                           else 'PROVISÓRIO'
                                      end as s115_c_tipo ";
        $sWhere = "z01_v_cgccpf = '{$sCpf}'";
        $sOrderBy = "s115_c_tipo, s115_c_cartaosus desc limit 1";
        $sSqlCartaoSus = $oDaoCgsCartaosus->sql_query(null, $sCampos, $sOrderBy, $sWhere);
        $rsCartaoSus = db_query($sSqlCartaoSus);

        if (!$rsCartaoSus) {
            throw new DBException("Erro ao buscar a informação do cartao Sus ({$iMatricula})." . pg_last_error());
        }

        $oRetornoDadosServidor->sTipocartaosus = '';
        $oRetornoDadosServidor->sCartaoSus = '';
        if (pg_numrows($rsCartaoSus) > 0) {
            $oCartaoSus = db_utils::fieldsMemory($rsCartaoSus, 0);
            $oRetornoDadosServidor->sTipocartaosus = $oCartaoSus->s115_c_tipo;
            $oRetornoDadosServidor->sCartaoSus = $oCartaoSus->s115_c_cartaosus;
        }

        if ($oServidor->getSexo() == 'M') {
            $oRetornoDadosServidor->sSexo = 'MASCULINO';
        } else {
            $oRetornoDadosServidor->sSexo = 'FEMININO';
        }

        $oRetornoDadosServidor->sEmail = $oServidor->getCgm()->getEmail();
        $oRetornoDadosServidor->sCelular = db_formatar($oServidor->getCgm()->getCelular(), 'celular');
        $oRetornoDadosServidor->sEndereco = $oServidor->getCgm()->getLogradouro();
        $oRetornoDadosServidor->iNumero = $oServidor->getCgm()->getNumero();
        $oRetornoDadosServidor->sComplemento = $oServidor->getCgm()->getComplemento();
        $oRetornoDadosServidor->sBairro = $oServidor->getCgm()->getBairro();
        $oRetornoDadosServidor->sUf = $oServidor->getCgm()->getUf();
        $oRetornoDadosServidor->sMunicipio = $oServidor->getCgm()->getMunicipio();
        $oRetornoDadosServidor->sCep = db_formatar($oServidor->getCgm()->getCep(), 'cep');

        if ($oServidor->isRescindido()) {
            $iCodigoSituacao = 2;
        } else {
            if ($this->iAssociado == '') {
                $iCodigoSituacao = 1;
            } else {
                $iCodigoSituacao = $this->getSituacaoAssociado($iMatricula);
            }
        }

        $oRetornoDadosServidor->iCodSituacao = $iCodigoSituacao;
        $oRetornoDadosServidor->sSituacao = $this->getDescricaoSituacaoAssociado($iCodigoSituacao);

        return $oRetornoDadosServidor;
    }

    /**
     * Retorna a descrição da situação do associado
     * @return String
     */
    public function getSituacaoAssociado($iMatricula)
    {
        /* busca situação atual do associado */
        $oDaoAssociadoMovimentacao = new cl_associadomovimentacao;
        $sWhere = "rh01_regist = {$iMatricula} order by fm03_dtmov desc limit 1";
        $sSqlAssociadoMovimentacao = $oDaoAssociadoMovimentacao->sql_query(null, "fm03_situacao", null, $sWhere);
        $rsAssociadoMovimentacao = db_query($sSqlAssociadoMovimentacao);

        if (!$rsAssociadoMovimentacao) {
            throw new DBException("Erro ao buscar a situação do servidor ({$iMatricula})." . pg_last_error());
        }

        $iCodigoSituacao = 0;

        if (pg_num_rows($rsAssociadoMovimentacao) > 0) {
            $oAssociadoMovimentacao = db_utils::fieldsMemory($rsAssociadoMovimentacao, 0);
            $iCodigoSituacao = $oAssociadoMovimentacao->fm03_situacao;
        }

        return $iCodigoSituacao;
    }

    /**
     * Retorna a descrição da situação do associado
     * @return String
     */
    public function getDescricaoSituacaoAssociado($iCodigoSituacao)
    {
        $oDaoAssociadoSituacao = new cl_associadosituacao;
        $sSqlAssociadoSituacao = $oDaoAssociadoSituacao->sql_query_file($iCodigoSituacao);
        $rsAssociadoSituacao = db_query($sSqlAssociadoSituacao);

        $sMensagem = "Erro ao buscar descrição da situação do servidor";
        if (!$rsAssociadoSituacao) {
            throw new DBException("{$sMensagem} ({$this->iMatricula})." . pg_last_error());
        }

        if (pg_num_rows($rsAssociadoSituacao) > 0) {
            $oAssociadoSituacao = db_utils::fieldsMemory($rsAssociadoSituacao, 0);
            $this->sDescricaoAssociadoSituacao = $oAssociadoSituacao->fm02_descricao;
        }

        return $this->sDescricaoAssociadoSituacao;
    }

    /**
     * Retorna os dados do funcionário
     * @return Objetct
     */
    public function getDadosAssociado()
    {
        return $this->oDadosMatricula;
    }

    /**
     * Retorna as situações do associado cadastradas
     * @return Array
     */
    public function getSituacoes()
    {
        $oDaoAssociadoSituacao = new cl_associadosituacao();
        $sCampos = "fm02_situacao, fm02_descricao";
        $sSqlAssociadoSituacao = $oDaoAssociadoSituacao->sql_query_file(null, $sCampos, "fm02_situacao", null);
        $rsAssociadoSituacao = db_query($sSqlAssociadoSituacao);

        if (pg_num_rows($rsAssociadoSituacao) == 0) {
            throw new DBException("Cadastro de situações vazio, verifique." . pg_last_error());
        }

        $aSituacoes = array();
  
        while ($aRetornoSituacao = pg_fetch_object($rsAssociadoSituacao)) {
            $aSituacoes[$aRetornoSituacao->fm02_situacao] = $aRetornoSituacao->fm02_descricao;
        }

        return $aSituacoes;
    }

    public function salvarDados($oParametros)
    {
        $oResultAssociado = $this->salvarDadosAssociado($oParametros);

        if ($this->getSituacaoAssociado($oParametros->matricula) <> $oParametros->situacao) {
            $oResultAssociadoMovimentacao = $this->salvarDadosMovimentacao($oResultAssociado);
        }

        if ($oResultAssociadoMovimentacao->erro_status == 0) {
            $oResultAssociado->erro_msg = $oResultAssociadoMovimentacao->erro_msg;
            $oResultAssociado->erro_status = $oResultAssociadoMovimentacao->erro_status;
        }

        return $oResultAssociado->erro_msg;
    }

    public function salvarDadosMovimentacao($oParametros)
    {
        $oDaoAssociadoMovimentacao = new cl_associadomovimentacao;
        $oDaoAssociadoMovimentacao->fm03_associado = $oParametros->associado;
        $oDaoAssociadoMovimentacao->fm03_situacao = $oParametros->situacao;
        $oDaoAssociadoMovimentacao->fm03_usuario = db_getsession("DB_id_usuario");
        $oDaoAssociadoMovimentacao->incluir(null);

        $sMensagem = "Não foi possível incluir a movimentação do associado. Erro:";
        if ($oDaoAssociadoMovimentacao->erro_status == 0) {
            throw new DBException("{$sMensagem} {$oDaoAssociadoMovimentacao->erro_msg}");
        }

        return $oDaoAssociadoMovimentacao;
    }

    public function salvarDadosAssociado($oParametros)
    {
        $oDaoAssociado = new cl_associado;
        $oDaoAssociado->fm01_regist = $oParametros->matricula;
        $oDaoAssociado->fm01_cartaosus = $oParametros->cartaosus;
        $oDaoAssociado->fm01_dtcad = $oParametros->dtcadastro;

        if (empty($oParametros->associado)) {
            $oDaoAssociado->incluir(null);
            if ($oDaoAssociado->erro_status == 0) {
                throw new DBException("Não foi possível incluir o associado. Erro: {$oDaoAssociado->erro_msg}");
            }
        } else {
            $oDaoAssociado->alterar($oParametros->associado);
            if ($oDaoAssociado->erro_status == 0) {
                throw new DBException("Não foi possível alterar o associado. Erro: {$oDaoAssociado->erro_msg}");
            }
        }

        $oParametros->associado = $oDaoAssociado->fm01_associado;
        $oParametros->erro_msg = $oDaoAssociado->erro_msg;
        $oParametros->erro_status = $oDaoAssociado->erro_status;

        return $oParametros;
    }
}
