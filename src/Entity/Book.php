<?php

namespace App\Entity;

use App\Repository\BookRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    const string TYPE_SCIENCE_FICTION = 'science_fiction';
    const string TYPE_FANTASY = 'fantasy';
    const string TYPE_MYSTERY = 'mystery';
    const string TYPE_HORROR = 'horror';
    const string TYPE_ROMANCE = 'romance';
    const string TYPE_THRILLER = 'thriller';
    const string TYPE_ADVENTURE = 'adventure';
    const string TYPE_HISTORICAL = 'historical';
    const string TYPE_DYSTOPIA = 'dystopia';
    const string TYPE_BIOGRAPHY = 'biography';
    const string TYPE_AUTOBIOGRAPHY = 'autobiography';
    const string TYPE_ESSAY = 'essay';
    const string TYPE_DRAMA = 'drama';
    const string TYPE_POETRY = 'poetry';
    const string TYPE_PHILOSOPHICAL = 'philosophical';
    const string TYPE_GOTHIC = 'gothic';
    const string TYPE_SHORT_STORY = 'short_story';
    const string TYPE_EPISTOLARY = 'epistolary';
    const string TYPE_SURREALISM = 'surrealism';
    const string TYPE_UTOPIA = 'utopia';
    const string TYPE_FANTASTIC = 'fantastic';
    const string TYPE_MEMOIR = 'memoir';
    const string TYPE_COMING_OF_AGE = 'coming_of_age';
    const string TYPE_POST_APOCALYPTIC = 'post_apocalyptic';
    const string TYPE_SATIRE = 'satire';

    public function __construct()
    {
        $this->pages = new ArrayCollection();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'books')]
    #[ORM\JoinColumn]
    private ?User $user;

    #[ORM\OneToMany(targetEntity: Page::class, mappedBy: 'book')]
    private Collection $pages;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $filePath = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $createdAt = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function addPage(Page $page): static
    {
        if (!$this->pages->contains($page)) {
            $this->pages->add($page);
            $page->setBook($this);
        }

        return $this;
    }

    public function removePage(Page $page): static
    {
        if ($this->pages->removeElement($page)) {
            // set the owning side to null (unless already changed)
            if ($page->getBook() === $this) {
                $page->setBook(null);
            }
        }

        return $this;
    }

    public function getPages(): Collection
    {
        return $this->pages;
    }

    public function setPages(Collection $pages): void
    {
        $this->pages = $pages;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): void
    {
        $this->filePath = $filePath;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public static function getTypes(): array
    {
        $reflection = new \ReflectionClass(__CLASS__);
        $constants = $reflection->getConstants();
        $types = [];

        foreach ($constants as $key => $value) {
            if (str_starts_with($key, 'TYPE_')) {
                $types[$value] = $value;
            }
        }

        return $types;
    }
}
