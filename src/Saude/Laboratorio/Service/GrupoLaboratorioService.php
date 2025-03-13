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
use ECidade\Saude\Laboratorio\Repository\GrupoLaboratorioRepository;
use ECidade\Saude\Laboratorio\Repository\GrupoExameRepository;
use ECidade\Saude\Laboratorio\Model\GrupoLaboratorio;
use Exception;

/**
 * Class GrupoLaboratorioService
 * @package ECidade\Saude\Laboratorio\Service
 */
class GrupoLaboratorioService
{
    private $repositorio;

    private $repositorioGrupoExame;

    /**
     * GrupoLaboratorioService constructor.
     * @param GrupoLaboratorioRepository $repositorio
     * @param GrupoExameRepository $repositorio
     */
    public function __construct(
        GrupoLaboratorioRepository $repositorio,
        GrupoExameRepository $repositorioGrupoExame = null
    ) {
        $this->repositorio = $repositorio;
        $this->repositorioGrupoExame = $repositorioGrupoExame;
    }

    /**
     * Buscar grupos vinculados ao laboratorio
     */
    public function buscarGruposLaboratorio($parametros)
    {
        return $this->repositorio->buscar(" la67_laboratorio = ".$parametros->laboratorio);
    }

    /**
     * Auto complete grupos vinculados ao laboratorio
     */
    public function autoCompleteGruposLaboratorio($parametros)
    {
        return $this->repositorio->buscar(" la67_laboratorio = $parametros->laboratorio 
            and la66_descricao ilike '%$parametros->descricaoGrupo%'");
    }

    /**
     * Salva os dados do grupo
     */
    public function salvar($parametros)
    {
        $grupoLaboratorio = new GrupoLaboratorio();
        $grupoLaboratorio->setCodigo(isset($parametros->codigo) ? $parametros->codigo : '');
        $grupoLaboratorio->setLaboratorio(isset($parametros->laboratorio) ? $parametros->laboratorio : '');
        $grupoLaboratorio->setGrupo(isset($parametros->grupo) ? $parametros->grupo : '');

        $grupoLaboratorio = $this->repositorio->salvar($grupoLaboratorio);

        return $grupoLaboratorio;
    }

    /**
     * Excluir vinculo de grupo com laboratório
     */
    public function excluirGrupoLaboratorio($parametros)
    {
        $where = " la67_codigo = ".$parametros->codigo;
        $examesVinculados = $this->repositorioGrupoExame->buscar($where);
        if ($examesVinculados) {
            throw new Exception("Não é possivel excluir este grupo, pois o mesmo possui exames vinculados.");
        }
        return $this->repositorio->excluir($parametros->codigo);
    }
}
