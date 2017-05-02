<?php

namespace SosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserCritere
 *
 * @ORM\Table(name="user_critere")
 * @ORM\Entity(repositoryClass="SosBundle\Repository\UserCritereRepository")
 */
class UserCritere
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="PosteRecherche")
     */
    private $poste;

    /**
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="criteres")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Etablissement")
     */
    private $etablissement;
    
    /**
     * @ORM\ManyToOne(targetEntity="Secteur")
     */
    private $secteur;


    /**
     * @ORM\ManyToOne(targetEntity="Service")
     */
    private $service;

    /**
     * @ORM\ManyToOne(targetEntity="Contrat")
     */
    private $contrat;

    /**
     * @ORM\ManyToOne(targetEntity="TypeContrat")
     */
    private $typeContrat;

    /**
     * @ORM\ManyToOne(targetEntity="Experience")
     */
    private $experience;

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
     * @var int
     *
     * @ORM\Column(name="rayon_emploi", type="integer", length=11)
     */
    public $rayonEmploi;

    /**
     * @return mixed
     */
    public function getSecteur()
    {
        return $this->secteur;
    }

    /**
     * @param mixed $secteur
     */
    public function setSecteur($secteur)
    {
        $this->secteur = $secteur;
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function getTypeContrat()
    {
        return $this->typeContrat;
    }

    /**
     * @param mixed $typeContrat
     */
    public function setTypeContrat($typeContrat)
    {
        $this->typeContrat = $typeContrat;
    }

    /**
     * @return mixed
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * @param mixed $experience
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;
    }

    /**
     * @return mixed
     */
    public function getFormation()
    {
        return $this->formation;
    }

    /**
     * @param mixed $formation
     */
    public function setFormation($formation)
    {
        $this->formation = $formation;
    }

    /**
     * @return mixed
     */
    public function getCursus()
    {
        return $this->cursus;
    }

    /**
     * @param mixed $cursus
     */
    public function setCursus($cursus)
    {
        $this->cursus = $cursus;
    }

    /**
     * @return mixed
     */
    public function getDateDisponibilite()
    {
        return $this->dateDisponibilite;
    }

    /**
     * @param mixed $dateDisponibilite
     */
    public function setDateDisponibilite($dateDisponibilite)
    {
        $this->dateDisponibilite = $dateDisponibilite;
    }

    /**
     * @ORM\ManyToOne(targetEntity="Formation")
     */
    private $formation;

    /**
     * @ORM\ManyToOne(targetEntity="CursusScolaire")
     */
    private $cursus;

    /**
     * @ORM\Column(name="date_disponibilite", type="text")
     */
    private $dateDisponibilite;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set poste
     *
     * @param string $poste
     *
     * @return UserCritere
     */
    public function setPoste($poste)
    {
        $this->poste = $poste;

        return $this;
    }

    /**
     * Get poste
     *
     * @return string
     */
    public function getPoste()
    {
        return $this->poste;
    }

    /**
     * Set user
     *
     * @param string $user
     *
     * @return UserCritere
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set etablissement
     *
     * @param string $etablissement
     *
     * @return UserCritere
     */
    public function setEtablissement($etablissement)
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    /**
     * Get etablissement
     *
     * @return string
     */
    public function getEtablissement()
    {
        return $this->etablissement;
    }

    /**
     * Set contrat
     *
     * @param string $contrat
     *
     * @return UserCritere
     */
    public function setContrat($contrat)
    {
        $this->contrat = $contrat;

        return $this;
    }

    /**
     * Get contrat
     *
     * @return string
     */
    public function getContrat()
    {
        return $this->contrat;
    }

}

