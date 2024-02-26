<?php
use CRM_Pilnetreports_ExtensionUtil as E;

class CRM_Pilnetreports_Form_Report_Caseanalysis extends CRM_Report_Form {
  /**
   *
   * @var bool
   * If TRUE, use non-temporary tables in self::_debug_temp_table(), to facilitate query debugging in SQL.
   */
  protected $_debug = FALSE;

  protected $_autoIncludeIndexedFieldsAsOrderBys = TRUE;
  protected $_exposeContactID = FALSE;
  protected $_customGroupGroupBy = FALSE;

  public function __construct() {
    $this->_columns = array(
      'civicrm_case' => array(
        'alias' => 'caseanalysis_case',
        'fields' => array(
          'case_id_key' => array(
            'name' => 'id',
            'no_display' => TRUE,
            'required' => TRUE,
          ),
          'case_id' => array(
            'name' => 'id',
            'title' => E::ts('Case ID'),
            'default' => TRUE,
          ),
          'case_subject' => array(
            'name' => 'subject',
            'title' => E::ts('Case subject'),
            'default' => TRUE,
          ),
          'start_date' => array(
            'title' => E::ts('Case start date'),
            'default' => TRUE,
          ),
          'status_id' => array(
            'title' => E::ts('Case status'),
            'default' => TRUE,
          ),
          'is_matched' => array(
            'title' => E::ts('Is matched'),
            'default' => TRUE,
            'dbAlias' => "if(caseanalysis_case_civireport.status_id = 10, 'Yes', 'No')",
          ),
        ),
        'order_bys' => array(
          'case_id' => array(
            'name' => 'id',
            'title' => 'Case ID',
            'default' => TRUE,
            'default_weight' => 30,
          ),
        ),
        'filters' => array(
          'is_deleted' => array(
            'title' => E::ts('Case is deleted'),
            'type' => CRM_Utils_Type::T_BOOLEAN,
            'default' => 0,
          ),
          'status_id' => array(
            'title' => E::ts('Case status'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Case_BAO_Case::buildOptions('status_id'),
            'type' => CRM_Utils_Type::T_INT,
          ),
        ),
        'grouping' => 'case-fields',
      ),
      'civicrm_contact' => array(
        'dao' => 'CRM_Contact_DAO_Contact',
        'fields' => array(
          'display_name' => array(
            'title' => E::ts('Case Contact Name'),
            'default' => TRUE,
          ),
          'cc_id_key' => array(
            'name' => 'id',
            'no_display' => TRUE,
            'required' => TRUE,
          ),
          'cc_id' => array(
            'name' => 'id',
            'title' => E::ts('Case Contact ID'),
            'default' => TRUE,
          ),
        ),
        'order_bys' => array(
          'display_name' => array(
            'title' => 'Case Contact Name',
            'default' => TRUE,
            'default_weight' => 1,
          ),
          'cc_id' => array(
            'name' => 'id',
            'title' => 'Case Contact ID',
            'default' => TRUE,
            'default_weight' => 2,
          ),
        ),
        'filters' => array(
          'sort_name' => array(
            'title' => E::ts('Case Contact Name'),
            'operator' => 'like',
          ),
        ),
        'grouping' => 'contact-fields',
      ),
      'civicrm_email' => array(
        'dao' => 'CRM_Core_DAO_Email',
        'fields' => array(
          'email' => array(
            'title' => E::ts('Firm employee email'),
            'default' => TRUE,
          ),
        ),
        'grouping' => 'employee-fields',
      ),
      'TEMP_employee' => array(
        'fields' => array(
          'emp_display_name' => array(
            'title' => E::ts('Firm employee name'),
            'default' => TRUE,
          ),
          'emp_cid_key' => array(
            'name' => 'emp_cid',
            'no_display' => TRUE,
            'required' => TRUE,
          ),
          'emp_cid' => array(
            'title' => E::ts('Firm employee ID'),
            'default' => TRUE,
          ),
        ),
        'grouping' => 'employee-fields',
      ),
      'TEMP_caseactivity_assigned' => array(
        'fields' => array(
          'firm_name' => array(
            'title' => E::ts('Firm name'),
            'default' => TRUE,
          ),
          'firm_contact_id_key' => array(
            'name' => 'firm_contact_id',
            'no_display' => TRUE,
            'required' => TRUE,
          ),
          'firm_contact_id' => array(
            'title' => E::ts('Firm ID'),
            'default' => TRUE,
          ),
          'caseactivity_id_key' => array(
            'name' => 'id',
            'no_display' => TRUE,
            'required' => TRUE,
          ),
          'caseactivity_id' => array(
            'name' => 'id',
            'title' => E::ts('"Assigned" activity ID'),
            'default' => TRUE,
          ),
          'caseactivity_subject' => array(
            'name' => 'subject',
            'title' => E::ts('"Assigned" activity subject'),
            'default' => TRUE,
          ),
          'caseactivity_date_time' => array(
            'name' => 'activity_date_time',
            'title' => E::ts('"Assigned" activity date/time'),
            'default' => TRUE,
          ),
        ),
        'grouping' => 'activity-fields',
      ),
      'TEMP_caseactivity_dates' => array(
        'fields' => array(
          'matter_assigned' => array(
            'title' => E::ts('Last Matter Assigned'),
          ),
          'status_changed' => array(
            'title' => E::ts('Last Assigned/Completed status change'),
          ),
        ),
        'filters' => array(
          'matter_assigned' => array(
            'title' => E::ts('Last Matter Assigned'),
            'operatorType' => CRM_Report_Form::OP_DATE,
            'type' => CRM_Utils_Type::T_DATE,
          ),
          'status_changed' => array(
            'title' => E::ts('Last Assigned/Completed status change'),
            'operatorType' => CRM_Report_Form::OP_DATE,
            'type' => CRM_Utils_Type::T_DATE,
          ),
        ),
        'grouping' => 'activity-fields',
      ),
      'civicrm_country' => array(
        'fields' => array(
          'name' => array(
            'title' => E::ts('Jurisdiction'),
            'default' => TRUE,
          ),
        ),
        'filters' => array(
          'country_id' => array(
            'name' => 'id',
            'title' => E::ts('Jurisdiction (per output row)'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Core_BAO_Address::buildOptions('country_id'),
            'type' => CRM_Utils_Type::T_INT,
          ),
        ),
        'order_bys' => array(
          'jurisdiction' => array(
            'name' => 'name',
            'title' => 'Jurisdiction',
            // Would be nice to have this as a default sort, but there's a UX problem:
            // If the user de-selects the corresponding display column, they expect
            // that the number of rows will decrease (since the report displays
            // extra rows for distinct values on this table.column). However, if
            // we're sorting on this column, we need to include it in the query,
            // and therefore we'll still get those extra rows. Therefore, I'm deicding
            // to require the user to set this order_by manually if they want it,
            // so they have a better chance of understanding the implications for
            // extra rows.
            //
            // 'default' => TRUE,
            // 'default_weight' => 4,
          ),
        ),
        'grouping' => 'case-fields',
      ),
      'civicrm_option_value_ch' => array(
        'alias' => 'ov_ch',
        'fields' => array(
          'ch_label' => array(
            'dbAlias' => 'ov_ch_civireport.label',
            'title' => E::ts('Clearinghouse'),
            'default' => TRUE,
          ),
        ),
        'filters' => array(
          'clearinghouse_value' => array(
            'name' => 'value',
            'title' => E::ts('Clearinghouse (per output row)'),
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Core_BAO_OptionValue::getOptionValuesAssocArrayFromName('clearinghouse_20210729165514'),
            'type' => CRM_Utils_Type::T_STRING,
          ),
        ),
        'order_bys' => array(
          'clearinghouse' => array(
            'name' => 'label',
            'title' => 'Clearinghouse',
            // Would be nice to have this as a default sort, but there's a UX problem:
            // If the user de-selects the corresponding display column, they expect
            // that the number of rows will decrease (since the report displays
            // extra rows for distinct values on this table.column). However, if
            // we're sorting on this column, we need to include it in the query,
            // and therefore we'll still get those extra rows. Therefore, I'm deicding
            // to require the user to set this order_by manually if they want it,
            // so they have a better chance of understanding the implications for
            // extra rows.
            //
            // 'default' => TRUE,
            // 'default_weight' => 5,
          ),
        ),
        'grouping' => 'case-fields',
      ),
      'civicrm_value_matter_case_d_18' => array(
        'fields' => array(
          'legal_topics_35' => array(
            'title' => E::ts('Legal topics'),
            'default' => TRUE,
          ),
          'tracking_number_41' => array(
            'title' => E::ts('External tracking number'),
            'default' => TRUE,
          ),
        ),
        'grouping' => 'case-fields',
      ),
      'civicrm_option_value_cat' => array(
        'alias' => 'ov_cat',
        'fields' => array(
          'cat_label' => array(
            'dbAlias' => 'ov_cat_civireport.label',
            'title' => E::ts('Category'),
            'default' => TRUE,
          ),
        ),
        'grouping' => 'case-fields',
      ),
    );
    parent::__construct();
  }

  public function from() {
    $this->_from = NULL;

    $this->_from = "
      FROM
        civicrm_case {$this->_aliases['civicrm_case']}
        INNER JOIN civicrm_case_contact cc
          ON cc.case_id = {$this->_aliases['civicrm_case']}.id
        INNER JOIN civicrm_contact {$this->_aliases['civicrm_contact']}
          ON {$this->_aliases['civicrm_contact']}.id = cc.contact_id
    ";
    if (
      $this->isTableSelected('TEMP_caseactivity_assigned')
      || $this->isTableSelected('TEMP_employee')
    ) {
      $this->_from .= "
        LEFT JOIN TEMP_caseactivity_assigned {$this->_aliases['TEMP_caseactivity_assigned']}
          ON {$this->_aliases['TEMP_caseactivity_assigned']}.case_id = {$this->_aliases['civicrm_case']}.id
          AND ifnull({$this->_aliases['TEMP_caseactivity_assigned']}.firm_contact_id, 0) != {$this->_aliases['civicrm_contact']}.id
      ";
    }
    if (
      $this->isTableSelected('TEMP_employee')
      || $this->isTableSelected('civicrm_email')
    ) {
      $this->_from .= "
        LEFT JOIN TEMP_employee {$this->_aliases['TEMP_employee']}
          ON {$this->_aliases['TEMP_employee']}.activity_id = {$this->_aliases['TEMP_caseactivity_assigned']}.id
          AND {$this->_aliases['TEMP_employee']}.contact_id not in ({$this->_aliases['civicrm_contact']}.id, {$this->_aliases['TEMP_caseactivity_assigned']}.firm_contact_id)
          AND {$this->_aliases['TEMP_employee']}.contact_id_b = {$this->_aliases['TEMP_caseactivity_assigned']}.firm_contact_id
      ";
    }
    if ($this->isTableSelected('civicrm_email')) {
      $this->_from .= "
        LEFT JOIN civicrm_email {$this->_aliases['civicrm_email']}
          ON {$this->_aliases['civicrm_email']}.contact_id = {$this->_aliases['TEMP_employee']}.emp_cid AND {$this->_aliases['civicrm_email']}.is_primary
      ";
    }
    if (
      $this->isTableSelected('civicrm_value_matter_case_d_18')
      || $this->isTableSelected('civicrm_option_value_ch')
      || $this->isTableSelected('civicrm_option_value_cat')
      || $this->isTableSelected('civicrm_country')
    ) {
      $this->_from .= "
        LEFT JOIN civicrm_value_matter_case_d_18 {$this->_aliases['civicrm_value_matter_case_d_18']}
          ON {$this->_aliases['civicrm_value_matter_case_d_18']}.entity_id = {$this->_aliases['civicrm_case']}.id
      ";
    }
    if ($this->isTableSelected('civicrm_country')) {
      $this->_from .= "
        LEFT JOIN civicrm_country {$this->_aliases['civicrm_country']}
          ON {$this->_aliases['civicrm_value_matter_case_d_18']}.jurisdiction2_42 LIKE CONCAT('%', {$this->_aliases['civicrm_country']}.iso_code, '%')
      ";
    }
    if ($this->isTableSelected('civicrm_option_value_ch')) {
      $this->_from .= "
        LEFT JOIN civicrm_option_value {$this->_aliases['civicrm_option_value_ch']}
          ON {$this->_aliases['civicrm_option_value_ch']}.option_group_id = 121
          AND {$this->_aliases['civicrm_value_matter_case_d_18']}.clearinghouse_39 LIKE CONCAT('%', {$this->_aliases['civicrm_option_value_ch']}.value, '%')
      ";
    }
    if ($this->isTableSelected('civicrm_option_value_cat')) {
      $this->_from .= "
        LEFT JOIN civicrm_option_value {$this->_aliases['civicrm_option_value_cat']}
          ON {$this->_aliases['civicrm_option_value_cat']}.option_group_id = 119
          AND {$this->_aliases['civicrm_value_matter_case_d_18']}.category_37 = {$this->_aliases['civicrm_option_value_cat']}.value
     ";
    }
    if ($this->isTableSelected('TEMP_caseactivity_dates')) {
      $this->_from .= "
        LEFT JOIN TEMP_caseactivity_dates {$this->_aliases['TEMP_caseactivity_dates']}
          ON {$this->_aliases['TEMP_caseactivity_dates']}.case_id = {$this->_aliases['civicrm_case']}.id
     ";
    }
  }

  public function alterDisplay(&$rows) {
    // custom code to alter rows
    $entryFound = FALSE;
    $caseStatuses = CRM_Case_PseudoConstant::caseStatus();
    foreach ($rows as $rowNum => $row) {
      if (array_key_exists('civicrm_case_case_subject', $row)) {
        if ($value = $row['civicrm_case_case_subject']) {
          $url = CRM_Utils_System::url("civicrm/contact/view/case",
            "reset=1&id={$row['civicrm_case_case_id_key']}&cid={$row['civicrm_contact_cc_id_key']}&action=view",
            $this->_absoluteUrl
          );
          $rows[$rowNum]['civicrm_case_case_subject_link'] = $url;
          $rows[$rowNum]['civicrm_case_case_subject_hover'] = E::ts("Manage this Case.");
        }
        $entryFound = TRUE;
      }
      if (array_key_exists('civicrm_contact_display_name', $row)) {
        if ($value = $row['civicrm_contact_display_name']) {
          $url = CRM_Utils_System::url("civicrm/contact/view",
            "reset=1&cid={$row['civicrm_contact_cc_id_key']}",
            $this->_absoluteUrl
          );
          $rows[$rowNum]['civicrm_contact_display_name_link'] = $url;
          $rows[$rowNum]['civicrm_contact_display_name_hover'] = E::ts("View Contact Summary for this Contact.");
        }
        $entryFound = TRUE;
      }
      if (array_key_exists('TEMP_employee_emp_display_name', $row)) {
        if ($value = $row['TEMP_employee_emp_display_name']) {
          $url = CRM_Utils_System::url("civicrm/contact/view",
            "reset=1&cid={$row['TEMP_employee_emp_cid_key']}",
            $this->_absoluteUrl
          );
          $rows[$rowNum]['TEMP_employee_emp_display_name_link'] = $url;
          $rows[$rowNum]['TEMP_employee_emp_display_name_hover'] = E::ts("View Contact Summary for this Contact.");
        }
        $entryFound = TRUE;
      }
      if (array_key_exists('TEMP_caseactivity_assigned_firm_name', $row)) {
        if ($value = $row['TEMP_caseactivity_assigned_firm_name']) {
          $url = CRM_Utils_System::url("civicrm/contact/view",
            "reset=1&cid={$row['TEMP_caseactivity_assigned_firm_contact_id_key']}",
            $this->_absoluteUrl
          );
          $rows[$rowNum]['TEMP_caseactivity_assigned_firm_name_link'] = $url;
          $rows[$rowNum]['TEMP_caseactivity_assigned_firm_name_hover'] = E::ts("View Contact Summary for this Contact.");
        }
        $entryFound = TRUE;
      }
      if (array_key_exists('TEMP_caseactivity_assigned_caseactivity_subject', $row)) {
        if ($value = $row['TEMP_caseactivity_assigned_caseactivity_subject']) {
          $url = CRM_Utils_System::url("civicrm/activity",
            "action=view&reset=1&id={$row['TEMP_caseactivity_assigned_caseactivity_id_key']}",
            $this->_absoluteUrl
          );
          $rows[$rowNum]['TEMP_caseactivity_assigned_caseactivity_subject_link'] = $url;
          $rows[$rowNum]['TEMP_caseactivity_assigned_caseactivity_subject_hover'] = E::ts("View this Activity.");
        }
        $entryFound = TRUE;
      }
      if (array_key_exists('civicrm_case_status_id', $row)) {
        if ($value = $row['civicrm_case_status_id']) {
          $rows[$rowNum]['civicrm_case_status_id'] = $caseStatuses[$value];
        }
        $entryFound = TRUE;
      }
      if (array_key_exists('civicrm_address_country_id', $row)) {
        if ($value = $row['civicrm_address_country_id']) {
          $rows[$rowNum]['civicrm_address_country_id'] = CRM_Core_PseudoConstant::country($value, FALSE);
        }
        $entryFound = TRUE;
      }

      if (!$entryFound) {
        break;
      }
    }
  }

  /**
   * Depending on the value of $this->_debug, either indicate that the given
   * table should be temporary, or that it should be created as a regular table
   * for later review. For regular tables, drop the table in case it exists
   * already.
   *
   * @param  <type> $table_name
   * @return string MySQL keyword "TEMPORARY" if $this->_debug; else empty string.
   */
  public function _debug_temp_table($table_name) {
    if ($this->_debug) {
      $query = "DROP TABLE IF EXISTS {$table_name}";
      CRM_Core_DAO::executeQuery($query);
      $temporary = '';
    }
    else {
      $temporary = 'TEMPORARY';
    }
    return $temporary;
  }

  /**
   * Override parent::buildQuery in order to first build some temporary tables.
   */
  public function buildQuery($applyLimit = TRUE) {
    if ($this->isTableSelected('TEMP_caseactivity_assigned')) {
      $temporary = $this->_debug_temp_table('TEMP_caseactivity_assigned');
      $query = "
        CREATE $temporary TABLE TEMP_caseactivity_assigned
          (
            index (firm_contact_id),
            index (case_id)
          )
          select
            a.*,
            ca.case_id,
            c.id as firm_contact_id,
            c.display_name as firm_name
          from
            civicrm_case_activity ca
            INNER JOIN civicrm_activity a
              on a.id = ca.activity_id
              and a.activity_type_id = 83
            INNER JOIN civicrm_activity_contact ac
              on ac.activity_id = a.id
              and ac.record_type_id = 3
            LEFT JOIN civicrm_contact c
              on c.id = ac.contact_id
              and c.contact_type = 'organization'
      ";
      CRM_Core_DAO::executeQuery($query);
      $this->addToDeveloperTab($query);
    }

    if ($this->isTableSelected('TEMP_caseactivity_dates')) {
      $temporary = $this->_debug_temp_table('TEMP_caseactivity_dates');

      $query = "
        CREATE $temporary TABLE `TEMP_caseactivity_dates` (
          `case_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'Unique  Other Activity ID',
          `status_changed` datetime NULL DEFAULT NULL,
          `matter_assigned` datetime NULL DEFAULT NULL,
          KEY `case_id` (`case_id`),
          KEY `status_changed` (`status_changed`),
          KEY `matter_assigned` (`matter_assigned`)
        ) ENGINE=InnoDB
        ";
      CRM_Core_DAO::executeQuery($query);
      $this->addToDeveloperTab($query);
      $query = "
        INSERT INTO TEMP_caseactivity_dates
        SELECT 
          case_id, 
          NULLIF (
            GREATEST(
              MAX(if(action = 'status:completed', atime, '0')),
              MAX(if(action = 'status:assigned', atime, '0'))
            ), '0'
          ) as status_changed,
          NULLIF (
            MAX(
              if(action = 'matter_assigned', atime, '0')
            ), '0'
          ) as matter_assigned
        FROM (
          SELECT 
            ca.case_id, 
            a.activity_type_id, activity_date_time as atime,
            if(activity_type_id = 83, 'matter_assigned', concat('status:', REGEXP_REPLACE(a.subject, '^Case status changed from [a-zA-Z ]+ to ', ''))) as action
          FROM 
            civicrm_case_activity ca
            INNER JOIN civicrm_activity a ON a.id = ca.activity_id
          WHERE 
            a.activity_type_id in (16, 83)
        ) t
        GROUP BY case_id
      ";
      CRM_Core_DAO::executeQuery($query);
      $this->addToDeveloperTab($query);
    }

    if ($this->isTableSelected('TEMP_employee')) {
      $temporary = $this->_debug_temp_table('TEMP_employee');
      $query = "
        CREATE $temporary TABLE TEMP_employee
          (
            index(activity_id),
            index(contact_id),
            index(contact_id_b)
          )
          select distinct
            ac.activity_id,
            ac.contact_id,
            r.contact_id_b,
            c.display_name as emp_display_name,
            c.id as emp_cid
          from
            civicrm_activity_contact ac
            INNER JOIN TEMP_caseactivity_assigned a on a.id = ac.activity_id
            LEFT JOIN civicrm_contact c on c.id = ac.contact_id
            LEFT JOIN civicrm_relationship r on
              r.relationship_type_id = 4
              and r.contact_id_a = c.id
              and r.is_active
              and ifnull(r.end_date, now()) >= now()
          where
            ac.record_type_id = 3
      ";
      CRM_Core_DAO::executeQuery($query);
      $this->addToDeveloperTab($query);
    }

    // Now that we've built the temp tables, return the original report query SQL.
    return parent::buildQuery($applyLimit);
  }

  /**
   * Override parent method.
   *
   * CRM_Report_Form (parent) does not respect order_bys[]['default_weight'],
   * so we sort them here.
   *
   * @param type $formValues
   */
  public function preProcessOrderBy(&$formValues) {
    ksort($formValues['order_bys']);
    parent::preProcessOrderBy($formValues);
  }

}
