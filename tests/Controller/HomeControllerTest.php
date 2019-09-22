<?php
// tests/Controller/HomeControllerTest.php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testIndex()
    {   
      $client = static::createClient();

      $crawler = $client->request('GET', '/');
      
      $form = $crawler->selectButton('Search')->form();

      $form['symbol_form[companySymbol]'] = 'AAPL';
      $form['symbol_form[startDate]'] = '2010-08-01';
      $form['symbol_form[endDate]'] = '2010-09-01';
      $form['symbol_form[email]'] = 'john_maris@hotmail.com';

      $crawler = $client->submit($form);

      $this->assertEquals(200, 
                    $client->getResponse()->getStatusCode(),
                    "Failed to submint the form");
    }

    public function testIndexWithEmailSend()
    {   
      $client = static::createClient();

      $crawler = $client->request('GET', '/');
      
      $form = $crawler->selectButton('Search')->form();

      $form['symbol_form[companySymbol]'] = 'AAPL';
      $form['symbol_form[startDate]'] = '2010-08-01';
      $form['symbol_form[endDate]'] = '2010-09-01';
      $form['symbol_form[email]'] = 'john_maris@hotmail.com';
      $form['symbol_form[emailSend]'] = '1';

      $crawler = $client->submit($form);
      
      $this->assertEquals(200, 
                    $client->getResponse()->getStatusCode(),
                    "Failed to call the form with send email checkbox");
    }

    public function testSearchResults()
    {
      $client = static::createClient();

      $crawler = $client->request('GET', '/');
      
      $form = $crawler->selectButton('Search')->form();

      $form['symbol_form[companySymbol]'] = 'UNH';
      $form['symbol_form[startDate]'] = '2010-08-01';
      $form['symbol_form[endDate]'] = '2010-09-01';
      $form['symbol_form[email]'] = 'john_maris@hotmail.com';
      $form['symbol_form[emailSend]'] = '1';

      $crawler = $client->submit($form);

      $resultTableRows = $crawler->filter('.results tbody tr');
      
      $expectedValues = [
        0 => [
          '2010-08-02', '30.89', '31.56', '30.83' ,'31.44', '7440900.0'
        ],
        1 => [
          '2010-08-03', '31.39', '32.58', '31.27', '32.42', '9954200.0'
        ]
      ];

      foreach($expectedValues as $index => $values) 
      {
        $resultColumns = $resultTableRows->eq($index)->filter('td');
        
        $concatenatedColumns = "";
        
        foreach($resultColumns as $column) 
        {          
          $concatenatedColumns .= $column->nodeValue;
        }
        $this->assertEquals(implode($values), 
                            $concatenatedColumns,
                          "The historical values are not correct or missing");
      }     
    }
}