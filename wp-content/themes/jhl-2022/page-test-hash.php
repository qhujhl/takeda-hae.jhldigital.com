<?php
$data = "qhu1@jhldigital.com";
echo md5($data);
echo "<br>";

// for a password
$hash = password_hash($data, PASSWORD_BCRYPT); // Compliant

echo $hash;
echo "<br>";

// other context
$hash = hash("sha256", $data);
echo $hash;
echo "<br>";

echo wp_generate_uuid4();