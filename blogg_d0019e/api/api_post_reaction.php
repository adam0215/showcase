<?php
require_once '../db/db_query.php';
// Starta session för att komma åt ev. sessionsvariabler
session_start();

function addReaction($post_id, $reaction_type, $reactor_id)
{
    $db_query = new DatabaseQuery;

    // För att användaren ska få ta bort posten måsted deras inloggade användare (id) matcha id:et på författaren för inlägget
    $user_authorized = isset($_SESSION['id']);

    $post_reactions = $db_query->get_all_post_reactions($post_id);
    $user_already_reacted = false;

    foreach ($post_reactions as $reaction) {
        if ($reaction['reactor'] === $reactor_id) $user_already_reacted = true;
    }

    if ($user_already_reacted) {
        // Skicka autentiseringsfel ifall användaren redan har reagerat på inlägget
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        die();
    }

    if (!$user_authorized) {
        // Skicka autentiseringsfel ifall användaren inte äger inlägget
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        die();
    }

    // Om allt ovan är felfritt, ta bort inlägget
    $db_query->add_reaction($reaction_type, $post_id, $reactor_id);

    return true;
}

function deleteReaction($post_id, $reactor_id)
{
    $db_query = new DatabaseQuery;

    $post_reactions = $db_query->get_all_post_reactions($post_id);
    $reaction_id = null;

    // Sök efter om användaren har reagerat på inlägget
    foreach ($post_reactions as $reaction) {
        if ($reaction['reactor'] === $reactor_id) $reaction_id = $reaction['id'];
    }

    if (!$reaction_id) {
        // Skicka autentiseringsfel ifall användaren inte har reagerat på inlägget
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        die();
    }

    // Om allt ovan är felfritt, ta bort inlägget
    $db_query->delete_post_reaction($reaction_id);

    return true;
}

// Om en användare är inloggad
if (isset($_SESSION['id'])) {
    // Data i POST-förfrågan
    $req_data = json_decode(file_get_contents('php://input'), true);
    // Inlägg att göra reaktionsoperationerna  på
    $post_to_react = $req_data['postID'];
    // Reaktionstyp
    $reaction_type = $req_data['type'];
    // Reagerare / Användare som reagerar / Inloggad användare
    $reactor = $_SESSION['id'];
    // Operationstyp / Handling att utföra
    $reaction_action = $req_data['action'];

    // Om alla variabler existerar
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($post_to_react) && isset($reaction_action)) {
        // Om en reaktion ska läggas till
        if (
            $reaction_action === 'add' && isset($reaction_type)
        ) {
            // Lägg till reaktion i databas
            addReaction($post_to_react, $reaction_type, $reactor);
            // Skicka framgångsmeddelande
            echo json_encode(['success' => true, 'postID' => $post_to_react, 'type' => $reaction_type, 'action' => 'add']);
            // Om en reaktion ska tas bort
        } elseif ($reaction_action === 'delete') {
            // Ta bort reaktion från databas
            deleteReaction($post_to_react, $reactor);
            // Skicka framgångsmeddelande
            echo json_encode(['success' => true, 'postID' => $post_to_react, 'type' => $reaction_type, 'action' => 'delete']);
        }
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
