
<div class="<?php //echo $col_grid_class?>input-group <?php echo $attributes['type']?>">

    <?php echo (isset($label) && $label) ? $label : ""?>
    <?php
    if(isset($attributes['inline-edit']) && $attributes['inline-edit']){
    	
    	include "fields/inline-edit.php";
    	
    }elseif(isset($attributes['type']) && $attributes['type'] == 'view'){
        //$comment = (isset($attributes['comment'])) ? $attributes['comment'] : false;
        if(isset($form_data[$element])){
            $attributes['value'] = isset($form_data[$element]) ? stripslashes($form_data[$element]) : "";
        }

        if(array_key_exists("value", $attributes) && $attributes['value'] != ''){
        	if(array_key_exists('options', $attributes) && array_key_exists($attributes['value'], $attributes['options'])){
        		echo '<div class="view">'.clean_display($attributes['options'][$attributes['value']]).'</div>';
        	}elseif(array_key_exists('image', $attributes)){

                $base_path = base_url();
                if(array_key_exists('base_path', $attributes)){
                    $base_path = $attributes['base_path'];
                }
        		echo '<div class=""><img style="max-width:150px; max-height:150px;" src="'.$base_path.clean_display($attributes['value']).'" /></div>';
        	}else{
	        	echo '<div class="view">'.stripslashes($attributes['value']).'</div>';
        	}	
        }
    }elseif(isset($attributes['type']) && $attributes['type'] == 'inline-html'){
        echo (isset($attributes['html'])) ? '<div class="">'.$attributes['html'].'</div>' : '';
    
    }elseif(isset($attributes['type']) && $attributes['type'] == 'rating'){
        
        include "fields/rating.php";

    }elseif(isset($attributes['type']) && $attributes['type'] == 'file'){
        if(isset($form_data[$element])){
            $attributes['value'] = isset($form_data[$element]) ? clean_display($form_data[$element]) : "";
        }
        if(array_key_exists('ajax', $attributes) && $attributes['ajax'] === true){
            if(array_key_exists('multiple', $attributes) && $attributes['multiple'] === true){
                include "fields/file_upload_multiple_ajax.php";
            }else{
                include "fields/file_upload_ajax.php";
            }    
        }else{
            if(array_key_exists('multiple', $attributes) && $attributes['multiple'] === true){
                echo "Multiple files upload supported with ajax only. Use attribute ajax=true in the field declaration.";
            }else{
                include "fields/file.php";
            }    
        }    


    }elseif(isset($attributes['type']) && $attributes['type'] == 'tags'){
        if(isset($form_data[$element])){
            $attributes['value'] = isset($form_data[$element]) ? clean_display($form_data[$element]) : "";
        }
        include "fields/tags.php";    
    
    }elseif(isset($attributes['type']) && $attributes['type'] == 'date'){
        
        if(isset($form_data[$element])){
            if(!isset($attributes['value']) || $attributes['value'] == ''){
                $attributes['value'] = isset($form_data[$element]) ? (($attributes['type'] != 'multiselect') ? clean_display($form_data[$element]) : $form_data[$element]) : "";
            }
        }
        
        include "fields/date.php";
        
    }else{
        {//form elements
            $e = "form_".element_types($attributes['type']);
                
            //important for repopulation
            if(!isset($attributes['value'])){
                $attributes['value'] = '';
            } 
            
            //if edit than populate value
            $e_name = $element;

            if(stristr($element, '[]')){
                $e_name = str_replace("[]", "", $element);
            }

            if(isset($form_data[$e_name])){
            //if(array_key_exists($element, $form_data)){
            	if(!isset($attributes['value']) || $attributes['value'] == ''){
            		//if($form_data[$element]){
            			if($attributes['type'] == 'textarea' || $attributes['type'] == 'texteditor'){
            				$attributes['value'] = (($form_data[$e_name]));
            			}elseif($attributes['type'] == 'multiselect' || $attributes['type'] == 'checkbox'){
                            
            				$attributes['value'] = $form_data[$e_name];
                        
                        }elseif($attributes['type'] == 'text'){

                            $attributes['value'] = utf8_decode($form_data[$e_name]);
            			}else{
            				$attributes['value'] = clean_display($form_data[$e_name]);
            			}
            		//}
            	}
            }
            
            //if element is disabled than do not repopulate the value
            if(!isset($attributes['disabled']) && isset($attributes['value'])){
                $attributes['value'] = (($attributes['type'] != 'multiselect' && $attributes['type'] != 'checkbox') ? (set_value($element, $attributes['value'])) : set_value($element, $attributes['value']));
            }
        }
        
        $comment = (isset($attributes['comment'])) ? $attributes['comment'] : false;
        
        $is_required = false;
        if(isset($attributes['validation']) && $attributes['validation'] != ''){
            $is_required = true;
        }
		
        //remove unused properties from form attributes
        unset($attributes['default_value']);
        unset($attributes['row_class']);
        unset($attributes['comment']);
        unset($attributes['label']);
        unset($attributes['form_type']);
        unset($attributes['validation']);
        unset($attributes['validate_unique']);
                
        //set name and id attribute for element
        $attributes['name'] = $element;
        $attributes['id'] = $element_id;
        $attributes['class'] = (isset($attributes['class'])) ? $attributes['class'].' form-control' : 'form-control';
                
        if($attributes['type']=="texteditor"){
            $text_editors[] = $attributes['name'];
            $attributes['class'] = "mceEditor";
            $attributes['cols'] = (isset($attributes['cols'])) ? $attributes['cols'] : 80;
                            
            //overwrite class for simple theme
            if(isset($attributes['theme']) && $attributes['theme']=='simple'){
                $attributes['class'] = 'mceSimpleEditor';
            }
        }	
            
        if($attributes['value'] == '0000-00-00' || $attributes['value'] == '0000-00-00 00:00:00'){
            $attributes['value'] = '';
        }

        //check if date format
        // if(!is_array($attributes['value']) && isset($attributes['value']) && (!isset($attributes['calendar']))){
        //     $date_format = date_parse($attributes['value']);
            
        //     if($date_format['year'] && $date_format['month'] && $date_format['day'] && $date_format['error_count'] == 0 && $date_format['warning_count'] == 0){
        //         //its date format
        //     if($date_format['hour'] && $date_format['minute'] && $date_format['second']){
        //             $attributes['value'] = date('d M Y, g:iA', strtotime($attributes['value']));
        //         }else{
        //             $attributes['value'] = date('d M Y', strtotime($attributes['value']));
        //         }
        //     }
        //     $attributes['value'];
        // }				
        
        if(isset($attributes['calendar'])){
        	if(isset($attributes['value']) &&  $attributes['value'] != ''){
	        	$attributes['value'] = date('d-m-Y', strtotime($attributes['value']));
        	}	
        	unset($attributes['calendar']);
        }
        
        $addon_item = false;
        
		//create element
        switch($attributes['type']){
        
            case("select");

                if((count($attributes['options']) > 5) || (isset($attributes['dropdown_field']) && $attributes['dropdown_field']) || (isset($attributes['force_select']))){
                    $attr = 'id='.$attributes['name']." class=".$attributes['class'];
                    $attr .= (isset($attributes['disabled'])) ? " disabled='disabled'" : "";
                    $attr .= (isset($attributes['readonly'])) ? " readonly='readonly'" : "";
                    $attr .= ' data-field="select"';
                    echo $e($attributes['name'], $attributes['options'], (isset($attributes['value']) ? $attributes['value'] : ''), $attr);
                }else{
                    
                    include "fields/radio.php";						
                }	
            break;
            
            case("multiselect");
                $attr = 'id="'.$attributes['id'].'" class="form-control '.((($is_required) ? "required-entry" : '')).'"';
                $attr .= (isset($attributes['disabled'])) ? " disabled='disabled'" : "";
                $attr .= (isset($attributes['readonly'])) ? " readonly='readonly'" : "";
                $attr .= (isset($attributes['size'])) ? " size='".$attributes['size']."'" : "";
                $attr .= ' data-field="multiselect"';
                echo $e($attributes['name'], $attributes['options'], (isset($attributes['value']) ? $attributes['value'] : ''), $attr);
            break;
            
            case("radio"):
                if(isset($attributes['options']) && is_array($attributes['options'])){
                    $radio_options = $attributes['options'];
                    unset($attributes['options']);
                    foreach($radio_options as $option_label => $option_value){
                    
                        $attributes['id'] = $attributes['id'] . "_" .$option_value;
                        $attributes['value'] = $option_value;
                        $attributes['data-field'] = 'radio';
                                            
                        echo "<label for='".$attributes['id']."'>{$option_label}</label>";
                        echo $e($attributes);
                    }
                }else{
                    $attributes['data-field'] = 'radio';
                    echo $e($attributes);
                }
            break;
            
            case("checkbox"):

                if(isset($attributes['options']) && is_array($attributes['options'])){
                    $attributes['name'] = $attributes['name']."[]";
                    $radio_options = $attributes['options'];
                    unset($attributes['options']);

                    $values = $attributes['value'];

                    foreach($radio_options as $option_value => $option_label){
                    
                        $attributes['id'] = $attributes['id'] . "_" .$option_value;
                        $attributes['value'] = $option_value;
                        $attributes['data-field'] = 'checkbox';

                        $checked = (is_array($values) && in_array($option_value, $values)) ? true : false;
                        
                        echo "<div class='input-box'>";

                            echo $e($attributes, $option_value, $checked);
                            echo "<label for='".$attributes['id']."'>{$option_label}</label>";
                        echo "</div>";
                    }
                }else{
                    $attributes['data-field'] = 'checkbox';
                    echo $e($attributes);
                }
            break;
            
            case("textarea"):
                $otions = $attributes;
                if(isset($attributes['cols'])){
                    $otions['cols'] = $attributes['cols'];
                }
                if(isset($attributes['rows'])){
                    $otions['rows'] = $attributes['rows'];
                }
                echo "<textarea ".((array_key_exists("rows", $attributes)) ? "rows='".$attributes['rows']."'" : '')." ".((array_key_exists("name", $attributes)) ? "name='".$attributes['name']."'" : '')." ".((array_key_exists("cols", $attributes)) ? "cols='".$attributes['cols']."'" : '')." ".((array_key_exists("id", $attributes)) ? "id='".$attributes['id']."'" : '')." ".((array_key_exists("class", $attributes)) ? "class='".$attributes['class']."'" : '')." data-field='textarea'>".((array_key_exists("value", $attributes)) ? clean_display($attributes['value']) : '')."</textarea>";
            break;
			
			case("texteditor"):
                $otions = $attributes;
                if(isset($attributes['cols'])){
                    $otions['cols'] = $attributes['cols'];
                }
                if(isset($attributes['rows'])){
                    $otions['rows'] = $attributes['rows'];
                }

                echo "<textarea ".((array_key_exists("rows", $attributes)) ? "rows='".$attributes['rows']."'" : '')." ".((array_key_exists("name", $attributes)) ? "name='".$attributes['name']."'" : '')." ".((array_key_exists("cols", $attributes)) ? "cols='".$attributes['cols']."'" : '')." ".((array_key_exists("id", $attributes)) ? "id='".$attributes['id']."'" : '')." ".((array_key_exists("class", $attributes)) ? "class='".$attributes['class']."'" : '')." data-field='texteditor'>".((array_key_exists("value", $attributes)) ? stripslashes(stripslashes($attributes['value'])) : '')."</textarea>";
            break;
            
            case("yesno"):
                $options = array(""=>"Select Yes/No", 0=>"Yes", 1=>"No");
                $attr = 'id='.$attributes['name'];
                $attr .= (isset($attributes['disabled'])) ? " disabled='disabled'" : "";
                $attr .= (isset($attributes['readonly'])) ? " readonly='readonly'" : "";
                $attr .= " data-field='select'";
                echo $e($attributes['name'], $options, (isset($attributes['value']) ? $attributes['value'] : ''), $attr);
            break;
            
            case("button"):
                $options = array(""=>"Select Yes/No", 0=>"Yes", 1=>"No");
                $attr = 'id='.$attributes['name'];
                $attr .= (isset($attributes['disabled'])) ? " disabled='disabled'" : "";
                $attr .= (isset($attributes['readonly'])) ? " readonly='readonly'" : "";
                echo $e($attributes['name'], $options, (isset($attributes['value']) ? $attributes['value'] : ''), $attr);
            break;
            
            case("categories"):
                $ci = ci();
                
                $selected = (isset($attributes['parent']) ? $attributes['parent'] : false);
                $exclude = (isset($attributes['current_category']) ? $attributes['current_category'] : false);
                $load_from = (isset($attributes['load_from']) ? $attributes['load_from'] : 0);
                echo $ci->category_model->category_dropdown($selected, $exclude, $load_from);
            break;
            
            case("image"):
                if(array_key_exists('ajax', $attributes)){
            
                    include "fields/image_upload_ajax.php";
                
                }else{
                    $ci = ci();
                    $attr = 'id='.$attributes['name'];
                    $attr .= (isset($attributes['disabled'])) ? " disabled='disabled'" : "";
                    $attr .= (isset($attributes['readonly'])) ? " readonly='readonly'" : "";
                    if(isset($attributes['value']) && $attributes['value'] != ''){
                        if(isset($attributes['image_path']) && $attributes['image_path'] != ''){
                            $path = ltrim(rtrim($attributes['image_path'], '/'), '/');
                            $path = base_url().$path;
                            echo "<img src='".$path.$attributes['value']."' width='100' height='100' style='float:left; margin-right:10px;' />&nbsp;&nbsp;&nbsp;&nbsp;";
                        }
                    }
                    echo form_upload($attributes['name']);
                    if(isset($attributes['value']) && $attributes['value'] != ''){
                        echo "<label>Delete Image</label>&nbsp;&nbsp;<input type='checkbox' name='delete_".$attributes['name']."' value='1' />";
                        echo "<input type='hidden' name='url_".$attributes['name']."' value='".$attributes['value']."' />";
                    }
                }	
            break;

            case("gallery"):
                if(array_key_exists('ajax', $attributes)){
            
                    include "fields/gallery_upload_ajax.php";
                
                }else{
                    //To do
                }	
            break;
            
            // case("file"):
            //     if(array_key_exists('ajax', $attributes)){
                
            //         include "fields/gallery_upload_ajax.php";
                    
            //     }else{
            //         //To do
            //     }	
            //     include "fields/file.php";
            //     $ci = ci();
            //     $attr = 'id='.$attributes['name'];
            //     $attr .= (isset($attributes['disabled'])) ? " disabled='disabled'" : "";
            //     $attr .= (isset($attributes['readonly'])) ? " readonly='readonly'" : "";
            //     if(isset($attributes['value']) && $attributes['value'] != ''){
            //         echo "<b style='float:left; margin-right:10px;'>".$attributes['value']."</b>&nbsp;&nbsp;&nbsp;&nbsp;";
            //     }
            //     if(isset($attributes['value']) && $attributes['value'] == ''){
            //         unset($attributes['value']);
            //     }
            //     echo "<input type='file' name='".$attributes['name']."' id='".$attributes['id']."' accept='image/jpg,image/png,image/jpeg,image/gif' />";
            //     echo "<script type='text/javascript'>";
            //         echo "";
            //     echo "</script>";
            //     //echo form_upload($attributes['name']);
            //     if(isset($attributes['value']) && $attributes['value'] != ''){
            //         echo "<label>Delete file</label>&nbsp;&nbsp;<input type='checkbox' name='delete_".$attributes['name']."' value='1' />";
            //         echo "<input type='hidden' name='url_".$attributes['name']."' value='".$attributes['value']."' />";
            //     }
            // break;
            
            default:
                //Input:text
                $addon_item = true;
            
                if(isset($attributes['array'])){//create array element
                    $attributes['name'] = $attributes['name']."[]";
                    unset($attributes['array']);
                }
                if(array_key_exists("read_only", $attributes)){
                    $editable_readonly = ($attributes['read_only'] === 'editable') ? ' editable' : '';
                    if(array_key_exists("class", $attributes)){
                        $attributes['class'] = $attributes['class'] . ' readonly'.$editable_readonly;
                    }else{
                        $attributes['class'] = 'readonly'.$editable_readonly;
                    }
                }
                
                $disabled = (array_key_exists("disabled", $attributes) && $attributes["disabled"] === 'disabled') ? true : false;
                if(array_key_exists("disabled", $attributes) && ($attributes['disabled'] === 'editable')){
                    if(isset($attributes['value']) && $attributes['value'] != ''){
                        $disabled = true;
                        if(array_key_exists("class", $attributes)){
                            $attributes['class'] = $attributes['class'] . ' disabled editable';
                        }else{
                            $attributes['class'] = 'disabled editable';
                        }
                    }else{
                        $disabled = false;
                    } 
                }

                $value = '';

                if(isset($form_data) && is_array($form_data)){
                    $value = array_key_exists('name', $attributes) ? (stripslashes($form_data[$attributes['name']])) : '';
                }else{
					 if(isset($attributes['type']) && $attributes['type'] == 'text' && isset($attributes['value']) && $attributes['value'] != ''){
					 	$value = $attributes['value'];
					 }
				} 
			

                echo "<input ".((array_key_exists("value", $attributes)) ? "value='".stripslashes(stripslashes(htmlentities($value, ENT_QUOTES)))."'" : '')." ".((array_key_exists("name", $attributes)) ? "name='".$attributes['name']."'" : '')." ".((array_key_exists("type", $attributes)) ? "type='".$attributes['type']."'" : '')." ".((array_key_exists("id", $attributes)) ? "id='".$attributes['id']."'" : '')." ".((array_key_exists("class", $attributes)) ? "class='".$attributes['class']."'" : '')." ".(($disabled) ? "disabled='disabled'" : '')." ".((array_key_exists("read_only", $attributes)) ? "readonly='readonly'" : '')." data-field='text' />";
                
                if(array_key_exists("read_only", $attributes) && ($attributes['read_only'] === 'editable')){
                    echo '<div class="btn-group pull-lg-right form-control-buttons">';
                        echo "<button type='button' class='btn btn-xs btn-warning' onclick='edit_read_only(this, \"".$attributes['id']."\")'>Edit</button>";
                    echo '</div>';
                }

                if(array_key_exists("disabled", $attributes) && ($attributes['disabled'] === 'editable')){
                    if(isset($attributes['value']) && $attributes['value'] != ''){
                        echo '<div class="btn-group pull-lg-right form-control-buttons">';
                            echo "<button type='button' class='btn btn-xs btn-warning' onclick='edit_disabled(this, \"".$attributes['id']."\")'>Edit</button>";
                        echo '</div>';
                    } 
                }
            break;
        }
        
        if($addon_item && $attributes['type'] != 'texteditor'){
            if(isset($attributes['validation']) && strstr($attributes['validation'], "email")){
                echo '<div class="input-group-addon"><i class="ico">email</i></div>';
            }else{
                //echo '<div class="input-group-addon"><i class="ico">format_color_text</i></div>';	
            }
        }	
        echo (isset($comment) && $comment != '') ? '<span class="text-muted">'.$comment.'</span>' : '';			
        //element error
        echo form_error($element, '<div class="help-block help-block-pop">', '</div>');
        ?>
    <?php }?>
</div>