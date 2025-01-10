<?php
$errors = [];
function getAllErrors()
{
    if (isset($_GET['errors'])) {
        $errors = json_decode($_GET['errors'], true);
        return $errors;
    }
    return [];
}

header('Content-Type: application/json');
echo json_encode(['errors' => getAllErrors()]);
exit();
