<?php

namespace Syonix\ChangelogViewer;

use Doctrine\Common\Collections\ArrayCollection;

class Version {
    private $version;
    private $releaseDate;
    private $url;
    private $changes;

    public function __construct()
    {
        $this->changes = array(
            'new' => new ArrayCollection(),
            'improved' => new ArrayCollection(),
            'fixed' => new ArrayCollection()
        );
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    public function setReleaseDate($releaseDate)
    {
        $this->releaseDate = $releaseDate;
        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function getChanges()
    {
        return $this->changes;
    }

    public function addChange($type, $change) {
        if(!array_key_exists($type, $this->changes)) {
            throw new \InvalidArgumentException('The changelog file contains an unexpected entry type ("'.$type.'"). '
                .'Did you forget to specify the correct locale for your file?');
        }
        $this->changes[$type]->add($change);
    }
}
