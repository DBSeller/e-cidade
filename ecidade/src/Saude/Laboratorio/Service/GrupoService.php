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

namespace ECidade\Saude\Laboratorio\Service;

use DateTime;
use ECidade\Saude\Laboratorio\Repository\GrupoRepository;
use ECidade\Saude\Laboratorio\Repository\GrupoLaboratorioRepository;
use ECidade\Saude\Laboratorio\Repository\GrupoExameRepository;
use ECidade\Saude\Laboratorio\Model\Grupo;
use ECidade\Saude\Laboratorio\Model\GrupoLaboratorio;
use Exception;

/**
 * Class GrupoService
 * @package ECidade\Saude\Laboratorio\Service
 */
class GrupoService
{
    private $repositorio;
    
    private $repositorioGrupoLaboratorio;

    /**
     * GrupoService constructor.
     * @param GrupoRepository $repositorio
     */
    public function __construct(GrupoRepository $repositorio, GrupoLaboratorioRepository $repositorioGrupoLaboratorio)
    {
        $this->repositorio = $repositorio;
        $this->repositorioGrupoLaboratorio = $repositorioGrupoLaboratorio;
    }

    /**
     * Buscar grupos
     */
    public function buscarGrupos()
    {
        return $this->repositorio->buscar();
    }

    /**
     * Salva os dados do grupo
     */
    public function salvar($parametros)
    {
        $grupo = new Grupo();
        $grupo->setCodigo(isset($parametros->codigo) ? $parametros->codigo : '');
        $grupo->setDescricao(isset($parametros->descricao) ? $parametros->descricao : '');

        $grupo = $this->repositorio->salvar($grupo);

        return $grupo;
    }

    /**
     * Excluir grupo por código
     */
    public function excluirGrupo($codigo)
    {
        $laboratoriosVinculados = $this->repositorioGrupoLaboratorio->buscar(" la67_grupo = ".$codigo);
        if ($laboratoriosVinculados) {
            throw new Exception("O grupo esta vinculado a um laboratorio, logo não pode ser excluido.");
        }
        return $this->repositorio->excluir($codigo);
    }
}
