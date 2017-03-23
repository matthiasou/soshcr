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

