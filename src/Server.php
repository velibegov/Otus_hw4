<?php
namespace Server;
class Server {
    private $socket_address;
    private $config;

        public function __construct($ini_file_path) {
            $this->config = parse_ini_file($ini_file_path);
            $this->socket_address = $this->config['socketfilepath'];
        }
        public function connect() {
            if (file_exists($this->socket_address)) {
                unlink($this->socket_address);
            }
            if (extension_loaded('sockets')) {echo 'sockets ENABLED' . PHP_EOL;}
            if ($socket  = socket_create(AF_UNIX, SOCK_STREAM, 0)) {echo 'Socket CREATED'.PHP_EOL;}
            if(socket_bind($socket, $this->socket_address)) {echo 'Socket BINDED'.PHP_EOL;}
            return $socket;
        }
        public function run($socket) {
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
                    if ($msg === 'q') {
                        break;
                    }
                } while (true);
                socket_close($connection);
                break;
            } while (true);
            socket_close($socket);
        }
}
