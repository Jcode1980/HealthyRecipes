<?php
	require_once BLOGIC."/BLogic.php";
	require_once ROOT."/Components/Controllers/PageWrapperAdmin.php";


	class EditViewWrapper extends PageWrapperAdmin  
	{
	    protected $prevPage = 'index.php';
	    protected $id;

	   public function __construct($formData, $innerTemplate = "EditView") 
	   {
	        parent::__construct($formData, $innerTemplate);
		
	        global $bl_url_args;
	        $this->id = safeValue($bl_url_args, 0);
		
	        addCSS('css/editform.css');
	    }
   
	    /** Form Submit Functions **/
	    public function cancel() 
		{
	        $this->goToLocation($this->prevPage);
	    }

    
	    public function delete() 
		{
			if ($this->hasInvalidTransactionID()) {
				$this->errorMessage = "The previous action can not be re-performed from reloading the page.";
				return;
			}
	        $this->currentEntity()->vars['deleted'] = mysql_date();
			try {
				$this->currentEntity()->save();
			}
			catch (Exception $error) {
				debugln($error->getMessage());
				$this->errorMessgae = "There was a problem attempting to delete this item. Please try again.";
			}
            $list = $this->pageWithName($this->prevPage);
	        $list->alertMessage = "Product deleted.";
	        $list->goToLocation();
	    }

    
	    /**
	     * Save joined/related Entities based on input values arrays
	     * @param type $currEntities - Current list of joined values (e.g. arrayValueForRelationship...)
	     * @param type $key - Form Key Prefix for Input (e.g. Category for Category|....)
	     * @param type $keyField - Primary Key Field in joined table (default is 'id')
	     */
	    function saveEntity($currEntities, $key, $keyField = 'id') 
		{
	        $current = array();
	        foreach($currEntities as $entity) {
	            $current[$entity->valueForKeyPath($keyField)] = $entity;
	        }
        
	        //Add / Update relevant stock counts
	        $newEntities = $this->allFormValuesWhoseKeysStartWith($key);
        
        
	        debugln(array_keys($newEntities), 3);
	        $class = new ReflectionClass($this);
	        foreach ($newEntities as $formKey=>$value)
	        {  
	            //$id = $this->formValueForKey($formID);
	            if(empty($value)) {
	                continue;
	            }
	            if(!array_key_exists($value, $current)) {
	                 $method = $class->getMethod('add'.ucFirst($key));
	                 debugln("ADDING FOR KEY $value", 3);
	                 $method->invoke($this, $value, $formKey);
	            } else {
	                //remove from array
	                debugln("REMOVING $id from current", 3);
	                $current = array_diff_key($current, array($value=>0));
	            }
	        }	
	        foreach ($current as $id=>$entity) {
	            $method = $class->getMethod('delete'.ucFirst($key));
	            if($method) {
	                $method->invoke($this, $entity);
	            } else {
	                $entity->delete();
	            }
	        }
	    }
    
   

        
	    /*******************************************
	      *                                         *
	      *  Joined Attribute Management Functions  *
	      *                                         *
	      *******************************************/
    
    
	   /**
	    * This function prints existing attributes and a form to add new attributes, in a table format,
	    * for attributes which are joined to external tables.  In the basic case, this assumes 'id' field as
	    * the value to POST as 'name' field as the value to display, but these can be overridden.  
	    * 
	    * Alternatively, for more complex joins (i.e. attribute type and value) You can post you own functions
	    * for printing the existing value and new select rows.
	    * 
	    * This function also requires:
	    *   print$TagSelect() function = to print available options for adding new mappings
	    *   save() method searching for the appropriate tags
	    *   accompanying JS to handle adding multiple new options (if adding more than one at a time)
	    * 
	    * @param String $title - Table title
	    * @param String $tag - Tag which will be prefixed to all inputs (to search fro in save() method)
	    * @param type $relationship - Relationship of join to table from object
	    * @param type $valFunc - [OPTIONAL] function ot print existing values.
	    * @param type $id - [OPTIONAL] if no $valFunc, then name of field conatining value to be POSTed (default is id).
	    * @param type $name -  [OPTIONAL] if no $valFunc, then name of field conatining value to be displayed (default is name).
	    */
	    public function printJoinSelectRow($title, $tag, $relationship, $valFunc = null, $id = 'id', $name='name') {
        
	        $item = $this->currentEntity();
	        $rows = $item->arrayValueForRelationship($relationship);
        
	        //print List of current Categories
	        print '<tr>
	                <td colspan="2">
	                    <table class="'.$tag.'_table">
	                        <tr>
	                            <th class="fieldLabel"><h3>'.$title.'</h3></th>
	                       </tr>';
        
	        foreach($rows as $row) {
	            print '
	                    <tr>
	                        <td>';
	            if($valFunc) {
	                        $class = new ReflectionClass($this);
	                        $method = $class->getMethod($valFunc);
	                        $method->invoke($this, $row);
	            } else {
	                 $uniqid = uniqid();
	             print '<input type="hidden" id="'.$tag.'|'.$uniqid.'" name="'.$tag.'|'.$uniqid.'" value="'.$row->vars[$id].'" />';
	             print $row->vars[$name].'</td>';
	            }
	            print '<td>';
	            addSubmitButtonWithActions('X', array('action'=>'save'), null, null, '$(this).parent().parent().remove();');
	            // Optional JS method print '<input type="button" class="delete" value="X" onClick="$(this).parent().parent().remove();" />';
	            print '</td></tr>';
	        }
        
	        //Print select input for new category
 
	        print '<tr class="new'.$tag.' root'.$tag.'">
	            <td>
	                        ';
	                        $funcName = 'print'.$tag.'Select';
	                        $class = new ReflectionClass($this);
	                        $method = $class->getMethod($funcName);
	                        $method->invoke($this);
	                        print '	
                        
                        
	                        </td><td>
	                        <input type="button" class="delete hidden" value="X" onClick="remove'.$tag.'($(this));" >
                       
	                </td>
	            </tr>
	            </table></td></tr>';
	    }
   
  
	    //Relationship specific functions for joins
    
	    //Category select dialogue - could also be used in Item filter interface
	    public function printElementSelect($tag, $list, $idField = 'id', $displayField='name') 
		{
	        $uniqid = uniqid();
	        print  '<select id="$tag|'.$uniqid.'" name="$tag|'.$uniqid.'" >';
	        constructSelectOption("", "", "- Select -");
	        foreach($list as $option) {
	            print '<option value="'.$option->vars[$idField].'" >'.$option->vars[$displayField].'</option>';
	        }
	        print '    </select>';
	    }
    
    
    
	    //Image Assist functions - can be overridden by inheriting classes for non-standard saves
	    protected function checkImageInfo($imgInfo) {
        
	    }
    
	    protected function getImagePath($image) {
	        return $image->imagePath();
	    }
    
	    protected function imageSaved($image) {
            
	    }
	
		// For backwards compatibility.
		public function deleteImage()
		{
			$this->setFormValueForKey("type", doEncrypt("Image"));
		}
    
	    public function deleteRelatedRecord() 
		{
			if ($this->hasInvalidTransactionID()) {
				$this->errorMessage = "The previous action can not be re-performed from reloading the page.";
				return;
			}
			
	        $imageID = doDecrypt($this->formValueForKey("selectedImageID"));
			$type = doDecrypt($this->formValueForKey("type"));
			if ($imageID && $type)
			{
		        $image = BLGenericRecord::recordMatchingKeyAndValue($type, "id", $imageID);
		        if ($image) 
		        {
					try {
						$image->delete();
						$this->alertMessage = "Selected item has been deleted.";
					}
		            catch (Exception $error) {
		            	debugln($error->getMessage());
						$this->errorMessage = "The item could not be deleted due to a problem, please consult the log file for more information.";
		            }
		        }
		        else
		        {
		            $this->errorMessage = "There was a problem completing your request, please try again.";
		        }
			}
			else {
				$this->errorMessage = "There was a problem completing your request, please try again.";
			}      
	    }
    
    	public function processImages()
    	{
    		parent::uploadImages();
    	}
    
	    public function uploadImages($inputName = 'image', $imageEntityName = 'Image', $joinKey = null) 
		{
	        $entity = $this->currentEntity();
	        if (! $entity)  
			{
	            $this->errorMessage = "Error Uploading Image. Please ensure you have created the item first by clicking save.";
	            return;
	        }
	        if (! empty($_FILES)) 
			{
				$entityID = $entity->field($entity->pkNames());
				if (! $joinKey) {
					$joinKey = strtolower($entity->tableName()).strtoupper($entity->pkNames());
				}
			
	            foreach ($_FILES as $inputName => $fileInfo) 
				{
	                if (empty($inputName) || empty($fileInfo)) {
	                    continue;
	                }
	                $count = sizeof($fileInfo["tmp_name"]);
	                $limit = 50 * 1024 * 1024; // 50MB;
                
	                $fileStatus = $fileInfo["error"];
	                $name = $fileInfo["name"];
	                if (empty($name)) {
	                    continue;
	                }
	                if (! checkUpload($this, $inputName, $limit, 0)) {
	                    continue;
	                }
	                $src = $fileInfo["tmp_name"];

	                $size = getimagesize($src);
	                $mimeType = $newImage->vars["mimeType"] = image_type_to_mime_type($size[2]);

	                if (strpos($mimeType, 'image') !== 0) {
	                    $this->errorMessage = 'Uploaded file: ' . $name . ' is not an image';
	                    continue;
	                }

	                $this->checkImageInfo($size);
	                if ($this->errorMessage) {
	                    // if image check set an error message, return this to the user and don't upload image
	                    return;
	                }
                
					$destPath = "";
	                if ($imageEntityName) 
					{                    
	                    $newImage = BLGenericRecord::newRecordOfType($imageEntityName);
	                    $newImage->vars["fileName"] = $name;
	                    $newImage->vars["width"] = $size[0];
	                    $newImage->vars["height"] = $size[1];
	                    $newImage->vars["mimeType"] = $mimeType;
						$parts = explode(".", $name);
	                    $newImage->vars["fileExtension"] = end($parts);
						$newImage->vars[$joinKey] = $entityID;
	                    try {
	                    	$newImage->save();	
	                    }
						catch (Exception $error) {
							debugln($error->getMessage());
							$this->errorMessgae = "There was an error saving your image. Please consult the log file for more information.";
							return;
						}
					
	                    //configure any fields and joins, return image path
	                    $destPath = $this->getImagePath($newImage);
	                } 
					if (! $destPath)
						return;
                
	                /** init directory structure as necessary **/
	                $dir = dirname($destPath);
	                if (! is_dir($dir)) {
	                    mkdir($dir, 0777, true);
	                }

	                debugln("saving $destPath", 1);
	                // Store full resolution image
	                if (! move_uploaded_file($src, $destPath)) 
					{
	                    $newImage->delete();
	                    debugln("failed to move file to $destPath");
	                    $this->errorMessage = "Sorry there was a problem uploading the file $name. Please try again.";
	                    return;
	                }
	                $this->imageSaved($newImage);
	            }
	        }
	    }
	}
?>