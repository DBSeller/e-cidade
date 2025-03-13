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
use ECidade\Financeiro\Contabilidade\Sigap\OutrosArquivosSigapFiscal;
use Periodo;

/**
 * Class RGFRetornoAoLimiteDivida
 * @package ECidade\Financeiro\Contabilidade\Sigap\V2020
 */
class RgfRetornoAoLimiteDivida extends OutrosArquivosSigapFiscal
{
    const TAG = 'RGFRetornoAoLimiteDivida';

    /**
     * @var string[]
     */
    protected $template = [
        'rldCodigoEntidade',
        'rldQuadrimestre',
        'rldSemestre',
        'rldMesAnoMovimento',
        'rldExercicio',
        'rldQuadrimestreExced',
        'rldLimiteMaximo',
        'rldPercDCL',
        'rldPercExcedente',
        'rldExercicio1',
        'rldQuadrimestreAjuste1',
        'rldRedExcedente1',
        'rldPercLimite1',
        'rldPercDCL1',
        'rldExercicio2',
        'rldQuadrimestreAjuste2',
        'rldRedExcedente2',
        'rldPercLimite2',
        'rldPercDCL2',
        'rldExercicio3',
        'rldQuadrimestreAjuste3',
        'rldRedExcedente3',
        'rldPercLimite3',
        'rldPercDCL3',
    ];

    /**
     * @return array
     */
    protected function processar()
    {
        return [
            'rldCodigoEntidade' => $this->codigoTCE,
            'rldQuadrimestre' => PeriodoDePara::quadrimestre($this->periodo),
            'rldSemestre' => '00',
            'rldMesAnoMovimento' => $this->periodo->getDataFinal($this->ano)->getDate(),
            'rldExercicio' => 0,
            'rldQuadrimestreExced' => 0,
            'rldExercicio1' => 0,
            'rldQuadrimestreAjuste1' => 0,
            'rldExercicio2' => 0,
            'rldQuadrimestreAjuste2' => 0,
            'rldExercicio3' => 0,
            'rldQuadrimestreAjuste3' => 0,
        ];
    }
}
