<?php
require_once 'db.php';

class DatabaseQuery extends Database
{
    // Skapa inlägg
    public function create_post($title, $content, $author, $image_name)
    {
        // Lägg manuellt in ett unikt ID då MySQL-versionen kanske inte har stöd för UUID som standardvärde, åtminstone inte på min utvecklingsserver...
        // Random_bytes istället för uniqid därför att den genererar en 36 kryptografiskt säkra slumpmässiga bytes vilket matchar längden på SQL:s inbyggda UUID-funktion som jag inte kan använda.
        $post_uuid = $this->generate_uuid();
        $title = $this->db_escape($title);
        $content = $this->db_escape($content);
        $author = $this->db_escape($author);
        $image_name = $this->db_escape($image_name);

        $this->db_query("INSERT INTO post (id, title, author, content, image) VALUES ('$post_uuid', '$title', '$author', '$content', '$image_name')");
        return $post_uuid;
    }

    // Uppdatera information kopplat till ett inlägg, såsom titel, innehåll och bild
    public function update_post($id, $title, $content, $image_name)
    {
        $title = $this->db_escape($title);
        $content = $this->db_escape($content);

        if (!$image_name) {
            // Uppdatera allt förutom bild i post
            $result = $this->db_query("UPDATE post SET title='$title', content='$content' WHERE id='$id'");
            return $result;
        }
        // Uppdatera allt i post
        $image_name = $this->db_escape($image_name);
        $result = $this->db_query("UPDATE post SET title='$title', content='$content', image='$image_name' WHERE id='$id'");

        return $result;
    }

    // Uppdatera en bilds beskrivning
    public function update_image_description($id, $new_desc)
    {
        $id = $this->db_escape($id);
        $new_desc = $this->db_escape($new_desc);

        $result = $this->db_query("UPDATE image SET description='$new_desc' WHERE id='$id'");

        return $result;
    }

    // Hämta inlägg från databas
    public function get_post($id)
    {
        $id = $this->db_escape($id);
        $result = $this->db_query("SELECT post.id, post.author, post.title, post.content, post.created_at, image.filename as 'image_filename', image.description as 'image_description', image.id as 'image_id' FROM post JOIN image ON post.image = image.id WHERE post.id='$id'");

        if ($result->num_rows === 0) return null;
        return mysqli_fetch_assoc($result);
    }

    // Ta bort inlägg från databas
    public function delete_post($id)
    {
        $id = $this->db_escape($id);
        $result = $this->db_query("DELETE FROM post WHERE id='$id'");

        return $result;
    }

    // Hämta id:t till författaren för ett visst inlägg
    public function get_post_author($id)
    {
        $id = $this->db_escape($id);
        $result = $this->db_query("SELECT author FROM post WHERE id='$id'");

        if ($result->num_rows === 0) return null;
        return mysqli_fetch_all($result, MYSQLI_ASSOC)[0]['author'];
    }

    // Hämta id:t till bilden för ett visst inlägg
    public function get_post_image_filename($id)
    {
        $id = $this->db_escape($id);
        $result = $this->db_query("SELECT image.filename as 'image_filename' FROM post JOIN image ON post.image = image.id WHERE post.id='$id'");

        if ($result->num_rows === 0) return null;
        return mysqli_fetch_all($result, MYSQLI_ASSOC)[0]['image_filename'];
    }

    // Hämta de senaste inläggen i databasen, sorterad nyast först, standardgräns: 2 inlägg
    public function get_latest_posts($limit = 2)
    {
        $limit = $this->db_escape($limit);
        $result = $this->db_query("SELECT post.id, post.author, post.title, post.content, post.created_at, image.filename as 'image_filename', image.description as 'image_description' FROM post JOIN image ON post.image = image.id ORDER BY post.created_at DESC LIMIT $limit");

        if ($result->num_rows === 0) return null;
        return $result;
    }

    // Hämta senaste från kreatör, standardgräns: 2st inlägg
    public function get_latest_from_creator($id, $limit = 2)
    {
        $limit = $this->db_escape($limit);
        $result = $this->db_query("SELECT * FROM post WHERE author='$id' ORDER BY created_at DESC LIMIT $limit");

        if ($result->num_rows === 0) return null;
        return $result;
    }

    // Hämta lista över alla inlägg från en kreatör, sorterad nyast först, standardgräns: 1 000 000 000st inlägg
    public function get_all_posts_from_creator($id, $limit = 0)
    {
        // Om begränsningen är satt till noll, returnerna orimligt stort nummer för att göra begränsningen till i princip obefintlig / stänga av begränsning
        $limit = $limit === 0 ? 1000000000 : $this->db_escape($limit);
        $result = $this->db_query("SELECT * FROM post WHERE author='$id' ORDER BY created_at DESC LIMIT $limit");

        if ($result->num_rows === 0) return null;
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // Lägg till kreatör i databas
    public function add_creator($firstname, $lastname, $username, $password, $profile_pic)
    {
        // Lägg manuellt in ett unikt ID då MySQL-versionen kanske inte har stöd för UUID som standardvärde, åtminstone inte på min utvecklingsserver...
        $user_uuid = $this->generate_uuid();
        // Escapea all användarinput för att motverka SQL injection
        $user_firstname = $this->db_escape($firstname);
        $user_lastname = $this->db_escape($lastname);
        $user_username = $this->db_escape($username);
        $user_password = $this->db_escape($password);

        $this->db_query("INSERT INTO creator (id, firstname, lastname, username, password, profile_picture) VALUES ('$user_uuid', '$user_firstname', '$user_lastname', '$user_username', '$user_password', '$profile_pic')");
    }

    // Hämta data kopplat till enskild kreatör
    public function get_creator($id = null, $username = null)
    {
        if ($id === null && $username === null) return false;

        if ($id) {
            $id = $this->db_escape($id);
            $result = $this->db_query("SELECT * FROM creator WHERE id='$id'");
        } else if ($username) {
            $username = $this->db_escape($username);
            $result = $this->db_query("SELECT * FROM creator WHERE username='$username'");
        }

        if ($result->num_rows === 0) {
            return false;
        } else return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // Hämta lista med kreatörer, sorterad nyast först
    public function get_creator_list($limit)
    {
        $limit = $this->db_escape($limit);
        $result = $this->db_query("SELECT creator.id, creator.firstname, creator.lastname, creator.username, creator.biography, creator.created_at, image.filename as 'image_filename', image.description as 'image_description' FROM creator JOIN image ON creator.profile_picture = image.id ORDER BY created_at DESC LIMIT $limit;");

        if ($result->num_rows === 0) return null;
        return $result;
    }

    // Hämta en användares biografi
    public function get_creator_bio($id)
    {
        $id = $this->db_escape($id);
        $result = $this->db_query("SELECT biography FROM creator WHERE id='$id'");

        if ($result->num_rows === 0) return null;
        return mysqli_fetch_all($result, MYSQLI_ASSOC)[0]['biography'];
    }

    // Uppdatera en användares biografi
    public function update_creator_bio($id, $new_bio)
    {
        $id = $this->db_escape($id);
        $new_bio = $this->db_escape($new_bio);

        $result = $this->db_query("UPDATE creator SET biography = '$new_bio' WHERE id = '$id'");

        return $result;
    }

    // Lägg till bildinformation i databas
    public function add_image($filename, $description)
    {
        // Lägg manuellt in ett unikt ID då MySQL-versionen kanske inte har stöd för UUID som standardvärde, åtminstone inte på min utvecklingsserver...
        $image_uuid = $this->generate_uuid();
        // Escapea ev. användarinput för att motverka SQL injection
        $image_filename = $this->db_escape($filename);

        $image_description = $this->db_escape($description);

        $this->db_query("INSERT INTO image (id, filename, description) VALUES ('$image_uuid', '$image_filename', '$image_description')");

        return $image_uuid;
    }

    // Hämta endast filnamn kopplat till en bild
    public function get_image_filename($id)
    {
        $result =  $this->db_query("SELECT filename FROM image WHERE id='$id'");

        if ($result->num_rows === 0) return null;

        return mysqli_fetch_all($result, MYSQLI_ASSOC)[0]['filename'];
    }

    // Hämta all data relaterat till en bild såsom id, filnamn och beskrivning
    public function get_image_data($id)
    {
        $result =  $this->db_query("SELECT * FROM image WHERE id='$id'");

        if ($result->num_rows === 0) return null;

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // Lägg till en reaktion på ett inlägg
    public function add_reaction($type, $post_id, $reactor_id)
    {
        // Lägg manuellt in ett unikt ID då MySQL-versionen kanske inte har stöd för UUID som standardvärde, åtminstone inte på min utvecklingsserver...
        $reaction_uuid = $this->generate_uuid();

        $this->db_query("INSERT INTO post_reaction (id, post, type, reactor) VALUES ('$reaction_uuid', '$post_id', '$type', '$reactor_id')");

        return $reaction_uuid;
    }

    // Hämta ett inläggs reaktioner
    public function get_all_post_reactions($post_id)
    {
        $result =  $this->db_query("SELECT id, type, reactor FROM post_reaction WHERE post='$post_id'");

        if ($result->num_rows === 0) return [];

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // Ta bort reaktion på inlägg
    public function delete_post_reaction($reaction_id)
    {
        $id = $this->db_escape($reaction_id);
        $result = $this->db_query("DELETE FROM post_reaction WHERE id='$id'");

        return $result;
    }

    /**
     * Denna funktion anropar mysqli_real_escape_string
     * Används på all dynamisk data som ska skickas till databasen
     * @param $connection
     * @param $str
     * @return string
     */
    private function db_escape($str)
    {
        return mysqli_real_escape_string($this->connection, $str);
    }

    private function generate_uuid($length = 36)
    {
        $byte2hex_length = floor($length / 2);
        return bin2hex(random_bytes($byte2hex_length));
    }
}
