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


namespace ECidade\Educacao\Escola\Censo\MatriculaInicial;

use ECidade\Educacao\Escola\Censo\Censo;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Service\Registro00Service;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Service\Registro10Service;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Service\Registro20Service;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Service\Registro30Service;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Service\Registro40Service;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Service\Registro50Service;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Service\Registro60Service;
use ECidade\Educacao\Escola\Censo\Registry\LogCensoRegistry;
use Escola;
use Exception;

class DadosExportacao implements ExportacaoBuilderInterface
{
    /**
     * @var Censo
     */
    private $censo;

    /**
     * @var Escola
     */
    private $escola;

    /**
     * @var Registro00Service
     */
    private $registro00;

    /**
     * @var Registro10Service
     */
    private $registro10;

    /**
     * @var Registro20Service
     */
    private $registro20;

    /**
     * @var Registro30Service
     */
    private $registro30;

    /**
     * @var Registro40Service
     */
    private $registro40;

    /**
     * @var Registro50Service
     */
    private $registro50;

    /**
     * @var Registro60Service
     */
    private $registro60;

    /**
     * @return Registro00Service
     */
    public function getRegistro00()
    {
        return $this->registro00;
    }

    /**
     * @return Registro10Service
     */
    public function getRegistro10()
    {
        return $this->registro10;
    }

    /**
     * @return Registro20Service
     */
    public function getRegistro20()
    {
        return $this->registro20;
    }

    /**
     * @return Registro30Service
     */
    public function getRegistro30()
    {
        return $this->registro30;
    }

    /**
     * @return Registro40Service
     */
    public function getRegistro40()
    {
        return $this->registro40;
    }

    /**
     * @return Registro50Service
     */
    public function getRegistro50()
    {
        return $this->registro50;
    }

    /**
     * @return Registro60Service
     */
    public function getRegistro60()
    {
        return $this->registro60;
    }

    /**
     * @param Censo $censo
     */
    public function setCenso(Censo $censo)
    {
        $this->censo = $censo;
    }

    /**
     * @param Escola $escola
     */
    public function setEscola(Escola $escola)
    {
        $this->escola = $escola;
    }


    /**
     * @throws Exception
     */
    public function processar()
    {
        $this->buscarDados();
        $this->preencherRequisitos();
        return $this->validarRegistros();
    }

    /**
     * @throws Exception
     */
    private function buscarDados()
    {
        $this->buscarDadosRegistro00();
        $this->buscarDadosRegistro10();
        $this->buscarDadosRegistro20();
        $this->buscarDadosRegistro30();
        $this->buscarDadosRegistro40();
        $this->buscarDadosRegistro50();
        $this->buscarDadosRegistro60();
    }

    /**
     * @throws Exception
     */
    private function buscarDadosRegistro00()
    {
        $this->registro00 = new Registro00Service();
        $this->registro00->setEscola($this->escola);
        $this->registro00->setCenso($this->censo);
        $this->registro00->buscarDados();
    }

    /**
     * @throws Exception
     */
    private function buscarDadosRegistro10()
    {
        $this->registro10 = new Registro10Service();
        $this->registro10->setEscola($this->escola);
        $this->registro10->buscarDados();
    }

    /**
     * @throws Exception
     */
    private function buscarDadosRegistro20()
    {

        $this->registro20 = new Registro20Service();
        $this->registro20->setEscola($this->escola);
        $this->registro20->setCenso($this->censo);
        $this->registro20->buscarDados();
    }

    /**
     * @throws Exception
     */
    private function buscarDadosRegistro30()
    {
        $this->registro30 = new Registro30Service();
        $this->registro30->setEscola($this->escola);
        $this->registro30->setCenso($this->censo);
        $this->registro30->buscarDados();
    }

    /**
     * @throws Exception
     */
    private function buscarDadosRegistro40()
    {
        $this->registro40 = new Registro40Service();
        $this->registro40->setEscola($this->escola);
        $this->registro40->buscarDados();
    }

    /**
     * @throws Exception
     */
    private function buscarDadosRegistro50()
    {
        $this->registro50 = new Registro50Service();
        $this->registro50->setEscola($this->escola);
        $this->registro50->setCenso($this->censo);
        $this->registro50->buscarDados();
    }

    /**
     * @throws Exception
     */
    private function buscarDadosRegistro60()
    {
        $this->registro60 = new Registro60Service();
        $this->registro60->setCenso($this->censo);
        $this->registro60->buscarDados();
    }

    private function preencherRequisitos()
    {
        // Definir serviços necessários para a validação
        $this->registro10->setRegistro00Service($this->registro00);
        $this->registro10->setRegistros20Service($this->registro20);

        $this->registro20->setRegistro00Service($this->registro00);
        $this->registro20->setRegistro10Service($this->registro10);
        $this->registro20->setRegistro50Service($this->registro50);
        $this->registro20->setRegistro60Service($this->registro60);

        $this->registro30->setRegistro00Service($this->registro00);
        $this->registro30->setRegistro20Service($this->registro20);
        $this->registro30->setRegistro40Service($this->registro40);
        $this->registro30->setRegistro50Service($this->registro50);
        $this->registro30->setRegistro60Service($this->registro60);

        $this->registro40->setRegistro00Service($this->registro00);
        $this->registro40->setRegistro30Service($this->registro30);

        $this->registro50->setRegistro00Service($this->registro00);
        $this->registro50->setRegistro20Service($this->registro20);
        $this->registro50->setRegistro30Service($this->registro30);

        $this->registro60->setRegistro00Service($this->registro00);
        $this->registro60->setRegistro20Service($this->registro20);
        $this->registro60->setRegistro30Service($this->registro30);
    }

    private function validarRegistros()
    {
        $this->validarRegistro00();
        $this->validarRegistro10();
        $this->validarRegistro20();
        $this->validarRegistro30();
        $this->validarRegistro40();
        $this->validarRegistro50();
        $this->validarRegistro60();

        return !LogCensoRegistry::get(LogCensoRegistry::MATRICULA_INICIAL)->exist();
    }

    private function validarRegistro00()
    {
        $this->registro00->validar();
    }

    private function validarRegistro10()
    {
        $this->registro10->validar();
    }

    private function validarRegistro20()
    {
        $this->registro20->validar();
    }

    private function validarRegistro30()
    {
        $this->registro30->validar();
    }

    private function validarRegistro40() {
        $this->registro40->validar();
    }

    private function validarRegistro50() {
        $this->registro50->validar();
    }

    private function validarRegistro60() {
        $this->registro60->validar();
    }
}
