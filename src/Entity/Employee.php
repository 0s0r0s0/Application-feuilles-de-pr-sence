<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmployeeRepository")
 */
class Employee
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Status", inversedBy="employees")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=55)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=55)
     */
    private $lastName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TimeTable", mappedBy="employee")
     */
    private $timeTables;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Timesheet", mappedBy="employee")
     */
    private $timesheets;

    /**
     * @ORM\OneToMany(targetEntity=Calendrier::class, mappedBy="employee")
     */
    private $calendriers;

    /**
     * @ORM\OneToMany(targetEntity=Notice::class, mappedBy="employee")
     */
    private $notices;

    /**
     * @ORM\ManyToOne(targetEntity=Job::class, inversedBy="employees")
     */
    private $job;

    public function __construct()
    {
        $this->timeTables = new ArrayCollection();
        $this->timesheets = new ArrayCollection();
        $this->calendriers = new ArrayCollection();
        $this->notices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

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
            $timeTable->setEmployee($this);
        }

        return $this;
    }

    public function removeTimeTable(TimeTable $timeTable): self
    {
        if ($this->timeTables->contains($timeTable)) {
            $this->timeTables->removeElement($timeTable);
            // set the owning side to null (unless already changed)
            if ($timeTable->getEmployee() === $this) {
                $timeTable->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Timesheet[]
     */
    public function getTimesheets(): Collection
    {
        return $this->timesheets;
    }

    public function addTimesheet(Timesheet $timesheet): self
    {
        if (!$this->timesheets->contains($timesheet)) {
            $this->timesheets[] = $timesheet;
            $timesheet->setEmployee($this);
        }

        return $this;
    }

    public function removeTimesheet(Timesheet $timesheet): self
    {
        if ($this->timesheets->contains($timesheet)) {
            $this->timesheets->removeElement($timesheet);
            // set the owning side to null (unless already changed)
            if ($timesheet->getEmployee() === $this) {
                $timesheet->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Calendrier[]
     */
    public function getCalendriers(): Collection
    {
        return $this->calendriers;
    }

    public function addCalendrier(Calendrier $calendrier): self
    {
        if (!$this->calendriers->contains($calendrier)) {
            $this->calendriers[] = $calendrier;
            $calendrier->setEmployee($this);
        }

        return $this;
    }

    public function removeCalendrier(Calendrier $calendrier): self
    {
        if ($this->calendriers->contains($calendrier)) {
            $this->calendriers->removeElement($calendrier);
            // set the owning side to null (unless already changed)
            if ($calendrier->getEmployee() === $this) {
                $calendrier->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Notice[]
     */
    public function getNotices(): Collection
    {
        return $this->notices;
    }

    public function addNotice(Notice $notice): self
    {
        if (!$this->notices->contains($notice)) {
            $this->notices[] = $notice;
            $notice->setEmployee($this);
        }

        return $this;
    }

    public function removeNotice(Notice $notice): self
    {
        if ($this->notices->contains($notice)) {
            $this->notices->removeElement($notice);
            // set the owning side to null (unless already changed)
            if ($notice->getEmployee() === $this) {
                $notice->setEmployee(null);
            }
        }

        return $this;
    }

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function setJob(?Job $job): self
    {
        $this->job = $job;

        return $this;
    }
}
