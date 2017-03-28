<?php

namespace SosBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SosBundle extends Bundle
{
    //declare bundle as a child of the FOSUserBundle so we can override the parent bundle's templates
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
