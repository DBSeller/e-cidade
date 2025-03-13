<?php

/**
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

namespace ECidade\Financeiro\Contabilidade\ContaCorrente\Consulta;

use ECidade\Financeiro\Contabilidade\ContaCorrente\Consulta\Formatter\ConsultaInterface;
use ECidade\Financeiro\Contabilidade\ContaCorrente\Consulta\Processamento\Processamento;
use ECidade\Financeiro\Contabilidade\ContaCorrente\Model\Visao;

class Consulta
{

    /**
     * Processamento dos dados da consutla
     * @var Processamento
     */
    protected $processamento;

    protected $lMsc = false;


    /**
     * @var ConsultaInterface
     */
    protected $formatter;

    /**
     * Colunas visiveis na consulta
     * @var array
     */
    protected $colunas = array();


    /**
     * filtros do processamento
     * @var \stdClass
     */
    private $filtros = null;

    /**
     * @var Visao
     */
    private $visao;



    public function setMsc($lMsc)
    {
        $this->lMsc = $lMsc;
    }

    public function getMsc()
    {
        return $this->lMsc;
    }





    /**
     * Consulta constructor.
     * @param ConsultaInterface $formatter
     * @param Processamento $processamento
     */
    public function __construct(ConsultaInterface $formatter, Processamento $processamento)
    {
        $this->formatter = $formatter;
        $this->processamento = $processamento;
    }


    /**
     * @return null
     */
    public function getFiltros()
    {
        return $this->filtros;
    }

    /**
     * @param null $filtros
     */
    public function setFiltros($filtros)
    {
        $this->filtros = $filtros;
        $this->processamento->setFiltros($filtros);
    }

    /**
     * @return array
     */
    public function getColunas()
    {
        return $this->colunas;
    }

    /**
     * @param array $colunas
     */
    public function setColunas($colunas)
    {
        $this->colunas = $colunas;
    }


    /**
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function emitir()
    {
        $this->processamento->setFiltros($this->filtros);
        $this->formatter->setColunas($this->colunas);
        $this->processamento->setColunas($this->colunas);

        if (!empty($this->visao)) {
            $this->formatter->setVisao($this->visao);
        }

        if ($this->getMsc()) {
            $dadosProcessados =  $this->processamento->getDadosMsc();
        } else {
            $dadosProcessados =  $this->processamento->getDados();
        }

        if (count($dadosProcessados) == 0) {
            throw new \Exception("Não existem movimentações de conta corrente para os filtros informados.");
        }
        $this->formatter->setDados($dadosProcessados);
        $this->formatter->setAgruparPorDocumento($this->processamento->agrupaPorDocumentoContabil());

        $aRetorno = $this->formatter->formatar();

        return $aRetorno;
    }

    /**
     * @return Visao
     */
    public function getVisao()
    {

        return $this->visao;
    }

    /**
     * @param Visao $visao
     */
    public function setVisao($visao)
    {

        $this->visao = $visao;
    }

    /**
     * Retorna a configuracao Padrao da
     * @return array
     */
    public function getConfiguracaoPadrao()
    {
        $configuracaoGrid = array (
            'configuracaoGrid' =>
                array (
                    'estrutural' =>
                        array (
                            'label' => 'Conta',
                            'visible' => true,
                        ),
                    'descricao' =>
                        array (
                            'label' => 'Descricao',
                            'visible' => true,
                        ),
                    'conta_corrente' =>
                        array (
                            'label' => 'Conta Corrente',
                            'visible' => true,
                        ),
                    'saldo_anterior' =>
                        array (
                            'label' => 'Saldo Anterior',
                            'visible' => true,
                        ),
                    'debitos' =>
                        array (
                            'label' => 'Débitos',
                            'visible' => true,
                        ),
                    'creditos' =>
                        array (
                            'label' => 'Crédito',
                            'visible' => true,
                        ),
                    'saldo_final' =>
                        array (
                            'label' => 'Saldo Final',
                            'visible' => true,
                        ),
                ),
        );
        return (object)$configuracaoGrid;
    }
}
