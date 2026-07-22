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
 * Class RgfAcompRetornoAoLimiteDivida
 * @package ECidade\Financeiro\Contabilidade\Sigap\V2020
 */
class RgfAcompanhamentoRetornoAoLimiteDivida extends OutrosArquivosSigapFiscal
{
    const TAG = 'RGFAcompRetornoAoLimiteDivida';

    protected $template = [
        'rdcCodigoEntidade',
        'rdcQuadrimestre',
        'rdcSemestre',
        'rdcMesAnoMovimento',
        'rdcExercicio',
        'rdcQuadrimestreExced',
        'rdcLimiteMaximo',
        'rdcPercDCL',
        'rdcPercExcedente',
        'rdcExercicio1',
        'rdcQuadrimestreAjuste1',
        'rdcRedExcedente1',
        'rdcPercLimite1',
        'rdcPercDCL1',
        'rdcExercicio2',
        'rdcQuadrimestreAjuste2',
        'rdcRedExcedente2',
        'rdcPercLimite2',
        'rdcPercDCL2',
        'rdcExercicio3',
        'rdcQuadrimestreAjuste3',
        'rdcRedExcedente3',
        'rdcPercLimite3',
        'rdcPercDCL3',
    ];

    protected function processar()
    {
        return [
            'rdcCodigoEntidade' => $this->codigoTCE,
            'rdcQuadrimestre' => PeriodoDePara::quadrimestre($this->periodo),
            'rdcSemestre' => '00',
            'rdcMesAnoMovimento' => $this->periodo->getDataFinal($this->ano)->getDate(),
            'rdcExercicio' => '0000',
            'rdcQuadrimestreExced' => 0,
            'rdcExercicio1' => 0,
            'rdcQuadrimestreAjuste1' => 0,
            'rdcExercicio2' => 0,
            'rdcQuadrimestreAjuste2' => 0,
            'rdcExercicio3' => 0,
            'rdcQuadrimestreAjuste3' => 0,
        ];
    }
}
