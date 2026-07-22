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

namespace ECidade\Financeiro\Contabilidade\PlanoDeContas;

/**
 * Class Estrutural
 *
 * Foi visto que essa classe não representa a MASCARA da Despesa e sim do PCASP.
 * Mesmo considerando o três (3) a mais presente no estrutural do e-cidade estaria errado a mascara deveria ser:
 * 3.3.3.90.30.00.00.00.00 e hoje o e-Cidade faz assim: 3.3.3.9.0.30.00.00.00.00 [ERRADO]
 *
 * Classe para controle de um estrutural da contabilidade
 * @package ECidade\Financeiro\Contabilidade\PlanoDeContas
 */
class Estrutural
{
    /**
     * @var string
     */
    protected $estrutural;

    /**
     * Estrutural constructor.
     * @param $estrutural
     */
    public function __construct($estrutural)
    {
        $this->estrutural = str_replace('.', '', $estrutural);
        $this->estrutural = str_pad($this->estrutural, 15, '0', STR_PAD_RIGHT);
    }

    /**
     * Retorna o nível que o estrutural possui
     * EX: 3.3.3.9.0.30.00.00.00.00
     * RETORNA: 6
     * @return int
     */
    public function getNivel()
    {
        $niveis = explode(".", $this->getEstruturalComMascara());
        $nivelRetorno = 1;
        foreach ($niveis as $indice => $nivelEstrutural) {
            $tamanhoNivel = strlen($nivelEstrutural);
            if ($nivelEstrutural != str_repeat('0', $tamanhoNivel)) {
                $nivelRetorno = $indice + 1;
            }
        }
        return $nivelRetorno;
    }

    /**
     * Retorna o estrutural até o nível dele
     * EX: 3.3.3.9.0.30.00.00.00.00
     * RETORNA: 3339030
     * @return string
     */
    public function getEstruturalAteNivel()
    {
        $partesEstrutural = explode(".", $this->getEstruturalComMascara());
        return implode('', array_slice($partesEstrutural, 0, $this->getNivel()));
    }

    /**
     * @return string
     */
    public function getEstrutural()
    {
        return $this->estrutural;
    }

    /**
     * Retorna o estrutural com máscara aplicada.
     * EX: 3.3.3.9.0.30.00.00.00.00
     * @return string
     */
    public function getEstruturalComMascara()
    {
        return db_formatar($this->estrutural, 'receita');
    }

    public function getCodigoEstruturalPai()
    {
        $aNiveis = explode(".", $this->getEstruturalComMascara());
        $iNivel = $this->getNivel() - 1;
        $iTamanho = strlen($aNiveis[$iNivel]);
        $aNiveis[$iNivel] = str_repeat('0', $iTamanho);
        return implode(".", $aNiveis);
    }

    public function getEstruturalPai()
    {
        return new static($this->getCodigoEstruturalPai());
    }
}
