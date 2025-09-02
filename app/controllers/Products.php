<?php

require_once APPROOT . '/helpers/validation_helper.php';

class Products extends Controller
{
    private $productRepo;
    private $digitalRepo;
    private $physicalRepo;
    private $categoryRepo;

    public function __construct()
    {
        $db = new Database;
        $this->productRepo = new ProductRepository($db);
        $this->digitalRepo = new DigitalProductRepository($db);
        $this->physicalRepo = new PhysicalProductRepository($db);
        $this->categoryRepo = new CategoryRepository($db);
    }

    // Show all products
    public function index()
    {
        if (!isLoggedIn()) redirect('users/login');
        $products = $this->productRepo->getAll();
        $data = ['products' => $products];
        $this->view('products/index', $data);
    }

    // Add product
    public function add()
    {
        if (!isLoggedIn()) redirect('users/login');
        elseif(!isAdmin()) redirect('products');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $type = $_POST['type'] ?? '';

            // Validate common fields
            $errors = validateProductCommon($_POST);

            // Type-specific validations
            if ($type === 'digital') {
                $errors = array_merge($errors, validateDigitalProduct($_POST));
            } elseif ($type === 'physical') {
                $errors = array_merge($errors, validatePhysicalProduct($_POST));
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
            $product = new Product($_POST['name'], $_POST['email'], $_POST['price'], $_POST['category_id']);
            $productId = $this->productRepo->insert($product);

            if ($type === 'digital') {
                $digital = new DigitalProduct($productId, $_POST['name'], $_POST['email'], $_POST['price'], $_POST['category_id'], $_POST['file_size'], $_POST['download_link']);
                $this->digitalRepo->insert($digital);
            } elseif ($type === 'physical') {
                $physical = new PhysicalProduct($productId, $_POST['name'], $_POST['email'], $_POST['price'], $_POST['category_id'], $_POST['weight'], $_POST['shipping_cost']);
                $this->physicalRepo->insert($physical);
            }

            flash('add_success', 'Product added successfully');
            redirect('products');

        } else {
            $data = [
                'categories' => $this->categoryRepo->getAll(),
                'errors' => [],
                'form' => []
            ];
            $this->view('products/add', $data);
        }
    }

    // Edit product
    public function edit($id=null)
    {
         if (!isLoggedIn()) redirect('users/login');
        elseif(!isAdmin()) redirect('products');

        $product = $this->productRepo->getById($id);
        if (!$product) die("Product not found");

        $type = $this->productRepo->getProductType($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $errors = validateProductCommon($_POST);

            if ($type === 'digital') {
                $errors = array_merge($errors, validateDigitalProduct($_POST));
            } elseif ($type === 'physical') {
                $errors = array_merge($errors, validatePhysicalProduct($_POST));
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
            $product->setCategoryId($_POST['category_id']);
            $this->productRepo->update($product);

            // Update child table
            if ($type === 'digital') {
                $digital = new DigitalProduct($id, $_POST['name'], $_POST['email'], $_POST['price'], $_POST['category_id'], $_POST['file_size'], $_POST['download_link']);
                $this->digitalRepo->update($digital);
            } elseif ($type === 'physical') {
                $physical = new PhysicalProduct($id, $_POST['name'], $_POST['email'], $_POST['price'], $_POST['category_id'], $_POST['weight'], $_POST['shipping_cost']);
                $this->physicalRepo->update($physical);
            }

            flash('update_success', 'Product updated successfully');
            redirect('products');

        } else {
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
    }

    // Delete product
    public function delete($id=null)
    {
         if (!isLoggedIn()) redirect('users/login');
        elseif(!isAdmin()) redirect('products');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->productRepo->delete($id);
            flash('delete_success', 'Product deleted successfully');
            redirect('products');
        } else {
            die("Invalid request");
        }
    }
}
