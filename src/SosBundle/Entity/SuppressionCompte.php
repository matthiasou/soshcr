<?php

namespace SosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SuppressionCompte
 *
 * @ORM\Table(name="suppression_compte")
 * @ORM\Entity(repositoryClass="SosBundle\Repository\SuppressionCompteRepository")
 */
class SuppressionCompte
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
     * @ORM\Column(name="contenu", type="string", length=255, nullable=true)
     */
    private $contenu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="RaisonSuppression")
     */
    private $raisonSuppression;

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
     * Set contenu
     *
     * @param string $contenu
     *
     * @return SuppressionCompte
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return SuppressionCompte
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set raisonSuppression
     *
     * @param \SosBundle\Entity\RaisonSuppression $raisonSuppression
     *
     * @return SuppressionCompte
     */
    public function setRaisonSuppression(\SosBundle\Entity\RaisonSuppression $raisonSuppression = null)
    {
        $this->raisonSuppression = $raisonSuppression;

        return $this;
    }

    /**
     * Get raisonSuppression
     *
     * @return \SosBundle\Entity\RaisonSuppression
     */
    public function getRaisonSuppression()
    {
        return $this->raisonSuppression;
    }
}
