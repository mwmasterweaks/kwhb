<?php

namespace App\Myob\Model;

use App\Myob\Http\MyobAuthMiddleware as http;
use App\Myob\Http\MyobSettings;

class Employee
{

	private $uid;
	private $firstname;
	private $lastname;
	private $displayID;
	private $addresses;
	protected $fillable = [
		'first_name', 'last_name', 'gender', 'pronouns', 'indigenous',
		'personal_phone', 'personal_email', 'work_phone', 'work_email', 'job_title',
		'status', 'address', 'abn', 'tax_number', 'dob',
		'date_hired', 'location_id', 'division_id', 'employment_id'
	];

	public function __construct(array $request)
	{
		$this->uid = $request['uid'] ?? null;
		$this->displayID = $request['displayID'] ?? null;
		$this->firstname = $request['firstname'] ?? null;
		$this->lastname = $request['lastname'] ?? null;
		$this->addresses = $request['addresses'] ?? null;
	}

	public function Create()
	{
		$data = new \AlexaCRM\Xrm\Entity('contact');

		$data['firstname'] = $this->firstname;

		return $data;
	}

	public function Update($entity_id)
	{
		$data = new \AlexaCRM\Xrm\Entity('contact', $entity_id);

		$data['firstname'] = $this->firstname;

		return $data;
	}

	public function getData()
	{

		//return $data;
	}
	public function getAll()
	{
		try {
			$setting = new MyobSettings();
			$http = new http($setting);
			$httpClient = $http->getHttpClient();
			$ret = $httpClient->get('/Contact/Employee');
			return response()->json($ret);
		} catch (Exception $ex) {
			return response()->json(['error' => $ex->getMessage()], 500);
		}


		//return $data;
	}
}
