<?php
namespace Kakarazi\Businessrss\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\HTTP\Client\Curl;

class Data extends AbstractHelper
{
    protected $curl;

    // Thêm constructor để inject model factory
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        Curl $curl
    ) {
        $this->curl = $curl;
        parent::__construct($context);
    }

    public function getRssFeed()
    {
        $url = 'https://vnexpress.net/rss/kinh-doanh.rss';
        $this->curl->get($url);
        $response = $this->curl->getBody();

        $xml = simplexml_load_string($response);
        $rssItems = [];

        foreach ($xml->channel->item as $item) {
            $enclosureUrl = null;
            if ($item->enclosure && $item->enclosure->attributes()) {
                $enclosureAttributes = $item->enclosure->attributes();
                if (isset($enclosureAttributes['url'])) {
                    $enclosureUrl = (string) $enclosureAttributes['url'];
                }
            }

            $rssItems[] = [
                'title' => (string) $item->title,
                'link' => (string) $item->link,
                'description' => (string) $item->description,
                'pubDate' => (string) $item->pubDate,
                'image' => $enclosureUrl, // Gán URL ảnh vào key 'image'
            ];
        }


        return $rssItems;
    }
}

