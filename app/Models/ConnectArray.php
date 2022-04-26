<?php

namespace App\Models;

use Core\DataBase;

class ConnectArray
{
    protected $data = [];
    protected $build = null;

    public function palabras(array $pal)
    {
        foreach ($pal as $data) {
            $this->data[] = $data;

        }
        return $this;

    }

    public function link($caracter)
    {
        foreach ($this->data as $data) {
            $this->build .= $caracter . $data;

        }
        return $this;
    }

    public function get()
    {
        return $this->build;
    }
}