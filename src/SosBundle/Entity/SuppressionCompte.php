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
     * @ORM\Column(name="contenu", type="string", length=255)
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
     *    @ORM\OneToOne(targetEntity="User", cascade={"persist"})
     */

    private $utilisateur;

    /**
     * @var string
     *
     *    @ORM\OneToOne(targetEntity="RaisonSuppression", cascade={"persist"})
     */
    private $raisonSuppression;


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
     * Set utilisateur
     *
     * @param string $utilisateur
     *
     * @return SuppressionCompte
     */
    public function setUtilisateur($utilisateur)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return string
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * Set raisonSuppression
     *
     * @param string $raisonSuppression
     *
     * @return SuppressionCompte
     */
    public function setRaisonSuppression($raisonSuppression)
    {
        $this->raisonSuppression = $raisonSuppression;

        return $this;
    }

    /**
     * Get raisonSuppression
     *
     * @return string
     */
    public function getRaisonSuppression()
    {
        return $this->raisonSuppression;
    }
}

