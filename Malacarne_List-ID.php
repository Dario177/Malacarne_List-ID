<?php
header("Content-Type: application/json");

$libri = [
    [
        'id' => 1,
        'titolo' => "Fahrenheit 501"
    ],
    [
        'id' => 2,
        'titolo' => "peter pan"
    ],
    [
        'id' => 3,
        'titolo' => "harry potter e la pietra filosofale"
    ]
];

$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'GET':
        echo json_encode($libri);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['id']) && isset($data['titolo'])) {
            $response = [
                'status' => 'success',
                'message' => 'User created successfully',
                'user' => $data
            ];
            echo json_encode($response);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['id']) && isset($data['titolo'])) {
            $response = [
                'status' => 'success',
                'message' => 'User updated successfully',
                'user' => $data
            ];
            echo json_encode($response);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        $user_id = isset($data['id']) ? $data['id'] : null;

        if ($user_id) {
            $response = [
                'status' => 'success',
                'message' => 'User deleted successfully'
            ];
            echo json_encode($response);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'User ID is required']);
        }
        break;

    default:
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}
?>