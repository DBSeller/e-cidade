<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 18/02/19
 * Time: 17:19
 */

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Converter;

use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Entity\Detalhe;
use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Enum\TipoRegistro;
use ECidade\Tributario\Arrecadacao\Entity\Matricula;
use ECidade\Tributario\Arrecadacao\Entity\Inscricao;
use ECidade\Tributario\Arrecadacao\Entity\Contribuinte;
use ECidade\Tributario\Caixa\Entity\Strategy\ReciboValorTotal;
use ECidade\Tributario\Library\Entity;

final class DetalheConverter extends Converter
{
    const TIPO_ATUALIZACAO_INCLUSAO  = "01";
    const TIPO_ATUALIZACAO_ALTERACAO = "02";
    const TIPO_ATUALIZACAO_EXCLUSAO  = "03";
    /**
     * @param Entity $entity
     * @return string
     */
    public function build(Entity $entity)
    {
        $valorReciboStrategy = new ReciboValorTotal();
        $valorRecibo = $valorReciboStrategy->calculate($entity->getRecibo());
        //$valorRecibo = 100.23;

        $detalhe  = TipoRegistro::DETALHE;

        $size = $this->layout->getSize(Detalhe::RESPONSAVEL_DEBITO);
        $detalhe .= substr(str_pad($entity->getInstituicao()->getNome(), $size, ' ', STR_PAD_RIGHT), 0, $size);

        $size = $this->layout->getSize(Detalhe::TIPO_ATUALIZACAO);
        $detalhe .= substr(str_pad(self::TIPO_ATUALIZACAO_INCLUSAO, $size, '0', STR_PAD_LEFT), ($size * -1));

        $size = $this->layout->getSize(Detalhe::IDENTIFICACAO_DEVEDOR);


        $cpfCnpj = preg_replace('/\D/', '', $entity->getContribuinte()->getCpfCnpj());
        $detalhe .= substr(str_pad($cpfCnpj, $size, '0', STR_PAD_LEFT), ($size * -1));

        if ($entity->getContribuinte() instanceof Contribuinte) {
            $identificadorContribuinte = Contribuinte::CGM;
        }

        if ($entity->getContribuinte() instanceof Matricula) {
            $identificadorContribuinte = Contribuinte::MATRICULA;
        }

        if ($entity->getContribuinte() instanceof Inscricao) {
            $identificadorContribuinte = Contribuinte::INSCRICAO;
        }

        $size = $this->layout->getSize(Detalhe::IDENTIFICADOR_DEBITO);
        $identificadorContribuinte .= $entity->getContribuinte()->getIdentificador();
        $identificadorContribuinte .= '-';
        $identificadorContribuinte .= $entity->getRecibo()->getNumpre();
        $detalhe .= substr(str_pad($identificadorContribuinte, $size, ' ', STR_PAD_RIGHT), 0, $size);

        $size = $this->layout->getSize(Detalhe::REFERENCIA_DEBITO);
        $detalhe .= substr(str_pad($entity->getNomeTipoDebito(), $size, ' ', STR_PAD_RIGHT), ($size * -1));

        $endereco  = $entity->getContribuinte()->getEndereco()->getTipoRua();
        $endereco .= ' '. $entity->getContribuinte()->getEndereco()->getDescricaoRua() . ',';
        $endereco .= ' '. $entity->getContribuinte()->getEndereco()->getNumeroLocal();
        $endereco .= ' '. '-';
        $endereco .= ' '. $entity->getContribuinte()->getEndereco()->getDescricaoMunicipio();
        $size = $this->layout->getSize(Detalhe::DETALHAMENTO_DEBITO);
        $detalhe .= substr(str_pad($endereco, $size, ' ', STR_PAD_RIGHT), 0, $size);

        $size = $this->layout->getSize(Detalhe::VENCIMENTO_CODIGO_BARRAS);
        $dtVencimento = $entity->getRecibo()->getVencimento()->format("Ymd");
        $detalhe .= substr(str_pad($dtVencimento, $size, ' ', STR_PAD_LEFT), ($size * -1));

        $size = $this->layout->getSize(Detalhe::CODIGO_BARRAS);
        $codigoBarras = preg_replace('/\D/', '', $entity->getRecibo()->getLinhaDigitavel());
        $detalhe .= substr(str_pad($codigoBarras, $size, ' ', STR_PAD_LEFT), ($size * -1));
        
        $size = $this->layout->getSize(Detalhe::VALOR_DEBITO);
        $valorRecibo = preg_replace('/\D/', '', $this->format->decimal($valorRecibo));
        $detalhe .= substr(str_pad($valorRecibo, $size, '0', STR_PAD_LEFT), ($size * -1));
        
        $size = $this->layout->getSize(Detalhe::TIPO_DEBITO);
        $detalhe .= substr(str_pad($entity->getTipoDebito(), $size, '0', STR_PAD_LEFT), ($size * -1));

        $size = $this->layout->getSize(Detalhe::NUMERO_PARCELA);
        $numParcela = $entity->getNumeroParcela() == "" ? "999" : $entity->getNumeroParcela();
        // CASO N TENHA VINCULO COM PARCELAMENTO
        $detalhe .= substr(str_pad($numParcela, $size, ' ', STR_PAD_LEFT), ($size * -1));
        
        $size = $this->layout->getSize(Detalhe::CHASSI_VEICULO);
        $detalhe .= str_repeat(' ', $size);

        $size = $this->layout->getSize(Detalhe::VALOR_VENAL_IMOVEL);
        $detalhe .= str_repeat('0', $size);
        
        $size = $this->layout->getSize(Detalhe::CODIGO_BARRAS_AGRUPADOR);
        $codBarrasAgrup = preg_replace('/\D/', '', $entity->getCodigoBarrasAgrupador());
        $codigoBarrasAgrupador = $entity->getNumeroParcela() == "" ? "" : $codBarrasAgrup;
        $detalhe .= substr(str_pad($codigoBarrasAgrupador, $size, ' ', STR_PAD_LEFT), ($size * -1));

        if (strlen($cpfCnpj) == 14) {
            $tpPessoa = 2;
        } else {
            $tpPessoa = 1;
        }
        $detalhe .= $tpPessoa;
        
        $size = $this->layout->getSize(Detalhe::RESERVADO);
        $detalhe .= str_repeat(' ', $size);
        
        $size = $this->layout->getSize(Detalhe::SEQUENCIAL);
        $detalhe .= substr(str_pad($entity->getSequencial(), $size, '0', STR_PAD_LEFT), ($size * -1));

        return $detalhe;
    }
}
