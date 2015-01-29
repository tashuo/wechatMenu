<?php
    header('Content-Type: application/vnd.ms-excel; charset=utf8');
    header('Content-Disposition: attachment; filename=example.xls');

    readfile('includes/example.xls');
    exit;