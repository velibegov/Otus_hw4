<?php

class Server {
    private $address;

        public function __construct($address) {
            $this->address = $address;
            if (file_exists($this->address)) {
                unlink($this->address);
            }
        }
        public function connect() {
            $socket = socket_create(AF_UNIX, SOCK_STREAM,0);

            return $socket;
        }
        public function run() {
            $socket = $this->connect();
            socket_listen($socket);
            do {
                $connection = socket_accept($socket);
                do {
                    $buffer = socket_read($connection, 2048, PHP_BINARY_READ);
                    $buffer = trim($buffer);
                    if ($buffer === 'q') {
                        break;
                    }
                    print sprintf("Client send: %s\n", $buffer);
                    $msg = readline("Write something ");
                    socket_write($connection, $msg, strlen($msg));
                } while (true);
                socket_close($connection);
            } while (true);
            socket_close($socket);
        }
}
