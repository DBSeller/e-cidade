<?php

namespace App\Domain\Financeiro\Planejamento\Services\Relatorios;

use App\Domain\Financeiro\Planejamento\Models\EstimativaReceita;
use App\Domain\Financeiro\Planejamento\Factories\OutrosAnexosRclFactory;
use App\Domain\Financeiro\Planejamento\Services\AnexosLDOService;
use ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;
use Exception;

/**
 *
 */
class RelatorioPlanejamentoRclService extends AnexosLDOService
{
    protected $sections = ['receitas' => [1, 34]];
    protected $linhasAplicarAbs = [29];

    /**
     * @param $filtros
     * @throws Exception
     */
    public function __construct($filtros)
    {
        parent::__construct($filtros);

        $this->processar();
        $this->parser = OutrosAnexosRclFactory::getModeloRelatorio($filtros['tipo_planejamento']);
    }

    protected function processar()
    {
        parent::processar();

        $ano = $this->plano->pl2_ano_inicial;
        $this->propriedadesAnoConstante[$ano] = 'valor_ano_referencia';
        $ano += 1;
        $this->propriedadesAnoConstante[$ano] = 'valor_ano_mais_um';
        $ano += 1;
        $this->propriedadesAnoConstante[$ano] = 'valor_ano_mais_dois';

        $this->processaLinhas();
        $this->organizaLinhas();
    }

    protected function processaEstimativas($estimativas, $linha)
    {
        $estimativas->each(function (EstimativaReceita $estimativaReceita) use ($linha) {
            $valores = $estimativaReceita->getValores();

            foreach ($valores as $valor) {
                $propriedade = $this->propriedadesAnoConstante[$valor->pl10_ano];
                $linha->{$propriedade} += $valor->pl10_valor;
            }
        });
    }

    protected function aplicarAbsLinha()
    {
        foreach ($this->linhasOrganizadas['receitas'] as $linha) {
            if (in_array($linha->ordem, $this->linhasAplicarAbs)) {
                $linha->valor_ano_referencia = abs($linha->valor_ano_referencia);
                $linha->valor_ano_mais_um = abs($linha->valor_ano_mais_um);
                $linha->valor_ano_mais_dois = abs($linha->valor_ano_mais_dois);
            }
        }
    }

    protected function processaReceita($linha)
    {
        $estimativas = $this->estimativasPlanejamentoCompativeisReceita($linha->parametros->contas);
        $this->processaEstimativas($estimativas, $linha);
    }

    protected function processaLinha31e33($linha)
    {
        $estimativas = $this->estimativasPlanejamentoCompativeisReceita($linha->parametros->contas);
        $novasEstimativas = $estimativas->filter(function (EstimativaReceita $estimativaReceita) use ($linha) {
            return $this->verificaFiltrosOrcamento($estimativaReceita, $linha->parametros->orcamento);
        });

        $this->processaEstimativas($novasEstimativas, $linha);
    }

    protected function processaLinhas()
    {
        $linhas = $this->getLinhas();

        foreach ($linhas as $linha) {
            /**
             * @var linhaRelatorioContabil $linhaRelatorio
             */
            $linhaRelatorio = $linha->oLinhaRelatorio;
            if ((int)$linhaRelatorio->getOrigemDados() === AnexosLDOService::RECEITA
                && !in_array($linha->ordem, [31,33])
            ) {
                $this->processaReceita($linha);
            }

            if (in_array($linha->ordem, [31,33])) {
                $this->processaLinha31e33($linha);
            }
        }

        $this->processaValorManual();
    }

    protected function verificaFiltrosOrcamento($estimativaReceita, $parametros)
    {
        foreach ($parametros as $key => $param) {
            if ($key == 'recurso') {
                if ($param->operador == 'in' && sizeof($param->valor) > 0) {
                    if (!in_array($estimativaReceita->recurso->o15_codigo, $param->valor)) {
                        return false;
                    }
                }

                if ($param->operador == 'notin' && sizeof($param->valor) > 0) {
                    if (in_array($estimativaReceita->recurso->o15_codigo, $param->valor)) {
                        return false;
                    }
                }
            }

            if ($key == 'fonterecurso') {
                if ($param->operador == 'in' && sizeof($param->valor) > 0) {
                    if (!in_array($estimativaReceita->recurso->o15_recurso, $param->valor)) {
                        return false;
                    }
                }

                if ($param->operador == 'notin' && sizeof($param->valor) > 0) {
                    if (in_array($estimativaReceita->recurso->o15_recurso, $param->valor)) {
                        return false;
                    }
                }
            }

            if ($key == 'complemento') {
                if ($param->operador == 'in' && sizeof($param->valor) > 0) {
                    if (!in_array($estimativaReceita->recurso->complemento->o200_sequencial, $param->valor)) {
                        return false;
                    }
                }

                if ($param->operador == 'notin' && sizeof($param->valor) > 0) {
                    if (in_array($estimativaReceita->recurso->complemento->o200_sequencial, $param->valor)) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    protected function processaDespesa($linha)
    {
        // TODO: Implement processaDespesa() method.
    }

    public function emitir()
    {
        foreach ($this->linhasOrganizadas as $section => $linhas) {
            $this->parser->addCollection($section, $linhas);
        }
        
        $this->aplicarAbsLinha();
        
        $instituicaoSessao = \InstituicaoRepository::getInstituicaoSessao();
        $ente = DemonstrativoFiscal::getEnteFederativo($instituicaoSessao);
        if ($instituicaoSessao->getTipo() != \Instituicao::TIPO_PREFEITURA) {
            $ente .= "\n" . $instituicaoSessao->getDescricao();
        }

        $endereco = sprintf('%, %s', $this->emissor->getLogradouro(), $this->emissor->getNumero());
        $this->parser->setVariavel('ente_emissor', $this->emissor->getDescricao());
        $this->parser->setVariavel('endereco_ente', $endereco);
        $this->parser->setVariavel('ente_federecao', $ente);
        $this->parser->setVariavel('municipio', $this->emissor->getMunicipio());
        $this->parser->setVariavel('telefone', $this->emissor->getTelefone());
        $this->parser->setVariavel('cnpj', $this->emissor->getCNPJ());
        $this->parser->setVariavel('email', $this->emissor->getEmail());
        $this->parser->setVariavel('site', $this->emissor->getSite());
        $this->parser->setAnoReferencia($this->plano->pl2_ano_inicial);
        $this->parser->setVariavel('nota_explicativa', $this->getNotaExplicativa());
        $this->parser->setVariavel('departamento', $this->departamento->descrdepto);

        $this->parser->addImage(
            $this->emissor->getImagemLogo(),
            'B1',
            ["width" => 100, "height" => 140, 'name' => 'Logo', 'description' => 'Logo municipio', "offsetx" => 20]
        );

        $filename = $this->parser->gerar();

        return [
            'xls' => $filename,
            'xlsLinkExterno' => ECIDADE_REQUEST_PATH . $filename
        ];
    }
}
