<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 * fields={"email"},
 * message="Cet email est déjà associé à un compte"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir votre mot de passe")
     * @Assert\Length(
     * min = "6",
     * minMessage = "Votre mot de passe doit contenir un minimum de 6 caractères"
     * )
     * @Assert\EqualTo(
     * propertyPath="confirm_password",
     * message="les mots de passe ne sont pas identiques"
     * )
     */
    private $password;



    /**
     * @Assert\EqualTo(
     * propertyPath="password",
     * message="les mots de passe ne sont pas identiques"
     * )
     */
    public $confirm_password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir votre nom")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir votre prénom")
     */
    private $prenom;


    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }


    // On doit inclure 5 fonctions de UserInterface
    // password / username / roles/ salt / eraseCredentials




    public function getUsername() : ?string
    {
        return (string) $this->email;
    }


    // Méthode renvoie un tableau de chaine de caractères (les roles)
    public function getRoles() : array
    {
        $roles = $this->roles;
        return array_unique($roles);
        //return array('ROLE_USER'); // utlilisateur classique
    }

    public function setRoles(array $roles):self
    {
        $this->roles = $roles;
        return $this;
    }

    // Méthode renvoie la chaine de caractère non encodé que l'utilisateur a saisir, qui a été utilisé à l'origine pour code le mot de passe
    public function getSalt(){}


    // Méthode destinée uniquement à nettoyer les mots de passe en texte brut éventuellement stockés
    public function eraseCredentials(){}

}
