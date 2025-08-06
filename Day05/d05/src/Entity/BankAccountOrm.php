<?php

namespace App\Entity;

use App\Repository\BankAccountOrmRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BankAccountOrmRepository::class)]
class BankAccountOrm
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $iban = null;

    #[ORM\OnetoOne(inversedBy: 'bankAccount')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PersonsOrm $person = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(string $iban): static
    {
        $this->iban = $iban;

        return $this;
    }

    public function getPerson(): ?PersonsOrm {
        return $this->person;
    }

    public function setPerson(PersonsOrm $person): static {
        $this->person = $person;

        return $this;
    }
}
