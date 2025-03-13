<?php

namespace ECidade\Integracao\Sped\Common\Evento;

use Avaliacao;
use AvaliacaoPergunta;
use CgmBase;
use db_utils;
use DBException;
use DBFormulaCGM;
use Exception;
use stdClass;

/**
 * Class EventoAbstract
 * @package ECidade\Integracao\Sped\Common\Evento
 */
abstract class EventoAbstract
{
    /**
     * @var CgmBase
     */
    protected $cgm;

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
     * @param AvaliacaoPergunta $avaliacaoPergunta
     * @param $codigoGrupoResposta
     * @param $campos
     * @return stdClass[]
     * @throws Exception
     */
    public function getRespostasPergunta(
        AvaliacaoPergunta $avaliacaoPergunta,
        $codigoGrupoResposta = null,
        $campos = null
    ) {
        if ($codigoGrupoResposta) {
            $where = array(
                "db103_sequencial = {$avaliacaoPergunta->getCodigo()}",
                "db107_sequencial = {$codigoGrupoResposta}"
            );

            $where = implode(' AND ', $where);

            $sql = "
            SELECT DISTINCT {$campos}
            FROM avaliacaogruporesposta
                   INNER JOIN avaliacaogrupoperguntaresposta ON db108_avaliacaogruporesposta = db107_sequencial
                   INNER JOIN avaliacaoresposta ON db106_sequencial = db108_avaliacaoresposta
                   INNER JOIN avaliacaoperguntaopcao ON db104_sequencial = db106_avaliacaoperguntaopcao
                   INNER JOIN avaliacaopergunta ON db103_sequencial = db104_avaliacaopergunta
                   INNER JOIN avaliacaogrupopergunta ON db103_avaliacaogrupopergunta = db102_sequencial
                   INNER JOIN avaliacao ON db102_avaliacao = db101_sequencial
            WHERE {$where}
        ";

            $rs = db_query($sql);

            if (!$rs) {
                throw new Exception('Não foi possível buscar as informações de contribuinte.');
            }

            if (pg_num_rows($rs)) {
                return db_utils::makeCollectionFromRecord($rs, function ($resposta) {
                    $oStdResposta = new stdClass();
                    $oStdResposta->codigoresposta = $resposta->db106_avaliacaoperguntaopcao;
                    $oStdResposta->textoresposta = $resposta->db106_resposta;

                    return $oStdResposta;
                });
            }
        }

        return array();
    }

    /**
     * @param stdClass $parametros
     */
    public abstract function buscarCodigoPreenchimento(stdClass $parametros);

    /**
     * @param Avaliacao $avaliacao
     * @param array $parametros
     * @param array $codigosPerguntasRespostas
     * @param null $preenchimento
     * @return int
     * @throws Exception
     */
    public final function salvar(
        Avaliacao $avaliacao,
        array $parametros = array(),
        $codigosPerguntasRespostas = array(),
        $preenchimento = null
    ) {
        $this->excluirRespostasAnteriores($codigosPerguntasRespostas, $preenchimento);
        return $this->persistir($avaliacao, $parametros);
    }

    /**
     * @param Avaliacao $avaliacao
     * @param array $parametros
     * @return int
     */
    public abstract function persistir(Avaliacao $avaliacao, array $parametros = array());

    /**
     * @param AvaliacaoPergunta $avaliacaoPergunta
     * @return array
     * @throws Exception
     */
    public function getSugestaoRespostaDaPergunta(AvaliacaoPergunta $avaliacaoPergunta)
    {
        $respostas = array();

        $sqlFormulaPergunta = $avaliacaoPergunta->getFormulaVinculada();

        if (empty($sqlFormulaPergunta)) {
            return $respostas;
        }

        $formulaSugestaoResposta = $this->getContextoFormula();

        if (empty($formulaSugestaoResposta)) {
            return $respostas;
        }

        $sqlFormulaPergunta = "
            SELECT substring(ROW (sugestoes.*)::varchar, '^\\\(\"*(.*?)\"*\\\)$') AS sugestao
            FROM [{$sqlFormulaPergunta}] AS sugestoes
        ";

        $sSqlRespostas = $formulaSugestaoResposta->parse($sqlFormulaPergunta);

        $rsRespostas = db_query($sSqlRespostas);

        if (!$rsRespostas) {
            throw new DBException("Não foi possível executar a fórmula {$sSqlRespostas} para a pergunta {$avaliacaoPergunta->getDescricao()}.");
        }

        while ($resposta = pg_fetch_object($rsRespostas)) {
            switch ($avaliacaoPergunta->getTipo()) {
                case AvaliacaoPergunta::TIPO_RESPOSTA_OBJETIVA:
                case AvaliacaoPergunta::TIPO_RESPOSTA_MULTIPLA:
                    $codigoResposta = $resposta->sugestao;
                    $textoResposta = 1;
                    break;
                case AvaliacaoPergunta::TIPO_RESPOSTA_DISSERTATIVA:
                    $respostasPergunta = $avaliacaoPergunta->getRespostas();
                    $codigoResposta = $respostasPergunta[0]->codigoresposta;
                    $textoResposta = $resposta->sugestao;
                    break;
                default:
                    $codigoResposta = '';
                    $textoResposta = '';
                    break;
            }

            $stdResposta = new stdClass();
            $stdResposta->codigoresposta = $codigoResposta;
            $stdResposta->textoresposta = $textoResposta;
            $respostas[] = $stdResposta;
        }

        return $respostas;
    }

    /**
     * @return DBFormulaCGM|null
     */
    private function getContextoFormula()
    {
        $formulaCgm = $this->getFormulaCgm();

        return $formulaCgm ?: null;
    }

    /**
     * @return DBFormulaCGM|null
     */
    private function getFormulaCgm()
    {
        if ($this->cgm) {
            $formulaCgm = new DBFormulaCGM($this->cgm);
            $formulaCgm->adicionar('CODIGO_CGM', $this->cgm->getCodigo());

            return $formulaCgm;
        }

        return null;
    }

    /**
     * @param array $codigosPerguntas
     * @param null $preenchimento
     * @return bool
     * @throws Exception
     */
    private final function excluirRespostasAnteriores(array $codigosPerguntas = array(), $preenchimento = null)
    {
        if ($preenchimento && $codigosPerguntas) {
            $codigosPerguntas = implode(', ', $codigosPerguntas);

            $sql = "
                SELECT db106_sequencial
                FROM avaliacaogrupoperguntaresposta
                       JOIN avaliacaoresposta
                            ON avaliacaoresposta.db106_sequencial = avaliacaogrupoperguntaresposta.db108_avaliacaoresposta
                       JOIN avaliacaoperguntaopcao
                            ON avaliacaoperguntaopcao.db104_sequencial = avaliacaoresposta.db106_avaliacaoperguntaopcao
                WHERE db108_avaliacaogruporesposta = {$preenchimento}
                  AND db104_avaliacaopergunta IN ({$codigosPerguntas})     
            ";

            $rs = db_query($sql);

            if (pg_num_rows($rs) === 0) {
                return false;
            }

            if (!$rs) {
                throw new Exception('Não foi possível buscar as respostas anteriores do formulário.');
            }

            $codigos = array();

            while ($row = pg_fetch_object($rs)) {
                $codigos[] = $row->db106_sequencial;
            }

            $codigos = implode(', ', $codigos);

            $sql = "
                DELETE
                FROM avaliacaogrupoperguntaresposta
                WHERE db108_avaliacaoresposta IN ({$codigos})           
            ";

            $rs = db_query($sql);

            if (!$rs) {
                throw new DBException('Não foi possível excluir as respostas anteriores do formulário.');
            }

            $sql = "
                DELETE
                FROM avaliacaoresposta
                WHERE db106_sequencial IN ({$codigos})           
            ";

            $rs = db_query($sql);

            if (!$rs) {
                throw new DBException('Não foi possível excluir as respostas anteriores do formulário.');
            }
        }

        return true;
    }
}