<?php
class AdminShippifyOrdersController extends ModuleAdminController
{

  /*
   * Constructor Method
   */
  public function __construct()
  {

    $this->table = 'shippify_order'; // SQL table name, will be prefixed with _DB_PREFIX_
    $this->className = 'ShippifyOrder';  // PHP class name
    $this->bootstrap = true; // use Bootstrap CSS
    $this->allow_export = false; // allow export in CSV, XLS..
    $this->lang = false;
    $this->explicitSelect = true;
    $this->context = Context::getContext();

    $this->_select =  '
    UPPER(case a.status when 0 then "NOT CREATED" when 1 then "CREATED" end) AS shippify_status,
    a.id_order AS id,
    ords.id_currency, ords.reference AS ref,
    ords.id_order AS id_pdf,
    CONCAT(c.`firstname`, \' \', c.`lastname`) AS `customer`,
    osl.`name` AS `osname`,
    os.`color`,
    IF((SELECT so.id_order FROM `' . _DB_PREFIX_ . 'orders` so WHERE so.id_customer = ords.id_customer AND so.id_order < ords.id_order LIMIT 1) > 0, 0, 1) as new,
    country_lang.name as cname,
    IF(ords.valid, 1, 0) badge_success';

    $this->_join = '
    LEFT JOIN `' . _DB_PREFIX_ . 'orders` ords ON a.id_order = ords.id_order
    LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.`id_customer` = ords.`id_customer`)
    LEFT JOIN `'._DB_PREFIX_.'address` address ON address.id_address = ords.id_address_delivery
    LEFT JOIN `'._DB_PREFIX_.'state` state ON state.id_state = address.id_state
    LEFT JOIN `'._DB_PREFIX_.'zone` zone ON zone.id_zone = state.id_zone
    LEFT JOIN `'._DB_PREFIX_.'country` country ON address.id_country = country.id_country
    LEFT JOIN `'._DB_PREFIX_.'country_lang` country_lang ON (country.`id_country` = country_lang.`id_country` AND country_lang.`id_lang` = '.(int)$this->context->language->id.')
    LEFT JOIN `'._DB_PREFIX_.'order_state` os ON (os.`id_order_state` = ords.`current_state`)
    LEFT JOIN `'._DB_PREFIX_.'carrier` AS transporte ON (transporte.`id_carrier` = ords.`id_carrier`)
    LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = '.(int)$this->context->language->id.')';

    $this->_orderBy = 'ords.date_add';
    $this->_orderWay = 'DESC';

    $statuses = OrderState::getOrderStates((int)$this->context->language->id);
    foreach ($statuses as $status) {
      $this->statuses_array[$status['id_order_state']] = $status['name'];
    }

    // Database fields to show in the table
    $this->fields_list = array(
      'id_shippify_order' => array(
        'title' => $this->l(''),
        'filter_key' => 'a!id_shippify_order'
      ),
      'ref' => array(
        'title' => $this->l('ID'),
        'align' => 'text-center',
        'class' => 'fixed-width-xs',
        'filter_key' => 'ords!id_order'
      ),
      'task_id' => array(
        'title' => $this->l('Track #')
      ),
      'readable_status' => array(
        'title' => $this->l('Shippify Status'),
        // 'type' => 'select',
        'color' => 'color',
        // 'list' => array(
        //   '0' => 'Not created',
        //   '1' => 'Created'
        // ),
        'filter_key' => 'a!readable_status'
      ),
      'customer' => array(
        'title' => $this->l('Customer'),
        'havingFilter' => true,
      ),
      'address1' => array(
        'title' => $this->l('Address'),
        'havingFilter' => true,
      ),
      'id_state' => array(
        'title' => $this->l('Neighborhood'),
        'align' => 'left',
        'havingFilter' => true,
        'filter_key' => 'state!name',
      ),
      'id_zone' => array(
        'title' => $this->l('County'),
        'align' => 'left',
        'havingFilter' => true,
        'filter_key' => 'zone!name',
      ),
      'osname' => array(
        'title' => $this->l('Order Status'),
        'type' => 'select',
        'color' => 'color',
        'list' => $this->statuses_array,
        'filter_key' => 'os!id_order_state',
        'filter_type' => 'int',
        'order_key' => 'osname'
      ),
      'date_add' => array(
        'title' => $this->l('Date'),
        'align' => 'text-right',
        'type' => 'datetime',
        'filter_key' => 'ords!date_add'
      )
    );

    // Add action buttons
    $this->addRowAction('shippify');


    // Get the dispatched orders
    $confirmed_orders_sql = "select id_shippify_order, task_id from `" . _DB_PREFIX_ . "shippify_order` where task_id is not null";
    $confirmed_orders = Db::getInstance()->executeS($confirmed_orders_sql);
    
    $get_id_from_order = function ($order_ids, $order) {
      $order_ids[$order['id_shippify_order']] = $order['task_id'];
      return $order_ids;
    };

    $get_tasks_ids = function($order) {
      return $order['task_id'];
    };

    if (count($confirmed_orders) > 0) {
      $shippify_tasks_ids = array_map($get_tasks_ids, $confirmed_orders);
      $joined_tasks_ids = implode(",", $shippify_tasks_ids);
      $api_token = Configuration::get('SHPY_API_TOKEN', '');

      $context = stream_context_create(array(
        'http' => array(
          'method' => 'GET',
          'header' => "Authorization: Basic {$api_token}\r\n" .
          "Content-Type: application/json\r\n"
        )
      ));

      // get all orders status
      $response = @file_get_contents('https://api.shippify.co/v1/prestashop/orders/statuses/?ids=' . $joined_tasks_ids, FALSE, $context);
  
      if ($response !== FALSE) {
        $response_data = json_decode($response, TRUE);

        if ($response_data["code"] !== "ERROR") {
          $all_statuses = $response_data['data']['statuses'];
    
          if (is_array($all_statuses) || is_object($all_statuses)) {
            foreach ($all_statuses as $single_status) {
              $task_id = $single_status['id'];
              $response_readable_status = $single_status['_status'];
              $response_status = $single_status['state'];
          
              $update_status_query = 'UPDATE `' . _DB_PREFIX_ . 'shippify_order` SET `status` = '. $response_status . ' , `readable_status` = \'' . $response_readable_status . '\' WHERE `task_id` = \'' . $task_id . '\'';
          
              Db::getInstance()->execute($update_status_query);
            }
          }
        }
      }
    }

    $confirmed_orders_by_id = array_reduce($confirmed_orders, $get_id_from_order, array());
    $this->confirmed_orders_by_id = $confirmed_orders_by_id;
    
    parent::__construct();

    // Bulk Action
    $this->actions_available[] = 'shippify';
    $this->bulk_actions = array(
      'shippify' => array(
          'text'    => $this->l('Dispacth Selected'),
          'icon'    => 'icon-truck',
          'confirm' => $this->l('Dispatch all of selected items?'),
      ),
    );
  }

  /**
   * Surcharge de la fonction de traduction sur PS 1.7 et supérieur.
   * La fonction globale ne fonctionne pas
   * @param type $string
   * @param type $class
   * @param type $addslashes
   * @param type $htmlentities
   * @return type
   */
  protected function l($string, $class = null, $addslashes = false, $htmlentities = true)
  {
      if ( _PS_VERSION_ >= '1.7') {
          return Context::getContext()->getTranslator()->trans($string);
      } else {
          return parent::l($string, $class, $addslashes, $htmlentities);
      }
  }

  protected function processBulkShippify(){

    foreach ($this->boxes as $id) {
      $this->performShippifyTaskCreation($id);
    }

  }

  /**
   *  Shows the Track or Ship! button, depending on the order status 
   *  @param string $id Shippify order id from the prestashop store
   *  @param string $token Security token
   */
  public function displayShippifyLink($token, $id)
  {
    // Check if order is dispatched
    $order_is_confirmed = array_key_exists($id, $this->confirmed_orders_by_id);

    // Create the template for the button from the shipItLink.tpl file 
    $tpl = $this->context->smarty->createTemplate(_PS_MODULE_DIR_ . '/shippify/views/templates/admin/shipItLink.tpl', $this->context->smarty);

    // Assing action to the template 
    $tpl->assign(array(
      'href' => $order_is_confirmed ? ('https://api.shippify.co/track/' . $this->confirmed_orders_by_id[$id])  : (self::$currentIndex.'&token='.$this->token.'&
      '.$this->identifier.'='.$id.'&shipit'.$this->table.'=1'),
      'action' => $this->l($order_is_confirmed ? 'Rastreo' : 'Despachar con Shippify'),
      'href_detail' => $order_is_confirmed ? ('https://dash.shippify.co/routes/' . $this->confirmed_orders_by_id[$id]) : '',
      'action_detail' => 'Más información'
    ));
    return $tpl->fetch();
  }

  /**
   *  Executed when the shipitLink.tpl button is clicked. 
   *  Calls the task creation method when the url has the shipit parameter
   */
  public function initProcess()
  {
    parent::initProcess();
    if (Tools::getValue('shipit'.$this->table))
    {
      $this->performShippifyTaskCreation(Tools::getValue($this->identifier));
    }
  }

  /**
   *  Creates a shippify task in the dashboard.
   *  @param string $id_shippify_order Shippify order id from the prestashop store.
   */
  public function performShippifyTaskCreation($id_shippify_order)
  {
    // Get configurations
    $api_token = Configuration::get('SHPY_API_TOKEN', '');
    $id_warehouse = Configuration::get('SHPY_WAREHOUSE_ID', '');
    $sender_support_email = Configuration::get('SHPY_SUPPORT_EMAIL', '');
    $sender_support_phone = Configuration::get('SHPY_SUPPORT_PHONE', '');
    $compact_products = Configuration::get('SHPY_COMPACT_PRODUCTS', '');
    $anonimize_products = Configuration::get('SHPY_ANONIMIZE_PRODUCTS', '');
    // If one of them is empty, dont do nothing
    if (empty($api_token)) return FALSE;
    if (empty($id_warehouse)) return FALSE;
    if (empty($sender_support_email)) return FALSE;

    // Get the order info from the database
    $order_sql = 'SELECT *, ords.id_order AS id, ords.reference AS ref, shps.status AS shippify_order_status, ords.total_paid, CONCAT(cuts.firstname, \' \', cuts.lastname) AS customer_name, cuts.email AS customer_email, adrs.phone AS customer_phone, adrs.phone_mobile AS customer_mobile, adrs.address1, adrs.address2, adrs.postcode, adrs.city, adrs.other FROM `' . _DB_PREFIX_ . 'shippify_order` shps INNER JOIN `' . _DB_PREFIX_ . 'orders` ords ON shps.id_order = ords.id_order INNER JOIN `' . _DB_PREFIX_ . 'customer` cuts ON ords.id_customer = cuts.id_customer INNER JOIN `' . _DB_PREFIX_ . 'address` adrs ON ords.id_address_delivery = adrs.id_address WHERE shps.id_shippify_order = ' . $id_shippify_order;
    $order = Db::getInstance()->getRow($order_sql);

    // If the order has already been shipped
    if ($order['shippify_order_status'] == 1) return TRUE;

    if ($anonimize_products === 'SI'){
      // Private Product Name Query
      $products_sql = 'SELECT dets.`product_id` AS id, dets.`product_id` AS name, dets.`product_quantity` AS qty, prds.height, prds.width, prds.depth, 3 as size FROM `' . _DB_PREFIX_ . 'shippify_order` shps INNER JOIN `' .  _DB_PREFIX_ . 'order_detail` dets ON shps.id_order = dets.id_order INNER JOIN `' . _DB_PREFIX_ . 'product` prds ON dets.product_id = prds.id_product WHERE shps.`id_shippify_order` = ' . $id_shippify_order;
    } else {
      // Non Private Product Name Query
      $products_sql = 'SELECT dets.`product_id` AS id, dets.`product_name` AS name, dets.`product_quantity` AS qty, prds.height, prds.width, prds.depth, 3 as size FROM `' . _DB_PREFIX_ . 'shippify_order` shps INNER JOIN `' .  _DB_PREFIX_ . 'order_detail` dets ON shps.id_order = dets.id_order INNER JOIN `' . _DB_PREFIX_ . 'product` prds ON dets.product_id = prds.id_product WHERE shps.`id_shippify_order` = ' . $id_shippify_order;
    }

    $products_array = Db::getInstance()->executeS($products_sql);

    if ($compact_products === 'SI'){
      $get_products_name = function($product){
        return $product["name"];
      };
      $products_name_array = array_map($get_products_name, $products_array);
      $products_array = array (
        array(
          "name" => implode(" | ", $products_name_array),
          "qty" => 1,
          "size" => 3
        )
      );
    }

    $address2 = $order['address1'] . ', ' . $order['address2'] . ', ' . $order['city'] . ', ' . $order['other'];
    $address = $order['address1'] . ', ' . $order['city']; 
  
    if (isset($order['numero_house'])) {
      $address2 = $address2 . ' ' . $order['numero_house'];
      $address = $address . ' ' . $order['numero_house'];
    }

    if(isset($order['dpto_house'])) {
      $address2 = $address . ' ' . $order['dpto_house'];
      $address = $address . ' ' . $order['dpto_house'];
    }
  
    $address = $address . ', ' . $order['city']; 

    // Prepare the request
    $post_data = array(
      'deliveries' => array(
        array(
          'pickup' => array(
            'contact' => array(
              'email' => $sender_support_email,
              'phone' => $sender_support_phone
            ),
            'location' => array(
              'warehouse' => $id_warehouse
            )
          ),
          'dropoff' => array(
            'contact' => array(
              'name' => $order['customer_name'],
              'email' => $order['customer_email'],
              'phonenumber' => (!empty($order['customer_phone']) ? $order['customer_phone'] : $order['customer_mobile']),
            ),
            'location' => array(
              'address' => $address,
              'instructions' => $address2
            )
          ),
          'packages' => $products_array,
          'total_amount' => $order['total_paid'],
          'referenceId' => $order['id'], 
          'tags' => array(
            'PRESTASHOP'
          )
        )
      )
    );
    // Authentication
    $context = stream_context_create(array(
      'http' => array(
        'method' => 'POST',
        'header' => "Authorization: Basic {$api_token}\r\n" .
        "Content-Type: application/json\r\n",
        'content' => json_encode($post_data)
      )
    ));

    // Do the request
    $response = @file_get_contents('https://api.shippify.co/v1/deliveries', FALSE, $context);

    // If error, nothing happens
    if ($response === FALSE) return FALSE;
    $response_data = json_decode($response, TRUE);
    // Order has been created, status is set to 1
    $sql = 'UPDATE `' . _DB_PREFIX_ . 'shippify_order` SET `status` = 1, `task_id` = \'' . $response_data['payload'][0]['id'] . '\' WHERE `id_shippify_order` = ' . $id_shippify_order;
    // Update confirmed orders if SQL is successful
    if (Db::getInstance()->execute($sql)) {
      $this->confirmed_orders_by_id[$id_shippify_order] = $response_data['payload'][0]['id'];

      // Change order status to Shipped
      $objOrder = new Order((int)$order['id']);
      $history = new OrderHistory();
      $history->id_order = (int)$objOrder->id;
      $history->changeIdOrderState(4, (int)($objOrder->id));
      return TRUE;
    }
    return FALSE;
  }
}
