<?php

namespace App\Rss;

use Carbon\Carbon;

class RssBuilder
{
    static function build($programs)
    {
        $dom = new \DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = true;
        $root = $dom->createElement('rss');

        $rssAttr = [
            'xmlns:dc' => 'http://purl.org/dc/elements/1.1/',
            'xmlns:content' => "http://purl.org/rss/1.0/modules/content/",
            'xmlns:atom' => "http://www.w3.org/2005/Atom",
            'version' => "2.0",
            'xmlns:itunes' => "http://www.itunes.com/dtds/podcast-1.0.dtd",
            'xmlns:anchor' => "https://anchor.fm/xmlns"
        ];

        foreach($rssAttr as $tag => $value) {
            $attr = new \DOMAttr($tag, $value);
            $root->setAttributeNode($attr);
        }

        $dom->appendChild($root);

        $channel = $dom->createElement('channel');

        $root->appendChild($channel);

        $channelTags = [
            'title' => '<![CDATA[' . '' . $programs->title . '' . ']]>',
            'description' => '<![CDATA[' . '' . $programs->description . '' . ']]>',
            'link' => '<![CDATA[' . '' . $programs->title . '' . ']]>'
        ];

        foreach($channelTags as $tag => $value) {
            $channel->appendChild(
                $dom->createElement($tag, $value)
            );
        }

        $image = $dom->createElement('image');

        $root->appendChild($image);

        $imageTags = [
            'url' => '',
            'title' => $programs->title,
            'link' => ''
        ];

        foreach($imageTags as $tag => $value) {
            $image->appendChild(
                $dom->createElement($tag, $value)
            );
        }
        
        $tags = [
            'generator' => 'Narra',
            'lastBuildDate' => Carbon::now()->toRfc7231String(),
        ];

        foreach($tags as $tag => $value) {
            $root->appendChild(
                $dom->createElement($tag, $value)
            );
        }

        $atomLink = $dom->createElement('atom:link');

        $atomLinkAttr = [
            'href' => '',
            'rel' => 'self',
            'type' => 'application/rss+xml'
        ];

        foreach($atomLinkAttr as $tag => $value) {
            $attr = new \DOMAttr($tag, $value);
            $atomLink->setAttributeNode($attr);
        }

        $root->appendChild($atomLink);

        $tags = [
            'author' => '<![CDATA[' . '' . $programs->title . '' . ']]>',
            'copyright' => '<![CDATA[' . '' . $programs->title . '' . ']]>',
            'language' => '<![CDATA[pt-br]]>',
        ];

        foreach($tags as $tag => $value) {
            $root->appendChild(
                $dom->createElement($tag, $value)
            );
        }

        $atomLink = $dom->createElement('atom:link');

        $atomLinkAttr = [
            'href' => '',
            'rel' => 'hub'
        ];

        foreach($atomLinkAttr as $tag => $value) {
            $attr = new \DOMAttr($tag, $value);
            $atomLink->setAttributeNode($attr);
        }

        $root->appendChild($atomLink);

        $tags = [
            'itunes:author' => '<![CDATA[' . '' . $programs->title . '' . ']]>',
            'itunes:summary' => '<![CDATA[' . '' . $programs->description . '' . ']]>',
            'itunes:type' => 'episodic',
        ];

        foreach($tags as $tag => $value) {
            $root->appendChild(
                $dom->createElement($tag, $value)
            );
        }

        $itunesOwner = $dom->createElement('itunes:owner');

        $root->appendChild($itunesOwner);

        $itunesOwnerTags = [
            'itunes:name' => '',
            'itunes:email' => ''
        ];

        foreach($itunesOwnerTags as $tag => $value) {
            $itunesOwner->appendChild(
                $dom->createElement($tag, $value)
            );
        }

        $itunesExplicit = $dom->createElement('itunes:explicit', 'No');

        $root->appendChild($itunesExplicit);

        $categoryParentText = 'Sports';
        $itunesCategoryParent = $dom->createElement('itunes:category');
        $itunesCategoryParentText = new \DOMAttr('text', $categoryParentText);
        $itunesCategoryParent->setAttributeNode($itunesCategoryParentText);

        $root->appendChild($itunesCategoryParent);

        $categories = [
            'itunes:category' => 'Basketball'
        ];
        
        foreach($categories as $tag => $value) {
            $newTag = $dom->createElement($tag);
            $newTag->setAttributeNode(new \DOMAttr('text', $value));
            $itunesCategoryParent->appendChild($newTag);
        }

        $itunesImageHref = '';
        $itunesImage = $dom->createElement('itunes:image');
        $itunesImage->setAttributeNode(new \DOMAttr('href', $itunesImageHref));

        $root->appendChild($itunesImage);

        foreach($programs->episodes as $episode) {
            $item = $dom->createElement('item');
            $item->appendChild(
                $dom->createElement('title', $episode->title)
            );

            $item->appendChild(
                $dom->createElement('description', $episode->description)
            );

            $item->appendChild(
                $dom->createElement('link', '')
            );

            $guid = $dom->createElement('guid', '');
            $guid->setAttributeNode(new \DOMAttr('isPermaLink', false));
            $item->appendChild($guid);

            $item->appendChild(
                $dom->createElement('dc:creator', '')
            );

            $item->appendChild(
                $dom->createElement('pubDate', '')
            );

            $enclosure = $dom->createElement('enclosure');
            $enclosure->setAttributeNode(new \DOMAttr('url', ''));
            $item->appendChild($enclosure);

            $item->appendChild(
                $dom->createElement('itunes:explicit', '')
            );

            $item->appendChild(
                $dom->createElement('itunes:summary', '')
            );

            $item->appendChild(
                $dom->createElement('itunes:duration', '')
            );

            $itunesImage = $dom->createElement('itunes:image');
            $itunesImage->setAttributeNode(new \DOMAttr('href', ''));
            $item->appendChild($itunesImage);


            $item->appendChild(
                $dom->createElement('itunes:episodeType', '')
            );

            $root->appendChild($item);
        }

        return $dom;
    }
}
