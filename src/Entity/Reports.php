<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReportsRepository")
 */
class Reports
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $reporterUserId;

    /**
     * @ORM\Column(type="integer")
     */
    private $reportedUserId;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Type(type="string")
     */
    private $report;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $solvedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReporterUserId(): ?int
    {
        return $this->reporterUserId;
    }

    public function setReporterUserId(int $reporterUserId): self
    {
        $this->reporterUserId = $reporterUserId;

        return $this;
    }

    public function getReportedUserId(): ?int
    {
        return $this->reportedUserId;
    }

    public function setReportedUserId(int $reportedUserId): self
    {
        $this->reportedUserId = $reportedUserId;

        return $this;
    }

    public function getReport(): ?string
    {
        return $this->report;
    }

    public function setReport(string $report): self
    {
        $this->report = $report;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getSolvedAt(): ?\DateTimeInterface
    {
        return $this->solvedAt;
    }

    public function setSolvedAt(?\DateTimeInterface $solvedAt): self
    {
        $this->solvedAt = $solvedAt;

        return $this;
    }
}
