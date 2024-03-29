<?php
namespace Core;
use DateTime;
class Log
{

    private $path = '/logs/';
    private $formate_date = 'Y-m-d h:i:s A';
    public function __construct()
    {
        date_default_timezone_set('America/Bogota');
        $this->path = dirname(__FILE__) . $this->path;
    }
    public function write($mensaje = false)
    {
        $date = new DateTime();
        $log = $this->path . $date->format('Y-m-d') . ".log";

        if (is_dir($this->path)) {
            if (!file_exists($log)) {
                $fh = fopen($log, 'a+') or die("!Error Fatal!");
                $logcontent = "Hora : {$date->format($this->formate_date)}\r\n{$mensaje}";
                fwrite($fh, $logcontent);
                fclose($fh);
            } else {
                $this->edit($log, $date, $mensaje);
            }
        } else {
            if (mkdir($this->path, 0777) === true) {
                $this->write($mensaje);
            }
        }
    }
    private function edit($log, $date, $mensaje)
    {
        $logcontent = "Hora : {$date->format($this->formate_date)}\r\n{$mensaje}\r\n\r\n";
        $logcontent = $logcontent . file_get_contents($log);
        file_put_contents($log, $logcontent);
    }
}
