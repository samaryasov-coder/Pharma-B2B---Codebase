<?php
class pb2bApiService
{
    protected string $base_url = '';
    protected array $default_headers = array(
        'Content-Type' => 'application/json',
        'Accept' => '*/*',
    );


    public function __construct($base_url)
    {
        $this->base_url = trim($base_url);
    }
   
    public function get($endpoint, array $params = [], array $headers = []): array
    {
        try {
            $url = $this->buildUrl($endpoint);
            if(!empty($params)) $url .= '?' . http_build_query($params);

            $all_headers = array_merge($this->default_headers, $headers);
            $wa_net = new waNet(array(waNet::FORMAT_RAW, 'timeout' => 10), $all_headers);
            $response = $wa_net->query($url, array(), waNet::METHOD_GET);
            return $this->handleResponse($response);

        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }
    
    public function post($endpoint, array $data = array(), array $params = array()): array
    {
        try {
            $url = $this->buildUrl($endpoint);
            $payload = json_encode($data, $params['encode_flag'] ?? JSON_UNESCAPED_UNICODE);
            $headers = array_merge($this->default_headers, $params['headers'] ?? []);
            $wa_net = new waNet(array(waNet::FORMAT_RAW, 'timeout' => 10),$headers);
            $response = $wa_net->query($url, $payload, waNet::METHOD_POST);
            return $this->handleResponse($response, $wa_net->getResponseHeader());
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }
	
    protected function buildUrl($endpoint)
    {
        if(preg_match('~^https?://~', $endpoint)) return $endpoint;
        if(strpos($endpoint, '?') === 0) return rtrim($this->base_url, '/') . $endpoint;
        return rtrim($this->base_url, '/') . '/' . ltrim($endpoint, '/');
    }


    protected function handleResponse($response, array $headers = [])
    {
        $data = json_decode($response, true);
        if(json_last_error() === JSON_ERROR_NONE) 
        {
            return array(
                'status' => 'success',
                'data' => $data,
                'headers' => $headers,
            );
        }

        return array(
            'status' => 'success',
            'data' => $response,
            'headers' => $headers,
        );
    }
     
    protected function handleError($e)
    {
        return array(
            'status' => 'error',
            'message' => $e->getMessage()
        );
    }
}
