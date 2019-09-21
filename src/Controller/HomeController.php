<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\SymbolForm;
use App\Entity\Symbol;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class HomeController extends AbstractController
{
  /**
   * @Route("/", name="home")
   */
  public function index(Request $request,  \Swift_Mailer $mailer)
  {     
    $dataTable = "";
    $symbol = new Symbol();
    $form = $this->createForm(SymbolForm::class, $symbol);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid())
    {      
      $symbol = $form->getData();      
      $callbackLink = "https://www.quandl.com/api/v3/datasets/WIKI";
      $callbackLink.="/".$symbol->getCompanySymbol().".csv";
      $callbackLink.="?order=asc";
      $callbackLink.="&start_date=".$symbol->getStartDate();
      $callbackLink.="&end_date=".$symbol->getEndDate();

      $csvData = $this->getRemoteCSV($callbackLink);
      $dataTable = $this->parseCSVData($csvData);
      
      if(true === $form['emailSend']->getData())
      { 
        $mailStatus = $this->sendEmail(
          $symbol->getCompanySymbol(), 
          $dataTable, 
          $symbol->getEmail(), 
          $symbol->getStartDate(), 
          $symbol->getEndDate(),
          $mailer
        );
      }
    }

    return $this->render('home/index.html.twig', [
        'controller_name' => 'HomeController',
        'form' => $form->createView(),
        'data' => $dataTable
    ]);
  }

  protected function getRemoteCSV($link)
  {
    $client = HttpClient::create();
    $response = $client->request('GET', $link);

    $statusCode = $response->getStatusCode();
    if($statusCode != "200")
    {
      return "";
    }
    $contentType = $response->getHeaders()['content-type'][0];
    $content = $response->getContent();

    return $content;
  }

  protected function parseCSVData($csvData) 
  {
    $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);    
    $data = $serializer->decode($csvData, 'csv');
    return $data;
  }

  protected function sendEmail($subject, $data, $toEmail, $startDate, $endDate, $mailer)
  {
    $message = (new \Swift_Message())
      ->setSubject($subject)
      ->setFrom(['maris@vnet-marketing.com'])
      ->setTo([$toEmail])
      ->setBody($this->renderView(
        'home/mail.html.twig',
        [
          'data' => $data,
          'startDate' => $startDate,
          'endDate' => $endDate
        ]
      ),'text/html');

    $mailSendStatus = $mailer->send($message);

    return $mailSendStatus;
  }
}