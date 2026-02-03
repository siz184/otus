<?php require_once (__DIR__.'/crest.php');

if(!empty($_POST['data']['FIELDS']['ID'])){

	$result = CRest::call(
		'crm.activity.get',
		[
			'id' => $_POST['data']['FIELDS']['ID']
		]
	);

	if(!empty($result['result']['OWNER_ID'])){

		$result = CRest::call(
			'crm.contact.update',
			[
				'id' => $result['result']['OWNER_ID'],
				'fields' => [
					'UF_CRM_1770047640805' => date('d.m.Y')
				]
			]
		);

	}

}