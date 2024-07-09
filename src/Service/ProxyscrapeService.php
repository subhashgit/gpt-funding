<?php

namespace App\Service;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProxyscrapeService
{
    public function __construct(private HttpClientInterface $httpClient) {}

    /**
     * @param string $protocol  http|socks4|socks5|all
     * @param string $country   Alpha 2 ISO country code or 'all'
     * @param string $ssl       yes|no|all
     * @param string $anonymity elite|anonymous|transparent|all
     *
     * @return string[]
     */
    public function getProxyLists(
        string $protocol = 'http',
        int $timeout = 5000,
        string $country = 'all',
        string $ssl = 'all',
        string $anonymity = 'all',
    ): array {
        $cache = new FilesystemAdapter();

        $key = md5(serialize(func_get_args()));

        return $cache->get("proxy_list_{$key}", function (ItemInterface $item) use ($protocol, $timeout, $country, $ssl, $anonymity): array {
            $item->expiresAfter(3600);

            $http = $this->httpClient
                ->request('GET', "https://api.proxyscrape.com/v2/?request=displayproxies&protocol={$protocol}&timeout={$timeout}&country={$country}&ssl={$ssl}&anonymity={$anonymity}")
            ;

            $content = $http->getContent();

            return explode("\r\n", trim($content));
        });
    }

    public function scrapPageWithProxy(string $link, array $proxies): ?Crawler
    {
        foreach ($proxies as $proxy) {
            try {
                $html = $this->httpClient->request('GET', $link, [
                    'headers' => ['user_agent' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'],
                    'proxy' => "http://{$proxy}",
                    'timeout' => 10,
                    'http_version' => '1.1',
                ]);

                return new Crawler($html->getContent(true));
            } catch (\Exception $e) {
            }
        }

        return null;
    }
}
