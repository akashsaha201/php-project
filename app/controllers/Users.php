<?php

require_once APPROOT . '/helpers/validation_helper.php';

class Users extends Controller
{
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository(new Database);
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $errors = validateUserRegistration($_POST, $this->userRepository);

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

        } else {
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
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $errors = validateUserLogin($_POST);

            if (!empty($errors)) {
                $data = array_merge($_POST, $errors);
                $this->view('users/login', $data);
                return;
            }

            $user = $this->userRepository->findByEmail($_POST['email']);
            if ($user && password_verify($_POST['password'], $user->getPassword())) {
                $this->createUserSession($user);
            } else {
                $data = array_merge($_POST, ['password_err' => 'Invalid credentials']);
                $this->view('users/login', $data);
            }

        } else {
            $data = ['email' => '', 'password' => '', 'email_err' => '', 'password_err' => ''];
            $this->view('users/login', $data);
        }
    }

    private function createUserSession(User $user)
    {
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['email'] = $user->getEmail();
        $_SESSION['role'] = $user->getRole();
        redirect('products');
    }

    public function logout()
    {
        unset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['email'], $_SESSION['role']);
        session_destroy();
        redirect('users/login');
    }
}
