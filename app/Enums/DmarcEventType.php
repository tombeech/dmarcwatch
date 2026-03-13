<?php

namespace App\Enums;

enum DmarcEventType: string
{
    case NEW_SOURCE = 'new_source';
    case POLICY_FAILURE = 'policy_failure';
    case COMPLIANCE_DROP = 'compliance_drop';
    case SPF_FAILURE_SPIKE = 'spf_failure_spike';
    case DKIM_FAILURE_SPIKE = 'dkim_failure_spike';
    case DNS_RECORD_CHANGE = 'dns_record_change';
    case REPORT_RECEIVED = 'report_received';
}
