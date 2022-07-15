<?php

namespace Riclep\StoryblokForms\Tests;


use App\Rules\Address;
use App\Storyblok\Forms\FormResponse;
use Illuminate\Http\Request;
use Riclep\StoryblokForms\Blocks\LsfConditionalSelect;
use Riclep\StoryblokForms\Blocks\LsfFieldset;
use Riclep\StoryblokForms\Blocks\LsfForm;
use Riclep\StoryblokForms\Blocks\LsfInput;
use Riclep\StoryblokForms\Blocks\LsfRepeatingFieldset;


class InputTest extends TestCase
{
	private function getBlockContents($index) {
		$story = json_decode(file_get_contents(__DIR__ . '/Fixtures/all-fields.json'), true);
		return $story['story']['content']['fields'][$index];
	}

	private function getPageContents() {
		$story = json_decode(file_get_contents(__DIR__ . '/Fixtures/all-fields.json'), true);
		return $story['story']['content'];
	}

	/** @test */
	public function can_create_basic_input_validation_rules() {
		$definition = json_decode('{"_uid": "c4382f23-4444-4fb7-8de2-74aae551eaa4","help": "","name": "field_name","type": "text","label": "First Name","component": "lsf-input","validators": [{"component": "lsf-validator-required","error_message": "This field is required"},{"component": "lsf-validator-min","parameter": "0","error_message": "This field is required"}],"placeholder": ""}', true);

		$field = new LsfInput($definition, null);

		$validationRules = $field->validationRules();

		$this->assertEquals(['field_name' => ['required', 'min:0']], $validationRules);
	}

	/** @test */
	public function can_create_class_input_validation_rules() {
		$definition = json_decode('{"_uid": "c4382f23-4444-4fb7-8de2-74aae551eaa4","help": "","name": "field_name","type": "text","label": "First Name","component": "lsf-input","validators": [{"component": "lsf-validator-required","error_message": "This field is required"},{"component": "lsf-validator-class","parameter": "","class": "Address","error_message": "This field is required"}],"placeholder": ""}', true);

		$field = new LsfInput($definition, null);

		$validationRules = $field->validationRules();

		$this->assertEquals(['field_name' => ['required', new Address()]], $validationRules);
	}

	/** @test */
	public function can_create_validation_dot_names_for_nested_conditional_fields() {
		$form = new LsfForm($this->getPageContents(), null);
		$field = $form->fields[1]->fields[0];

		$this->assertStringContainsString('"validators":["required_if:client_details.client_2.selected,4,5"]', (string) $field);
	}

	/** @test */
	public function can_add_default_data_to_json() {
		$definition = json_decode('{"max":"10","min":"","name":"basic_6","label":"The label","fields":[{"help":"","name":"basic_7","type":"date","label":"Date of claim","settings":[],"component":"lsf-input","validators":[],"placeholder":""}],"settings":[],"component":"lsf-repeating-fieldset","item_name":""}', true);

		$field = new LsfRepeatingFieldset($definition, null);

		$this->assertStringContainsString('"item_name":"Item"', (string) $field);
	}

	/** @test */
	public function can_create_dot_names_for_nested_conditional_fields() {
		$form = new LsfForm($this->getPageContents(), null);
		$field = $form->fields[1]->fields[0];

		$this->assertStringContainsString('"dot_name":"client_details.client_2.client_3"', (string) $field);
	}

	/** @test */
	public function can_create_dot_names_for_repeatable_fields() {
		$definition = json_decode('{"max":"10","min":"","name":"basic_6","label":"The label","fields":[{"help":"","name":"basic_7","type":"date","label":"Date of claim","settings":[],"component":"lsf-input","validators":[],"placeholder":""},{"help":"Enter numbers only","name":"basic_8","type":"number","label":"Value of claim","settings":[],"component":"lsf-input","validators":[],"placeholder":""},{"name":"basic_9","label":"Is the claim settled?","settings":[],"component":"lsf-radio-button","validators":[],"radio_buttons":"[true]Yes\n[false]No"},{"help":"","name":"basic_10","size":"","label":"What was the cause of the claim?","options":"[1]Accidental Damage\n[2]Accidental Damage to Audio/Visual Equipment\n[3]Accidental Loss\n[4]Accidental Loss Of Contents\n[5]Aircraft\n[6]All Risks - If A Type Not Already Listed\n[7]Breakage/Collapse Of TV Aerials","settings":[],"component":"lsf-select","validators":[],"placeholder":"Please select","show_empty_option":true},{"help":"","name":"basic_11","type":"text","label":"Postcode of the premises where the claim happened","settings":[],"component":"lsf-input","validators":[],"placeholder":""}],"settings":[],"component":"lsf-repeating-fieldset","item_name":"Claim"}', true);

		$field = new LsfRepeatingFieldset($definition, null);

		$this->assertStringContainsString('"dot_name":"basic_6.*.basic_7"', (string) $field);
	}

	/** @test */
	public function real_world_can_get_fieldset_validation_rules() {
		$fieldset = new LsfFieldset($this->getBlockContents(1), null);

		$rules = [
			"client_details.client_2.client_3" => [
					"required_if:client_details.client_2.selected,4,5"
				],
			"client_details.client_2.client_4" => [
					"required_if:client_details.client_2.selected,1,2,3"
				],
			"client_details.client_2.client_5" => [
					"required_if:client_details.client_2.selected,1,2,3"
				],
			"client_details.client_2.client_6" => [
					"required_if:client_details.client_2.selected,1,2,3"
				],
			"client_details.client_2.client_7" => [
					"required_if:client_details.client_2.selected,3"
				],
			"client_details.client_2.selected" => [
					"required"
				],
			"client_details.client_9" => [
					new \App\Rules\Address()
				],
			"client_details.client_14.*.client_15" => [
					"required"
				]
		];

		$this->assertEquals($rules, $fieldset->validationRules());
	}

	/** @test */
	public function real_world_can_get_form_validation_rules() {
		$form = new LsfForm($this->getPageContents(), null);

		$rules = [
			"broker_details.product_type" => [
				"required",
			],
			"client_details.client_2.client_3" => [
				"required_if:client_details.client_2.selected,4,5",
			],
			"client_details.client_2.client_4" => [
				"required_if:client_details.client_2.selected,1,2,3",
			],
			"client_details.client_2.client_5" => [
				"required_if:client_details.client_2.selected,1,2,3",
			],
			"client_details.client_2.client_6" => [
				"required_if:client_details.client_2.selected,1,2,3",
			],
			"client_details.client_2.client_7" => [
				"required_if:client_details.client_2.selected,3",
			],
			"client_details.client_2.selected" => [
				"required",
			],
			"client_details.client_9" => [
				new \App\Rules\Address(),
			],
			"client_details.client_14.*.client_15" => [
				"required",
			],
			"basic_risk_details.basic_1" => [
				"required",
				new \App\Rules\NextThirtyDays(),

			],
			"basic_risk_details.basic_2" => [
				"required",
			],
			"basic_risk_details.basic_3" => [
				new \App\Rules\RoundNumber(),
			],
			"basic_risk_details.basic_4" => [
				"required",
			],
			"basic_risk_details.basic_5" => [
				"required",
			],
			"basic_risk_details.basic_6.*.basic_7" => [
				"required",
				1 => new \App\Rules\PastDate()
			],
			"basic_risk_details.basic_6.*.basic_8" => [
				new \App\Rules\RoundNumber(),
				"required",
				"min:0",
				"numeric",
			],
			"basic_risk_details.basic_6.*.basic_9" => [
				"required",
			],
			"basic_risk_details.basic_6.*.basic_10" => [
				"required",
			],
			"basic_risk_details.basic_6.*.basic_11" => [
				"required",
			],
			"premises_to_be_insured.premises.*.risk_address_1" => [
				"required",
			],
			"premises_to_be_insured.premises.*.risk_address_6" => [
				"required",
			],
			"premises_to_be_insured.premises.*.risk_address_7" => [
				"required",
			],
			"premises_to_be_insured.premises.*.risk_address_8.risk_address_9" => [
				"required_if:premises_to_be_insured.premises.*.risk_address_8.selected,1,2,3",
			],
			"premises_to_be_insured.premises.*.risk_address_8.risk_address_10.risk_address_11" => [
				new \App\Rules\RoundNumber(),
				"required_if:premises_to_be_insured.premises.*.risk_address_8.risk_address_10.selected,true",
			],
			"premises_to_be_insured.premises.*.risk_address_8.risk_address_10.selected" => [
				"required_if:premises_to_be_insured.premises.*.risk_address_8.selected,2",
			],
			"premises_to_be_insured.premises.*.risk_address_8.risk_address_11a" => [
				"required_if:premises_to_be_insured.premises.*.risk_address_8.selected,2",
			],
			"premises_to_be_insured.premises.*.risk_address_8.selected" => [
				"required",
			],
			"premises_to_be_insured.premises.*.risk_address_12" => [
				"required",
			],
			"premises_to_be_insured.premises.*.risk_address_13.walls.*.risk_address_14" => [
				"required",
			],
			"premises_to_be_insured.premises.*.risk_address_13.walls.*.risk_address_15" => [
				"required",
				new \App\Rules\BuildingMaterialsTotal(),
			],
			"premises_to_be_insured.premises.*.risk_address_13.selected" => [
				"required",
			],
			"premises_to_be_insured.premises.*.risk_address_17.roofing.*.risk_address_18" => [
				"required",
				new \App\Rules\BuildingMaterialsTotal(),
			],
			"premises_to_be_insured.premises.*.risk_address_17.roofing.*.risk_address_19" => [
				"required",
			],
			"premises_to_be_insured.premises.*.risk_address_17.selected" => [
				"required",
			],
			"premises_to_be_insured.premises.*.risk_address_21" => [
				"required",
			],
			"premises_to_be_insured.premises.*.risk_address_22" => [
				"required",
			],
			"premises_to_be_insured.premises.*.risk_address_23" => [
				"required",
			],
			"premises_to_be_insured.premises.*.risk_address_24" => [
				"required",
			],
			"premises_to_be_insured.premises.*.risk_address_25" => [
				"required",
			],
			"premises_to_be_insured.premises.*.risk_address_26" => [
				"required",
			],
			"premises_to_be_insured.premises.*.risk_address_27.risk_address_28" => [
				"required_if:premises_to_be_insured.premises.*.risk_address_27.selected,true",
			],
			"premises_to_be_insured.premises.*.risk_address_27.selected" => [
				"required",
			],
			"premises_to_be_insured.premises.*.risk_address_29" => [
				"required",
			],
			"premises_to_be_insured.premises.*.risk_address_30" => [
				"required",
			],
			"premises_to_be_insured.premises.*.risk_address_31" => [
				"required",
			],
			"premises_to_be_insured.premises.*.risk_address_32.*.risk_address_33" => [
				"required",
			],
			"premises_to_be_insured.premises.*.risk_address_32.*.risk_address_34" => [
				new \App\Rules\Address(),
			],
			"premises_to_be_insured.premises.*.risk_address_40.risk_address_41" => [
				new \App\Rules\RoundNumber(),
				"required_if:premises_to_be_insured.premises.*.risk_address_40.selected,1",
				"min:0",
				"max:5000000",
				"numeric",
			],
			"premises_to_be_insured.premises.*.risk_address_40.selected" => [
				"required",
			],
			"premises_to_be_insured.premises.*.risk_address_42.risk_address_43" => [
				new \App\Rules\RoundNumber(),
				"required_if:premises_to_be_insured.premises.*.risk_address_42.selected,1",
				"min:0",
				"max:1000000",
				"numeric",
			],
			"premises_to_be_insured.premises.*.risk_address_42.selected" => [
				"required",
			],
			"premises_to_be_insured.premises.*.risk_address_44" => [
				"required",
			],
			"premises_to_be_insured.premises.*.risk_address_45" => [
				new \App\Rules\RoundNumber(),
				"required",
				"max:1500000",
				"numeric",
			],
			"premises_to_be_insured.premises.*.risk_address_46" => [
				"required",
			],
			"premises_to_be_insured.premises.*.risk_address_47" => [
				"required",
			],
			"liab.liab_1" => [
				"required",
			],
			"liab.liab_2.liab_3" => [
				new \App\Rules\RoundNumber(),
				"required_if:liab.liab_2.selected,1",
				"numeric",
				"min:0",
			],
			"liab.liab_2.selected" => [
				"required",
			]
		];

		$this->assertEquals($rules, $form->validationRules());
	}

	/** @test */
	public function can_get_fieldset_validation_errors() {
		$fieldset = new LsfFieldset($this->getBlockContents(1), null);

		$errors = [
			"client_details.client_2.client_3.required_if" => "This field is required",
			"client_details.client_2.client_4.required_if" => "The Title field is required.",
			"client_details.client_2.client_5.required_if" => "This field is required",
			"client_details.client_2.client_6.required_if" => "This field is required",
			"client_details.client_2.client_7.required_if" => "This field is required",
			"client_details.client_2.selected.required" => "This field is required",
			"client_details.client_9" => "Address line 1, town and postcode are required.",
			"client_details.client_14.*.client_15.required" => "This field is required",
		];

		$this->assertEquals($errors, $fieldset->errorMessages());
	}

	/** @test */
	public function real_world_can_get_form_validation_errors() {
		$fieldset = new LsfForm($this->getPageContents(), null);

		$errors = [
			"broker_details.product_type.required" => "The Product type field is required.",
			"client_details.client_2.client_3.required_if" => "This field is required",
			"client_details.client_2.client_4.required_if" => "The Title field is required.",
			"client_details.client_2.client_5.required_if" => "This field is required",
			"client_details.client_2.client_6.required_if" => "This field is required",
			"client_details.client_2.client_7.required_if" => "This field is required",
			"client_details.client_2.selected.required" => "This field is required",
			"client_details.client_9" => "Address line 1, town and postcode are required.",
			"client_details.client_14.*.client_15.required" => "This field is required",
			"basic_risk_details.basic_1.required" => "This field is required",
			"basic_risk_details.basic_1" => "The date must be within the next 30 days",
			"basic_risk_details.basic_2.required" => "This field is required",
			"basic_risk_details.basic_3" => "This must be a round number",
			"basic_risk_details.basic_4.required" => "This field is required",
			"basic_risk_details.basic_5.required" => "This field is required",
			"basic_risk_details.basic_6.*.basic_7.required" => "This field is required",
			"basic_risk_details.basic_6.*.basic_7" => "The date must be in the past",
			"basic_risk_details.basic_6.*.basic_8" => "This must be a round number",
			"basic_risk_details.basic_6.*.basic_8.required" => "This field is required",
			"basic_risk_details.basic_6.*.basic_8.min:0" => "Value can not be negative",
			"basic_risk_details.basic_6.*.basic_8.numeric" => "The Value of claim must be a number.",
			"basic_risk_details.basic_6.*.basic_9.required" => "This field is required",
			"basic_risk_details.basic_6.*.basic_10.required" => "This field is required",
			"basic_risk_details.basic_6.*.basic_11.required" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_1.required" => "The Premises Address field is required.",
			"premises_to_be_insured.premises.*.risk_address_6.required" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_7.required" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_8.risk_address_9.required_if" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_8.risk_address_10.risk_address_11" => "This must be a round number",
			"premises_to_be_insured.premises.*.risk_address_8.risk_address_10.risk_address_11.required_if" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_8.risk_address_10.selected.required_if" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_8.risk_address_11a.required_if" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_8.selected.required" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_12.required" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_13.walls.*.risk_address_14.required" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_13.walls.*.risk_address_15.required" => "The Please select the % of Wall Construction: field is required.",
			"premises_to_be_insured.premises.*.risk_address_13.walls.*.risk_address_15" => "The total must add up to no more than 100%",
			"premises_to_be_insured.premises.*.risk_address_13.selected.required" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_17.roofing.*.risk_address_18.required" => "The Please select the type of Roof Construction? field is required.",
			"premises_to_be_insured.premises.*.risk_address_17.roofing.*.risk_address_18" => "The total must add up to no more than 100%",
			"premises_to_be_insured.premises.*.risk_address_17.roofing.*.risk_address_19.required" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_17.selected.required" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_21.required" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_22.required" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_23.required" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_24.required" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_25.required" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_26.required" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_27.risk_address_28.required_if" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_27.selected.required" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_29.required" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_30.required" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_31.required" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_32.*.risk_address_33.required" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_32.*.risk_address_34" => "Address line 1, town and postcode are required.",
			"premises_to_be_insured.premises.*.risk_address_40.risk_address_41" => "This must be a round number",
			"premises_to_be_insured.premises.*.risk_address_40.risk_address_41.min:0" => "Value can not be negative",
			"premises_to_be_insured.premises.*.risk_address_40.risk_address_41.max:5000000" => "Maximum value is £5m",
			"premises_to_be_insured.premises.*.risk_address_40.risk_address_41.numeric" => "The Buildings Declared Value: must be a number.",
			"premises_to_be_insured.premises.*.risk_address_40.risk_address_41.required_if" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_40.selected.required" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_42.risk_address_43" => "This must be a round number",
			"premises_to_be_insured.premises.*.risk_address_42.risk_address_43.min:0" => "Value can not be negative",
			"premises_to_be_insured.premises.*.risk_address_42.risk_address_43.max:1000000" => "Maximum value is £1m",
			"premises_to_be_insured.premises.*.risk_address_42.risk_address_43.numeric" => "The Landlords Contents Declared Value: must be a number.",
			"premises_to_be_insured.premises.*.risk_address_42.risk_address_43.required_if" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_42.selected.required" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_44.required" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_45" => "This must be a round number",
			"premises_to_be_insured.premises.*.risk_address_45.required" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_45.max:1500000" => "Maximum value is £1.5m",
			"premises_to_be_insured.premises.*.risk_address_45.numeric" => "The Loss of Rent sum insured: must be a number.",
			"premises_to_be_insured.premises.*.risk_address_46.required" => "This field is required",
			"premises_to_be_insured.premises.*.risk_address_47.required" => "This field is required",
			"liab.liab_1.required" => "This field is required",
			"liab.liab_2.liab_3" => "This must be a round number",
			"liab.liab_2.liab_3.numeric" => "The Annual wageroll for Employees? must be a number.",
			"liab.liab_2.liab_3.min:0" => "This value can’t be negative",
			"liab.liab_2.liab_3.required_if" => "This field is required",
			"liab.liab_2.selected.required" => "This field is required",
		];

		$this->assertEquals($errors, $fieldset->errorMessages());
	}

	/** @test */
	public function real_world_can_format_form_response() {
		$request = new Request();
		$request->replace(json_decode('{"form":{"_slug":"portal/insurance/sme/form","broker_details.product_type":"1","client_details.client_2.selected":"1","client_details.client_8":"","client_details.client_9":"","basic_risk_details.basic_1":"","basic_risk_details.basic_2":"","basic_risk_details.basic_3":"","basic_risk_details.basic_4":"","basic_risk_details.basic_5":"","liab.liab_1":"2","liab.liab_2.selected":"","liab.test":"","premises_to_be_insured.premises.0.risk_address_1":"","premises_to_be_insured.premises.0.risk_address_6":"","premises_to_be_insured.premises.0.risk_address_7":"","premises_to_be_insured.premises.0.risk_address_8.selected":"2","premises_to_be_insured.premises.0.risk_address_12":"","premises_to_be_insured.premises.0.risk_address_13.selected":"","premises_to_be_insured.premises.0.risk_address_17.selected":"","premises_to_be_insured.premises.0.risk_address_21":"","premises_to_be_insured.premises.0.risk_address_22":"","premises_to_be_insured.premises.0.risk_address_23":"","premises_to_be_insured.premises.0.risk_address_24":"","premises_to_be_insured.premises.0.risk_address_25":"","premises_to_be_insured.premises.0.risk_address_26":"","premises_to_be_insured.premises.0.risk_address_27.selected":"","premises_to_be_insured.premises.0.risk_address_29":"","premises_to_be_insured.premises.0.risk_address_30":"","premises_to_be_insured.premises.0.risk_address_31":"","premises_to_be_insured.premises.0.risk_address_40.selected":"","premises_to_be_insured.premises.0.risk_address_42.selected":"","premises_to_be_insured.premises.0.risk_address_44":"","premises_to_be_insured.premises.0.risk_address_45":"","premises_to_be_insured.premises.0.risk_address_46":"","premises_to_be_insured.premises.0.risk_address_47":"","premises_to_be_insured.premises.0.risk_address_8.risk_address_9":"","premises_to_be_insured.premises.0.risk_address_8.risk_address_10.selected":"","premises_to_be_insured.premises.0.risk_address_8.risk_address_11a":"","client_details.client_2.client_4":"","client_details.client_2.client_5":"","client_details.client_2.client_6":"","client_details.client_2.client_4":"captain"},"step":1}', true));


		$formResponse = new FormResponse($request, $this->makePage());

		$formatted = require('Fixtures/all-fields-response.php');

		$this->assertEquals($formatted, $formResponse->response());
	}
}

