<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Plat
 *
 * @ORM\Table(name="plat", indexes={@ORM\Index(name="fk_plat", columns={"nomProd"})})
 * @ORM\Entity
 */
class Plat
{
    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="designation", type="string", length=255, nullable=false)
     */
    private $designation;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=500, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="imageP", type="string", length=255, nullable=false)
     */
    private $imagep;

    /**
     * @var string
     *
     * @ORM\Column(name="nomProd", type="string", length=255, nullable=false)
     */
    private $nomprod;

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImagep(): ?string
    {
        return $this->imagep;
    }

    public function setImagep(string $imagep): self
    {
        $this->imagep = $imagep;

        return $this;
    }

    public function getNomprod(): ?string
    {
        return $this->nomprod;
    }

    public function setNomprod(string $nomprod): self
    {
        $this->nomprod = $nomprod;

        return $this;
    }


}
