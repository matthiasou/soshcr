<?php

namespace SosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use SosBundle\Form\AdresseType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('prenom', null, array('label' => 'Prénom'));
        $builder->add('nom');
        $builder->add('telephone', null, array('label' => 'Téléphone'));
        $builder->add('dateNaissance', TextType::class, array('label' => 'Date de naissance'));
        // $builder->add('adresse', AdresseType::class);
        $builder->remove('username');

    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';

        // Or for Symfony < 2.8
        // return 'fos_user_registration';
    }
}