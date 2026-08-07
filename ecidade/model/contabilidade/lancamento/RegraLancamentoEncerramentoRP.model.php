<?php

/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2009 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

require_once(modification("interfaces/IRegraLancamentoContabil.interface.php"));

/**
 * Class RegraLancamentoEncerramentoRP
 */
class RegraLancamentoEncerramentoRP implements IRegraLancamentoContabil
{
    /**
     * @var array
     */
    private $regras;

    /**
     * @var array
     */
    private $regrasExercicioAtual;

    /**
     * @var array
     */
    private $regrasExercicioAnterior;

    /**
     * @var
     */
    private $oEventoContabil;

    /**
     * @var
     */
    private $oLancamentoEventoContabil;

    /**
     * @param int $iCodigoDocumento
     * @param int $iCodigoLancamento
     * @param ILancamentoAuxiliar $oLancamentoAuxiliar
     * @return RegraLancamentoContabil|bool
     * @throws Exception
     */
    public function getRegraLancamento($iCodigoDocumento, $iCodigoLancamento, ILancamentoAuxiliar $oLancamentoAuxiliar)
    {
        $exercicioAtual = db_getsession('DB_anousu');

        $this->oEventoContabil = EventoContabilRepository::getEventoContabilByCodigo($iCodigoDocumento, $exercicioAtual);
        $this->oLancamentoEventoContabil = $this->oEventoContabil->getEventoContabilLancamentoPorCodigo($iCodigoLancamento);
        $this->regras = $this->oLancamentoEventoContabil->getRegrasLancamento();

        if (!$this->oLancamentoEventoContabil || empty($this->regras)) {
            return false;
        }

        if (count($this->regras) == 1) {
            return array_shift($this->regras);
        }

        $this->validaRegras($exercicioAtual);

        if ($oLancamentoAuxiliar->getEmpenho()->getAno() == $exercicioAtual) {
            return array_shift($this->regrasExercicioAtual);
        }

        return array_shift($this->regrasExercicioAnterior);
    }

    /**
     * @param $exercicioAtual
     * @throws Exception
     */
    private function validaRegras($exercicioAtual)
    {
        $mensagem = "Documento: {$this->oEventoContabil->getCodigoDocumento()} - {$this->oEventoContabil->getDescricaoDocumento()}\n";
        $mensagem .= "Lançamento: {$this->oLancamentoEventoContabil->getDescricao()}\n\n";
        $mensagem .= "Verifique o cadastro de transação.";

        $regrasExercicioPosterior = array_values(array_filter($this->regras, function ($oRegra) use ($exercicioAtual) {
            return $oRegra->getAnoUso() > $exercicioAtual;
        }));

        if ($regrasExercicioPosterior) {
            throw new Exception("Não pode haver regra cadastrada para exercício posterior à {$exercicioAtual}.\n\n{$mensagem}");
        }

        $this->regrasExercicioAtual = array_values(array_filter($this->regras, function ($oRegra) use ($exercicioAtual) {
            return $oRegra->getAnoUso() == $exercicioAtual;
        }));

        if (empty($this->regrasExercicioAtual)) {
            throw new Exception("Não há regra cadastrada para o exercício de {$exercicioAtual}.\n\n{$mensagem}");
        }

        $this->regrasExercicioAnterior = array_values(array_filter($this->regras, function ($oRegra) use ($exercicioAtual) {
            return $oRegra->getAnoUso() < $exercicioAtual;
        }));

        if (empty($this->regrasExercicioAnterior)) {
            throw new Exception("Não há regra cadastrada para exercícios anteriores à {$exercicioAtual}.\n\n{$mensagem}");
        }
    }
}
