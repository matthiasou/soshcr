<?php

namespace SosBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="utilisateur")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    public $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    public $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=255, unique=true)
     */
    public $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="date_naissance", type="string", length=255)
     */
    public $dateNaissance;

    /**
     * @var int
     *
     * @ORM\Column(name="score", type="integer", length=11)
     */
    public $score;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Anglais")
     */
    public $niveauAnglais;

    /**
     *
     * @ORM\ManyToOne(targetEntity="CursusScolaire")
     */
    public $cursusScolaire;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Adresse")
     */
    public $adresse;

    /**
     * @var int
     *
     * @ORM\Column(name="rayon_emploi", type="integer", length=11)
     */
    public $rayonEmploi;

    /**
     *
     * @ORM\OneToMany(targetEntity="UserCritere", mappedBy="user")
     */
    public $criteres;


    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}