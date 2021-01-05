<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InvoiceRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Invoice
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $invoice_id;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;


    /**
     * @ORM\Column(type="float")
     */
    private $selling_price;

    /**
     * @ORM\Column(type="date")
     */
    private $due_date;

    /**
     * @ORM\ManyToOne(targetEntity=InvoiceSourceFile::class, inversedBy="invoices")
     */
    private $invoiceSourceFile;


    /**
     * Gets triggered only on insert
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        //calculate selling price
        $today = new \DateTime();

        $days = (int) $today->diff($this->getDueDate())->format("%r%a");

        if ($days > 30)
            $this->setSellingPrice($this->getAmount() * .5);
        elseif ($days <= 30) {
            $this->setSellingPrice($this->getAmount() * .3);
        }
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInvoiceId(): ?int
    {
        return $this->invoice_id;
    }

    public function setInvoiceId(int $invoice_id): self
    {
        $this->invoice_id = $invoice_id;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->due_date;
    }

    public function setDueDate(\DateTimeInterface $due_date): self
    {
        $this->due_date = $due_date;

        return $this;
    }

    public function getInvoiceSourceFile(): ?InvoiceSourceFile
    {
        return $this->invoiceSourceFile;
    }

    public function setInvoiceSourceFile(?InvoiceSourceFile $invoiceSourceFile): self
    {
        $this->invoiceSourceFile = $invoiceSourceFile;

        return $this;
    }

    /**
     * Get the value of selling_price
     */
    public function getSellingPrice()
    {
        return $this->selling_price;
    }

    /**
     * Set the value of selling_price
     *
     * @return  self
     */
    public function setSellingPrice($selling_price)
    {
        $this->selling_price = $selling_price;

        return $this;
    }
}
