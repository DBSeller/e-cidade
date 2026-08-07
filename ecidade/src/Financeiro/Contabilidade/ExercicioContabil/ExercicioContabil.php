<?php
namespace ECidade\Financeiro\Contabilidade\ExercicioContabil;

use ECidade\V3\Extension\Logger;

/**
 * Class ExercicioContabil
 *
 * @package ECidade\Financeiro\Contabilidade\ExercicioContabil
 */
abstract class ExercicioContabil
{

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var string
     */
    protected $nomeLog;

    /**
     * @var integer
     */
    protected $ano;

    /**
     * @var \DBDate
     */
    protected $data;

    /**
     * @var \Instituicao
     */
    protected $instituicao;


    /**
     * @var array
     */
    protected $mensagensLog;
    /**
     * ExercicioContabil constructor.
     * @param $ano
     * @param \DBDate $data
     * @param \Instituicao $instituicao
     */
    public function __construct($ano, \DBDate $data, \Instituicao $instituicao)
    {
        $this->ano = $ano;
        $this->data = $data;
        $this->instituicao = $instituicao;
        $this->logger = new Logger("tmp/{$this->nomeLog}", Logger::DEBUG_5);
    }

    /**
     * @return mixed
     */
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * @param mixed $ano
     */
    public function setAno($ano)
    {
        $this->ano = $ano;
    }

    /**
     * @return \DBDate
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param \DBDate $data
     */
    public function setData($data)
    {
        $this->data = $data;
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
     * Retorna os registros que devem ser executados no encerramento
     * @param $documento
     * @return string
     */
    protected function getConsultaLancamentosDoDocumento($documento)
    {
        $sqlRegra = "select c92_regra
                       from contabilidade.conhistdocregra
                      where c92_conhistdoc = {$documento}
                        and c92_anousu = {$this->getAno()}";

        $rsRegra = db_query($sqlRegra);
        if (pg_num_rows($rsRegra) == 0) {
            return '';
        }
        $sqlDoDocumento = \db_utils::fieldsMemory($rsRegra, 0)->c92_regra;
        return $sqlDoDocumento;
    }

    /**
     * Realiza o cancelamento do exercicio contabil.
     * consiste em excluir os dados o Encerramento Contábil
     */
    public function cancelar()
    {
        foreach ($this->getDocumentosParaProcessamento() as $codigoDocumento => $tipo) {
            $this->cancelarDocumento($codigoDocumento);
        }
    }

    /**
     * @return array
     */
    abstract protected function getDocumentosParaProcessamento();

    /**
     * @param integer $codigoDocumento
     * @return mixed
     */
    abstract protected function cancelarDocumento($codigoDocumento);
}
