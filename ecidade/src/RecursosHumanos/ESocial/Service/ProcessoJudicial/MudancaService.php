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

use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Mudanca as MudancaProcesso;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\MudancaRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ContratoRepository;
use Exception;
use stdClass;
use DBDate;

class MudancaService
{
    /**
     * @var
     */
    private $mudancaAtual;

    /**
     * @var
     */
    private $mudancaRepository;

    /**
     * MudancaService constructor.
    */
    public function __construct(MudancaProcesso $mudanca)
    {
        $this->mudancaAtual = $mudanca;
        $this->mudancaRepository = new MudancaRepository();
    }

    /**
     * @param MudancaProcesso $mudanca
     * @return MudancaProcesso[]
     * @throws Exception
     */
    public function salvar(MudancaProcesso $mudanca)
    {
        //Informações de Novo Código de Categoria Reconhecido Juduicialmente.
        $contrato = $mudanca->getProcessoContrato();

        $mudancaRepository = [];
        $obrigatorio = false;
        $complemento = " em <strong>'Informações de Novo Código de Categoria Reconhecido Judicialmente.'</strong>";

        if (!empty($mudanca->getCodigoCategoria()) ||
            !empty($mudanca->getDataMudancaCategoria())) {
                $obrigatorio = true;
        }

        if (((int) $mudanca->getCodigoCategoria() > 0 &&
            $mudanca->getCodigoCategoria() < 99) ||
            ((int) $mudanca->getCodigoCategoria() > 0 &&
            $mudanca->getCodigoCategoria() > 999)) {
                throw new Exception("Código de categoria de mudança inválido " .
                "{$complemento}. Favor revisar.");
        }

        if ($contrato[0]->getIndicativoCategoria() == 'S' ||
            $contrato[0]->getNaturezaAtividade() == 'S') {
             $obrigatorio = true;
        }

        if ($obrigatorio && empty($mudanca->getCodigoCategoria())) {
            throw new Exception("Código de categoria de mudança não preenchido " .
                "{$complemento}. Favor revisar.");
        }

        if ($obrigatorio && empty($mudanca->getDataMudancaCategoria())) {
            throw new Exception("Data de mudança de categoria não preenchido " .
                "{$complemento}. Favor revisar.");
        }
        
        $mudancaRepository = $this->mudancaRepository->save($this->mudancaAtual);

        return $mudancaRepository;
    }

    /**
     * @param MudancaProcesso
     * @return MudancaProcesso
     * @throws BusinessException
     */
    public function excluir(MudancaProcesso $mudanca)
    {
        return $this->mudancaRepository->delete($mudanca);
    }
}
