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

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\Factory\AnexoIII;
use ECidade\Financeiro\Contabilidade\Sigap\ArquivoSigapFiscal;
use ECidade\Financeiro\Contabilidade\Sigap\Mapper\PeriodoDePara;

/**
 * Class DemonstrativoReceitaCorrenteLiquida
 * @package ECidade\Financeiro\Contabilidade\Sigap\V2020
 */
class DemonstrativoReceitaCorrenteLiquida extends ArquivoSigapFiscal
{
    const TAG = 'RREORecCorrenteLiquida';

    protected $template = [
        'rclCodigoEntidade',
        'rclBimestre',
        'rclMesAnoMovimento',
        'rclContaLRF',
        'rclDescricaoContaLRF',
        'rclVMR11',
        'rclVMR10',
        'rclVMR09',
        'rclVMR08',
        'rclVMR07',
        'rclVMR06',
        'rclVMR05',
        'rclVMR04',
        'rclVMR03',
        'rclVMR02',
        'rclVMR01',
        'rclVMR',
        'rclTotal',
        'rclPrevisaoAtualizada',
    ];

    /**
     * @var array
     */
    private $linhasProcessadas = [];

    protected function processar()
    {
        $anexoIII = AnexoIII::getInstance($this->ano, $this->periodo->getCodigo());
        $anexoIII->setInstituicoes(implode(', ', $this->codigoInstituicoes));
        $this->linhasProcessadas = $anexoIII->getDados();
    }

    public function getLinhasTemplate()
    {
        $path = static::TEMPLATE_PATH . 'V2020' . DS . 'Linhas';
        $this->linhasTemplate = require($path . DS . 'linha_Demonstrativo_Receita_Corrente_Liquida_AnexoIII.php');
        return $this->linhasTemplate;
    }

    protected function criaLinhaCalculo($linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];
        $dados = $this->criaLinhaTitulo($linha);
        $dados['rclVMR11'] = $linhaRelatorio->mes_1;
        $dados['rclVMR10'] = $linhaRelatorio->mes_2;
        $dados['rclVMR09'] = $linhaRelatorio->mes_3;
        $dados['rclVMR08'] = $linhaRelatorio->mes_4;
        $dados['rclVMR07'] = $linhaRelatorio->mes_5;
        $dados['rclVMR06'] = $linhaRelatorio->mes_6;
        $dados['rclVMR05'] = $linhaRelatorio->mes_7;
        $dados['rclVMR04'] = $linhaRelatorio->mes_8;
        $dados['rclVMR03'] = $linhaRelatorio->mes_9;
        $dados['rclVMR02'] = $linhaRelatorio->mes_10;
        $dados['rclVMR01'] = $linhaRelatorio->mes_11;
        $dados['rclVMR'] = $linhaRelatorio->mes_12;
        $dados['rclTotal'] = $linhaRelatorio->total;
        $dados['rclPrevisaoAtualizada'] = $linhaRelatorio->previsao_atualizada;

        return $dados;
    }

    /**
     * @param array $linha
     * @return array
     */
    protected function criaLinhaTitulo($linha)
    {
        return [
            'rclContaLRF' => $linha['conta_lrf'],
            'rclDescricaoContaLRF' => $linha['descricao'],
        ];
    }

    /**
     * @return array
     */
    protected function criaEstruturaCabecalho()
    {
        $periodo = $this->periodo->getDataFinal($this->ano);
        return [
            'rclCodigoEntidade' => $this->codigoTCE,
            'rclBimestre' => PeriodoDePara::bimestre($this->periodo),
            'rclMesAnoMovimento' => $periodo->getDate(),
        ];
    }
}
