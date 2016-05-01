<?php

namespace Zoolanders\Service;

class Link extends Service
{
    /**
     * Get link to this component's related resources.
     *
     * @param array $query The query parameters
     * @param boolean $xhtml If the link should be valid xhtml
     * @param boolean $ssl If the link should be forced to be ssl
     *
     * @return string The url requested
     *
     * @since 1.0.0
     */
    public function link($query = array(), $xhtml = false, $ssl = null)
    {
        // prepend option to query
        $query = array_merge(array('option' => 'com_zoolanders'), $query);
        return $this->route('index.php?' . http_build_query($query, '', '&'), $xhtml, $ssl);
    }

    /**
     * @see \JRoute::_
     * @param $link
     * @param $xhtml
     * @param $ssl
     */
    public function route($link, $xhtml = false, $ssl = null)
    {
        return \JRoute::_($link, $xhtml, $ssl);
    }
}