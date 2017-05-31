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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="criteres", onDelete="CASCADE")
     */
    private $user;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Etablissement")
     */
    private $etablissement;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Contrat")
     */
    private $contrat;

    /**
     *
     * @ORM\ManyToMany(targetEntity="TypeContrat")
     */
    private $duree;

    /**
     *
     * @ORM\ManyToMany(targetEntity="CursusScolaire")
     */
    private $cursus;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Formation")
     */
    private $formation;

    /**
     *
     * @ORM\ManyToOne(targetEntity="PosteRecherche")
     */
    private $poste;

    /**
     *
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
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", length=11, nullable=false)
     */
    private $latitude;

    /**
     * @var float
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
     * @var int
     *
     * @ORM\Column(name="rayon_emploi", type="integer", length=11)
     */
    private $rayonEmploi;

    /**
     * @ORM\Column(name="disponibilites", type="text")
     */
    private $disponibilites;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->etablissement = new \Doctrine\Common\Collections\ArrayCollection();
        $this->duree = new \Doctrine\Common\Collections\ArrayCollection();
        $this->cursus = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formation = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set disponibilites
     *
     * @param string $disponibilites
     *
     * @return UserCritere
     */
    public function setDisponibilites($disponibilites)
    {
        $this->disponibilites = $disponibilites;

        return $this;
    }

    /**
     * Get disponibilites
     *
     * @return string
     */
    public function getDisponibilites()
    {
        return $this->disponibilites;
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
     * Add etablissement
     *
     * @param \SosBundle\Entity\Etablissement $etablissement
     *
     * @return UserCritere
     */
    public function addEtablissement(\SosBundle\Entity\Etablissement $etablissement)
    {
        $this->etablissement[] = $etablissement;

        return $this;
    }

    /**
     * Remove etablissement
     *
     * @param \SosBundle\Entity\Etablissement $etablissement
     */
    public function removeEtablissement(\SosBundle\Entity\Etablissement $etablissement)
    {
        $this->etablissement->removeElement($etablissement);
    }

    /**
     * Get etablissement
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEtablissement()
    {
        return $this->etablissement;
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
     * Add duree
     *
     * @param \SosBundle\Entity\TypeContrat $duree
     *
     * @return UserCritere
     */
    public function addDuree(\SosBundle\Entity\TypeContrat $duree)
    {
        $this->duree[] = $duree;

        return $this;
    }

    /**
     * Remove duree
     *
     * @param \SosBundle\Entity\TypeContrat $duree
     */
    public function removeDuree(\SosBundle\Entity\TypeContrat $duree)
    {
        $this->duree->removeElement($duree);
    }

    /**
     * Get duree
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Add cursus
     *
     * @param \SosBundle\Entity\CursusScolaire $cursus
     *
     * @return UserCritere
     */
    public function addCursus(\SosBundle\Entity\CursusScolaire $cursus)
    {
        $this->cursus[] = $cursus;

        return $this;
    }

    /**
     * Remove cursus
     *
     * @param \SosBundle\Entity\CursusScolaire $cursus
     */
    public function removeCursus(\SosBundle\Entity\CursusScolaire $cursus)
    {
        $this->cursus->removeElement($cursus);
    }

    /**
     * Get cursus
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCursus()
    {
        return $this->cursus;
    }

    /**
     * Add formation
     *
     * @param \SosBundle\Entity\Formation $formation
     *
     * @return UserCritere
     */
    public function addFormation(\SosBundle\Entity\Formation $formation)
    {
        $this->formation[] = $formation;

        return $this;
    }

    /**
     * Remove formation
     *
     * @param \SosBundle\Entity\Formation $formation
     */
    public function removeFormation(\SosBundle\Entity\Formation $formation)
    {
        $this->formation->removeElement($formation);
    }

    /**
     * Get formation
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormation()
    {
        return $this->formation;
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
}
