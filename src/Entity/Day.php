<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DayRepository")
 */
class Day
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TimeTable", mappedBy="day")
     */
    private $timeTables;

    public function __construct()
    {
        $this->timeTables = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection|TimeTable[]
     */
    public function getTimeTables(): Collection
    {
        return $this->timeTables;
    }

    public function addTimeTable(TimeTable $timeTable): self
    {
        if (!$this->timeTables->contains($timeTable)) {
            $this->timeTables[] = $timeTable;
            $timeTable->setDay($this);
        }

        return $this;
    }

    public function removeTimeTable(TimeTable $timeTable): self
    {
        if ($this->timeTables->contains($timeTable)) {
            $this->timeTables->removeElement($timeTable);
            // set the owning side to null (unless already changed)
            if ($timeTable->getDay() === $this) {
                $timeTable->setDay(null);
            }
        }

        return $this;
    }
}
