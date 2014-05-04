<h1>Oh, bother.</h1>
<h2>We couldn't find any search results for "<?php the_search_query(); ?>". Perhaps try another search term:</h2>

<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
  <div>
    <input type="text" value="<?php the_search_query(); ?>" name="s" id="s" />
    <input type="submit" id="searchsubmit" value="Search" />
  </div>