<?php
class Router
{
    private function getUrl() //возвращает текущий url или пустую строку
    {
        return isset($_GET['url']) ? $_GET['url'] : '';
    }
    function use ($route, $controller) // переход к нужному контроллеру по роуту
    {
        $url = $this->getUrl();
        if ($url == $route) {
            $controller();
            //die();
        }
    }

    function start($method, $route, $controller) //запуск контроллера по соответствующему методу и урлу
    {
        $url = $this->getUrl();
        if ($_SERVER['REQUEST_METHOD'] == $method && $url == $route) {
            $controller();
            die();
        }
    }
}

?>