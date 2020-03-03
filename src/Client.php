<?php
namespace Client;
class Client {
    private $socket_address;
    private $config;

    public function __construct($ini_file_path) {
        $this->config = parse_ini_file($ini_file_path);
        $this->socket_address = $this->config['socketfilepath'];
    }
    public function run() {
        $socket = socket_create(AF_UNIX, SOCK_STREAM,0);
        socket_connect($socket, $this->socket_address);
        do {
            $msg = readline("Write something ");
            $msg = trim($msg);
            socket_write($socket, $msg, strlen($msg));
            if ($msg === 'q') {
                break;
            }
            $answer = socket_read($socket, 2048, PHP_BINARY_READ);
            $answer = trim($answer);
            if ($answer === 'q') {
                break;
            }
            print sprintf("Server send: %s\n", $answer);
        } while (true);
        socket_close($socket);
    }
}