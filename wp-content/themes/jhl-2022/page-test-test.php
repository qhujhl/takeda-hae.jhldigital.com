<?php
/*
update_user_meta(2, 'first_name', 'Qiang');
update_user_meta(2, 'last_name', 'Hu');
update_user_meta(2, 'nick_name', 'Qiang');

print_r(get_user_meta(2));

print_r(get_user_meta(2, 'first_name'));
echo get_user_meta(2, 'last_name', true);
*/
//echo get_theme_file_uri('assets/favicon/apple-touch-icon.png');
//echo "<br>";
//var_dump(wp_get_upload_dir());

$jhl_admin_approve_email = get_field('jhl_admin_approve_email', 'options');
$jhl_arr = ju_textarea_to_array($jhl_admin_approve_email, true);

foreach ($jhl_arr as $key=>$val)
    echo $key.":".$val."\n";

print("<pre>".print_r($jhl_arr, true)."</pre>");