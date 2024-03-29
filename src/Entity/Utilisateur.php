<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur")
 * @ORM\Entity(repositoryClass="App\Repository\UtilisateurRepository")
 */
class Utilisateur
{
    /**
     * @var int
     *
     * @ORM\OneToMany(targetEntity="Commentaire", mappedBy="Utilisateur")
     * @ORM\OneToMany(targetEntity="Reservation", mappedBy="Utilisateur")
     * @ORM\Column(name="idU", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idu;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_prenom", type="string", length=255, nullable=false)
      */
    private $nomPrenom;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     *@Assert\NotBlank(message="Le Champ email est obligatoire")
          * @Assert\Email(message = "The email '{{ value }}' is not a valid email")

     * 
     * 
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Le Champ email est obligatoire")
     *  @Assert\Length(
     *     min=5,
     *     minMessage="La Description doit contenir au moins 5 carcatères "
     * )

     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=30, nullable=false)
     * 
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=false)
     * 
     */
    private $adresse;

    /**
     * @var int
     *
     * @ORM\Column(name="telephone", type="integer", nullable=false)
     * 
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=100, nullable=false)
     *
     */
    private $picture;

    /**
     * @var string
     *
     * @ORM\Column(name="verif", type="string", length=255, nullable=false)
     * 
     */
    private $verif;

   

    public function getIdu(): ?int
    {
        return $this->idu;
    }
    
    public function setIdu(int $idu): self
    {
        $this->idu = $idu;

        return $this;
    }

    public function getNomPrenom(): ?string
    {
        return $this->nomPrenom;
    }

    public function setNomPrenom(string $nomPrenom): self
    {
        $this->nomPrenom = $nomPrenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getVerif(): ?string
    {
        return $this->verif;
    }

    public function setVerif(string $verif): self
    {
        $this->verif = $verif;

        return $this;
    }
    public function __construct($role,$verif)
    {
        $this->role =$role;
        $this->verif=$verif;

    }

    public function __toString()
    {
        return (string)$this->getIdu();
    }
}
