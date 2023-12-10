<?php

namespace NextDeveloper\CRM\Services\RiskManagement;

use NextDeveloper\CRM\Database\Models\Accounts;

class RiskManagementService
{
    private $_account;

    private $_user;

    private $_log = [];

    public function __construct( Accounts $account )
    {
        $this->_account = $account;
        $this->_user = $account->owner;
    }

    public function calculateRiskLevel() {
        $riskLevel = 70;

        if( $this->_account->is_suspended ) {
            $this->_account->risk_level = 200;
            return;
        }

        if( ! $this->_account->owner ) {
            return 200;
        }

        $riskLevel -= $this->getPointForName();
        $riskLevel -= $this->getPointForEmail();
        $riskLevel -= $this->getPointByEmailCountryRelation();
        $riskLevel -= $this->getPointForEmailVerification();
        $riskLevel -= $this->getPointForCellPhoneVerification();
        $riskLevel -= $this->getPointForIDVerification();

        return $riskLevel;
    }

    public function getLog() {
        return $this->_log;
    }

    public function getPointForName() {
        if($this->_user->name == $this->_user->surname) {
            $this->_log['name'][] = 'İsim ve soyad aynı. -50 puan.';
            return -50;
        }
    }

    public function getPointByEmailCountryRelation() {
        $points = 0;

        $explode = explode( '@', $this->_user->email );
        $email = $explode[ 0 ];
        $domain = $explode[ 1 ];

        //	Eğer domain'de .com.tr varsa 20 puan ekle
        if(strpos($domain, '.com.tr')) {
            $this->_log['emailCountryRelation'][] = 'Domainde .com.tr var. +20 puan.';
            $points += 20;
        }

        return $points;
    }

    public function getPointForEmail() {
        $points = 0;

        $explode = explode('@', $this->_user->email);
        $email = $explode[0];
        $domain = $explode[1];

        //  If account has no username, set risk level to top
        if( ! $this->_user->name )
            return 1000;

        if( ! $this->_user->surname )
            return 1000;

        //$mailgunValidator = Mailgun::validator()->validate( $this->_user->email );

        //  Eğer disposable email ise 100 puan düşür
        /*
        if( $mailgunValidator->is_disposable_address ) {
            $this->_log['email'][] = 'Email adresi disposable. -100 puan.';
            $points += -100;
        }
        */

        //	Eğer e-posta da rakam varsa 50 puan geriye at
        if(1 === preg_match('~[0-9]~', $email)) {
            $this->_log['email'][] = 'Email adresinde rakam var. -50 puan.';
            $points += -50;
        }

        //	Eğer domain'de rakam varsa 10 puan geriye at
        if(1 === preg_match('~[0-9]~', $domain)) {
            $this->_log['email'][] = 'Domainde rakam var. -10 puan.';
            $points += -10;
        }

        //	Eğer kullanıcının adı email'in de geçiyorsa
        if( strpos( strtolower( $email ), strtolower( $this->_user->name ) ) !== false ) {
            $this->_log['email'][] = 'Email adresi içinde adı geçiyor. +10 puan.';
            $points += 10;
        }

        if( strpos( strtolower( $email ), strtolower( $this->_user->surname ) ) !== false ) {
            //	@todo: burada hata var
            $this->_log['email'][] = 'Email adresi içinde soyadı geçiyor. +10 puan.';
            $points += 10;
        }

        return $points;
    }

    public function getPointForEmailVerification() {
        if( $this->_user->email_verification_date != null ) {
            $this->_log['emailVerification'][] = 'Email adresi onaylanmış. +5 puan.';
            return +5;
        }
    }

    public function getPointForCellPhoneVerification() {
        if( $this->_user->cellphone_verification_date != null ) {
            $this->_log['cell_phone'][] = 'Cep telefonu onaylanmış. +5 puan.';
            return +10;
        }
    }

    public function getPointForIDVerification() {
        if( $this->_user->nin_verification_date != null ) {
            $this->_log['id_verification'][] = 'Kimlik onaylanmış. +5 puan.';
            return +20;
        }
    }

}
