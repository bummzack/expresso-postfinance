<?php

class Store_postfinance_ext
{
    public $name = 'Store Postfinance';
    public $version = '1.0.0';
    public $description = 'Postfinance for Expresso Store';
    public $settings_exist = 'n';
    public $docs_url = 'https://github.com/bummzack/expresso-postfinance/';

    public function activate_extension()
    {
        $data = array(
            'class'     => __CLASS__,
            'method'    => 'store_payment_gateways',
            'hook'      => 'store_payment_gateways',
            'priority'  => 10,
            'settings'  => '',
            'version'   => $this->version,
            'enabled'   => 'y'
        );

        ee()->db->insert('extensions', $data);
    }

    /**
     * This hook is called when Store is searching for available payment gateways
     * We will use it to tell Store about our custom gateway
     */
    public function store_payment_gateways($gateways)
    {
        // allow multiple extensions to use this hook
        // see: http://ellislab.com/expressionengine/user-guide/development/extensions.html#multiple-extensions-same-hook
        if (ee()->extensions->last_call) {
            $gateways = ee()->extensions->last_call;
        }

        // tell Store about our new payment gateway
        // (this must match the name of your gateway in the Omnipay directory)
        $gateways[] = 'Postfinance';

        // tell PHP where to find the gateway classes
        // Store will automatically include your files when they are needed
        $composer = require(PATH_THIRD.'store/autoload.php');
        $composer->addPsr4('Omnipay\\Postfinance\\', __DIR__ .'/Omnipay/Postfinance/src');
        
        return $gateways;
    }
}
