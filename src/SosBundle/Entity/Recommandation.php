<?php

namespace SosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Recommandation
 *
 * @ORM\Table(name="recommandation")
 * @ORM\Entity(repositoryClass="SosBundle\Repository\RecommandationRepository")
 */
class Recommandation
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
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $user;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Civilite")
     */
    private $civilite;



    /**
     * @ORM\Column(name="nom_etablissement", type="string", length=255)
     */
    private $nomEtablissement;

    /**
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(name="ville", type="string", length=255)
     */
    private $ville;

    /**
     * @ORM\Column(name="nom_responsable", type="string", length=255)
     */
    private $nomResponsable;

    /**
     * @ORM\Column(name="valide", type="integer")
     */
    private $valide;

    /**
     * @ORM\Column(name="code", type="string", length=5)
     */
    private $code;



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
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Recommandation
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Set nomEtablissement
     *
     * @param string $nomEtablissement
     *
     * @return Recommandation
     */
    public function setNomEtablissement($nomEtablissement)
    {
        $this->nomEtablissement = $nomEtablissement;

        return $this;
    }

    /**
     * Get nomEtablissement
     *
     * @return string
     */
    public function getNomEtablissement()
    {
        return $this->nomEtablissement;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Recommandation
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return Recommandation
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set nomResponsable
     *
     * @param string $nomResponsable
     *
     * @return Recommandation
     */
    public function setNomResponsable($nomResponsable)
    {
        $this->nomResponsable = $nomResponsable;

        return $this;
    }

    /**
     * Get nomResponsable
     *
     * @return string
     */
    public function getNomResponsable()
    {
        return $this->nomResponsable;
    }

    /**
     * Set valide
     *
     * @param integer $valide
     *
     * @return Recommandation
     */
    public function setValide($valide)
    {
        $this->valide = $valide;

        return $this;
    }

    /**
     * Get valide
     *
     * @return integer
     */
    public function getValide()
    {
        return $this->valide;
    }

    /**
     * Set user
     *
     * @param \SosBundle\Entity\User $user
     *
     * @return Recommandation
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
     * Set civilite
     *
     * @param \SosBundle\Entity\Civilite $civilite
     *
     * @return Recommandation
     */
    public function setCivilite(\SosBundle\Entity\Civilite $civilite = null)
    {
        $this->civilite = $civilite;

        return $this;
    }

    /**
     * Get civilite
     *
     * @return \SosBundle\Entity\Civilite
     */
    public function getCivilite()
    {
        return $this->civilite;
    }
}
