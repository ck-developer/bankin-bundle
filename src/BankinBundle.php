<?php

namespace Bankin\Bundle;

use Bankin\Bundle\DependencyInjection\Compiler\HttplugPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BankinBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new HttplugPass());
    }
}