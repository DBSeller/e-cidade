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

use App\Domain\Financeiro\Planejamento\Relatorios\Anexos\XlsAnexoIII;
use App\Domain\Financeiro\Planejamento\Relatorios\Anexos\XlsAnexoIV;
use stdClass;

/**
 * Class RelatorioAnexoIVService
 * @package App\Domain\Financeiro\Planejamento\Services
 */
class RelatorioAnexoIVService extends AnexosLDOService
{
    public function __construct(array $filtros)
    {
        parent::__construct($filtros);

        $this->processar();
        $this->parser = new XlsAnexoIV();
    }

    public function emitir()
    {
        $this->parser->setDados($this->getLinhas());
        $this->parser->setEnteFederativo($this->enteFederativo);
        $this->parser->setEmissor($this->emissor);
        $this->parser->setAnoReferencia($this->plano->pl2_ano_inicial);
        $this->parser->setNotaExplicativa($this->getNotaExplicativa());

        $filename = $this->parser->gerar();

        return [
            'xls' => $filename,
            'xlsLinkExterno' => ECIDADE_REQUEST_PATH . $filename
        ];
    }

    protected function processaLinhas()
    {
        $this->getLinhas();
        $this->processaValorManual();
    }

    protected function processar()
    {
        parent::processar();
        $this->processaLinhas();
    }

    protected function processaReceita($linha)
    {
        // TODO: Implement processaReceita() method.
    }

    protected function processaDespesa($linha)
    {
        // TODO: Implement processaDespesa() method.
    }
}
