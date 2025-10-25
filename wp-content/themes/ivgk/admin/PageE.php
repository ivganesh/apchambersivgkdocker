<?php
add_shortcode("CustomPage", "CustomPage");
function CustomPage($atts)
{
	if ( is_user_logged_in()) {
		//print_r($_POST); 
		global $wp;
        global $wpdb;
        global $jsData;
        global $field;
		global $fieldValue;
        global $errorMsg;

		extract($atts);
        extract($jsData);
        $pagename = strtolower($pagename);
        $nonceField = $pagename . "_action";
        $returnSubmitValue = '';
        $isSuccess = true;
        $classUI = new classUI();
        $classMysql = new classMysql();
        $classAction = new classAction();
        $classValidator = new classValidator();
        $classDate = new classDate();
        $field['fieldData']['pageName'] = $pagename;
        $field['fieldData']['pageNm'] = $pagenm;
        $user = wp_get_current_user();
        $role = (array) $user->roles; $roleKey = key($role);
        $field['fieldData']['currentId'] = $user->ID;
        $field['fieldData']['currentRole'] =  $role[$roleKey];
		$field['fieldData']['currentAction'] =  array( 'ADD' ,'UPDATE','TRASH','RESTORE','VIEW','DELETE');
        $field['fieldData']['errorPrefix'] = isset( $errorprefix ) ? $errorprefix : "Default Error";
        $field['fieldData']['reportheadername'] = isset( $reportheadername )  ? $reportheadername : "Default Form Name";
        $field['fieldData']['formheadername'] = isset( $formheadername )  ? $formheadername : "Default Form Name";
        $field['fieldData']['nonceField'] = $pagename . "_action";
        $field['fieldData']['uniqueField'] = array("Page_Name" => "Page_Name","1" => "AND", "Field_Name" => "Field_Name","2" => "AND", "Group_Field_Name" => "Group_Field_Name" );
        $field['fieldData']['shownAfterAction'] = array("Group_Order" => "Group_Order","Field_Order" => "Field_Order");
		$field['fieldData']['setAfterAction'] = array("Col_Class"=>"Col_Class", "Form_Group" => "Form_Group","Page_Name" => "Page_Name","Field_Group" => "Field_Group", "Group_Order" => "Group_Order", "Field_Order" => "Field_Order");
        $field['fieldData']['insertJsonData'] ="NO";
		$field['fieldData']['dontChange'] = array("" => "");
		$fieldValue['Col_Class'] = 'col-sm-12 col-md-6';
		

        $thisTable = "{$wpdb->prefix}pagename";
        $pagenameList = $wpdb->get_results("SELECT ID,
												   Page_Name
											FROM {$thisTable}
											ORDER BY Page_Name", 'ARRAY_A');
        $pagenameList = $classUI->setAsSelectOption(array("table_data" => $pagenameList,
					"option_text" => array("ID"),
					"option_value" => array("Page_Name"),
				)
		);

		$fieldGroupList["field"] = "Field";
		$fieldGroupList["search"] = "search" ;
		$fieldGroupList["multi"] = "Multi";

		


 

		$hasRelationList = array("" => "Select Option","relation" => "relation","maxlength"=>"maxlength");							
		$rowList = array("" => "Select Option","YES" => "YES");					
		
		$formGroupList = array("" => "Select Form Group", "vertical"=>"Vertical" , "horizontal"=> "Horizontal", "form-outline"=> "outline", "material" => "material"); 
		$fieldTypeList = array("" => "Select Field Type", "input"=>"input" , "select"=> "select","textarea" => "textarea", "radio" => "radio", "checkbox" => "checkbox", "avatar" => "avatar", "gmap" => "gmap", "datalist"=>"datalist");
		$getQueryList = array("" => "Select Option", 
							"equalOne"=> "Search Equal One","equalTwo"=> "Search Equal Two",
							"equalThree"=> "Search Equal Three","equalFour"=> "Search Equal Four",
							"equalFive"=> "Search Equal Five","equalSix"=> "Search Equal Six",
							 "likeOne"=> "Search Like One", "likeTwo"=> "Search Like Two",
							 "dateOne"=> "Search Date One","dateTwo"=> "Search Date Two",
							 "multiOne"=> "Search Multi One","multiTwo"=> "Search Multi Two");

		$inputTypeList = array("" => "Select Option","number"=>"number","text"=>"text","date"=>"date","time"=>"time","submit"=>"submit","button" => "button","email"=>"email","file"=>"file","hidden"=>"hidden", "password"=>"password", "multiple"=>"multiple");						
		$datatableColumnTypeList = array(""=>"Select Option", "text"=>"text", "text-right"=>"text-right", "text-left"=>"text-left", "float-right"=>"float-right", "float"=>"float", "edit" => "edit", "delete" => "delete", "print" => "print", "editdelete" => "edit delete", "editdeleteprint" => "edit delete print"  );
	
		$field['1']['field']['ID']['ID'] = array(
            'fieldType' => "input",
            "fieldValue" => array(
                'type' => "hidden",
				"name" => "ID",
				"id" => "ID",
                "class" => "form-control",
            ),
        );
		$field['1']['field']['Page_Name']['Page_Name'] = array(
            'fieldType' => "select",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
            "rowOpen" => true,
			"firstOption" => "Select Page Name",
			"optionList" => $pagenameList,
			"formGroup"=>"form-outline", 
            "fieldValue" => array(
                'required' => true,
				"name" => "Page_Name",
				"id" => "Page_Name",
                "class" => "form-control select2",
                "autofocus" => "autofocus",
            ),
        );
		

		$field['1']['field']['Field_Group']['Field_Group'] = array(
            'fieldType' => "select",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"optionList" => $fieldGroupList,
			"firstOption" => "Select Field Group",
			"formGroup"=>"form-outline",
            "fieldValue" => array(
                'required' => true,
				"name" => "Field_Group",
				"id" => "Field_Group",
				"class" => "form-control",
            ),
        );
		
		$field['1']['field']['Form_Group']['Form_Group'] = array(
			'fieldType' => "select",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"optionList" => $formGroupList,
			"firstOption" => "Select Form Group",
			"formGroup"=>"form-outline",
            "fieldValue" => array(
                'required' => true,
				"name" => "Form_Group",
				"id" => "Form_Group",
				"class" => "form-control",
            ),
        );

		$field['1']['field']['Col_Class']['Col_Class'] = array(
            'fieldType' => "input",
			"formGroup"=>"form-outline", 
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
            "fieldValue" => array(
				"name" => "Col_Class",
				"id" => "Col_Class",
				"type" => "text",
				"maxlength" => 100,
                "class" => "form-control",
				"placeholder"=>"col-sm-12 col-md-6 col-lg-4",
            ),
        );
		$field['1']['field']['Row_Open']['Row_Open'] = array(
            'fieldType' => "select",
			"formGroup"=>"form-outline",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"optionList" => $rowList,
            "fieldValue" => array(
				"name" => "Row_Open",
				"id" => "Row_Open",
                "class" => "form-control",
            ),
        );
		$field['1']['field']['Row_Close']['Row_Close'] = array(
            'fieldType' => "select",
			"formGroup"=>"form-outline",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"optionList" => $rowList,
            "fieldValue" => array(
				"name" => "Row_Close",
				"id" => "Row_Close",
                "class" => "form-control",
				
            ),
        );
		$field['1']['field']['Group_Order']['Group_Order'] = array(
            'fieldType' => "input",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
				"name" => "Group_Order",
				"id" => "Group_Order",
                'required' => true,
				"type" => "number",
				"step" => 1,
				"min" => 1,
				"max" => 999,
                "class" => "form-control",
            ),
        );
		$field['1']['field']['Field_Order']['Field_Order'] = array(
            'fieldType' => "input",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
				"name" => "Field_Order",
				"id" => "Field_Order",
                'required' => true,
				"type" => "number",
				"step" => 1,
				"min" => 1,
				"max" => 999,
                "class" => "form-control",
            ),
        );
		$field['1']['field']['Group_Field_Name']['Group_Field_Name'] = array(
            'fieldType' => "input",
			"formGroup"=>"form-outline",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
            "fieldValue" => array(
				"name" => "Group_Field_Name",
				"id" => "Group_Field_Name",
                'required' => true,
				"type" => "text",
				"maxlength" => 100,
                "class" => "form-control",
            ),
        );
		$field['1']['field']['Field_Name']['Field_Name'] = array(
            'fieldType' => "input",
			"formGroup"=>"form-outline",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
            "fieldValue" => array(
				"name" => "Field_Name",
				"id" => "Field_Name",
                'required' => true,
				"type" => "text",
				"maxlength" => 100,
                "class" => "form-control",
            ),
        );

		
		$field['1']['field']['Display_Name']['Display_Name'] = array(
            'fieldType' => "input",
			"formGroup"=>"form-outline",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
            "fieldValue" => array(
				"name" => "Display_Name",
				"id" => "Display_Name",
				"type" => "text",
				"maxlength" => 100,
                "class" => "form-control",
            ),
        );
		/*
		$field['1']['field']['Is_Hide']['Is_Hide'] = array(
            'fieldType' => "select",
			"formGroup"=>"form-outline",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"rowClose" => true,
			"optionList" => $rowList,
            "fieldValue" => array(
				"name" => "Is_Hide",
				"id" => "Is_Hide",
                "class" => "form-control",
            ),
        );
		*/
		




		

		
		$field['1']['field']['Field_Type']['Field_Type'] = array(
			'fieldType' => "select",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"optionList" => $fieldTypeList,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
				"name" => "Field_Type",
				"id" => "Field_Type",
                'required' => true,
				"class" => "form-control",
            ),
        );


		$field['1']['field']['type']['type'] = array(
			'fieldType' => "select",
            "colClass" => 'col-sm-4 col-md-3',
            "spanWidth" => $spanWidth,
			"optionList" => $inputTypeList,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
				"name" => "type",
				"id" => "type",
				"class" => "form-control",
            ),
        );
		$field['1']['field']['dv']['dv'] = array(
            'fieldType' => "input",
			"formGroup"=>"form-outline",
            "colClass" => 'col-sm-8 col-md-9',
            "spanWidth" => $spanWidth,
            "fieldValue" => array(
				"name" => "dv",
				"id" => "dv",
				"type" => "text",
				"maxlength" => 100,
                "class" => "form-control",
            ),
        );
		$field['1']['field']['Has_Index']['Has_Index'] = array(
			'fieldType' => "select",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"optionList" => $rowList,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
				"name" => "Has_Index",
				"id" => "Has_Index",
				"class" => "form-control",
				
		    ),
        );
		$field['1']['field']['class']['class'] = array(
            'fieldType' => "input",
			"formGroup"=>"form-outline",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
            "fieldValue" => array(
				"name" => "class",
				"id" => "class",
				"type" => "text",
				"maxlength" => 100,
                "class" => "form-control",
				"placeholder"=>"select2 , dateRangePickerCurrentDate"
            ),
        );
		
		$field['1']['field']['required']['required'] = array(
            'fieldType' => "select",
			"formGroup"=>"form-outline",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"optionList" => $rowList,
            "fieldValue" => array(
				"name" => "required",
				"id" => "required",
                "class" => "form-control",
            ),
        );
		$field['1']['field']['autofocus']['autofocus'] = array(
            'fieldType' => "select",
			"formGroup"=>"form-outline",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"optionList" => $rowList,
            "fieldValue" => array(
				"name" => "autofocus",
				"id" => "autofocus",
                "class" => "form-control",
            ),
        );
		$field['1']['field']['readonly']['readonly'] = array(
            'fieldType' => "select",
			"formGroup"=>"form-outline",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"optionList" => $rowList,
            "fieldValue" => array(
				"name" => "readonly",
				"id" => "readonly",
                "class" => "form-control",
            ),
        );
		
		$field['1']['field']['show_message']['show_message'] = array(
			 'fieldType' => "select",
			"formGroup"=>"form-outline",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"optionList" => array(""=>"Select Option","HIDE"=>"HIDE"),
            "fieldValue" => array(
				"name" => "show_message",
				"id" => "show_message",
                "class" => "form-control",
            ),
        );
		
		$field['1']['field']['placeholder']['placeholder'] = array(
			'fieldType' => "input",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
				"name" => "placeholder",
				"id" => "placeholder",
				"class" => "form-control",
				"type" => "text",
		    ),
        );

		$field['1']['field']['step']['step'] = array(
			'fieldType' => "input",
			"displayName"=> "step:[ input:number ]",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
				"name" => "step",
				"id" => "step",
				"class" => "form-control",
				"type" => "number",
				"min" => 0,
				"placeholder" => "step:[ input:number ]",
            ),
        );

		$field['1']['field']['min']['min'] = array(
			'fieldType' => "input",
			"displayName"=> "min:[ input:number ]",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
				"name" => "min",
				"id" => "min",
				"class" => "form-control",
				"type" => "number",
				"placeholder" => "min:[ input:number ]",
            ),
        );

		$field['1']['field']['max']['max'] = array(
			'fieldType' => "input",
			"displayName"=> "max:[ input:number ]",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
				"name" => "max",
				"id" => "max",
				"class" => "form-control",
				"type" => "number",
				"placeholder" => "max:[ input:number ]",
            ),
        );

		$field['1']['field']['minlength']['minlength'] = array(
			'fieldType' => "input",
			"displayName"=> "minlength:[ input:text ]",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
				"name" => "minlength",
				"id" => "minlength",
				"class" => "form-control",
				"type" => "number",
				"step" => 1,
				"min" => 0,
				"max" => 5000,
				"placeholder" => "minlength:[ input:text ]", 
			),
        );


		$field['1']['field']['maxlength']['maxlength'] = array(
			'fieldType' => "input",
			"displayName"=> "maxlength:[ input:text ]",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
				"name" => "maxlength",
				"id" => "maxlength",
				"class" => "form-control",
				"type" => "number",
				"step" => 1,
				"min" => 0,
				"max" => 5000,
				"placeholder" => "maxlength:[ input:text ]",
            ),
        );
		$field['1']['field']['First_Option']['First_Option'] = array(
			'fieldType' => "input",
			"displayName"=> "First Option:[ select ]",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
				"name" => "First_Option",
				"id" => "First_Option",
				"class" => "form-control",
				"type" => "text",
				"maxlength" => 100,
				"placeholder" => "First Option:[ select ]",
            ),
        );
		$field['1']['field']['Option_List']['Option_List'] = array(
			'fieldType' => "input",
			"displayName"=> "Option List:[ select ]",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
				"name" => "Option_List",
				"id" => "Option_List",
				"class" => "form-control",
				"maxlength" => 100,
				"type" => "text",
				"placeholder" => "Option List:[ select ]",
		    ),
        );
		
		
		$field['1']['field']['rows']['rows'] = array(
			'fieldType' => "input",
			"displayName"=> "rows:[ textarea ]",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
				"name" => "rows",
				"id" => "rows",
				"class" => "form-control",
				"type" => "number",
				"step" => 1,
				"min" => 1,
				"placeholder"=> "rows:[ textarea ]"
		    ),
        );


		$field['1']['field']['Block_On_Update']['Block_On_Update'] = array(
			'fieldType' => "select",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"optionList" => $rowList,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
				"class" => "form-control",
				"name" => "Block_On_Update",
				"id" => "Block_On_Update"
			),
        );
		$field['1']['field']['Block_On_Copy']['Block_On_Copy'] = array(
			'fieldType' => "select",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"optionList" => $rowList,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
				"class" => "form-control",
				"name" => "Block_On_Copy",
				"id" => "Block_On_Copy"
			),
        );

		$field['1']['field']['Unique_Field']['Unique_Field'] = array(
			'fieldType' => "select",
            "colClass" => $md3lg3,
			"optionList" => $rowList,
            "spanWidth" => $spanWidth,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
				"class" => "form-control",
				"name" => "Unique_Field",
				"id" => "Unique_Field"
			),
        );
		$field['1']['field']['Get_Query']['Get_Query'] = array(
			'fieldType' => "select",
            "colClass" => $md3lg3,
			"optionList" => $getQueryList,
            "spanWidth" => $spanWidth,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
				"class" => "form-control",
				"name" => "Get_Query",
				"id" => "Get_Query"
			),
        );
		$field['1']['field']['Label_Width']['Label_Width'] = array(
			'fieldType' => "input",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
				"type" => "number",
				"min" => 0,
				"step" => 5,
				"max" => 500,
				"class" => "form-control",
				"name" => "Label_Width",
				"id" => "Label_Width"
			),
        );

		$field['1']['field']['Shown_After_Action']['Shown_After_Action'] = array(
			'fieldType' => "select",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"optionList" => $rowList,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
				"name" => "Shown_After_Action",
				"id" => "Shown_After_Action",
				"class" => "form-control",
            ),
        );
		$field['1']['field']['Set_After_Action']['Set_After_Action'] = array(
			'fieldType' => "select",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"optionList" => $rowList,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
				"name" => "Set_After_Action",
				"id" => "Set_After_Action",
				"class" => "form-control",
            ),
        );
		$field['1']['field']['DBTbl_Name']['DBTbl_Name'] = array(
			'fieldType' => "input",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
				"name" => "DBTbl_Name",
				"id" => "DBTbl_Name",
				"type" => "text",
				"maxlength" => 50,
				"class" => "form-control",
            ),
        );
		$field['1']['field']['DBTbl_Key']['DBTbl_Key'] = array(
			'fieldType' => "input",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"rowClose" => true,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
				"name" => "DBTbl_Key",
				"id" => "DBTbl_Key",
				"type" => "text",
				"maxlength" => 50,
				"class" => "form-control",
            ),
        );

		$field['1']['field']['Group_Prefix']['Group_Prefix'] = array(
			'fieldType' => "textarea",
            "colClass" => $md6lg6,
            "spanWidth" => $spanWidth,
			"formGroup"=>"form-outline",
			"rowOpen" => true,
            "fieldValue" => array(
				"class" => "form-control",
				"name" => "Group_Prefix",
				"id" => "Group_Prefix",
				"rows" => 5,
			),
        );

		$field['1']['field']['Group_Suffix']['Group_Suffix'] = array(
			'fieldType' => "textarea",
            "colClass" => $md6lg6,
            "spanWidth" => $spanWidth,
			"formGroup"=>"form-outline",
			"rowClose" => true,
            "fieldValue" => array(
				"class" => "form-control",
				"name" => "Group_Suffix",
				"id" => "Group_Suffix",
				"rows" => 5,
			),
        );
		


		
		$field['1']['field']["ADD"]["ADD"] = array(
            "fieldType" => "input",
            "colClass" => 'col-6',
            "rowOpen" => true,
			
            "formGroup" => "material",
            "fieldValue" => array(
				"name" => "ADD",
				"id" => "ADD",
                "type" => "submit",
                "class" => "form-control",
            ),
        );
        $field['1']['field']["REFRESH"]["REFRESH"] = array(
            "fieldType" => "input",
            "colClass" => 'col-6',
            "rowClose" => true,
            "formGroup" => "material",
            "fieldValue" => array(
				"name" => "REFRESH",
				"id" => "REFRESH",
                "type" => "submit",
                "class" => "form-control cancel",
            ),
        );
        $field['1']['search']["SName"]["SName"] = array(
            "fieldType" => "select",
            "displayName" => "Page Name",
            "rowOpen" => true,
			"firstOption" => "select Page Name",
			"optionList" => $pagenameList,
            "colClass" => $md5lg5,
			"formGroup" => "form-outline",
            "spanWidth" => $spanWidth,
            "fieldValue" => array(
				"name" => "SName",
				"id" => "SName",
                "type" => "text",
                "class" => "form-control select2",
            ),
        );
		$field['1']['search']["SField"]["SField"] = array(
            "fieldType" => "input",
            "displayName" => "Field Name",
            "colClass" => $md5lg5,
			"formGroup" => "form-outline",
            "spanWidth" => $spanWidth,
            "fieldValue" => array(
				"name" => "SField",
				"id" => "SField",
                "type" => "text",
                "class" => "form-control",
            ),
        );
   
        $field['1']['search']['SearchData']['SearchData'] = array(
            "fieldType" => "input",
            "colClass" => $md2lg2." mt-auto",
            "rowClose" => true,
            "fieldValue" => array(
				"name" => "SearchData",
				"id" => "SearchData",
                "type" => "submit",
                "class" => "form-control",
            ),
        );
		





		if (isset($_POST['SearchData'])) {
            $getExtraQry = '';
            $thisTbl = "{$wpdb->prefix}{$pagename}";
			$joinPagename = "{$wpdb->prefix}pagename";
            $getExtraQry .= isset($_POST['SName']) ? $_POST['SName'] != '' ? "  AND  {$thisTbl}.Page_Name = {$_POST['SName']} " : "" : "";
            $getExtraQry .= isset($_POST['SField']) ? $_POST['SField'] != '' ? "  AND  {$thisTbl}.Field_Name LIKE '%{$_POST['SField']}%' " : "" : "";
            $field['getQry'] = "select {$thisTbl}.ID,
								{$thisTbl}.isTrash ,
								{$thisTbl}.Option_List,
								{$thisTbl}.Get_Query,
							CONCAT( {$thisTbl}.Field_Group , ' = ', {$thisTbl}.Group_Order , ' - ', {$thisTbl}.Field_Order , ' => ',{$thisTbl}.Field_Name , ' [ ', {$thisTbl}.Field_Type , ' = ', {$thisTbl}.type , ' ( ', {$thisTbl}.required ,' <=> ', {$thisTbl}.Unique_Field, ' ) ]'  ) as orderNo,
							CONCAT( {$thisTbl}.Row_Open , ' <=> ', {$thisTbl}.Row_Close ) as columns,
							CONCAT( {$thisTbl}.Col_Class , ' <=> ', {$thisTbl}.class ) as colClass,
                             {$joinPagename}.Page_Name as pageName
							 FROM {$thisTbl}
							LEFT JOIN {$joinPagename} ON ( {$joinPagename}.ID = {$thisTbl}.Page_Name )
                            WHERE {$thisTbl}.isSys = 0
                                {$getExtraQry}
                            ORDER BY  {$joinPagename}.Page_Name, {$thisTbl}.Field_Group, {$thisTbl}.Group_Order ,{$thisTbl}.Field_Order , {$thisTbl}.Field_Group ";

            $field['tableCol'] = array(
                "Action" => array("value" => "ID", "type" => "editdeleteOpen"),
                "Name" => array("value" => "pageName", "type" => "text"),
				"Field_Req_Uniq" => array("value" => "orderNo", "type" => "text"),
				
				"Class" => array("value" => "colClass", "type" => "text"),
				"Open" => array("value" => "columns", "type" => "text"),
                
				
				"Option_List" => array("value" => "Option_List", "type" => "text"),
				"Get_Query" => array("value" => "Get_Query", "type" => "text"),
				
				
            );
        }


		
		
        if (isset($_POST['ADD'])) {

            if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check", false);
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();
            }
			$isCreated = $isIndexed = true;
            if ($isSuccess) 
			{
				if( $_POST['ADD']  == 'ADD' || $_POST['ADD']  == 'UPDATE' )
				{
					if( isset( $_POST['Field_Group'] ) && isset( $_POST['type'] ) )
					{
						
						if( $_POST['Field_Group'] == 'field' )
						{
							if (  $_POST['type'] != 'submit' && $_POST['type'] != 'button')
							{
								$isCreated = alterTable();
								
								if($isCreated)
								{
									$errorMsg[] = array("TABLE has been ALTER successfully", true);
									
								}
								else $errorMsg[] = array("Failed To ALTER TABLE ", false);
								if( $_POST['Has_Index'] == 'YES' && $_POST['Field_Group'] == 'field' )
								{
									$isIndexed = alterIndex();
									if($isIndexed)
									{
										$errorMsg[] = array("INDEX successfully created", true);
										
									}
									else $errorMsg[] = array("Failed To create INDEX ", false);
								}
							}
						}
					}
				}
				if( $isCreated && $isIndexed )
				{
					$isSuccess = $classAction->tableToMaster();
					if($isSuccess && isset($_GET['multiTasking']))
					{
						echo '<script>window.close();</script>';
					}
				}
            }

        }
	
        if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }

        if (isset($_POST['ADD'])) {
            if (strlen($returnSubmitValue) < 1) {
                if (isset($_POST)) {unset($_POST);}
            }
        }
        if (isset($_GET)) {extract($_GET);}
        if (isset($_POST)) {extract($_POST);}

        if ( isset($_GET['logID']) || isset($_POST['copyForm']) || isset($_POST['trashForm']) || isset($_POST['editForm']) || isset($_POST['deleteForm']) || isset($_POST['restoreForm'])) 
		{
			$classMysql->extractData();
            if( trim($_POST['dv']) == '' && $_POST['Field_Group'] == 'field')
			{
				if( $_POST['Field_Name'] != 'ID' && $_POST['Field_Name'] != 'Refresh_Type' && $_POST['Field_Type'] != 'gmap'
					&& $_POST['type'] != 'submit'	&& $_POST['type'] != 'button'				
				 ) 
				{
					$getTableName = $wpdb->get_results("Select DBTbl_Name from {$wpdb->prefix}pagename WHERE ID = '{$_POST['Page_Name']}'", "ARRAY_A");
					
					if( count ($getTableName) > 0 )
					{	
						$thisTablename = $getTableName[0]['DBTbl_Name'];
						if( $thisTablename != '' )
						{
							$checkColumn = $wpdb->get_results("DESC `{$wpdb->prefix}{$thisTablename}`", "ARRAY_A");
							//$indexColumn = $wpdb->get_results("SHOW INDEX FROM `{$wpdb->prefix}{$thisTablename}`", "ARRAY_A");
							
							foreach( $checkColumn as $key => $value )
							{
								if( strtolower($value['Field']) == strtolower($_POST['Field_Name'] ) ) 
								{
									
									$dvValue = $value['Type'];
									if( isset( $value['Null'] ) ) 
									{
										if( $value['Null'] == 'NO') 
										{
											$dvValue .= " NOT NULL ";
											if( isset( $value['Default'] ) ) 
											{ 
												if( strpos($value['Type'], 'int') !== false  || strpos($value['Type'], 'float') !== false  || strpos($value['Type'], 'double') !== false  ) 
													$dvValue .= " DEFAULT ".$value['Default']. " ";
												 else $dvValue .= " DEFAULT '{$value['Default']}' ";
												
											}
										}
										else $dvValue .=  " NULL ";
									}
									$_POST['dv'] = $dvValue;
									//print_r($value); 
									break;
									
								}
							}
						}
					}
				}
			}		
			extract($_POST);	

        }

        //$Admit_Date = isset( $Admit_Date ) ? $Admit_Date : date("Y-m-d");
        //$Admit_Time = isset( $Admit_Time ) ? $Admit_Time : date("H:i");
     
		echo $classUI->echoForm();
   
        echo $classUI->searchFormAndData();
		?>
		<script>
		var _rulesString = {
			type: {
				required: function(element) {
					return $("#Field_Type").val() == "input";
				}
			},
			
			
			Option_List: {
				required: function(element) {
					return  $("#Field_Type").val() == "datalist" ||  $("#Field_Type").val() == "select" || $("#Field_Type").val() == "radio" || $("#Field_Type").val() == "checkbox" ;
				}	
			},
			rows: {
				required: function(element) {
					return $("#Field_Type").val() == "textarea" ;
				}	
			},
			Group_Field_Name: {
				equalTo: {
					param: '#Field_Name',
					depends: function(element) { 
						return $("#Field_Group").val() == 'field' || $("#Field_Group").val() == 'search'  ; 
					}
				},
				whiteSpace : true,
			},
			Field_Name: {
				whiteSpace : true,
			},
			// Set_After_Action: {
			// 	equalTo:  function(element) { 
			// 			if(  $("#Has_Relation").val() == 'relation'  ) 
			// 			{
			// 				return ''  ; 
			// 			}
						
			// 		}
			// },
			// Shown_After_Action: {
			// 	equalTo:  function(element) { 
			// 			if(  $("#Has_Relation").val() == 'relation' ) 
			// 			{
			// 				return ''  ; 
			// 			}
						
			// 		}
			// },
		};
		var _messagesString = {
			type: "This field is required if Field Type=input",
			Option_List : "This field is required if Field_Type=select",
			rows : "This field is required if Field_Type=textarea",
			Group_Field_Name:"If Field_Group=field | Field_Group=search then Group_Field_Name=Field Name && special charcter not allowed",
			Field_Name:"special charcter not allowed",
			Set_After_Action:"This field must be blank if  Has_Relation=relation",
			Shown_After_Action:"This field must be blank if Has_Relation=relation",
		};
		var fldName = ''
		var dvSource = ["VARCHAR(20) NOT NULL DEFAULT ''",
						"VARCHAR(20) NOT NULL DEFAULT ''",
						"VARCHAR(20) NOT NULL",
						"BIGINT(20) NOT NULL DEFAULT '0'",
						"BIGINT(20) NOT NULL",
						"FLOAT(12,2) NOT NULL DEFAULT '0.00'",
						"FLOAT(12,2) NOT NULL",
						"DATE NOT NULL DEFAULT '0000-00-00'",
						"DATE NOT NULL",
						"TIME NOT NULL DEFAULT '00:00:00'",
						"TIME NOT NULL",
						"TEXT NOT NULL"
		];
		var groupFieldSource1 = ["Search_EqualOne",
						"Search_EqualTwo",
						"Search_EqualThree",
						"Search_EqualFour",
						"Search_EqualFive",
						"Search_EqualSix",
						"Search_DateOne",
						"Search_DateTwo",
						"Search_LikeOne",
						"Search_LikeTwo",
						"Search_MultiOne",
						"Search_MultiTwo",
						"SearchData"
		];
		var groupFieldSource2 = [
						"ID",
						"Refresh_Type",
						"ADD",
						"REFRESH"
		];
		$(function () {
			
			$.validator.addMethod("whiteSpace", function(value, element) {
				var format = /^[a-zA-Z0-9_]+$/;
				var groupName = $("#Group_Field_Name").val();
				var fieldName = $("#Field_Name").val(); 
				//alert(format.test(groupName));
				if( format.test(groupName) && format.test(fieldName) )
				{
					return true;
				}
				else return false;
				
			}, "Invalid group or field name");
			jQuery("body").on( "focus", "#dv" , function(){
				jQuery(this).autocomplete({
					source: dvSource, 	
					matchCase: false,
					minLength: 0,		
					autoFocus: true
				});
			});
				
			jQuery("body").on( "focus", "#Group_Field_Name" , function(){
				if( jQuery("#Field_Group").val() == "search" ) var groupFieldSource = groupFieldSource1;
				else var groupFieldSource = groupFieldSource2;
				jQuery(this).autocomplete({
					source: groupFieldSource, 	
					matchCase: false,
					minLength: 0,		
					autoFocus: true
				});
			});

			$('body').on('blur', '#Group_Field_Name',function () {
				var thisVal = $(this).val();
				if( $("#Field_Name").val() == '' )  $("#Field_Name").val(thisVal);
				if( thisVal == 'Refresh_Type' || thisVal == 'ID' || thisVal == 'SearchData' || thisVal == 'ADD' || thisVal == 'REFRESH' )
				{
					$("#Field_Type").val("input");
					if( thisVal == 'SearchData' || thisVal == 'ADD' || thisVal == 'REFRESH' ) $("#type").val("submit");
					if( thisVal == 'ID' || thisVal == 'Refresh_Type')$("#type").val("hidden");
					if (  thisVal == 'REFRESH' ) $("#class").val("cancel");
				}
			});
			$('body').on('change', '#Field_Type',function () {
				if( $(this).val() == 'select')
				{
					var fldName = $("#Display_Name").val();
					if( fldName == '') fldName = $("#Field_Name").val();
					if( $("#First_Option").val() == '')
					{
						var str = "Select "+ fldName.replace("_", " ");
						 $("#First_Option").val(str);
					}
					if( $("#Option_List").val() == '' )
					{
						var str = fldName.replace("_", "");
						var str = str.charAt(0).toLowerCase() + str.slice(1)+"List";
						$("#Option_List").val(str.replace(" ", ""));
					}
				}					
			});
		});
		</script>
		<?php
		
		} else {
			showLogoutMsg( false , is_user_logged_in());
		}
		

}



add_shortcode("CustomPagePage", "CustomPagePage");
function CustomPagePage($atts)
{
	if ( is_user_logged_in()) {
		global $wp;
		global $wpdb;
		global $jsData;
		global $field;
		global $fieldValue;
		global $errorMsg;

		extract($atts);
		extract($jsData);
		$pagename = strtolower($pagename);
		$nonceField = $pagename . "_action";
		$returnSubmitValue = '';
		$isSuccess = true;
		$classUI = new classUI();
		$classMysql = new classMysql();
		$classAction = new classAction();
		$classValidator = new classValidator();
		$classDate = new classDate();
		$field['fieldData']['pageName'] = $pagename;
        $field['fieldData']['pageNm'] = $pagenm;
		$user = wp_get_current_user();
		$role = (array) $user->roles; $roleKey = key($role);
		$field['fieldData']['currentId'] = $user->ID;
		$field['fieldData']['currentRole'] =  $role[$roleKey];
		$field['fieldData']['currentAction'] =  array( 'ADD' ,'UPDATE','TRASH','RESTORE','VIEW','DELETE');
		$field['fieldData']['errorPrefix'] = isset( $errorprefix ) ? $errorprefix : "Default Error";
		$field['fieldData']['reportheadername'] = isset( $reportheadername )  ? $reportheadername : "Default Form Name";
		$field['fieldData']['formheadername'] = isset( $formheadername )  ? $formheadername : "Default Form Name";
		$field['fieldData']['nonceField'] = $pagename . "_action";
		$field['fieldData']['insertJsonData'] ="NO";
		$field['fieldData']['uniqueField'] = array("Page_Name" => "Page_Name");
		$field['fieldData']['dontChange'] = array("" => "");


      //  $field['uniqueField'] = array("Page_Name" => "Page_Name");
       // $field['dontChange'] = array("" => "");
		$jsData['Before_CallBack_Check'] = array("" => "Select Option", "YES"=>"YES", "NO"=>"NO");
		$Action = 'ADD';
		$field['1']['field']['ID']['ID'] = array(
            'fieldType' => "input",
            "fieldValue" => array(
				'type' => "hidden",
				"name" => "ID",
				"id" => "ID",
            ),
        );
		$field['1']['field']['Refresh_Type']['Refresh_Type'] = array(
            'fieldType' => "input",
            "fieldValue" => array(
				'type' => "hidden",
				"name" => "Refresh_Type",
				"id" => "Refresh_Type",
            ),
        );
		$field['1']['field']['Page_Id']['Page_Id'] = array(
            'fieldType' => "input",
            "fieldValue" => array(
				'type' => "hidden",
				"name" => "Page_Id",
				"id" => "Page_Id",
            ),
        );
		$field['1']['field']['Page_Name']['Page_Name'] = array(
            'fieldType' => "input",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
            "rowOpen" => true,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
                'required' => true,
				'type' => "text",
				"maxlength" => 100,
                "class" => "form-control",
                "autofocus" => "autofocus",
				"name" => "Page_Name",
				"id" => "Page_Name",
            ),
        );
		$field['1']['field']['DBTbl_Name']['DBTbl_Name'] = array(
            'fieldType' => "input",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
				'type' => "text",
				"maxlength" => 100,
                "class" => "form-control",
				"name" => "DBTbl_Name",
				"id" => "DBTbl_Name",
            ),
        );
		
		$field['1']['field']['Error_Prefix']['Error_Prefix'] = array(
            'fieldType' => "input",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
                'required' => true,
				"type" => "text",
				"maxlength" => 100,
				"class" => "form-control",
				"name" => "Error_Prefix",
				"id" => "Error_Prefix",
            ),
        );
		$field['1']['field']['DataTable_Name']['DataTable_Name'] = array(
            'fieldType' => "input",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
                'required' => true,
				"type" => "text",
				"maxlength" => 100,
				"class" => "form-control",
				"name" => "DataTable_Name",
				"id" => "DataTable_Name",
            ),
        );
		$field['1']['field']['Form_Name']['Form_Name'] = array(
            'fieldType' => "input",
			"formGroup"=>"form-outline",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
            "fieldValue" => array(
                'required' => true,
				"type" => "text",
				"maxlength" => 100,
				"class" => "form-control",
				"name" => "Form_Name",
				"id" => "Form_Name",
            ),
        );
		$field['1']['field']['Form_Class']['Form_Class'] = array(
            'fieldType' => "input",
			"formGroup"=>"form-outline",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
            "fieldValue" => array(
				"type" => "text",
				"maxlength" => 100,
				"class" => "form-control",
				"name" => "Form_Class",
				"id" => "Form_Class",
            ),
        );
		
		$field['1']['field']['Search_Form_Name']['Search_Form_Name'] = array(
            'fieldType' => "input",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
				'required' => true,
				"type" => "text",
				"maxlength" => 100,
				"class" => "form-control",
				"name" => "Search_Form_Name",
				"id" => "Search_Form_Name",
            ),
        );
		$field['1']['field']['Search_Class']['Search_Class'] = array(
            'fieldType' => "input",
			"formGroup"=>"form-outline",
            "colClass" => $md3lg3,
            "spanWidth" => $spanWidth,
            "fieldValue" => array(
				"type" => "text",
				"maxlength" => 100,
				"class" => "form-control",
				"name" => "Search_Class",
				"id" => "Search_Class",
            ),
        );
		



		$field['1']['field']['Insert_JsonData']['Insert_JsonData'] = array(
            'fieldType' => "select",
            "colClass" => 'col-12 col-sm-4 col-md-6',
            "spanWidth" => $spanWidth,
			"formGroup"=>"form-outline",
			"optionList" => $jsData['Before_CallBack_Check'],
            "fieldValue" => array(
				"class" => "form-control",
				"name" => "Insert_JsonData",
				"id" => "Insert_JsonData",
            ),
        );
		
		$field['1']['field']['Fa_Icon']['Fa_Icon'] = array(
            'fieldType' => "input",
			"formGroup"=>"form-outline",
            "colClass" => 'col-12 col-sm-12 col-md-6',
            "spanWidth" => $spanWidth,
            "fieldValue" => array(
				"type" => "text",
				"maxlength" => 100,
				"class" => "form-control",
				"name" => "Fa_Icon",
				"id" => "Fa_Icon",
            ),
        );
		
		$field['1']['field']['ShortCode']['ShortCode'] = array(
            'fieldType' => "input",
            "colClass" => "col-12 col-sm-12",
            "spanWidth" => $spanWidth,
			 "rowClose" => true,
			"formGroup"=>"form-outline",
            "fieldValue" => array(
				"class" => "form-control",
				"type" => "text",
				"name" => "ShortCode",
				"id" => "ShortCode",
            ),
        );


		

		$field['1']['field']["ADD"]["ADD"] = array(
            "fieldType" => "input",
            "colClass" => 'col-6',
			 "rowOpen" => true,
            "formGroup" => "material",
            "fieldValue" => array(
                "type" => "submit",
				"name" => "ADD",
				"id" => "ADD",
                "class" => "form-control",
            ),
        );
        $field['1']['field']["REFRESH"]["REFRESH"] = array(
            "fieldType" => "input",
            "colClass" => 'col-6',
            "rowClose" => true,
            "formGroup" => "material",
            "fieldValue" => array(
                "type" => "submit",
				"name" => "REFRESH",
				"id" => "REFRESH",
                "class" => "form-control",
            ),
        );
        $field['2']['search']["SName"]["SName"] = array(
            "fieldType" => "input",
            "displayName" => "Page Name",
            "rowOpen" => true,
			"formGroup" => "form-outline",
            "colClass" => $md8lg8,
            "spanWidth" => $spanWidth,
            "fieldValue" => array(
				"name" => "SName",
				"id" => "SName",
                "type" => "text",
                "class" => "form-control",
            ),
        );
   
        $field['2']['search']['SearchData']['SearchData'] = array(
            "fieldType" => "input",
            "colClass" => $md4lg4." mt-auto",
            "rowClose" => true,
			"formGroup" => "form-outline",
            "fieldValue" => array(
                "type" => "submit",
				"name" => "SearchData",
				"id" => "SearchData",
                "class" => "form-control",
            ),
        );
		

	

		if (isset($_POST['SearchData'])) {
            $getExtraQry = '';
            $thisTbl = "{$wpdb->prefix}{$pagename}";
            $getExtraQry .= isset($_POST['SName']) ? $_POST['SName'] != '' ? "  AND  {$thisTbl}.Page_Name LIKE '%{$_POST['SName']}%' " : "" : "";
            $field['getQry'] = "select {$thisTbl}.*
                            FROM  {$thisTbl}
                            WHERE {$thisTbl}.isSys = 0
                                {$getExtraQry}
                            ORDER BY  {$thisTbl}.Page_Name";

            $field['tableCol'] = array(
                "Action" => array("value" => "ID", "type" => "copyeditdelete"),
                "Page_Name" => array("value" => "Page_Name", "type" => "text"),
				"DBTbl_Name" => array("value" => "DBTbl_Name", "type" => "text"),
                "Error_Prefix" => array("value" => "Error_Prefix", "type" => "text"),
				"Form_Name" => array("value" => "Form_Name", "type" => "text"),

            );
        }
        if (isset($_POST['ADD'])) {
			
			if (!wp_verify_nonce($_REQUEST[$field['fieldData']['nonceField']], -1)){
                $errorMsg[] = array("Failed security check", $_POST["submit"], false);
                $isSuccess = false;
            } else {
                $isSuccess = $classValidator->checkValidation();
            }
			if( $isSuccess ) 
			{
				//$isSuccess = $classValidator->checkPageName();
			}
            if ($isSuccess) {
				
				//$isCreated = true;
				
				$isCreated = createPageAsTable();
				
                if($isCreated)
				{
					$errorMsg[] = array("TABLE : {$_POST['DBTbl_Name']} has been successfully created", true);
				}
				else $errorMsg[] = array("Failed To CREATE TABLE : {$_POST['DBTbl_Name']}", false);
				
				$_POST['ShortCode'] = trim($_POST['ShortCode']);
				$isPosted = true;
				if( $_POST['ADD'] != 'DELETE' )
					$isPosted = createPageAsPost($user->ID);
					 
				
				
				if($isPosted)
				{
					//$classAction->tableToMaster();
					 $isSuccess = $classAction->tableToMaster();
					if($isSuccess && isset($_GET['multiTasking']))
					{
						echo '<script>window.close();</script>';
					}
				}
				
				
            }

        }
        if (isset($errorMsg)) {
            $returnSubmitValue = alert_display($errorMsg);
        }

        if (isset($_POST['ADD'])) {
            if (strlen($returnSubmitValue) < 1) {
                if (isset($_POST)) {unset($_POST);}
				
            }
        }
        if (isset($_GET)) {extract($_GET);}
        if (isset($_POST)) {extract($_POST);}

        if ( isset($_GET['logID']) || isset($_POST['copyForm']) || isset($_POST['trashForm']) || isset($_POST['editForm']) || isset($_POST['deleteForm']) || isset($_POST['restoreForm'])) 
		{
			 $classMysql->extractData();
           
			if( (int)$_POST['Page_Id'] == 0 || $_POST['Page_Id'] == '')
			{
				$myposts = $wpdb->get_results( "SELECT ID,post_content FROM $wpdb->posts WHERE post_type='page' AND post_title = '".wp_strip_all_tags( $_POST['Page_Name'] )."'", "ARRAY_A" );
				if( count ( $myposts ) > 0)
				{ 
					$_POST['Page_Id'] = (int)$myposts[0]['ID'];
					if($_POST['Page_Id'] > 0)
					{
						if( trim($_POST['ShortCode']) == '')
						{
							$myShortCode= trim($myposts[0]['post_content'], '<!-- wp:shortcode -->');
							$myShortCode= trim($myShortCode, '<!-- /wp:shortcode -->');
							$_POST['ShortCode'] = $myShortCode;
						}
					}
				}
			}
			 extract($_POST);
			//print_r($_POST);

        }

        //$Admit_Date = isset( $Admit_Date ) ? $Admit_Date : date("Y-m-d");
        //$Admit_Time = isset( $Admit_Time ) ? $Admit_Time : date("H:i");

       // $classUI->setField();

       echo $classUI->echoForm();

       echo $classUI->searchFormAndData();
	   ?>
	   <script>
		var _rulesString = {};
		var _messagesString = {};
		$(function () {
			$('body').on('blur', '#Page_Name',function () {
				var thisVal = $(this).val();
				var add = $.trim($("#ADD").val());
				if( thisVal != '' )
				{
					if( $("#DBTbl_Name").val() == '' && add == 'ADD' )$("#DBTbl_Name").val(thisVal.replace(" ", "_").replace(" ", "_").toLowerCase());
					if( $("#Error_Prefix").val() == '' )$("#Error_Prefix").val(thisVal);
					if( $("#DataTable_Name").val() == '' ) $("#DataTable_Name").val(thisVal+ " Data");
					if( $("#Form_Name").val() == '' ) $("#Form_Name").val(thisVal);
					if( $("#Search_Form_Name").val() == '' ) $("#Search_Form_Name").val("Get "+thisVal+" Data");
				}
			});
			
			$('body').on('blur', '#DBTbl_Name',function () {
				var thisVal = $.trim($(this).val());
				var pageName = $.trim($("#Page_Name").val());
				var shortCodeName = pageName.replace(" ","").replace(" ","");
				var shortCode = $.trim($("#ShortCode").val());
				var add = $.trim($("#ADD").val());
				if( add == 'ADD' && pageName != '' && shortCode == '' )
				{
					if( thisVal == '' ) var newShortCode = "["+shortCodeName+"Page pagenm='"+pageName+"' pagename='"+pageName+"' ]";
					else var newShortCode = "["+shortCodeName+"Page pagenm='"+pageName+"' pagename='"+thisVal+"' ]";
					$("#ShortCode").val(newShortCode);
				}
			})
			
		});
		</script>
		<?php
		} else {
			showLogoutMsg( false , is_user_logged_in());
		}

}

function alterIndex()
{
	global $wpdb;
	$isIndex = true;
	$thisTable ='';
	$oldTableQry = $wpdb->get_results("SELECT DBTbl_Name FROM {$wpdb->prefix}pagename WHERE ID='{$_POST['Page_Name']}'","ARRAY_A");
	if( count($oldTableQry) == 1 )
	{
		$thisTable = $oldTableQry[0]['DBTbl_Name'];
		if( $thisTable != '')
		{
			$showIndex = $wpdb->get_results("SHOW INDEX FROM {$wpdb->prefix}{$thisTable}","ARRAY_A");
			
			foreach( $showIndex as $key => $value )
			{
				foreach ( $value as $k => $v )
				{
					if( strtolower( $k ) == 'key_name')
					{
						if( $v == $_POST['Field_Name'] )
						{
							$isIndex = false;
							break;
						}
					}
				}
				if( !$isIndex )	
					break;
			}
		}
	}
	
		
	
	if( $isIndex && $thisTable != '')
	{
		
		//echo "CREATE INDEX {$_POST['Field_Name']} ON {$wpdb->prefix}{$thisTable} ({$_POST['Field_Name']})"; exit;
		$return = $wpdb->query("CREATE INDEX {$_POST['Field_Name']} ON {$wpdb->prefix}{$thisTable} ({$_POST['Field_Name']})");
		return $return;
		
	}

	else return true;
}
function alterTable()
{
	global $wpdb;
	global $errorMsg;
	$isContinue = true;
	$creatQuery = '';
	if( $_POST['ADD'] == 'ADD' || $_POST['ADD'] == 'UPDATE')
	{
		$fieldLength = '' ;
		if( strlen( $_POST['dv'] ) >  4 )
		{
			if( $_POST['Field_Name'] != 'ID' && $_POST['Field_Name'] != 'Refresh_Type' && 	 
			$_POST['Field_Group'] == 'field' )
			{
				$fieldLength = stripslashes ( $_POST['dv'] ); 
			}
		}
		
		if( $fieldLength != '')
		{
			$newFieldLength = 0;
			$fieldLengthNew = explode( "(" , $fieldLength );
			if( isset ( $fieldLengthNew[1] ) )
			{
				$fieldLengthNew = explode( ")" , $fieldLengthNew[1] );
				$newFieldLength = (int)$fieldLengthNew[0];
			}
			
			$getPageName = $wpdb->get_results("SELECT DBTbl_Name FROM {$wpdb->prefix}pagename
												WHERE ID='{$_POST['Page_Name']}' ","ARRAY_A");

			$getPageName = $getPageName[0]['DBTbl_Name'];
			if($_POST['ADD'] == 'UPDATE')
			{
				$getOldName = $wpdb->get_results("SELECT Field_Name FROM {$wpdb->prefix}pagenamefield
													WHERE ID='{$_POST['ID']}' ","ARRAY_A");
				$getOldName	= $getOldName[0]['Field_Name'];

				$checkColumn = $wpdb->get_results("DESC `{$wpdb->prefix}{$getPageName}`", "ARRAY_A");
				$isExist = false;
				foreach( $checkColumn as $key => $value )
				{
					if( strtolower($value['Field']) == strtolower($getOldName ) ) { $isExist = true;  }
				}
				if($isExist)
				{
					if( !preg_match('/text /i',strtolower ( $fieldLength ) ) &&  !preg_match('/date /i',strtolower ( $fieldLength ) ) && !preg_match('/datetime /i',strtolower ( $fieldLength ) ) )
					{
						$checkLengthQry  = $wpdb->get_results("SELECT MAX(LENGTH(`{$getOldName}`)) AS fldLen  FROM {$wpdb->prefix}{$getPageName} LIMIT 1", "ARRAY_A");
						$currentFieldlenth = $checkLengthQry[0]['fldLen'];
						if($newFieldLength < $currentFieldlenth )
						{	
							$errorMsg[] = array("This column has {$currentFieldlenth} character long string. You trying to set it to {$newFieldLength}" , false);
							return false;
							$isContinue = false;
						}
					}
				
					$creatQuery = "ALTER TABLE `{$wpdb->prefix}{$getPageName}` CHANGE `{$getOldName}` `{$_POST['Field_Name']}` {$fieldLength};";
				}
				else $creatQuery = "ALTER TABLE `{$wpdb->prefix}{$getPageName}` ADD `{$_POST['Field_Name']}` {$fieldLength}";
			}
			else if($_POST['ADD'] == 'ADD')
			{
				//echo $_POST['ADD'];
				$checkColumn = $wpdb->get_results("DESC `{$wpdb->prefix}{$getPageName}`", "ARRAY_A");
				$isExist = false;
				foreach( $checkColumn as $key => $value )
				{ 
					if( strtolower($value['Field']) == strtolower($_POST['Field_Name'] ) ) { $isExist = true;  }
				}
				if($isExist)
				{
					if( !preg_match('/text /i',strtolower ( $fieldLength ) ) )
					{
						$checkLengthQry  = $wpdb->get_results("SELECT MAX(LENGTH(`{$_POST['Field_Name']}`)) AS fldLen  FROM {$wpdb->prefix}{$getPageName} LIMIT 1", "ARRAY_A");
						$currentFieldlenth = $checkLengthQry[0]['fldLen'];
						if($newFieldLength < $currentFieldlenth )
						{	
							$errorMsg[] = array("This column has {$currentFieldlenth} character long string. You trying to set it to {$newFieldLength}" , false);
							return false;
							$isContinue = false;
						}
					}
					$creatQuery = "ALTER TABLE `{$wpdb->prefix}{$getPageName}` CHANGE `{$_POST['Field_Name']}` `{$_POST['Field_Name']}` {$fieldLength};";
				}
				else $creatQuery = "ALTER TABLE `{$wpdb->prefix}{$getPageName}` ADD `{$_POST['Field_Name']}` {$fieldLength}";
			} 
			//echo $creatQuery;
			 
		}
		
		
	}
	
	if( $creatQuery != '' ){
		$return =  $wpdb->query($creatQuery);
		//echo $wpdb->last_error; 
		//echo $wpdb->print_error();
		return $return;
	}
	else return true;
}
function createPageAsTable()
{
	global $wpdb;
	$isReturn = true;
	$creatQuery = array();
	if( trim($_POST['DBTbl_Name']) != '')
	{
		if($_POST['ADD'] == 'ADD') 
		{
			$creatQuery[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}{$_POST['DBTbl_Name']} (
				ID BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
				Refresh_Type varchar(20) NOT NULL DEFAULT '', 
				isTrash tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
				isSys tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
				userId BIGINT(20) UNSIGNED NOT NULL,
				updated DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
				added DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
				INDEX (isTrash),
				INDEX (isSys),
				INDEX (userId)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;";
			
		}else 
		{
			$oldTableQry = $wpdb->get_results("SELECT DBTbl_Name FROM {$wpdb->prefix}pagename WHERE ID='{$_POST['ID']}'","ARRAY_A");
			if( count($oldTableQry) == 1 )
			{
				$oldTableName = $oldTableQry[0]['DBTbl_Name'];
				if( $oldTableName != $_POST['DBTbl_Name'])
					$creatQuery[] = "ALTER TABLE {$wpdb->prefix}{$oldTableName} RENAME TO {$wpdb->prefix}{$_POST['DBTbl_Name']};";
			}
			
			
		}
		
	}
	
	foreach( $creatQuery as $val )
	{
		//echo $val;
		$result = $wpdb->query($val);
		if(!$result) $isReturn = false;
	}
	return $isReturn;
		
}

function createPageAsPost($id)
{
	global $wpdb;
	global $errorMsg;
	$isReturn = true;
	
		if($_POST['ADD'] == 'ADD')
		{
			$my_post = array(
			  'post_title'    => wp_strip_all_tags( $_POST['Page_Name'] ),
			  'post_content'  =>  $_POST['ShortCode'] , 
			  'post_name'   => sanitize_title_with_dashes($_POST['Page_Name']),
			  'post_status'   => 'publish',
			  'post_type'   => 'page',
			  'post_author'   => $id,
			  'comment_status'   => 'closed',
			  'ping_status'   => 'closed',
			  'ping_type'   => 'closed',
			  
			);
			 
			$postId = wp_insert_post( $my_post );
			if($postId)
			{	
				$_POST['Page_Id'] = $postId;
				 $errorMsg[] = array("page created : {$_POST['Page_Name']}", true);
				 $postId++;
				 
				 update_post_meta( $postId, '_nav_menu_role', 'in' );
				 update_post_meta( $postId, '_shown_nav_menu', 'shown' );
				 update_post_meta( $postId, '_shown_nav_role', 'administrator' );
				 update_post_meta( $postId, '_nav_menu_action', array( 'ADD' ,'UPDATE','TRASH','RESTORE','VIEW','DELETE'));
				if( trim($_POST['Fa_Icon']) != '' )
					update_post_meta( $postId, '_icon', 'fa-trash' );
			}
			 else 
				  $errorMsg[] = array("page [post-type] created failed : {$_POST['Page_Name']}", false);
				return $postId;
			
		}else 
		{
			if ( (int)$_POST['Page_Id'] > 0)
			{
				$my_post = array(
				  'ID'           => $_POST['Page_Id'],
				  'post_title'    => wp_strip_all_tags( $_POST['Page_Name'] ),
				  'post_content'  => $_POST['ShortCode'],
				  'post_name'   => sanitize_title_with_dashes($_POST['Page_Name']),
				 

			  );
			  if( trim($_POST['Fa_Icon']) != '' )
				update_post_meta( $_POST['Page_Id'], '_icon', $_POST['Fa_Icon'] );
			 else 	
				 delete_post_meta( $_POST['Page_Id'], '_icon' );
			 
			  $postId = wp_update_post( $my_post );
			  if($postId)
			  {
				 $errorMsg[] = array("page [post-type] updated : {$_POST['Page_Name']}", true);
			  }
			 else 
			 {
				  $errorMsg[] = array("page [post-type] updated failed : {$_POST['Page_Name']}", false);
				
			 }
				return $postId;
			}else
			{ 
				  $errorMsg[] = array("page [post-type] updated failed : undefined POST ID", false);
				return true;
			}
		}
		
		return $isReturn;
		
	
	
	
		
}