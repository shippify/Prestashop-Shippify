<fieldset>
  <h2>{l s='Shippify Module configuration' mod='shippify'}</h2>

  <div class="panel">
    <div class="panel-heading">
      <legend><img src="../img/admin/cog.gif" alt="" width="16"/>{l s='Configuration' mod='shippify'}</legend>
    </div>
    <form action="" method="post">
      <div class="form-group clearfix">
        <label class="col-lg-3">{l s='Insert Shippify API credentials' mod='shippify'}</label>
        <div class="col-lg-9">
          <label class="t">{l s='API Id:' mod='shippify'}</label>
          <input type="text" id="shpy__configuration__api-id__input" name="api-id" {if isset($api_id)}value="{$api_id}"{/if}/>
          <label class="t">{l s='API Secret:' mod='shippify'}</label>
          <input type="text" id="shpy__configuration__api-secret__input" name="api-secret" {if isset($api_secret)}value="{$api_secret}"{/if}/>
        </div>
        {if isset($success_credentials)}
          <div class="alert alert-success">{$success_credentials}</div>
        {/if}
        {if isset($failure_credentials)}
          <div class="alert alert-danger">{$failure_credentials}</div>
        {/if}
      </div>
      <div class="form-group clearfix">
        <label class="col-lg-3">{l s='Insert your company\'s warehouse id number.' mod='shippify'}</label>
        <div class="col-lg-9">
          <input type="text" id="shpy__configuration__warehouse-id__input" name="warehouse-id" {if isset($warehouse_id)}value="{$warehouse_id}"{/if}/>
        </div>
        <p class="t">{l s='* If you don\'t have one yet, create a warehouse in the shippify platform.' mod='shippify'}</p>
        {if isset($success_warehouse)}
          <div class="alert alert-success">{$success_warehouse}</div>
        {/if}
        {if isset($failure_warehouse)}
          <div class="alert alert-danger">{$failure_warehouse}</div>
        {/if}
      </div>
      <div class="form-group clearfix">
        <label class="col-lg-3">{l s='Insert an email for pickup contact support.' mod='shippify'}</label>
        <div class="col-lg-9">
          <input type="text" id="shpy__configuration__support-email__input" name="sender-support-email" {if isset($sender_support_email)}value="{$sender_support_email}"{/if} placeholder="E.g. support@mycompany.com"/>
        </div>
        {if isset($success_email)}
          <div class="alert alert-success">{$success_email}</div>
        {/if}
        {if isset($failure_email)}
          <div class="alert alert-danger">{$failure_email}</div>
        {/if}
      </div>

      <div class="form-group clearfix">
        <label class="col-lg-3">{l s='Insert a phone number for pickup contact support.' mod='shippify'}</label>
        <div class="col-lg-9">
          <input type="text" id="shpy__configuration__support-phone__input" name="sender-support-phone" {if isset($sender_support_phone)}value="{$sender_support_phone}"{/if} placeholder="E.g. +592992476224"/>
        </div>
        {if isset($success_phone)}
          <div class="alert alert-success">{$success_phone}</div>
        {/if}
        {if isset($failure_phone)}
          <div class="alert alert-danger">{$failure_phone}</div>
        {/if}
      </div>

      <div class="form-group clearfix">
        <label class="col-lg-3">{l s='¿Desea anonimizar el nombre de los productos comprados?' mod='shippify'}</label>
        <div class="col-lg-9">
          <select id="shpy__configuration__operating-anonimize__input" name="anonimize_products" value="{$anonimize_products}">
            <option value="-1">{l s='Selecciona una opción' mod='shippify'}</h3>
            <option value="SI"{if $anonimize_products == 'SI' } selected{/if}>SI</h3>
            <option value="NO"{if $anonimize_products == 'NO' } selected{/if}>NO</h3>
          </select>
        </div>
        {if isset($success_anonimize_products)}
          <div class="alert alert-success">{$success_anonimize_products}</div>
        {/if}
        {if isset($failure_anonimize_products)}
          <div class="alert alert-danger">{$failure_anonimize_products}</div>
        {/if}
      </div>

      <div class="form-group clearfix">
        <label class="col-lg-3">{l s='¿Desea compactar los nombres de los productos comprados en una sola etiqueta?' mod='shippify'}</label>
        <div class="col-lg-9">
          <select id="shpy__configuration__operating-compact__input" name="compact_products" value="{$compact_products}">
            <option value="-1">{l s='Selecciona una opción' mod='shippify'}</h3>
              <option value="SI"{if $compact_products == 'SI' } selected{/if}>SI</h3>
              <option value="NO"{if $compact_products == 'NO' } selected{/if}>NO</h3>
          </select>
        </div>
        {if isset($success_compact_products)}
          <div class="alert alert-success">{$success_compact_products}</div>
        {/if}
        {if isset($failure_compact_products)}
          <div class="alert alert-danger">{$failure_compact_products}</div>
        {/if}
      </div>

      <div class="form-group clearfix">
        <label class="col-lg-3">{l s='Select a zone for shippify to operate in.' mod='shippify'}</label>
        <div class="col-lg-9">
          <select id="shpy__configuration__operating-zone__input" name="operating-zone" value="{$selected_zone_id}">
            <option value="-1">{l s='Select zone' mod='shippify'}</h3>
            {section name=co loop=$available_zones}
              <option value="{$available_zones[co].id_zone}"{if $available_zones[co].selected == '1' } selected{/if}>{$available_zones[co].name}</h3>
            {sectionelse}
              <option value="-1">{l s='No zones found' mod='shippify'}</option>
            {/section}
          </select>
        </div>
        <p class="t">{l s='* If a zone is not selected, shippify will create orders for all zones.' mod='shippify'}</p>
        {if isset($success_zone)}
          <div class="alert alert-success">{$success_zone}</div>
        {/if}
        {if isset($failure_zone)}
          <div class="alert alert-danger">{$failure_zone}</div>
        {/if}
      </div>
      <div class="panel-footer">
        <input class="btn btn-default pull-right" type="submit" name="configuration-submit" value="Save"/>
      </div>
    </form>
  </div>
</fieldset>
