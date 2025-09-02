<?php
function validateProductCommon( $data) {
    $errors = [];

    // Name
    $name = trim($data['name'] ?? '');
    if (empty($name)) {
        $errors['name'] = 'Please enter name';
    } elseif (strlen($name) < 3) {
        $errors['name'] = "Product name must be at least 3 characters.";
    }

    // Email
    $email = trim($data['email'] ?? '');
    if (empty($email)) {
        $errors['email'] = 'Please enter email';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    // Price
    $price = $data['price'] ?? '';
    if ($price === '') {
        $errors['price'] = 'Please enter price';
    }elseif (!is_numeric($price) || $price <= 0) {
        $errors['price'] = "Price must be a positive number.";
    }
    
    // Quantity
    $quantity = $data['quantity'] ?? '';
    if ($quantity === '') {
        $errors['quantity'] = 'Please enter quantity';
    } elseif (!is_numeric($quantity) || $quantity < 0) {
        $errors['quantity'] = "Quantity cannot be negative.";
    }

    // Category
    $category_id = $data['category_id'] ?? '';
    if (empty($category_id)) {
        $errors['category_id'] = "Please select a category.";
    }

    return $errors;
}

function validateDigitalProduct( $data)  {
    $errors = [];

    $fileSize = $data['file_size'] ?? '';
    $downloadLink = trim($data['download_link'] ?? '');
    if (empty($fileSize)) {
        $errors['file_size'] = 'Please enter file size';
    }elseif (!is_numeric($fileSize) || $fileSize <= 0) {
        $errors['file_size'] = "File size must be a positive number.";
    }

    if (empty($downloadLink)) {
        $errors['download_link'] = 'Please enter download link';
    }elseif (!filter_var($downloadLink, FILTER_VALIDATE_URL)) {
        $errors['download_link'] = "Download link must be a valid URL.";
    }

    return $errors;
}

function validatePhysicalProduct( $data)  {
    $errors = [];

    $weight = $data['weight'] ?? '';
    $shippingCost = $data['shipping_cost'] ?? '';

    if (empty($weight)) {
        $errors['weight'] = 'Please enter weight';
    }elseif (!is_numeric($weight) || $weight <= 0) {
        $errors['weight'] = "Weight must be a positive number.";
    }
    if (empty($shippingCost)) {
        $errors['shipping_cost'] = 'Please enter shipping cost';
    }elseif (!is_numeric($shippingCost) || $shippingCost < 0) {
        $errors['shipping_cost'] = "Shipping cost must be 0 or greater.";
    }

    return $errors;
}

function validateUserRegistration( $data, UserRepository $userRepo)  {
    $errors = [];

    if (empty($data['username'])) {
        $errors['username_err'] = 'Please enter name';
    }

    if (empty($data['email'])) {
        $errors['email_err'] = 'Please enter email';
    } elseif ($userRepo->findByEmail($data['email'])) {
        $errors['email_err'] = 'Email already registered';
    }

    if (empty($data['password'])) {
        $errors['password_err'] = 'Please enter password';
    } elseif (strlen($data['password']) < 6) {
        $errors['password_err'] = 'Password must be at least 6 characters';
    }

    if (empty($data['confirm_password'])) {
        $errors['confirm_password_err'] = 'Please confirm password';
    } elseif ($data['password'] != $data['confirm_password']) {
        $errors['confirm_password_err'] = 'Passwords do not match';
    }

    return $errors;
}

function validateUserLogin( $data)  {
    $errors = [];

    if (empty($data['email'])) {
        $errors['email_err'] = 'Please enter email';
    }
    if (empty($data['password'])) {
        $errors['password_err'] = 'Please enter password';
    }

    return $errors;
}
