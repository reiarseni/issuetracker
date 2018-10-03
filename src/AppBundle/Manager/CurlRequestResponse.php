<?php

namespace AppBundle\Manager;

use Symfony\Component\DomCrawler\Crawler;

class CurlRequestResponse
{
    private $ch;

    const USER_AGENT = 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:47.0) Gecko/20100101 Firefox/47.0';
    const COOKIE_FILE = '/tmp/cookie.txt';
    const TIME_OUT_DEFAULT = 10;

    /**
     * Prepare curl request
     *
     * @param string $method Solo soporta GET y POST
     * @param $url
     * @param $query_params (Un array de parametros, para mandaros en la peticion, tanto para get como post)
     * @param $curl_params (Parametros propios del CURL)
     * @throws \Exception
     */
    public function prepareRequest($url, $method = 'GET', $query_params = array(), $curl_params = array())
    {
        if (!function_exists('curl_init')) {
            throw new \Exception('Php curl extension must be installed.');
        }

        $this->ch = curl_init($url);

        if (!in_array($method, array('GET', 'POST'))) {
            throw new \Exception('Methods not allowed.');
        }

        if (isset($curl_params['user_agent']) && $curl_params['user_agent'])
            @curl_setopt($this->ch, CURLOPT_USERAGENT, $curl_params['user_agent']);

        if (isset($curl_params['referer']) && $curl_params['referer'])
            @curl_setopt($this->ch, CURLOPT_REFERER, $curl_params['referer']);

        if (isset($curl_params['cookie']) && $curl_params['cookie'])
            @curl_setopt($this->ch, CURLOPT_COOKIE, $curl_params['cookie']);

        if (isset($curl_params['header']) && $curl_params['header']) {
            $header = $curl_params['header'];
        } else {
            $header = array(
                "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
                "Accept-Language: en-US,en;q=0.5",
                "Accept-Charset: windows-1251,utf-8;q=0.7,*;q=0.7",
                "Keep-Alive: 300"
            );
        }

        @curl_setopt($this->ch, CURLOPT_HTTPHEADER, $header);

        @curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        @curl_setopt($this->ch, CURLOPT_VERBOSE, 1);
        @curl_setopt($this->ch, CURLOPT_HEADER, 1);
        @curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1);

        @curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
        @curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);

        $build_query = http_build_query($query_params);

        if ($method == 'POST') {
            @curl_setopt($this->ch, CURLOPT_URL, $url);
            @curl_setopt($this->ch, CURLOPT_POST, 1);
            @curl_setopt($this->ch, CURLOPT_FRESH_CONNECT, 1);
            @curl_setopt($this->ch, CURLOPT_FORBID_REUSE, 1);
            @curl_setopt($this->ch, CURLOPT_POSTFIELDS, $build_query);
        }

        if ($method == 'GET') {
            @curl_setopt($this->ch, CURLOPT_URL, $url . (strpos($url, '?') === FALSE ? '?' : '') . $build_query);
        }

        if (isset($curl_params['cookie_file']) && $curl_params['cookie_file']) {
            @curl_setopt($this->ch, CURLOPT_COOKIEFILE, $curl_params['cookie_file']);
            @curl_setopt($this->ch, CURLOPT_COOKIEJAR, $curl_params['cookie_file']);
        }

        if (isset($curl_params['time_out']) && $curl_params['time_out']) {
            $time_out = $curl_params['time_out'];
        } else {
            $time_out = $this::TIME_OUT_DEFAULT;
        }

        @curl_setopt($this->ch, CURLOPT_TIMEOUT, $time_out);

    }

    /**
     * Make curl request
     *
     * @return array  'header','body','curl_error','http_code','last_url', 'content_type'
     */
    public function getResponse()
    {
        $response = curl_exec($this->ch);

        $error = curl_error($this->ch);

        $result = array(
            'header' => '',
            'body' => '',
            'curl_error' => '',
            'http_code' => '',
            'last_url' => ''
        );

        if ($error != "") {
            $result['curl_error'] = $error;
            return $result;
        }

        $header_size = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
        $result['header'] = substr($response, 0, $header_size);
        $result['content'] = substr($response, $header_size);
        $result['http_code'] = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        $result['last_url'] = curl_getinfo($this->ch, CURLINFO_EFFECTIVE_URL);
        $result['content_type'] = curl_getinfo($this->ch, CURLINFO_CONTENT_TYPE);

        curl_close($this->ch);

        return $result;
    }

    /**
     * Log in
     *
     * First, do a request search the _csrf_token field in the DOM login form; Second, catch the _csrf_token value an do a second request
     * @param string $_url
     * @param string $_username
     * @param string $_password
     * @return array
     */
    function login($_url = 'http://issuetracker/app_dev.php/login_check', $_username = '***', $_password = '***')
    {
        $_method = 'POST';
        $_submit = 'Entrar';
        $_csrf_token = $this->getCsrfToken();

        $query_params = array(
            '_username' => $_username,
            '_password' => $_password,
            '_csrf_token' => $_csrf_token,
            '_submit' => $_submit
        );

        $curl_params = array(
            'cookie_file' => $this::COOKIE_FILE,
            'referer' => '',
            'user_agent' => $this::USER_AGENT
        );

        $this->prepareRequest($_url, $_method, $query_params, $curl_params);

        return $this->getResponse();
    }

    /**
     * Navigate
     *
     * @param string $_url
     * @param string $_method
     * @param array $query_params
     * @return array
     */
    public function navigate($_url = 'http://issuetracker/app_dev.php', $_method = 'GET', $query_params = array())
    {
        $_cookie_file = '/tmp/cookie.txt';
        $_referer = '';

        $curl_params = array(
            'cookie_file' => $_cookie_file,
            'referer' => $_referer,
            'user_agent' => $this::USER_AGENT
        );

        $this->prepareRequest($_url, $_method, $query_params, $curl_params);

        return $this->getResponse();
    }

    public function exampleLogin()
    {
        $c = new CurlRequestResponse();
        $response = $c->login('http://issuetracker/app_dev.php/login_check', 'rei', '123');
    }

    public function exampleNavigate()
    {
        $c = new CurlRequestResponse();
        $response = $c->navigate('http://issuetracker/app_dev.php');
        dump($response);
        die;
    }

    /**
     * Creates a crawler.
     *
     * This method returns null if the DomCrawler component is not available.
     *
     * @param string $uri A URI
     * @param string $content Content for the crawler to use
     * @param string $type Content type
     *
     * @return Crawler|null
     */
    protected function createCrawlerFromContent($uri, $content, $type)
    {
        if (!class_exists('Symfony\Component\DomCrawler\Crawler')) {
            return;
        }

        $crawler = new Crawler(null, $uri);
        $crawler->addContent($content, $type);

        return $crawler;
    }

    /**
     * return the CsrfToken in one symfony login form
     *
     * @return string | null
     */
    private function getCsrfToken()
    {
        $_url = 'http://issuetracker/app_dev.php/login';

        $_method = 'GET';

        $query_params = array();

        $curl_params = array(
            'cookie_file' => $this::COOKIE_FILE,
            'referer' => '',
            'user_agent' => $this::USER_AGENT
        );

        $this->prepareRequest($_url, $_method, $query_params, $curl_params);

        $response = $this->getResponse();

        $crawler = $this->createCrawlerFromContent($_url, $response['content'], $response['content_type']);

        $formValues = $crawler->filter('form')->form()->getValues();

        if (isset($formValues['_csrf_token'])) {
            $_csrf_token = $formValues['_csrf_token'];
        } else {
            $_csrf_token = null;
        }

        return $_csrf_token;
    }

}