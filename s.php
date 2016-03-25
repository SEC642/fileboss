<?php
/* Receive and store the file from the user */

/**
 * Encrypt given plain text using the key with RC4 algorithm.
 * All parameters and return value are in binary format.
 *
 * @param string key - secret key for encryption
 * @param string pt - plain text to be encrypted
 * @return string
 */
function rc4encrypt($key, $pt) {
    $s = array();
    for ($i=0; $i<256; $i++) {
        $s[$i] = $i;
    }
    $j = 0;
    $x;
    for ($i=0; $i<256; $i++) {
        $j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
        $x = $s[$i];
        $s[$i] = $s[$j];
        $s[$j] = $x;
    }
    $i = 0;
    $j = 0;
    $ct = '';
    $y;
    for ($y=0; $y<strlen($pt); $y++) {
        $i = ($i + 1) % 256;
        $j = ($j + $s[$i]) % 256;
        $x = $s[$i];
        $s[$i] = $s[$j];
        $s[$j] = $x;
        $ct .= $pt[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
    }
    return $ct;
}

/**
 * Decrypt given cipher text using the key with RC4 algorithm.
 * All parameters and return value are in binary format.
 *
 * @param string key - secret key for decryption
 * @param string ct - cipher text to be decrypted
 * @return string
*/
function rc4decrypt($key, $ct) {
    return rc4encrypt($key, $ct);
}


if (!isset($_FILES["ufile"])) {
    header( 'Location: /' ) ;
    exit;
}

echo "<html><head></head><body>";

if ($_FILES["ufile"]["size"] < 200000) {
    if ($_FILES["ufile"]["error"] > 0) {
        echo "Return Code: " . $_FILES["ufile"]["error"] . "<br />";
    } else {

        if (file_exists("upload/" . $_FILES["ufile"]["name"])) {
            echo $_FILES["ufile"]["name"] . " already exists. ";
        } else {
            /* Encrypt the file for storage */

            $fcontent = file_get_contents($_FILES["ufile"]["tmp_name"],false);
            $fecontent = rc4encrypt("password", $fcontent);

            $fp = fopen("upload/".$_FILES["ufile"]["name"],"wb");
            fwrite($fp, $fecontent);
            fclose($fp);

			/* Remove the temporary file */
            unlink($_FILES["ufile"]["tmp_name"]);

            /* echo "Stored in: " . "upload/" . $_FILES["ufile"]["name"] . "<br>"; */
			echo "Awesome, your uploaded file is stored in an encrypted form.  You can retrieve the file at any time by visiting the <a href=\"http://fileboss.sec642.org\">FileBoss</a> page.  See your administrator if you need a copy of the tool to decrypt files.<br>";
        }
    }
} else {
    echo "Invalid file";
}

?>

</body>
</html>
