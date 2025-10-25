<?PHP
class classUI{
	
	public function getTableField()
	{
		global $wpdb;
		global $field;
		global $jsData;
		$pageName = "{$wpdb->prefix}pagename";
		$pageField = "{$wpdb->prefix}pagenamefield";
		$getField = $wpdb->get_results("SELECT
											
											{$pageName}.Error_Prefix,
											{$pageName}.DataTable_Name,
											{$pageName}.Form_Name,
											{$pageName}.Search_Form_Name,
											{$pageName}.Insert_JsonData,
											{$pageName}.Form_Class,
											{$pageName}.Search_Class,
											{$pageField}.*
										FROM {$pageName}
											LEFT JOIN {$pageField} ON ( {$pageField}.Page_Name = {$pageName}.ID ) 
										WHERE  {$pageField}.isTrash = 0 AND {$pageName}.isTrash = 0 AND {$pageName}.Page_Name = '{$field['fieldData']['pageNm']}'
										ORDER BY {$pageField}.Group_Order , {$pageField}.Field_Order , {$pageField}.Field_Group, {$pageField}.ID
										","ARRAY_A");
		$i = 0;
		$ui = 0;
		if( count( $getField) > 0 )
		{

			
			foreach( $getField as $key => $value )
			{
				// echo "<pre>";
				// var_dump($value);
				// echo "</pre>";
				if($i == 0)
				{
					$field['fieldData']['errorPrefix'] = $value['Error_Prefix'];
					
					$field['fieldData']['datatableHeaderName'] = $value['DataTable_Name'];
					$field['fieldData']['formheadername'] = $value['Form_Name'];
					$field['fieldData']['reportheadername'] = $value['Search_Form_Name'];
					$field['fieldData']['insertJsonData'] = $value['Insert_JsonData'];
					$field['fieldData']['formClass'] = $value['Form_Class'];
					$field['fieldData']['searchClass'] = $value['Search_Class'];
					
					
				}
				if( strlen( $value['Shown_After_Action'] ) > 1 )$field['fieldData']['shownAfterAction'][$value['Field_Name']] = $value['Field_Name'];
				if( strlen( $value['Set_After_Action'] ) > 1 )$field['fieldData']['setAfterAction'][$value['Field_Name']] = $value['Field_Name'];

				if( strlen( $value['DBTbl_Key'] ) > 1 )
					$field[$value['Group_Order']][$value['Field_Group']][$value['Group_Field_Name']][$value['Field_Name']]['tableKey'] = $value['DBTbl_Key'];
				if( strlen( $value['DBTbl_Name'] ) > 1 )
					$field[$value['Group_Order']][$value['Field_Group']][$value['Group_Field_Name']][$value['Field_Name']]['tableName'] = $value['DBTbl_Name'];

				//tableName

				if( $value['Get_Query'] == 'equalOne' ) $field['fieldData']['equalOne'] = $value['Field_Name'];
				else if( $value['Get_Query'] == 'equalTwo' ) $field['fieldData']['equalTwo'] = $value['Field_Name'];
				else if( $value['Get_Query'] == 'equalThree' ) $field['fieldData']['equalThree'] = $value['Field_Name'];
				else if( $value['Get_Query'] == 'equalFour' ) $field['fieldData']['equalFour'] = $value['Field_Name'];
				else if( $value['Get_Query'] == 'equalFive' ) $field['fieldData']['equalFive'] = $value['Field_Name'];
				else if( $value['Get_Query'] == 'equalSix' ) $field['fieldData']['equalSix'] = $value['Field_Name'];
				else if( $value['Get_Query'] == 'likeOne' ) $field['fieldData']['likeOne'] = $value['Field_Name'];
				else if( $value['Get_Query'] == 'likeTwo' ) $field['fieldData']['likeTwo'] = $value['Field_Name'];
				else if( $value['Get_Query'] == 'dateOne' ) $field['fieldData']['dateOne'] = $value['Field_Name'];
				else if( $value['Get_Query'] == 'dateTwo' ) $field['fieldData']['dateTwo'] = $value['Field_Name'];
				else if( $value['Get_Query'] == 'multiOne' ) $field['fieldData']['multiOne'] = $value['Field_Name'];
				else if( $value['Get_Query'] == 'multiTwo' ) $field['fieldData']['multiTwo'] = $value['Field_Name'];
					
					
				if( strlen ( $value['Unique_Field'] ) > 1 )
				{ 
					if( $ui > 0 ) $field['fieldData']['uniqueField'][$ui] = "AND";
					$field['fieldData']['uniqueField'][$value['Group_Field_Name']] = $value['Group_Field_Name'];
					$ui++;
				}
					
				
				if( strlen ( $value['Block_On_Update'] ) > 1)
				{
					$field['fieldData']['dontChange'][$value['Group_Field_Name']] = $value['Group_Field_Name'];
				}
				
				if(  strlen ( $value['Block_On_Copy'] ) > 1 )
				{
					$field['fieldData']['copyChange'][$value['Group_Field_Name']] = $value['Group_Field_Name'];
				}
			

				if ( $value['Field_Order'] == 1  || $value['Field_Order'] == '1' )
				{
					$field[$value['Group_Order']]['Group_Prefix'] = stripslashes($value['Group_Prefix']);
					$field[$value['Group_Order']]['Group_Suffix'] = stripslashes($value['Group_Suffix']);
				}
				

				$field[$value['Group_Order']][$value['Field_Group']][$value['Group_Field_Name']][$value['Field_Name']]['fieldType'] = $value['Field_Type'];
				if( strlen( $value['Display_Name'] ) > 1 )$field[$value['Group_Order']][$value['Field_Group']][$value['Group_Field_Name']][$value['Field_Name']]['displayName'] = $value['Display_Name'];
				$field[$value['Group_Order']][$value['Field_Group']][$value['Group_Field_Name']][$value['Field_Name']]['colClass'] = $value['Col_Class'];
				if( strlen( $value['Row_Open'] ) > 1 )$field[$value['Group_Order']][$value['Field_Group']][$value['Group_Field_Name']][$value['Field_Name']]['rowOpen'] = $value['Row_Open'];
				if( strlen( $value['Row_Close'] ) > 1 )$field[$value['Group_Order']][$value['Field_Group']][$value['Group_Field_Name']][$value['Field_Name']]['rowClose'] = $value['Row_Close'];
				

				
					$field[$value['Group_Order']][$value['Field_Group']][$value['Group_Field_Name']][$value['Field_Name']]['spanWidth'] = $value['Label_Width'];
				
				$field[$value['Group_Order']][$value['Field_Group']][$value['Group_Field_Name']][$value['Field_Name']]['formGroup'] = $value['Form_Group'];
				
				$value['class'] = "form-control ".$value['class'];

				$field[$value['Group_Order']][$value['Field_Group']][$value['Group_Field_Name']][$value['Field_Name']]['fieldValue']['class'] = $value['class'];
				if( strlen( $value['readonly'] ) > 1 )$field[$value['Group_Order']][$value['Field_Group']][$value['Group_Field_Name']][$value['Field_Name']]['fieldValue']['readonly'] = 'readonly';
				if( strlen( $value['required'] ) > 1 )$field[$value['Group_Order']][$value['Field_Group']][$value['Group_Field_Name']][$value['Field_Name']]['fieldValue']['required'] = $value['required'];
				if( strlen( $value['autofocus'] ) > 1 )$field[$value['Group_Order']][$value['Field_Group']][$value['Group_Field_Name']][$value['Field_Name']]['fieldValue']['autofocus'] = $value['autofocus'];
				if( strlen( $value['placeholder'] ) > 1 )$field[$value['Group_Order']][$value['Field_Group']][$value['Group_Field_Name']][$value['Field_Name']]['fieldValue']['placeholder'] = $value['placeholder'];
				if( strlen( $value['show_message'] ) > 1 )$field[$value['Group_Order']][$value['Field_Group']][$value['Group_Field_Name']][$value['Field_Name']]['fieldValue']['showmessage'] = $value['show_message'];
				$field[$value['Group_Order']][$value['Field_Group']][$value['Group_Field_Name']][$value['Field_Name']]['fieldValue']['name'] = $value['Field_Name'];
				$field[$value['Group_Order']][$value['Field_Group']][$value['Group_Field_Name']][$value['Field_Name']]['fieldValue']['id'] = $value['Field_Name'];
					

				if( $value['Field_Type'] == 'datalist' || $value['Field_Type'] == 'select' || $value['Field_Type'] == 'radio' || $value['Field_Type'] == 'checkbox')
				{
					if( strlen( $value['Option_List'] ) > 1 )
					{

						if( isset ( $jsData[$value['Option_List']] ) )$field[$value['Group_Order']][$value['Field_Group']][$value['Group_Field_Name']][$value['Field_Name']]['optionList'] = $jsData[$value['Option_List']];
					}
					if( strlen( $value['First_Option'] ) > 1 )$field[$value['Group_Order']][$value['Field_Group']][$value['Group_Field_Name']][$value['Field_Name']]['firstOption'] = $value['First_Option'];
					if(  $value['type'] == 'multiple' )$field[$value['Group_Order']][$value['Field_Group']][$value['Group_Field_Name']][$value['Field_Name']]['fieldValue']['multiple'] = $value['type'];
				}
				else if( $value['Field_Type'] == 'input')
				{
					$field[$value['Group_Order']][$value['Field_Group']][$value['Group_Field_Name']][$value['Field_Name']]['fieldValue']['type'] = $value['type'];
					
					if( strlen( $value['maxlength'] ) > 0 )$field[$value['Group_Order']][$value['Field_Group']][$value['Group_Field_Name']][$value['Field_Name']]['fieldValue']['maxlength'] = $value['maxlength'];
					if( strlen( $value['minlength'] ) > 0 )$field[$value['Group_Order']][$value['Field_Group']][$value['Group_Field_Name']][$value['Field_Name']]['fieldValue']['minlength'] = $value['minlength'];
				
					if( strlen( $value['max'] ) > 0 )$field[$value['Group_Order']][$value['Field_Group']][$value['Group_Field_Name']][$value['Field_Name']]['fieldValue']['max'] = $value['max'];
					if( strlen( $value['min'] ) > 0 )$field[$value['Group_Order']][$value['Field_Group']][$value['Group_Field_Name']][$value['Field_Name']]['fieldValue']['min'] = $value['min'];
					if( strlen( $value['step'] ) > 0 )$field[$value['Group_Order']][$value['Field_Group']][$value['Group_Field_Name']][$value['Field_Name']]['fieldValue']['step'] = $value['step'];
					
					
				}
				else if( $value['Field_Type'] == 'textarea')
				{
					$field[$value['Group_Order']][$value['Field_Group']][$value['Group_Field_Name']][$value['Field_Name']]['fieldValue']['rows'] = $value['rows'];
				}
		
				$i++;
			}
		}else
		{
			$this->noDataFound("No field found for this page");
			die();
		}
		//print_r($field);
	}

	public function formSearchQuery($thisTbl)
	{
		global $field;
		$getExtraQry = '';
		// $getExtraQry .= isset($_POST['Search_LikeOne']) ? ( $_POST['Search_LikeOne'] != '' ? " AND {$thisTbl}.{$field['fieldData']['likeOne']}  LIKE '%{$_POST['Search_LikeOne']}%' " : "" ) : "";
		// $getExtraQry .= isset($_POST['Search_LikeTwo']) ? ( $_POST['Search_LikeTwo'] != '' ? " AND {$thisTbl}.{$field['fieldData']['likeTwo']}  LIKE '%{$_POST['Search_LikeTwo']}%' " : "" ) : "";
		
		if( isset( $_POST['Search_LikeOne'] ) ) 
		{
			if( $_POST['Search_LikeOne'] != '' )
			{
				$thisData = explode(" ",  $_POST['Search_LikeOne'] );
				$thisImplode = implode("%' AND {$thisTbl}.{$field['fieldData']['likeOne']} LIKE '%" , $thisData );
				$getExtraQry .= " AND ( {$thisTbl}.{$field['fieldData']['likeOne']} LIKE '%{$thisImplode}%' ) ";
			}
		}
		if( isset( $_POST['Search_LikeTwo'] ) ) 
		{
			if( $_POST['Search_LikeTwo'] != '' )
			{
				$thisData = explode(" ",  $_POST['Search_LikeTwo'] );
				$thisImplode = implode("%' AND {$thisTbl}.{$field['fieldData']['likeTwo']} LIKE '%" , $thisData );
				$getExtraQry .= " AND ( {$thisTbl}.{$field['fieldData']['likeTwo']} LIKE '%{$thisImplode}%' ) ";
			}
		}
		$getExtraQry .= isset($_POST['Search_EqualOne']) ? ( $_POST['Search_EqualOne'] != '' ? " AND {$thisTbl}.{$field['fieldData']['equalOne']} = '{$_POST['Search_EqualOne']}' " : "" ) : "";
		$getExtraQry .= isset($_POST['Search_EqualTwo']) ? ( $_POST['Search_EqualTwo'] != '' ? " AND {$thisTbl}.{$field['fieldData']['equalTwo']} = '{$_POST['Search_EqualTwo']}' " : "" ) : "";
		$getExtraQry .= isset($_POST['Search_EqualThree']) ? ( $_POST['Search_EqualThree'] != '' ? " AND {$thisTbl}.{$field['fieldData']['equalThree']} = '{$_POST['Search_EqualThree']}' " : "" ) : "";
		$getExtraQry .= isset($_POST['Search_EqualFour']) ? ( $_POST['Search_EqualFour'] != '' ? " AND {$thisTbl}.{$field['fieldData']['equalFour']} = '{$_POST['Search_EqualFour']}' " : "" ) : "";
		$getExtraQry .= isset($_POST['Search_EqualFive']) ? ( $_POST['Search_EqualFive'] != '' ? " AND {$thisTbl}.{$field['fieldData']['equalFive']} = '{$_POST['Search_EqualFive']}' " : "" ) : "";
		$getExtraQry .= isset($_POST['Search_EqualSix']) ? ( $_POST['Search_EqualSix'] != '' ? " AND {$thisTbl}.{$field['fieldData']['equalSix']} = '{$_POST['Search_EqualSix']}' " : "" ) : "";
		$getExtraQry .= isset($_POST['Search_DateOne']) ? ( $_POST['Search_DateOne'] != '' ? " AND {$thisTbl}.{$field['fieldData']['dateOne']}  = '{$_POST['Search_DateOne']}' " : "" ) : "";
		$getExtraQry .= isset($_POST['Search_DateTwo']) ? ( $_POST['Search_DateTwo'] != '' ? " AND {$thisTbl}.{$field['fieldData']['dateTwo']}  = '{$_POST['Search_DateTwo']}' " : "" ) : "";
		if ( isset($_POST['Search_MultiOne'] ) )
		{
			 if( $_POST['Search_MultiOne'] != '') {
				 $dateRange = explode(" - ", $_POST['Search_MultiOne']);
				 $fromDate = implode("-", array_reverse(explode("-", $dateRange[0])));
				 $toDate = implode("-", array_reverse(explode("-", $dateRange[1])));
				 $getExtraQry .= " AND {$thisTbl}.{$field['fieldData']['multiOne']}  >= '{$fromDate}' AND {$thisTbl}.{$field['fieldData']['multiOne']}  <= '{$toDate}' ";
			 }
		 }
		 if ( isset($_POST['Search_MultiTwo'] ) )
		{
			 if( $_POST['Search_MultiTwo'] != '') {
				 $dateRange = explode(" - ", $_POST['Search_MultiTwo']);
				 $fromDate = implode("-", array_reverse(explode("-", $dateRange[0])));
				 $toDate = implode("-", array_reverse(explode("-", $dateRange[1])));
				 $getExtraQry .= " AND {$thisTbl}.{$field['fieldData']['multiTwo']}  >= '{$fromDate}' AND {$thisTbl}.{$field['fieldData']['multiTwo']}  <= '{$toDate}' ";
			 }
		 }
		 //$getExtraQry .= (strtolower($field['fieldData']['currentRole']) != 'administrator' || strtolower($field['fieldData']['currentRole']) != 'admin') ? " AND {$thisTbl}.userId = '{$field['fieldData']['currentId']}' " : "";
		 return $getExtraQry;
	} 
	public function echoForm($modal='yes')
	{
		global $field;
		$formClass = '';
		if( isset ( $field['fieldData']['formClass'] ) )
			if( $field['fieldData']['formClass'] != '' )
				$formClass = $field['fieldData']['formClass'];
			
		$this->startSubmitForm("submitPageForm", $field['fieldData']['formheadername'],$formClass,$modal);
		wp_nonce_field(-1, $field['fieldData']['nonceField'], true, true);
		$this->showAllField('not-search');
		$this->endSubmitForm($modal);
   }
	
	
	public function searchFormAndDataTable($modal = 'no')
	{
		global $field;
		global $wpdb;
		$formClass = '';
		if( isset ( $field['fieldData']['searchClass'] ) )
			if( $field['fieldData']['searchClass'] != '' )
				$formClass = $field['fieldData']['searchClass'];
		$tableArr = array();
		if( in_array( 'VIEW', $field['fieldData']['currentAction'] )   ) 
		{
			if( isset( $_GET['multiTasking'] ) == false )
			{
				$this->startSubmitForm("reportPageForm", $field['fieldData']['reportheadername'],$formClass, $modal);
				$this->showAllField("search");
				$this->endSubmitForm($modal); 

				if( isset ( $_POST['SearchData'] ) ) 
				{	
					$countShowDataRow = 1;
					$getQry = $wpdb->get_results($field['getQry'], "ARRAY_A");
					
					foreach ($getQry as $key => $value) 
					{
						$desData = array();
						if( isset( $field['tableCol']['jsonData']) &&  isset($value['jsonData']))
						{
							$desData = json_decode($value['jsonData'], true);
							foreach( $field['unsetJsonData'] as $jsonKey ) 
							{
								if( isset( $desData[$jsonKey] ) ) unset( $desData[$jsonKey] ); 
							}
						}
						foreach ($field['tableCol'] as $k => $v) {
							if($k != 'jsonData')
							{
								$tableArr[$countShowDataRow][$k] = array("value" => isset( $value[$v["value"]] ) ? $value[$v["value"]] :"", "type" => $v["type"]);
								if( isset( $v["id"] ) )$tableArr[$countShowDataRow][$k]['id'] =  $v["id"];
							}
						}
						
						foreach($desData as $k=>$v) 
						{
							if ( !isset( $field['tableCol'][$k] ) ) $field['tableCol'][$k] = array("value" => $k, "type" => "text");
							$tableArr[$countShowDataRow][$k] = array("value" => $v, "type" => "text");
							
						}
						$countShowDataRow++;
					}
					
					
					?>
					<div class="row  mt-3" >
						<div class="col-12">
						<?php 
							if( count($tableArr) > 0 )
								$this->showReports($tableArr, "reportTable01", array()) ;
							else  $this->noDataFound("No Data Found...");
						?>
						</div>
					</div>
				<?php
				}
			}
    
		} else $this->noDataFound("View is not allowed for you...");

	}
	public function searchFormAndData($modal = 'no')
	{
		global $field;
		global $wpdb;
		$formClass = '';
		if( isset ( $field['fieldData']['searchClass'] ) )
			if( $field['fieldData']['searchClass'] != '' )
				$formClass = $field['fieldData']['searchClass'];
		$tableArr = array();
		if( in_array( 'VIEW', $field['fieldData']['currentAction'] ) ) 
		{
			if( isset( $_GET['multiTasking'] ) == false )
			{
				$this->startSubmitForm("reportPageForm", $field['fieldData']['reportheadername'],$formClass, $modal);
				$this->showAllField("search");
				$this->endSubmitForm($modal);

				if( isset ( $_POST['SearchData'] ) ) 
				{	
					$countShowDataRow = 1;
					$getQry = $wpdb->get_results($field['getQry'], "ARRAY_A");
					foreach ($getQry as $key => $value) {
						foreach ($field['tableCol'] as $k => $v) {
							$tableArr[$countShowDataRow][$k] = array("value" => $value[$v["value"]], "type" => $v["type"]);
							
						}
						$tableArr[$countShowDataRow]['isTrash'] = array("value" => $value["isTrash"], "type" => $v["type"]);
						$countShowDataRow++;
					}
					?>
					
						<?php 
							if( count($tableArr) > 0 )
								$this->showReports($tableArr, "reportTable01", array()) ;
							else  $this->noDataFound("No Data Found...");
						?>
					
				<?php
				}
			}
		} else $this->noDataFound("View is not allowed for you...");
    

	}
	
	
	public function noDataFound($atts)
	{
		?>
		<div class="row  mt-3" >
			<div class="col-12">
				<div class="card alert alert-info">
					<div class="card-body text-center p-1">
						<?php echo $atts; ?>
					</div>
				 </div>
			</div>
		</div>
		<?php
	}
	
	

	public function showReports($atts, $tableID,$mailArr)
	{	
		global $wp;
		global $field;
		global $actioOpenWithNewWindow;
		//$thStr = '<th>Sr</th>';
		$thStr = '';
		$i=1;
		$headerField = isset( $atts[1] ) ? $atts[1]  : $atts[0];
		?>
		<div class="table-responsive">
			<table id="<?php echo $tableID; ?>" class="table table-striped table-hover dt-responsive display nowrap" cellspacing="0">
				<thead>
					<tr>
						<?php
						foreach ( $headerField as $k => $v )
						{
							if($k != 'isTrash')
							{
							$tdClass = '';
							if ( preg_match('/right/', $v["type"] ) )
							{
								$tdClass = 'class="text-right"';
							} else if( preg_match('/left/', $v["type"] ) )
							{
								$tdClass = 'class="text-left"';
							}else  $tdClass = 'class="text-center"';
							
							?>
							<th <?php echo $tdClass; ?>><?php echo str_replace("_" , " ", $k ); ?></th>
							<?php $thStr .= '<th '.$tdClass.'>'. str_replace("_" , " ", $k ).'</th>';
								  $i++;
							
							}
						}
						?>
					</tr>
				</thead>
				<tbody>	
				<?php	
				$i = 1;
				$total = array();
				foreach ( $atts as $key => $value )
				{
					?>
						<tr>
					<?php
					
					foreach( $value as $k => $v )
					{	
						
						if( $k != 'isTrash')
						{
							
							
							
							if ( preg_match('/deleteOpen/',$v['type'] )  ||  preg_match('/restore/',$v['type'] )  ||  preg_match('/trash/',$v['type'] )  ||   preg_match('/copy/',$v['type'] )  || preg_match('/edit/',$v['type'] )  || preg_match('/delete/',$v['type'] )  || preg_match('/print/',$v['type'] )  ) 
							{
								global $post;
								$post_slug = isset( $post->post_name ) ?  $post->post_name  : '';
							?>
							<td class="text-left py-0 align-middle">
								<div class="btn-group">
								<?php 
								
								if ( preg_match('/copy/',$v['type'] ) )
								{

								?>
									<a id="copyForm_<?php echo $v['value']; ?>"  class="btn btn-danger">
										<i class="fas fa-copy text-danger">
											<form method="post" action="<?php echo home_url( $wp->request ); ?>" >
												<input type="hidden" name="copyForm" value="<?php echo $v['value']; ?>" />
											</form>
										</i>
									</a> 
								<?php
								}
								if ( preg_match('/deleteOpen/',$v['type'] ) )
								{

								?>
									<a id="openDeleteUserLogs--<?=$post_slug; ?>--<?=$v['value']; ?>"  class="btn btn-danger">
												<i class="fas fa-file-excel text-danger">
												</i>
											</a>
								<?php
								}
								if ( preg_match('/edit/',$v['type'] ) )
								{
									if( in_array("UPDATE", $field['fieldData']['currentAction']  ) )
									{
										if( $actioOpenWithNewWindow == 'YES' )
										{
											?>
										<a id="openUserLogs--<?=$post_slug; ?>--<?=$v['value']; ?>"  class="btn btn-danger">
												<i class="fas fa-edit text-danger">
												</i>
											</a>
										<?php
										}
										else
										{
								?>
									<a id="editForm_<?php echo $v['value']; ?>"  class="btn btn-danger">
										<i class="fas fa-eye text-danger">
											<form method="post" action="<?php echo home_url( $wp->request ); ?>" >
												<input type="hidden" name="editForm" value="<?php echo $v['value']; ?>" />
											</form>
										</i>
									</a>
								<?php
										}
									}
								}			  
								if( isset ( $value['isTrash']['value'] ) ||  preg_match('/trash/',$v['type'] ) )
								{
									if( in_array("TRASH", $field['fieldData']['currentAction']  ) )
									{
										if( (int)$value['isTrash']['value'] == 0 )
										{
									?>
									<a id="trashForm_<?php echo $v['value']; ?>" class="btn btn-danger">
										<i class="fas fa-trash  text-danger">
											<form method="post" action="<?php echo home_url( $wp->request ); ?>" >
												<input type="hidden" name="trashForm" value="<?php echo $v['value']; ?>" />
											</form>
										</i>
									</a>
									<?php
										}
									}
								}
								if( isset ( $value['isTrash']['value'] ) ||  preg_match('/restore/',$v['type'] ) )
								{
									if( in_array("RESTORE", $field['fieldData']['currentAction']  ) )
									{
										if( (int)$value['isTrash']['value'] == 1 )
										{
									?>
									<a id="deleteForm_<?php echo $v['value']; ?>" class="btn btn-danger">
										<i class="fas fa-trash  text-warning">
											<form method="post" action="<?php echo home_url( $wp->request ); ?>" >
												<input type="hidden" name="restoreForm" value="<?php echo $v['value']; ?>" />
											</form>
										</i>
									</a>
									<?php 
										}
									}
								}
								
								 if ( preg_match('/delete/',$v['type'] ) )
								{
									if( in_array("DELETE", $field['fieldData']['currentAction']  ) )
									{
									?>
									<a id="deleteForm_<?php echo $v['value']; ?>" class="btn btn-danger">
										<i class="far fa-window-close text-danger">
											<form method="post" action="<?php echo home_url( $wp->request ); ?>" >
												<input type="hidden" name="deleteForm" value="<?php echo $v['value']; ?>" />
											</form>
										</i>
									</a>
									<?php
									}
								}
								 if ( preg_match('/print/',$v['type'] ) )
								{

									?>	
										<a id="printPage--<?php echo $field['fieldData']['pageName'].'--'.$v['value'];?>" class="btn btn-danger">
											<i class="fas fa-print  text-danger"></i>
										</a>

								
							</td>
							<?php }
							 if ( preg_match('/OPD/',$v['type'] ) )
								{

									?>	
										<a id="opdPage_<?php echo $field['fieldData']['pageName'].'_'.$v['value'];?>" class="btn btn-danger">
											<i class="fas fa-external-link-alt text-danger"></i>
										</a>

								
							<?php } ?>
							</div>
							</td>
						<?php }
						else 
						{
							$tdClass = $aTagOpen = $aTagClose = '';
							if ( preg_match('/right/', $v["type"] ) )
							{
								$tdClass = 'class="text-right"';
							} else if( preg_match('/left/', $v["type"] ) )
							{
								$tdClass = 'class="text-left"';
							}else  $tdClass = 'class="text-center"';

							if ( preg_match('/float/', $v["type"] ) )
							{
								if ( isset( $total[$k] ) )  $total[$k] += (float)$v['value'];
								else $total[$k] = (float)$v['value'];
								$v['value'] = number_format(round( (float)$v['value'] ,2),2);
							}
							else if ( preg_match('/image/', $v["type"] ) )
							{
								$aTagOpen = '<img class="img-fluid rounded" src="'.$v['value'] .'" >';
								$v['value'] ='';;
							}

							if( isset ( $v["id"] ) ) 
							{ $tdClass = 'class="text-left"';
								$toggleStr= '';
								$toggleCls = 'class="btn btn-info"';
								if( isset ( $v['toggle'] ) )
								{
									$toggleStr = 'data-toggle="modal" data-target="#'.$v['toggle'].'"';
									
								}  
								$aTagOpen = '<a type="button" id="'.$v['id'].'" '.$toggleStr.' >';
								$aTagClose = '</a>';
							}
							$v['value'] = isset( $v['value'] ) ?   $v['value']  : ""; 
							?>
							<td <?php echo $tdClass; ?>><?php echo $aTagOpen . $v['value'] . $aTagClose;?></td>
							<?php
						}
						}
					}
					
					$i++;
					
					
				}
		
				
				if( count ( $total ) > 0 )
				{
					?>
					<tr>
					<?php
					foreach( $headerField as $k => $v )
					{
						if($k != 'isTrash')
							{
						$tdClass = '';
						if ( preg_match('/right/', $v["type"] ) )
						{
							$tdClass = 'class="text-right"';
						} else if( preg_match('/left/', $v["type"] ) )
						{
							$tdClass = 'class="text-left"';
						}else  $tdClass = 'class="text-center"';

							
						if ( isset( $total[$k] ) )
						{
							?>
							<td <?php echo $tdClass;?> ><?php echo number_format(round( (float)$total[$k] ,2),2);?></td>
							<?php
						} 
						else
						{
							?>  
							<td>&nbsp;</td>
							<?php
						}
							}
					}
					?>
					</tr>
					<?php
				}
				?>
					
		
				</tbody>
				<tfoot>
				<?php echo $thStr; ?>
				</tfoot>
			</table>
		</div>
		<?php
	}
	public function showReportsRows($atts, $tableID,$mailArr)
	{
		?>
		<div class="table-responsive">
			<table id="<?php echo $tableID; ?>" class="table table-striped table-hover dt-responsive display nowrap" cellspacing="0">
				<tbody>	
				<?php
				foreach ( $atts as $key => $value )
				{	
					
				?>
				<tr><td><b><?php echo str_replace("_"," " ,  $key);?></b></td><td class="text-right"><?php echo number_format(round( (float)$value['value'] ,2),2);  ?></td></tr>
				<?php
				}
				?>
				</tbody>
			</table>
		</div>		
		<?php
	}

	
	public function showReportsWithoutTotal($atts, $tableID,$mailArr)
	{	
		global $wp;
		global $field;
		//$thStr = '<th>Sr</th>';
		$thStr = '';
		$i=1;
		$headerField = isset( $atts[1] ) ? $atts[1]  : $atts[0];
		?>
		<div class="table-responsive">
			<table id="<?php echo $tableID; ?>" class="table table-striped table-hover dt-responsive display nowrap" cellspacing="0">
				<thead>
					<tr>
						<?php
						foreach ( $headerField as $k => $v )
						{
							?>
							<th><?php echo str_replace("_" , " ", $k ); ?></th>
							<?php $thStr .= '<th>'. str_replace("_" , " ", $k ).'</th>';
								  $i++;
							
							
						}
						?>
					</tr>
				</thead>
				<tbody>	
				<?php	
				$i = 1;
				$total = array();
				foreach ( $atts as $key => $value )
				{
					?>
					<tr>
					<?php
					foreach( $value as $k => $v )
					{	
				
						
							$tdClass = $aTagOpen = $aTagClose = '';
							if ( isset(  $v["class"] ) )
							{
								$tdClass = 'class="'.$v["class"].'"';
							} else  $tdClass = '';

							if ( preg_match('/float/', $v["type"] ) )
							{
								if ( isset( $total[$k] ) )  $total[$k] += (float)$v['value'];
								else $total[$k] = (float)$v['value'];
								$v['value'] = number_format(round( (float)$v['value'] ,2),2);
							}
							if ( preg_match('/date/', $v["type"] ) )
							{
								$v['value'] = date_format( date_create( $v['value'] ), 'd/m/Y');
							}

							if( isset ( $v["id"] ) ) 
							{ 
								$toggleStr= '';
								$toggleCls = '';
								if( isset ( $v['toggle'] ) )
								{
									$toggleStr = 'data-toggle="modal" data-target="#'.$v['toggle'].'"';
									$toggleCls = 'class="btn btn-info"';
								} 
								$aTagOpen = '<a '.$toggleCls.' type="button" id="'.$v['id'].'" '.$toggleStr.' >';
								$aTagClose = '</a>';
							}
							?>
							<td <?php echo $tdClass; ?> id="<?php echo $k; ?>"><?php echo $aTagOpen . $v['value']. $aTagClose;?></td>
							<?php
						
				
					}
					?>
					</tr>
					<?php
					$i++;
				}
		
				?>
					
		
				</tbody>
				
			</table>
		</div>
		<?php
	}

	
	
	public function multiField($atts)
	{
			global $wpdb;
			global $field;
			$jsArr = $addNoOfLine = $jaVar = '';
			

				if( count ( $atts ) > 0 && is_array($atts) )
				{

				
					foreach( $atts as $key => $value )
					{
						$addLine=array();
						if( isset ( $_POST ) )
						{	$thisI = 1;
							foreach( $_POST as $k => $v)
							{	
								if( preg_match("/{$key}_/", $k) && $v != ''  )
								{
									$thisId = explode("{$key}_",$k);
									array_push($addLine,$thisId[1]);
									$thisI++;
								}

									
							}
						}
						if(count($addLine) < 1)$addLine  = array('1');
						
						 
						
						//$addNewLineText = '<div class="row">';
						$lastField = '';
						$addNewStr = '<div class="row" id="row_'.$key.'_\'+nextId+\'">';
						////// START  FOR AUTOCOMPELETE  ///////
						foreach( $value as $kv => $vv )
						{
							
							if(  isset ( $vv['tableName'] ) && isset ( $vv['tableKey'] ) )
							{
								$arrKey = array();
								$arrData = $wpdb->get_results("select {$vv['tableKey']} from {$wpdb->prefix}{$vv['tableName']} WHERE isTrash=0 order by {$vv['tableKey']} " , 'ARRAY_A' );
								foreach( $arrData as $ak => $av )
								{
									$arrKey[$av[$vv['tableKey']]] = $av[$vv['tableKey']] ;
								}
								$jaVar .=  'var '.$kv.'Arr = ["'.implode('", "' , $arrKey).'"];'; 
						
							}
						}
						////// END  FOR AUTOCOMPELETE  ///////
						foreach( $value as $kv => $vv )
						{
							$lastField = $kv;
						}
						foreach( $value as $kv => $vv )
						{
					
							
								$valueName = $vv['fieldValue']["id"] = $vv['fieldValue']["name"] = "{$kv}_'+nextId+'";
								$vv['fieldValue']["value"] = isset($$valueName) ? $$valueName : ""; 
								
								$columnStr = $this -> _showColumnStart($kv, $vv, 'multi' );

								 $addNewStr .= $columnStr['startStr'];
								if(  $vv["fieldType"]  == "gmap" ) echo $this->createGmapField($value,'multi',$lastField);			
								else if(  $vv["fieldType"]  == "avatar" ) echo $this->createAvatarField($value,'multi',$lastField);			
								else if(  $vv["fieldType"]  == "input" ) $addNewStr .= $this->createInputField($vv,'multi',$lastField);
								else if( $vv["fieldType"] == "select" ) $addNewStr .= $this->createSelectField($vv,'multi',$lastField);
								else if( $vv["fieldType"] == "datalist" ) $addNewStr .= $this->createInputList($vv,'multi',$lastField);
								else if ( $vv["fieldType"] == 'textarea' )$addNewStr .= $this->createTextAreaField($vv,'multi',$lastField);
								else if ( $vv["fieldType"] == 'buttonGroup' )$addNewStr .= $this->createButtonGroupField($vv,'multi',$lastField);
								if( $lastField == $kv)
								{
									$addNewStr .= '<div class="input-group-append"><a tabindex="0" id="'.$key.'_addRow_\'+nextId+\'" class="btn btn-info"><i class="fas fa-plus  text-info"></i></a><a id="'.$key.'_deleteRow_\'+nextId+\'" class="btn btn-danger"><i class="fas fa-trash  text-danger"></i></a></div>';
								}
					
								$addNewStr .= $columnStr['endStr'];

							
									
							
						}
						$addNewStr .= '</div>';
						//$addNewLineText .= '</div>';
						$addNewLineText = '';
						$addNewLineText .= '<div class="multi_overflow" id="'.$key.'_addNewRows">';
						//for( $i=1; $i <= $addLine; $i++)
						foreach($addLine as $k => $i)
						{
							
							$addNewLineText .= '<div class="row" id="row_'.$key.'_'.$i.'">';
							foreach( $value as $kv => $vv ) 
							{
								$valueName =  $vv['fieldValue']["id"] = $vv['fieldValue']["name"] = "{$kv}_{$i}";
								$vv['fieldValue']["value"] = isset($_POST[$valueName]) ? $_POST[$valueName] : "";

								//$addNewLineText .= $this -> _showColumnStart($kv, $vv ,'multi');
								$columnStr = $this -> _showColumnStart($kv, $vv, 'multi' );
								$addNewLineText .= $columnStr['startStr'];
								if(  $vv["fieldType"]  == "gmap" ) echo $this->createGmapField($value,'multi','');			
								else if(  $vv["fieldType"]  == "avatar" ) echo $this->createAvatarField($value,'multi','');			
								else if(  $vv["fieldType"]  == "input" ) $addNewLineText .= $this->createInputField($vv,'multi',$lastField);
								else if( $vv["fieldType"] == "select" ) $addNewLineText .= $this->createSelectField($vv,'multi',$lastField);
								else if( $vv["fieldType"] == "datalist" ) $addNewLineText .= $this->createInputList($vv,'multi',$lastField);
								else if ( $vv["fieldType"] == 'textarea' )$addNewLineText .= $this->createTextAreaField($vv,'multi',$lastField);
								else if ( $vv["fieldType"] == 'buttonGroup' )$addNewLineText .= $this->createButtonGroupField($vv,'multi',$lastField);
								if( $lastField == $kv)
								{
									$addNewLineText .= '<div class="input-group-append"><a tabindex="0" id="'.$key.'_addRow_'.$i.'" class="btn btn-info"><i class="fas fa-plus  text-info"></i></a><a id="'.$key.'_deleteRow_'.$i.'" class="btn btn-danger"><i class="fas fa-trash  text-danger"></i></a></div>';
								}
								$addNewLineText .= $columnStr['endStr'];

 
									
							}
							$addNewLineText .= '</div>';
						
						}
						$addNewLineText .= '</div>';
						$addNewLineText .= '<script>';
						$addNewLineText .= $jaVar;
						$addNewLineText .= 'function '.$key.'_addNewRows(nextId){
											if( jQuery("[name='.$key.'_"+nextId+"]").length == 0 )
											{	
												var str = \''.$addNewStr.'\';';
						if( isset( $field['fieldData']['downWard'] ) )
						{
							$addNewLineText .= 'jQuery("#'.$key.'_addNewRows:last").append(str);';
						}
						else{
							$addNewLineText .= 'jQuery("#'.$key.'_addNewRows:first").prepend(str);';
						}
						$addNewLineText .= '}
										}
						';				
										
						$addNewLineText .= '
											</script>';
						
						$addNoOfLine .= $addNewLineText;
					}
				}
			
			
			return $addNoOfLine;
	}
	//public function showAllSearchField
	public function showAllField($searchVal)
	{
		global $field;
		
		$returnStr = '';
		
		foreach( $field as $fieldKey => $fieldValue )
		{
			
			
			if(   $fieldKey != 'unsetJsonData' && $fieldKey != 'getQry' && $fieldKey != 'tableCol' && $fieldKey != 'fieldData' && $fieldKey != 'rowGroup') 
			{	
				if( isset( $field[$fieldKey]['Group_Prefix'] ) )
					echo html_entity_decode ( $field[$fieldKey]['Group_Prefix']  );
					 
				
				foreach( $fieldValue as $k => $v )
				{
					// echo "<pre>";
					// var_dump($fieldValue);
					// echo "</pre>";
					// exit;

					if( ( $searchVal == 'search' && $k == 'search' ) || 
						(  $searchVal != 'search' && $k != 'search' && $k != 'multi' ) )
					{
						if( $k != 'Group_Prefix' && $k != 'Group_Suffix')
						{
							foreach(  $v as $keyy => $valuee )
							{
								//print_r($valuee); echo '<br><br><br>';
								foreach(  $valuee as $key => $value )
								{
									if( isset( $value['fieldType']) )
									{
										$endClass =  '';
										if( isset ( $value['rowOpen'] ) ) echo '<div class="row">';
										
										$columnStr = $this -> _showColumnStart($keyy, $value ,'single' );
										echo $columnStr['startStr'];
										
										
										if(  $value["fieldType"]  == "gmap" ) echo $this->createGmapField($value,'single','');			
										else if(  $value["fieldType"]  == "avatar" ) echo $this->createAvatarField($value,'single','');			
										else if(  $value["fieldType"]  == "input" ) echo $this->createInputField($value,'single','');
										else if(  $value["fieldType"]  == "radio" ) echo $this->createRadioField($value,'single','');
										else if(  $value["fieldType"]  == "checkbox" ) echo $this->createCheckboxField($value,'single','');
										else if(  $value["fieldType"]  == "datalist" ) echo $this->createInputList($value,'single','');
										
										else if( $value["fieldType"] == "select" ) echo $this->createSelectField($value,'single','');
										else if ( $value["fieldType"] == 'textarea' ) echo $this->createTextAreaField($value,'single','');
										echo $columnStr['endStr'];
										if( isset ( $value['rowClose'] ) )  echo '</div>';
											

									}
								}

							}
						}	
					}
					else if (  $searchVal != 'search' &&  $k != 'search' &&  $k != 'field'  )
					{
						echo  $this->multiField($v);
					}
					
					
				}
				if(  isset( $field[$fieldKey]['Group_Suffix'] ) )
				echo html_entity_decode (  $field[$fieldKey]['Group_Suffix'] ); 
			}
		}
		
		
	}
	
	public function startSubmitForm($formName,$formText,$formClass='',$modal='yes')
	{
		global $wp;
		//print_r($wp);
		//echo $_SERVER['REQUEST_URI'];
		if( $modal == 'yes')
		{
			?>
			<button type="button" class="my-2 mx-4 btn btn-primary" data-toggle="modal" id="addNew" data-target="#<?=$formName; ?>Modal">Add New</button>
			<div class="modal fade" id="<?=$formName; ?>Modal" tabindex="-1" 
				 role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  <div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-body">
		<?
		}
		?>

<div class="<?=$formName; ?>Card card mb-2 <?=$formClass;?> d-print-none">
<div class="card-header"><?=$formText;?>
	<? 
		if( $modal == 'yes')
		{
			?>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
	<? } ?>
</div>
<div class="card-body p-0 p-sm-2 p-md-3">
<form id="<?=$formName; ?>" autocomplete="off" name="entry_page" method="post" enctype="multipart/form-data" action="<?php echo get_site_url().'/'.$wp->request; ?>">		
	<?php
		
					
	}
	public function endSubmitForm($modal = 'yes')
	{
		?>
</form>
</div>
</div>
	<?php
		if( $modal == 'yes')
		{
			?>
			</div>
      
    </div>
  </div>
</div>
		<?
		}
		
	}
	public function setAsSelectOption($atts)
	{	$newArray = array();
		foreach($atts['table_data'] as $value )
		{  
			
			$textValue = '';
			$i = 0;
			foreach($atts['option_text'] as $val)
			{
				if( $i == 0 ) {  $textValue .=  $value[$val]; $i++; }
				else  $textValue .= " --- " . $value[$val]  ; 
				
			}
			
			$optionValue = '';
			$i = 0;
			$thisCount = count( $atts['option_value'] );
			foreach($atts['option_value'] as $val)
			{
				if( isset (  $value[$val] ) ) 
				{
					if( $i == 0 ) { $optionValue .=  $value[$val]; $i++; }
					else $optionValue .=  " -- " . $value[$val] ; 
				}
				else $optionValue .=   $val ;  
				
			}
					
			$newArray[$textValue] = $optionValue;
		}
		
		unset($atts);
		return $newArray;
	}
	
	
	public function createInputList($atts,$isMulit,$lastField='')
	{	
		global $field;
		global $fieldValue;
		$selectStr = '';
		
		if(   ( isset ( $atts['fieldValue']['readonly'] ) || ( isset( $_POST['editForm'] ) && isset( $field['fieldData']['dontChange'][$atts['fieldValue']['name']] ) ) || ( isset( $_POST['copyForm'] ) && isset( $field['fieldData']['copyChange'][$atts['fieldValue']['name']] ) ) ) )
		{  
			$atts['fieldValue']['tabindex'] = -1; 
			$atts['fieldValue']['style'] = "pointer-events:none"; 
			$atts['fieldValue']['class'] = str_replace("select2","", $atts['fieldValue']['class']); 
		}

		$fldName = $atts['fieldValue']["name"];
		$atts['fieldValue']["value"] = 	isset( $_POST[$fldName] ) ? $_POST[$fldName] :
			( isset( $fieldValue[$fldName] ) ? $fieldValue[$fldName] : "" );

		//readonlyprint_r($atts);
		$selectStr = '<input ';
			$selectStr .= 'list="'.$fldName.'"';
			$selectStr .= 'value="'.$atts['fieldValue']["value"].'"';
			foreach($atts['fieldValue'] as $key => $value)
			{	
				// if($key == 'readonly')$selectStr .= 'disabled="readonly" ';
				// else
				if($key == 'required' || $key == 'autofocus')	 $selectStr .=  " {$key} ";
				else if($key == 'id')	$selectStr .= 'id="dataList_'.$value.'" ';
				else if($key != 'value' && $key != 'name') $selectStr .= $key.'="'.$value.'" ';
			}
		$selectStr .= '>';
		$selectStr .= '<input type="hidden" name="'.$fldName.'" value="'.$atts['fieldValue']["value"].'">';
		 $selectStr .= '<datalist id="'.$fldName.'">';
		 if ( isset( $atts['firstOption']  ) > 0 ) {  
		
			$selectStr  .= '<option data-value="" value="'.$atts['firstOption'].'">';
		}
		//print_r($atts['optionList']);
		
		
		if( isset ( $atts['optionList'] ) )
		{
			
			if( count ( $atts['optionList'] ) > 0 )	
			{
				
				
					foreach($atts['optionList'] as $key => $value)
					{	
						$selected = '';
						
							
							 if( $atts['fieldValue']['value'] == $key ) $selected = 'selected';
						
						
						$selectStr .= '<option data-value="'.htmlspecialchars($key).'" value="'.$value.'" >';
						
					}
				
			}
		}
		 $selectStr .= '</datalist>';
		
		return $selectStr;
	}
	
	public function createSelectField($atts,$isMulit,$lastField='')
	{	
		global $field;
		global $fieldValue;
		$selectStr = '';
		
		if(   ( isset ( $atts['fieldValue']['readonly'] ) || ( isset( $_POST['editForm'] ) && isset( $field['fieldData']['dontChange'][$atts['fieldValue']['name']] ) ) || ( isset( $_POST['copyForm'] ) && isset( $field['fieldData']['copyChange'][$atts['fieldValue']['name']] ) ) ) )
		{  
			$atts['fieldValue']['tabindex'] = -1; 
			$atts['fieldValue']['style'] = "pointer-events:none"; 
			$atts['fieldValue']['class'] = str_replace("select2","", $atts['fieldValue']['class']); 
		}

		$fldName = $atts['fieldValue']["name"];
		$atts['fieldValue']["value"] = 	isset( $_POST[$fldName] ) ? $_POST[$fldName] :
			( isset( $fieldValue[$fldName] ) ? $fieldValue[$fldName] : "" );

		
		if( isset ( $atts['fieldValue']['multiple'] ) )  {  $atts['fieldValue']['name'] .= "[]"; }
		//readonlyprint_r($atts);
		$selectStr = '<select ';
			foreach($atts['fieldValue'] as $key => $value)
			{	
				// if($key == 'readonly')$selectStr .= 'disabled="readonly" ';
				// else
				if($key == 'required' || $key == 'autofocus')	 $selectStr .=  " {$key} ";
				else if ( $key == 'class') $selectStr .= isset ( $atts['formGroup'] ) ?  ' class="'.$value.' '.$atts['formGroup'].'"' : ' class="'.$value.'"';
				else if($key != 'value') $selectStr .= $key.'="'.$value.'" ';
			}
		$selectStr .= '>';
    	

		if ( isset( $atts['firstOption']  ) > 0 ) {  
		
			$selectStr  .= '<option value="">'.$atts['firstOption'].'</option>';
		}
		
		if( isset ( $atts['optionList'] ) )
		{
		if( count ( $atts['optionList'] ) > 0 )	
		{
			
			
				foreach($atts['optionList'] as $key => $value)
				{	
					$selected = '';
										
						if( is_array( $atts['fieldValue']['value'] ) )
						{   
							$selected = '';
							foreach( $atts['fieldValue']['value'] as $ak => $av)
							{	
								if( $av == $key && strlen($av) == strlen($key) ) $selected = 'selected';
							}
						}
						else if( $atts['fieldValue']['value'] == $key && strlen($atts['fieldValue']['value']) == strlen($key) )
						{			
							//echo "{$atts['fieldValue']['value']}={$key}<br>";	
								$selected = 'selected';
						}
					
					
					$selectStr .= '<option value="'.htmlspecialchars($key).'" '.$selected.' > '.$value.' </option>';
					
				}
			
		}
		}
		
		
		$selectStr .= '</select>';
		return $selectStr;
	}
	public function createCheckboxField( $atts,$isMulit,$lastField='' )
	{
		
		$str = '';
		
			
		if( isset ( $atts['optionList'] ) )
		{
			if( count( $atts['optionList'] ) > 0 )
			{
				foreach($atts['optionList'] as $key => $value)
				{
					$selected = $className = '';
					$thisValue = 	isset( $_POST[$key] ) ?  $_POST[$key]  :
					( isset( $fieldValue[$key] ) ? $fieldValue[$key] : "" );
			
			
					if( $thisValue == $value ) $selected = 'checked';
					if( isset ( $atts['class'] ) ) $className = $atts['class'];
					$str .='<div class="form-check '.$className.'">
							  <input class="form-check-input" type="checkbox" value="'.$value.'"  id="'.$key.'" name="'.$key.'" '.$selected.'>
							  <label class="form-check-label" for="'.$key.'">
								'.$value.'
							  </label>
							</div>';
				}
			}
		}
		
		

		return $str;
		
	}
	
	public function createRadioField( $atts,$isMulit,$lastField='' )
	{
		
		$str = '';
		$fldName = $atts['fieldValue']["name"];
		$atts['fieldValue']["value"] = 	isset( $_POST[$fldName] ) ?  $_POST[$fldName] :
			( isset( $fieldValue[$fldName] ) ? $fieldValue[$fldName] : "" );
			
		if( isset ( $atts['optionList'] ) )
		{
			if( count( $atts['optionList'] ) > 0 )
			{
				foreach($atts['optionList'] as $key => $value)
				{
					$selected = $className = '';
					if( $atts['fieldValue']['value'] == $value ) $selected = 'checked';
					if( isset ( $atts['class'] ) ) $className = $atts['class'];
					
					$str .='<div class="form-check '.$className.'">
							  <input class="form-check-input" type="radio" value="'.$value.'"  id="'.$key.'" name="'.$fldName.'"  '.$selected.'>
							  <label class="form-check-label" for="'.$key.'">
								'.$value.'
							  </label>
							</div>';
				}
			}
		}
		
		

		return $str;
		
	}
	
	public function createButtonGroupField($atts,$isMulit,$lastField='')
	{
		$str = '';
		$class = isset( $atts['class'] ) ? 'class="'.$atts['class'].'"' : "";
		$str .='<div class="btn-group d-flex">
                    <button type="button" class="btn btn-info">Action</button>
                    <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
					<div class="dropdown-menu" role="menu" style="">';
					if( isset( $atts['actionArray'] ) )
					{
						foreach( $atts['actionArray'] as $key => $value)
						{
							$str .= '<a class="dropdown-item" id="'.$value.'">'.$key.'</a>';
						}
					}else echo "actionArray is not defined for {$atts['name']}";
        $str .= '</div>
                  </div>';

		return $str;
	}
	public function createTextAreaField($atts,$isMulit,$lastField='')
	{
		global $wpdb;
		global $field;
		$str = '';
		if(  ( isset ( $atts['fieldValue']['readonly'] ) || ( isset( $_POST['editForm'] ) && isset( $field['fieldData']['dontChange'][$atts['fieldValue']['name']] ) ) || ( isset( $_POST['copyForm'] ) && isset( $field['fieldData']['copyChange'][$atts['fieldValue']['name']] ) ) ) )
		{ 
			$atts['fieldValue']['tabindex'] = -1; 
			$atts['fieldValue']['style'] = "pointer-events:none"; 
		}

		$fldName = $atts['fieldValue']["name"];
		$atts['fieldValue']["value"] = 	isset( $_POST[$fldName] ) ?( strlen($_POST[$fldName]) > 0 ? stripslashes( $_POST[$fldName] ) : $_POST[$fldName] ):
			( isset( $fieldValue[$fldName] ) ? $fieldValue[$fldName] : "" );


			
		$str =  '';
		if( $isMulit != 'multi')
		{
			if(  isset ( $atts['tableName'] ) && isset ( $atts['tableKey'] ) )
			{
				$arrKey = array();
				$arrData = $wpdb->get_results("select {$atts['tableKey']} from {$wpdb->prefix}{$atts['tableName']} WHERE isTrash=0 order by {$atts['tableKey']} " , 'ARRAY_A' );
				foreach( $arrData as $ak => $av )
				{
					$arrKey[$av[$atts['tableKey']]] = $av[$atts['tableKey']] ;
				}
				$str .=  '<script>var '.$atts['fieldValue']['name'].'Arr = ["'.implode('", "' , $arrKey).'"];</script>'; 
				
			}
		}
		

		$str .= '<textarea ';
		
		foreach($atts['fieldValue'] as $key => $value)
		{	
			if($key == 'required' || $key == 'autofocus')	 $str .=  " {$key} ";
			else if ( $key == 'class') $str .= isset ( $atts['formGroup'] ) ?  ' class="'.$value.' '.$atts['formGroup'].'"' : ' class="'.$value.'"';
			else $str .=  $key.'="'.$value.'" ';
			
		}
		$str .= '>'.$atts['fieldValue']['value'].'</textarea>';
		return $str;
	}
	public function createGmapField($atts,$isMulit,$lastField='')
	{
		$fldName = $atts['fieldValue']["name"];
		$atts['fieldValue']["value"] = isset( $_POST[$fldName] ) ?  stripslashes($_POST[$fldName]) :
										( isset( $fieldValue[$fldName] ) ? stripslashes($fieldValue[$fldName] ): "" );
		$thisId = explode("_",$fldName);
		$thisCount = '';
		if( count($thisId) > 1) 
		{
			$lastId = count($thisId) - 1; 
			$thisCount = "_{$thisId[$lastId]}"; 
		}			
		$str = '<div style="height:300px; width:100%;" id="'.$fldName.'"></div>'; 
	
			return $str;				
	}
	public function createAvatarField($atts,$isMulit,$lastField='')
	{
		$fldName = $atts['fieldValue']["name"];
		$atts['fieldValue']["value"] = isset( $_POST[$fldName] ) ?  stripslashes($_POST[$fldName]) :
										( isset( $fieldValue[$fldName] ) ? stripslashes($fieldValue[$fldName] ): "" );
		$thisId = explode("_",$fldName);
		$thisCount = '';
		if( count($thisId) > 1) 
		{
			$lastId = count($thisId) - 1; 
			$thisCount = "_{$thisId[$lastId]}"; 
		}			
		$srcText = '';
		//$defaultProfile = get_site_url().'/default-user.png';
		if( $atts['fieldValue']["value"] != '') $srcText = $atts['fieldValue']["value"];
		else $srcText = get_site_url().'/default-user.png';
		if( isset( $atts['displayName']) ) $shownName = $atts['displayName'];
		else $shownName = str_replace("_", " " ,$atts['fieldValue']['name']);
		
		$str = '<div class="row">
					<div class="col-sm-12 p-4">
							<a id="deleteImage'.$thisCount.'" class="btn btn-danger avatar-badge">
									<i class="fa fa-window-close fa-xl "></i>
							</a>
						<div class="image_area ">
							
							<label data-toggle="tooltip" title="Select '.$shownName.'">
								
								<img  src="'.$srcText.'" id="uploaded_image_'.$fldName.'" class="img-responsive img-circle" />
								
								
								<input type="file" name="'.$fldName.'" class="image" id="'.$fldName.'" style="display:none" />
								<!-- <input type="hidden" id="Data'.$fldName.'" name="Data'.$fldName.'" /> -->
							</label>
						</div>
					</div>
					<!-- 
					<div class="modal fade" id="modal_'.$fldName.'" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title">Crop Image Before Upload</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">×</span>
									</button>
								</div>
								<div class="modal-body">
									<div class="img-container">
										<div class="row">
											<div class="col-12" id="model_image">
												<img src="" style="width:100%" id="sample_'.$fldName.'" />
											</div>
											 
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" id="crop_'.$fldName.'" class="btn btn-primary">Crop</button>
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
								</div>
							</div>
						</div>
					</div> -->
				</div>';
			
		/*
		$str = '<div class="avatar-wrapper shadow-sm ">
					<img tabindex="-1" class="img-fluid" id="profilePic'.$thisCount.'" '.$srcText.'  />
					<a id="deleteImage'.$thisCount.'" class="btn btn-danger avatar-badge">
						<i class="fas fa-window-close fa-xl "></i>
					</a>
					<input  accept=".png, .jpg, .jpeg" class="form-control" type="file" name="'.$fldName.'" id="'.$fldName.'" />
					
				</div>';
				*/
	
			return $str;				
	}
	public function createInputField($atts,$isMulit,$lastField='')
	{	
		//print_r($atts)
		global $fieldValue;
		global $field;
		global $wpdb;
		$jsstr = '';
		if($isMulit != 'multi')
		{
			if(  isset ( $atts['tableName'] ) && isset ( $atts['tableKey'] ) )
			{
					
				$arrKey = array();
				$tblName = strtolower(str_replace("_","",$atts['tableName']));
				$arrData = $wpdb->get_results("select {$atts['tableKey']} from {$wpdb->prefix}{$atts['tableName']} WHERE isTrash=0 GROUP BY {$atts['tableKey']} " , 'ARRAY_A' );
				foreach( $arrData as $ak => $av )
				{
					$arrKey[$av[$atts['tableKey']]] = $av[$atts['tableKey']];
				}
				$jsstr .=  '<script>var '.$atts['tableKey'].'Arr = ["'.implode('", "' , $arrKey).'"];</script>';
			}
		}
		

		$fldName = $atts['fieldValue']["name"];
		if(  (  isset ( $atts['fieldValue']['readonly'] ) || ( ( isset( $_POST['editForm'] ) || isset( $_POST['restoreForm'] ) || isset( $_GET['logID'] ) ) && isset( $field['fieldData']['dontChange'][$atts['fieldValue']['name']] ) ) || ( isset( $_POST['copyForm'] ) && isset( $field['fieldData']['copyChange'][$atts['fieldValue']['name']] ) ) ) )
		{ 
			$atts['fieldValue']['tabindex'] = -1; 
			$atts['fieldValue']['style'] = "pointer-events:none"; 
		}
		if($atts['fieldValue']['type'] == "submit" || $atts['fieldValue']['type'] == 'button')
			$atts['fieldValue']["value"] = isset( $_POST[$fldName] ) ?  $_POST[$fldName] : (	isset( $atts['displayName'] ) ?  $atts['displayName'] : 
											( isset( $fldName ) ? str_replace("_"," ",$fldName) : '' ) );

		else $atts['fieldValue']["value"] = isset( $_POST[$fldName] ) ? ( strlen($_POST[$fldName]) > 0 ? stripslashes($_POST[$fldName]) : $_POST[$fldName] ) :
										( isset( $fieldValue[$fldName] ) ? $fieldValue[$fldName] : "" );
		
		if($atts['fieldValue']['type'] == "date" )
		{
			$atts['fieldValue']["min"] = '1940-12-31';
			$atts['fieldValue']["max"] = '2050-12-31';
		}
		if( $atts['fieldValue']['type'] == 'file')
		{
			$str = '<div class="input-group"><div class="custom-file"><input ';
						if( isset( $atts['fieldValue'] ) )
						{
							//print_r($atts['fieldValue']);  
							foreach($atts['fieldValue'] as $key => $value)
							{	
								if($key != 'value' )
								{
									if($key == 'placeholder')$str .=   'accept="'.$value.'"';
									else if($key == 'class')$str .=   $key.'="custom-file-input" ';
									else if($key == 'required' || $key == 'autofocus')	 $str .=  " {$key} ";
									else $str .=  $key.'="'.$value.'" ';
								}
							}
						}
			$str .= ' /><label class="custom-file-label" for="'.$fldName.'">Choose</label></div></div>';
		}
		else{
			$str = '<input ';
			if( isset( $atts['fieldValue'] ) )
			{
				foreach($atts['fieldValue'] as $key => $value)
				{	
					if($key == 'required' || $key == 'autofocus')	 $str .=  " {$key} ";
					else if ( $key == 'class') $str .= isset ( $atts['formGroup'] ) ?  ' class="'.$value.' '.$atts['formGroup'].'"' : ' class="'.$value.'" ';
					else $str .=  $key.'="'.$value.'" ';
				}
			}else
			{
				$this->noDataFound( "fieldValue not found for input:{$atts['fieldValue']['name']}");
				exit;
			}
			$str .= ' /> ';
		}
		$str .= $jsstr;
		return $str;
		
	}
	
	
	
	
	public function setOptionData($atts)
	{
		$options = '';
		if ( isset( $atts['firstOption']  ) ) {  
	
			$options .= '<option value="">' . $atts['firstOption'] . '</option>';
		}

		if( count( $atts['option'] ) > 0 )
		{
			foreach($atts['option'] as $key => $value)
			{	
					$options .= '<option value="' . $key . '">' . $value . '</option>';
			}
		}
		return $options;
	}

	private function _showColumnStart($fieldID, $attColumnStart, $multi )
	{
		
		$labelStr = '';
		$returnStr = array();
		$returnStr['startStr'] = $returnStr['endStr'] = ''; 
		$thisClass = 'mb-1';
		$groupStr = '';
		$spanWidth = isset( $attColumnStart['spanWidth'] ) ? $attColumnStart['spanWidth']: "50";
		if( isset( $attColumnStart['displayName']) ) $shownName = $attColumnStart['displayName'];
		else $shownName = str_replace("_", " " ,$attColumnStart['fieldValue']['name']);
		
		$reqAstrik = '';
		if(  isset( $attColumnStart['fieldValue']['required'] ) ) 
			if( $attColumnStart['fieldValue']['required'] )  
				$reqAstrik = '*';
			
		if( isset ( $attColumnStart['fieldValue']['type'] ) )
		{
			if(  $attColumnStart['fieldValue']['type'] != 'hidden' && $attColumnStart['fieldValue']['type'] != 'button' && $attColumnStart['fieldValue']['type'] != 'submit' ) 
			{
				if ( isset( $attColumnStart['formGroup']  ) )
				{
					if( $attColumnStart['formGroup'] == 'form-outline'  )  
					{
						if( $attColumnStart['fieldType'] != 'avatar' )
						{
							$labelStr = '<label class="outline-label" for="'.$attColumnStart['fieldValue']['name'].'">'.$shownName.' '.$reqAstrik.'</label>';
						}
						$groupStr  = '<div class="input-group">';
						$returnStr['endStr'] = '</div>';
					}
					else if( $attColumnStart['formGroup'] == 'vertical'  )  
					{
						if( $attColumnStart['fieldType'] != 'avatar' )
						{
							$labelStr = '<label for="'.$attColumnStart['fieldValue']['name'].'">'.$shownName.' '.$reqAstrik.'</label>';
						}
						$groupStr  = '<div class="input-group">';
						$returnStr['endStr'] = '</div>';
					}
					else if( $attColumnStart['formGroup'] == 'horizontal'  ) 
					{	
						if( $attColumnStart['fieldType'] != 'avatar' )
						{
							$labelStr = '<div class="input-group">	
										<div class="input-group-prepend">
											<lable class="input-group-text" style="min-width:'.$spanWidth.'px">'.$shownName.' '.$reqAstrik.' </lable>
										</div>';
						}
						$returnStr['endStr'] = '</div>';
						
				
					}
					else if( $attColumnStart['formGroup'] == 'material'  ) 
					{
						if( $attColumnStart['fieldType'] != 'avatar' )
						{
							$labelStr = '<label for="'.$attColumnStart['fieldValue']['name'].'">'.$shownName.' '.$reqAstrik.'</label>';
						}
						$groupStr  = '<div class="input-group">';
						$returnStr['endStr'] = '</div>';
						
					}
					 
				} 
				else 
				{ 
					if( $attColumnStart['fieldType'] != 'avatar' )
					{
						$labelStr = '<label for="'.$attColumnStart['fieldValue']['name'].'">'.$shownName.' '.$reqAstrik.'</label>';
					}
					$groupStr  = '<div class="input-group">';
					$returnStr['endStr'] = '</div>';
				}
				
			}
		}
		else  
		{
			
			if ( isset( $attColumnStart['formGroup']  ) )
				{
					if( $attColumnStart['formGroup'] == 'form-outline'  )  
					{
						if( $attColumnStart['fieldType'] != 'avatar' )
						{
							$labelStr = '<label class="outline-label"  for="'.$attColumnStart['fieldValue']['name'].'">'.$shownName.' '.$reqAstrik.'</label>';
						}
						$groupStr  = '<div class="input-group">';
						$returnStr['endStr'] = '</div>';
					}
					else if( $attColumnStart['formGroup'] == 'vertical'  )  
					{
						if( $attColumnStart['fieldType'] != 'avatar' )
						{
							$labelStr = '<label for="'.$attColumnStart['fieldValue']['name'].'">'.$shownName.' '.$reqAstrik.'</label>';
						}
						$groupStr  = '<div class="input-group">';
						$returnStr['endStr'] = '</div>';
					}
					else if( $attColumnStart['formGroup'] == 'horizontal'  ) 
					{	
						if( $attColumnStart['fieldType'] != 'avatar' )
						{
							$labelStr = '<div class="input-group">	
										<div class="input-group-prepend">
											<lable class="input-group-text" style="min-width:'.$spanWidth.'px">'.$shownName.' '.$reqAstrik.' </lable>
										</div>';
						}
						$returnStr['endStr'] = '</div>';
						
				
					}
					else if( $attColumnStart['formGroup'] == 'material'  ) 
					{
						if( $attColumnStart['fieldType'] != 'avatar' )
						{
							$labelStr = '<label for="'.$attColumnStart['fieldValue']['name'].'">'.$shownName.' '.$reqAstrik.'</label>';
						}
						$groupStr  = '<div class="input-group">';
						$returnStr['endStr'] = '</div>';
					}
					 
				} 
				else 
				{
					if( $attColumnStart['fieldType'] != 'avatar' )
					{
					$labelStr = '<label for="'.$attColumnStart['fieldValue']['name'].'">'.$shownName.' '.$reqAstrik.'</label>';
					}
					$groupStr  = '<div class="input-group">';
					$returnStr['endStr'] = '</div>';
				}
		}
		$thisClass .= isset( $attColumnStart['colClass']  ) ?  " ". $attColumnStart['colClass'] : '';
		$thisClass .= isset( $attColumnStart['formGroup']  ) ? " ".$attColumnStart['formGroup'] : '';
		if( isset( $attColumnStart['colClass']  ) )
		{
			$returnStr['startStr'] = '<div id="C_'.$fieldID.'" class="'.$thisClass.'">'.$labelStr.$groupStr;
			$returnStr['endStr'] .= '</div>';
		} 
		else 
		{
			$returnStr['startStr'] = $groupStr  ;
		}
		return $returnStr; 
			
	}

}
?>