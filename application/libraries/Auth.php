<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth
{
	public $CI_OBJECT;
	public $HEADER_TOKEN;
	public $VERIFICATION_RESPONSE;
    /**
     * @var false
     */
    private bool $LOGGED_IN;
    private  $access_exp_time;
    private $refresh_exp_time;

    public function __construct()
	{
		$this->CI_OBJECT =& get_instance();
		$this->HEADER_TOKEN = "";
		$this->LOGGED_IN = false;

        $this->access_exp_time = 30 * 60;
        $this->refresh_exp_time = 3 * 24 * 60 * 60;

        $this->CI_OBJECT->load->library('jwt');
        $this->HEADER_TOKEN = $this->CI_OBJECT->input->request_headers()['HTTP_AUTHORIZATION'] ?? "";
	}

    public function doAuthentication($type = "AUTHENTICATION")
    {
        if (!empty($this->HEADER_TOKEN)) {

            // Check if the header starts with "Bearer"
            if (preg_match('/Bearer\s(\S+)/', $this->HEADER_TOKEN, $matches)) {

                $this->HEADER_TOKEN = $matches[1]; // Extract the token value
                $decodedPayload = $this->CI_OBJECT->jwt->decode('access_token', $this->HEADER_TOKEN);
                if($decodedPayload){

                    if($decodedPayload['exp'] > time()){

                        $this->LOGGED_IN = true;

                    }else{

                        return array(
                            'http_response_code' => 401,
                            'status' => 69,
                            'message' => 'Token Expired'
                        );

                    }
                }

            }else{
                return array(
                    'http_response_code' => 401,
                    'status' => 0,
                    'message' => 'Invalid bearer token format'
                );
            }

        }else{
            return array(
                'http_response_code' => 401,
                'status' => 0,
                'message' => 'Missing authorization token'
            );
        }


        if ($this->LOGGED_IN) {

            switch($type){

                case "AUTHORIZATION" :

                    return array(
                        'http_response_code' => 200,
                        'status' => 1,
                        'message' => 'Authenticated user token',
                        'payload' => $decodedPayload
                    );

                    break;

                default :

                    return array(
                        'http_response_code' => 200,
                        'status' => 1,
                        'message' => 'Authenticated user token',
                        'payload' => $decodedPayload
                    );
            }


        }

        return array(
            'http_response_code' => 401,
            'status' => 0,
            'message' => 'Unauthenticated user token'
        );

    }

    public function refresh_token()
    {
        if (!empty($this->HEADER_TOKEN)) {

            // Check if the header starts with "Bearer"
            if (preg_match('/Bearer\s(\S+)/', $this->HEADER_TOKEN, $matches)) {

                $this->HEADER_TOKEN = $matches[1]; // Extract the token value
                $decodedPayload = $this->CI_OBJECT->jwt->decode('refresh_token',$this->HEADER_TOKEN);
                if($decodedPayload){

                    if($decodedPayload['exp'] > time()){

                        $this->CI_OBJECT->db->where('token', $this->HEADER_TOKEN);
                        $ref_query = $this->CI_OBJECT->db->get('refresh_token');
                        if($ref_query->num_rows() > 0){

                            return $this->processSignIn('login', ['user_id' => $decodedPayload['uid']]);

                        }

                        return array(
                            'http_response_code' => 401,
                            'status' => 0,
                            'message' => 'Invalid token authentication'
                        );

                    }

                    return array(
                        'http_response_code' => 401,
                        'status' => 69,
                        'message' => 'Token Expired'
                    );
                }

            }

            return array(
                'http_response_code' => 401,
                'status' => 0,
                'message' => 'Invalid bearer token format'
            );

        }

        return array(
            'http_response_code' => 401,
            'status' => 0,
            'message' => 'Missing authorization token'
        );
    }

	public function getUserData($value, $sourceType = "id")
	{
		if ($sourceType === "id") {
			$this->CI_OBJECT->db->where('id', $value);
		} else {
			$this->CI_OBJECT->db->where($sourceType, $value);
		}

		return $this->CI_OBJECT->db->get('user')->result()[0];

	}


	//Start Sign-up process
	public function processSignup($input)
	{
        //validate if user already exists
        $this->CI_OBJECT->db->where('username', filter($input['username']));
        $this->CI_OBJECT->db->where('status', 1);
        if(!($this->CI_OBJECT->db->get('user')->num_rows() > 0)) {

            //begin processing of user registration
            $userData = array(
                'name' => filter($input['name']),
                'username' => filter($input['username']),
                'password' => password_hash(filter($input['password']), PASSWORD_BCRYPT)
            );

            if ($this->CI_OBJECT->db->insert('user', $userData)) {

                return [
                    'http_response_code' => 200, 'status' => 1,
                    'message' => 'User registered successfully'
                ];

            }
        }else{

            return [
                'http_response_code' => 409, 'status' => 0,
                'message' => 'Username Taken'
            ];

        }

		return [
			'http_response_code' => 500, 'status' => 0,
			'message' => 'An error occurred while processing you request'
		];
	}
	//End Sign-up Process

	//Start checking username and password
	public function verifyUser($username, $password)
	{
		$this->CI_OBJECT->db->where('username', $username);

		if ($this->CI_OBJECT->db->get('user')->num_rows() > 0) {

			$userData = $this->getUserData($username, 'username');

			$password_hash = $userData->password;

			if (password_verify($password, $password_hash)) {

				$this->VERIFICATION_RESPONSE = array(
					'http_response_code' => 200,
					'status' => 1,
					'user_id' => (int)$userData->id,
					'message' => 'username & password is valid',
					'user_status' => (int)$userData->status
				);

			} else {

				$this->VERIFICATION_RESPONSE = array(
					'http_response_code' => 400,
					'status' => 0,
					'message' => 'The password is invalid'
				);

			}

		} else {

			$this->VERIFICATION_RESPONSE = array(
				'http_response_code' => 401,
				'status' => 0,
				'message' => 'The user is invalid'
			);

		}

		return false;
	}

	//End checking username and password

	public function processSignIn($request = "authenticateUser", array $data = [])
	{
		switch ($request) {

			case "authenticateUser":
				$username = filter($data['username']);
				$password = filter($data['password']);

				$this->verifyUser($username, $password);

				return $this->VERIFICATION_RESPONSE;

			case "login":

                $userID = $data['user_id'];
                $access_exp_time = $this->access_exp_time; //30min
                $refresh_exp_time = $this->refresh_exp_time; //7days

                $access_token = $this->CI_OBJECT->jwt->encode('access_token', ['uid' => $userID], $access_exp_time);
                $refresh_token = $this->CI_OBJECT->jwt->encode('refresh_token', ['uid' => $userID], $refresh_exp_time);
                if($access_token && $refresh_token)
                {
                    $this->CI_OBJECT->db->delete('refresh_token', ['user_id' => $userID]);
                    $this->CI_OBJECT->db->insert('refresh_token', ['user_id' => $userID, 'token' => $refresh_token, 'exp_time' => time() + $refresh_exp_time]);

                    return array(
                        'http_response_code' => 200,
                        'status' => 1,
                        'access_token'  => $access_token,
                        'refresh_token'  => $refresh_token,
                        'message' => 'Login successful'
                    );
                }

                return array(
                    'http_response_code' => 500,
                    'status' => 0,
                    'message' => 'An error occurred while processing you request'
                );

			default:

				return [
					'http_response_code' => 400,
					'status' => 0,
					'message' => 'No request found'
				];
		}

	}

	public function processSignOut($USER_ID){


		$this->CI_OBJECT->db->where('user_id', $USER_ID);
		if ($this->CI_OBJECT->db->delete('refresh_token')) {

			return array(
				'http_response_code' => 200,
				'status' => 1,
				'message' => 'User signed out successfully'
			);

		}

		return false;
	}

}
