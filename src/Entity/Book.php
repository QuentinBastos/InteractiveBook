<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Contracts\Translation\TranslatorInterface;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    const string TYPE_SCIENCE_FICTION = 'Science Fiction';
    const string TYPE_FANTASY = 'Fantasy';
    const string TYPE_MYSTERY = 'Mystery';
    const string TYPE_HORROR = 'Horror';
    const string TYPE_ROMANCE = 'Romance';
    const string TYPE_THRILLER = 'Thriller';
    const string TYPE_ADVENTURE = 'Adventure';
    const string TYPE_HISTORICAL = 'Historical';
    const string TYPE_DYSTOPIA = 'Dystopia';
    const string TYPE_BIOGRAPHY = 'Biography';
    const string TYPE_AUTOBIOGRAPHY = 'Autobiography';
    const string TYPE_ESSAY = 'Essay';
    const string TYPE_DRAMA = 'Drama';
    const string TYPE_POETRY = 'Poetry';
    const string TYPE_PHILOSOPHICAL = 'Philosophical';
    const string TYPE_GOTHIC = 'Gothic';
    const string TYPE_SHORT_STORY = 'Short Story';
    const string TYPE_EPISTOLARY = 'Epistolary';
    const string TYPE_SURREALISM = 'Surrealism';
    const string TYPE_UTOPIA = 'Utopia';
    const string TYPE_FANTASTIC = 'Fantastic';
    const string TYPE_MEMOIR = 'Memoir';
    const string TYPE_COMING_OF_AGE = 'Coming of Age';
    const string TYPE_POST_APOCALYPTIC = 'Post Apocalyptic';
    const string TYPE_SATIRE = 'Satire';

    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
        $this->pages = new ArrayCollection();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'integer', length: 255, nullable: true)]
    private ?int $type = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'books')]
    #[ORM\JoinColumn]
    private ?User $user;

    #[ORM\OneToMany(targetEntity: Page::class, mappedBy: 'book')]
    private Collection $pages;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): void
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

    public function getTranslatedTypes(): array
    {
        $types = self::getTypes();
        $translatedTypes = [];

        foreach ($types as $type) {
            $translatedTypes[$type] = $this->translator->trans('book.type.' . strtolower(str_replace(' ', '_', $type)));
        }

        return $translatedTypes;
    }
}
