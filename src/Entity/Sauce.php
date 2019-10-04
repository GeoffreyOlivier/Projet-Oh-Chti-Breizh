<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SauceRepository")
 */
class Sauce
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="sauce")
     */
    private $Product_sauce;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    public function __construct()
    {
        $this->Product_sauce = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProductSauce(): Collection
    {
        return $this->Product_sauce;
    }

    public function addProductSauce(Product $productSauce): self
    {
        if (!$this->Product_sauce->contains($productSauce)) {
            $this->Product_sauce[] = $productSauce;
            $productSauce->setSauce($this);
        }

        return $this;
    }

    public function removeProductSauce(Product $productSauce): self
    {
        if ($this->Product_sauce->contains($productSauce)) {
            $this->Product_sauce->removeElement($productSauce);
            // set the owning side to null (unless already changed)
            if ($productSauce->getSauce() === $this) {
                $productSauce->setSauce(null);
            }
        }

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }
}
