<?php

namespace ECidade\Educacao\Escola\Service;

use App\Domain\Educacao\Secretaria\Models\ParametrosNotificacao;
use ECidade\Educacao\Escola\Model\Aluno;
use ECidade\Educacao\Escola\Repository\AlunoRepository;
use ECidade\Lib\Mail\SendMail;
use PHPMailer\PHPMailer\Exception;

class NotificacaoTransferenciaService
{
    /**
     * @var string
     */
    private $guiaTransferencia = '';

    /**
     * @var \Escola
     */
    private $escolaOrigem;

    /**.
     * @var \Escola|\EscolaProcedencia
     */
    private $escolaDestino;
    private $aluno;
    /**
     * @var DBDate
     */
    private $dataTransferencia;

    public function __construct($escolaOrigem, $escolaDestino, $codigoAluno, $tipo, $dataTransferencia)
    {
        $this->escolaOrigem = \EscolaRepository::getEscolaByCodigo($escolaOrigem);
        if ($tipo == 'TR') {
            $this->escolaDestino = new \Escola($escolaDestino);
        } else {
            $this->escolaDestino = new \EscolaProcedencia($escolaDestino);
        }
        $this->aluno = AlunoRepository::find($codigoAluno);

        $this->dataTransferencia = new \DBDate($dataTransferencia);
    }

    /**
     * @param $escolaOrigem
     * @param $escolaDestino
     * @param $codigoAluno
     * @param $tipo
     * @param $observacoes
     * @throws Exception|\Exception
     */
    public function notificar($turmaOrigem, $observacoes)
    {
        $parametros = ParametrosNotificacao::first();
        $emails = [];
        if ($parametros->ed177_notificar_escolas) {
            $emails[] = (object)[
                "email" => $this->escolaOrigem->getEmail(),
                "nome" => $this->escolaOrigem->getNome()
            ];
            $emails[] = (object)[
                "email" => $this->escolaDestino->getEmail(),
                "nome" => $this->escolaDestino->getNome()
            ];
        }
        if ($parametros->ed177_notificar_secretaria) {
            $emails[] = (object)[
                "email" => $parametros->ed177_email_secretaria,
                "nome" => $parametros->ed177_email_secretaria,
            ];
        }
        foreach ($emails as $email) {
            try {
                $mail = new SendMail(true);
                if (empty($email->email)) {
                    continue;
                }
                $mail->addAddress($email->email, $email->nome);
                if (!empty($this->escolaOrigem->getEmail())) {
                    $mail->addReplyTo($this->escolaOrigem->getEmail(), $this->escolaOrigem->getAbreviatura());
                }
                if ($this->guiaTransferencia != '') {
                    $mail->addAttachment($this->guiaTransferencia);
                }

                //Content
                $data = $this->dataTransferencia->getDate(\DBDate::DATA_PTBR);
                    
                $mensagem = "Informamos efetivação de transferência em {$data}:\r\n\r\n";
                $mensagem .= "Aluno: {$this->aluno->getCodigo()} - {$this->aluno->getNome()}\r\n\r\n";
                $mensagem .= "Escola de origem: {$this->escolaOrigem->getNome()}\r\n\r\n";
                $mensagem .= "Turma: {$turmaOrigem}\r\n\r\n";
                $mensagem .= "Escola destino: {$this->escolaDestino->getNome()}\r\n\r\n";
                $mensagem .= $observacoes;

                $mensagemHtml = "<p>Informamos efetivação de transferência em {$data}:</p>";
                $mensagemHtml .= "<p>Aluno: <b>{$this->aluno->getCodigo()} - {$this->aluno->getNome()}</b></p>";
                $mensagemHtml .= "<p>Escola de origem: {$this->escolaOrigem->getNome()}</p>";
                $mensagemHtml .= "<p>Turma: {$turmaOrigem}</p>";
                $mensagemHtml .= "<p>Escola destino: {$this->escolaDestino->getNome()}</p>";
                $mensagemHtml .= $observacoes;

                $tipo = "REDE";
                if ($this->escolaDestino instanceof \EscolaProcedencia) {
                    $tipo = "FORA";
                }
                $mail->Subject = "Informe de transferência ({$tipo}) - {$this->aluno->getNome()}";
                $mail->Body = $mensagemHtml;
                $mail->AltBody = $mensagem;
                $mail->send();
            } catch (Exception $e) {
                throw new \Exception("A mensagem não foi enviada. Erro: {$mail->ErrorInfo}");
            }
        }
    }

    public function setGuiaTransferencia($path)
    {
        $this->guiaTransferencia = $path;
    }

    public function notificarCancelamento($turmaOrigem)
    {
        $parametros = ParametrosNotificacao::first();
        $emails = [];
        if ($parametros->ed177_notificar_escolas) {
            $emails[] = (object)[
                "email" => $this->escolaOrigem->getEmail(),
                "nome" => $this->escolaOrigem->getNome()
            ];
            $emails[] = (object)[
                "email" => $this->escolaDestino->getEmail(),
                "nome" => $this->escolaDestino->getNome()
            ];
        }
        if ($parametros->ed177_notificar_secretaria) {
            $emails[] = (object)[
                "email" => $parametros->ed177_email_secretaria,
                "nome" => $parametros->ed177_email_secretaria,
            ];
        }
        foreach ($emails as $email) {
            try {
                $mail = new SendMail(true);
                if (empty($email->email)) {
                    continue;
                }
                $mail->addAddress($email->email, $email->nome);
                if (!empty($this->escolaOrigem->getEmail())) {
                    $mail->addReplyTo($this->escolaOrigem->getEmail(), $this->escolaOrigem->getAbreviatura());
                }
                if ($this->guiaTransferencia != '') {
                    $mail->addAttachment($this->guiaTransferencia);
                }

                //Content
                $data = $this->dataTransferencia->getDate(\DBDate::DATA_PTBR);
                $mensagem = "Informamos o cancelamento da transferência em {$data}:\r\n";
                $mensagem .= "Aluno: {$this->aluno->getCodigo()} - {$this->aluno->getNome()}\r\n\r\n";
                $mensagem .= "Escola de origem: {$this->escolaOrigem->getNome()}\r\n";
                $mensagem .= "Turma: {$turmaOrigem}\r\n\r\n";
                $mensagem .= "Escola destino: {$this->escolaDestino->getNome()}\r\n\r\n";

                $tipo = "REDE";
                if ($this->escolaDestino instanceof \EscolaProcedencia) {
                    $tipo = "FORA";
                }

                $mail->Subject = "Cancelamento de transferência ({$tipo}) - {$this->aluno->getNome()}";
                $mail->Body = $mensagem;

                $mail->send();
            } catch (Exception $e) {
                throw new \Exception("A mensagem não foi enviada. Erro: {$mail->ErrorInfo}");
            }
        }
    }
}
