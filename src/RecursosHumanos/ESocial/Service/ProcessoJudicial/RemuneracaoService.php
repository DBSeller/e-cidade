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

use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\RemuneracaoRepository;
use BusinessException;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Remuneracao;

class RemuneracaoService
{
    /**
     * @var
     */
    private $remuneracaoRepository;

    /**
     *  constructor.
    */
    public function __construct()
    {
        $this->remuneracaoRepository = new RemuneracaoRepository();
    }

    /**
     * @param Remuneracao
     * @return Remuneracao
     * @throws BusinessException
     */
    public function salvar(Remuneracao $remuneracao)
    {
        $obrigatorio = false;
        if (!empty($remuneracao->getDataRemuneracao()) ||
            !empty($remuneracao->getValorRemuneracao() ||
            !empty($remuneracao->getUnidadeSalarioFixo()))) {
                $obrigatorio = true;
        }

        $contrato = $remuneracao->getProcessoContrato();
        $servidor = $contrato[0]->getServidorProcesso();
        if ((int) $contrato[0]->getTipoContrato() != 6 &&
            (int) $servidor[0]->getRegimePrevidenciario() == 2) {
            $obrigatorio = false;
        }

        if ((int) $contrato[0]->getTipoContrato() != 6 &&
            (int) $servidor[0]->getRegimePrevidenciario() == 1) {
            $obrigatorio = true;
        }

        if ($obrigatorio && $remuneracao->getDataRemuneracao()) {
            throw new BusinessException("Data de remuneração vazia. Favor revisar.");
        }

        if ($obrigatorio && $remuneracao->getValorRemuneracao()) {
            throw new BusinessException("Valor nulo de remuneração. Favor revisar.");
        }

        if ($obrigatorio && ($remuneracao->getValorRemuneracao() < 0)) {
            throw new BusinessException("Valor de remuneração negativo. Favor revisar.");
        }

        if ($obrigatorio && $remuneracao->getUnidadeSalarioFixo()) {
            throw new BusinessException("Unidade de salário em remuneração não definido. Favor revisar");
        }

        return $this->remuneracaoRepository->save($remuneracao);
    }

    /**
     * @param Remuneracao
     * @return Remuneracao
     * @throws Exception
     */
    public function excluir(Remuneracao $remuneracao)
    {
        return $this->remuneracaoRepository->delete($remuneracao);
    }
}
