<?php
/**
 * @package     mod_jt_contentfilter
 * @copyright   Copyright (C) 2007 - 2021 http://www.joomlatema.net, Inc. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @author     	JoomlaTema.Net
 * @link 		http://www.joomlatema.net
 **/

defined('_JEXEC') or die;
use Joomla\Module\JTContentFilter\Site\helper\jtcontentfilterhelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
if($params->get('show_sortandshuffle')== 1){
$shuffleControl="const shuffleControl".$module->id." = document.querySelector('.shuffle-btn');
    shuffleControl".$module->id.".addEventListener('click', function() {
      sortControls".$module->id.".forEach((control) => control.classList.remove('active'));
    });";
	}
	else {
	$shuffleControl='';
	}
	$slideColumn=$params->get('slideColumn');
	$slideColumnsm= $params->get('slideColumnsm');
	$slideColumnxs= $params->get('slideColumnxs');
	$ItemWidth=100/$slideColumn;
	$ItemWidthSm=100/$slideColumnsm;
	$ItemWidthXs=100/$slideColumnxs;
/////////////////	

?>
<style type="text/css">.jtcf<?php echo $module->id; ?> .filtr-item{ width:<?php echo $ItemWidth;?>%;padding:<?php echo $params->get('article_block_padding');?>;margin:<?php echo $params->get('article_block_margin');?>}
@media screen and (max-width: 767px) {
.jtcf<?php echo $module->id; ?> .filtr-item{ width:<?php echo $ItemWidthXs;?>%;}
.simplefilter li, .multifilter li, .sortandshuffle li {padding:0.5rem 0.7rem;}
}

@media screen and (min-width:768px) and (max-width:990px){
.jtcf<?php echo $module->id; ?> .filtr-item{ width:<?php echo $ItemWidthSm;?>%;}
.simplefilter li, .multifilter li, .sortandshuffle li {padding:0.7rem 0.9rem;}

}

</style>
<div class="jtcf_item_wrapper jt-cf" style="padding:<?php echo $params->get('content_padding');?>;">
<?php if( $params->get('show_pretext')==1 ): ?>
<div class="jt-pretext">
<span class="pretext_title"><?php echo $params->get('pretext_title');?></span>
<p class="pretext"><?php echo $params->get('pretext');?></p>
</div>
<?php endif; ?>
<div class="jtcf<?php echo $module->id; ?> slides-container">
<div class="row">
      </div>
 <?php 
	$n = 1;
	$morecatlinks = array();
	$categoryTitles = array(); // Initialize an array to store category titles

	foreach ($list as $i =>  $item) : 
 // More Category List and Blog
        $item->categlist = Route::_('index.php?option=com_content&view=category&id='.$item->catid);

        // Store unique category titles in the array
        if (!in_array($item->category_title, $categoryTitles)) {
            $categoryTitles[] = $item->category_title;
        }
		
			// More Category List and Blog
			$item->categlist = Route::_('index.php?option=com_content&view=category&id='.$item->catid);
			// Get the thumbnail 
			$thumb_img = jtcontentfilterhelper::getThumbnail($item->id, $item->images,$thumb_folder,$show_default_thumb,$thumb_width,$thumb_height,$item->title,$item->introtext,$modulebase);
			$org_img = jtcontentfilterhelper::getOrgImage($item->id, $item->images,$item->title,$item->introtext,$modulebase);
			$caption_text = jtcontentfilterhelper::getCaption($item->id, $item->images,$item->introtext,$use_caption);
			
      
 endforeach;
 // Now you can display the collected category titles
 if ($params->get('select_filter')=='category'){
    if (!empty($categoryTitles)) {
        echo '<ul class="simplefilter'.$module->id.' simplefilter '.$params->get('FilterBtnClass').'">';
		  echo '<li class="fltr-controls active" data-filter="all">All</li>';
        foreach ($categoryTitles as $categoryTitle) {
            echo '<li class="fltr-controls'.$module->id.'" data-filter="' . $categoryTitle . '">' . $categoryTitle . '</li>';
        }
        echo '</ul>';
    }
 }
 ?>
 <?php 
    if ($params->get('select_filter') == 'tags') {
        echo '<ul class="simplefilter' . $module->id . ' simplefilter ' . $params->get('FilterBtnClass') . '">';
        
        // Array to store tags that have been already displayed
        $displayedTags = [];
        
        foreach ($list as $i => $item) {
            $itemtags = jtcontentfilterhelper::getArticleTags($item->id);
            
            foreach ($itemtags as $tagtitle)  {
                // Check if the tag has already been displayed
                if (!in_array($tagtitle, $displayedTags)) {
                    echo '<li class="fltr-controls' . $module->id . '"  data-filter="' . $tagtitle . '">' . $tagtitle . '</li>';
                    
                    // Add the tag to the displayed tags array
                    $displayedTags[] = $tagtitle;
                }
            }
        }
        
        echo '</ul>';
    }
?>

       <?php if($params->get('show_sortandshuffle')== 1):?>
      <!-- Shuffle & Sort Controls -->
        <ul class="sortandshuffle <?php echo $params->get('SortShuffleBtnClass'); ?>">
          <!-- Basic shuffle control -->
          <li class="fltr-controls<?php echo $module->id; ?> shuffle-btn" data-shuffle>Shuffle</li>
          <!-- Basic sort controls consisting of asc/desc button and a select -->
          <li class="fltr-controls<?php echo $module->id; ?> sort-btn active" data-sortAsc>Asc</li>
          <li class="fltr-controls<?php echo $module->id; ?> sort-btn" data-sortDesc>Desc</li>
		    <select  style="display:none" class="fltr-controls" data-sortOrder>
            <option value="index">
              Position
            </option>
            <option value="sortData">
              Description
            </option>
          </select>
        </ul>
	<?php endif; ?>
	   <div class="filtr-container<?php echo $module->id; ?>">
<?php 
	$n = 1;
	$morecatlinks = array();
	foreach ($list as $i =>  $item) : 
		// Check whether thumbnail image is exist or not. If it's not then start thumbnail generation process
		
			// More Category List and Blog
			$item->categlist = Route::_('index.php?option=com_content&view=category&id='.$item->catid);
			// Get the thumbnail 
			$thumb_img = jtcontentfilterhelper::getThumbnail($item->id, $item->images,$thumb_folder,$show_default_thumb,$thumb_width,$thumb_height,$item->title,$item->introtext,$modulebase);
			$org_img = jtcontentfilterhelper::getOrgImage($item->id, $item->images,$item->title,$item->introtext,$modulebase);
			$caption_text = jtcontentfilterhelper::getCaption($item->id, $item->images,$item->introtext,$use_caption);
			$itemtags= jtcontentfilterhelper::getArticleTags($item->id);
			?>
			
<?php 
if ($params->get('select_filter') == 'tags') {
    echo '<div class="filtr-item" data-category="' . implode(',', $itemtags) . '">';
} else {
    echo '<div class="filtr-item" data-category="' . $item->category_title . '">';
}
?>
 
			  
			 <?php if( $params->get('show_thumbnail') ==1): ?>
				<div class="jt-imagecover" style="float:<?php echo $params->get('thumb_align');?>;margin<?php if($params->get('thumb_align')=="left") {echo "-right";} else if($params->get('thumb_align')=="right"){echo "-left";} else echo "-bottom";?>:<?php echo $params->get('thumb_margin');?>">
				<?php if($params->get('link_image')== 1):?>	
				<a class="link-image" title="<?php echo $item->title;?>" href="<?php echo $item->link;?>"><?php echo $thumb_img;?></a>
				<?php else :?>
				<?php echo $thumb_img;?>
				<?php endif; ?>
				<?php if($params->get('hover_icons')== 1):?>	
				<div class="hover-icons">
				<a class="jt-icon icon-url" title="<?php echo $item->title;?>" href="<?php echo $item->link;?>"> <i class="fa fa-link"></i></a>
			<a class="jt-icon icon-lightbox jt-image-link" href="<?php echo $org_img; ?>" data-lightbox="jt-1"><i class="fa fa-search"></i></a>
		</div><?php endif; ?>
		<?php if($params->get('use_caption')== 1 &&  !empty($caption_text) ):?><span class="jt-caption"><?php echo $caption_text;?></span><?php endif; ?>
		</div>
		<?php endif; ?>
				<?php if($params->get('show_category')== 1):?>	
				<?php if($params->get('show_category_link')== 1):?>	
				<span class="jt-category">
				<a href="<?php echo Route::_('index.php?option=com_content&view=category&id='.$item->catid);?>" class="cat-link"><?php echo $item->category_title;?></a>
				</span>
				<?php else :?>
				<span class="jt-category">
				<?php echo $item->category_title;?>
				</span>
				<?php endif; ?>
				<?php endif; ?>
		<?php if($params->get('show_title')== 1):?>	<h4 class="jtcf-title">
				<a class="jt-title" href="<?php echo $item->link; ?>" itemprop="url">
					<?php 
					//$item->title =  JHtmlString::truncate($item->title, $limit_title,false,true); 
					//$item->title= str_replace('...', $params->get('replacer',''), $item->title);
					/////////////////////////////
			if ($limit_title_by == 'word' && $limit_title > 0) {
                $item->title = jtcontentfilterhelper::substrword($item->title,$strip_tags, $limit_title, $replacertitle);
            } 
			else if ($limit_title_by == 'char' && $limit_title > 0) {
                $item->title = jtcontentfilterhelper::substring($item->title, $strip_tags,$limit_title, $replacertitle);
            }
				//////////////////////////////
					echo $item->title ?>
				</a></h4>
				<?php endif; ?>
	
				
				<?php 
				if($params->get('ShowContentTags')== 1){
				foreach ($itemtags as $itemtag){
				echo '<span class="content-tags">'.$itemtag.', </span>' ;
				}
				}?>
				<?php if(($params->get('show_date')== 1) || ($params->get('show_author')== 1)):?>	
				<div class="jt-author-date">
				<?php if($params->get('show_date')== 1):?>	
				<span class="jt-date"><i class="far fa-clock"></i>
				<?php
					//show date
					jtcontentfilterhelper::getDate($show_date, $show_date_type, $item->created, $custom_date_format);
				?>
				</span>
				<?php endif; ?>
				<?php if($params->get('show_author')== 1):?>	
				<span class="jt-author"><i class="far fa-user"></i>	
				<?php echo $item->author;?>
				<?php endif; ?>
				</span>
				<?php if($params->get('show_hits')== 1):?>	
				<span class="jt-hits"><i class="far fa-thumbs-up"></i> <?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $item->hits); ?></span>
				<?php endif; ?>
				</div><!--//jt-authot-date-->
				<?php endif; ?>
				<?php if($params->get('show_introtext')== 1):?>		
				<div class="jt-introtext">
					<?php 	
            if ($limit_intro_by == 'word' && $introtext_truncate > 0) {
                $item->introtext = jtcontentfilterhelper::substrword($item->introtext,$strip_tags, $introtext_truncate, $replacer_text);
            } 
			else if ($limit_intro_by == 'char' && $introtext_truncate > 0) {
                $item->introtext = jtcontentfilterhelper::substring($item->introtext, $strip_tags,$introtext_truncate, $replacer_text);
            }
			echo $item->introtext; ?>
					
				</div>
				<?php endif; ?>
				<?php if($params->get('showReadmore')== 1):?>
					<p class="readmore">
						<a class="btn btn-primary jt-readmore" target="<?php echo $openTarget; ?>"
							title="<?php echo $item->title;?>"
							href="<?php echo $item->link;?>"><i class="fas fa-file-alt" aria-hidden="true">&nbsp;</i> <?php echo $params->get('ReadMoreText');?>
						</a>
					</p>
					<?php endif; ?>
<div></div><div style="clear:both"></div></div>
		<?php 
		// More category links
		$morecatlink = "<a href=".Route::_('index.php?option=com_content&view=category&id='.$item->catid).">".$item->category_title."</a>";
		$morecatlinks[$morecatlink] = true;
		if (isset($morecatlinks[$morecatlink])) {
			continue;
		}
	endforeach; ?></div></div> 
	

<?php 
if($show_morecat_links) 
{	echo "<div class='jtcf_more_cat'>".$params->get('morein_text'). " ";
	foreach ($morecatlinks as $morecatlink => $val) 
	{
		echo $morecatlink. '&nbsp;&nbsp;';
	}
	echo "</div>";
}
?>
</div>

<script defer type="text/javascript">
 const simpleFilters<?php echo $module->id; ?> = document.querySelectorAll('.simplefilter<?php echo $module->id; ?> li');
    Array.from(simpleFilters<?php echo $module->id; ?>).forEach((node) =>
      node.addEventListener('click', function() {
        simpleFilters<?php echo $module->id; ?>.forEach((filter) => filter.classList.remove('active'));
        node.classList.add('active');
      })
    );

    const multiFilters<?php echo $module->id; ?> = document.querySelectorAll('.multifilter li');
    Array.from(multiFilters<?php echo $module->id; ?>).forEach((node) =>
      node.addEventListener('click', function() {
        node.classList.toggle('active');
      })
    );

    const sortControls<?php echo $module->id; ?> = document.querySelectorAll('.sort-btn');
    Array.from(sortControls<?php echo $module->id; ?>).forEach((node) =>
      node.addEventListener('click', function() {
        sortControls<?php echo $module->id; ?>.forEach((control) => control.classList.remove('active'));
        node.classList.add('active');
      })
    );
//shuffleControl
<?php echo $shuffleControl; ?>
  
    // Expose this filterizr as a global for debugging
    window.filterizr = new window.Filterizr('.filtr-container<?php echo $module->id; ?>', {
      controlsSelector<?php echo $module->id; ?>: '.fltr-controls<?php echo $module->id; ?>',
   gridItemsSelector: '.filtr-item',
  gutterPixels:<?php echo $params->get('gutterPixels',15); ?>,
  layout: '<?php echo $params->get('jtcf_style'); ?>', 
  delayMode: '<?php echo $params->get('delayMode'); ?>',
  animationDuration:<?php echo $params->get('animationDuration',0.5); ?>,
  delay:<?php echo $params->get('delay',25); ?>,
      spinner: {
        enabled: true,
      },
	      
filterOutCss: {
    opacity: 0,
    transform: 'scale(0.5)'
  },
  filterInCss: {
    opacity: 1,
    transform: 'scale(1)'
  },
    });
 
</script>

<script>
lightbox.option({
    fadeDuration:<?php echo $params->get('fadeDuration',300);?>,
    fitImagesInViewport:<?php echo $params->get('fitImagesInViewport',true);?>,
    imageFadeDuration: <?php echo $params->get('imageFadeDuration',300);?>,
    positionFromTop: <?php echo $params->get('positionFromTop');?>,
    resizeDuration: <?php echo $params->get('resizeDuration',150);?>,
	  })
</script>