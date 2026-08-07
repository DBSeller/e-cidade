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

namespace ECidade\RecursosHumanos\ESocial\Agendamento\Processamento;

use ECidade\RecursosHumanos\ESocial\Agendamento\Evento;
use ECidade\RecursosHumanos\ESocial\DadosESocial;
use ECidade\RecursosHumanos\ESocial\Integracao\FormatterFactory;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;

class EFDExclusaoEventos extends ProcessamentoAbstract implements ProcessamentoInterface
{

    private $cgm;
    /**
     * ProcessamentoInterface constructor.
     * @param $cgm
     */
    public function __construct($cgm)
    {
        $this->cgm = $cgm;
    }

    /**
     * @return mixed
     */
    public function processar()
    {
        $cgm = $this->cgm;
        $alteracaoDados = false;

        $dadosEsocial = new DadosESocial();
        $dadosEsocial->setIntegracao(Tipo::EFD_EXCLUSAO_EVENTOS);
        $dadosEsocial->setReponsavelPeloPreenchimento($cgm);
        $dadosPreenchimento = $dadosEsocial->getPorTipo(Tipo::EFD_EXCLUSAO_EVENTOS);

        $formatter = FormatterFactory::get(Tipo::R9000);
        $dadosFormatados = $formatter->formatar($dadosPreenchimento);

        foreach ($dadosFormatados as $dados) {
            $eventoFila = new Evento(Tipo::R9000, $cgm, $dados->referencia, $dados);

            if ($eventoFila->adicionarFila()) {
                $alteracaoDados = true;
            }
        }

        return $alteracaoDados;
    }
}