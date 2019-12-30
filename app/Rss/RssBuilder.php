<?php

namespace App\Rss;

use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str;

class RssBuilder
{
    static function build($program)
    {
        $baseUrl = url('/') . '/';

        // TO DO: remove this and use url('/') when necessary
        // next 3 lines
        // removes / at the end of $baseUrl
        // so can create proper img path 
        // by concatanating with images->path
        $baseUrlExploded = collect(explode('/', $baseUrl));
        $baseUrlExploded->pop();
        $baseUrlExploded = $baseUrlExploded->implode('/');

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
            'title' => $program->title,
            'description' => $program->description,
            'link' => url('/') . '/' . $program->slug
        ];

        foreach($channelTags as $tag => $value) {
            $item = $dom->createElement($tag);
            $item->appendChild(
                $dom->createCDATASection($value)
            );

            $channel->appendChild($item);
        }

        $image = $dom->createElement('image');

        $root->appendChild($image);

        $imageTags = [
            'url' => $baseUrlExploded . $program->images->path,
            'title' => $program->title,
            'link' => $baseUrl . $program->slug
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
            'href' => url('/') . '/' . $program->folder . '/rss',
            'rel' => 'self',
            'type' => 'application/rss+xml'
        ];

        foreach($atomLinkAttr as $tag => $value) {
            $attr = new \DOMAttr($tag, $value);
            $atomLink->setAttributeNode($attr);
        }

        $root->appendChild($atomLink);

        $tags = [
            'author' => $program->title,
            'copyright' => $program->title,
            'language' => $program->settings->language->iso_639_2,
        ];

        foreach($tags as $tag => $value) {
            $item = $dom->createElement($tag);
            $item->appendChild(
                $dom->createCDATASection($value)
            );

            $root->appendChild($item);
        }

        $atomLink = $dom->createElement('atom:link');

        $atomLinkAttr = [
            'href' => 'https://pubsubhubbub.appspot.com/',
            'rel' => 'hub'
        ];

        foreach($atomLinkAttr as $tag => $value) {
            $attr = new \DOMAttr($tag, $value);
            $atomLink->setAttributeNode($attr);
        }

        $root->appendChild($atomLink);

        $tags = [
            'itunes:author' => $program->title,
            'itunes:summary' => $program->description,
            'itunes:type' => 'episodic',
        ];

        foreach($tags as $tag => $value) {
            $item = $dom->createElement($tag);
            $item->appendChild(
                $dom->createCDATASection($value)
            );

            $root->appendChild($item);
        }

        $itunesOwner = $dom->createElement('itunes:owner');

        $root->appendChild($itunesOwner);

        $itunesOwnerTags = [
            'itunes:name' => $program->title,
            'itunes:email' => Auth::user()->email
        ];

        foreach($itunesOwnerTags as $tag => $value) {
            $itunesOwner->appendChild(
                $dom->createElement($tag, $value)
            );
        }

        $itunesExplicit = $dom->createElement('itunes:explicit', $program->settings->explicit_string);

        $root->appendChild($itunesExplicit);
        
        $categoryParentText = '';
        $categories = [];
        foreach($program->categories->toArray() as $category) {
          if (is_null($category['parent_id'])) {
            $categoryParentText = $category['name'];
          } else {
            $categories[] = $category['name'];
          } 
        }

        $categoryParentText = 'Sports';
        $itunesCategoryParent = $dom->createElement('itunes:category');
        $itunesCategoryParentText = new \DOMAttr('text', $categoryParentText);
        $itunesCategoryParent->setAttributeNode($itunesCategoryParentText);

        $root->appendChild($itunesCategoryParent);

        foreach($categories as $value) {
            $newTag = $dom->createElement('itunes:category');
            $newTag->setAttributeNode(new \DOMAttr('text', $value));
            $itunesCategoryParent->appendChild($newTag);
        }

        $itunesImageHref = $baseUrlExploded . $program->images->path;
        $itunesImage = $dom->createElement('itunes:image');
        $itunesImage->setAttributeNode(new \DOMAttr('href', $itunesImageHref));

        $root->appendChild($itunesImage);

        foreach($program->episodes as $episode) {
            $item = $dom->createElement('item');
            $item->appendChild(
                $dom->createElement('title', $episode->title)
            );

            $item->appendChild(
                $dom->createElement('description', $episode->description)
            );

            $item->appendChild(
                $dom->createElement('link', url('/') . '/' . $program->slug . '/episode/' . $episode->slug)
            );

            $guid = $dom->createElement('guid', Str::uuid());
            $guid->setAttributeNode(new \DOMAttr('isPermaLink', false));
            $item->appendChild($guid);

            $item->appendChild(
                $dom->createElement('dc:creator', $program->title)
            );

            $item->appendChild(
                $dom->createElement('pubDate', (new Carbon($episode->updated_at))->toRfc7231String())
            );

            $enclosure = $dom->createElement('enclosure');

            $enclosure->setAttributeNode(new \DOMAttr('url', $baseUrlExploded . $episode->audios->path));
            $enclosure->setAttributeNode(new \DOMAttr('type', $episode->type));
            $enclosure->setAttributeNode(new \DOMAttr('length', $episode->size));

            $item->appendChild($enclosure);

            $item->appendChild(
                $dom->createElement('itunes:explicit', $program->settings->explicit_string)
            );

            $item->appendChild(
                $dom->createElement('itunes:summary', $episode->description)
            );

            $item->appendChild(
                $dom->createElement('itunes:duration', $episode->duration)
            );

            $itunesImage = $dom->createElement('itunes:image');
            $itunesImage->setAttributeNode(new \DOMAttr('href', $baseUrlExploded . $episode->images->path));
            $item->appendChild($itunesImage);


            $item->appendChild(
                $dom->createElement('itunes:episodeType', 'full')
            );

            $root->appendChild($item);
        }

        return $dom;
    }
}
