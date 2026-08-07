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

use App\Domain\Integracoes\EFDReinf\Services\ConsultaEventoService;
use ECidade\RecursosHumanos\ESocial\Agendamento\Evento;
use ECidade\RecursosHumanos\ESocial\DadosESocial;
use ECidade\RecursosHumanos\ESocial\Integracao\FormatterFactory;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use ECidade\RecursosHumanos\ESocial\Repository;

class EFDFechamentoPeriodicos extends ProcessamentoAbstract implements ProcessamentoInterface
{

    private $cgm;
    /**
     * ProcessamentoInterface constructor.
     * @param $cgm
     */
    public function __construct($cgm, $ano = null, $mes = null)
    {
        $this->cgm = $cgm;
        $this->ano = $ano;
        $this->mes = $mes;

        if (empty($ano) || empty($mes)) {
            throw new \Exception("Você deve informar a competência");
        }
    }

    /**
     * @return mixed
     */
    public function processar()
    {
        $cgm = $this->cgm;
        $alteracaoDados = false;

        $dadosEsocial = new DadosESocial();
        $dadosEsocial->setIntegracao(Tipo::EFD_REINF);
        $dadosEsocial->setReponsavelPeloPreenchimento($cgm);
        $dadosEsocial->setAno($this->ano);
        $dadosEsocial->setMes($this->mes);
        $dadosPreenchimento = $dadosEsocial->getPorTipo(Tipo::EFD_FECHAMENTO_PERIODICOS);

        $formatter = FormatterFactory::get(Tipo::R2099);
        $dadosFormatados = $formatter->formatar($dadosPreenchimento);
        $dadosFormatados = $this->preProcessar($dadosFormatados);

        foreach ($dadosFormatados as $dados) {
            $eventoFila = new Evento(Tipo::R2099, $cgm, $dados['referencia'], $dados);

            if ($eventoFila->adicionarFila()) {
                $repository = new Repository\ESocialEnvioRepository();
                $repository->scopeEmpregador($cgm);
                $repository->scopeEvento(current(Tipo::getLayout(Tipo::EFD_REABERTURA_EVENTOS)));
                $repository->scopeResponsavelPreenchimento($dados['referencia']);
                $reabertura_eventos = current($repository->get());
                if ($reabertura_eventos) {
                    $repository->atualizarEvento(
                        $reabertura_eventos->getCodigo(),
                        $reabertura_eventos->getSituacaosalva(),
                        true
                    );
                }
                $alteracaoDados = true;
            }
        }

        return $alteracaoDados;
    }

    private function preProcessar($dados)
    {
        foreach ($dados as $key => $dado) {
            $referencia = $dado['referencia'] . '%';
            $hasSentR2055 = ConsultaEventoService::hasProcessadoSucesso(
                'R-2055',
                $this->cgm,
                $referencia
            );

            if ($hasSentR2055) {
                $dados[$key]['infoFech']['evtAquis'] = "S";
            }
        }

        return $dados;
    }
}
