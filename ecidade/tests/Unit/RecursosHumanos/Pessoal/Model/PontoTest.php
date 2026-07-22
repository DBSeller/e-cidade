<?php


namespace Tests\Unit\RecursosHumanos\Pessoal\Model;


use Exception;
use Ponto;
use Tests\TestCase;

class PontoTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testDeveRetornarTabelaPelaSigla()
    {
        self::assertEquals('pontofx', Ponto::buscaTabelaPorSigla('fx'));
        self::assertEquals('pontofx', Ponto::buscaTabelaPorSigla('Rfx'));
        self::assertEquals('pontofs', Ponto::buscaTabelaPorSigla('fs'));
        self::assertEquals('pontofs', Ponto::buscaTabelaPorSigla('Rfs'));
        self::assertEquals('pontofa', Ponto::buscaTabelaPorSigla('fa'));
        self::assertEquals('pontofa', Ponto::buscaTabelaPorSigla('Rfa'));
        self::assertEquals('pontofe', Ponto::buscaTabelaPorSigla('fe'));
        self::assertEquals('pontofe', Ponto::buscaTabelaPorSigla('Rfe'));
        self::assertEquals('pontocom', Ponto::buscaTabelaPorSigla('com'));
        self::assertEquals('pontocom', Ponto::buscaTabelaPorSigla('Rcom'));
        self::assertEquals('pontof13', Ponto::buscaTabelaPorSigla('f13'));
        self::assertEquals('pontof13', Ponto::buscaTabelaPorSigla('Rf13'));
        self::assertEquals('pontofr', Ponto::buscaTabelaPorSigla('fr'));
        self::assertEquals('pontofr', Ponto::buscaTabelaPorSigla('Rfr'));
        self::assertEquals('pontoprovfe', Ponto::buscaTabelaPorSigla('provfe'));
        self::assertEquals('pontoprovfe', Ponto::buscaTabelaPorSigla('Rprovfe'));
        self::assertEquals('pontoprovf13', Ponto::buscaTabelaPorSigla('provf13'));
        self::assertEquals('pontoprovf13', Ponto::buscaTabelaPorSigla('Rprovf13'));
    }
}
