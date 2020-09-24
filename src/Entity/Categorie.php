<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 */
class Categorie implements \JsonSerializable
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
    private $nomCategorie;

    /**
     * @ORM\ManyToMany(targetEntity=Games::class, mappedBy="id_Categorie")
     */
    private $games;

    public function __construct()
    {
        $this->games = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCategorie(): ?string
    {
        return $this->nomCategorie;
    }

    public function setNomCategorie(string $nomCategorie): self
    {
        $this->nomCategorie = $nomCategorie;

        return $this;
    }

    /**
     * @return Collection|Games[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Games $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->addIdCategorie($this);
        }

        return $this;
    }

    public function removeGame(Games $game): self
    {
        if ($this->games->contains($game)) {
            $this->games->removeElement($game);
            $game->removeIdCategorie($this);
        }

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            "IdCategorie" => $this->getId(),
            "NomCategorie" => $this->getNomCategorie()

        ];
    }
}
