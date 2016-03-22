<?php

namespace EsSandbox\Bundle\AppBundle;

use EsSandbox\Bundle\AppBundle\DependencyInjection\EsSandboxAppExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{
    /** {@inheritdoc} */
    public function getContainerExtension()
    {
        return new EsSandboxAppExtension();
    }
}
