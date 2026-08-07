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

namespace ECidade\RecursosHumanos\Pessoal\Service;

use ECidade\RecursosHumanos\Pessoal\Model\RubricasUsuario;
use ECidade\RecursosHumanos\Pessoal\Repository\RubricasUsuarioRepository;
use Exception;
use Instituicao;
use UsuarioSistema;

class RubricasUsuarioService
{
    /**
     * @var RubricasUsuarioRepository
     */
    private $repositorio;

    /**
     * ConfirmacaoRematriculaService constructor.
     */
    public function __construct()
    {
        $this->repositorio = new RubricasUsuarioRepository();
    }

    /**
     * @param UsuarioSistema $usuario
     * @param Instituicao $instituicao
     * @return RubricasUsuario[]
     * @throws Exception
     */
    public function buscarRubricasUsuario(UsuarioSistema $usuario, Instituicao $instituicao)
    {
        return $this->repositorio->scopeInstituicao($instituicao)->scopeUsuario($usuario)->get();
    }

    /**
     * @param RubricasUsuario[] $rubricaUsuario
     * @return array
     */
    public function toArray(array $rubricaUsuario)
    {
        return array_map(function (RubricasUsuario $rubricaUsuario) {
            return array(
                'sequencial' => $rubricaUsuario->getSequencial(),
                'usuario' => array(
                    'codigo' => $rubricaUsuario->getUsuario()->getCodigo(),
                    'nome' => $rubricaUsuario->getUsuario()->getNome(),
                ),
                'instituicao' => array(
                    'codigo' => $rubricaUsuario->getInstituicao()->getCodigo(),
                    'descricao' => $rubricaUsuario->getInstituicao()->getDescricao(),
                ),
                'rubrica' => array(
                    'codigo' => $rubricaUsuario->getRubrica()->getCodigo(),
                    'descricao' => $rubricaUsuario->getRubrica()->getDescricao(),
                )
            );
        }, $rubricaUsuario);
    }

    /**
     * @param UsuarioSistema $usuarioSistema
     * @param Instituicao $instituicao
     * @return bool
     * @throws Exception
     */
    public function possuiConfiguracao(UsuarioSistema $usuarioSistema, Instituicao $instituicao)
    {
        $rubricasUsuarios = $this->repositorio->scopeInstituicao($instituicao)->scopeUsuario($usuarioSistema)->get();
        if (count($rubricasUsuarios) > 0) {
            return true;
        }
        return false;
    }
}
