<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 */
class Customer
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=InvoiceSourceFile::class, mappedBy="customer", orphanRemoval=true)
     */
    private $uploads;

    public function __construct()
    {
        $this->uploads = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|InvoiceSourceFile[]
     */
    public function getUploads(): Collection
    {
        return $this->uploads;
    }

    public function addUpload(InvoiceSourceFile $upload): self
    {
        if (!$this->uploads->contains($upload)) {
            $this->uploads[] = $upload;
            $upload->setCustomer($this);
        }

        return $this;
    }

    public function removeUpload(InvoiceSourceFile $upload): self
    {
        if ($this->uploads->removeElement($upload)) {
            // set the owning side to null (unless already changed)
            if ($upload->getCustomer() === $this) {
                $upload->setCustomer(null);
            }
        }

        return $this;
    }
}
