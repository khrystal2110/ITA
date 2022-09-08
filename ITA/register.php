<?php
require_once 'core/init.php';

//var_dump(Token::check(Input::get('token')));

if(Input::exists()) {
    if(Token::check(Input::get('token'))){

        //echo 'I have been run';

        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array(
                'required' => true,
                'min' => 2,
                'max' => 20,
                'unique' => 'users'
            ),
            'password' => array(
                'required' => true,
                'min' => 6
            ),
            'password_again' => array(
                'required' => true,
                'matches' => 'password'
            ),
            'name' => array(
                'required' => true,
                'min' => 2,
                'max' => 50
            ),
        ));

        if($validation->passed()) {
            // register user
            //echo 'Passed';
            /*Session::flash('success', 'You registered successfully!');
            header('Location: index.php');
            */
            //header('Location: index.php');
            $user = new User();

            //echo $salt = Hash::salt(32);
            //die();
            $salt = Hash::salt(10);

            try {
                $user->create(array(
                   'username' => Input::get('username'),
                   'password' => Hash::make(Input::get('password'), $salt), 
                   'salt' => $salt, 
                   'name' => Input::get('name'), 
                   'joined' => date('Y-m-d H:i:s'), 
                   'group' => 1,  
                ));

                Session::flash('home', 'You have been registered and now log in!');
                //header('Location: index.php');
                Redirect::to('index.php');

            } catch(Exception $e) {
                die($e->getMessage());
            }
        } else {
            // output errors
            //print_r($validation->errors());
            foreach($validation->errors() as $error) {
                echo $error, '<br>'; 
            }  
        }
    }
}

?>
<form action="" method="post">
    <div class="field">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="<?php echo escape(Input::get('username')); ?>" autocomplete="off">

    </div>

    <div class="field">
        <label for="password">Choose a password</label>
        <input type="password" name="password" id="password">
    </div>

    <div class="field">
        <label for="password_again">Enter your password again</label>
        <input type="password" name="password_again" id="password_again">
    </div>

    <div class="field">
        <label for="name">Your name</label>
        <input type="text" name="name" value="<?php echo escape(Input::get('name')); ?>" id="name">
    </div>

    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input type="submit" value="Register">
</form>