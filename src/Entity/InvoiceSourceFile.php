<?php

namespace App\Entity;

use App\Repository\InvoiceSourceFileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InvoiceSourceFileRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class InvoiceSourceFile
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
    private $file_name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $errros;

    /**
     * @ORM\Column(type="integer")
     */
    private $total_records;

    /**
     * @var datetime $created
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="uploads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\OneToMany(targetEntity=Invoice::class, mappedBy="invoiceSourceFile", cascade={"remove"})
     */
    private $invoices;


    private $invoicefileUrl;

    public function __construct()
    {
        $this->invoices = new ArrayCollection();
    }


    /**
     * Gets triggered only on insert
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created = new \DateTime("now");
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->file_name;
    }

    public function setFileName(string $file_name): self
    {
        $this->file_name = $file_name;

        return $this;
    }

    public function getErrros(): ?array
    {
        return $this->errros ? json_decode($this->errros, true) : [];
    }

    public function setErrros(?string $errros): self
    {
        $this->errros = $errros;

        return $this;
    }

    public function getTotalRecords(): ?int
    {
        return $this->total_records;
    }

    public function setTotalRecords(int $total_records): self
    {
        $this->total_records = $total_records;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Collection|Invoice[]
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoice $invoice): self
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices[] = $invoice;
            $invoice->setInvoiceSourceFile($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): self
    {
        if ($this->invoices->removeElement($invoice)) {
            // set the owning side to null (unless already changed)
            if ($invoice->getInvoiceSourceFile() === $this) {
                $invoice->setInvoiceSourceFile(null);
            }
        }

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Get the value of invoicefileUrl
     */
    public function getInvoicefileUrl()
    {
        return '/uploads/invoices/' . $this->getFileName();
    }
}
