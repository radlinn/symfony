<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\Column(length: 2000)]
    private string $content;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTime $dateCreated;

    #[ORM\Column(length: 255)]
    private string $author;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'articles')]
    private Collection $Article;

    public function __construct()
    {
        $this->dateCreated = new \DateTime();
        $this->Article = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;
        return $this;
    }

    public function getDateCreated(): \DateTime
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTime $dateCreated): static
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getArticle(): Collection
    {
        return $this->Article;
    }

    public function addArticle(User $article): static
    {
        if (!$this->Article->contains($article)) {
            $this->Article->add($article);
        }

        return $this;
    }

    public function removeArticle(User $article): static
    {
        $this->Article->removeElement($article);

        return $this;
    }
}