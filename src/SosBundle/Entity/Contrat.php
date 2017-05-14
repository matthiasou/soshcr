<?php

namespace SosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contrat
 *
 * @ORM\Table(name="contrat")
 * @ORM\Entity(repositoryClass="SosBundle\Repository\ContratRepository")
 */
class Contrat
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
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=45, nullable=true)
     */
    private $libelle;

    /**
     *
     * @ORM\ManyToMany(targetEntity="TypeContrat")
     */
    private $duree;

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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Contrat
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->duree = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add duree
     *
     * @param \SosBundle\Entity\TypeContrat $duree
     *
     * @return Contrat
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
}
