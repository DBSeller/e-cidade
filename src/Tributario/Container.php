<?php

namespace ECidade\Tributario;

use ECidade\Tributario\Library\Container as ContainerAbstract;
use ECidade\Tributario\Arrecadacao\Container as ArrecadacaoContainer;
use ECidade\Tributario\Cadastro\Container as CadastroContainer;
use ECidade\Tributario\Caixa\Container as CaixaContainer;
use ECidade\Tributario\Configuracao\Container as ConfiguracaoContainer;
use ECidade\Tributario\Inflatores\Container as InflatoresContainer;
use ECidade\Tributario\Issqn\Container as IssqnContainer;
use ECidade\Tributario\Divida\Container as DividaContainer;
use ECidade\Tributario\Juridico\Container as JuridicoContainer;

final class Container extends ContainerAbstract
{
    private $arrecadacaoContainer;

    private $cadastroContainer;

    private $caixaContainer;

    private $configuracaoContainer;

    private $inflatoresContainer;

    private $issqnContainer;

    private $dividaContainer;

    private $juridicoContainer;

    public function __construct($container)
    {
        $this->arrecadacaoContainer = new ArrecadacaoContainer($container);
        $this->cadastroContainer = new CadastroContainer($container);
        $this->caixaContainer = new CaixaContainer($container);
        $this->configuracaoContainer = new ConfiguracaoContainer($container);
        $this->inflatoresContainer = new InflatoresContainer($container);
        $this->issqnContainer = new IssqnContainer($container);
        $this->dividaContainer = new DividaContainer($container);
        $this->juridicoContainer = new JuridicoContainer($container);

        parent::__construct($container);
    }

    public function charge()
    {
        $this->content = array(
            'DataBase' => function ($container) {
                return \ECidade\Tributario\Library\DataBase::getInstance();
            },
            'File' => function ($container) {
                return new \ECidade\Library\File\File();
            },
            'FileService' => function ($container) {
                $file = $container->get('File');
                return new \ECidade\Library\File\FileService($file);
            },
            'Format' => function ($container) {
                return new \ECidade\Tributario\Library\Format();
            },
            'Session' => function ($container) {
                return new \ECidade\Tributario\Library\Session();
            },
            'DataBaseLegacy' => function ($container) {
                return \ECidade\V3\Datasource\Database::getInstance();
            },
        );

        $this->content = array_merge(
            $this->content,
            $this->arrecadacaoContainer->getContent(),
            $this->cadastroContainer->getContent(),
            $this->caixaContainer->getContent(),
            $this->configuracaoContainer->getContent(),
            $this->inflatoresContainer->getContent(),
            $this->issqnContainer->getContent(),
            $this->dividaContainer->getContent(),
            $this->juridicoContainer->getContent()
        );

        foreach ($this->content as $name => $value) {
            $this->register($name, $value);
        }
    }
}
