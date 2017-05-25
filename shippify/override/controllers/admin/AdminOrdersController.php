<?php
/**
 * Overrides AdminOrdersController to add Bulk Actions for shippify order draft creation
 */
class AdminOrdersController extends AdminOrdersControllerCore
{
	/**
	 * Constructor
	 */
    public function __construct()
    {
        parent::__construct();
        $this->bulk_actions = array(
	      	'shippifyorder' => array(
	          'text'    => $this->l('Create Shippify Draft'),
	          'icon'    => 'icon-truck',
	          'confirm' => $this->l('Transfer all orders to Shippify Orders?')
	    	)
	    );
	    $this->actions_available[] = "shippifyorder";
	}

	/**
	 * Bulk action processing: Shippify Order Draft Creation
	 */
	public function processBulkShippifyorder(){
		foreach ($this->boxes as $id_order) {
		  	$sql = 'INSERT INTO `' . _DB_PREFIX_ . 'shippify_order` (`id_order`) VALUES (' . $id_order . ')';
		  	Db::getInstance()->execute($sql);
		}
	}
}
