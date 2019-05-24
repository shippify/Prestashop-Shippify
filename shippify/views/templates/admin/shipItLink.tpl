{if ($action != 'Rastreo')}
  <a href="{$href}"><div>{$action}</div></a>
{else}
  <a target="_blank" href="{$href}"><div>{$action}</div></a>
  <a target="_blank" href="{$href_detail}"><div>{$action_detail}</div></a>
{/if}