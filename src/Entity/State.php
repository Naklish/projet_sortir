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
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    //RELATION STATE/OUTING
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Outing", mappedBy="states")
     */
    private $outing;

    public function __construct()
    {
        $this->outing = new ArrayCollection();
    }



    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
