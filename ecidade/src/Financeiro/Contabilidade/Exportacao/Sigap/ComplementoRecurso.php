<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\Sigap;

class ComplementoRecurso
{

    public static $listDotacoes = [];

    /**
     * realizada o de para do recurso
     * @param $dotacao
     * @param $ano
     * @param $recurso
     * @return string|string[]
     * @throws \Exception
     */
    public static function getComplementoPelaDotacao($dotacao, $ano, $recurso)
    {

        $listaDotacoes = self::getArquivoDePara($ano);
        if (!empty($listaDotacoes[$dotacao])) {
            $recurso = substr_replace($recurso, $listaDotacoes[$dotacao], 4);
        }
        return $recurso;
    }

    /**
     * Retorna os dados do Arquivo de de/para
     * @param $ano
     * @return array
     * @throws \Exception
     */
    public static function getArquivoDePara($ano)
    {
        if (empty(self::$listDotacoes)) {
            $nomeChave = "sigap_complemento_recurso_{$ano}";
            $conteudo = \ECidade\Configuracao\Opcao\Opcao::get($nomeChave, $ano);
            $nomeArquivo = 'tmp/'.$nomeChave.date('Ymdhis').'csv';
            file_put_contents($nomeArquivo, $conteudo);
            $linhas = file($nomeArquivo);
            unlink($nomeArquivo);
            foreach ($linhas as $linha) {
                $dadosLinha = explode(';', str_replace("\n", '', $linha));
                $dotacao = trim($dadosLinha[0]);
                $complemento = trim($dadosLinha[1]);
                self::$listDotacoes[$dotacao] = $complemento;
            }
        }
        return self::$listDotacoes;
    }
}
