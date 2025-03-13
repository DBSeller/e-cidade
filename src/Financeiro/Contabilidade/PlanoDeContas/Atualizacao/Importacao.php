<?php
namespace ECidade\Financeiro\Contabilidade\PlanoDeContas\Atualizacao;

use PhpOffice\PhpWord\Exception\Exception;

/**
 * Class Importacao
 * @package ECidade\Financeiro\Contabilidade\PlanoDeContas\Atualizacao
 */
class Importacao
{

    /**
     * @var integer
     */
    private $codigo;

    /**
     * @var integer
     */
    protected $modeloPlanoConta;

    /**
     * @var \DBDate
     */
    protected $dataImportacao;

    /**
     * Importacao constructor.
     * @param integer $modeloImportacao
     */
    public function __construct($modeloImportacao)
    {
        $this->modeloPlanoConta = $modeloImportacao;
    }

    /**
     * @param \DBDate $dataImportacao
     */
    public function setDataImportacao(\DBDate $dataImportacao)
    {
        $this->dataImportacao = $dataImportacao;
    }

    /**
     * @param $codigo
     */
    protected function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return int
     */
    public function getModelo()
    {
        return $this->modeloPlanoConta;
    }

    /**
     * @return bool
     * @throws \DBException|\ParameterException
     */
    public function salvar()
    {
        if (empty($this->dataImportacao)) {
            throw new \ParameterException("Data de Importação não informada.");
        }

        $daoImportacao = new \cl_importacaoplanoconta();
        $daoImportacao->c96_sequencial = null;
        $daoImportacao->c96_modeloplanoconta = $this->modeloPlanoConta;
        $daoImportacao->c96_data = $this->dataImportacao->getDate(\DBDate::DATA_EN);
        $daoImportacao->incluir(null);
        if ($daoImportacao->erro_status === '0') {
            throw new \DBException("Ocorreu um erro ao salvar as informações de importação.");
        }
        return true;
    }

    /**
     * @param integer $exercicio
     *
     * @return mixed
     */
    public function getUltimaImportacao($exercicio)
    {
        $daoImportacao = new \cl_importacaoplanoconta();

        $sSql = $daoImportacao->sql_query(null, null, 'c96_sequencial DESC LIMIT 1', 'c94_exercicio = ' . $exercicio . ' AND c94_sequencial = ' . $this->modeloPlanoConta, null);

        $result = $daoImportacao->sql_record($sSql);

        if (!$daoImportacao->numrows) {
            return false;
        }

        $ultimaImportacao = \db_utils::makeFromRecord($result, function($item) {
            return $item;
        });

        return $ultimaImportacao;
    }

    /**
     * @param $codigoImportacao
     * @return Importacao
     * @throws \DBException
     */
    public static function get($codigoImportacao)
    {
        $daoImportacao   = new \cl_importacaoplanoconta();
        $buscaImportacao = $daoImportacao->sql_query_file($codigoImportacao);
        $resImportacao   = db_query($buscaImportacao);
        if (!$resImportacao) {
            throw new \DBException("Ocorreu um erro ao consultar a importação realizada.");
        }
        $stdImportacao = \db_utils::fieldsMemory($resImportacao, 0);
        $importacao = new Importacao($stdImportacao->c96_modeloplanoconta);
        $importacao->setDataImportacao(new \DBDate($stdImportacao->c96_data));
        $importacao->setCodigo($stdImportacao->c96_sequencial);
        return $importacao;

    }
}
