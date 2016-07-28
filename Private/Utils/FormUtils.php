<?php   
    // =======================
    // = Edit Form Functions =
    // =======================
    /*
     * These convenience functions extend the FormUtils.php functions to build standard
     * forms for editing and Entity.
     */
    
	function printForm($method='post', $url=null, $class=null, $id='mainForm', $other=null, $applyDefaults = true) 
    {
        $isPOST = strtolower($method) == "post";
        
        $attribs = array("method" => $method, "accept-charset" => "utf-8", "name" => $id, "id" => $id);
        if ($url)
            $attribs["action"] = $url;
        if ($class)
            $attribs["class"] = $class;
        if ($isPOST)
            $attribs["enctype"] = "multipart/form-data";
        
        $compiled = BLArrayUtils::implode_assoc("\" ", $attribs, "=\"")."\"";
		
        print "<form $compiled";
        if ($other)
            print " $other";
        print ">\n";
        
		//Print hidden inputs
		if ($applyDefaults) 
        {
            $pageID = BL_DEFAULT_PAGE_ID;
            print "<input type=\"hidden\" name=\"page\" value=\"\" id=\"$pageID\" />\n";
            $actionID = BL_DEFAULT_ACTION_ID;
            print "<input type=\"hidden\" name=\"action\" value=\"\" id=\"$actionID\" />\n";
		}
	}

    function printSimpleSelectRow($title, $keypath, $values, $default = "", $labelClass = "", $class = "") 
    {
        print "<tr>\n<td class=\"$labelClass\">$title</td>\n";
        print "<td>";
        addSimpleSelect($keypath, $values, $default, $class);
        print "</td></tr>";
    }
    
    function printYesNoRow($title, $keypath, $default = "", $fieldClass = "", $class = "") 
    {
        printSimpleSelectRow($title, $keypath, array(1 => "Yes", 0 => "No"), $default, $fieldClass, $class);
    }
    
    /** Form data functions **/
    function printSubmitSelectRow($form, $title, $keypath, $values, $key, $val, $page, $action, $default=null) 
    {
        $selected = isset($default) ? $default : $form->formValueForKeyPath($keypath);
         print '  <tr>
                <td class="fieldLabel"></td>
                <td>
                        <select name="'.$keypath.'" onChange="document.getElementById("'.BL_DEFAULT_PAGE_ID.'").value='.doEncrypt($page).';document.getElementById("'.BL_DEFAULT_ACTION_ID.'").value='.doEncrypt($action).'; submit();">
                            <option selected>'.$title.'</option>';
         foreach($values as $value) {
                constructSelectOption($selected, $value->vars[$key], $value->vars[$val]);
         }
        print ' </select>	
                </td>
        </tr>';
    }
     
    //print table header contianing sort buttons
    function printSortableHeaders($controller, $headers, $page, $sortByKey = 'sortBy') 
    {
       $sortBy = $controller->formValueForKey($sortByKey);
       foreach ($headers as $header => $field) {

           //field is sortable, so make header a button
           print '<th';
           if ($field === $sortBy) {
               print ' class="selected" ';
               $sortOrder = ($controller->formValueForKey('sortOrder') == ORDER_ASCEND) ? ORDER_DESCEND : ORDER_ASCEND;
           } else {
               $sortOrder = ORDER_ASCEND;
           }

           print '>';
           if (!is_numeric($field)) {
               addSubmitButtonWithActions($header, array(BL_DEFAULT_ACTION_ID => "doNothing", BL_DEFAULT_PAGE_ID => $page, 'sortBy' => $field, 'sortOrder' => $sortOrder), 'header', null, "setSort('$field','$sortOrder')");
           } else {
               addSubmitButtonWithActions($header, array(BL_DEFAULT_ACTION_ID => "doNothing", BL_DEFAULT_PAGE_ID => $page, 'sortBy' => $field, 'sortOrder' => $sortOrder), 'header', null, "setSort('$field','$sortOrder')");
           }


           print '</th>';
       }
   }

   //Process submitted 'sort by' values
    function getSortBy($controller, $sortByKey, $sortOrderKey, $default) 
    {
       //try to read value from form input
       $sortByField = $controller->formValueForKey('sortBy');
       debugln('SORT BY : ' . $sortByField);
       //check if set AND for correct form (in case tab changed or other  erroneous value)
       if (empty($sortByField) || (array_search($sortByField, $controller->headers()) === FALSE)) {
           //if not set, return supplied defaults, and set for key to default
           $sortBy = $default;
           $default_keys = array_keys($default);
           $controller->setFormValueForKey($sortByKey, $default_keys[0]);
       } else {
           //if values passed in, format into array to pass to find()
           $sortOrder = $controller->formValueForKey($sortOrderKey);
           if (empty($sortOrder)) {
               $sortOrder = ORDER_ASCEND;
           }
           $sortBy = array($sortByField => $sortOrder);
       }

       ///return array
       return $sortBy;
   }
    
    /*  A generic entry form, based on a keypath values for an entity
        will just print table rows per input, so you can break up with more 
        complex fields when required.
    */
     function printEditForm($inputs, $controller, $defaultClasses = array()) 
     {
        foreach ($inputs as $title => $input) 
        {   
            $input_type = safeValue($input, "type");
            $keypath = safeValue($input, "keypath");
            $other = safeValue($input, "other");
            $labelClass = safeValue($input, "labelClass");
            if ($labelClass)
                $labelClass = " class=\"$labelClass\"";
            else
            {
                $labelClass = safeValue($defaultClasses, "labelClass");
                if ($labelClass)
                    $labelClass = " class=\"$labelClass\"";
            }
            
            $fieldClass = safeValue($input, "fieldClass");
            if ($fieldClass)
                $fieldClass = " class=\"$fieldClass\"";
            else
            {
            	$fieldClass = safeValue($input, 'class');
            	if (! $fieldClass)
                	$fieldClass = safeValue($defaultClasses, "fieldClass", "textEntry");
                if ($fieldClass)
                    $fieldClass = " class=\"$fieldClass\"";
            }
            
            $value = htmlspecialchars($controller->formValueForKeyPath($keypath), ENT_QUOTES);
            if($input_type == 'date')
            {
                $input_type = 'text';
                if($value) {
                    list($y, $m, $d) = preg_split('/[\/-]/', $value);
                    if($y > 31) {
                        $value = $d.'/'.$m.'/'.$y;
                    }
                }
            }
            
            // print the field label cell.
            print "<tr>\n";
            print "<td$labelClass>$title</td>\n";
            
            if ($input_type == "textarea")
            {                
                $placeholder = array_key_exists('placeholder', $input) ?  htmlspecialchars($input['placeholder'], ENT_QUOTES).'"' : "";
                
                print "<td><textarea name=\"$keypath\" id=\"$keypath\" placeholder=\"$placeholder\"$fieldClass>$value</textarea></td>\n";
            }
            else if ($input_type == "select")
            {
                $options = safeValue($input, "options");
                if (! is_array($options))
                {
                    trigger_error("no options provided for select field: $keypath");
                    return;
                }
                print "<td><select name=\"$keypath\" id=\"$keypath\" $other>\n";
                foreach($options as $label => $optionValue) 
                    constructSelectOption($value, $label, $optionValue);
                print "</select></td>";
            }
            else
            {
                $placeholder = array_key_exists('placeholder', $input) ? 'placeholder="'.  htmlspecialchars($input['placeholder'], ENT_QUOTES).'"' : '';
                $suppliedValue = safeValue($input, "value");
                $val = (strtolower($input_type) == "checkbox") ? $suppliedValue : $value;
                $checked = (strtolower($input_type) == "checkbox" && $suppliedValue == $value) ? " checked" : "";
                print "<td><input type=\"$input_type\" name=\"$keypath\" value=\"$val\" $placeholder $other id=\"$keypath\"$fieldClass$checked></td>";
            }
            
            print "</tr>";
            
            // check for a seperator option and if it exists create a proceeding row.
            $seperator = safeValue($input, "seperator");
            if ($seperator == "hr")
            {
                // add a divider row..
                $cellClass = safeValue($defaultClasses, "dividerClass");
                if ($cellClass)
                    $cellClass = " class=\"$cellClass\"";
                print "<tr><td></td><td$cellClass><hr></td></tr>\n";
            }
        }
    }
    
    //print the footer of a standard edit form - usually requires the same 3 buttons (delete, save, cancel)
     function printEditFormFooter($mPage, $backURL = null, $ajax = null, $function=null) {
        print '
        <tr>
                <td>';
        addSubmitButtonWithActions("Delete", array(BL_DEFAULT_PAGE_ID => $mPage, BL_DEFAULT_ACTION_ID => "delete"), 'editform-delete', null, "confirmDelete()");
        print ' </td>
                <td>';
        if(empty($backURL)) {
            addSubmitButtonWithActions("Back", array(BL_DEFAULT_PAGE_ID => $mPage, BL_DEFAULT_ACTION_ID => "cancel"), 'editform-back');
        } else {
            addLinkWithParams('Back', domainName().'/'.$backURL, array(), 'editform-back');
        }
        print '      &nbsp;';
        if($ajax) {
            addAjaxSubmitButton("Save", array(BL_DEFAULT_PAGE_ID => $mPage, BL_DEFAULT_ACTION_ID => "save"), 'editform-save', $function);
        } else {
            addSubmitButtonWithActions("Save", array(BL_DEFAULT_PAGE_ID => $mPage, BL_DEFAULT_ACTION_ID => "save"), 'editform-save', null, $function);
        }
        print '</td>
        </tr>	';
    }
    
    
       /** Data Upload **/
     function checkUpload($form, $varName, $limit=null, $index = null) 
     {
        //check file was uplaoided to expected var name
        if (!isset($_FILES[$varName]))
        {
            debugln("$varName NOT SET");
            return false;
        }
        $file = $_FILES[$varName];
        
        //check file uploaded ok
        $fileStatus = (is_array($file['error']) && isset($index))  ? $file["error"][$index] : $file["error"];
        $name = (is_array($file['name']) && isset($index))  ? $file["name"][$index] : $file['name'];
        
        if (debugLogging() > 0)
                debugln("$varName upload status of $name: $fileStatus");
        if ($fileStatus == UPLOAD_ERR_OK) {
            //Check File Size
            $src = (is_array($file['tmp_name']) && isset($index)) ?  $file['tmp_name'][$index] : $file["tmp_name"];
            if(!isset($limit)) $limit  = 5 * 1024 * 1024; // 5MB;
            if (filesize($src) > $limit)
            {
                $form->errorMessage = "The file $name you tried to attach exceeds the maximum file size allowed. Please keep your attachments below ".floor($limit/1024/1024)."MB in size.";
                return false;
            }
        } else if ($fileStatus != UPLOAD_ERR_NO_FILE) {
            if ($fileStatus == UPLOAD_ERR_INI_SIZE)
            {
                $form->errorMessage = "The file $name you tried to attach exceeds the maximum allowed file size. Please keep your attachments beloow ".floor($limit/1024/1024)."MB in size.";
            }
            else if ($fileStatus == UPLOAD_ERR_PARTIAL || $fileStatus ==  UPLOAD_ERR_FORM_SIZE)
            {
                $form->errorMessage = "The file $name you attached did not fully upload to the server. Please try again.";
            }
            else
            {
                $form->errorMessage = "Sorry, an error occured while trying to upload your image $name. <br>".join('<br>', $fileStatus)."<br>Please contact support for assistance.";
                //trigger_error("Error occured while uploading attachment '$name': $fileStatus\n\nUser: ".$this->user()->fullName());
            }
            return false;
        }
        
        //If no errors found, return true;
        return true;
    }
    
     function printPagedTableToolbar($controller, $page, $name, $displayGroup, $headers, $extras = null) {
        
        if ($name) {
            print '
        <div class="toolbar-name">
            ' . $name . '
        </div>';
        }
        if ($headers) {
            print '
                <div class="toolbar-sort">
                <span class="toolbar-sortby">
                        Sort Results By: 
                    </span>
                    <div class="toolbar-select-holder">
              <select name="sortBy" value="';
            print $controller->formValueForKey("sortBy") ? $controller->formValueForKey('sortBy') : 'n/a';
            print '" id="sortBy" >';

            $sortBy = $controller->formValueForKey("sortBy");

            foreach ($headers as $header => $field) {
                if (!is_numeric($field)) {
                    constructSelectOption($sortBy, $field, $header);
                }
            }

            print '    </select>
                </div>
           <span class="toolbar-order">Order</span>
            <div class="toolbar-select-holder">';
            addSimpleSelect('sortOrder', array(ORDER_ASCEND => 'Asc', ORDER_DESCEND => 'Desc'), $controller);
            print '</div>';
            addSubmitButtonWithActions("Go", array(BL_DEFAULT_ACTION_ID => "doNothing", BL_DEFAULT_PAGE_ID => $page), "small");
        print '</div>';
        }

        if ($displayGroup) {
            print '
                <div class="toolbar-page">
                    <span class="toolbar-per-page">
                    <span class="toolbar-label">Results per page:</span>';
                    $resPerPage = $controller->formValueForKey("resPerPage");
                    if (empty($resPerPage)) {
                        $resPerPage = 50;
                    }
                    $controller->setFormValueForKey('resPerPage', $resPerPage);
                    print '<div class="toolbar-select-holder">
                    <select name="resPerPage" value="' . $resPerPage . '" id="resPerPage" >';

                    constructSelectOption($resPerPage, 25, 25);
                    $i = 50;
                    while ($i <= 500) {
                        constructSelectOption($resPerPage, $i, $i);
                        $i += 50;
                    }
                    print ' </select>'
                    . '</div>';
                    addSubmitButtonWithActions("Go", array(BL_DEFAULT_ACTION_ID => "doNothing", BL_DEFAULT_PAGE_ID => $page), "small");

                print '</span>
                    <span class="toolbar-choose-page">
                 <input type="hidden" name="pageNo" value="'.$controller->formValueForKey('pageNo').'" id="pageNo">

                 &nbsp;&nbsp;';
                addSubmitButtonWithActions("&lt;&lt;", array(BL_DEFAULT_ACTION_ID => "previousBatch", BL_DEFAULT_PAGE_ID => $page), "small");
                print '<span class="toolbar-current-page toolbar-label">
                 Page ' . $displayGroup->currentBatch() . ' of ' . $displayGroup->batchCount() . '</span>';
                addSubmitButtonWithActions("&gt;&gt;", array(BL_DEFAULT_ACTION_ID => "nextBatch", BL_DEFAULT_PAGE_ID => $page), "small");

            print '</span></div>';
        }
        if (isset($extras)) {
            print $extras;
        }

    }    
    
    /**
    * Convert and Entity array to a select input array, with simple value=>display mapping
    * @param type $entities - list of entities
    * @param type $valField - value field (default is id)
    * @param type $textField - display text field (default is title)
    * @param type $topOption - (Optional) string for top row indicating no option selected
    * @return type - array of value=>display mappings, which can be passed to printSimpleSelect or printEditForm functions
    */
   function toSelectArray($entities, $valField='id', $textField='title', $topOption=null) 
   {
       $options = array();
       if($topOption) {
           $options[''] = $topOption;
       }
       foreach ($entities as $entity) {
           $options[$entity->vars[$valField]] = $entity->vars[$textField];
       }
       return $options;
   }
    
    
/********************************
 *                              *
 *       Image Functions        *
 *                              *
 ********************************/
 function addSingleImageUpload($entity, $tag) { {
     $input = lcfirst($tag);
  ?>
    <div id="editIcon" class="editSection">
        <div class='section-header'><?php echo $tag; ?></div>
        <div class='section-content'>
        
            <table id="imagesTable">
        <?php $path = $entity->getPath($tag); if (file_exists($path)) : ?>
                <tr class='curr-image'>
                    <td class='img-holder'>
                        <img src="<?php echo $path; ?>" class="editform" />
                    </td>	
                    <td class="submitColumn">
                        <?php
                        addSubmitButtonWithActions("Delete Image", array("action" => "delete$tag"), "red", null, "confirmDelete('$input')");
                        ?>
                    </td>
                </tr>	
            <?php else : ?>
                <p class='no-upload'>No <?php echo $tag; ?> Uploaded</p>
            <?php endif; ?>	
                <tr class='upload-row'>
                    <td>Upload New <?php echo $tag; ?> </td>
                </tr>
                <tr class='upload-row'>
                    <td><input type="file" name="<?php echo $input; ?>" value="" id="<?php echo $input; ?>" /></td>
                    <td>
                        <?php addSubmitButtonWithActions("Upload", array("action" => "upload".$tag), "purple"); ?>
                    </td>
                </tr>
            
            </table><!-- ImagesTable -->
       
        </div>
    </div><!-- EditImages -->
<?php } } ?>