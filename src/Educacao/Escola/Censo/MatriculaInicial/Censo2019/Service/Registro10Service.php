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

namespace ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Service;

use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Builder\Registro10Builder;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model\Registro10;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Repository\Registro10Repository;
use Escola;
use Exception;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Validators\Registro10Validator;

class Registro10Service
{
    /**
     * @var Escola
     */
    private $escola;

    /**
     * @var Registro10
     */
    private $registro;

    /**
     * @var Registro00Service
     */
    private $registro00Service;

    /**
     * @var Registro20Service
     */
    private $registro20Service;

    public function __construct()
    {
        $this->registro = new Registro10();
    }

    /**
     * @return Registro10
     */
    public function getRegistro()
    {
        return $this->registro;
    }

    /**
     * @param Escola $escola
     */
    public function setEscola(Escola $escola)
    {
        $this->escola = $escola;
    }

    /**
     * Serviço usado durante a validação
     * @param Registro00Service $registro00Service
     */
    public function setRegistro00Service(Registro00Service $registro00Service)
    {
        $this->registro00Service = $registro00Service;
    }

    /**
     * Serviço usado durante a validação
     * @param Registro20Service $registro20Service
     */
    public function setRegistros20Service(Registro20Service $registro20Service)
    {
        $this->registro20Service = $registro20Service;
    }

    /**
     * @throws Exception
     */
    public function buscarDados()
    {
        $repository = new Registro10Repository();

        $builder = new Registro10Builder();
        $builder->addDadosFormulario($repository->buscarDadosAbaInfraEstrutura($this->escola));
        $builder->addDadosLinguaIndigena($repository->buscarLinguaIndigena($this->escola));
        $builder->addDadosINEP($this->escola->getCodigoInep());

        $this->registro = $builder->build();
    }

    public function validar()
    {
        $validator = new Registro10Validator();

        $validator->setRegistro($this->registro);
        $validator->setRegistro00($this->registro00Service->getRegistro());
        $validator->setRegistros20($this->registro20Service->getRegistros());

        $validator->validar();
    }
}
