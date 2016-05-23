<?php
if (post_password_required()) {
  return;
}
?>

<section id="comments" class="comments">
  <?php comment_form(); ?>
</section>
