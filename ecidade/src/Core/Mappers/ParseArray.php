<?php


namespace ECidade\Core\Mappers;

/**
 * Class Mapper
 *
 * Criei essa para uso no Censo escolar, como vi reuso no PADRS, resolvi colocar no Core do e-Cidade.
 * Basicamente a classe que estender essa classe deve ter um array $dePara e ao usar o metodo parse informando um
 *  array com chave valor como no array $dePara, retorna um array de valores.
 *
 * Dúvidas de uso, pode ser vista na escrita do censo escolar:
 * src/Educacao/Escola/Censo/MatriculaInicial/Censo2019/Layout/Layout.php:194
 *
 * @package ECidade\Core\Mappers
 */
abstract class ParseArray
{
    protected $dePara = array();

    public function parse(array $dadosRegistro)
    {
        $dados = array();
        foreach ($this->dePara as $item) {
            $dados[] = $dadosRegistro[$item];
        }

        return $dados;
    }
}
