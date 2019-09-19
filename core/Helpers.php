<?php

namespace Core;
class Helpers
{
    public static function unicode_urldecode($url)
    {
        preg_match_all('/%u([[:alnum:]]{4})/', $url, $a);

        foreach ($a[1] as $uniord) {
            $dec = hexdec($uniord);
            $utf = '';

            if ($dec < 128) {
                $utf = chr($dec);
            } else if ($dec < 2048) {
                $utf = chr(192 + (($dec - ($dec % 64)) / 64));
                $utf .= chr(128 + ($dec % 64));
            } else {
                $utf = chr(224 + (($dec - ($dec % 4096)) / 4096));
                $utf .= chr(128 + ((($dec % 4096) - ($dec % 64)) / 64));
                $utf .= chr(128 + ($dec % 64));
            }

            $url = str_replace('%u' . $uniord, $utf, $url);
        }

        return urldecode($url);
    }

    public static function error($numero = false, $texto = false, $exepcion = false)
    {
        $ddf = fopen('error.log', 'a');
        fwrite($ddf, "[" . date("r") . "] Error $numero: $texto *** $exepcion\r\n");
        fclose($ddf);
    }

    public static function print_html($input, $collapse = false)
    {
        $recursive = function ($data, $level = 0) use (&$recursive, $collapse) {
            global $argv;

            $isTerminal = isset($argv);

            if (!$isTerminal && $level == 0 && !defined("DUMP_DEBUG_SCRIPT")) {
                define("DUMP_DEBUG_SCRIPT", true);

                echo '<script language="Javascript">function toggleDisplay(id) {';
                echo 'var state = document.getElementById("container"+id).style.display;';
                echo 'document.getElementById("container"+id).style.display = state == "inline" ? "none" : "inline";';
                echo 'document.getElementById("plus"+id).style.display = state == "inline" ? "inline" : "none";';
                echo '}</script>' . "\n";
            }

            $type = !is_string($data) && is_callable($data) ? "Callable" : ucfirst(gettype($data));
            $type_data = null;
            $type_color = null;
            $type_length = null;

            switch ($type) {
                case "String":
                    $type_color = "green";
                    $type_length = strlen($data);
                    $type_data = "\"" . htmlentities($data) . "\"";
                    break;

                case "Double":
                case "Float":
                    $type = "Float";
                    $type_color = "#0099c5";
                    $type_length = strlen($data);
                    $type_data = htmlentities($data);
                    break;

                case "Integer":
                    $type_color = "red";
                    $type_length = strlen($data);
                    $type_data = htmlentities($data);
                    break;

                case "Boolean":
                    $type_color = "#92008d";
                    $type_length = strlen($data);
                    $type_data = $data ? "TRUE" : "FALSE";
                    break;

                case "NULL":
                    $type_length = 0;
                    break;

                case "Array":
                    $type_length = count($data);
            }

            if (in_array($type, array("Object", "Array"))) {
                $notEmpty = false;

                foreach ($data as $key => $value) {
                    if (!$notEmpty) {
                        $notEmpty = true;

                        if ($isTerminal) {
                            echo $type . ($type_length !== null ? "(" . $type_length . ")" : "") . "\n";

                        } else {
                            $id = substr(md5(rand() . ":" . $key . ":" . $level), 0, 8);

                            echo "<a href=\"javascript:toggleDisplay('" . $id . "');\" style=\"text-decoration:none\">";
                            echo "<span style='color:#666666'>" . $type . ($type_length !== null ? "(" . $type_length . ")" : "") . "</span>";
                            echo "</a>";
                            echo "<span id=\"plus" . $id . "\" style=\"display: " . ($collapse ? "inline" : "none") . ";\">&nbsp;&#10549;</span>";
                            echo "<div id=\"container" . $id . "\" style=\"display: " . ($collapse ? "" : "inline") . ";\">";
                            echo "<br />";
                        }

                        for ($i = 0; $i <= $level; $i++) {
                            echo $isTerminal ? "|    " : "<span style='color:black'>|</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                        }

                        echo $isTerminal ? "\n" : "<br />";
                    }

                    for ($i = 0; $i <= $level; $i++) {
                        echo $isTerminal ? "|    " : "<span style='color:black'>|</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    }

                    echo $isTerminal ? "[" . $key . "] => " : "<span style='color:black'>[" . $key . "]&nbsp;=>&nbsp;</span>";

                    call_user_func($recursive, $value, $level + 1);
                }

                if ($notEmpty) {
                    for ($i = 0; $i <= $level; $i++) {
                        echo $isTerminal ? "|    " : "<span style='color:black'>|</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    }

                    if (!$isTerminal) {
                        echo "</div>";
                    }

                } else {
                    echo $isTerminal ?
                        $type . ($type_length !== null ? "(" . $type_length . ")" : "") . "  " :
                        "<span style='color:#666666'>" . $type . ($type_length !== null ? "(" . $type_length . ")" : "") . "</span>&nbsp;&nbsp;";
                }

            } else {
                echo $isTerminal ?
                    $type . ($type_length !== null ? "(" . $type_length . ")" : "") . "  " :
                    "<span style='color:#666666'>" . $type . ($type_length !== null ? "(" . $type_length . ")" : "") . "</span>&nbsp;&nbsp;";

                if ($type_data != null) {
                    echo $isTerminal ? $type_data : "<span style='color:" . $type_color . "'>" . $type_data . "</span>";
                }
            }

            echo $isTerminal ? "\n" : "<br />";
        };

        call_user_func($recursive, $input);
    }

    public static function encriptar_string($email = false)
    {
        $partes = str_split(trim($email));
        $nuevo = '';
        foreach ($partes as $valor) {
            $nuevo .= '&#' . ord($valor) . ';';
        }
        return $nuevo;
    }

    public static function limpia_string($cadena, $search = ' ', $replace = '')
    {
        $cadena = str_replace($search, $replace, $cadena);
        return $cadena;
    }

    /*
     * function to encrypt
     * @param string $data
     * @param string $key
     */
    public static function encrypt($data = false, $key = false)
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, "aes-256-cbc", $key, 0, $iv);
        // return the encrypted string with $iv joined
        return base64_encode($encrypted . "::" . $iv);
    }

    /*
     * function to decrypt
     * @param string $data
     * @param string $key
     */
    public static function decrypt($data = false, $key = false)
    {
        list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $key, 0, $iv);
    }

    public static function base64ToImage($imageData, $route = false)
    {
        list($type, $imageData) = explode(';', $imageData);
        list(, $extension) = explode('/', $type);
        list(, $imageData) = explode(',', $imageData);
        $fileName = uniqid() . '.' . $extension;
        $imageData = base64_decode($imageData);
        file_put_contents('uploads/' . $route . '/' . $fileName, $imageData);
        return $fileName;
    }

    /**/
    public static function jsonencode($array = [], $options = JSON_UNESCAPED_UNICODE, $header = "Content-type: application/json; charset=utf-8")
    {
        header($header);
        return json_encode($array, $options);
    }
}