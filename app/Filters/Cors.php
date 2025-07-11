<?php namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Cors implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header("Access-Control-Expose-Headers: Content-Length, Content-Type");
        
        if ($request->getMethod() === 'options') {
            return \Config\Services::response()
                ->setStatusCode(200)
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->send();
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}