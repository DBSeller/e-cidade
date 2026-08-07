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

use ECidade\Financeiro\Contabilidade\Sigap\ArquivoSigapFiscal;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2019\AnexoV;
use Periodo;

/**
 * Class DisponibilidadeCaixaRestosPagar
 * @package ECidade\Financeiro\Contabilidade\Sigap\V2020
 */
class DisponibilidadeCaixaRestosPagar extends ArquivoSigapFiscal
{
    const TAG = 'RGFDisponibilidadeDeCaixaERAP';

    private $notasExplicativas;

    public function get()
    {
        if ($this->periodo->getCodigo() === 11) {
            $this->periodo = new Periodo(13);
        }
        $this->periodo = new Periodo(13);

        $anexo = new AnexoV($this->ano, $this->periodo->getCodigo());
        $anexo->setInstituicoes(implode(', ', $this->codigoInstituicoes));
        $this->linhasProcessadas = $anexo->getDados();

        $this->notasExplicativas = $anexo->getTextoNotaExplicativa();
//        $anexo::CODIGO_RELATORIO
//        $relatorio = new RelatoriosLegaisBase($this->ano, $layout->getCodigoRelatorio(), $this->periodo->getCodigo());

        dd($anexo);
    }

    protected function processar()
    {
        // TODO: Implement processar() method.
    }

    public function getLinhasTemplate()
    {
        $path = static::TEMPLATE_PATH . 'V2020' . DS . 'Linhas' . DS;
        return require_once $path . 'linhas_RGF_Demonstrativo_Disponibilidade_Caixa_Restos_Pagar.php';
    }

    protected function criaLinhaCalculo($linha)
    {
        // TODO: Implement criaLinhaCalculo() method.
    }

    protected function criaLinhaTitulo($linha)
    {
        // TODO: Implement criaLinhaTitulo() method.
    }

    protected function criaEstruturaCabecalho()
    {
        // TODO: Implement criaEstruturaCabecalho() method.
    }
}
