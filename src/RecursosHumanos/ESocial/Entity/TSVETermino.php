<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                    www.dbseller.com.br
 *                 e-cidade@dbseller.com.br
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

namespace ECidade\RecursosHumanos\ESocial\Entity;

use ECidade\RecursosHumanos\Pessoal\Model\ServidorOperadoraSaude;
use ECidade\RecursosHumanos\Pessoal\Model\ServidorOutrosVinculos;
use ECidade\RecursosHumanos\Pessoal\Model\ServidorProcessosJudiciaisFolha;

use Servidor;

/**
 * Class TSVETermino
 * @package ECidade\RecursosHumanos\ESocial\Entity
 */
class TSVETermino
{
    /**
     * @var int
     */
    const AVALIACAO = 3000033;

    /**
     * @var ServidorOutrosVinculos[]
     */
    private $servidorOutrosVinculos = array();

    /**
     * @var ServidorOperadoraSaude[]
     */
    private $planoSaude;

    /**
     * @var ServidorProcessosJudiciaisFolha[]
     */
    private $pocessosJudiciais = array();

    /**
     * @var Servidor
     */
    private $servidor;

    /**
     * @var array
     */
    private $pagamentos = array();

    /**
     * @return ServidorOutrosVinculos[]
     */
    public function getServidorOutrosVinculos()
    {
        return $this->servidorOutrosVinculos;
    }

    /**
     * @param ServidorOutrosVinculos[] $servidorOutrosVinculos
     * @return ServidorOutrosVinculos[] $servidorOutrosVinculos
     */
    public function setServidorOutrosVinculos($servidorOutrosVinculos)
    {
        return $this->servidorOutrosVinculos = $servidorOutrosVinculos;
    }

    /**
     * @return ServidorOperadoraSaude[]
     */
    public function getPlanoSaude()
    {
        return $this->planoSaude;
    }

    /**
     * @param ServidorOperadoraSaude[] $planoSaude
     */
    public function setPlanoSaude($planoSaude)
    {
        $this->planoSaude = $planoSaude;
    }

    /**
     * @return Servidor
     */
    public function getServidor()
    {
        return $this->servidor;
    }

    /**
     * @param Servidor $servidor
     */
    public function setServidor(Servidor $servidor)
    {
        $this->servidor = $servidor;
    }

    /**
     * @return ServidorProcessosJudiciaisFolha[]
     */
    public function getProcessosJudiciais()
    {
        return $this->pocessosJudiciais;
    }

    /**
     * @param ServidorProcessosJudiciaisFolha[] $pocessosJudiciais
     */
    public function setProcessosJudiciais($pocessosJudiciais)
    {
        $this->pocessosJudiciais = $pocessosJudiciais;
    }

    /**
     * @return array
     */
    public function getPagamentos()
    {
        return $this->pagamentos;
    }

    /**
     * @param array $pagamentos
     */
    public function setPagamentos($pagamentos)
    {
        $this->pagamentos = $pagamentos;
    }
}
