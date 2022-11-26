<?php
if (is_user_logged_in() && current_user_can('administrator')){
    $args = [
        'role' => 'subscriber',
        'orderby' => 'ID',
        'order' => 'ASC',
        'fields' => [
            'ID',
            'user_email',
            'user_registered'
        ]
    ];
    $users = get_users($args);
    $aResults = [];
    foreach ($users as $user){
        $aResults[] = get_object_vars($user);
    }

    foreach ($aResults as $key => $a){
        $aResults[$key]['session_tokens'] = get_user_meta($a['ID'],'session_tokens',true);
    }
    $aFinals = array();
    foreach($aResults as $oRow) {
        if (is_array($oRow['session_tokens'])){
            $session_tokens_arr = array_values($oRow['session_tokens']);
            if ($session_tokens_arr[0]['login']){
                $sLastLoginTimeStamp = date("Y-m-d", $session_tokens_arr[0]['login']);
                $inactive = round((time() - strtotime($sLastLoginTimeStamp))/3600/24);
            }
        }else{
            $sLastLoginTimeStamp = '';
            $inactive = round((time() - strtotime($oRow['user_registered']))/3600/24);;
        }


        $oRow['user_registered'] = date('Y-m-d',strtotime($oRow['user_registered']));
        $aFinals[] = [$oRow['ID'], $oRow['user_email'], $oRow['user_registered'], $sLastLoginTimeStamp, $inactive];

    }
    $table_html = '<table border="1" style="width: 80%; margin: 0 auto;"><tr><td>ID</td><td> Email</td><td>Registration</td><td>Lastaccess</td><td>Inactive</td></tr>';
    foreach ($aFinals as $sRow) {
        $table_html .= '<tr>
        <td>'.$sRow[0].'</td>
        <td>'.$sRow[1].'</td>
        <td>'.$sRow[2].'</td>
        <td>'.$sRow[3].'</td>
        <td>'.$sRow[4].'</td>
      </tr>';
    }
    $table_html .= '</table>';
    echo $table_html;
}else{
    echo '<h1>No permission to access.</h1>';exit();
}