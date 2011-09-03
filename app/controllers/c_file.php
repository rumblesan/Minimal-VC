<?php

class c_file extends Controller
{
    public $filename = '';

    function __construct($args)
    {
        parent::__construct($args);

        $filename = $this->args[0];
        if ($filename == '')
        {
            $filename = 'basic';
        }
        $this->filename = $filename . '.txt';
        $this->filepath = FILES . $this->filename;
    }

    public function _put()
    {
        $data = file_get_contents("php://input");
        if ($data == '')
        {
            echo "must have some content for file\n";
            exit;
        }

        if (file_exists($this->filepath))
        {
            echo $this->filename . " will be updated\n";
        }
        else
        {
            echo $this->filename . " will be saved\n";
        }

        $fp   = fopen(FILES . $this->filename, 'w');
        fwrite($fp, $data);
        fclose($fp);
        echo $this->filename . " was saved\n";
    }

    public function _get()
    {
        echo $this->filepath . "\n";
        if (file_exists($this->filepath))
        {
            echo file_get_contents($this->filepath) . "\n";
        }
        else
        {
            echo $this->filename . " does not exist\n";
        }
    }

    public function _delete()
    {
        if (file_exists($this->filepath))
        {
            unlink($this->filepath);
            echo $this->filename . " was deleted\n";
        }
        else
        {
            echo $this->filename . " does not exist\n";
        }
    }
}

