<?php

function validate_not_empty( $val ){
    return !empty($val);
}

function validate_is_email( $email ){
    return is_email( $email );
}

function validate_is_email_unique ( $email ) {
    return !email_exists( $email );
}