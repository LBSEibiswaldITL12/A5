<?php

/**
 * RegisterController
 * Register new user
 */
class RegisterController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class. The parent::__construct thing is necessary to
     * put checkAuthentication in here to make an entire controller only usable for logged-in users (for sure not
     * needed in the RegisterController).
     */
    public function __construct()
    {
        Auth::checkAdminAuthentication();
        parent::__construct();
    }

    /**
     * Register page
     * Show the register form, but redirect to main-page if user is already logged-in
     */
    public function index()
    {
        $this->View->render('register/index');
    }

    /**
     * Register page action
     * POST-request after form submit
     */
    public function register_action()
    {
        RegistrationModel::registerNewUser();

        Redirect::to('register/index');
    }
}
