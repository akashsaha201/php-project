<?php

function validateName($name) {
    if ($name === "" || $name === null) {
        return "Name is required.";
    }
    if (ctype_digit($name)) {
        return "Product name cannot be only numbers.";
    }
    return null; // no error
}

function validatePrice($price) {
    if ($price === "" || $price === null) {
        return "Price is required.";
    }
    if (!is_numeric($price) || $price <= 0) {
        return "Price must be a valid positive number.";
    }
    return null;
}

function validateEmail($email) {
    if (empty($email)) {
        return "Supplier email is required.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    }
    return null;
}

/**
 * Validate all product fields at once
 */
function validateProduct($data) {
    $errors = [];

    if ($error = validateName($data['name'] ?? "")) {
        $errors['name'] = $error;
    }

    if ($error = validatePrice($data['price'] ?? "")) {
        $errors['price'] = $error;
    }

    if ($error = validateEmail($data['email'] ?? "")) {
        $errors['email'] = $error;
    }

    return $errors;
}
