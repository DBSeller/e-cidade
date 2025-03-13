<?php

require_once modification('src/RecursosHumanos/ESocial/Service/ExclusaoEventosService.php');

use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use ECidade\RecursosHumanos\ESocial\Service\ExclusaoEventosService;
use ECidade\RecursosHumanos\ESocial\Service\ReintegracaoService;
use ECidade\RecursosHumanos\ESocial\Service\TrabalhoIntermitenteService;
use ECidade\RecursosHumanos\ESocial\Factory\ESocialPreenchimentoValidatorFactory;

class AvaliacaoESocial
{

    /**
     * Instancia da Avaliacao
     * @var Avaliacao
     */
    private $oAvaliacao;

    /**
     * Instancia do servidor
     * @var Servidor
     */
    private $oServidor;

    /**
     * Instancia do cgm
     * @var \CgmBase
     */
    private $oCgm;

    private $oPerguntasRespostas;

    private $ano;

    private $perguntasParaExcluirRespostas = array();

    public function __construct()
    {
    }

    public function setAvaliacao($oAvaliacao)
    {
        $this->oAvaliacao = $oAvaliacao;
    }

    public function getAvaliacao()
    {
        return $this->oAvaliacao;
    }

    /**
     * Define do servidor da Avaliacao
     * @param \Servidor $oServidor
     */
    public function setServidor(Servidor $oServidor)
    {
        $this->oServidor = $oServidor;
    }

    /**
     * @return Servidor
     */
    public function getServidor()
    {
        return $this->oServidor;
    }

    public function setPerguntasRespostas($oPerguntasRespostas)
    {
        $this->oPerguntasRespostas = $oPerguntasRespostas;
    }

    public function gerPerguntasRespostas()
    {
        return $this->oPerguntasRespostas;
    }

    /**
     * @param null $iCodigoGrupoPerguntas
     * @param null $sTipoFormulario
     * @param null $aParametros
     * @throws DBException
     * @throws ParameterException
     */
    public function salvar($iCodigoGrupoPerguntas = null, $sTipoFormulario = null, $aParametros = null)
    {
        if (empty($this->oPerguntasRespostas)) {
            throw new ParameterException("Não foram enviadas respostas para salvar.");
        }

        /**
         * Verifica se existe preenchimento do formulario
         * Se existir é uma alteracao sem incluir novas respostas
         */
        if (!empty($aParametros["iCodigoPreenchimento"])) {
            $this->oAvaliacao->setAvaliacaoGrupo($aParametros["iCodigoPreenchimento"], true);
        }


        /**
         * Obtém as perguntas da avaliação
         */
        $aPerguntasAvaliacao = $this->oAvaliacao->getPerguntas($iCodigoGrupoPerguntas);

        /**
         * Percorre os grupoas enviados para montar array com as perguntas
         * e respostas que será utilizado para salvar as repostas logo abaixo
         */
        $aPerguntasRespondidas = array();

        if (is_object($this->oPerguntasRespostas->grupos)) {
            $this->oPerguntasRespostas->grupos = array($this->oPerguntasRespostas->grupos);
        }

        foreach ($this->oPerguntasRespostas->grupos as $iGrupo => $oGrupo) {
            foreach ($oGrupo->perguntas as $oPergunta) {
                $aPerguntasRespondidas[$oPergunta->codigo] = $oPergunta->respostas;
            }
        }

        $esocialPreenchimentoValidator = ESocialPreenchimentoValidatorFactory::getByIdentificador(
            $this->oAvaliacao->getIdentificador()
        );
        if (!empty($esocialPreenchimentoValidator)) {
            $esocialPreenchimentoValidator->setPerguntas($aPerguntasRespondidas);
            $esocialPreenchimentoValidator->validar();
            if ($esocialPreenchimentoValidator->temErros()) {
                throw new Exception("Há inconsistências no preenchimento do formulário: <br>"
                                     . $esocialPreenchimentoValidator->formataErros());
            }
        }

        /**
         * Percorre as perguntas da avaliação para salvar as respostas
         */
        foreach ($aPerguntasAvaliacao as $oPergunta) {
            $oAvaliacaoResposta = new AvaliacaoResposta();
            $oAvaliacaoResposta->setPergunta($oPergunta);
            AvaliacaoRespostaRepository::delete($oAvaliacaoResposta);

            $oPergunta->getRespostas();

            if (isset($aPerguntasRespondidas[$oPergunta->getCodigo()])) {
                $aRespostasSalvar = array(); // Array com as respostas que devem ser salvas

                if ($oPergunta->getTipo() == 1) {

                    $perguntaRespondida = false;
                    foreach ($aPerguntasRespondidas[$oPergunta->getCodigo()] as $oRespostaObjetiva) {

                        if ((bool)$oRespostaObjetiva->valor === false) {
                            continue;
                        }
                        $perguntaRespondida = true;
                    }

                    // Caso não tenha sido marcado nenhuma opção de reposta para a pergunta,
                    // removemos todas as repostas anteriores dessa pergunta.
                    if (!$perguntaRespondida) {
                        unset($aPerguntasRespondidas[$oPergunta->getCodigo()]);
                        $this->perguntasParaExcluirRespostas[] = $oPergunta->getCodigo();
                        continue;
                    }
                }


                foreach ($aPerguntasRespondidas[$oPergunta->getCodigo()] as $oRespostaSalvar) {
                    if (in_array((int)$oPergunta->getTipo(),
                        array(1, 3))) { // Se for pergunta do tipo objetiva ou multipla escolha
                        if ((bool)$oRespostaSalvar->valor === false) { // Salva apenas se resposta estiver marcada
                            continue;
                        }
                    }
                    if ($oPergunta->getTipoComponente() == 5 && !empty($oRespostaSalvar->valor)) {
                        $datetime = new DateTime($oRespostaSalvar->valor);
                        $oRespostaSalvar->valor = $datetime->format("Y-m-d");
                    }
                    /**
                     * Popula array com objetos de resposta para salvar
                     * caso a pergunta seja dos tipo 1 ou 3 que são objetivas
                     * ou múltipla escolha então nao salva o valor apenas vincula a reposta
                     * salva o valor auxiliar quando existir
                     */
                    $sTextoResposta = (!empty($oRespostaSalvar->valorAuxiliar)) ? $oRespostaSalvar->valorAuxiliar : ((in_array((int)$oPergunta->getTipo(),
                        array(1, 3))) ? $oRespostaSalvar->codigo : $oRespostaSalvar->valor);
                    $oAvaliacaoResposta->setPerguntaOpcao($oRespostaSalvar->codigo);
                    $oAvaliacaoResposta->setResposta($sTextoResposta);
                    AvaliacaoRespostaRepository::persist($oAvaliacaoResposta);
                }
            }
        }

        if (!empty($sTipoFormulario)) {
            switch ($sTipoFormulario) {
                case 'admissaoPreliminar':
                    $this->persistirDadosAdmissaoPreliminar($aParametros);
                    break;
                case 'lotacaoTributaria':
                    $this->persitirDadosLotacao();
                    break;
                case 'obras':
                    $this->persitirDadosObras($aParametros);
                    break;
                case 'processos':
                    $this->persitirDadosProcessos($aParametros);
                    break;
                case 'trabalhoIntermitente':
                    $this->persistirTrabalhoIntermitente();
                    break;
                case 'avisoprevio':
                    $this->persistirAvisoPrevio($aParametros);
                    break;
                case 'afastamentotemporario':
                    $this->persistirAfastamentoTemporario($aParametros);
                    break;
                case 'previsao_receita':
                    $this->persistirPrevisaoReceita($aParametros);
                    break;
                case Tipo::EXCLUSAO_EVENTOS:
                    $service = new ExclusaoEventosService();
                    $service->setAvaliacao($this->oAvaliacao);
                    $service->setParametros($aParametros);
                    $service->salvar();
                    break;
                case Tipo::TRABALHO_INTERMITENTE:
                    $service = new TrabalhoIntermitenteService();
                    $service->setAvaliacao($this->oAvaliacao);
                    $service->setParametros($aParametros);
                    $service->salvar();
                    break;
                case Tipo::TRABALHADOR_SEM_VINCULO:
                    $this->persitirDadosTrabalhador();
                    $this->persitirDadosCgm();
                    break;
                case Tipo::REINTEGRACAO:
                    $service = new ReintegracaoService();
                    $service->setAvaliacao($this->oAvaliacao);
                    $service->setParametros($aParametros);
                    $service->salvar();
                    break;
                case TIPO::ALTERACAO_SERVIDOR:
                    $this->persitirAlteracaoDadosServidor();
                    $this->persitirDadosCgm();
                    break;
                case TIPO::TERMINO_TRABALHADOR_SEM_VINCULO:
                    $this->persitirTerminoTrabalhadorSemVinculoDadosServidor();
                    $this->persitirDadosCgm();
                    break;
                case Tipo::ALTERACAO_CONTRATUAL:
                    $this->persistirAlteracaoCadastral($aParametros);
                    break;
                case Tipo::ALTERACAO_TRABALHADOR_SEM_VINCULO:
                    $this->persistirAlteracaoContratoTSVE($aParametros);
                    break;
                case Tipo::REMUNERACAO_RGPS:
                    $this->persistirRemuneracaoRGPS($aParametros);
                    break;
                case Tipo::DESLIGAMENTO_SERVIDOR:
                    $this->persitirDesligamentoServidor();
                    break;
                case Tipo::CAT:
                    $this->persitirDadosCat($aParametros);
                    break;
                case Tipo::MONITORAMENTO_SAUDE:
                    $this->persitirDadosMonitoriamentoSaude($aParametros);
                    break;
                default:
                    $this->salvarPreenchimentoDoServidor();
                    $this->persitirDadosCgm();
                    break;
            }
        } else {
            $this->salvarPreenchimentoDoServidor();
            $this->persitirDadosCgm();
        }
    }


    /**
     * Persiste os dados da avaliacao do servidor
     * @throws \DBException
     */
    private function persitirAlteracaoDadosServidor()
    {
        if (empty($this->oServidor)) {
            return;
        }

        foreach ($this->perguntasParaExcluirRespostas as $codigoPergunta) {

            $where = "eso02_rhpessoal = {$this->getServidor()->getMatricula()} and db103_sequencial = {$codigoPergunta}";
            $this->excluirRespostas("avaliacaogruporespostarhpessoal", "eso02_avaliacaogruporesposta", $where);
        }

        /**
         * Vincula as matriculas as repostas
         */
        $oDaoAvaliacaoGrupoRespostaMatricula = new cl_avaliacaogruporespostarhpessoalalteracao();
        $oDaoAvaliacaoGrupoRespostaMatricula->eso17_avaliacaogruporesposta = $this->oAvaliacao->getAvaliacaoGrupo();
        $oDaoAvaliacaoGrupoRespostaMatricula->eso17_rhpessoal = $this->getServidor()->getMatricula();
        $oDaoAvaliacaoGrupoRespostaMatricula->incluir(null);
        if ($oDaoAvaliacaoGrupoRespostaMatricula->erro_status == "0") {
            throw new DBException("Ocorreu um erro ao vincular o matrícula ao questionário\n\n" . $oDaoAvaliacaoGrupoRespostaMatricula->erro_sql . PHP_EOL . pg_last_error());
        }
    }

    /**
     * @throws Exception
     */
    private function salvarPreenchimentoDoServidor()
    {
        if (empty($this->oServidor)) {
            return;
        }

        if (!$this->oCgm) {
            $this->oCgm = $this->oServidor->getEmpregador();
        }

        foreach ($this->perguntasParaExcluirRespostas as $codigoPergunta) {
            $where = "eso02_rhpessoal = {$this->oServidor->getMatricula()} AND db103_sequencial = {$codigoPergunta}";
            $this->excluirRespostas('avaliacaogruporespostarhpessoal', 'eso02_avaliacaogruporesposta', $where);
        }

        $oDaoAvaliacaoGrupoRespostaMatricula = new cl_avaliacaogruporespostarhpessoal();
        $oDaoAvaliacaoGrupoRespostaMatricula->eso02_avaliacaogruporesposta = $this->oAvaliacao->getAvaliacaoGrupo();
        $oDaoAvaliacaoGrupoRespostaMatricula->eso02_rhpessoal = $this->oServidor->getMatricula();
        $oDaoAvaliacaoGrupoRespostaMatricula->eso02_avaliacao = $this->oAvaliacao->getCodigo();
        $oDaoAvaliacaoGrupoRespostaMatricula->eso02_empregador = $this->oCgm->getCodigo();
        $oDaoAvaliacaoGrupoRespostaMatricula->incluir(null);

        if ($oDaoAvaliacaoGrupoRespostaMatricula->erro_status == "0") {
            throw new DBException("Ocorreu um erro ao vincular o matrícula ao questionário\n\n" . $oDaoAvaliacaoGrupoRespostaMatricula->erro_sql . PHP_EOL . pg_last_error());
        }
    }

    /**
     * Persiste os ddos da Avaliacao do Cgm
     * @throws \DBException
     */
    private function persitirDadosCgm()
    {
        if (empty($this->oCgm)) {
            return;
        }

        if (empty($this->oServidor)) {
            foreach ($this->perguntasParaExcluirRespostas as $codigoPergunta) {
                $where = "eso03_cgm = {$this->getCgm()->getCodigo()} and db103_sequencial = {$codigoPergunta}";
                $this->excluirRespostas("avaliacaogruporespostacgm", "eso03_avaliacaogruporesposta", $where);
            }
        }

        $oDaoAvaliacaoGrupoRespostaCgm = new cl_avaliacaogruporespostacgm();
        $oDaoAvaliacaoGrupoRespostaCgm->eso03_avaliacaogruporesposta = $this->oAvaliacao->getAvaliacaoGrupo();
        $oDaoAvaliacaoGrupoRespostaCgm->eso03_cgm = $this->getCgm()->getCodigo();
        $oDaoAvaliacaoGrupoRespostaCgm->incluir(null);

        if ($oDaoAvaliacaoGrupoRespostaCgm->erro_status == "0") {
            throw new DBException("Ocorreu um erro ao vincular o matrícula ao questionário\n\n" . $oDaoAvaliacaoGrupoRespostaCgm->erro_sql . PHP_EOL . pg_last_error());
        }
    }

    /**
     * @throws DBException
     */
    private function persistirTrabalhoIntermitente()
    {
        if (empty($this->oCgm) || empty($this->oServidor)) {
            return null;
        }

        $where = array(
            "eso06_cgmempregador = {$this->getCgm()->getCodigo()}",
            "eso06_rhpessoal = {$this->getServidor()->getMatricula()}"
        );

        $daoTrabalhoIntermitente = new cl_avaliacaogruporespostatrabalhointermitente();
        $sql = $daoTrabalhoIntermitente->sql_query_file(null, '1', null, implode(' AND ', $where));
        $resultado = db_query($sql);

        if (!$resultado) {
            throw new DBException('Não foi possível buscar o formulário.');
        }

        if (pg_num_rows($resultado)) {
            return null;
        }

        $daoTrabalhoIntermitente->eso06_sequencial = null;
        $daoTrabalhoIntermitente->eso06_avaliacaogruporesposta = $this->oAvaliacao->getAvaliacaoGrupo();
        $daoTrabalhoIntermitente->eso06_rhpessoal = $this->getServidor()->getMatricula();
        $daoTrabalhoIntermitente->eso06_cgmempregador = $this->getCgm()->getCodigo();
        $daoTrabalhoIntermitente->incluir($daoTrabalhoIntermitente->eso06_sequencial);

        if ($daoTrabalhoIntermitente->erro_status === '0') {
            throw new DBException("Ocorreu u erro ao vincular o empregador/servidor ao questionário.\n\n" . $daoTrabalhoIntermitente->erro_sql . PHP_EOL . pg_last_error());
        }
    }

    /**
     *
     * @return \CgmBase
     */
    public function getCgm()
    {
        return $this->oCgm;
    }

    public function setCgm(\CgmBase $oCgm)
    {
        $this->oCgm = $oCgm;
    }

    /**
     * Persiste os ddos da Avaliacao da Lotacao
     * @throws \DBException
     */
    private function persitirDadosLotacao()
    {
        if (empty($this->oCgm)) {
            return;
        }

        foreach ($this->perguntasParaExcluirRespostas as $codigoPergunta) {

            $where = "eso04_cgm = {$this->getCgm()->getCodigo()} and db103_sequencial = {$codigoPergunta}";
            $this->excluirRespostas("avaliacaogruporespostalotacao", "eso04_avaliacaogruporesposta", $where);
        }

        $oDaoAvaliacaoGrupoRespostaLotacao = new cl_avaliacaogruporespostalotacao();
        $oDaoAvaliacaoGrupoRespostaLotacao->eso04_avaliacaogruporesposta = $this->oAvaliacao->getAvaliacaoGrupo();
        $oDaoAvaliacaoGrupoRespostaLotacao->eso04_cgm = $this->getCgm()->getCodigo();
        $oDaoAvaliacaoGrupoRespostaLotacao->incluir(null);

        if ($oDaoAvaliacaoGrupoRespostaLotacao->erro_status == "0") {
            throw new DBException("Ocorreu um erro ao vincular o matrícula ao questionário\n\n" . $oDaoAvaliacaoGrupoRespostaLotacao->erro_sql . PHP_EOL . pg_last_error());
        }
    }

    /**
     * Persiste os ddos da Avaliacao dos Processos
     * @throws \DBException
     */
    private function persitirDadosProcessos($aProcesso)
    {
        if (empty($this->oCgm)) {
            return;
        }

        /*Busca a opção de resposta conforme o codigo informado*/
        $oDaoAvaliacaoPerguntaOpcao = new cl_avaliacaoperguntaopcao();
        $sSql = $oDaoAvaliacaoPerguntaOpcao->sql_query_file($aProcesso['tipoProcesso'], 'db104_valorresposta');
        $rsTipoProcesso = db_query($sSql);

        if (pg_num_rows($rsTipoProcesso) == 0) {
            throw new \DBException("Tipo de processo não encontrado.");
        }

        $tipoProcesso = \db_utils::fieldsMemory($rsTipoProcesso, 0)->db104_valorresposta;

        if (empty($tipoProcesso)) {
            throw new \DBException("Ocorreu algum problema ao buscar o tipo de processo.");
        }

        // Valida se o processo e tipo ja foram preenchidos com grupos de respostas diferentes
        // Caso sim, não permite a inclusao (tem que ser a edicao)
        $oDaoAvaliacaoGrupoRespostaProcessos = new cl_avaliacaogruporespostaprocesso();

        $where = "eso05_processo = '{$aProcesso['nroProcesso']}' and eso05_tipoprocesso = {$tipoProcesso} ";
        $where .= "and eso05_cgm =  {$this->getCgm()->getCodigo()} and eso05_avaliacaogruporesposta != {$this->oAvaliacao->getAvaliacaoGrupo()}";

        $sql = $oDaoAvaliacaoGrupoRespostaProcessos->sql_query_file(null, '*', null, $where);

        $rsRespostaProcesso = db_query($sql);

        if (pg_num_rows($rsRespostaProcesso) > 0) {
            throw new Exception("Formulario já preenchido com essas informações.");
        }

        // Valida se já existe o mesmo preenchimento
        // Nesse caso, é uma alteracao do preenchimento
        // Logo, não insere

        $where = " eso05_avaliacaogruporesposta = {$this->oAvaliacao->getAvaliacaoGrupo()}";

        $oDaoAvaliacaoGrupoRespostaProcessos->excluir(null, $where);

        if ($oDaoAvaliacaoGrupoRespostaProcessos->erro_status == "0") {
            throw new DBException("Ocorreu um erro atualizar as informações\n\n" . $oDaoAvaliacaoGrupoRespostaProcessos->erro_sql . PHP_EOL . pg_last_error());
        }

        $oDaoAvaliacaoGrupoRespostaProcessos->eso05_avaliacaogruporesposta = $this->oAvaliacao->getAvaliacaoGrupo();
        $oDaoAvaliacaoGrupoRespostaProcessos->eso05_cgm = $this->getCgm()->getCodigo();
        $oDaoAvaliacaoGrupoRespostaProcessos->eso05_processo = $aProcesso['nroProcesso'];
        $oDaoAvaliacaoGrupoRespostaProcessos->eso05_tipoprocesso = $tipoProcesso;
        $oDaoAvaliacaoGrupoRespostaProcessos->incluir(null);

        if ($oDaoAvaliacaoGrupoRespostaProcessos->erro_status == "0") {
            throw new DBException("Ocorreu um erro ao vincular o matrícula ao questionário\n\n" . $oDaoAvaliacaoGrupoRespostaProcessos->erro_sql . PHP_EOL . pg_last_error());
        }
    }


    /**
     * Persiste os ddos da Avaliacao de Obras
     * @throws \DBException
     */
    private function persitirDadosObras($aObras)
    {
        if (empty($this->oCgm)) {
            return;
        }

        // Valida se o cnpj e empregador ja foi preenchidos com grupos de respostas diferentes
        // Caso sim, não permite a inclusao (tem que ser a edicao)
        $oDaoAvaliacaoGrupoRespostaObras = new cl_avaliacaogruporespostaobras();

        $where = "eso35_cnpj = '{$aObras['cnpj']}' and eso35_empregador = {$this->getCgm()->getCodigo()}
            and eso35_avaliacaogruporesposta != {$this->oAvaliacao->getAvaliacaoGrupo()}";

        $sql = $oDaoAvaliacaoGrupoRespostaObras->sql_query_file(null, '*', null, $where);

        $rsRespostaProcesso = db_query($sql);

        if (pg_num_rows($rsRespostaProcesso) > 0) {
            throw new Exception("Formulario já preenchido com essas informações.");
        }

        // Valida se já existe o mesmo preenchimento
        // Nesse caso, é uma alteracao do preenchimento
        // Logo, não insere

        $where = " eso35_avaliacaogruporesposta = {$this->oAvaliacao->getAvaliacaoGrupo()}";

        $oDaoAvaliacaoGrupoRespostaObras->excluir(null, $where);

        if ($oDaoAvaliacaoGrupoRespostaObras->erro_status == "0") {
            throw new DBException("Ocorreu um erro atualizar as informações.");
        }

        $oDaoAvaliacaoGrupoRespostaObras->eso35_avaliacaogruporesposta = $this->oAvaliacao->getAvaliacaoGrupo();
        $oDaoAvaliacaoGrupoRespostaObras->eso35_empregador = $this->getCgm()->getCodigo();
        $oDaoAvaliacaoGrupoRespostaObras->eso35_cnpj = $aObras['cnpj'];
        $oDaoAvaliacaoGrupoRespostaObras->incluir(null);

        if ($oDaoAvaliacaoGrupoRespostaObras->erro_status == "0") {
            throw new DBException("Ocorreu um erro ao vincular o cnpj ao questionário.");
        }
    }

    /**
     * Persiste os ddos da Avaliacao de CAT
     * @throws \DBException
     */
    private function persitirDadosCat($aCat)
    {
        if (empty($this->oCgm)) {
            return;
        }

        // Valida se a matricula e data de acidente ja foram preenchidos com grupos de respostas diferentes
        // Caso sim, não permite a inclusao (tem que ser a edicao)
        $oDaoAvaliacaoGrupoRespostaCat = new cl_esoacidentetrabalho();
        $data = DBDate::format($aCat['data'], DBDATE::DATA_EN);

        $aWhere = [];
        $aWhere[] = "eso36_empregador = {$this->getCgm()->getCodigo()}";
        $aWhere[] = "eso36_data = '{$data}'";
        $aWhere[] = "eso36_cpf = '{$aCat['cpf']}'";
        $aWhere[] = "eso36_avaliacaogruporesposta != {$this->oAvaliacao->getAvaliacaoGrupo()}";
        if (!empty($aCat['matricula'])) {
            $aWhere[] = "eso36_matricula = {$aCat['matricula']}";
        }

        $where = implode(" and ", $aWhere);
        $sql = $oDaoAvaliacaoGrupoRespostaCat->sql_query_file(null, 'eso36_sequencial', null, $where);

        $rsRespostaCat = db_query($sql);

        if (pg_num_rows($rsRespostaCat) > 0) {
            throw new Exception("Formulario já preenchido com essas informações.");
        }

        // Valida se já existe o mesmo preenchimento
        // Nesse caso, é uma alteracao do preenchimento
        // Logo, não insere
        $where = " eso36_avaliacaogruporesposta = {$this->oAvaliacao->getAvaliacaoGrupo()}";

        $oDaoAvaliacaoGrupoRespostaCat->excluir(null, $where);

        if ($oDaoAvaliacaoGrupoRespostaCat->erro_status == "0") {
            throw new DBException("Ocorreu um erro atualizar as informações\n\n");
        }

        $oDaoAvaliacaoGrupoRespostaCat->eso36_avaliacaogruporesposta = $this->oAvaliacao->getAvaliacaoGrupo();
        $oDaoAvaliacaoGrupoRespostaCat->eso36_empregador = $this->getCgm()->getCodigo();
        $oDaoAvaliacaoGrupoRespostaCat->eso36_matricula = $aCat['matricula'];
        $oDaoAvaliacaoGrupoRespostaCat->eso36_cpf = $aCat['cpf'];
        $oDaoAvaliacaoGrupoRespostaCat->eso36_data = $aCat['data'];
        $oDaoAvaliacaoGrupoRespostaCat->incluir(null);

        if ($oDaoAvaliacaoGrupoRespostaCat->erro_status == "0") {
            throw new DBException("Ocorreu um erro ao vincular o CPF ao questionário");
        }
    }


        /**
     * Persiste os ddos da Avaliacao de CAT
     * @throws \DBException
     */
    private function persitirDadosMonitoriamentoSaude($aCat)
    {
        if (empty($this->oCgm)) {
            return;
        }

        // Valida se a cpf foi preenchido com grupos de respostas diferentes
        // Caso sim, não permite a inclusao (tem que ser a edicao)
        $oDaoAvaliacaoGrupoRespostaMonitoriamentoSaude = new cl_avaliacaogruporespostamonitoramentosaude();
        $data = DBDate::format($aCat['dataAtestado'], DBDATE::DATA_EN);

        $aWhere = [];
        $aWhere[] = "eso37_empregador = {$this->getCgm()->getCodigo()}";
        $aWhere[] = "eso37_cpf = '{$aCat['cpf']}'";
        $aWhere[] = "eso37_dataatestado = '{$data}' ";
        $aWhere[] = "eso37_avaliacaogruporesposta != {$this->oAvaliacao->getAvaliacaoGrupo()}";

        $where = implode(" and ", $aWhere);
        $sql = $oDaoAvaliacaoGrupoRespostaMonitoriamentoSaude->sql_query_file(null, 'eso37_sequencial', null, $where);

        $rsRespostaMonitoriamentoSaude = db_query($sql);

        if (pg_num_rows($rsRespostaMonitoriamentoSaude) > 0) {
            throw new Exception("Formulario já preenchido com essas informações.");
        }

        // Valida se já existe o mesmo preenchimento
        // Nesse caso, é uma alteracao do preenchimento
        // Logo, não insere
        $where = " eso37_avaliacaogruporesposta = {$this->oAvaliacao->getAvaliacaoGrupo()}";

        $oDaoAvaliacaoGrupoRespostaMonitoriamentoSaude->excluir(null, $where);

        if ($oDaoAvaliacaoGrupoRespostaMonitoriamentoSaude->erro_status == "0") {
            throw new DBException("Ocorreu um erro atualizar as informações\n\n");
        }

        $oDaoAvaliacaoGrupoRespostaMonitoriamentoSaude->eso37_avaliacaogruporesposta = $this->oAvaliacao->getAvaliacaoGrupo();
        $oDaoAvaliacaoGrupoRespostaMonitoriamentoSaude->eso37_empregador = $this->getCgm()->getCodigo();
        $oDaoAvaliacaoGrupoRespostaMonitoriamentoSaude->eso37_matricula = $aCat['matricula'];
        $oDaoAvaliacaoGrupoRespostaMonitoriamentoSaude->eso37_cpf = $aCat['cpf'];
        $oDaoAvaliacaoGrupoRespostaMonitoriamentoSaude->eso37_dataatestado = $aCat['dataAtestado'];
        $oDaoAvaliacaoGrupoRespostaMonitoriamentoSaude->incluir(null);
        if ($oDaoAvaliacaoGrupoRespostaMonitoriamentoSaude->erro_status == "0") {
            throw new DBException("Ocorreu um erro ao vincular o CPF ao questionário");
        }
    }

    private function persistirAvisoPrevio($aDados)
    {
        if (empty($this->oCgm)) {
            return;
        }

        if (empty($aDados["matricula"])) {
            return;
        }
        $oDaoAvaliacaoGrupoRespostaAvisoPrevio = new cl_avaliacaogruporespostaavisoprevio();

        $where = " eso07_avaliacaogruporesposta = {$this->oAvaliacao->getAvaliacaoGrupo()}";
        $oDaoAvaliacaoGrupoRespostaAvisoPrevio->excluir(null, $where);

        if ($oDaoAvaliacaoGrupoRespostaAvisoPrevio->erro_status == "0") {
            throw new DBException("Ocorreu um erro atualizar as informações\n\n" . $oDaoAvaliacaoGrupoRespostaAvisoPrevio->erro_sql . PHP_EOL . pg_last_error());
        }

        $oDaoAvaliacaoGrupoRespostaAvisoPrevio->eso07_avaliacaogruporesposta = $this->oAvaliacao->getAvaliacaoGrupo();
        $oDaoAvaliacaoGrupoRespostaAvisoPrevio->eso07_empregador = $this->getCgm()->getCodigo();
        $oDaoAvaliacaoGrupoRespostaAvisoPrevio->eso07_regist = $aDados['matricula'];


        $oDaoAvaliacaoGrupoRespostaAvisoPrevio->incluir(null);

        if ($oDaoAvaliacaoGrupoRespostaAvisoPrevio->erro_status == '0') {
            throw new DBException($oDaoAvaliacaoGrupoRespostaAvisoPrevio->erro_msg);
        }
    }

    /**
     * Cria um vínculo do preenchimento do formulário com o afastamento do servidor.
     * @param $parametros
     * @return bool
     * @throws Exception
     */
    private function persistirAfastamentoTemporario($parametros)
    {
        if (empty($parametros['vinculo'])) {
            throw new Exception("Não foi informado o vínculo do servidor e o afastamento.");
        }

        $dao = new \cl_avaliacaogruporespostaafastamentoesocial();
        $sql = $dao->sql_query_file(null, "*", null, " eso13_afastamentoservidoresocial = {$parametros['vinculo']}");
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar validar se o preenchimento existe.");
        }

        if (pg_num_rows($rs) > 0) {
            return true;
        }

        if (!$this->oServidor) {
            $this->oServidor = ServidorRepository::getInstanciaByCodigo($parametros['matricula']);
        }

        if (!$this->oCgm) {
            $this->oCgm = $this->oServidor->getEmpregador();
        }

        $dao->eso13_sequencial = null;
        $dao->eso13_avaliacaogruporesposta = $this->oAvaliacao->getAvaliacaoGrupo();
        $dao->eso13_afastamentoservidoresocial = $parametros['vinculo'];
        $dao->eso13_avaliacao = $this->oAvaliacao->getCodigo();
        $dao->eso13_empregador = $this->oCgm->getCodigo();
        $dao->incluir(null);

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao salvar vínculo do preenchimento do formulário com o afastamento." . $dao->erro_msg);
        }

        return true;
    }

    private function persistirPrevisaoReceita($parametros)
    {
        $preenchimento = $this->getAvaliacao()->getAvaliacaoGrupo();
        $sequencial = null;

        $dao = new cl_avaliacaogruporespostaconta();
        $sql = $dao->sql_query_file(null, '*', null,
            "c06_avaliacaogruporesposta = {$preenchimento} AND c06_ano = {$this->getAno()}");
        $resultado = db_query($sql);

        $dao->c06_avaliacaogruporesposta = $this->oAvaliacao->getAvaliacaoGrupo();
        $dao->c06_conta = $parametros['conta'];
        $dao->c06_ano = $this->getAno();

        if (pg_num_rows($resultado) > 0) {
            $dao->c06_sequencial = pg_fetch_object($resultado)->c06_sequencial;
            $dao->alterar($dao->c06_sequencial);
        } else {
            $dao->incluir(null);
        }

        if ($dao->erro_status == 0) {
            throw new Exception("Não foi possível salvar o preenchimento do formulário. Erro: {$dao->erro_msg}");
        }
    }

    /**
     * @return int
     */
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * @param int $ano
     */
    public function setAno($ano)
    {
        $this->ano = $ano;
    }

    /**
     * Exclui as respostas anteriores
     * @param string $tabela
     * @param string $coluna
     * @param string $where
     */
    private function excluirRespostas($tabela, $coluna, $where)
    {
        //Busca respostas anteriores
        $sqlAvaliacaoResposta = " SELECT db106_sequencial FROM {$tabela} ";
        $sqlAvaliacaoResposta .= "     INNER JOIN avaliacaogruporesposta ON avaliacaogruporesposta.db107_sequencial = {$coluna} ";
        $sqlAvaliacaoResposta .= "     INNER JOIN avaliacaogrupoperguntaresposta ON avaliacaogrupoperguntaresposta.db108_avaliacaogruporesposta = avaliacaogruporesposta.db107_sequencial ";
        $sqlAvaliacaoResposta .= "     INNER JOIN avaliacaoresposta ON avaliacaoresposta.db106_sequencial = avaliacaogrupoperguntaresposta.db108_avaliacaoresposta ";
        $sqlAvaliacaoResposta .= "     INNER JOIN avaliacaoperguntaopcao ON db104_sequencial = db106_avaliacaoperguntaopcao ";
        $sqlAvaliacaoResposta .= "     INNER JOIN avaliacaopergunta ON db103_sequencial = db104_avaliacaopergunta ";
        $sqlAvaliacaoResposta .= "     INNER JOIN avaliacaogrupopergunta ON avaliacaogrupopergunta.db102_sequencial = avaliacaopergunta.db103_avaliacaogrupopergunta ";
        $sqlAvaliacaoResposta .= "  WHERE {$where} ";

        $rsAvalicaoResposta = db_query($sqlAvaliacaoResposta);
        if (!$rsAvalicaoResposta) {
            throw new DBException("Erro ao buscar respostas anteriores.");
        }

        $linhas = pg_num_rows($rsAvalicaoResposta);

        if ($linhas == 0) {
            return;
        }

        $codigosAvaliacaoResposta = db_utils::makeCollectionFromRecord($rsAvalicaoResposta, function ($retorno) {
            return $retorno->db106_sequencial;
        });

        $codigosAvaliacaoResposta = implode(", ", $codigosAvaliacaoResposta);

        $daoAvaliacaogrupoperguntaresposta = new cl_avaliacaogrupoperguntaresposta();
        $daoAvaliacaogrupoperguntaresposta->excluir(null, "db108_avaliacaoresposta in ($codigosAvaliacaoResposta)");

        if ($daoAvaliacaogrupoperguntaresposta->erro_status == '0') {
            throw new DBException("Erro ao excluir resposta vinculada a avaliação.");
        }

        $daoAvaliacaoResposta = new cl_avaliacaoresposta();
        $daoAvaliacaoResposta->excluir(null, "db106_sequencial in ($codigosAvaliacaoResposta)");

        if ($daoAvaliacaoResposta->erro_status == '0') {
            throw new DBException("Erro ao excluir respostas anteriores.");
        }
    }

    private function persitirDadosTrabalhador()
    {
        if (empty($this->oServidor)) {
            return;
        }

        foreach ($this->perguntasParaExcluirRespostas as $codigoPergunta) {

            $where = "eso16_rhpessoal = {$this->getServidor()->getMatricula()} and db103_sequencial = {$codigoPergunta}";
            $this->excluirRespostas("avaliacaogruporespostatsveinicial", "eso16_avaliacaogruporesposta", $where);
        }

        if (empty($this->oCgm)) {
            $this->oCgm = $this->getServidor()->getEmpregador();
        }


        /**
         * Vincula as matriculas as repostas
         */
        $oDaoAvaliacaoGrupoRespostaTrawbalhador = new cl_avaliacaogruporespostatsveinicial();
        $oDaoAvaliacaoGrupoRespostaTrawbalhador->eso16_avaliacaogruporesposta = $this->oAvaliacao->getAvaliacaoGrupo();
        $oDaoAvaliacaoGrupoRespostaTrawbalhador->eso16_rhpessoal = $this->getServidor()->getMatricula();
        $oDaoAvaliacaoGrupoRespostaTrawbalhador->eso16_avaliacao = $this->oAvaliacao->getCodigo();
        $oDaoAvaliacaoGrupoRespostaTrawbalhador->eso16_empregador = $this->oCgm->getCodigo();
        $oDaoAvaliacaoGrupoRespostaTrawbalhador->incluir(null);

        if ($oDaoAvaliacaoGrupoRespostaTrawbalhador->erro_status == "0") {
            throw new DBException("Ocorreu um erro ao vincular o matrícula ao questionário\n\n" . $oDaoAvaliacaoGrupoRespostaTrawbalhador->erro_sql . PHP_EOL . pg_last_error());
        }
    }

    /**
     * Metodo responsavel por persistir, na tabela de vinculo trabalhador sem vinculo
     *
     * @throws DBException
     */
    private function persitirTerminoTrabalhadorSemVinculoDadosServidor()
    {
        if (empty($this->oServidor)) {
            return;
        }

        foreach ($this->perguntasParaExcluirRespostas as $codigoPergunta) {

            $where = "eso24_rhpessoal = {$this->getServidor()->getMatricula()} and db103_sequencial = {$codigoPergunta}";
            $this->excluirRespostas("avaliacaogruporespostatertrabasemvinc", "eso24_avaliacaogruporesposta", $where);
        }

        /**
         * Vincula as matriculas as repostas
         */
        $anoFolha = $this->getServidor()->getAnoCompetencia();
        $mesFolha = $this->getServidor()->getMesCompetencia();
        $matricula = $this->getServidor()->getMatricula();

        $oDaoAvaliacaoGrupoRespostaTrawbalhadorsemVinculo = new cl_avaliacaogruporespostatertrabasemvinc();
        $oDaoAvaliacaoGrupoRespostaTrawbalhadorsemVinculo->eso24_avaliacaogruporesposta = $this->oAvaliacao->getAvaliacaoGrupo();
        $oDaoAvaliacaoGrupoRespostaTrawbalhadorsemVinculo->eso24_rhpessoal = $this->getServidor()->getMatricula();
        $oDaoAvaliacaoGrupoRespostaTrawbalhadorsemVinculo->eso24_cgmempregador = $this->getCgm()->getCodigo();
        $oDaoAvaliacaoGrupoRespostaTrawbalhadorsemVinculo->eso24_codigorescisao = $matricula . $anoFolha . $mesFolha;
        $oDaoAvaliacaoGrupoRespostaTrawbalhadorsemVinculo->eso24_avaliacao = $this->oAvaliacao->getCodigo();

        $oDaoAvaliacaoGrupoRespostaTrawbalhadorsemVinculo->incluir(null);

        if ($oDaoAvaliacaoGrupoRespostaTrawbalhadorsemVinculo->erro_status == "0") {
            throw new DBException("Ocorreu um erro ao vincular o matrícula ao questionário\n\n" . $oDaoAvaliacaoGrupoRespostaTrawbalhadorsemVinculo->erro_sql . PHP_EOL . pg_last_error());
        }
    }

    private function persitirDesligamentoServidor()
    {
        if (empty($this->oServidor)) {
            return;
        }

        foreach ($this->perguntasParaExcluirRespostas as $codigoPergunta) {

            $where = "eso15_regist = {$this->getServidor()->getMatricula()} and db103_sequencial = {$codigoPergunta}";
            $this->excluirRespostas("avaliacaogruporespostarhpesrescisao", "eso15_avaliacaogruporesposta", $where);
        }

        /**
         * Vincula as matriculas as repostas
         */
        $anoFolha = $this->getServidor()->getAnoCompetencia();
        $mesFolha = $this->getServidor()->getMesCompetencia();
        $matricula = $this->getServidor()->getMatricula();

        $oDaoAvaliacaoGrupoRespostaRhPesRescisao = new cl_avaliacaogruporespostarhpesrescisao();
        $oDaoAvaliacaoGrupoRespostaRhPesRescisao->eso15_avaliacaogruporesposta = $this->oAvaliacao->getAvaliacaoGrupo();
        $oDaoAvaliacaoGrupoRespostaRhPesRescisao->eso15_regist = $this->getServidor()->getMatricula();
        $oDaoAvaliacaoGrupoRespostaRhPesRescisao->eso15_cgmempregador = $this->getCgm()->getCodigo();
        $oDaoAvaliacaoGrupoRespostaRhPesRescisao->eso15_codigorescisao = $matricula . $anoFolha . $mesFolha;
        $oDaoAvaliacaoGrupoRespostaRhPesRescisao->eso15_avaliacao = $this->oAvaliacao->getCodigo();

        $oDaoAvaliacaoGrupoRespostaRhPesRescisao->incluir(null);

        if ($oDaoAvaliacaoGrupoRespostaRhPesRescisao->erro_status == "0") {
            throw new DBException("Ocorreu um erro ao vincular a matrícula ao questionário\n\n" . $oDaoAvaliacaoGrupoRespostaRhPesRescisao->erro_sql . PHP_EOL . pg_last_error());
        }
    }

    private function persistirDadosAdmissaoPreliminar($aParametros)
    {
        if (empty($aParametros['cpfTrabalhador'])) {
            throw new Exception("CPF do trabalhador é obrigatório.");
        }

        if (empty($aParametros['matricula'])) {
            throw new Exception("Matricula do trabalhador é obrigatório.");
        }

        $daoAdmissaoPreliminar = new cl_avaliacaogruporespostaadmissaopreliminar();

        $cgm = $this->getCgm()->getCodigo();
        $cpf = str_replace(array(".", "-"), "", $aParametros['cpfTrabalhador']);

        $daoAdmissaoPreliminar->excluir(null, "eso18_cgm = {$cgm} AND eso18_cpf = '{$cpf}'");

        if ($daoAdmissaoPreliminar->erro_status == "0") {
            throw new DBException("Ocorreu um erro ao tentar excluir as respostas anteriores para esse formulário.\nContate o suporte.");
        }

        $daoAdmissaoPreliminar->eso18_sequencial = null;
        $daoAdmissaoPreliminar->eso18_cgm = $cgm;
        $daoAdmissaoPreliminar->eso18_avaliacaogruporesposta = $this->oAvaliacao->getAvaliacaoGrupo();
        $daoAdmissaoPreliminar->eso18_cpf = $cpf;
        $daoAdmissaoPreliminar->eso18_regist = $aParametros['matricula'];

        $daoAdmissaoPreliminar->incluir(null);

        if ($daoAdmissaoPreliminar->erro_status == "0") {
            throw new DBException("Ocorreu um erro ao tentar incluir as respostas para esse formulário.\nContate o suporte.");
        }
    }

    private function persistirAlteracaoCadastral($parametros)
    {
        if (empty($parametros['matricula'])) {
            throw new Exception("Matrícula é de preenchimento obrigatório.");
        }

        $daoAlteracaoCadastral = new cl_avaliacaogruporespostaaltercontratual();

        $cgm = $this->getCgm()->getCodigo();
        $matricula = $parametros['matricula'];

        $daoAlteracaoCadastral->excluir(null, "eso20_cgm = {$cgm} AND eso20_rhpessoal = {$matricula}");

        if ($daoAlteracaoCadastral->erro_status == "0") {
            throw new DBException("Ocorreu um erro ao tentar excluir as respostas anteriores para esse formulário.\nContate o suporte.");
        }

        $daoAlteracaoCadastral->eso20_sequencial = null;
        $daoAlteracaoCadastral->eso20_cgm = $cgm;
        $daoAlteracaoCadastral->eso20_avaliacaogruporesposta = $this->oAvaliacao->getAvaliacaoGrupo();
        $daoAlteracaoCadastral->eso20_rhpessoal = $matricula;

        $daoAlteracaoCadastral->incluir(null);

        if ($daoAlteracaoCadastral->erro_status == "0") {
            throw new DBException("Ocorreu um erro ao tentar incluir as respostas para esse formulário.\nContate o suporte.");
        }
    }

    /**
     * Vincula os dados os dados de alteração de contrato do TSVE
     * @param $parametros
     * @return bool
     * @throws DBException
     */
    private function persistirAlteracaoContratoTSVE($parametros)
    {

        $daoTsve = new cl_avaliacaogruporespostatsvealteracao();
        $daoTsve->eso23_sequencial = null;
        $daoTsve->eso23_rhpessoal = $parametros['matricula'];
        $daoTsve->eso23_avaliacaogruporesposta = $this->oAvaliacao->getAvaliacaoGrupo();
        $daoTsve->incluir(null);
        if ($daoTsve->erro_status == "0") {
            throw new DBException("Ocorreu um erro ao vincular a resposta do usuário com os formulários.");
        }
        return true;
    }

    /**
     * @param $parametros
     * @return bool
     * @throws DBException
     */
    private function persistirRemuneracaoRGPS($parametros)
    {
        if (empty($parametros['cgm'])) {
            return false;
        }

        $daoRemuneracaoRGPS = new cl_avaliacaogruporespostaremuneracaorgps();
        $whereRemuneracaoRGPS = array(
            "eso28_cgm = {$parametros['cgm']}",
            "eso28_ano = {$parametros['ano']}",
            "eso28_mes = {$parametros['mes']}"
        );
        $sqlRemuneracaoRGPS = $daoRemuneracaoRGPS->sql_query_file(null, 'eso28_sequencial', null,
            implode(' AND ', $whereRemuneracaoRGPS));
        $rsRemuneracaoRGPS = db_query($sqlRemuneracaoRGPS);

        if (!$rsRemuneracaoRGPS) {
            throw new DBException("Erro ao validar a existência de avaliação para o CGM na competência.");
        }

        if (pg_num_rows($rsRemuneracaoRGPS)) {
            return false;
        }

        $daoRemuneracaoRGPS->eso28_avaliacaogruporesposta = $this->oAvaliacao->getAvaliacaoGrupo();
        $daoRemuneracaoRGPS->eso28_cgm = $parametros['cgm'];
        $daoRemuneracaoRGPS->eso28_ano = $parametros['ano'];
        $daoRemuneracaoRGPS->eso28_mes = $parametros['mes'];
        $daoRemuneracaoRGPS->incluir(null);

        if ($daoRemuneracaoRGPS->erro_status == '0') {
            throw new DBException('Erro ao salvar o vínculo da avaliação do CGM na competência.');
        }

        return true;
    }
}
