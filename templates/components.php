<?php
  if( have_rows('components') ):
       // loop through the rows of data
    while ( have_rows('components') ) : the_row();
      get_template_part('templates/components/banner');
    endwhile;
  endif;

