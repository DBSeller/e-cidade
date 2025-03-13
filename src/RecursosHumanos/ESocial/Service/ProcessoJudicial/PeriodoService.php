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

use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Periodo as PeriodoProcesso;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\PeriodoRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ContratoRepository;
use Exception;
use stdClass;
use DBDate;
use DateTime;
use ECidade\Tributario\Library\Format;

class PeriodoService
{
    /**
     * @var
     */
    private $periodoAtual;

    /**
     * @var
     */
    private $contratoRepository;

    /**
     * periodoService constructor.
    */
    public function __construct(PeriodoProcesso $periodo)
    {
        $this->periodoAtual = $periodo;
        $this->contratoRepository = new ContratoRepository;
    }

    /**
     * @param PeriodoProcesso $periodo
     * @return PeriodoProcesso
     * @throws Exception
     */
    public function salvar(PeriodoProcesso $periodo)
    {
        //Informações de Novo Código de Categoria Reconhecido Juduicialmente.
        $complemento = ' em "Lançamentos"';
        $contrato = $this->contratoRepository
            ->scopeSequencial($periodo->getSequencialProcessoContrato())
            ->get();
        $periodoRepository = [];

        $competenciaInicial = explode("-", $contrato[0]->getCompetenciaInicial());
        $competenciaFinal = explode("-", $contrato[0]->getCompetenciaFinal());
        $mesInicial = $competenciaInicial[0];
        $anoInicial = $competenciaInicial[1];
        $mesFinal = $competenciaFinal[0];
        $anoFinal = $competenciaFinal[1];
        $mes = [1,2,3,4,5,6,7,8,9,10,11,12];
        $periodoApuracao = explode("-", $periodo->getPeriodo());
        $mesPeriodoApuracao = $periodoApuracao[1];

        if (!(in_array((int) $mesInicial, $mes))) {
            throw new Exception('Valor inválido mês "Competência Inicial do Processo/Conciliação" ' .
            $complemento . '. Favor revisar.');
        }

        if (!(in_array((int) $mesFinal, $mes))) {
            throw new Exception('Valor inválido mês "Competência Final do Processo/Conciliação" ' .
            $complemento . '. Favor revisar.');
        }
        
        if (!(in_array((int) $mesPeriodoApuracao, $mes))) {
            throw new Exception('Valor inválido mês "Período Apuração" ' .
            $complemento . '. Favor revisar.');
        }

        if ($contrato[0]->getIndicativoRepercussao() == 1) {
            if (empty($this->periodoAtual)) {
                throw new Exception("Obrigatório os lançamentos (Para fins previdenciários/FGTS) ".
                "porque há definição em ".
                "<strong>'Decisão com repercussão tributária e/ou FGTS'</strong> defenida em " .
                "'Informações dos períodos e valores decorrentes de processo trabalhista e ainda não declarados " .
                "no eSocial'. Favor revisar.");
            }
        }

        $periodoRepository = new PeriodoRepository();
        $periodoRepository = $periodoRepository->save($periodo);

        return $periodoRepository;
    }
}
