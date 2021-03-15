<?php

namespace App\Entity;

use App\Repository\OutingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OutingRepository::class)
 */
class Outing
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateHourStart;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\Column(type="datetime")
     */
    private $deadlineRegistration;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxRegistration;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $state;


    //RELATION OUTING/CAMPUS
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Campus", inversedBy="outings")
     */
    private $campus;
    //RELATION OUTING/USER
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="outings")
     */
    private $o_users;
    //RELATION OUTING/USER
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="registered_outing")
     */
    private $registered_user;
    //RELATION OUTING/STATE
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\State", inversedBy="outing")
     */
    private $states;
    //RELATION OUTING/LOCATION
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Location", inversedBy="outing"
     */
    private $locations;



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

    public function getDateHourStart(): ?\DateTimeInterface
    {
        return $this->dateHourStart;
    }

    public function setDateHourStart(\DateTimeInterface $dateHourStart): self
    {
        $this->dateHourStart = $dateHourStart;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDeadlineRegistration(): ?string
    {
        return $this->deadlineRegistration;
    }

    public function setDeadlineRegistration(string $deadlineRegistration): self
    {
        $this->deadlineRegistration = $deadlineRegistration;

        return $this;
    }

    public function getMaxRegistration(): ?int
    {
        return $this->maxRegistration;
    }

    public function setMaxRegistration(int $maxRegistration): self
    {
        $this->maxRegistration = $maxRegistration;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCampus()
    {
        return $this->campus;
    }

    /**
     * @param mixed $campus
     */
    public function setCampus($campus): void
    {
        $this->campus = $campus;
    }

    /**
     * @return mixed
     */
    public function getOUsers()
    {
        return $this->o_users;
    }

    /**
     * @param mixed $o_users
     */
    public function setOUsers($o_users): void
    {
        $this->o_users = $o_users;
    }

    /**
     * @return mixed
     */
    public function getRegisteredUser()
    {
        return $this->registered_user;
    }

    /**
     * @param mixed $registered_user
     */
    public function setRegisteredUser($registered_user): void
    {
        $this->registered_user = $registered_user;
    }

    /**
     * @return mixed
     */
    public function getStates()
    {
        return $this->states;
    }

    /**
     * @param mixed $states
     */
    public function setStates($states): void
    {
        $this->states = $states;
    }

    /**
     * @return mixed
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * @param mixed $locations
     */
    public function setLocations($locations): void
    {
        $this->locations = $locations;
    }

    /**
     * @return mixed
     */


}
