<?php

namespace ECidade\Lib\Request\Storage;

class File
{
    private $realPath;
    private $clientOriginalName;
    private $visibility;
    private $allowed;
    private $url;
    private $signers;
    private $signers_signed;
    private $file_father;
    private $signers_required;
    private $metadata;

    public function realPath($realPath = null)
    {
        if (!empty($realPath)) {
            $this->realPath = $realPath;
            return $this;
        }

        return $this->realPath;
    }

    public function clientOriginalName($clientOriginalName = null)
    {
        if (!empty($clientOriginalName)) {
            $this->clientOriginalName = $clientOriginalName;
            return $this;
        }

        return $this->clientOriginalName;
    }

    public function visibility($visibility = null)
    {
        if (!empty($visibility)) {
            $this->visibility = $visibility;
            return $this;
        }

        return $this->visibility;
    }

    public function allowed(Array $allowed = null)
    {
        if (!empty($allowed)) {
            $this->allowed = $allowed;
            return $this;
        }

        return $this->allowed;
    }

    public function url($url = null)
    {
        if (!empty($url)) {
            $this->url = $url;
            return $this;
        }

        return $this->url;
    }

    public function signers($signers = null)
    {
        if (!empty($signers)) {
            $this->signers = $signers;
            return $this;
        }
        
        return $this->signers;
    }

    public function signersSigned($signers_signed = null)
    {
        if (!empty($signers_signed)) {
            $this->signers_signed = $signers_signed;
            return $this;
        }
        
        return $this->signers_signed;
    }

    public function fileFather($file_father = null)
    {
        if (!empty($file_father)) {
            $this->file_father = $file_father;
            return $this;
        }

        return $this->file_father;
    }

    public function signersRequired($signers_required = null)
    {
        if (!empty($signers_required)) {
            $this->signers_required = $signers_required;
            return $this;
        }
        
        return $this->signers_required;
    }

    public function metadata($metadata = null)
    {
        if (!empty($metadata)) {
            $this->metadata = $metadata;
            return $this;
        }

        return $this->metadata;
    }
}
