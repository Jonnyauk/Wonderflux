<?php

//// The Wonderflux action hooks available to theme developers ////

//////// IMPORTANT CORE HOOKS

function wf_head_meta() { do_action('wf_head_meta'); }
function wf_head_after_all_css() { do_action('wf_head_after_all_css'); }
function wf_footer() { do_action('wf_footer'); }

//////// Before and after everything

function wfbody_before_wrapper() { do_action('wfbody_before_wrapper'); }
function wfbody_after_wrapper() { do_action('wfbody_after_wrapper'); }

//////// Header hooks

function wfheader_before_wrapper() { do_action('wfheader_before_wrapper'); }
function wfheader_before_container() { do_action('wfheader_before_container'); }
function wfheader_before_content() { do_action('wfheader_before_content'); }
function wfheader_after_content() { do_action('wfheader_after_content'); }
function wfheader_after_container() { do_action('wfheader_after_container'); }
function wfheader_after_wrapper() { do_action('wfheader_after_wrapper'); }

//////// Footer hooks

function wffooter_before_wrapper() { do_action('wffooter_before_wrapper'); }
function wffooter_before_container() { do_action('wffooter_before_container'); }
function wffooter_before_content() { do_action('wffooter_before_content'); }
function wffooter_after_content() { do_action('wffooter_after_content'); }
function wffooter_after_container() { do_action('wffooter_after_container'); }
function wffooter_after_wrapper() { do_action('wffooter_after_wrapper'); }

//////// Sidebar hooks

function wfsidebar_before_all() { do_action('wfsidebar_before_all'); }
function wfsidebar_after_all() { do_action('wfsidebar_after_all'); }

//////// Loop hooks

function wfloop_before() { do_action('wfloop_before'); }
function wfloop_after() { do_action('wfloop_after'); }

//////// Main content area hooks - before and after everything

function wfmain_before_wrapper() { do_action('wfmain_before_wrapper'); }
function wfmain_after_wrapper() { do_action('wfmain_after_wrapper'); }

//////// Main content area hooks - before and after everything

function wfmain_before_all_container() { do_action('wfmain_before_all_container'); }
function wfmain_after_all_container() { do_action('wfmain_after_all_container'); }
function wfmain_before_all_content() { do_action('wfmain_before_all_content'); }
function wfmain_after_all_content() { do_action('wfmain_after_all_content'); }

//////// After content and sidebars (inside main content div)
// There is also a location specific one of these for each below
// NOTE: If you want before the main content and remain inside the main content div, use wfmain_before_all_content() or wfmain_before_LOCATION_content()
function wfmain_after_all_main_content() { do_action('wfmain_after_all_last_content'); }

//////// LOCATION SPECIFIC MAIN CONTENT AREA HOOKS ////////

// Index
function wfsidebar_before_index() { do_action('wfsidebar_before_index'); }
function wfsidebar_after_index() { do_action('wfsidebar_after_index'); }
function wfmain_before_index_container() { do_action('wfmain_before_index_container'); }
function wfmain_before_index_content() { do_action('wfmain_before_index_content'); }
function wfmain_after_index_content() { do_action('wfmain_after_index_content'); }
function wfmain_after_index_main_content() { do_action('wfmain_after_index_last_content'); }
function wfmain_after_index_container() { do_action('wfmain_after_index_container'); }

// Home
function wfsidebar_before_home() { do_action('wfsidebar_before_home'); }
function wfsidebar_after_home() { do_action('wfsidebar_after_home'); }
function wfmain_before_home_container() { do_action('wfmain_before_home_container'); }
function wfmain_before_home_content() { do_action('wfmain_before_home_content'); }
function wfmain_after_home_content() { do_action('wfmain_after_home_content'); }
function wfmain_after_home_main_content() { do_action('wfmain_after_home_last_content'); }
function wfmain_after_home_container() { do_action('wfmain_after_home_container'); }

// Page
function wfsidebar_before_page() { do_action('wfsidebar_before_page'); }
function wfsidebar_after_page() { do_action('wfsidebar_after_page'); }
function wfmain_before_page_container() { do_action('wfmain_before_page_container'); }
function wfmain_before_page_content() { do_action('wfmain_before_page_content'); }
function wfmain_after_page_content() { do_action('wfmain_after_page_content'); }
function wfmain_after_page_main_content() { do_action('wfmain_after_page_last_content'); }
function wfmain_after_page_container() { do_action('wfmain_after_page_container'); }

// Single
function wfsidebar_before_single() { do_action('wfsidebar_before_single'); }
function wfsidebar_after_single() { do_action('wfsidebar_after_single'); }
function wfmain_before_single_container() { do_action('wfmain_before_single_container'); }
function wfmain_before_single_content() { do_action('wfmain_before_single_content'); }
function wfmain_after_single_content() { do_action('wfmain_after_single_content'); }
function wfmain_after_single_main_content() { do_action('wfmain_after_single_last_content'); }
function wfmain_after_single_container() { do_action('wfmain_after_single_container'); }

// Category
function wfsidebar_before_category() { do_action('wfsidebar_before_category'); }
function wfsidebar_after_category() { do_action('wfsidebar_after_category'); }
function wfmain_before_category_container() { do_action('wfmain_before_category_container'); }
function wfmain_before_category_content() { do_action('wfmain_before_category_content'); }
function wfmain_after_category_content() { do_action('wfmain_after_category_content'); }
function wfmain_after_category_main_content() { do_action('wfmain_after_category_last_content'); }
function wfmain_after_category_container() { do_action('wfmain_after_category_container'); }

// Date
function wfsidebar_before_date() { do_action('wfsidebar_before_date'); }
function wfsidebar_after_date() { do_action('wfsidebar_after_date'); }
function wfmain_before_date_container() { do_action('wfmain_before_date_container'); }
function wfmain_before_date_content() { do_action('wfmain_before_date_content'); }
function wfmain_after_date_content() { do_action('wfmain_after_date_content'); }
function wfmain_after_date_main_content() { do_action('wfmain_after_date_last_content'); }
function wfmain_after_date_container() { do_action('wfmain_after_date_container'); }

// Author
function wfsidebar_before_author() { do_action('wfsidebar_before_author'); }
function wfsidebar_after_author() { do_action('wfsidebar_after_author'); }
function wfmain_before_author_container() { do_action('wfmain_before_author_container'); }
function wfmain_before_author_content() { do_action('wfmain_before_author_content'); }
function wfmain_after_author_content() { do_action('wfmain_after_author_content'); }
function wfmain_after_author_main_content() { do_action('wfmain_after_author_last_content'); }
function wfmain_after_author_container() { do_action('wfmain_after_author_container'); }

// Tag
function wfsidebar_before_tag() { do_action('wfsidebar_before_tag'); }
function wfsidebar_after_tag() { do_action('wfsidebar_after_tag'); }
function wfmain_before_tag_container() { do_action('wfmain_before_tag_container'); }
function wfmain_before_tag_content() { do_action('wfmain_before_tag_content'); }
function wfmain_after_tag_content() { do_action('wfmain_after_tag_content'); }
function wfmain_after_tag_main_content() { do_action('wfmain_after_tag_last_content'); }
function wfmain_after_tag_container() { do_action('wfmain_after_tag_container'); }

// Taxonomy
function wfsidebar_before_taxonomy() { do_action('wfsidebar_before_taxonomy'); }
function wfsidebar_after_taxonomy() { do_action('wfsidebar_after_taxonomy'); }
function wfmain_before_taxonomy_container() { do_action('wfmain_before_taxonomy_container'); }
function wfmain_before_taxonomy_content() { do_action('wfmain_before_taxonomy_content'); }
function wfmain_after_taxonomy_content() { do_action('wfmain_after_taxonomy_content'); }
function wfmain_after_taxonomy_main_content() { do_action('wfmain_after_taxonomy_last_content'); }
function wfmain_after_taxonomy_container() { do_action('wfmain_after_taxonomy_container'); }

// Archive
function wfsidebar_before_archive() { do_action('wfsidebar_before_archive'); }
function wfsidebar_after_archive() { do_action('wfsidebar_after_archive'); }
function wfmain_before_archive_container() { do_action('wfmain_before_archive_container'); }
function wfmain_before_archive_content() { do_action('wfmain_before_archive_content'); }
function wfmain_after_archive_content() { do_action('wfmain_after_archive_content'); }
function wfmain_after_archive_main_content() { do_action('wfmain_after_archive_last_content'); }
function wfmain_after_archive_container() { do_action('wfmain_after_archive_container'); }

// Search
function wfsidebar_before_search() { do_action('wfsidebar_before_search'); }
function wfsidebar_after_search() { do_action('wfsidebar_after_search'); }
function wfmain_before_search_container() { do_action('wfmain_before_search_container'); }
function wfmain_before_search_content() { do_action('wfmain_before_search_content'); }
function wfmain_after_search_content() { do_action('wfmain_after_search_content'); }
function wfmain_after_search_main_content() { do_action('wfmain_after_search_last_content'); }
function wfmain_after_search_container() { do_action('wfmain_after_search_container'); }

// Attachment
function wfsidebar_before_attachment() { do_action('wfsidebar_before_attachment'); }
function wfsidebar_after_attachment() { do_action('wfsidebar_after_attachment'); }
function wfmain_before_attachment_container() { do_action('wfmain_before_attachment_container'); }
function wfmain_before_attachment_content() { do_action('wfmain_before_attachment_content'); }
function wfmain_after_attachment_content() { do_action('wfmain_after_attachment_content'); }
function wfmain_after_attachment_main_content() { do_action('wfmain_after_attachment_last_content'); }
function wfmain_after_attachment_container() { do_action('wfmain_after_attachment_container'); }

// 404
function wfsidebar_before_404() { do_action('wfsidebar_before_404'); }
function wfsidebar_after_404() { do_action('wfsidebar_after_404'); }
function wfmain_before_404_container() { do_action('wfmain_before_404_container'); }
function wfmain_before_404_content() { do_action('wfmain_before_404_content'); }
function wfmain_after_404_content() { do_action('wfmain_after_404_content'); }
function wfmain_after_404_main_content() { do_action('wfmain_after_404_last_content'); }
function wfmain_after_404_container() { do_action('wfmain_after_404_container'); }

?>