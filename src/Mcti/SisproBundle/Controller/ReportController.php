<?php
/* * * * * * * * * * * * * * * * * * * * * * * * 
 *  FECHA: MARCH 2014                          *
 *  FRAMEWORK PHP: SYMFONY Version 2.3.1       *
 *                 http://www.syfony.com       *
 *  PHONE: +58-416-9052533 / 212-5153033       *
 *  SKYPE: kolrinah                            *
 * * * * * * * * * * * * * * * * * * * * * * * */
namespace Mcti\SisproBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReportController extends Controller
{
    /*
     * TEST ELANCE
     */    
    public function packingSlipAction()
    {      
      $data = array(     'dateToday' => new \DateTime('now'),
                        'customerId' => '[12345]',
                       'companyName' => '[Company Name]',
                     'companySlogan' => '[Company Slogan]',
                        'webAddress' => '[web adrress]',
              'companyStreetAddress' => '[Street Address]',
                       'companyCity' => 'City',
                         'companyST' => 'ST',
                        'companyZip' => 'ZIP',
                      'companyPhone' => '[000-000-0000]',
                        'companyFax' => '[000-000-0000]',
                          'billName' => '[name]',
                       'billCompany' => '[Company name]',
                        'billStreet' => '[Street Address]',
                          'billCity' => 'City',
                            'billST' => 'ST',
                           'billZip' => 'ZIP',
                         'billPhone' => '[Phone]',
                          'shipName' => '[name]',
                       'shipCompany' => '[Company name]',
                        'shipStreet' => '[Street Address]',
                          'shipCity' => 'City',
                            'shipST' => 'ST',
                           'shipZip' => 'ZIP',
                         'shipPhone' => '[Phone]',  
                         'orderDate' => new \DateTime('now'),
                       'orderNumber' => '[123456]',
                     'purchaseOrder' => '[123456]',
                   'customerContact' => 'PurchasingDept.',
            'details' => array(array('item' => '[23423423]',
                                     'description' => '[Product XYZ]',
                                        'orderQty' => 15, 
                                         'shipQty' => 13
                                           ),
                                array('item' => '[45645645]',
                                     'description' => '[Product ABC]',
                                        'orderQty' => 1, 
                                         'shipQty' => 1
                                           )
                               ),
          'comments' => 'Backordered Items will ship as they become aviable',
                        'contactData' => '[Name, Phone #, E-Mail]',
          
                   );
            
      return $this->render('SisproBundle:ReportesDinamicos:packing-slip.html.twig', 
                                       array('data'=>$data));     
    }
    
    public function packingSlip2Action()
    {      
      $data = array(     'dateToday' => new \DateTime('now'),
                        'customerId' => '[ABC12345]',
                       'companyName' => '[Your Company Name]',
                     'companySlogan' => '[Your Company Slogan Here]',
                        'companyLogo' => 'Your Logo Here',
              'companyStreetAddress' => '[Street Address]',
                       'companyCity' => 'City',
                         'companyST' => 'ST',
                        'companyZip' => 'ZIP Code',
                      'companyPhone' => '[Phone]',
                        'companyFax' => '[Fax]',
                      'companyEmail' => '[E-mail]',
                          'billName' => '[name]',
                       'billCompany' => '[Company name]',
                        'billStreet' => '[Street Address]',
                          'billCity' => 'City',
                            'billST' => 'ST',
                           'billZip' => 'ZIP Code',
                         'billPhone' => '[Phone]',
                          'shipName' => '[name]',
                       'shipCompany' => '[Company name]',
                        'shipStreet' => '[Street Address]',
                          'shipCity' => 'City',
                            'shipST' => 'ST',
                           'shipZip' => 'ZIP Code',
                         'shipPhone' => '[Phone]',  
                         'orderDate' => null,
                       'salesperson' => '[name]',
                     'purchaseOrder' => '[100001]',
                       'packingDate' => null,
            'details' => array(array('item' => '113322',
                                     'description' => 'Product 6',
                                        'orderQty' => 3
                                           ),
                                array('item' => '444444',
                                     'description' => 'Product 17',
                                        'orderQty' => 8
                                           )
                               ),
                           'comments' => null,
                         'packagedBy' => '[Name]',
                        'managerName' => '[Managers Name]',
                   'managerSignature' => '[Managers Signature]'
          
                   );
            
      return $this->render('SisproBundle:ReportesDinamicos:packing-slip2.html.twig', 
                                       array('data'=>$data));     
    }    

}