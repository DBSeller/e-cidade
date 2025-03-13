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

namespace ECidade\RecursosHumanos\ESocial\Migracao;

use cl_avaliacaogruporespostarhpessoal;
use db_utils;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use Exception;
use stdClass;

/**
 * Class Servidor
 * @package ECidade\RecursosHumanos\ESocial\Migracao
 */
class Servidor extends Migracao implements MigracaoInterface
{
    /**
     * Servidor constructor.
     */
    public function __construct()
    {
        $this->nomeFormulario = Tipo::getTitulos(Tipo::SERVIDOR);
    }

    /**
     * @param $codigoFormulario
     * @return stdClass[]
     * @throws Exception
     */
    public function buscarUltimoPreenchimento($codigoFormulario)
    {
        $campos = array(
            'eso02_rhpessoal AS matricula',
            'max(eso02_avaliacaogruporesposta) AS preenchimento',
            'eso02_empregador AS empregador',
            'eso02_avaliacao AS avaliacao'
        );

        $where = array(
            "eso02_avaliacao = {$codigoFormulario}"
        );

        $dao = new cl_avaliacaogruporespostarhpessoal();
        $sql = $dao->sql_avaliacao_preenchida(
            $campos,
            $where,
            array('eso02_rhpessoal', 'eso02_empregador', 'eso02_avaliacao')
        );

        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os preenchimentos do formulário {$this->nomeFormulario}.");
        }

        return db_utils::getCollectionByRecord($rs);
    }

    /**
     * @param stdClass $preenchimento
     * @return int
     * @throws Exception
     */
    protected function criarNovoPreenchimento($preenchimento)
    {
        $novoPreenchimento = parent::criarNovoPreenchimento($preenchimento);

        $daoServidor = new cl_avaliacaogruporespostarhpessoal();
        $daoServidor->eso02_avaliacaogruporesposta = $novoPreenchimento;
        $daoServidor->eso02_rhpessoal = $preenchimento->matricula;
        $daoServidor->eso02_empregador = $preenchimento->empregador;
        $daoServidor->eso02_avaliacao = $preenchimento->avaliacao;
        $daoServidor->incluir(null);

        if ($daoServidor->erro_status == 0) {
            throw new Exception("Não foi possível migrar a versão do formulário {$this->nomeFormulario}.");
        }

        return $novoPreenchimento;
    }
}
