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
use App\Domain\Financeiro\Planejamento\Relatorios\ProgramasPorElementoPdf;
use Illuminate\Support\Facades\DB;

class RelatorioProjecaoPorElementoService
{
    /**
     * @var Planejamento
     */
    private $planejamento;

    private $dados = [];
    /**
     * @var bool
     */
    private $apresentarRecurso = false;

    /**
     * Se deve mostrar o recurso original (da orctiporec)
     * @var bool
     */
    private $apresentarRecursoOriginal = false;

    public function __construct(array $filtros)
    {
        $this->processar($filtros);
    }

    public function emitirPdf()
    {
        $pdf = new ProgramasPorElementoPdf();
        $pdf->setDados($this->dados);
        return $pdf->emitir();
    }

    private function processar(array $filtros)
    {
        $this->planejamento = Planejamento::find($filtros['planejamento_id']);
        $this->apresentarRecurso = $filtros['apresentarRecurso'] === 't';
        $this->apresentarRecursoOriginal = $filtros['apresentarRecursoOriginal'] === 't';

        $dados = $this->buscarDados($filtros);
        if (empty($dados)) {
            throw new \Exception("Não foi encontrado registros", 403);
        }

        $this->organizaDados($dados);
    }

    private function buscarDados(array $filtros)
    {
        $where = $this->montaWhere($filtros);

        $where = implode(' and ', $where);
        $sql = "
        with valores_por_elemento as (
            select pl20_anoorcamento,
                   pl20_orcorgao,
                   pl20_orcunidade,
                   pl20_orcfuncao,
                   pl20_orcsubfuncao,
                   pl9_orcprograma,
                   pl12_orcprojativ,
                   pl20_orcelemento,
                   pl20_recurso,
                   pl10_ano,
                   sum(pl10_valor) as valor
              from planejamento.planejamento
              join programaestrategico on programaestrategico.pl9_planejamento = planejamento.pl2_codigo
              join planejamento.iniciativaprojativ
                   on iniciativaprojativ.pl12_programaestrategico = programaestrategico.pl9_codigo
              join planejamento.detalhamentoiniciativa
                   on detalhamentoiniciativa.pl20_iniciativaprojativ = iniciativaprojativ.pl12_codigo
              left join planejamento.valores on pl10_chave = detalhamentoiniciativa.pl20_codigo
                                            and pl10_origem = 'DETALHAMENTO INICIATIVA'
             where {$where}
            group by
               pl20_anoorcamento,
               pl20_orcorgao,
               pl20_orcunidade,
               pl20_orcfuncao,
               pl20_orcsubfuncao,
               pl9_orcprograma,
               pl12_orcprojativ,
               pl20_orcelemento,
               pl20_recurso,
               pl10_ano
        ), agrupa_valores as (
            select pl20_anoorcamento,
                   pl20_orcorgao,
                   pl20_orcunidade,
                   pl20_orcfuncao,
                   pl20_orcsubfuncao,
                   pl9_orcprograma,
                   pl12_orcprojativ,
                   pl20_orcelemento,
                   pl20_recurso,
                   json_agg(
                      json_build_object(
                         'ano', pl10_ano,
                         'valor', valor
                      )
                   ) as valores
             from valores_por_elemento
             where valor is not null
             group by
               pl20_anoorcamento,
               pl20_orcorgao,
               pl20_orcunidade,
               pl20_orcfuncao,
               pl20_orcsubfuncao,
               pl9_orcprograma,
               pl12_orcprojativ,
               pl20_orcelemento,
               pl20_recurso
        ), descricao_dados as (
            select agrupa_valores.*
                   ,o40_descr as descricao_orgao
                   ,o41_descr as descricao_unidade
                   ,o52_descr as descricao_funcao
                   ,o53_descr as descricao_subfuncao
                   ,o54_descr as descricao_programa
                   ,o55_descr as descricao_iniciativa
                   ,o56_elemento as elemento
                   ,o56_descr as descricao_elemento
                   ,orctiporec.o15_recurso as recurso_original
                   ,fonterecurso.codigo_siconfi as recurso
                   ,o15_complemento
              from agrupa_valores
              join orcamento.orcorgao on (o40_anousu, o40_orgao) = (pl20_anoorcamento, pl20_orcorgao)
              join orcamento.orcunidade
              on (o41_anousu, o41_orgao, o41_unidade) = (pl20_anoorcamento, pl20_orcorgao, pl20_orcunidade)
              join orcamento.orcfuncao on o52_funcao = pl20_orcfuncao
              join orcamento.orcsubfuncao on o53_subfuncao = pl20_orcsubfuncao
              join orcamento.orcprograma on (o54_anousu, o54_programa) = (pl20_anoorcamento, pl9_orcprograma)
              join orcamento.orcprojativ on (o55_anousu, o55_projativ) = (pl20_anoorcamento, pl12_orcprojativ)
              join orcamento.orcelemento on (o56_codele, o56_anousu) = (pl20_orcelemento, pl20_anoorcamento)
              join orcamento.orctiporec on orctiporec.o15_codigo = pl20_recurso
              join orcamento.fonterecurso on fonterecurso.orctiporec_id = orctiporec.o15_codigo
                   and fonterecurso.exercicio = agrupa_valores.pl20_anoorcamento
              order by
                pl20_orcorgao,
                pl20_orcunidade,
                pl20_orcfuncao,
                pl20_orcsubfuncao,
                pl9_orcprograma,
                pl12_orcprojativ,
                o56_elemento
        ) select * from descricao_dados;
        ";

        return DB::select($sql);
    }

    private function organizaDados(array $dados)
    {
        $this->dados['apresentarRecurso'] = $this->apresentarRecurso;
        $this->dados['apresentarRecursoOriginal'] = $this->apresentarRecursoOriginal;
        $this->dados['planejamento'] = $this->planejamento;
        $this->dados['planejamento']['exercicios'] = $this->planejamento->execiciosPlanejamento();

        $dadosOrganizados = [];
        $totalGeral = $this->criaArrayTotalizador();

        foreach ($dados as $dado) {
            $orgao = $dado->pl20_orcorgao;
            if (!array_key_exists($orgao, $dadosOrganizados)) {
                $dadosOrganizados[$orgao] = $this->createObjeto($orgao, $dado->descricao_orgao);
                $dadosOrganizados[$orgao]->totalizador = $this->criaArrayTotalizador();
                $dadosOrganizados[$orgao]->dados = [];
            }

            $hash = $this->createHash($dado);
            if (!array_key_exists($hash, $dadosOrganizados[$orgao]->dados)) {
                $dadosOrganizados[$orgao]->dados[$hash] = $this->createDadosAgrupador($dado);
            }
            $objetoElemento = $this->createObjetoElemento($dado);

            $dadosOrganizados[$orgao]->dados[$hash]->elementos[] = $objetoElemento;

            foreach ($objetoElemento->valores as $valor) {
                $dadosOrganizados[$orgao]->dados[$hash]->totalizador[$valor->ano] += $valor->valor;
                $dadosOrganizados[$orgao]->totalizador[$valor->ano] += $valor->valor;
                $totalGeral[$valor->ano] += $valor->valor;
            }
        }

        $this->dados['dados'] = $dadosOrganizados;
        $this->dados['totalizador'] = $totalGeral;
    }

    private function createHash($dado)
    {
        return sprintf(
            '%s#%s#%s#%s#%s#%s#%s',
            $dado->pl20_anoorcamento,
            $dado->pl20_orcorgao,
            $dado->pl20_orcunidade,
            $dado->pl20_orcfuncao,
            $dado->pl20_orcsubfuncao,
            $dado->pl9_orcprograma,
            $dado->pl12_orcprojativ
        );
    }

    private function createDadosAgrupador($dado)
    {
        $orgao = str_pad($dado->pl20_orcorgao, 2, '0', STR_PAD_LEFT);
        $unidade = sprintf('%s.%s', $orgao, str_pad($dado->pl20_orcunidade, 2, '0', STR_PAD_LEFT));
        $funcao = sprintf('%s.%s', $unidade, str_pad($dado->pl20_orcfuncao, 2, '0', STR_PAD_LEFT));
        $subfuncao = sprintf('%s.%s', $funcao, str_pad($dado->pl20_orcsubfuncao, 3, '0', STR_PAD_LEFT));
        $programa = sprintf('%s.%s', $subfuncao, str_pad($dado->pl9_orcprograma, 4, '0', STR_PAD_LEFT));
        $iniciativa = sprintf('%s.%s', $programa, str_pad($dado->pl12_orcprojativ, 4, '0', STR_PAD_LEFT));

        return (object)[
            'orgao' => $this->createObjeto($orgao, $dado->descricao_orgao),
            'unidade' => $this->createObjeto($unidade, $dado->descricao_unidade),
            'funcao' => $this->createObjeto($funcao, $dado->descricao_funcao),
            'subfuncao' => $this->createObjeto($subfuncao, $dado->descricao_subfuncao),
            'programa' => $this->createObjeto($programa, $dado->descricao_programa),
            'iniciativa' => $this->createObjeto($iniciativa, $dado->descricao_iniciativa),
            'totalizador' => $this->criaArrayTotalizador(),
            'elementos' => [],
        ];
    }

    private function createObjeto($codigo, $descricao)
    {
        return (object)[
            'codigo' => $codigo,
            'descricao' => $descricao
        ];
    }

    private function createObjetoElemento($dado)
    {
        $elemento = $this->createObjeto($dado->elemento, $dado->descricao_elemento);
        $elemento->recurso_original = sprintf('%s - %s', $dado->recurso_original, $dado->o15_complemento);
        $elemento->recurso = sprintf('%s - %s', $dado->recurso, $dado->o15_complemento);
        $elemento->valores = json_decode($dado->valores);
        return $elemento;
    }

    private function criaArrayTotalizador()
    {
        $valores = [];
        foreach ($this->planejamento->execiciosPlanejamento() as $exercicio) {
            $valores[$exercicio] = 0;
        }
        return $valores;
    }

    private function montaWhere(array $filtros)
    {
        $where = ["pl2_codigo = {$filtros['planejamento_id']}"];
        $filtro = json_decode(str_replace('\"', '"', $filtros['filtros']));

        if (!empty($filtro->orgao->aOrgaos)) {
            $operador = $filtro->orgao->operador === 'notin' ? 'not in' : 'in';
            $data = implode(', ', $filtro->orgao->aOrgaos);
            $where[] = "pl20_orcorgao {$operador} ({$data})";
        }

        if (!empty($filtro->unidade->aUnidades)) {
            $operador = $filtro->unidade->operador === 'notin' ? 'not in' : 'in';
            $filtroUnidades = [];
            foreach ($filtro->unidade->aUnidades as $unidade) {
                $data = explode('-', $unidade);
                $filtroUnidades[] = sprintf(
                    '(pl20_orcorgao %s (%s) and pl20_orcunidade %s (%s))',
                    $operador,
                    $data[0],
                    $operador,
                    $data[1]
                );
            }
            $where[] = '(' . implode(' or ', $filtroUnidades) . ')';
        }
        if (!empty($filtro->funcao->aFuncoes)) {
            $operador = $filtro->funcao->operador === 'notin' ? 'not in' : 'in';
            $data = implode(', ', $filtro->funcao->aFuncoes);
            $where[] = "pl20_orcfuncao {$operador} ({$data})";
        }

        if (!empty($filtro->subfuncao->aSubFuncoes)) {
            $operador = $filtro->funcao->operador === 'notin' ? 'not in' : 'in';
            $data = implode(', ', $filtro->subfuncao->aSubFuncoes);
            $where[] = "pl20_orcsubfuncao {$operador} ({$data})";
        }

        if (!empty($filtro->programa->aProgramas)) {
            $operador = $filtro->programa->operador === 'notin' ? 'not in' : 'in';
            $data = implode(', ', $filtro->programa->aProgramas);
            $where[] = "pl9_orcprograma {$operador} ({$data})";
        }

        if (!empty($filtro->projativ->aProjAtiv)) {
            $operador = $filtro->projativ->operador === 'notin' ? 'not in' : 'in';
            $data = implode(', ', $filtro->projativ->aProjAtiv);
            $where[] = "pl12_orcprojativ {$operador} ({$data})";
        }

        if (!empty($filtro->elemento->aElementos)) {
            $operador = $filtro->elemento->operador === 'notin' ? 'not exists' : 'exists';
            $data = implode("', '", $filtro->elemento->aElementos);
            $where[] = "
                {$operador} (select 1 from orcamento.orcelemento
                where o56_anousu = pl20_anoorcamento
                  and o56_codele = pl20_orcelemento
                  and o56_elemento in ('{$data}')
                )
            ";
        }

        if (!empty($filtro->recurso->aRecursos)) {
            $operador = $filtro->projativ->operador === 'notin' ? 'not in' : 'in';
            $data = implode(", ", $filtro->recurso->aRecursos);
            $where[] = "pl20_recurso {$operador} ({$data})";
        }

        return $where;
    }
}
