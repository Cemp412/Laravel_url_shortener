<?php 
use Illuminate\Support\Facades\Hash;

if(!function_exists('hashWithPepper')) {
    /**
     * Combines password with pepper and hashes it.
     */
    function hashWithPepper($password) {
        $pepper = config('app.pepper');
        return Hash::make($password. trim($pepper));
    }
}

if(!function_exists('checkWithPepper')) {
    /**
     * Verify plain password + pepper against stored hash.
     */
    function checkWithPepper($password, $hashedPassword){
        $pepper = config('app.pepper');
        return Hash::check($password . trim($pepper), $hashedPassword);
    }
    
}

?>