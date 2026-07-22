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

use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\AbonoRepository;
use BusinessException;
use stdClass;
use DBDate;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Abono;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ContratoRepository;

class AbonoService
{
    /**
     * @var
     */
    private $abonoRepository;

    /**
     * @var
     */
    private $contratoRepository;

    /**
     * constructor.
    */
    public function __construct()
    {
        $this->abonoRepository = new AbonoRepository();
        $this->contratoRepository = new ContratoRepository();
    }

    /**
     * @param Abono
     * @return Abono
     * @throws BusinessException
     */
    public function salvar(Abono $abono)
    {

        $contrato = $this->contratoRepository
            ->scopeSequencial($abono->getSequencialProcessoContrato())
            ->get();

        $obrigatorio = false;

        if ($contrato[0]->getIndenizacaoAbono() == 'S') {
            $obrigatorio = true;
        }

        if ($obrigatorio) {
            $quantidadeAbono = $this->abonoRepository
                ->scopeSequencialContrato($abono->getSequencialProcessoContrato())
                ->count();
            if ($quantidadeAbono == 0) {
                if (empty($abono->getAnoAbono())) {
                    throw new BusinessException("Foi definido que <strong>Houve decisão para pagamento" .
                        " da indenização substitutiva de abono salarial'</strong>'. Favor revisar");
                }
            }
        }

        return $this->abonoRepository->save($abono);
    }

        /**
     * @param Abono
     * @return Abono
     * @throws BusinessException
     */
    public function excluir(Abono $Abono)
    {
        return $this->abonoRepository->delete($Abono);
    }
}
