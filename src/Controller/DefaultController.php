<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Invoice;
use App\Entity\InvoiceSourceFile;
use App\Form\InvoiceUploadType;
use App\Repository\InvoiceSourceFileRepository;
use App\Service\InvoiceImporter;
use League\Csv\Reader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    public function test()
    {
        $today = new \DateTime();
        $dueDate = new \DateTime("2021-12-12");

        $data = strtotime('2021-21-12');
        return $this->json($data);
    }
    public function index(Request $request, InvoiceImporter $csvInvoiceImporter): Response
    {
        $em = $this->getDoctrine()->getManager();

        $item = new InvoiceSourceFile;
        $form = $this->createForm(InvoiceUploadType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $csvFile */
            $csvFile = $form->get('file_name')->getData();


            if ($csvFile) {

                $newFilename = uniqid() . '.' . $csvFile->getClientOriginalExtension();

                try {
                    $csvFile->move(
                        $this->getParameter('invoice_file_directory'),
                        $newFilename
                    );

                    $uploadedCsvFile = $this->getParameter('invoice_file_directory') . '/' . $newFilename;

                    $customer = $this->getDoctrine()->getRepository(Customer::class)->find(1);
                    $importer = $csvInvoiceImporter->setCustomer($customer)->parseAndImport($uploadedCsvFile);
                } catch (FileException $e) {
                }

                $this->addFlash('success', 'data imported successfully');
            }
        }

        $files = $this->getDoctrine()->getRepository(InvoiceSourceFile::class)->findAll();

        return $this->render('home.html.twig', [
            'form' => $form->createView(),
            'files' => $files
        ]);
    }

    public function listInvoices($id, InvoiceSourceFileRepository $fileRepository): Response
    {
        $item = $fileRepository->find($id);
        if (!$item) {
            throw $this->createNotFoundException(
                'There are no item with the following id: ' . $id
            );
        }

        return $this->render('invoices.html.twig', [
            'items' => $item->getInvoices(),
            'file' => $item
        ]);
    }

    public function listErrors($id, InvoiceSourceFileRepository $fileRepository): Response
    {
        $item = $fileRepository->find($id);
        if (!$item) {
            throw $this->createNotFoundException(
                'There are no item with the following id: ' . $id
            );
        }

        //dd($item->getErrros());

        return $this->render('errors.html.twig', [
            'file' => $item
        ]);
    }



    public function delete($id, InvoiceSourceFileRepository $fileRepository): Response
    {

        $item = $fileRepository->find($id);

        if (!$item) {
            throw $this->createNotFoundException(
                'There are no item with the following id: ' . $id
            );
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($item);
        $em->flush();

        $this->addFlash(
            'success',
            'Item Deleted'
        );

        return $this->redirectToRoute("index");
    }
}
