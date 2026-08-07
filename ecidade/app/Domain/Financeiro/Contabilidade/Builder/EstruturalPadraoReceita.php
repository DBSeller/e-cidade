<?php

namespace App\Domain\Financeiro\Contabilidade\Builder;

class EstruturalPadraoReceita
{
    protected $mapa = [
        ['inicio' => 0, 'tamanho' => 1],
        ['inicio' => 1, 'tamanho' => 1],
        ['inicio' => 2, 'tamanho' => 1],
        ['inicio' => 3, 'tamanho' => 1],
        ['inicio' => 4, 'tamanho' => 2],
        ['inicio' => 6, 'tamanho' => 1],
        ['inicio' => 7, 'tamanho' => 1],
        ['inicio' => 8, 'tamanho' => 2],
        ['inicio' => 10, 'tamanho' => 2],
        ['inicio' => 12, 'tamanho' => 2],
    ];

    protected $mapaDeducao = [
        ['inicio' => 0, 'tamanho' => 1],
        ['inicio' => 1, 'tamanho' => 1],
        ['inicio' => 2, 'tamanho' => 1],
        ['inicio' => 3, 'tamanho' => 1],
        ['inicio' => 4, 'tamanho' => 1],
        ['inicio' => 5, 'tamanho' => 2],
        ['inicio' => 7, 'tamanho' => 1],
        ['inicio' => 8, 'tamanho' => 1],
        ['inicio' => 9, 'tamanho' => 2],
        ['inicio' => 11, 'tamanho' => 2],
        ['inicio' => 13, 'tamanho' => 2],
    ];

    /**
     * Esse valor é do e-cidade apenas.
     *   Classe: 4 são as receitas
     *   Classe: 9 são as deduções
     * @var integer
     */
    protected $classe;

    /**
     * Estrutural da conta
     * @var array
     */
    protected $estrutural = [];

    /**
     * @param string $estrutural
     * @param integer $classe
     */
    public function __construct($estrutural, $classe)
    {
        $this->classe = $classe;
        $this->setEstrutural($estrutural);
    }

    /**
     * Retorna o estrutural com máscara
     * @return string
     */
    public function estruturalComMascara()
    {
        return implode('.', $this->estrutural);
    }

    /**
     * Retorna o estrutural sem máscara
     * @return string
     */
    public function estruturalSemMascara()
    {
        return implode('', $this->estrutural);
    }

    /**
     * Retorna o nível da conta
     * @return int
     */
    public function getNivel()
    {
        $nivel = $this->classe === 9 ? 11 : 10;
        $estrutural = array_reverse($this->estrutural);
        foreach ($estrutural as $valor) {
            if ($valor === str_repeat('0', strlen($valor))) {
                $nivel--;
            }

            if ($valor !== str_repeat('0', strlen($valor))) {
                return $nivel;
            }
        }
    }

    /**
     * Retorna o estrutural até o Nível da conta
     * @return string
     */
    public function estruturalAteNivel()
    {
        $nivel = $this->getNivel();

        $estrutural = '';
        for ($i = 0; $i < $nivel; $i++) {
            $estrutural .= $this->estrutural[$i];
        }
        return $estrutural;
    }

    /**
     * Retorna o estrutural pai da conta
     * @return string
     */
    public function estruturalPai()
    {
        $estrutural = $this->estrutural;
        $nivel = $this->getNivel() - 1;

        $estrutural[$nivel] = str_repeat('0', strlen($estrutural[$nivel]));
        return implode('.', $estrutural);
    }

    /**
     * @param $estrutural
     * @return void
     */
    private function setEstrutural($estrutural)
    {
        if (strpos($estrutural, '.')) {
            $estrutural = str_replace('.', '', $estrutural);
        }

        $mapa = $this->classe === 9 ? $this->mapaDeducao : $this->mapa;
        foreach ($mapa as $item) {
            $estrutural = str_pad($estrutural, '15', '0', STR_PAD_RIGHT);
            $this->estrutural[] = substr($estrutural, $item['inicio'], $item['tamanho']);
        }
    }
}
