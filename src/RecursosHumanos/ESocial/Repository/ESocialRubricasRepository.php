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

namespace ECidade\RecursosHumanos\ESocial\Repository;

use cl_esocialrubricas;
use Exception;
use Instituicao;
use Rubrica;
use stdClass;
use InstituicaoRepository;
use ECidade\RecursosHumanos\ESocial\Integracao\ESocial;
use ECidade\V3\Extension\Registry;

/**
 * Class ESocialRubricasRepository
 * @package ECidade\RecursosHumanos\ESocial\Repository
 */
class ESocialRubricasRepository
{
    /**
     * @var cl_esocialrubricas
     */
    private $dao;

    /**
     * ESocialRubricasRepository constructor.
     */
    public function __construct()
    {
        $this->dao = new cl_esocialrubricas();
    }

    /**
     * @param Rubrica $rubrica
     * @param Instituicao $instituicao
     * @return stdClass
     * @throws Exception
     */
    public function getByRubricaAndInstituicao(Rubrica $rubrica, Instituicao $instituicao)
    {
        $where = array(
          "eso26_rubrica = '{$rubrica->getCodigo()}'",
          "eso26_instituicao = {$instituicao->getCodigo()}"
        );

        $sql = $this->dao->sql_query_file(null, '*', null, implode(' AND ', $where));
        $rs = db_query($sql);

        if (!$rs) {
            $mensagem = pg_last_error();
            $mensagem ="Não foi possível buscar as informações da rubrica {$rubrica->getCodigo()}."
                . " Contate o suporte. \n{$mensagem}";
            throw new Exception($mensagem);
        }

        $retorno = new stdClass();
        $retorno->rubrica = $rubrica->getCodigo();
        $retorno->instituicao = $instituicao->getCodigo();

        if (pg_num_rows($rs) === 0) {
            $retorno->sequencial = null;
            $retorno->codIncCP = null;
            $retorno->codIncIRRF = null;
            $retorno->codIncFGTS = null;
            $retorno->natureza = null;
            $retorno->dataInicial = null;
            $retorno->dataFinal = null;
            $retorno->codTetoRemun = null;
            $retorno->codIncCPRP = null;
            $retorno->subgrupotce = null;
            return $retorno;
        }

        $resultado = pg_fetch_object($rs);

        $retorno->sequencial = $resultado->eso26_sequencial;
        $retorno->codIncCP = $resultado->eso26_avaliacaoperguntaopcaocodinccp;
        $retorno->codIncIRRF = $resultado->eso26_avaliacaoperguntaopcaocodincirrf;
        $retorno->codIncFGTS = $resultado->eso26_avaliacaoperguntaopcaocodincfgts;
        $retorno->codIncCPRP = $resultado->eso26_avaliacaoperguntaopcaocodinccprp;
        $retorno->codTetoRemun = $resultado->eso26_avaliacaoperguntaopcaocodtetoremun;
        $retorno->natureza = $resultado->eso26_natureza;
        $retorno->dataInicial = $resultado->eso26_datainicial;
        $retorno->dataFinal = $resultado->eso26_datafinal;
        $retorno->subgrupotce = $resultado->eso26_subgrupotce;
        return $retorno;
    }

    /**
     * @param stdClass $rubrica
     * @return stdClass
     * @throws Exception
     */
    public function persist(stdClass $rubrica)
    {
        $this->validate($rubrica);
        $this->dao->eso26_sequencial = $rubrica->sequencial;
        $this->dao->eso26_rubrica = $rubrica->rubrica;
        $this->dao->eso26_instituicao = $rubrica->instituicao;
        $this->dao->eso26_avaliacaoperguntaopcaocodinccp = $rubrica->codIncCP;
        $this->dao->eso26_avaliacaoperguntaopcaocodincirrf = $rubrica->codIncIRRF;
        $this->dao->eso26_avaliacaoperguntaopcaocodincfgts = $rubrica->codIncFGTS;
        $this->dao->eso26_avaliacaoperguntaopcaocodinccprp = $rubrica->codIncCPRP;
        $this->dao->eso26_avaliacaoperguntaopcaocodtetoremun = $rubrica->codTetoRemun;
        $this->dao->eso26_natureza = $rubrica->natureza;
        $this->dao->eso26_datainicial = $rubrica->dataInicial;
        $this->dao->eso26_datafinal = $rubrica->dataFinal;
        $this->dao->eso26_subgrupotce = $rubrica->subgrupotce;

        if (empty($this->dao->eso26_sequencial)) {
            $this->dao->incluir($this->dao->eso26_sequencial);
        } else {
            $this->dao->alterar($this->dao->eso26_sequencial);
        }

        if ($this->dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar as informações da rubrica. Contate o suporte.");
        }

        return $this->getByRubricaAndInstituicao(
            new Rubrica($rubrica->rubrica),
            new Instituicao($rubrica->instituicao)
        );
    }

    /**
     * @param Rubrica $rubrica
     * @param Instituicao $instituicao
     * @throws Exception
     */
    public function delete(Rubrica $rubrica, Instituicao $instituicao)
    {
        $where = array(
          "eso26_rubrica = '{$rubrica->getCodigo()}'",
          "eso26_instituicao = {$instituicao->getCodigo()}"
        );

        $this->dao->excluir(null, implode(' AND ', $where));

        if ($this->dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir as informações da rubrica. Contate o suporte.");
        }
    }

    /**
     * @param stdClass $rubrica
     * @throws Exception
     */
    private function validate(stdClass $rubrica)
    {
        $isPB = isParaiba();

        if (!$isPB) {
            if (empty($rubrica->codIncCP)) {
                throw new Exception('Incidência de Contrib. Previdenciária não informada.');
            }
    
            if (empty($rubrica->codIncIRRF)) {
                throw new Exception("Incidência de IRRF não informada.");
            }
    
            if (empty($rubrica->codIncFGTS)) {
                throw new Exception("Incidência de FGTS não informada.");
            }
    
            if (empty($rubrica->natureza)) {
                throw new Exception("Natureza da rubrica não informada.");
            }
        }

        if (empty($rubrica->rubrica)) {
            throw new Exception("Código da rubrica não informado.");
        }

        if (empty($rubrica->instituicao)) {
            throw new Exception("Código da instituição não informado.");
        }

        if (empty($rubrica->dataInicial)) {
            throw new Exception("Data de início de validade não informada.");
        }
    }

    /**
     * Define quais rubricas são válidas para o layout, de acordo com o que foi respondido no formulário S-1010
     * @param string $layout (Ex.: 2299)
     * @return array
     */
    public function validarRubricas($layout = null)
    {
        $body = new stdClass();
        $body->inscricaoEmpregador = InstituicaoRepository::getInstituicaoSessao()->getCNPJ();
        $body->idEvento = $layout;
        $service = new ESocial(Registry::get('app.config'), '/evento/consultar_rubricas_desconto_irrf');
        $service->setDados($body);
        $dadosRubrica = $service->request('GET');

        $rubricasValidas = array();
        foreach ($dadosRubrica as $dadoRubrica) {
            if (!isset($rubricasValidas[$dadoRubrica->referencia])) {
                $rubricasValidas[$dadoRubrica->referencia] = $dadoRubrica;
            }
        }
        return $rubricasValidas;
    }
}
