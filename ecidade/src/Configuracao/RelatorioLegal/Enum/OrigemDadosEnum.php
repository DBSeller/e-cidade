<?php

namespace ECidade\Configuracao\RelatorioLegal\Enum;

class OrigemDadosEnum
{
    const SEM_ORIGEM = 0;
    const BALANCETE_RECEITA = 1;
    const BALANCETE_DESPESA = 2;
    const BALANCETE_VERIFICACAO = 3;
    const RESTOS_PAGAR = 4;
    const MSC = 5;
    const BALANCETE_DESPESA_DESDOBRAMENTO = 6;


    /**
     * @var array
     */
    private static $descricoes = array(
        self::SEM_ORIGEM => 'Sem Origem',
        self::BALANCETE_RECEITA => 'Balancete da Receita',
        self::BALANCETE_DESPESA => 'Balancete da Despesa',
        self::BALANCETE_VERIFICACAO => 'Balancete de Verificação',
        self::RESTOS_PAGAR => 'Restos à Pagar',
        self::MSC => 'Matriz Saldo Contábil',
        self::BALANCETE_DESPESA_DESDOBRAMENTO => 'Balancete da Despesa por Desdobramento'
    );

    /**
     * @param mixed $chave
     * @return mixed
     */
    public static function descricaoPorChave($chave)
    {
        return self::$descricoes[$chave];
    }

    /**
     * @return array
     */
    public static function todas()
    {
        return self::$descricoes;
    }

    /**
     * @param $chave
     * @return bool
     */
    public static function existe($chave)
    {
        return array_key_exists($chave, self::$descricoes);
    }
}
