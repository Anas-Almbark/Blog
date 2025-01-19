<?php
$errors = [];
function setError($errorKey, $error)
{
    array_push($error, [$errorKey, $error]);
}

function getAllErrors()
{
    global $errors;
    header('Content-Type: application/json');
    $errors = json_encode($errors);
    return $errors;
}

echo getAllErrors();
