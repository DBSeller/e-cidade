<?php

namespace ECidade\Tributario\Library;

use ECidade\V3\Extension\Registry as ExtensionRegistry;

final class Registry
{
    public static function getContainer()
    {
        return ExtensionRegistry::get('app.container')->get('tributario.container');
    }
}
