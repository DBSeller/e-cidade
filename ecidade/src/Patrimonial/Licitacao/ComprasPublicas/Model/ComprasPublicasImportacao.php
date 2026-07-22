<?php

namespace ECidade\Patrimonial\Licitacao\ComprasPublicas\Model;

use Exception;
use licitacao;
use stdClass;
use db_utils;
use cl_licitacaoreservacotas;
use cl_pcorcamitemlic;
use fornecedor;

/**
 * Classe para importação dos dados retornados da API
 * @package Ecidade\Patrimonial\Licitacao\ComprasPublicas\Model
 */
class ComprasPublicasImportacao
{

    /**
     * @var licitacao
     */
    private $licitacao;
    
    private $dadosItens;

    private $dadosFornecedores;
    
    private $dadosEventos;

    public function __construct($codigoLicitacao, $itensRetornados, $dadosFornecedores)
    {
        $this->dados             = $itensRetornados;
        $this->licitacao         = new licitacao($codigoLicitacao);
        $this->dadosFornecedores = $dadosFornecedores;
    }
   
    /**
     *
     * @param $codigoLicitacao integer
     * @param $itensRetornados array
     */
    public function validaItens()
    {
        $itensLicitacao  = $this->licitacao->getItens();
        $itensValidar    = [];
        foreach ($itensLicitacao as $itenLicitacao) {
            $itensValidar[$itenLicitacao->getCodigo()] = $itenLicitacao->getItemSolicitacao()->getQuantidade();
        }

        $quantidadeItens   = count($itensLicitacao);
        $quantidadeRetorno = count($this->dados);
        if ($quantidadeItens != $quantidadeRetorno) {
            $mensagem = "Quantidade de itens retornado está diferente";
            $mensagem .= " do sistema {$quantidadeItens} {$quantidadeRetorno}";
            throw new Exception($mensagem);
        }

        foreach ($this->dados as $itenRetornado) {
            if (!array_key_exists($itenRetornado->_id, $itensValidar)) {
                throw new Exception("Item {$itenRetornado->_id} não existe no sistema");
            }

            if (!isset($itenRetornado->IdItemCotaPrincipal)) {
                if ($itensValidar[$itenRetornado->_id] != $itenRetornado->QT_ITENS) {
                    $mensagem = "Quantidade retornada do item {$itenRetornado->_id} está diferente do sistema\n";
                    $mensagem .= "Quantidade Retorno: {$itenRetornado->QT_ITENS}\n";
                    $mensagem .= "Quantidade Sistema: {$itensValidar[$itenRetornado->_id]}\n";
                    throw new Exception($mensagem);
                }
            }

            if (isset($itenRetornado->IdItemCotaPrincipal)) {
                $licitacaoReservaCotas         = new cl_licitacaoreservacotas;
                $where    = "l19_liclicitemorigem = {$itenRetornado->_id}";
                $sSql      = $licitacaoReservaCotas->sql_query_file(null, 'l19_liclicitemreserva', null, $where);
                $reservaCotas = db_query($sSql);
                if (!$reservaCotas || pg_num_rows($reservaCotas) === 0) {
                    throw new Exception('Reserva de cotas não encontrada');
                }

                $reserva = db_utils::fieldsMemory($reservaCotas, 0);
                $reserva->l19_liclicitemreserva;
                if (!array_key_exists($reserva->l19_liclicitemreserva, $itensValidar)) {
                    throw new Exception("Item {$itenRetornado->_id} não existe no sistema");
                }

                if ($itensValidar[$reserva->l19_liclicitemreserva] != $itenRetornado->QT_ITENS) {
                    $mensagem = "Quantidade retornada do item {$reserva->l19_liclicitemreserva}";
                    $mensagem .= " está diferente do sistema.\n";
                    $mensagem .= "Quantidade Retorno: {$itenRetornado->QT_ITENS}\n";
                    $mensagem .= "Quantidade Sistema: {$itensValidar[$reserva->l19_liclicitemreserva]}\n";
                    throw new Exception($mensagem);
                }
            }
        }
    }

    public function importaDados()
    {
        if ($this->licitacao->hasJulgamento()) {
            throw new Exception("Licitação já está julgada");
        }

        $orcamento        = new ComprasPublicasOrcamento();
        
        $orcamento->importar($this->dadosFornecedores);
        $deParaFornecedor = $orcamento->getDeParaFornecedores();
        foreach ($this->dados as $dado) {
            $codigoItem = $dado->_id;
            if (isset($dado->IdItemCotaPrincipal)) {
                $licitacaoReservaCotas                = new cl_licitacaoreservacotas;
                $where        = "l19_liclicitemorigem = {$dado->_id}";
                $sSql         = $licitacaoReservaCotas->sql_query_file(null, 'l19_liclicitemreserva', null, $where);
                $reservaCotas = db_query($sSql);
                if (!$reservaCotas || pg_num_rows($reservaCotas) === 0) {
                    throw new Exception('Reserva de cotas não encontrada');
                }

                $reserva    = db_utils::fieldsMemory($reservaCotas, 0);
                $codigoItem = $reserva->l19_liclicitemreserva;
            }
            
            $retornoItem         = new ComprasPublicasRetornoItens($codigoItem, $dado, $deParaFornecedor);
            $retornoItem->processar();
            $orcamentoItem       = new ComprasPublicasItemOrcamento(
                $orcamento->getCodigoOrcamento(),
                $codigoItem,
                $dado->TP_RESULTADO_ITEM
            );
            $codigoOrcamentoItem = $orcamentoItem->importar();
            $fornecedorVencedor  = null;
            if (count($dado->Vencedores) > 1) {
                throw new Exception("Licitação com mais de um vencedor");
            }

            foreach ($dado->Vencedores as $vencedor) {
                $fornecedorVencedor = $deParaFornecedor[$vencedor->IdFornecedor];
                $valor              = $vencedor->ValorTotal;
                $valorUnitario      = $vencedor->ValorUnitario;
                $cancelado          = $vencedor->Cancelado;
                $percentualDesconto = $vencedor->ValorEsconto;
            }

            foreach ($retornoItem->getPropostas() as $proposta) {
                $fornecedor = new ComprasPublicasFornecedorOrcamento(
                    $proposta->getFornecedor(),
                    $codigoOrcamentoItem,
                    $proposta->getValorTotal(),
                    $dado->QT_ITENS,
                    $proposta->getMarca(),
                    $proposta->getValorUnitario(),
                    $proposta->getData(),
                    $proposta->getDesconto()
                );

                $resultadosnaogerajulgamento = ['F', 'N'];
                $fornecedor->setVencedor($fornecedorVencedor, $cancelado);
                $fornecedor->setGeraJulgamento(in_array(
                    $dado->TP_RESULTADO_ITEM,
                    $resultadosnaogerajulgamento
                )?false:true);
                if ($fornecedor->isVencedor($proposta->getFornecedor())) {
                    $fornecedor->setValor($valor);
                    $fornecedor->setValorUnitario($valorUnitario);
                    if ($percentualDesconto != null && $percentualDesconto > 0) {
                        $fornecedor->setPercentualDesonto($percentualDesconto);
                    }
                    $fornecedor->save($orcamento->getCodigoLogJulgamento());
                    continue;
                }

                $fornecedor->save($orcamento->getCodigoLogJulgamento());
            }
        }

        $this->licitacao->alterarSituacao(1, "Retorno do Compras Públicas");
    }
    
    public function validaItensEditados()
    {
        $itensLicitacao  = $this->licitacao->getItens();
        $itensValidar    = [];
        foreach ($itensLicitacao as $itenLicitacao) {
            $itensValidar[$itenLicitacao->getCodigo()] = $itenLicitacao->getItemSolicitacao()->getQuantidade();
        }
        
        $quantidadeItens   = count($itensLicitacao);
        $quantidadeRetorno = count($this->dados);
        if ($quantidadeItens != $quantidadeRetorno) {
            $mensagem = "Quantidade de itens retornado está diferente";
            $mensagem .= " do sistema {$quantidadeItens} {$quantidadeRetorno}";
            throw new Exception($mensagem);
        }
        
        foreach ($this->dados as $itenRetornado) {
            if (!array_key_exists($itenRetornado->codigo, $itensValidar)) {
                throw new Exception("Item {$itenRetornado->codigo} não existe no sistema");
            }
        }
    }

    public function validaValoresEditados($aValores, $sAgrupador)
    {
  
        $nValor = 0;
        foreach ($aValores as $oValor) {
            if ($oValor->lote == $sAgrupador) {
                $nValor += $oValor->valorTotal;
            }
        }
     
        return $nValor;
    }

    public function importaDadosEditados()
    {
        if ($this->licitacao->hasJulgamento()) {
            throw new Exception("Licitação já está julgada");
        }
         
        $orcamento = new ComprasPublicasOrcamento();
        $orcamento->importar($this->dadosFornecedores);
        $deParaFornecedor = $orcamento->getDeParaFornecedores();
        
        foreach ($this->dados as $dado) {
            $nValorAgrupado = $this->validaValoresEditados($this->dados, $dado->lote);
            if ($dado->valorTotalJulgado < $nValorAgrupado || $dado->valorTotalJulgado > $nValorAgrupado) {
                $mensagem  = "A soma dos valores do lote devem igualar o valor julgado.";
                $mensagem .= " {$dado->valorTotalJulgado} diferente de {$nValorAgrupado}";
                throw new Exception($mensagem);
            }

            $codigoItem = $dado->codigo;
            $retornoItem         = new ComprasPublicasRetornoItens($codigoItem, $dado, $deParaFornecedor);
            $retornoItem->processar();
            $orcamentoItem       = new ComprasPublicasItemOrcamento(
                $orcamento->getCodigoOrcamento(),
                $codigoItem,
                $dado->resultado
            );
            $codigoOrcamentoItem = $orcamentoItem->importar();
            
            $fornecedor = new ComprasPublicasFornecedorOrcamento(
                $deParaFornecedor[$dado->fornecedor],
                $codigoOrcamentoItem,
                $dado->valorTotal,
                $dado->quantidade,
                null,
                $dado->valorUnitario,
                date("Y-m-d"),
                0
            );

            $fornecedor->setVencedor($deParaFornecedor[$dado->fornecedor], false);
            $fornecedor->save($orcamento->getCodigoLogJulgamento());
        }

        $this->licitacao->alterarSituacao(1, "Retorno do Compras Públicas");
    }
}
