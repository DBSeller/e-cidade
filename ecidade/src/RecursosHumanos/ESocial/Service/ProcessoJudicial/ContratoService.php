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

use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Contrato as ContratoProcessual;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ContratoRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ServidorRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ProcessoJudicialRepository;
use Exception;
use stdClass;
use DBDate;

class ContratoService
{
    /**
     * @var
     */
    private $contrato;

    /**
     * @var
     */
    private $matriculaServidor;

    /**
     * @var
     */
    private $nomeServidor;

    /**
     * @var
     */
    private $matriculaNome;

    /**
     * @var
     */
    private $dataSentenca ;

    /**
     * @var
     */
    private $dataAcordo ;

    /**
     * @var
     */
    private $competenciaDataAdmissao;

    /**
     * @var
     */
    private $competenciaDataSentenca;

    /**
     * @var
     */
    private $competenciaDataAcordo;

    /**
     * @var
     */
    private $servidorAtual;

    /**
     * ContratoService constructor.
     */
    public function __construct(ContratoProcessual $contrato)
    {
        $this->contrato = $contrato;
        $this->matriculaServidor = $contrato->getServidorProcesso()[0]->getMatricula();
        $this->nomeServidor = $contrato->getServidorProcesso()[0]->getNomeServidor();
        $this->matriculaNome = $this->matriculaServidor . ' - ' . $this->nomeServidor;
        $this->dataAcordo = $this->contrato->getDataAcordo();
        $this->dataSentenca = $this->contrato->getDataSentenca();
        $this->competenciaDataAcordo = explode("-", $this->dataAcordo);
        $this->competenciaDataSentenca = explode("-", $this->dataSentenca);
        $this->competenciaDataAdmissao =
            explode("-", $this->contrato->getServidorProcesso()[0]->getDataAdmissao());

        $this->servidorAtual = \ServidorRepository::getInstanciaByCodigo($this->matriculaServidor);
    }

    /**
     * @param ContratoProcessual $contrato
     * @return Processos
     * @throws Exception
     */
    public function salvar(ContratoProcessual $contrato)
    {

        $mes = [1,2,3,4,5,6,7,8,9,10,11,12];
        $referencia = " Refererente ao servidor <strong>{$this->matriculaNome}</strong>";
        $dataAdmissaoServidor = $contrato->getServidorProcesso()[0]->getDataAdmissao();
        $dataRescisaoServidor = $contrato->getServidorProcesso()[0]->getDataDemissao();

        if (!empty($dataRescisaoServidor)) {
            $anoRescisao = date('Y', strtotime($dataRescisaoServidor));
            $mesRescisao = date('m', strtotime($dataRescisaoServidor));
            $diaRescisao = date('d', strtotime($dataRescisaoServidor));
            $dataRescisaoBrasil =  date('d/m/Y', strtotime($dataRescisaoServidor));
        }

        if (!empty($dataAdmissaoServidor)) {
            $anoAdmissao = date('Y', strtotime($dataAdmissaoServidor));
            $mesAdmissao = date('m', strtotime($dataAdmissaoServidor));
            $diaAdmissao = date('d', strtotime($dataAdmissaoServidor));
            $dataAdmissaoBrasil = date('d/m/Y', strtotime($dataAdmissaoServidor));
        }
        $competenciaInicial = $contrato->getCompetenciaInicial();
        $mesCompetenciaInicial = (explode('-', $competenciaInicial)[0]);
        $anoCompetenciaInicial = (explode('-', $competenciaInicial)[1]);
        $competencialFinal = $contrato->getCompetenciaFinal();
        $mesCompetenciaFinal = (explode('-', $competencialFinal)[0]);
        $anoCompetenciaFinal = (explode('-', $competencialFinal)[1]);

        $dataAcordo = $contrato->getProcessoJudicial()[0]->getDataCelebracaoAcordo();
        $dataSentenca = $contrato->getProcessoJudicial()[0]->getDataSentenca();
        $dataSentencaAcordo = $dataAcordo ? $dataAcordo : $dataSentenca;
        $anoSentencaAcordo = date('Y', strtotime($dataSentencaAcordo));
        $mesSentencaAcordo = date('m', strtotime($dataSentencaAcordo));
        $diaSentencaAcordo = date('d', strtotime($dataSentencaAcordo));
        $dataSetencaBrasil = date('d/m/Y', strtotime($dataSentencaAcordo));

        if (empty($contrato->getCodigoCategoria())) {
            throw new Exception('O <strong>Código de Categoria</strong> para o servidor <strong>' .
            $this->matriculaNome . '</strong> não definido. Favor revisar.');
        }
        if (empty($dataRescisaoServidor)) {
            throw new Exception('A <strong>data de rescisão</strong> para o servidor <strong>' .
            $this->matriculaNome . '</strong> não definida. Favor revisar.');
        }
        if (empty($dataAdmissaoServidor)) {
            throw new Exception('A <strong>data de admissão</strong> para o servidor <strong>' .
            $this->matriculaNome . '</strong> não definida. Favor revisar.');
        }
        if (strtotime($dataRescisaoServidor) < strtotime($dataAdmissaoServidor)) {
            throw new Exception('A <strong>data de rescisão</strong> terá que ser maior que a data de admissão ' .
            'para o servidor <strong>' . $this->matriculaNome .
            '</strong>. Favor revisar.');
        }

        if (empty($contrato->getCodigoCBO())) {
            if (!in_array($contrato->getCodigoCategoria(), ["901","903","904"])) {
                throw new Exception('Código CBO (Código Brasileiro de Ocupações) do servidor <strong>' .
                $this->matriculaNome . '</strong> não definido. Favor revisar.');
            }
        } else {
            if (strlen($contrato->getCodigoCBO()) != 6) {
                throw new Exception('Código CBO (Código Brasileiro de Ocupações) do servidor <strong>' .
                $this->matriculaNome . '</strong> esta incorreto. Favor revisar.');
            }
        }

        //Informações Adicionais ref. ao Contrato de trabalho
        $complemento = ' em <i>"Informações Adicionais ref. ao Contrato de trabalho"</i>.';
        if (empty($contrato->getTipoContrato())) {
            throw new Exception('É necessário informar o "Tipo de contrato a que se refere o processo judicial" ' .
                $complemento . $referencia . ' Favor revisar.');
        }

        if (empty($contrato->getIndicativoContrato())) {
            throw new Exception('É necessário informar o "Indicativo se o contrato possui informação no evento ' .
                'S-2190, S-2200 ou S-2300 no declarante." ' . $complemento . $referencia . ' Favor revisar.');
        }

        if (empty($contrato->getIndicativoCategoria())) {
            throw new Exception('É necessário informar o "Indicativo se houve reconhecimento de categoria do ' .
                'trabalhador diferente da informada (no eSocial ou na GFIP) pelo declarante." ' . $complemento .
                $referencia . ' Favor revisar.');
        }
        if (empty($contrato->getIndicativoNaturezaAtividade())) {
            throw new Exception('É necessário informar o "Indicativo se houve reconhecimento de natureza da atividade' .
                ' diferente da cadastrada pelo declarante." ' . $complemento . $referencia . ' Favor revisar.');
        }

        if (empty($contrato->getIndicativoMotivoDesligamento())) {
            throw new Exception('É necessário informar o "Indicativo se houve reconhecimento de motivo de ' .
                ' desligamento diferente do informado pelo declarante." ' . $complemento .  $referencia .
                ' Favor revisar.');
        }

        //Informações dos períodos e valores decorrentes de processo trabalhista e ainda não declarados
        //no eSocial
        $complemento = ' em <i>"Informações dos períodos e valores decorrentes de processo trabalhista e ainda não ' .
        'declarados no eSocial"</i>';

        if (empty($anoCompetenciaInicial)) {
            throw new Exception('É necessário informar o <strong>Ano </strong>da <strong>"Competência Inicial do ' .
                'Processo/Conciliação"</strong> ' . $complemento .  $referencia . ' Favor revisar.');
        }

        if (empty($mesCompetenciaInicial)) {
            throw new Exception('É necessário informar o <strong>Mês </strong>da <strong>"Competência Inicial do ' .
                'Processo/Conciliação"</strong> ' . $complemento .  $referencia . ' Favor revisar.');
        }

        if (!(in_array((int) $mesCompetenciaInicial, $mes))) {
            throw new Exception('É necessário informar o <strong>Mês válido </strong> da <strong>"' .
            ' Competência Inicial do Processo/Conciliação"</strong> ' .
            $complemento .  $referencia . '. Favor revisar.');
        }

        if (empty($anoCompetenciaFinal)) {
            throw new Exception('É necessário informar o <strong>Ano </strong>da <strong>"Competência Final do ' .
                'Processo/Conciliação"</strong> ' . $complemento .  $referencia . ' Favor revisar.');
        }

        if (empty($mesCompetenciaFinal)) {
            throw new Exception('É necessário informar o <strong>Mês </strong>da <strong>"Competência Final do ' .
                'Processo/Conciliação"</strong> ' . $complemento .  $referencia . ' Favor revisar.');
        }

        if (!(in_array((int) $mesCompetenciaFinal, $mes))) {
            throw new Exception('É necessário informar o <strong>Mês válido </strong> da <strong>"Competência Final ' .
                ' do Processo/Conciliação"</strong> ' . $complemento .  $referencia . ' Favor revisar.');
        }
        
        if (in_array($contrato->getTipoContrato(), ['1','3','6','7','8','9'])) {
            if ((int) $anoCompetenciaInicial < (int) $anoAdmissao) {
                throw new Exception("O <strong>ano de competência inicial({$anoCompetenciaInicial})</strong> " .
                    " é menor que o ano da admissão do servidor({$anoAdmissao}) {$complemento} {$referencia}" .
                    " Data admissão {$dataAdmissaoBrasil}. Favor revisar.");
            }
            if ((int) $anoCompetenciaInicial == (int) $anoAdmissao) {
                if ((int) $mesCompetenciaInicial < (int) $mesAdmissao) {
                    throw new Exception("O <strong>mês de competência inicial({$mesCompetenciaInicial})</strong> " .
                        " é menor que o ano da admissão do servidor({$mesAdmissao}) {$complemento} {$referencia}" .
                        " Data admissão {$dataAdmissaoBrasil}. Favor revisar.");
                }
            }
        }

        if (in_array($contrato->getTipoContrato(), ['2','4','5'])) {
            if ((int) $anoCompetenciaInicial != (int) $anoAdmissao) {
                throw new Exception("O ano de competência inicial({$anoCompetenciaInicial}) deverá ser igual " .
                    " ao ano da admissão do servidor({$anoAdmissao}) {$complemento} {$referencia}." .
                    " Data admissão {$dataAdmissaoBrasil}. Favor revisar.");
            }
            if ((int) $anoCompetenciaInicial == (int) $anoAdmissao) {
                if ((int) $mesCompetenciaInicial != (int) $mesAdmissao) {
                    throw new Exception("O <strong>mês de competência inicial({$mesCompetenciaInicial})</strong> " .
                        " deverá ser igual ao ano da admissão do servidor({$mesAdmissao}) " .
                        "{$complemento} {$referencia}.Data admissão {$dataAdmissaoBrasil}. Favor revisar.");
                }
            }
        }

        if ((int) $anoSentencaAcordo < (int) $anoCompetenciaInicial) {
            throw new Exception("O <strong>ano de competência inicial({$anoCompetenciaInicial})</strong> " .
                " deverá ser maior que ano da Sentença/Acordo do servidor({$anoSentencaAcordo}) {$complemento} " .
                "{$referencia}." .
                " Data Sentença/Acordo <strong>{$dataSetencaBrasil}</strong>. Favor revisar.");
        }

        if ((int) $anoCompetenciaFinal > (int) $anoSentencaAcordo) {
            throw new Exception("O <strong>ano de competência final({$anoCompetenciaFinal})</strong> " .
                " deverá ser menor ou igual que ano da Sentença/Acordo do servidor({$anoSentencaAcordo}) " .
                "{$complemento} {$referencia}." .
                " Data Sentença/Acordo <strong>{$dataSetencaBrasil}</strong>. Favor revisar.");
        }

        if ((int) $anoSentencaAcordo == (int) $anoCompetenciaFinal) {
            if ((int) $mesCompetenciaFinal > (int) $mesSentencaAcordo) {
                throw new Exception("O <strong>mês de competência final({$mesCompetenciaFinal})</strong> " .
                    " deverá ser menor ou igual que mês da Sentença/Acordo do servidor({$mesSentencaAcordo}) " .
                    "{$complemento} {$referencia}." .
                    " Data Sentença/Acordo <strong>{$dataSetencaBrasil}</strong>. Favor revisar.");
            }
        }

        if ((int) $anoCompetenciaInicial > (int) $anoCompetenciaFinal) {
            throw new Exception("O <strong>ano de competência final({$anoCompetenciaInicial})</strong> " .
                " deverá ser menor ou igual que ano da final da competência ({$anoCompetenciaFinal}) " .
                "{$complemento} {$referencia}." .
                " Favor revisar.");
        }

        if ((int) $anoCompetenciaInicial == (int) $anoCompetenciaFinal) {
            if ((int) $mesCompetenciaFinal < (int) $mesCompetenciaInicial) {
                throw new Exception("O <strong>mês de competência inicial({$mesCompetenciaInicial})</strong> " .
                    " deverá ser menor ou igual que ano da final da competência ({$mesCompetenciaFinal}) " .
                    "{$complemento} {$referencia}." .
                    " Favor revisar.");
            }
        }

        $contratoRepository = new ContratoRepository();
        $contratoRepository = $contratoRepository->save($contrato);

        return $contratoRepository;
    }
}
