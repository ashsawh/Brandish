<?php namespace Brandish\Services\Validation\Containers;
use Brandish\Services\Containers as Containers;
use Brandish\Services\Messaging as Messaging;

class ValidationDiction implements Containers\ContainerFacade {
	public function getContainer() {
		$diction = new Messaging\Diction('en_US');
		$diction->add('required', ':attribute: is required.');
		$diction->add('characters', ':attribute: must between :min: and :max: total characters.');
		$diction->add('longer', ':attribute: must be longer than :max: characters.' );
		$diction->add('shorter', ':attribute: must be shorter than :min: characters.' );
		$diction->add('wide', ':attribute: must be exactly :value: characters wide.' );
		$diction->add('longer', ':attribute: must be more than :max:.' );
		$diction->add('shorter', ':attribute: must be less than :min:.' );
		$diction->add('exact', ':attribute: must be exactly :value:.' );
		$diction->add('between', ':attribute: must between :min: and :max:.');
		$diction->add('alpha', ':attribute: is limited to alphabetical characters.');
		$diction->add('alphanumeric', ':attribute: is limited to numerical and alphabetical characters.');
		$diction->add('sentence', ':attribute: is limited to characters typically used in sentences.');
		$diction->add('numeric', ':attribute: is limited to numerical characters.');
		$diction->add('bool', ':attribute: must be a boolean.');
		$diction->add('email', ':attribute: is an invalid email address.');
		$diction->add('url', ':attribute: is an invalid url.');
		$diction->add('ip', ':attribute: is an invalid ip address.');
		$diction->add('float', ':attribute: must be a floating point number.');
		$diction->add('username', ':attribute: is not an acceptable username.');
		$diction->add('password', ':attribute: is not an acceptable password.');
		$diction->add('complexpassword', ':attribute: is does not meet the password criteria.');
		$diction->add('in', ':attribute: is limited to :values:.');
		$diction->add('notin', ':attribute: can\'t be :values:.');
		$diction->add('accepted', ':attribute: must be accepted.');
		$diction->add('confirmed', ':attribute: confirmation must match :value:.');	
		$diction->add('same', ':attribute: must be the same as :value:.');
		$diction->add('uniqueindb', ':attribute: is not unique.');
		$diction->add('indb', ':attribute: does not exist.');
		return $diction;
	}
}