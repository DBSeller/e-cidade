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

use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\SuspensaoPensaoRepository;
use BusinessException;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\SuspensaPensao;

class SuspensaoPensaoService
{
    /**
     * @var
     */
    private $suspensaoPensaoRepository;

    /**
     * unicidadeService constructor.
    */
    public function __construct()
    {
        $this->suspensaoPensaoRepository = new SuspensaoPensaoRepository();
    }

    /**
     * @param SuspensaPensao
     * @return SuspensaPensao
     * @throws BusinessException
     */
    public function salvar(SuspensaPensao $suspensaoPensao)
    {
        $verificarRegra = false;

        if (!empty($suspensaoPensao->getCpfDependente()) ||
            !empty($suspensaoPensao->getValorDeducao())) {
                $verificarRegra = true;
        }

        if (empty($suspensaoPensao->getCpfDependente()) && $verificarRegra) {
            throw new BusinessException("Número de inscrição no CPF é obrigatório em " .
            "'Informação das deduções suspensas por dependentes e beneficiários da pensão alimentícia'." .
            "Favor revisar.");
        }

        if (strlen($suspensaoPensao->getCpfDependente()) != 11 && $verificarRegra) {
            throw new BusinessException("CPF é inválido em " .
            "'Informação das deduções suspensas por dependentes e beneficiários da pensão alimentícia'." .
            "Favor revisar.");
        }

        if ((int) $suspensaoPensao->getValorDeducao() <= 0 && $verificarRegra) {
            throw new BusinessException("Valor de dedução inválido em " .
            "'Informação das deduções suspensas por dependentes e beneficiários da pensão alimentícia'." .
            "Favor revisar.");
        }

        return $this->suspensaoPensaoRepository->save($suspensaoPensao);
    }

        /**
     * @param SuspensaPensao
     * @return SuspensaPensao
     * @throws BusinessException
     */
    public function excluir(SuspensaPensao $suspensaoPensao)
    {
        return $this->suspensaoPensaoRepository->delete($suspensaoPensao);
    }
}
