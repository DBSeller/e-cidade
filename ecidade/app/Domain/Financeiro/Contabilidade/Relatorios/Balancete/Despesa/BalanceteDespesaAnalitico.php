<?php

namespace App\Domain\Financeiro\Contabilidade\Relatorios\Balancete\Despesa;

class BalanceteDespesaAnalitico extends BalanceteDespesa
{
    protected $modelo = 'ANALÍTICO';

    protected function imprimir()
    {
        $controleImpressao = [
            'orgao' => null,
            'unidade' => null,
            'funcao' => null,
            'subfuncao' => null,
            'programa' => null,
            'projeto' => null,
            'elemento' => null
        ];
        foreach ($this->dados as $dado) {
            $this->criaHashs($dado);
            $this->quebraPagina();
            if ($controleImpressao['orgao'] != $dado->hashOrgao) {
                $this->imprimeCabecalho();
                $this->imprimeCelula($dado->hashOrgao, $dado->descricao_orgao, true);
                $controleImpressao['orgao'] = $dado->hashOrgao;
            }
            $this->quebraPagina();
            if ($controleImpressao['unidade'] != $dado->hashUnidade) {
                $this->imprimeCelula($dado->hashUnidade, $dado->descricao_unidade, false);
                $controleImpressao['unidade'] = $dado->hashUnidade;
            }
            $this->quebraPagina();
            if ($controleImpressao['funcao'] != $dado->hashFuncao) {
                $this->imprimeCelula($dado->hashFuncao, $dado->descricao_funcao, false);
                $controleImpressao['funcao'] = $dado->hashFuncao;
            }
            $this->quebraPagina();
            if ($controleImpressao['subfuncao'] != $dado->hashSubfuncao) {
                $this->imprimeCelula($dado->hashSubfuncao, $dado->descricao_subfuncao, false);
                $controleImpressao['subfuncao'] = $dado->hashSubfuncao;
            }
            $this->quebraPagina();
            if ($controleImpressao['subfuncao'] != $dado->hashSubfuncao) {
                $this->imprimeCelula($dado->hashSubfuncao, $dado->descricao_subfuncao, false);
                $controleImpressao['subfuncao'] = $dado->hashSubfuncao;
            }
            $this->quebraPagina();
            if ($controleImpressao['programa'] != $dado->hashPrograma) {
                $this->imprimeCelula($dado->hashPrograma, $dado->descricao_programa, false);
                $controleImpressao['programa'] = $dado->hashPrograma;
            }
            $this->quebraPagina();
            if ($controleImpressao['projeto'] != $dado->hashProjeto) {
                $this->imprimeCelula($dado->hashProjeto, $dado->descricao_projeto, false);
                $controleImpressao['projeto'] = $dado->hashProjeto;
            }

            $altura = (count($dado->recursos) + 3 ) * $this->hLinha;
            if ($this->getAvailHeight() < ($this->hQuebraPagina + $altura)) {
                $this->imprimeCabecalho();
            }
            if ($controleImpressao['elemento'] != $dado->hashElemento) {
                $elemento = sprintf('%s - %s', $dado->elemento, $dado->descricao_elemento);
                $this->imprimeCelula("Dotação: " .$dado->reduzido, $elemento);
                $controleImpressao['elemento'] = $dado->hashElemento;
            }

            $this->regular();
            $this->imprimeRecursos($dado->recursos);

            $this->imprimeSaldos($dado);
            $this->ln(2);
        }

        $this->imprimeTotalizador($this->totalizador);
        $this->imprimirAssinaturas();
    }

    private function criaHashs($dado)
    {
        $dado->hashOrgao = str_pad($dado->orgao, 2, '0', STR_PAD_LEFT);
        $dado->hashUnidade = sprintf('%s.%s', $dado->hashOrgao, str_pad($dado->unidade, 2, '0', STR_PAD_LEFT));
        $dado->hashFuncao = sprintf('%s.%s', $dado->hashUnidade, str_pad($dado->funcao, 2, '0', STR_PAD_LEFT));
        $dado->hashSubfuncao = sprintf('%s.%s', $dado->hashFuncao, str_pad($dado->subfuncao, 3, '0', STR_PAD_LEFT));
        $dado->hashPrograma = sprintf('%s.%s', $dado->hashSubfuncao, str_pad($dado->programa, 4, '0', STR_PAD_LEFT));
        $dado->hashProjeto = sprintf('%s.%s', $dado->hashPrograma, str_pad($dado->projeto, 4, '0', STR_PAD_LEFT));
        $dado->hashElemento = sprintf('%s.%s.%s', $dado->hashProjeto, $dado->elemento, $dado->reduzido);
    }

    private function imprimeCelula($hash, $descricao, $bold = false)
    {
        if ($bold) {
            $this->bold();
        }
        $this->Cell(40, $this->hLinha, $hash);
        $this->Cell(112, $this->hLinha, $descricao, 0, 1);
        $this->regular();
    }
}
