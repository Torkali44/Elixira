<?php

namespace App\Support;

use App\Models\DxnTeamRequest;

class DxnApplicationService
{
    public function adminWhatsAppNumber(): string
    {
        return preg_replace('/\D/', '', (string) config('dxn.admin_whatsapp', '9665674618916'));
    }

    public function whatsAppUrlForApplication(DxnTeamRequest $application): string
    {
        return 'https://wa.me/'.$this->adminWhatsAppNumber().'?text='.rawurlencode($this->buildAdminMessage($application));
    }

    public function buildAdminMessage(DxnTeamRequest $application): string
    {
        if ($application->application_type === 'existing_member') {
            return $this->buildExistingMemberMessage($application);
        }

        $lines = [
            __('dxn_team.whatsapp_new_title'),
            '—',
            __('dxn_team.form_name').': '.$application->name,
            __('dxn_team.form_email').': '.$application->email,
            __('dxn_team.form_phone').': '.$application->phone,
            __('dxn_team.form_country').': '.($application->country ?: '—'),
            __('dxn_team.form_sponsor_code').': '.($application->sponsor_code ?: '—'),
            __('dxn_team.form_sponsor_name').': '.($application->sponsor_name ?: '—'),
        ];

        if ($application->gender) {
            $lines[] = __('dxn_team.form_gender').': '.__('dxn_team.gender_'.$application->gender);
        }
        if ($application->date_of_birth) {
            $lines[] = __('dxn_team.form_dob').': '.$application->date_of_birth->format('Y-m-d');
        }
        if ($application->id_number) {
            $lines[] = __('dxn_team.form_id_number').': '.$application->id_number;
        }
        if ($application->passport_number) {
            $lines[] = __('dxn_team.form_passport_number').': '.$application->passport_number;
        }
        if ($application->nationality) {
            $lines[] = __('dxn_team.form_nationality').': '.$application->nationality;
        }
        if ($application->address) {
            $lines[] = __('dxn_team.form_address').': '.$application->address;
            $lines[] = __('dxn_team.form_address_city').': '.($application->address_city ?: '—');
            $lines[] = __('dxn_team.form_postal_code').': '.($application->postal_code ?: '—');
        }

        $lines[] = '—';
        $lines[] = __('dxn_team.whatsapp_request_id').': #'.$application->id;

        return implode("\n", $lines);
    }

    protected function buildExistingMemberMessage(DxnTeamRequest $application): string
    {
        $lines = [
            __('dxn_team.whatsapp_existing_title'),
            '—',
            __('dxn_team.existing_member_code').': '.($application->member_code ?: '—'),
            __('dxn_team.form_name').': '.$application->name,
            __('dxn_team.form_email').': '.$application->email,
            __('dxn_team.form_phone').': '.$application->phone,
        ];

        if ($application->message) {
            $lines[] = __('dxn_team.form_message').': '.$application->message;
        }

        $lines[] = '—';
        $lines[] = __('dxn_team.whatsapp_request_id').': #'.$application->id;

        return implode("\n", $lines);
    }
}
