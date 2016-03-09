<?php

namespace SplitBill\ItsAuthentication;
use SplitBill\Exception\ItsLoginException;
use SplitBill\IApplication;
use SplitBill\Request\HttpRequest;

/**
 * Proxies OAuth requests via PS for security reasons.
 *
 * @package SplitBill\ItsAuthentication
 */
class PsOAuthManager implements IOAuthManager {

    /**
     * @var array The app config.
     */
    private $config;

    /**
     * @var IApplication
     */
    private $app;
    /**
     * @var HttpRequest
     */
    private $request;

    /**
     * PsOAuthManager constructor.
     * @param IApplication $app The application instance.
     */
    public function __construct(IApplication $app, HttpRequest $req) {
        $this->app = $app;
        $this->config = $this->app->getConfig();
        $this->request = $req;
    }

    private function getRequestedUrl() {
        //TODO: This is bad.
        return "http://" . $this->request->getHeader("Host") . $this->request->getUrlRequested();
    }

    /**
     * @return string The start URL to redirect users to.
     */
    public function getOAuthStartUrl() {
        return $this->config['oauth']['base_url'] . "/oauth/splitbill/begin/?back=" . str_replace("startIts", "endIts", $this->getRequestedUrl());
    }

    /**
     * @param string $uuid User ID given to us by the OAuth manager backend implementation.
     * @return ItsUserInfo
     * @throws ItsLoginException If the login failed.
     */
    public function getUserInfoUsingUuid($uuid) {
        $data = file_get_contents("https://protostar.adamwilliams.eu/oauth/userInfo?uuid=" . $uuid, null, $this->getStreamContext());
        $data = json_decode($data, true);

        if (array_key_exists("exception", $data)) {
            throw new ItsLoginException($data['exception'] . " was thrown by external service: " . $data['message']);
        }

        $data = $data['data'];
        return new ItsUserInfo(
            $data['email'],
            $data['user'],
            $data['dept'],
            $data['deptcode'],
            $data['firstname'],
            $data['lastname'],
            $data['name'],
            $data['urn:websignon:usertype']
        );
    }

    private function getStreamContext() {
        return stream_context_create(array(
            'http' => array('ignore_errors' => true)
        ));
    }
}
