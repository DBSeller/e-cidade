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

use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\DependenteRepository;
use BusinessException;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Dependente;

class DependenteService
{
    /**
     * @var
     */
    private $dependenteRepository;

    /**
     * unicidadeService constructor.
    */
    public function __construct()
    {
        $this->dependenteRepository = new DependenteRepository();
    }

    /**
     * @param Dependente
     * @return Dependente
     * @throws BusinessException
     */
    public function salvar(Dependente $dependente)
    {
        $verificarRegra = false;

        if (!empty($dependente->getTipoRendimento()) ||
            !empty($dependente->getCpfDependente()) ||
            !empty($dependente->getValorDeducao())) {
                $verificarRegra = true;
        }

        if (empty($dependente->getTipoRendimento()) && $verificarRegra) {
            throw new BusinessException("Tipo de rendimento é obrigatório em " .
            "'Dedução do rendimento tributável relativa a dependentes'." .
            "Favor revisar.");
        }

        if (empty($dependente->getCpfDependente()) && $verificarRegra) {
            throw new BusinessException("O número de inscrição do dependente no CPF é obrigatório em " .
            "'Dedução do rendimento tributável relativa a dependentes'." .
            "Favor revisar.");
        }

        if (empty($dependente->getValorDeducao()) && $verificarRegra) {
            throw new BusinessException("O valor da dedução da base de cálculo é obrigatório em " .
            "'Dedução do rendimento tributável relativa a dependentes'." .
            "Favor revisar.");
        }

        return $this->dependenteRepository->save($dependente);
    }

        /**
     * @param Dependente
     * @return Dependente
     * @throws BusinessException
     */
    public function excluir(Dependente $dependente)
    {
        return $this->dependenteRepository->delete($dependente);
    }
}
