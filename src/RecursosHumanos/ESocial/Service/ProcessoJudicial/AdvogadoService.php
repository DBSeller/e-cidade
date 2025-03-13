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

use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\AdvogadoRepository;
use BusinessException;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Advogado;

class AdvogadoService
{
    /**
     * @var
     */
    private $advogadoRepository;

    /**
     * unicidadeService constructor.
    */
    public function __construct()
    {
        $this->advogadoRepository = new AdvogadoRepository();
    }

    /**
     * @param Advogado
     * @return Advogado
     * @throws BusinessException
     */
    public function salvar(Advogado $advogado)
    {
        $verificarRegra = false;

        if (!empty($advogado->getTipoInscricao()) ||
            !empty($advogado->getNumeroInscricao()) ||
            !empty($advogado->getValorDespesa())) {
                $verificarRegra = true;
        }

        if (empty($advogado->getTipoInscricao()) && $verificarRegra) {
            throw new BusinessException("Tipo de inscri??o ? obrigat?rio em " .
            "'Identifica??o dos advogados'." .
            "Favor revisar.");
        }

        if (empty($advogado->getNumeroInscricao()) && $verificarRegra) {
            throw new BusinessException("O n?mero de inscri??o do advogado ? obrigat?rio em " .
            "'Identifica??o dos advogados'." .
            "Favor revisar.");
        }

        return $this->advogadoRepository->save($advogado);
    }

        /**
     * @param Advogado
     * @return Advogado
     * @throws BusinessException
     */
    public function excluir(Advogado $advogado)
    {
        return $this->advogadoRepository->delete($advogado);
    }
}
