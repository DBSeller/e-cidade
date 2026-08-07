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

namespace ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Service;

use ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Factories\FluxoCaixaFactory;
use ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Layout\FluxoCaixa2020Layout;
use ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Model\FluxoCaixa;
use Exception;

/**
 * Class FluxoCaixaService
 * @package ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Service
 */
class FluxoCaixaService
{
    const QUADRO_PRINCIPAL = 1;
    const QUADRO_RECEITAS = 2;
    const QUADRO_TRANSFERENCIAS = 3;
    const QUADRO_DESEMBOLSOS = 4;
    const QUADRO_DIVIDA = 5;
    /**
     * @var FluxoCaixa
     */
    private $relatorio;
    /**
     * @var FluxoCaixa2020Layout
     */
    private $layout;

    /**
     * FluxoCaixaService constructor.
     * @param string $ano
     * @param integer $periodo
     * @param string $listaInstituicoes
     * @param bool $exercicioAnterior
     * @param array $quadros
     * @throws Exception
     */
    public function __construct(
        $modelo,
        $ano,
        $periodo,
        $listaInstituicoes,
        $exercicioAnterior = false,
        array $quadros = []
    ) {
        $this->relatorio = FluxoCaixaFactory::getModel($modelo, $ano, $periodo);
        $this->relatorio->setInstituicoes($listaInstituicoes);
        $this->relatorio->setExibirExercicioAnterior($exercicioAnterior);

        $this->layout = FluxoCaixaFactory::getLayout($modelo, $ano, $this->relatorio);
        $this->layout->setExibirQuadros($quadros);
    }

    public function imprimir()
    {
        $this->layout->imprimir();
    }
}
