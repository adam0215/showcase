<?php
require_once '../db/db_query.php';
// Starta session för att komma åt ev. sessionsvariabler
session_start();

function deletePost($post_id)
{
    $db_query = new DatabaseQuery;

    $actual_post_author = $db_query->get_post_author($post_id) ?? null;

    // För att användaren ska få ta bort posten måsted deras inloggade användare (id) matcha id:et på författaren för inlägget
    $user_authorized = $actual_post_author === $_SESSION['id'];

    if (!isset($actual_post_author)) {
        // Skicka fel vid ev. fel i SQL-funktionen
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        die();
    }

    if (!$user_authorized) {
        // Skicka autentiseringsfel ifall användaren inte äger inlägget
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        die();
    }

    // Hämta filnamnet för inläggets bild
    $post_img_filename = $db_query->get_post_image_filename($post_id);
    // Ta bort bild
    unlink("../uploads/$post_img_filename");

    // Om allt ovan är felfritt, ta bort inlägget
    $db_query->delete_post($post_id);

    return true;
}

// Om en användare är inloggad
if (isset($_SESSION['id'])) {
    // Data i POST-förfrågan
    $req_data = json_decode(file_get_contents('php://input'), true);
    // Inlägg att ta bort
    $post_to_delete = $req_data['postID'];

    // Om ett inlägg att tas bort har specificerats i förfrågan
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($post_to_delete)) {
        // Ta bort inlägg från databas
        deletePost($post_to_delete);
        // Skicka framgångsmeddelande
        echo json_encode(['success' => true, 'postID' => $post_to_delete]);
    } else {
        // Skicka fel vid fel i förfrågan
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
    }
} else {
    // Skicka autentiseringsfel ifall ingen session med id är igång
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
}
