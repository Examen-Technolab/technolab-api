<?php
class Images
{
    private $db;
    private $errHandler;
    public function __construct($db, $errHandler)
    {
        $this->errHandler = $errHandler;
        $this->db = $db;
    }

    private function getDir($mainDir, $dir)
    {
        return __DIR__ . "/../../examen-technolab.ru/images/$mainDir/$dir";
    }

    function postImages()
    {
        $file = file_get_contents('php://input');
        if (isset($_GET['name'], $_GET['dir'], $_GET['mainDir'])) {
            $name = $_GET['name'];
            $dir = $_GET['dir'];
            $mainDir = $_GET['mainDir'];
            $fileDir = $this->getDir($mainDir, $dir);
            $filename = "$name.png";
            if (!file_exists($fileDir)) {
                mkdir($fileDir, 0777, true);
            }
            file_put_contents("$fileDir/$filename", $file);
            echo json_encode(array('message' => 'ok'));
        } else
            $this->errHandler->setError(400, '', 'Переданы некорректные параметры name, dir или mainDir');
    }


    function deleteImages()
    {
        if (isset($_GET['dir'], $_GET['mainDir'])) {
            $dir = $_GET['dir'];
            $mainDir = $_GET['mainDir'];

            $fileDir = $this->getDir($mainDir, $dir);

            if ($imgs = glob($fileDir . "/*")) {
                foreach ($imgs as $img) {
                    unlink($img);
                }
            }

            echo json_encode(['ok' => 'true']);
            die();
        }
        echo json_encode(['ok' => 'false']);
        die();
    }
}
?>