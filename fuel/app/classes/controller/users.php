<?php

class Controller_Users extends Controller_Template
{public function before()
    {
    Lang::load(Session::get('lang'));
    return parent::before();
}
    public function action_login()
    {
        $data["subnav"] = array('login' => 'active');
        $auth = Auth::instance();

        $view = View::forge('users/login', $data);
        $form = Form::forge('login');
        $form->add('username', __('Username :'));
        $form->add('password', __('Password :'), array('type' => 'password'));
        $form->add('submit', '', array('type' => 'submit', 'value' => __('Login')));
        if (Input::post()) {
            if ($auth->login(Input::post('username'), Input::post('password'))) {
                Auth::remember_me($auth->get_id());
                Session::set_flash(__('success'), __('loggedsuccess') . $auth->get_screen_name());

                Response::redirect('messages/');

            } else {

                Session::set_flash(__('error'), __('Userpasincorrect'));

            }
        }
        $view->set('reg', $form, false);
        $this->template->title = __('Login');
        $this->template->content = $view;
    }
    public function action_chpass()
    {
        $data["subnav"] = array('Change' => 'active');
        $auth = Auth::instance();
        $view = View::forge('users/chpass', $data);
        $form = Form::forge('chpass');
        $form->add('Opassword', __('Old-password'), array('type' => 'password'))->add_rule(__('required'));
        $form->add('Npassword', __('New-password'), array('type' => 'password'))->add_rule(__('required'));
        $form->add('reNpassword', __('Re-try password'), array('type' => 'password'))->add_rule(__('required'));
        $form->add('submit', '', array('type' => 'submit', 'value' => __('Change')));

        if (Input::post()) {

            if ($auth->login($auth->get('username'), Input::post('Opassword')) and Input::post('Npassword') == Input::post('reNpassword')) {

                $result = $auth->change_password(Input::post('Opassword'), Input::post('Npassword'));
                if ($result) {

                    Session::set_flash(__('success'), __('passsuccechange'));
                    Response::redirect('users/manage');
                } else {
                    Session::set_flash(__('error'), __('passincorect'));
                    Response::redirect('users/login');
                }
            } else {
                Session::set_flash(__('error'), __('passincorect'));
                Response::redirect('users/login');

            }
        }

        $view->set('reg', $form, false);
        $this->template->title = 'Users &raquo; Change password';
        $this->template->content = $view;

    }
    public function action_edit()
    {

        $data["subnav"] = array('manage' => 'active');
        $auth = Auth::instance();
        if (!is_null($auth)) {

            $view = View::forge('users/edit', $data);
            $form = Form::forge('edit');
            $form->add('password', 'password', array('type' => 'password'))->add_rule(__('required'));
            $form->add('email', $auth->get('email'))->add_rule(__('required'));
            $form->add('submit', '', array('type' => 'submit', 'value' => 'submit'));

            if (Input::post()) {$results = DB::select()
                    ->from('users')
                    ->where('username', Input::post('username'))
                    ->execute();
                if (count($results) >= 1) {
                    Session::set_flash(__('error'), __('Usernamerepeated'));
                } else {
                    $result = $auth->update_user(array('email' => Input::post('email'), 'old_password' => Input::post('password')));
                    if ($result) {

                        Session::set_flash(__('success'), __('SuccessgullyChanged'));
                        Response::redirect('users/manage');
                        $this->template->title = __('Manage');
                        $this->template->content = $view;

                    } else {
                        Session::set_flash(__('error'), __('somethingwentwrong'));
                        $this->template->title = __('Manage');
                        $this->template->content = $view;
                    }

                }

                $this->template->title = __('Manage');
                $this->template->content = $view;
            }
            $view->set('reg', $form, false);
            $this->template->title = __('Manage');
            $this->template->content = $view;
        }
    }
    public function action_manage()
    {

        $data["subnav"] = array('manage' => 'active');
        $id = Auth::instance()->get('id');
        //$username=Auth::instance()->get('username');

        if (!is_null($id)) {
            $user = Model_User::find($id);
            $view = View::forge('users/manage', $data);
            $view->set('username', $user->username, false);
            $view->set('email', $user->email, false);
            $view->set('id', $user->id, false);
            $this->template->title = __('Manage');
            $this->template->content = $view;

        } else {
            echo "error";

        }
        $this->template->title = __('Manage');
        $this->template->content = $view;
    }
    public function action_logout()
    {
        $auth = Auth::instance();
        $auth->logout();
        Session::set_flash(__('success'), __('Loggedout'));
        Response::redirect('messages/');
        //$data["subnav"] = array('logout'=> 'active' );
        //$this->template->title = 'Users &raquo; Logout';
        //$this->template->content = View::forge('users/logout', $data);
    }

    public function action_register($fieldset = null, $errors = null)
    {
        $data["subnav"] = array('register' => 'active');
        $auth = Auth::instance();
        $view = View::forge('users/register', $data);

        if (empty($fieldset)) {
            $fieldset = Fieldset::forge('regiser');
            Model_User::populate_register_fieldset($fieldset);
        }

        $view->set('reg', $fieldset->build(), false);

        if ($errors) {
            $view->set_safe('errors', $errors);
        }

        $this->template->title = __('Register');
        $this->template->content = $view;

    }

    public function get_register($fieldset = null, $errors = null)
    {
        $data["subnav"] = array('register' => 'active');
        $auth = Auth::instance();
        $view = View::forge('users/register', $data);

        if (empty($fieldset)) {
            $fieldset = Fieldset::forge('register');
            Model_User::populate_register_fieldset($fieldset);
        }

        $view->set('reg', $fieldset->build(), false);
        if ($errors) {
            $view->set_safe('errors', $errors);
        }

        $this->template->title = __('Register');
        $this->template->content = $view;
    }

    public function post_register()
    {
        $fieldset = Model_User::populate_register_fieldset(Fieldset::forge('register'));
        $fieldset->repopulate();
        $result = Model_User::validate_registration($fieldset, Auth::instance());
        if ($result['e_found']) {
            return $this->get_register($fieldset, $result['errors']);
        }
        Session::set_flash(__('success'), _('created'));
        Auth::remember_me(Auth::get_id());
        Response::redirect('./');
    }

}
