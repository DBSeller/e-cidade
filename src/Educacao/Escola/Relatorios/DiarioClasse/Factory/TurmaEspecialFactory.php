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

namespace ECidade\Educacao\Escola\Relatorios\DiarioClasse\Factory;

use App\Domain\Educacao\Escola\Requests\EmissaoDiarioClasseEspecialRequest;
use ECidade\Educacao\Escola\Relatorios\DiarioClasse\Service\TurmaEspecialService;
use ECidade\Educacao\Escola\Relatorios\DiarioClasse\Service\TurmaRegularAtividadeComplementarSevice;
use Exception;

/**
 * Class TurmaEspecialFactory
 * @package ECidade\Educacao\Escola\Relatorio\DiarioClasse\Factory
 */
class TurmaEspecialFactory
{
    /**
     * @param $tipoTurma
     * @param EmissaoDiarioClasseEspecialRequest $request
     * @return TurmaEspecialService|TurmaRegularAtividadeComplementarSevice
     * @throws Exception
     */
    public static function get($tipoTurma, EmissaoDiarioClasseEspecialRequest $request)
    {
        if ((int)$tipoTurma === 1) {
            return new TurmaRegularAtividadeComplementarSevice($request);
        }
        if ((int) $tipoTurma === 4) {
            return new TurmaEspecialService($request);
        }

        return new TurmaEspecialService($request);
    }
}
