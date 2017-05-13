<?php

namespace SosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContratCritere
 *
 * @ORM\Table(name="contrat_critere")
 * @ORM\Entity(repositoryClass="SosBundle\Repository\ContratCritereRepository")
 */
class ContratCritere
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
     * @ORM\ManyToOne(targetEntity="Contrat")
     */
    private $contrat;

    /**
     *
     * @ORM\ManyToOne(targetEntity="TypeContrat")
     */
    private $duree;


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
     * Set contrat
     *
     * @param \SosBundle\Entity\Contrat $contrat
     *
     * @return ContratCritere
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
     * Set duree
     *
     * @param \SosBundle\Entity\TypeContrat $duree
     *
     * @return ContratCritere
     */
    public function setDuree(\SosBundle\Entity\TypeContrat $duree = null)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return \SosBundle\Entity\TypeContrat
     */
    public function getDuree()
    {
        return $this->duree;
    }
}
