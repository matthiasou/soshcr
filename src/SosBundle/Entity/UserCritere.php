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
     * @ORM\ManyToOne(targetEntity="Formation")
     */
    private $formation;

    /**
     * @ORM\Column(name="date_disponibilite", type="text")
     */
    private $dateDisponibilite;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
     * Set dateDisponibilite
     *
     * @param string $dateDisponibilite
     *
     * @return UserCritere
     */
    public function setDateDisponibilite($dateDisponibilite)
    {
        $this->dateDisponibilite = $dateDisponibilite;

        return $this;
    }

    /**
     * Get dateDisponibilite
     *
     * @return string
     */
    public function getDateDisponibilite()
    {
        return $this->dateDisponibilite;
    }

    /**
     * Set poste
     *
     * @param \SosBundle\Entity\PosteRecherche $poste
     *
     * @return UserCritere
     */
    public function setPoste(\SosBundle\Entity\PosteRecherche $poste = null)
    {
        $this->poste = $poste;

        return $this;
    }

    /**
     * Get poste
     *
     * @return \SosBundle\Entity\PosteRecherche
     */
    public function getPoste()
    {
        return $this->poste;
    }

    /**
     * Set user
     *
     * @param \SosBundle\Entity\User $user
     *
     * @return UserCritere
     */
    public function setUser(\SosBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \SosBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set etablissement
     *
     * @param \SosBundle\Entity\Etablissement $etablissement
     *
     * @return UserCritere
     */
    public function setEtablissement(\SosBundle\Entity\Etablissement $etablissement = null)
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    /**
     * Get etablissement
     *
     * @return \SosBundle\Entity\Etablissement
     */
    public function getEtablissement()
    {
        return $this->etablissement;
    }

    /**
     * Set secteur
     *
     * @param \SosBundle\Entity\Secteur $secteur
     *
     * @return UserCritere
     */
    public function setSecteur(\SosBundle\Entity\Secteur $secteur = null)
    {
        $this->secteur = $secteur;

        return $this;
    }

    /**
     * Get secteur
     *
     * @return \SosBundle\Entity\Secteur
     */
    public function getSecteur()
    {
        return $this->secteur;
    }

    /**
     * Set service
     *
     * @param \SosBundle\Entity\Service $service
     *
     * @return UserCritere
     */
    public function setService(\SosBundle\Entity\Service $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \SosBundle\Entity\Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set contrat
     *
     * @param \SosBundle\Entity\Contrat $contrat
     *
     * @return UserCritere
     */
    public function setContrat(\SosBundle\Entity\Contrat $contrat = null)
    {
        $this->contrat = $contrat;

        return $this;
    }

    /**
     * Get contrat
     *
     * @return \SosBundle\Entity\Contrat
     */
    public function getContrat()
    {
        return $this->contrat;
    }

    /**
     * Set typeContrat
     *
     * @param \SosBundle\Entity\TypeContrat $typeContrat
     *
     * @return UserCritere
     */
    public function setTypeContrat(\SosBundle\Entity\TypeContrat $typeContrat = null)
    {
        $this->typeContrat = $typeContrat;

        return $this;
    }

    /**
     * Get typeContrat
     *
     * @return \SosBundle\Entity\TypeContrat
     */
    public function getTypeContrat()
    {
        return $this->typeContrat;
    }

    /**
     * Set experience
     *
     * @param \SosBundle\Entity\Experience $experience
     *
     * @return UserCritere
     */
    public function setExperience(\SosBundle\Entity\Experience $experience = null)
    {
        $this->experience = $experience;

        return $this;
    }

    /**
     * Get experience
     *
     * @return \SosBundle\Entity\Experience
     */
    public function getExperience()
    {
        return $this->experience;
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

    /**
     * Set formation
     *
     * @param \SosBundle\Entity\Formation $formation
     *
     * @return UserCritere
     */
    public function setFormation(\SosBundle\Entity\Formation $formation = null)
    {
        $this->formation = $formation;

        return $this;
    }

    /**
     * Get formation
     *
     * @return \SosBundle\Entity\Formation
     */
    public function getFormation()
    {
        return $this->formation;
    }
}
