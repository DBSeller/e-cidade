<?php

namespace ECidade\Patrimonial;

use ECidade\Tributario\Library\Container as ContainerAbstract;
use ECidade\Patrimonial\Protocolo\Container as ProtocoloContainer;

final class Container extends ContainerAbstract
{
    private $protocoloContainer;

    public function __construct($container)
    {
        $this->protocoloContainer = new ProtocoloContainer($container);
        parent::__construct($container);
    }

    /**
     * @todo rever uma forma de gerar container do protocolo para poder segmentar os servicos
     */
    public function charge()
    {
        $this->content = array(
            'DataBase' => function ($container) {
                return \ECidade\Tributario\Library\DataBase::getInstance();
            },
            'File' => function ($container) {
                return new \ECidade\Tributario\Library\File\File();
            },
            'FileService' => function ($container) {
                $file = $container->get('File');
                return new \ECidade\Tributario\Library\File\FileService($file);
            },
            'Format' => function ($container) {
                return new \ECidade\Tributario\Library\Format();
            },
            'Session' => function ($container) {
                return new \ECidade\Tributario\Library\Session();
            }
        );

        $this->content = array_merge(
            $this->content,
            $this->protocoloContainer->getContent()
        );

        foreach ($this->content as $name => $value) {
            $this->register($name, $value);
        }
    }
}
