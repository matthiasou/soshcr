<?php

namespace SosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PosteCritere
 *
 * @ORM\Table(name="poste_critere")
 * @ORM\Entity(repositoryClass="SosBundle\Repository\PosteCritereRepository")
 */
class PosteCritere
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
     * @ORM\ManyToOne(targetEntity="Experience")
     */
    private $experience;


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
     * Set poste
     *
     * @param \SosBundle\Entity\PosteRecherche $poste
     *
     * @return PosteCritere
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
     * @return PosteCritere
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
}
