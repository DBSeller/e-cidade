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

use cl_pontocom;
use ECidade\RecursosHumanos\Pessoal\Model\PontoComplementar;
use Exception;

class PontoComplementarRepository
{
    private $scopes = array();

    /**
     * @param $matricula
     * @param $ano
     * @param $mes
     * @param $rubrica
     * @return bool|ponto$pontoComplementar
     * @throws Exception
     */
    public static function find($matricula = null, $ano = null, $mes = null, $rubrica = null)
    {
        $dao = new cl_pontocom();

        $sql = $dao->sql_query_file($ano, $mes, $matricula, $rubrica);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar a rubrica do usuário.\nContate o suporte.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);
        $pontoComplementar = PontoComplementar::fromState($resultado);

        return $pontoComplementar;
    }

    public function scopeInstituicao($instituicao)
    {
        $this->scopes[] = "r47_instit = {$instituicao}";
        return $this;
    }

    public function scopeRubrica($rubrica)
    {
        $this->scopes[] = "r47_rubric = {$rubrica}";
        return $this;
    }

    /**
     * @return RubricasUsuario[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_pontocom();
        $sql = $dao->sql_query(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível encontrar o ponto complementar.\nContate o suporte.");
        }

        $pontoComplementar = array();

        if (pg_num_rows($rs) === 0) {
            return $pontoComplementar;
        }

        while ($ponto = pg_fetch_array($rs)) {
            $pontoComplementar[] = PontoComplementar::fromState($ponto);
        }

        return $pontoComplementar;
    }

    public function save(PontoComplementar $pontoComplementar)
    {
        $this->validate($pontoComplementar);

        $dao = new \cl_pontocom();
        $dao->r47_anousu = $pontoComplementar->getAno();
        $dao->r47_mesusu = $pontoComplementar->getMes();
        $dao->r47_regist = $pontoComplementar->getMatricula();
        $dao->r47_rubric = $pontoComplementar->getRubrica();
        $dao->r47_valor = $pontoComplementar->getValor();
        $dao->r47_quant = $pontoComplementar->getQuantidade();
        $dao->r47_lotac = $pontoComplementar->getLotacao();
        $dao->r47_datlim = $pontoComplementar->getDataLimite();
        $dao->r47_instit = $pontoComplementar->getInstituicao();

        $pontoComplementarModel = self::find(
            $pontoComplementar->getMatricula(),
            $pontoComplementar->getAno(),
            $pontoComplementar->getMes(),
            $pontoComplementar->getRubrica()
        );

        if (empty($pontoComplementarModel)) {
            return $dao->incluir(
                $pontoComplementar->getAno(),
                $pontoComplementar->getMes(),
                $pontoComplementar->getMatricula(),
                $pontoComplementar->getRubrica()
            );
        } else {
            return $dao->alterar(
                $pontoComplementar->getAno(),
                $pontoComplementar->getMes(),
                $pontoComplementar->getMatricula(),
                $pontoComplementar->getRubrica()
            );
        }
    }

    public function delete(PontoComplementar $pontoComplementar)
    {
        $this->validate($pontoComplementar);
        $dao = new \cl_pontocom();
        return $dao->excluir(
            $pontoComplementar->getAno(),
            $pontoComplementar->getMes(),
            $pontoComplementar->getMatricula(),
            $pontoComplementar->getRubrica()
        );
    }

    public function validate(PontoComplementar $pontoComplementar)
    {
        if (empty($pontoComplementar->getAno())) {
            throw new \Exception('Não é possível excluir o registro do ponto, pois o ano não está preenchido');
        }
        if (empty($pontoComplementar->getMes())) {
            throw new \Exception('Não é possível excluir o registro do ponto, pois o mês não está preenchido');
        }
        if (empty($pontoComplementar->getMatricula())) {
            throw new \Exception('Não é possível excluir o registro do ponto, pois a matrícula não está preenchida');
        }
        if (empty($pontoComplementar->getRubrica())) {
            throw new \Exception('Não é possível excluir o registro do ponto, pois a rubrica não está preenchida');
        }
    }
}
