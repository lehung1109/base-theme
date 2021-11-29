<?php
  // Navigation
  function custom_theme_navigation($menuclass, $name, $themelocaltion='') {
    wp_nav_menu(
    array(
      'theme_location'  => $themelocaltion,
      'menu'            => $name,
      'container'       => '',
      'container_class' => $menuclass,
      'container_id'    => '',
      'menu_class'      => $menuclass,
      'menu_id'         => '',
      'echo'            => true,
      'fallback_cb'     => 'wp_page_menu',
      'before'          => '',
      'after'           => '',
      'link_before'     => '',
      'link_after'      => '',
      'items_wrap'      => '<ul class="'.$menuclass.'">%3$s</ul>',
      'depth'           => 0,
      'walker'          => ''
      )
    );
  }


  // Register Navigation
  function custom_theme_register_menu() {
    register_nav_menus(array( // Using array to specify more menus if needed
      'header-menu' => __('Header Menu', 'custom_theme'), // Main Navigation
      'sidebar-menu' => __('Sidebar Menu', 'custom_theme'), // Sidebar Navigation
      'extra-menu' => __('Extra Menu', 'custom_theme') // Extra Navigation if needed (duplicate as many as you need!)
    ));
  }

  // Actions
  add_action('init', 'custom_theme_register_menu');

