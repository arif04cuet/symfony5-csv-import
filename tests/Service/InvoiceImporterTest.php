<?php

namespace App\Tests\Service;

use App\Entity\Customer;
use App\Entity\Invoice;
use App\Entity\InvoiceSourceFile;
use App\Service\InvoiceImporter;
use App\Tests\DatabasePrimer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        DatabasePrimer::prime($kernel);

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testParseAndImport()
    {
        /** @var Container $container */
        $container = self::$container; // this allows us to access even private services directly

        /** @var EntityManager $em */
        $em = $container->get('doctrine')->getManager();

        $customer = $this->entityManager
            ->getRepository(Customer::class)
            ->findOneby(['name' => 'Customer 1']);
        $invoiceSourceFileRepo = $this->entityManager
            ->getRepository(InvoiceSourceFile::class);

        $invoiceRepo = $this->entityManager
            ->getRepository(Invoice::class);


        $this->assertSame('Customer 1', $customer->getName());

        $invoiceImporter = $container->get(InvoiceImporter::class);

        $this->assertInstanceOf('App\Service\InvoiceImporter', $invoiceImporter);

        $invoiceImporter->setCustomer($customer);

        $this->assertSame($customer->getName(), $invoiceImporter->getCustomer()->getName());

        $this->assertCount(0, $invoiceSourceFileRepo->findAll([]));
        $this->assertCount(0, $invoiceRepo->findAll([]));

        $csvFilePath = __DIR__ . "/../files/MOCK_DATA.csv";

        $invoiceImporter->parseAndImport($csvFilePath);

        $this->assertCount(1, $invoiceSourceFileRepo->findAll([]));
        $this->assertCount(993, $invoiceRepo->findAll([]));
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
