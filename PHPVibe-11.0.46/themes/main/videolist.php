<?php the_sidebar();
      //Add sorter
      if(isset($sortop) && $sortop) {
          /* Most liked , Most viewed time sorting */
          $st = '
<div class="inline-block pull-right relative">
       <a data-toggle="dropdown" class="btn btn-default btn-outline dropdown-toogle"> <i class="icon icon-calendar"></i> <i class="icon icon-angle-down"></i> </a>
			<ul class="dropdown-menu dropdown-menu-right bullet">
			<li title="'._lang("This Week").'"><a href="'.list_url(token()).'?sort=w"><i class="icon icon-circle-thin"></i>'._lang("This Week").'</a></li>
			<li title="'._lang("This Month").'"><a href="'.list_url(token()).'?sort=m"><i class="icon icon-circle-thin"></i>'._lang("This Month").'</a></li>
			<li title="'._lang("This Year").'"><a href="'.list_url(token()).'?sort=y"><i class="icon icon-circle-thin"></i>'._lang("This Year").'</a></li>
			<li class="drop-footer" title="'._lang("This Week").'"><a href="'.list_url(token()).'"><i class="icon icon-circle-thin"></i>'._lang("All").'</a></li>
		</ul>
		</div>
';
      }

?>
<div class="row main-holder">


    <div id="videolist-content">
        <?php echo _ad('0','video-list-top');
              include_once(TPL.'/video-loop.php');
              echo _ad('0','video-list-bottom');
        ?>



    </div>
</div>
