<?php

namespace Admin\Mail;

use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail as SendmailTransport;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Mime;

class Mailer
{
    protected $offer          = 'gielarek@gmail.com';
    protected $notification   = 'gielarek@gmail.com';       
    protected $serviceMail    = 'gielarek@gmail.com';
    protected $contact        = 'gielarek@gmail.com';

    public function sendContactMail($content)
    {
       
        $text = new MimePart('Nowa treść dostepna na http://'.$_SERVER['SERVER_NAME'].'/admin');
        $text->type = "text/plain";
        $oFooter = $this->getFooter();
        $html = new MimePart('
           <div style="font-family:Tahoma;">
                <div>
                    <h3>Formularz kontakowy:</h3> 
                </div>
                <div style="font-size:12px; font-color:#444;">
                    <p><b>Imię i Nazwisko: </b>' .$content['contactName']. '</p>
                    <p><b>E-mail: </b>' .$content['contactEmail']. '</p>                                         
                </div>
                <div style="margin:15px 0; font-color:#444;">
                    <p><b>Treść wiadomości: </p>
                    ' .$content['contactMessage']. '
                </div>
                    <hr style="border:0; border-bottom: 1px solid #efefef"/>
               '.$oFooter.'
          </div>           
          
        ');
        
        $html->type = "text/html";

        $body = new MimeMessage();
        $body->setParts(array($text, $html));        
        
        $message = new Message();
        
        //$message->addFrom($content['contactEmail'], $content['contactName'])
        $message->addFrom( 'test@wp.pl', 'Strona domowa')
                ->addTo($this->contact)
                ->setSubject('Formularz kontaktowy V-Walk');
        
        $message->setBody($body);
        
        $message->addReplyTo($content['contactEmail'], $content['contactName']);
        
        $message->setEncoding("UTF-8");
        $message->getHeaders()->get('content-type')->setType('multipart/alternative');
        
        $transport = new SendmailTransport();
        $transport->send($message);       
    
    }
    
    public function sendNotificationMail($content)
    {
       
        $text = new MimePart('Nowa treść dostepna na http://'.$_SERVER['SERVER_NAME'].'/admin');
        $text->type = "text/plain";
       
        $html = new MimePart('
           <div style="font-family:Tahoma;">
                <div>
                    <h3>Formularz kontakowy - Powiadomienia mailowe:</h3> 
                </div>
                <div style="font-size:12px; font-color:#444;">                  
                    <p><b>E-mail: </b>' .$content['notificationEmail']. '</p>   
                    <p><b>Preferowany kraj: </b>' .($content['notificationCountry'] == 'pl' ? 'Polska' : 'Niemcy' ). '</p>
                </div>
                <div style="margin:15px 0; font-color:#444;">
                    <p><b>Preferowane stanowisko: </p>
                    ' .$content['notificationNote']. '
                </div>
                    <hr style="border:0; border-bottom: 1px solid #efefef"/>
                <div>
                    <img src="http://'.$_SERVER['SERVER_NAME'].'/images/logo.png" alt="Winckler Personal"/>                             
                </div>
                <div>
                    <p>
                        ul. Bolesława Chrobrego 23 <br/>
                        02-479 Warszawa<br/>
                        WINCKLER PERSONAL<br/><br/>

                        Tel: +48 (68) 307 00 99<br/>
                        Kom: + 48 531 178 222<br/>
                        Fax: +48 12 444 70 75<br/>
                        Email: info@wincklerpersonal.pl<br/>
                    </p>
                </div>
          </div>           
          
        ');
        
        $html->type = "text/html";

        $body = new MimeMessage();
        $body->setParts(array($text, $html));        
        
        $message = new Message();
        
        //$message->addFrom($content['contactEmail'], $content['contactName'])
        $message->addFrom( 'biuro@wincklerpersonal.pl', 'Strona domowa')
                ->addTo($this->notification)
                ->setSubject('Formularz kontaktowy Winckler Personal - Powiadomienia mailowe');
        
        $message->setBody($body);
        
        $message->addReplyTo($content['notificationEmail']);
        
        $message->setEncoding("UTF-8");
        $message->getHeaders()->get('content-type')->setType('multipart/alternative');
        
        $transport = new SendmailTransport();
        $transport->send($message);       
    
    }
    protected $renderer;
    
    public function sendServiceMail($content)
    {
           
        $text = new MimePart('Problem w V-Walk ... ');
        $text->type = "text/plain";       
     
        $html = new MimePart('
           <div style="font-family:Tahoma;">
                <div>
                    <h3>Serwis Mail - V-Walk</h3> 
                </div>
                <div style="margin:15px 0; font-color:#444;">
                    <p><b>Treść wiadomości: </p>
                    ' .$content['emailDesc']. '
                </div>
                    <hr style="border:0; border-bottom: 1px solid #efefef"/>
                
                '.$this->getFooter().'
          </div>           
          
        ');
        
        $html->type = "text/html";

        $body = new MimeMessage();
        $body->setParts(array($text, $html));  
        
        $message = new Message();
        
        $message->addFrom( 'info@vwalk.pl', 'Strona domowa')
                ->addTo($this->serviceMail)
                ->setSubject($content['emailTitle']);
        
        $message->setBody($body);                
        $message->setEncoding("UTF-8");
        $message->getHeaders()->get('content-type')->setType('multipart/alternative');
        
        
        $transport = new SendmailTransport();
        $transport->send($message);      
    }
    
    public function getFooter() {
        
        $footer = ' <div>
                        <img src="http://'.$_SERVER['SERVER_NAME'].'/mgr/public/images/logoo.png" alt=""/>                             
                    </div>                   
                  '; 
        
        return $footer;        
    }
}