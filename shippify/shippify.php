<?php
require_once(dirname(__FILE__).'/classes/ShippifyOrder.php');
class Shippify extends Module
{
  public function __construct()
  {
    $this->name = 'shippify';
    $this->tab = 'shipping_logistics';
    $this->version = '0.1';
    $this->author = 'Álvaro Ortiz';
    $this->bootstrap = true;
    parent::__construct();
    $this->displayName = $this->l('Shippify');
    $this->description = $this->l('Add easy shipping at checkout with Shippify.');
  }
  public function install()
  {
    if (
      parent::install() &&
      $this->registerHook('displayAdminOrderTabShip') &&
      $this->registerHook('actionValidateOrder') &&
      $this->createShippifyOrdersTable() &&
      $this->installTab('AdminOrders', 'AdminShippifyOrders', 'Shippify Orders')
    ) {
      return TRUE;
    }
    return FALSE;
  }
  public function installTab($parent, $class_name, $name)
  {
    $tab = new Tab();
    $tab->id_parent = (int)Tab::getIdFromClassName($parent);
    $tab->name = array();
    foreach (Language::getLanguages(true) as $lang)
    {
      $tab->name[$lang['id_lang']] = $name;
    }
    $tab->class_name = $class_name;
    $tab->module = $this->name;
    $tab->active = 1;
    return $tab->add();
  }
  public function createShippifyOrdersTable()
  {
    $sql = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'shippify_order` (`id_shippify_order` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `id_order` INT(11) NOT NULL, `status` TINYINT(1) NOT NULL DEFAULT 0, `task_id` VARCHAR(20) DEFAULT NULL)';
    return Db::getInstance()->execute($sql);
  }
  public function uninstall()
  {
    if (
      $this->uninstallTab('AdminShippifyOrders') &&
      $this->deleteConfigurationValues() &&
      $this->dropShippifyOrdersTable() &&
      parent::uninstall()
    ) {
      return TRUE;
    }
    return FALSE;
  }
  public function uninstallTab($class_name)
  {
    $id_tab = (int)Tab::getIdFromClassName($class_name);
    $tab = new Tab($id_tab);
    return $tab->delete();
  }
  public function deleteConfigurationValues()
  {
    if (
      Configuration::deleteByName('SHPY_API_TOKEN') &&
      Configuration::deleteByName('SHPY_WAREHOUSE_ID') &&
      Configuration::deleteByName('SHPY_SUPPORT_EMAIL') &&
      Configuration::deleteByName('SHPY_ZONE')
    ) {
      return TRUE;
    }
    return FALSE;
  }
  public function dropShippifyOrdersTable()
  {
    $sql = 'DROP TABLE `' . _DB_PREFIX_ . 'shippify_order`';
    return Db::getInstance()->execute($sql);
  }
  public function assignConfiguration()
  {
    $api_token = Configuration::get('SHPY_API_TOKEN', '');
    list($api_id, $api_secret) = explode(':', base64_decode($api_token));
    $this->context->smarty->assign('api_id', $api_id);
    $this->context->smarty->assign('api_secret', $api_secret);

    $id_warehouse = Configuration::get('SHPY_WAREHOUSE_ID', '');
    $this->context->smarty->assign('warehouse_id', $id_warehouse);

    $sender_support_email = Configuration::get('SHPY_SUPPORT_EMAIL', '');
    $this->context->smarty->assign('sender_support_email', $sender_support_email);

    $selected_zone_id = Configuration::get('SHPY_ZONE', '-1');
    $available_zones_sql = 'select *, (id_zone = \'' . $selected_zone_id . '\') as selected from `' . _DB_PREFIX_ . 'zone`';
    $available_zones = Db::getInstance()->executeS($available_zones_sql);
    $this->context->smarty->assign('available_zones', $available_zones);

    $this->context->smarty->assign('selected_zone_id', $selected_zone_id);
  }
  public function processConfiguration()
  {
    if (Tools::isSubmit('configuration-submit'))
    {
      $current_api_token = Configuration::get('SHPY_API_TOKEN', '');
      list($current_api_id, $current_api_secret) = explode(':', base64_decode($current_api_token));
      $current_id_warehouse = Configuration::get('SHPY_WAREHOUSE_ID', '');
      $current_sender_support_email = Configuration::get('SHPY_SUPPORT_EMAIL', '');
      $current_operating_zone = Configuration::get('SHPY_ZONE', '');

      $api_id = Tools::getValue('api-id');
      $api_secret = Tools::getValue('api-secret');
      $id_warehouse = Tools::getValue('warehouse-id');
      $sender_support_email = Tools::getValue('sender-support-email');
      $operating_zone = Tools::getValue('operating-zone');

      $have_credentials_changed = strcmp($current_api_id, $api_id) != 0 || strcmp($current_api_secret, $api_secret) != 0;
      $has_id_warehouse_changed = strcmp($current_id_warehouse, $id_warehouse) != 0;
      $has_support_email_changed = strcmp($current_sender_support_email, $sender_support_email) != 0;
      $has_operating_zone_changed = strcmp($current_operating_zone, $operating_zone) != 0;

      $should_update_credentials = empty($current_api_token) || $have_credentials_changed;
      $should_update_id_warehouse = empty($current_id_warehouse) || $have_credentials_changed || $has_id_warehouse_changed;
      $should_update_support_email = empty($current_sender_support_email) || $has_support_email_changed;
      $should_update_operating_zone = $has_operating_zone_changed;

      if ($should_update_credentials || $should_update_id_warehouse)
      {
        if (empty($api_id))
        {
          $this->context->smarty->assign('failure_credentials', $this->l('Shippify API id is missing.'));
        }
        else if (empty($api_secret))
        {
          $this->context->smarty->assign('failure_credentials', $this->l('Shippify API secret is missing.'));
        }
        else
        {
          $api_token = base64_encode($api_id . ':' . $api_secret);
          $ch = curl_init();
          $url = 'https://api.shippify.co/warehouses/' . (!empty($id_warehouse) ? $id_warehouse : 'none') . '/id';

          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Basic $api_token\r\n",
            "Content-Type: application/json\r\n"
          ));
          $output = curl_exec($ch);
          $response = json_decode($output);
          $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
          curl_close($ch);

          $is_server_down = $status >= 500;
          $are_credentials_valid = $status != 401;
          $is_id_warehouse_valid = $status == 200;
          if ($is_server_down) {
            $this->context->smarty->assign('failure_credentials', $this->l('Shippify servers are down for the moment.'));
          } else if ($should_update_credentials) {
            if ($are_credentials_valid)
            {
              if (Configuration::updateValue('SHPY_API_TOKEN', $api_token))
              {
                $this->context->smarty->assign('success_credentials', $this->l('Shippify API credentials have been updated.'));
              }
              else
              {
                $this->context->smarty->assign('failure_credentials', $this->l('Shippify API credentials could not be updated.'));
              }
            }
            else
            {
              $this->context->smarty->assign('failure_credentials', $this->l('Shippify API credentials are invalid.'));
            }
          }
          if (empty($id_warehouse))
          {
            $this->context->smarty->assign('failure_warehouse', $this->l('Shippify warehouse Id is missing.'));
          }
          else if ($is_id_warehouse_valid)
          {
            if (Configuration::updateValue('SHPY_WAREHOUSE_ID', $id_warehouse))
            {
              $this->context->smarty->assign('success_warehouse', $this->l('Your pickup warehouse has been updated.'));
            }
            else
            {
              $this->context->smarty->assign('failure_warehouse', $this->l('Your pickup warehouse could not be updated.'));
            }
          }
          else if (!$are_credentials_valid && !$is_id_warehouse_valid)
          {
            if ($current_id_warehouse != $id_warehouse) {
              $this->context->smarty->assign('failure_warehouse', $this->l('Warehouse info could not be fetched due to invalid API credentials.'));
            }
          }
          else
          {
            $this->context->smarty->assign('failure_warehouse', $this->l('Your company does not possess a warehouse with id: ' . $id_warehouse));
          }
        }
      }

      if ($should_update_support_email) {
        if (empty($sender_support_email)) {
          $this->context->smarty->assign('failure_email', $this->l('Must have an email as pickup contact.'));
        } else if (Configuration::updateValue('SHPY_SUPPORT_EMAIL', $sender_support_email)) {
          $this->context->smarty->assign('success_email', $this->l('Shippify support email has been updated.'));
        } else {
          $this->context->smarty->assign('failure_email', $this->l('Shippify support email could not be updated.'));
        }
      }

      if ($should_update_operating_zone) {
        $operating_zone = (strcmp($operating_zone, '-1') != 0) ? $operating_zone : '';
        if (Configuration::updateValue('SHPY_ZONE', $operating_zone)) {
          $this->context->smarty->assign('success_zone', $this->l('Shippify operating zone has been updated.'));
        } else {
          $this->context->smarty->assign('failure_zone', $this->l('Shippify operating zone could not be updated.'));
        }
      }
    }
  }
  public function getContent()
  {
    $this->processConfiguration();
    $this->assignConfiguration();
    return $this->display(__FILE__, 'getContent.tpl');
  }
  public function assignAdminOrderTabShip()
  {
    $id_order = (int)Tools::getValue('id_order');
    $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'shippify_order` WHERE `id_order` = ' . $id_order;
    $row = Db::getInstance()->getRow($sql);
    if ($row)
    {
      $this->context->smarty->assign('exists', TRUE);
    }
    else
    {
      $this->context->smarty->assign('exists', FALSE);
    }
  }
  public function processAdminOrderTabShip()
  {
    if (Tools::isSubmit('create-order-submit'))
    {
      $id_order = (int)Tools::getValue('id_order');
      $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'shippify_order` (`id_order`) VALUES (' . $id_order . ')';
      if (!Db::getInstance()->execute($sql))
      {
        $this->context->smarty->assign('failure', $this->l('Shippify order could not be created.'));
      }
      else
      {
        $this->context->smarty->assign('success', $this->l('Shippify order has been created.'));
      }
    }
  }
  public function hookDisplayAdminOrderTabShip($params)
  {
    $this->processAdminOrderTabShip();
    $this->assignAdminOrderTabShip();
    return $this->display(__FILE__, 'displayAdminOrderTabShip.tpl');
  }
  public function hookActionValidateOrder($params)
  {
    $id_order = $params['order']->id;
    $operating_zone = Configuration::get('SHPY_ZONE', '');
    if (!empty($operating_zone)) {
      $check_sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'orders` ords LEFT JOIN `'._DB_PREFIX_.'address` address ON address.id_address = ords.id_address_delivery
      LEFT JOIN `'._DB_PREFIX_.'state` state ON state.id_state = address.id_state
      WHERE ords.id_order = \'' . $id_order . '\' and state.id_zone = \'' . $operating_zone . '\'';
      $order = Db::getInstance()->getRow($check_sql);
      if (!$order) {
        return TRUE;
      }
    }
    $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'shippify_order` (`id_order`) VALUES (' . $id_order . ')';
    return Db::getInstance()->execute($sql);
  }
}
