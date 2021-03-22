<?php

namespace App\Entity;

use App\Repository\StateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StateRepository::class)
 */
class State
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $stateName;

    public function getId(): ?int
    {
        return $this->id;
    }

    //RELATION STATE/OUTING
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Outing", mappedBy="state")
     *
     */
    private $outing;

    public function __construct()
    {
        $this->outing = new ArrayCollection();
    }



    public function getStateName(): ?string
    {
        return $this->stateName;
    }

    public function setStateName(string $stateName): self
    {
        $this->stateName = $stateName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOuting()
    {
        return $this->outing;
    }

    /**
     * @param mixed $outing
     */
    public function setOuting($outing): void
    {
        $this->outing = $outing;
    }

}
