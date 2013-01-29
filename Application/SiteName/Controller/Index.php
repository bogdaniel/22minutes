<?php
namespace SiteName\Controller;
use Kernel\MVC\Controller\Controller;
class Index extends Controller
{
    public $name = '';

    public function home()
    {
        $this->render($this->twig->render('index.html.twig'));
    }
}
