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
     * @ORM\Column(name="score", type="integer", length=11, nullable=false)
     */
    private $score;

    /**
     * @var int
     *
     * @ORM\Column(name="latitude", type="float", length=11, nullable=false)
     */
    private $latitude;

    /**
     * @var int
     *
     * @ORM\Column(name="longitude", type="float", length=11, nullable=false)
     */
    private $longitude;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Anglais")
     */
    private $niveauAnglais;

    /**
     *
     * @ORM\ManyToOne(targetEntity="CursusScolaire")
     */
    private $cursusScolaire;

    /**
     * @var int
     *
     * @ORM\Column(name="rayon_emploi", type="integer", length=11)
     */
    private $rayonEmploi;

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


    /**
     * Set score
     *
     * @param integer $score
     *
     * @return UserCritere
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
     * Set latitude
     *
     * @param float $latitude
     *
     * @return UserCritere
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return UserCritere
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set rayonEmploi
     *
     * @param integer $rayonEmploi
     *
     * @return UserCritere
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
     * @return UserCritere
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
     * @return UserCritere
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
}
