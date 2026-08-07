<?php


namespace App\Domain\Financeiro\Contabilidade\Relatorios\Balancete\Despesa;

use ECidade\File\Csv\Dumper\Dumper;

/**
 * Class BalanceteDespesaCsv
 * @package App\Domain\Financeiro\Contabilidade\Relatorios\Balancete\Despesa
 */
class BalanceteDespesaCsv extends Dumper
{
    private $dados = [];

    public function setDados(array $dados)
    {
        $this->dados = $dados;
    }

    public function emitir()
    {
        $filename = sprintf('tmp/balancete-despesa-%s.csv', time());
        $this->dumpToFile($this->organizarDados(), $filename);
        return [
            'csv' => $filename,
            'csvLinkExterno' => ECIDADE_REQUEST_PATH . $filename
        ];
    }

    private function organizarDados()
    {
        $dadosImprimir = [$this->cabecalho()];

        foreach ($this->dados as $dado) {
            foreach ($dado->recursos as $codigoRecurso => $recurso) {
                if ($dado->recurso === $codigoRecurso) {
                    $dadosImprimir[] = $this->criaLinhaPrincipal($dado, $recurso);
                } else {
                    $dadosImprimir[] = $this->criaLinhaSecundaria($dado, $recurso);
                }
            }
        }

        return $dadosImprimir;
    }

    private function cabecalho()
    {
        return [
            'Dotação',
            'Órgão',
            'Descrição Órgão',
            'Unidade',
            'Descrição Unidade',
            'Função',
            'Descrição Função',
            'Subfunção',
            'Descrição Subfunção',
            'Programa',
            'Descrição Programa',
            'Projeto/Atividade',
            'Descrição Projeto/Atividade',
            'Elemento',
            'Descrição Elemento',
            'Recurso',
            'Descrição Recurso',
            'Complemento',
            'Descrição Complemento',
            'Instituição',

            'Empenhado Mês',
            'Anulado Mês',
            'Empenho Líquido Mês',
            'Liquidado Mês',
            'Pago Mês',

            'Empenhado Ano',
            'Anulado Ano',
            'Empenho Líquido Ano',
            'Liquidado Ano',
            'Pago Ano',

            'Saldo Inicial',
            'Suplementação',
            'Crédito Especiais',
            'Reduções',
            'Total Créditos',
            'Saldo Disponível',
            'Á Liquidar',
            'Á Pagar Liquidado',
        ];
    }

    private function criaLinhaPrincipal($dado, $recurso)
    {
        $totalCreditos = $dado->saldo_inicial + $dado->suplementado + $dado->suplementado_especial - $dado->reducoes;
        return [
            $dado->reduzido,
            $dado->orgao,
            $dado->descricao_orgao,
            $dado->unidade,
            $dado->descricao_unidade,
            $dado->funcao,
            $dado->descricao_funcao,
            $dado->subfuncao,
            $dado->descricao_subfuncao,
            $dado->programa,
            $dado->descricao_programa,
            $dado->projeto,
            $dado->descricao_projeto,
            $dado->elemento,
            $dado->descricao_elemento,
            $recurso->recurso,
            $recurso->descricao_recurso,
            $recurso->complemento,
            $recurso->descricao_complemento,
            $dado->nome_instituicao,

            db_formatar($recurso->valores->empenhado, 'f'),
            db_formatar($recurso->valores->anulado, 'f'),
            db_formatar($recurso->valores->empenhado_liquido, 'f'),
            db_formatar($recurso->valores->liquidado, 'f'),
            db_formatar($recurso->valores->pago, 'f'),

            db_formatar($recurso->valores->empenhado_acumulado, 'f'),
            db_formatar($recurso->valores->anulado_acumulado, 'f'),
            db_formatar($recurso->valores->empenhado_liquido_acumulado, 'f'),
            db_formatar($recurso->valores->liquidado_acumulado, 'f'),
            db_formatar($recurso->valores->pago_acumulado, 'f'),

            db_formatar($dado->saldo_inicial, 'f'),
            db_formatar($dado->suplementado, 'f'),
            db_formatar($dado->suplementado_especial, 'f'),
            db_formatar($dado->reducoes, 'f'),
            db_formatar($dado->saldo_disponivel, 'f'),
            db_formatar($totalCreditos, 'f'),
            db_formatar($dado->a_liquidar, 'f'),
            db_formatar($dado->a_pagar, 'f'),
            db_formatar($dado->a_pagar_liquidado, 'f'),
        ];
    }

    private function criaLinhaSecundaria($dado, $recurso)
    {
        return [
            $dado->reduzido,
            $dado->orgao,
            $dado->descricao_orgao,
            $dado->unidade,
            $dado->descricao_unidade,
            $dado->funcao,
            $dado->descricao_funcao,
            $dado->subfuncao,
            $dado->descricao_subfuncao,
            $dado->programa,
            $dado->descricao_programa,
            $dado->projeto,
            $dado->descricao_projeto,
            $dado->elemento,
            $dado->descricao_elemento,
            $recurso->recurso,
            $recurso->descricao_recurso,
            $recurso->complemento,
            $recurso->descricao_complemento,
            $dado->nome_instituicao,

            db_formatar($recurso->valores->empenhado, 'f'),
            db_formatar($recurso->valores->anulado, 'f'),
            db_formatar($recurso->valores->empenhado_liquido, 'f'),
            db_formatar($recurso->valores->liquidado, 'f'),
            db_formatar($recurso->valores->pago, 'f'),

            db_formatar($recurso->valores->empenhado_acumulado, 'f'),
            db_formatar($recurso->valores->anulado_acumulado, 'f'),
            db_formatar($recurso->valores->empenhado_liquido_acumulado, 'f'),
            db_formatar($recurso->valores->liquidado_acumulado, 'f'),
            db_formatar($recurso->valores->pago_acumulado, 'f'),
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
        ];
    }
}
