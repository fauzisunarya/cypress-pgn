<?php

class Component {

    private $button;

    /**
     * Get the value of button
     */ 
    public function getButton()
    {
        return $this->button;
    }

    /**
     * Set the value of button
     *
     * @return  self
     */ 
    public function setButton(array $button_properties)
    {
        $this->button = array(
            "type" => "button",
            "label" => "Button",
            "no_url" => empty($button_properties['url']) || $button_properties['url'] == '#' ? true : false,
            "value" => array_merge(
                $button_properties,
                array(
                    'fb_event'          => null,
                    'fb_value'          => null,
                    'fb_currency'       => null,
                    'fb_content_name'   => null,
                    'fb_content_ids'    => null,
                    'fb_campaign_url'   => null
                )
            )
        );
    }
}