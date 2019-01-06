<?php

namespace Bilyiv\RequestDataBundle;

/**
 * @author Vladyslav Bilyi <beliyvladislav@gmail.com>
 */
trait OriginalDataTrait
{
    private $originalData;

    public function getOriginalData(): ?array
    {
        return $this->originalData;
    }

    public function setOriginalData(?array $data): self
    {
        $this->originalData = $data;

        return $this;
    }
}