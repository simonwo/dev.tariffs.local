<?php
require(dirname(__FILE__) . "../../includes/db.php");
?>
<!DOCTYPE html>
<html lang="en" class="govuk-template">

<head>
    <meta charset="utf-8" />
    <title>View workbasket : Manage the UK Tariff</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#0b0c0c" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" sizes="16x16 32x32 48x48" href="/assets/images/favicon.ico" type="image/x-icon" />
    <link rel="mask-icon" href="/assets/images/govuk-mask-icon.svg" color="#0b0c0c">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/govuk-apple-touch-icon-180x180.png">
    <link rel="apple-touch-icon" sizes="167x167" href="/assets/images/govuk-apple-touch-icon-167x167.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/assets/images/govuk-apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" href="/assets/images/govuk-apple-touch-icon.png">
    <!--[if !IE 8]><!-->
    <link href="/css/govuk-frontend-3.4.0.min.css" rel="stylesheet" />
    <link href="/css/application.css" rel="stylesheet" />
    <link href="/css/modaal.css" rel="stylesheet" />
    <!--<![endif]-->
    <script src="/js/jquery-3.4.1.js"></script>
    <!--
    <script src="/js/jquery-3.4.1.min.js"></script>
    //-->
    <script src="/js/jquery-ui.min.js"></script>
    <script src="/js/js.cookie.js"></script>
    <script src="/js/typeahead.bundle.js"></script>
    <link href="/css/select2.css" rel="stylesheet" />
    <script src="/js/select2.js"></script>
    <script src="/js/application.js"></script>
    <script src="/js/date.format.js"></script>
    <script src="/js/cursor.js"></script>
    <script src="/js/moment.js"></script>
    <script src="/js/modaal.min.js"></script>
    <script src="/js/notify.min.js"></script>


    <!--[if IE 8]>
<link href="/css/govuk-frontend-ie8-3.4.0.min.css" rel="stylesheet" />
<link href="/css/govuk-frontend-ie8-3.4.0.min.css" rel="stylesheet" />
<![endif]-->

    <!--[if lt IE 9]>
<script src="/html5-shiv/html5shiv.js"></script>
<![endif]-->

    <meta property="og:image" content="/assets/images/govuk-opengraph-image.png">
</head>

<body class="govuk-template__body">
    <script>
        document.body.className = ((document.body.className) ? document.body.className + ' js-enabled' : 'js-enabled');
    </script>

    <a href="#main-content" class="govuk-skip-link">Skip to main content</a>
    <header class="govuk-header" role="banner" data-module="govuk-header">
        <div class="govuk-header__container govuk-width-container">
            <div class="govuk-header__logo">
                <a href="/" class="govuk-header__link govuk-header__link--homepage">
                    <span class="govuk-header__logotype">
                        <svg role="presentation" focusable="false" class="govuk-header__logotype-crown" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 132 97" height="30" width="36">
                            <path fill="currentColor" fill-rule="evenodd" d="M25 30.2c3.5 1.5 7.7-.2 9.1-3.7 1.5-3.6-.2-7.8-3.9-9.2-3.6-1.4-7.6.3-9.1 3.9-1.4 3.5.3 7.5 3.9 9zM9 39.5c3.6 1.5 7.8-.2 9.2-3.7 1.5-3.6-.2-7.8-3.9-9.1-3.6-1.5-7.6.2-9.1 3.8-1.4 3.5.3 7.5 3.8 9zM4.4 57.2c3.5 1.5 7.7-.2 9.1-3.8 1.5-3.6-.2-7.7-3.9-9.1-3.5-1.5-7.6.3-9.1 3.8-1.4 3.5.3 7.6 3.9 9.1zm38.3-21.4c3.5 1.5 7.7-.2 9.1-3.8 1.5-3.6-.2-7.7-3.9-9.1-3.6-1.5-7.6.3-9.1 3.8-1.3 3.6.4 7.7 3.9 9.1zm64.4-5.6c-3.6 1.5-7.8-.2-9.1-3.7-1.5-3.6.2-7.8 3.8-9.2 3.6-1.4 7.7.3 9.2 3.9 1.3 3.5-.4 7.5-3.9 9zm15.9 9.3c-3.6 1.5-7.7-.2-9.1-3.7-1.5-3.6.2-7.8 3.7-9.1 3.6-1.5 7.7.2 9.2 3.8 1.5 3.5-.3 7.5-3.8 9zm4.7 17.7c-3.6 1.5-7.8-.2-9.2-3.8-1.5-3.6.2-7.7 3.9-9.1 3.6-1.5 7.7.3 9.2 3.8 1.3 3.5-.4 7.6-3.9 9.1zM89.3 35.8c-3.6 1.5-7.8-.2-9.2-3.8-1.4-3.6.2-7.7 3.9-9.1 3.6-1.5 7.7.3 9.2 3.8 1.4 3.6-.3 7.7-3.9 9.1zM69.7 17.7l8.9 4.7V9.3l-8.9 2.8c-.2-.3-.5-.6-.9-.9L72.4 0H59.6l3.5 11.2c-.3.3-.6.5-.9.9l-8.8-2.8v13.1l8.8-4.7c.3.3.6.7.9.9l-5 15.4v.1c-.2.8-.4 1.6-.4 2.4 0 4.1 3.1 7.5 7 8.1h.2c.3 0 .7.1 1 .1.4 0 .7 0 1-.1h.2c4-.6 7.1-4.1 7.1-8.1 0-.8-.1-1.7-.4-2.4V34l-5.1-15.4c.4-.2.7-.6 1-.9zM66 92.8c16.9 0 32.8 1.1 47.1 3.2 4-16.9 8.9-26.7 14-33.5l-9.6-3.4c1 4.9 1.1 7.2 0 10.2-1.5-1.4-3-4.3-4.2-8.7L108.6 76c2.8-2 5-3.2 7.5-3.3-4.4 9.4-10 11.9-13.6 11.2-4.3-.8-6.3-4.6-5.6-7.9 1-4.7 5.7-5.9 8-.5 4.3-8.7-3-11.4-7.6-8.8 7.1-7.2 7.9-13.5 2.1-21.1-8 6.1-8.1 12.3-4.5 20.8-4.7-5.4-12.1-2.5-9.5 6.2 3.4-5.2 7.9-2 7.2 3.1-.6 4.3-6.4 7.8-13.5 7.2-10.3-.9-10.9-8-11.2-13.8 2.5-.5 7.1 1.8 11 7.3L80.2 60c-4.1 4.4-8 5.3-12.3 5.4 1.4-4.4 8-11.6 8-11.6H55.5s6.4 7.2 7.9 11.6c-4.2-.1-8-1-12.3-5.4l1.4 16.4c3.9-5.5 8.5-7.7 10.9-7.3-.3 5.8-.9 12.8-11.1 13.8-7.2.6-12.9-2.9-13.5-7.2-.7-5 3.8-8.3 7.1-3.1 2.7-8.7-4.6-11.6-9.4-6.2 3.7-8.5 3.6-14.7-4.6-20.8-5.8 7.6-5 13.9 2.2 21.1-4.7-2.6-11.9.1-7.7 8.8 2.3-5.5 7.1-4.2 8.1.5.7 3.3-1.3 7.1-5.7 7.9-3.5.7-9-1.8-13.5-11.2 2.5.1 4.7 1.3 7.5 3.3l-4.7-15.4c-1.2 4.4-2.7 7.2-4.3 8.7-1.1-3-.9-5.3 0-10.2l-9.5 3.4c5 6.9 9.9 16.7 14 33.5 14.8-2.1 30.8-3.2 47.7-3.2z"></path>
                            <image src="/assets/images/govuk-logotype-crown.png" xlink:href="" class="govuk-header__logotype-crown-fallback-image" width="36" height="32"></image>
                        </svg>
                        <span class="govuk-header__logotype-text">Manage the UK Tariff</span><span class="env">Development</span>
                    </span>
                </a>
            </div>
            <div class="govuk-header__controls">
                Current workbasket: <a href="/workbaskets/view.html">Steel trade remedy</a>&nbsp;&nbsp;<a href="/session/sign_out.html">Sign out</a>
                <div><img id='logged_in_user' title='Logged on as Matt Lavis' src='/assets/images/user.png' width='24' /></div>
            </div>
        </div>
    </header>

    <div class="govuk-width-container">
        <!-- Start phase banner //-->
        <div class="govuk-phase-banner">
            <p class="govuk-phase-banner__content">
                <strong class="govuk-tag govuk-phase-banner__content__tag">beta</strong>
                <span class="govuk-phase-banner__text">
                    This is a new service – your <a class="govuk-link" href="#">feedback</a> will help us to improve it.
                </span>
            </p>
        </div>
        <!-- End phase banner //-->

        <!-- Start breadcrumbs //-->
        <div class="govuk-breadcrumbs">
            <ol class="govuk-breadcrumbs__list">
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/">Home</a>
                </li>
                <li class="govuk-breadcrumbs__list-item">
                    <a class="govuk-breadcrumbs__link" href="/#workbaskets">Workbaskets</a>
                </li>
                <li class="govuk-breadcrumbs__list-item" aria-current="page">View workbasket</li>
            </ol>
        </div>
        <!-- End breadcrumbs //-->

        <main class="govuk-main-wrapper" id="main-content" role="main">
            <!-- Start main title //-->
            <h1 class="govuk-heading-xl">Workbasket &quot;Steel trade remedy&quot;</h1>
            <!-- End main title //-->


            <div class="govuk-tabs" data-module="govuk-tabs">
                <h2 class="govuk-tabs__title">
                    Contents
                </h2>
                <ul class="govuk-tabs__list">
                    <li class="govuk-tabs__list-item govuk-tabs__list-item--selected">
                        <a class="govuk-tabs__tab" href="#workbasket_detail">
                            Workbasket detail
                        </a>
                    </li>
                    <li class="govuk-tabs__list-item">
                        <a class="govuk-tabs__tab" href="#workbasket_activities">
                            Workbasket activities
                        </a>
                    </li>
                    <li class="govuk-tabs__list-item">
                        <a class="govuk-tabs__tab" href="#workbasket_history">
                            Workbasket history
                        </a>
                    </li>
                </ul>
                <section class="govuk-tabs__panel" id="workbasket_detail">
                    <div class="govuk-grid-row">
                        <div class="govuk-grid-column-three-quarters">
                            <h2 class="govuk-heading-l">About this workbasket</h2>
                            <table class="govuk-table">
                                <thead class="govuk-table__head">
                                    <tr class="govuk-table__row">
                                        <th scope="col" class="govuk-table__header" style="width:25%;display:none;">Field</th>
                                        <th scope="col" class="govuk-table__header" style="width:75%;display:none;">Value</th>
                                    </tr>
                                </thead>
                                <tbody class="govuk-table__body">
                                    <tr class="govuk-table__row">
                                        <th scope="row" class="govuk-table__header nopad" style="width:25%">Workbasket ID</th>
                                        <td class="govuk-table__cell" style="width:75%">128</td>
                                    </tr>
                                    <tr class="govuk-table__row">
                                        <th scope="row" class="govuk-table__header nopad" style="width:25%">Workbasket name</th>
                                        <td class="govuk-table__cell" style="width:75%">Steel trade remedy</td>
                                    </tr>
                                    <tr class="govuk-table__row">
                                        <th scope="row" class="govuk-table__header nopad">Reason</th>
                                        <td class="govuk-table__cell">Phasellus imperdiet nisi libero, eget dignissim purus feugiat sed.</td>
                                    </tr>
                                    <tr class="govuk-table__row">
                                        <th scope="row" class="govuk-table__header nopad">User</th>
                                        <td class="govuk-table__cell">Matt Lavis</td>
                                    </tr>
                                    <tr class="govuk-table__row">
                                        <th scope="row" class="govuk-table__header nopad">Created</th>
                                        <td class="govuk-table__cell">18&nbsp;Mar&nbsp;20&nbsp;16:15</td>
                                    </tr>
                                    <tr class="govuk-table__row">
                                        <th scope="row" class="govuk-table__header nopad">Last amended</th>
                                        <td class="govuk-table__cell">-</td>
                                    </tr>
                                    <tr class="govuk-table__row">
                                        <th scope="row" class="govuk-table__header nopad">Workbasket status</th>
                                        <td class="govuk-table__cell status_cell"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><span>In progress (active)</span></td>
                                    </tr>
                                    <tr class="govuk-table__row">
                                        <th scope="row" class="govuk-table__header nopad">Activity summary</th>
                                        <td class="govuk-table__cell status_cell">
                                            13 activities
                                            <br />13 x In progress</span></td>
                                    </tr>
                                </tbody>
                            </table>



                        </div>
                        <div class="govuk-grid-column-one-quarter">
                            <div class="gem-c-contextual-sidebar">



                                <div class="gem-c-related-navigation">
                                    <h2 class="gem-c-related-navigation__main-heading" data-track-count="sidebarRelatedItemSection">
                                        Actions
                                    </h2>
                                    <nav role="navigation" class="gem-c-related-navigation__nav-section" aria-labelledby="related-nav-related_items-90f47a0c" data-module="gem-toggle">
                                        <ul class="gem-c-related-navigation__link-list" data-module="track-click">


                                            <li class="govuk-link gem-c-related-navigation__link"><a class="govuk-link" href="edit_workbasket.html?workbasket_id=128">Edit workbasket detail</a></li>
                                            <li class="govuk-link gem-c-related-navigation__link"><a class="govuk-link" title='Close this workbasket' href='/workbaskets/actions.php?action=close&workbasket_id=128'>Close this workbasket</a></li>


                                        </ul>
                                    </nav>

                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="govuk-tabs__panel govuk-tabs__panel--hidden" id="workbasket_activities">
                    <h2 class="govuk-heading-l">Workbasket activities</h2>
                    <p class="govuk-body">This workbasket contains contains the following x changes:</p>


                    <div class="govuk-accordion" data-module="govuk-accordion" id="accordion-with-summary-sections">

                        <div class="govuk-accordion__section " id="block_footnote_types">
                            <!-- Start accordion section - footnote types //-->
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-accordion-with-summary-sections-footnotes">
                                        Footnote types (3)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-accordion-with-summary-sections-footnotes" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-accordion-with-summary-sections-footnotes">
                                <table class="govuk-table govuk-table--m">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th width="10%" scope="col" class="govuk-table__header ">Activity</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Type ID</th>
                                            <th width="32%" scope="col" class="govuk-table__header ">Footnote type description</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Application code</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Start date</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">End date</th>
                                            <th width="10%" scope="col" class="govuk-table__header c">Status</th>
                                            <th scope="col" class="govuk-table__header nw l">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Create footnote type</td>
                                            <td class="govuk-table__cell ">XX</td>
                                            <td class="govuk-table__cell ">My footnote type description</td>
                                            <td class="govuk-table__cell ">2 TARIC Nomenclature</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Update footnote type</td>
                                            <td class="govuk-table__cell ">XX</td>
                                            <td class="govuk-table__cell ">My footnote type description</td>
                                            <td class="govuk-table__cell ">2 TARIC Nomenclature</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">31&nbsp;Dec&nbsp;22</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Delete footnote type</td>
                                            <td class="govuk-table__cell ">XX</td>
                                            <td class="govuk-table__cell ">My footnote type description</td>
                                            <td class="govuk-table__cell ">2 TARIC Nomenclature</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">31&nbsp;Dec&nbsp;22</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <!-- End accordion section - footnote types //-->
                        </div>

                        <div class="govuk-accordion__section " id="block_certificate_types">
                            <!-- Start accordion section - certificate types //-->
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-accordion-with-summary-sections-footnotes">
                                        Certificate types (3)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-accordion-with-summary-sections-footnotes" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-accordion-with-summary-sections-footnotes">
                                <table class="govuk-table govuk-table--m">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th width="10%" scope="col" class="govuk-table__header ">Activity</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Type code</th>
                                            <th width="42%" scope="col" class="govuk-table__header ">Certificate type description</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Start date</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">End date</th>
                                            <th width="10%" scope="col" class="govuk-table__header c">Status</th>
                                            <th scope="col" class="govuk-table__header nw l">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Create certificate type</td>
                                            <td class="govuk-table__cell ">XX</td>
                                            <td class="govuk-table__cell ">My certificate type description</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Update certificate type</td>
                                            <td class="govuk-table__cell ">XX</td>
                                            <td class="govuk-table__cell ">My certificate type description</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">31&nbsp;Dec&nbsp;21</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Delete certificate type</td>
                                            <td class="govuk-table__cell ">XX</td>
                                            <td class="govuk-table__cell ">My certificate type description</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <!-- End accordion section - certificate types //-->
                        </div>

                        <div class="govuk-accordion__section " id="block_additional_code_types">
                            <!-- Start accordion section - additional code types //-->
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-accordion-with-summary-sections-footnotes">
                                        Additional code types (3)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-accordion-with-summary-sections-footnotes" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-accordion-with-summary-sections-footnotes">
                                <table class="govuk-table govuk-table--m">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th width="10%" scope="col" class="govuk-table__header ">Activity</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Type</th>
                                            <th width="42%" scope="col" class="govuk-table__header ">Additional code type description</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Start date</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">End date</th>
                                            <th width="10%" scope="col" class="govuk-table__header c">Status</th>
                                            <th scope="col" class="govuk-table__header nw l">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Create additional code type</td>
                                            <td class="govuk-table__cell ">XX</td>
                                            <td class="govuk-table__cell ">My additional code type description</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Update additional code type</td>
                                            <td class="govuk-table__cell ">XX</td>
                                            <td class="govuk-table__cell ">My additional code type description</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">31&nbsp;Dec&nbsp;21</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Delete additional code type</td>
                                            <td class="govuk-table__cell ">XX</td>
                                            <td class="govuk-table__cell ">My additional code type description</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <!-- End accordion section - additional code types //-->
                        </div>

                        <div class="govuk-accordion__section " id="block_additional_code_types">
                            <!-- Start accordion section - additional code types //-->
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-accordion-with-summary-sections-footnotes">
                                        Additional code types / measure types (3)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-accordion-with-summary-sections-footnotes" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-accordion-with-summary-sections-footnotes">
                                <table class="govuk-table govuk-table--m">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th width="10%" scope="col" class="govuk-table__header ">Activity</th>
                                            <th width="21%" scope="col" class="govuk-table__header ">Additional code type</th>
                                            <th width="21%" scope="col" class="govuk-table__header ">Measure type</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Start date</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">End date</th>
                                            <th width="10%" scope="col" class="govuk-table__header c">Status</th>
                                            <th scope="col" class="govuk-table__header nw l">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Create ACT / MT relationship</td>
                                            <td class="govuk-table__cell ">XX New additional code type</td>
                                            <td class="govuk-table__cell ">103 Third country duty</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Update ACT / MT relationship</td>
                                            <td class="govuk-table__cell ">XX New additional code type</td>
                                            <td class="govuk-table__cell ">103 Third country duty</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">31&nbsp;Dec&nbsp;21</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Delete ACT / MT relationship</td>
                                            <td class="govuk-table__cell ">XX New additional code type</td>
                                            <td class="govuk-table__cell ">103 Third country duty</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;22</td>
                                            <td class="govuk-table__cell ">31&nbsp;Dec&nbsp;22</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <!-- End accordion section - additional code types //-->
                        </div>
                        <div class="govuk-accordion__section " id="block_footnotes">
                            <!-- Start accordion section - footnotes //-->
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-accordion-with-summary-sections-footnotes">
                                        Footnotes (6)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-accordion-with-summary-sections-footnotes" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-accordion-with-summary-sections-footnotes">
                                <table class="govuk-table govuk-table--m">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th width="10%" scope="col" class="govuk-table__header ">Activity</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Footnote ID</th>
                                            <th width="32%" scope="col" class="govuk-table__header ">Footnote description</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Type</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Start date</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">End date</th>
                                            <th width="10%" scope="col" class="govuk-table__header c">Status</th>
                                            <th scope="col" class="govuk-table__header nw l">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Create footnote</td>
                                            <td class="govuk-table__cell ">NC 035</td>
                                            <td class="govuk-table__cell ">Description of a footnote</td>
                                            <td class="govuk-table__cell ">NC Combined Nomenclature</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Update footnote</td>
                                            <td class="govuk-table__cell ">NC 036</td>
                                            <td class="govuk-table__cell ">Description of a footnote</td>
                                            <td class="govuk-table__cell ">NC Combined Nomenclature</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Delete footnote</td>
                                            <td class="govuk-table__cell ">NC 035</td>
                                            <td class="govuk-table__cell ">Description of a footnote</td>
                                            <td class="govuk-table__cell ">NC Combined Nomenclature</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Create footnote description</td>
                                            <td class="govuk-table__cell ">NC 035</td>
                                            <td class="govuk-table__cell ">Description of a footnote</td>
                                            <td class="govuk-table__cell ">NC Combined Nomenclature</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Update footnote description</td>
                                            <td class="govuk-table__cell ">NC 036</td>
                                            <td class="govuk-table__cell ">Description of a footnote</td>
                                            <td class="govuk-table__cell ">NC Combined Nomenclature</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Delete footnote description</td>
                                            <td class="govuk-table__cell ">NC 036</td>
                                            <td class="govuk-table__cell ">Description of a footnote</td>
                                            <td class="govuk-table__cell ">NC Combined Nomenclature</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- End accordion section - footnotes //-->
                        </div>

                        <div class="govuk-accordion__section " id="block_certificates">
                            <!-- Start accordion section - certificates //-->
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-accordion-with-summary-sections-certificates">
                                        Certificates (6)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-accordion-with-summary-sections-certificates" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-accordion-with-summary-sections-certificates">
                                <table class="govuk-table govuk-table--m">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th width="10%" scope="col" class="govuk-table__header ">Activity</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Certificate ID</th>
                                            <th width="32%" scope="col" class="govuk-table__header ">Certificate description</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Type</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Start date</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">End date</th>
                                            <th width="10%" scope="col" class="govuk-table__header c">Status</th>
                                            <th scope="col" class="govuk-table__header nw l">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Create certificate</td>
                                            <td class="govuk-table__cell ">Y908</td>
                                            <td class="govuk-table__cell ">Description of a certificate</td>
                                            <td class="govuk-table__cell ">Y Particular provisions</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Update certificate</td>
                                            <td class="govuk-table__cell ">Y908</td>
                                            <td class="govuk-table__cell ">Description of a certificate</td>
                                            <td class="govuk-table__cell ">Y Particular provisions</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Delete certificate</td>
                                            <td class="govuk-table__cell ">Y908</td>
                                            <td class="govuk-table__cell ">Description of a certificate</td>
                                            <td class="govuk-table__cell ">Y Particular provisions</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Create certificate description</td>
                                            <td class="govuk-table__cell ">Y908</td>
                                            <td class="govuk-table__cell ">Description of a certificate</td>
                                            <td class="govuk-table__cell ">Y Particular provisions</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Update certificate description</td>
                                            <td class="govuk-table__cell ">Y908</td>
                                            <td class="govuk-table__cell ">Description of a certificate</td>
                                            <td class="govuk-table__cell ">Y Particular provisions</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Delete certificate description</td>
                                            <td class="govuk-table__cell ">Y908</td>
                                            <td class="govuk-table__cell ">Description of a certificate</td>
                                            <td class="govuk-table__cell ">Y Particular provisions</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- End accordion section - certificates //-->
                        </div>

                        <div class="govuk-accordion__section " id="block_measure_types">
                            <!-- Start accordion section - measure types //-->
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-accordion-with-summary-sections-footnotes">
                                        Measure types (3)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-accordion-with-summary-sections-footnotes" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-accordion-with-summary-sections-footnotes">
                                <table class="govuk-table govuk-table--m">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th scope="col" class="govuk-table__header " width="10%">Activity</th>
                                            <th scope="col" class="govuk-table__header " width="10%">Type</th>
                                            <th scope="col" class="govuk-table__header " width="32%">Measure type description and key fields</th>
                                            <th scope="col" class="govuk-table__header " width="10%">Series</th>
                                            <th scope="col" class="govuk-table__header " width="10%">Start date</th>
                                            <th scope="col" class="govuk-table__header " width="10%">End date</th>
                                            <th scope="col" class="govuk-table__header c" width="10%">Status</th>
                                            <th scope="col" class="govuk-table__header nw l">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr id="workbasket_item_sid_492" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Create measure type</td>
                                            <td class="govuk-table__cell ">999</td>
                                            <td class="govuk-table__cell "><b>Description</b>: Description of a measure type<br><b>Import / export</b>: 0 Import<br><b>Requires duties</b>: 1 Mandatory<br><b>Requires order number</b>: 2 Not permitted</td>
                                            <td class="govuk-table__cell ">C Applicable duty</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class="status_image" alt="Status: In progress" title="Status: In progress" src="/assets/images/in_progress.png"><br>In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href="/measure_types/view.html?mode=view&amp;measure_type_id=999"><img src="/assets/images/view.png"><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&amp;workbasket_id=128&amp;workbasket_item_sid=492"><img src="/assets/images/delete.png"><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_492" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Edit measure type</td>
                                            <td class="govuk-table__cell ">999</td>
                                            <td class="govuk-table__cell "><b>Description</b>: Description of a measure type<br><b>Import / export</b>: 0 Import<br><b>Requires duties</b>: 1 Mandatory<br><b>Requires order number</b>: 2 Not permitted</td>
                                            <td class="govuk-table__cell ">C Applicable duty</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">31&nbsp;Dec&nbsp;21</td>
                                            <td class="govuk-table__cell c"><img class="status_image" alt="Status: In progress" title="Status: In progress" src="/assets/images/in_progress.png"><br>In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href="/measure_types/view.html?mode=view&amp;measure_type_id=999"><img src="/assets/images/view.png"><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&amp;workbasket_id=128&amp;workbasket_item_sid=492"><img src="/assets/images/delete.png"><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_492" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Delete measure type</td>
                                            <td class="govuk-table__cell ">999</td>
                                            <td class="govuk-table__cell "><b>Description</b>: Description of a measure type<br><b>Import / export</b>: 0 Import<br><b>Requires duties</b>: 1 Mandatory<br><b>Requires order number</b>: 2 Not permitted</td>
                                            <td class="govuk-table__cell ">C Applicable duty</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">31&nbsp;Dec&nbsp;21</td>
                                            <td class="govuk-table__cell c"><img class="status_image" alt="Status: In progress" title="Status: In progress" src="/assets/images/in_progress.png"><br>In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href="/measure_types/view.html?mode=view&amp;measure_type_id=999"><img src="/assets/images/view.png"><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&amp;workbasket_id=128&amp;workbasket_item_sid=492"><img src="/assets/images/delete.png"><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                            <!-- End accordion section - measure types //-->
                        </div>

                        <div class="govuk-accordion__section " id="block_additional_codes">
                            <!-- Start accordion section - additional codes //-->
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-accordion-with-summary-sections-additional codes">
                                        Additional codes (6)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-accordion-with-summary-sections-additional codes" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-accordion-with-summary-sections-additional codes">
                                <table class="govuk-table govuk-table--m">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th width="10%" scope="col" class="govuk-table__header ">Activity</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Additional code ID</th>
                                            <th width="32%" scope="col" class="govuk-table__header ">Additional code description</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Type</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Start date</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">End date</th>
                                            <th width="10%" scope="col" class="govuk-table__header c">Status</th>
                                            <th scope="col" class="govuk-table__header nw l">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Create additional code</td>
                                            <td class="govuk-table__cell ">C234</td>
                                            <td class="govuk-table__cell ">Description of an additional code, such as a company name</td>
                                            <td class="govuk-table__cell ">C Anti-dumping / anti-subsidy</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Update additional code</td>
                                            <td class="govuk-table__cell ">C234</td>
                                            <td class="govuk-table__cell ">Description of an additional code, such as a company name</td>
                                            <td class="govuk-table__cell ">C Anti-dumping / anti-subsidy</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Delete additional code</td>
                                            <td class="govuk-table__cell ">C234</td>
                                            <td class="govuk-table__cell ">Description of an additional code, such as a company name</td>
                                            <td class="govuk-table__cell ">C Anti-dumping / anti-subsidy</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Create additional code description</td>
                                            <td class="govuk-table__cell ">C234</td>
                                            <td class="govuk-table__cell ">Description of an additional code, such as a company name</td>
                                            <td class="govuk-table__cell ">C Anti-dumping / anti-subsidy</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Update additional code description</td>
                                            <td class="govuk-table__cell ">C234</td>
                                            <td class="govuk-table__cell ">Description of an additional code, such as a company name</td>
                                            <td class="govuk-table__cell ">C Anti-dumping / anti-subsidy</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Delete additional code description</td>
                                            <td class="govuk-table__cell ">C234</td>
                                            <td class="govuk-table__cell ">Description of an additional code, such as a company name</td>
                                            <td class="govuk-table__cell ">C Anti-dumping / anti-subsidy</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- End accordion section - additional codes //-->
                        </div>

                        <div class="govuk-accordion__section " id="block_geographical_areas">
                            <!-- Start accordion section - geographical areas //-->
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-accordion-with-summary-sections-geographical areas">
                                        Geographical areas (6)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-accordion-with-summary-sections-geographical areas" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-accordion-with-summary-sections-geographical areas">
                                <table class="govuk-table govuk-table--m">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th width="10%" scope="col" class="govuk-table__header ">Activity</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Geographical area ID</th>
                                            <th width="32%" scope="col" class="govuk-table__header ">Geographical area description</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Area code</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Start date</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">End date</th>
                                            <th width="10%" scope="col" class="govuk-table__header c">Status</th>
                                            <th scope="col" class="govuk-table__header nw l">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Create geographical area</td>
                                            <td class="govuk-table__cell ">NN</td>
                                            <td class="govuk-table__cell ">Description of a geographical area</td>
                                            <td class="govuk-table__cell ">0 Country</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Update geographical area</td>
                                            <td class="govuk-table__cell ">NN</td>
                                            <td class="govuk-table__cell ">Description of a geographical area</td>
                                            <td class="govuk-table__cell ">0 Country</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Delete geographical area</td>
                                            <td class="govuk-table__cell ">NN</td>
                                            <td class="govuk-table__cell ">Description of a geographical area</td>
                                            <td class="govuk-table__cell ">0 Country</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Create geographical area description</td>
                                            <td class="govuk-table__cell ">NN</td>
                                            <td class="govuk-table__cell ">Description of a geographical area</td>
                                            <td class="govuk-table__cell ">0 Country</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Update geographical area description</td>
                                            <td class="govuk-table__cell ">NN</td>
                                            <td class="govuk-table__cell ">Description of a geographical area</td>
                                            <td class="govuk-table__cell ">0 Country</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Delete geographical area description</td>
                                            <td class="govuk-table__cell ">NN</td>
                                            <td class="govuk-table__cell ">Description of a geographical area</td>
                                            <td class="govuk-table__cell ">0 Country</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- End accordion section - geographical areas //-->
                        </div>

                        <div class="govuk-accordion__section " id="block_geographical_area_memberships">
                            <!-- Start accordion section - geographical area memberships //-->
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-accordion-with-summary-sections-geographical areas">
                                        Geographical area memberships (3)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-accordion-with-summary-sections-geographical areas" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-accordion-with-summary-sections-geographical areas">
                                <table class="govuk-table govuk-table--m">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th width="10%" scope="col" class="govuk-table__header ">Activity</th>
                                            <th width="26%" scope="col" class="govuk-table__header ">Parent</th>
                                            <th width="26%" scope="col" class="govuk-table__header ">Member</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Start date</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">End date</th>
                                            <th width="10%" scope="col" class="govuk-table__header c">Status</th>
                                            <th scope="col" class="govuk-table__header nw l">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Create membership</td>
                                            <td class="govuk-table__cell ">1033 CARIFORUM</td>
                                            <td class="govuk-table__cell ">HT Haiti</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Update membership</td>
                                            <td class="govuk-table__cell ">1033 CARIFORUM</td>
                                            <td class="govuk-table__cell ">HT Haiti</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">31&nbsp;Dec&nbsp;21</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Delete membership</td>
                                            <td class="govuk-table__cell ">1033 CARIFORUM</td>
                                            <td class="govuk-table__cell ">HT Haiti</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;22</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <!-- End accordion section - geographical area memberships //-->
                        </div>

                        <div class="govuk-accordion__section " id="block_regulations">
                            <!-- Start accordion section - regulations //-->
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-accordion-with-summary-sections-regulations">
                                        Regulations (3)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-accordion-with-summary-sections-regulations" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-accordion-with-summary-sections-regulations">
                                <table class="govuk-table govuk-table--m">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th width="10%" scope="col" class="govuk-table__header ">Activity</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Regulation ID</th>
                                            <th width="28%" scope="col" class="govuk-table__header ">Regulation description and key fields</th>
                                            <th width="14%" scope="col" class="govuk-table__header ">Regulation group</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Start date</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">End date</th>
                                            <th width="10%" scope="col" class="govuk-table__header c">Status</th>
                                            <th scope="col" class="govuk-table__header nw l">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Create regulation</td>
                                            <td class="govuk-table__cell ">N2100010</td>
                                            <td class="govuk-table__cell "><b>Public identifier</b>: Taxation Notice: 2021/001<br /><b>Description</b>: Description of a regulation<br /><b>URL</b>: www.test_url.com<br /><b>Trade Remedies case</b>: </td>
                                            <td class="govuk-table__cell ">DUM - Trade remedies (Anti-dumping and anti-subsidy duties)</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&workbasket_id=128&workbasket_item_sid=459"><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_466" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Update regulation</td>
                                            <td class="govuk-table__cell ">X2200011</td>
                                            <td class="govuk-table__cell "><b>Public identifier</b>: 2022 No. 1<br /><b>Description</b>: My regulation<br /><b>URL</b>: http://www.legislation.gov.uk/uksi/2022/1/contents/made<br /><b>Trade Remedies case</b>: </td>
                                            <td class="govuk-table__cell ">DUM - Trade remedies (Anti-dumping and anti-subsidy duties)</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;22</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&workbasket_id=128&workbasket_item_sid=466"><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_466" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Delete regulation</td>
                                            <td class="govuk-table__cell ">X2200011</td>
                                            <td class="govuk-table__cell "><b>Public identifier</b>: 2022 No. 1<br /><b>Description</b>: My regulation<br /><b>URL</b>: http://www.legislation.gov.uk/uksi/2022/1/contents/made<br /><b>Trade Remedies case</b>: </td>
                                            <td class="govuk-table__cell ">DUM - Trade remedies (Anti-dumping and anti-subsidy duties)</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;22</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&workbasket_id=128&workbasket_item_sid=466"><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- End accordion section - regulations //-->
                        </div>

                        <div class="govuk-accordion__section " id="block_quotas">
                            <!-- Start accordion section - quotas //-->
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-accordion-with-summary-sections-regulations">
                                        Quotas (1)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-accordion-with-summary-sections-regulations" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-accordion-with-summary-sections-regulations">
                                <table class="govuk-table govuk-table--m">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th width="10%" scope="col" class="govuk-table__header ">Activity</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Order number</th>
                                            <th width="32%" scope="col" class="govuk-table__header ">Description and key fields</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Origin(s)</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Start date</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">End date</th>
                                            <th width="10%" scope="col" class="govuk-table__header c">Status</th>
                                            <th scope="col" class="govuk-table__header nw l">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr id="workbasket_item_sid_459" class="govuk-table__row noborder">
                                            <td class="govuk-table__cell ">Create quota</td>
                                            <td class="govuk-table__cell ">081234</td>
                                            <td class="govuk-table__cell ">
                                                <b>Description</b>: Sheep, lamb and goat meat<br />
                                                <b>Mechanism</b>: First Come, First Served<br />
                                                <b>Category</b>: WTO quota<br />
                                                <b>Measure type</b>:122 Non preferential tariff quota<br /><br />
                                                <a id="workbasket_view_detail_01" class="govuk-link workbasket_view_detail" href="">View detail of quota 081234 ...</a>
                                            </td>
                                            <td class="govuk-table__cell ">Argentina</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&workbasket_id=128&workbasket_item_sid=459"><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_detail_01" class="govuk-table__row xhidden">
                                            <td width="10%" class="govuk-table__cell ">&nbsp;</td>
                                            <td width="10%" class="govuk-table__cell ">&nbsp;</td>
                                            <td width="80%" class="govuk-table__cell silver" colspan="6">

                                                <h2 class="govuk-heading-s mb0">Quota definition periods</h2>
                                                <table class="govuk-table govuk-table--s">
                                                    <thead class="govuk-table__head">
                                                        <tr class="govuk-table__row">
                                                            <th width="15%" scope="col" class="govuk-table__header ">From</th>
                                                            <th width="15%" scope="col" class="govuk-table__header ">To</th>
                                                            <th width="15%" scope="col" class="govuk-table__header ">Initial volume</th>
                                                            <th width="15%" scope="col" class="govuk-table__header">Critical status</th>
                                                            <th width="40%" scope="col" class="govuk-table__header c">Critical threshold</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="govuk-table__body">
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 21</td>
                                                            <td class="govuk-table__cell ">31 Dec 21</td>
                                                            <td class="govuk-table__cell ">200,000 KGM</td>
                                                            <td class="govuk-table__cell">No</td>
                                                            <td class="govuk-table__cell c">90</td>
                                                        </tr>
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 22</td>
                                                            <td class="govuk-table__cell ">31 Dec 22</td>
                                                            <td class="govuk-table__cell ">200,000 KGM</td>
                                                            <td class="govuk-table__cell">No</td>
                                                            <td class="govuk-table__cell c">90</td>
                                                        </tr>
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 23</td>
                                                            <td class="govuk-table__cell ">31 Dec 23</td>
                                                            <td class="govuk-table__cell ">200,000 KGM</td>
                                                            <td class="govuk-table__cell">No</td>
                                                            <td class="govuk-table__cell c">90</td>
                                                        </tr>
                                                    </tbody>
                                                </table><br>

                                                <h2 class="govuk-heading-s mb0">Measures</h2>
                                                <table class="govuk-table govuk-table--s">
                                                    <thead class="govuk-table__head">
                                                        <tr class="govuk-table__row">
                                                            <th scope="col" class="govuk-table__header ">From</th>
                                                            <th scope="col" class="govuk-table__header ">To</th>
                                                            <th scope="col" class="govuk-table__header ">Commodity code</th>
                                                            <th scope="col" class="govuk-table__header ">Regulation</th>
                                                            <th scope="col" class="govuk-table__header ">Measure type</th>
                                                            <th scope="col" class="govuk-table__header ">Geo area</th>
                                                            <th width="25%" scope="col" class="govuk-table__header ">Duty</th>
                                                            <th scope="col" class="govuk-table__header ">Conditions</th>
                                                            <th scope="col" class="govuk-table__header ">Footnotes</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="govuk-table__body">
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 21</td>
                                                            <td class="govuk-table__cell ">31 Dec 21</td>
                                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000007") ?></td>
                                                            <td class="govuk-table__cell ">N2100010</td>
                                                            <td class="govuk-table__cell ">142</td>
                                                            <td class="govuk-table__cell ">AD</td>
                                                            <td class="govuk-table__cell ">0.0%</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                        </tr>
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 22</td>
                                                            <td class="govuk-table__cell ">31 Dec 22</td>
                                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000008") ?></td>
                                                            <td class="govuk-table__cell ">N2100010</td>
                                                            <td class="govuk-table__cell ">142</td>
                                                            <td class="govuk-table__cell ">AD</td>
                                                            <td class="govuk-table__cell ">0.0%</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                        </tr>
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 23</td>
                                                            <td class="govuk-table__cell ">31 Dec 23</td>
                                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000008") ?></td>
                                                            <td class="govuk-table__cell ">N2100010</td>
                                                            <td class="govuk-table__cell ">142</td>
                                                            <td class="govuk-table__cell ">AD</td>
                                                            <td class="govuk-table__cell ">0.0%</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <!-- End accordion section - regulations //-->
                        </div>

                        <div class="govuk-accordion__section " id="block_quota_definitions">
                            <!-- Start accordion section - quota definitions //-->
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-accordion-with-summary-sections-regulations">
                                        Quota definitions (3)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-accordion-with-summary-sections-regulations" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-accordion-with-summary-sections-regulations">
                                <table class="govuk-table govuk-table--m">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th width="10%" scope="col" class="govuk-table__header ">Activity</th>
                                            <th width="16%" scope="col" class="govuk-table__header ">Quota order number</th>
                                            <th width="16%" scope="col" class="govuk-table__header ">Initial volume</th>
                                            <th width="10%" scope="col" class="govuk-table__header c">Critical status</th>
                                            <th width="10%" scope="col" class="govuk-table__header c">Critical threshold</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Start date</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">End date</th>
                                            <th width="10%" scope="col" class="govuk-table__header c">Status</th>
                                            <th scope="col" class="govuk-table__header nw l">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Create quota definition</td>
                                            <td class="govuk-table__cell ">081234</td>
                                            <td class="govuk-table__cell ">200,000 KGM</td>
                                            <td class="govuk-table__cell c">No</td>
                                            <td class="govuk-table__cell c">90</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">31&nbsp;Dec&nbsp;21</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&workbasket_id=128&workbasket_item_sid=459"><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Update quota definition</td>
                                            <td class="govuk-table__cell ">081234</td>
                                            <td class="govuk-table__cell ">300,000 KGM</td>
                                            <td class="govuk-table__cell c">No</td>
                                            <td class="govuk-table__cell c">90</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">31&nbsp;Dec&nbsp;21</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&workbasket_id=128&workbasket_item_sid=459"><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Delete quota definition</td>
                                            <td class="govuk-table__cell ">081234</td>
                                            <td class="govuk-table__cell ">300,000 KGM</td>
                                            <td class="govuk-table__cell c">No</td>
                                            <td class="govuk-table__cell c">90</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;22</td>
                                            <td class="govuk-table__cell ">31&nbsp;Dec&nbsp;22</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&workbasket_id=128&workbasket_item_sid=459"><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- End accordion section - regulations //-->
                        </div>

                        <div class="govuk-accordion__section " id="block_quota_associations">
                            <!-- Start accordion section - quota associations //-->
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-accordion-with-summary-sections-regulations">
                                        Quota associations (3)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-accordion-with-summary-sections-regulations" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-accordion-with-summary-sections-regulations">
                                <table class="govuk-table govuk-table--m">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th width="10%" scope="col" class="govuk-table__header ">Activity</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Main order number</th>
                                            <th width="16%" scope="col" class="govuk-table__header ">Main definition</th>
                                            <th width="10%" scope="col" class="govuk-table__header">Sub order number</th>
                                            <th width="16%" scope="col" class="govuk-table__header">Sub definition</th>
                                            <th width="10%" scope="col" class="govuk-table__header c">Relation type</th>
                                            <th width="10%" scope="col" class="govuk-table__header c">Coefficient</th>
                                            <th width="10%" scope="col" class="govuk-table__header c">Status</th>
                                            <th scope="col" class="govuk-table__header nw l">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Create quota association</td>
                                            <td class="govuk-table__cell ">081234</td>
                                            <td class="govuk-table__cell ">1 Jan 2021 - 31 Dec 2021</td>
                                            <td class="govuk-table__cell ">081235</td>
                                            <td class="govuk-table__cell ">1 Jan 2021 - 31 Dec 2021</td>
                                            <td class="govuk-table__cell c">NM</td>
                                            <td class="govuk-table__cell c">1.00000</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&workbasket_id=128&workbasket_item_sid=459"><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Update quota association</td>
                                            <td class="govuk-table__cell ">081234</td>
                                            <td class="govuk-table__cell ">1 Jan 2021 - 31 Dec 2021</td>
                                            <td class="govuk-table__cell ">081235</td>
                                            <td class="govuk-table__cell ">1 Jan 2021 - 31 Dec 2021</td>
                                            <td class="govuk-table__cell c">NM</td>
                                            <td class="govuk-table__cell c">1.00000</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&workbasket_id=128&workbasket_item_sid=459"><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Delete quota association</td>
                                            <td class="govuk-table__cell ">081234</td>
                                            <td class="govuk-table__cell ">1 Jan 2022 - 31 Dec 2022</td>
                                            <td class="govuk-table__cell ">081235</td>
                                            <td class="govuk-table__cell ">1 Jan 2022 - 31 Dec 2022</td>
                                            <td class="govuk-table__cell c">NM</td>
                                            <td class="govuk-table__cell c">1.00000</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&workbasket_id=128&workbasket_item_sid=459"><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- End accordion section - quota associations //-->
                        </div>


                        <div class="govuk-accordion__section " id="block_quota_blocking_periods">
                            <!-- Start accordion section - quota blocking periods //-->
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-accordion-with-summary-sections-regulations">
                                        Quota blocking periods (3)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-accordion-with-summary-sections-regulations" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-accordion-with-summary-sections-regulations">
                                <table class="govuk-table govuk-table--m">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th width="10%" scope="col" class="govuk-table__header ">Activity</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Order number</th>
                                            <th width="16%" scope="col" class="govuk-table__header ">Definition</th>
                                            <th width="10%" scope="col" class="govuk-table__header">Type</th>
                                            <th width="16%" scope="col" class="govuk-table__header">Description</th>
                                            <th width="10%" scope="col" class="govuk-table__header c">Start date</th>
                                            <th width="10%" scope="col" class="govuk-table__header c">End date</th>
                                            <th width="10%" scope="col" class="govuk-table__header c">Status</th>
                                            <th scope="col" class="govuk-table__header nw l">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Create quota blocking period</td>
                                            <td class="govuk-table__cell ">081234</td>
                                            <td class="govuk-table__cell ">1 Jan 2021 - 31 Dec 2021</td>
                                            <td class="govuk-table__cell ">1 - Block the allocations for a quota due to a late publication.</td>
                                            <td class="govuk-table__cell ">Description of blocking period</td>
                                            <td class="govuk-table__cell c">1 Jan 2021</td>
                                            <td class="govuk-table__cell c">3 Feb 2021</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&workbasket_id=128&workbasket_item_sid=459"><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Update quota blocking period</td>
                                            <td class="govuk-table__cell ">081234</td>
                                            <td class="govuk-table__cell ">1 Jan 2021 - 31 Dec 2021</td>
                                            <td class="govuk-table__cell ">1 - Block the allocations for a quota due to a late publication.</td>
                                            <td class="govuk-table__cell ">Description of blocking period</td>
                                            <td class="govuk-table__cell c">1 Jan 2021</td>
                                            <td class="govuk-table__cell c">3 Feb 2021</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&workbasket_id=128&workbasket_item_sid=459"><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Delete quota blocking period</td>
                                            <td class="govuk-table__cell ">081234</td>
                                            <td class="govuk-table__cell ">1 Jan 2021 - 31 Dec 2021</td>
                                            <td class="govuk-table__cell ">1 - Block the allocations for a quota due to a late publication.</td>
                                            <td class="govuk-table__cell ">Description of blocking period</td>
                                            <td class="govuk-table__cell c">1 Jan 2021</td>
                                            <td class="govuk-table__cell c">3 Feb 2021</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&workbasket_id=128&workbasket_item_sid=459"><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- End accordion section - quota blocking periods //-->
                        </div>

                        <div class="govuk-accordion__section " id="block_quota_suspension_periods">
                            <!-- Start accordion section - quota suspension periods //-->
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-accordion-with-summary-sections-regulations">
                                        Quota suspension periods (3)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-accordion-with-summary-sections-regulations" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-accordion-with-summary-sections-regulations">
                                <table class="govuk-table govuk-table--m">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th width="10%" scope="col" class="govuk-table__header ">Activity</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Order number</th>
                                            <th width="16%" scope="col" class="govuk-table__header ">Definition</th>
                                            <th width="26%" scope="col" class="govuk-table__header">Description</th>
                                            <th width="10%" scope="col" class="govuk-table__header c">Start date</th>
                                            <th width="10%" scope="col" class="govuk-table__header c">End date</th>
                                            <th width="10%" scope="col" class="govuk-table__header c">Status</th>
                                            <th scope="col" class="govuk-table__header nw l">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Create quota suspension period</td>
                                            <td class="govuk-table__cell ">081234</td>
                                            <td class="govuk-table__cell ">1 Jan 2021 - 31 Dec 2021</td>
                                            <td class="govuk-table__cell ">Description of suspension period</td>
                                            <td class="govuk-table__cell c">1 Jan 2021</td>
                                            <td class="govuk-table__cell c">3 Feb 2021</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&workbasket_id=128&workbasket_item_sid=459"><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Update quota suspension period</td>
                                            <td class="govuk-table__cell ">081234</td>
                                            <td class="govuk-table__cell ">1 Jan 2021 - 31 Dec 2021</td>
                                            <td class="govuk-table__cell ">Description of suspension period</td>
                                            <td class="govuk-table__cell c">1 Jan 2021</td>
                                            <td class="govuk-table__cell c">3 Feb 2021</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&workbasket_id=128&workbasket_item_sid=459"><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Delete quota suspension period</td>
                                            <td class="govuk-table__cell ">081234</td>
                                            <td class="govuk-table__cell ">1 Jan 2021 - 31 Dec 2021</td>
                                            <td class="govuk-table__cell ">Description of suspension period</td>
                                            <td class="govuk-table__cell c">1 Jan 2021</td>
                                            <td class="govuk-table__cell c">3 Feb 2021</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&workbasket_id=128&workbasket_item_sid=459"><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- End accordion section - quota suspension periods //-->
                        </div>

                        <div class="govuk-accordion__section " id="block_commodities">
                            <!-- Start accordion section - commodities //-->
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-accordion-with-summary-sections-commodities">
                                        Commodity codes (6)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-accordion-with-summary-sections-commodities" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-accordion-with-summary-sections-commodities">
                                <table class="govuk-table govuk-table--m">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th width="10%" scope="col" class="govuk-table__header ">Activity</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Commodity code</th>
                                            <th width="10%" scope="col" class="govuk-table__header c">Suffix</th>
                                            <th width="10%" scope="col" class="govuk-table__header c">Indent</th>
                                            <th width="22%" scope="col" class="govuk-table__header ">Description</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Start date</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">End date</th>
                                            <th width="10%" scope="col" class="govuk-table__header c">Status</th>
                                            <th scope="col" class="govuk-table__header nw l">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Create commodity code</td>
                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000007") ?></td>
                                            <td class="govuk-table__cell c">80</td>
                                            <td class="govuk-table__cell c">6</td>
                                            <td class="govuk-table__cell ">Lorem ipsum dolor sit amet, consectetur adipiscing elit. De illis, cum volemus. An eiusdem modi</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Update commodity code</td>
                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000007") ?></td>
                                            <td class="govuk-table__cell c">80</td>
                                            <td class="govuk-table__cell c">6</td>
                                            <td class="govuk-table__cell ">Lorem ipsum dolor sit amet, consectetur adipiscing elit. De illis, cum volemus. An eiusdem modi</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">31&nbsp;Dec&nbsp;21</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Delete commodity code</td>
                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000007") ?></td>
                                            <td class="govuk-table__cell c">80</td>
                                            <td class="govuk-table__cell c">6</td>
                                            <td class="govuk-table__cell ">Lorem ipsum dolor sit amet, consectetur adipiscing elit. De illis, cum volemus. An eiusdem modi</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">31&nbsp;Dec&nbsp;21</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Create commodity code description</td>
                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000007") ?></td>
                                            <td class="govuk-table__cell c">80</td>
                                            <td class="govuk-table__cell c">6</td>
                                            <td class="govuk-table__cell ">Lorem ipsum dolor sit amet, consectetur adipiscing elit. De illis, cum volemus. An eiusdem modi</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Update commodity code description</td>
                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000007") ?></td>
                                            <td class="govuk-table__cell c">80</td>
                                            <td class="govuk-table__cell c">6</td>
                                            <td class="govuk-table__cell ">Lorem ipsum dolor sit amet, consectetur adipiscing elit. De illis, cum volemus. An eiusdem modi</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">31&nbsp;Dec&nbsp;21</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_item_sid_463" class="govuk-table__row">
                                            <td class="govuk-table__cell ">Delete commodity code description</td>
                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000007") ?></td>
                                            <td class="govuk-table__cell c">80</td>
                                            <td class="govuk-table__cell c">6</td>
                                            <td class="govuk-table__cell ">Lorem ipsum dolor sit amet, consectetur adipiscing elit. De illis, cum volemus. An eiusdem modi</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">31&nbsp;Dec&nbsp;21</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href=""><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href=""><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <!-- End accordion section - commodities //-->
                        </div>

                        <!-- Start accordion section - measure activities //-->
                        <div class="govuk-accordion__section ">
                            <div class="govuk-accordion__section-header">
                                <h2 class="govuk-accordion__section-heading">
                                    <span class="govuk-accordion__section-button" id="accordion-with-summary-sections-heading-accordion-with-summary-sections-measure_activities">
                                        Measure activities (7)
                                    </span>
                                </h2>
                            </div>
                            <div id="accordion-with-summary-sections-content-accordion-with-summary-sections-measure_activities" class="govuk-accordion__section-content" aria-labelledby="accordion-with-summary-sections-heading-accordion-with-summary-sections-measure_activities">
                                <table class="govuk-table govuk-table--m">
                                    <thead class="govuk-table__head">
                                        <tr class="govuk-table__row">
                                            <th width="10%" scope="col" class="govuk-table__header ">Activity</th>
                                            <th width="18%" scope="col" class="govuk-table__header ">Activity name</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Regulation</th>
                                            <th width="12%" scope="col" class="govuk-table__header ">Measure type</th>
                                            <th width="12%" scope="col" class="govuk-table__header ">Geo. area</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">Start date</th>
                                            <th width="10%" scope="col" class="govuk-table__header ">End date</th>
                                            <th width="10%" scope="col" class="govuk-table__header c">Status</th>
                                            <th scope="col" class="govuk-table__header nw l">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="govuk-table__body">


                                        <tr id="workbasket_create_measures" class="govuk-table__row noborder">
                                            <td class="govuk-table__cell ">Create measures</td>
                                            <td class="govuk-table__cell ">
                                                Andorra trade agreement - 1st tranche
                                                <br /><br />
                                                <a id="workbasket_view_detail_01" class="govuk-link workbasket_view_detail" href="">View activity detail</a>
                                            </td>
                                            <td class="govuk-table__cell ">N2100010</td>
                                            <td class="govuk-table__cell ">142 - Tariff preference</td>
                                            <td class="govuk-table__cell ">Andorra</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: Incomplete' src='/assets/images/incomplete.png' /><br />Incomplete</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href="/measures/create_edit_summary.html?mode=view&measure_activity_sid=143"><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&workbasket_id=128&workbasket_item_sid=460"><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                    <li><a class="govuk-link" title="Complete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&workbasket_id=128&workbasket_item_sid=460"><img src="/assets/images/continue.png" /><span>Complete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_detail_01" class="govuk-table__row xhidden">
                                            <td width="10%" class="govuk-table__cell ">&nbsp;</td>
                                            <td width="90%" class="govuk-table__cell silver" colspan="8">

                                                <h2 class="govuk-heading-s mb0">Measures</h2>
                                                <table class="govuk-table govuk-table--s">
                                                    <thead class="govuk-table__head">
                                                        <tr class="govuk-table__row">
                                                            <th scope="col" class="govuk-table__header ">From</th>
                                                            <th scope="col" class="govuk-table__header ">To</th>
                                                            <th scope="col" class="govuk-table__header ">Commodity code</th>
                                                            <th scope="col" class="govuk-table__header ">Regulation</th>
                                                            <th scope="col" class="govuk-table__header ">Measure type</th>
                                                            <th scope="col" class="govuk-table__header ">Geo area</th>
                                                            <th scope="col" class="govuk-table__header ">Duty</th>
                                                            <th scope="col" class="govuk-table__header ">Conditions</th>
                                                            <th scope="col" class="govuk-table__header ">Footnotes</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="govuk-table__body">
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 21</td>
                                                            <td class="govuk-table__cell ">31 Dec 21</td>
                                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000007") ?></td>
                                                            <td class="govuk-table__cell ">N2100010</td>
                                                            <td class="govuk-table__cell ">142</td>
                                                            <td class="govuk-table__cell ">AD</td>
                                                            <td class="govuk-table__cell ">0.0%</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                        </tr>
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 22</td>
                                                            <td class="govuk-table__cell ">31 Dec 22</td>
                                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000008") ?></td>
                                                            <td class="govuk-table__cell ">N2100010</td>
                                                            <td class="govuk-table__cell ">142</td>
                                                            <td class="govuk-table__cell ">AD</td>
                                                            <td class="govuk-table__cell ">0.0%</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                        </tr>
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 23</td>
                                                            <td class="govuk-table__cell ">31 Dec 23</td>
                                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000008") ?></td>
                                                            <td class="govuk-table__cell ">N2100010</td>
                                                            <td class="govuk-table__cell ">142</td>
                                                            <td class="govuk-table__cell ">AD</td>
                                                            <td class="govuk-table__cell ">0.0%</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </td>
                                        </tr>


                                        <tr id="workbasket_change_generating_regulation" class="govuk-table__row noborder">
                                            <td class="govuk-table__cell ">Change generating regulation</td>
                                            <td class="govuk-table__cell ">
                                                Andorra trade agreement - change regulation
                                                <br /><br />
                                                <a id="workbasket_view_detail_01" class="govuk-link workbasket_view_detail" href="">View activity detail</a>
                                            </td>
                                            <td class="govuk-table__cell ">N2100010</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href="/measures/create_edit_summary.html?mode=view&measure_activity_sid=143"><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&workbasket_id=128&workbasket_item_sid=460"><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_detail_01" class="govuk-table__row xhidden">
                                            <td width="10%" class="govuk-table__cell ">&nbsp;</td>
                                            <td width="90%" class="govuk-table__cell silver" colspan="8">

                                                <h2 class="govuk-heading-s mb0">Measures</h2>
                                                <table class="govuk-table govuk-table--s">
                                                    <thead class="govuk-table__head">
                                                        <tr class="govuk-table__row">
                                                        <th scope="col" class="govuk-table__header ">From</th>
                                                            <th scope="col" class="govuk-table__header ">To</th>
                                                            <th scope="col" class="govuk-table__header ">Commodity code</th>
                                                            <th scope="col" class="govuk-table__header ">Regulation</th>
                                                            <th scope="col" class="govuk-table__header ">Measure type</th>
                                                            <th scope="col" class="govuk-table__header ">Geo area</th>
                                                            <th scope="col" class="govuk-table__header ">Duty</th>
                                                            <th scope="col" class="govuk-table__header ">Conditions</th>
                                                            <th scope="col" class="govuk-table__header ">Footnotes</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="govuk-table__body">
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 21</td>
                                                            <td class="govuk-table__cell ">31 Dec 21</td>
                                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000007") ?></td>
                                                            <td class="govuk-table__cell ">N2100010</td>
                                                            <td class="govuk-table__cell ">142</td>
                                                            <td class="govuk-table__cell ">AD</td>
                                                            <td class="govuk-table__cell ">0.0%</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                        </tr>
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 22</td>
                                                            <td class="govuk-table__cell ">31 Dec 22</td>
                                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000008") ?></td>
                                                            <td class="govuk-table__cell ">N2100010</td>
                                                            <td class="govuk-table__cell ">142</td>
                                                            <td class="govuk-table__cell ">AD</td>
                                                            <td class="govuk-table__cell ">0.0%</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                        </tr>
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 23</td>
                                                            <td class="govuk-table__cell ">31 Dec 23</td>
                                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000008") ?></td>
                                                            <td class="govuk-table__cell ">N2100010</td>
                                                            <td class="govuk-table__cell ">142</td>
                                                            <td class="govuk-table__cell ">AD</td>
                                                            <td class="govuk-table__cell ">0.0%</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </td>
                                        </tr>


                                        <tr id="workbasket_item_sid_460" class="govuk-table__row noborder">
                                            <td class="govuk-table__cell ">Change measure type</td>
                                            <td class="govuk-table__cell ">
                                                Andorra trade agreement - Changing to authorised use only
                                                <br /><br />
                                                <a id="workbasket_view_detail_01" class="govuk-link workbasket_view_detail" href="">View activity detail</a>
                                            </td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell ">142 - Tariff preference</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href="/measures/create_edit_summary.html?mode=view&measure_activity_sid=143"><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&workbasket_id=128&workbasket_item_sid=460"><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_detail_01" class="govuk-table__row xhidden">
                                            <td width="10%" class="govuk-table__cell ">&nbsp;</td>
                                            <td width="90%" class="govuk-table__cell silver" colspan="8">

                                                <h2 class="govuk-heading-s mb0">Measures</h2>
                                                <table class="govuk-table govuk-table--s">
                                                    <thead class="govuk-table__head">
                                                        <tr class="govuk-table__row">
                                                        <th scope="col" class="govuk-table__header ">From</th>
                                                            <th scope="col" class="govuk-table__header ">To</th>
                                                            <th scope="col" class="govuk-table__header ">Commodity code</th>
                                                            <th scope="col" class="govuk-table__header ">Regulation</th>
                                                            <th scope="col" class="govuk-table__header ">Measure type</th>
                                                            <th scope="col" class="govuk-table__header ">Geo area</th>
                                                            <th scope="col" class="govuk-table__header ">Duty</th>
                                                            <th scope="col" class="govuk-table__header ">Conditions</th>
                                                            <th scope="col" class="govuk-table__header ">Footnotes</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="govuk-table__body">
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 21</td>
                                                            <td class="govuk-table__cell ">31 Dec 21</td>
                                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000007") ?></td>
                                                            <td class="govuk-table__cell ">N2100010</td>
                                                            <td class="govuk-table__cell ">142</td>
                                                            <td class="govuk-table__cell ">AD</td>
                                                            <td class="govuk-table__cell ">0.0%</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                        </tr>
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 22</td>
                                                            <td class="govuk-table__cell ">31 Dec 22</td>
                                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000008") ?></td>
                                                            <td class="govuk-table__cell ">N2100010</td>
                                                            <td class="govuk-table__cell ">142</td>
                                                            <td class="govuk-table__cell ">AD</td>
                                                            <td class="govuk-table__cell ">0.0%</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                        </tr>
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 23</td>
                                                            <td class="govuk-table__cell ">31 Dec 23</td>
                                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000008") ?></td>
                                                            <td class="govuk-table__cell ">N2100010</td>
                                                            <td class="govuk-table__cell ">142</td>
                                                            <td class="govuk-table__cell ">AD</td>
                                                            <td class="govuk-table__cell ">0.0%</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </td>
                                        </tr>



                                        <tr id="workbasket_change_validity_period" class="govuk-table__row noborder">
                                            <td class="govuk-table__cell ">Change validity period</td>
                                            <td class="govuk-table__cell ">
                                                Andorra Trade Agreement - terminate measures
                                                <br /><br />
                                                <a id="workbasket_view_detail_01" class="govuk-link workbasket_view_detail" href="">View activity detail</a>
                                            </td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">31&nbsp;Dec&nbsp;21</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href="/measures/create_edit_summary.html?mode=view&measure_activity_sid=143"><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&workbasket_id=128&workbasket_item_sid=460"><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_detail_01" class="govuk-table__row xhidden">
                                            <td width="10%" class="govuk-table__cell ">&nbsp;</td>
                                            <td width="90%" class="govuk-table__cell silver" colspan="8">

                                                <h2 class="govuk-heading-s mb0">Measures</h2>
                                                <table class="govuk-table govuk-table--s">
                                                    <thead class="govuk-table__head">
                                                        <tr class="govuk-table__row">
                                                        <th scope="col" class="govuk-table__header ">From</th>
                                                            <th scope="col" class="govuk-table__header ">To</th>
                                                            <th scope="col" class="govuk-table__header ">Commodity code</th>
                                                            <th scope="col" class="govuk-table__header ">Regulation</th>
                                                            <th scope="col" class="govuk-table__header ">Measure type</th>
                                                            <th scope="col" class="govuk-table__header ">Geo area</th>
                                                            <th scope="col" class="govuk-table__header ">Duty</th>
                                                            <th scope="col" class="govuk-table__header ">Conditions</th>
                                                            <th scope="col" class="govuk-table__header ">Footnotes</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="govuk-table__body">
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 21</td>
                                                            <td class="govuk-table__cell ">31 Dec 21</td>
                                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000007") ?></td>
                                                            <td class="govuk-table__cell ">N2100010</td>
                                                            <td class="govuk-table__cell ">142</td>
                                                            <td class="govuk-table__cell ">AD</td>
                                                            <td class="govuk-table__cell ">0.0%</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                        </tr>
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 22</td>
                                                            <td class="govuk-table__cell ">31 Dec 22</td>
                                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000008") ?></td>
                                                            <td class="govuk-table__cell ">N2100010</td>
                                                            <td class="govuk-table__cell ">142</td>
                                                            <td class="govuk-table__cell ">AD</td>
                                                            <td class="govuk-table__cell ">0.0%</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                        </tr>
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 23</td>
                                                            <td class="govuk-table__cell ">31 Dec 23</td>
                                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000008") ?></td>
                                                            <td class="govuk-table__cell ">N2100010</td>
                                                            <td class="govuk-table__cell ">142</td>
                                                            <td class="govuk-table__cell ">AD</td>
                                                            <td class="govuk-table__cell ">0.0%</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </td>
                                        </tr>



                                        <tr id="workbasket_geography" class="govuk-table__row noborder">
                                            <td class="govuk-table__cell ">Change geography</td>
                                            <td class="govuk-table__cell ">
                                                Iceland trade agreement - change geography
                                                <br /><br />
                                                <a id="workbasket_view_detail_01" class="govuk-link workbasket_view_detail" href="">View activity detail</a>
                                            </td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell ">Iceland</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href="/measures/create_edit_summary.html?mode=view&measure_activity_sid=143"><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&workbasket_id=128&workbasket_item_sid=460"><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_detail_01" class="govuk-table__row xhidden">
                                            <td width="10%" class="govuk-table__cell ">&nbsp;</td>
                                            <td width="90%" class="govuk-table__cell silver" colspan="8">

                                                <h2 class="govuk-heading-s mb0">Measures</h2>
                                                <table class="govuk-table govuk-table--s">
                                                    <thead class="govuk-table__head">
                                                        <tr class="govuk-table__row">
                                                        <th scope="col" class="govuk-table__header ">From</th>
                                                            <th scope="col" class="govuk-table__header ">To</th>
                                                            <th scope="col" class="govuk-table__header ">Commodity code</th>
                                                            <th scope="col" class="govuk-table__header ">Regulation</th>
                                                            <th scope="col" class="govuk-table__header ">Measure type</th>
                                                            <th scope="col" class="govuk-table__header ">Geo area</th>
                                                            <th scope="col" class="govuk-table__header ">Duty</th>
                                                            <th scope="col" class="govuk-table__header ">Conditions</th>
                                                            <th scope="col" class="govuk-table__header ">Footnotes</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="govuk-table__body">
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 21</td>
                                                            <td class="govuk-table__cell ">31 Dec 21</td>
                                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000007") ?></td>
                                                            <td class="govuk-table__cell ">N2100010</td>
                                                            <td class="govuk-table__cell ">142</td>
                                                            <td class="govuk-table__cell ">AD</td>
                                                            <td class="govuk-table__cell ">0.0%</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                        </tr>
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 22</td>
                                                            <td class="govuk-table__cell ">31 Dec 22</td>
                                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000008") ?></td>
                                                            <td class="govuk-table__cell ">N2100010</td>
                                                            <td class="govuk-table__cell ">142</td>
                                                            <td class="govuk-table__cell ">AD</td>
                                                            <td class="govuk-table__cell ">0.0%</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                        </tr>
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 23</td>
                                                            <td class="govuk-table__cell ">31 Dec 23</td>
                                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000008") ?></td>
                                                            <td class="govuk-table__cell ">N2100010</td>
                                                            <td class="govuk-table__cell ">142</td>
                                                            <td class="govuk-table__cell ">AD</td>
                                                            <td class="govuk-table__cell ">0.0%</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </td>
                                        </tr>

                                        <tr id="workbasket_change_duties" class="govuk-table__row noborder">
                                            <td class="govuk-table__cell ">Change duties</td>
                                            <td class="govuk-table__cell ">
                                                Andorra trade agreement - change duties (annual staging)
                                                <br /><br />
                                                <a id="workbasket_view_detail_01" class="govuk-link workbasket_view_detail" href="">View activity detail</a>
                                            </td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href="/measures/create_edit_summary.html?mode=view&measure_activity_sid=143"><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&workbasket_id=128&workbasket_item_sid=460"><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_detail_01" class="govuk-table__row xhidden">
                                            <td width="10%" class="govuk-table__cell ">&nbsp;</td>
                                            <td width="90%" class="govuk-table__cell silver" colspan="8">

                                                <h2 class="govuk-heading-s mb0">Measures</h2>
                                                <table class="govuk-table govuk-table--s">
                                                    <thead class="govuk-table__head">
                                                        <tr class="govuk-table__row">
                                                        <th scope="col" class="govuk-table__header ">From</th>
                                                            <th scope="col" class="govuk-table__header ">To</th>
                                                            <th scope="col" class="govuk-table__header ">Commodity code</th>
                                                            <th scope="col" class="govuk-table__header ">Regulation</th>
                                                            <th scope="col" class="govuk-table__header ">Measure type</th>
                                                            <th scope="col" class="govuk-table__header ">Geo area</th>
                                                            <th scope="col" class="govuk-table__header ">Duty</th>
                                                            <th scope="col" class="govuk-table__header ">Conditions</th>
                                                            <th scope="col" class="govuk-table__header ">Footnotes</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="govuk-table__body">
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 21</td>
                                                            <td class="govuk-table__cell ">31 Dec 21</td>
                                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000007") ?></td>
                                                            <td class="govuk-table__cell ">N2100010</td>
                                                            <td class="govuk-table__cell ">142</td>
                                                            <td class="govuk-table__cell ">AD</td>
                                                            <td class="govuk-table__cell ">0.0%</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                        </tr>
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 22</td>
                                                            <td class="govuk-table__cell ">31 Dec 22</td>
                                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000008") ?></td>
                                                            <td class="govuk-table__cell ">N2100010</td>
                                                            <td class="govuk-table__cell ">142</td>
                                                            <td class="govuk-table__cell ">AD</td>
                                                            <td class="govuk-table__cell ">0.0%</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                        </tr>
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 23</td>
                                                            <td class="govuk-table__cell ">31 Dec 23</td>
                                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000008") ?></td>
                                                            <td class="govuk-table__cell ">N2100010</td>
                                                            <td class="govuk-table__cell ">142</td>
                                                            <td class="govuk-table__cell ">AD</td>
                                                            <td class="govuk-table__cell ">0.0%</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </td>
                                        </tr>



                                        <tr id="workbasket_item_sid_460" class="govuk-table__row noborder">
                                            <td class="govuk-table__cell ">Change footnotes</td>
                                            <td class="govuk-table__cell ">
                                                Apply footnotes to Andorra Trade Agreement
                                                <br /><br />
                                                <a id="workbasket_view_detail_01" class="govuk-link workbasket_view_detail" href="">View activity detail</a>
                                            </td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href="/measures/create_edit_summary.html?mode=view&measure_activity_sid=143"><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&workbasket_id=128&workbasket_item_sid=460"><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_detail_01" class="govuk-table__row xhidden">
                                            <td width="10%" class="govuk-table__cell ">&nbsp;</td>
                                            <td width="90%" class="govuk-table__cell silver" colspan="8">

                                                <h2 class="govuk-heading-s mb0">Measures</h2>
                                                <table class="govuk-table govuk-table--s">
                                                    <thead class="govuk-table__head">
                                                        <tr class="govuk-table__row">
                                                        <th scope="col" class="govuk-table__header ">From</th>
                                                            <th scope="col" class="govuk-table__header ">To</th>
                                                            <th scope="col" class="govuk-table__header ">Commodity code</th>
                                                            <th scope="col" class="govuk-table__header ">Regulation</th>
                                                            <th scope="col" class="govuk-table__header ">Measure type</th>
                                                            <th scope="col" class="govuk-table__header ">Geo area</th>
                                                            <th scope="col" class="govuk-table__header ">Duty</th>
                                                            <th scope="col" class="govuk-table__header ">Conditions</th>
                                                            <th scope="col" class="govuk-table__header ">Footnotes</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="govuk-table__body">
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 21</td>
                                                            <td class="govuk-table__cell ">31 Dec 21</td>
                                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000007") ?></td>
                                                            <td class="govuk-table__cell ">N2100010</td>
                                                            <td class="govuk-table__cell ">142</td>
                                                            <td class="govuk-table__cell ">AD</td>
                                                            <td class="govuk-table__cell ">0.0%</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                            <td class="govuk-table__cell ">FC001</td>
                                                        </tr>
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 22</td>
                                                            <td class="govuk-table__cell ">31 Dec 22</td>
                                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000008") ?></td>
                                                            <td class="govuk-table__cell ">N2100010</td>
                                                            <td class="govuk-table__cell ">142</td>
                                                            <td class="govuk-table__cell ">AD</td>
                                                            <td class="govuk-table__cell ">0.0%</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                            <td class="govuk-table__cell ">FC001</td>
                                                        </tr>
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 23</td>
                                                            <td class="govuk-table__cell ">31 Dec 23</td>
                                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000008") ?></td>
                                                            <td class="govuk-table__cell ">N2100010</td>
                                                            <td class="govuk-table__cell ">142</td>
                                                            <td class="govuk-table__cell ">AD</td>
                                                            <td class="govuk-table__cell ">0.0%</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                            <td class="govuk-table__cell ">FC001</td>
                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </td>
                                        </tr>



                                        <tr id="workbasket_terminate" class="govuk-table__row noborder">
                                            <td class="govuk-table__cell ">Delete measures</td>
                                            <td class="govuk-table__cell ">
                                                Delete unwanted Trade Remedy
                                                <br /><br />
                                                <a id="workbasket_view_detail_01" class="govuk-link workbasket_view_detail" href="">View activity detail</a>
                                            </td>
                                            <td class="govuk-table__cell ">N2100010</td>
                                            <td class="govuk-table__cell ">142 - Tariff preference</td>
                                            <td class="govuk-table__cell ">Andorra</td>
                                            <td class="govuk-table__cell ">01&nbsp;Jan&nbsp;21</td>
                                            <td class="govuk-table__cell ">-</td>
                                            <td class="govuk-table__cell c"><img class='status_image' alt='Status: In progress' title='Status: In progress' src='/assets/images/in_progress.png' /><br />In progress</td>
                                            <td class="govuk-table__cell nw" style="width:8%">
                                                <ul class="measure_activity_action_list">
                                                    <li><a class="govuk-link" title="View or edit this activity" href="/measures/create_edit_summary.html?mode=view&measure_activity_sid=143"><img src="/assets/images/view.png" /><span>View</span></a></li>
                                                    <li><a class="govuk-link" title="Delete this activity" href="workbasket_item_delete.php?action=delete_workbasket_item&workbasket_id=128&workbasket_item_sid=460"><img src="/assets/images/delete.png" /><span>Delete</span></a></li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr id="workbasket_detail_01" class="govuk-table__row xhidden">
                                            <td width="10%" class="govuk-table__cell ">&nbsp;</td>
                                            <td width="90%" class="govuk-table__cell silver" colspan="8">

                                                <h2 class="govuk-heading-s mb0">Measures</h2>
                                                <table class="govuk-table govuk-table--s">
                                                    <thead class="govuk-table__head">
                                                        <tr class="govuk-table__row">
                                                        <th scope="col" class="govuk-table__header ">From</th>
                                                            <th scope="col" class="govuk-table__header ">To</th>
                                                            <th scope="col" class="govuk-table__header ">Commodity code</th>
                                                            <th scope="col" class="govuk-table__header ">Regulation</th>
                                                            <th scope="col" class="govuk-table__header ">Measure type</th>
                                                            <th scope="col" class="govuk-table__header ">Geo area</th>
                                                            <th scope="col" class="govuk-table__header ">Duty</th>
                                                            <th scope="col" class="govuk-table__header ">Conditions</th>
                                                            <th scope="col" class="govuk-table__header ">Footnotes</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="govuk-table__body">
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 21</td>
                                                            <td class="govuk-table__cell ">31 Dec 21</td>
                                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000007") ?></td>
                                                            <td class="govuk-table__cell ">N2100010</td>
                                                            <td class="govuk-table__cell ">142</td>
                                                            <td class="govuk-table__cell ">AD</td>
                                                            <td class="govuk-table__cell ">0.0%</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                        </tr>
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 22</td>
                                                            <td class="govuk-table__cell ">31 Dec 22</td>
                                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000008") ?></td>
                                                            <td class="govuk-table__cell ">N2100010</td>
                                                            <td class="govuk-table__cell ">142</td>
                                                            <td class="govuk-table__cell ">AD</td>
                                                            <td class="govuk-table__cell ">0.0%</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                        </tr>
                                                        <tr id="workbasket_item_sid_459" class="govuk-table__row">
                                                            <td class="govuk-table__cell ">01 Jan 23</td>
                                                            <td class="govuk-table__cell ">31 Dec 23</td>
                                                            <td class="govuk-table__cell "><?= format_goods_nomenclature_item_id("0702000008") ?></td>
                                                            <td class="govuk-table__cell ">N2100010</td>
                                                            <td class="govuk-table__cell ">142</td>
                                                            <td class="govuk-table__cell ">AD</td>
                                                            <td class="govuk-table__cell ">0.0%</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                            <td class="govuk-table__cell ">-</td>
                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <script>
                            $('.modaal-ajax').modaal({
                                type: 'ajax'
                            });
                        </script>

                        <!-- End accordion section - measure activities //-->
                    </div>
                    <form method="get" action="actions.php">

                        <!-- Start hidden control //-->
                        <input type="hidden" name="workbasket_id" id="workbasket_id" value="128" />
                        <!-- End hidden control //-->
                        <!-- Start hidden control //-->
                        <input type="hidden" name="action" id="action" value="submit_for_approval" />
                        <!-- End hidden control //-->
                        <input type="hidden" name="submitted" id="submitted" value="1" /> <!-- Start button //-->
                        <button name="submit_workbasket" id="submit_workbasket" value="submit_workbasket" class="govuk-button" data-module="govuk-button">Submit workbasket for approval</button>
                        <!-- End button //-->
                    </form>

                </section>
                <section class="govuk-tabs__panel govuk-tabs__panel--hidden" id="workbasket_history">
                    <h2 class="govuk-heading-l">Workbasket history</h2>
                    <p class="govuk-body">The table below shows the actions that have been performed on this workbasket.</p>
                    <!-- Start table control //-->
                    <table class="govuk-table  govuk-table--m sticky" id="results">
                        <thead class="govuk-table__head">
                            <tr class="govuk-table__row">
                                <th scope="col" class="govuk-table__header  tip  nw">Date</th>
                                <th scope="col" class="govuk-table__header  tip  nw">User</th>
                                <th scope="col" class="govuk-table__header  tip  nw">Event type</th>
                                <th scope="col" class="govuk-table__header  tip ">Description</th>
                            </tr>
                        </thead>
                        <tbody class="govuk-table__body">
                            <tr class="govuk-table__row ">
                                <td class="govuk-table__cell nw">18&nbsp;Mar&nbsp;20&nbsp;16:15</td>
                                <td class="govuk-table__cell nw">Matt Lavis</td>
                                <td class="govuk-table__cell nw">Create workbasket</td>
                                <td class="govuk-table__cell "><span class='json b'>CREATE WORKBASKET</span><span class='json'><span class='json_key'>Title:</span><span class='json_value'>Steel trade remedy</span></span><span class='json'><span class='json_key'>Reason for creation:</span><span class='json_value'>safsfsdf</span></span></td>
                            </tr>
                            <tr class="govuk-table__row ">
                                <td class="govuk-table__cell nw">18&nbsp;Mar&nbsp;20&nbsp;16:16</td>
                                <td class="govuk-table__cell nw">Matt Lavis</td>
                                <td class="govuk-table__cell nw">Create activity</td>
                                <td class="govuk-table__cell "></td>
                            </tr>
                            <tr class="govuk-table__row ">
                                <td class="govuk-table__cell nw">05&nbsp;Apr&nbsp;20&nbsp;19:48</td>
                                <td class="govuk-table__cell nw">Matt Lavis</td>
                                <td class="govuk-table__cell nw">Create activity</td>
                                <td class="govuk-table__cell "><span class='json b'>NEW FOOTNOTE</span><span class='json'><span class='json_key'>Footnote type ID:</span><span class='json_value'>NC</span></span><span class='json'><span class='json_key'>Footnote ID:</span><span class='json_value'>035</span></span><span class='json'><span class='json_key'>Description:</span><span class='json_value'>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</span></span><span class='json'><span class='json_key'>Validity start date:</span><span class='json_value'>2021-01-01</span></span><span class='json'><span class='json_key'>Validity end date:</span><span class='json_value'>_</span></span></td>
                            </tr>
                            <tr class="govuk-table__row ">
                                <td class="govuk-table__cell nw">13&nbsp;Apr&nbsp;20&nbsp;12:20</td>
                                <td class="govuk-table__cell nw">Matt Lavis</td>
                                <td class="govuk-table__cell nw">Create activity</td>
                                <td class="govuk-table__cell "></td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- End table control //-->
                </section>

            </div>
        </main>
    </div>

    <!-- Begin footer //-->
    <footer class="govuk-footer " role="contentinfo">
        <div class="govuk-width-container ">
            <div class="govuk-footer__navigation">
                <div class="govuk-footer__section">
                    <h2 class="govuk-footer__heading govuk-heading-m">Useful TAP functions</h2>
                    <ul class="govuk-footer__list govuk-footer__list--columns-2">
                        <li class="govuk-footer__list-item">
                            <a class="govuk-footer__link" href="/regulations/create_edit.html">Create new regulation</a>
                        </li>
                        <li class="govuk-footer__list-item">
                            <a class="govuk-footer__link" href="/measures/create_edit.html">Create new measures</a>
                        </li>
                        <li class="govuk-footer__list-item">
                            <a class="govuk-footer__link" href="/quotas/create_edit.html">Create new quota</a>
                        </li>
                        <li class="govuk-footer__list-item">
                            <a class="govuk-footer__link" href="#3">Create new goods classification</a>
                        </li>
                        <li class="govuk-footer__list-item">
                            <a class="govuk-footer__link" href="/geographical_areas/create_edit.html">Create new geographical area</a>
                        </li>
                        <li class="govuk-footer__list-item">
                            <a class="govuk-footer__link" href="/footnotes/create_edit.html">Create new footnote</a>
                        </li>
                        <li class="govuk-footer__list-item">
                            <a class="govuk-footer__link" href="/certificates/create_edit.html">Create new certificate</a>
                        </li>
                        <li class="govuk-footer__list-item">
                            <a class="govuk-footer__link" href="/additional_codes/create_edit.html">Create new additional code</a>
                        </li>
                    </ul>
                </div>
                <div class="govuk-footer__section">
                    <h2 class="govuk-footer__heading govuk-heading-m">Other useful resources</h2>
                    <ul class="govuk-footer__list ">
                        <li class="govuk-footer__list-item">
                            <a class="govuk-footer__link" href="https://www.gov.uk/trade-tariff" target="_blank">
                                Trade Tariff Service
                            </a>
                        </li>
                        <li class="govuk-footer__list-item">
                            <a class="govuk-footer__link" href="#2" target="_blank">
                                Trade with the UK
                            </a>
                        </li>
                        <li class="govuk-footer__list-item">
                            <a class="govuk-footer__link" href="https://ec.europa.eu/taxation_customs/dds2/taric/taric_consultation.jsp?Lang=en" target="_blank">
                                EU Taric consultation site
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="govuk-footer__section-break">
            <div class="govuk-footer__meta">
                <div class="govuk-footer__meta-item govuk-footer__meta-item--grow">

                    <svg role="presentation" focusable="false" class="govuk-footer__licence-logo" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 483.2 195.7" height="17" width="41">
                        <path fill="currentColor" d="M421.5 142.8V.1l-50.7 32.3v161.1h112.4v-50.7zm-122.3-9.6A47.12 47.12 0 0 1 221 97.8c0-26 21.1-47.1 47.1-47.1 16.7 0 31.4 8.7 39.7 21.8l42.7-27.2A97.63 97.63 0 0 0 268.1 0c-36.5 0-68.3 20.1-85.1 49.7A98 98 0 0 0 97.8 0C43.9 0 0 43.9 0 97.8s43.9 97.8 97.8 97.8c36.5 0 68.3-20.1 85.1-49.7a97.76 97.76 0 0 0 149.6 25.4l19.4 22.2h3v-87.8h-80l24.3 27.5zM97.8 145c-26 0-47.1-21.1-47.1-47.1s21.1-47.1 47.1-47.1 47.2 21 47.2 47S123.8 145 97.8 145" />
                    </svg>
                    <span class="govuk-footer__licence-description">
                        All content is available under the
                        <a class="govuk-footer__link" href="https://www.nationalarchives.gov.uk/doc/open-government-licence/version/3/" rel="license">Open Government Licence v3.0</a>, except where otherwise stated
                    </span>
                </div>
                <div class="govuk-footer__meta-item">
                    <a class="govuk-footer__link govuk-footer__copyright-logo" href="https://www.nationalarchives.gov.uk/information-management/re-using-public-sector-information/uk-government-licensing-framework/crown-copyright/">© Crown copyright</a>
                </div>
            </div>
        </div>
    </footer>
    <!-- End footer //-->


    <script src="/js/govuk-frontend-3.4.0.min.js"></script>
    <script>
        window.GOVUKFrontend.initAll()
    </script>
</body>

</html>