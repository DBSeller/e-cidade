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

namespace ECidade\Educacao\Escola\Censo\Identificacao;

use Aluno;
use ECidade\Educacao\Escola\Censo\Helpers\Pessoa as PessoaHelper;
use ECidade\Educacao\Escola\Censo\Identificacao\Builder\PessoaBuilder;
use ECidade\Educacao\Escola\Censo\Identificacao\Model\Pessoa;
use ECidade\Educacao\Escola\Repository\ProfissionalEscolaRepository;
use ECidade\File\Csv\Dumper\Dumper;
use Exception;
use stdClass;

/**
 * Class Importar
 * @package ECidade\Educacao\Escola\Censo\Identificacao
 */
class Importar
{
    /**
     * @var Pessoa[]
     */
    private $pessoas = [];

    public function importar(stdClass $file)
    {
        $this->validar($file);
        $this->lerArquivo($file);
        foreach ($this->pessoas as $pessoa) {
            $this->atualizarInep($pessoa);
        }
    }

    private function validar($file)
    {
        if ($file->extension !== 'txt') {
            throw new Exception('Arquivo inválido, extensão do arquivo não é "txt".');
        }
    }

    private function lerArquivo($file)
    {
        $dumperCsv = new Dumper();
        $dumperCsv->setCsvControl('|');
        $linhasArquivo = $dumperCsv->ler($file->path);

        foreach ($linhasArquivo as $linha) {
            if (!is_array($linha)) {
                continue;
            }
            $builder = new PessoaBuilder();
            $this->pessoas[] = $builder->buildFromLine($linha);
        }
    }

    /**
     * @param Pessoa $pessoa
     * @throws Exception
     */
    private function atualizarInep(Pessoa $pessoa)
    {
        if (PessoaHelper::isAluno($pessoa->getCodigoPessoa())) {
            $this->atualizaInepAluno($pessoa);
        } else {
            $this->atualizaInepProfissional($pessoa);
        }
    }

    /**
     * @param Pessoa $pessoa
     * @throws Exception
     */
    public function atualizaInepAluno(Pessoa $pessoa)
    {
        $codigoAluno = PessoaHelper::decodeCodigoAluno($pessoa->getCodigoPessoa());
        $aluno = new Aluno($codigoAluno);
        $aluno->setCodigoInep($pessoa->getInep());
        $aluno->salvar();
    }

    private function atualizaInepProfissional(Pessoa $pessoa)
    {
        $codigo = PessoaHelper::decodeCodigoProfissional($pessoa->getCodigoPessoa());
        $repository = new ProfissionalEscolaRepository();

        $profissionais = $repository->findByCpf($codigo);
        foreach ($profissionais as $profissional) {
            $profissional->setCodigoInep($pessoa->getInep());
            $repository->atualizarINEP($profissional);
        }
    }
}
