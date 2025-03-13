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

namespace ECidade\Financeiro\Contabilidade\Sigap\V2020;

use ECidade\Financeiro\Contabilidade\Sigap\Mapper\PeriodoDePara;
use ECidade\Financeiro\Contabilidade\Sigap\Model\PublicidadeSigapFiscal;
use ECidade\Financeiro\Contabilidade\Sigap\Service\PublicidadeSigapFiscalService;
use Exception;
use InstituicaoRepository;
use Periodo;

/**
 * Class ArquivoPublicidade
 * @package ECidade\Financeiro\Contabilidade\Sigap\V2020
 */
class ArquivoPublicidade
{
    const TAG = 'Publicidade';

    /**
     * @var Periodo
     */
    private $periodo;
    /**
     * @var array
     */
    private $codigoInstituicoes;
    /**
     * @var integer
     */
    private $codigoTCE;

    /**
     * Array com as notas explicativas dos relatórios enviados. indexado pelo código do demonstrativo
     * @var array
     */
    private $notas = [];
    /**
     * @var int
     */
    private $ano;

    /**
     * @var string[]
     */
    protected $template = [
        'pubCodigoEntidade',
        'pubMesAnoMovimento',
        'pubDescricao',
        'pubDataPublicacao',
        'pubTipoMeioComunicacao',
        'pubTipoRelatorioFiscal',
        'pubPeriodo',
        'pubLinkTransparencia',
        'pubLocalPublicacao',
    ];

    /**
     * @var array
     */
    private $linhasProcessadas = [];


    public function __construct(Periodo $periodo, array $codigoInstituicoes, $ano, $codigoTCE)
    {
        $this->periodo = $periodo;
        $this->codigoInstituicoes = $codigoInstituicoes;
        $this->codigoTCE = $codigoTCE;
        $this->ano = $ano;
    }

    /**
     * @throws Exception
     */
    public function processar()
    {
        foreach ($this->codigoInstituicoes as $codigoInstituicao) {
            $instituicao = InstituicaoRepository::getInstituicaoByCodigo($codigoInstituicao);

            $service = new PublicidadeSigapFiscalService($instituicao);
            $publicidades = $service->getPublicidadesPorAno($this->ano);
            foreach ($publicidades as $publicidade) {
                if (!$this->validaPeriodo($publicidade)) {
                    continue;
                }

                $this->linhasProcessadas[] = $this->criaEstruturaXml($publicidade);
            }
        }
    }

    /**
     * @return string
     */
    public function emitirXML()
    {
        $this->processar();
        $xml = new \DOMDocument("1.0", "UTF-8");
        $principalNode = $xml->createElement(static::TAG);

        foreach ($this->linhasProcessadas as $linhasProcessada) {
            $elementoLinha = $xml->createElement('Elem' . static::TAG);

            foreach ($this->template as $tag) {
                $elementoColuna = $xml->createElement($tag, $linhasProcessada[$tag]);
                $elementoLinha->appendChild($elementoColuna);
            }

            $principalNode->appendChild($elementoLinha);
        }

        $xml->appendChild($principalNode);

        $filePath = 'tmp' . DS . static::TAG . '_' . $this->codigoTCE . '.xml';
        file_put_contents($filePath, $xml->saveXML());

        return $filePath;
    }

    /**
     * @param PublicidadeSigapFiscal $publicidade
     */
    private function criaEstruturaXml(PublicidadeSigapFiscal $publicidade)
    {
        if (in_array($publicidade->getPeriodo()->getCodigo(), [6, 7, 8, 9, 10, 11])) {
            $periodo = PeriodoDePara::bimestre($publicidade->getPeriodo());
        } elseif (in_array($publicidade->getPeriodo()->getCodigo(), [14, 15, 16])) {
            $periodo = PeriodoDePara::quadrimestre($publicidade->getPeriodo());
        }

        $meio = str_pad($publicidade->getMeioComunicacao()->getCodigoSigap(), 2, '0', STR_PAD_LEFT);

        return [
            'pubCodigoEntidade' => $this->codigoTCE,
            'pubMesAnoMovimento' => $this->periodo->getDataFinal($this->ano)->getDate(),
            'pubDescricao' => $publicidade->getDescricao(),
            'pubDataPublicacao' => $publicidade->getDataPublicacao()->getDate(),
            'pubTipoMeioComunicacao' => $meio,
            'pubTipoRelatorioFiscal' => str_pad($publicidade->getCodigoTipoRelatorio(), 2, '0', STR_PAD_LEFT),
            'pubPeriodo' => $periodo,
            'pubLinkTransparencia' => $publicidade->getLink(),
            'pubLocalPublicacao' => $publicidade->getLocalPublicacao(),
        ];
    }

    private function validaPeriodo(PublicidadeSigapFiscal $publicidade)
    {
        $codigo = (int) $publicidade->getPeriodo()->getCodigo();
        switch ($this->periodo->getCodigo()) {
            case 6:
                return $codigo === 6;
            case 7:
                return in_array($codigo, [7, 14]);
            case 8:
                return $codigo === 8;
            case 9:
                return in_array($codigo, [9, 15]);
            case 10:
                return $codigo === 10;
            case 11:
                return in_array($codigo, [11, 16]);
        }

        return false;
    }
}
