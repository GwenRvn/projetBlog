<?php
namespace App\config;
use App\src\controller\FrontController;
use App\src\controller\ErrorController;
use Exception;

class Router
{
    private $frontController;
    private $errorController;
    public function __construct()
    {
        $this->frontController = new FrontController();
        $this->errorController = new ErrorController();
    }
    public function run()
    {
        try {
            if (isset($_GET['route'])) {
                if ($_GET['route'] === 'article') {
                    $this->frontController->article($_GET['articleId']);
                } elseif ($_GET['route'] === 'addComment') {
                    if (!empty($_POST['pseudo']) && !empty($_POST['content'])) {
                        $this->frontController->addComment($_GET['articleId'], $_POST['pseudo'], $_POST['content']);
                    }else{
                        $this->errorController->errorNotFound();
                    }
                } else {
                    $this->errorController->errorNotFound();
                }
            } else {
                $this->frontController->home();
            }
        } catch (Exception $e) {
            $this->errorController->errorServer();
        }
    }
}