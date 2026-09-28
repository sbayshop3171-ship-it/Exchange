<?php
header("Content-Type:text/css");
$color = "#f0f"; // Change your Color Here

function checkhexcolor($color)
{
    return preg_match('/^#[a-f0-9]{6}$/i', $color);
}

$color = '';

if (isset($_GET['color']) and $_GET['color'] != '') {
    $color = "#" . $_GET['color'];
}

if (!$color or !checkhexcolor($color)) {
    $color = "#336699";
}
?>

.scrollToTop,
input[type="submit"], .custom-pagination li a.active, .common-pagination span, ::selection , .header-top, .header-bottom .header-bottom-area .menu-area .menu li .sub-menu li:hover > a, .header-bar span, .exchange-form .form-group input[type="submit"], .exchange-form .form-group .rates, .section-bg.get-service .exchange-form .form-group input[type="submit"],.blog-sidebar .widget.widget-search form .form-group button, .feature-item::after, .how-item.active .serial, .how-item:hover .serial, .newsletter-form button, .get-service .exchange-form .form-group input[type="submit"], .account-form .form-group input[type="submit"], .privacy-content .item .bullet-list li::before, .contact-form .form-group input[type="submit"], .contact--item::before, .contact--item::after, .process-tab .tab-menu li.active , .exchange-from-to-form .form-group input[type="submit"], .affiliate-item .affiliate-thumb, .theme-button, .post-item .post-content::before, .post-item .post-content::after, .post-item.post-details .post-content .thumb-area ul li::before, .post-item blockquote::before, .blog-pagination li a.active, .blog-pagination li a:hover, .comment-wrapper li .comment-item:hover .reply-button , .comment-form-group button, .custom-button, .custom-button.white:hover, .swiper-slide-next .custom-button:hover, .swiper-slide-next .custom-button.white , .transaction-table .t-header, .widget.widget-search .search--form button, .widget.widget-tags ul li a:hover, .bg_theme, .faq-item.open .faq-title, .list::-webkit-scrollbar-track,.copied::after,.btn--base,.bg--base,.form--check .form-check-input:checked,.form--control[type="file"]::-webkit-file-upload-button,.widget-item__icon,.custom--table thead,.page-item.active .page-link,.page-link:focus,.pagination .page-item .page-link.active, .pagination .page-item .page-link:hover,.btn--base-outline:hover,.contact-item__icon,.form__title::after,.date::before, .dropdown-list>.dropdown-list__item:hover, .sub-menu li a.active, .how-item .how-thumb,.currency-wrapper__header{
background:<?php echo $color; ?> !important;
}
.custom-pagination li a, .custom-pagination li a.active, .exchange-form .form-group input:focus, .blog-sidebar .widget.widget-search form .form-group button, .blog-sidebar .widget.widget-tags ul li a:hover, .client-item .client-content blockquote, .account-form .form-group input:focus, .contact-form .form-group input:focus, .contact-form .form-group textarea:focus, .exchange-from-to-form .form-group input[type="submit"], .custom-button:hover, .widget.widget-tags ul li a:hover,.btn--base-outline,.form--check .form-check-input,.form--check .form-check-input:checked, .border--base,.page-item.active .page-link, .form--control:focus, .select2-container--default .select2-search--dropdown .select2-search__field:focus, .select2-container--open .select2-selection.select2-selection--single, .select2-container--open .select2-selection.select2-selection--multiple, .form-control:focus {
border-color:<?php echo $color; ?> !important;
}

.feature-thumb i, .section-header .cate, .section-header .title span, .custom-pagination li a:hover, .footer-bottom p a, .footer-area .footer-widget.widget-link ul li a:before, .footer-area .footer-widget.widget-link ul li a:hover, .blog-sidebar .widget.widget-archive ul li a:hover, .blog-sidebar .widget.widget-category ul li a:hover, .blog-sidebar .widget.widget-post ul li a:hover .subtitle, .blog-sidebar .widget.widget-tags ul li a:hover, .feature-item.active .feature-content .title, .feature-item:hover .feature-content .title, .how-item.active .title, .counter-item .counter-header .title, .amount, .breadcrumb li:hover, .breadcrumb li a:hover, .breadcrumb li, .currency-converter p a, .account-form .form-group label a, .process-tab .tab-menu li .thumb i, .currency-rate span, .confirmation-group .confirmation-content .con-header p .currency-cl, .confirmation-group .confirmation-content .transaction-id li .trans-id, .confirmation-group .confirmation-content .content a:hover, .responsive-table strong, .dashboard-item .dashboard-content .amount, .post-item .post-content .meta-post a i, .post-item .post-content .meta-post a:hover, .post-item.post-details .post-content .tag-options .tags a:hover, .post-item.post-details .post-content .tag-options .share a:hover, .post-item:hover .post-content .blog-header .title a, .comment-wrapper li .comment-item:hover .sub-title a, .custom-button.white, .swiper-slide-next .custom-button, .widget.widget-archive ul li a::before, .widget.widget-category ul li a::before, .widget.widget-archive ul li a:hover, .widget.widget-category ul li a:hover, .widget.widget-banner a:hover , .widget.widget-post ul li .content .sub-title a:hover, .widget.widget-post ul li .content .meta a, .text--base,.btn--base-outline, .header-bottom .header-bottom-area .preloader .wellcome span,how-item .how-thumb,.menu-area .menu .menu-item .menu-item__link.active, .exchange-form__icon {
color:<?php echo $color; ?> !important;
}

.btn--base:hover, .input-group .input-group-text.mobile-code {
background-color: <?php echo $color; ?>bf !important;
}

.preloader .wellcome span, .widget-item__amount, .page-link {
color: <?php echo $color; ?>;
}

.service-item .service-thumb {
color: <?php echo $color ?>22;
}
.how-item:hover::before {
color: <?php echo $color ?>90;
}
.widget-item::before{
background-color:<?php echo $color ?>24;
}
.widget-item:hover::before {
background-color: <?php echo $color ?>57;
}

.payment-item:has(.payment-item__radio:checked) .payment-item__check {
    border: 3px solid <?php echo $color ?> !important;
}

.payment-item__check {
    border: 1px solid <?php echo $color ?> !important;
}

.payment-item:has(.payment-item__radio:checked){
    border-left: 3px solid <?php echo $color ?>;
}


<!-- 1396F3 -->
