<?php
/**
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

namespace ECidade\Financeiro\Contabilidade\Encerramento;

use DBDate;
use Instituicao;
use UsuarioSistema;

/**
 * Class PeriodoContabil
 * @package ECidade\Financeiro\Contabilidade\Encerramento
 */
class PeriodoContabil
{
    /**
     * @var Instituicao
     */
    private $instituicao;

    /**
     * @var DBDate
     */
    private $data;

    /**
     * @var UsuarioSistema
     */
    private $usuario;

    /**
     * @var integer
     */
    private $exercicio;

    /**
     * @var Repository
     */
    private $repository;

    /**
     * PeriodoContabil constructor.
     * @param Instituicao $instituicao
     * @param DBDate $data
     * @param UsuarioSistema $usuario
     * @param $exercicio
     */
    public function __construct(Instituicao $instituicao, DBDate $data, UsuarioSistema $usuario, $exercicio)
    {
        $this->instituicao = $instituicao;
        $this->data = $data;
        $this->usuario = $usuario;
        $this->exercicio = $exercicio;

        $this->repository = Repository::getInstance();
    }

    /**
     * @throws \DBException
     * @throws \ParameterException
     */
    public function encerrar()
    {
        $dataUltimoEncerramento = $this->repository->getDataUltimoEncerramento(
            $this->instituicao->getSequencial(),
            $this->exercicio
        );

        if (is_null($dataUltimoEncerramento)) {
            $dataUltimoEncerramento = $this->data->getDate();
        }

        $dataUltimoEncerramento = new DBDate($dataUltimoEncerramento);

        if ($dataUltimoEncerramento->getTimeStamp() <=  $this->data->getTimeStamp()) {
            $this->repository->encerrarContabilidadeByPeriodo(
                $this->instituicao->getSequencial(),
                $this->data->getDate(),
                $this->usuario->getCodigo(),
                $this->exercicio
            );
        }
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function cancelarEncerramento()
    {
        $this->repository->cancelarEncerramento($this->instituicao->getCodigo(), $this->data->getDate());
        return true;
    }
}
