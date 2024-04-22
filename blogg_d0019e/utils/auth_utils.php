<?php
class UserHandler
{
    private $db_query;

    function __construct()
    {
        $this->db_query = new DatabaseQuery;
    }

    // Publik funktion för användning i alla delar av skriptet
    public function user_exists($username)
    {
        // Om databasen hittar en användare
        if ($this->db_query->get_creator(null, $username) !== false) {
            return ['status' => true, 'message' => 'user-found'];
        }
        // Om användarnamnet inte hittas i databasen...
        return ['status' => false, 'message' => 'user-not-found'];
    }

    // Publik funktion för användning i alla delar av skriptet
    public function verify_user($username, $password)
    {
        // Hämtar användare
        $user = $this->get_user($username);

        /*
        Jag väljer aktivt att inte berätta för den som försöker logga in 
        ifall användaren finns eller inte på grund av integritetsskäl. Om användaren
        vet att det finns ett konto med användarnamnet kan den förutsätta att just lösenordet är fel
        och därefter försöka bryta sig in med hjälp av det. Ifall den inte vet ifall ens användaren finns
        skapas åtminstone ett litet extra hinder som ger de användare som har ett konto lite mer integritet.
        */

        // Om användaren inte finns
        if ($user === null) return ['status' => false, 'message' => 'user-not-found'];
        // Om användare finns, verifiera den och skicka med resultat
        $passwordVerificationStatus = password_verify($password, $user['password']);
        return ['status' => $passwordVerificationStatus, 'message' => ($passwordVerificationStatus ? 'user-verified' : 'user-not-verified'), 'user_data' => $user];
    }

    // Privat funktion, bara för intern användning i klassens andra metoder
    private function get_user($username)
    {
        $user = $this->db_query->get_creator(null, $username);
        if ($user !== false) {
            return $user[0];
        } else return null;
    }

    public function add_user($firstname, $lastname, $username, $password)
    {
        // Om användaren redan finns, stanna här och returnera false
        if ($this->user_exists($username)['status']) {
            return ['status' => false, 'message' => 'user-exists'];
        }
        // Kryptera lösenord
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $profile_pic_file_name = upload_image() ?? '';
        // Lägg till bilddata i "image"-tabellen
        $image_db_id = $this->db_query->add_image($profile_pic_file_name, 'Användares profilbild');
        // Lägg till användare i databas
        $this->db_query->add_creator($firstname, $lastname, $username, $hashedPassword, $image_db_id);
    }
}

function initiate_session($sessionVariables)
{
    // Starta sessionen
    session_start();
    // Lägg in sessionsvariablerna som är i parametrarna för funktionen
    foreach ($sessionVariables as $key => $val) {
        $_SESSION[$key] = $val;
    }
}

function generate_error($relatedField)
{
    global $inputError;

    // Om det finns ett lösenordsfel och relaterat fält är satt till "password" i parametrarna skicka tillbaka HTML-element för fel
    if ($inputError['passwordError'] !== '' && $relatedField === 'password') {
        return "<span class='input-error-msg alerting-text'>{$inputError['passwordError']}</span>";
    }

    // Om det finns ett användarnamnsfel och relaterat fält är satt till "username" i parametrarna skicka tillbaka HTML-element för fel
    if ($inputError['usernameError'] !== '' && $relatedField === 'username') {
        return "<span class='input-error-msg alerting-text'>{$inputError['usernameError']}</span>";
    }

    // Annars skicka tillbaka tom textsträng
    return '';
}
