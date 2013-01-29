<?php
namespace Kernel\MVC\Controller;
class Controller
{
    public
        $loader,
        $twig;
    public function __construct()
    {
        $this->loader = new \Twig_Loader_Filesystem('Application/SiteName/View');
        $this->twig = new \Twig_Environment($this->loader, array(
            'cache' => 'Application\SiteName\Cache',
            'autoescape' => true,
            'strict_variables' => true,
            'debug' => 'true'));
    }
    public function render($name)
    {
        echo $name;
    }
}
