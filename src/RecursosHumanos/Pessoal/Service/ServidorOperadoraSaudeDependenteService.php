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

namespace ECidade\RecursosHumanos\Pessoal\Service;

use ECidade\RecursosHumanos\Pessoal\Model\ServidorOperadoraSaude;
use ECidade\RecursosHumanos\Pessoal\Model\ServidorOperadoraSaudeDependente;
use ECidade\RecursosHumanos\Pessoal\Repository\DependenteRepository;
use ECidade\RecursosHumanos\Pessoal\Repository\ServidorOperadoraSaudeDependenteRepository;
use ECidade\RecursosHumanos\Pessoal\Repository\ServidorOperadoraSaudeRepository;
use Exception;
use Ponto;
use stdClass;

/**
 * Class ServidorOperadoraSaudeDependenteService
 * @package ECidade\RecursosHumanos\Pessoal\Service
 */
class ServidorOperadoraSaudeDependenteService
{
    /**
     * @var ServidorOperadoraSaudeDependenteRepository
     */
    private $repositorio;

    /**
     * ServidorOperadoraSaudeDependenteService constructor.
     */
    public function __construct()
    {
        $this->repositorio = new ServidorOperadoraSaudeDependenteRepository();
    }

    /**
     * @param stdClass $parametros
     * @throws Exception
     */
    public function excluir(stdClass $parametros)
    {
        if (empty($parametros->sequencial)) {
            throw new Exception('É necessário informar o código sequencial.');
        }

        $dependente = ServidorOperadoraSaudeDependenteRepository::find($parametros->sequencial);

        $this->repositorio->delete($dependente);
        $this->atualizarValorDesconto($dependente->getServidorOperadoraSaude());
    }

    /**
     * @param $parametros
     * @return ServidorOperadoraSaudeDependente
     * @throws Exception
     */
    public function salvar($parametros)
    {
        if (empty($parametros->codigoDependente)) {
            throw new Exception('O campo "Dependente" é obrigatório.');
        }

        if (empty($parametros->tipoDependente)) {
            throw new Exception('O campo "Tipo" é obrigatório.');
        }

        if (empty($parametros->codigoPlanoSaudeServidor)) {
            throw new Exception("É necessário informar o servidor vinculado.");
        }

        $dependente = DependenteRepository::find($parametros->codigoDependente);
        $servidorOperadoraSaude = ServidorOperadoraSaudeRepository::find($parametros->codigoPlanoSaudeServidor);

        if ($dependente->getMatricula() != $servidorOperadoraSaude->getServidor()->getMatricula()) {
            throw new Exception("O dependente não possui vínculo com o servidor.");
        }

        $repositorio = $this->repositorio->scopeDependente($dependente)->scopeServidorOperadoraSaude($servidorOperadoraSaude);

        if ($parametros->sequencial) {
            $repositorio->scopeSequencial($parametros->sequencial, '!=');
        }

        if ($repositorio->count()) {
            throw new Exception("Já existe um vínculo do dependente com o plano de saúde {$servidorOperadoraSaude->getOperadoraSaude()->getNome()}.");
        }

        $servidorOperadoraSaudeDependente = new ServidorOperadoraSaudeDependente();
        $servidorOperadoraSaudeDependente->setSequencial($parametros->sequencial);
        $servidorOperadoraSaudeDependente->setDependente($dependente);
        $servidorOperadoraSaudeDependente->setValor($parametros->valor);
        $servidorOperadoraSaudeDependente->setServidorOperadoraSaude($servidorOperadoraSaude);
        $servidorOperadoraSaudeDependente->setTipo($parametros->tipoDependente);
        $servidorOperadoraSaudeDependente = $this->repositorio->save($servidorOperadoraSaudeDependente);

        $this->atualizarValorDesconto($servidorOperadoraSaude);

        return $servidorOperadoraSaudeDependente;
    }

    /**
     * @param ServidorOperadoraSaude $servidorOperadoraSaude
     * @throws Exception
     */
    private function atualizarValorDesconto(ServidorOperadoraSaude $servidorOperadoraSaude)
    {
        $servidorService = new ServidorOperadoraSaudeService();
        $valor = $servidorService->calcularTotalDesconto($servidorOperadoraSaude);

        $servidor = $servidorOperadoraSaude->getServidor();
        $rubrica = $servidorOperadoraSaude->getRubrica();

        $pontoService = new PontoService();
        $pontoService->adicionarRubrica($servidor, $rubrica, Ponto::FIXO, $valor, 1);
        $pontoService->adicionarRubrica($servidor, $rubrica, Ponto::SALARIO, $valor, 1);
    }
}
