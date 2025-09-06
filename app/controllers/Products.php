<?php

class Products extends Controller
{
    private $productRepo;
    private $digitalRepo;
    private $physicalRepo;
    private $categoryRepo;

    public function __construct()
    {
        if (!isLoggedIn()) redirect('users/login');

        $currentMethod = end(explode('/',$_REQUEST['url']));
        $adminMethods = ['create', 'store', 'edit', 'update', 'destroy'];

        if (!isAdmin() && in_array($currentMethod, $adminMethods)) {
            redirect('products');
        }
    }

    // Show all products
    public function index()
    {
        $this->productRepo = new ProductRepository();

        $products = $this->productRepo->getAll();
        $data = ['products' => $products];
        $this->view('products/index', $data);
    }

    //  Add product page
    public function create() {

        $this->categoryRepo = new CategoryRepository();

        $data = [
            'categories' => $this->categoryRepo->getAll(),
            'errors' => [],
            'form' => []
        ];
        $this->view('products/add', $data);
    }


    // Handle Add product submission
    public function store()
    {
        
        $this->setupRepositories();


        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $type = $_POST['type'] ?? '';

        // Validate common fields
        $errors = $this->validateProduct($_POST);

        // Type-specific validations
        if ($type === 'digital') {
            $errors = array_merge($errors, $this->validateDigitalProduct($_POST));
        } elseif ($type === 'physical') {
            $errors = array_merge($errors, $this->validatePhysicalProduct($_POST));
        } else {
            $errors['type'] = "Invalid product type.";
        }

        if (!empty($errors)) {
            $data = [
                'errors' => $errors,
                'form' => $_POST,
                'categories' => !empty($type) ? $this->categoryRepo->getByType($type) : []
            ];
            $this->view('products/add', $data);
            return;
        }

        // Save product (common part)
        $product = new Product($_POST['name'], $_POST['email'], $_POST['price'], $_POST['quantity'], $_POST['category_id']);
        $productId = $this->productRepo->insert($product);

        if ($type === 'digital') {
            $digital = new DigitalProduct($productId, $_POST['name'], $_POST['email'], $_POST['price'], $_POST['quantity'], $_POST['category_id'], $_POST['file_size'], $_POST['download_link']);
            $this->digitalRepo->insert($digital);
        } elseif ($type === 'physical') {
            $physical = new PhysicalProduct($productId, $_POST['name'], $_POST['email'], $_POST['price'], $_POST['quantity'], $_POST['category_id'], $_POST['weight'], $_POST['shipping_cost']);
            $this->physicalRepo->insert($physical);
        }

        flash('add_success', 'Product added successfully');
        redirect('products');
    }

    // Edit product page
    public function edit($id = null) {

        $this->setupRepositories();

        $product = $this->productRepo->getById($id);
        if (!$product) die("Product not found");

        $type = $this->productRepo->getProductType($id);

        $extra = ($type === 'digital') ? $this->digitalRepo->getById($id) : $this->physicalRepo->getById($id);

        $data = [
            'form' => array_merge((array)$product, (array)$extra),
            'errors' => [],
            'categories' => $this->categoryRepo->getByType($type),
            'id' => $id,
            'type' => $type
        ];
        $this->view('products/edit', $data);

    }

    // Handle edit product
    public function update($id = null)
    {
        
        $this->setupRepositories();

        $product = $this->productRepo->getById($id);
        if (!$product) die("Product not found");

        $type = $this->productRepo->getProductType($id);

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $errors = $this->validateProduct($_POST);

        if ($type === 'digital') {
            $errors = array_merge($errors, $this->validateDigitalProduct($_POST));
        } elseif ($type === 'physical') {
            $errors = array_merge($errors, $this->validatePhysicalProduct($_POST));
        }

        if (!empty($errors)) {
            $data = [
                'errors' => $errors,
                'form' => $_POST,
                'categories' => $this->categoryRepo->getByType($type),
                'id' => $id,
                'type' => $type
            ];
            $this->view('products/edit', $data);
            return;
        }

        // Update product table
        $product->setName($_POST['name']);
        $product->setEmail($_POST['email']);
        $product->setPrice($_POST['price']);
        $product->setQuantity($_POST['quantity']);
        $product->setCategoryId($_POST['category_id']);

        // Update child table
        if ($type === 'digital') {
            $digital = new DigitalProduct($id, $_POST['name'], $_POST['email'], $_POST['price'], $_POST['quantity'], $_POST['category_id'], $_POST['file_size'], $_POST['download_link']);
            $this->digitalRepo->update($digital);
        } elseif ($type === 'physical') {
            $physical = new PhysicalProduct($id, $_POST['name'], $_POST['email'], $_POST['price'], $_POST['quantity'], $_POST['category_id'], $_POST['weight'], $_POST['shipping_cost']);
            $this->physicalRepo->update($physical);
        }

        flash('update_success', 'Product updated successfully');
        redirect('products');
    }

    // Delete product
    public function destroy($id=null)
    {

        $this->productRepo = new ProductRepository();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->productRepo->delete($id);
            flash('delete_success', 'Product deleted successfully');
            redirect('products');
        } else {
            die("Invalid request");
        }
    }

    private function setupRepositories() {
        $this->productRepo = new ProductRepository();
        $this->digitalRepo = new DigitalProductRepository();
        $this->physicalRepo = new PhysicalProductRepository();
        $this->categoryRepo = new CategoryRepository();
    }

    // validate base product inputs
    private function validateProduct( $data) {
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

    // validate digital product inputs
    private function validateDigitalProduct( $data)  {
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

    // validate physical product inputs
    private function validatePhysicalProduct( $data)  {
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
}
