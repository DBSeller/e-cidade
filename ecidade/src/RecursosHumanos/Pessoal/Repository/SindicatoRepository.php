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

namespace ECidade\RecursosHumanos\Pessoal\Repository;

use cl_rhsindicato;
use ECidade\RecursosHumanos\Pessoal\Model\Sindicato;
use Exception;

/**
 * Class SindicatoRepository
 * @package ECidade\RecursosHumanos\Pessoal\Repository
 */
class SindicatoRepository
{
    /**
     * @var array
     */
    private $scopes = array();

    /**
     * @param $id
     * @param array $columns
     * @return bool|Sindicato
     * @throws Exception
     */
    public static function find($id, $columns = array('*'))
    {
        $dao = new cl_rhsindicato;
        $sql = $dao->sql_query($id, implode(', ', $columns));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o período da contribuicao sindical.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return Sindicato::fromState($resultado);
    }

    /**
     * @param Sindicato $sindicato
     * @return Sindicato
     * @throws Exception
     */
    public static function save(Sindicato $sindicato)
    {
        $dao = new cl_rhsindicato();
        $dao->rh116_sequencial = $sindicato->getSequencial();
        $dao->rh116_codigo = $sindicato->getCodigo();
        $dao->rh116_cnpj = $sindicato->getCnpj();
        $dao->rh116_descricao = $sindicato->getRazaoSocial();
        $dao->rh116_mesdatabase = $sindicato->getMesDataBase();

        if ($sindicato->getSequencial()) {
            $dao->alterar($sindicato->getSequencial());
        } else {
            $dao->incluir(null);
        }

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar as informações.\nContate o suporte.");
        }

        $sindicato->setSequencial($dao->rh116_sequencial);

        return $sindicato;
    }

    /**
     * @param Sindicato|null $sindicato
     * @throws Exception
     */
    public function delete(Sindicato $sindicato = null)
    {
        $sequencial = $sindicato instanceof Sindicato ? $sindicato->getSequencial() : null;

        $dao = new cl_rhsindicato();
        $dao->excluir($sequencial, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir o sindicato.\nContate o suporte.");
        }
    }
}
