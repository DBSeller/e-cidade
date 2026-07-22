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

namespace ECidade\Financeiro\Contabilidade\Sigap;

use Periodo;

/**
 * Class OutrosArquivosSigapFiscal
 * @package ECidade\Financeiro\Contabilidade\Sigap
 */
abstract class OutrosArquivosSigapFiscal implements ArquivosSigapFiscalInterface
{
    /**
     * @var integer
     */
    protected $ano;
    /**
     * @var Periodo
     */
    protected $periodo;
    /**
     * @var array
     */
    protected $codigoInstituicoes = [];
    /**
     * @var integer
     */
    protected $codigoTCE;

    public function __construct(Periodo $periodo, array $codigoInstituicoes, $ano, $codigoTCE)
    {
        $this->periodo = $periodo;
        $this->codigoInstituicoes = $codigoInstituicoes;
        $this->codigoTCE = $codigoTCE;
        $this->ano = $ano;
    }

    abstract protected function processar();


    public function emitirXML()
    {
        $colunas = $this->processar();
        $xml = new \DOMDocument("1.0", "UTF-8");
        $principalNode = $xml->createElement(static::TAG);
        $elementoLinha = $xml->createElement('Elem' . static::TAG);

        foreach ($this->template as $tag) {
            if (array_key_exists($tag, $colunas)) {
                $elementoColuna = $xml->createElement($tag, $colunas[$tag]);
            } else {
                $elementoColuna = $xml->createElement($tag, '0.00');
            }
            $elementoLinha->appendChild($elementoColuna);
        }

        $principalNode->appendChild($elementoLinha);

        $xml->appendChild($principalNode);

        $filePath = 'tmp' . DS . static::TAG . '_' . $this->codigoTCE . '.xml';
        file_put_contents($filePath, $xml->saveXML());

        return $filePath;
    }
}
