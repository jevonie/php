<?php
    /*
     install the database
     This will be the database use in the plugin
     Created by tibong
    */
     
    global $wpdb;
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   
    $charset_collate = $wpdb->get_charset_collate();

     $sql = "CREATE TABLE wp_activated (
     activated_id int(11) NOT NULL AUTO_INCREMENT,
     generated_id int(11) NOT NULL,
     fields_id varchar(30) NOT NULL,
     status varchar(30) NOT NULL,
     field_position int(11) NOT NULL,
     PRIMARY KEY (activated_id)
    ) ENGINE=InnoDB AUTO_INCREMENT=534 DEFAULT CHARSET=utf8";

    dbDelta( $sql );

     $sql = "CREATE TABLE wp_generated (
     generated_id int(11) NOT NULL AUTO_INCREMENT,
     category_id int(11) NOT NULL,
     list_of_accounts_id int(11) NOT NULL,
     form_name varchar(30) NOT NULL,
     shortcode varchar(30) NOT NULL,
     widget varchar(30) NOT NULL,
     status	int(10) NOT NULL,
     PRIMARY KEY (generated_id)
    ) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8";

    dbDelta( $sql );

     $sql = " CREATE TABLE wp_list_of_accounts (
     list_of_account_id int(11) NOT NULL AUTO_INCREMENT,
     account_name varchar(30) NOT NULL,
     description varchar(30) NOT NULL,
     sync_mailchimp varchar(10) NOT NULL,
     mailchimp_code_name varchar(10) NOT NULL,
     PRIMARY KEY (list_of_account_id)
    ) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8";

    dbDelta( $sql );

     $sql = "CREATE TABLE wp_maximizer (
     id int(11) NOT NULL AUTO_INCREMENT,
     account_id int(11) NOT NULL,
     name varchar(30) NOT NULL,
     status int(10) NOT NULL,
     description varchar(30) NOT NULL,
     group_type int(10) NOT NULL,
     givenname varchar(30) NOT NULL,
     html varchar(100000) NOT NULL,
     PRIMARY KEY (id)
    ) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8";

    dbDelta( $sql );

    $sql = "CREATE TABLE wp_maximizerheader (
     id int(10) NOT NULL AUTO_INCREMENT,
     type varchar(30) NOT NULL,
     action varchar(5000) NOT NULL,
     PRIMARY KEY (id)
    ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8";

    dbDelta( $sql );

    $sql = "CREATE TABLE wp_maximizer_settings (
     action_url_company varchar(1000) NOT NULL,
     action_url_individuals varchar(1000) NOT NULL,
     account_id int(11) NOT NULL,
     PRIMARY KEY (account_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";

    dbDelta( $sql );

    $sql = "CREATE TABLE wp_maximizer_settings_fields (
     settings_fields_id int(11) NOT NULL AUTO_INCREMENT,
     fields_id int(11) NOT NULL,
     account_id int(11) NOT NULL,
     display_name varchar(30) NOT NULL,
     mandatory varchar(30) NOT NULL,
     PRIMARY KEY (settings_fields_id)
    ) ENGINE=InnoDB AUTO_INCREMENT=492 DEFAULT CHARSET=utf8";

    dbDelta( $sql );