<?php

namespace App\Entity;

use App\Repository\OutingRepository;
use App\Repository\StateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\DateType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=OutingRepository::class)
 */
class Outing
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"outing"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"outing"})
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
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\State", inversedBy="outing")
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

    //RELATION OUTING/LOCATION
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Location", inversedBy="outing")
     */
    private $locations;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cancel_motive;


    public function __construct()
    {
        $this->registered_user = new ArrayCollection();
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

    public function getDeadlineRegistration(): ?\DateTimeInterface
    {
        return $this->deadlineRegistration;
    }

    public function setDeadlineRegistration(\DateTime $deadlineRegistration): self
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

    public function getState()
    {
        return $this->state;
    }

    public function setState(State $state): self
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
    public function getCancelMotive()
    {
        return $this->cancel_motive;
    }

    /**
     * @param mixed $cancel_motive
     * @return Outing
     */
    public function setCancelMotive($cancel_motive)
    {
        $this->cancel_motive = $cancel_motive;
        return $this;
    }



    public function addRegisteredUser(User $user)
    {
        $this->registered_user->add($user);
        $user->getOutings()->add($this);
    }

    public function unregisterUser(User $user)
    {
        $this->registered_user->removeElement($user);
        $user->getOutings()->removeElement($this);
    }

    public function unregisterAllUsers()
    {
        foreach ($this->registered_user as $user)
        {
            $user->getOutings()->removeElement($this);
            $this->registered_user->removeElement($user);
        }
    }

    public function checkState(StateRepository $stateRepo)
    {
        $today = new \DateTime();
        if($this->getState()->getId() == 2)
        {
            if($today > $this->getDeadlineRegistration()){
                $state = $stateRepo->find(3);
                $this->setState($state);
            }
            if($today > $this->getDateHourStart()){
                $state = $stateRepo->find(4);
                $this->setState($state);
            }
            if(count($this->getRegisteredUser()) == $this->getMaxRegistration()){
                $state = $stateRepo->find(3);
                $this->setState($state);
            };
        }
        if ($this->getState()->getId() == 4)
        {
            try {
                if ($today > $this->getDateHourStart()->add(new \DateInterval('PT' . $this->getDuration() . 'M'))) {
                    $state = $stateRepo->find(5);
                    $this->setState($state);
                }
            } catch (\Exception $e) {
            }
        }
    }
}
