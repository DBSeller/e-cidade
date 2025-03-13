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

namespace ECidade\RecursosHumanos\ESocial\Service\ProcessoJudicial;

use Exception;
use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Unicidade;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\UnicidadeRepository;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ContratoRepository;

class UnicidadeService
{
    /**
     * @var
     */
    private $unicidadeRepository;

    /**
     * @var
     */
    private $contratoRepository;

    /**
     * unicidadeService constructor.
    */
    public function __construct()
    {
        $this->unicidadeRepository = new UnicidadeRepository();
        $this->contratoRepository = new ContratoRepository();
    }

    /**
     * @param Unicidade
     * @return Unicidade
     * @throws Exception
     */
    public function salvar(Unicidade $unicidade)
    {
        $contrato = $this->contratoRepository
        ->scopeSequencial($unicidade->getSequencialProcessoContrato())
        ->get();
    
        $obrigatorio = false;

        if ((int) $contrato[0]->getTipoContrato() == 9) {
            $obrigatorio = true;
        }
        $complemento = " em <strong>Informações dos Vínculos/Contratos " .
        "Incorporados por Reconhecimento de Unicidade Contratual</strong>";
        if (((int) $unicidade->getCodigoCategoriaUnicidade() > 0 &&
            $unicidade->getCodigoCategoriaUnicidade() < 99) ||
            ((int) $unicidade->getCodigoCategoriaUnicidade() > 0 &&
            $unicidade->getCodigoCategoriaUnicidade() > 999)) {
                throw new Exception("Código Categoria por Unicidade é inválido " .
                "{$complemento}. Favor revisar.");
        }

        if (empty($unicidade->getMatriculaUnicidade()) &&
            empty($unicidade->getCodigoCategoriaUnicidade()) &&
            empty($unicidade->getDataInicioUnicidade()) &&
            $obrigatorio) {
                throw new Exception("O tipo de contrato é " .
                "'Trabalhador cujos contratos foram unificados (unicidade contratual)'" .
                "e é obrigatório o preenchimento {$complemento}. Favor revisar.");
        }
        return $this->unicidadeRepository->save($unicidade);
    }

    /**
     * @param Unicidade
     * @return Unicidade
     * @throws Exception
     */
    public function excluir(Unicidade $unicidade)
    {
        return $this->unicidadeRepository->delete($unicidade);
    }
}
