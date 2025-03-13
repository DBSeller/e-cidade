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

use ECidade\RecursosHumanos\ESocial\Agendamento\Evento;
use ECidade\RecursosHumanos\ESocial\Integracao\FormatterFactory;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use ECidade\RecursosHumanos\ESocial\Integracao\Recurso;
use ECidade\RecursosHumanos\ESocial\Integracao\ESocial;
use ECidade\V3\Extension\Registry;
use DBPessoal;
use stdClass;
use CgmRepository;
use ServidorRepository;
use ParameterException;
use ParametrosPessoalRepository;

/**
 * Class PagamentosRendimentosTrabalho
 * @package ECidade\RecursosHumanos\ESocial\Agendamento\Processamento
 */
class PagamentosRendimentosTrabalho extends ProcessamentoAbstract implements ProcessamentoInterface
{
    private $cgm;

    private $mes;
    private $ano;

    private $competenciaAnterior;


    /**
     * @param integer $mes
     * Seta o mes da competencia informada
     */
    public function setMes($mes)
    {
        $this->mes = $mes;
    }

    /**
     * @param integer $ano
     */
    public function setAno($ano)
    {
        $this->ano = $ano;
    }

    public function __construct($cgm)
    {
        $this->competenciaAnterior = DBPessoal::getCompetenciaFolha()->getCompetenciaAnterior();
        $this->mes = $this->competenciaAnterior->getMes();
        $this->ano = $this->competenciaAnterior->getAno();
        $this->cgm = $cgm;
    }

    /**
     * @return bool|mixed
     * @throws \Exception
     */
    public function processar()
    {
        ini_set("memory_limit", "1024M");
        $alteracao = false;
        $competencia =  $this->ano . $this->mes;
        $quantidadeCompetencia = -6;
        $servidores = [];

        if (empty($this->ano) || empty($this->mes)) {
            throw new ParameterException("Ano ou mês não informados.");
        }
        if ($this->isForcarMatricula() && sizeof($this->servidores) == 0) {
            throw new ParameterException("Nenhuma matrícula informada. Por Favor selecione as matrículas.");
        }

        $dados = new stdClass();

        $body = new stdClass();
        $body->inscricaoEmpregador = CgmRepository::buscarCNPJEmpregador($this->cgm);
        $body->competencia = $competencia;

        $cgmFiltro = [];
        if (!empty($this->servidores)) {
            foreach ($this->servidores as $servidor) {
                $cgmFiltro[] = $servidor->getCgm()->getCodigo();
            }
            if (sizeof($this->servidores) == 1) {
                $body->competencia = $cgmFiltro[0] . '%' . $body->competencia;
                if ($this->servidores[0]->isRescindido()) {
                    $dadosRescisao = $this->servidores[0]->getDadosRescisao();
                    $dataRescisao = new \DBDate($dadosRescisao->rh05_recis);

                    if ($this->anoCompetencia == $dataRescisao->getAno() &&
                        $this->mesCompetencia == $dataRescisao->getMes()) {
                        $body->competencia = $dadosRescisao->rh05_codigorescisao;
                    }
                }
            }
        }
        $service = new ESocial(
            Registry::get('app.config'),
            Recurso::CONSULTA_REFERENCIA_PARA_PAGAMENTOS_RENDIMENTOS_TRABALHO
        );
        $service->setDados($body);
        $dados = $service->request('GET');

        if (empty($dados)) {
            $dados = (object) $dados;
        }

        if ($this->isForcarMatricula()) {
            $servidores = $this->servidores;
        }

        $this->servidores = [];
        $dados->cgms = [];
        if (isset($dados->eventos)) {
            foreach ($dados->eventos as $dado) {
                $dadoCgm = new \stdClass();
                $dadoCgm->evento = $dado->tipo_evento_id;
                switch ($dado->tipo_evento_id) {
                    case TIPO::S2299_API:
                    case TIPO::S2399_API:
                        $dado->referencia = substr($dado->referencia, 0, $quantidadeCompetencia);
                        if (!\ServidorRepository::isMatriculaValida($dado->referencia)) {
                            continue;
                        }
                        $servidor = ServidorRepository::getInstanciaByCodigo(
                            $dado->referencia,
                            $this->ano,
                            $this->mes
                        );
                        $dadoCgm->cgm = $servidor->getCgm();
                        if ($servidor->isRpps()) {
                            $dadoCgm->evento = TIPO::S1202_API;
                        } else {
                            $dadoCgm->evento = TIPO::S1200_API;
                        }
                        unset($servidor);
                        break;
                    case TIPO::S1207_API:
                        $referencia = explode('_', $dado->referencia);
                        $dado->referencia = $referencia[0];
                        $dadoCgm->cgm = CgmRepository::getByCodigo($dado->referencia);
                        break;
                    case TIPO::S1202_API:
                        $referencia = explode('-', $dado->referencia);
                        $dado->referencia = $referencia[0];
                        $dadoCgm->cgm = CgmRepository::getByCodigo($dado->referencia);
                        break;
                    default:
                        $dado->referencia = substr($dado->referencia, 0, -7);
                        $dadoCgm->cgm = CgmRepository::getByCodigo($dado->referencia);
                        break;
                }
                if (!empty($cgmFiltro)) {
                    if (in_array($dadoCgm->cgm->getCodigo(), $cgmFiltro)) {
                        $dados->cgms[] = $dadoCgm;
                    }
                } else {
                    if (isset($dadoCgm->cgm)) {
                        $dados->cgms[] = $dadoCgm;
                    }
                }
            }
        }
        $dados->eventos = [];
        $dados->inscricao_empregador = $body->inscricaoEmpregador;
        $dados->anoCompetencia = $this->ano;
        $dados->mesCompetencia = $this->mes;
        $competencia = new \DBCompetencia($this->ano, $this->mes);
        $parametros = ParametrosPessoalRepository::getParametros($competencia);
        $formatter = FormatterFactory::get(Tipo::S1210);

        if ($this->isForcarMatricula()) {
            $formatter->setServidores($servidores);
        }
        // Verifica se a competencia é a de pagamento do decimo terceiro
        if (!empty($parametros->getMes13()) && $parametros->getMes13() == $this->mes) {
            $formatter->setDecimoTerceiro();
        }

        $dadosPreenchimentoEmpregador = $formatter->formatar((array) $dados);
        $validaMd5 = true;
        if ($this->envioForcado) {
            $validaMd5 = false;
        }
        foreach ($dadosPreenchimentoEmpregador as $indice => $dados) {
            $evento = new Evento(TIPO::S1210, $this->cgm, $dados->referencia, $dados);
            $evento->iContador = $indice;

            if ($evento->adicionarFila(false, $validaMd5)) {
                $alteracao = true;
            }
        }

        return $alteracao;
    }
}
