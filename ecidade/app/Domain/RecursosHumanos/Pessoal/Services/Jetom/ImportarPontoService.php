<?php

namespace App\Domain\RecursosHumanos\Pessoal\Services\Jetom;

use App\Domain\RecursosHumanos\Pessoal\Factories\Jetom\PontoFactory;

use ECidade\File\Csv\LerCsv;
use DBCompetencia;
use Exception;
use Servidor;

/**
 * @author   Lucas Jarrier de Aquino Cavalcanti - lucas.cavalcanti@dbseller.com.br
 * @package  Pessoal
 */
class ImportarPontoService
{
    /**
     * @var integer
     */
    private $ano;

    /**
     * @var integer
     */
    private $mes;

    /**
     * @var string
     */
    private $separador;

    /**
     * @var string
     */
    private $acao;

    /**
     * @var string
     */
    private $tipo;

    /**
     * @var integer
     */
    private $instituicao;

    /**
     * @var string
     */
    private $path;

    /**
     * @var mixed
     */
    private $repository;

    public function __construct(array $dados)
    {
        // Competência
        $this->ano = $dados['exercicio'];
        $this->mes = (int) $dados['mes'];
        $this->instituicao = $dados['DB_instit'];

        // Configuração
        $this->separador = $dados['separador'];
        $this->tipo = $dados['ponto'];
        $this->acao = $dados['acao'];

        $this->path = \JSON::create()->parse(str_replace('\"', '"', $dados['file']))->path;

        $this->repository = PontoFactory::getRepository($this->tipo);
    }

    /**
     *
     * @throws Exception
     */
    public function importar()
    {
        $csv = new LerCsv($this->path);
        $csv->setCsvControl($this->separador);
        $linha = $csv->read();

        
        foreach ($linha as $dadosLinha) {
            if (!$dadosLinha) {
                continue;
            }

            if ($this->tipo != $dadosLinha[6]) {
                throw new Exception("Planilha não corresponde a tabela selecionada, verifique o CSV.");
            }
            
            if ($this->verificarPontoExistente($dadosLinha)) {
                continue;
            }

            $this->salvarPonto($dadosLinha);
        }
    }

    /**
     * @param array $dadosLinha
     * @return bool
     */
    private function verificarPontoExistente(array $dadosLinha)
    {
        $pontoRepository = $this->repository;
        $ponto = $pontoRepository::find(
            $dadosLinha[0],
            $this->ano,
            $this->mes,
            str_pad($dadosLinha[1], 4, '0', STR_PAD_LEFT)
        );

        if ($ponto && $this->acao == 'ignorar') {
            return true;
        }

        if ($ponto) {
            $this->repository->delete($ponto);
        }

        return false;
    }

    /**
     * @throws Exception
     */
    private function salvarPonto(array $dadosLinha)
    {
        $servidor = new Servidor($dadosLinha[0]);

        if (!$servidor->getCodigoLotacao()) {
            throw new Exception("Não existe lotação cadastrada para a matrícula: " . $dadosLinha[0]);
        }

        $ponto = PontoFactory::getModel($this->tipo);
        $ponto->setAno($this->ano);
        $ponto->setMes($this->mes);
        $ponto->setInstituicao($this->instituicao);
        $ponto->setMatricula($dadosLinha[0]);
        $ponto->setRubrica(str_pad($dadosLinha[1], 4, '0', STR_PAD_LEFT));
        $ponto->setQuantidade($dadosLinha[2]);
        $ponto->setValor($dadosLinha[3]);
        $ponto->setLotacao($servidor->getCodigoLotacao());

        if ($dadosLinha[5]) {
            $competencia = DBCompetencia::createFromString($dadosLinha[5]);
            $ponto->setDataLimite($competencia->getCompetencia(DBCompetencia::FORMATO_AAAAMM, true));
        }

        $this->repository->save($ponto);
    }
}
