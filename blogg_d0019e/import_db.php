<?php
require_once "db/db.php";

$db = new Database();

$db->db_import('db/cms.sql', true);
