<?php

namespace App\Tests\Controller;

use App\Tests\DatabaseAwareTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DefaultControllerTest extends WebTestCase
{

    public function testHomePage()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testMoctCSVFileExist()
    {
        $csvFilePath = __DIR__ . "/../files/MOCK_DATA.csv";
        $this->assertFileExists($csvFilePath);
    }

    // public function testUploadFile()
    // {
    //     $client = static::createClient();

    //     $csvFilePath = __DIR__ . "/../files/MOCK_DATA.csv";
    //     $this->assertFileExists($csvFilePath);


    //     // $file = new UploadedFile(
    //     //     $csvFilePath,
    //     //     'MOCK_DATA.csv',
    //     //     'text/csv',
    //     //     null
    //     // );
    //     // $client->request(
    //     //     'POST',
    //     //     '/',
    //     //     [],
    //     //     ['invoice_upload[file_name]' => $file]
    //     // );

    //     // $this->assertEquals(1, count($client->getRequest()->files->all()));
    // }
}
