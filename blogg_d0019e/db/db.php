<?php
// Hämtar inloggningsuppgifterna
require_once('db_credentials.php');

class Database
{
    // Databasuppkoppling
    protected $connection;

    function __construct()
    {
        // Vid initialisering, koppla upp till databas
        $this->connection = $this->db_connect();
    }

    function __destruct()
    {
        // När inga fler referenser görs till klassen, koppla bort från databas
        $this->db_disconnect();
    }

    /** Kopplar upp oss mot databasen */
    private  function db_connect()
    {
        // Kopplar upp mot databasen
        $db_connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

        // Kollar om något gick fel, i så fall skrivs orsaken ut och skriptet avslutas
        if (mysqli_errno($db_connection)) {
            $msg = "Databasuppkopplingen misslyckades: ";
            $msg .= mysqli_connect_error();
            $msg .= " (" . mysqli_connect_errno() . ")";
            exit($msg);
        }

        // Sätter teckenkodning för kommunikation med databas till UTF-8
        mysqli_set_charset($db_connection, 'utf8mb4');
        return $db_connection;
    }

    /** Koppla ner från databasen */

    public function db_disconnect()
    {
        if (isset($this->connection))
            mysqli_close($this->connection);
    }

    /** Utför en databasfråga (query) */
    protected function db_query($query)
    {
        $result = mysqli_query($this->connection, $query);
        if (!$result)
            echo '<br>Fel i frågan: <strong>\'' . $query . '\'</strong>:<br>' . $this->db_error($this->connection) . '<br>';
        return $result;
    }

    /** Hämtar det senaste felet som upstått i databasen */
    public function db_error($connection)
    {
        if (isset($connection))
            return mysqli_error($connection);
        return mysqli_connect_error();
    }

    public function db_import($filename, $dropOldTables)
    {
        // Om $dropOldTables är TRUE så ska vi ta bort alla gamla tabeller
        if ($dropOldTables) {
            // Ignorera FK begränsningar
            $query = 'SET foreign_key_checks = 0;';
            $result = $this->db_query($query);

            // Börjar med att hämta eventuella tabeller som finns i databasen
            $query = 'SHOW TABLES';
            $result = $this->db_query($query);

            // Om några tabeller hämtats
            if ($result) {
                // Hämta rad för rad ur resultatet
                while ($row = mysqli_fetch_row($result)) {
                    $query = 'DROP TABLE ' . $row[0];
                    //echo $query . '<br>';
                    if ($this->db_query($query))
                        echo 'Tabellen <strong>' . $row[0] . '</strong> togs bort<br>';
                }
            }
            // Sätt på FK begränsningar igen
            $query = 'SET foreign_key_checks = 1;';
            $result = $this->db_query($query);
        }
        $query = '';
        // Läs in filens innehåll
        $lines = file($filename);

        // Hantera en rad i taget
        foreach ($lines as $line) {
            // Gör inget med kommentarer eller tomma rader (gå till nästa rad)
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;

            // Varje rad läggs till i frågan (query)
            $query .= $line;

            // Slutet på frågan är hittad om ett semikolon hittades i slutet av raden
            if (substr(trim($line), -1, 1) == ';') {
                // Kör frågan mot databasen
                if (!$this->db_query($query))
                    echo '<br>Fel i frågan: <strong>\'' . $query . '\'</strong>:<br>' . $this->db_error($this->connection) . '<br>';

                // Töm $query så vi kan starta med nästa fråga
                $query = '';
            }
        }
        echo 'Importeringen är klar!<br>';
    }
}
