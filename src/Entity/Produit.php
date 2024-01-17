<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use App\Entity\Trait\SlugTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\CreatedAtTrait;
use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[Vich\Uploadable]
#[UniqueEntity(fields: 'nom', message: 'Ce produit existe déjà.')]
class Produit
{
    use CreatedAtTrait;
    use SlugTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $prix = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\Column(nullable: true)]
    #[Vich\UploadableField(mapping: 'product_images', fileNameProperty: 'imagePath')]
    private ?string $imagePath = null;

    #[ORM\Column(nullable: true)]
    #[Vich\UploadableField(mapping: 'product_images', fileNameProperty: 'imageFile')]
    #[Assert\Image(mimeTypes: ['image/jpeg', 'image/png'], maxSize: '5M', maxSizeMessage: 'L\'image ne doit pas dépasser 5 Mo.')]
    private ?File $imageFile = null;
    

    // #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    // private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    #[Vich\UploadableField(mapping: 'product_images', fileNameProperty: 'imageFile')]
    private ?Categorie $categorie = null;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Image::class, orphanRemoval: true)]
    private Collection $images;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: DetailsCommande::class)]
    private Collection $detailsCommandes;

    #[ORM\ManyToMany(targetEntity: Promotion::class, mappedBy: 'Produit')]
    private Collection $promotions;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->detailsCommandes = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->promotions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    // public function getCreatedAt(): ?\DateTimeImmutable
    // {
    //     return $this->createdAt;
    // }

    // public function setCreatedAt(\DateTimeImmutable $createdAt): static
    // {
    //     $this->createdAt = $createdAt;

    //     return $this;
    // }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setProduit($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProduit() === $this) {
                $image->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DetailsCommande>
     */
    public function getDetailsCommandes(): Collection
    {
        return $this->detailsCommandes;
    }

    public function addDetailsCommande(DetailsCommande $detailsCommande): static
    {
        if (!$this->detailsCommandes->contains($detailsCommande)) {
            $this->detailsCommandes->add($detailsCommande);
            $detailsCommande->setProduit($this);
        }

        return $this;
    }

    public function removeDetailsCommande(DetailsCommande $detailsCommande): static
    {
        if ($this->detailsCommandes->removeElement($detailsCommande)) {
            // set the owning side to null (unless already changed)
            if ($detailsCommande->getProduit() === $this) {
                $detailsCommande->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Promotion>
     */
    public function getPromotions(): Collection
    {
        return $this->promotions;
    }

    public function addPromotion(Promotion $promotion): static
    {
        if (!$this->promotions->contains($promotion)) {
            $this->promotions->add($promotion);
            $promotion->addProduit($this);
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): static
    {
        if ($this->promotions->removeElement($promotion)) {
            $promotion->removeProduit($this);
        }

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }




    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile = null): self
{
    $this->imageFile = $imageFile;

    if ($imageFile instanceof UploadedFile) {
        $this->updatedAt = new \DateTimeImmutable();
    }

    return $this;
}
}
