<?php
namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

class OutingSearch {


    /**
     * @var Campus|null
     */
    private $campus;

    /**
     * @var string|null
     */
    private $searchBar;

    /**
     * @var \DateTime|null
     */
    private $minDate;

    /**
     * @var \DateTime|null
     */
    private $maxDate;

    private $checkOrg;

    private $checkRegistered;

    private $checkNotRegistered;

    private $checkFinished;

    /**
     * @return Campus|null
     */
    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    /**
     * @param Campus|null $campus
     * @return OutingSearch
     */
    public function setCampus(?Campus $campus): OutingSearch
    {
        $this->campus = $campus;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSearchBar(): ?string
    {
        return $this->searchBar;
    }

    /**
     * @param string|null $searchBar
     * @return OutingSearch
     */
    public function setSearchBar(?string $searchBar): OutingSearch
    {
        $this->searchBar = $searchBar;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getMinDate(): ?\DateTime
    {
        return $this->minDate;
    }

    /**
     * @param \DateTime|null $minDate
     * @return OutingSearch
     */
    public function setMinDate(?\DateTime $minDate): OutingSearch
    {
        $this->minDate = $minDate;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getMaxDate(): ?\DateTime
    {
        return $this->maxDate;
    }

    /**
     * @param \DateTime|null $maxDate
     * @return OutingSearch
     */
    public function setMaxDate(?\DateTime $maxDate): OutingSearch
    {
        $this->maxDate = $maxDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCheckOrg()
    {
        return $this->checkOrg;
    }

    /**
     * @param mixed $checkOrg
     * @return OutingSearch
     */
    public function setCheckOrg($checkOrg)
    {
        $this->checkOrg = $checkOrg;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCheckRegistered()
    {
        return $this->checkRegistered;
    }

    /**
     * @param mixed $checkRegistered
     * @return OutingSearch
     */
    public function setCheckRegistered($checkRegistered)
    {
        $this->checkRegistered = $checkRegistered;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCheckNotRegistered()
    {
        return $this->checkNotRegistered;
    }

    /**
     * @param mixed $checkNotRegistered
     * @return OutingSearch
     */
    public function setCheckNotRegistered($checkNotRegistered)
    {
        $this->checkNotRegistered = $checkNotRegistered;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCheckFinished()
    {
        return $this->checkFinished;
    }

    /**
     * @param mixed $checkFinished
     * @return OutingSearch
     */
    public function setCheckFinished($checkFinished)
    {
        $this->checkFinished = $checkFinished;
        return $this;
    }



}