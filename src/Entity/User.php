<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    const ID = 'user_id';

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank()
     */
    private $email;
    const EMAIL = "email";

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];
    const ROLES = "roles";

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $password;
    const PASSWORD = "password";

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastName;
    const LAST_NAME = "last_name";

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;
    const FIRST_NAME = "first_name";

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;
    const ADDRESS = "address";

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $zipCode;
    const ZIP_CODE = "zip_code";

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;
    const CITY = "city";

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $birthday;
    const BIRTHDAY = "birthday";

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;
    const CREATED_AT = "created_at";

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $deletedAt;
    const DELETE_AT = "delete_at";

    /**
     * @ORM\Column(type="string", length=255, unique=true).
     *
     * @Assert\NotBlank()
     */
    private $pseudo;
    const PSEUDO = "pseudo";

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="sendBy")
     */
    private $messages;
    const MESSAGES = "messages";

    /**
     * @ORM\OneToMany(targetEntity=Salon::class, mappedBy="manager")
     */
    private $salons;
    const SALONS = "salons";

    /**
     * @ORM\OneToOne(targetEntity=Image::class, cascade={"persist", "remove"})
     */
    private $profileImage;
    const PROFILE_IMAGE = "profile_image";

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="createdBy")
     */
    private $posts;
    const POSTS = "posts";

    /**
     * @ORM\ManyToMany(targetEntity=Salon::class, mappedBy="artists")
     */
    private $workingSalon;
    const WORKING_SALON = "working_salon";

    /**
     * @ORM\OneToMany(targetEntity=Notice::class, mappedBy="userNoted")
     */
    private $notices;
    const NOTICES = 'notices';

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->salons = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->workingSalon = new ArrayCollection();
    }

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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
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
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
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

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(?string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setSendBy($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getSendBy() === $this) {
                $message->setSendBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Salon[]
     */
    public function getSalons(): Collection
    {
        return $this->salons;
    }

    public function addSalon(Salon $salon): self
    {
        if (!$this->salons->contains($salon)) {
            $this->salons[] = $salon;
            $salon->setManager($this);
        }

        return $this;
    }

    public function removeSalon(Salon $salon): self
    {
        if ($this->salons->removeElement($salon)) {
            // set the owning side to null (unless already changed)
            if ($salon->getManager() === $this) {
                $salon->setManager(null);
            }
        }

        return $this;
    }

    public function getProfileImage(): ?Image
    {
        return $this->profileImage;
    }

    public function setProfileImage(?Image $profileImage): self
    {
        $this->profileImage = $profileImage;

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setCreatedBy($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getCreatedBy() === $this) {
                $post->setCreatedBy(null);
            }
        }

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return Collection<int, Salon>
     */
    public function getWorkingSalon(): Collection
    {
        return $this->workingSalon;
    }

    public function addWorkingSalon(Salon $workingSalon): self
    {
        if (!$this->workingSalon->contains($workingSalon)) {
            $this->workingSalon[] = $workingSalon;
            $workingSalon->addArtist($this);
        }

        return $this;
    }

    public function removeWorkingSalon(Salon $workingSalon): self
    {
        if ($this->workingSalon->removeElement($workingSalon)) {
            $workingSalon->removeArtist($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Notice>
     */
    public function getNotices(): Collection
    {
        return $this->notices;
    }

    public function addNotice(Notice $notice): self
    {
        if (!$this->notices->contains($notice)) {
            $this->notices[] = $notice;
            $notice->setSalonNoted($this);
        }

        return $this;
    }

    public function removeNotice(Notice $notice): self
    {
        if ($this->notices->removeElement($notice)) {
            // set the owning side to null (unless already changed)
            if ($notice->getSalonNoted() === $this) {
                $notice->setSalonNoted(null);
            }
        }

        return $this;
    }
}
