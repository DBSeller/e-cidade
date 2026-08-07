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

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2020\AnexoXIII;
use ECidade\Financeiro\Contabilidade\Sigap\ArquivoSigapFiscal;
use ECidade\Financeiro\Contabilidade\Sigap\Mapper\PeriodoDePara;

class RREOParticipacaoPublicaPrivada extends ArquivoSigapFiscal
{
    /**
     * Tag principal do arquivo xml
     */
    const TAG = 'RREOParcPublicPrivada';

    /**
     * @var string[]
     */
    protected $template = [
        "pppCodigoEntidade",
        "pppBimestre",
        "pppMesAnoMovimento",
        "pppContaLRF",
        "pppDescricaoContaLRF",
        "pppExercAnterior",
        "pppRegBimestre",
        "pppRegateBimestre",
        "pppDepExercAnterior",
        "pppDepExercCorrente",
        "pppEC1",
        "pppEC2",
        "pppEC3",
        "pppEC4",
        "pppEC5",
        "pppEC6",
        "pppEC7",
        "pppEC8",
        "pppEC9",
    ];

    /**
     * @var array
     */
    protected $linhasProcessadas = [];


    protected function processar()
    {
        $this->getLinhasProcessadas();
    }

    public function getLinhasProcessadas()
    {
        $layout = new AnexoXIII($this->ano, $this->periodo->getCodigo());
        $layout->setInstituicoes(implode(', ', $this->codigoInstituicoes));

        $linhas = $layout->getLinhas();

        $contadorLinha = 1;

        foreach ($linhas as $linha) {
            if (preg_match('/^\d+$/', $linha->descricao)) {
                continue;
            }

            $this->linhasProcessadas[$contadorLinha] = $linha;
            $contadorLinha++;
        }
    }

    public function getLinhasTemplate()
    {
        $path = static::TEMPLATE_PATH . 'V2020' . DS . 'Linhas';
        $this->linhasTemplate = require($path . DS . 'linhas_RREO_Participacao_Publica_Privada.php');
        return $this->linhasTemplate;
    }

    protected function criaLinhaCalculo($linha)
    {
        $linha_ecidade = $linha['linha_ecidade'];

        if ($linha_ecidade < 12) {
            return $this->criarLinhaImpactosContratacoes($linha);
        }

        return $this->criarLinhaDespesasPPP($linha);
    }


    private function criarLinhaImpactosContratacoes($linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        return [
            "pppContaLRF" => $linha['conta_lrf'],
            "pppDescricaoContaLRF" => $linha['descricao'],
            "pppExercAnterior" => $this->formatarValor($linhaRelatorio->saldo_anterior),
            "pppRegBimestre" => $this->formatarValor($linhaRelatorio->saldo_anterior_no_bimestre),
            "pppRegateBimestre" => $this->formatarValor($linhaRelatorio->saldo_final)
        ];
    }

    private function criarLinhaDespesasPPP($linha)
    {
        $linhaRelatorio = $this->linhasProcessadas[$linha['linha_ecidade']];

        return [
            "pppContaLRF" => $linha['conta_lrf'],
            "pppDescricaoContaLRF" => $linha['descricao'],
            "pppDepExercAnterior" => $this->formatarValor($linhaRelatorio->exercicio_anterior),
            "pppDepExercCorrente" => $this->formatarValor($linhaRelatorio->exercicio_corrente),
            "pppEC1" => $this->formatarValor($linhaRelatorio->exercicio_corrente_1),
            "pppEC2" => $this->formatarValor($linhaRelatorio->exercicio_corrente_2),
            "pppEC3" => $this->formatarValor($linhaRelatorio->exercicio_corrente_3),
            "pppEC4" => $this->formatarValor($linhaRelatorio->exercicio_corrente_4),
            "pppEC5" => $this->formatarValor($linhaRelatorio->exercicio_corrente_5),
            "pppEC6" => $this->formatarValor($linhaRelatorio->exercicio_corrente_6),
            "pppEC7" => $this->formatarValor($linhaRelatorio->exercicio_corrente_7),
            "pppEC8" => $this->formatarValor($linhaRelatorio->exercicio_corrente_8),
            "pppEC9" => $this->formatarValor($linhaRelatorio->exercicio_corrente_9)
        ];
    }


    protected function criaLinhaTitulo($linha)
    {
        return [
            "pppContaLRF" => $linha['conta_lrf'],
            "pppDescricaoContaLRF" => $linha['descricao']
        ];
    }

    protected function criaEstruturaCabecalho()
    {
        return [
            "pppCodigoEntidade" => $this->codigoTCE,
            "pppBimestre" =>  PeriodoDePara::bimestre($this->periodo),
            "pppMesAnoMovimento" => $this->periodo->getDataFinal($this->ano)->getDate(),
        ];
    }
}
