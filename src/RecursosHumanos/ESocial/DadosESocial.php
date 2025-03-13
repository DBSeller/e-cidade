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
namespace ECidade\RecursosHumanos\ESocial;

use ECidade\Integracao\Sped\Common\Configuracao\ConfiguracaoFactory;
use ECidade\RecursosHumanos\ESocial\Formatter\DadosPreenchimento as DadosPreenchimentoFormatter;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Preenchimentos;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use ECidade\RecursosHumanos\Pessoal\Servidor\Repository\Rescisao as RescisaoRepository;
use AssentamentoRepository;
use BusinessException;
use DBCompetencia;
use DBException;
use DBPessoal;
use Exception;
use ParameterException;
use Servidor;
use ServidorRepository;
use cl_avaliacaogruporespostaafastamentoesocial;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\TributoBaseRepository;
use DBDate;
use ECidade\RecursosHumanos\ESocial\Repository\CadastroBeneficio;
use ECidade\RecursosHumanos\ESocial\Repository\CadastroBeneficiario;

/**
 * Constrói uma coleção com os dados para o envio do eSocial
 *
 * @package ECidade\RecursosHumanos\ESocial
 */
class DadosESocial
{
    private $tipo;

    private $dados;

    private $indicativoPeriodoApuracao;

    private $listaRubricas = [];

    /**
     * Responsável pelo preenchimento do formulário
     *
     * @var mixed
     */
    private $responsavelPreenchimento;

    /**
     * @var \Instituicao $instituicao
     */
    private $instituicao;

    /**
     * CGM do Empregador
     * @var integer
     */
    private $cgmEmpregador;

    private $integracao = Tipo::ESOCIAL;

    /**
     * Ano para Envio
     */
    private $ano;

    /**
     * @var Date
     */
    private $dataInicial;

    public function setIndicativoPeriodoApuracao($indicativoPeriodoApuracao)
    {
        $this->indicativoPeriodoApuracao = $indicativoPeriodoApuracao;
    }

    /**
     * @return Date
     */
    public function getDataInicial()
    {
        return $this->dataInicial;
    }

    /**
     * @param Date $dataInicial
     */
    public function setDataInicial($dataInicial)
    {
        $this->dataInicial = $dataInicial;
    }

    /**
     * @var Date
     */
    protected $dataFinal;

    /**
     * @return Date
     */
    public function getDataFinal()
    {
        return $this->dataFinal;
    }

    /**
     * @param Date $dataFinal
     */
    public function setDataFinal($dataFinal)
    {
        $this->dataFinal = $dataFinal;
    }

    /**
     * @var Date
     */
    protected $dataPreenchidaInicio;

    /**
     * @return Date
     */
    public function getDataPreenchidaInicial()
    {
        return $this->dataPreenchidaInicio;
    }

    /**
     * @param Date $dataPreenchidaInicio
     */
    public function setDataPreenchidaInicial($dataPreenchidaInicio)
    {
        $this->dataPreenchidaInicio = $dataPreenchidaInicio;
    }

    /**
     * @return Date
     */
    public function getDataPreenchidaFinal()
    {
        return $this->dataPreenchidaFim;
    }

    /**
     * @param Date $dataPreenchidaFim
     */
    public function setDataPreenchidaFinal($dataPreenchidaFim)
    {
        $this->dataPreenchidaFim = $dataPreenchidaFim;
    }

    /**
     * @var Date
     */
    protected $dataPreenchidaFim;


    /**
     * Mês para Envio
     */
    private $mes;

    /**
     * @var Servidor[]
     */
    private $servidores = array();

    private $recibo = '';

    /**
     * Informa o cgm do empregador
     */
    public function setCgmEmpregador($cgmEmpregador)
    {
        $this->cgmEmpregador = $cgmEmpregador;
    }

    /**
     * @param int $integracao
     * @return DadosESocial
     */
    public function setIntegracao($integracao)
    {
        $this->integracao = $integracao;
        return $this;
    }

    /**
     * Informa o responsável pelo preenchimento. Se não indormado, busca de todos
     *
     * @param mixed $responsavel
     */
    public function setReponsavelPeloPreenchimento($responsavel)
    {
        $this->responsavelPreenchimento = $responsavel;
    }

    /**
     * @param int $ano
     * @return DadosESocial
     */
    public function setAno($ano)
    {
        $this->ano = $ano;
        return $this;
    }

    /**
     * @param int $mes
     * @return DadosESocial
     */
    public function setMes($mes)
    {
        $this->mes = $mes;
        return $this;
    }

    /**
     * Retorna todos os preenchimentos e suas respostas para o tipo informado
     *
     * @param integer $tipo
     * @return ECidade\RecursosHumanos\ESocial\Model\Formulario\DadosPreenchimento[]
     * @throws Exception
     */
    public function getPorTipo($tipo)
    {
        $this->tipo = $tipo;
        $preenchimentos = $this->buscaPreenchimentos();
        $this->buscaRespostas($preenchimentos);
        return $this->dados;
    }

    /**
     * Busca os preenchimentos conforme o tipo de formulário informado
     *
     * @throws \Exception
     * @return \stdClass[]
     */
    private function buscaPreenchimentos()
    {
        $configuracao = ConfiguracaoFactory::getInstance($this->integracao);
        $codigoFormulario = $configuracao->getFormulario($this->tipo);

        $preenchimento = new Preenchimentos();
        $preenchimento->setReponsavelPeloPreenchimento($this->responsavelPreenchimento);
        $preenchimento->setCodigoFormulario($codigoFormulario);
        $preenchimento->setServidores($this->servidores);
        switch ($this->tipo) {
            case Tipo::SERVIDOR:
                return $preenchimento->buscarUltimoPreenchimentoServidor(
                    $codigoFormulario,
                    $this->cgmEmpregador
                );
            case Tipo::EMPREGADOR:
                return $preenchimento->buscarUltimoPreenchimentoEmpregador($codigoFormulario);
            case Tipo::OBRAS:
                return $preenchimento->buscarUltimoPreenchimentoObras($codigoFormulario);
            case Tipo::RUBRICA:
                return $preenchimento->buscarUltimoPreenchimentoPorInstituicao(
                    $codigoFormulario,
                    $this->getInstituicao()->getCodigo(),
                    $this->cgmEmpregador,
                    $this->listaRubricas
                );
            case Tipo::CARGO:
                return $preenchimento->buscarUltimoPreenchimentoCargo(
                    $codigoFormulario,
                    $this->getInstituicao()->getCodigo(),
                    $this->cgmEmpregador
                );
            case Tipo::ADMISSAO_PRELIMINAR:
                if ($this->getDataPreenchidaInicial() && $this->getDataPreenchidaFinal()) {
                    $preenchimento->setDataInicio($this->getDataPreenchidaInicial());
                    $preenchimento->setDataFim($this->getDataPreenchidaFinal());
                }
                return $preenchimento->buscarUltimoPreenchimentoAdmissaoPreliminar($codigoFormulario);
            case Tipo::HORARIO:
                return $preenchimento->buscarUltimoPreenchimentoPorInstituicao(
                    $codigoFormulario,
                    $this->getInstituicao()->getCodigo(),
                    $this->cgmEmpregador
                );
            case Tipo::FUNCAO:
                return $preenchimento->buscarUltimoPreenchimentoPorInstituicao(
                    $codigoFormulario,
                    $this->getInstituicao()->getCodigo(),
                    $this->cgmEmpregador
                );
            case Tipo::LOTACAO_TRIBUTARIA:
                return $preenchimento->buscarUltimoPreenchimentoLotacao($codigoFormulario);
            case Tipo::PROCESSOS:
                return $preenchimento->buscarUltimoPreenchimentoProcesso($codigoFormulario);
            case Tipo::TRABALHO_INTERMITENTE:
                return $preenchimento->buscarUltimoPreenchimentoTrabalhoIntermitente($this->cgmEmpregador);
            case Tipo::AVISO_PREVIO:
                $preenchimento->setInstituicao($this->getInstituicao());
                return $preenchimento->buscarUltimoAvisoPrevio($this->responsavelPreenchimento);
                break;
            case Tipo::AFASTAMENTO_TEMPORARIO:
                $preenchimento->setInstituicao($this->getInstituicao());
                return $preenchimento->buscarUltimoPreenchimentoAfastamentoTemporario(
                    $codigoFormulario,
                    $this->getInstituicao()->getCodigo(),
                    $this->ano,
                    $this->mes,
                    $this->cgmEmpregador
                );
                break;
            case Tipo::DESLIGAMENTO_SERVIDOR:
                $preenchimento->setInstituicao($this->getInstituicao());
                return $preenchimento->buscarUltimoPreenchimentoDesligamentoServidor($this->cgmEmpregador);
                break;
            case Tipo::EXCLUSAO_EVENTOS:
                if (empty($this->dataPreenchidaInicio) && empty($this->dataPreenchidaFim)) {
                    throw new BusinessException("Período não informado.");
                }
                if (empty($this->dataPreenchidaInicio)) {
                    throw new BusinessException("Data inicial do período não informada.");
                }
                if (empty($this->dataPreenchidaFim)) {
                    throw new BusinessException("Data final do período não informada.");
                }
                return $preenchimento->buscarUltimoPreenchimentoExclusaoEventos(
                    $this->cgmEmpregador,
                    $this->recibo,
                    $this->dataPreenchidaInicio,
                    $this->dataPreenchidaFim
                );
                break;
            case Tipo::TRABALHADOR_SEM_VINCULO:
                return $preenchimento->buscarUltimoPreenchimentoTSVEInicial(
                    $codigoFormulario,
                    $this->cgmEmpregador
                );
            case Tipo::TERMINO_TRABALHADOR_SEM_VINCULO:
                return $preenchimento->buscarUltimoPreenchimentoTSVETermino(
                    $codigoFormulario,
                    $this->cgmEmpregador
                );
            case Tipo::ALTERACAO_TRABALHADOR_SEM_VINCULO:
                return $preenchimento->buscarUltimoPreenchimentoTSVEAlteracao(
                    $codigoFormulario,
                    $this->getInstituicao()->getCodigo(),
                    $this->cgmEmpregador
                );
            case Tipo::ALTERACAO_SERVIDOR:
                return $preenchimento->buscarUltimoPreenchimentoAlteracaoDadosServidor(
                    $this->getInstituicao()->getCodigo(),
                    $this->cgmEmpregador
                );
                break;
            case Tipo::REINTEGRACAO:
                return $preenchimento->buscarUltimoPreenchimentoReintegracao($this->cgmEmpregador);
                break;
            case Tipo::ALTERACAO_CONTRATUAL:
                return $preenchimento->buscarUltimoPreenchimentoAlteracaoContratual($this->cgmEmpregador);
            case Tipo::CONTRIBUINTE:
                return $preenchimento->buscarUltimoPreenchimentoContribuinte();
            case Tipo::EFD_PROCESSOS:
                return $preenchimento->buscarUltimoPreenchimentoEFDProcessos();
            case Tipo::EFD_EXCLUSAO_EVENTOS:
                return $preenchimento->buscarUltimoPreenchimentoEFDExclusaoEventos();
            case Tipo::REMUNERACAO_RGPS:
                return $preenchimento->buscarUltimoPreenchimentoRemuneracaoRGPS($this->cgmEmpregador);
            case Tipo::EFD_RETENCOES_SERVICOS_TOMADOS:
                return $preenchimento->buscarUltimoPreenchimentoEFDServicosTomados($this->ano, $this->mes);
            case Tipo::EFD_PROCESSOS:
                return $preenchimento->buscarUltimoPreenchimentoEFDProcessos();
            case Tipo::EFD_SERVICOS_PRESTADOS:
                return $preenchimento->buscarUltimoPreenchimentoEFDServicosPrestados();
            case Tipo::TOTALIZACAO_PAGAMENTOS_CONTINGENCIA:
                return $preenchimento->buscarUltimoPreenchimentoTotalizacaoPagamentosContingencia();
            case Tipo::FECHAMENTO_EVENTOS_PERIODICOS:
                return $preenchimento->buscarUltimoPreenchimentoFechamentoEventosPeriodicos(
                    $this->indicativoPeriodoApuracao,
                    $this->ano,
                    $this->mes
                );
            case Tipo::EFD_FECHAMENTO_PERIODICOS:
                return $preenchimento->buscarUltimoPreenchimentoEFDFechamentoPeriodicos($this->ano, $this->mes);
            case Tipo::CAT:
                $preenchimento->setInstituicao($this->getInstituicao());
                return $preenchimento->buscarUltimoPreenchimentoCat(
                    $codigoFormulario,
                    $this->cgmEmpregador,
                    $this->dataInicial,
                    $this->dataFinal
                );
                break;
            default:
                throw new Exception('Tipo não encontrado.');
        }
    }

    /**
     * Busca as respostas de um preenchimento do formulário
     *
     * @param array $preenchimentos
     * @throws Exception
     */
    private function buscaRespostas(array $preenchimentos)
    {
        $dadosPreechimento = new DadosPreenchimentoFormatter();
        foreach ($preenchimentos as $preenchimento) {
            $this->dados[] = $dadosPreechimento->formatar(
                $this->tipo,
                $this->identificaResponsavel($preenchimento),
                (isset($preenchimento->inscricao_empregador)
                    ? $preenchimento->inscricao_empregador
                    : $preenchimento->inscricao_contribuinte
                ),
                Preenchimentos::buscaRespostas($preenchimento->preenchimento),
                $preenchimento
            );
        }
    }

    /**
     * Identifica o responsável pelo preenchimento
     * O responsável é a figura "dona" das respostas/ que preencheu o formulário
     *
     * @param \stdClass $preenchimento
     * @throws \Exception
     * @return integer
     */
    private function identificaResponsavel(\stdClass $preenchimento)
    {
        switch ($this->tipo) {
            case Tipo::SERVIDOR:
            case Tipo::TRABALHADOR_SEM_VINCULO:
            case Tipo::ALTERACAO_TRABALHADOR_SEM_VINCULO:
            case Tipo::AVISO_PREVIO:
            case Tipo::ADMISSAO_PRELIMINAR:
            case Tipo::ALTERACAO_SERVIDOR:
                return $preenchimento->matricula;
            case Tipo::EMPREGADOR:
            case Tipo::LOTACAO_TRIBUTARIA:
                return $preenchimento->cgm;
            case Tipo::CARGO:
                return $preenchimento->cargo;
            case Tipo::RUBRICA:
            case Tipo::FUNCAO:
            case Tipo::HORARIO:
            case Tipo::TRABALHO_INTERMITENTE:
            case Tipo::AFASTAMENTO_TEMPORARIO:
            case Tipo::EXCLUSAO_EVENTOS:
            case Tipo::REINTEGRACAO:
            case Tipo::CONTRIBUINTE:
            case Tipo::ALTERACAO_CONTRATUAL:
            case Tipo::REMUNERACAO_RGPS:
            case Tipo::EFD_EXCLUSAO_EVENTOS:
            case Tipo::EFD_RETENCOES_SERVICOS_TOMADOS:
            case Tipo::FECHAMENTO_EVENTOS_PERIODICOS:
            case Tipo::TOTALIZACAO_PAGAMENTOS_CONTINGENCIA:
            case TIPO::EFD_FECHAMENTO_PERIODICOS:
                return $preenchimento->identificador;
            case Tipo::DESLIGAMENTO_SERVIDOR:
            case Tipo::TERMINO_TRABALHADOR_SEM_VINCULO:
                return $preenchimento->referencia;
            case Tipo::PROCESSOS:
            case Tipo::EFD_PROCESSOS:
            case Tipo::EFD_SERVICOS_PRESTADOS:
                return $preenchimento->preenchimento;
            case Tipo::OBRAS:
                return $preenchimento->cnpj_obras;
            case Tipo::CAT:
                return "{$preenchimento->cpf_cat}_{$preenchimento->data_cat}";
            default:
                throw new Exception('Identificador de responsável por tipo de evento não encontrado.');
        }
    }

    /**
     * @return \Instituicao
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param \Instituicao $instituicao
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * @param Servidor[] $servidores
     */
    public function setServidores(array $servidores)
    {
        $this->servidores = $servidores;
    }

    /**
     * @return bool
     */
    public function filtraServidores()
    {
        return count($this->servidores) > 0;
    }

    /**
     * Retorna o where do responsável de preenchimento, para os casos de arquivos que filtram por matrícula no envio
     * @param int $tipo
     * @param array $referencias
     * @return string
     * @throws BusinessException
     * @throws DBException
     * @throws ParameterException
     */
    public static function buscaCondicaoResponsavelPreenchimento(
        $tipo,
        $referencias = null,
        $competencia = null,
        $indicativoPeriodoApuracao = null,
        $dataInicio = null,
        $dataFim = null,
        $cgmEmpregador = null
    ) {
        if (empty($tipo)) {
            throw new ParameterException('Tipo de arquivo não informado.');
        }

        $where = '';

        switch ($tipo) {
            case Tipo::AFASTAMENTO_TEMPORARIO:
                $instituicao = \InstituicaoRepository::getInstituicaoSessao();
                $servidores = ServidorRepository::getServidoresByMatriculas(
                    $competencia->ano,
                    $competencia->mes,
                    $referencias
                );

                $codigosAssentamentos = array();

                $matriculas = [];
                foreach ($servidores as $servidor) {
                    $matriculas[] = $servidor->getMatricula();
                }
                $where = array(
                    "rh01_instit = {$instituicao->getCodigo()}",
                    "avaliacaopergunta.db103_identificadorcampo = 'codMotAfast'"
                );
                if (!empty($matriculas)) {
                    $where[] = " eso12_rhpessoal IN (". implode(', ', $matriculas) . ")";
                }

                $dao = new cl_avaliacaogruporespostaafastamentoesocial();
                $sql = $dao->sql_avaliacao_afastamentos(
                    $instituicao->getCodigo(),
                    $where,
                    $competencia->ano,
                    $competencia->mes,
                    $cgmEmpregador
                );
                $rs = db_query($sql);

                if ($rs) {
                    $contador = pg_num_rows($rs);
                    for ($i = 0; $i < $contador; $i++) {
                        $assentamento = \db_utils::fieldsMemory($rs, $i);
                        $codigosAssentamentos[] = $assentamento->identificador;
                    }
                }

                if (!empty($codigosAssentamentos)) {
                    $where = " AND rh213_responsavelpreenchimento in ('" . implode("','", $codigosAssentamentos) . "')";
                } else {
                    throw new DBException("Nenhuma informação encontrada para envio.");
                }
                break;
            case Tipo::DESLIGAMENTO_SERVIDOR:
            case Tipo::TERMINO_TRABALHADOR_SEM_VINCULO:
                $codigosRescisoes = [];

                foreach ($referencias as $matricula) {
                    $servidor = ServidorRepository::getInstanciaByCodigo($matricula);
                    $rescisaoRepository = RescisaoRepository::getInstance();
                    $rescisaoModel = $rescisaoRepository->getRescisaoPorServidorEsocial($servidor);

                    if ($rescisaoModel->getCodigo() !== null) {
                        $codigosRescisoes[] = $rescisaoModel->getCodigo();
                    }
                }

                if (!empty($codigosRescisoes)) {
                    $where = " AND rh213_responsavelpreenchimento in ('" . implode("','", $codigosRescisoes) . "')";
                }
                break;
            case Tipo::MONITORAMENTO_SAUDE:
                $sqlAssenta = "
                    select
                        h16_codigo::text
                    from
                        recursoshumanos.assenta
                        inner join esocialenvio
                            on h16_codigo::text = rh213_responsavelpreenchimento
                    where
                        (
                            extract(MONTH FROM h16_dtconc) = {$competencia->mes}
                            AND extract(YEAR FROM h16_dtconc) = {$competencia->ano}
                        ) OR (
                            h16_dtterm is not null
                            AND  extract(month from h16_dtterm) = {$competencia->mes}
                            AND extract(year from h16_dtterm) = {$competencia->ano}
                        )
                ";
                if (!empty($referencias)) {
                    $sqlAssenta .= " AND h16_regist in (" . implode(",", $referencias) . ")";
                }
                $where = " and rh213_responsavelpreenchimento in ({$sqlAssenta}) ";
                break;
            case Tipo::EXPOSICAO_RISCO:
                if (empty($referencias)) {
                    throw new ParameterException('Nenhuma matrícula informada.');
                }

                $totalMatriculas = sizeof($referencias);
                if ($totalMatriculas > 0) {
                    $where = " and (rh213_responsavelpreenchimento ilike '{$referencias[0]}_%' ";
                    for ($i = 1; $i < $totalMatriculas; $i++) {
                        $where .= " or rh213_responsavelpreenchimento ilike '{$referencias[$i]}_%' ";
                    }
                    $where .= ")";
                }
                break;

            case Tipo::CADASTRO_BENEFICIARIO:
                if ((isset($competencia->ano) && empty($competencia->ano))
                    || (isset($competencia->mes) && empty($competencia->mes))
                ) {
                    $competencia = new \stdClass();
                    $competencia->ano = DBPessoal::getAnoFolha();
                    $competencia->mes = DBPessoal::getMesFolha();
                }
                if (empty($referencias)) {
                    $competencia = new DBCompetencia($competencia->ano, $competencia->mes);
                    $servidores = CadastroBeneficiario::buscarBeneficiarios($competencia);
                    foreach ($servidores as $servidor) {
                        $referencias[] = $servidor->getMatricula();
                    }
                }
                $totalMatriculas = sizeof($referencias);
                if ($totalMatriculas > 0) {
                    $where = " AND rh213_responsavelpreenchimento in (select z01_cgccpf from protocolo.cgm where ";
                    $where .= "z01_numcgm in(select rh01_numcgm from pessoal.rhpessoal where rh01_regist ";
                    $where .= "in('" . implode("','", $referencias) . "')))";
                }
                break;
            case Tipo::CADASTRO_BENEFICIARIO_ALTERACAO:
                if (empty($referencias)) {
                    throw new ParameterException('Nenhuma matrícula informada.');
                }

                $totalMatriculas = sizeof($referencias);
                if ($totalMatriculas > 0) {
                    $where = " AND rh213_responsavelpreenchimento in (select z01_cgccpf from protocolo.cgm where ";
                    $where .= "z01_numcgm in(select rh01_numcgm from pessoal.rhpessoal where rh01_regist ";
                    $where .= "in('" . implode("','", $referencias) . "')))";
                }
                break;
            case Tipo::CESSAO_EXERCICIO:
                if (empty($referencias)) {
                    throw new ParameterException('Nenhuma matrícula informada.');
                }

                $totalMatriculas = sizeof($referencias);
                if ($totalMatriculas > 0) {
                    $where = " and (";
                    foreach ($referencias as $contador => $matricula) {
                        if ($contador > 0) {
                            $where .= " or ";
                        }
                        $where .="rh213_responsavelpreenchimento ilike '%{$matricula}%'";
                    }
                    $where .= ")";
                }
                break;
            case Tipo::REMUNERACAO_RPPS:
            case Tipo::REMUNERACAO_RGPS:
                $rpps = false;
                if ($tipo == Tipo::REMUNERACAO_RPPS) {
                    $rpps = true;
                }
                // Caso nao seja informada a competencia, é setada a competencia atual
                if (empty($competencia)) {
                    $competencia = new \stdClass();
                    $competencia->ano = DBPessoal::getAnoFolha();
                    $competencia->mes = DBPessoal::getMesFolha();
                }
                /**
                 * Caso nao seje informado o indicativo de apuracao, é setado o periodo mensal = 1
                 * 1 = mensal
                 * 2 = anual
                 */
                if (empty($indicativoPeriodoApuracao)) {
                    $indicativoPeriodoApuracao = 1;
                }
                if (empty($referencias)) {
                    $servidores = ServidorRepository::getServidoresPorCompetencia(
                        $competencia->ano,
                        $competencia->mes,
                        null,
                        true
                    );
                } else {
                    $servidores = ServidorRepository::getServidoresByMatriculas(
                        $competencia->ano,
                        $competencia->mes,
                        $referencias
                    );
                }
                $cgms = [];
                /**
                 * S-1200 nao utiliza matricula, precisamos buscar os servidores para montar a referencia com*
                 */
                foreach ($servidores as $servidor) {
                    if ($rpps) {
                        $cgms[] = $servidor->getCgm()->getCodigo() . '-' . $competencia->ano . $competencia->mes
                            . '-' .$indicativoPeriodoApuracao;
                    } else {
                        $cgms[] = $servidor->getCgm()->getCodigo() . $competencia->ano . $competencia->mes
                            . $indicativoPeriodoApuracao;
                    }
                }
                $where = " AND rh213_responsavelpreenchimento in ('" . implode("','", $cgms) . "')";
                break;
            case Tipo::REMUNERACAO_BENEFICIOS_ENTES_PUBLICOS:
                if (empty($referencias)) {
                    throw new ParameterException('Nenhuma matrícula informada.');
                }

                // Caso nao seja informada a competencia, é setada a competencia atual
                if (empty($competencia)) {
                    $competencia = new \stdClass();
                    $competencia->ano = DBPessoal::getAnoFolha();
                    $competencia->mes = DBPessoal::getMesFolha();
                }
                /**
                 * Caso nao seja informado o indicativo de apuracao, é setado o periodo mensal = 1
                 * 1 = mensal
                 * 2 = anual
                 */
                if (empty($indicativoPeriodoApuracao)) {
                    $indicativoPeriodoApuracao = 1;
                }
                $servidores = ServidorRepository::getServidoresByMatriculas(
                    $competencia->ano,
                    $competencia->mes,
                    $referencias
                );
                $cgms = [];
                /**
                 * S-1207 nao utiliza matricula, precisamos buscar os servidores para montar a referencia com*
                 */
                foreach ($servidores as $servidor) {
                    $cgms[] = $servidor->getCgm()->getCodigo() . '_' . $competencia->ano . $competencia->mes
                        . '_' . $indicativoPeriodoApuracao;
                }
                $where = " AND rh213_responsavelpreenchimento in ('" . implode("','", $cgms) . "')";
                break;
            case Tipo::PAGAMENTOS_RENDIMENTOS_TRABALHO:
                if (empty($referencias)) {
                    throw new ParameterException('Nenhuma matrícula informada.');
                }

                if (empty($competencia)) {
                    $competencia = new \stdClass();
                    $competencia->ano = DBPessoal::getAnoFolha();
                    $competencia->mes = DBPessoal::getMesFolha();
                }

                $servidores = ServidorRepository::getServidoresByMatriculas(
                    $competencia->ano,
                    $competencia->mes,
                    $referencias
                );
                $cgms = [];
                /**
                 * S-1210 nao utiliza matricula, precisamos buscar os servidores para montar a referencia com*
                 */
                foreach ($servidores as $servidor) {
                    $cgms[] = $servidor->getCgm()->getCodigo() .'_'. $competencia->ano . $competencia->mes;
                }
                $where = " AND rh213_responsavelpreenchimento in ('" . implode("','", $cgms) . "')";
                break;
            case Tipo::ALTERACAO_BENEFICIO:
                if ((isset($competencia->ano) && empty($competencia->ano))
                    || (isset($competencia->mes) && empty($competencia->mes))
                ) {
                    $competencia = new \stdClass();
                    $competencia->ano = DBPessoal::getAnoFolha();
                    $competencia->mes = DBPessoal::getMesFolha();
                }
                if (!empty($referencias)) {
                    $matriculaServidores = $referencias;
                } else {
                    $instituicao = \InstituicaoRepository::getInstituicaoSessao();
                    $dataInicio = "{$competencia->ano}-{$competencia->mes}-01";
                    $quantidadeDias = DBDate::getQuantidadeDiasMes($competencia->mes, $competencia->ano);
                    $dataFim = "{$competencia->ano}-{$competencia->mes}-{$quantidadeDias}";
                    $sql = <<<SQL
                        select
                            eso38_matricula as matricula
                        from
                            esocial.servidoralteracao
                            inner join pessoal.rhpessoal on eso38_matricula = rh01_regist
                        where
                            rh01_instit = {$instituicao->getCodigo()}
                            and eso38_s2416data between '{$dataInicio}' and '{$dataFim}'
SQL;
                    $rs = db_query($sql);
                    if (!$rs) {
                        throw new DBException("Erro ao buscar matriculas para envio do evento S-2416.");
                    }
                    $servidores = \db_utils::getCollectionByRecord($rs);
                    foreach ($servidores as $servidor) {
                        $matriculaServidores[] = $servidor->matricula;
                    }
                }

                $where = " AND rh213_responsavelpreenchimento in ('" . implode("','", $matriculaServidores) . "')";
                break;
            case Tipo::CADASTRO_BENEFICIO:
                if ((isset($competencia->ano) && empty($competencia->ano))
                    || (isset($competencia->mes) && empty($competencia->mes))
                ) {
                    $competencia = new \stdClass();
                    $competencia->ano = DBPessoal::getAnoFolha();
                    $competencia->mes = DBPessoal::getMesFolha();
                }
                if (!empty($referencias)) {
                    $matriculaServidores = $referencias;
                } else {
                    $competencia = new DBCompetencia($competencia->ano, $competencia->mes);
                    $servidores = CadastroBeneficio::buscarBeneficios($competencia);
                    foreach ($servidores as $servidor) {
                        $matriculaServidores[] = $servidor->getMatricula();
                    }
                }
                $where = " AND rh213_responsavelpreenchimento in ('" . implode("','", $matriculaServidores) . "')";
                break;
            case Tipo::EXCLUSAO_EVENTOS:
                if (empty($dataInicio) || (empty($dataFim))) {
                    throw new ParameterException('As datas de Preenchimento tem que ser preenchidas');
                }
                $where =  " INNER JOIN esocial.avaliacaogruporespostaexclusaoeventos";
                $where .= " ON eso14_protocolo = rh213_responsavelpreenchimento";
                $where .= " INNER JOIN habitacao.avaliacaogruporesposta";
                $where .= " ON db107_sequencial = eso14_avaliacaogruporesposta";
                $where .= " AND db107_datalancamento between '{$dataInicio}' and '{$dataFim}'";
                break;
            case Tipo::TRABALHADOR_SEM_VINCULO:
                if ((isset($competencia->ano) && !empty($competencia->ano))
                    || (isset($competencia->mes) && !empty($competencia->mes))
                ) {
                    $ano = $competencia->ano;
                    $mes = $competencia->mes;
                } else {
                    $ano = DBPessoal::getAnoFolha();
                    $mes = DBPessoal::getMesFolha();
                }

                if (!empty($referencias)) {
                    $matriculaServidores = $referencias;
                } else {
                    if (!empty($competencia->ano) && !empty($competencia->mes)) {
                        $servidores = ServidorRepository::getServidoresPorCompetenciaAdmissao(
                            $ano,
                            $mes
                        );
                    } else {
                        $servidores = ServidorRepository::getServidoresPorCompetencia($ano, $mes, null, true);
                    }

                    foreach ($servidores as $servidor) {
                        $matriculaServidores[] = $servidor->getMatricula();
                    }
                }
                $where = " AND rh213_responsavelpreenchimento in ('" . implode("','", $matriculaServidores) . "')";
                break;
            case Tipo::PROCESSO_TRABALHISTA:
                if (!empty($referencias)) {
                    $where = " AND split_part(rh213_responsavelpreenchimento,'-',1) in ('" .
                        implode("','", $referencias) . "')";
                }
                break;
            case Tipo::TRIBUTO_TRABALHISTA:
                    $reponsavelPreenchimento = [];
                    $tributoBaseRepository = new TributoBaseRepository();
                foreach ($referencias as $matricula) {
                    $tributosBase = $tributoBaseRepository
                        ->scopeMatricula($matricula)
                        ->get();
                    $servidor = ServidorRepository::getInstanciaByCodigo($matricula);
                    $cpf = $servidor->getCgm()->getCpf();
                    foreach ($tributosBase as $tributoBase) {
                        if (!empty($cpf)) {
                            $tributoBase->setCpf($cpf);
                            $tributoBase->setResponsavelPreenchimento();
                        }
                        $reponsavelPreenchimento[] = $tributoBase->getResponsavelPreenchimento();
                    }
                }

                if (!empty($reponsavelPreenchimento)) {
                    $where = " AND rh213_responsavelpreenchimento in ('" .
                        implode("','", $reponsavelPreenchimento) . "')";
                }
                break;
            default:
                if (!empty($referencias)) {
                    $where = " AND rh213_responsavelpreenchimento in ('" . implode("','", $referencias) . "')";
                }
                break;
        }
        return $where;
    }

    public function setRecibo($recibo)
    {
        $this->recibo = $recibo;
    }

    /**
     * Get the value of listaRubricas
     */
    public function getListaRubricas()
    {
        return $this->listaRubricas;
    }

    /**
     * Set the value of listaRubricas
     *
     * @return  self
     */
    public function setListaRubricas($listaRubricas)
    {
        $this->listaRubricas = $listaRubricas;
    }
}
