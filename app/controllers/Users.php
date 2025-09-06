<?php

class Users extends Controller
{
    private $userRepository;

    public function __construct()
    {
        $currentMethod = end(explode('/',$_REQUEST['url']));
        $publicMethods = ['create', 'store', 'showLoginForm', 'authenticate'];

        if (isLoggedIn() && in_array($currentMethod, $publicMethods)) {
            redirect('products');
        }
    }
    
    // Show registration page
    public function create() {
        $data = [
            'username' => '',
            'email' => '',
            'password' => '',
            'confirm_password' => '',
            'username_err' => '',
            'email_err' => '',
            'password_err' => '',
            'confirm_password_err' => ''
        ];
        $this->view('users/register', $data);
    }

    // Handle register submission
    public function store()
    {
        $this->userRepository = new UserRepository();
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $errors = $this->validateUserRegistration($_POST);

        if (!empty($errors)) {
            $data = array_merge($_POST, $errors);
            $this->view('users/register', $data);
            return;
        }

        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $user = new User($_POST['username'], $_POST['email'], $hashedPassword);

        if ($this->userRepository->save($user)) {
            flash('register_success', 'You are registered and can log in');
            redirect('users/login');
        } else {
            die('Something went wrong');
        }
    }
    
    // Show login page
    public function showLoginForm() {
        $data = ['email' => '', 'password' => '', 'email_err' => '', 'password_err' => ''];
        $this->view('users/login', $data);
    }

    // Handle login submission
    public function authenticate()
    {
        $this->userRepository = new UserRepository();
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $errors = $this->validateUserLogin($_POST);

        if (!empty($errors)) {
            $data = array_merge($_POST, $errors);
            $this->view('users/login', $data);
            return;
        }

        $user = $this->userRepository->findByEmail($_POST['email']);
        if ($user && password_verify($_POST['password'], $user->getPassword())) {
            $this->createSession($user);
        } else {
            $data = array_merge($_POST, ['password_err' => 'Invalid credentials']);
            $this->view('users/login', $data);
        }
    }

    // Logout user
    public function destroySession()
    {
        unset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['email'], $_SESSION['role']);
        session_destroy();
        redirect('users/showLoginForm');
    }
    
    // Create user session
    private function createSession(User $user)
    {
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['email'] = $user->getEmail();
        $_SESSION['role'] = $user->getRole();
        redirect('products');
    }

    // Validate user registration inputs
    private function validateUserRegistration( $data)  {
        $errors = [];

        if (empty($data['username'])) {
            $errors['username_err'] = 'Please enter name';
        }

        if (empty($data['email'])) {
            $errors['email_err'] = 'Please enter email';
        } elseif ($this->userRepository->findByEmail($data['email'])) {
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

    // Validate user login inputs
    private function validateUserLogin( $data)  {
        $errors = [];

        if (empty($data['email'])) {
            $errors['email_err'] = 'Please enter email';
        }
        if (empty($data['password'])) {
            $errors['password_err'] = 'Please enter password';
        }

        return $errors;
    }
}
