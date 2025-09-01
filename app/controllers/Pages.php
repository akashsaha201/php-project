<?php
class Pages extends Controller {
    public function __construct() {
        
    }

    // Landing page
    public function index() {
        if(isLoggedIn()) {
            redirect('products');
        }
        $data = [
            'title' => 'Welcome to My App'
        ];

        $this->view('pages/index', $data);
    }

    // About page
    public function about() {
        $data = ['title' => 'About Us'];
        $this->view('pages/about', $data);
    }
}
