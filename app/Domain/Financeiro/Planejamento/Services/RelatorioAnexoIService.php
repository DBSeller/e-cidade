<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace App\Domain\Financeiro\Planejamento\Services;

use App\Domain\Financeiro\Planejamento\Models\DetalhamentoDespesa;
use App\Domain\Financeiro\Planejamento\Models\EstimativaReceita;
use App\Domain\Financeiro\Planejamento\Relatorios\Anexos\XlsAnexoI;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use stdClass;

/**
 * Class RelatorioAnexoIService
 * @package App\Domain\Financeiro\Planejamento\Services
 */
class RelatorioAnexoIService extends AnexosLDOService
{
    protected $propriedades = [
        "vlr_corrente",
        "vlr_constante",
        "pib",
        "rcl",
    ];

    protected $sufixos = [
        0 => "ano_inicial",
        1 => "ano_mais_um",
        2 => "ano_mais_dois",
    ];

    /**
     * @var array
     */
    protected $sufixosPorAno = [];
    /**
     * @var Collection
     */
    protected $projecaoReceita;
    /**
     * @var Collection
     */
    protected $projecaoDespesa;
    /**
     * @var XlsAnexoI
     */
    private $parser;

    public function __construct(array $filtros)
    {
        parent::__construct($filtros);
        $this->parser = new XlsAnexoI();

        $this->processar();
    }

    /**
     * @return array
     */
    public function emitir()
    {
        $this->parser->setDados($this->getLinhas());
        $this->parser->setVariavel('ente_federecao', $this->enteFederativo);

        $endereco = sprintf('%, %s', $this->emissor->getLogradouro(), $this->emissor->getNumero());
        $this->parser->setVariavel('ente_emissor', $this->emissor->getDescricao());
        $this->parser->setVariavel('endereco_ente', $endereco);
        $this->parser->setVariavel('municipio', $this->emissor->getMunicipio());
        $this->parser->setVariavel('telefone', $this->emissor->getTelefone());
        $this->parser->setVariavel('cnpj', $this->emissor->getCNPJ());
        $this->parser->setVariavel('email', $this->emissor->getEmail());
        $this->parser->setVariavel('site', $this->emissor->getSite());
        $this->parser->setVariavel('ano_referencia', 'Ano de referência: ' . $this->plano->pl2_ano_inicial);
        $this->parser->setVariavel('nota_explicativa', $this->getNotaExplicativa());
        $this->parser->setVariavel('departamento', $this->departamento->descrdepto);

        foreach ($this->plano->execiciosPlanejamento() as $key => $exercicio) {
            $this->parser->setVariavel($this->sufixos[$key], $exercicio);
        }

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

    protected function processar()
    {
        parent::processar();
        $this->calcularRCLPlanejamento();
        $this->fatorCorrecaoReceita();
        $this->fatorCorrecaoDespesa();
        $this->identificaPropriedadesPorAno();
        $this->processaLinhas();
    }

    /**
     * Organiza as propriedades por exercício
     */
    protected function identificaPropriedadesPorAno()
    {
        foreach ($this->plano->execiciosPlanejamento() as $key => $exercicio) {
            $this->sufixosPorAno[$exercicio] = $this->sufixos[$key];
        }
    }

    /**
     * @param stdClass $linha linha do relatório legal
     * @throws Exception
     */
    protected function processaReceita($linha)
    {
        $estimativas = $this->estimativasPlanejamentoCompativeisReceita($linha->parametros->contas);
        // soma o valor corrente
        $estimativas->each(function (EstimativaReceita $estimativaReceita) use ($linha) {
            $fatores = $this->getFatoresReceita($estimativaReceita);
            $valores = $estimativaReceita->getValores();
            foreach ($valores as $valor) {
                $ano = $valor->pl10_ano;
                $valor = $valor->pl10_valor;
                $sufixo = $this->sufixosPorAno[$ano];
                $vlrCorrente = "vlr_corrente_{$sufixo}";
                $vlrConstante = "vlr_constante_{$sufixo}";
                $vlrPIB = "pib_{$sufixo}";
                $vlrRCL = "rcl_{$sufixo}";

                $fatorCorrecao = $this->filtraFatorReceitaUtilizadoNoAno($fatores, $ano);

                $pib = $this->getPibAno($ano);
                $rcl = $this->getRCLAno($ano);

                $valorConstante = $valor;
                if (!is_null($fatorCorrecao)) {
                    if ($fatorCorrecao->deflator) {
                        $fatorCorrecao->percentual *= -1;
                    }

                    $percentual = 1 + ($fatorCorrecao->percentual / 100);
                    $valorConstante = $valor / $percentual;
                }

                $linha->{$vlrCorrente} += $valor;
                $linha->{$vlrConstante} += $valorConstante;

                $linha->{$vlrPIB} += empty($pib) ? 0 : (($valor / $pib) * 100);
                $linha->{$vlrRCL} += empty($rcl) ? 0 : (($valor / $rcl) * 100);
            }
        });
    }

    /**
     * @param stdClass $linha
     * @throws Exception
     */
    protected function processaDespesa($linha)
    {
        $estimativas = $this->estimativasPlanejamentoCompativeisDespesa($linha->parametros->contas);
        $estimativas->each(function (DetalhamentoDespesa $detalhamentoDespesa) use ($linha) {
            $fatores = $this->getFatoresDespesa($detalhamentoDespesa);
            $valores = $detalhamentoDespesa->getValores();

            foreach ($valores as $valor) {
                $ano = $valor->pl10_ano;
                $valor = $valor->pl10_valor;
                $sufixo = $this->sufixosPorAno[$ano];
                $vlrCorrente = "vlr_corrente_{$sufixo}";
                $vlrConstante = "vlr_constante_{$sufixo}";
                $vlrPIB = "pib_{$sufixo}";
                $vlrRCL = "rcl_{$sufixo}";

                $fatorCorrecao = $this->filtraFatorDespesaUtilizadoNoAno($fatores, $ano);

                $pib = $this->getPibAno($ano);
                $rcl = $this->getRCLAno($ano);

                $valorConstante = $valor;
                if (!is_null($fatorCorrecao)) {
                    if ($fatorCorrecao->deflator) {
                        $fatorCorrecao->pl7_percentual *= -1;
                    }

                    $percentual = 1 + ($fatorCorrecao->pl7_percentual / 100);
                    $valorConstante = $valor / $percentual;
                }

                $linha->{$vlrCorrente} += $valor;
                $linha->{$vlrConstante} += $valorConstante;
                $linha->{$vlrPIB} += empty($pib) ? 0 : (($valor / $pib) * 100);
                $linha->{$vlrRCL} += empty($rcl) ? 0 : (($valor / $rcl) * 100);
            }
        });
    }
}
