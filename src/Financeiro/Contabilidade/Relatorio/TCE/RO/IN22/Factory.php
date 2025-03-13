<?php
/**
 * Created by PhpStorm.
 * User: robson
 * Date: 2020-02-05
 * Time: 16:40
 */

namespace ECidade\Financeiro\Contabilidade\Relatorio\TCE\RO\IN22;

class Factory
{

    /**
     * @param $codigoRelatorio
     * @param $ano
     * @param $periodo
     * @param $instituicoes
     * @param $usuario
     * @return In22
     * @throws \Exception
     */
    public static function getInstance($codigoRelatorio, $ano, $periodo, $instituicoes, $usuario)
    {
        $anexo = null;
        switch ($codigoRelatorio) {
            case Anexo1::CODIGO_RELATORIO:
                $anexo = new Anexo1();
                break;
            case Anexo2::CODIGO_RELATORIO:
                $anexo = new Anexo2();
                break;
            case Anexo3::CODIGO_RELATORIO:
                $anexo = new Anexo3();
                break;
            case Anexo4::CODIGO_RELATORIO:
                $anexo = new Anexo4();
                break;
            case Anexo5::CODIGO_RELATORIO:
                $anexo = new Anexo5();
                break;
            case Anexo6::CODIGO_RELATORIO:
                $anexo = new Anexo6();
                break;
            case Anexo7::CODIGO_RELATORIO:
                $anexo = new Anexo7();
                break;
            case Anexo8::CODIGO_RELATORIO:
                $anexo = new Anexo8();
                break;
            case Anexo9::CODIGO_RELATORIO:
                $anexo = new Anexo9();
                break;
            case Anexo10::CODIGO_RELATORIO:
                $anexo = new Anexo10();
                break;
            case Anexo10A::CODIGO_RELATORIO:
                $anexo = new Anexo10A();
                break;
            case Anexo11::CODIGO_RELATORIO:
                $anexo = new Anexo11();
                break;
            case Anexo11A::CODIGO_RELATORIO:
                $anexo = new Anexo11A();
                break;
            case Anexo11B::CODIGO_RELATORIO:
                $anexo = new Anexo11B();
                break;
            case Anexo11C::CODIGO_RELATORIO:
                $anexo = new Anexo11C();
                break;
            case Anexo12::CODIGO_RELATORIO:
                $anexo = new Anexo12();
                break;
            case Anexo13::CODIGO_RELATORIO:
                $anexo = new Anexo13();
                break;
            case Anexo13A::CODIGO_RELATORIO:
                $anexo = new Anexo13A();
                break;
            case Anexo14::CODIGO_RELATORIO:
                $anexo = new Anexo14();
                break;
            case Anexo15::CODIGO_RELATORIO:
                $anexo = new Anexo15();
                break;
            case Anexo16::CODIGO_RELATORIO:
                $anexo = new Anexo16();
                break;
            default:
                throw new \Exception('Relatório não encontrado.');
                break;
        }

        $anexo->setAno($ano);
        $anexo->setPeriodo($periodo);
        $anexo->setInstituicoes($instituicoes);
        $anexo->setUsuario($usuario);
        return $anexo;
    }
}
