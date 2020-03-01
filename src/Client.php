<?php


class Client {
    private $address;
    private $socket;

    public function __construct($address) {
        $this->address = $address;
        $this->socket = socket_create(AF_UNIX, SOCK_STREAM,0);
    }
    public function run() {
        socket_connect($this->socket, $this->address);
        do {
            $msg = readline("Write something ");
            $msg = trim($msg);
            socket_write($this->socket, $msg, strlen($msg));
            if ($msg === 'q') {
                break;
            }
            $answer = socket_read($this->socket, 2048, PHP_BINARY_READ);
            $answer = trim($answer);
            if ($answer === 'q') {
                break;
            }
            print sprintf("Server send: %s\n", $answer);
        } while (true);

        socket_close($this->socket);
    }
}