<?php
require_once __DIR__ . '/../config/helpers.php';
if (session_status() == PHP_SESSION_NONE) session_start();
session_unset();
session_destroy();
redirect(base_url('admin/login'));
?>
