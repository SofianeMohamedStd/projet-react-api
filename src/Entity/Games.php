<?php

namespace App\Entity;

use App\Repository\GamesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GamesRepository::class)
 */
class Games implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="integer")
     */
    private $annee;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=Studio::class, inversedBy="games")
     */
    private $studio_id;

    /**
     * @ORM\ManyToMany(targetEntity=Categorie::class, inversedBy="games")
     */
    private $id_Categorie;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="game_id")
     */
    private $comments;

    public function __construct()
    {
        $this->id_Categorie = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getStudioId(): ?Studio
    {
        return $this->studio_id;
    }

    public function setStudioId(?Studio $studio_id): self
    {
        $this->studio_id = $studio_id;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            "id" => $this->getId(),
            "titre" => $this->getTitre(),
            "annee" => $this->getAnnee(),
            "image" => $this->getImage(),
            "studio" => $this->getStudioId(),
            "categorie" => $this->getIdCategorie()

        ];
    }

    /**
     * @return Collection|Categorie[]
     */
    public function getIdCategorie(): Collection
    {
        return $this->id_Categorie;
    }

    public function addIdCategorie(Categorie $idCategorie): self
    {
        if (!$this->id_Categorie->contains($idCategorie)) {
            $this->id_Categorie[] = $idCategorie;
        }

        return $this;
    }

    public function removeIdCategorie(Categorie $idCategorie): self
    {
        if ($this->id_Categorie->contains($idCategorie)) {
            $this->id_Categorie->removeElement($idCategorie);
        }

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setGameId($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getGameId() === $this) {
                $comment->setGameId(null);
            }
        }

        return $this;
    }
}
