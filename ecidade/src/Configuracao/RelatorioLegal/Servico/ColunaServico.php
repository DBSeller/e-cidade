<?php

namespace ECidade\Configuracao\RelatorioLegal\Servico;

use ECidade\Configuracao\RelatorioLegal\Enum\OrigemDadosEnum;
use ECidade\Configuracao\RelatorioLegal\Modelo\Coluna;
use ECidade\Configuracao\RelatorioLegal\Modelo\ColunaEstrutural;
use ECidade\Configuracao\RelatorioLegal\Registry\ColunaRegistry;
use ECidade\Configuracao\RelatorioLegal\Registry\RelatorioRegistry;
use ECidade\Configuracao\RelatorioLegal\Repositorio\ColunaEstruturalRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\ColunaRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\LinhaColunaRepositorio;
use Exception;
use JSON;
use stdClass;

/**
 * Class ColunaServico
 * @package ECidade\Configuracao\RelatorioLegal\Servico
 */
class ColunaServico
{
    /**
     * @var stdClass
     */
    private $parametros;

    /**
     * ColunaServico constructor.
     * @param stdClass $parametros
     */
    public function __construct(stdClass $parametros)
    {
        $this->parametros = $parametros;
    }

    /**
     * @return Coluna
     * @throws Exception
     */
    public function salvar()
    {
        if (empty($this->parametros->descricao)) {
            throw new Exception('O campo "Descrição" é obrigatório.');
        }

        if (empty($this->parametros->nome)) {
            throw new Exception('O campo "Nome da Coluna" é obrigatório.');
        }

        if (!OrigemDadosEnum::existe($this->parametros->origem)) {
            throw new Exception('Origem informada não existe.');
        }

        $coluna = new Coluna();
        $coluna->setDescricao(trim($this->parametros->descricao));
        $coluna->setNome(trim($this->parametros->nome));

        if (!empty($this->parametros->sequencial)) {
            $coluna->setSequencial($this->parametros->sequencial);
        }

        if (!empty($this->parametros->ano)) {
            $coluna->setAno($this->parametros->ano);
        }

        if (!empty($this->parametros->tipo)) {
            $coluna->setTipo($this->parametros->tipo);
        }

        if (!empty($this->parametros->default)) {
            $coluna->setDefault($this->parametros->default);
        }

        if (!empty($this->parametros->formula)) {
            $coluna->setFormula($this->parametros->formula);
        }

        if (!empty($this->parametros->origem)) {
            $coluna->setOrigem($this->parametros->origem);
        }


        if (!empty($this->parametros->relatorio)) {
            $coluna->setRelatorio(RelatorioRegistry::get($this->parametros->relatorio));
        }

        if ($coluna->getSequencial()) {
            $parametros = new stdClass();
            $parametros->coluna = $coluna->getSequencial();
            $parametros->ano = $coluna->getAno();

            $colunaEstruturalServico = new ColunaEstruturalServico($parametros);
            $colunaEstruturalServico->excluirPorColuna();
        }

        $coluna = ColunaRepositorio::save($coluna);

        if ($this->parametros->origem == OrigemDadosEnum::MSC && !empty($this->parametros->contas)) {
            $colunaEstruturalRepositorio = new ColunaEstruturalRepositorio();

            foreach ($this->parametros->contas as $conta) {
                $conta = JSON::create()->parse($conta);

                $colunaEstrutural = $colunaEstruturalRepositorio->resetScopes()
                    ->scopeEstrutural($conta->estrutural)
                    ->scopeColuna($coluna)
                    ->scopeAno($coluna->getAno())
                    ->first();

                $parametros = new stdClass();
                $parametros->coluna = $coluna->getSequencial();
                $parametros->ano = $coluna->getAno();
                $parametros->exclusao = $conta->exclusao === 'Sim' ? 'true' : 'false';
                $parametros->estrutural = $conta->estrutural;

                if ($colunaEstrutural instanceof ColunaEstrutural) {
                    $parametros->sequencial = $colunaEstrutural->getSequencial();
                }

                $colunaEstruturalServico = new ColunaEstruturalServico($parametros);
                $colunaEstruturalServico->salvar();
            }
        }

        return $coluna;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function excluir()
    {
        if (empty($this->parametros->sequencial)) {
            throw new Exception('O sequencial é obrigatório.');
        }

        $this->parametros->coluna = $this->parametros->sequencial;

        $coluna = ColunaRegistry::get($this->parametros->coluna);
        $linhaColunaRepositorio = new LinhaColunaRepositorio();
        $linhasColunas = $linhaColunaRepositorio->scopeColuna($coluna)->get();

        if (count($linhasColunas) > 0) {
            throw new Exception('Não é possível excluir a coluna, pois a mesma já está sendo usada em um ou mais relatórios.');
        }

        $colunaEstruturalServico = new ColunaEstruturalServico($this->parametros);
        $colunaEstruturalServico->excluirPorColuna();

        ColunaRepositorio::destroy($this->parametros->sequencial);

        return 'Informações da coluna excluídas com sucesso!';
    }
}
