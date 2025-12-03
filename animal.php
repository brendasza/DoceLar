<?php
require_once '../CORE/conexao.php';

class AnimalDAO {
    private $conn;
    public function __construct() {
        $this->conn = Conexao::getConexao();
    }

    /**
     * Cadastra um novo animal
     * 
     * @param string $nome 
     * @param string $especie
     * @param string $sexo 
     * @param string $data_nascimento 
     * @param string $porte 
     * @param string $raca 
     * @param string $descricao 
     * @param string $cidade 
     * @param string $estado
     * @param int $id_ong_abrigo 
     * @return bool 
     */
    public function cadastrarAnimal(string $nome, string $especie, string $sexo, string $data_nascimento, string $porte, string $raca, string $descricao, string $cidade, string $estado, int $id_ong_abrigo): bool {
        $stmt = $this->conn->prepare("
            INSERT INTO ANIMAL 
                (nome, especie, sexo, data_nascimento, porte, raca, descricao, cidade, estado, id_ong_abrigo)
            VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        
        $stmt->bind_param("sssssssssi", 
            $nome, $especie, $sexo, $data_nascimento, $porte, $raca, $descricao, $cidade, $estado, $id_ong_abrigo
        );
        
        return $stmt->execute();
    }

    /**
     * Lista dos animais
     * 
     * @param string $status Opcional Filtra por status ('Disponível', 'Reservado', 'Adotado' ou 'todos').
     * @return array array com animais
     */
    public function listarAnimais(string $status = 'Disponível'): array {
        $sql = "SELECT * FROM ANIMAL";
        $params = [];
        $types = "";

        if ($status !== 'todos') {
            $sql .= " WHERE status_adocao = ?";
            $params[] = $status;
            $types = "s";
        }

        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $resultado = $stmt->get_result();
        $animais = [];

        while ($linha = $resultado->fetch_assoc()) {
            $animais[] = $linha;
        }

        return $animais;
    }

    /**
     * procura o animal pelo id
     * @param int $id_animal 
     * @return array|null Retorna se achou o animal
     */
    public function buscarAnimalPorId(int $id_animal): ?array {
        $stmt = $this->conn->prepare("SELECT * FROM ANIMAL WHERE id_animal = ?");
        $stmt->bind_param("i", $id_animal);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    /**
     * Atualiza o status de adoção 
     * 
     * @param int $id_animal
     * @param string $novo_status 
     * @return bool retorna true ou false
     */
    public function atualizarStatusAdocao(int $id_animal, string $novo_status): bool {
        $stmt = $this->conn->prepare("
            UPDATE ANIMAL 
            SET status_adocao = ? 
            WHERE id_animal = ?
        ");
        $stmt->bind_param("si", $novo_status, $id_animal);
        return $stmt->execute();
    }
}
?>