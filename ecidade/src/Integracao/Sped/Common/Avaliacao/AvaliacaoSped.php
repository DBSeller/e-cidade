<?php

namespace ECidade\Integracao\Sped\Common\Avaliacao;

use Avaliacao;
use AvaliacaoResposta;
use AvaliacaoRespostaRepository;
use CgmBase;
use cl_avaliacaoperguntaopcao;
use DateTime;
use ECidade\Integracao\Sped\Common\Evento\EventoFactory;
use Exception;

/**
 * Class AvaliacaoSped
 * @package ECidade\Integracao\Sped\Common\Avaliacao
 */
class AvaliacaoSped
{
    /**
     * @var CgmBase
     */
    protected $cgm;
    /**
     * @var Avaliacao
     */
    protected $avaliacao;
    /**
     * @var array
     */
    protected $perguntasParaExcluirRespostas = array();
    /**
     * @var int
     */
    protected $preenchimento;
    /**
     * @var int
     */
    protected $codigoGrupoPerguntas;

    /**
     * AvaliacaoSped constructor.
     * @param Avaliacao $avaliacao
     */
    public function __construct(Avaliacao $avaliacao)
    {
        $this->avaliacao = $avaliacao;
    }

    /**
     * @param $perguntasRespostas
     * @param null $tipoFormulario
     * @param array $parametros
     * @return int
     * @throws Exception
     */
    public function salvar($perguntasRespostas, $tipoFormulario = null, array $parametros = array())
    {
        $perguntasAvaliacao = $this->avaliacao->getPerguntas($this->getCodigoGrupoPerguntas());
        $perguntasRespondidas = array();

        if (is_object($perguntasRespostas->grupos)) {
            $perguntasRespostas->grupos = array($perguntasRespostas->grupos);
        }

        foreach ($perguntasRespostas->grupos as $chave => $grupoResposta) {
            foreach ($grupoResposta->perguntas as $pergunta) {
                $perguntasRespondidas[$pergunta->codigo] = $pergunta->respostas;
            }
        }

        foreach ($perguntasAvaliacao as $pergunta) {
            $avaliacaoResposta = new AvaliacaoResposta();
            $avaliacaoResposta->setPergunta($pergunta);

            AvaliacaoRespostaRepository::delete($avaliacaoResposta);

            $pergunta->getRespostas();

            if (isset($perguntasRespondidas[$pergunta->getCodigo()])) {
                if ($pergunta->getTipo() == 1) {
                    $perguntaRespondida = false;

                    foreach ($perguntasRespondidas[$pergunta->getCodigo()] as $respostaObjetiva) {
                        if ((bool)$respostaObjetiva->valor === false) {
                            continue;
                        }

                        $perguntaRespondida = true;
                    }

                    if (!$perguntaRespondida) {
                        unset($perguntasRespondidas[$pergunta->getCodigo()]);

                        $this->perguntasParaExcluirRespostas[] = $pergunta->getCodigo();

                        continue;
                    }
                }

                foreach ($perguntasRespondidas[$pergunta->getCodigo()] as $respostaSalvar) {
                    if (in_array($pergunta->getTipo(), array(1, 3)) && (bool)$respostaSalvar->valor === false) {
                        continue;
                    }

                    if ($pergunta->getTipoComponente() == 5 && !empty($respostaSalvar->valor)) {
                        $dateTime = new DateTime($respostaSalvar->valor);
                        $respostaSalvar->valor = $dateTime->format('Y-m-d');
                    }

                    if (empty($respostaSalvar->valorAuxiliar)) {
                        $textoResposta = in_array($pergunta->getTipo(), array(1, 3))
                            ? $respostaSalvar->codigo
                            : $respostaSalvar->valor;
                    } else {
                        $textoResposta = $respostaSalvar->valorAuxiliar;
                    }

                    $avaliacaoResposta->setPerguntaOpcao($respostaSalvar->codigo);
                    $avaliacaoResposta->setResposta($textoResposta);

                    AvaliacaoRespostaRepository::persist($avaliacaoResposta);
                }
            }
        }

        $evento = EventoFactory::getInstance($tipoFormulario);
        $evento->setCgm($this->cgm);

        $preenchimento = $evento->salvar(
            $this->avaliacao,
            $parametros,
            $this->perguntasParaExcluirRespostas,
            $this->preenchimento
        );

        return $preenchimento ?: null;
    }

    /**
     * @return CgmBase
     */
    public function getCgm()
    {
        return $this->cgm;
    }

    /**
     * @param CgmBase $cgm
     */
    public function setCgm(CgmBase $cgm)
    {
        $this->cgm = $cgm;
    }

    /**
     * @return int
     */
    public function getPreenchimento()
    {
        return $this->preenchimento;
    }

    /**
     * @param int $preenchimento
     */
    public function setPreenchimento($preenchimento)
    {
        $this->preenchimento = $preenchimento;
    }

    /**
     * @return int
     */
    public function getCodigoGrupoPerguntas()
    {
        return $this->codigoGrupoPerguntas;
    }

    /**
     * @param int $codigoGrupoPerguntas
     */
    public function setCodigoGrupoPerguntas($codigoGrupoPerguntas)
    {
        $this->codigoGrupoPerguntas = $codigoGrupoPerguntas;
    }

    public static function getValorRespostaByCodigoPergunta($id)
    {
        $dao = new cl_avaliacaoperguntaopcao();
        $rs = db_query($dao->sql_query_file($id, 'db104_valorresposta'));

        if (!$rs) {
            throw new Exception("Erro ao buscar valor da resposta da opção.");
        }

        if (pg_num_rows($rs) === 0) {
            throw new Exception("Opção {$id} não existe.");
        }

        return \db_utils::fieldsMemory($rs, 0)->db104_valorresposta;
    }
}