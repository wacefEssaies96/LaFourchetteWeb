<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TableResto
 *
 * @ORM\Table(name="table_resto")
 * @ORM\Entity(repositoryClass="App\Repository\TableRestoRepository")
 */
class TableResto
{
    /**
     * @var int
     *
     * @ORM\Column(name="IdT", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idt;

    /**
     * @var int
     *
     * @ORM\Column(name="NbrPlace", type="integer", nullable=false)
     */
    private $nbrplace;

    /**
     * @var string
     *
     * @ORM\Column(name="Etat", type="string", length=20, nullable=false)
     */
    private $etat;

    /**
     * @var string
     *
     * @ORM\Column(name="ImageTable", type="string", length=100, nullable=false)
     */
    private $imagetable;

    /**
     * @var string
     *
     * @ORM\Column(name="Vip", type="string", length=100, nullable=false)
     */
    private $vip;

    /**
     * @var float
     *
     * @ORM\Column(name="Prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix;

    public function getIdt(): ?int
    {
        return $this->idt;
    }

    public function getNbrplace(): ?int
    {
        return $this->nbrplace;
    }

    public function setNbrplace(int $nbrplace): self
    {
        $this->nbrplace = $nbrplace;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getImagetable(): ?string
    {
        return $this->imagetable;
    }

    public function setImagetable(string $imagetable): self
    {
        $this->imagetable = $imagetable;

        return $this;
    }

    public function getVip(): ?string
    {
        return $this->vip;
    }

    public function setVip(string $vip): self
    {
        $this->vip = $vip;

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
