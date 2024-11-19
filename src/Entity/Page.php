<?php

namespace App\Entity;

use App\Entity\Book\Book;
use App\Repository\PageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PageRepository::class)]
class Page
{
    const int NO_PAGE = 0;
    const int FIRST_PAGE = 1;
    const string STRUCT_TOP = 'top';
    const string STRUCT_BOTTOM = 'bottom';
    const string STRUCT_DOUBLE_PAGE_LEFT = 'double_page_left';
    const string STRUCT_DOUBLE_PAGE_RIGHT = 'double_page_right';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $number = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?self $parent = null;

    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent')]
    private Collection $children;

    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'pages')]
    #[ORM\JoinColumn]
    private ?Book $book = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $title = null;
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $content = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $lastPage = false;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $filePath = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $struct = null;

    #[ORM\OneToMany(targetEntity: Target::class, mappedBy: 'fromPage', cascade: ['persist', 'remove'])]
    private Collection $fromTargets;

    #[ORM\OneToMany(targetEntity: Target::class, mappedBy: 'toPage', cascade: ['persist', 'remove'])]
    private Collection $toTargets;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->fromTargets = new ArrayCollection();
        $this->toTargets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): void
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }
    }

    public function removeChild(self $child): void
    {
        if ($this->children->removeElement($child)) {
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }
    }

    public function addFromTarget(Target $target): void
    {
        if (!$this->fromTargets->contains($target)) {
            $this->fromTargets->add($target);
            $target->setFromPage($this);
        }
    }

    public function removeFromTarget(Target $target): void
    {
        if ($this->fromTargets->removeElement($target)) {
            if ($target->getFromPage() === $this) {
                $target->setFromPage(null);
            }
        }
    }

    public function addToTarget(Target $target): void
    {
        if (!$this->toTargets->contains($target)) {
            $this->toTargets->add($target);
            $target->setToPage($this);
        }
    }

    public function removeToTarget(Target $target): void
    {
        if ($this->toTargets->removeElement($target)) {
            if ($target->getToPage() === $this) {
                $target->setToPage(null);
            }
        }
    }

    public function getFromTargets(): Collection
    {
        return $this->fromTargets;
    }

    public function setFromTargets(Collection $fromTargets): void
    {
        $this->fromTargets = $fromTargets;
    }

    public function getToTargets(): Collection
    {
        return $this->toTargets;
    }

    public function setToTargets(Collection $toTargets): void
    {
        $this->toTargets = $toTargets;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    public function getLastPage(): bool
    {
        return $this->lastPage;
    }

    public function setLastPage(bool $lastPage): void
    {
        $this->lastPage = $lastPage;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): void
    {
        $this->filePath = $filePath;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): void
    {
        $this->book = $book;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): void
    {
        $this->number = $number;
    }

    public function getStruct(): ?string
    {
        return $this->struct;
    }

    public function setStruct(?string $struct): void
    {
        $this->struct = $struct;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public static function getStructChoices(): array
    {
        return [
            self::STRUCT_TOP => 'page.struct.top',
            self::STRUCT_BOTTOM => 'page.struct.bottom',
            self::STRUCT_DOUBLE_PAGE_LEFT => 'page.struct.double_page_left',
            self::STRUCT_DOUBLE_PAGE_RIGHT => 'page.struct.double_page_right',
        ];
    }
}
