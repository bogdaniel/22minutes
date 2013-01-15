<?php
namespace SiteName\Controller;
use Kernel\MVC\Controller\Controller;
use Twelve\Utils\Utils;
use Twelve\Http as Response;
class Index extends Controller
{
    public $name = '';

    public function home($name)
    {
        $response = new Response\Response;
        $this->render($this->twig->render('index.html.twig', array('name' => 'Bogdan')));
    }
}
