<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Usager $Usager = null;

    #[ORM\OneToMany(mappedBy: 'Commande', targetEntity: LigneCommande::class)]
    private Collection $ligneCommandes;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsager(): ?Usager
    {
        return $this->Usager;
    }

    public function setUsager(?Usager $Usager): self
    {
        $this->Usager = $Usager;

        return $this;
    }

    public function addLigneCommande(LigneCommande $ligneCommande): self
    {
        if (!$this->ligneCommandes->contains($ligneCommande)) {
            $this->ligneCommandes->add($ligneCommande);
            $ligneCommande->setCommande($this);
        }

        return $this;
    }
}
