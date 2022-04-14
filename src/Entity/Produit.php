<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @ORM\Entity(repositoryClass="App\Repository\ProduitRepository")
 */
class Produit
{
    
    /**
     * @var string
     * @ORM\Id
     * @ORM\OneToMany(targetEntity="ProduitFournisseur", mappedBy="produit")
     * @Assert\NotBlank(message="Ce champ est obligatoire")
     * @ORM\Column(name="nomProd", type="string", nullable=false)
     */
    private $nomprod;

    /**
     * @var int
     * @Assert\NotNull(message="La quantité doît être différente de 0")
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     */
    private $quantite;

    /**
     * @var string
     * @Assert\NotBlank(message="Il faut mettre une image")
     * @Assert\File(mimeTypes={"image/png","image/jpeg"})
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     */
    private $image;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     * @Assert\NotNull(message="Le prix doît être différent de 0")
     */
    private $prix;

    

    public function getNomprod(): ?string
    {
        return $this->nomprod;
    }
    public function setNomProd(string $nomprod): self
    {
        $this->nomprod = $nomprod;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }


}
