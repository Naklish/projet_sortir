<?php
namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

class OutingSearch {


    /**
     * @var string|null
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

    /**
     * @return string
     */
    public function getCampus(): ?string
    {
        return $this->campus;
    }

    /**
     * @param string $campus
     * @return OutingSearch
     */
    public function setCampus(string $campus): OutingSearch
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


}