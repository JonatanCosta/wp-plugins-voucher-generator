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
            'generates_per_day' => $_POST['generates'],
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
                'generates_per_day' => $_POST['generates']
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
 * Ajax to disable voucher
 */
add_action('wp_ajax_disable_voucher', 'disable_voucher');
function disable_voucher()
{
    try {
        global $wpdb;

        $wpdb->update(
            get_db_prefix().'vouchers',
            ['active' => 0],
            [ 'id' => $_POST['id']]
        );

        echo json_encode([
            'message' => 'Voucher desativado com sucesso!',
            'status_code' => 200
        ]);

        die();
    } catch(\Exception $exception) {
        echo json_encode([
            'message' => 'Ocorreu um erro ao desativar o voucher',
            'status_code' => $exception->getCode()
        ]);
        die();
    }
}

/*
 * Ajax to active voucher
 */
add_action('wp_ajax_active_voucher', 'active_voucher');
function active_voucher()
{
    try {
        global $wpdb;

        disable_all_vouchers();

        $wpdb->update(
            get_db_prefix().'vouchers',
            ['active' => 1],
            [ 'id' => $_POST['id']]
        );

        echo json_encode([
            'message' => 'Voucher ativado com sucesso!',
            'status_code' => 200
        ]);

        die();
    } catch(\Exception $exception) {
        echo json_encode([
            'message' => 'Ocorreu um erro ao ativar o voucher',
            'status_code' => $exception->getCode()
        ]);
        die();
    }
}

/*
 * Disable all vouchers
 */
function disable_all_vouchers()
{
    $allVouchers = get_vouchers(25, true);
    global $wpdb;

    foreach ($allVouchers as $voucher) {
        if ($voucher->active) {
            $wpdb->update(
                get_db_prefix().'vouchers',
                ['active' => 0],
                [ 'id' => $voucher->id]
            );
        }
    }
}



/*
 *  Get All Vouchers
 */
function get_vouchers( $num = 25, $all = false, $start = 0 )
{
    global $wpdb;
    $prefix = get_db_prefix();

    $limit = "limit " . ( int ) $start . "," . ( int ) $num;
    if ( 0 == ( int ) $num || $all == true) {
        $limit = "";
    }

    return $wpdb->get_results("select * from " . $prefix . "vouchers where deleted = 0 ORDER BY active DESC ".$limit.";");
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
