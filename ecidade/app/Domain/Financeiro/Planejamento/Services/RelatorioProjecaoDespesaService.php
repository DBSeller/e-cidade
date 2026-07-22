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

use App\Domain\Financeiro\Planejamento\Models\Planejamento;
use Exception;

/**
 * Class RelatorioProjecaoDespesaService
 * @package App\Domain\Financeiro\Planejamento\Services
 */
abstract class RelatorioProjecaoDespesaService
{
    /**
     * @var Planejamento
     */
    protected $planejamento;

    /**
     * @var array
     */
    protected $execiciosPlanejamento;
    /**
     * @var array
     */
    protected $exerciciosAnteriores;

    protected $dados = [];

    /**
     * @var string
     */
    protected $agrupar;

    protected $carregarExercicioAnterior = true;

    /**
     * RelatorioProjecaoDespesaService constructor.
     * @param array $filtros
     */
    public function __construct(array $filtros)
    {
        $this->processar($filtros);
    }

    /**
     *
     * @return array
     */
    abstract public function emitirPdf();

    /**
     * @param array $filtros
     */
    protected function processar(array $filtros)
    {
        $this->planejamento = Planejamento::find($filtros['planejamento_id']);
        if (!empty($filtros['agrupar'])) {
            $this->agrupar = $filtros['agrupar'];
        }
        $this->carregaExercicios();
    }

    /**
     * Busca os exercícios que o relatório processará.
     * - do planejamento selecionado como os anteriores
     */
    protected function carregaExercicios()
    {
        $this->execiciosPlanejamento = $this->planejamento->execiciosPlanejamento();
        if ($this->carregarExercicioAnterior) {
            $this->exerciciosAnteriores();
        }
    }

    /**
     * Calcula os exercícios anteriores referente ao planejamento indexando em um array
     */
    protected function exerciciosAnteriores()
    {
        $anoFinal = $this->planejamento->pl2_ano_inicial - 1;
        $anoInicial = $anoFinal - 3;

        for ($ano = $anoInicial; $ano <= $anoFinal; $ano++) {
            $this->exerciciosAnteriores[] = $ano;
        }
    }

    /**
     * Cria um array indexado pelo exercício e valor 0 (zero) para totalizar os valores, tanto do planejamento, dotação
     * e o totalizador
     * @param array $exercicios
     * @return array
     */
    protected function criaArrayValores(array $exercicios)
    {
        $array = [];
        foreach ($exercicios as $exercicio) {
            $array[$exercicio] = 0;
        }
        return $array;
    }

    /**
     * Seta no array de dados as informações do planejamento e dos exercícios anteriores
     */
    protected function organizaPlanejamento()
    {
        $this->dados['planejamento'] = $this->planejamento->toArray();
        $this->dados['planejamento']['exercicios'] = $this->execiciosPlanejamento;
        if ($this->carregarExercicioAnterior) {
            $this->dados['exerciciosAnteriores'] = $this->exerciciosAnteriores;
        }
    }

    /**
     * Busca os dados do Agrupador selecionado
     * @throws Exception
     */
    protected function fitroAgruparPlanejamento()
    {
        switch ($this->agrupar) {
            case "orgao":
                $this->dados['agrupador'] = 'Órgão';
                $this->buscarOrgaos();
                break;
            case "unidade":
                $this->dados['agrupador'] = 'Unidade';
                $this->buscarUnidades();
                break;
            case "funcao":
                $this->dados['agrupador'] = 'Função';
                $this->buscarFuncoes();
                break;
            case "subfuncao":
                $this->dados['agrupador'] = 'Subfunção';
                $this->buscarSubfuncoes();
                break;
            case "programa":
                $this->dados['agrupador'] = 'Programa';
                $this->buscarProgramas();
                break;
            case "iniciativa":
                $this->dados['agrupador'] = 'Iniciativa (Projeto/Atividade)';
                $this->buscarIniciativas();
                break;
            case "elemento":
                $this->dados['agrupador'] = 'Elemento';
                $this->buscarElementos();
                break;
            case "recurso":
                $this->dados['agrupador'] = 'Recurso';
                $this->buscarRecursos();
                break;
            default:
                throw new Exception('Agrupador não identificado');
        }
    }


    protected function buscarOrgaos()
    {
        // TODO: Implement buscarOrgaos() method.
    }

    protected function buscarUnidades()
    {
        // TODO: Implement buscarUnidades() method.
    }

    protected function buscarFuncoes()
    {
        // TODO: Implement buscarFuncoes() method.
    }

    protected function buscarSubfuncoes()
    {
        // TODO: Implement buscarSubfuncoes() method.
    }

    protected function buscarProgramas()
    {
        // TODO: Implement buscarProgramas() method.
    }

    protected function buscarIniciativas()
    {
        // TODO: Implement buscarIniciativas() method.
    }

    protected function buscarElementos()
    {
        // TODO: Implement buscarElementos() method.
    }

    protected function buscarRecursos()
    {
        // TODO: Implement buscarRecursos() method.
    }
}
