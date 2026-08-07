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

use ECidade\RecursosHumanos\ESocial\Model\ProcessoJudicial\Servidor as ServidorProcesso;
use ECidade\RecursosHumanos\ESocial\Repository\ProcessoJudicial\ServidorRepository as ServidorRepositoryProcesso;
use Exception;
use stdClass;
use DBDate;

class ServidorService
{
    /**
     * @var
     */
    private $processoServidorRepository;

    /**
     * @var
     */
    private $sequencialProcesso;

    /**
     * ProcessoJudicialService constructor.
     */
    public function __construct($sequencialProcesso = null)
    {
        if (empty($sequencialProcesso)) {
            throw new Exception('Vínculo obrigatório de Processo com Servidor não definido. 
                Favor entrar em contato com o suporte.');
        }
        $this->sequencialProcesso = $sequencialProcesso;
        $this->processoServidorRepository = new ServidorRepositoryProcesso();
    }

    /**
     * @throws Exception
     */
    public function salvar(ServidorProcesso $servidor)
    {
        $servidorFolha = \ServidorRepository::getInstanciaByCodigo($servidor->getMatricula());

        $matricula = $servidor->getMatricula();
        $nome = $servidorFolha->getCgm()->getNome();
        if (empty($servidorFolha->getLocalTrabalhoPrincial())) {
            throw new Exception("<i>'Dados do Local de Trabalho'</i> do servidor <strong>({$matricula} - {$nome}) " .
                "</strong>não definido. Favor revisar.");
        }
        $qtdeDigitosInscricao = strlen($servidorFolha->getLocalTrabalhoPrincial()->getInstituicao()->getCNPJ());
        if ($qtdeDigitosInscricao < 11
            && $qtdeDigitosInscricao > 14
            && ($qtdeDigitosInscricao == 11 ||
            $qtdeDigitosInscricao == 14)) {
                throw new Exception("<i> Número de inscrição do empregador não válido em " .
                "'Dados do Local de Trabalho'</i> do servidor <strong>({$matricula} - {$nome}) " .
                "</strong>. Favor revisar.");
        }

        $sequencialSevidorProcesso = ServidorRepositoryProcesso::getExisteSequencialServidorProcesso($servidor);
        $servidor->setSequencial($sequencialSevidorProcesso);

        return $this->processoServidorRepository->save($servidor);
    }

    /**
     * @return Processos
     * @throws Exception
     */
    public function listaServidorProcesso()
    {
        $processosServidores = ServidorRepositoryProcesso::getServidoresProcessos($this->sequencialProcesso);

        $retorno = [];
        if (!empty($processosServidores)) {
            foreach ($processosServidores as $dado) {
                $servidor = \ServidorRepository::getInstanciaByCodigo($dado->matricula);
                $dados = new \stdClass();
                $dados->sequencial = $dado->sequencial;
                $dados->nome = $servidor->getCgm()->getNomeCompleto();
                $dados->matricula = $dado->matricula;
                $retorno[] = $dados;
            }
        }
        return $retorno;
    }
}
