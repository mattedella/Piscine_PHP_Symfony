<?php

namespace App\Entity;

use App\Repository\AddressOrmRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressOrmRepository::class)]
class AddressOrm
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $address = null;
    
    #[ORM\ManyToOne(targetEntity: PersonsOrm::class, inversedBy: 'addresses')]
    #[ORM\JoinColumn(name: 'person_id', referencedColumnName: 'id', nullable: false)]
    private ?PersonsOrm $person = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

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
