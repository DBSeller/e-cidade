<?php

namespace ECidade\Financeiro\Contabilidade\MatrizSaldoContabil;

use DBString;
use Exception;
use Instituicao;

class DeParaAtributos
{
    /**
     * @var array
     */
    private static $deParaFR = array();

    /**
     * @var array
     */
    private static $deParaPO = array();

    /**
     * @param int $ano
     */
    private static function carregarFR($ano)
    {
        if (empty(static::$deParaFR[$ano])) {
            $caminho = "config/financeiro/siconfi/recursos/recurso_{$ano}.csv";

            if (file_exists($caminho)) {
                $conteudo = file_get_contents($caminho);
                $linhas = explode("\n", $conteudo);

                static::$deParaFR[$ano] = array();

                foreach ($linhas as $linha) {
                    $dadosLinha = explode('#', $linha);

                    if (empty(static::$deParaFR[$ano][$dadosLinha[2]])) {
                        static::$deParaFR[$ano][$dadosLinha[2]] = array();
                    }

                    static::$deParaFR[$ano][$dadosLinha[2]][] = $dadosLinha[0];
                }
            }
        }
    }

    /**
     * @param int $ano
     * @param mixed $codigoSiconfi
     * @return array
     */
    public static function getFR($ano, $codigoSiconfi)
    {
        static::carregarFR($ano);

        if (empty(static::$deParaFR[$ano])) {
            return array();
        }

        $dePara = array();

        if (DBString::contem($codigoSiconfi, '_')) {
            $pattern = self::montarRegex($codigoSiconfi);

            foreach (static::$deParaFR[$ano] as $indexSiconfi => $codigos) {
                if (preg_match($pattern, $indexSiconfi)) {
                    $dePara = array_merge($dePara, $codigos);
                }
            }

            return $dePara;
        }

        return empty(static::$deParaFR[$ano][$codigoSiconfi]) ? array() : static::$deParaFR[$ano][$codigoSiconfi];
    }


    /**
     * @param Instituicao[] $instituicoes
     * @throws Exception
     */
    private static function carregarPO(array $instituicoes)
    {
        if (empty(static::$deParaPO) && !empty($instituicoes)) {
            $codigoInstituicoes = implode(', ', array_map(function (Instituicao $instituicao) {
                return $instituicao->getCodigo();
            }, $instituicoes));

            $sql = "
                SELECT db_config.codtrib, db_tipoinstit.db21_codigosiconfi AS codigo_siconfi
                FROM db_tipoinstit
                         JOIN db_config ON db_config.db21_tipoinstit = db_tipoinstit.db21_codtipo
                WHERE db_config.codigo IN ({$codigoInstituicoes})
            ";

            $rs = db_query($sql);

            if (!$rs) {
                throw new Exception('Não foi possível buscar as informações de Poder/Órgão.');
            }

            while ($registro = pg_fetch_object($rs)) {
                if (empty(static::$deParaPO[$registro->codigo_siconfi])) {
                    static::$deParaPO[$registro->codigo_siconfi] = array();
                }

                static::$deParaPO[$registro->codigo_siconfi][] = $registro->codtrib;
            }
        }
    }

    /**
     * @param Instituicao[] $instituicoes
     * @param mixed $codigoSiconfi
     * @return array
     * @throws Exception
     */
    public static function getPO(array $instituicoes, $codigoSiconfi)
    {
        static::carregarPO($instituicoes);
        return empty(static::$deParaPO[$codigoSiconfi]) ? array() : static::$deParaPO[$codigoSiconfi];
    }

    /**
     * @param $codigoSiconfi
     * @return string
     */
    private static function montarRegex($codigoSiconfi)
    {
        $pattern = '';
        $caracteres = str_split($codigoSiconfi);
        $underlines = 0;
        $concatenouPattern = false;

        foreach ($caracteres as $chave => $caractere) {
            if ($caractere === '_') {
                $underlines++;
                $concatenouPattern = false;

                if (count($caracteres) - 1 === $chave) {
                    $pattern .= "[0-9]{{$underlines}}";
                }
            } else {
                if (!$concatenouPattern && $underlines > 0) {
                    $pattern .= "[0-9]{{$underlines}}{$caractere}";
                    $concatenouPattern = true;
                } else {
                    $pattern .= $caractere;
                }

                $underlines = 0;
            }
        }
        return $pattern;
    }
}
