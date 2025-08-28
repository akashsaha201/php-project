<?php
/**
 * Validation functions for product forms
 * Covers both common fields and type-specific fields
 */

// ---------------------------
// Common Field Validators
// ---------------------------

/**
 * Validate Product Name
 */
function validateName($name) {
    if ($name === "" || $name === null) {
        return "Name is required.";
    }
    if (ctype_digit($name)) {
        return "Product name cannot be only numbers.";
    }
    return null;
}

/**
 * Validate Product Price
 */
function validatePrice($price) {
    if ($price === "" || $price === null) {
        return "Price is required.";
    }
    if (!is_numeric($price) || $price <= 0) {
        return "Price must be a valid positive number.";
    }
    return null;
}

/**
 * Validate Supplier Email
 */
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
 * Validate Product Type
 */
function validateType($type) {
    if ($type === "" || $type === null) {
        return "Product type is required.";
    }
    if (!in_array($type, ["physical", "digital"])) {
        return "Invalid product type.";
    }
    return null;
}

// ---------------------------
// Physical Product Validators
// ---------------------------

/**
 * Validate Weight
 */
function validateWeight($weight) {
    if ($weight === "" || $weight === null) {
        return "Weight is required for physical products.";
    }
    if (!is_numeric($weight) || $weight <= 0) {
        return "Weight must be a positive number.";
    }
    return null;
}

/**
 * Validate Shipping Cost
 */
function validateShippingCost($cost) {
    if ($cost === "" || $cost === null) {
        return "Shipping cost is required for physical products.";
    }
    if (!is_numeric($cost) || $cost < 0) {
        return "Shipping cost must be a valid non-negative number.";
    }
    return null;
}

// ---------------------------
// Digital Product Validators
// ---------------------------

/**
 * Validate Download Link
 */
function validateDownloadLink($link) {
    if ($link === "" || $link === null) {
        return "Download link is required for digital products.";
    }
    if (!filter_var($link, FILTER_VALIDATE_URL)) {
        return "Download link must be a valid URL.";
    }
    return null;
}

/**
 * Validate File Size
 */
function validateFileSize($size) {
    if ($size === "" || $size === null) {
        return "File size is required for digital products.";
    }
    if (!is_numeric($size) || $size <= 0) {
        return "File size must be a positive number.";
    }
    return null;
}

// ---------------------------
// Main Validation Function
// ---------------------------

/**
 * Validate Product Data (Common + Type Specific)
 */
function validateProduct($data) {
    $errors = [];

    // Common fields
    if ($error = validateName($data['name'] ?? "")) {
        $errors['name'] = $error;
    }
    if ($error = validatePrice($data['price'] ?? "")) {
        $errors['price'] = $error;
    }
    if ($error = validateEmail($data['email'] ?? "")) {
        $errors['email'] = $error;
    }
    if ($error = validateType($data['type'] ?? "")) {
        $errors['type'] = $error;
    }

    // Conditional fields (based on product type)
    $type = $data['type'] ?? "";

    if ($type === "physical") {
        if ($error = validateWeight($data['weight'] ?? "")) {
            $errors['weight'] = $error;
        }
        if ($error = validateShippingCost($data['shipping_cost'] ?? "")) {
            $errors['shipping_cost'] = $error;
        }
    } elseif ($type === "digital") {
        if ($error = validateDownloadLink($data['download_link'] ?? "")) {
            $errors['download_link'] = $error;
        }
        if ($error = validateFileSize($data['file_size'] ?? "")) {
            $errors['file_size'] = $error;
        }
    }

    return $errors;
}
