<h3>Shippify</h3>
{if isset($success)}
  <div class="alert alert-success">{$success}</div>
{/if}
{if isset($failure)}
  <div class="alert alert-danger">{$failure}</div>
{/if}
<div class="rte">
  <form action="" method="post" id="order-form">
    {if isset($exists) && $exists}
      <div class="alert alert-info">{l s='Shippify order already exists.' mod='shippify'}</div>
    {else}
      <div class="submit">
        <button type="submit" name="create-order-submit" class="button btn btn-default button-medium">
          <span>{l s='Confirm shipment' mod='shippify'}<i class="icon-chevron-right right"></i></span>
        </button>
      </div>
    {/if}
  </form>
</div>
