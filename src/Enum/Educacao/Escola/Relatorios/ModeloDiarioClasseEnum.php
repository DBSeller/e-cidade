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

namespace ECidade\Enum\Educacao\Escola\Relatorios;

use ECidade\Enum\Enum;
use Exception;

/**
 * Class ModeloDiarioClasseEnum
 * @package ECidade\Enum\Educacao\Escola\Relatorios
 */
class ModeloDiarioClasseEnum extends Enum
{
    const ESPECIAL = 1;
    const MODELO_UMA_DISCIPLINA_PAGINA = 2;
    const MODELO_DUAS_PAGINAS = 3;
    const MODELO_CURRICULO = 4;
    const MODELO_EJA = 5;

    /**
     * @return string
     * @throws Exception
     */
    public function name()
    {
        $data = array(
            self::ESPECIAL => "Turmas de AC e AEE",
            self::MODELO_UMA_DISCIPLINA_PAGINA => "Uma disciplina por página (Área)",
            self::MODELO_DUAS_PAGINAS => "Duas páginas por disciplina (Página 1 - Presenças / Página 2 - Avaliações)",
            self::MODELO_CURRICULO => "Todas disciplinas em uma página (Currículo)",
            self::MODELO_EJA => "Turma EJA",
        );

        if (empty($data[$this->getValue()])) {
            throw new Exception('Modelo não encontrado.');
        }

        return $data[$this->getValue()];
    }

    /**
     * Retorna os valores com os nomes
     * @return array
     * @throws Exception
     */
    public static function toArrayWithNames()
    {
        $tipos = self::values();
        $return = array();
        foreach ($tipos as $tipo) {
            $return[] = array(
                'value' => $tipo->value(),
                'name' => $tipo->name()
            );
        }

        return $return;
    }
}
