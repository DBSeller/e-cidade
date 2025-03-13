<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\RecursosHumanos\ESocial\Factory;

use ECidade\RecursosHumanos\ESocial\Mapeadores\Tabelas\CategoriaCNH;
use ECidade\RecursosHumanos\ESocial\Mapeadores\Tabelas\EstadoCivil;
use ECidade\RecursosHumanos\ESocial\Mapeadores\Tabelas\GrauInstrucao;
use ECidade\RecursosHumanos\ESocial\Mapeadores\Tabelas\OnusCedencia;
use ECidade\RecursosHumanos\ESocial\Mapeadores\Tabelas\Paises;
use ECidade\RecursosHumanos\ESocial\Mapeadores\Tabelas\RacaCor;
use ECidade\RecursosHumanos\ESocial\Mapeadores\Tabelas\Sexo;
use ECidade\RecursosHumanos\ESocial\Mapeadores\Tabelas\TipoDependente;
use ECidade\RecursosHumanos\ESocial\Mapeadores\Tabelas\TipoLogradouro;
use ECidade\RecursosHumanos\ESocial\Mapeadores\Tabelas\TipoUnidadePagamento;

/**
 * Class CamposS2300
 * Encontra o mapeador correto para cada campo
 *
 * @package ECidade\RecursosHumanos\ESocial\Factory
 */
class CamposS2300
{
    /**
     * @param $campo
     * @return EstadoCivil|GrauInstrucao|Paises|RacaCor|TipoDependente|TipoLogradouro|TipoUnidadePagamento
     */
    public static function getCampo($campo) {

        switch (mb_strtolower($campo)) {
            case 'paisnac':
            case 'paisnascto':
                return new Paises();
                break;
            case 'racacor':
                return new RacaCor();
                break;
            case 'estciv':
                return new EstadoCivil();
                break;
            case 'grauinstr':
                return new GrauInstrucao();
                break;
            case 'categoriacnh':
                return new CategoriaCNH();
                break;
            case 'tpdep':
                return new TipoDependente();
                break;
            case 'undsalfixo':
                return new TipoUnidadePagamento();
                break;
            case 'tplograd':
                return new TipoLogradouro();
                break;
            case 'infonus':
                return new OnusCedencia();
                break;
            case 'sexo':
                return new Sexo();
                break;
        }
    }

}
