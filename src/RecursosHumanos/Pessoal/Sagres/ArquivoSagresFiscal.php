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

namespace ECidade\RecursosHumanos\Pessoal\Sagres;

use Matrix\Exception;
use Periodo;

/**
 * Class ArquivoSagresFiscal
 * @package ECidade\RecursosHumanos\Pessoal\Sagres
 */
abstract class ArquivoSagresFiscal implements ArquivosSagresFiscalInterface
{
    /**
     * Caminho base dos Templates de Linhas
     */
    const TEMPLATE_PATH = ECIDADE_PATH . DS . 'src' . DS . 'RecursosHumanos' . DS . 'Pessoal' . DS . 'Sagres' . DS;
    /**
     * @var string
     */
    protected $periodo;
    /**
     * @var integer
     */
    protected $ano;
    /**
     * @var integer
     */
    protected $codigoTCE;
    /**
     * @var array
     */
    protected $codigoInstituicoes = [];

    /**
     * @var array
     */
    protected $template = [];

    /**
     * @var string
     */
    protected $notasExplicativas;

    /**
     * ArquivoSagresFiscal constructor.
     * @param $periodo
     * @param array $codigoInstituicoes
     * @param integer $ano
     * @param integer $codigoTCE
     */
    public function __construct($periodo, array $codigoInstituicoes, $ano, $codigoTCE)
    {
        $this->periodo = $periodo;
        $this->ano = $ano;
        $this->codigoInstituicoes = $codigoInstituicoes;
        $this->codigoTCE = $codigoTCE;
    }

    /**
     * Deve encapsular a busca e processamento de todas informações do relatório
     * @return void
     */
    abstract protected function processar();

    /**
     * Deve retornar um conjunto as linhas do xml em um array estruturado
     * @return array
     */
    abstract public function getLinhasTemplate();

    /**
     * linha do template: .../V2020/Linhas/linhas_*.php
     * Essa é uma linha existente no relatório legal
     * @param array $linha
     * @return array
     */
    abstract protected function criaLinhaCalculo($linha);

    /**
     * linha do template: .../V2020/Linhas/linhas_*.php
     * É uma linha de cabeçalho
     * @param array $linha
     * @return array
     */
    abstract protected function criaLinhaTitulo($linha);

    /**
     * Deve retornar um array com a estrutura comum a todos layouts dos relatórios legais
     * @return array
     */
    abstract protected function criaEstruturaCabecalho();

    /**
     * @return string
     */
    public function emitirXML()
    {
        $this->processar();
        $xml = new \DOMDocument("1.0", "UTF-8");
        $principalNode = $xml->createElement(static::TAG);

        $linhas = $this->getLinhasTemplate();
        foreach ($linhas as $linha) {
            $elementoLinha = $xml->createElement('Elem' . static::TAG);
            $cabecalho = $this->criaEstruturaCabecalho();

            // processa as informações da linha devolvendo um array com todas colunas do layout
            if (!empty($linha['linha_ecidade'])) {
                $dados = $this->criaLinhaCalculo($linha);
            } else {
                $dados = $this->criaLinhaTitulo($linha);
            }

            if (!is_array($cabecalho) || !is_array($dados)) {
                throw new Exception(
                    sprintf('Erro ao emitir formulário "%s", Linha "%s"', static::TAG, $linha['descricao'])
                );
            }

            $dados = array_merge($cabecalho, $dados);
            // percorre o template das colunas do layou criando os elementos conforme a tag
            foreach ($this->template as $tag) {
                // se a tag é uma linha calculada do  relatório do e-cidade, cria a coluna com o valor apresentado
                // no relatório, se não cria com valor default
                if (array_key_exists($tag, $dados)) {
                    $elementoColuna = $xml->createElement($tag, $dados[$tag]);
                } else {
                    $elementoColuna = $xml->createElement($tag, '0.00');
                }

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
     * @param $valor
     * @return float
     */
    protected function formatarValor($valor)
    {
        $valorFormatado = empty($valor) || $valor === '-' ? '0.00' : round($valor, 2);
        return number_format($valorFormatado, 2, '.', '');
    }

    /**
     * @return string
     */
    public function getNotasExplicativas()
    {
        return $this->notasExplicativas;
    }
}
