<?php

namespace Buzz\Client;

use Buzz\Message\MessageInterface;
use Buzz\Message\RequestInterface;
use Buzz\Util\CookieJar;
use Buzz\Exception\ClientException;
use Illuminate\Config\Repository;

class FileGetContents extends AbstractStream
{
  /**
   * @var CookieJar
   */
  protected $cookieJar;


	/**
	 * Config
	 *
	 * @var Illuminate\Config\Repository
	 */
	public $config;

  /**
   * @param CookieJar|null $cookieJar
   * @param Repository|null $config
   */
  public function __construct(CookieJar $cookieJar = null, Repository $config = null)
  {
    if($config->get('buzz::timeout')){
      $this->setTimeout($config->get('buzz::timeout'));
    }

    if($config->get('buzz::ignoreErrors')){
      $this->setIgnoreErrors($config->get('buzz::ignoreErrors'));
    }

    if($config->get('buzz::maxRedirects')){
      $this->setMaxRedirects($config->get('buzz::maxRedirects'));
    }

    if($config->get('buzz::verifyPeer')){
      $this->setVerifyPeer($config->get('buzz::verifyPeer'));
    }

    if($config->get('buzz::proxy')){
      $this->setProxy($config->get('buzz::proxy'));
    }

    if ($cookieJar) {
      $this->setCookieJar($cookieJar);
    }
  }

  /**
   * @param CookieJar $cookieJar
   */
  public function setCookieJar(CookieJar $cookieJar)
  {
    $this->cookieJar = $cookieJar;
  }

  /**
   * @return CookieJar
   */
  public function getCookieJar()
  {
    return $this->cookieJar;
  }

  /**
   * @see ClientInterface
   *
   * @throws ClientException If file_get_contents() fires an error
   */
  public function send(RequestInterface $request, MessageInterface $response)
  {
    if ($cookieJar = $this->getCookieJar()) {
      $cookieJar->clearExpiredCookies();
      $cookieJar->addCookieHeaders($request);
    }

    $context = stream_context_create($this->getStreamContextArray($request));
    $url = $request->getHost().$request->getResource();

    $level = error_reporting(0);
    $content = file_get_contents($url, 0, $context);
    error_reporting($level);
    if (false === $content) {
      $error = error_get_last();
      throw new ClientException($error['message']);
    }

    $response->setHeaders($this->filterHeaders((array) $http_response_header));
    $response->setContent($content);

    if ($cookieJar) {
      $cookieJar->processSetCookieHeaders($request, $response);
    }
  }

  private function filterHeaders(array $headers)
  {
    $filtered = array();
    foreach ($headers as $header) {
      if (0 === stripos($header, 'http/')) {
        $filtered = array();
      }

      $filtered[] = $header;
    }

    return $filtered;
  }
}
