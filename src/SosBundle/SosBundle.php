<?php

namespace SosBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SosBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
