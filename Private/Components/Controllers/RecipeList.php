<?php
    require_once BLOGIC."/BLogic.php";
    require_once ROOT."/Components/Controllers/ListViewComponent.php";

    class RecipeList extends ListViewComponent   
    {
        protected $headers = array(
    		'id' => 'id',
            'name' => 'name',
            'options' => 0
        );
        
        protected $default_sort_key = "name";
         
        public function __construct($formData) 
        { 
            parent::__construct($formData, "RecipeList");
            
            if (! $this->formValueForKey("sortBy")) {
                $this->setFormValueForKey($this->default_sort_key, "sortBy");
            }
            if (! $this->formValueForKey("sortOrder")) {
                $this->setFormValueForKey(ORDER_ASCEND, "sortOrder");
            }
        } 

        /* Return Display Group or List */
        public function getDisplayGroup() 
        {
            if (! $this->displayGroup) 
            {
                $quals = array();
                $filters = $this->allFormValuesWhoseKeysStartWith('filter|');
                $foundDate = false;
                foreach ($filters as $filter=>$value) 
                {
                    if ($value == null) {
                        continue;
                    }
                    $key = str_replace('filter|', '', $filter);
                    switch ($key) 
                    {
                        case 'startDate':
                            $quals[] = $this->processStartDate($value, 'filter|endDate');
                            $foundDate = true;
                            break;
                        case 'endDate':
                            // skip if already processed
                            if (! $foundDate) {
                                $quals[] = $this->processEndDate($value);
                            }
                            break;
                        default:
                            $quals[] = new BLKeyValueQualifier($key, OP_CONTAINS, '%'.$value.'%');
                            break;
                    }
                }
            
                $qual = empty($quals) ? null : new BLAndQualifier($quals);
                $this->displayGroup = new PLDisplayGroup('Recipe', $qual, $this->getSortBy($this->default_sort_key), $this->resPerPage(), $this, 'pageNo');
            }
            return $this->displayGroup;
        }
    
        public function printFilters() 
        {
            foreach ($this->headers as $header => $keyPath) 
            {
                print '<td>';
                switch($header) 
                {
                    case('id'): 
                        $this->printFilterInput($keyPath, 'small');
                        break;
                    case('Date'):
                        $this->printDateFilter();
                        break;
                    case("options"):
                        addSubmitButtonWithActions('Go', array(), 'filter');
                    default:
                        if (! (is_numeric($keyPath) && (strpos($keyPath, '.') === FALSE)))
                            $this->printFilterInput($keyPath);
                        break;
                }
                print '</td>';
            }
        }
    
        protected function printFilterInput($keyPath, $class = null) 
        {
            print '<input class="filter '.$class.'" type="text" name="filter|'.$keyPath.'" id="filter|'.$keyPath.'" value="'.$this->formValueForKey('filter|'.$keyPath).'"/>';
        }

        public function printField($header, $value, $id, $entity) 
        {
            // See if controller has method to print field
             switch ($header) 
             {
                 case('options'):
                    addLinkWithParams("Edit", "RecipeEdit/$id", array());
                    break;
                 default:
                    if (is_numeric($value)) {
                        print number_format(floatval($value));
                    } 
                    else if ($value) {
                        print $value;
                    } 
                    else {
                        print "N/A";
                    }
                    break;
             }
        }
		
		public function likeRecipeToggle(){
			//debugln("goat here : " . doDecrypt($this->formValueForKey("likeRecipeID")));
			$this->user()->likeRecipeToggle(doDecrypt($this->formValueForKey("likeRecipeID")));
		}

		public function likesImageString($recipe){
			//debugln("what am i ? " . $recipe);
			if($this->user()->likesRecipe($recipe->vars["id"])){
				return "images/favouriteOn.png";
			}
			else{
				return "images/favouriteOff.png";
			}
			
		}
    }
?>