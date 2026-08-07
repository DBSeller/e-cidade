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

namespace ECidade\RecursosHumanos\ESocial\Repository;

use ECidade\RecursosHumanos\Pessoal\Service\PensaoAlimenticiaService;

use BaseClassRepository;
use DBCompetencia;
use ParametrosPessoalRepository;
use Servidor;
use cl_pesdiver;
use db_utils;
use cl_avaliacaogruporespostarhpessoal;
use Exception;


/**
 * Class PagamentosRendimentosTrabalho
 * @package ECidade\RecursosHumanos\ESocial\Repository
 */
class PagamentosRendimentosTrabalho extends BaseClassRepository
{
    protected static $oInstance;

    /**
     * Busca a rubrica de pensão alimentícia configurada para a Instituição logada
     *
     * @param DBCompetencia $competencia
     * @return String|null
     * @throws \DBException
     */
    public static function buscarParametroRubricaPensaoAlimenticia(DBCompetencia $competencia)
    {
        $parametrosPessoal = ParametrosPessoalRepository::getParametros($competencia);

        return $parametrosPessoal->getRubricaPensaoAlimenticia();
    }

    /**
     * Retorna um array com os eventos financeiros do Servidor na competência
     *
     * @param Servidor $servidor
     * @param $tipoCalculo
     * @return array|\EventoFinanceiroFolha[]|null
     * @throws \BusinessException
     * @throws \DBException
     */
    public static function buscarCalculoFinanceiroServidor(Servidor $servidor, $tipoCalculo)
    {
        $calculoFinanceiro = $servidor->getCalculoFinanceiro($tipoCalculo);

        if(is_null($calculoFinanceiro)) {
            return null;
        }

        return $calculoFinanceiro->getEventosFinanceiros();
    }

    /**
     * Retorna o valor de desconto IRRF para dependentes, configurado no Diversos D901
     *
     * @param DBCompetencia $competencia
     * @return mixed
     * @throws \BusinessException
     * @throws \DBException
     */
    public static function valorDescontoIRRFPorDependente(DBCompetencia $competencia)
    {
        $daoPesDiver = new cl_pesdiver();
        $sqlPesDiver = $daoPesDiver->sql_query_file(
          $competencia->getAno(),
          $competencia->getMes(),
          'D901',
          \InstituicaoRepository::getInstituicaoSessao()->getCodigo(),
          'r07_valor'
        );

        $rsPesDiver = db_query($sqlPesDiver);

        if(!$rsPesDiver) {
            throw new \DBException("Erro ao buscar o valor de desconto IRRF para dependente.");
        }

        if(pg_num_rows($rsPesDiver) == 0) {
            throw new \BusinessException("Nenhum valor de desconto IRRF para dependente configurado('D901').");
        }

        return db_utils::fieldsMemory($rsPesDiver, 0)->r07_valor;
    }

    /**
     * @param Servidor $servidor
     * @param DBCompetencia $competencia
     * @return \ECidade\RecursosHumanos\Pessoal\Model\PensaoAlimenticia[]
     */
    public static function buscarBeneficiariosPensaoAlimenticia(Servidor $servidor, DBCompetencia $competencia)
    {
        $pensaoAlimenticiaService = new PensaoAlimenticiaService();
        $pensaoAlimenticiaService->setAnoCompetencia($competencia->getAno());
        $pensaoAlimenticiaService->setMesCompetencia($competencia->getMes());

        return $pensaoAlimenticiaService->buscarPensoesPorServidorCompetencia($servidor);
    }

    /**
     * Retorna o total de dependentes que foi preenchido no formulário S-2200 para a matrícula informada.
     * @param int $matricula
     * @return int
     */   
    public static function buscarTotalDependentesPorMatricula($matricula)
    {
        $dao = new cl_avaliacaogruporespostarhpessoal();    
        $campos = " count(db106_resposta) as total";
        $where = "
            db103_sequencial in (3000779, 3000737, 3000730, 3000751, 3000744, 3000786, 3000758, 3000723, 3000772, 3000765) 
                and db106_resposta in ('3003192', '3003211', '3003230', '3003249', '3003268', '3003287', '3003306', '3003325', '3003344', '3003363')        
                and eso02_avaliacaogruporesposta = (select max(eso02_avaliacaogruporesposta) from avaliacaogruporespostarhpessoal where eso02_rhpessoal = {$matricula})
        ";
        $sql = $dao->buscaRespostasPorPerguntaMatricula(null, null, $campos, null, $where);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar o total de dependentes da matrícula {$matricula}");
        }

        return db_utils::fieldsMemory($rs, 0)->total;
    }
}
