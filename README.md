

# wialon-php-sdk
PHP library for Wialon API interaction, includes  API methods.
## 1. Prerequisites

-   PHP 7.1 or later
-   guzzlehttp/guzzle": "~6.0"

## 2. Installation

The wialon-php-sdk can be installed using Composer by running the following command:

**composer require valentinbv/wialon-php-sdk**

For install from git add to composer.json:

    {
        "repositories": [
            {
                "type": "vcs",
                "url": "https://github.com/ValentinBV/wialon-php-sdk.git"
            }
        ],
        "require": {
            "valentinbv/wialon-php-sdk": "dev-master"
        }
    }

## 3. Initialization

Create Wialon API Client object using the following code:

    $httpClient = new GuzzleHttp\Client();
    $wialonRequest = new valentinbv\Wialon\Request\Action($httpClient);
    $wialonRequest->sid='your sid';

## 4. API Requests

You can find the full list of Wialon API methods  [https://sdk.wialon.com/wiki/en/start](https://sdk.wialon.com/wiki/en/start).

### Request sample

Example of calling method  **core/get_account_data**:

    $httpClient = new GuzzleHttp\Client();
    $wialonRequest = new valentinbv\Wialon\Request\Action($httpClient);
    $wialonRequest->sid='your sid';
    try {
        $result = $wialonRequest->execute(
            'core/get_account_data',
            ['type' => 2]
        );
    } catch(\Exception  $e) {
        //some action
    }


The $result array contains the result of the query to the wialon server according to the documentation
[https://sdk.wialon.com/wiki/en/start](https://sdk.wialon.com/wiki/en/start)

## 5. Structure

All classes for working with the Wialon API are derived from the base class.valentinbv\Wialon\Request\BaseRequest

So, you can execute a request to the Wialon API using the base class
    
    $httpClient = new GuzzleHttp\Client();
    $wialonRequest = new valentinbv\Wialon\Request\BaseRequest($httpClient);
    $wialonRequest->sid='your sid';
    try {
        $result = $wialonRequest->request(
            [
                'sid' => 'your sid',
                'svc' => 'core/get_account_data',
                'params' => json_encode(['type' => 1])
            ]
        );
    } catch(\Exception  $e) {
        //some action
    }

The above method is convenient to use when you need to execute an arbitrary request or a request from a section [https://sdk.wialon.com/wiki/en/sidebar/remoteapi/apiref/requests/requests](https://sdk.wialon.com/wiki/en/sidebar/remoteapi/apiref/requests/requests)

For most requests, the valentinbv\Wialon\Request\Action class is more convenient.

A special class for receiving events has also been added. This query is conveniently used to maintain a session according to the documentation. [https://sdk.wialon.com/wiki/en/sidebar/remoteapi/apiref/requests/avl_evts](https://sdk.wialon.com/wiki/en/sidebar/remoteapi/apiref/requests/avl_evts)

    $httpClient = new GuzzleHttp\Client();
    $wialonRequest = new valentinbv\Wialon\Request\Events($httpClient);
    $wialonRequest->sid='your sid';
    try {
        $result = $wialonRequest->execute();
    } catch(\Exception  $e) {
        //some action
    }

support: bvv1988@gmail.com