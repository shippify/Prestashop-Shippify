{if ($action != 'Track')}
  <a href="{$href}"><div>{$action}</div></a>
{else}
  <a target="_blank" href="{$href}"><div>{$action}</div></a>
{/if}