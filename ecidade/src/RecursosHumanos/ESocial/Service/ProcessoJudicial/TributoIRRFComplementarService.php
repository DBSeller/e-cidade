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

use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\TributoIRRFComplementarRepository;
use BusinessException;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\TributoIRRFComplementar;

class TributoIRRFComplementarService
{
    /**
     * @var
     */
    private $tributoIRRFComplementarRepository;

    /**
     * unicidadeService constructor.
    */
    public function __construct()
    {
        $this->tributoIRRFComplementarRepository = new TributoIRRFComplementarRepository();
    }

    /**
     * @param TributoIRRFComplementar
     * @return TributoIRRFComplementar
     * @throws BusinessException
     */
    public function salvar(TributoIRRFComplementar $tributoIRRFComplementarRepository)
    {
        $verificarRegra = false;

        if (!empty($tributoIRRFComplementarRepository->getCpfDependente()) ||
            !empty($tributoIRRFComplementarRepository->getDataNascimento()) ||
            !empty($tributoIRRFComplementarRepository->getNome()) ||
            !empty($tributoIRRFComplementarRepository->getIRRFDependenteTributavel()) ||
            !empty($tributoIRRFComplementarRepository->getTipoDependente()) ||
            !empty($tributoIRRFComplementarRepository->getDescricaoDependencia())) {
            $verificarRegra = true;
        }

        if (empty($tributoIRRFComplementarRepository->getCpfDependente()) && $verificarRegra) {
            throw new BusinessException("Número de inscrição no CPF é obrigatório em " .
            "'Informações de dependentes não cadastrados pelo S-2200/S-2205/S-2300'. Favor revisar.");
        }

        if (strlen($tributoIRRFComplementarRepository->getCpfDependente()) != 11 && $verificarRegra) {
            throw new BusinessException("Número de inscrição no CPF é inválido em " .
            "'Informações de dependentes não cadastrados pelo S-2200/S-2205/S-2300'. Favor revisar.");
        }

        if ($tributoIRRFComplementarRepository->getCpfDependente() == 'S' && $verificarRegra) {
            if (empty($tributoIRRFComplementarRepository->getTipoDependente())) {
                throw new BusinessException("Tipo de dependente é obrigatório " .
                "porque foi definido que o dependente possui dedução IRRF em " .
                "'Informações de dependentes não cadastrados pelo S-2200/S-2205/S-2300'. Favor revisar.");
            }
        }
        return $this->tributoIRRFComplementarRepository->save($tributoIRRFComplementarRepository);
    }

    /**
     * @param TributoIRRFComplementar
     * @return TributoIRRFComplementar
     * @throws BusinessException
     */
    public function excluir(TributoIRRFComplementar $tributoIRRFComplementarRepository)
    {
        return $this->tributoIRRFComplementarRepository->delete($tributoIRRFComplementarRepository);
    }
}
