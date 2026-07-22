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

namespace ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial;

use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\RetencaoRepository;
use BusinessException;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Retencao;

class RetencaoService
{
    /**
     * @var
     */
    private $retencaoRepository;

    /**
     * unicidadeService constructor.
    */
    public function __construct()
    {
        $this->retencaoRepository = new RetencaoRepository();
    }

    /**
     * @param Retencao
     * @return Retencao
     * @throws BusinessException
     */
    public function salvar(Retencao $retencao)
    {
        $verificarRegra = false;

        if (!empty($retencao->getCodigoIndicativoSuspensao()) ||
            !empty($retencao->getNumeroProcesso()) ||
            !empty($retencao->getNumeroProcesso())) {
                $verificarRegra = true;
        }

        if (empty($retencao->getTipoProcesso()) && $verificarRegra) {
            throw new BusinessException("O código correspondente ao tipo de processo é obrigatório em " .
            "'Informações de processos relacionados a não retenção de tributos ou a depósitos judiciais'." .
            "Favor revisar.");
        }

        if (empty($retencao->getNumeroProcesso()) && $verificarRegra) {
            throw new BusinessException("O número do processo administrativo/judicial é obrigatório em " .
            "'Informações de processos relacionados a não retenção de tributos ou a depósitos judiciais'." .
            "Favor revisar.");
        }

        if (!in_array((int) strlen($retencao->getNumeroProcesso()), [17,20,21])) {
            throw new BusinessException("O número do processo administrativo/judicial inválido em " .
            "'Informações de processos relacionados a não retenção de tributos ou a depósitos judiciais'." .
            "Favor revisar.");
        }

        return $this->retencaoRepository->save($retencao);
    }

        /**
     * @param Retencao
     * @return Retencao
     * @throws BusinessException
     */
    public function excluir(Retencao $retencao)
    {
        return $this->retencaoRepository->delete($retencao);
    }
}
