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

use CgmBase;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use ECidade\RecursosHumanos\ESocial\Transformer\R2020;
use ECidade\RecursosHumanos\ESocial\Transformer\R2099;
use ECidade\RecursosHumanos\ESocial\Transformer\S2200;
use ECidade\RecursosHumanos\ESocial\Transformer\S2205;
use ECidade\RecursosHumanos\ESocial\Transformer\S2206;
use ECidade\RecursosHumanos\ESocial\Transformer\S2300;
use ECidade\RecursosHumanos\ESocial\Transformer\S2306;
use Exception;
use ServidorRepository;
use stdClass;

/**
 * Class SugestaoPreenchimento
 * Factory
 * @package ECidade\RecursosHumanos\ESocial\Factory
 */
class SugestaoPreenchimento
{
    /**
     * @var int
     */
    private $matricula;
    /**
     * @var CgmBase
     */
    private $cgmResponsavel;
    /**
     *
     * @var stdClass
     */
    private $parametros;

    /**
     * Retorna a classe responsavel para buscar a sugestão de preenchimento de um formulário.
     * Deve ser informado o filtro necessário
     * @param $tipo
     * @return R2020|R2099|S2200|S2205|S2206|S2300|S2306|null
     * @throws Exception
     */
    public function porTipo($tipo)
    {
        switch ($tipo) {
            case Tipo::SERVIDOR:
                $this->validarMatricula();
                return new S2200($this->matricula);
            case Tipo::TRABALHADOR_SEM_VINCULO:
                $this->validarMatricula();
                return new S2300($this->matricula);
            case TIPO::ALTERACAO_SERVIDOR:
                $this->validarMatricula();
                return new S2205($this->matricula);
            case Tipo::ALTERACAO_CONTRATUAL:
                $this->validarMatricula();
                return new S2206($this->matricula, $this->cgmResponsavel);
            case Tipo::ALTERACAO_TRABALHADOR_SEM_VINCULO:
                $this->validarMatricula();
                return new S2306(ServidorRepository::getInstanciaByCodigo($this->matricula), $this->cgmResponsavel);
            case Tipo::EFD_SERVICOS_PRESTADOS:
                return new R2020($this->cgmResponsavel, $this->parametros);
            case Tipo::EFD_FECHAMENTO_PERIODICOS:
                return new R2099($this->parametros);
            default:
                return null;
        }
    }

    /**
     * @throws Exception
     */
    private function validarMatricula()
    {
        if (empty($this->matricula)) {
            throw new Exception('Informe a matrícula para buscar a sugestão de preenchimento.');
        }
    }

    /**
     * @param int $matricula
     */
    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;
    }

    public function setCgmResponsavel($cgmResponsavel)
    {
        $this->cgmResponsavel = $cgmResponsavel;
    }

    /**
     * @param stdClass $parametros
     */
    public function setParametros(stdClass $parametros)
    {
        $this->parametros = $parametros;
    }
}
