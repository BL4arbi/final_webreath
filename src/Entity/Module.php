<?php

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ModuleRepository::class)]
class Module
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $detail = null;

    #[ORM\OneToMany(mappedBy: 'module', targetEntity: ModuleData::class, orphanRemoval: true)]
    private Collection $moduleData;

    #[ORM\Column]
    private ?bool $statut = false;

    public function __construct()
    {
        $this->moduleData = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(?string $detail): static
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * @return Collection<int, ModuleData>
     */
    public function getModuleData(): Collection
    {
        return $this->moduleData;
    }

    public function addModuleData(ModuleData $moduleData): static
    {
        if (!$this->moduleData->contains($moduleData)) {
            $this->moduleData->add($moduleData);
            $moduleData->setModule($this);
        }

        return $this;
    }

    public function removeModuleData(ModuleData $moduleData): static
    {
        if ($this->moduleData->removeElement($moduleData)) {
            // set the owning side to null (unless already changed)
            if ($moduleData->getModule() === $this) {
                $moduleData->setModule(null);
            }
        }

        return $this;
    }

    public function isStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): static
    {
        $this->statut = $statut;

        return $this;
    }
}
