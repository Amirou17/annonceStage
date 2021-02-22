<?php

namespace App\Entity;

use App\Repository\StageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="Stage")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=StageRepository::class)
 */
class Stage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min=5,
     *     max=20,
     *     minMessage="Le poste ne doit pas avoir minimun 5 caractères",
     *     maxMessage="Le poste ne doit pas depasser 20 caratères"
     * )
     */
    private $poste;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *     min=10,
     *     max=255,
     *     minMessage="La description ne doit pas avoir minimun 10 caractères",
     *     maxMessage="Le poste ne doit pas depasser 20 caratères"
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_expiration;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_modification;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="L'e-mail '{{ value }}' n'est pas un e-mail valide")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage="La sociéte ne doit pas avoir minimun 3 caractères",
     *     maxMessage="Le sociéte ne doit pas depasser 15 caratères"
     * )
     */
    private $societe;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min=3,
     *     max=255,
     *     minMessage="La ville ne doit pas avoir minimun 3 caractères",
     *     maxMessage="Le ville ne doit pas depasser 15 caratères"
     * )
     */
    private $ville;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="stages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\ManyToMany(targetEntity=Tuteur::class, inversedBy="stages")
     */
    private $tuteurs;

    /**
     * @Gedmo\Slug(fields={"poste"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    public function __construct()
    {
        $this->tuteurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(string $poste): self
    {
        $this->poste = $poste;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getDateExpiration(): ?\DateTimeInterface
    {
        return $this->date_expiration;
    }

    public function setDateExpiration(?\DateTimeInterface $date_expiration): self
    {
        $this->date_expiration = $date_expiration;

        return $this;
    }

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->date_modification;
    }

    public function setDateModification(?\DateTimeInterface $date_modification): self
    {
        $this->date_modification = $date_modification;

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

    public function getSociete(): ?string
    {
        return $this->societe;
    }

    public function setSociete(string $societe): self
    {
        $this->societe = $societe;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection|Tuteur[]
     */
    public function getTuteurs(): Collection
    {
        return $this->tuteurs;
    }

    public function addTuteur(Tuteur $tuteur): self
    {
        if (!$this->tuteurs->contains($tuteur)) {
            $this->tuteurs[] = $tuteur;
        }

        return $this;
    }

    public function removeTuteur(Tuteur $tuteur): self
    {
        $this->tuteurs->removeElement($tuteur);

        return $this;
    }


    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->date_creation = new \DateTime();
        $this->date_modification = new \DateTime();

        if (!$this->date_expiration) {
            $this->date_expiration = (clone $this->date_creation)->modify('+20 days');
        }
    }

    /**
     * @ORM\PreUpdate() */
    public function preUpdate()
    {
        $this->date_modification = new \DateTime();
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function __toString(){
        return $this->poste;
    }

}
