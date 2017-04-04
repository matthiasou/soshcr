<?php

namespace SosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use SosBundle\Form\AdresseType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('prenom');
        $builder->add('nom');
        $builder->add('telephone');
        $builder->add('niveauAnglais', EntityType::class, array(
            'class' => 'SosBundle:Anglais',
            'choice_label' => 'libelle'));
        $builder->add('cursusScolaire', EntityType::class, array(
            'class' => 'SosBundle:CursusScolaire',
            'choice_label' => 'libelle'));
        $builder->add('rayonEmploi');
        $builder->add('dateNaissance', DateType::class, array(
            'widget' => 'single_text',
        ));
        $builder->add('score', HiddenType::class, array(
            'data' => 0));
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