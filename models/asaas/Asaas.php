<?php

/**
 *
 * Classe que realiza buscas dos débitos
 *
 * @author Cristina Stanck
 *
 **/
class Asaas
{

	private $environment;
	private $token;

	public function __construct()
	{
		$this->environment = (ENV == 'dev') ? 'sandbox.' : 'www.';
		$this->token       = (ENV == 'dev') ? '' : '';
	}

	public function createUserAccount(array $user, $update = null)
	{
		$ch = curl_init();

		if ($user['cpf'] == '') {
			//$address 	= $this->company->getCompanyById($user['id']);
			//$userfirst 	= $this->company->getFirstUser($user['id']);
			$email      = $user['email'];
		} else {
			$email = $user['email'];
		}

		curl_setopt($ch, CURLOPT_URL, "https://" . $this->environment . "asaas.com/api/v3/customers");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_POST, TRUE);
		$document = str_replace(".", "", (isset($user["cnpj"])) ? $user["cnpj"] : $user["cpf"]);
		$document = str_replace("/", "", $document);
		$document = str_replace(" ", "", $document);
		$document = str_replace("-", "", $document);

		$user['phone'] = str_replace("(", "", $user['phone']);
		$user['phone'] = str_replace(")", "", $user['phone']);
		$user['phone'] = str_replace("-", "", $user['phone']);
		$user['phone'] = str_replace(" ", "", $user['phone']);

		$name = (isset($user['name_fantasy'])) ? $user['name_fantasy'] : $user['name'];

		$address = array();

		curl_setopt($ch, CURLOPT_POSTFIELDS, "{
			\"name\": \"" . $name . "\",
			\"company\": \"" . @$user['name_social'] . "\",
			\"email\": \"" . $email . "\",
			\"phone\": \"" . $user['phone'] . "\",
			\"mobilePhone\": \"" . $user['phone'] . "\",
			\"cpfCnpj\": \"" . $document . "\",
			\"externalReference\": \"" . $user['id'] . "\",
			\"address\": \"" . @$address['street'] . "\",
			\"addressNumber\": \"" . @$address['number'] . "\",
			\"complement\": \"" . @$address['complement'] . "\",
			\"province\": \"" . @$address['neighborhood'] . "\",
			\"postalCode\": \"" . @$address['cep'] . "\",
			\"stateInscription\": \"" . @$user['stateInscription'] . "\",
			\"notificationDisabled\": true,
		}");


		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json",
			"access_token: " . $this->token
		));

		$response = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($response, true);


		return $response['id'];
	}

	public function updateUserAccount(array $user, $customerid, $update = null)
	{
		$ch = curl_init();

		if ($user['cpf'] == '') {
			//$address 	= $this->company->getCompanyById($user['id']);
			//$userfirst 	= $this->company->getFirstUser($user['id']);
			$email      = $user['email'];
		} else {
			$email = $user['email'];
		}

		curl_setopt($ch, CURLOPT_URL, "https://" . $this->environment . "asaas.com/api/v3/customers/" . $customerid);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_POST, TRUE);
		$document = str_replace(".", "", (isset($user["cnpj"])) ? $user["cnpj"] : $user["cpf"]);
		$document = str_replace("/", "", $document);
		$document = str_replace(" ", "", $document);
		$document = str_replace("-", "", $document);

		$user['phone'] = str_replace("(", "", $user['phone']);
		$user['phone'] = str_replace(")", "", $user['phone']);
		$user['phone'] = str_replace("-", "", $user['phone']);
		$user['phone'] = str_replace(" ", "", $user['phone']);

		$name = (isset($user['name_fantasy'])) ? $user['name_fantasy'] : $user['name'];

		$address = array();

		curl_setopt($ch, CURLOPT_POSTFIELDS, "{
			\"name\": \"" . $name . "\",
			\"company\": \"" . @$user['name_social'] . "\",
			\"email\": \"" . $email . "\",
			\"phone\": \"" . $user['phone'] . "\",
			\"mobilePhone\": \"" . $user['phone'] . "\",
			\"cpfCnpj\": \"" . $document . "\",
			\"externalReference\": \"" . $user['id'] . "\",
			\"address\": \"" . @$address['street'] . "\",
			\"addressNumber\": \"" . @$address['number'] . "\",
			\"complement\": \"" . @$address['complement'] . "\",
			\"province\": \"" . @$address['neighborhood'] . "\",
			\"postalCode\": \"" . @$address['cep'] . "\",
			\"stateInscription\": \"" . @$user['stateInscription'] . "\",
			\"notificationDisabled\": true,
		}");

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json",
			"access_token: " . $this->token
		));

		$response = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($response, true);


		return $response;
	}

	public function getPix($id)
	{

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://" . $this->environment . "asaas.com/api/v3/payments/" . $id . "/pixQrCode");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json",
			"access_token: " . $this->token
		));

		$response = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($response, true);

		return $response;
	}

	public function createPayment($date, $value, $customerid, $billingType, $product = '')
	{

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://" . $this->environment . "asaas.com/api/v3/payments");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_POST, TRUE);

		$dateCompare = date('Y/m/d');

		curl_setopt($ch, CURLOPT_POSTFIELDS, "{
			\"customer\": \"" . $customerid . "\",
			\"billingType\": \"" . $billingType . "\",
			\"dueDate\": \"" . $date . "\",
			\"value\": " . $value . ",
			\"description\": " . $product . ",
			\"externalReference\": \"056984\" 
		}");

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json",
			"access_token: " . $this->token
		));

		$response = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($response, true);
		return $response;
	}

	public function createPaymentCredit($date, $value, $customerid, $billingType, $holder, $number, $month, $year, $cvv, $name, $email, $phone, $document, $cep, $numberAdd, $installmentCount, $installmentValue, $product = '')
	{

		$document = str_replace(".", "", $document);
		$document = str_replace("/", "", $document);
		$document = str_replace(" ", "", $document);
		$document = str_replace("-", "", $document);

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://" . $this->environment . "asaas.com/api/v3/payments");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_POST, TRUE);

		$dateCompare = date('Y/m/d');

		if ($installmentCount == 1) {

			curl_setopt($ch, CURLOPT_POSTFIELDS, "{
				\"customer\": \"" . $customerid . "\",
				\"billingType\": \"" . $billingType . "\",
				\"dueDate\": \"" . $date . "\",
				\"value\": " . $value . ",
				\"description\": \"" . $product . "\",
				\"externalReference\": \"056984\",
				\"creditCard\": 
				{
					\"holderName\": \"" . $holder . "\",
					\"number\": \"" . $number . "\",
					\"expiryMonth\": \"" . $month . "\",
					\"expiryYear\": \"" . $year . "\",
					\"ccv\": \"" . $cvv . "\",
					\"installmentCount\": \"" . $installmentCount . "\",
					\"installmentValue\": \"" . $installmentValue . "\",

				}
				,
				\"creditCardHolderInfo\": 
				{
					\"name\": \"" . $name . "\",
					\"email\": \"" . $email . "\",
					\"cpfCnpj\": \"" . $document . "\",
					\"postalCode\": \"" . $cep . "\",
					\"addressNumber\": \"" . $numberAdd . "\",
					\"phone\": \"" . $phone . "\",
				}
			}");
		} else {

			curl_setopt($ch, CURLOPT_POSTFIELDS, "{
				\"customer\": \"" . $customerid . "\",
				\"billingType\": \"" . $billingType . "\",
				\"dueDate\": \"" . $date . "\",
				\"installmentCount\": \"" . $installmentCount . "\",
				\"installmentValue\": \"" . $installmentValue . "\",
				\"description\": \"" . $product . "\",
				\"externalReference\": \"056984\",
				\"creditCard\": 
				{
					\"holderName\": \"" . $holder . "\",
					\"number\": \"" . $number . "\",
					\"expiryMonth\": \"" . $month . "\",
					\"expiryYear\": \"" . $year . "\",
					\"ccv\": \"" . $cvv . "\",
					\"installmentCount\": \"" . $installmentCount . "\",
					\"installmentValue\": \"" . $installmentValue . "\",

				}
				,
				\"creditCardHolderInfo\": 
				{
					\"name\": \"" . $name . "\",
					\"email\": \"" . $email . "\",
					\"cpfCnpj\": \"" . $document . "\",
					\"postalCode\": \"" . $cep . "\",
					\"addressNumber\": \"" . $numberAdd . "\",
					\"phone\": \"" . $phone . "\",
				}
			}");
		}

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json",
			"access_token: " . $this->token
		));

		$response = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($response, true);

		return $response;
	}

	public function createPaymentCreditToken($customerid, $token,$value, $name_property)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://". $this->environment."asaas.com/api/v3/payments");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_POST, TRUE);

		curl_setopt($ch, CURLOPT_POSTFIELDS, "{
			\"customer\": \"".$customerid."\",
			\"billingType\": \"CREDIT_CARD\",
			\"dueDate\": \"". date("Y-m-d") ."\",
			\"value\": ".$value.",
			\"description\": \"Aplicação de Multa da Pousada\",
			\"externalReference\": \"".$name_property."\",
			
			\"creditCardToken\": \"".$token."\"
		}");

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json",
			"access_token: " . $this->token
		));

		$response = curl_exec($ch);
		curl_close($ch);

		$response = json_decode($response, true);

		return $response;
	}

	public function createSignature($date, $value, $customerid, $billingType, $product = '')
	{

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://" . $this->environment . "asaas.com/api/v3/subscriptions");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_POST, TRUE);

		curl_setopt($ch, CURLOPT_POSTFIELDS, "{
			\"customer\": \"" . $customerid . "\",
			\"billingType\": \"" . $billingType . "\",
			\"nextDueDate\": \"" . $date . "\",
			\"value\": " . $value . ",
			\"description\": " . $product . ",
			\"cycle\": \"MONTHLY\",
		}");

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json",
			"access_token: " . $this->token
		));

		$response = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($response, true);
		return $response;
	}

	public function createSignatureCredit($date, $value, $customerid, $billingType, $holder, $number, $month, $year, $cvv, $name, $email, $phone, $document, $cep, $numberAdd, $installmentCount, $installmentValue, $product = '', $ipclient)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://" . $this->environment . "asaas.com/api/v3/subscriptions");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_POST, TRUE);


		curl_setopt($ch, CURLOPT_POSTFIELDS, "{
			\"customer\": \"" . $customerid . "\",
			\"billingType\": \"" . $billingType . "\",
			\"nextDueDate\": \"" . $date . "\",
			\"value\": " . $value . ",
			\"description\": \"" . $product . "\",
			\"externalReference\": \"056984\",
			\"cycle\": \"MONTHLY\",
			\"remoteIp\": \"" . $ipclient . "\",
			\"creditCard\": 
			{
				\"holderName\": \"" . $holder . "\",
				\"number\": \"" . $number . "\",
				\"expiryMonth\": \"" . $month . "\",
				\"expiryYear\": \"" . $year . "\",
				\"ccv\": \"" . $cvv . "\",

			}
			,
			\"creditCardHolderInfo\": 
			{
				\"name\": \"" . $name . "\",
				\"email\": \"" . $email . "\",
				\"cpfCnpj\": \"" . $document . "\",
				\"postalCode\": \"" . $cep . "\",
				\"addressNumber\": \"" . $numberAdd . "\",
				\"phone\": \"" . $phone . "\",
			}
		}");

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json",
			"access_token: " . $this->token
		));

		$response = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($response, true);

		return $response;
	}

	public function receiverWebhook($post)
	{

		$a = fopen('asaas.txt', 'w');
		if ($a == false) die('Não foi possível criar o arquivo.');
		fwrite($a, 'entrou');
		fclose($a);
	}

	public function getTransaction($id)
	{

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://" . $this->environment . "asaas.com/api/v3/payments/" . $id);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json",
			"access_token: " . $this->token
		));

		$response = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($response, true);

		return $response['status'];
	}

	public function getSignature($id)
	{

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://" . $this->environment . "asaas.com/api/v3/subscriptions/" . $id);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json",
			"access_token: " . $this->token
		));

		$response = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($response, true);

		return $response['status'];
	}

	public function getPaymentSignature($id)
	{

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://" . $this->environment . "asaas.com/api/v3/subscriptions/" . $id . "/payments");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json",
			"access_token: " . $this->token
		));

		$response = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($response, true);

		return $response;
	}

	public function removeSignature($id)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://" . $this->environment . "asaas.com/api/v3/subscriptions/" . $id);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json",
			"access_token: " . $this->token
		));

		$response = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($response, true);

		return $response;
	}

	public function removePayment($id)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://" . $this->environment . "asaas.com/api/v3/payments/" . $id);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json",
			"access_token: " . $this->token
		));

		$response = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($response, true);

		return $response;
	}

	public function newAccountAsaas(array $data)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://" . $this->environment . "asaas.com/api/v3/accounts");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_POST, TRUE);

		curl_setopt($ch, CURLOPT_POSTFIELDS, "{
			\"name\": \"" . $data['name'] . "\",
			\"email\": \"" . $data['email'] . "\",
			\"cpfCnpj\": \"" . $data['cpf'] . "\",
			\"phone\": \"" . $data['phone'] . "\",
			\"mobilePhone\": \"" . $data['mobilePhone'] . "\",
			\"address\": \"" . $data['address'] . "\",
			\"addressNumber\": \"" . $data['addressNumber'] . "\",
			\"province\": \"" . $data['province'] . "\",
			\"postalCode\": \"" . $data['postalCode'] . "\",
		}");

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json",
			"access_token: " . $this->token
		));

		$response = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($response, true);

		return $response;
	}

	public function getClient($idasaas)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://" . $this->environment . "asaas.com/api/v3/customers/" . $idasaas);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json",
			"access_token: " . $this->token
		));

		$response = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($response, true);

		return $response;
	}

	public function makeTransaction($value, $walletid)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://" . $this->environment . "asaas.com/api/v3/transfers");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_POST, TRUE);

		curl_setopt($ch, CURLOPT_POSTFIELDS, "{
		\"value\": $value,
		\"walletId\": \"" . $walletid . "\"
		}");

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json",
			"access_token: " . $this->token
		));

		$response = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($response, true);

		return $response;
	}

	public function trasfer($bank, $name, $cpf, $birthdate, $agency, $account, $digit, $type, $value)
	{

		$accountName = $bank . ' - ' . $name . ' ' . $cpf;

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://" . $this->environment . "asaas.com/api/v3/transfers");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_POST, TRUE);

		curl_setopt($ch, CURLOPT_POSTFIELDS, "{
		        \"value\": \"" . $value . "\",
		        \"bankAccount\": {
		            \"bank\": {
		                \"code\": \"" . $bank . "\",
		            },
		            \"accountName\": \"" . $accountName . "\",
		            \"ownerName\": \"" . $name . "\",
		            \"ownerBirthDate\": \"" . $birthdate . "\",
		            \"cpfCnpj\": \"" . $cpf . "\",
		            \"agency\": \"" . $agency . "\",
		            \"account\": \"" . $account . "\",
		            \"accountDigit\": \"" . $digit . "\",
		            \"bankAccountType\": \"" . $type . "\",
		        }
		    }");

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json",
			"access_token: " . $this->token
		));

		$response = curl_exec($ch);
		curl_close($ch);

		return $response;
	}

	public function getTranfers($date)
	{

		//https://www.asaas.com/api/v3/transfers?dateCreated=2019-05-02

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://" . $this->environment . "asaas.com/api/v3/transfers/?dateCreated=" . $date);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json",
			"access_token: " . $this->token
		));

		$response = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($response, true);

		return $response;
	}

	public function getBarCode($idasaas)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://" . $this->environment . "asaas.com/api/v3/payments/" . $idasaas . "/identificationField");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json",
			"access_token: " . $this->token
		));

		$response = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($response, true);

		return $response['identificationField'];
	}

	public function tokenizateCard($customerid, $name_card, $number_card, $cvv, $month, $year, $name, $email, $document, $cep, $numberAdd, $phone, $ip_cliente)
	{

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://" . $this->environment . "asaas.com/api/v3/creditCard/tokenize");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_POST, TRUE);


		curl_setopt($ch, CURLOPT_POSTFIELDS, "{
			\"customer\": \"" . $customerid . "\",
			\"creditCard\": {
				\"holderName\": \"" . $name_card . "\",
				\"number\": \"" . $number_card . "\",
				\"expiryMonth\": \"" . $month . "\",
				\"expiryYear\": \"" . $year . "\",
				\"ccv\": \"" . $cvv . "\",
			},
			\"creditCardHolderInfo\": {
				\"name\": \"" . $name . "\",
				\"email\": \"" . $email . "\",
				\"cpfCnpj\": \"" . $document . "\",
				\"postalCode\": \"" . $cep . "\",
				\"addressNumber\": \"" . $numberAdd . "\",
				\"phone\": \"" . $phone . "\",
			}
		}");

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json",
			"access_token: " . $this->token
		));

		$response = curl_exec($ch);
		curl_close($ch);

		$response = json_decode($response, true);

		return $response;
	}

	public function updateSignature($id_asaas, $period, $price)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://" . $this->environment . "asaas.com/api/v3/subscriptions/".$id_asaas);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_POST, TRUE);

		curl_setopt($ch, CURLOPT_POSTFIELDS, "{
		\"value\": " . $price . ",
		\"cycle\": \"" . $period . "\",
		\"updatePendingPayments\": true,
		}");

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Content-Type: application/json",
		"access_token: " . $this->token
		));
	}
}
