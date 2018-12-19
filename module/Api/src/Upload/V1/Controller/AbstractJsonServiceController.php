<?php
namespace Api\Upload\V1\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
 /**
  * 
 * case 100: $text = 'Continue'; break;
 * case 101: $text = 'Switching Protocols'; break;
 * case 200: $text = 'OK'; break;
 * case 201: $text = 'Created'; break;
 * case 202: $text = 'Accepted'; break;
 * case 203: $text = 'Non-Authoritative Information'; break;
 * case 204: $text = 'No Content'; break;
 * case 205: $text = 'Reset Content'; break;
 * case 206: $text = 'Partial Content'; break;
 * case 300: $text = 'Multiple Choices'; break;
 * case 301: $text = 'Moved Permanently'; break;
 * case 302: $text = 'Moved Temporarily'; break;
 * case 303: $text = 'See Other'; break;
 * case 304: $text = 'Not Modified'; break;
 * case 305: $text = 'Use Proxy'; break;
 * case 400: $text = 'Bad Request'; break;
 * case 401: $text = 'Unauthorized'; break;
 * case 402: $text = 'Payment Required'; break;
 * case 403: $text = 'Forbidden'; break;
 * case 404: $text = 'Not Found'; break;
 * case 405: $text = 'Method Not Allowed'; break;
 * case 406: $text = 'Not Acceptable'; break;
 * case 407: $text = 'Proxy Authentication Required'; break;
 * case 408: $text = 'Request Time-out'; break;
 * case 409: $text = 'Conflict'; break;
 * case 410: $text = 'Gone'; break;
 * case 411: $text = 'Length Required'; break;
 * case 412: $text = 'Precondition Failed'; break;
 * case 413: $text = 'Request Entity Too Large'; break;
 * case 414: $text = 'Request-URI Too Large'; break;
 * case 415: $text = 'Unsupported Media Type'; break;
 * case 500: $text = 'Internal Server Error'; break;
 * case 501: $text = 'Not Implemented'; break;
 * case 502: $text = 'Bad Gateway'; break;
 * case 503: $text = 'Service Unavailable'; break;
 * case 504: $text = 'Gateway Time-out'; break;
 * case 505: $text = 'HTTP Version not supported'; break;
  * 
  * @author Julio_MOLINERO
  * ===========================================================
  * Example taken from
  * @link https://github.com/stevenalexander/zf2-restful-api
  * ===========================================================
  *
  */
class AbstractJsonServiceController extends AbstractRestfulController
{
    protected function methodNotAllowed()
    {
        $this->response->setStatusCode(405);
        throw new \Exception('Method Not Allowed');
    }    
    protected function notFound( $message ){
        $this->response->setStatusCode(404);
        throw new \Exception( $message );        
    }
    protected function applicationError( $message ){
        $this->response->setStatusCode(500);
        throw new \Exception( $message );
    }
    protected function forbidden( $message ){
        $this->response->setStatusCode(403);
        throw new \Exception( $message );
    }
    /*
    # Override default actions as they do not return valid JsonModels    
    public function create($data)
    {
        return $this->methodNotAllowed();
    }
    
    
    public function delete($id)
    {
        return $this->methodNotAllowed();
    }
    
    public function deleteList($data)
    {
        return $this->methodNotAllowed();
    }
    
    public function getList()
    {
        return $this->methodNotAllowed();
    }
    
    public function head($id = null)
    {
        return $this->methodNotAllowed();
    }
    
    public function options()
    {
        return $this->methodNotAllowed();
    }    
    public function replaceList($data)
    {
        return $this->methodNotAllowed();
    }
    
    public function patchList($data)
    {
        return $this->methodNotAllowed();
    }
        
    public function update($id, $data)
    {
        return $this->methodNotAllowed();
    }
    public function patch($id, $data)
    {
        return $this->methodNotAllowed();
    }
    */    
}

