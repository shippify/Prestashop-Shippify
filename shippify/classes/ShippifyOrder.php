<?php
class ShippifyOrder extends ObjectModel {
  public $id_shippify_order;
  public $id_order;
  public static $definition = array(
    'table' => 'shippify_order',
    'primary' => 'id_shippify_order',
    'multilang' => false,
    'fields' => array(
      'id_shippify_order' => array('type' => self::TYPE_INT, 'required' => true),
      'id_order' => array('type' => self::TYPE_INT, 'required' => true),
      'status' => array('type' => self::TYPE_INT, 'required' => true)
    )
  );
}