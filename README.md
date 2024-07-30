# PrestaShop Shippify Module

<div align="center">
    <img src="https://cdn.shippify.co/dash/users/img/users-shippify-logo.svg" alt="Shippify" style="max-height: 135px; max-width: 522px;" />
</div>

<br>

- **Contributors:**  [Álvaro Ortiz](https://github.com/AJShippify), [Leonardo Kuffo](https://github.com/lkuffo/), [Ruben Carvajal](https://github.com/rubancar/), [Matheus de Paula](https://github.com/matheus-depaula/)
- **Tags:** Shipping, delivery, shippify
- **Version:** 0.5.1
- **Compatible With:** PrestaShop 1.7.x and 8.x
- **Documentation:** https://docs.shippify.co/developers/v/en/e-commerce/prestashop

Deliver your PrestaShop shop products in less than 24h. Integration between PrestaShop and Shippify. 

## Description ##

[Shippify](http://www.shippify.co/) is a technology company that offers their clients **Same-Day Delivery** shipping for their products. This module allows PrestaShop store owners to use Shippify as a delivery method in their shop. 

The module currently offers the following functionalities:

- Shippify orders admin panel. 
- Dispatch/Ship orders with shippify.
- Bulk orders dispatch.
- Bulk orders drafts creation.
- Shippify order tracking.

## Installation ##

### At Host File System: ###

1. Get the latest release [here](https://github.com/shippify/Prestashop-Shippify/releases) by downloading the **shippify.zip** file.
2. Drag the **shippify** folder to your host server's **modules** directory.
3. At PrestaShop, go to **Modules and Services** tab.
4. Type **Shippify** at the modules list search box.
5. Click on the **Install** button to install the module.

### At PrestaShop: ###

1. Get the latest release [here](https://github.com/shippify/Prestashop-Shippify/releases) by downloading the **shippify.zip** file.
2. In your panel, go to the "Modules and Services" tab. In the upper right corner, click at the **Add new module** button.
4. Click to choose a file and upload the **shippify.zip** file you have downloaded.
6. Click on **Upload This Module**.
7. At PrestaShop, go to **Modules and Services** tab.
8. Type **Shippify** at the modules list search box.
9. Click on the **Install** button to install the module.

![Finding shippify module](https://i.imgur.com/opQFYdB.png)

## Settings ##

### Requirements

#### [Shippify Account](http://shippify.co/companies#empresas-form)
    Before you can provide shippify as a shipping method in your shop, you need an APP ID and an APP SECRET. 

To use the module correctly, the shop owner should follow these instructions to configure the settings:

1. Go to the **Modules and Services** tab, type `Shippify` at the Modules List search box and click on the **Configure** button to access the Shippify configurations.

2. Enter the APP ID and the APP SECRET provided to you, as they are in the Shippify Dashboard Settings (contact us at engineering@shippify.co for support in this matter).

3. Enter the Warehouse ID from which you are going to dispatch your products (contact us at engineering@shippify.co for support in this matter).

4. Enter an E-mail for support contact if something unexpected happened.

5. Select the zone for shippify to operate in.

Aftere this, you are ready to go!

## How it works ##

1. At the moment a customer makes a purchase from your store, a Shippify Order is created. If the order is not processed, you will have to create the Shippify Order manually. You may do this one-by-one by accessing the order detail and click on **Confirm Shipment**, or in bulk (**Create Shippify Draft**).

2. You can manage the pending shippify orders from your PrestaShop Store in the **Orders** -> **Shippify Orders** tab. Here you can also send them to create in our system (Clicking on: DISPATCH ORDER button).

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
