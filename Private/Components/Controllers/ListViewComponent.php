<?php
	 require_once BLOGIC."/BLogic.php";
    require_once ROOT."/Components/Controllers/PageWrapper.php";

	class ListViewComponent extends PageWrapper  
	{
		public function __construct($formData, $innerTemplate) 
		{ 
			parent::__construct($formData, "ListViewComponent"); 
			$this->innerTemplate = $this->templateForName($innerTemplate);
			$this->innerTemplate->set("controller", $this);
		} 
	    /**
	     * Return display group (retrieve if necessary)
	    */
	    protected $displayGroup;
	
	    public function displayGroup()
		{
	        if (! $this->displayGroup)
	        {
	            $this->displayGroup = $this->getDisplayGroup(); 
	        }
	        return $this->displayGroup;
	    }

	    /** Table management **/
    
	    /* Page Management */
	    public function nextBatch()
	    {
	        $this->displayGroup()->nextBatch();
	    }

	    public function previousBatch()
	    {
	        $this->displayGroup()->previousBatch();
	    }
    
	    // return this table's headers
	    protected $headers;
	
	    public function headers() 
		{
	        return $this->headers;
	    }
    
	    /* Manage Tooldbar display options */
	    //print table header contianing sort buttons
	    public function printSortableHeaders($headers, $page, $sortByKey = 'sortBy') 
		{
	        $sortBy = $this->formValueForKey($sortByKey);
	        foreach($headers as $header=>$field) 
			{
	            //field is sortable, so make header a button
	            print '<th';
	            if($field === $sortBy) {
	                  print ' class="selected" ';
	                  $sortOrder = ($this->formValueForKey('sortOrder') == ORDER_ASCEND) ? ORDER_DESCEND : ORDER_ASCEND;
	            } else {
	                  $sortOrder = ORDER_ASCEND;
	            }

	            print '>';
	            if(!is_numeric($field)) {
	                addSubmitButtonWithActions($header, array("action" => "doNothing", "page" => $page, 'sortBy'=>$field, 'sortOrder'=>$sortOrder), 'header', null, "setSort('$field','$sortOrder')");
	            } else {
	                addSubmitButtonWithActions($header, array("action" => "doNothing", "page" => $page, 'sortBy'=>$field, 'sortOrder'=>$sortOrder), 'header', null, "setSort('$field','$sortOrder')");
	            }
	            print '</th>';
	        }
	    }
    
	    // Process submitted 'sort by' values
	    public function getSortBy($default=array('id' => ORDER_ASCEND), $sortByKey='sortBy', $sortOrderKey='sortOrder') 
		{
	        // try to read value from form input
	        $sortByField =  $this->formValueForKey($sortByKey);
	        //check if set AND for correct form (in case tab changed or other  erroneous value)
	        if (empty($sortByField) || (array_search($sortByField, $this->headers()) === FALSE)) {
	           // if not set, return supplied defaults, and set for key to default
	           $sortBy = $default; 
	           $default_keys = array_keys($default);
	           $this->setFormValueForKey($sortByKey, $default_keys[0]);
	        } else {
	            //if values passed in, format into array to pass to find()
	            $sortOrder = $this->formValueForKey($sortOrderKey);
	            if (empty($sortOrder)) {
	                $sortOrder = ORDER_ASCEND;
	            }
	            $sortBy = array($sortByField => $sortOrder);
	        }

	        return $sortBy;

	    }
    
	    public function resPerPage($key='resPerPage', $default=50) {
	        $perPage = $this->formValueForKey($key);
	        if (! $perPage) {
	            $perPage = $default;
	        }
	        return $perPage;
	    }
    
	        /** Date Filters **/
	    public function processStartDate($value, $endInput = 'filter|endDate', $field = 'date') {
	        //Filter between dates, or after startDate
	        $sDate = new DateTime($value);
	        //Before End date (if sepcified - default will b]e now)
	        $eDate = new DateTime($this->formValueForKey($endInput));
	        return new BLKeyValueQualifier($field, OP_BETWEEN, array($sDate->format('Y-m-d H:i:s'), $eDate->format('Y-m-d H:i:s')));
	    }

	    public function processEndDate($value, $field = 'date') {
	        $eDate = new DateTime($value);
	        return new BLKeyValueQualifier($field, OP_LESS_EQUAL, $eDate->format('Y-m-d H:i:s'));
	    }

	    public function printDateFilter($input = 'Date') {
	        $this->printFilterInput('start' . $input, 'date', 'date', 'From');
	        print '<br>';
	        $this->printFilterInput('end' . $input, 'date', 'date', 'To');
	    }
	}

?>