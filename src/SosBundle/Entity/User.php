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
     * @var int
     *
     * @ORM\Column(name="score", type="integer", length=11, nullable=true)
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
     * Set score
     *
     * @param integer $score
     *
     * @return User
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set rayonEmploi
     *
     * @param integer $rayonEmploi
     *
     * @return User
     */
    public function setRayonEmploi($rayonEmploi)
    {
        $this->rayonEmploi = $rayonEmploi;

        return $this;
    }

    /**
     * Get rayonEmploi
     *
     * @return integer
     */
    public function getRayonEmploi()
    {
        return $this->rayonEmploi;
    }

    /**
     * Set niveauAnglais
     *
     * @param \SosBundle\Entity\Anglais $niveauAnglais
     *
     * @return User
     */
    public function setNiveauAnglais(\SosBundle\Entity\Anglais $niveauAnglais = null)
    {
        $this->niveauAnglais = $niveauAnglais;

        return $this;
    }

    /**
     * Get niveauAnglais
     *
     * @return \SosBundle\Entity\Anglais
     */
    public function getNiveauAnglais()
    {
        return $this->niveauAnglais;
    }

    /**
     * Set cursusScolaire
     *
     * @param \SosBundle\Entity\CursusScolaire $cursusScolaire
     *
     * @return User
     */
    public function setCursusScolaire(\SosBundle\Entity\CursusScolaire $cursusScolaire = null)
    {
        $this->cursusScolaire = $cursusScolaire;

        return $this;
    }

    /**
     * Get cursusScolaire
     *
     * @return \SosBundle\Entity\CursusScolaire
     */
    public function getCursusScolaire()
    {
        return $this->cursusScolaire;
    }

    /**
     * Set adresse
     *
     * @param \SosBundle\Entity\Adresse $adresse
     *
     * @return User
     */
    public function setAdresse(\SosBundle\Entity\Adresse $adresse = null)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return \SosBundle\Entity\Adresse
     */
    public function getAdresse()
    {
        return $this->adresse;
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

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function setEmail($email){
        parent::setEmail($email);
        $this->setUsername($email);
    }
}
