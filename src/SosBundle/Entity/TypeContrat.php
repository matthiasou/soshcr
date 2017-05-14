<?php

namespace SosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeContrat
 *
 * @ORM\Table(name="type_contrat")
 * @ORM\Entity(repositoryClass="SosBundle\Repository\TypeContratRepository")
 */
class TypeContrat
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
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;


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
     * @return TypeContrat
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
     * Set contrat
     *
     * @param string $contrat
     *
     * @return TypeContrat
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
     * Constructor
     */
    public function __construct()
    {
        $this->contrat = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add contrat
     *
     * @param \SosBundle\Entity\Contrat $contrat
     *
     * @return TypeContrat
     */
    public function addContrat(\SosBundle\Entity\Contrat $contrat)
    {
        $this->contrat[] = $contrat;

        return $this;
    }

    /**
     * Remove contrat
     *
     * @param \SosBundle\Entity\Contrat $contrat
     */
    public function removeContrat(\SosBundle\Entity\Contrat $contrat)
    {
        $this->contrat->removeElement($contrat);
    }
}
