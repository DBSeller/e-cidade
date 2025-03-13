<?php

namespace ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\ArquivoExterno;

/**
 * Class Importacao
 * @package ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\ArquivoExterno
 */
class Importacao
{


    /**
     * @var \DBCompetencia
     */
    private $competencia;

    /**
     * @var string
     */
    protected $arquivo;

    /**
     * @var integer
     */
    private $codigoTribunal;

    const CODIGO_MSC = 999999;

    /**
     * Importacao constructor.
     * @param \DBCompetencia $competencia
     */
    public function __construct(\DBCompetencia $competencia = null)
    {
        $this->competencia = $competencia;
    }

    /**
     * @param $arquivo
     * @throws \Exception
     */
    public function importar($arquivo)
    {
        $arquivo = $this->parse($arquivo);
        $this->salvar($arquivo);
    }

    /**
     * @param $arquivo
     * @return string
     * @throws \Exception
     */
    public function parse($arquivo)
    {
        $file = file($arquivo);
        if (empty($file)) {
            throw new \Exception("Arquivo {$arquivo} não é um arquivo válido da MSC.");
        }

        $tamanhaoColunasPadrao = 16;
        if ($this->competencia->getAno() <= 2021) {
            $tamanhaoColunasPadrao = 18;
        }
        $aColunasCabecalhoMSC = explode(';', $file[1]);
        if (count($aColunasCabecalhoMSC) != $tamanhaoColunasPadrao) {
            throw new \Exception("Arquivo {$arquivo} não é um arquivo válido da MSC. Header inválido");
        }
        array_splice($file, 0, 2);
        return implode($file);
    }

    public function setCodigoTribunal($codigoTribunal)
    {
        $this->codigoTribunal = $codigoTribunal;
    }

    public function getCodigoTribunal()
    {
        return $this->codigoTribunal;
    }

    /**
     * @param $arquivo
     * @throws \BusinessException
     */
    protected function salvar($arquivo)
    {

        $daoConarquivospad = new \cl_conarquivospad();
        $nomeArquivo = "MSC_EXTERNO_{$this->competencia->getMes()}_{$this->competencia->getAno()}";
        $where = array("c54_nomearq =  '{$nomeArquivo}'");
        $where[] = " c54_codtrib = " . $this->getCodigoTribunal();
        $daoConarquivospad->excluir(null, implode(" and ", $where));
        if ($daoConarquivospad->erro_status == 0) {
            throw new \BusinessException("Erro ao salvar dados do arquivo");
        }

        $daoConarquivospad->c54_codtrib = $this->getCodigoTribunal();
        $daoConarquivospad->c54_arquivo = $arquivo;
        $daoConarquivospad->c54_nomearq = $nomeArquivo;
        $daoConarquivospad->c54_anousu = $this->competencia->getAno();
        $daoConarquivospad->incluir(null);
        if ($daoConarquivospad->erro_status == 0) {
            throw new \BusinessException("Erro ao salvar dados do arquivo");
        }
    }

    /**
     * @return \stdClass[]
     */
    public function getArquivos()
    {
        $daoConarquivospad = new \cl_conarquivospad();
        $campos = "c54_codarq as codigo, c54_nomearq as nome, c54_codtrib as codtrib";
        $sWhere = "c54_nomearq ilike 'MSC_EXTERNO_%' ";
        $sqlArquivos = $daoConarquivospad->sql_query_file(
            null,
            "$campos",
            'c54_nomearq',
            $sWhere
        );
        $rsArquivos = db_query($sqlArquivos);
        $arquivos = \db_utils::makeCollectionFromRecord($rsArquivos, function ($arquivo) {
            $partesNomeArquivo = explode("_", $arquivo->nome);
            $arquivo->competencia = $partesNomeArquivo[2] . "/" . $partesNomeArquivo[3];
            return $arquivo;
        });
        return $arquivos;
    }

    /**
     * @param $codigo
     * @throws \BusinessException
     */
    public function remover($codigo)
    {
        $daoConarquivospad = new \cl_conarquivospad();
        $daoConarquivospad->excluir($codigo);
        if ($daoConarquivospad->erro_status == 0) {
            throw new \BusinessException("Erro ao salvar dados do arquivo");
        }
    }

    /**
     * @param $ano
     * @param $mes
     * @return array|\stdClass[]
     * @throws \Exception
     */
    public static function getArquivosPorCompetencia($ano, $mes)
    {

        $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
        $daoConarquivospad = new \cl_conarquivospad();
        $nomeArquivo = "MSC_EXTERNO_{$mes}_{$ano}";
        $where = array("c54_nomearq =  '{$nomeArquivo}'");
        $sCampos = "c54_arquivo as conteudo_arquivo ";
        $sWhere = implode(' and ', $where);
        $sqlBuscaArquivo = $daoConarquivospad->sql_query_file(null, $sCampos, null, $sWhere);
        $rsBuscaArquivo = db_query($sqlBuscaArquivo);
        if (!$rsBuscaArquivo) {
            throw new \Exception("Erro ao consultar o arquivo externo.");
        }

        if (pg_num_rows($rsBuscaArquivo) > 0) {
            return \db_utils::getCollectionByRecord($rsBuscaArquivo);
        }
        return array();
    }
}
