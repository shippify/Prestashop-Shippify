# Prestashop Shippify Module

<img src="http://startupbrasil.org.br/wp-content/uploads/2014/12/shippify_logo_big.png" alt="Drawing" style="max-height: 135px; max-width: 522px; text-align:center;"/>

- **Contributors:**  [Álvaro Ortiz](https://github.com/AJShippify), [Leonardo Kuffo](https://github.com/lkuffo/), [Ruben Carvajal](https://github.com/rubancar/)
- **Tags:** Shipping, delivery, shippify
- **Version:** 0.4
- **Compatible With:** Prestashop 1.7.x

Deliver your Prestashop shop products in less than 24h. Integration between Prestashop and Shippify. 

## Description ##

[Shippify](http://www.shippify.co/) is a technology company that offers their clients **Same-Day Delivery** shipping for their products. This module allows Prestashop store owners to use Shippify as a delivery method in their shop. 

The module currently offers the following functionalities:

- Shippify orders admin panel. 
- Dispatch/Ship orders with shippify.
- Bulk orders dispatch.
- Bulk orders drafts creation.
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

![Finding shippify module](https://i.imgur.com/opQFYdB.png)

## Settings ##

#### Requirements
##### [Shippify Account](http://shippify.co/companies#empresas-form)
     Before you can provide shippify as a shipping method in your shop, you need an APP ID and an APP SECRET. 

To use the module correctly, the shop owner should follow these instructions to configure the settings:

1. Configure Shippify API Settings: 
    0. Go to the **Modules and Services** tab, type `Shippify` at the Modules List search box and click on the **Configure** button to access the Shippify configurations.
    1. Enter the APP ID and the APP SECRET provided to you, as they are in the Shippify Dashboard Settings (contact us at engineering@shippify.co for support in this matter).
    2. Enter the Warehouse ID from which you are going to dispatch your products (contact us at engineering@shippify.co for support in this matter). 
    3. Enter an E-mail for support contact if something unexpected happened.
    4. Select the zone for shippify to operate in.  

2. You are ready to go! 

## How it works ##

1. At the moment a customer makes a purchase from your store, a Shippify Order is created. If the order is not processed, you will have to create the Shippify Order manually. You may do this one-by-one by accessing the order detail and click on **Confirm Shipment**, or in bulk (**Create Shippify Draft**).
2. You can manage the pending shippify orders from your Prestashop Store in the **Orders** -> **Shippify Orders** tab. Here you can also send them to create in our system (Clicking on: DISPATCH ORDER button).
3. Once you select an order and dispatched it (DISPATCH ORDER button), the associated task will be created in the Shippify server and will be ready to be dispatched by our Shippers to your customers.
4. Already dispatched orders will have a Track button, which redirects to the Shippify's task tracking page, in which you can check the order shippify status. In addition to this, they will have a Detail button, which redirect to the Shippify's dashboard detail.
5. You can further manage your tasks from your shippify dashboard.

### Recomendations ###

- Make tests to make sure the module is well configured.
- If there is an unexpected error or malfunctioning please [report us](https://shippify.slack.com/messages/integrations).

## Comming Soon ##

- Multiple Warehouses support. 

## Screenshots ##

### Shippify Orders Tab: ###
![Orders Tab](https://i.imgur.com/skiRRnp.png)

### Shippify Orders Admin Panel: ###
![Shippify Orders Admin Panel](https://i.imgur.com/hJ1VuSp.png)

### Module Configurations: ###
![Module Configurations](https://i.imgur.com/duUM6jy.png)
