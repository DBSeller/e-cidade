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

namespace ECidade\Educacao\Escola\Service;

use ECidade\Educacao\Escola\Model\AreaProcedimento;
use ECidade\Educacao\Escola\Model\AreaProcedimentoAvaliacao;
use ECidade\Educacao\Escola\Model\AreaProcedimentoComposicaoResultado;
use ECidade\Educacao\Escola\Model\AreaProcedimentoResultado;
use ECidade\Educacao\Escola\Repository\AreaProcedimentoAvaliacaoRepository;
use ECidade\Educacao\Escola\Repository\AreaProcedimentoComposicaoResultadoRepository;
use ECidade\Educacao\Escola\Repository\AreaProcedimentoRepository;
use ECidade\Educacao\Escola\Repository\AreaProcedimentoResultadoRepository;
use ECidade\Educacao\Escola\Repository\DiarioAlunoRepository;
use ECidade\Enum\Educacao\Escola\FormaObtencaoEnum;
use Exception;
use FormaAvaliacao;
use FormaAvaliacaoRepository;
use PeriodoAvaliacaoRepository;
use ProcedimentoAvaliacao;
use RegenciaRepository;
use TipoResultado;

/**
 * Class AreaProcedimentoService
 * @package ECidade\Educacao\Escola\Service
 */
class AreaProcedimentoService
{
    /**
     * @param ProcedimentoAvaliacao $procedimentoAvaliacao
     * @return AreaProcedimento|null
     * @throws Exception
     */
    public function getAreaProcedimentoPorProcedimentoAvaliacao(ProcedimentoAvaliacao $procedimentoAvaliacao)
    {
        $repository = new AreaProcedimentoRepository();
        $areaProcedimento = $repository->scopeProcedimento($procedimentoAvaliacao)->first();

        if (is_null($areaProcedimento)) {
            return null;
        }

        return $areaProcedimento;
    }

    /**
     * @param AreaProcedimento $areaProcedimento
     * @return AreaProcedimentoAvaliacao[]
     * @throws Exception
     */
    public function getAvaliacoes(AreaProcedimento $areaProcedimento)
    {
        $repository = new AreaProcedimentoAvaliacaoRepository();
        $avaliacoes = $repository->scopeAreaProcedimento($areaProcedimento)->get();
        $areaProcedimento->setAvaliacoes($avaliacoes);

        return $avaliacoes;
    }

    /**
     * @param ProcedimentoAvaliacao $procedimento
     * @return AreaProcedimento
     * @throws Exception
     */
    public function criarAreaProcedimento(ProcedimentoAvaliacao $procedimento)
    {
        $areaProcedimento = new AreaProcedimento();
        $areaProcedimento->setProcedimento($procedimento);

        $repository = new AreaProcedimentoRepository();
        $areaProcedimento = $repository->salvar($areaProcedimento);

        return $areaProcedimento;
    }

    /**
     * @param AreaProcedimento $areaProcedimento
     * @param $parametros
     * @return AreaProcedimento
     * @throws Exception
     */
    public function salvarAvaliacoes(AreaProcedimento $areaProcedimento, $parametros)
    {
        $avaliacao = new AreaProcedimentoAvaliacao();
        $avaliacao->setCodigo($parametros->codigoAvaliacao);
        $avaliacao->setAreaProcedimento($areaProcedimento);
        $avaliacao->setFormaAvaliacao(FormaAvaliacaoRepository::getByCodigo($parametros->codigoFormaAvaliacao));
        $avaliacao->setPeriodoAvaliacao(
            PeriodoAvaliacaoRepository::getPeriodoAvaliacaoByCodigo($parametros->codigoPeriodoAvaliacao)
        );
        $avaliacao->setTipo($parametros->tipo);
        $avaliacao->setOrdemElemento($parametros->ordemElementoProcedimento);
        $avaliacao->setFormaObtencao(new FormaObtencaoEnum($parametros->formaObtencao));
        $avaliacao->setPeso(null);
        $avaliacao->setOrdem($parametros->ordem);

        $repository = new AreaProcedimentoAvaliacaoRepository();
        $areaProcedimento->addAvaliacao($repository->salvar($avaliacao));

        $areaProcedimentoResultado = $areaProcedimento->getResultado();
        if (!is_null($areaProcedimentoResultado)) {
            $areaProcedimentoComposicoesResultado = $this->atualizarComposicoesResultado(
                $areaProcedimentoResultado,
                $areaProcedimento
            );
            $areaProcedimentoResultado->setComposicao($areaProcedimentoComposicoesResultado);
            $areaProcedimento->setResultado($areaProcedimentoResultado);
        }

        return $areaProcedimento;
    }

    /**
     * @param AreaProcedimento $areaProcedimento
     * @param $codigo
     * @return AreaProcedimento
     * @throws Exception
     */
    public function excluirAvaliacao(AreaProcedimento $areaProcedimento, $codigo)
    {
        $repository = new AreaProcedimentoAvaliacaoRepository();

        foreach ($areaProcedimento->getAvaliacoes() as $avaliacao) {
            if ($avaliacao->getCodigo() == $codigo) {
                $repository->excluir($avaliacao);
                $areaProcedimento->removerAvaliacao($avaliacao);
            }
        }

        if (empty($areaProcedimento->getAvaliacoes())) {
            $this->excluirResultado($areaProcedimento);
        }

        return $areaProcedimento;
    }

    /**
     * @param AreaProcedimento $areaProcedimento
     * @param $parametros
     * @return AreaProcedimento
     * @throws Exception
     */
    public function salvarResultado(AreaProcedimento $areaProcedimento, $parametros)
    {
        $areaProcedimentoResultado = $areaProcedimento->getResultado();
        if (is_null($areaProcedimentoResultado)) {
            $areaProcedimentoResultado = new AreaProcedimentoResultado();
        }
        $areaProcedimentoResultado->setCodigo($parametros->codigoResultado);
        $areaProcedimentoResultado->setAreaProcedimento($areaProcedimento);
        $areaProcedimentoResultado->setFormaAvaliacao(
            FormaAvaliacaoRepository::getByCodigo($parametros->codigoFormaAvaliacaoResultado)
        );
        $areaProcedimentoResultado->setTipoResultado(new TipoResultado($parametros->codigoTipoResultado));
        $areaProcedimentoResultado->setFormaObtencao(new FormaObtencaoEnum($parametros->formaObtencaoResultado));

        $repository = new AreaProcedimentoResultadoRepository();
        $repository->scopeAreaProcedimento($areaProcedimento);
        $areaProcedimentoResultado = $repository->salvar($areaProcedimentoResultado);
        $areaProcedimentoComposicoesResultado = $this->atualizarComposicoesResultado(
            $areaProcedimentoResultado,
            $areaProcedimento
        );
        $areaProcedimentoResultado->setComposicao($areaProcedimentoComposicoesResultado);
        $areaProcedimento->setResultado($areaProcedimentoResultado);

        return $areaProcedimento;
    }

    /**
     * @param AreaProcedimentoResultado $areaProcedimentoResultado
     * @return array|AreaProcedimentoComposicaoResultado[]
     * @throws Exception
     */
    public function atualizarComposicoesResultado(
        AreaProcedimentoResultado $areaProcedimentoResultado,
        AreaProcedimento $areaProcedimento
    ) {
        $areaProcedimentoComposicoesResultado = $areaProcedimentoResultado->getComposicao();

        $repositoryComposicao = new AreaProcedimentoComposicaoResultadoRepository();

        $areaProcedimentoAvaliacoes = $areaProcedimento->getAvaliacoes();
        foreach ($areaProcedimentoAvaliacoes as $areaProcedimentoAvaliacao) {
            $encontrou = false;

            if (!is_null($areaProcedimentoComposicoesResultado)) {
                foreach ($areaProcedimentoComposicoesResultado as $composicaoResultado) {
                    $codigo = $composicaoResultado->getAreaProcedimentoAvaliacao()->getCodigo();
                    if ($areaProcedimentoAvaliacao->getCodigo() === $codigo) {
                        $encontrou = true;
                        continue;
                    }
                }
            }

            if (!$encontrou) {
                $areaProcedimentoComposicaoResultado = new AreaProcedimentoComposicaoResultado();
                $areaProcedimentoComposicaoResultado->setAreaProcedimentoResultado($areaProcedimentoResultado);
                $areaProcedimentoComposicaoResultado->setAreaProcedimentoAvaliacao($areaProcedimentoAvaliacao);

                $areaProcedimentoComposicoesResultado[] = $repositoryComposicao->salvar(
                    $areaProcedimentoComposicaoResultado
                );
            }
        }

        return $areaProcedimentoComposicoesResultado;
    }

    /**
     * @param AreaProcedimento $areaProcedimento
     * @return bool
     * @throws Exception
     */
    public function excluirResultado(AreaProcedimento $areaProcedimento)
    {
        $areaProcedimentoResultado = $areaProcedimento->getResultado();
        if (!is_null($areaProcedimentoResultado)) {
            $repository = new AreaProcedimentoResultadoRepository();
            $repository->scopeAreaProcedimento($areaProcedimento);
            $repository->excluir($areaProcedimentoResultado);
        }

        return true;
    }

    /**
     * @param AreaProcedimento $areaProcedimento
     * @return AreaProcedimento
     * @throws Exception
     */
    public function salvarOrdemElementos(AreaProcedimento $areaProcedimento)
    {
        $areaProcedimentoAvaliacoes = $areaProcedimento->getAvaliacoes();
        $repository = new AreaProcedimentoAvaliacaoRepository();
        foreach ($areaProcedimentoAvaliacoes as $areaProcedimentoAvaliacao) {
            $repository->salvar($areaProcedimentoAvaliacao);
        }

        return $areaProcedimento;
    }

    /**
     * @param AreaProcedimento $areaProcedimento
     * @return bool
     * @throws Exception
     */
    public function excluirAreaProcedimento(AreaProcedimento $areaProcedimento)
    {
        $turmasEncerradas = $this->hasTurmasEncerradas($areaProcedimento);
        if ($turmasEncerradas) {
            throw new Exception("Não é possível excluir Procedimentos de Avaliação que tenham turmas encerradas.");
            return;
        }
//        $codigoEscola = db_getsession('DB_coddepto');
//        $codigoProcedimento = $areaProcedimento->getProcedimento()->getCodigo();
//        $sql = "
//            delete from diarioaluno where ed161_turma in (select ed220_i_turma
//              from turmaserieregimemat
//                       join turma on ed57_i_codigo = ed220_i_turma
//              where ed220_i_procedimento = {$codigoProcedimento} and ed57_i_calendario in(
//                  select calendario.ed52_i_codigo from calendarioescola
//                    join calendario ON calendario.ed52_i_codigo = calendarioescola.ed38_i_calendario
//                    where ed38_i_escola = {$codigoEscola} and (ed52_i_ano >= 2020)
//              ) group by ed220_i_turma)
//        ";
//        $rs = pg_query($sql);
//
//        if (!$rs) {
//            throw new Exception("Erro ao excluir Diário do Aluno.");
//        }
        $areaProcedimentoAvaliacoes = $areaProcedimento->getAvaliacoes();
        if (!is_null($areaProcedimentoAvaliacoes)) {
            foreach ($areaProcedimentoAvaliacoes as $areaProcedimentoAvaliacao) {
                $areaProcedimento = $this->excluirAvaliacao($areaProcedimento, $areaProcedimentoAvaliacao->getCodigo());
            }
        }

        $repository = new AreaProcedimentoRepository();
        $repository->excluir($areaProcedimento);

        return true;
    }

    /**
     * @param AreaProcedimento $areaProcedimento
     * @return bool
     */
    public function hasTurmasEncerradas(AreaProcedimento $areaProcedimento)
    {
        $procedimento = $areaProcedimento->getProcedimento();
        $sqlQuantidadeTurmasEncerradas = "
            select count(*)
             from turmaserieregimemat
                      join regencia on ed59_i_turma = ed220_i_turma and ed59_c_encerrada = 'S'
             where ed220_i_procedimento = {$areaProcedimento->getProcedimento()->getCodigo()}
         ";
        $rs = pg_query($sqlQuantidadeTurmasEncerradas);
        $quantidadeTurmasEncerradas = pg_fetch_array($rs);
        if ($quantidadeTurmasEncerradas[0] > 0) {
            return true;
        }
        return false;
    }
}
