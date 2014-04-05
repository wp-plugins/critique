<?php
/*
Plugin Name: Critique
Plugin URI: http://fatfolderdesign.com/critique/
Description: Critique is a simple review platform with the power to do what you need.
Version: 1.0.0 - Ashworth Ave
Author: Phillip Gooch
Author URI: mailto:phillip.gooch@gmail.com
License: Undecided
*/

class critique {

	public $settings = array();
	public $scales = array(
		'5-stars' => array(
			'min' => 0,
			'max' => 5,
			'step' => 0.5,
		),
		'out-of-100' => array(
			'min' => 0,
			'max' => 100,
			'step' => 1,
		),
	);
	public $more_link = '';
	public $more_link_length = -1;

	public function __construct(){
		// Load the settings
		$settings=get_option('critique_settings','[]');
		$settings=json_decode($settings,true);
		$settings=array_merge(array(
			// These are the defaults
			'post_types' => array(
				'post' => 'on',
				'page' => 'off',
			),
			'scale' => '10-stars',
			'sections' => '',
			'add_average' => 'off',
			'add_to_blog' => 'on',
			'add_to_feed' => 'on',
			'show_options' => array(
				'full_in_short' => 'off',
				'overall_in_short' => 'on',
			),
		),(array)$settings);
		$this->settings = $settings;
		// Add the settings menu item
		add_action('admin_menu',array($this,'add_menu_item'));
		// Load up all the admin scripts and styles
		add_action('admin_enqueue_scripts',array($this,'admin_enqueued'));
		// Load up all the regular scripts and styles
		add_action('wp_enqueue_scripts',array($this,'enqueued'));
		// Add the meta box (determins if it's the appropriate section and all that in the function)
		add_action('add_meta_boxes',array($this,'init_metabox'));
		// This action will save the critique metabox data (IE the score)
		add_action('save_post',array($this,'save_metabox'));
		// These filters are used to add the critique data to the post 
		add_filter('the_content',array($this,'add_to_post'));
		add_filter('the_content_more_link',array($this,'post_more_link'),9001);
	}

	public function admin_enqueued(){
		// Load the admin scripts and styles where needed
		// JS
		wp_enqueue_script('critique_admin_js',plugin_dir_url( __FILE__ ).'/admin.js',array('jquery'));
		// CSS
		wp_enqueue_style('critique_admin_css',plugin_dir_url( __FILE__ ).'/admin.css');
		wp_enqueue_style('critique_admin_css');
		wp_enqueue_style('critique_admin_font_css',plugin_dir_url( __FILE__ ).'/fontello/css/critique.css');
		wp_enqueue_style('critique_admin_font_css');
	}
	public function enqueued(){
		// Load the regular scripts and styles where needed
		// CSS
		wp_enqueue_style('critique_font_css',plugin_dir_url( __FILE__ ).'/fontello/css/critique.css');
		wp_enqueue_style('critique_font_css');
		wp_enqueue_style('critique_css',plugin_dir_url( __FILE__ ).'/critique.css');
		wp_enqueue_style('critique_css');
	}

	public function add_menu_item(){
		// This will add the settings menu item
		add_menu_page('Critique Settings','Critique Settings','manage_options','critique-settings',array($this,'settings_page'),'dashicons-awards',66);
	}
	public function settings_page(){
		// Save the settings if sending post data
		if(isset($_POST['critique_action'])&&$_POST['critique_action']=='critique_update'){
			echo '<div id="setting-error-settings_updated" class="updated settings-error"><p><strong>Settings saved.</strong></p></div>';
			unset($_POST['critique_action']);
			$this->save_settings($_POST);
		}
		// Load Settings Page, kept externally for brevity's sake
		require_once('settings.php');
	}

	public function init_metabox(){
		// add in the meta boxes for the chosen sections
		foreach($this->settings['post_types'] as $type => $status){
			if($status=='on'){
				add_meta_box('critique-metabox','Critique Review Score',array($this,'build_metabox'),$type,'side');
			}
		}
	}
	public function build_metabox($post){
		// Builds the contents of the critique meta box
		// Since we may temporarily overwrite settings, lets move them out of the class
		$settings = $this->settings;
		// Load the saved scorebox (if there is one)
		$saved_scorebox = get_post_meta($post->ID,'critique_score',true);
		$saved_scorebox = json_decode($saved_scorebox,true);
		// Look at the saves scorebox, if there is one, and has a scale, override the settings for this metabox to use the old scale (to prevent a good 5 star become a mediocre 10 star)
		if(is_array($saved_scorebox)&&isset($saved_scorebox['scale'])&&$saved_scorebox['scale']!=''){
			$settings['scale'] = $saved_scorebox['scale'];
		}
		// Determine what sections we are going to add
		$sections = explode(',',$settings['sections']);
		$overal_average_added = false;
		if(count($sections)>1 && $settings['add_average']=='on'){
			$overal_average_added = true;
			$sections[] = 'Overall';
			$avg_score = 0;
			$section_count = 0;
			if(is_array($saved_scorebox['review'])){
				foreach($saved_scorebox['review'] as $n => $score){
					if(trim($score)!=''){
						$avg_score += $score;
						$section_count++;
					}
				}
				if($section_count>0){
					if($this->scales[$settings['scale']]['step']==0.5){
						$saved_scorebox['review']['Overall'] = round(($avg_score/$section_count)*2)/2;

					}else{
						$saved_scorebox['review']['Overall'] = round($avg_score/$section_count);
					}
				}
			}
		}
		// Display the metabox markup
		echo '<input type="hidden" name="critique_action" value="critique_scorebox_save" />';
		echo '<table class="critique-review-inputs">';
			foreach($sections as $n => $section){
				echo '<tr'.($overal_average_added&&$n+1==count($sections)?' class="overall-average-line"':'').'>';
					// If there is a section title, display it, otherwise it's an uneeded element
					if(trim($section)!=''){
						echo '<th>'.$section.'</th>';
					}
					// Determine which input to display in the ratebox
					echo '<td class="critique-metabox-scale type-'.$settings['scale'].'">';
						switch($settings['scale']){
							case '5-stars':
								echo '<div class="critique-admin-star-container">';
									for($star=1;$star<=$this->scales[$settings['scale']]['max'];$star++){
										$class_mod = '';
										if($saved_scorebox['review'][$section]-$star>=0){
											$class_mod = 'full';
										}else if($saved_scorebox['review'][$section]-$star>=-0.5){
											$class_mod = 'half';
										}
										echo '<i class="star icon-star '.$class_mod.'"></i>';
									}
								echo '</div>';
							break;
							case 'out-of-100':
								echo '<div class="critique-admin-of100-bar"><div style="width:'.($saved_scorebox['review'][$section]==''?0:$saved_scorebox['review'][$section]).'%;"></div></div>';
							break;
							default:
								echo 'Opps, I can\'t find the right form for a "'.$settings['scale'].'" review scale.';
							break;
						}
					echo '</td>';
					echo '<td class="critique-admin-raw-value">';
						// If it's set to have an overall and it is on the last one (and meeds the other overall reqs) then do something a bit different (overall is not saved, always derived)
						if($overal_average_added && $n+1==count($sections)){
							echo '<input type="number" min="0" max="'.$this->scales[$settings['scale']]['max'].'" step="'.$this->scales[$settings['scale']]['step'].'" class="critique-overall-average" disabled value="'.$saved_scorebox['review'][$section].'"/></td>';
						}else{
							echo '<input type="number" min="0" max="'.$this->scales[$settings['scale']]['max'].'" step="'.$this->scales[$settings['scale']]['step'].'" name="critique_scorebox[review]['.$section.']" value="'.$saved_scorebox['review'][$section].'"/></td>';
						}
				echo '</tr>';
			}
		echo '</table>';
		echo '<input type="hidden" name="critique_scorebox[scale]" value="'.$settings['scale'].'" />';
	}

	public function post_more_link($link){
		// Grab some info on the more link, we use that to determine if the post has it (since I didn't see a better way to determine loading short or full posts).
		// Wordpress wraps this in a P tag and adds a line break, so we need to be ready for that.
		$this->more_link = '<p>'.$link.'</p>'."\n";
		$this->more_link_length = strlen('<p>'.$link.'</p>'."\n");
		return $link;
	}
	public function add_to_post($post){
		// This adds the display block to the post
		// First, load the data (and skip everything else if it's not there) and add the overall average is settings dictate
    	$critique = json_decode(get_post_meta(get_the_ID(),'critique_score',true),true);
			// Determine the overall if enabled and add if it is
 		if($this->settings['add_average']=='on'&&count($critique['review'])>1){
 			$total_score = 0;
 			foreach($critique['review'] as $section => $score){
 				if(trim($score=='')){
					unset($critique['review'][$section]);	 					
 				}
 				$total_score += (int)$score;
 			}
 			$critique['review']['Overall'] = $total_score/count($critique['review']);
			if($this->scales[$critique['scale']]['step']==0.5){
				$critique['review']['Overall'] = round($critique['review']['Overall']*2)/2;
			}else{
				$critique['review']['Overall'] = round($critique['review']['Overall']);
			}
 		}
 		if(isset($critique['scale'])){
 			// Star the output buffer
 			ob_start();
			// First thing we need to do is determine if this is a short or a long post, were going to assume long and check for short.
			$display_block = true;
			$post_type = 'long';
			if(!is_single()){
				$post_type = 'short';
				$display_block = false;
			}
			// If it's a short post pull we need to do some additional checking to determine what we display
			if($post_type=='short'){
				if($this->settings['show_options']['full_in_short']=='on'){
					// We don't have to do anything
					$display_block = true;
				}else if($this->settings['show_options']['overall_in_short']=='on'){
					// This is not an else if because if more options come up there probably each me there own thing, not all or nothing.
					$display_block = true;
	 				if($this->settings['add_average']=='on'&&count($critique['review'])>1&&$this->settings['show_options']['overall_in_short']=='on'){
	 					$section = 0;
	 					$sections = count($critique['review']);
	 					foreach($critique['review'] as $k => $v){
	 						$section++;
	 						if($section!=$sections){
	 							unset($critique['review'][$k]);
	 						}
	 					}
					}
				}
			}
			// Display the block
			if($display_block){
				?>
				<div class="critique-display-wrapper <?= $post_type ?> type-<?= $critique['scale'] ?>">
					<ul id="critique-display">
						<?php foreach($critique['review'] as $scale => $value){ ?>
							<li class="critique-row">
								<span class="critique-title"><?= trim($scale) ?></span>
								<span class="critique-scale">
									<?php switch($critique['scale']){
										case '5-stars':
											for($star=1;$star<=$this->scales[$critique['scale']]['max'];$star++){
												$class_mod = '';
												if($critique['review'][$scale]-$star>=0){
													$class_mod = 'full';
												}else if($critique['review'][$scale]-$star>=-0.5){
													$class_mod = 'half';
												}
												echo '<i class="star icon-star '.$class_mod.'"></i>';
											}
										break;
										case 'out-of-100':
											echo '<i class="out-of-100-bar"><b style="width:'.($critique['review'][$scale]).'%;"></b></i>';
										break;
										default:
											echo 'No scale display code found for "'.$critique['scale'].'" but it is a '.$critique['review'][$scale].'.';
										break;
									} ?>
								</span>
								<span class="critique-numeric"><?= $value ?><i>/<?= $this->scales[$critique['scale']]['max'] ?></i></span>
							</li>
						<?php } ?>
					</ul>
				</div>
				<?php
				/* Debug Stuff 
				echo '<pre>'.print_r($critique,true).'</pre>';
				echo '<pre>'.print_r($this->settings,true).'</pre>';
				var_dump($post,$this->more_link);
				/* End Debug */
				// Get block and add it where needed.
				$critique_block = ob_get_clean();
				// Wrap it in P tags, wordpress like to do that.
				$critique_block = '<p>'.$critique_block.'</p>';
				if($post_type=='long'){
					$post .= $critique_block;
				}else{ // $post_type == short
					$post = substr_replace($post,$critique_block,($this->more_link_length*-1),0);
				}
			}
 		}
 		return $post;
	}

	public function save_metabox($post_id){
		// Save the critique metabox (if data is sent)
		if(isset($_POST['critique_action'])&&$_POST['critique_action']!=''){
			update_post_meta($post_id,'critique_score',json_encode($_POST['critique_scorebox']));
		}
	}
	public function save_settings($save_options){
		// This will take the existing settings, merge the update with them, then update both wordpress and the $this->settings var
		$options=$this->settings;
		$options=array_merge($options,$save_options);
		$options=json_encode($options);
		update_option('critique_settings',$options);
		$this->settings = json_decode($options,true);
	}
}
$critique = new critique();