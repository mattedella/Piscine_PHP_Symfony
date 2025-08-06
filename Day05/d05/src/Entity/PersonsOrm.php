<?php

namespace App\Entity;

use App\Repository\PersonsOrmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonsOrmRepository::class)]
class PersonsOrm
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $enable = null;

    #[ORM\Column]
    private ?\DateTime $birthdate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $maritalStatus = null;

    #[ORM\OnetoOne(mappedBy: 'person', cascade: ['persist', 'remove'])]
    private ?BankAccountOrm $bankAccount = null;

    /** @var Collection<int, AddressOrm> */
    #[ORM\OnetoMany(mappedBy: 'person', targetEntity: AddressOrm::class, cascade: ['persist', 'remove'])]
    private $addresses;

    public function __construct() {
        $this->address = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getEnable(): ?string
    {
        return $this->enable;
    }

    public function setEnable(string $enable): static
    {
        $this->enable = $enable;

        return $this;
    }

    public function getBirthdate(): ?\DateTime
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTime $birthdate): static
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getMaritalStatus(): ?string
    {
        return $this->maritalStatus;
    }

    public function setMaritalStatus(string $maritalStatus): self
    {
        // Optional: Validate manually against allowed values
        $allowed = ['single', 'married', 'widower'];
        if (!in_array($maritalStatus, $allowed, true)) {
            throw new \InvalidArgumentException("Invalid marital status: $maritalStatus");
        }

        $this->maritalStatus = $maritalStatus;
        return $this;
    }

    public function getAddresses(): ?AddressOrm {
        return $this->addresses;
    }

    public function setAddresses(AddressOrm $address): static {
        $this->addresses = $address;

        return $this;
    }

    public function getBankAccount(): ?BankAccountOrm {
        return $this->bankAccount;
    }

    public function setBankAccount(BankAccountOrm $bankAccount): static {
        $this->bankAccount = $bankAccount;

        return $this;
    }
}