<?php

namespace App\Entity;

use App\Repository\TargetRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: TargetRepository::class)]
class Target
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: Page::class, inversedBy: 'fromTargets')]
    private ?Page $fromPage = null;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: Page::class, inversedBy: 'toTargets')]
    private ?Page $toPage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getFromPage(): ?Page
    {
        return $this->fromPage;
    }

    public function setFromPage(?Page $fromPage): void
    {
        $this->fromPage = $fromPage;
    }

    public function getToPage(): ?Page
    {
        return $this->toPage;
    }

    public function setToPage(?Page $toPage): void
    {
        $this->toPage = $toPage;
    }
}
