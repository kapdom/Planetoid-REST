<?php
/**
 * Created by PhpStorm.
 * User: DOOMinikP
 * Date: 2018-07-23
 * Time: 15:10
 */

namespace App\Core\Request;

use App\Core\Router\Router;
use App\Core\Services\LogService;
use App\Core\Validator\Validator;
use App\Core\View\View;

class Request
{

    /**
     * @var Request method
     */
    public $requestMethod;

    /**
     * @var array GET data
     */
    private $getData = [];

    /**
     * @var array POST data
     */
    private $postData = [];

    /**
     * @var array PUT data
     */
    private $putData = [];

    /**
     * Request constructor.
     */
    public function __construct()
    {
        try {
            $this->requestMethod = $_SERVER['REQUEST_METHOD'];
            $this->parsePutData();
            $this->parsePostData();
        }catch (\Exception $e){
            LogService::addLog(3,$e->getMessage());
            View::abort(400,'Wrong data');
        }
    }

    /**
     * Extract GET data from URL
     *
     * @param string $uri
     * @return array
     */
    public function parseGetData(string $uri): array
    {
        if ($uri != "") {
            $uriPatternPlaceholder = explode('/', $uri);
            $patternIndexKeys = array_keys($uriPatternPlaceholder,'?');
            $getUrlArray = explode('/',Router::$url);

            foreach ($patternIndexKeys as $index){
                $this->getData[] = $getUrlArray[$index];
            }
        }
        return $this->getData;
    }

    /**
     * Get return content type
     *
     * @return string
     */
    public static function getReturnContentTypeHeader(): string
    {
        $returnContentTypeHeader = 'application/json';
        $requestContentType = getallheaders();
        if (!isset($requestContentType['Content-type'])) {
            $returnContentTypeHeader = 'text/html';
        };

        return $returnContentTypeHeader;
    }

    /**
     * Validate and store POST data
     */
    public function parsePostData(): void
    {
        $this->postData = Validator::filterPost();
    }

    /**
     * @return array get POST data
     */
    public function getPostData(): array
    {
        return $this->postData;
    }

    /**
     * Validate and store PUT data
     */
    public function parsePutData(): void
    {
        $jsonArray = json_decode(file_get_contents('php://input'), true);
        if(!empty($jsonArray)) {
            foreach ($jsonArray as $key => $val) {
                $this->putData[$key] = Validator::filterStringData($val);
            }
        }
    }

    /**
     * @return array get PUT data
     */
    public function getPutData(): array
    {
        return $this->putData;
    }
}