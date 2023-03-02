<?php
// This file declares a managed database record of type "ReportTemplate".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
return [
  [
    'name' => 'CRM_Pilnetreports_Form_Report_Caseanalysis',
    'entity' => 'ReportTemplate',
    'params' => [
      'version' => 3,
      'label' => 'Case Analysis',
      'description' => 'PILnet Case Analysis, suitable for CSV export',
      'class_name' => 'CRM_Pilnetreports_Form_Report_Caseanalysis',
      'report_url' => 'pilnetreports/caseanalysis',
      'component' => 'CiviCase',
    ],
  ],
];
