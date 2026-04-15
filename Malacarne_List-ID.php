<?php
header("Content-Type: application/json");

require 'pro.php';

$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'GET':
        $stmt = $conn->prepare("SELECT * FROM libri");
        $stmt->execute();
        $result = $stmt->get_result();
        $libri = [];
        while ($row = $result->fetch_assoc()) {
            $libri[] = $row;
        }
        echo json_encode($libri);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['id']) && isset($data['titolo']) && isset($data['autore']) && isset($data['anno'])) {
            $stmt = $conn->prepare("INSERT INTO libri (id, titolo, autore, anno) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("issi", $data['id'], $data['titolo'], $data['autore'], $data['anno']);
            if ($stmt->execute()) {
                http_response_code(201);
                $response = [
                    'status'  => 'success',
                    'message' => 'User created successfully',
                    'user'    => $data
                ];
                echo json_encode($response);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Database error']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
        }
        break;

    case 'PUT':
        $data    = json_decode(file_get_contents("php://input"), true);
        $user_id = isset($_GET['id']) ? intval($_GET['id']) : null;

        if ($user_id && isset($data['titolo']) && isset($data['autore']) && isset($data['anno'])) {
            $stmt = $conn->prepare("UPDATE libri SET titolo = ?, autore = ?, anno = ? WHERE id = ?");
            $stmt->bind_param("ssii", $data['titolo'], $data['autore'], $data['anno'], $user_id);
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $response = [
                        'status'  => 'success',
                        'message' => 'User updated successfully',
                        'user'    => $data
                    ];
                    echo json_encode($response);
                } else {
                    http_response_code(404);
                    echo json_encode(['status' => 'error', 'message' => 'User not found']);
                }
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Database error']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
        }
        break;

    case 'DELETE':
        $user_id = isset($_GET['id']) ? intval($_GET['id']) : null;

        if ($user_id) {
            $stmt = $conn->prepare("DELETE FROM libri WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $response = [
                        'status'  => 'success',
                        'message' => 'User deleted successfully'
                    ];
                    echo json_encode($response);
                } else {
                    http_response_code(404);
                    echo json_encode(['status' => 'error', 'message' => 'User not found']);
                }
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Database error']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'User ID is required']);
        }
        break;

    default:
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}
?>
