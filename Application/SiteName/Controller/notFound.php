<?php
namespace SiteName\Controller;
use Kernel\MVC\Controller\Controller;
class NotFound extends Controller
{
    public function error()
    {
        $this->render($this->twig->render('Error/404.html.twig'));
    }
}
