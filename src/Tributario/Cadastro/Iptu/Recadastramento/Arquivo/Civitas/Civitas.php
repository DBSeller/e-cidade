<?php

namespace ECidade\Tributario\Cadastro\Iptu\Recadastramento\Arquivo\Civitas;

use ECidade\Tributario\Integracao\Civitas\Model\Situacao;
use ECidade\Tributario\Integracao\Civitas\Logger\RequestLogger;
use \ECidade\Tributario\Cadastro\Iptu\Recadastramento\Processamento;
use ECidade\Tributario\Integracao\Civitas\Parser\ContrucaoParser;
use ECidade\Tributario\Integracao\Civitas\Parser\LoteParser;

/**
 * Repository do importador de arquivos do Civitas
 */
class Civitas
{

    /**
     * @var string $arquivoLote
     */
    private $arquivoLote;

    /**
     * @var string $arquivoConstrucao
     */
    private $arquivoConstrucao;

    /**
     * @var string $nomeImportacao
     */
    private $nomeImportacao;

    /**
     * @var array $aMatriculasImportadas
     */
    private $aMatriculasImportadas = array();

    /**
     * @var bool $importacaoManual
     */
    private $importacaoManual;


    /**
     * __construct
     *
     * Civitas constructor.
     * @param $nomeImportacao
     * @param bool $importacaoManual
     */
    public function __construct($nomeImportacao, $importacaoManual = true)
    {
        $this->nomeImportacao = $nomeImportacao;
        $this->importacaoManual = $importacaoManual;
    }

    /**
     * setArquivoConstrucao
     *
     * @param $arquivo
     */
    public function setArquivoConstrucao($arquivo)
    {
        $this->arquivoConstrucao = $arquivo;
    }

    /**
     * setArquivoLote
     *
     * @param $arquivo
     */
    public function setArquivoLote($arquivo)
    {
        $this->arquivoLote = $arquivo;
    }

    /**
     * Processa  Arquvios importados
     *
     * @throws \DBException
     * @throws \Exception
     */
    public function processar()
    {

        db_query("set search_path=public,{$this->nomeImportacao}");

        if (!empty($this->arquivoLote)) {
            $this->processarLote();
        }

        if (!empty($this->arquivoConstrucao)) {
            $this->processarConstrucao();
        }

        db_query("select fc_set_pg_search_path();");
    }


    /**
     * Busca setores cadastradas na base.
     *
     * @return array
     * @throws \DBException
     */
    private function getSetores()
    {

        $oDaoSetor = new \cl_setor();
        $sSql = $oDaoSetor->sql_query_file(null, 'j30_codi');
        $rsSetor = db_query($sSql);

        if (!$rsSetor || pg_num_rows($rsSetor) == 0) {
            throw new \DBException('Erro ao buscar os setores cadastrados no sistema.');
        }

        $aSetores = \db_utils::makeCollectionFromRecord($rsSetor, function ($oSetor) {
            return $oSetor->j30_codi;
        });

        return $aSetores;
    }

    /**
     * Processa Arquivo de Lote
     *
     * @throws \DBException
     * @throws \Exception
     */
    private function processarLote()
    {

        $oArquivoLote = new \SplFileObject($this->arquivoLote);
        $oArquivoLote->setFlags(\SplFileObject::READ_CSV);
        $oArquivoLote->setCsvControl('|');

        $aSetores = $this->getSetores();

        $aLinhasArquivoLote = new \LimitIterator($oArquivoLote, 1);

        RequestLogger::log(
            RequestLogger::INFO,
            'civitas_carga_processa_lote', $this->nomeImportacao , '' ,  array(
            'linhas' =>  iterator_to_array($aLinhasArquivoLote)
        ));

        foreach ($aLinhasArquivoLote as $aLinha) {

            if (count($aLinha) == 1) {
                continue;
            }

            $oLote = LoteParser::parser($aLinha);

            $matricula   = $oLote->getMatricula();
            $codigoSetor = $oLote->getSetor();

            if (!in_array($codigoSetor, $aSetores)) {
                $sSetor = str_pad($codigoSetor, 4, "0", STR_PAD_LEFT);
                $excecao = new \BusinessException("Erro ao importar arquivo de Lote. \nSetor {$sSetor} informado no arquivo, não existe no sistema.");
                Situacao::lancarExcecao($excecao, $this->importacaoManual);
                continue;
            }

            if (!empty($matricula)) {
                $oMatricula = new \stdClass();
                $oMatricula->iMatricula = $aLinha[1];
                $oMatricula->iStatus = Processamento::MATRICULA_PENDENTE;
                $oMatricula->globalid = $aLinha[11];

                $this->aMatriculasImportadas[$oMatricula->iMatricula] = $oMatricula;
            }

            try {
                $oLote->atualizar();
            } catch (Exception $excecao) {
                Situacao::lancarExcecao($excecao, $this->importacaoManual);
                continue;
            }
        }
    }

    /**
     *  Processa Arquivo de Construcao
     *
     * @throws \Exception
     */
    private function processarConstrucao()
    {
        $oArquivoConstrucao = new \SplFileObject($this->arquivoConstrucao);

        $oArquivoConstrucao->setFlags(\SplFileObject::READ_CSV);
        $oArquivoConstrucao->setCsvControl('|');

        $aLinhasArquivoConstrucao = new \LimitIterator($oArquivoConstrucao, 1);

        RequestLogger::log(
            RequestLogger::INFO,
            'civitas_carga_processa_construcao', $this->nomeImportacao , '' ,  array(
            'linhas' =>  iterator_to_array($aLinhasArquivoConstrucao)
        ));

        foreach ($aLinhasArquivoConstrucao as $key => $aLinha) {

            if (count($aLinha) == 1) {
                continue;
            }

            $oConstrucao = ContrucaoParser::parser($aLinha);

            $matricula = $oConstrucao->getMatricula();
            $idbql     = $oConstrucao->getIdbql();
            $codigoRua = $oConstrucao->getRua();
            $codigoConstrucao = $oConstrucao->getIdConstrucao();

            if (empty($idbql) || empty($codigoRua) || empty($codigoConstrucao)) {
                $line = ($key + 1);
                $excecao = new \BusinessException("Erro ao importar linha {$line} do arquivo de contrucao, colunas idbl 
                 ou rua_codigo ou codigo_contrucao  estao vazios");
                Situacao::lancarExcecao($excecao, $this->importacaoManual);
                continue;
            }

            $iStatus = Processamento::MATRICULA_PENDENTE;

            if (empty($matricula)) {
                $iStatus = Processamento::MATRICULA_NOVA;
                $oConstrucao->setISchemaMatricula(Processamento::MATRICULA_NOVA);
            }

            try {

                $oConstrucao->salvar();

            } catch (\Exception $excecao) {
                Situacao::lancarExcecao($excecao, $this->importacaoManual);
                continue;
            }

            $oMatricula = new \stdClass();
            $oMatricula->iMatricula = $oConstrucao->getMatricula();
            $oMatricula->iStatus    = $iStatus;
            $oMatricula->globalid   = $aLinha[16];

            $this->aMatriculasImportadas[$oMatricula->iMatricula] = $oMatricula;
        }

    }

    /**
     * Retorna Matriculas Importadas
     *
     * @return array
     */
    public function getMatriculasImportadas()
    {
        return $this->aMatriculasImportadas;
    }


    /**
     * Salva  de para da matricula com codigo externo
     *
     */
    public function salvaMatriculaCodigoExterno()
    {

        $daoCivitasComplementar = new \cl_civitasinfoscomplementar();

        foreach ($this->aMatriculasImportadas as $iMatricula => $oMatricula) {

            if (empty($oMatricula->globalid) || empty($iMatricula)) {
                $excecao = new \BusinessException("Não foi possivel vincular globalid com matricula.");
                Situacao::lancarExcecao($excecao, false);
                continue;
            }

            $sWhere = "matricula = {$iMatricula} ";
            $sSqlAtualizacaoMatricula = "SELECT * FROM  cadastro.civitasinfoscomplementar WHERE " . $sWhere;
            $rsAtualizacaoMatricula = db_query($sSqlAtualizacaoMatricula);

            if ($rsAtualizacaoMatricula && pg_num_rows($rsAtualizacaoMatricula) > 0) {
                continue;
            }

            $daoCivitasComplementar->matricula = $iMatricula;
            $daoCivitasComplementar->codigo_api = $oMatricula->globalid;
            $daoCivitasComplementar->nova_matricula = '0';

            $daoCivitasComplementar->incluir();
        }
    }
}
