<?php
namespace SiteName\Controller;
use Kernel\MVC\Controller\Controller;
use Twelve\Utils\Utils;
use Twelve\Cms\Media\Model as Model;
use Twelve\Cms\Media\Action as Action;
use Twelve\Http\Request;
class Index extends Controller
{
    public $name = '';

    public function home($name)
    {
        $this->render($this->twig->render('index.html.twig', array('name' => 'Bogdan')));
        $media = new Model\Image("images");
        $del = new Action\Delete($media);
    }
}