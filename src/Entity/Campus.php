<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CampusRepository::class)
 */
class Campus
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


    //RELATION CAMPUS/USER
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="campus")
     */
    private $users;

    //RELATION CAMPUS/OUTING
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Outing", mappedBy="campus")
     */
    private $outings;

    public function __construct()
    {
        $this->users =new ArrayCollection();
        $this->outings = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param mixed $users
     */
    public function setUser($users): void
    {
        $this->users = $users;
    }

    /**
     * @return ArrayCollection
     */
    public function getOutings(): ArrayCollection
    {
        return $this->outings;
    }

    /**
     * @param ArrayCollection $outings
     */
    public function setOutings(ArrayCollection $outings): void
    {
        $this->outings = $outings;
    }

}
