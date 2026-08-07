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

use cl_rubricasusuario;
use ECidade\RecursosHumanos\Pessoal\Model\RubricasUsuario;
use Exception;
use Instituicao;
use Rubrica;
use UsuarioSistema;

class RubricasUsuarioRepository
{
    private $scopes = array();

    /**
     * @param $sequencial
     * @return bool|RubricasUsuario
     * @throws Exception
     */
    public static function find($sequencial)
    {
        $dao = new cl_rubricasusuario();
        $sql = $dao->sql_query($sequencial);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar a rubrica do usuário.\nContate o suporte.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return RubricasUsuario::fromState($resultado);
    }

    public function scopeUsuario(UsuarioSistema $usuario)
    {
        $this->scopes[] = "rh219_usuario = {$usuario->getCodigo()}";
        return $this;
    }

    public function scopeInstituicao(Instituicao $instituicao)
    {
        $this->scopes[] = "rh219_instituicao = {$instituicao->getCodigo()}";
        return $this;
    }

    public function scopeRubrica(Rubrica $rubrica)
    {
        $this->scopes[] = "rh219_rubrica = {$rubrica->getCodigo()}";
        return $this;
    }

    /**
     * @return RubricasUsuario[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_rubricasusuario();
        $sql = $dao->sql_query(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar as rubricas do usuário.\nContate o suporte.");
        }

        $rubricasUsuarios = array();

        if (pg_num_rows($rs) === 0) {
            return $rubricasUsuarios;
        }

        while ($rubricasUsuario = pg_fetch_array($rs)) {
            $rubricasUsuarios[] = RubricasUsuario::fromState($rubricasUsuario);
        }

        return $rubricasUsuarios;
    }

    /**
     * @throws Exception
     */
    public function deleteFromScope()
    {
        $dao = new cl_rubricasusuario();
        $dao->excluir(null, implode(' and ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception(
                "Não foi possível excluir as permissões de rubricas do usuário.\nContate o suporte."
            );
        }
    }

    /**
     * @param RubricasUsuario $rubricasUsuario
     * @throws Exception
     */
    public function save(RubricasUsuario $rubricasUsuario)
    {
        $dao = new cl_rubricasusuario();
        $dao->rh219_sequencial = $rubricasUsuario->getSequencial();
        $dao->rh219_usuario = $rubricasUsuario->getUsuario()->getCodigo();
        $dao->rh219_instituicao = $rubricasUsuario->getInstituicao()->getCodigo();
        $dao->rh219_rubrica = $rubricasUsuario->getRubrica()->getCodigo();

        if (!empty($dao->rh219_sequencial)) {
            $dao->alterar($rubricasUsuario->getSequencial());
        } else {
            $dao->incluir(null);
        }

        if ($dao->erro_status === '0') {
            throw new Exception(
                "Não foi possível salvar permissão da {$rubricasUsuario->getRubrica()->getDescricao()}.\nContate o suporte."
            );
        }
    }
}
