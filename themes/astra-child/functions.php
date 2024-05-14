<?php
/**
 * Recommended way to include parent theme styles.
 * (Please see http://codex.wordpress.org/Child_Themes#How_to_Create_a_Child_Theme)
 *
 */

if(isset($_POST['download_csv'])){
    $csv_data = generate_csv_data();
    download_csv($csv_data);
    exit;
}
function generate_csv_data() {
    $args = array(
        'post_type'      => 'leads',
        'posts_per_page' => -1,
        'post_status' => 'draft',
        'meta_key' => 'user_id',
        'meta_value' => get_current_user_id(),
    );

    $query = new WP_Query($args);
    $posts = $query->posts;

    $csv_data = array();
    foreach ($posts as $post) {
        $lead_category = get_post_meta($post->ID, 'lead_category', true);
        $lead_department = get_post_meta($post->ID, 'lead_department', true);
        $user_lead_status = get_post_meta($post->ID, 'user_lead_status', true);
        $user_lead_email = get_post_meta($post->ID, 'lead_email', true);
        $user_lead_phone = get_post_meta($post->ID, 'lead_phone', true);
        $user_lead_address = get_post_meta($post->ID, 'user_lead_status', true);
        $pente_du_toit = get_post_meta($post->ID, 'pente_du_toit', true);
        $matrielle = get_post_meta($post->ID, 'matrielle', true);
        $facture_delectricit = get_post_meta($post->ID, 'facture_delectricit', true);

        $csv_data[] = array(
            'Name'          => $post->post_title,
            'Email'       => $user_lead_email,
            'Phone'       => $user_lead_phone,
            'Address'       => $user_lead_address,
            'Category'       => $lead_category,
            'Department'     => $lead_department,
            'Pente du toit'     => $pente_du_toit,
            'MatÃ©rielle'     => $matrielle,
            'Facture delectricitÃ©'     => $facture_delectricit,
            'Status'    => $user_lead_status,
        );
    }

    return $csv_data;
}
function download_csv($data) {
    header('Content-Type: csv');
    header('Content-Disposition: attachment; filename="leads.csv"');
    $output = fopen('php://output', 'w');
    // Write CSV header
    fputcsv($output, array_keys($data[0]));

    // Write CSV rows
    foreach ($data as $row) {
        fputcsv($output, $row);
    }

    fclose($output);
}

add_action( 'wp_enqueue_scripts', 'astra_child_style' );
function astra_child_style() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css',array('parent-style'));
}


add_shortcode('buy_leads','buy_leads');
function buy_leads(){
    ob_start();
   
?>
    <div class="container-step-form">

 <div class="buy-follower-overlay full-overlay flex-wrap-class logged-in-user-leads-buy">
<div class="spinner"></div>
<div class="message-row">
<p class="messageText">S'il vous plaît, attendez</p>
<p class="messageText redirecttext">Ne fermez pas la fenêtre</p>

</div>
</div>
        <h2 class="form-top-heading">Achetez des leads pour votre entreprise</h2>
        <div class="progress-bar-container">
            <div class="progress-bar"></div>
        </div>
        <!--        <form id="step-form">-->
        <div class="step step-1" data-step="1">
            <div class="buy-leads-fields-section">
                <div class="buy-leads-fields-row">
<?php
$terms_cat = get_terms([
    'taxonomy' => 'lead-category',
    'hide_empty' => false,
]);

 ?>
                    <label for="lead-category">Catégorie du lead</label>
                    <select id="lead-category" name="select_lead_category" class="buy-leads-fields">
                        <option>Sélectionnez une catégorie</option>
<?php
                        foreach($terms_cat as $cat_term){
?>
                     <option value="<?=$cat_term->slug?>"><?=$cat_term->name?></option>
<?php
                        }
 ?>

                    </select>
                    <p class="error-msg-design category-error">requis</p>
                </div>

<?php
$terms_dept = get_terms([
    'taxonomy' => 'lead-department',
    'hide_empty' => false,
    'parent' => 0,
]);

 ?>
                <div class="buy-leads-fields-row">
                    <div class="department-box-top">Département du lead</div>
                    <div class="box-open-div close-box">Sélectionnez le département</div>
                     <div class="checkbox-dept-box">
                        <div class="dept-list-col">
<?php
                  foreach($terms_dept as $dept_term){
?>                        
                        <div class="parent-department">
<input type="checkbox" class="lead-dept-checkbox"  name="select_lead_department[]" value="<?=$dept_term->slug?>">
                        <label><?=$dept_term->name?></label>
                    </div>
<?php
                        }
?>     
</div>                
 <div class="valdiate-button">
               <button type="button" class="dept-valid-btn">Valider</button>  
                </div>
                     </div> 
                     <p class="error-msg-design dept-error">requis</p>
            
                </div>

            </div>
            <div class="button-groups">
                <button type="button" class="cancel-btn back-btn-design first-back-btn">Retour</button>
                <button type="button" class="next-step first-step-btn">Suivant  <img src="http://myleads.fr/wp-content/uploads/2023/08/right-arrow.png" class="next-arrow"></button>
            </div>
        </div>
        <div class="step step-2" data-step="2">
            <div class="buy-leads-fields-section">
                <div class="buy-leads-fields-row">
                    <label for="lead-numbers-total">Entrez le nombre de prospects</label>
                     <input type="number" id="leads-qty" name="leads_qty" class="buy-leads-fields leads-qty-field" placeholder="Entrez le nombre de prospects">
             
                     <p class="error-msg-design lead-no-error">requis</p>
                </div>
<p class="credits-details">*1 lead = 50€</p>
 <p class="total-order">Total: <span class="lead_price"></span></p>            
            </div>
 
            <div class="button-groups">
     <button type="button" class="prev-step back-btn-design"> <img src="http://myleads.fr/wp-content/uploads/2023/08/small-right.png" class="back-arrow">Retour </button>
<?php
if (is_user_logged_in()) {
?>     
     <button type="button" class="second-btn">Suivant <img src="http://myleads.fr/wp-content/uploads/2023/08/right-arrow.png" class="next-arrow"> </button>
<?php
    }else{
?>
<button type="button" class="not-logged-in-second-btn">Suivant <img src="http://myleads.fr/wp-content/uploads/2023/08/right-arrow.png" class="next-arrow"> </button>
<?php        
    }
?>         
              
            </div>
        </div>
        <div class="step step-3" data-step="3">
            <div class="buy-leads-fields-section">
                <img src="http://myleads.fr/wp-content/uploads/2023/08/undraw_done_re_oak4-1.png" class="completion-image">
                <h3 class="purchased-">Vous avez acheter <span class="user-purchased-credits">0</span> leads</h3>
                <div class="btn-row">
                    <a href="https://myleads.fr/my-leads/" class="after-lead-purchase-btn">Voir mes leads</a>
                </div>
            </div>
        </div>

       
    </div>
    <?php

    return ob_get_clean();
}
add_action('wp_ajax_user_buy_leads', 'user_buy_leads');
add_action('wp_ajax_nopriv_user_buy_leads', 'user_buy_leads');
function user_buy_leads(){

   $lead_category = $_POST['data']['lead_cat'];
   $lead_quantity = $_POST['data']['num_of_leads'];
   $lead_department = $_POST['data']['lead_dept'];
 $current_user = wp_get_current_user();
 $lead_price = $lead_quantity * 50;

   $customer = new WC_Customer(get_current_user_id());
    $username = $current_user->user_login;
        $email = $current_user->user_email;
    $order = wc_create_order(array('customer_id' => $customer->get_id()));

                $order->set_payment_method('stripe');

                $billing_address = array(
                    'first_name' => $username,
                    'email'      => $email,
                );
                $order->set_address($billing_address, 'billing');

                $product_id = 1680;
                $product = wc_get_product($product_id);
                $item = new WC_Order_Item_Product();
                $item_price = 50;
                $item->set_props(array(
                    'product' => $product,
                    'quantity' => $lead_quantity,
                    'subtotal' => $lead_price,
                    'total' => $lead_price,
                ));

                $order->add_item($item);
                $order->calculate_totals();

                $order->update_status('pending');

                $order->add_meta_data("lead_category", $lead_category, true);
                $order->add_meta_data("lead_department", $lead_department, true);

                $order->save();

                 require_once ABSPATH . 'vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_51IkTAMJBYWyzcYivwnKs6CXECiprIUZI2SPRMDqBSa8ua0MYwstVAMAB05x51kzs5A025g4DscbZ4q9vvCkQ63zt0071cFViJh');

try {
    // Create a Checkout Session
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => "Leads",
                   
                ],
                'unit_amount' => 50*100,
            ],
            'quantity' => $lead_quantity,
        ]],
        'mode' => 'payment',
        'success_url' => 'https://myleads.fr/thank-you/?orderid='.$order->get_id(),
        'cancel_url' => 'https://myleads.fr/cancel-order/?orderid='.$order->get_id(),

    ]);

    wp_send_json_success($session->url);
} catch (\Stripe\Exception\ApiErrorException $e) {

    wp_send_json_error($e->getMessage(), 401);
}

    // $num_of_leads = $_POST['userdata']['num_of_leads'];
    // $lead_department = $_POST['userdata']['lead_dept'];
    // $lead_cat = $_POST['userdata']['lead_cat'];
    // $user_id = $_POST['userdata']['user_id'];
    // $availbale_credits = get_user_meta( $user_id, "user_credits", true );

//         $current_user = wp_get_current_user();
// $user_name = $current_user->display_name;
// $user_email = $current_user->user_email;
// $current_date = current_time('Y-m-d');
// $current_time = current_time('H:i:s');

    // $args = array(
    //     'post_type' => 'leads',
    //     'posts_per_page' => $num_of_leads,
    //     'post_status' => 'publish',
    //     'orderby' => 'date',    
    //     'order' => 'ASC',
    //     'meta_query' => array(
    //         'relation' => 'AND',
    //         array(
    //             'key' => 'lead_status', 
    //             'value' => 'Available',  
    //             'compare' => '=',
    //         ),
    //         array(
    //             'key' => 'lead_category', 
    //             'value' => $lead_cat,   
    //             'compare' => '=',
    //         ),
    //         array(
    //             'key' => 'lead_department',
    //             'value' => $lead_department,   
    //             'compare' => '=',
    //         ),
    //     ),
    // );

    // $leads_query = new WP_Query($args);

    // if ($leads_query->have_posts()) {
    //     while ($leads_query->have_posts()) {
    //         $leads_query->the_post();
    //         add_post_meta( get_the_ID(), "user_id", $user_id, true );
    //         add_post_meta( get_the_ID(), "user_lead_status", "In Progress", true );
    //         update_post_meta( get_the_ID(), "lead_status", "Sold", "Available" );
    //         update_post_meta(get_the_ID(), 'nom', $user_name);
    //         update_post_meta(get_the_ID(), 'lead_purchased_email', $user_email);
    //         update_post_meta(get_the_ID(), 'lead_purchased_date', $current_date);
    //         update_post_meta(get_the_ID(), 'temps', $current_time);
    //         $updated_post = array(
    //             'ID' => get_the_ID(),
    //             'post_status' => 'draft', // Replace with the desired post status
    //         );
    //         $new_user_credit = $availbale_credits - $num_of_leads;
    //         update_user_meta( $user_id, "user_credits", $new_user_credit );
    //         // Update the post
    //         wp_update_post($updated_post);
    //     }
    //     $response = array('sucess' => true, 'message' => "Purchased");
    //     wp_send_json($response);
    //     wp_die();
    //     wp_reset_postdata();
    // } else {
    //     echo 'No leads found.';
    // }
    wp_die();
}

add_shortcode('get_stripe_return_data', 'get_stripe_return_data');
function get_stripe_return_data(){
  
  if(isset($_GET['orderid'])){
 
?>

    <script>
       jQuery.ajax({
                type: 'POST',
                url:"https://myleads.fr/wp-admin/admin-ajax.php",
                data: {
                action: 'check_lead_order',
                orderid: '<?php echo $_GET['orderid']?>'
            },
                success: function(response) {
                window.close();
                },

            });
        </script>
<?php   
  }

}

add_action('wp_ajax_check_lead_order', 'check_lead_order');
add_action('wp_ajax_nopriv_check_lead_order', 'check_lead_order');
function check_lead_order(){


// Get order items and calculate total quantity
$order = wc_get_order($_POST['orderid']);
if ($order) {
    // Get order items
    $order_items = $order->get_items();

    // Calculate total quantity
    $total_quantity = 0;
    foreach ($order_items as $item) {
        $total_quantity += $item->get_quantity();
    }

    $lead_category = get_post_meta($_POST['orderid'], "lead_category", true);
    $lead_department = get_post_meta($_POST['orderid'], "lead_department", true);

$current_user = wp_get_current_user();
$user_name = $current_user->display_name;
$user_email = $current_user->user_email;
$current_date = current_time('Y-m-d');
$current_time = current_time('H:i:s');
 $user_id = $current_user->ID;

 $args = array(
        'post_type' => 'leads',
        'posts_per_page' => $total_quantity,
        'post_status' => 'publish',
        'orderby' => 'date',    
        'order' => 'ASC',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'lead_status', 
                'value' => 'Available',  
                'compare' => '=',
            ),
            array(
                'key' => 'lead_category', 
                'value' => $lead_category,   
                'compare' => '=',
            ),
   
        ),
    );

    foreach ($lead_dep_values as $value) {
    $args['meta_query'][] = array(
        'key' => 'lead_department',
                'value' => $lead_department,   
                'compare' => 'IN',
    );
}

     $leads_query = new WP_Query($args);

    if ($leads_query->have_posts()) {
        while ($leads_query->have_posts()) {
            $leads_query->the_post();
            update_post_meta( get_the_ID(), "user_id", $user_id, true );
            update_post_meta( get_the_ID(), "user_lead_status", "In Progress", true );
            update_post_meta( get_the_ID(), "lead_status", "Sold", "Available" );
            update_post_meta(get_the_ID(), 'nom', $user_name);
            update_post_meta(get_the_ID(), 'lead_purchased_email', $user_email);
            update_post_meta(get_the_ID(), 'lead_purchased_date', $current_date);
            update_post_meta(get_the_ID(), 'temps', $current_time);
            $updated_post = array(
                'ID' => get_the_ID(),
                'post_status' => 'draft', // Replace with the desired post status
            );

 
            wp_update_post($updated_post);
          
        }

    }

    $order->update_status('processing');
    $order->save();
    update_user_meta($user_id, "new_order_id", $_POST['orderid']);
wp_send_json_success("success");
}

}

add_action('wp_ajax_new_order_check', 'new_order_check');
add_action('wp_ajax_nopriv_new_order_check', 'new_order_check');
function new_order_check(){

$current_user_id = get_current_user_id();
$new_order = get_user_meta($current_user_id, "new_order_id", true);

delete_user_meta($current_user_id, "new_order_id");

    if(isset($new_order)){
        $order = wc_get_order($new_order);
        if ($order) {

    $order_status = $order->get_status();

    if ($order_status === 'processing') {
        $response = array('success' => true, 'message' => $new_order);
         wp_send_json($response);
    } 
}
       
    }
}

add_shortcode('buy_credits','buy_credits');
function buy_credits(){
    ob_start();
  //   $current_user_id = get_current_user_id();
  //   if($current_user_id){

  
  //   $currentDate = new DateTime();

  //   $currentDate->add(new DateInterval('P30D'));
  //   $futureDate = $currentDate->format('d F Y');

  //   if(isset($_POST['pay_stripe'])){

  //       $credits_purchased = $_POST['buy_credits'];
  //       $stripe_card_number = $_POST['stripe_card_number'];
  //       $stripe_card_expiry = $_POST['stripe_expiry'];
  //       $stripe_card_cvv = $_POST['stripe_cvv'];
  //       $explodedArray = explode("/", $stripe_card_expiry);
  //       $stripe_card_expiry_month = $explodedArray[0];
  //       $stripe_card_expiry_year = $explodedArray[1];
  //       $current_user = wp_get_current_user();
  //       $username = $current_user->user_login;
  //       $email = $current_user->user_email;

  //       require_once ABSPATH.'vendor/autoload.php';

  //       $stripeSecretKey = 'sk_test_51IkTAMJBYWyzcYivwnKs6CXECiprIUZI2SPRMDqBSa8ua0MYwstVAMAB05x51kzs5A025g4DscbZ4q9vvCkQ63zt0071cFViJh';
  //       \Stripe\Stripe::setApiKey($stripeSecretKey);

  //       $amount = $credits_purchased * 100;

  //       // Step 1: Create a token from credit card information
  //       try {
  //           $token = \Stripe\Token::create([
  //               'card' => [
  //                   'number' => $stripe_card_number,
  //                   'exp_month' => $stripe_card_expiry_month,
  //                   'exp_year' => $stripe_card_expiry_year,
  //                   'cvc' => $stripe_card_cvv,
  //               ],
  //           ]);
  //       } catch (\Stripe\Exception\ApiErrorException $e) {
  //           // Handle API errors

  //           $payment_error = $e->getMessage();
  //       }
  //       try {
  //           $customer = \Stripe\Customer::create([
  //               'email' => $email,
  //               'source' => $token->id,
  //               'name' => $username,
  //           ]);
  //       } catch (\Stripe\Exception\ApiErrorException $e) {
  //           // Handle API errors
  //           $payment_error = $e->getMessage();
  //       }
  //       // Step 3: Charge the customer with the specified amount (159 euros)
  //       try {
  //           $charge = \Stripe\Charge::create([
  //               'amount' => $amount,
  //               'currency' => 'usd',
  //               'customer' => $customer->id,
  //               'description' => 'Leads Purchased',
  //           ]);

  //           // Payment successful
  //           if($charge->id){
  //               if(!empty(get_user_meta( $current_user_id, "user_credits", true ))){
  //                   $credits = get_user_meta( $current_user_id, "user_credits", true );
  //                   $update_credits = $credits + $credits_purchased;
  //                   update_user_meta($current_user_id, "user_credits", $update_credits );
  //                   update_user_meta($current_user_id, "credits_expiry", $futureDate );
  //               }else{
  //                   add_user_meta( $current_user_id, "user_credits", $credits_purchased, true);
  //                   add_user_meta($current_user_id, "credits_expiry", $futureDate, true);
  //               }

  //               $quantity = $credits_purchased;
  //               $price = $credits_purchased;
  //               $user_email = $email;
  //               $meta_key = 'leads_purchased';
  //               $meta_value = $credits_purchased;

  //               $customer = new WC_Customer(get_current_user_id());

  //               $order = wc_create_order(array('customer_id' => $customer->get_id()));

  //               $order->set_payment_method('stripe');

  //               $billing_address = array(
  //                   'first_name' => $username,
  //                   'email'      => $email,
  //               );
  //               $order->set_address($billing_address, 'billing');

  //               $product_id = 1680;
  //               $product = wc_get_product($product_id);
  //               $item = new WC_Order_Item_Product();
  //               $item_price = $credits_purchased;
  //               $item->set_props(array(
  //                   'product' => $product,
  //                   'quantity' => $credits_purchased,
  //                   'subtotal' => $credits_purchased,
  //                   'total' => $credits_purchased,
  //               ));

  //               $order->add_item($item);
  //               $order->calculate_totals();

  //               $order->update_status('completed');

  //               $order->add_meta_data($meta_key, $meta_value, true);

  //               $order->save();

  //           }

  // echo("<script>location.href = 'https://myleads.fr/nosleads/';</script>");
  //   die();
  //       } catch (\Stripe\Exception\ApiErrorException $e) {
  //           // Handle API errors
  //           $payment_error = $e->getMessage();
  //       }
  //   }

  //   if(!empty(get_user_meta( $current_user_id, "user_credits", true ))){
  //       $available_credits = get_user_meta( $current_user_id, "user_credits", true );
  //   }else{
  //       $available_credits = 0;
  //   }
?>
    <div class="buy-credits-main-row">
        <!-- <div class="buy-credit-top-row">
            <h2 class="balance-heading">Vos crédits: <?=$available_credits?></h2>
            <p class="credit-expiry">Date d'expiration = <?=$futureDate?></p>
        </div> -->
        <form action="" method="post">
            <div class="buy-more-credits-row">
                <h3 class="credit-row-heading">Acheter des prospects</h3>
                <select name="buy_credits" class="buy-credit-field">
                    <option>Select number of credits</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                    <option value="50">50</option>
                </select>
                <div class="top-last-row">
                    <div class="price-row">
                        <h3 class="selected-price">00$</h3>
                    </div>
                    <div class="detail-section">
                        <p class="detail-section-text">Description</p>
                        <p class="tip-text">1 lead coûte 50 euros</p>
                    </div>
                </div>
            </div>
            <div class="payment-gatewy-row">
                <!-- <h2 class="payment-gateway-heading">Sélectionner votre moyen de paiement</h2> -->
                <div class="payemnt-method-section">
                    <div class="payment-row payment-stripe-row">
                       <!--  <div class="title-img-row">
                            <div class="title-col">
                                <input type="radio" class="selected-payment-method" name="stripe_payment" checked>
                                <p class="payemnt-card-title">Carte de débits</p>
                            </div>
                            <div class="cards-img-col">
                                <img src="http://myleads.fr/wp-content/uploads/2023/11/card-logo.png" class="cards-icon-img">
                            </div>
                        </div> -->
                       <!--  <div class="payment-fields-section">
                            <div class="card-number-field">
                                <input type="number" class="payment-fields stripe-card-number-field" name="stripe_card_number" placeholder="Numéro de carte" required>
                                <img src="http://myleads.fr/wp-content/uploads/2023/08/card-icon.png" class="fields-images">
                            </div>
                            <div class="fields-groups">
                                <div class="cards-expiry-section">
                                    <input type="text" class="payment-fields stripe-expiry" id="stripe-card-expiry" name="stripe_expiry" placeholder="MM / YY" required>
                                    <img src="http://myleads.fr/wp-content/uploads/2023/08/card-expiry.png" class="fields-images">
                                </div>
                                <div class="card-cvv-section">
                                    <input type="number" name="stripe_cvv" class="payment-fields stripe-cvv-field" placeholder="CVV" required>
                                    <img src="http://myleads.fr/wp-content/uploads/2023/08/lock-icon.png" class="fields-images">
                                </div>
                            </div>
                        </div> -->
<?php
                        // if(isset($payment_error))
                        // {
?>
                            <!-- <p class="stripe-error"><?=$payment_error?></p> -->
<?php
                        // }
?>
                        <div class="disclamair-section">
                            <p>Les données sont protégées selon la norme PCI DSS. Nous ne stockons pas vos données et ne les partageons pas avec le commerçant.</p>
                        </div>
                        <div class="submit-btn-section">
                            <input type="submit" name="pay_stripe" class="stripe-payment-btn" value="Payer">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php
// }else{
//     wp_redirect(home_url('sign-in'), 302);
// }
    return ob_get_clean();
}



add_action ( 'init', 'hook_inHeader', 20 );
function hook_inHeader() {
    require ABSPATH.'wp-admin/includes/taxonomy.php';
     $csvFilee = ABSPATH.'leads.csv';
if (file_exists($csvFilee)) {

     $fileHandle = fopen($csvFilee, 'r');

      $lineNumberr = 0;
$term_exists_dept = ' ';
$dept_code;
      while (($dataa = fgetcsv($fileHandle)) !== false) {

      if ($lineNumberr === 0) {
                $lineNumberr++;
                continue;
            }
  
// $term_exists_category = term_exists($dataa[4], 'lead-category');
// if (!$term_exists_category) {
//     $term_cat = wp_insert_term($dataa[4], 'lead-category');

//     if (!is_wp_error($term_cat)) {
//         $term_id = $term_cat['term_id'];
//     } else {
//         // Handle term creation error
//         echo "Error adding term: " . $term->get_error_message();
//     }
// }
     preg_match_all('/(?<!\d)\d{5}(?!\d)/', $dataa[3], $match) ? $match[0] : []; 
 
$dept_code = $match[0];

if(!empty($dept_code)){
 $firstTwoDigits = substr($dept_code[0], 0, 2);
 
 $term_exists_dept = term_exists($firstTwoDigits, 'lead-department');
    if(empty($term_exists_dept)){
    $term_dept = wp_insert_term($firstTwoDigits, 'lead-department');
    $child_term = wp_insert_term($dept_code[0], 'lead-department', array('parent' => $term_dept['term_id']));
}else{
    $parent_term = get_term_by('name', $firstTwoDigits, 'lead-department');
    $term_child_exists_dept = term_exists($dept_code[0], 'lead-department');
    if(empty($term_child_exists_dept)){
$child_term = wp_insert_term($dept_code[0], 'lead-department', array('parent' => $parent_term->term_id));
    }
    
}
}
      }
       fclose($fileHandle); 

 $fileHandlee = fopen($csvFilee, 'r');
  $lineNumber = 0;
       while (($datas = fgetcsv($fileHandlee)) !== false) {

      if ($lineNumber === 0) {
                $lineNumber++;
                continue;
            }

      global $post;
      global $query;

    $args = array(
    'post_type'      => 'leads',
    'post_status'    => array('publish', 'draft'),
    'posts_per_page' => -1,
    'title'          => $datas[0],
       'meta_query' => array(
                    array(
                        'key' => 'lead_email',
                        'value' => $datas[1],
                        'compare' => '='
                    )
                )
);

$query = new WP_Query($args);

  if ($query->have_posts()) {
                return false;
            } else {
preg_match_all('/(?<!\d)\d{5}(?!\d)/', $datas[3], $match) ? $match[0] : []; 
 
$dept_value = $match[0];

 $parentCategory = substr($dept_value[0], 0, 2);
 $childCategory = $dept_value[0];
  $taxonomy_name = 'lead-department';

    
     $post_data = array(
                    'post_title'   => $datas[0], // Set the post title
                    'post_type'    => 'leads', // Set the post type
                    'post_status'  => 'publish',
                    'meta_input'   => array(
                        'lead_email' => $datas[1],
                        'lead_phone' => $datas[2],
                        'date_de_creation' => date('F j, Y'),
                        'temps_de_creation' => date('H:i:s'),
                        'lead_address' => $datas[3],
                        'pente_du_toit' => $datas[6],
                        'matrielle' => $datas[7],
                        'facture_delectricit' => $datas[8],
                        'lead_status' => 'Available',
                        'lead_category' => $datas[4],
                        'lead_department' => $parentCategory,
                        // 'lead_department' => $datas[5],
                    ),
                );
                $post_id = wp_insert_post($post_data); 
                 $taxonomy = 'lead-department';
                    if ($post_id && !empty($parentCategory) && !empty($childCategory)) {
            $parent_term = get_term_by('name', $parentCategory, $taxonomy);
            $child_term = get_term_by('name', $childCategory, $taxonomy);
             if ($parent_term && $child_term) {
                wp_set_post_terms($post_id, array($parent_term->term_id, $child_term->term_id), $taxonomy, true);
            }
        }  


  }
      }
 fclose($fileHandlee);

}
//    $csvFile = ABSPATH.'leads-file.csv';
//    if (file_exists($csvFile)) {
//        // Open the CSV file for reading
//        $fileHandle = fopen($csvFile, 'r');
//
//        $lineNumber = 0;
//
//        // Loop through each line in the CSV file
//        while (($data = fgetcsv($fileHandle)) !== false) {
//
//            // Skip the first line (header row)
//            if ($lineNumber === 0) {
//                $lineNumber++;
//                continue;
//            }
//
//            $args = array(
//                'post_type' => 'leads', // Replace 'post' with your custom post type if needed
//                'post_status' => 'published', // Check all post statuses (e.g., publish, draft, pending, etc.)
//                'meta_query' => array(
//                    array(
//                        'key' => 'lead_email',
//                        'value' => $data[1],
//                        'compare' => '='
//                    )
//                )
//            );
//
//            // Run the query
//            $query = new WP_Query($args);
//
//            // Check if the post exists
//            if ($query->have_posts()) {
//                return false;
//            } else {
//                $post_data = array(
//                    'post_title'   => $data[0], // Set the post title
//                    'post_type'    => 'leads', // Set the post type
//                    'post_status'  => 'publish', // Set the post status to 'publish'
//                    'meta_input'   => array(
//                        'test_meta_key' => 'value of test_meta_key',
//                        'test_meta_key' => 'value of test_meta_key',
//                    ),
//                );
//                $post_id = wp_insert_post($post_data);
//
//                if ($post_id) {
//
//                    // Update custom fields using update_post_meta function
//                    update_post_meta($post_id, 'lead_email', $data[1]??'');
//                    update_post_meta($post_id, 'lead_phone', $data[2]);
//                    update_post_meta($post_id, 'lead_category', $data[3]);
//                    update_post_meta($post_id, 'lead_department', $data[4]);
//                    update_post_meta($post_id, 'date_de_creation', $data[5]);
//                    update_post_meta($post_id, 'temps_de_creation', $data[6]);
//                    update_post_meta($post_id, 'lead_status', "Available");
//                } else {
//                    echo 'Error creating lead post.';
//                }
//
//            }
//
//        }
//
//        // Close the file handle
//        fclose($fileHandle);
//    } else {
//        echo "CSV file not found.";
//    }
}
function custom_post_type_columns($columns) {
    // Add the new column "Lead Email" after the "Title" column
    $columns['lead_email'] = 'Lead Email';
    return $columns;
}
add_filter('manage_leads_posts_columns', 'custom_post_type_columns');

function custom_post_type_columns2($columns) {
    // Add the new column "Lead Email" after the "Title" column
    $columns['lead_status'] = 'Status';
    return $columns;
}
add_filter('manage_leads_posts_columns', 'custom_post_type_columns2');
function custom_post_type_custom_column($column, $post_id)
{
    if ($column === 'lead_email') {
        // Get the lead email custom field value
        $lead_email = get_post_meta($post_id, 'lead_email', true);

        // Output the lead email value
        echo $lead_email;
    }
}

add_action('manage_leads_posts_custom_column', 'custom_post_type_custom_column', 10, 2);

function custom_post_type_custom_column2($column, $post_id)
{
    if ($column === 'lead_status') {
        $lead_status = get_post_meta($post_id, 'lead_status', true);

        echo $lead_status;
    }
}

add_action('manage_leads_posts_custom_column', 'custom_post_type_custom_column2', 10, 2);

add_shortcode('my_leads_page', 'my_leads_page');
function my_leads_page(){
    ob_start();
    $current_user_id = get_current_user_id();

    if(isset($_POST['update_lead'])){
        $perosnal_notes = get_post_meta($_POST['modal_lead_id'], 'lead_personal_notes', true);
        if(empty($perosnal_notes)){
         update_post_meta($_POST['modal_lead_id'], 'lead_personal_notes', $_POST['modal_lead_personal_note']);
        }else{
            update_post_meta($_POST['modal_lead_id'], 'lead_personal_notes', $_POST['modal_lead_personal_note']);
        }
        if(!empty($_POST['modal_lead_status'])){
            update_post_meta($_POST['modal_lead_id'], 'user_lead_status', $_POST['modal_lead_status'] );
        }

    }
    if(isset($_POST['apply_filters'])){
        $args2 = array(
            'post_type' => 'leads',
            'post_status' => 'draft',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'user_id',
                    'value' => $current_user_id,
                    'compare' => '=',
                    'type' => 'NUMERIC',
                ),
            ),
        );

        if (!empty($_POST['filter_lead_category'])) {
            $args2['meta_query'][] = array(
                'key' => 'lead_category',
                'value' => $_POST['filter_lead_category'],
                'compare' => '=',
            );
        }

        if (!empty($_POST['filter_lead_department'])) {
            $args2['meta_query'][] = array(
                'key' => 'lead_department',
                'value' => $_POST['filter_lead_department'],
                'compare' => '=',
            );
        }
        if (!empty($_POST['lead_status'])) {
            $args2['meta_query'][] = array(
                'key' => 'user_lead_status',
                'value' => $_POST['lead_status'],
                'compare' => '=',
            );
        }

    }else{
        $args2 = array(
            'post_type' => 'leads',
            'post_status' => 'draft',
            'meta_key' => 'user_id',  // Replace with the actual meta key
            'meta_value' => $current_user_id,      // Replace with the desired meta value
        );

    }
    $query2 = new WP_Query($args2);
    $total_posts = $query2->found_posts;
    ?>
    <div class="my-lead-main-section">
        <div class="my-lead-page-top-row">
            <div class="my-lead-page-heading">
                <h2 class="">Mes leads <sub>(<?=$total_posts?>)</sub></h2>
            </div>
            <div class="sorting-column">
                <div class="search-field-my-leads">
                    <input type="text" placeholder="Rechercher dans mes leads" class="search-my-leads">
                    <span><i class="fa fa-search" aria-hidden="true"></i></span>
                </div>
                <div class="sorting-icon">
                    <img src="http://myleads.fr/wp-content/uploads/2023/08/filtersearch.png" class="fitler-search">
                    <div class="fitler-main">
                        <form action="#" method="POST">
                            <p class="filter-title">Filtres</p>
                            <div class="field-row">
                                <label>Catégories</label>
                                <select class="filter-lead-category" name="filter_lead_category">
                                    <option value="">Select a Category</option>
                                    <option value="lead category">Lead Category</option>
                                    <option value="lead category2">Lead Category2</option>
                                </select>
                            </div>

                            <div class="field-row">
                                <label>Département</label>
                                <select class="filter-lead-department" name="filter_lead_department">
                                    <option value="">Select a Department</option>
                                    <option value="lead department">Lead Department</option>
                                    <option value="lead department2">Lead Department2</option>
                                </select>
                            </div>

                            <div class="radio-btn-row">
                                <p class="status-para">Statut</p>
                                <div class="status-radio-buttons">
                                    <input type="radio" id="in-progress" name="lead_status" value="In Progress">
                                    <label for="in-progress">En cours</label>
                                    <input type="radio" id="sold" name="lead_status" value="Sold">
                                    <label for="sold">Traiter</label>
                                    <input type="radio" id="calling" name="lead_status" value="Call">
                                    <label for="calling">Appeler</label>
                                </div>
                            </div>
                            <div class="buttons-section">
                                <button type="button" class="button-design cancel-button">Retour</button>
                                <input type="submit" name="apply_filters" class="button-design apply-filter-button" value="Appliquer les filtres">
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <div class="modal">
            <div class="modal-overlay modal-toggle"></div>
            <div class="modal-wrapper modal-transition">
                <div class="modal-body">
                    <div class="modal-content">
                        <div class="modal-top-header">
                            <div class="lead-export-csv-column">
                                <form method="POST" action="#" class="export-csv-form">
                                    <input type="submit" name="download_csv" class="export-btn" value="Export CSV" style="display:none;">
                                </form>
                                <div class="csv-export-main">
                           <img src="http://myleads.fr/wp-content/uploads/2023/08/exportcircle.png" class="export-csv-image" alt="Export CSV">
                                    <p>Export CSV</p>
                                </div>
                                <?php
                                $user_id = get_current_user_id();
                                ?>
                            </div>
                            <div class="lead-report-section" style="position:relative;">
                                <div class="lead-report-col">
                                    <img src="http://myleads.fr/wp-content/uploads/2023/08/flag.png" onclick="show_reporting_area()" class="report-lead-image" alt="Report Lead">
                                    <p onclick="show_reporting_area()">Signaler le lead</p>
                                </div>
                                <div class="report-dropdown" style="display:none;">
                                    <input type="hidden" value="<?=$user_id?>" class="toggle-user-hidden-field-modal">
                                    <input type="hidden" value="" class="toggle-lead-hidden-post">
                                    <div class="spam-row"><p class="report-span">Spam</p></div>
                                    <div class="mis-leading-info"><p class="misleading-info">Mis leading Information</p></div>
                                    <div class="report-other-row">
                                        <p class="reprt-other-text">Other</p>
                                        <div class="other-field-col">
                                            <input type="text" maxlength="150" class="report-other-field" placeholder="Max 150 Words">
                                            <img src="http://myleads.fr/wp-content/uploads/2023/08/send.png" class="click-icon">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="email-message"></div>
                        </div>
                        <div class="ajax-result-data">

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="table-data">
            <table class="leads-data-table">
                <thead>
                <tr class="table-heading-row">
                    <th>Nom</th>
                    <th>Catégorie</th>
                    <th>Département</th>
                    <th>Statut</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if($query2->have_posts()) {
                    while($query2->have_posts()) {
                        $query2->the_post();
                        $lead_status = get_post_meta(get_the_ID(), "user_lead_status", true);
                        $lead_category = get_field('lead_category', get_the_ID());
                        $lead_department = get_field('lead_department', get_the_ID() );
                        $perosnal_notes = get_post_meta(get_the_ID(), 'lead_personal_notes', true);
                        ?>
                        <tr class="table-data-row" data-lead-id="<?=get_the_id()?>">
                            <td class="lead-title-cell"><?php echo get_the_title(); ?></td>
                            <td class="lead-cat-cell"><?php   echo $lead_category; ?></td>
                            <td class="lead-dept-cell"><?php echo $lead_department; ?></td>
                            <?php
                            if($lead_status === "In Progress"){
                                ?>
                                <td class="lead-status-cell"><p class="lead-status-green">En cours</p></td>
                                <?php
                            }
                            if($lead_status === "Sold"){
                                ?>
                                <td class="lead-status-cell"><p class="lead-status-sold">Contacter</p></td>
                                <?php
                            }

                            if($lead_status === "Call"){
                                ?>
                                <td class="lead-status-cell"><p class="lead-status-call">Traiter</p></td>
                                <?php
                            }
                            ?>
                        </tr>
                        <?php
                    }
                }else {
                    ?>
                    <tr class="table-data-no-record-row">
                        <td class="no-record-cell">Pas de leads</td>
                        <td class="no-record-cell"></td>
                        <td class="no-record-cell"></td>
                        <td class="no-record-cell"></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>

        </div>
    </div>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}

add_action('wp_ajax_search_leads', 'search_leads');
add_action('wp_ajax_nopriv_search_leads', 'search_leads');
function search_leads(){
    $current_user_id = get_current_user_id();
    $search_leads = array(
        'post_type' => 'leads',
        'post_status' => 'draft',
        's' => $_POST['searchValue'],
        'meta_key' => 'user_id',
        'meta_value' => $current_user_id,
    );
    $query_search_leads = new WP_Query($search_leads);
    ?>
    <div class="search-table-data">
        <table class="leads-data-table search-lead-table">
            <thead>
            <tr class="search-lead-table-head">
                <th>Name</th>
                <th>Category</th>
                <th>Department</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if( $query_search_leads->have_posts()) {
                while($query_search_leads->have_posts()) {
                    $query_search_leads->the_post();
                    $lead_status = get_post_meta(get_the_ID(), "user_lead_status", true);
                    $lead_category = get_field('lead_category', get_the_ID());
                    $lead_department = get_field('lead_department', get_the_ID() );

                    ?>
                    <tr class="search-lead-table-row" data-lead-id="<?=get_the_id()?>">
                        <td class="search-lead-name"><?php echo get_the_title(); ?></td>
                        <td class="search-lead-category"><?php   echo $lead_category; ?></td>
                        <td class="search-lead-departent"><?php echo $lead_department; ?></td>
                        <td class="search-lead-status"><p class="search-lead-status-progress"><?=$lead_status?></p></td>
                    </tr>
                    <?php
                }
            }else{
                ?>
                <tr class="table-data-no-record-row">
                    <td class="no-record-cell">No Record Found</td>
                    <td class="no-record-cell"></td>
                    <td class="no-record-cell"></td>
                    <td class="no-record-cell"></td>
                </tr>
                <?php
            }
            wp_reset_postdata();
            wp_die();
            ?>
            </tbody>
        </table>

    </div>
    <?php
}

add_action('wp_ajax_my_leads_details', 'my_leads_details');
add_action('wp_ajax_nopriv_my_leads_details', 'my_leads_details');
function my_leads_details(){
    $user_id = get_current_user_id();
    $args = array(
        'post_type' => 'leads',
        'post_status' => 'draft',
        'p' => $_GET['selected_lead_id'],
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $lead_status = get_post_meta(get_the_ID(), "user_lead_status", true);
            $lead_category = get_field('lead_category', get_the_ID());
            $lead_department = get_field('lead_department', get_the_ID() );
            $perosnal_notes_modal = get_post_meta($_GET['selected_lead_id'], 'lead_personal_notes', true);
            ?>
            <div class="modal-main-container">

                <div class="lead-info-section">
                    <div class="info-left-side">
                        <div class="lead-image-column">
                            <div class="modal-lead-image-box">
                                <img src="http://myleads.fr/wp-content/uploads/2023/08/lead-image.png" class="lead-image-modal" alt="Lead Image">
                            </div>
                        </div>
                        <div class="lead-title-progress-column">
                            <p class="modal-lead-title"><?=the_title()?></p>
                            <?php
                            if($lead_status === "In Progress"){
                                ?>
                                <p class="modal-lead-status lead-status-green">En Cours</p>
                                <?php
                            }
                            if($lead_status === "Call"){
                                ?>
                                <p class="modal-lead-status lead-status-call">Contacter</p>
                                <?php
                            }
                            if($lead_status === "Sold"){
                                ?>
                                <p class="modal-lead-status lead-status-sold">Traiter</p>
                                <?php
                            }
                            ?>

                        </div>
                    </div>
                    <div class="info-right-side">
                        <div class="modal-lead-category">
                            <p class="modal-category-heading">Catégorie:</p>
                            <p class="modal-category-title"><?=$lead_category?></p>
                        </div>
                        <div class="modal-lead-department">
                            <p class="modal-department-heading">Département: </p>
                            <p class="modal-department-title "><?=$lead_department?></p>
                        </div>
                    </div>

                </div>
                <div class="form-row-section">
                    <form id="modal-lead-form" action="#" method="POST">
                        <div class="modal-form-row form-textarea-field">
                            <label for="perosnal-note">Ajouter une note</label>
                            <?php
                            if(empty($perosnal_notes_modal)){
                                ?>
                                <textarea name="modal_lead_personal_note" id="personal-note" rows="20"  maxlength="250" placeholder="Add note max 250 words"></textarea>
                                <?php
                            }else{
                                ?>
                                <textarea name="modal_lead_personal_note" id="personal-note" rows="20"  maxlength="250"><?=$perosnal_notes_modal?></textarea>
                                <?php
                            }
                            ?>

                        </div>
                        <div class="modal-form-row modal-form-status-row">
                            <p>Statut du lead</p>
                            <div class="radio-button-modal">
                                <input type="radio" id="inprogress" name="modal_lead_status" value="In Progress">
                                <label for="inprogress" class="modal-lead-status-form modal-lead-status-progress" onclick="statusprogress()">En cours</label>

                                <input type="radio" id="statuscall" name="modal_lead_status" value="Call">
                                <label for="statuscall" class="modal-lead-status-form  modal-lead-status-call" onclick="statuscall()">Contacter</label>

                                <input type="radio" id="status-sold" name="modal_lead_status" value="Sold">
                                <label for="status-sold" class="modal-lead-status-form  modal-lead-status-sold" value="Sold" onclick="statussold()">Traiter</label>
                            </div>
                        </div>
                        <div class="modal-button-group">
                            <button type="button" class="button-design-modal modal-cancel-button" onclick="closemodal()">Retour</button>
                            <input type="submit" name="update_lead" class="button-design-modal save-lead-button" value="Enregistrer">
                            <input type="hidden" name="modal_lead_id" value="<?=get_the_id()?>">
                        </div>
                    </form>
                </div>
            </div>
            <?php
        }
        wp_reset_postdata();
    }
    wp_die();
}

add_action('wp_ajax_lead_report', 'lead_report');
add_action('wp_ajax_nopriv_lead_report', 'lead_report');
function lead_report(){
    $lead_id =  $_POST['post_id'];
    $user_id = $_POST['user_id'];
    $nickname = get_user_meta($user_id, 'nickname', true);
    $report = $_POST['message'];
    $args = array(
        'post_type' => 'leads',
        'post_status' => 'draft',
        'p' => $lead_id,
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $admin_email = get_option('admin_email');

            $subject = 'My Leads : User Report';
            $message = $nickname . ' has reported the lead ' . get_the_title() . ' as ' . $report;

            $headers = array(
                'Content-Type: text/html; charset=UTF-8',
            );

            $result = wp_mail($admin_email, $subject, $message, $headers);

            if ($result) {
                echo 'Lead Reported';
            } else {
                echo 'Could not be Reported';
            }
        }
    }
    wp_die();
}

add_shortcode('my_invoices', 'my_invoices');
function my_invoices(){
    ob_start();
    if(isset($_POST['order_select_date'])){
        $days_ago =  date('Y-m-d', strtotime('-'.$_POST['order_select_date']));;
    }else{
        $days_ago = date('Y-m-d', strtotime('-7 days'));
    }
    $current_user_id = get_current_user_id();

    $args = array(
        'date_created' => '>' . $days_ago,
        'customer' => $current_user_id,
    );
    $orders = wc_get_orders($args);

    ?>

    <div class="table-data-invoices">
        <table class="invoices-data-table">
            <thead>
            <tr class="table-heading-row">
                <th>N° de la facture</th>
                <th>Date & Heure</th>
                <th>Crédits</th>
                <th>Montant</th>
                <th>
                    <div class="order-select">
                        <?php
                        ?>
                        <form method="POST" action="#" class="ordersort-form">
                            <select class="order-sort-select" name="order_select_date">
                                <option value="7 days"  <?php  if($_POST['order_select_date'] === "7 days"){
                                    echo "selected";
                                } ?>>7 jours</option>
                                <option value="30 days" <?php  if($_POST['order_select_date'] === "30 days"){
                                    echo "selected";
                                } ?>>Mois</option>
                            </select>
                        </form>
                    </div>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php
            if(!empty($orders)){
                foreach ($orders as $order) {
                    foreach ($order->get_items() as $item_id => $item) {
                        $total_quantity = $item->get_quantity();
                    }
                    $user_order = $order->get_id();
                    $order_date = $order->get_date_created();
                    $order_amount = $order->get_total();
                    $order_number = $order->get_order_number();
                    $formatted_date = date("d F Y h:i A", strtotime($order_date));
                    ?>
                    <tr class="table-data-row">
                        <td class="lead-order-number"><?=$order_number?></td>
                        <td class="lead-buy-date"><?=$formatted_date?></td>
                        <td class="lead-buy-credits"><?= $total_quantity?></td>
                        <td class="lead-buy-amount"><?= $total_quantity?></td>

                        <td class="lead-sorting"><a href="https://myleads.fr/wp-admin/admin-ajax.php?action=generate_wpo_wcpdf&amp;document_type=invoice&amp;order_ids=<?=$user_order?>&amp;access_key=<?=$_REQUEST['access_key']?>&amp;my-account=true" class="woocommerce-button button invoice generatePdf">Download</a></td>
                    </tr>


                    <?php
                }
            }else{
                ?>
                <tr class="table-data-no-record-row">
                    <td class="no-record-cell">Pas de facture</td>
                    <td class="no-record-cell"></td>
                    <td class="no-record-cell"></td>
                    <td class="no-record-cell"></td>
                    <td class="no-record-cell"></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>

    </div>
    <?php
    return ob_get_clean();
}



add_filter( 'cron_schedules', 'my_leads_custom_cron' );
function my_leads_custom_cron( $schedules ) {
    $schedules['every_three_minutes'] = array(
        'interval'  => 24 * 60 * 60,
        'display'   => __( 'Daily', 'textdomain' )
    );
    return $schedules;
}

// Schedule an action if it's not already scheduled
if ( ! wp_next_scheduled( 'my_leads_custom_cron' ) ) {
    wp_schedule_event( time(), 'every_three_minutes', 'my_leads_custom_cron' );
}

// Hook into that action that'll fire every three minutes
add_action( 'my_leads_custom_cron', 'check_leads_expiry_cron_func' );
function check_leads_expiry_cron_func() {
    $user_ids = get_users(array(
        'fields' => 'ID', // Retrieve only user IDs
    ));
    foreach ($user_ids as $user_id) {
        if(!empty(get_user_meta($user_id, "credits_expiry")) && get_user_meta($user_id, "credits_expiry") > 0){
            $lead_expiry_date = get_user_meta($user_id, "credits_expiry")[0];
            $current_date =  date("d F, Y");
            $givenDate = new DateTime($lead_expiry_date);
            if($current_date > $givenDate){
                update_user_meta( $user_id, "user_credits", 0 );
            }
        }
    }

}

add_shortcode('my_profile', 'my_profile');
function my_profile(){
    ob_start();

    $current_user = wp_get_current_user();

    if ($current_user instanceof WP_User) {
        $first_name = $current_user->first_name;
        $last_name = $current_user->last_name;
        $email = $current_user->user_email;
        $profile_image = get_avatar_url($current_user->ID);

    }
    if(isset($_POST['update_user_profile'])){

        if(!empty($_POST['user_old_password'])){
            $old_password_hash = $current_user->user_pass;
            if (wp_check_password($_POST['user_old_password'], $old_password_hash, $current_user->ID)) {
                $error_message = '';
            } else {
                $error_message =  "Old Password not matched";

            }
        }
        if(!empty($_POST['user_new_password']) && !empty($_POST['user_confirm_pass']) ){
            if($_POST['user_new_password'] != $_POST['user_confirm_pass']){
                $error_message = "Password Not Matched";

            }else{
                $error_message = '';
            }
        }

        if($error_message==''){
            if(!empty($_POST['user_first_name'])){
                $userfirstname = $_POST['user_first_name'];
            }else{
                $userfirstname = $first_name;
            }

            if(!empty($_POST['user_last_name'])){
                $userlastname = $_POST['user_last_name'];
            }else{
                $userlastname = $last_name;
            }
            wp_update_user(array(
                'ID' => $current_user->ID,
                'first_name' => $userfirstname,
                'last_name' => $userlastname
            ));
            if(!empty($_POST['user_new_password'])){
                $newuser_password = $_POST['user_new_password'];
                wp_set_password($newuser_password, $current_user->ID);
            }




                if (isset($_FILES['profile_image']) && !empty($_FILES['profile_image']['name'])) {
                $custom_upload_folder = 'user-profile'; // Replace with your custom folder name
                $upload_dir = wp_upload_dir();
                $upload_path = wp_upload_dir()['baseurl'] . '/' . $custom_upload_folder . '/';
                $uploaded_image = $_FILES['profile_image'];
                $image_name = basename($uploaded_image['name']);
                $image_path = $upload_path . $image_name;
                $upload_result = wp_upload_bits($image_name, null, file_get_contents($uploaded_image['tmp_name']));
                if ($upload_result['error'] === false) {
                    $attachment = array(
                        'guid' => $upload_result['url'],
                        'post_mime_type' => $uploaded_image['type'],
                        'post_title' => sanitize_file_name($image_name),
                        'post_content' => '',
                        'post_status' => 'inherit'
                    );

                    $attachment_id = wp_insert_attachment($attachment, $upload_result['file']);
                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                    $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload_result['file']);
                    wp_update_attachment_metadata($attachment_id, $attachment_data);

                    update_user_meta($current_user->ID, 'profile_image_url', $upload_result['url']);
                } else {
                    echo "Error uploading image: " . $upload_result['error'];
                }

            }

        }

    }


    if ($current_user instanceof WP_User) {
        $first_name = $current_user->first_name;
        $last_name = $current_user->last_name;
        $email = $current_user->user_email;
        $profile_image = get_avatar_url($current_user->ID);

    }
    $avatar_url = get_user_meta($current_user->ID, 'profile_image_url', true);
    if(!empty($avatar_url)){
        $profile_image =  $avatar_url;
    }
    ?>
    <form method="POST" action="#" enctype="multipart/form-data" class="my-profile-form">
        <div class="my-profile-main-container">
            <div class="error-message-row">
                <p><?php if(!empty($error_message)){ echo $error_message; }?></p>
            </div>
            <div class="top-image-upload-row">
                <div class="image-column-profile">
                    <img src="<?=esc_attr($profile_image)?>" class="user-profile-image">
                    <div class="profile-image-text">
                        <h3>Mettre à jour la photo de profil</h3>
                        <p>Format de la photo jpeg, png</p>
                    </div>
                </div>
                <div class="image-upload-button">
                    <input type="file" id="file-upload"  name="profile_image" class="form-field-profile" accept="image/jpeg, image/png">
                    <label for="file-upload" class="custom-button">
                        <img src="http://myleads.fr/wp-content/uploads/2023/08/file-upload.png" class="file-upload-image">
                        <p class="upload-photo-text">Mettre en ligne la photo</p>
                    </label>
                </div>

            </div>
            <div class="form-field-section top-section-fields">
                <div class="field-row">
                    <div class="field-column">
                        <label>Prénom</label>
                        <input type="text" name="user_first_name" class="user-profile-field" value="<?=$first_name?>">
                    </div>
                    <div class="field-column">
                        <label>Nom</label>
                        <input type="text" name="user_last_name" class="user-profile-field" value="<?=$last_name?>">
                    </div>
                </div>
                <div class="field-row-one-field">
                    <div class="field-column one-column-field">
                        <label>Email</label>
                        <input type="text" name="user_email" class="user-profile-field" value="<?=$email?>" readonly>
                    </div>
                </div>
            </div>
            <div class="form-field-section bottom-section-fields">
                <div class="field-row-one-field">
                    <div class="field-column one-column-field">
                        <label>Ancien mot de passe</label>
                        <input type="password" name="user_old_password" class="user-profile-field" palceholder="Enter Old Password first">
                    </div>
                </div>
                <div class="field-row">
                    <div class="field-column">
                        <label>Nouveau mot de passe</label>
                        <input type="password" name="user_new_password" class="user-profile-field">
                    </div>
                    <div class="field-column">
                        <label>Confirmer le mot de passe</label>
                        <input type="password" name="user_confirm_pass" class="user-profile-field">
                    </div>
                </div>
            </div>

        </div>

        <div class="form-button-section">
            <div class="form-cancel-btn-col">
                <button type="button" class="cancel-button-profile">Retour</button>
            </div>
            <div class="form-submit-button">
                <input type="submit" name="update_user_profile" value="Envoyer" class="user-profile-update-button">
            </div>
        </div>

    </form>
    <?php
    return ob_get_clean();
}

add_shortcode('conditioanl_header','conditioanl_header');
function conditioanl_header(){
    ob_start();
    $available_credits = 0;
    if (is_user_logged_in()) {
        $current_user_id = get_current_user_id();
        $avatar_url = get_user_meta(get_current_user_id(), 'profile_image_url', true);
        if(!empty($avatar_url)){
            $profile_image =  $avatar_url;
        }else{
            $profile_image = get_avatar_url(get_current_user_id());
        }

        // if(!empty(get_user_meta( $current_user_id, "user_credits", true ))){
        //     $available_credits = get_user_meta( $current_user_id, "user_credits", true );
        // }else{
        //     $available_credits = 0;
        // }
        ?>
        <div class="main-header-container-menu">
        <div class="logo-row">
        <a href="https://myleads.fr/">
        <img src="http://myleads.fr/wp-content/uploads/2023/11/my-lead-logo.png" class="logo-image">
        </a>
        </div>
        <div class="main-header-side-row">
            <!-- <div class="user-credits logged-in-user-credits"><p><?=$available_credits?><span class="credit-text-header"> Crédit(s)</span></p></div> -->
            <div class="buy-credits-link"><a href="https://myleads.fr/buy-leads/">Acheter des leads</span></div>
            <div class="my-leads-link"><a href="https://myleads.fr/my-leads/">Mes leads</a></div>
            <div class="user-profile-image-header">
                <img src="<?=$profile_image?>" class="header-top-image">
                <img src="http://myleads.fr/wp-content/uploads/2023/08/arrowdown2.png" class="dropdown-icon-header">

                  <div class="header-dropdown-area">
            <ul class="header-hidden-menu-ul">
            <li><a href="https://myleads.fr/my-profile/" class="my-acc-link header-menu-link">Mon compte</a></li>
             <li><a href="https://myleads.fr/my-invoices/" class="my-invoices-link header-menu-link">Mes factures
</a></li>
<!--               <li><a href="https://myleads.fr/support/" class="support-link header-menu-link"><?php //echo "Support"; ?></a></li> -->
               <li><a href="<?php echo wp_logout_url(home_url()); ?>" class="logout-link header-menu-link">Déconnexion</a></li>
            </ul>
        </div>
        </div>
        <?php
    } else {
?>
        <div class="main-header-container-menu">
        <div class="logo-row">
			<a href="https://myleads.fr/">  <img src="http://myleads.fr/wp-content/uploads/2023/11/my-lead-logo.png" class="logo-image"> </a>
        </div>
        <div class="main-header-side-row">
<!--             <div class="user-credits logged-out-user-credit"><p><?=$available_credits?> Crédit(s)</p></div> -->
            <div class="user-login-link"><a href="https://myleads.fr/nos-leads/">Découvrir nos leads</a></div>
            <div class="user-regist-link"><a href="https://myleads.fr/sign-up">Mon compte</a></div>
        </div>
         </div>
<?php
    }
    return ob_get_clean();
}

add_action('wp_ajax_user_user_another_login', 'user_another_login');
add_action('wp_ajax_nopriv_user_another_login', 'user_another_login');
function user_another_login(){

 
    $user_email = $_POST['data']['user_email'];
    $user_password = $_POST['data']['user_password'];
    $remember_me = $_POST['data']['remember_me'];
$remember = false;

       if (is_email($user_email)) {
        $user = get_user_by('email', $user_email);
    } else {
        $user = get_user_by('login', $user_email);
    }

    if (!$user) {
          $errormessage = 'Invalid please try again';
           $response = array('success' => false, 'message' => $errormessage);
         
    }else{
        if($remember_me == "yes"){
            $remember = true;
        }
        
         // Attempt to sign in the user
    $sign_in_result = wp_signon(array(
        'user_login' => $user->user_login,
        'user_password' => $user_password,
        'remember' => $remember,
    ));
     $response = array('success' => true, 'message' => "loggedin");
         wp_send_json($response);
    }
    wp_send_json($response);
}

add_shortcode('user_sign_in_form','user_sign_in_form');
function user_sign_in_form(){
     ob_start();
  
     if(isset($_POST['user_sign_in'])){
         
    if (is_email($_POST['user_name_email'])) {
        $user = get_user_by('email', $_POST['user_name_email']);
    } else {
        $user = get_user_by('login', $_POST['user_name_email']);
    }

    if (!$user) {
          $errormessage = 'Invalid please try again';
    }else{
        $remember = isset($_POST['rememberme']) ? true : false;
         // Attempt to sign in the user
    $sign_in_result = wp_signon(array(
        'user_login' => $user->user_login,
        'user_password' => $_POST['user_sign_in_pwd'],
        'remember' => $remember,
    ));
    }



    if (is_wp_error($sign_in_result)) {
         $errormessage = 'Invalid please try again';

    }else{
            wp_safe_redirect(home_url());
    exit;
    }


     }
 ?>
  <div class="buy-follower-overlay full-overlay flex-wrap-class logged-out-user-page">
<div class="spinner"></div>
<div class="message-row">
<p class="messageText">S'il vous plaît, attendez</p>
<p class="messageText redirecttext">Ne fermez pas la fenêtre</p>

</div>
</div>
    <div class="sign-in-main-container">
            <div class="signin-form-row">
              <p class="error-message-section">
<?php
            if(!empty($errormessage)){
                echo $errormessage;
            }
?></p>
     <form id="loginform" action="#" method="post">
     <p class="login-username">
				<label for="user_login">E-mail ou nom d'utilisateur</label>
				<input type="text" name="user_name_email" id="user_login" autocomplete="username" class="input" value="" size="20" placeholder="ex: john@email.com" required>
			</p>
			<p class="login-password">
				<label for="user_pass">Mot de passe</label>
				<input type="password" name="user_sign_in_pwd" id="user_pass" autocomplete="current-password" spellcheck="false" class="input" value="" size="20" placeholder="i.e. IAmthepreciouspass123 " required>
			</p>
			<p class="login-remember">
			<label>
			<input name="rememberme" type="checkbox" id="rememberme"> Gardez-moi connecté</label>
			</p>
<?php
            if(isset($_GET['data'])){

            
          $decodedData = urldecode($_GET['data']);
            
               $decodedData2 = json_decode(stripslashes($decodedData), true);

             // $encodedData = urlencode(http_build_query($decodedData2)); 
        $lead_department = $decodedData2['lead_dept'];
        $lead_category = $decodedData2['lead_cat'];
        $lead_qty = $decodedData2['num_of_leads'];
?>
<p class="login-submit">
    <input type="hidden" value="<?=$lead_department?>" class="lead-dept-hidden">
    <input type="hidden" value="<?=$lead_category?>" class="lead-cat-hidden">
    <input type="hidden" value="<?=$lead_qty?>" class="lead-qty-hidden">
                <button type="button"class="user-sign-in-button-logout">Se connecter</button>
            </p>
<?php
            }else{
?>
            <p class="login-submit">
                <input type="submit" name="user_sign_in"  class="user-sign-in-button" value="Se connecter">
            </p>    
<?php
            }
?>            
	
			</form>
                <p class="forget-pass-area">Mot de passe oublié?<a href="https://myleads.fr/reset-password" class="reset-pass-link">Réinitialisez maintenant !</a></p>
            </div>

</div>

<?php
 return ob_get_clean();
}

add_action('init', 'custom_logout_handler');

function custom_logout_handler() {
    if (isset($_GET['logout']) && $_GET['logout'] === 'custom-page') {
        wp_logout();
        wp_redirect(home_url('/'));
        exit;
    }
}



add_shortcode('contact_form','contact_form');
function contact_form(){
    if(isset($_POST['contact'])){
        $to = "contact@myleads.fr";
        $subject = "My Leads" ." " . $_POST['subject'];
        $email_message = "Nom : " . " ". $_POST['contact_name']. "<br/>";
         $email_message .= "Prénom : " . " " . $_POST['subject'] . "<br/>";
		$email_message .= "Email : " . " " . $_POST['contact_email'] . "<br/>";

        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        $headers[] = 'From: My Leads <info@myleads.fr>';

       $email_sent = wp_mail($to, $subject, $email_message, $headers);
         if($email_sent){
                $email_sent_message =  "E-mail envoyé avec succès.";
            }else{
            $email_sent_message = "Échec de l'envoi de l'e-mail.";
            }
    }
 ?>
    <div class="contact-in-main-container">
            <div class="contact-form-row">
              <p class="email-message-section">
<?php
            if(!empty($email_sent_message)){
                echo $email_sent_message;
            }
?></p>
      <form id="contactform" action="#" method="post">
        <p class="contact-name">
            <label for="contact_name">Nom</label>
            <input type="text" name="contact_name" id="contact_name" class="input" value="" size="20" placeholder="ex: John Ibram" required>
        </p>
		  
		   <p class="contact-subject">
            <label for="subject">Prénom</label>
            <input type="text" name="subject" id="subject" class="input" value=""  required>
        </p>
		  
        <p class="contact-email">
            <label for="contact_email">Email</label>
            <input type="text" name="contact_email" id="contact_email" class="input" value="" placeholder="ex: john@email.com" required>
        </p>
       
<!--         <p class="contact-message">
            <label for="subject">Message</label>
            <textarea name="message" id="message" class="input input_textarea" value="" rows="10" placeholder ="Add a detailed description (Max 500 words)" required></textarea>
        </p> -->
        <p class="contact-submit">
            <input type="submit" name="contact" class="contact_btn" value="Envoyer">
        </p>
    </form>
            </div>

</div>

<?php
}

add_shortcode('counter_text','counter_text');
function counter_text(){
     ob_start();
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
<div class="counter">
<p>Approuvé par
  <span class="count">0</span>+<br/>
  clients satisfaits
  </p>
</div>
<script>
jQuery(document).ready(function($) {
  var $counter = $('.count');
  var targetValue = 2000; // The target value you want to reach
  var animationDuration = 2000; // Animation duration in milliseconds

  // Initialize the counter text to 0
  $counter.text('0');

  // Use Waypoints to trigger the animation when the .counter element is in the viewport
  $counter.waypoint(function() {
    $counter.prop('Counter', 0).animate({
      Counter: targetValue
    }, {
      duration: animationDuration,
      easing: 'swing',
      step: function(now) {
        $counter.text(Math.ceil(now));
      }
    });
  }, {
    offset: 'bottom-in-view',
    triggerOnce: true
  });
});
</script>


<?php
    return ob_get_clean();
}

add_shortcode('sliderimages','sliderimages');
function sliderimages(){
	 ob_start();
?>
			 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
			<style>
			  .swiper {
      width: 100%;
      height: 100%;
    }

    .swiper-slide {
      text-align: center;
      font-size: 18px;
      background: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .swiper-slide img {
      display: block;
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .swiper-slide {
      width: 80%;
    }

 
  </style>
			  <div class="swiper mySwiper">
    <div class="swiper-wrapper">
      <div class="swiper-slide">
      <img src="http://myleads.fr/wp-content/uploads/2023/11/image-1-1.png" class="slider-image">
      <p class="slider-text">Nos leads photovoltaïques sont constituer de la façon suivante</p>
      </div>
      <div class="swiper-slide">
      <img src="http://myleads.fr/wp-content/uploads/2023/11/image2.png" class="slider-image">
      <p class="slider-text">Nos leads photovoltaïques sont constituer de la façon suivante</p>
      </div>
      <div class="swiper-slide">
      <img src="http://myleads.fr/wp-content/uploads/2023/11/image3.png" class="slider-image">
      <p class="slider-text">Nos leads photovoltaïques sont constituer de la façon suivante</p>
      </div>
      <div class="swiper-slide">
      <img src="http://myleads.fr/wp-content/uploads/2023/11/image4.png" class="slider-image">
      <p class="slider-text">Nos leads photovoltaïques sont constituer de la façon suivante</p>
      </div>
 
    </div>
   <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
  </div>
			 <div class="swiper-pagination"></div>
			<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
			  <script>
    var swiper = new Swiper(".mySwiper", {
      slidesPerView: "auto",
      spaceBetween: 30,
       navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
		 loop: true,

      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
    });
  </script>
<?php			
	return ob_get_clean();
}