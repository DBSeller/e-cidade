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

namespace ECidade\Saude\Laboratorio\Repository;

use CgmFisico;
use CgmJuridico;
use CgmRepository;
use cl_lab_labresp;
use db_utils;
use ECidade\Saude\Laboratorio\Model\Responsavel as ResponsavelModel;
use Exception;
use Laboratorio;
use LaboratorioRepository;
use stdClass;

/**
 * Class Responsavel
 * @package ECidade\Saude\Laboratorio\Repository
 */
class Responsavel extends \BaseClassRepository
{
    /**
     * @param Laboratorio $laboratorio
     * @param $cgm
     * @return ResponsavelModel|null
     * @throws Exception
     */
    public function getByLaboratorioCgm(Laboratorio $laboratorio, $cgm)
    {
        if (!$cgm instanceof CgmFisico && !$cgm instanceof CgmJuridico) {
            throw new Exception('CGM inválido.');
        }

        $responsavel = $this->getInstanceCollectionByLaboratorioCgm($laboratorio, $cgm);

        if ($responsavel instanceof ResponsavelModel) {
            return $responsavel;
        }

        $dao = new cl_lab_labresp();
        $where = array(
          "la06_i_laboratorio = {$laboratorio->getCodigo()}",
          "la06_i_cgm = {$cgm->getCodigo()}"
        );

        $sql = $dao->sql_query_file(null, 'lab_labresp.*', null, implode(' AND ', $where));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Erro ao buscar o responsável pelo no Laboratório.');
        }

        if (pg_num_rows($rs) === 0) {
            return null;
        }

        return $this->makeByStdClass(db_utils::fieldsMemory($rs, 0));
    }

    /**
     * @param Laboratorio $laboratorio
     * @param $cgm
     * @return null
     * @throws Exception
     */
    private function getInstanceCollectionByLaboratorioCgm(Laboratorio $laboratorio, $cgm)
    {
        if (!$cgm instanceof CgmFisico && !$cgm instanceof CgmJuridico) {
            throw new Exception('CGM inválido.');
        }

        foreach ($this->aColecao as $responsavel) {
            if ($responsavel->getLaboratorio()->getCodigo() === $laboratorio->getCodigo()) {
                if ($responsavel->getCgm()->getCodigo() === $cgm->getCodigo()) {
                    return $responsavel;
                }
            }
        }

        return null;
    }

    /**
     * @param stdClass $stdClass
     * @return ResponsavelModel
     * @throws Exception
     */
    protected function makeByStdClass(stdClass $stdClass)
    {
        $responsavel = new ResponsavelModel();
        $responsavel->setCodigo($stdClass->la06_i_codigo);
        $responsavel->setLaboratorio(LaboratorioRepository::getLaboratorioByCodigo($stdClass->la06_i_laboratorio));
        $responsavel->setCgm(CgmRepository::getByCodigo($stdClass->la06_i_cgm));
        $responsavel->setOrgaoClasse($stdClass->la06_c_orgaoclasse);

        self::getInstance()->add($responsavel);

        return $responsavel;
    }
}
