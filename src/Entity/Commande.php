<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Scalar\String_;

/**
 * Commande
 *
 * @ORM\Table(name="commande", indexes={@ORM\Index(name="fk_idU_C", columns={"idU"}),@ORM\Index(name="fk_refplat", columns={"referenceplat"})})
 * @ORM\Entity(repositoryClass="App\Repository\CommandeRepository")
 */
class Commande
{
    /**
     * @var int
     *
     * @ORM\Column(name="idC", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idc;

    /**
     * @var Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur", inversedBy="Commande")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idU", referencedColumnName="idU", nullable=false)
     * })
     */
    private $idu;

     /**
      * @ORM\Column(name="quantity", type="integer", length=255, nullable=false)
      */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="livraison", type="string", length=255, nullable=false)
     */
    private $livraison;

    /**
     * @var float
     *
     * @ORM\Column(name="prixC", type="float", precision=10, scale=0, nullable=false)
     */
    private $prixc;

    /**
     * @var Plat
     * @ORM\Column(name="referenceplat", type="string", nullable=false)
     * @ORM\ManyToOne(targetEntity=Plat::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="reference", referencedColumnName="reference", nullable=false)
     * })
     */
    private $referenceplat;


    public function getIdc(): ?int
    {
        return $this->idc;
    }

    public function getIdu(): ?Utilisateur
    {
        return $this->idu;
    }

    public function setIdu(?Utilisateur $idu): self
    {
        $this->idu = $idu;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getLivraison(): ?string
    {
        return $this->livraison;
    }

    public function setLivraison(string $livraison): self
    {
        $this->livraison = $livraison;

        return $this;
    }

    public function getPrixc(): ?float
    {
        return $this->prixc;
    }

    public function setPrixc(float $prixc): self
    {
        $this->prixc = $prixc;

        return $this;
    }

    public function getReferencePlat(): ?string
    {
        return  $this->referenceplat;
    }

    public function setReferencePlat(?Plat $referenceplat): self
    {
        $this->referenceplat = $referenceplat;

        return $this;
    }




}
