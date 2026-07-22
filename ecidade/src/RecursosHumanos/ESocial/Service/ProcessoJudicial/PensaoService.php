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

use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\PensaoRepository;
use BusinessException;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Pensao;

class PensaoService
{
    /**
     * @var
     */
    private $pensaoRepository;

    /**
     * unicidadeService constructor.
    */
    public function __construct()
    {
        $this->pensaoRepository = new PensaoRepository();
    }

    /**
     * @param Pensao
     * @return Pensao
     * @throws BusinessException
     */
    public function salvar(Pensao $pensao)
    {
        $verificarRegra = false;

        if (!empty($pensao->getTipoRendimento()) ||
            !empty($pensao->getCpfPensao()) ||
            !empty($pensao->getValorPensao())) {
                $verificarRegra = true;
        }

        if (empty($pensao->getTipoRendimento()) && $verificarRegra) {
            throw new BusinessException("Tipo de rendimento é obrigatório em" .
            "'Informação dos beneficiários da pensão alimentícia'." .
            "Favor revisar.");
        }

        if (empty($pensao->getCpfPensao()) && $verificarRegra) {
            throw new BusinessException("O número de CPF é obrigatório em " .
            "'Informação dos beneficiários da pensão alimentícia'." .
            "Favor revisar.");
        }

        if (empty($pensao->getValorPensao()) && $verificarRegra) {
            throw new BusinessException("O valor é obrigatório em " .
            "'Valor relativo à dedução do rendimento tributável correspondente a pagamento de pensão alimentícia.'." .
            "Favor revisar.");
        }

        return $this->pensaoRepository->save($pensao);
    }

        /**
     * @param Pensao
     * @return Pensao
     * @throws BusinessException
     */
    public function excluir(Pensao $pensao)
    {
        return $this->pensaoRepository->delete($pensao);
    }
}
