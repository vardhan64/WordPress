<?php

$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
$query_args = (array(
    'post_type' => array('wordpress'),
    'post_status' => 'publish', 
    'orderby' => 'date', 
    'order' => 'DESC',
    'posts_per_page' => 8,
    'paged'=> $paged,
    )
);

$wp_query = new WP_Query($query_args);

if ( $wp_query->have_posts() ) {
    while ($wp_query->have_posts()){
        $wp_query->the_post();
        
        $wp_postType = get_post_type();
        $wp_postId = get_the_ID();
        $wp_title = get_the_title(); 
        $wp_post_date = get_the_date('F Y', $wp_postId);
        $wp_content = wp_trim_words( get_the_content(), 32, '...' );
        
        echo "<p>" .$wp_title. "</p>";
    }
    
    $total_pages = $wp_query->max_num_pages;       
    if ($total_pages > 1){
        $current_page = max(1, get_query_var('paged'));            
        $paginate_links = paginate_links(array(
            'type' => 'array',
            'base' => get_pagenum_link(1) . '%_%',
            'format' => 'page/%#%',
            'current' => $current_page,
            'total' => $total_pages,
            'show_all' => true,
            'prev_next' => false,
            )
        );
        
        $prev = '<span class="next-prev-btn previous-page"> Prev </span>';
        if($current_page > 1){
            $prev = '<a class="next-prev-btn previous-page" href="'.get_pagenum_link($current_page - 1).'"> Prev </a>';
        }
        
        $next = '<span class="next-prev-btn next-page"> Next </span>';
        if($current_page != $total_pages){
            $next = '<a class="next-prev-btn next-page" href="'.get_pagenum_link($current_page + 1).'"> Next </a>';
        }
        
        $pagination_link = $paginate_links;
        if($total_pages >= 5){
            if(($current_page+5) > $total_pages){
                $pagination_link = array_slice($paginate_links, ($total_pages - 5), 5);
            } else {
                $pagination_link = array_slice($paginate_links, ($current_page - 1), 5);
            }
        }
        
        echo '<div class="wordpress-pagenation">';
        for( $i = 0; $i < count($pagination_link); $i++){
            if($i == 0) echo $prev;
            if($i < 5) echo $pagination_link[$i];
            if($i == count($pagination_link) - 1) echo $next;
        }
        echo '</div>';
    }
}

?>