# Prestashop-Shippify
Shippify's Prestashop module for easily creating tasks inside the Shippify platform.

<p align="center"><img src="https://cloud.githubusercontent.com/assets/14082276/21063131/12ac5f0a-be22-11e6-9f13-591a527eb3c8.png" width="600"/></p>

## Installation

There are some ways of installing Shippify's Prestashop module:

### Manually

Cloning this repo and inserting it in your host server is the preferred method for the moment.

1. Clone this repo `git clone https://github.com/shippify/Prestashop-Shippify.git` or download it as a zip file.
2. Drag the **shippify** folder to your host server's **modules** directory.
3. Done.

## Getting started

No matter how you install your module, once it resides in your modules folder it will be found in the Modules tab from your store.

![Finding shippify module](https://cloud.githubusercontent.com/assets/14082276/20902554/9bfb1c80-bb05-11e6-8b14-2a1e987a3f94.png)

Once you add it to your store, Prestashop will guide you to the Configuration window. If the redirections does not occurr, click on Configure.

### Configuration

<p align="center"><img src="https://cloud.githubusercontent.com/assets/14082276/20902550/9be95e82-bb05-11e6-85b6-8633a58c7f9a.png" width="480" /></p>

All the fields are required to create tasks using the module.

- **Api Id & Secret:** Shippify issued credentials for your company.
- **Warehouse Id:** Shippify warehouses can be created from your company's Shippify dashboard. Create one with your pickup location's address and insert it's id here. Get your warehouse ids from https://api.shippify.co/warehouse/list.
- **Company support email:** Sometimes a little help is needed at the moment of pickup. Provide an email for support.

<p align="center"><img src="https://cloud.githubusercontent.com/assets/14082276/20902546/9be0eda6-bb05-11e6-937a-849afa54c4e2.png" width="600" /></p>

## Module flow
At the moment a customer makes a purchase from your store, after going through checkout, a special Shippify order is created as well in your Prestashop store database.

<p align="center"><img width="600" alt="screen shot 2016-12-05 at 3 09 45 pm" src="https://cloud.githubusercontent.com/assets/14082276/21063129/12abe96c-be22-11e6-8639-0e4cb9e572bd.png"></p>

This order holds a link with a task in the Shippify's service database. You can manage the pending shippify orders from your Prestashop Store in the Orders > Shippify Orders.

<p align="center"><img width="400" alt="screen shot 2016-12-05 at 3 15 49 pm" src="https://cloud.githubusercontent.com/assets/14082276/20902551/9bf4afb2-bb05-11e6-8598-75e808c82084.png"></p>

Once you select an order and Ship It!!!, the associated task will be created in the Shippify server and will be ready to be dispatched by our Shippers to your customers.

<p align="center"><img width="600" alt="screen shot 2016-12-05 at 3 10 37 pm" src="https://cloud.githubusercontent.com/assets/14082276/21063130/12ac4c54-be22-11e6-9152-6acc1c78589b.png"></p>

Already confirmed orders will have instead a Track button, which redirects to the Shippify's task tracking page.

You can further manage your tasks from your shippify dashboard in https://admin.shippify.co.
