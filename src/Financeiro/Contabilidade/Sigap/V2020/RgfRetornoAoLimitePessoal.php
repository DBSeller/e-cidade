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

/**
 * Class RgfRetornoAoLimitePessoal
 * @package ECidade\Financeiro\Contabilidade\Sigap\V2020
 */
class RgfRetornoAoLimitePessoal extends OutrosArquivosSigapFiscal
{
    const TAG = 'RGFRetornoAoLimitePessoal';

    protected $template = [
        'rlpCodigoEntidade',
        'rlpQuadrimestre',
        'rlpSemestre',
        'rlpMesAnoMovimento',
        'rlpExercicio',
        'rlpPeriodoExcedente',
        'rlpLimiteMaximo',
        'rlpPercDTP',
        'rlpPercExcedente',
        'rlpExercicio1',
        'rlpQuadrimestreAjuste1',
        'rlpRedExcedente',
        'rlpPercLimite1',
        'rlpPercDTP1',
        'rlpExercicio2',
        'rlpQuadrimestreAjuste2',
        'rlpRedResidual',
        'rlpPercLimite2',
        'rlpPercDTP2',
    ];

    protected function processar()
    {
        return [
            'rlpCodigoEntidade' => $this->codigoTCE,
            'rlpQuadrimestre' => PeriodoDePara::quadrimestre($this->periodo),
            'rlpSemestre' => '00',
            'rlpMesAnoMovimento' => $this->periodo->getDataFinal($this->ano)->getDate(),
            'rlpExercicio' => 0,
            'rlpPeriodoExcedente' => 0,
        ];
    }
}
