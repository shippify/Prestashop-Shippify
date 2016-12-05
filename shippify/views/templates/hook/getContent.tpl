<fieldset>
  <h2>Shippify Module configuration</h2>

  <div class="panel">
    <div class="panel-heading">
      <legend><img src="../img/admin/cog.gif" alt="" width="16"/>Configuration</legend>
    </div>
    <form action="" method="post">
      <div class="form-group clearfix">
        <label class="col-lg-3">Insert Shippify API credentials</label>
        <div class="col-lg-9">
          <label class="t">API Id:</label>
          <input type="text" id="shpy__configuration__api-id__input" name="api-id" {if isset($api_id)}value="{$api_id}"{/if}/>
          <label class="t">API Secret:</label>
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
        <label class="col-lg-3">Insert your company's warehouse id number.</label>
        <div class="col-lg-9">
          <input type="text" id="shpy__configuration__warehouse-id__input" name="warehouse-id" {if isset($warehouse_id)}value="{$warehouse_id}"{/if}/>
        </div>
        <p class="t">* If you don't have one yet, create a warehouse in the shippify platform.</p>
        {if isset($success_warehouse)}
          <div class="alert alert-success">{$success_warehouse}</div>
        {/if}
        {if isset($failure_warehouse)}
          <div class="alert alert-danger">{$failure_warehouse}</div>
        {/if}
      </div>
      <div class="form-group clearfix">
        <label class="col-lg-3">Insert an email for pickup contact support.</label>
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
      <div class="panel-footer">
        <input class="btn btn-default pull-right" type="submit" name="configuration-submit" value="Save"/>
      </div>
    </form>
  </div>
</fieldset>
