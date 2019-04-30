<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\entities\Site;
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\resources\Form;
use cmsgears\core\common\models\resources\FormField;

use cmsgears\core\common\utilities\DateUtil;

/**
 * The paypal rest data migration inserts the base data required to run the application.
 *
 * @since 1.0.0
 */
class m160623_021950_paytm_data extends \cmsgears\core\common\base\Migration {

	// Public Variables

	// Private Variables

	private $prefix;

	private $site;

	private $master;

	public function init() {

		// Table prefix
		$this->prefix = Yii::$app->migration->cmgPrefix;

		// Site config
		$this->site		= Site::findBySlug( CoreGlobal::SITE_MAIN );
		$this->master	= User::findByUsername( Yii::$app->migration->getSiteMaster() );

		Yii::$app->core->setSite( $this->site );
	}

    public function up() {

		// Create various config
		$this->insertPaytmConfig();

		// Init default config
		$this->insertDefaultConfig();
    }

	private function insertPaytmConfig() {

		$this->insert( $this->prefix . 'core_form', [
            'siteId' => $this->site->id,
            'createdBy' => $this->master->id, 'modifiedBy' => $this->master->id,
            'name' => 'Config Paytm', 'slug' => 'config-paytm',
            'type' => CoreGlobal::TYPE_SYSTEM,
            'description' => 'Paytm configuration form.',
            'success' => 'All configurations saved successfully.',
            'captcha' => false,
            'visibility' => Form::VISIBILITY_PROTECTED,
            'status' => Form::STATUS_ACTIVE, 'userMail' => false,'adminMail' => false,
            'createdAt' => DateUtil::getDateTime(),
            'modifiedAt' => DateUtil::getDateTime()
        ]);

		$config	= Form::findBySlugType( 'config-paytm', CoreGlobal::TYPE_SYSTEM );

		$columns = [ 'formId', 'name', 'label', 'type', 'compress', 'meta', 'active', 'validators', 'order', 'icon', 'htmlOptions' ];

		$fields	= [
			[ $config->id, 'status', 'Status', FormField::TYPE_SELECT, false, true, true, 'required', 0, NULL, '{"title":"Status","items":{"staging":"Staging","production":"Production"}}' ],
			[ $config->id, 'payments', 'Payments', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Payments Enabled"}' ],
			[ $config->id, 'mid_staging', 'Staging ID', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Staging MID","placeholder":"Staging MID"}' ],
			[ $config->id, 'mid_production', 'Production ID', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Production MID","placeholder":"Production MID"}' ],
			[ $config->id, 'key_staging', 'Staging Key', FormField::TYPE_PASSWORD, false, true, true, 'required', 0, NULL, '{"title":"Staging Key","placeholder":"Staging Key"}' ],
			[ $config->id, 'key_production', 'Production Key', FormField::TYPE_PASSWORD, false, true, true, 'required', 0, NULL, '{"title":"Production Key","placeholder":"Production Key"}' ],
			[ $config->id, 'website_staging', 'Staging Website', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Staging Website","placeholder":"Staging Website"}' ],
			[ $config->id, 'website_production', 'Production Website', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Production Website","placeholder":"Production Website"}' ],
			[ $config->id, 'industry_staging', 'Staging Industry', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Staging Industry","placeholder":"Staging Industry"}' ],
			[ $config->id, 'industry_production', 'Production Industry', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Production Industry","placeholder":"Production Industry"}' ],
			[ $config->id, 'callback_url', 'Callback URL', FormField::TYPE_TEXT, false, true, true, 'required', 0, NULL, '{"title":"Callback URL","placeholder":"Callback URL"}' ],
			[ $config->id, 'payment_mode_only', 'Payment Mode Only', FormField::TYPE_TOGGLE, false, true, true, 'required', 0, NULL, '{"title":"Payment Mode Only"}' ],
			[ $config->id, 'auth_mode', 'Auth Mode', FormField::TYPE_SELECT, false, true, true, 'required', 0, NULL, '{"title":"Status","items":{"-1":"Choose",3D":"3D","USRPWD":"USRPWD"}}' ],
			[ $config->id, 'payment_type_id', 'Payment Type Id', FormField::TYPE_SELECT, false, true, true, 'required', 0, NULL, '{"title":"Status","items":{"-1":"Choose","CC":"Credit Card Mode","DC":"Debit Card Mode","NB":"Net Banking Mode","PPI":"Paytm Wallet","EMI":"EMI","UPI":"UPI"}}' ],
			[ $config->id, 'bank_code', 'Bank Code', FormField::TYPE_SELECT, false, true, true, 'required', 0, NULL, '{"title":"Bank Code","items":{"-1":"Choose","ICICI":"ICICI","HDFC":"HDFC","AXIS":"AXIS Bank"}}' ]
		];

		$this->batchInsert( $this->prefix . 'core_form_field', $columns, $fields );
	}

	private function insertDefaultConfig() {

		$columns = [ 'modelId', 'name', 'label', 'type', 'active', 'valueType', 'value', 'data' ];

		$attributes	= [
			[ $this->site->id, 'status', 'Status', 'paytm', 1, 'text', NULL, NULL ],
			[ $this->site->id, 'payments', 'Payments', 'paytm', 1, 'flag', '0', NULL ],
			[ $this->site->id, 'mid_staging', 'Staging ID', 'paytm', 1, 'text', NULL, NULL ],
			[ $this->site->id, 'mid_production', 'Production ID', 'paytm', 1, 'text', NULL, NULL ],
			[ $this->site->id, 'key_staging', 'Staging Key', 'paytm', 1, 'text', NULL, NULL ],
			[ $this->site->id, 'key_production', 'Production Key', 'paytm', 1, 'text', NULL, NULL ],
			[ $this->site->id, 'website_staging', 'Staging Website', 'paytm', 1, 'text', NULL, NULL ],
			[ $this->site->id, 'website_production', 'Production Website', 'paytm', 1, 'text', NULL, NULL ],
			[ $this->site->id, 'industry_staging', 'Staging Industry', 'paytm', 1, 'text', NULL, NULL ],
			[ $this->site->id, 'industry_production', 'Production Industry', 'paytm', 1, 'text', NULL, NULL ],
			[ $this->site->id, 'callback_url', 'Callback URL', 'paytm', 1, 'text', NULL, NULL ],
			[ $this->site->id, 'payment_mode_only', 'Payment Mode Only', 'paytm', 1, 'flag', '0', NULL ],
			[ $this->site->id, 'auth_mode', 'Auth Mode', 'paytm', 1, 'text', '-1', NULL ],
			[ $this->site->id, 'payment_type_id', 'Payment Type Id', 'paytm', 1, 'text', '-1', NULL ],
			[ $this->site->id, 'bank_code', 'Bank Code', 'paytm', 1, 'text', '-1', NULL ],
		];

		$this->batchInsert( $this->prefix . 'core_site_meta', $columns, $attributes );
	}

    public function down() {

        echo "m160623_021950_paytm_data will be deleted with m160621_014408_core.\n";

        return true;
    }

}
