<?php
/*
 * Action to create voucher
 */
add_action('admin_post_register_voucher', 'register_voucher');

function register_voucher()
{
    try {
        global $wpdb;
        $prefix = get_db_prefix();

        $wpdb->insert($prefix.'vouchers', [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'codeprefix' => $_POST['prefix'],
            'deleted' => 0
        ]);

        create_message_response('Voucher criado com sucesso!', 1);
        return header( "Location: admin.php?page=voucher");

    } catch (\Exception $exception) {
        create_message_response('Ocorreu um erro ao criar o voucher!', 2);
        return header( "Location: admin.php?page=voucher");
    }
}

/*
 * Action to update voucher
 */
add_action('admin_post_update_voucher', 'update_voucher');

function update_voucher()
{
    try {
        global $wpdb;
        $prefix = get_db_prefix();

        $wpdb->update(
            get_db_prefix().'vouchers',
            [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'codeprefix' => $_POST['prefix'],
            ],
            ['id' => $_POST['id']]
        );

        create_message_response('Voucher atualizado com sucesso!', 1);
        return header( "Location: admin.php?page=voucher");

    } catch (\Exception $exception) {
        create_message_response('Ocorreu um erro ao atualizar o voucher!', 2);
        return header( "Location: admin.php?page=voucher");
    }
}


/*
 * Ajax to delete voucher
 */
add_action('wp_ajax_delete_voucher', 'delete_voucher');
function delete_voucher()
{
    try {
        global $wpdb;

        $wpdb->update(
            get_db_prefix().'vouchers',
            ['deleted' => 1],
            ['id' => $_POST['id']]
        );

        echo json_encode([
            'message' => 'Voucher deletado com sucesso!',
            'status_code' => 200
        ]);

        die();
    } catch(\Exception $exception) {
        echo json_encode([
            'message' => 'Ocorreu um erro ao deletar o voucher',
            'status_code' => $exception->getCode()
        ]);
        die();
    }
}

/*
 *  Get All Vouchers
 */
function get_vouchers( $num = 25, $all = false, $start = 0 )
{
    global $wpdb;
    $prefix = get_db_prefix();

    $showall = "0";
    if ( $all ) {
        $showall = "1";
    }

    $limit = "limit " . ( int ) $start . "," . ( int ) $num;
    if ( 0 == ( int ) $num ) {
        $limit = "";
    }

    return $wpdb->get_results("select * from " . $prefix . "vouchers where deleted = 0 ".$limit.";");
}

/*
 * Count off Vouchers
 */
function get_vouchers_count()
{
    global $wpdb;
    $prefix = get_db_prefix();
    $sql = "select count(id) from " . $prefix . "vouchers where deleted = 0;";
    return $wpdb->get_var( $sql );
}

/*
 * Get Voucher by ID
 */
function get_voucher($id)
{
    global $wpdb;

    $prefix = get_db_prefix();

    $results  = $wpdb->get_results('select * from '.$prefix.'vouchers where deleted = 0 and id = '.$id);

    if ($results) {
        return $results[0];
    }

    return 0;

}
