<?php

namespace ECidade\Tributario\Integracao\Civitas\Parser;
use ECidade\Tributario\Cadastro\Iptu\Recadastramento\Construcao;

/**
 * Class Contrucao Responsavel por gerar o parser do Objeto Contrucao
 *
 * @author Augusto Oliveira <augusto.oliveira@dbseller.com.br>
 * @package ECidade\Tributario\Integracao\Civitas\Parser
 */
class ContrucaoParser
{

    /**
     * Methodo responsavel por fazer parser do array recebido
     *
     * @param array $aLinha
     * @return Construcao
     */
  public static function parser(array $aLinha)
  {

      $aCaracteristicas = array(
          $aLinha[20], $aLinha[22],
          $aLinha[24], $aLinha[26],
          $aLinha[28], $aLinha[30],
          $aLinha[32], $aLinha[34],
          $aLinha[36], $aLinha[38],
          $aLinha[40], $aLinha[42],
          $aLinha[44], $aLinha[46],
          $aLinha[48], $aLinha[50],
          $aLinha[52]
      );

      $aCaracteristicas = array_filter($aCaracteristicas);

      $oConstrucao = new Construcao();

      $oConstrucao->setMatricula($aLinha[1]);
      $oConstrucao->setAreaConstrucao($aLinha[10]);
      $oConstrucao->setIdConstrucao($aLinha[9]);
      $oConstrucao->setCaracteristicas($aCaracteristicas);
      $oConstrucao->setIdbql($aLinha[2]);
      $oConstrucao->setRua($aLinha[6]);
      $oConstrucao->setComplemento(substr(trim($aLinha[15]), 0, 20));

      $iNumero = !empty($aLinha[7]) ? $aLinha[7] : 0;

      $oConstrucao->setNumero($iNumero);

      if (!empty($aLinha[14])) {
          $aDataDemolicao = explode(' ', $aLinha[14]);
          $oConstrucao->setDataDemolicao(new \DBDate($aDataDemolicao[0]));
      }

      return  $oConstrucao;
  }

}