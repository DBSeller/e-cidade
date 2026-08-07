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

namespace ECidade\RecursosHumanos\ESocial\Agendamento\Processamento;

use ECidade\RecursosHumanos\ESocial\Agendamento\Evento;
use ECidade\RecursosHumanos\ESocial\Model\ContribuicaoSindical\Periodo;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use ECidade\RecursosHumanos\ESocial\Repository\ContribuicaoSindical\ContribuicaoRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ContribuicaoSindical\PeriodoRepository;

class ContribuicaoSindical extends ProcessamentoAbstract implements ProcessamentoInterface
{
    private $cgm;

    /**
     * ProcessamentoInterface constructor.
     * @param $cgm
     */
    public function __construct($cgm)
    {
        $this->cgm = $cgm;
    }

    /**
     * @return boolean
     * @throws \Exception
     */
    public function processar()
    {
        $dadosPreenchimento = $this->processarDados();
        $dadosFormatados = $this->formatar($dadosPreenchimento);

        $alteracao = false;
        foreach ($dadosFormatados as $dados) {
            $eventoFila = new Evento(Tipo::S1300, $this->cgm, $dados->referencia, $dados);

            if ($eventoFila->adicionarFila()) {
                $alteracao = true;
            }
        }

        return $alteracao;
    }

    private function processarDados()
    {
        $periodoRepository = new PeriodoRepository();
        $periodos = $periodoRepository->scopeEmpregador($this->cgm)->get();

        $periodosProcessar = array();
        foreach ($periodos as $periodo) {
            $contribuicaoRepository = new ContribuicaoRepository();
            $contribuicoes = $contribuicaoRepository->scopePeriodo($periodo->getSequencial())->get();

            if (count($contribuicoes) > 0) {
                $periodo->setContribuicoesSindicais($contribuicoes);
                $periodosProcessar[] = $periodo;
            }
        }
        return $periodosProcessar;
    }

    /**
     * @param Periodo[] $periodosProcessar
     * @return array
     */
    private function formatar(array $periodosProcessar)
    {
        $dadosFormatados = array();

        foreach ($periodosProcessar as $periodo) {

            $periodoFormatado = (object)array(
                'inscricao_empregador' => $periodo->getEmpregador()->getCnpj(),
                'referencia' => (int)$periodo->getSequencial(),
                'ideEmpregador' => (object)array(
                    'indApuracao' => (int)$periodo->getIndicativoPeriodo(),
                    'perApur' => $periodo->getPeriodo(),
                    'tpInsc' => (int)1,
                    'nrInsc' => str_replace(array('.', '/', '-'), '', $periodo->getEmpregador()->getCnpj()),
                ),
                'contribSind' => array()
            );

            foreach ($periodo->getContribuicoesSindicais() as $contribuicoesSindical) {
                $contribSind = (object)array(
                    'cnpjSindic' => str_replace(array('.', '/', '-'), '',
                        $contribuicoesSindical->getSindicato()->getCnpj()),
                    'tpContribSind' => (int)$contribuicoesSindical->getTipoContribuicao(),
                    'vlrContribSind' => (float)$contribuicoesSindical->getValor()
                );

                $periodoFormatado->contribSind[] = $contribSind;
            }

            $dadosFormatados[] = $periodoFormatado;
        }

        return $dadosFormatados;
    }

}
