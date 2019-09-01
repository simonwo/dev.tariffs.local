<!DOCTYPE html>
<html lang="en" class="govuk-template no-js">
<head>
	<meta charset="utf-8" />
	<title>
<?php
	if (isset($title)) {
		echo ($title . " - ");
	}
?>Tariff management</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#0b0c0c" /> 
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<link rel="shortcut icon" sizes="16x16 32x32 48x48" href="/assets/images/favicon.ico" type="image/x-icon" />
	<link rel="mask-icon" href="/assets/images/govuk-mask-icon.svg" color="#0b0c0c"> 
	<link rel="apple-touch-icon" sizes="180x180" href="/assets/images/govuk-apple-touch-icon-180x180.png">
	<link rel="apple-touch-icon" sizes="167x167" href="/assets/images/govuk-apple-touch-icon-167x167.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/assets/images/govuk-apple-touch-icon-152x152.png">
	<link rel="apple-touch-icon" href="/assets/images/govuk-apple-touch-icon.png">
	<link href="/stylesheets/main-9fc2b6373f3fed690333ff1e95b23718.css" rel="stylesheet" media="all" />
	<link href="/stylesheets/styles.css" rel="stylesheet" />
	<script src="/javascripts/vendor/modernizr.js"></script>
	<script src="/javascripts/vendor/jquery-latest.js"></script>
	<script src="/javascripts/vendor/jquery.cookie.js" type="text/javascript"></script>
	<script src="/javascripts/tariffs.js" type="text/javascript"></script>
	<link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">
</head>
<body class="govuk-template__body ">
	<script>document.body.className = ((document.body.className) ? document.body.className + ' js-enabled' : 'js-enabled');</script>
	<a href="#main-content" class="govuk-skip-link">Skip to main content</a>
	<div class="app-pane app-pane--enabled">
		<div class="app-cookie-banner js-cookie-banner">
			<p class="govuk-width-container">GOV.UK uses cookies to make the site simpler. <a href="/cookies" class="govuk-link">Find out more about cookies</a></p>
		</div>
		<div class="app-pane__header">
			<header class="app-header" role="banner">
				<div class="govuk-width-container">
					<a href="/" class="govuk-link app-header__link">
						<span class="app-header__logotype">
							<svg role="presentation" focusable="false" xmlns="http://www.w3.org/2000/svg" class="app-header__logotype-crown" width="36" height="32" viewBox="0 0 132 97">
								<path d="M25 30.2c3.5 1.5 7.7-.2 9.1-3.7 1.5-3.6-.2-7.8-3.9-9.2-3.6-1.4-7.6.3-9.1 3.9-1.4 3.5.3 7.5 3.9 9zM9 39.5c3.6 1.5 7.8-.2 9.2-3.7 1.5-3.6-.2-7.8-3.9-9.1-3.6-1.5-7.6.2-9.1 3.8-1.4 3.5.3 7.5 3.8 9zM4.4 57.2c3.5 1.5 7.7-.2 9.1-3.8 1.5-3.6-.2-7.7-3.9-9.1-3.5-1.5-7.6.3-9.1 3.8-1.4 3.5.3 7.6 3.9 9.1zm38.3-21.4c3.5 1.5 7.7-.2 9.1-3.8 1.5-3.6-.2-7.7-3.9-9.1-3.6-1.5-7.6.3-9.1 3.8-1.3 3.6.4 7.7 3.9 9.1zm64.4-5.6c-3.6 1.5-7.8-.2-9.1-3.7-1.5-3.6.2-7.8 3.8-9.2 3.6-1.4 7.7.3 9.2 3.9 1.3 3.5-.4 7.5-3.9 9zm15.9 9.3c-3.6 1.5-7.7-.2-9.1-3.7-1.5-3.6.2-7.8 3.7-9.1 3.6-1.5 7.7.2 9.2 3.8 1.5 3.5-.3 7.5-3.8 9zm4.7 17.7c-3.6 1.5-7.8-.2-9.2-3.8-1.5-3.6.2-7.7 3.9-9.1 3.6-1.5 7.7.3 9.2 3.8 1.3 3.5-.4 7.6-3.9 9.1zM89.3 35.8c-3.6 1.5-7.8-.2-9.2-3.8-1.4-3.6.2-7.7 3.9-9.1 3.6-1.5 7.7.3 9.2 3.8 1.4 3.6-.3 7.7-3.9 9.1zM69.7 17.7l8.9 4.7V9.3l-8.9 2.8c-.2-.3-.5-.6-.9-.9L72.4 0H59.6l3.5 11.2c-.3.3-.6.5-.9.9l-8.8-2.8v13.1l8.8-4.7c.3.3.6.7.9.9l-5 15.4v.1c-.2.8-.4 1.6-.4 2.4 0 4.1 3.1 7.5 7 8.1h.2c.3 0 .7.1 1 .1.4 0 .7 0 1-.1h.2c4-.6 7.1-4.1 7.1-8.1 0-.8-.1-1.7-.4-2.4V34l-5.1-15.4c.4-.2.7-.6 1-.9zM66 92.8c16.9 0 32.8 1.1 47.1 3.2 4-16.9 8.9-26.7 14-33.5l-9.6-3.4c1 4.9 1.1 7.2 0 10.2-1.5-1.4-3-4.3-4.2-8.7L108.6 76c2.8-2 5-3.2 7.5-3.3-4.4 9.4-10 11.9-13.6 11.2-4.3-.8-6.3-4.6-5.6-7.9 1-4.7 5.7-5.9 8-.5 4.3-8.7-3-11.4-7.6-8.8 7.1-7.2 7.9-13.5 2.1-21.1-8 6.1-8.1 12.3-4.5 20.8-4.7-5.4-12.1-2.5-9.5 6.2 3.4-5.2 7.9-2 7.2 3.1-.6 4.3-6.4 7.8-13.5 7.2-10.3-.9-10.9-8-11.2-13.8 2.5-.5 7.1 1.8 11 7.3L80.2 60c-4.1 4.4-8 5.3-12.3 5.4 1.4-4.4 8-11.6 8-11.6H55.5s6.4 7.2 7.9 11.6c-4.2-.1-8-1-12.3-5.4l1.4 16.4c3.9-5.5 8.5-7.7 10.9-7.3-.3 5.8-.9 12.8-11.1 13.8-7.2.6-12.9-2.9-13.5-7.2-.7-5 3.8-8.3 7.1-3.1 2.7-8.7-4.6-11.6-9.4-6.2 3.7-8.5 3.6-14.7-4.6-20.8-5.8 7.6-5 13.9 2.2 21.1-4.7-2.6-11.9.1-7.7 8.8 2.3-5.5 7.1-4.2 8.1.5.7 3.3-1.3 7.1-5.7 7.9-3.5.7-9-1.8-13.5-11.2 2.5.1 4.7 1.3 7.5 3.3l-4.7-15.4c-1.2 4.4-2.7 7.2-4.3 8.7-1.1-3-.9-5.3 0-10.2l-9.5 3.4c5 6.9 9.9 16.7 14 33.5 14.8-2.1 30.8-3.2 47.7-3.2z" fill="currentColor" fill-rule="evenodd"></path>
								<image src="/assets/images/govuk-logotype-crown.png" xlink:href="" class="app-header__logotype-crown-fallback-image"></image>
							</svg>
							<span class="app-header__logotype-text">GOV.UK</span>
						</span>
						<span class="app-header__title">Tariff Management</span>
					</a>
					<!--
					<div class="options">
						<span id="showing">Showing Now</span>&nbsp;&nbsp;&nbsp;<a id="context_switcher" href="#"><span id="show_instead">Show Brexit instead</span></a>
					</div>
					//-->
					<div class="options">
						<span id="showing">Connected to database <?=$dbase?></span>
					</div>
				</div>
			</header>
			<div class="app-phase-banner__wrapper">
				<div class="govuk-phase-banner app-phase-banner govuk-width-container">
					<p class="govuk-phase-banner__content"><strong class="govuk-tag govuk-phase-banner__content__tag ">beta</strong>
						<span class="govuk-phase-banner__text">This is a new service – your <a class="govuk-link" href="#">feedback</a> will help us to improve it.</span>
					</p>
				</div>
			</div>
		</div>
		<div class="app-pane__body govuk-width-container">
			<div class="app-pane__content">
				<main id="main-content" class="app-content" role="main" style="padding:0px;margin-top:2em;">
