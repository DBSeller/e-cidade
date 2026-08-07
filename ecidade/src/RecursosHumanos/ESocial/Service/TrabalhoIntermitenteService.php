<?php

namespace ECidade\RecursosHumanos\ESocial\Service;

use Avaliacao;
use CgmJuridico;
use CgmRepository;
use cl_avaliacaogruporespostatrabintermitente;
use cl_rhpessoal;
use ECidade\RecursosHumanos\ESocial\Entity\TrabalhoIntermitente;
use ECidade\RecursosHumanos\ESocial\Integracao\ESocial;
use ECidade\RecursosHumanos\ESocial\Integracao\Recurso;
use ECidade\V3\Extension\Registry;
use Exception;
use InstituicaoRepository;
use ParameterException;
use stdClass;
use DBCompetencia;

/**
 * Class TrabalhoIntermitenteService
 * @package ECidade\RecursosHumanos\ESocial\Service
 */
class TrabalhoIntermitenteService
{
    /**
     * @var Avaliacao
     */
    private $avaliacao;
    /**
     * @var array
     */
    private $parametros;
    /**
     * @var TrabalhoIntermitente
     */
    private $eventoTrabalhoIntermitente;

    /**
     * @var CgmJuridico
     */
    private $cgmEmpregador;

    /**
     * @throws Exception
     */
    public function salvar()
    {
        $codHorContrat = array_key_exists('codHorContrat', $this->parametros)
          ? $this->parametros['codHorContrat']
          : null;

        $this->eventoTrabalhoIntermitente = new TrabalhoIntermitente();
        $this->eventoTrabalhoIntermitente->setMatricula($this->parametros['matricula']);
        $this->eventoTrabalhoIntermitente->setCodConv($this->parametros['codConv']);
        $this->eventoTrabalhoIntermitente->setCodHorContrat($codHorContrat);
        $this->cgmEmpregador = CgmRepository::getByCodigo($this->parametros['empregador']);

        $this->validarSeServidorExiste();
        $this->validarEventoAdmissao();
        $this->validarTabelaHoraContratual();
        $this->persistir();
    }

    /**
     * @throws \BusinessException
     * @throws \DBException
     */
    private function validarSeServidorExiste()
    {
        $dao = new cl_rhpessoal();
        $sql = $dao->sql_query_file($this->eventoTrabalhoIntermitente->getMatricula());
        $rs = db_query($sql);

        if (!$rs) {
            throw new \DBException("Erro ao buscar o servidor.");
        }

        if (pg_num_rows($rs) == 0) {
            throw new \BusinessException("Não foi possível salvar o formulário. Nenhum servidor encontrado para a matrícula informada.");
        }
    }

    private function validarEventoAdmissao()
    {
        $requisicao = new stdClass();
        $requisicao->idEvento = 'S-2200';
        $requisicao->idReferencia = $this->eventoTrabalhoIntermitente->getMatricula();
        $requisicao->inscricaoEmpregador = $this->cgmEmpregador->getCnpj();

        $resposta = $this->request('GET', Recurso::CONSULTA_RECIBO, $requisicao);

        if (empty($resposta)) {
            $mensagem = "Não foi encontrado recibo do evento S-2200 (Cadastramento Inicial do Vínculo";
            $mensagem .= " e Admissão/Ingresso de Trabalhador) para a matrícula";
            $mensagem .= " {$this->eventoTrabalhoIntermitente->getMatricula()}.";

            throw new Exception($mensagem);
        }
    }

    /**
     * @param $method
     * @param $url
     * @param $body
     * @return null|stdClass
     * @throws ParameterException
     */
    private function request($method, $url, $body)
    {
        if (empty($url)) {
            throw new ParameterException('Recurso para requisição da API não informado.');
        }

        $service = new ESocial(Registry::get('app.config'), $url);
        $service->setDados($body);

        return $service->request($method);
    }

    /**
     * @throws Exception
     */
    private function validarTabelaHoraContratual()
    {
        if ($codHorContrat = $this->eventoTrabalhoIntermitente->getCodHorContrat()) {
            $requisicao = new stdClass();
            $requisicao->idEvento = 'S-1050';
            $requisicao->idReferencia = $codHorContrat;
            $requisicao->inscricaoEmpregador = $this->cgmEmpregador->getCnpj();

            $resposta = $this->request('GET', Recurso::CONSULTA_RECIBO, $requisicao);

            if (empty($resposta)) {
                $mensagem = "Não foi encontrado recibo do evento S-1050 (Tabela de Horários/Turnos de Trabalho)";
                $mensagem .= " para o código {$codHorContrat}.";

                throw new Exception($mensagem);
            }
        }
    }

    /**
     * @throws Exception
     */
    private function persistir()
    {
        $dao = new cl_avaliacaogruporespostatrabintermitente();
        $where = "eso19_codigoconvocacao = '{$this->eventoTrabalhoIntermitente->getCodConv()}'";
        $where .= " AND eso19_cgm = {$this->parametros['empregador']}";
        $sql = $dao->sql_query_file(null, '*', null, $where);
        $resultado = db_query($sql);

        if (!$resultado) {
            throw new Exception("Não foi possível salvar o preenchimento do formulário. Verifique os valores informados. Se o problema persistir, contate o suporte.");
        }

        $dao->eso19_matricula = $this->eventoTrabalhoIntermitente->getMatricula();
        $dao->eso19_codigoconvocacao = $this->eventoTrabalhoIntermitente->getCodConv();
        $dao->eso19_cgm = $this->parametros['empregador'];

        if (pg_num_rows($resultado) > 0) {
            $preenchimento = pg_fetch_object($resultado);
            $dao->eso19_sequencial = $preenchimento->eso19_sequencial;
            $dao->eso19_avaliacaogruporesposta = $preenchimento->eso19_avaliacaogruporesposta;
            $dao->alterar($dao->eso19_sequencial);
        } else {
            $dao->eso19_avaliacaogruporesposta = $this->avaliacao->getAvaliacaoGrupo();
            $dao->incluir(null);
        }

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar o preenchimento do formulário. Verifique os valores informados. Se o problema persistir, contate o suporte.");
        }
    }

    public function setAvaliacao($avaliacao)
    {
        $this->avaliacao = $avaliacao;
    }

    public function setParametros($parametros)
    {
        $this->parametros = $parametros;
    }

    /**
     * @param $matricula
     * @param $ano
     * @param $mes
     * @return array
     * @throws ParameterException
     */
    public function buscarDadosApiPorMatricula($matricula, $ano, $mes)
    {

        $competencia = new DBCompetencia($ano, $mes);
        $requisicao = new stdClass();
        $requisicao->matricula = $matricula;
        $requisicao->dtInicio = $competencia->getDataDeInicio()->getDate();
        $requisicao->dtFim = $competencia->getDataDeTermino()->getDate();
        $requisicao->inscricaoEmpregador = InstituicaoRepository::getInstituicaoSessao()->getCNPJ();

        $respostas = $this->request('GET', Recurso::CONSULTA_TRABALHADOR_INTERMITENTE, $requisicao);
        $dadosTrabalhadorIntermitente = array();

        foreach ($respostas as $resposta) {
            $eventoTrabalhoIntermitente = new TrabalhoIntermitente();
            $eventoTrabalhoIntermitente->setCodConv($resposta->referencia);
            $dadosTrabalhadorIntermitente[$resposta->referencia] = $eventoTrabalhoIntermitente;
        }

        return $dadosTrabalhadorIntermitente;
    }
}