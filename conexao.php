<?php

require_once 'config.php';

class Conexao {
    private static $conexao;

    public static function getConexao() {
       
        if (!isset(self::$conexao)) {
            self::$conexao = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            
            if (self::$conexao->connect_error) {
                die("Erro na conexão com o banco de dados: " . self::$conexao->connect_error);
            }

           
            self::$conexao->set_charset("utf8");
        }

        return self::$conexao;
    }
}
?>