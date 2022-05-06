<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
  * @ORM\Table(name="utilisateur")

 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    const ROLE_ADMIN="ROLE_ADMIN";

    /**
     * @ORM\Id
 
     * @ORM\GeneratedValue
     * @ORM\Column(name="idU",type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="email",type="string", length=180, unique=true)
     */
    private $email;
    /**
     * @ORM\Column(name="nom_prenom",type="string", length=180, )
     */
    private $name;

    /**
     * @ORM\Column(name="role",type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(name="password",type="string")
     */
    private $password;
    /**
     * @ORM\Column(name="reset_token",type="string", length=255, nullable=true)
      */
    private $resetToken;

         /**
     * @ORM\Column(name="verif",type="string", length=255, nullable=true)
      */
    private $verif;
          /**
     * @ORM\Column(name="adresse",type="string", length=255, nullable=true)
      */
      private $addresse;
       /**
     * @ORM\Column(name="telephone",type="string", length=255, nullable=true)
      */
      private $telephone;
          /**
     * @ORM\Column(name="picture",type="string", length=255, nullable=true)
      */
      private $picture;
    public function getId(): ?int
    {
        return $this->id;
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
    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }
    public function getAddresse(): ?string
    {
        return $this->addresse;
    }

    public function setAddresse(string $addresse): self
    {
        $this->addresse = $addresse;

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
    public function getVerif(): ?string
    {
        return $this->verif;
    }

    public function setVerif(string $verif): self
    {
        $this->verif = $verif;

        return $this;
    }
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    public function isAdmin():bool{
        return in_array(self::ROLE_ADMIN,$this->getRoles());
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }
}
