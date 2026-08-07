<?php


namespace ECidade\Api\V1\Models\Session;


use ECidade\Configuracao\Cadastro\Model\Menu;
use ECidade\Configuracao\Cadastro\Model\Modulo;
use ECidade\Configuracao\Cadastro\Repository\MenuRepository;
use ECidade\Configuracao\Cadastro\Repository\ModuloRepository;
use Exception;

class MenuAcessadoSession
{
    /**
     * @var Menu
     */
    private $menu;
    /**
     * @var Modulo
     */
    private $modulo;

    /**
     * MenuModulo constructor.
     * @param Menu $menu
     * @param Modulo $modulo
     */
    protected function __construct($menu, $modulo)
    {
        $this->menu = $menu;
        $this->modulo = $modulo;
    }

    /**
     * @param integer $codigoMenu
     * @param integer $codigoModulo
     * @return MenuAcessadoSession
     * @throws Exception
     */
    public static function build($codigoMenu, $codigoModulo)
    {
        $menu = MenuRepository::find($codigoMenu);
        $modulo = ModuloRepository::find($codigoModulo);
        return new MenuAcessadoSession($menu, $modulo);
    }

    /**
     * @return Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @param Menu $menu
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;
    }

    /**
     * @return Modulo
     */
    public function getModulo()
    {
        return $this->modulo;
    }

    /**
     * @param Modulo $modulo
     */
    public function setModulo($modulo)
    {
        $this->modulo = $modulo;
    }
}
