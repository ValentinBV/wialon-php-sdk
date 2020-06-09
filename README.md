

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

You can find the full list of Wialon API methods  [here]([https://sdk.wialon.com/wiki/ru/start](https://sdk.wialon.com/wiki/ru/start)).

### Request sample

Example of calling method  **avl_evts**:

    $httpClient = new GuzzleHttp\Client();
    $wialonRequest = new valentinbv\Wialon\Request\Action($httpClient);
    $wialonRequest->sid='your sid';
    try {
        $result = $wialonRequest->request(
            'avl_evts',
            []
        );
    } catch(\Exception  $e) {
        //some action
    }


The $result array contains the result of the query to the wialon server according to the documentation
[https://sdk.wialon.com/wiki/ru/start](https://sdk.wialon.com/wiki/ru/start)
