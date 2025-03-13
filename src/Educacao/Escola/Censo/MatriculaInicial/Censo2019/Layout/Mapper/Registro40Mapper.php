<?php
/**
 * Created by PhpStorm.
 * User: andri
 * Date: 08/05/2019
 * Time: 10:46
 */

namespace ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Layout\Mapper;

/**
 * Class Registro40Mapper
 * @package ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Layout\Mapper
 */
class Registro40Mapper extends Mapper
{
    protected $dePara = array(
        "Tipo de registro" => "tipoRegistro",
        "Código de escola - Inep" => "codigoInepEscola",
        "Código da pessoa física no sistema próprio" => "codigoPessoa",
        "Identificação única (Inep)" => "codigoInep",
        "Cargo" => "cargo",
        "Critério de acesso ao cargo/função" => "criterioAcesso",
//        "Especificação do critério de acesso" => "especificacaoCriterioAcesso",
        "Situação Funcional/ Regime de contratação/Tipo de vínculo" => "regimeContratacao",
    );
}
