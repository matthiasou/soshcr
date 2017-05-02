<?php

namespace SosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

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
     * @ORM\Column(name="date_naissance", type="date")
     */
    public $dateNaissance;

    /**
     *
     * @ORM\OneToMany(targetEntity="UserCritere", mappedBy="user")
     */
    public $criteres;

    /**
     * @var string
     *
     * @ORM\Column(name="date_abonnement", type="date", nullable=true)
     */
    public $dateAbonnement;


    /**
     * @ORM\Column(name="message_5J", type="boolean", nullable=true)
     */
    private $message5J;

 

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return User
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     *
     * @return User
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set dateAbonnement
     *
     * @param \DateTime $dateAbonnement
     *
     * @return User
     */
    public function setDateAbonnement($dateAbonnement)
    {
        $this->dateAbonnement = $dateAbonnement;

        return $this;
    }

    /**
     * Get dateAbonnement
     *
     * @return \DateTime
     */
    public function getDateAbonnement()
    {
        return $this->dateAbonnement;
    }

    /**
     * Set message5J
     *
     * @param boolean $message5J
     *
     * @return User
     */
    public function setMessage5J($message5J)
    {
        $this->message5J = $message5J;

        return $this;
    }

    /**
     * Get message5J
     *
     * @return boolean
     */
    public function getMessage5J()
    {
        return $this->message5J;
    }

    /**
     * Add critere
     *
     * @param \SosBundle\Entity\UserCritere $critere
     *
     * @return User
     */
    public function addCritere(\SosBundle\Entity\UserCritere $critere)
    {
        $this->criteres[] = $critere;

        return $this;
    }

    /**
     * Remove critere
     *
     * @param \SosBundle\Entity\UserCritere $critere
     */
    public function removeCritere(\SosBundle\Entity\UserCritere $critere)
    {
        $this->criteres->removeElement($critere);
    }

    /**
     * Get criteres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCriteres()
    {
        return $this->criteres;
    }
}
