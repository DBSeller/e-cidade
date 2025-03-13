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

namespace ECidade\Financeiro\Contabilidade\Sagres\V2022;

use Exception;

/**
 * Class ArquivosFactory
 * @package ECidade\Financeiro\Orcamento\Sagres
 */
class ArquivosFactory
{
    private $ano;
    public function __construct($ano)
    {
        $this->ano = $ano;
    }

    /**
     * @param $arquivo
     * @param object $params
     * @param array $codigoInstituicoes
     * @param $codigoTCE
     * @throws Exception
     */
    public function get($arquivo, $params, array $codigoInstituicoes, $codigoTCE)
    {
        switch ($arquivo) {
            case 'UnidadeOrcamentaria':
                return new UnidadeOrcamentaria($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'Programas':
                return new Programas($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'Acao':
                return new Acao($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'Dotacao':
                return new Dotacao($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'AtualizacaoOrcamentaria':
                return new AtualizacaoOrcamentaria($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'DecretoseOficios':
                return new DecretoseOficios($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'ReceitaPrevista':
                return new ReceitaPrevista($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'Empenhos':
                return new Empenhos($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'Estorno':
                return new Estornos($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'Liquidacao':
                return new Liquidacao($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'EstornoLiquidacao':
                return new EstornoLiquidacao($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'Pagamentos':
                return new Pagamentos($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'EstornoPagamento':
                return new EstornoPagamento($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'Retencao':
                return new Retencao($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'EstornoRetencao':
                return new EstornoRetencao($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'ReceitaOrcamentaria':
                return new ReceitaOrcamentaria($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'TransfRecebida':
                return new TransfRecebida($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'TransfConcedida':
                return new TransfConcedida($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'ReceitaExtra':
                return new ReceitaExtra($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'DespesaExtra':
                return new DespesaExtra($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'EstornoReceitaExtra':
                return new EstornoReceitaExtra($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'EstornoDespesaExtra':
                return new EstornoDespesaExtra($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'CadastroContaBancaria':
                return new CadastroContaBancaria($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'RelacionamentoCCorrenteFontePagadora':
                return new RelacionamentoCCorrenteFontePagadora(
                    $params,
                    $codigoInstituicoes,
                    $this->ano,
                    $codigoTCE
                );
            case 'SaldoInicial':
                return new SaldoInicial($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'SaldoMensal':
                return new SaldoMensal($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'ConciliacaoBancaria':
                return new ConciliacaoBancaria($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'PagamentosRestos':
                return new PagamentosRestos($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'EstornoPagamentoRestos':
                return new EstornoPagamentoRestos($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'CancelamentoRestos':
                return new CancelamentoRestos($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'LiquidacaoRestos':
                return new LiquidacaoRestos($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'EstornoLiquidacaoRestos':
                return new EstornoLiquidacaoRestos($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'RetencaoRestos':
                return new RetencaoRestos($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'EstornoRetencaoRestos':
                return new EstornoRetencaoRestos($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'Fornecedores':
                return new Fornecedores($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'Ordenador':
                return new Ordenador($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'RelacionamentoEmpenhoObra':
                return new RelacionamentoEmpenhoObra($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'RelacionamentoEmpenhoLicitacao':
                return new RelacionamentoEmpenhoLicitacao(
                    $params,
                    $codigoInstituicoes,
                    $this->ano,
                    $codigoTCE
                );
            case 'RelacionamentoLiquidacaoCodigoAgrupamentoFolhaPagamento':
                return new RelacionamentoLiquidacaoCodigoAgrupamentoFolhaPagamento(
                    $params,
                    $codigoInstituicoes,
                    $this->ano,
                    $codigoTCE
                );
            case 'RestosInscritos':
                return new RestosInscritos($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'PloaAcao':
                return new PloaAcao($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'PloaDotacao':
                return new PloaDotacao($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'PloaPrograma':
                return new PloaPrograma($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'PloaReceitaPrevista':
                return new PloaReceitaPrevista($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'PloaUnidadeOrcamentaria':
                return new PloaUnidadeOrcamentaria($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            case 'RelacionamentoEmpenhoTipoMeta':
                return new RelacionamentoEmpenhoTipoMeta(
                    $params,
                    $codigoInstituicoes,
                    $this->ano,
                    $codigoTCE
                );
            case 'SaldoMensalCoConciliado':
                return new SaldoMensalCoConciliado($params, $codigoInstituicoes, $this->ano, $codigoTCE);
            default:
                throw new Exception("Classe {$arquivo} não implementada.");
        }
    }
}
