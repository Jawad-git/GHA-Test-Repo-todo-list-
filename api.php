<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'] ?? '/';

// Simple in-memory storage (in production, use a database)
$todos = [];

// Load from session
session_start();
if (isset($_SESSION['todos'])) {
    $todos = $_SESSION['todos'];
}

function saveTodos($todos) {
    $_SESSION['todos'] = $todos;
}

function generateId() {
    return microtime(true) * 10000;
}

switch ($method) {
    case 'GET':
        echo json_encode($todos);
        break;
        
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['text']) && !empty($data['text'])) {
            $newTodo = [
                'id' => generateId(),
                'text' => $data['text'],
                'completed' => false
            ];
            $todos[] = $newTodo;
            saveTodos($todos);
            echo json_encode($newTodo);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Text is required']);
        }
        break;
        
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? null;
        
        if ($id !== null) {
            foreach ($todos as &$todo) {
                if ($todo['id'] === $id) {
                    if (isset($data['completed'])) {
                        $todo['completed'] = $data['completed'];
                    }
                    if (isset($data['text'])) {
                        $todo['text'] = $data['text'];
                    }
                    saveTodos($todos);
                    echo json_encode($todo);
                    exit;
                }
            }
            http_response_code(404);
            echo json_encode(['error' => 'Todo not found']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID is required']);
        }
        break;
        
    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? null;
        
        if ($id !== null) {
            $todos = array_filter($todos, function($todo) use ($id) {
                return $todo['id'] !== $id;
            });
            $todos = array_values($todos);
            saveTodos($todos);
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID is required']);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
?>