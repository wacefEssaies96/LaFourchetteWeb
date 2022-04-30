<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Fournisseur;
use App\Entity\Produit;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProduitFournisseur
 *
 * @ORM\Table(name="produit_fournisseur", indexes={@ORM\Index(name="idF", columns={"idF"}), @ORM\Index(name="nomProd", columns={"nomProd"})})
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
     * @ORM\ManyToOne(targetEntity="Fournisseur", inversedBy="produitFournisseur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idF", referencedColumnName="idF")
     * })
     */
    private $idf;

    /**
     * @var \Produit
     *
     * @ORM\ManyToOne(targetEntity="Produit", inversedBy="produitFournisseur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="nomProd", referencedColumnName="nomProd")
     * })
     */
    private $nomprod;
    /**
     * @var int
     * @Assert\NotNull(message="La quantité doît être différente de 0")
     */
    private $quantite;
    
    public function getQuantite(){
        return $this->quantite;
    }
    public function setQuantite($quantite){
        $this->quantite = $quantite;
    }

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
