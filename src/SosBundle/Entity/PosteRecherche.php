<?php

namespace SosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PosteRecherche
 *
 * @ORM\Table(name="poste_recherche")
 * @ORM\Entity(repositoryClass="SosBundle\Repository\PosteRechercheRepository")
 */
class PosteRecherche
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
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Secteur")
     */
    private $secteur;


    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Service")
     */
    private $service;


    /**
     * @var int
     *
     * @ORM\Column(name="coefficient", type="integer")
     */
    private $coefficient;


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
     * @return PosteRecherche
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
     * Set secteur
     *
     * @param string $secteur
     *
     * @return PosteRecherche
     */
    public function setSecteur($secteur)
    {
        $this->secteur = $secteur;

        return $this;
    }

    /**
     * Get secteur
     *
     * @return string
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
     * @return PosteRecherche
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
     * Set coefficient
     *
     * @param integer $coefficient
     *
     * @return PosteRecherche
     */
    public function setCoefficient($coefficient)
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    /**
     * Get coefficient
     *
     * @return integer
     */
    public function getCoefficient()
    {
        return $this->coefficient;
    }
}
