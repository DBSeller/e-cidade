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

namespace ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Model;

use BalancoPatrimonialDCASP2017;
use ECidade\Financeiro\Orcamento\Repository\RecursoRepository;
use Exception;
use stdClass;

/**
 * Class BalancoPatrimonialDCASP2018
 * @package ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Model
 */
class BalancoPatrimonialDCASP2018 extends BalancoPatrimonialDCASP2017
{
    /**
     * @return bool|stdClass[]
     * @throws Exception
     */
    protected function getSuperavitDeficit()
    {
        if (!$this->exibirQuadroRelatorio(self::QUADRO_SUPERAVIT)) {
            return false;
        }

        $mes = $this->oPeriodo->getMesInicial();

        $recursosExercicioAtual = $this->getValoresRecursos($this->iAnoUsu, $mes);

        $recursosExercicioAnterior = $this->lExibirExercicioAnterior ? $this->getValoresRecursos($this->iAnoUsu - 1, 12) : array();

        return $this->processaSuperavitDeficit($recursosExercicioAtual, $recursosExercicioAnterior);
    }

    /**
     * @param $ano
     * @param $mes
     * @return stdClass[]
     * @throws Exception
     */
    private function getValoresRecursos($ano, $mes)
    {
        $instituicoes = $this->getInstituicoes(true);

        return RecursoRepository::getValoresRecursosPorCompetencia($ano, $mes, '82111', $instituicoes);
    }

}
