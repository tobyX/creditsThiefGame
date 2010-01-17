{include file="documentHeader"}
<head>
	<title>{lang}wcf.guthaben.thief.title{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	{include file='headInclude' sandbox=false}
	<meta http-equiv="refresh" content="{if $wait|isset}{@$wait}{else}10{/if};URL={$url}" />
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
{include file='header' sandbox=false}

<div id="main">
	
	<div class="error">
		<p>{lang}wcf.guthaben.thief.ambush{$ambushType}{/lang}</p>
	</div>

</div>

{include file='footer' sandbox=false}
</body>
</html>