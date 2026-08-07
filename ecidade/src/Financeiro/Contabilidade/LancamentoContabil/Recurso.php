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
namespace ECidade\Financeiro\Contabilidade\LancamentoContabil;

use ILancamentoAuxiliar;

/**
 * Class Recurso
 *
 * @package ECidade\Financeiro\Contabilidade\LancamentoContabil
 */
class Recurso
{
    private $UTILIZA_DOMICILIO_BANCARIO;

    public function __construct()
    {
        $this->UTILIZA_DOMICILIO_BANCARIO = (
            (isset($_SESSION["DB_utiliza_domicilio_bancario"])
                &&
            $_SESSION["DB_utiliza_domicilio_bancario"] == "t")
                ?
                true
                :
                false
        );
    }

    /**
     * Verifica o documento do
     *
     * @param \EventoContabil $eventoContabil
     * @throws \ReflectionException
     */
    public function processar($codigoLancamnento, ILancamentoAuxiliar $lancamentoAuxiliar = null)
    {

        $tipoRecurso = new RecursoContaPagadora();

        if ($this->UTILIZA_DOMICILIO_BANCARIO) {
            $tipoRecurso = new RecursoOrigem();
        }
        $tipoRecurso->processar($codigoLancamnento, $lancamentoAuxiliar);
        return;
    }
}
