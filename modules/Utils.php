<?php
/*
 * Voucher Page URL
 * */
function voucher_page_url()
{
    $pageURL = 'http';
    if ( isset( $_SERVER["HTTPS"] ) && "on" == $_SERVER["HTTPS"] ) {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ( "80" != $_SERVER["SERVER_PORT"] ) {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

/*
 * Get DB Prefix
 */
function get_db_prefix()
{
    global $wpdb;

    if ( isset( $wpdb->base_prefix ) ) {
        return $wpdb->base_prefix;
    }

    return $wpdb->prefix;
}

/*
 * Create Message
 */
function create_message_response($message, $type)
{
    $class = $type == 1 ? "notice-success" : "notice-error";

    $response = "<div class=\"notice $class is-dismissible \" style=\"margin-bottom: 5vh;\">
        <p>$message</p>
    </div>";

    $_SESSION['message'] = $response;
}

/*
 * Redirect
 */
function redirect($url)
{
    echo '<script type="text/javascript">
           window.location = "'.$url.'"
         </script>';
}

/*
 * Response JSON to ajax requests
 */
function response($array = [], $status_code)
{
    http_response_code($status_code);
    echo json_encode($array);
    die();
}

/*
 * Validation email
 */
function validation_email_domain($email)
{
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        return false;
    }

    $dominio=explode('@', $email);

    if (!checkdnsrr($dominio[1],'A')) {
        return false;
    }

    return true;
}

/*
 * Random String
 */
function generateRandomString($length = 10)
{
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}
