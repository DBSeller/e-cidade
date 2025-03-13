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

use cl_pontofs;
use ECidade\RecursosHumanos\Pessoal\Model\PontoSalario;
use Exception;

class PontoSalarioRepository
{
    private $scopes = array();

    /**
     * @param $matricula
     * @param $ano
     * @param $mes
     * @param $rubrica
     * @return bool|PontoSalario
     * @throws Exception
     */
    public static function find($matricula = null, $ano = null, $mes = null, $rubrica = null)
    {
        $dao = new cl_pontofs();

        $sql = $dao->sql_query_file($ano, $mes, $matricula, $rubrica);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar a rubrica do usuário.\nContate o suporte.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);
        $pontoSalario = PontoSalario::fromState($resultado);

        return $pontoSalario;
    }

    public function scopeInstituicao($instituicao)
    {
        $this->scopes[] = "r10_instit = {$instituicao}";
        return $this;
    }

    public function scopeRubrica($rubrica)
    {
        $this->scopes[] = "r10_rubric = {$rubrica}";
        return $this;
    }

    /**
     * @return RubricasUsuario[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_pontofs();
        $sql = $dao->sql_query(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível o ponto salário.\nContate o suporte.");
        }

        $pontoSalario = array();

        if (pg_num_rows($rs) === 0) {
            return $pontoSalario;
        }

        while ($ponto = pg_fetch_array($rs)) {
            $pontoSalario[] = PontoSalario::fromState($ponto);
        }

        return $pontoSalario;
    }

    public function save(PontoSalario $pontoSalario)
    {
        $this->validate($pontoSalario);

        $dao = new \cl_pontofs();
        $dao->r10_anousu = $pontoSalario->getAno();
        $dao->r10_mesusu = $pontoSalario->getMes();
        $dao->r10_regist = $pontoSalario->getMatricula();
        $dao->r10_rubric = $pontoSalario->getRubrica();
        $dao->r10_valor = $pontoSalario->getValor();
        $dao->r10_quant = $pontoSalario->getQuantidade();
        $dao->r10_lotac = $pontoSalario->getLotacao();
        $dao->r10_datlim = $pontoSalario->getDataLimite();
        $dao->r10_instit = $pontoSalario->getInstituicao();

        $pontoSalarioModel = self::find(
            $pontoSalario->getMatricula(),
            $pontoSalario->getAno(),
            $pontoSalario->getMes(),
            $pontoSalario->getRubrica()
        );

        if (empty($pontoSalarioModel)) {
            return $dao->incluir(
                $pontoSalario->getAno(),
                $pontoSalario->getMes(),
                $pontoSalario->getMatricula(),
                $pontoSalario->getRubrica()
            );
        } else {
            return $dao->alterar(
                $pontoSalario->getAno(),
                $pontoSalario->getMes(),
                $pontoSalario->getMatricula(),
                $pontoSalario->getRubrica()
            );
        }
    }

    public function delete(PontoSalario $pontoSalario)
    {
        $this->validate($pontoSalario);
        $dao = new \cl_pontofs();
        return $dao->excluir(
            $pontoSalario->getAno(),
            $pontoSalario->getMes(),
            $pontoSalario->getMatricula(),
            $pontoSalario->getRubrica()
        );
    }

    public function validate(PontoSalario $pontoSalario)
    {
        if (empty($pontoSalario->getAno())) {
            throw new \Exception('Não é possível excluir o registro do ponto, pois o ano não está preenchido');
        }
        if (empty($pontoSalario->getMes())) {
            throw new \Exception('Não é possível excluir o registro do ponto, pois o mês não está preenchido');
        }
        if (empty($pontoSalario->getMatricula())) {
            throw new \Exception('Não é possível excluir o registro do ponto, pois a matrícula não está preenchida');
        }
        if (empty($pontoSalario->getRubrica())) {
            throw new \Exception('Não é possível excluir o registro do ponto, pois a rubrica não está preenchida');
        }
    }
}
