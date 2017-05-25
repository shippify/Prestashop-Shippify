# Prestashop Shippify Module

<img src="http://startupbrasil.org.br/wp-content/uploads/2014/12/shippify_logo_big.png" alt="Drawing" style="max-height: 135px; max-width: 522px; text-align:center;"/>

- **Contributors:**  [Álvaro Ortiz](https://github.com/AJShippify), [Leonardo Kuffo](https://github.com/lkuffo/)
- **Tags:** Shipping, delivery, shippify
- **Version:** 0.2
- **Compatible With:** Prestashop 1.6.x

Deliver your Prestashop shop products in less than 24h. Integration between Prestashop and Shippify. 

## Description ##

[Shippify](http://www.shippify.co/) is a technology company that offers their clients **Same-Day Delivery** shipping for their products. This module allows Prestashop store owners to use Shippify as a delivery method in their shop. 

The module currently offers the following functionalities:

- Shippify orders admin panel. 
- Dispatch/Ship orders with shippify.
- Bulk orders dispatch.
- Bulk orders drafts creation.
- Shippify as a carrier in Checkout.
- Shippify order tracking.

## Installation ##

### At Host File System: ###

1. Clone this repo `git clone https://github.com/shippify/Prestashop-Shippify.git` or download it as a zip file.
2. Drag the **shippify** folder to your host server's **modules** directory.
3. At Prestashop, go to **Modules and Services** tab.
4. Type `Shippify` at the Modules List search box.
5. Click on the **Install** button to install the module.

### At Prestashop: ###

1. Clone this repo `git clone https://github.com/shippify/Prestashop-Shippify.git` or download it as a zip file.
2. In your dashboard go to **Modules and Services** tab.
3. In the upper right corner click on **Add new module**.
4. Click on **Choose a File**.
5. Select a .zip file containing the **shippify** folder.
6. Click on **Upload This Module**.
7. At Prestashop, go to **Modules and Services** tab.
8. Type `Shippify` at the Modules List search box.
9. Click on the **Install** button to install the module.

![Finding shippify module](https://cloud.githubusercontent.com/assets/14082276/20902554/9bfb1c80-bb05-11e6-8b14-2a1e987a3f94.png)

## Settings ##

#### Requirements
##### [Shippify Account](http://shippify.co/companies#empresas-form)
     Before you can provide shippify as a shipping method in your shop, you need an APP ID and an APP SECRET. 

To use the module correctly, the shop owner should follow these instructions to configure the settings:

1. Configure Shippify API Settings: 
    0. Go to the **Modules and Services** tab, type `Shippify` at the Modules List search box and click on the **Configure** button to access the Shippify configurations.
    1. Enter the APP ID and the APP SECRET provided to you, as they are in the Shippify Dashboard Settings.
    2. Enter the Warehouse ID from which you are going to dispatch your products. 
    3. Enter an E-mail for support contact if something unexpected happened.
    4. Select the zone for shippify to operate in.  

2. Configure Shippify on Carrier:
    1. Go to the **Shipping** -> **Carriers** tab.
    2. Select the Shippify Carrier.
    3. Go to the **Shipping locations and costs** tab.
    4.  Select the zones for shippify to operate in.

3. You are ready to go! 

## How it works ##

1. At the moment a customer makes a purchase from your store, a Shippify Order is created. If the order is not processed, you will have to create the Shippify Order manually. You may do this one-by-one by accessing the order detail and click on **Confirm Shipment**, or in bulk (**Create Shippify Draft**).
2. You can manage the pending shippify orders from your Prestashop Store in the **Orders** -> **Shippify Orders** tab.
3. Once you select an order and dispatched it (Ship!), the associated task will be created in the Shippify server and will be ready to be dispatched by our Shippers to your customers.
4. Already dispatched orders will have a Track button, which redirects to the Shippify's task tracking page, in which you can check the order shippify status.
5. You can further manage your tasks from your shippify dashboard.

### Recomendations ###

- Make tests to make sure the module is well configured.
- If there is an unexpected error or malfunctioning please [report us](https://shippify.slack.com/messages/integrations).

## Comming Soon ##

- Multiple Warehouses support. 

## Screenshots ##

### Shippify Orders Tab: ###
<p align="center"><img width="400" alt="screen shot 2016-12-05 at 3 15 49 pm" src="https://cloud.githubusercontent.com/assets/14082276/20902551/9bf4afb2-bb05-11e6-8598-75e808c82084.png"></p>

### Shippify Orders Admin Panel: ###
<p align="center"><img width="600" alt="screen shot 2016-12-05 at 3 10 37 pm" src="https://cloud.githubusercontent.com/assets/14082276/21063130/12ac4c54-be22-11e6-9152-6acc1c78589b.png"></p>

### Module Configurations: ###
<p align="center"><img src="https://cloud.githubusercontent.com/assets/14082276/20902546/9be0eda6-bb05-11e6-937a-849afa54c4e2.png" width="600" /></p>
