<?php
/**
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
 *  junto com este programa; se nao, escreva para a Free Softwareb
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\RecursosHumanos\ESocial\Agendamento\Processamento;

use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ProcessoJudicialRepository;

use DBDate;

use db_utils;
use DBException;
use ECidade\RecursosHumanos\ESocial\Agendamento\Evento;
use ECidade\RecursosHumanos\ESocial\DadosESocial;
use ECidade\RecursosHumanos\ESocial\Integracao\FormatterFactory;
use ECidade\RecursosHumanos\ESocial\Model\Configuracao;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use stdClass;
use CgmRepository;

/**
 * Class Processos
 * @package ECidade\RecursosHumanos\ESocial\Agendamento\Processamento
 */
class ProcessoTrabalhista extends ProcessamentoAbstract implements ProcessamentoInterface
{
    /**
     * @var
     */
    private $cgm;

    /**
     * Processos constructor.
     * @param $cgm
     */
    public function __construct($cgm)
    {
        $this->cgm = $cgm;
    }

    /**
     * @return bool|mixed
     * @throws DBException
     * @throws \Exception
     */
    public function processar()
    {
        $processos = [];
        $processosTrabalhistas = [];
        $bAlteracao = false;
        $processosRepository = new ProcessoJudicialRepository();

        if (!empty($this->mesCompetencia) && !empty($this->anoCompetencia)) {
            $processos = $processosRepository->getCompetenciaProcessos($this->mesCompetencia, $this->anoCompetencia);
        }

        if (!empty($this->selecao)) {
            $processos = $processosRepository->getSelecaoProcessos($this->selecao);
        }

        if (!empty($this->servidores)) {
            $processos = $processosRepository->getMatriculaProcessos($this->servidores);
        }

        $formatterProcessoTrabalhista = FormatterFactory::get(Tipo::S2500);

        $formatterProcessoTrabalhista->setEmpregador(CgmRepository::getByCodigo($this->cgm));

        $processosTrabalhistas = $formatterProcessoTrabalhista->formatar($processos);

        $validaMd5 = true;
        if ($this->envioForcado) {
            $validaMd5 = false;
        }
        foreach ($processosTrabalhistas as $iIndice => $oDados) {
            $oEvento = new Evento(Tipo::S2500, $this->cgm, $oDados->referencia, $oDados);
            $oEvento->iContador = $iIndice;

            if ($oEvento->adicionarFila(false, $validaMd5)) {
                $bAlteracao = true;
            }
        }

        return $bAlteracao;
    }
}
