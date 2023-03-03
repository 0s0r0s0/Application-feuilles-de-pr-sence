<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TimeTableRepository")
 */
class TimeTable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Employee", inversedBy="timeTables")
     * @ORM\JoinColumn(nullable=false)
     */
    private $employee;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Absence", inversedBy="timeTables")
     */
    private $absence;


    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $am_start_at;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $am_end_at;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $pm_start_at;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $pm_end_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Day", inversedBy="timeTables")
     * @ORM\JoinColumn(nullable=false)
     */
    private $day;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    public function getAbsence(): ?Absence
    {
        return $this->absence;
    }

    public function setAbsence(?Absence $absence): self
    {
        $this->absence = $absence;

        return $this;
    }
    

    public function getAmStartAt(): ?\DateTimeInterface
    {
        return $this->am_start_at;
    }

    public function setAmStartAt(?\DateTimeInterface $am_start_at): self
    {
        $this->am_start_at = $am_start_at;

        return $this;
    }

    public function getAmEndAt(): ?\DateTimeInterface
    {
        return $this->am_end_at;
    }

    public function setAmEndAt(?\DateTimeInterface $am_end_at): self
    {
        $this->am_end_at = $am_end_at;

        return $this;
    }

    public function getPmStartAt(): ?\DateTimeInterface
    {
        return $this->pm_start_at;
    }

    public function setPmStartAt(?\DateTimeInterface $pm_start_at): self
    {
        $this->pm_start_at = $pm_start_at;

        return $this;
    }

    public function getPmEndAt(): ?\DateTimeInterface
    {
        return $this->pm_end_at;
    }

    public function setPmEndAt(?\DateTimeInterface $pm_end_at): self
    {
        $this->pm_end_at = $pm_end_at;

        return $this;
    }

    public function getDay(): ?Day
    {
        return $this->day;
    }

    public function setDay(?Day $day): self
    {
        $this->day = $day;

        return $this;
    }
}
