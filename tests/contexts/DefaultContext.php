<?php

namespace tests\contexts;

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Symfony2Extension\Context\KernelDictionary;

class DefaultContext implements KernelAwareContext, SnippetAcceptingContext
{
    use KernelDictionary;

    protected function container()
    {
        return $this->getContainer();
    }
}
