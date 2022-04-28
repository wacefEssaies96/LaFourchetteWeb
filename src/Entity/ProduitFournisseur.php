<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProduitFournisseur
 *
 * @ORM\Table(name="produit_fournisseur", indexes={@ORM\Index(name="idF", columns={"idF"}), @ORM\Index(name="id", columns={"id"})})
 * @ORM\Entity(repositoryClass="App\Repository\ProduitFournisseurRepository")
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
     *   @ORM\JoinColumn(name="id", referencedColumnName="id")
     * })
     */
    private $nomprod;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdf()
    {
        return $this->idf;
    }

    public function setIdf(?Fournisseur $idf): self
    {
        $this->idf = $idf;

        return $this;
    }

    public function getNomprod()    
    {
        return $this->nomprod;
    }

    public function setNomprod(?Produit $nomprod): self
    {
        $this->nomprod = $nomprod;

        return $this;
    }


}
