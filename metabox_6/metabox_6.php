
<?php
/**
 * Plugin Name: Metabox_6
 * Plugin URI:  Plugin URL Link
 * Author:      Plugin Author Name
 * Author URI:  Plugin Author Link
 * Description: This plugin make for pratice wich is "Metabox_6".
 * Version:     0.1.0
 * License:     GPL-2.0+
 * License URL: http://www.gnu.org/licenses/gpl-2.0.txt
 * text-domain: mb_6
 */
// Textdomin loaded
function languages_filde_loaded(){
    load_plugin_textdomain('mb_6', false, dirname(__FILE__)."/languages");
}
add_action('plugins_loaded','languages_filde_loaded');

// Reginster metabox
function metabox_reginstration(){
    add_meta_box('MetaBox_6', __('Your InFo:','mb_6'), 'metabox_6_function', 'post');
}
function metabox_6_function($post){
    $colors = array('red','green','blue','yellow','megenta','pink','black');
    // wp_nonce_field('your_info', '$name', $referer, $echo)
    wp_nonce_field('your_info_action', 'your_info_name');
    $label_1 = __('Your Name','mb_6');
    $label_2 =__("Do You like it:","mb_6");
    $label_3 =__("Your favorit color:","mb_6");
    $value_1 = get_post_meta($post->ID, 'save_name_fild',true);
    $value_2 = get_post_meta($post->ID, 'save_checked_fild',true);
    $checked = $value_2  == 1 ? 'checked':'';
    $value_3 = get_post_meta($post->ID, 'save_checked_filds',true);
    // if (!is_array($value_3)) {
    //     $value_3 = array();
    // }
  //  print_r($value_3);
    // $checked_2 = $value_3 == 1 ? 'checked': '';
    // echo 'value'.$value_2.'<br>';
    //echo "Result: ".$checked;
     echo 'Data: '. $value_2;
    $meta_html = <<<EOD
        <div>
            <label for="Name">{$label_1}</label>
            <input value="{$value_1}" id="Name" name="Name" type="text"/>
            <br>
            <br>
            <label for="checkbox2">{$label_2}</label>
            <input id="checkbox2" type="checkbox" name='checkbox1' value='1' {$checked}/>
            <br>
            <br>
            <label>{$label_3}</label>

    EOD;


    foreach($colors as $color){
        $_color = ucwords($color);

        // $ch = in_array($color,$saved_color_)? 
        $checked_2 = in_array($color,$value_3)?'checked':'';
        $meta_html .=<<<EOD
        <label for='clr_{$_color}'>{$_color}</label>
        <input id='clr_{$_color}' type='checkbox' name='clr_name[]' value='{$_color}' {$checked_2}/>
        EOD;
    }
    $meta_html.= "</div>";
    echo $meta_html;
    // echo "Data:". $value_2;
}
add_action('admin_init','metabox_reginstration');

// save data in database
function save_data_in_dataBase($post_id){

    array_key_exists('Name',$_POST) ? update_post_meta($post_id, "save_name_fild", $_POST['Name']):'';
  //  array_key_exists('checkbox1',$_POST) ? update_post_meta($post_id,"save_checked_fild", $_POST['checkbox1']):1;
    if(array_key_exists('checkbox1',$_POST)){
        update_post_meta($post_id, 'save_checked_fild', 1);
    }else{
        update_post_meta($post_id, 'save_checked_fild', 0);
    }
    // save several checkbox 
    if(array_key_exists('clr_name',$_POST)){

        // $sanitized_colors = array_map('sanitize_text_field', $_POST['clr_name']);
        // update_post_meta($post_id, 'save_checked_filds', $sanitized_colors);
        update_post_meta($post_id, 'save_checked_filds', $_POST['clr_name']);
    }else{
        update_post_meta($post_id, 'save_checked_filds', array());
    }
    
    


    // if(array_key_exists('checkBox',$_POST))
    // update_post_meta($post_id, "save_checked_fild", $_POST['checkBox'])

    

//Nonce security
    if(!isset($_POST['your_info_name']) || !wp_verify_nonce($_POST['your_info_name'], 'your_info_action')){
        return;
    }
// parmmission to save
    if(!current_user_can('edit_post',$post_id)){
        return;
    };
// Auto save
if(defined('DOING_AUTOSAVE')&&DOING_AUTOSAVE){
    return;
}



}
add_action('save_post','save_data_in_dataBase');







?>