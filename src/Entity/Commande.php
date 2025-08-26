<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom du client est obligatoire')]
    #[Assert\Length(max: 255, maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères')]
    private ?string $nomClient = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $prixTotal = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: CommandeMateriel::class, cascade: ['persist', 'remove'])]
    private Collection $commandeMateriels;

    public function __construct()
    {
        $this->commandeMateriels = new ArrayCollection();
        $this->dateCreation = new \DateTime();
        $this->prixTotal = '0.00';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomClient(): ?string
    {
        return $this->nomClient;
    }

    public function setNomClient(string $nomClient): static
    {
        $this->nomClient = $nomClient;

        return $this;
    }

    public function getPrixTotal(): ?string
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(string $prixTotal): static
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * @return Collection<int, CommandeMateriel>
     */
    public function getCommandeMateriels(): Collection
    {
        return $this->commandeMateriels;
    }

    public function addCommandeMateriel(CommandeMateriel $commandeMateriel): static
    {
        if (!$this->commandeMateriels->contains($commandeMateriel)) {
            $this->commandeMateriels->add($commandeMateriel);
            $commandeMateriel->setCommande($this);
        }

        return $this;
    }

    public function removeCommandeMateriel(CommandeMateriel $commandeMateriel): static
    {
        if ($this->commandeMateriels->removeElement($commandeMateriel)) {
            // set the owning side to null (unless already changed)
            if ($commandeMateriel->getCommande() === $this) {
                $commandeMateriel->setCommande(null);
            }
        }

        return $this;
    }

    /**
     * Calcule automatiquement le prix total de la commande
     */
    public function calculerPrixTotal(): void
    {
        $total = 0;
        foreach ($this->commandeMateriels as $commandeMateriel) {
            $total += (float)$commandeMateriel->getPrixUnitaire() * $commandeMateriel->getQuantite();
        }
        $this->prixTotal = number_format($total, 2, '.', '');
    }

    public function __toString(): string
    {
        return sprintf('Commande #%d - %s', $this->id ?? 0, $this->nomClient ?? '');
    }
}
