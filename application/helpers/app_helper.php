<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function print_message($str){
    $type = explode('|', $str);
    if($type[0] == 'success')
        return '<div class="alert-box success">'.$type[1].'</div>';
    elseif($type[0] == 'error')
    	return '<div class="alert-box alert">'.$type[1].'</div>';
    else
    	return '';
}

function print_message_admin($str){
	$type = explode('|', $str);
	if($type[0] == 'success')
		return '<div class="msg_box msg_ok">'.$type[1].'</div>';
	elseif($type[0] == 'error')
	return '<div class="msg_box msg_error">'.$type[1].'</div>';
	else
		return '';
}

/**
 * Convert Array Object to Array Assosiative to Make an dropdown
 *
 * @param array
 * @param text
 * @param text
 * @return array
 */
function make_array($data=array(),$primary_key='id',$text='nama', $first_option = ' Pilih Salah Satu: '){
	$return_array[''] = $first_option;
	foreach($data as $d){
		$return_array[$d->{$primary_key}] = ucfirst($d->{$text});
	}
	return $return_array;
}

/**
 * Function to generate menus dropdown
 **/
function generate_menus($menus, $name='menus'){
	//return '<option value="1">Hello World</option>';
	$r = '';
	foreach($menus as $m){
		$r .= '<option value="'.$m['id'].'">'.$m['title_en'].'</option>';
		//generate child
		if($m['child'])
			$r .= generate_menus($m['child']);
	}
	return $r;
}

function generate_menus_table($menus){
	$ci =& get_instance();
	$theme_url = $ci->template->get_theme_path();
	//return '<tr><td colspan="6">Data not found</td></tr>';
	$r = '';
	foreach($menus as $m){
		$r .= '<tr>';
		$r .= '<td><input type="checkbox" name="row_sel" class="inpt_c1"></td>';
		$r .= '<td>'.$m['title_en'].'</td>';
		if($m['parent']){
			$r .= '<td>'.$m['parent'].'</td>';
		}else{
			$r .= '<td>-</td>';
		}
		$r .= '<td>'.$m['type'].'</td>';
		$r .= '<td>'.$m['target'].'</td>';
		$r .= '<td align="center">'.anchor(site_url('admin/menus/edit/'.$m['id']), img($theme_url.'images/icons/pencil_gray.png'), 'style="margin-right: 5px"').anchor(site_url('admin/menus/delete/'.$m['id']), img($theme_url.'images/icons/trashcan_gray.png')).'</td>';	
       $r .= '</tr>';
       //generate child
		if($m['child'])
			$r .= generate_menus_table($m['child']);

	}
    return $r;
}

/**
 * Function to display image
 *
 * @author	andriansandi
 * @since 	July 22, 2012
 **/
function display_image($width, $height, $image,$a='c'){
	$tim_path = site_url('timthumb/timthumb.php');
	$image_path = site_url('files/'.$image);

	//display avatar
	if($image != ''){
		return img($tim_path.'?w='.$width.'&h='.$height.'&a='.$a.'&src='.$image_path);
	}else{
		$ci =& get_instance();
		if($type == 'user')
			return img($tim_path.'?w='.$width.'&h='.$height.'&a='.$a.'&src='.$ci->template->get_theme_path().'images/user_nopig.png');
		else
			return img($tim_path.'?w='.$width.'&h='.$height.'&a='.$a.'&src='.$ci->template->get_theme_path().'images/nopic.jpg');
	}
}

function PMT($i, $n, $p) {
 	return $i * $p * pow((1 + $i), $n) / (1 - pow((1 + $i), $n));
}

function breadcumbs($breadcumbs){
	//if breadcumbs is NULL or not SET
	if(!$breadcumbs) 
		return FALSE;
	
	$count = count($breadcumbs);
	$return = '<ul class="breadcrumbs">';
	$return .= '<li>'.anchor(base_url(), 'Home').'</li>';
	$i = 1;
	foreach($breadcumbs as $b){
		if(($i == $count && $count > 1) || $count == 1)
			$class = 'class="unavailable"';
		else 
			$class = '';
		//return
		$return .= '<li '.$class.'>'.anchor($b['url'], $b['title']).'</li>';
		$i++;
	}
	
	$return .= '</ul>';
	
	return $return;
}

function print_human_date($date,$timestamp=FALSE)
{
	
	if($timestamp === TRUE)
	{
		$dt = explode(' ',$date);
		$dt2 = explode('-',$dt[0]);
		return $dt2[2].'-'.$dt2[1].'-'.$dt2[0].' '.$dt[1];
	}else{
		$dt = explode('-',$date);
		return $dt[2].'-'.$dt[1].'-'.$dt[0];
	}
}
