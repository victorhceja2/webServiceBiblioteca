<?php
header("Content-Type: application/json");
error_reporting(E_ALL);

$servername = "mibibliotecapersonal-db-1"; // Cambia esto por el nombre del contenedor de la base de datos
$username = "root";
$password = "innovacionMovil2024*";
$dbname = "biblioteca";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $method = $_SERVER['REQUEST_METHOD'];
    $input = json_decode(file_get_contents('php://input'), true);

    switch ($method) {
        case 'GET':
            if (isset($_GET['id'])) {
                $stmt = $conn->prepare("SELECT * FROM libros WHERE id = :id");
                $stmt->bindParam(':id', $_GET['id']);
                $stmt->execute();
                $libro = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($libro);
            } else {
                $stmt = $conn->prepare("SELECT * FROM libros");
                $stmt->execute();
                $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($libros);
            }
            break;

        case 'POST':
            $stmt = $conn->prepare("INSERT INTO libros (titulo, autor, genero, anio) VALUES (:titulo, :autor, :genero, :anio)");
            $stmt->bindParam(':titulo', $input['titulo']);
            $stmt->bindParam(':autor', $input['autor']);
            $stmt->bindParam(':genero', $input['genero']);
            $stmt->bindParam(':anio', $input['anio']);
            $stmt->execute();
            echo json_encode(['id' => $conn->lastInsertId()]);
            break;

        case 'PUT':
            $stmt = $conn->prepare("UPDATE libros SET titulo = :titulo, autor = :autor, genero = :genero, anio = :anio WHERE id = :id");
            $stmt->bindParam(':id', $input['id']);
            $stmt->bindParam(':titulo', $input['titulo']);
            $stmt->bindParam(':autor', $input['autor']);
            $stmt->bindParam(':genero', $input['genero']);
            $stmt->bindParam(':anio', $input['anio']);
            $stmt->execute();
            echo json_encode(['status' => 'success']);
            break;

        case 'DELETE':
            $stmt = $conn->prepare("DELETE FROM libros WHERE id = :id");
            $stmt->bindParam(':id', $input['id']);
            $stmt->execute();
            echo json_encode(['status' => 'success']);
            break;

        default:
            echo json_encode(['error' => 'Invalid request method']);
            break;
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
}
?>