<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 31/08/18
 * Time: 09:30
 */

namespace ECidade\RecursosHumanos\ESocial\Mapeadores\Tabelas;

class EstadoCivil implements TabelasInterface
{
    /**
     * index é o código do e-cidade
     * valor é o código do eSocial
     * @var array
     */
    protected $estadoCivil = array(
        1 => 1,
        2 => 2,
        3 => 5,
        4 => 4,
        5 => 3,
    );

    /**
     * Retorna o valor da tabela do esocial equivalente ao valor de um dado no e-cidade
     * @param $valor do dado no e-cidade
     * @return mixed
     */
    public function getValue($valor)
    {
        if (isset($this->estadoCivil[$valor])) {
            return $this->estadoCivil[$valor];
        }

        return null;
    }
}
