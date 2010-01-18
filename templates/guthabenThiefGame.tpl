{include file="documentHeader"}
<head>
	<title>{lang}wcf.guthaben.thiefgame.title{/lang} - {lang}wcf.guthaben.pagetitle{/lang} - {PAGE_TITLE}</title>
	{include file='headInclude' sandbox=false}
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/AjaxRequest.class.js"></script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/Suggestion.class.js"></script>
</head>
<body>
{include file='header' sandbox=false}

<div id="main">
	
	<ul class="breadCrumbs">
		<li><a href="index.php?page=Index{@SID_ARG_2ND}"><img src="{icon}indexS.png{/icon}" alt="" /> <span>{PAGE_TITLE}</span></a> &raquo;</li>
		<li><a href="index.php?page=guthabenMain{@SID_ARG_2ND}"><img src="{icon}guthabenMainS.png{/icon}" alt="" /> <span>{lang}wcf.guthaben.pagetitle{/lang}</span></a> &raquo;</li>
	</ul>
	
	<div class="mainHeadline">
		<img src="{icon}guthabenTransferL.png{/icon}" alt="" />
		<div class="headlineContainer">
			<h2>{lang}wcf.guthaben.mainpage.thiefgame{/lang}</h2>
			<p>{lang}wcf.guthaben.mainpage.thiefgame.description{/lang}</p>
		</div>
	</div>

	<div class="border content">
		<div class="container-1">
			{lang}wcf.guthaben.mainpage.thiefgame.link{/lang}
		</div>
	</div>
	
</div>

<p class="copyright">{lang}wcf.guthaben.copyright{/lang}</p>
{include file='footer' sandbox=false}
</body>
</html>