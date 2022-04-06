<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProduitFournisseur
 *
 * @ORM\Table(name="produit_fournisseur", indexes={@ORM\Index(name="nomProd", columns={"nomProd"}), @ORM\Index(name="idF", columns={"idF"})})
 * @ORM\Entity
 */
class ProduitFournisseur
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Fournisseur
     *
     * @ORM\ManyToOne(targetEntity="Fournisseur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idF", referencedColumnName="idF")
     * })
     */
    private $idf;

    /**
     * @var \Produit
     *
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="nomProd", referencedColumnName="nomProd")
     * })
     */
    private $nomprod;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdf(): ?Fournisseur
    {
        return $this->idf;
    }

    public function setIdf(?Fournisseur $idf): self
    {
        $this->idf = $idf;

        return $this;
    }

    public function getNomprod(): ?Produit
    {
        return $this->nomprod;
    }

    public function setNomprod(?Produit $nomprod): self
    {
        $this->nomprod = $nomprod;

        return $this;
    }


}
