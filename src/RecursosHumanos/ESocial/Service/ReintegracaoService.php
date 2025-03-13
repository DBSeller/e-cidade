<?php

namespace ECidade\RecursosHumanos\ESocial\Service;

use Avaliacao;
use CgmJuridico;
use CgmRepository;
use cl_avaliacaogruporespostareintegracao;
use cl_rhpessoal;
use ECidade\RecursosHumanos\ESocial\Entity\Reintegracao;
use ECidade\RecursosHumanos\ESocial\Integracao\ESocial;
use ECidade\RecursosHumanos\ESocial\Integracao\Recurso;
use ECidade\V3\Extension\Registry;
use Exception;
use ParameterException;
use stdClass;

/**
 * Class ReintegracaoService
 * @package ECidade\RecursosHumanos\ESocial\Service
 */
class ReintegracaoService
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
     * @var Reintegracao
     */
    private $eventoReintegracao;

    /**
     * @var CgmJuridico
     */
    private $cgmEmpregador;

    /**
     * @throws Exception
     */
    public function salvar()
    {
        $this->eventoReintegracao = new Reintegracao();
        $this->eventoReintegracao->setMatricula($this->parametros['matricula']);
        $this->cgmEmpregador = CgmRepository::getByCodigo($this->parametros['empregador']);

        $this->validarSeServidorExiste();
        $this->validarEventoAdmissao();
        $this->persistir();
    }

    public function setAvaliacao($avaliacao)
    {
        $this->avaliacao = $avaliacao;
    }

    public function setParametros($parametros)
    {
        $this->parametros = $parametros;
    }

    private function validarEventoAdmissao()
    {
        $requisicao = new stdClass();
        $requisicao->idEvento = 'S-2200';
        $requisicao->idReferencia = $this->eventoReintegracao->getMatricula();
        $requisicao->inscricaoEmpregador = $this->cgmEmpregador->getCnpj();

        $resposta = $this->request('GET', Recurso::CONSULTA_RECIBO, $requisicao);

        if (empty($resposta)) {
            $mensagem = "Não foi encontrado recibo do evento S-2200 (Cadastramento Inicial do Vínculo";
            $mensagem .= " e Admissão/Ingresso de Trabalhador) para a matrícula";
            $mensagem .= " {$this->eventoReintegracao->getMatricula()}.";

            throw new Exception($mensagem);
        }
    }

    /**
     * @throws Exception
     */
    private function persistir()
    {
        $dao = new cl_avaliacaogruporespostareintegracao();
        $where = "eso21_matricula = '{$this->eventoReintegracao->getMatricula()}'";
        $where .= " AND eso21_cgm = {$this->parametros['empregador']}";
        $sql = $dao->sql_query_file(null, '*', null, $where);
        $resultado = db_query($sql);

        if (!$resultado) {
            throw new Exception("Não foi possível salvar o preenchimento do formulário. Verifique os valores informados. Se o problema persistir, contate o suporte.");
        }

        $dao->eso21_matricula = $this->eventoReintegracao->getMatricula();
        $dao->eso21_cgm = $this->parametros['empregador'];

        if (pg_num_rows($resultado) > 0) {
            $preenchimento = pg_fetch_object($resultado);
            $dao->eso21_sequencial = $preenchimento->eso21_sequencial;
            $dao->eso21_avaliacaogruporesposta = $preenchimento->eso21_avaliacaogruporesposta;
            $dao->alterar($dao->eso21_sequencial);
        } else {
            $dao->eso21_avaliacaogruporesposta = $this->avaliacao->getAvaliacaoGrupo();
            $dao->incluir(null);
        }

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar o preenchimento do formulário. Verifique os valores informados. Se o problema persistir, contate o suporte.");
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
     * @throws \BusinessException
     * @throws \DBException
     */
    private function validarSeServidorExiste()
    {
        $dao = new cl_rhpessoal();
        $sql = $dao->sql_query_file($this->eventoReintegracao->getMatricula());
        $rs = db_query($sql);

        if (!$rs) {
            throw new \DBException("Erro ao buscar o servidor.");
        }

        if (pg_num_rows($rs) == 0) {
            throw new \BusinessException("Não foi possível salvar o formulário. Nenhum servidor encontrado para a matrícula informada.");
        }
    }
}
