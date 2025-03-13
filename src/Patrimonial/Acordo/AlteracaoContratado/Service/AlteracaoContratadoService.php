<?php

namespace ECidade\Patrimonial\Acordo\AlteracaoContratado\Service;

use Acordo;
use AcordoPosicao;
use AcordoRepository;
use CgmFactory;
use cl_acordoposicao;
use cl_cgmestrangeiro;
use db_stdClass;
use ECidade\Patrimonial\Acordo\AlteracaoContratado\Repository\AlteracaoContratadoRepository;
use stdClass;

class AlteracaoContratadoService
{
    /**
     * @param stdClass $parametros
     * @return stdClass|string
     * @throws \Exception
     */
    public function buscarUltimaAlteracao(\stdClass $parametros)
    {
        $dados = null;
        $contratadoRepository = new AlteracaoContratadoRepository();
        $acordo = AcordoRepository::getByCodigo($parametros->acordoCodigo);
        $numeroAcordo = "{$acordo->getNumero()}/{$acordo->getAno()}";
        $posicoes = $acordo->getPosicoes();
        $posicao = end($posicoes);
        $alteracoes = $contratadoRepository->findLastByCodigoAcordo($acordo->getCodigo());
        $cgmAnterior = CgmFactory::getInstanceByCgm($alteracoes->getContratadoAnterior());
        $cgmNovo = CgmFactory::getInstanceByCgm($alteracoes->getContratadoNovo());

        if (!empty($alteracoes)) {
            $alteracoesContratado = $alteracoes->toArray();
            $dados = [
                'nomeAnterior' => $cgmAnterior->getNome(),
                'nomeNovo' => $cgmNovo->getNome(),
                'numeroAcordo' => $numeroAcordo,
                'posicaoAcordo' => $posicao->getNumeroAditamento()
            ];
            $dados = (object)array_merge($alteracoesContratado, $dados);
        }

        return $dados;
    }

    /**
     * @param stdClass $parametros
     * @return \_db_fields|stdClass
     */
    public function buscarContratadoAtual(\stdClass $parametros)
    {
        $acordo = new Acordo($parametros->codigoAcordo);
        $cgm = CgmFactory::getInstanceByCgm($acordo->getContratado()->getCodigo());

        if ($cgm->isJuridico()) {
            $documento = $cgm->getCnpj();
        } else {
            $documento = $cgm->getCpf();
        }

        $parametros->codigoAcordo = $cgm->getCodigo();
        $documentoEstrangeiro = $this->buscarEstrangeiro($parametros);

        $dados = (object)[
            'ac16_contratado' => $acordo->getContratado()->getCodigo(),
            'z01_nome' => $cgm->getNome(),
            'z01_cgccpf' => $documentoEstrangeiro ?: $documento,
            'ac16_datainicio' => $acordo->getDataInicial(),
            'ac16_datafim' => $acordo->getDataFinal(),
        ];

        return $dados;
    }

    /**
     * @param stdClass $parametros
     * @return void
     * @throws \BusinessException
     * @throws \DBException
     * @throws \ParameterException
     */
    public function salvarAlteracaoContratado(\stdClass $parametros)
    {
        $acordo = AcordoRepository::getByCodigo($parametros->codigoAcordo);
        $parametros->tipoAditamento = (int)$parametros->tipoAditamento;
        $justificativa = db_stdClass::normalizeStringJsonEscapeString($parametros->justificativa);
        $posicoes = $acordo->getUltimaPosicao();

        $aItens = array();
        foreach ($posicoes->getItens() as $posicao) {
            $item = new stdClass();
            $item->codigo = $posicao->getCodigo();
            $item->codigoitem = $posicao->getMaterial()->getMaterial();
            $item->elemento = $posicao->getDesdobramento();
            $item->descricaoitem = $posicao->getMaterial()->getDescricao();
            $item->valorunitario = 0;
            $item->quantidade = 0;
            $item->valor = 0;
            $item->servico = $posicao->getMaterial()->isServico();
            $item->servicoquantidade = $posicao->getControlaQuantidade();
            $item->dotacoes = array();

            foreach ($posicao->getDotacoes() as $oDotacao) {
                $item->dotacoes[] = (object) array(
                    'dotacao' => $oDotacao->dotacao,
                    'quantidade' => $oDotacao->quantidade,
                    'valor' => $oDotacao->valor,
                    'valororiginal' => $oDotacao->valor
                );
            }

            $aItens[] = $item;
        }

        $acordo->aditar(
            $aItens,
            $parametros->tipoAditamento,
            $parametros->dataInicio,
            $parametros->dataFim,
            $parametros->numeroAditamento,
            $justificativa,
            null,
            $parametros->oCboTipoAlteracao,
            $parametros->novoContratado,
            $parametros->contratadoAtual
        );
    }

    /**
     * @param stdClass $parametros
     * @return string
     */
    public function buscarEstrangeiro(\stdClass $parametros)
    {
        $daoCgmEstrangeiro = new cl_cgmestrangeiro();
        if ($parametros->cgmCodigo) {
            $where = "z09_numcgm = {$parametros->cgmCodigo}";
        } else {
            $where = "z09_numcgm = {$parametros->codigoAcordo}";
        }
        $sql = $daoCgmEstrangeiro->sql_query_file('', 'z09_documento', '', $where);
        $rs = $daoCgmEstrangeiro->sql_record($sql);
        $documentoEstrangeiro = '';
        if ($daoCgmEstrangeiro->numrows > 0) {
            $documentoEstrangeiro = \db_utils::fieldsMemory($rs, 0)->z09_documento;
        }

        return $documentoEstrangeiro;
    }

    /**
     * @param stdClass $parametros
     * @return void
     * @throws \BusinessException
     * @throws \DBException
     */
    public function exclusaoAditamentoContratado(\stdClass $parametros)
    {
        $daoAcordoPosicao = new cl_acordoposicao();
        $alteracaoContratadoRepository = new AlteracaoContratadoRepository();
        $alteracaoContratado = $alteracaoContratadoRepository->findByCodigo($parametros->sequencialAlteracao);
        $posicaoAcordo = new AcordoPosicao($alteracaoContratado->getPosicaoAcordo());
        $acordo = new Acordo($alteracaoContratado->getCodigoAcordo());
        $novoContratado = CgmFactory::getInstanceByCgm((int)$alteracaoContratado->getContratadoAnterior());
        $alteracaoContratadoRepository->excluir($alteracaoContratado);
        $posicaoAcordo->remover();
        $where = "ac26_acordo = {$acordo->getCodigoAcordo()}";
        $sql = $daoAcordoPosicao->sql_query_file(
            "",
            'ac26_sequencial',
            ' ac26_sequencial DESC LIMIT 2',
            $where
        );
        $rs = $daoAcordoPosicao->sql_record($sql);
        $result = \db_utils::getCollectionByRecord($rs);
        $acordoPosicao = new AcordoPosicao(end($result)->ac26_sequencial);
        $acordoPosicao->setSituacao(1);
        $acordoPosicao->save();
        $acordo->setContratado($novoContratado);
        $acordo->save();
    }
}
