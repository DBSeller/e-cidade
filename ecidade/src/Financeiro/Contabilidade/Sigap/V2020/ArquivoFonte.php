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

use DBDate;
use DBDepartamento;
use ECidade\Financeiro\Contabilidade\Sigap\ArquivosSigapFiscalInterface;
use ECidade\Financeiro\Contabilidade\Sigap\Mapper\PeriodoDePara;
use Exception;
use Periodo;

/**
 * Class ArquivoFonte
 * @package ECidade\Financeiro\Contabilidade\Sigap\V2020
 */
class ArquivoFonte implements ArquivosSigapFiscalInterface
{

    const TAG = 'ArquivodeFonte';

    const SISTEMA = 'DBSeller Serviços de Informática Ltda';

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
        'fntCodigoEntidade',
        'fntMesAnoMovimento',
        'fntSistema',
        'fntUnidResponsavel',
        'fntMesAnoEmissao',
        'fntHora',
    ];
    /**
     * @var DBDepartamento
     */
    private $departamento;


    public function __construct(Periodo $periodo, array $codigoInstituicoes, $ano, $codigoTCE)
    {
        $this->periodo = $periodo;
        $this->codigoInstituicoes = $codigoInstituicoes;
        $this->codigoTCE = $codigoTCE;
        $this->ano = $ano;
    }

    /**
     * @return string
     */
    public function emitirXML()
    {
        $xml = new \DOMDocument("1.0", "UTF-8");
        $principalNode = $xml->createElement(static::TAG);
        $elementoLinha = $xml->createElement('Elem' . static::TAG);
        $unidade = $this->getDescricaoUnidade();


        $dados = [
            'fntCodigoEntidade' => $this->codigoTCE,
            'fntMesAnoMovimento' => $this->periodo->getDataFinal($this->ano)->convertTo(DBDate::DATA_EN),
            'fntSistema' => utf8_encode(self::SISTEMA),
            'fntUnidResponsavel' => utf8_encode($unidade),
            'fntMesAnoEmissao' => date('Y-m-d'),
            'fntHora' => date('H:i'),
        ];

        foreach ($this->template as $tag) {
            $elementoColuna = $xml->createElement($tag, $dados[$tag]);
            $elementoLinha->appendChild($elementoColuna);
        }

        $principalNode->appendChild($elementoLinha);
        $xml->appendChild($principalNode);

        $filePath = 'tmp' . DS . static::TAG . '_' . $this->codigoTCE . '.xml';
        file_put_contents($filePath, $xml->saveXML());

        return $filePath;
    }

    public function setDepartamento(DBDepartamento $departamento)
    {
        $this->departamento = $departamento;
    }

    /**
     * @return string
     * @throws Exception
     */
    private function getDescricaoUnidade()
    {
        $where = " coddepto = {$this->departamento->getCodigo()} and o41_anousu = {$this->ano}";
        $dao = new \cl_db_departorg();
        $sql = $dao->sql_query(null, null, 'o41_descr', null, $where);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar unidade.");
        }

        if (pg_num_rows($rs) == 0) {
            throw new Exception("Erro ao buscar unidade.");
        }

        $dados = pg_fetch_array($rs);

        $unidade = sprintf('%s - %s', $this->departamento->getNomeDepartamento(), $dados[0]);
        return $unidade;
    }
}
