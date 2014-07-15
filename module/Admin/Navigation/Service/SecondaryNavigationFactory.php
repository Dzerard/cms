<?php

namespace Admin\Navigation\Service;

use Zend\Navigation\Service\AbstractNavigationFactory;

class SecondaryNavigationFactory extends AbstractNavigationFactory
{
    protected function getName()
    {
        return 'second';
    }
}