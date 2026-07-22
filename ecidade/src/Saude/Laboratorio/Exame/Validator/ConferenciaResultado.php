<?php
/**
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

namespace ECidade\Saude\Laboratorio\Exame\Validator;

use ECidade\Saude\Laboratorio\Exame\Model\ConferenciaResultado as ConferenciaResultadoModel;

/**
 * Class ConferenciaResultado
 * @package ECidade\Saude\Laboratorio\Exame\Validator
 */
class ConferenciaResultado
{
    const EXAMES_SEM_PROCEDIMENTO = 'EXAMES_SEM_PROCEDIMENTO';

    /**
     * @var ConferenciaResultado
     */
    public static $instance;

    /**
     * @var array
     */
    private $inconsistencias = array();

    private function __construct()
    {
    }

    /**
     * @return ConferenciaResultado
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * @param ConferenciaResultadoModel $conferenciaResultado
     * @return bool
     */
    public function validar(ConferenciaResultadoModel $conferenciaResultado)
    {
        if ($conferenciaResultado->getProcedimento() === null) {
            if (!isset($this->inconsistencias[self::EXAMES_SEM_PROCEDIMENTO])) {
                $this->inconsistencias[self::EXAMES_SEM_PROCEDIMENTO] = array();
            }

            $exame = $conferenciaResultado->getRequisicaoExame()->getExame();

            if (!array_key_exists($exame->getCodigo(), $this->inconsistencias[self::EXAMES_SEM_PROCEDIMENTO])) {
                $this->inconsistencias[self::EXAMES_SEM_PROCEDIMENTO][$exame->getCodigo()] = $exame;
            }

            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public function getInconstencias()
    {
        return $this->inconsistencias;
    }

    /**
     * @return bool
     */
    public function temInconsistencias()
    {
        return !empty($this->inconsistencias);
    }
}
