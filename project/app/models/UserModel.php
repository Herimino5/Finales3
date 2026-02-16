<?php
namespace app\models;

use flight;

class UserModel{
    public function getUsers(){
        $users = [
			[ 'id' => 1, 'name' => 'Bob Jones', 'email' => 'bob@example.com' ],
			[ 'id' => 2, 'name' => 'Bob Smith', 'email' => 'bsmith@example.com' ],
			[ 'id' => 3, 'name' => 'Suzy Johnson', 'email' => 'suzy@example.com' ],
			[ 'id' => 4, 'name' => 'koto Johnson', 'email' => 'koto@example.com' ],
			[ 'id' => 5, 'name' => 'Toto Johnson', 'email' => 'Toto@example.com' ],
		];
        return $users;
    }
    public function getUser($id){
        $users = [
			[ 'id' => 1, 'name' => 'Bob Jones', 'email' => 'bob@example.com' ],
			[ 'id' => 2, 'name' => 'Bob Smith', 'email' => 'bsmith@example.com' ],
			[ 'id' => 3, 'name' => 'Suzy Johnson', 'email' => 'suzy@example.com' ],
			[ 'id' => 4, 'name' => 'koto Johnson', 'email' => 'koto@example.com' ],
			[ 'id' => 5, 'name' => 'Toto Johnson', 'email' => 'Toto@example.com' ],
		];
        return $users[$id];
    }
}