<?php
/*
¸______¸_____¸__¸  ¸_____¸__ __¸______¸     ¸__¸_____¸_____¸
┊_¸  ¸_┊  _  ┊  ┊  ┊   __┊  ╲  ┊_¸  ¸_┊  ¸__┊  ┊     ┊   __┊
  ┊  ┊ ┊     ┊  ┊__┊   __┊     ┊ ┊  |    ┊  ┊  ┊  ┊  ┊   __┊
  ┊__┊ ┊__┊__┊_____┊_____┊__╲__┊ ┊__┊    ┊_____┊_____┊_____┊
============================================================
  TALENT JOE  |    
============================================================ 
*/

$test = (($test) ?? 'Fail');
$version = ($version ?? '0.0.0');

?>
<!DOCTYPE html>
<html lang="en" class="info  -mode">
<head>
<meta charset="utf-8">
<meta name="language" content="en-us">
<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Access Denied | Artex Essence</title>
<style type="text/css">
@import url("https://fonts.googleapis.com/css?family=Lato:300,400,700,900");
body,body main{position:relative}:root,:root.info,:root.lite-mode{--color-lite:#FFF;--color-lite-blend:#F9F9F9;--color-dark:#363636;--color-dark-blend:#F3F3F3;--primary-color:#09F;--blend-color:#A9A9A9;--default-color:#09F;--info-color:#09F;--debug-color:#b1c3cd;--notice-color:#ffc900;--warning-color:#ff5e06;--error-color:#F00;--success-color:#090;--artex-color:#c500ff;--artex-alt-color:#c016ff;--silver-color:#dae2e7;--silver-alt-color:#d3d3d3;--primary-font:"Lato",sans-serif;--info-icon-view:block;--warning-icon-view:none;--error-icon-view:none;--svg-icon-color:#DEDEDE;--background-color:var(--color-lite);--body-top-high:4.5px;--body-top-border:1px solid #cacaca;--body-top-color:var(--primary-color);--body-top-line:var(1px solid #F00);--body-bottom-high:36px;--body-bottom-color:transparent;--body-top-border:1px solid #f0f0f0;--bottom-element-high:calc(var(--body-bottom-high) - 6px);--bottom-element-color:var(--blend-color);--main-logo-width:54px;--main-logo-height:54px;--main-logo-color:#EBEBEB;--main-logo-stroke:#EEE;--main-logo-stroke-width:3px;--main-logo-top:18px;--main-logo-left:calc(50% - (var(--main-logo-width) / 2));--main-width:100%;--main-height:auto;--main-min-height:100vh;--main-min-height:100vh;--main-padding-top:calc(var(--body-top-high)  + var(--main-logo-height) + var(--main-logo-top));--main-padding:var(--main-padding-top) 0 var(--body-bottom-high) 0;--main-margin:none}:root.debug{--primary-color:var(--debug-color);--accent-border:#999}:root.artex{--primary-color:var(--artex-color);--accent-border:var(--artex-alt-color);--svg-icon-color:#e7cffd}:root.silver{--primary-color:var(--silver-color);--accent-border:var(--silver-alt-color)}:root.info{--primary-color:var(--info-color);--accent-border:#009}:root.success{--primary-color:var(--success-color);--accent-border:#900}:root.notice{--primary-color:var(--notice-color);--accent-border:#009}:root.warn,:root.warning{--info-icon-view:none;--warning-icon-view:block;--error-icon-view:none;--primary-color:var(--warning-color);--accent-border:#ff5200;--svg-icon-color:#f9a76f}:root.error{--info-icon-view:none;--warning-icon-view:none;--error-icon-view:block;--primary-color:var(--error-color);--accent-border:#900;--svg-icon-color:#ffbbbb}:root.dark-mode{--background-color:#101010;--body-bottom-color:transparent;--body-top-border:1px solid #1b1b1b}*,::after,::before{margin:0;padding:0;-webkit-outline:none;-moz-outline:none;-ms-outline:none;-o-outline:none;outline:0;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;-ms-box-sizing:border-box;-o-box-sizing:border-box;box-sizing:border-box;-webkit-tap-highlight-color:transparent}html{line-height:1.15;-webkit-text-size-adjust:100%;-webkit-scroll-behavior:smooth;-moz-scroll-behavior:smooth;-ms-scroll-behavior:smooth;-o-scroll-behavior:smooth;scroll-behavior:smooth}body,html{width:100%;height:auto;min-height:100vh;font-family:var(--primary-font)}body{background:var(--background-color);overflow:none;overflow-y:auto;z-index:1}body,body ul#body-bottom,body::before{width:100vw;max-width:100vw;display:flex;flex-direction:column}body main,body nav#body-bottom{flex-direction:column;display:flex}body ul#body-bottom,body::before{position:fixed;left:0;right:0;z-index:801}body::before{height:var(--body-top-high);content:'';background:var(--body-top-color);border-bottom:var(--body-top-line);top:0}body nav#body-bottom{width:100vw;height:var(--body-bottom-high);max-height:var(--body-bottom-high);padding:none;align-items:flex-start;background:var(--body-bottom-color);border-top:var(--body-top-border);position:fixed;bottom:0;z-index:600}body nav#body-bottom body ul#body-bottom>li{width:33%;height:var(--bottom-element-high);display:flex;justify-content:center;align-items:center;color:var(--bottom-element-color);font-family:var(--primary-font);font-weight:400;font-size:11px;text-transform:uppercase;letter-spacing:1.8px}body ul#body-bottom>li:first-child,body ul#body-bottom>li:last-child{font-size:8px;font-weight:300;color:#636363}body ul#body-bottom>li:first-child{justify-content:flex-start}body ul#body-bottom>li:last-child{justify-content:flex-end}body ul#body-bottom>li>span,body ul#body-bottom>li>strong{color:#b9b9b9}body ul#body-bottom>li:last-child>span{font-size:9px;font-weight:400;color:#999}body ul#body-bottom>li strong{margin-right:9px;font-weight:500}body ul#body-bottom>li span{margin:0 3px;font-weight:500}body ul#body-bottom>li>b{margin:0 3px;font-weight:700}body ul#body-bottom>li a{color:#999;text-decoration:none;transition:color .72s ease-in-out}body ul#body-bottom>li a:hover{color:#00b3ff}body main{width:var(--main-width);height:var(--main-height);min-height:var(--main-min-height);padding:var(--main-padding);margin:var(--main-margin);z-index:9}svg#artex-body-logo{width:var(--main-logo-width);height:var(--main-logo-height);position:fixed;top:var(--main-logo-top);left:var(--main-logo-left);z-index:2}svg#artex-body-logo>circle,svg#artex-body-logo>path{stroke:var(--main-logo-stroke);stroke-width:var(--main-logo-stroke-width);fill:var(--main-logo-color);filter:url('#atx-inset-shadow-lite')}section{width:600px;min-width:270px;max-width:calc(100vw - 10vw);height:auto;min-height:0;max-height:calc(100vh - 10vh);padding:18px;background:#fff;border:0;border-radius:9px;-webkit-box-shadow:0 0 40px 1px #eee;-moz-box-shadow:0 0 40px 1px #eee;-ms-box-shadow:0 0 40px 1px #eee;-o-box-shadow:0 0 40px 1px #eee;box-shadow:0 0 40px 1px #eee;position:absolute;top:50%;left:50%;-webkit-transform:translate(-50%,-50%);-moz-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);-o-transform:translate(-50%,-50%);transform:translate(-50%,-50%);z-index:9}section::after{width:calc(100% - 18px);height:27px;content:attr(data-generator);color:#ccc;font-size:10px;position:absolute;bottom:3px;left:9px;right:9px}section svg{width:45px;height:45px;margin-bottom:18px;fill:var(--svg-icon-color);stroke:none}section svg#icon-info{display:var(--info-icon-view)}section svg#icon-warning{display:var(--warning-icon-view)}section svg#icon-error{display:var(--error-icon-view)}section h1{color:var(--primary-color);font-size:27px;font-weight:900;letter-spacing:2px}section p{color:#363636;font-size:15px;font-weight:400}section p span{margin-bottom:3px;color:#636363;font-size:13px;font-weight:300}section p strong{color:#272727;font-size:18px;font-weight:700}section,section h1,section p,section p span,section p strong,section::after{display:flex;flex-direction:column;justify-content:center;align-items:center}section h1,section p span,section::after{text-transform:uppercase}section h1,section p{margin-bottom:27px}section p,section p span,section p strong,section::after{letter-spacing:1px}
</style>

</head>
<body id="ess-info">
	<main>

<!---------------------------------- 
Main Logo
------------------------------------>
	<svg id="artex-body-logo" viewBox="0 0 936 873.37">
		<path d="M288.9,454.51l2.21-3.94c28.55-50.78,32.08-102.13,26.36-167.21-3.81-43.39-2.32-88.57,20.7-129.52,50.49-89.77,165-122.75,255.42-73.52,92.93,50.56,126.27,167.18,74.59,259.09-23.25,41.36-61,67.3-101.13,85.42-56,25.3-101.91,58.69-130.75,110h0c-28.9,51.42-30.08,107.15-25.93,169.3,2.91,43.48,2.72,88.6-20.12,129.63C340,924,225.18,957.35,134.42,908,41.49,857.41,8.15,740.79,59.83,648.88c23-41,61-65.55,100-85C213.08,537.3,260.36,505.26,288.9,454.51Z" transform="translate(-35.5 -57.47)"/>
			<circle cx="746.5" cy="683.87" r="189.5"/>
	</svg>

<!-- 
##################################################--->

	<section data-generator="Artex Essence">
		<svg id="icon-info" viewBox="0 -26 511.81197 511" xmlns="http://www.w3.org/2000/svg">
			<path d="m504.148438 354.371094-183.304688-313.832032c-12.300781-24.597656-37.4375-40.132812-64.9375-40.132812s-52.636719 15.535156-64.9375 40.132812l-183.304688 313.832032c-11.253906 22.503906-10.050781 49.234375 3.179688 70.636718 13.226562 21.402344 36.59375 34.433594 61.757812 34.433594h366.609376c25.160156 0 48.53125-13.03125 61.757812-34.433594 13.230469-21.402343 14.429688-48.132812 3.179688-70.636718zm-248.242188 60.929687c-19.5 0-35.3125-15.808593-35.3125-35.308593s15.8125-35.308594 35.3125-35.308594 35.308594 15.808594 35.308594 35.308594-15.808594 35.308593-35.308594 35.308593zm35.308594-141.238281c0 19.5-15.808594 35.308594-35.308594 35.308594s-35.3125-15.808594-35.3125-35.308594v-167.726562c0-19.5 15.8125-35.308594 35.3125-35.308594s35.308594 15.808594 35.308594 35.308594zm0 0"/>
		</svg>
	
		<svg id="icon-warning" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
		<path fill-rule="evenodd" clip-rule="evenodd" d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10zm-1.5-5.009c0-.867.659-1.491 1.491-1.491.85 0 1.509.624 1.509 1.491 0 .867-.659 1.509-1.509 1.509-.832 0-1.491-.642-1.491-1.509zM11.172 6a.5.5 0 0 0-.499.522l.306 7a.5.5 0 0 0 .5.478h1.043a.5.5 0 0 0 .5-.478l.305-7a.5.5 0 0 0-.5-.522h-1.655z" />
		</svg>
	
		<svg id="icon-error" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
			<path fill-rule="evenodd" clip-rule="evenodd" d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10zM7.403 7.403a1 1 0 0 1 1.414 0L12 10.586l3.183-3.183a1 1 0 1 1 1.414 1.414L13.414 12l3.183 3.183a1 1 0 0 1-1.414 1.414L12 13.414l-3.183 3.183a1 1 0 0 1-1.414-1.414L10.586 12 7.403 8.817a1 1 0 0 1 0-1.414z" />
		</svg>	


		<h1>Access Denied</h1>
		<p>
			<span>Code</span>
			<small>Your current server PHP version is &nbsp; <b><?php echo $version; ?></b></small>
			<strong><?php echo $test; ?></strong>
		</p>
	</section>

<!-- ################################################## --->


<!---------------------------------- 
Body Bottom Element
------------------------------------>
	<nav id="body-bottom">

	</nav>

	<svg>
		<filter id="atx-inset-shadow-lite" x="-50%" y="-50%" width="200%" height="200%">
			<feComponentTransfer in=SourceAlpha>
				<feFuncA type="table" tableValues="1 0" />
			</feComponentTransfer>
			<feGaussianBlur stdDeviation="27"/>
			<feOffset dx="15" dy="15" result="offsetblur"/>
			<feFlood flood-color="rgb(150, 150, 150)" result="color"/>
			<feComposite in2="offsetblur" operator="in"/>
			<feComposite in2="SourceAlpha" operator="in" />
			<feMerge>
				<feMergeNode in="SourceGraphic" /><feMergeNode />
			</feMerge>
		</filter>
	</svg>

	</main>
<script type="text/javascript"></script>
</body>
</html>