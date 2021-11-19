<?php

//
// Written by Solar Designer <solar at openwall.com> in 2004-2006 and placed in
// the public domain.  Revised in subsequent years, still public domain.
//
// There's absolutely no warranty.
//
// Please be sure to update the Version line if you edit this file in any way.
// It is suggested that you leave the main version number intact, but indicate
// your project name (after the slash) and add your own revision information.
//
// Please do not change the "private" password hashing method implemented in
// here, thereby making your hashes incompatible.  However, if you must, please
// change the hash type identifier (the "$P$") to something different.
//
// Obviously, since this code is in the public domain, the above are not
// requirements (there can be none), but merely suggestions.
//

/**
 * Portable PHP password hashing framework.
 *
 * @version 0.3 / WordPress
 *
 * @see    http://www.openwall.com/phpass/
 * @since   2.5.0
 */
class MY_Portable_hash
{
    public static $crypt_names = [
        '$P$' => 'WordPress', '$1$' => 'MD5',
        '$2a$' => 'Blowfish', '$2y$' => 'Laravel',
        '$5$' => 'SHA-256', '$6$' => 'SHA-512',
    ];
    protected $id = '$P$';
    protected $subid = 'B';
    protected $enc_times = 16;
    protected $random_state;
    protected $itoa64;
    protected $dx_major_salt = '';

    /**
     * PHP5 constructor.
     */
    public function __construct()
    {
        $this->itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $this->random_state = microtime().uniqid(random_int(0, getrandmax()), true);
    }

    public function get_random_bytes($count)
    {
        $output = '';
        if (@is_readable('/dev/urandom')
            && ($fh = @fopen('/dev/urandom', 'rb'))
        ) {
            $output = fread($fh, $count);
            fclose($fh);
        }

        if (strlen($output) < $count) {
            $output = '';
            for ($i = 0; $i < $count; $i += 16) {
                $this->random_state = md5(microtime().$this->random_state);
                $output .= pack('H*', md5($this->random_state));
            }
            $output = substr($output, 0, $count);
        }

        return $output;
    }

    public function encode64($input, $count)
    {
        $output = '';
        $i = 0;
        do {
            $value = ord($input[$i++]);
            $output .= $this->itoa64[$value & 0x3F];
            if ($i < $count) {
                $value |= ord($input[$i]) << 8;
            }
            $output .= $this->itoa64[($value >> 6) & 0x3F];
            if ($i++ >= $count) {
                break;
            }
            if ($i < $count) {
                $value |= ord($input[$i]) << 16;
            }
            $output .= $this->itoa64[($value >> 12) & 0x3F];
            if ($i++ >= $count) {
                break;
            }
            $output .= $this->itoa64[($value >> 18) & 0x3F];
        } while ($i < $count);

        return $output;
    }

    public function crypt_private($password, $salt, $subid)
    {
        if (8 !== strlen($salt)) {
            return '*0';
        }
        $count_log2 = strpos($this->itoa64, $subid);
        if (false !== $count_log2 && $count_log2 >= 3) {
            $count = 1 << $count_log2;
        } else {
            return '*0';
        }

        // We're kind of forced to use MD5 here since it's the only
        // cryptographic primitive available in all versions of PHP
        // currently in use.  To implement our own low-level crypto
        // in PHP would result in much worse performance and
        // consequently in lower iteration counts and hashes that are
        // quicker to crack (by non-PHP code).

        $hash = md5($salt.$password, true);
        do {
            $hash = md5($hash.$password, true);
        } while (--$count);

        $output = $this->id.$subid.$salt;
        $output .= $this->encode64($hash, $this->enc_times);

        return $output;
    }

    /*
     * Function: _encode
     * Modified for DX_Auth
     * Original Author: FreakAuth_light 1.1
     */
    public function dx_encode($password)
    {
        $majorsalt = $this->dx_major_salt;
        // if PHP5
        if (function_exists('str_split')) {
            $_pass = str_split($password);
        }
        // encrypts every single letter of the password
        foreach ($_pass as $_hashpass) {
            $majorsalt .= md5($_hashpass);
        }
        // encrypts the string combinations of every single encrypted letter
        // and finally returns the encrypted password
        return md5($majorsalt);
    }

    public function hash_password($password)
    {
        $random = $this->get_random_bytes(6);
        $salt = $this->encode64($random, 6);
        $hash = $this->crypt_private($password, $salt, $this->subid);
        if (34 === strlen($hash)) {
            return $hash;
        }
    }

    public function check_password($password, $stored_hash)
    {
        $crypt_id = substr($stored_hash, 0, 3);
        if ('$' !== $crypt_id[2]) {
            $crypt_id .= $stored_hash[3];
        }
        if (! isset(self::$crypt_names[$crypt_id])) {
            return;
        }
        $crypt_name = strtolower(self::$crypt_names[$crypt_id]);
        if ('wordpress' === $crypt_name) {
            $subid = $stored_hash[3];
            $salt = substr($stored_hash, 4, 8);
            $hash = $this->crypt_private($password, $salt, $subid);

            return $hash === $stored_hash;
        }
        //$password = $this->dx_encode($password);
        return crypt($password, $stored_hash) === $stored_hash;
    }
}
