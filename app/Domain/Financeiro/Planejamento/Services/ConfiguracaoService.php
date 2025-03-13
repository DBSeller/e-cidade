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

namespace App\Domain\Financeiro\Planejamento\Services;

use App\Domain\Financeiro\Planejamento\Models\Configuracao;

/**
 * Class ConfiguracaoService
 * @package App\Domain\Financeiro\Planejamento\Services
 */
class ConfiguracaoService
{
    /**
     * @var Configuracao
     */
    private $model;

    public function __construct()
    {
        $this->model = Configuracao::first();
        if (is_null($this->model)) {
            throw new \Exception("Configuração do planejamento não implantada.", 403);
        }
    }

    /**
     * @return Configuracao
     */
    public function get()
    {
        return $this->model;
    }

    /**
     * @param array $fields
     * @return Configuracao
     */
    public function salvar(array $fields)
    {
        if (!is_bool($fields['apenas_valor_analitico'])) {
            $fields['apenas_valor_analitico'] = $fields['apenas_valor_analitico'] == 'true';
        }

        $this->model->apenas_valor_analitico = $fields['apenas_valor_analitico'];
        $this->model->save();
        return $this->model;
    }
}
