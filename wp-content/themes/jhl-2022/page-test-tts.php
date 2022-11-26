<?php
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://voicerss-text-to-speech.p.rapidapi.com/?key=b20c5cfdc3mshb600cab056b99f6p1dbf1djsn31da32c63273",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "src=Hello%2C%20world!&hl=en-us&r=0&c=mp3&f=8khz_8bit_mono",
    CURLOPT_HTTPHEADER => [
        "X-RapidAPI-Host: voicerss-text-to-speech.p.rapidapi.com",
        "X-RapidAPI-Key: b20c5cfdc3mshb600cab056b99f6p1dbf1djsn31da32c63273",
        "content-type: application/x-www-form-urlencoded"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    echo $response;
}