<?php

namespace App\Service;

use App\Entity\Invoice;
use App\Entity\InvoiceSourceFile;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use League\Csv\Statement;

class InvoiceImporter
{
    const BATCH_COUNT = 20;

    protected $em;
    public $errors = [];
    public $data = [];
    public $customer;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function parseAndImport($csvFileName)
    {

        $parts = explode('/', $csvFileName);
        $fileName = end($parts);


        $csv = Reader::createFromPath($csvFileName);
        $csv->setHeaderOffset(0);

        $records = Statement::create()->where(function ($record) {

            $recordIsValid = true;

            //validate invoice id
            $error = [];

            if (!filter_var($record['id'], FILTER_VALIDATE_INT))
                $error[] = 'invoice id is not valid';

            if (!filter_var($record['amount'], FILTER_VALIDATE_FLOAT) || !filter_var($record['amount'], FILTER_VALIDATE_INT))
                $error[] = 'Amount is not valid';
            // must be a valida date format and future date
            if (!strtotime($record['due_date']) || time() > strtotime($record['due_date']))
                $error[] = 'Due date is not valid';

            if (count($error) > 0) {
                $this->errors[$record['id']] = $error;
                $recordIsValid = false;
            }


            return $recordIsValid;
        })->process($csv);

        $totalRecords = count($records) + count($this->errors);

        $this->data = $records;

        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);

        $item = new InvoiceSourceFile();
        $item->setFileName($fileName);
        $item->setErrros(json_encode($this->errors));
        $item->setTotalRecords($totalRecords);
        $item->setCustomer($this->getCustomer());

        $this->em->persist($item);

        foreach ($records as $row) {

            $invoice = new Invoice;

            $invoice->setInvoiceId($row['id']);
            $invoice->setAmount($row['amount']);
            $invoice->setDueDate(\DateTime::createFromFormat('Y-m-d', $row['due_date']));
            $invoice->setInvoiceSourceFile($item);

            $this->em->persist($invoice);
        }

        $this->em->flush();
        $this->em->clear();
    }


    /**
     * Get the value of customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set the value of customer
     *
     * @return  self
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get the value of data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get the value of errors
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
