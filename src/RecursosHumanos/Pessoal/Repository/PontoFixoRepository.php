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

use cl_pontofx;
use ECidade\RecursosHumanos\Pessoal\Model\PontoFixo;
use Exception;

class PontoFixoRepository
{
    private $scopes = array();

    /**
     * @param $matricula
     * @param $ano
     * @param $mes
     * @param $rubrica
     * @return bool|pontoFixo
     * @throws Exception
     */
    public static function find($matricula = null, $ano = null, $mes = null, $rubrica = null)
    {
        $dao = new cl_pontofx();

        $sql = $dao->sql_query_file($ano, $mes, $matricula, $rubrica);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar a rubrica do usuário.\nContate o suporte.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);
        $pontoFixo = PontoFixo::fromState($resultado);

        return $pontoFixo;
    }

    public function scopeInstituicao($instituicao)
    {
        $this->scopes[] = "r90_instit = {$instituicao}";
        return $this;
    }

    public function scopeRubrica($rubrica)
    {
        $this->scopes[] = "r90_rubric = {$rubrica}";
        return $this;
    }

    /**
     * @return RubricasUsuario[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_pontofx();
        $sql = $dao->sql_query(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível encontrar o ponto fixo.\nContate o suporte.");
        }

        $pontoFixo = array();

        if (pg_num_rows($rs) === 0) {
            return $pontoFixo;
        }

        while ($ponto = pg_fetch_array($rs)) {
            $pontoFixo[] = PontoFixo::fromState($ponto);
        }

        return $pontoFixo;
    }

    public function save(PontoFixo $pontoFixo)
    {
        $this->validate($pontoFixo);

        $dao = new \cl_pontofx();
        $dao->r90_anousu = $pontoFixo->getAno();
        $dao->r90_mesusu = $pontoFixo->getMes();
        $dao->r90_regist = $pontoFixo->getMatricula();
        $dao->r90_rubric = $pontoFixo->getRubrica();
        $dao->r90_valor = $pontoFixo->getValor();
        $dao->r90_quant = $pontoFixo->getQuantidade();
        $dao->r90_lotac = $pontoFixo->getLotacao();
        $dao->r90_datlim = $pontoFixo->getDataLimite();
        $dao->r90_instit = $pontoFixo->getInstituicao();

        $pontoFixoModel = self::find(
            $pontoFixo->getMatricula(),
            $pontoFixo->getAno(),
            $pontoFixo->getMes(),
            $pontoFixo->getRubrica()
        );

        if (empty($pontoFixoModel)) {
            return $dao->incluir(
                $pontoFixo->getAno(),
                $pontoFixo->getMes(),
                $pontoFixo->getMatricula(),
                $pontoFixo->getRubrica()
            );
        } else {
            return $dao->alterar(
                $pontoFixo->getAno(),
                $pontoFixo->getMes(),
                $pontoFixo->getMatricula(),
                $pontoFixo->getRubrica()
            );
        }
    }

    public function delete(PontoFixo $pontoFixo)
    {
        $this->validate($pontoFixo);
        $dao = new \cl_pontofx();
        return $dao->excluir(
            $pontoFixo->getAno(),
            $pontoFixo->getMes(),
            $pontoFixo->getMatricula(),
            $pontoFixo->getRubrica()
        );
    }

    public function validate(PontoFixo $pontoFixo)
    {
        if (empty($pontoFixo->getAno())) {
            throw new \Exception('Não é possível excluir o registro do ponto, pois o ano não está preenchido');
        }
        if (empty($pontoFixo->getMes())) {
            throw new \Exception('Não é possível excluir o registro do ponto, pois o mês não está preenchido');
        }
        if (empty($pontoFixo->getMatricula())) {
            throw new \Exception('Não é possível excluir o registro do ponto, pois a matrícula não está preenchida');
        }
        if (empty($pontoFixo->getRubrica())) {
            throw new \Exception('Não é possível excluir o registro do ponto, pois a rubrica não está preenchida');
        }
    }
}
