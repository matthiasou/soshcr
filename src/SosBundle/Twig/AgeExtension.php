<?php

namespace SosBundle\Twig;

class AgeExtension extends \Twig_Extension{
	
	public function getFilters() {
        return array(
            'age' => new \Twig_Filter_Method($this, 'getAge'),
        );
    }

	public function getAge($date) 
	{
	    if (!$date instanceof \DateTime) {
	        // turn $date into a valid \DateTime object or let return
	        return null;
	    }

	    $referenceDate = date('01-01-Y');
	    $referenceDateTimeObject = new \DateTime($referenceDate);

	    $diff = $referenceDateTimeObject->diff($date);

	    return $diff->y;
	}

}