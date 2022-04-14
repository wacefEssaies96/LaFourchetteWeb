<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
// indexes={@ORM\Index(name="fk_plat", columns={"nomProd"})}
/**
 * Plat
 *
 * @ORM\Table(name="plat")
 * @ORM\Entity(repositoryClass="App\Repository\PlatRepository")
 */
class Plat
{
    /**
     * @var string
     *  * @Assert\Type("string")
     * @ORM\Id
     * @Assert\NotBlank(message="Le Champ Nom est obligatoire")
     * @ORM\Column(name="reference", type="string", nullable=false)
     */
    private $reference;

    /**
     * @var string
      * @Assert\Type("string")
     *@Assert\NotBlank(message="Le Champ Designation est obligatoire")

     * @ORM\Column(name="designation", type="string", length=255, nullable=false)
     */
    private $designation;

    /**
     * @var float
     * @Assert\
    @Assert\NotBlank(message="Le Champ prix est obligatoire")
     * @Assert\Positive(message="Price should be >0")
     * * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix;

    /**
     * @var string
     *  * @Assert\Type("string")
    @Assert\NotBlank(message="Le Champ Description est obligatoire")
     * @ORM\Column(name="description", type="string", length=500, nullable=false)
     */
    private $description;

    /**
     * @var string
      * @Assert\Type("string")
    @Assert\NotBlank(message="Le Champ image est obligatoire")
     * @ORM\Column(name="imageP", type="string", length=255, nullable=false)
     */
    private $imagep;

    /**
     * @var string
     * @Assert\Type("string")
    @Assert\NotBlank(message="Le Champ nomprod est obligatoire")
     * @ORM\Column(name="nomProd", type="string", length=255, nullable=false)
     */
    private $nomprod;

    public function getReference(): ?string
    {
        return $this->reference;
    }
    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
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
