<?php

/*
 *          List of Actions
 * ==================================
 *                                  |
 * #01 - Create Voucher             |
 * #02 - Update Voucher             |
 * #03 - Delete Voucher             |
 * #04 - Disable Voucher            |
 * #05 - Active Voucher             |
 * #06 - Disable All Vouchers       |
 * #07 - Get All Vouchers           |
 * #08 - Count off Vouchers         |
 * #09 - Get Voucher by ID          |
 *                                  |
 * ==================================
 */

/*
 * Action: #01
 * Description: Create voucher
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
 * Action: 02
 * Description: Update voucher
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
 * Action: 03
 * Description: Ajax to delete voucher
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

        return response([
            'message' => 'Voucher deletado com sucesso!',
            'status_code' => 200
        ], 200);
    } catch(\Exception $exception) {
        return response([
            'message' => 'Ocorreu um erro ao deletar o voucher',
            'status_code' => $exception->getCode()
        ], $exception->getCode());
    }
}

/*
 * Action: 04
 * Description: Ajax to disable voucher
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

        return response([
            'message' => 'Voucher desativado com sucesso!',
            'status_code' => 200
        ], 200);
    } catch(\Exception $exception) {
        return response([
            'message' => 'Ocorreu um erro ao desativar o voucher',
            'status_code' => $exception->getCode()
        ], $exception->getCode());
    }
}

/*
 * Action: 05
 * Description: Ajax to active voucher
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

        return response([
            'message' => 'Ocorreu um erro ao ativar o voucher',
            'status_code' => 200
        ], 200);

    } catch(\Exception $exception) {
        return response([
            'message' => 'Ocorreu um erro ao ativar o voucher',
            'status_code' => $exception->getCode()
        ], $exception->getCode());
    }
}

/*
 * Action: 06
 * Description: Disable all vouchers
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
 *  Action: 07
 *  Description: Get All Vouchers
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
 * Action: 08
 * Description: Count off Vouchers
 */
function get_vouchers_count()
{
    global $wpdb;
    $prefix = get_db_prefix();
    $sql = "select count(id) from " . $prefix . "vouchers where deleted = 0;";
    return $wpdb->get_var( $sql );
}

/*
 * Action: 09
 * Description: Get Voucher by ID
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

/*
 * Action: 10
 * Description: Generate code voucher
 */
function get_active_voucher()
{
    global $wpdb;

    $prefix = get_db_prefix();

    $results = $wpdb->get_results('select * from '.$prefix.'vouchers where deleted = 0 and active = 1');

    if ($results) {
        return $results[0];
    }

    return 0;
}

/*
 * Action: 11
 * Description: Verify limit of generate per days
 */
function verify_limit_voucher($voucher)
{
    global $wpdb;

    $prefix = get_db_prefix();

    if ($voucher->generates_per_day == 0) {
        return true;
    }

    $voucher_codes = $wpdb->get_results('select * from '.$prefix.'voucher_codes where voucher_id = '.$voucher->id);

    $uses = 0;

    foreach ($voucher_codes as $voucher_code) {
        if ($voucher_code->created_at) {
            $created_at = new DateTime($voucher_code->created_at);
            $now = new DateTime();

            if ($created_at->format('Y-m-d') == $now->format('Y-m-d')) {
                $uses++;
            }
        }
    }

    if ($uses == $voucher->generates_per_day) {
        return false;
    }

    return true;
}

/*
 * Action: 12
 * Description: Generate code voucher
 */
add_action('wp_ajax_nopriv_generate_voucher', 'generate_voucher');
add_action('wp_ajax_generate_voucher', 'generate_voucher');

function generate_voucher()
{
   try {
        $now = date('Y-m-d H:i:s');

        verifyEmail($_POST['email']);

        $voucher = get_active_voucher($_POST['id']);

        if (!$voucher) {
           throw new \Exception('Voucher não principal encontrado!', 404);
        }

        $limitVoucher = verify_limit_voucher($voucher);

        if (!$limitVoucher) {
            throw new \Exception('Limite de usos diarios deste voucher atingido!', 401);
        }

        $code = generate_code($voucher);

        global $wpdb;

        $wpdb->insert(get_db_prefix().'voucher_codes',[
            'voucher_id' => $voucher->id,
            'email' => $_POST['email'],
            'code' => $code,
            'used' => 0,
            'created_at' => $now
        ]);

        return response([
           'message' => 'Voucher criado com sucesso!',
           'status_code' => 200,
           'voucher' => $voucher,
           'voucher_code' => $code
        ], 200);

   } catch (\Exception $exception) {
       return response([
           'message' => $exception->getMessage(),
           'status_code' => $exception->getCode()
       ], $exception->getCode());
   }
}

/*
 * Action: 13
 * Description: Verify email in voucher generate
 */
function verifyEmail($email)
{
    if (!validation_email_domain($email)) {
        throw new \Exception('Email inválido ou já cadastrado!', 401);
    }

    $voucher = get_vouchercode_email($email);

    if ($voucher) {
        throw new \Exception('Email inválido ou já cadastrado!', 401);
    }

    return true;
}

/*
 * Action: 14
 * Description: Get voucher code by email
 */
function get_vouchercode_email($email)
{
    global $wpdb;

    $prefix = get_db_prefix();

    $results = $wpdb->get_results('select * from '.$prefix.'voucher_codes where email = '.'"'.$email.'"');

    if ($results) {
        return $results[0];
    }

    return 0;
}

/*
 * Action: 15
 * Description: Get voucher code by code
 */
function get_vouchercode($code, $used)
{
    global $wpdb;

    $prefix = get_db_prefix();

    $results = $wpdb->get_results('select * from '.$prefix.'voucher_codes where code = '.'"'.$code.'" and used = '+$used);

    if ($results) {
        return $results[0];
    }

    return 0;
}

/*
 * Action: 16
 * Description: Generate code voucher with prefix
 */
function generate_code($voucher)
{
    $new_code = "";

    while (strlen($new_code) == 0) {
        $code = $voucher->codeprefix.substr(md5(generateRandomString(10)),0,5);

        $voucher_by_code = get_vouchercode($code, 0);

        if (!$voucher_by_code) {
            $new_code .= $code;
        }
    }

    return $new_code;
}

