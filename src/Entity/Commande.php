<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande", indexes={@ORM\Index(name="fk_idU_C", columns={"idU"})})
 * @ORM\Entity
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
     * @var int
     *
     * @ORM\Column(name="idU", type="integer", nullable=false)
     */
    private $idu;

    /**
     * @var string
     *
     * @ORM\Column(name="etatC", type="string", length=255, nullable=false)
     */
    private $etatc;

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

    public function getIdc(): ?int
    {
        return $this->idc;
    }

    public function getIdu(): ?int
    {
        return $this->idu;
    }

    public function setIdu(int $idu): self
    {
        $this->idu = $idu;

        return $this;
    }

    public function getEtatc(): ?string
    {
        return $this->etatc;
    }

    public function setEtatc(string $etatc): self
    {
        $this->etatc = $etatc;

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


}
